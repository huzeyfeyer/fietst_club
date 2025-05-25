<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ContactMessageController extends Controller
{
    /**
     * Toon een lijst van ontvangen contactberichten.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $query = ContactMessage::orderBy('created_at', 'desc');

        $filters = [
            'filter_status' => $request->input('filter_status', 'all'),
            'include_archived' => $request->boolean('include_archived') // boolean() is handig hier
        ];
        session(['contact_messages_index_filters' => $filters]); // Sla filters op in sessie

        if (!$filters['include_archived']) {
            $query->whereNull('archived_at');
        }
        
        if ($filters['filter_status'] === 'unread') {
            $query->where('is_read', false);
        } elseif ($filters['filter_status'] === 'read') {
            $query->where('is_read', true);
        }

        $messages = $query->paginate(15)->withQueryString();

        return view('admin.contact-messages.index', compact('messages', 'filters')); // Geef filters ook mee aan view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ContactMessage $contactMessage
     * @return \Illuminate\View\View
     */
    public function show(ContactMessage $contactMessage): View
    {
        if (!$contactMessage->is_read) {
            $contactMessage->is_read = true;
            $contactMessage->save();
        }
        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactMessage $contactMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactMessage $contactMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();
        return redirect()->route('admin.contact-messages.index', session('contact_messages_index_filters', []))
                         ->with('success', __('Bericht permanent verwijderd.'));
    }

    /**
     * Markeer het bericht als ongelezen.
     */
    public function markAsUnread(ContactMessage $contactMessage): RedirectResponse
    {
        if (!$contactMessage->archived_at) { // Kan alleen als niet gearchiveerd
            $contactMessage->is_read = false;
            $contactMessage->save();
            return redirect()->route('admin.contact-messages.show', $contactMessage)
                             ->with('success', __('Bericht gemarkeerd als ongelezen.'));
        }
        return redirect()->route('admin.contact-messages.show', $contactMessage)
                         ->with('error', __('Kan gearchiveerd bericht niet als ongelezen markeren.'));
    }

    /**
     * Archiveer het bericht.
     */
    public function archive(ContactMessage $contactMessage): RedirectResponse
    {
        if (!$contactMessage->archived_at) {
            $contactMessage->archived_at = now();
            $contactMessage->is_read = true; // Gearchiveerde berichten worden ook als gelezen beschouwd
            $contactMessage->save();
            return redirect()->route('admin.contact-messages.show', $contactMessage)
                             ->with('success', __('Bericht gearchiveerd.'));
        }
        return redirect()->route('admin.contact-messages.show', $contactMessage);
    }

    /**
     * De-archiveer het bericht.
     */
    public function unarchive(ContactMessage $contactMessage): RedirectResponse
    {
        if ($contactMessage->archived_at) {
            $contactMessage->archived_at = null;
            $contactMessage->save(); // is_read status blijft behouden
            return redirect()->route('admin.contact-messages.show', $contactMessage)
                             ->with('success', __('Bericht uit archief gehaald.'));
        }
        return redirect()->route('admin.contact-messages.show', $contactMessage);
    }
}
