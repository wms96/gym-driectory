<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\ScheduleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScheduleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'class_id' => 'required|exists:classes,id',
            'coach_id' => 'required|exists:coaches,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $schedule = Schedule::create($request->only(['gym_id', 'class_id', 'coach_id', 'start_time', 'end_time']));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('schedule_images', 'public');
                ScheduleImage::create([
                    'schedule_id' => $schedule->id,
                    'image_path' => $path,
                    'alt_text' => $request->input('alt_text', 'Schedule image'),
                ]);
            }
        }

        return response()->json($schedule->load('images'), 201);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'coach_id' => 'required|exists:coaches,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $schedule->update($request->only(['class_id', 'coach_id', 'start_time', 'end_time']));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('schedule_images', 'public');
                ScheduleImage::create([
                    'schedule_id' => $schedule->id,
                    'image_path' => $path,
                    'alt_text' => $request->input('alt_text', 'Schedule image'),
                ]);
            }
        }

        return response()->json($schedule->load('images'));
    }

    public function destroy(Schedule $schedule)
    {
        foreach ($schedule->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $schedule->delete();

        return response()->json(null, 204);
    }

    public function deleteImage($id)
    {
        $image = ScheduleImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(null, 204);
    }

    // ... other methods like index, show, etc.
}
