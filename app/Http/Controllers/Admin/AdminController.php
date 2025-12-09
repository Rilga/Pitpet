<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    // --- VARIABEL GLOBAL YANG DIBUTUHKAN LAYOUT ---
    // Variabel ini dibutuhkan oleh x-app-layout jika ia memanggil badge count/filter dari dashboard.
    private function getLayoutDependencies()
    {
        return [
            'counts' => [],
            'filterStatus' => null
        ];
    }
    // ---------------------------------------------
    
    public function index(Request $request)
    {
        $filterStatus = $request->query('status');

        // Query Dashboard Utama
        $query = Order::query()->with('groomer');
        
        if ($filterStatus && $filterStatus !== 'all') {
            $query->where('status', $filterStatus);
        }

        if (!$filterStatus || $filterStatus === 'all') {
            $query->orderByRaw("FIELD(status, 'pending', 'confirmed', 'completed', 'cancelled', 'rejected')");
            $query->orderBy('created_at', 'desc'); 
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->paginate(10);
        
        // Ambil total order per status
        $counts = Order::select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->all();

        return view('admin.dashboard', [
            'orders' => $orders,
            'counts' => $counts,
            'filterStatus' => $filterStatus
        ]);
    }

    public function edit(Order $order)
    {
        $groomers = User::where('role', 'user')->get();

        // Gabungkan data dengan variabel layout yang dibutuhkan
        return view('admin.order.edit', array_merge([
            'order' => $order,
            'groomers' => $groomers
        ], $this->getLayoutDependencies())); 
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:pending,confirmed,completed,cancelled,rejected',
            'groomer_id' => 'nullable|exists:users,id', 
        ]);

        $timeSlotString = $request->start_time . '-' . $request->end_time;

        $order->update([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'date' => $request->date,
            'time_slot' => $timeSlotString,
            'status' => $request->status,
            'groomer_id' => $request->groomer_id,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Order berhasil diperbarui!');
    }
    
        public function schedule(Request $request)
    {
        // 1. Ambil tanggal dari input, atau default hari ini
        $date = $request->input('date', now()->format('Y-m-d'));

        // 2. Ambil semua Groomer
        $groomers = User::where('role', 'user')->get();

        // 3. Ambil Order pada tanggal tersebut (Eager Loading untuk performa)
        // Kita ambil semua order di hari itu agar tidak query berulang-ulang di view
        $orders = Order::whereDate('date', $date)
                       ->with('pets') // Ambil detail hewan untuk kolom 'Keterangan'
                       ->where('status', '!=', 'cancelled')
                       ->get();

        return view('admin.schedule', [
            'date' => $date,
            'groomers' => $groomers,
            'orders' => $orders
        ]);
    }

    public function mapView()
    {
        $orders = Order::where('status', 'pending')
                    ->whereNotNull('customer_address')
                    ->with('groomer') 
                    ->get();

        return view('admin.maps', compact('orders'));
    }
}
