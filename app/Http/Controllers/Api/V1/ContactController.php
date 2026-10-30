<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\IndexContactRequest;
use App\Http\Requests\Api\V1\StoreContactRequest;
use App\Http\Requests\Api\V1\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexContactRequest $request)
    {
        $perPage = $request->validated('per_page', 20);

        $contacts = Contact::with(['category', 'tags'])
            ->search($request->validated())
            ->latest()
            ->paginate($perPage);

        return ContactResource::collection($contacts->items())->additional([
            'meta' => [
                'current_page' => $contacts->currentPage(),
                'last_page' => $contacts->lastPage(),
                'per_page' => $contacts->perPage(),
                'total' => $contacts->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create($request->safe()->except(['tag_ids']));
        $contact->tags()->attach($request->validated('tag_ids', []));

        return (new ContactResource($contact->load(['category', 'tags'])))->response()->setstatuscode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        return new ContactResource($contact->load(['category', 'tags']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact->update($request->safe()->except(['tag_ids']));
        $contact->tags()->sync($request->validated('tag_ids', []));

        return new ContactResource($contact->load(['category', 'tags']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(null, 204);

    }
}
