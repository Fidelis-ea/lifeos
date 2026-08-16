<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transactions = Transaction::where('user_id', $user->id)
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->paginate(15);

        // Single query for income/expense totals instead of 2 separate queries
        $totals = Transaction::where('user_id', $user->id)
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as income,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as expense
            ")
            ->first();

        $income = $totals->income;
        $expense = $totals->expense;
        $balance = $income - $expense;

        // Monthly expense by category — use DB aggregation instead of PHP
        $expenseByCategory = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('date', today()->month)
            ->whereYear('date', today()->year)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        return view('finance.index', compact('transactions', 'income', 'expense', 'balance', 'expenseByCategory'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string|max:200',
            'date' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();
        Transaction::create($validated);

        return back()->with('success', 'Transaction logged! 💰');
    }
}
