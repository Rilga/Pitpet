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
        $date = $request->input('date', now()->format('Y-m-d'));
        $groomers = User::where('role', 'user')->get();
    
        // HILANGKAN ->toArray()
        $orders = Order::whereDate('date', $date)
                        ->with('pets')
                        ->where('status', '!=', 'cancelled')
                        ->get(); 
    
        // Safety: Variabel Layout (Tetap wajib)
        $counts = [];
        $filterStatus = null; 
    
        return view('admin.schedule', [
            'date' => $date,
            'groomers' => $groomers,
            'orders' => $orders, // Collection object dikirim
            'counts' => $counts,
            'filterStatus' => $filterStatus 
        ]);
    }
    
    public function mapView()
    {
        // HILANGKAN ->toArray()
        $orders = Order::where('status', 'pending')
                        ->whereNotNull('customer_address')
                        ->get(); // Collection object
    
        // Safety: Variabel Layout (Tetap wajib)
        $counts = [];
        $filterStatus = null; 
    
        return view('admin.maps', [
            'orders' => $orders, // Collection object dikirim
            'counts' => $counts,
            'filterStatus' => $filterStatus 
        ]);
    }
}
