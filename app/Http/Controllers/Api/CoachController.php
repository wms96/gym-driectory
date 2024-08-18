<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\CoachImage;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = Coach::with(['gym', 'images'])->get();

        return response()->json($coaches);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gym_id' => 'required',
            'name' => 'required|string|max:255',
            'metadata' => 'nullable|array',
            'specialization' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $coach = Coach::create([
            'name' => $request->name,
            'metadata' => $request->metadata,
            'gym_id' => $request->gym_id,
            'specialization' => $request->specialization,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('coach_images', 'public');
                CoachImage::create([
                    'coach_id' => $coach->id,
                    'image_path' => $path,
                    'alt_text' => $request->input('alt_text', $coach->name),
                ]);
            }
        }

        return response()->json($coach->load('images'), 201);
    }

    public function show(Coach $coach)
    {
        return response()->json($coach->load(['gym', 'images']));
    }

    public function update(Request $request, Coach $coach)
    {
        $request->validate([
            'gym_id' => 'required',
            'name' => 'required|string|max:255',
            'metadata' => 'nullable|array',
            'specialization' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $coach->update([
            'name' => $request->name,
            'metadata' => $request->metadata,
            'gym_id' => $request->gym_id,
            'specialization' => $request->specialization,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('coach_images', 'public');
                CoachImage::create([
                    'coach_id' => $coach->id,
                    'image_path' => $path,
                    'alt_text' => $request->input('alt_text', $coach->name),
                ]);
            }
        }

        return response()->json($coach->load('images'));
    }

    public function search(Request $request)
    {
        $query = Coach::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }

        if ($request->has('metadata')) {
            foreach ($request->metadata as $key => $value) {
                $query->where('metadata->'.$key, $value);
            }
        }

        $gyms = $query->with('images')->get();

        return response()->json($gyms);
    }

    public function deleteImage($id)
    {
        $image = CoachImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(null, 204);
    }

    public function destroy(Gym $gym)
    {
        $gym->delete();

        return response()->json(null, 204);
    }
}
