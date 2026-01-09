<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Voucher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_events' => Event::count(),
            'active_events' => Event::where('is_active', true)->count(),
            'total_participants' => Participant::count(),
            'pending_participants' => Participant::where('status_verifikasi', 'Pending')->count(),
            'valid_participants' => Participant::where('status_verifikasi', 'Valid')->count(),
            'redeemed_participants' => Participant::where('status_verifikasi', 'Redeem')->count(),
            'total_vouchers' => Voucher::count(),
            'active_vouchers' => Voucher::where('is_active', true)->count(),
        ];

        $recentParticipants = Participant::with('event')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $upcomingEvents = Event::where('is_active', true)
            ->where('tanggal_event', '>=', now())
            ->orderBy('tanggal_event')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentParticipants', 'upcomingEvents'));
    }
}
