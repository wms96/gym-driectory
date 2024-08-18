<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        $subscription = Subscription::create($request->only(['gym_id', 'name', 'description', 'price', 'duration_days']));

        if ($request->has('facilities')) {
            $subscription->facilities()->attach($request->facilities);
        }

        return response()->json($subscription->load('facilities'), 201);
    }

    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        $subscription->update($request->only(['name', 'description', 'price', 'duration_days']));

        if ($request->has('facilities')) {
            $subscription->facilities()->sync($request->facilities);
        }

        return response()->json($subscription->load('facilities'));
    }

    // ... other methods
}
