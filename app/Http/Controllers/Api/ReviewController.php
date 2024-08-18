<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['gym', 'member'])->get();

        return response()->json($reviews);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'member_id' => 'required|exists:members,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create($request->all());

        return response()->json($review, 201);
    }

    public function show(Review $review)
    {
        return response()->json($review->load(['gym', 'member']));
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($request->all());

        return response()->json($review);
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json(null, 204);
    }

    public function gymReviews($gymId)
    {
        $reviews = Review::where('gym_id', $gymId)->with('member')->get();

        return response()->json($reviews);
    }

    public function memberReviews($memberId)
    {
        $reviews = Review::where('member_id', $memberId)->with('gym')->get();

        return response()->json($reviews);
    }
}
