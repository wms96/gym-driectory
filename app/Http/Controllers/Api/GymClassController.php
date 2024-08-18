<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GymClass;
use Illuminate\Http\Request;

class GymClassController extends Controller
{
    public function index()
    {
        $classes = GymClass::with('gym')->get();

        return response()->json($classes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class = GymClass::create($request->all());

        return response()->json($class, 201);
    }

    public function show(GymClass $class)
    {
        return response()->json($class->load('gym'));
    }

    public function update(Request $request, GymClass $class)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class->update($request->all());

        return response()->json($class);
    }

    public function destroy(GymClass $class)
    {
        $class->delete();

        return response()->json(null, 204);
    }
}
