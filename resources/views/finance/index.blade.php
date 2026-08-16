@extends('layouts.app')

@section('title', 'Finance Tracker')

@section('content')
<!-- Finance Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8 font-mono text-center">
    <div class="card-brutal" style="background-color: #5BC0EB;">
        <span class="block text-xs font-bold text-gray-700 uppercase">CURRENT BALANCE</span>
        <span class="text-2xl lg:text-3xl font-headline font-extrabold">Rp {{ number_format($balance, 2) }}</span>
    </div>
    <div class="card-brutal" style="background-color: #9BE564;">
        <span class="block text-xs font-bold text-gray-700 uppercase">TOTAL INCOME</span>
        <span class="text-2xl lg:text-3xl font-headline font-extrabold">Rp {{ number_format($income, 2) }}</span>
    </div>
    <div class="card-brutal text-white" style="background-color: #FF6B6B;">
        <span class="block text-xs font-bold text-white uppercase">TOTAL EXPENSE</span>
        <span class="text-2xl lg:text-3xl font-headline font-extrabold">Rp {{ number_format($expense, 2) }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Log Transaction Form -->
    <div class="space-y-8">
        <div class="card-brutal bg-brutalist-yellow">
            <h3 class="font-headline text-xl font-bold mb-4 flex items-center gap-2">
                <span>💰</span> Log Transaction
            </h3>
            
            <form method="POST" action="{{ route('finance.store') }}" class="space-y-4 font-mono text-sm">
                @csrf
                
                <div>
                    <label for="type" class="block font-bold mb-1">TRANSACTION TYPE</label>
                    <select name="type" id="type" required class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 bg-white">
                        <option value="expense">Expense (Pengeluaran)</option>
                        <option value="income">Income (Pemasukan)</option>
                    </select>
                </div>

                <div>
                    <label for="amount" class="block font-bold mb-1">AMOUNT (RP)</label>
                    <input type="number" step="0.01" name="amount" id="amount" required placeholder="50000" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="category" class="block font-bold mb-1">CATEGORY</label>
                        <select name="category" id="category" required class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 bg-white">
                            <option value="Food">Food</option>
                            <option value="Transport">Transport</option>
                            <option value="Education">Education</option>
                            <option value="Gaming">Gaming</option>
                            <option value="Entertainment">Entertainment</option>
                            <option value="Internet">Internet</option>
                            <option value="Shopping">Shopping</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="date" class="block font-bold mb-1">DATE</label>
                        <input type="date" name="date" id="date" required value="{{ today()->toDateString() }}" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                    </div>
                </div>

                <div>
                    <label for="description" class="block font-bold mb-1">DESCRIPTION</label>
                    <input type="text" name="description" id="description" required placeholder="e.g. Lunch at McD, Freelance project pay" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                </div>

                <button type="submit" class="w-full py-3 btn-brutal-primary mt-2">
                    💰 LOG TRANSACTION
                </button>
            </form>
        </div>

        <!-- Monthly Expense Categories summary -->
        <div class="card-brutal bg-white font-mono text-sm">
            <h3 class="font-headline text-lg font-bold mb-4">📊 Monthly Expense Breakdown</h3>
            <div class="space-y-3">
                @forelse($expenseByCategory as $category => $amount)
                    <div class="flex justify-between items-center border-b border-brutalist-primary/20 pb-2 last:border-none">
                        <span class="font-bold">{{ $category }}</span>
                        <span class="font-semibold text-brutalist-red">Rp {{ number_format($amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 italic">No expenses recorded for this month.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Transaction History Logs -->
    <div class="lg:col-span-2 space-y-8">
        <div class="card-brutal bg-white">
            <h2 class="font-headline text-2xl font-bold mb-6">Transaction Logs</h2>

            <div class="space-y-4 font-mono text-sm">
                @forelse($transactions as $tx)
                    <div class="border-4 border-brutalist-primary rounded-[10px] p-4 bg-white shadow-brutal-sm hover:-translate-y-0.5 transition-transform flex justify-between items-center">
                        <div>
                            <span class="text-xs font-bold text-gray-500">{{ $tx->date->format('M d, Y') }}</span>
                            <h3 class="font-headline text-lg font-bold leading-tight">{{ $tx->description }}</h3>
                            <span class="text-[10px] font-bold uppercase bg-brutalist-bg border-2 border-brutalist-primary px-2 py-0.5 rounded mt-1 inline-block">
                                {{ $tx->category }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-headline font-extrabold {{ $tx->type === 'income' ? 'text-brutalist-green' : 'text-brutalist-red' }}">
                                {{ $tx->type === 'income' ? '+' : '-' }} Rp {{ number_format($tx->amount, 2) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center bg-brutalist-bg rounded-[10px] border-4 border-dashed border-brutalist-primary">
                        <span class="text-5xl block mb-4">💰</span>
                        <p class="text-base font-bold">No transactions logged yet.</p>
                        <p class="text-sm text-gray-500">Log pamasukan or pengeluaran on the left panel to watch your balance tracker.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
