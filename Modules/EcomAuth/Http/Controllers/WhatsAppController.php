<?php

namespace Modules\EcomAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class WhatsAppController extends Controller
{
    public function show()
    {
        return view('ecomauth::whatsapp.index', [
            'env' => [
                'WHATSAPP_API_URL' => env('WHATSAPP_API_URL'),
                'WHATSAPP_ACCESS_TOKEN' => env('WHATSAPP_ACCESS_TOKEN'),
                'FACEBOOK_APP_ID' => env('FACEBOOK_APP_ID'),
                'FACEBOOK_APP_SECRET' => env('FACEBOOK_APP_SECRET'),
                'WHATSAPP_PHONE_NUMBER_ID' => env('WHATSAPP_PHONE_NUMBER_ID'),
            ]
        ]);
    }

    public function update(Request $request)
    {
        $envPath = base_path('.env');

        if (File::exists($envPath)) {
            $env = File::get($envPath);

            $keys = [
                'WHATSAPP_API_URL',
                'WHATSAPP_ACCESS_TOKEN',
                'FACEBOOK_APP_ID',
                'FACEBOOK_APP_SECRET',
                'WHATSAPP_PHONE_NUMBER_ID'
            ];

            foreach ($keys as $key) {
                $pattern = "/^{$key}=.*$/m";
                $replacement = $key . '=' . $request->input($key);
                if (preg_match($pattern, $env)) {
                    $env = preg_replace($pattern, $replacement, $env);
                } else {
                    $env .= "\n" . $replacement;
                }
            }

            File::put($envPath, $env);
        }

        return redirect()->back()->with('success', 'Environment variables updated successfully.');
    }
}