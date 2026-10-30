<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportContactRequest;
use App\Http\Requests\StoreContactRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class ContactController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('contact.index', compact('categories', 'tags'));
    }

    public function store(StoreContactRequest $request)
    {
        $request->flash();

        $validated = $request->validated();
        $category = Category::find($validated['category_id']);
        $tags = Tag::wherein('id', $validated['tag_ids'] ?? [])->get();

        return view('contact.confirm', compact('validated', 'category', 'tags'));
    }

    public function thanksStore(StoreContactRequest $request)
    {
        $contact = Contact::create($request->safe()->except(['tag_ids']));
        $contact->tags()->attach($request->validated(['tag_ids'], []));

        return redirect('/thanks');
    }

    public function thanks()
    {
        return view('contact.thanks');
    }

    public function export(ExportContactRequest $request)
    {
        $contacts = Contact::with('category')
            ->search($request->validated())
            ->latest()
            ->orderByDesc('id')
            ->get();
        $fileName = 'contacts_'.now()->format('YmdHis').'.csv';

        return response()->streamDownload(function () use ($contacts) {
            $stream = fopen('php://output', 'w');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, Contact::CSV_HEADERS);
            foreach ($contacts as $contact) {
                fputcsv($stream, $contact->toCsvRow());
            }
            fclose($stream);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
