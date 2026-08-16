<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transactions = Transaction::where('user_id', $user->id)
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->paginate(15);

        // Statistics
        $income = Transaction::where('user_id', $user->id)->where('type', 'income')->sum('amount');
        $expense = Transaction::where('user_id', $user->id)->where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        // Group expense by category for current month
        $monthlyExpenses = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('date', today()->month)
            ->whereYear('date', today()->year)
            ->get();

        $expenseByCategory = $monthlyExpenses->groupBy('category')->map(function ($group) {
            return $group->sum('amount');
        })->toArray();

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
