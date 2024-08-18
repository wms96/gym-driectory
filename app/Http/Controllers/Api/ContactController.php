<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::with('gym')->get();

        return response()->json($contacts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'type' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $contact = Contact::create($request->all());

        return response()->json($contact, 201);
    }

    public function show(Contact $contact)
    {
        return response()->json($contact->load('gym'));
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'type' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $contact->update($request->all());

        return response()->json($contact);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(null, 204);
    }
}
