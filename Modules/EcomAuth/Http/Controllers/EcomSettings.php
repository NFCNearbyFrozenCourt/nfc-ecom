<?php

namespace Modules\EcomAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use Log;

class EcomSettings extends Controller
{
    public function index()
{
    // Fetch settings from the database (assuming single-row settings)
    $settings = DB::table('ecom_settings')->first();

    // If no settings exist yet, create a default object
    if (!$settings) {
        $settings = (object)[
            'product_mrp' => 0,
            'whatsapp_login' => 0,
            'firebase_login' => 0
        ];
    }

    return view('EcomLayouts.ecom-settings', compact('settings'));
}

public function updateSettings(Request $request)
{
    $product_mrp = $request->has('product_mrp') ? 1 : 0;
    $whatsapp_login = $request->has('whatsapp_login') ? 1 : 0;
    $firebase_login = $request->has('firebase_login') ? 1 : 0;

    // Check validation: at least one login method should be enabled
    if ($whatsapp_login == 0 && $firebase_login == 0) {
        return redirect()->back()->with('error', 'At least one login method (WhatsApp or Firebase) must be enabled.');
    }

    $exists = DB::table('ecom_settings')->exists();

    if ($exists) {
        DB::table('ecom_settings')->update([
            'product_mrp' => $product_mrp,
            'whatsapp_login' => $whatsapp_login,
            'firebase_login' => $firebase_login,
        ]);
    } else {
        DB::table('ecom_settings')->insert([
            'product_mrp' => $product_mrp,
            'whatsapp_login' => $whatsapp_login,
            'firebase_login' => $firebase_login,
        ]);
    }

    return redirect()->back()->with('success', 'E-Commerce settings updated successfully!');
}
}