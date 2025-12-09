<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        // Ambil order KHUSUS untuk Groomer yang sedang login
        // Dan HANYA untuk hari ini (agar fokus)
        $today = Carbon::today();
        
        $orders = Order::with('pets')
                    ->where('groomer_id', auth()->id()) // Filter by logged in user
                    ->whereDate('date', $today)         // Filter hari ini
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('time_slot', 'asc')       // Urutkan jam
                    ->get();

        return view('user.dashboard', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Pastikan order ini milik si groomer (Security check)
        if ($order->groomer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Update status (misal: Selesai)
        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status pekerjaan diperbarui!');
    }
}
