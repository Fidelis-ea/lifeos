<?php

namespace App\Http\Controllers;

use App\Models\DailyEntry;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = today();
        $entry = DailyEntry::where('user_id', $user->id)->where('date', $today)->first();

        return view('checkin.index', compact('entry', 'today'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mood'              => 'required|integer|min:1|max:10',
            'energy'            => 'required|integer|min:1|max:10',
            'sleep_hours'       => 'required|numeric|min:0|max:24',
            'productivity'      => 'required|integer|min:1|max:10',
            'notes'             => 'nullable|string|max:1000',
            'coding_minutes'    => 'nullable|integer|min:0',
            'learning_minutes'  => 'nullable|integer|min:0',
            'exercise_minutes'  => 'nullable|integer|min:0',
            'gaming_minutes'    => 'nullable|integer|min:0',
            'japanese_minutes'  => 'nullable|integer|min:0',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['date'] = today()->toDateString();

        DailyEntry::updateOrCreate(
            ['user_id' => auth()->id(), 'date' => $validated['date']],
            $validated
        );

        return redirect()->route('dashboard')->with('success', "Today's check-in saved! 🎉");
    }
}
