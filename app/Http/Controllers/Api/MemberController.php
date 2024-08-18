<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with('gym')->get();

        return response()->json($members);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
        ]);

        $member = Member::create($request->all());

        return response()->json($member, 201);
    }

    public function show(Member $member)
    {
        return response()->json($member->load('gym'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'gym_id' => 'sometimes|required|exists:gyms,id',
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:members,email,'.$member->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
        ]);

        $member->update($request->all());

        return response()->json($member);
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return response()->json(null, 204);
    }

    public function subscriptions(Member $member)
    {
        return response()->json($member->subscriptions);
    }

    public function addSubscription(Request $request, Member $member)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $member->subscriptions()->attach($request->subscription_id, [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json($member->load('subscriptions'), 201);
    }

    public function removeSubscription(Request $request, Member $member)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
        ]);

        $member->subscriptions()->detach($request->subscription_id);

        return response()->json($member->load('subscriptions'));
    }
}
