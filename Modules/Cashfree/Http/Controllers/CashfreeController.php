<?php

namespace Modules\Cashfree\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashfreeController extends Controller
{
    /**
     * Get payment transactions from the payments table
     * 
     * @return \Illuminate\Http\Response
     */
   public function getPaymentTransactions()
{
    $payments = DB::table('payments')
        ->select('id', 'user_id', 'order_id', 'request', 'response', 'status', 'created_at', 'updated_at')
        ->orderBy('created_at', 'desc') // Change to DESC for latest first
        ->get();
        
    // Return view instead of JSON
    return view('cashfree::transactions', compact('payments'));
}
    
    /**
     * Display payment links view
     * 
     * @return \Illuminate\View\View
     */
    public function paymentLinks()
    {
        return view('cashfree::payment-links');
    }


    public function getPaymentTransactionsApi()
    {
        $payments = DB::table('payments')
            ->select('id', 'user_id', 'order_id', 'request', 'response', 'status', 'created_at', 'updated_at')
            ->orderBy('created_at', 'desc') // Change to DESC for latest first
            ->get();
            
        return response()->json([
            'status' => 'success',
            'data' => $payments
        ]);
    }
}
