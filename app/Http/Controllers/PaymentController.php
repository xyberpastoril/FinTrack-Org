<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\StorePaymentRequest;
use App\Models\Receipt;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payment');
    }

    public function storeAjax(StorePaymentRequest $request)
    {
        $validated = $request->validated();

        // create receipt
        $receipt = Receipt::create([
            'enrolled_student_id' => $validated['enrolled_student_id'],
            'date' => $validated['date'],
            'logged_by_user_id' => Auth::user()->id,
        ]);

        // create transactions using insert
        $transactions = [];
        foreach ($validated['transaction_items'] as $transaction_item) {
            Transaction::create([
                'semester_id' => session('semester')->id,
                'receipt_id' => $receipt->id,
                'date' => $validated['date'],
                'category' => $transaction_item->category,
                'type' => 'income',
                'description' => $transaction_item->description,
                'amount' => $transaction_item->amount,
                'foreign_key_id' => $transaction_item->foreign_key_id,
                'logged_by_user_id' => Auth::user()->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment successfully recorded.',
            'receipt' => $receipt,
        ]);
    }
}
