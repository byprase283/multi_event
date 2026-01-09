<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function index()
    {
        return view('admin.scanner.index');
    }

    public function process(Request $request)
    {
        $request->validate([
            'token_hash' => 'required|string'
        ]);

        $participant = Participant::where('token_hash', $request->token_hash)->first();

        if (!$participant) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan!'
            ]);
        }

        if ($participant->status_verifikasi === 'Redeem') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah digunakan sebelumnya!',
                'participant' => $participant,
                'event' => $participant->event
            ]);
        }

        if ($participant->status_verifikasi !== 'Valid') {
            return response()->json([
                'success' => false,
                'message' => 'Status tiket tidak valid (' . $participant->status_verifikasi . ')',
                'participant' => $participant,
                'event' => $participant->event
            ]);
        }

        // Update status to Redeem
        $participant->update([
            'status_verifikasi' => 'Redeem'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil diredeem!',
            'participant' => $participant,
            'event' => $participant->event
        ]);
    }
}
