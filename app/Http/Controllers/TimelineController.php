<?php

namespace App\Http\Controllers;

use App\Models\TimelineEntry;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = TimelineEntry::where('user_id', $user->id)->orderByDesc('date')->orderByDesc('created_at');

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->date_from) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->where('date', '<=', $request->date_to);
        }

        $entries = $query->paginate(20);
        $categories = TimelineEntry::$categories;

        return view('timeline.index', compact('entries', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:200',
            'category'         => 'required|in:' . implode(',', array_keys(TimelineEntry::$categories)),
            'date'             => 'required|date',
            'description'      => 'nullable|string|max:500',
            'duration_minutes' => 'nullable|integer|min:0',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['icon'] = TimelineEntry::$categories[$validated['category']]['icon'] ?? '📌';

        TimelineEntry::create($validated);

        return back()->with('success', 'Entry added to timeline!');
    }

    public function update(Request $request, TimelineEntry $timelineEntry)
    {
        $this->authorize('update', $timelineEntry);

        $validated = $request->validate([
            'title'            => 'required|string|max:200',
            'category'         => 'required|in:' . implode(',', array_keys(TimelineEntry::$categories)),
            'date'             => 'required|date',
            'description'      => 'nullable|string|max:500',
            'duration_minutes' => 'nullable|integer|min:0',
        ]);

        $validated['icon'] = TimelineEntry::$categories[$validated['category']]['icon'] ?? '📌';
        $timelineEntry->update($validated);

        return back()->with('success', 'Entry updated!');
    }

    public function destroy(TimelineEntry $timelineEntry)
    {
        $this->authorize('delete', $timelineEntry);
        $timelineEntry->delete();
        return back()->with('success', 'Entry deleted.');
    }
}
