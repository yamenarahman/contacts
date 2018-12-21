<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = auth()->user()->contacts()->orderBy('name')->get();

        return response()->json(compact('contacts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|unique:contacts',
            'company' => 'nullable'
        ]);

        $attributes['user_id'] = auth()->id();

        $contact = Contact::create($attributes);

        return response()->json([
            'type' => 'success',
            'contact' => $contact
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return response()->json(compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|unique:contacts,phone,' . $contact->id,
            'company' => 'nullable'
        ]);

        $contact->update($attributes);

        return response()->json([
           'type' => 'success',
           'contact' => $contact
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Contact $contact
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(['type' => 'success']);
    }
}
