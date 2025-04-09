<?php

namespace Modules\EcomAuth\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Facades\FirebaseAuth;
use App\Facades\FirebaseDatabase;

class FirebaseService
{
    /**
     * Create a new user in Firebase Authentication
     *
     * @param string $email
     * @param string $password
     * @param array $userData
     * @return array
     */
    public function createUser($email, $password, array $userData = [])
    {
        try {
            $userProperties = [
                'email' => $email,
                'password' => $password,
                'displayName' => $userData['name'] ?? '',
                'disabled' => false,
            ];
            
            $firebaseUser = FirebaseAuth::createUser($userProperties);
            
            // Store additional user data in Realtime Database
            $databaseData = array_merge(
                $userData,
                [
                    'uid' => $firebaseUser->uid,
                    'email' => $email,
                    'created_at' => $this->databaseTimestamp(),
                ]
            );
            
            // Remove sensitive data
            unset($databaseData['password']);
            
            // Save to Firebase Database
            FirebaseDatabase::getReference('users/' . $firebaseUser->uid)
                ->set($databaseData);
                
            return [
                'success' => true,
                'user' => $firebaseUser,
                'uid' => $firebaseUser->uid
            ];
        } catch (Exception $e) {
            Log::error('Firebase user creation failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Sign in with email and password
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public function signInWithEmailAndPassword($email, $password)
    {
        try {
            $signInResult = FirebaseAuth::signInWithEmailAndPassword($email, $password);
            
            return [
                'success' => true,
                'user' => $signInResult->data(),
                'idToken' => $signInResult->idToken(),
                'refreshToken' => $signInResult->refreshToken()
            ];
        } catch (Exception $e) {
            Log::error('Firebase authentication failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get user data from Firebase
     *
     * @param string $uid
     * @return array|null
     */
    public function getUserData($uid)
    {
        try {
            $reference = FirebaseDatabase::getReference('users/' . $uid);
            return $reference->getValue();
        } catch (Exception $e) {
            Log::error('Failed to get Firebase user data: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Send password reset email
     *
     * @param string $email
     * @return bool
     */
    public function sendPasswordResetEmail($email)
    {
        try {
            FirebaseAuth::sendPasswordResetLink($email);
            return true;
        } catch (Exception $e) {
            Log::error('Failed to send password reset email: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Helper method to generate a Firebase timestamp
     * 
     * @return array
     */
    protected function databaseTimestamp()
    {
        return ['.sv' => 'timestamp'];
    }
}
