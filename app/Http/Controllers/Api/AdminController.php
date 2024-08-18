<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::with('gym')->get();

        return response()->json($admins);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
        ]);

        $admin = Admin::create($request->all());

        return response()->json($admin, 201);
    }

    public function show(Admin $admin)
    {
        return response()->json($admin->load('gym'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,'.$admin->id,
        ]);

        $admin->update($request->all());

        return response()->json($admin);
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        return response()->json(null, 204);
    }
}
