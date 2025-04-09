<?php

namespace Modules\EcomAuth\Http\Controllers;

use App\BusinessLocation;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\EcomAuth\Cart;
use Illuminate\Support\Facades\Log;
use App\TransactionSellLine;
use App\Transaction;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use App\Utils\ProductUtil;
use Illuminate\Support\Facades\DB;
use App\Product;
use Illuminate\Support\Facades\Http;
use App\Contact;

class CartController extends Controller
{

    public function getPincode(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');
        $apiKey = env('GOOGLE_MAPS_API_KEY'); // Store API key in .env
    
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "$lat,$lng",
            'key' => $apiKey
        ]);
    
        $data = $response->json();
        $pincode = 'Not Found';
    
        if (!empty($data['results'])) {
            foreach ($data['results'] as $result) {
                foreach ($result['address_components'] as $component) {
                    if (in_array('postal_code', $component['types'])) {
                        $pincode = $component['long_name'];
                        break 2;
                    }
                }
            }
        }
    
        // Store the pincode in session
        $request->session()->put('user_pincode', $pincode);
        
        return response()->json([
            'pincode' => $pincode,
            'message' => 'Pincode stored in session successfully'
        ]);
    }

    public function index()
    {
        try {
            $user = Auth::user();
            
            $shippingAddress = Contact::where('created_by', $user->id)->value('shipping_address');
            $pincode = $this->extractPincode($shippingAddress);
            if (!$pincode) {
                $businessLocation = null;
            } else {
                // Check location based on pincode
                $location = DB::table('ecom_delivery_locations')
                    ->where('pincode', $pincode)
                    ->select('business_location_id')
                    ->first();
                
                if (!$location) {
                    $businessLocation = null;
                } else {
                    // Get the selling_price_group_id from business_locations
                    $businessLocation = DB::table('business_locations')
                        ->where('id', $location->business_location_id)
                        ->select('selling_price_group_id')
                        ->first();
                }
            }

            return view('EcomLayouts.cart',compact('user', 'location'));
        } catch (\Exception $e) {
            Log::error('Failed to load cart view', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Unable to load cart. Please try again later.');
        }
    }

    public function getLatestCart()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                Log::warning('Attempted to get cart without authentication');
                return response()->json([
                    'error' => 'User not authenticated',
                    'cart' => []
                ], 401);
            }
            
            $user_id = $user->id;
            
            // Fetch user's shipping address and pincode
            $shippingAddress = Contact::where('created_by', $user->id)->value('shipping_address');
            $pincode = $this->extractPincode($shippingAddress);
            
            if (!$pincode) {
                $businessLocation = null;
            } else {
                // Check location based on pincode
                $location = DB::table('ecom_delivery_locations')
                    ->where('pincode', $pincode)
                    ->select('business_location_id')
                    ->first();
                
                if (!$location) {
                    $businessLocation = null;
                } else {
                    // Get the selling_price_group_id from business_locations
                    $businessLocation = DB::table('business_locations')
                        ->where('id', $location->business_location_id)
                        ->select('selling_price_group_id')
                        ->first();
                }
            }
    
            $cartItems = Cart::where('user_id', $user_id)
                ->with(['product.variations'])
                ->get();
            
            // Log successful cart retrieval
            Log::info('Cart retrieved successfully', [
                'user_id' => $user_id,
                'items_count' => $cartItems->count()
            ]);
            
            return response()->json([
                'cart' => $cartItems->map(function ($item) use ($businessLocation) {
                    // Check if product and variations exist to prevent errors
                    if (!$item->product || !$item->product->variations || $item->product->variations->isEmpty()) {
                        Log::warning('Cart item references missing product or variation', [
                            'cart_id' => $item->id,
                            'product_id' => $item->product_id
                        ]);
                        
                        return [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'image' => asset('uploads/img/' . $item->product->image),
                            'quantity' => $item->quantity,
                            'weight' => $item->product ? $item->product->weight : 0,
                            'product_name' => $item->product ? $item->product->name : 'Product unavailable',
                            'price' => 0,
                            'error' => 'Product or variation missing'
                        ];
                    }
                    
                    $variation = $item->product->variations->first();
                    $price = $variation->sell_price_inc_tax;
                    
                    // Adjust price based on selling price group
                    if ($businessLocation && $businessLocation->selling_price_group_id) {
                        $groupPrice = DB::table('variation_group_prices')
                            ->where('variation_id', $variation->id)
                            ->where('price_group_id', $businessLocation->selling_price_group_id)
                            ->select('price_inc_tax', 'price_type')
                            ->first();
                        
                        if ($groupPrice) {
                            if ($groupPrice->price_type === 'percentage') {
                                $price = $variation->sell_price_inc_tax * (1 + ($groupPrice->price_inc_tax / 100));
                            } else {
                                $price = $groupPrice->price_inc_tax;
                            }
                        }
                    }
                    
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'image' => asset($item->product->image ? 'uploads/img/' . $item->product->image : 'img/default.png'),
                        'quantity' => $item->quantity,
                        'weight' => $item->product->weight,
                        'product_name' => $item->product->name,
                        'price' => number_format($price, 2), // Two decimal places
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve latest cart', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to retrieve cart',
                'cart' => []
            ], 500);
        }
    }
    

    
    
    public function updateCart(Request $request) {
        try {
            $cartUpdates = $request->input('cart');
            
            if (!Auth::check()) {
                Log::warning('Unauthenticated cart update attempt');
                return response()->json([
                    'message' => 'Authentication required',
                    'redirect' => route('login') // Provide login URL
                ], 401);
            }
            
            $userId = auth()->id();
            
            // Validate cart data
            if (!is_array($cartUpdates)) {
                Log::warning('Invalid cart update format', ['user_id' => $userId]);
                return response()->json(['message' => 'Invalid cart format'], 400);
            }
            
            Log::info('Processing cart update', [
                'user_id' => $userId,
                'items_count' => count($cartUpdates)
            ]);
    
            foreach ($cartUpdates as $cartItem) {
                // Validate required fields
                if (!isset($cartItem['id']) || !isset($cartItem['quantity'])) {
                    Log::warning('Invalid cart item format', [
                        'user_id' => $userId,
                        'item' => $cartItem
                    ]);
                    continue;
                }
                
                try {
                    // Check if cart item exists for the same user and product
                    $existingCartItem = Cart::where('product_id', $cartItem['id'])
                        ->where('user_id', $userId)
                        ->first();
        
                    if ($existingCartItem) {
                        $newQuantity = $existingCartItem->quantity + $cartItem['quantity'];
        
                        if ($newQuantity > 0) {
                            // Update the quantity if it's greater than zero
                            $existingCartItem->update(['quantity' => $newQuantity]);
                            Log::info('Cart item updated', [
                                'user_id' => $userId,
                                'product_id' => $cartItem['id'],
                                'new_quantity' => $newQuantity
                            ]);
                        } else {
                            // Remove the cart item if quantity becomes zero or negative
                            $existingCartItem->delete();
                            Log::info('Cart item removed', [
                                'user_id' => $userId,
                                'product_id' => $cartItem['id']
                            ]);
                        }
                    } else {
                        // Create new cart item only if quantity is greater than zero
                        if ($cartItem['quantity'] > 0) {
                            Cart::create([
                                'product_id' => $cartItem['id'],
                                'user_id' => $userId,
                                'quantity' => $cartItem['quantity']
                            ]);
                            Log::info('New cart item added', [
                                'user_id' => $userId,
                                'product_id' => $cartItem['id'],
                                'quantity' => $cartItem['quantity']
                            ]);
                        }
                    }
                } catch (\Exception $itemException) {
                    Log::error('Error processing individual cart item', [
                        'user_id' => $userId,
                        'product_id' => $cartItem['id'] ?? 'unknown',
                        'error' => $itemException->getMessage()
                    ]);
                    // Continue processing other items even if one fails
                }
            }
    
            return response()->json(['message' => 'Cart updated successfully']);
        } catch (\Exception $e) {
            Log::error('Error updating cart', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Error updating cart', 'error' => $e->getMessage()], 500);
        }
    }


    // public function updateCart(Request $request) {
    //     try {
    //         $cartUpdates = $request->input('cart');
    
    //         if (!Auth::check()) {
    //             Log::warning('Unauthenticated cart update attempt');
    //             return response()->json(['message' => 'Authentication required'], 401);
    //         }
    
    //         $userId = auth()->id();
    
    //         // Validate cart data
    //         if (!is_array($cartUpdates)) {
    //             Log::warning('Invalid cart update format', ['user_id' => $userId]);
    //             return response()->json(['message' => 'Invalid cart format'], 400);
    //         }
    
    //         Log::info('Processing cart update', [
    //             'user_id' => $userId,
    //             'items_count' => count($cartUpdates)
    //         ]);
    
    //         foreach ($cartUpdates as $cartItem) {
    //             // Validate required fields
    //             if (!isset($cartItem['id']) || !isset($cartItem['quantity'])) {
    //                 Log::warning('Invalid cart item format', [
    //                     'user_id' => $userId,
    //                     'item' => $cartItem
    //                 ]);
    //                 continue;
    //             }
    
    //             try {
    //                 // Check if cart item exists for the same user and product
    //                 $existingCartItem = Cart::where('product_id', $cartItem['id'])
    //                     ->where('user_id', $userId)
    //                     ->first();
    
    //                 if ($existingCartItem) {
    //                     if ($cartItem['quantity'] > 0) {
    //                         // Update the cart item with the new quantity from API
    //                         $existingCartItem->update(['quantity' => $cartItem['quantity']]);
    //                         Log::info('Cart item updated', [
    //                             'user_id' => $userId,
    //                             'product_id' => $cartItem['id'],
    //                             'new_quantity' => $cartItem['quantity']
    //                         ]);
    //                     } else {
    //                         // Remove item if quantity is zero or negative
    //                         $existingCartItem->delete();
    //                         Log::info('Cart item removed', [
    //                             'user_id' => $userId,
    //                             'product_id' => $cartItem['id']
    //                         ]);
    //                     }
    //                 } else {
    //                     // Create new cart item only if quantity is greater than zero
    //                     if ($cartItem['quantity'] > 0) {
    //                         Cart::create([
    //                             'product_id' => $cartItem['id'],
    //                             'user_id' => $userId,
    //                             'quantity' => $cartItem['quantity']
    //                         ]);
    //                         Log::info('New cart item added', [
    //                             'user_id' => $userId,
    //                             'product_id' => $cartItem['id'],
    //                             'quantity' => $cartItem['quantity']
    //                         ]);
    //                     }
    //                 }
    //             } catch (\Exception $itemException) {
    //                 Log::error('Error processing individual cart item', [
    //                     'user_id' => $userId,
    //                     'product_id' => $cartItem['id'] ?? 'unknown',
    //                     'error' => $itemException->getMessage()
    //                 ]);
    //                 // Continue processing other items even if one fails
    //             }
    //         }
    
    //         return response()->json(['message' => 'Cart updated successfully']);
    //     } catch (\Exception $e) {
    //         Log::error('Error updating cart', [
    //             'user_id' => Auth::id(),
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         return response()->json(['message' => 'Error updating cart', 'error' => $e->getMessage()], 500);
    //     }
    // }

    public function getBusinessAddress()
    {
        try {
            $address = BusinessLocation::where('enable_ecommerce', 1)->get();
            
            Log::info('Business addresses retrieved', [
                'count' => $address->count(),
                'user_id' => Auth::id()
            ]);
            
            return view('EcomLayouts.address', compact('address'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve business addresses', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Unable to load addresses. Please try again later.');
        }
    }
    
    public function getpaymentdetails(Request $request)
    {

        $selected_location_id = $request->query('selected_address');
      

        try {

            if (!Auth::check()) {
                Log::info('Unauthenticated user redirected to login from payment page');
                return redirect()->route('login')->with('error', 'Please log in to continue.');
            }
            
            $user = Auth::user();
            $user_id = $user->id;
    
            // Fetch user's shipping address and extract pincode
            $shippingAddress = Contact::where('created_by', $user->id)->value('shipping_address');
            $pincode = $this->extractPincode($shippingAddress);
            Log::info("Extracted Pincode: " . $pincode);
            

           
           
           
            if (!$pincode) {
               
                // If no pincode is found, return all products
                $cartItems = Cart::where('user_id', $user_id)
                ->with(['product.variations'])
                ->get();
            } else {
                // Check if location exists for the given pincode in ecom_delivery_locations
                $location = DB::table('ecom_delivery_locations')
                    ->where('pincode', $pincode)
                    ->select('business_location_id')
                    ->first();

                $location = $location==null? $selected_location_id:$location->business_location_id;

                
    
                if (!$location) {

                   
                    
                    // If no location is found, `return all products
                    $cartItems = Cart::where('user_id', $user_id)
                    ->with(['product.variations'])
                    ->get();
                } else {
                    // Get the selling_price_group_id from business_locations
                    $businessLocation = DB::table('business_locations')
                        ->where('id', $location)
                        ->select('selling_price_group_id')
                        ->first();
            
                        
            
                       

                    if (!$businessLocation || !$businessLocation->selling_price_group_id) {
                       
                        
                        // If no pricing group found, return all products
                        $cartItems = Cart::where('user_id', $user_id)
                            ->with(['product.variations'])
                            ->get();
                    } else {
                        // Fetch cart items with the respective price group and include quantity from carts table
                        $cartItems = Product::with('variations')
                            ->join('variations', 'products.id', '=', 'variations.product_id')
                            ->join('carts', function($join) use ($user_id) {
                                $join->on('products.id', '=', 'carts.product_id')
                                    ->where('carts.user_id', $user_id);
                            })
                            ->leftJoin('variation_group_prices', function ($join) use ($businessLocation) {
                                $join->on('variations.id', '=', 'variation_group_prices.variation_id')
                                    ->where('variation_group_prices.price_group_id', $businessLocation->selling_price_group_id);
                            })
                            ->select(
                                'products.id',
                                'products.name',
                                'products.mrp',
                                'products.weight',
                                'products.image',
                                'variations.id as variation_id',
                                'variations.sell_price_inc_tax as regular_price',
                                'variation_group_prices.price_inc_tax',
                                'variation_group_prices.price_type',
                                'carts.quantity'
                            )
                            ->get();


    
                        // Process the products to calculate final prices with quantity
                        foreach ($cartItems as $product) {
                            if ($product->price_type === 'percentage' && $product->price_inc_tax !== null) {
                                $product->unit_price = $product->regular_price * (1 + ($product->price_inc_tax / 100));
                            } elseif ($product->price_inc_tax !== null) {
                                $product->unit_price = $product->price_inc_tax;
                            } else {
                                $product->unit_price = $product->regular_price;
                            }
                            
                            // Calculate line total by multiplying unit price with quantity
                            $product->final_price = $product->unit_price * $product->quantity;
                        }
                    }
                }
            }
            
        
            

            $Check_location = DB::table('ecom_delivery_locations')
                    ->where('pincode', $pincode)
                    ->select('business_location_id')
                    ->first();


            $totalAmount=0;

            
            if($location){
               
                $totalAmount = $cartItems->sum('final_price');

                if($totalAmount==0){
                    $totalAmount = $cartItems->sum(function ($item) {
                
                        if (!$item || !$item->product->variations || $item->product->variations->isEmpty()) {
                           
                            Log::warning('Invalid product in cart during payment calculation', [
                                'cart_id' => $item->id,
                                'product_id' => $item->product_id,
                                'user_id' => $item->user_id
                            ]);
                            return 0;
                        }
                        
                        
                        return $item->quantity * $item->product->variations->first()->sell_price_inc_tax;
                    });
                }
                
            }
            else{
                if($Check_location){
                    $totalAmount = $cartItems->sum('final_price');
                }
                else{
                    $totalAmount = $cartItems->sum(function ($item) {
                
                        if (!$item || !$item->variations || $item->variations->isEmpty()) {
                           
                            Log::warning('Invalid product in cart during payment calculation', [
                                'cart_id' => $item->id,
                                'product_id' => $item->product_id,
                                'user_id' => $item->user_id
                            ]);
                            return 0;
                        }
                        
                        
                        return $item->quantity * $item->variations->first()->sell_price_inc_tax;
                    });
                }
                
            }

                    


            // Calculate total price
            // $totalAmount = $cartItems->sum('final_price');

            // $totalAmount = $cartItems->sum(function ($item) {
            //     if (!$item->product || !$item->product->variations || $item->product->variations->isEmpty()) {
            //         Log::warning('Invalid product in cart during payment calculation', [
            //             'cart_id' => $item->id,
            //             'product_id' => $item->product_id,
            //             'user_id' => $item->user_id
            //         ]);
            //         return 0;
            //     }
            //     return $item->quantity * $item->product->variations->first()->sell_price_inc_tax;
            // });

    
            Log::info('Payment details retrieved successfully', [
                'user_id' => $user_id,
                'cart_items' => count($cartItems),
                'total_amount' => $totalAmount
            ]);
    
            return view('EcomLayouts.payment', compact('cartItems', 'totalAmount'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve payment details', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return redirect()->back()->with('error', 'Unable to process payment. Please try again later.');
        }
    }
    

    public function placeOrder(Request $request)
{
    $Util = new Util();
    try {
        DB::beginTransaction();

        $user = Auth::user();

        if (!$user) {
            Log::warning('Unauthenticated order placement attempt');
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 'AUTH_REQUIRED'
            ], 401);
        }
        
        $user_id = $user->id;
        
        Log::info('Order placement initiated', [
            'user_id' => $user_id,
            'location_id' => $request->location_id
        ]);

        // Fetch contact securely
        $contact = DB::table('contacts')
            ->where('created_by', $user_id)
            ->first();

        if (!$contact) {
            Log::error('Contact not found for user during order placement', ['user_id' => $user_id]);
            return response()->json([
                'error' => 'User contact not found.',
                'code' => 'CONTACT_MISSING'
            ], 404);
        }

        // Get shipping address and extract pincode
        $shippingAddress = Contact::where('created_by', $user->id)->value('shipping_address');
        $pincode = $this->extractPincode($shippingAddress);
        Log::info("Extracted Pincode for order: " . $pincode);

        // Get location based on pincode
        $location = DB::table('ecom_delivery_locations')
            ->where('pincode', $pincode)
            ->select('business_location_id')
            ->first();

        // Get pricing group
        $businessLocation = null;
        $pricingGroupId = null;
        
        $location = $location==null? $request->location_id:$location->business_location_id;
        
        if ($location) {
            $businessLocation = DB::table('business_locations')
                ->where('id', $location)
                ->select('selling_price_group_id')
                ->first();
                
            if ($businessLocation && $businessLocation->selling_price_group_id) {
                $pricingGroupId = $businessLocation->selling_price_group_id;
                Log::info("Using pricing group ID: " . $pricingGroupId);
            }
        }

        // Retrieve cart items
        $cartItems = Cart::where('user_id', $user_id)->get();

        if ($cartItems->isEmpty()) {
            Log::warning('Attempted to place order with empty cart', ['user_id' => $user_id]);
            return response()->json([
                'error' => 'Cart is empty.',
                'code' => 'EMPTY_CART'
            ], 400);
        }

        // Advanced total calculation with tax
        $totalAmount = 0;
        $totalTax = 0;
        $products = [];
        $invalidProducts = [];

        // Process each cart item with the correct pricing
        foreach ($cartItems as $item) {
            try {
                // Get product with variation and pricing info
                $productInfo = DB::table('products')
                    ->join('variations', 'products.id', '=', 'variations.product_id')
                    ->leftJoin('variation_group_prices', function ($join) use ($pricingGroupId) {
                        $join->on('variations.id', '=', 'variation_group_prices.variation_id')
                             ->where('variation_group_prices.price_group_id', $pricingGroupId);
                    })
                    ->where('products.id', $item->product_id)
                    ->select(
                        'products.id',
                        'products.name',
                        'products.tax',
                        'variations.id as variation_id',
                        'variations.default_sell_price',
                        'variations.sell_price_inc_tax as regular_price',
                        'variation_group_prices.price_inc_tax',
                        'variation_group_prices.price_type'
                    )
                    ->first();

                if (!$productInfo) {
                    $invalidProducts[] = $item->product_id;
                    Log::warning('Invalid product found in cart during order placement', [
                        'cart_id' => $item->id,
                        'product_id' => $item->product_id,
                        'user_id' => $user_id
                    ]);
                    continue;
                }

                // Calculate final unit price based on pricing group
                $unitPrice = $productInfo->default_sell_price;
                $selling_price = $productInfo->regular_price;

                // Fetch tax information directly from tax_rates table
                $productTax = DB::table('tax_rates')
                    ->where('id', $productInfo->tax)
                    ->first();

                // Tax calculation
                $taxRate = $productTax ? $productTax->amount : 0;
                
                // Apply pricing group price if available
                if ($pricingGroupId && $productInfo->price_inc_tax !== null) {
                    // Use pricing group price (which already includes tax)
                    $selling_price = $productInfo->price_inc_tax;
                    
                    // Calculate unit price without tax for internal calculations
                    $taxMultiplier = 1 + ($taxRate / 100);
                    $unitPrice = $selling_price / $taxMultiplier;
                }
                
                $itemTax = $Util->num_f($unitPrice * ($taxRate / 100));
                $lineTotal = $selling_price * $item->quantity;
                $lineTotalWithTax = $lineTotal;
                
                $totalAmount += $lineTotalWithTax;
                $totalTax += $itemTax * $item->quantity;
                
                $products[] = [
                    'transaction_id' => null, // Will be set later
                    'product_id' => $item->product_id,
                    'variation_id' => $productInfo->variation_id,
                    'quantity' => $item->quantity,
                    'unit_price_before_discount' => $Util->num_f($unitPrice),
                    'unit_price' => $Util->num_f($unitPrice),
                    'unit_price_inc_tax' => $Util->num_f($unitPrice+$itemTax),
                    'line_discount_type' => 'fixed',
                    'line_discount_amount' => 0.00,
                    'item_tax' => $itemTax,
                    'tax_id' => $productTax ? $productTax->id : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                Log::info('Processed cart item with pricing', [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotalWithTax
                ]);
                
            } catch (\Exception $itemException) {
                $invalidProducts[] = $item->product_id;
                Log::error('Error processing product during order placement', [
                    'cart_id' => $item->id,
                    'product_id' => $item->product_id,
                    'error' => $itemException->getMessage()
                ]);
            }
        }

        if (empty($products)) {
            Log::error('No valid products found for order placement', [
                'user_id' => $user_id,
                'invalid_products' => $invalidProducts
            ]);
            
            DB::rollBack();
            return response()->json([
                'error' => 'No valid products found in cart',
                'code' => 'INVALID_PRODUCTS'
            ], 400);
        }

        // Use utility for reference numbers
        $ref_count = $Util->setAndGetReferenceCount('sales_order', $user->business_id);
        $invoice_no = $Util->generateReferenceNumber('sales_order', $ref_count, $user->business_id);
        
        Log::info('Generated order reference', [
            'user_id' => $user_id,
            'invoice_no' => $invoice_no
        ]);

        // Insert transaction
        $transactionId = DB::table('transactions')->insertGetId([
            'business_id' => $user->business_id,
            'location_id' => $location,
            'contact_id' => $contact->id,
            'type' => 'sales_order',
            'status' => 'ordered',
            'transaction_date' => now(),
            'final_total' => $totalAmount,
            'total_before_tax' => $totalAmount - $totalTax,
            'tax_amount' => $totalTax,
            'created_by' => $user_id,
            'is_direct_sale' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'invoice_no' => $invoice_no,
            'selling_price_group_id' => $pricingGroupId,
        ]);
        
        Log::info('Transaction created', [
            'user_id' => $user_id,
            'transaction_id' => $transactionId,
            'amount' => $totalAmount
        ]);

        // Update transaction ID in products
        $products = array_map(function($product) use ($transactionId) {
            $product['transaction_id'] = $transactionId;
            return $product;
        }, $products);

        // Insert transaction sell lines
        DB::table('transaction_sell_lines')->insert($products);
        
        Log::info('Transaction line items inserted', [
            'transaction_id' => $transactionId,
            'products_count' => count($products)
        ]);

        // Clear the cart
        $deletedCount = Cart::where('user_id', $user_id)->delete();
        Log::info('Cart cleared after order placement', [
            'user_id' => $user_id,
            'items_removed' => $deletedCount
        ]);

        DB::commit();
        
        Log::info('Order placed successfully', [
            'user_id' => $user_id,
            'transaction_id' => $transactionId,
            'total_amount' => $totalAmount
        ]);

        return redirect()->route('orderSuccess', ['order_id' => $transactionId]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Order placement failed', [
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'error' => 'Order placement failed',
            'details' => config('app.debug') ? $e->getMessage() : null,
            'code' => 'ORDER_PLACEMENT_ERROR'
        ], 500);
    }
}
    
    public function orderSuccess(Request $request)
    {
        try {
            $order_id = $request->order_id;
            
            if (!$order_id) {
                Log::warning('Order success page accessed without order ID', [
                    'user_id' => Auth::id()
                ]);
                return redirect()->route('cart.index')->with('error', 'Invalid order reference.');
            }

            // Fetch order details from the database
            $orderDetails = DB::table('transactions')
                ->where('id', $order_id)
                ->select('id as order_id', 'final_total as total_amount', 'transaction_date', 'invoice_no')
                ->first();

            if (!$orderDetails) {
                Log::error('Order not found for success page', [
                    'order_id' => $order_id,
                    'user_id' => Auth::id()
                ]);
                
                return response()->json([
                    'error' => 'Order not found.',
                    'code' => 'ORDER_NOT_FOUND'
                ], 404);
            }
            
            Log::info('Order success page accessed', [
                'order_id' => $order_id,
                'user_id' => Auth::id()
            ]);

            return view('EcomLayouts.order-success', compact('orderDetails'));
        } catch (\Exception $e) {
            Log::error('Error displaying order success page', [
                'order_id' => $request->order_id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('cart.index')->with('error', 'An error occurred while processing your order. Please contact support.');
        }
    }

    public function orderHistory()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                Log::warning('Unauthenticated order history access attempt');
                return redirect()->route('login')->with('error', 'Please log in to view your order history.');
            }
            
            $user_id = $user->id;

            // Fetch contact securely
            $contact = DB::table('contacts')
                ->where('created_by', $user_id)
                ->first();

            if (!$contact) {
                Log::error('Contact not found for user during order history access', ['user_id' => $user_id]);
                return redirect()->back()->with('error', 'User contact not found.');
            }

            // Fetch pending transactions (Ordered)
            $pendingTransactions = DB::table('transactions')
                ->where('contact_id', $contact->id)
                ->where('status', 'ordered')
                ->where(function($query) {
                    $query->where('shipping_status', '<>', 'cancelled')
                        ->orWhereNull('shipping_status');
                })
                ->orderBy('transaction_date', 'desc')
                ->get()
                ->map(function ($transaction) {
                    try {
                        $transaction->item_count = DB::table('transaction_sell_lines')
                            ->where('transaction_id', $transaction->id)
                            ->count();
                    } catch (\Exception $e) {
                        Log::error('Error counting items for transaction', [
                            'transaction_id' => $transaction->id,
                            'error' => $e->getMessage()
                        ]);
                        $transaction->item_count = 0;
                    }
                    return $transaction;
                });
                
            // Fetch cancelled transactions (Shipping status = Cancelled)
            $cancelledTransactions = DB::table('transactions')
                ->where('contact_id', $contact->id)
                ->where('shipping_status', 'cancelled')
                ->orderBy('transaction_date', 'desc')
                ->get()
                ->map(function ($transaction) {
                    try {
                        $transaction->item_count = DB::table('transaction_sell_lines')
                            ->where('transaction_id', $transaction->id)
                            ->count();
                    } catch (\Exception $e) {
                        Log::error('Error counting items for cancelled transaction', [
                            'transaction_id' => $transaction->id,
                            'error' => $e->getMessage()
                        ]);
                        $transaction->item_count = 0;
                    }
                    return $transaction;
                });

            // Fetch completed transactions (Final)
            $completedTransactions = DB::table('transactions')
                ->where('contact_id', $contact->id)
                ->where('status', 'final')
                ->orderBy('transaction_date', 'desc')
                ->get()
                ->map(function ($transaction) {
                    try {
                        $transaction->item_count = DB::table('transaction_sell_lines')
                            ->where('transaction_id', $transaction->id)
                            ->count();
                    } catch (\Exception $e) {
                        Log::error('Error counting items for completed transaction', [
                            'transaction_id' => $transaction->id,
                            'error' => $e->getMessage()
                        ]);
                        $transaction->item_count = 0;
                    }
                    return $transaction;
                });
                
            Log::info('Order history retrieved successfully', [
                'user_id' => $user_id,
                'pending_count' => $pendingTransactions->count(),
                'cancelled_count' => $cancelledTransactions->count(),
                'completed_count' => $completedTransactions->count()
            ]);

            return view('EcomLayouts.order-history', compact('pendingTransactions', 'cancelledTransactions', 'completedTransactions'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve order history', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Unable to load order history. Please try again later.');
        }
    }

    public function orderDetails($transaction_id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                Log::warning('Unauthenticated order details access attempt', [
                    'transaction_id' => $transaction_id
                ]);
                return redirect()->route('login')->with('error', 'Please log in to view order details.');
            }
            
            $user_id = $user->id;
            
            Log::info('Order details access initiated', [
                'user_id' => $user_id,
                'transaction_id' => $transaction_id
            ]);

            // Fetch contact securely
            $contact = DB::table('contacts')
                ->where('created_by', $user_id)
                ->first();

            if (!$contact) {
                Log::error('Contact not found for user during order details access', [
                    'user_id' => $user_id,
                    'transaction_id' => $transaction_id
                ]);
                return redirect()->back()->with('error', 'User contact not found.');
            }

            // Fetch the transaction details
            $transaction = DB::table('transactions')
                ->where('id', $transaction_id)
                ->where('contact_id', $contact->id) // Ensure user owns the transaction
                ->first();

            if (!$transaction) {
                Log::warning('Transaction not found or does not belong to user', [
                    'user_id' => $user_id,
                    'transaction_id' => $transaction_id,
                    'contact_id' => $contact->id
                ]);
                return redirect()->back()->with('error', 'Transaction not found.');
            }

            // Fetch delivered products (transaction sell lines)
            try {
                $delivered_products = DB::table('transaction_sell_lines AS tsl')
                    ->join('products AS p', 'tsl.product_id', '=', 'p.id')
                    ->where('tsl.transaction_id', $transaction_id)
                    ->select(
                        'tsl.id',
                        'tsl.transaction_id',
                        'tsl.product_id',
                        'p.name AS product_name',
                        'p.weight AS weight',
                        'tsl.variation_id',
                        'tsl.quantity',
                        'tsl.unit_price',
                        'tsl.unit_price_inc_tax',
                        'tsl.item_tax'
                    )
                    ->get();
                    
                Log::info('Retrieved delivered products', [
                    'transaction_id' => $transaction_id,
                    'products_count' => $delivered_products->count()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to retrieve delivered products', [
                    'transaction_id' => $transaction_id,
                    'error' => $e->getMessage()
                ]);
                $delivered_products = collect();
            }

            // Fetch order products (from sales orders if available)
            $order_products = collect();
            if (!empty($transaction->sales_order_ids)) {
                try {
                    $sales_order_ids = json_decode($transaction->sales_order_ids, true); // Decode stored JSON array

                    if (is_array($sales_order_ids)) {
                        $order_products = DB::table('transaction_sell_lines AS tsl')
                            ->join('products AS p', 'tsl.product_id', '=', 'p.id')
                            ->whereIn('tsl.transaction_id', $sales_order_ids) // Fetch products from related sales orders
                            ->select(
                                'tsl.id',
                                'tsl.transaction_id',
                                'tsl.product_id',
                                'p.name AS product_name',
                                'p.weight AS weight',
                                'tsl.variation_id',
                                'tsl.quantity',
                                'tsl.unit_price',
                                'tsl.unit_price_inc_tax',
                                'tsl.item_tax'
                            )
                            ->get();
                            
                        Log::info('Retrieved order products from sales orders', [
                            'transaction_id' => $transaction_id,
                            'sales_order_ids' => $sales_order_ids,
                            'products_count' => $order_products->count()
                        ]);
                    } else {
                        Log::warning('Invalid sales_order_ids format', [
                            'transaction_id' => $transaction_id,
                            'sales_order_ids' => $transaction->sales_order_ids
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to retrieve order products from sales orders', [
                        'transaction_id' => $transaction_id,
                        'sales_order_ids' => $transaction->sales_order_ids,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Fetch total paid amount for the transaction
            try {
                $paid_amount = DB::table('transaction_payments')
                    ->where('transaction_id', $transaction_id)
                    ->sum('amount'); // Sum all payments related to this transaction
                    
                Log::info('Retrieved transaction payment information', [
                    'transaction_id' => $transaction_id,
                    'paid_amount' => $paid_amount
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to retrieve transaction payment information', [
                    'transaction_id' => $transaction_id,
                    'error' => $e->getMessage()
                ]);
                $paid_amount = 0;
            }

            return view('EcomLayouts.order-details', compact('transaction', 'delivered_products', 'order_products', 'paid_amount'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve order details', [
                'transaction_id' => $transaction_id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Unable to load order details. Please try again later.');
        }
    }

    public function search(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'query' => 'required|string|max:100',
            ]);
    
            $query = trim(strtolower($validated['query']));
            
            Log::info('Search Query: ' . $query);
    
            // Check if user is authenticated
            $user = auth()->user();
            
            // Base query to fetch products
            $productsQuery = Product::with('variations')
                ->whereRaw("LOWER(TRIM(products.name)) LIKE ?", ['%' . $query . '%'])
                ->limit(20);
    
            // If user is authenticated, apply user-specific filters
            if ($user && $user->user_type == 'user_customer') {
                $shippingAddress = Contact::where('created_by', $user->id)->value('shipping_address');
                $pincode = $this->extractPincode($shippingAddress);
    
                if ($pincode) {
                    $location = DB::table('ecom_delivery_locations')
                        ->where('pincode', $pincode)
                        ->select('business_location_id')
                        ->first();
    
                    if ($location) {
                        $businessLocation = DB::table('business_locations')
                            ->where('id', $location->business_location_id)
                            ->select('selling_price_group_id')
                            ->first();
    
                        if ($businessLocation && $businessLocation->selling_price_group_id) {
                            $productsQuery->leftJoin('variations', 'products.id', '=', 'variations.product_id')
                                ->leftJoin('variation_group_prices', function ($join) use ($businessLocation) {
                                    $join->on('variations.id', '=', 'variation_group_prices.variation_id')
                                        ->where('variation_group_prices.price_group_id', $businessLocation->selling_price_group_id);
                                })
                                ->select(
                                    'products.id',
                                    'products.name',
                                    'products.mrp',
                                    'products.weight',
                                    'products.image',
                                    'variations.id as variation_id',
                                    'variations.sell_price_inc_tax as regular_price',
                                    'variation_group_prices.price_inc_tax',
                                    'variation_group_prices.price_type'
                                );
                        }
                    }
                }
            } else {
                // For unauthenticated users, just get basic product info
                $productsQuery->select(
                    'products.id',
                    'products.name',
                    'products.mrp',
                    'products.weight',
                    'products.image'
                )->with(['variations' => function($query) {
                    $query->select('id', 'product_id', 'sell_price_inc_tax');
                }]);
            }
    
            // Fetch the products
            $products = $productsQuery->get();
            Log::info('Fetched Products Count: ' . $products->count());
    
            // Process the prices for authenticated users
            if ($user && $user->user_type == 'user_customer') {
                foreach ($products as $product) {
                    if ($product->price_type === 'percentage' && $product->price_inc_tax !== null) {
                        $product->final_price = $product->regular_price * (1 + ($product->price_inc_tax / 100));
                    } elseif ($product->price_inc_tax !== null) {
                        $product->final_price = $product->price_inc_tax;
                    } else {
                        $product->final_price = $product->regular_price;
                    }
                }
            }
    
            // Format the response based on authentication status
            if ($user && $user->user_type == 'user_customer') {
                $formattedProducts = $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'mrp' => $product->mrp,
                        'weight' => $product->weight,
                        'image' => $product->image,
                        'discount' => $product->discount ?? 0,
                        'variation' => $product->variation_id ?? null,
                        'final_price' => $product->final_price ?? ($product->variation->sell_price_inc_tax ?? $product->regular_price),
                    ];
                });
            } else {
                // For unauthenticated users, map basic product info
                $formattedProducts = $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'mrp' => $product->mrp,
                        'weight' => $product->weight,
                        'image' => $product->image,
                        'final_price' => $product->variations->first() ? $product->variations->first()->sell_price_inc_tax : null,
                    ];
                });
            }
    
            return response()->json([
                'success' => true,
                'products' => $products
            ]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Search validation failed: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function BillingProfile (){
        return view('EcomLayouts.billing-profile');
    }


    private function extractPincode($address)
    {
        if (!$address) {
            return null;
        }
    
        // Regular expression to match a 6-digit pincode
        preg_match('/\b\d{6}\b/', $address, $matches);
    
        // Return the matched pincode or null if not found
        return $matches[0] ?? null;
    }
}
