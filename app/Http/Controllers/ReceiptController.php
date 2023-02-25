<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function pdf(Receipt $receipt)
    {
        $receipt->load('enrolledStudent.student', 'transactions');
        // get total
        $receipt->total = $receipt->transactions->sum('amount');

        $pdf = Pdf::loadView('receipts.pdf', compact('receipt'));
        return $pdf->stream();
    }
}
