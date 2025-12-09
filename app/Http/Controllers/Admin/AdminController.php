<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

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

        $orders = Order::whereDate('date', $date)
                        ->with('pets') 
                        ->where('status', '!=', 'cancelled')
                        ->get();

        // ----------------------------------------------------
        // FIX MEMORI: PINDAHKAN LOGIKA JADWAL DARI VIEW KE CONTROLLER
        // ----------------------------------------------------
        $scheduleData = $groomers->map(function($groomer) use ($orders, $date) {
            $groomerOrders = $orders->filter(fn($o) => $o->groomer_id == $groomer->id)
                                    ->sortBy(fn($o) => explode('-', $o->time_slot)[0]);
            
            $schedule = [];
            $skipHours = [];
            
            for ($hour = 8; $hour <= 16; $hour++) {
                $timeString = sprintf('%02d:00', $hour);
                
                if (isset($skipHours[$hour])) {
                    $schedule[] = ['time' => $timeString, 'type' => 'skipped', 'order' => null];
                    continue; 
                }

                $order = $groomerOrders->first(function($o) use ($timeString) {
                    $start = explode('-', $o->time_slot)[0];
                    return trim($start) == $timeString;
                });

                if ($order) {
                    [$startStr, $endStr] = explode('-', $order->time_slot);
                    // Pastikan try-catch untuk Carbon
                    try {
                        $startTime = Carbon::createFromFormat('H:i', trim($startStr));
                        $endTime = Carbon::createFromFormat('H:i', trim($endStr));
                        $duration = $startTime->diffInHours($endTime);
                    } catch (\Exception $e) {
                        $duration = 1; // Default jika error
                    }
                    
                    $rowspan = $duration > 0 ? $duration : 1;
                    for($h = 1; $h < $rowspan; $h++) { $skipHours[$hour + $h] = true; }
                    
                    $addOnNames = [ 
                        'Lion Cut', 'Styling', 'Additional Handling', 'Bulu Gimbal & Kusut', 'Cukur Bulu Perut',
                        'Full Shave Cut', 'PitPet Styling', 'Brushing Teeth'
                    ];
                    // Konversi ke array sebelum filter untuk stabilitas memori
                    $petsArray = $order->pets->toArray();

                    $mainServicesList = collect($petsArray)->filter(fn($pet) => !in_array($pet['service_name'], $addOnNames))->toArray();
                    $addOnsList = collect($petsArray)->filter(fn($pet) => in_array($pet['service_name'], $addOnNames));
                    
                    $notes = [];
                    $cats = collect($mainServicesList)->where('pet_type', 'cat')->count();
                    $dogs = collect($mainServicesList)->where('pet_type', 'dog')->count();
                    
                    if($cats > 0) $notes[] = "$cats Kucing";
                    if($dogs > 0) $notes[] = "$dogs Anjing";
                    
                    $schedule[] = [
                        'time' => $timeString, 
                        'type' => 'booked', 
                        'rowspan' => $rowspan,
                        'order' => $order->toArray(), // Kirim Order sebagai Array
                        'services' => [
                            'main' => $mainServicesList,
                            'addons' => $addOnsList->pluck('service_name')->implode(', '),
                            'notes' => implode(', ', $notes),
                        ]
                    ];
                } else {
                     $schedule[] = ['time' => $timeString, 'type' => 'available', 'order' => null];
                }
            }
            
            return [
                'groomer' => $groomer,
                'schedule' => $schedule,
            ];
        });
        
        return view('admin.schedule', array_merge([
            'date' => $date,
            'scheduleData' => $scheduleData, // <-- DATA BARU
        ], $this->getLayoutDependencies()));
    }

    public function mapView()
    {
        $orders = Order::where('status', 'pending')
                    ->whereNotNull('customer_address')
                    ->with('groomer') // <-- AKTIFKAN KEMBALI INI
                    ->get(); 

        return view('admin.maps', array_merge([
            'orders' => $orders
        ], $this->getLayoutDependencies()));
    }
}
