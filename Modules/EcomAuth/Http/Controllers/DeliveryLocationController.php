<?php

namespace Modules\EcomAuth\Http\Controllers;

use App\BusinessLocation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\EcomAuth\DeliveryLocation;

class DeliveryLocationController extends Controller
{

 /**
     * Display a listing of the delivery locations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveryLocations = DeliveryLocation::with('businessLocation')->get();
        $locations = BusinessLocation::all();
        
        return view('EcomLayouts.delivery-locations', compact('deliveryLocations', 'locations'));
    }

    /**
     * Store a newly created delivery location in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'business_location' => 'required|exists:business_locations,id',
        'pincode' => 'required|digits:6|unique:ecom_delivery_locations,pincode',
    ]);

    // Save the new delivery location
    DeliveryLocation::create([
        'business_location_id' => $request->business_location,
        'pincode' => $request->pincode,
    ]);

    return redirect()->route('delivery-locations.index')->with('success', 'Delivery location added successfully.');
}

public function update(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'business_location' => 'required|exists:business_locations,id',
        'pincode' => "required|digits:6|unique:ecom_delivery_locations,pincode,$id",
    ]);

    $deliveryLocation = DeliveryLocation::findOrFail($id);

    // Update the delivery location
    $deliveryLocation->update([
        'business_location_id' => $request->business_location,
        'pincode' => $request->pincode,
    ]);

    return redirect()->route('delivery-locations.index')->with('success', 'Delivery location updated successfully.');
}

    /**
     * Remove the specified delivery location from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deliveryLocation = DeliveryLocation::findOrFail($id);
        $deliveryLocation->delete();

        return redirect()->route('delivery-locations.index')->with('success', 'Delivery location deleted successfully.');
    }

    
}
