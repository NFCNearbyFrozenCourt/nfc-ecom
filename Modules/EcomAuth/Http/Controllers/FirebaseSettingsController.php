<?php

namespace Modules\EcomAuth\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class FirebaseSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // Fetch Firebase details from the .env file
        $firebaseConfig = [
            'api_key'            => env('FIREBASE_API_KEY'),
            'auth_domain'        => env('FIREBASE_AUTH_DOMAIN'),
            'database_url'       => env('FIREBASE_DATABASE_URL'),
            'project_id'         => env('FIREBASE_PROJECT_ID'),
            'storage_bucket'     => env('FIREBASE_STORAGE_BUCKET'),
            'messaging_sender_id'=> env('FIREBASE_MESSAGING_SENDER_ID'),
            'app_id'             => env('FIREBASE_APP_ID'),
            'measurement_id'     => env('FIREBASE_MEASUREMENT_ID'),
        ];
    
        // Pass the Firebase config to the view
        return view('ecomauth::firebase.index', compact('firebaseConfig'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('ecomauth::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('ecomauth::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('ecomauth::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */

     public function update(Request $request)
     {
         try {
             Log::info('Update request received:', $request->all());
     
             $data = $request->except('_token', '_method');
     
             foreach ($data as $key => $value) {
                 // Format the key properly
                 $envKey = strtoupper($key);
                 if (strpos($envKey, 'FIREBASE_') !== 0) {
                     $envKey = 'FIREBASE_' . $envKey;
                 }
                 
                 $this->setEnvValue($envKey, $value);
             }
     
             // Clear config cache after updating .env
             \Artisan::call('config:clear');
             
             Log::info('Firebase settings updated successfully.');
             return redirect()->back()->with('success', 'Firebase settings updated successfully.');
         } catch (\Exception $e) {
             Log::error('Error updating Firebase settings: ' . $e->getMessage());
             return redirect()->back()->with('error', 'Failed to update Firebase settings: ' . $e->getMessage());
         }
     }
    
    /**
     * Function to update .env file dynamically
     */
    private function setEnvValue($key, $value)
{
    try {
        $path = base_path('.env');
        Log::info("Attempting to update .env at path: {$path}");
        Log::info("Updating key: FIREBASE_{$key} with value: {$value}");

        if (file_exists($path)) {
            $envContent = file_get_contents($path);
            
            // Format key with FIREBASE_ prefix if not already there
            $envKey = (strpos($key, 'FIREBASE_') === 0) ? $key : "FIREBASE_{$key}";
            
            // Check if key exists
            if (preg_match("/^{$envKey}=.*/m", $envContent)) {
                Log::info("Key {$envKey} found, replacing value");
                $envContent = preg_replace("/^{$envKey}=.*/m", "{$envKey}={$value}", $envContent);
            } else {
                Log::info("Key {$envKey} not found, adding new entry");
                $envContent .= "\n{$envKey}={$value}";
            }
            
            // Write back to file
            $result = file_put_contents($path, $envContent);
            Log::info("File write result: " . ($result !== false ? "Success ({$result} bytes)" : "Failed"));
        } else {
            Log::error('.env file not found at: ' . $path);
        }
    } catch (\Exception $e) {
        Log::error("Failed to update .env key {$key}: " . $e->getMessage());
        Log::error("Stack trace: " . $e->getTraceAsString());
    }
}

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
