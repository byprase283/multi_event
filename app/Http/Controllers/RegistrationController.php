<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    /**
     * Show registration form
     */
    public function create(Event $event)
    {
        if (!$event->is_active || !$event->hasAvailableSlots()) {
            return redirect()->route('home')
                ->with('error', 'Event tidak tersedia atau kuota sudah habis.');
        }

        return view('registration.create', compact('event'));
    }

    /**
     * Store registration
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'nama' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'usia' => 'required|integer|min:1|max:120',
            'whatsapp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'ukuran_jersey' => 'nullable|string|max:15',
            'kode_voucher' => 'nullable|string|max:50',
            'bukti_bayar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $event = Event::findOrFail($validated['event_id']);

        if (!$event->hasAvailableSlots()) {
            return back()->with('error', 'Maaf, kuota event sudah habis.');
        }

        // Handle voucher
        $voucherCode = null;
        if (!empty($validated['kode_voucher'])) {
            $voucher = Voucher::where('kode', $validated['kode_voucher'])->first();
            if ($voucher && $voucher->isValid()) {
                $voucher->use();
                $voucherCode = $voucher->kode;
            }
        }

        // Upload bukti bayar
        $buktiPath = $request->file('bukti_bayar')->store('bukti-bayar', 'public');

        // Create participant
        $participant = Participant::create([
            'event_id' => $event->id,
            'nama' => $validated['nama'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'usia' => $validated['usia'],
            'whatsapp' => $validated['whatsapp'],
            'alamat' => $validated['alamat'],
            'ukuran_jersey' => $validated['ukuran_jersey'] ?? null,
            'kode_voucher' => $voucherCode,
            'bukti_bayar' => $buktiPath,
            'waktu_daftar' => now(),
            'status_verifikasi' => 'Pending',
        ]);

        return redirect()->route('registration.success', $participant->id);
    }

    /**
     * Show success page
     */
    public function success(Participant $participant)
    {
        return view('registration.success', compact('participant'));
    }

    /**
     * Check voucher validity (AJAX)
     */
    public function checkVoucher(Request $request)
    {
        $kode = $request->input('kode');
        $eventId = $request->input('event_id');

        $voucher = Voucher::where('kode', $kode)->first();

        if (!$voucher) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher tidak ditemukan.',
            ]);
        }

        if (!$voucher->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher sudah tidak berlaku atau kuota habis.',
            ]);
        }

        $event = Event::find($eventId);
        $hargaAsli = $event ? $event->harga : 0;
        $hargaSetelahDiskon = max(0, $hargaAsli - $voucher->nominal);

        return response()->json([
            'valid' => true,
            'nominal' => $voucher->nominal,
            'harga_asli' => $hargaAsli,
            'harga_diskon' => $hargaSetelahDiskon,
            'message' => 'Voucher valid! Diskon Rp ' . number_format($voucher->nominal, 0, ',', '.'),
        ]);
    }

    /**
     * Verify ticket by token
     */
    public function verifyTicket(string $token)
    {
        $participant = Participant::where('token_hash', $token)
            ->with('event')
            ->first();

        if (!$participant) {
            return view('ticket.invalid');
        }

        return view('ticket.verify', compact('participant'));
    }
}
