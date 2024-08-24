<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::with('gym')->get();

        return response()->json($facilities);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $facilityData = $request->all();

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            $facilityData['main_image'] = $request->file('main_image')->store('facilities/main_images', 'public');
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('facilities/images', 'public');
            }
            $facilityData['images'] = $images;
        }

        $facility = Facility::create($facilityData);

        return response()->json($facility, 201);
    }

    public function show(Facility $facility)
    {
        return response()->json($facility->load('gym'));
    }

    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $facilityData = $request->all();

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            // Delete the old main image
            if ($facility->main_image) {
                Storage::disk('public')->delete($facility->main_image);
            }

            $facilityData['main_image'] = $request->file('main_image')->store('facilities/main_images', 'public');
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('facilities/images', 'public');
            }

            // Delete old images
            if ($facility->images) {
                foreach ($facility->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $facilityData['images'] = $images;
        }

        $facility->update($facilityData);

        return response()->json($facility);
    }

    public function destroy(Facility $facility)
    {
        // Delete the main image
        if ($facility->main_image) {
            Storage::disk('public')->delete($facility->main_image);
        }

        // Delete all associated images
        if ($facility->images) {
            foreach ($facility->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $facility->delete();

        return response()->json(null, 204);
    }
}
