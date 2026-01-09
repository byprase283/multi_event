<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('participants')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tanggal_event' => 'required|date',
            'lokasi' => 'nullable|string|max:255',
            'harga' => 'required|integer|min:0',
            'kuota' => 'required|integer|min:1',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('events', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dibuat!');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tanggal_event' => 'required|date',
            'lokasi' => 'nullable|string|max:255',
            'harga' => 'required|integer|min:0',
            'kuota' => 'required|integer|min:1',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($event->gambar) {
                Storage::disk('public')->delete($event->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('events', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil diupdate!');
    }

    public function destroy(Event $event)
    {
        if ($event->gambar) {
            Storage::disk('public')->delete($event->gambar);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus!');
    }
}
