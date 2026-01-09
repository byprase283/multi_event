<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display landing page with active events
     */
    public function index()
    {
        $events = Event::where('is_active', true)
            ->where('tanggal_event', '>=', now())
            ->orderBy('tanggal_event')
            ->get();

        return view('welcome', compact('events'));
    }

    /**
     * Display event detail
     */
    public function show(Event $event)
    {
        if (!$event->is_active) {
            abort(404);
        }

        return view('events.show', compact('event'));
    }
}
