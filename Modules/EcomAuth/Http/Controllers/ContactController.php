<?php

namespace Modules\EcomAuth\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;

class ContactController extends Controller
{
    /**
     * Display the authenticated user's contact details.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $userId = Auth::id();
        $contact = Contact::where('created_by', $userId)->first();

        if (!$contact) {
            return redirect()->back()->with('error', 'No contact found.');
        }

        return view('EcomLayouts.billing-profile', compact('contact'));
    }

    /**
     * Update the authenticated user's contact in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
            'supplier_business_name' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'landline' => 'nullable|string|max:20',
            'shipping_address' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // Find the contact by ID and update details
        $contact = Contact::findOrFail($id);
        $contact->update($request->all());
    
        // Return to the view with updated contact details
        return redirect()->route('billingProfile')
        ->with('success', 'Contact details updated successfully!');
    }

    public function updateShippingStatus(Request $request, $id)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        // Find the contact by ID and update shipping status
        $contact = Contact::findOrFail($id);
        $contact->update(['shipping_address' => $request->shipping_address]);
    
        return response()->json(['success' => 'Shipping status updated successfully!']);
    }

    public function updateShippingAddress(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Get the current user's id
            $userId = Auth::id();

            // Find the contact associated with the user
            $contact = Contact::where('created_by', $userId)->first();

            if (!$contact) {
                // Create a new contact record if one doesn't exist
                $contact = new Contact();
                $contact->user_id = $userId;
            }

            // Update the shipping address fields
            $contact->shipping_address = $request->address;
            

            // Save the contact
            $contact->save();

            return response()->json([
                'success' => true,
                'message' => 'Shipping address updated successfully',
                'data' => [
                    'address' => $contact->shipping_address,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating shipping address',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}