<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        $query = Participant::with('event');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Search by name or kode_registrasi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode_registrasi', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        $participants = $query->orderBy('created_at', 'desc')->paginate(15);
        $events = Event::all();

        return view('admin.participants.index', compact('participants', 'events'));
    }

    public function show(Participant $participant)
    {
        $participant->load('event');
        return view('admin.participants.show', compact('participant'));
    }

    /**
     * Validate participant - generate kode_registrasi and token_hash
     */
    public function validate(Participant $participant)
    {
        if ($participant->status_verifikasi !== 'Pending') {
            return back()->with('error', 'Peserta sudah diproses sebelumnya.');
        }

        $participant->validate();

        return back()->with('success', 'Peserta berhasil divalidasi! Kode: ' . $participant->kode_registrasi);
    }

    /**
     * Invalidate participant
     */
    public function invalidate(Participant $participant)
    {
        if ($participant->status_verifikasi !== 'Pending') {
            return back()->with('error', 'Peserta sudah diproses sebelumnya.');
        }

        $participant->invalidate();

        return back()->with('success', 'Peserta ditolak.');
    }

    /**
     * Get WhatsApp link to send ticket
     */
    public function sendTicket(Participant $participant)
    {
        if ($participant->status_verifikasi !== 'Valid') {
            return back()->with('error', 'Peserta belum divalidasi.');
        }

        $ticketUrl = route('ticket.verify', $participant->token_hash);
        $waLink = $participant->getWhatsAppLink($ticketUrl);

        return redirect()->away($waLink);
    }

    /**
     * Mark ticket as redeemed
     */
    public function redeem(Participant $participant)
    {
        if (!$participant->redeem()) {
            return back()->with('error', 'Tiket tidak bisa di-redeem.');
        }

        return back()->with('success', 'Tiket berhasil di-redeem!');
    }

    /**
     * Delete participant
     */
    public function destroy(Participant $participant)
    {
        // If participant was validated, decrement event count
        if ($participant->status_verifikasi === 'Valid' || $participant->status_verifikasi === 'Redeem') {
            $participant->event->decrement('terisi');
        }

        $participant->delete();

        return back()->with('success', 'Peserta berhasil dihapus.');
    }
}
