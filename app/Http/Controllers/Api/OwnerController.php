<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index()
    {
        $owners = Owner::with('gyms')->get();

        return response()->json($owners);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:owners,email',
        ]);

        $owner = Owner::create($request->all());

        return response()->json($owner, 201);
    }

    public function show(Owner $owner)
    {
        return response()->json($owner->load('gyms'));
    }

    public function update(Request $request, Owner $owner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:owners,email,'.$owner->id,
        ]);

        $owner->update($request->all());

        return response()->json($owner);
    }

    public function destroy(Owner $owner)
    {
        $owner->delete();

        return response()->json(null, 204);
    }
}
