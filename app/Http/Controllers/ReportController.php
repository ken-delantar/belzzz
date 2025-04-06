<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function vendor()
    {
        return view('reports.vendor');
    }

    public function index()
    {
        $reports = Report::paginate(3);

        // Calculate average rating
        $averageRating = Report::avg('rating');

        // Get rating distribution
        $ratingsCount = Report::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        // Total reviews count
        $totalReviews = Report::count();

        return view('reports.index', compact('reports', 'averageRating', 'ratingsCount', 'totalReviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'follow_up' => 'required|in:yes,no', // Accepts "yes" or "no"
        ]);

        Report::create([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'follow_up' => $request->follow_up === 'yes' ? 1 : 0, // Convert to boolean
            'report_by' => auth()->id(), // Store the ID of the logged-in user
        ]);

        return response()->json(['message' => 'Feedback submitted successfully!']);

    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
