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

    public function show(Coach $coach)
    {
        return response()->json($coach->load(['gym', 'images']));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            '*.gym_id' => 'nullable|exists:gyms,id',
            '*.name' => 'required|string|max:255',
            '*.description' => 'nullable|string',
            '*.is_freelancer' => 'boolean',
            '*.price_range' => 'nullable|string',
            '*.metadata' => 'nullable|array',
            '*.specialization' => 'nullable|array',
            '*.images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            '*.addresses' => 'nullable|array',
            '*.addresses.*.street' => 'required|string',
            '*.addresses.*.city' => 'required|string',
            '*.addresses.*.state' => 'required|string',
            '*.addresses.*.country' => 'required|string',
            '*.addresses.*.postal_code' => 'required|string',
            '*.addresses.*.latitude' => 'nullable|numeric',
            '*.addresses.*.longitude' => 'nullable|numeric',
        ]);

        $createdCoaches = [];

        foreach ($request->all() as $coachData) {
            $coach = Coach::create([
                'name' => $coachData['name'],
                'description' => $coachData['description'],
                'is_freelancer' => $coachData['is_freelancer'],
                'preferred_gyms' => [$coachData['preferred_gyms']],
                'price_range' => $coachData['price_range']??'',
                'metadata' => $coachData['metadata']??'',
                'specialization' => $coachData['specialization'],
            ]);

            if (isset($coachData['images'])) {
                foreach ($coachData['images'] as $image) {
                    $path = $image->store('coach_images', 'public');
                    CoachImage::create([
                        'coach_id' => $coach->id,
                        'image_path' => $path,
                        'alt_text' => $coachData['name'],
                    ]);
                }
            }

            if (isset($coachData['addresses'])) {
                foreach ($coachData['addresses'] as $address) {
                    $coach->addresses()->create($address);
                }
            }

            $createdCoaches[] = $coach->load('images', 'addresses');
        }

        return response()->json($createdCoaches, 201);
    }
    public function store(Request $request)
    {
        $request->validate([
            'gym_id' => 'nullable|exists:gyms,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_freelancer' => 'boolean',
            'preferred_gyms' => 'nullable|array',
            'price_range' => 'nullable|string',
            'metadata' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'addresses' => 'nullable|array',
            'addresses.*.street' => 'required|string',
            'addresses.*.city' => 'required|string',
            'addresses.*.state' => 'required|string',
            'addresses.*.country' => 'required|string',
            'addresses.*.postal_code' => 'required|string',
            'addresses.*.latitude' => 'nullable|numeric',
            'addresses.*.longitude' => 'nullable|numeric',
        ]);

        $coach = Coach::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_freelancer' => $request->is_freelancer,
            'preferred_gyms' => $request->preferred_gyms,
            'price_range' => $request->price_range,
            'metadata' => $request->metadata,
            'gym_id' => $request->gym_id,
            'specialization' => $request->specialization??[],
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

        if ($request->has('addresses')) {
            foreach ($request->addresses as $address) {
                $coach->addresses()->create($address);
            }
        }

        return response()->json($coach->load('images', 'addresses'), 201);
    }

    public function update(Request $request, Coach $coach)
    {
        $request->validate([
            'gym_id' => 'nullable|exists:gyms,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_freelancer' => 'boolean',
            'preferred_gyms' => 'nullable|array',
            'price_range' => 'nullable|string',
            'metadata' => 'nullable|array',
            'specialization' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'addresses' => 'nullable|array',
            'addresses.*.street' => 'required|string',
            'addresses.*.city' => 'required|string',
            'addresses.*.state' => 'required|string',
            'addresses.*.country' => 'required|string',
            'addresses.*.postal_code' => 'required|string',
            'addresses.*.latitude' => 'nullable|numeric',
            'addresses.*.longitude' => 'nullable|numeric',
        ]);

        $coach->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_freelancer' => $request->is_freelancer,
            'preferred_gyms' => $request->preferred_gyms,
            'price_range' => $request->price_range,
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

        if ($request->has('addresses')) {
            $coach->addresses()->delete();
            foreach ($request->addresses as $address) {
                $coach->addresses()->create($address);
            }
        }

        return response()->json($coach->load('images', 'addresses'));
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
