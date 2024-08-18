<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use App\Models\GymImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GymController extends Controller
{
    public function index()
    {
        $gyms = Gym::with(['owners', 'images', 'contacts', 'admin', 'classes', 'coaches', 'facilities', 'subscriptions', 'members', 'address', 'branches'])->get();

        return response()->json($gyms);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'metadata' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $gym = Gym::create([
            'name' => $request->name,
            'metadata' => $request->metadata,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('gym_images', 'public');
                GymImage::create([
                    'gym_id' => $gym->id,
                    'image_path' => $path,
                    'alt_text' => $request->input('alt_text', $gym->name),
                ]);
            }
        }

        return response()->json($gym->load('images'), 201);
    }

    public function show(Gym $gym)
    {
        return response()->json($gym->load(['owners', 'contacts', 'admin', 'classes', 'coaches', 'facilities', 'subscriptions', 'members', 'address', 'branches']));
    }

    public function update(Request $request, Gym $gym)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'metadata' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $gym->update([
            'name' => $request->name,
            'metadata' => $request->metadata,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('gym_images', 'public');
                GymImage::create([
                    'gym_id' => $gym->id,
                    'image_path' => $path,
                    'alt_text' => $request->input('alt_text', $gym->name),
                ]);
            }
        }

        return response()->json($gym->load('images'));
    }

    public function search(Request $request)
    {
        $query = Gym::query();

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
        $image = GymImage::findOrFail($id);
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
