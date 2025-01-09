<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class StaffController extends Controller
{
    public function index()
    {
        // Mengembalikan tampilan staff landing
        return view('staff.landing'); // Sesuaikan nama view jika perlu
    }

    public function getRecentTransactions(Request $request)
    {
        // Mendapatkan transaksi terbaru untuk staff
        $transactions = Transaction::with('item')
            ->where('user_id', $request->user()->id)
            ->orderBy('transaction_date', 'desc')
            ->take(5)
            ->get();

        return response()->json($transactions);
    }
}
