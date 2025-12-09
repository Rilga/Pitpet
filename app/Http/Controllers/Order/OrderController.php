<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderPet;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    // ==========================================
    // 1. MASTER DATA (HARDCODED SERVICES)
    // ==========================================

    private $catServices = [
        'Dry Grooming' => 50000,
        'Daily Grooming' => 100000,
        'Mandi Kutu' => 110000,
        'Mandi Jamur' => 110000,
        'Mandi Kutu & Jamur' => 150000,
        'Full Package' => 150000,
    ];

    private $catDescriptions = [
        'Dry Grooming' => 'Nail Trimming, Ear Cleaning',
        'Daily Grooming' => 'Nail Trimming, Ear Cleaning, Degreaser, Shampoo',
        'Mandi Kutu' => 'Nail Trimming, Ear Cleaning, Degreaser, Flea & Tick Shampoo',
        'Mandi Jamur' => 'Nail Trimming, Ear Cleaning, Degreaser, Anti Fungal Shampoo',
        'Mandi Kutu & Jamur' => 'Nail Trimming, Ear Cleaning, Degreaser, Anti Fungal Shampoo, Flea & Tick Shampoo',
        'Full Package' => 'Nail Trimming, Ear Cleaning, Degreaser, Deep Cleansing, Premium Shampoo, Final Touch',
    ];

    private $catAddons = [
        'Lion Cut' => 90000,
        'Styling' => 99000,
        'Additional Handling' => 50000,
        'Bulu Gimbal & Kusut' => 15000,
        'Cukur Bulu Perut' => 20000,
    ];

    private $catAddonDescriptions = [
        'Lion Cut' => 'Grooming model potong singa.',
        'Styling' => 'Bentuk styling lucu dan artistik.',
        'Additional Handling' => 'Untuk kucing galak/agresif.',
        'Bulu Gimbal & Kusut' => 'Jika pengerjaan lebih dari 30 menit.',
        'Cukur Bulu Perut' => 'Cukur area perut.',
    ];

    // DOG SERVICES (price per size)
    private $dogServices = [
        'Dry Grooming' => ['S'=>75000, 'M'=>110000, 'L'=>135000, 'XL'=>155000],
        'Mandi Kutu' => ['S'=>139000, 'M'=>179000, 'L'=>199000, 'XL'=>229000],
        'Mandi Jamur' => ['S'=>139000, 'M'=>179000, 'L'=>199000, 'XL'=>229000],
        'Mandi Kutu & Jamur' => ['S'=>159000, 'M'=>199000, 'L'=>229000, 'XL'=>249000],
        'Full Package' => ['S'=>179000, 'M'=>199000, 'L'=>229000, 'XL'=>249000],
    ];

    private $dogAddons = [
        'Full Shave Cut' => ['S'=>99000,'M'=>125000,'L'=>149000,'XL'=>199000],
        'PitPet Styling' => 350000,
        'Brushing Teeth' => 0, // Free or Request
        'Bulu Gimbal & Kusut' => 30000,
        'Cukur Bulu Perut' => 20000,
    ];

    private $dogDescriptions = [
        'Dry Grooming' => 'Nail Trimming, Ear Cleaning, Hair Trimming',
        'Mandi Kutu' => 'Nail Trimming, Ear Cleaning, Degreaser, Flea & Tick Shampoo, Anal Gland',
        'Mandi Jamur' => 'Nail Trimming, Ear Cleaning, Degreaser, Anti Fungal Shampoo, Anal Gland',
        'Mandi Kutu & Jamur' => 'Nail Trimming, Ear Cleaning, Degreaser, Flea & Tick Shampoo, Anti Fungal Shampoo, Anal Gland',
        'Full Package' => 'Nail Trimming, Ear Cleaning, Degreaser, Deep Cleansing, Premium Shampoo, Final Touch, Anal Gland',
    ];

    private $dogAddonDescriptions = [
        'Full Shave Cut' => 'Grooming model botak penuh.',
        'PitPet Styling' => 'Coat styling menggunakan styling scissors (tanyakan detail ke admin).',
        'Brushing Teeth' => 'Pembersihan gigi (by request).',
        'Bulu Gimbal & Kusut' => 'Jika pengerjaan lebih dari 30 menit.',
        'Cukur Bulu Perut' => 'Cukur area perut.',
    ];

    // ==========================================
    // 2. VIEW & API METHODS
    // ==========================================

    public function create()
    {
        return view('order.create', [
            'catServices' => $this->catServices,
            'catDescriptions' => $this->catDescriptions,
            'catAddons' => $this->catAddons,
            'catAddonDescriptions' => $this->catAddonDescriptions,
            'dogServices' => $this->dogServices,
            'dogDescriptions' => $this->dogDescriptions,
            'dogAddons' => $this->dogAddons,
            'dogAddonDescriptions' => $this->dogAddonDescriptions,
        ]);
    }

    /**
     * API untuk mengambil status slot waktu di tanggal tertentu.
     * Digunakan oleh Frontend (Visual Grid).
     */
    public function getSlotsStatus(Request $request)
    {
        $date = $request->query('date');
        if (!$date) return response()->json([]);

        // 1. Ambil order aktif (bukan cancelled/rejected)
        $orders = Order::where('date', $date)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->get();

        // 2. Definisi Jam Operasional (Start time slots)
        // Toko tutup jam 17:00, jadi slot terakhir booking (asumsi durasi 1 jam) adalah 16:00
        $operatingHours = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'];
        
        $occupancy = [];

        // 3. Hitung Okupansi
        foreach ($orders as $order) {
            $hours = $this->orderOccupiedHours($order->time_slot);
            foreach ($hours as $h) {
                if (!isset($occupancy[$h])) $occupancy[$h] = 0;
                $occupancy[$h]++;
            }
        }

        // 4. Format Data JSON
        $slots = [];
        $maxGroomer = 3;

        foreach ($operatingHours as $h) {
            $count = $occupancy[$h] ?? 0;
            $isFull = $count >= $maxGroomer;
            
            $slots[] = [
                'time' => $h,
                'count' => $count,
                'is_full' => $isFull,
                'label' => $isFull ? 'Penuh' : ($maxGroomer - $count) . ' slot'
            ];
        }

        return response()->json($slots);
    }

    public function store(Request $r)
    {
        // A. Validasi Dasar
        $r->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_email' => 'nullable|email',
            'customer_address' => 'required|string',
            'date' => 'required|date',
            'distance_km' => 'nullable|numeric',  // NEW: Jarak dari form
            'transport_fee' => 'nullable|integer',  // NEW: Biaya Transport dari form
            'latitude' => 'nullable|numeric',    // NEW: Koordinat jika digunakan
            'longitude' => 'nullable|numeric',
            'start_time' => 'required', // Format H:i
            'cats' => 'nullable|array',
            'dogs' => 'nullable|array',
        ]);

        // B. Hitung Durasi (Total Hewan)
        $catCount = isset($r->cats) ? count($r->cats) : 0;
        $dogCount = isset($r->dogs) ? count($r->dogs) : 0;
        $totalPets = $catCount + $dogCount;
        $duration = $totalPets;

        if ($totalPets < 1) return back()->withInput()->withErrors(['pets' => 'Silakan pilih minimal 1 hewan.']);
        if ($totalPets > 5) return back()->withInput()->withErrors(['pets' => 'Maksimal 5 hewan per booking.']);

        // C. Cek Ketersediaan (Validasi Server-Side untuk mencegah Race Condition)
        $check = $this->isSlotAvailable($r->date, $r->start_time, $duration);
        
        if (!$check['available']) {
            return back()->withInput()->withErrors(['start_time' => $check['message']]);
        }

        // D. Hitung Waktu & Buat Order
        $start = Carbon::createFromFormat('H:i', $r->start_time);
        $end = (clone $start)->addHours($duration);
        $timeSlotRange = $start->format('H:i') . '-' . $end->format('H:i');

        $transportFee = (int)($r->transport_fee ?? 0);

        $order = Order::create([
            'customer_name' => $r->customer_name,
            'customer_phone' => $r->customer_phone,
            'customer_email' => $r->customer_email ?? null,
            'customer_address' => $r->customer_address,
            'date' => $r->date,
            'time_slot' => $timeSlotRange,
            'distance_km' => $r->distance_km ?? 0,
            'transport_fee' => $transportFee, // Simpan biaya transport dari frontend
            'latitude' => $r->latitude ?? null,
            'longitude' => $r->longitude ?? null,
            'subtotal' => 0,
            'total' => 0,
            'status' => 'pending', 
        ]);

        $subtotal = 0;

        // E. Simpan Detail Kucing
        if (isset($r->cats) && is_array($r->cats)) {
            foreach ($r->cats as $c) {
                if (empty($c['service'])) continue;
                $service = $c['service'];
                $price = $this->catServices[$service] ?? 0;

                OrderPet::create([
                    'order_id' => $order->id,
                    'pet_type' => 'cat',
                    'service_name' => $service,
                    'service_price' => $price,
                    'quantity' => 1
                ]);
                $subtotal += $price;

                if (!empty($c['addons']) && is_array($c['addons'])) {
                    foreach ($c['addons'] as $addon) {
                        if (!isset($this->catAddons[$addon])) continue;
                        $addonPrice = $this->catAddons[$addon];
                        OrderPet::create([
                            'order_id' => $order->id,
                            'pet_type' => 'cat',
                            'service_name' => $addon,
                            'service_price' => $addonPrice,
                            'quantity' => 1
                        ]);
                        $subtotal += $addonPrice;
                    }
                }
            }
        }

        // F. Simpan Detail Anjing
        if (isset($r->dogs) && is_array($r->dogs)) {
            foreach ($r->dogs as $d) {
                if (empty($d['service']) || empty($d['size'])) continue;
                $service = $d['service'];
                $size = $d['size'];
                $price = $this->dogServices[$service][$size] ?? 0;

                OrderPet::create([
                    'order_id' => $order->id,
                    'pet_type' => 'dog',
                    'service_name' => $service,
                    'dog_size' => $size,
                    'service_price' => $price,
                    'quantity' => 1
                ]);
                $subtotal += $price;

                if (!empty($d['addons']) && is_array($d['addons'])) {
                    foreach ($d['addons'] as $addon) {
                        $addonPrice = 0;
                        if (isset($this->dogAddons[$addon])) {
                            $val = $this->dogAddons[$addon];
                            if (is_array($val) && isset($val[$size])) $addonPrice = $val[$size];
                            elseif (is_numeric($val)) $addonPrice = $val;
                        }
                        
                        // Khusus Brushing Teeth (0 rupiah) tetap disimpan
                        if ($addonPrice > 0 || $addon === 'Brushing Teeth') {
                            OrderPet::create([
                                'order_id' => $order->id,
                                'pet_type' => 'dog',
                                'service_name' => $addon,
                                'service_price' => $addonPrice,
                                'quantity' => 1
                            ]);
                            $subtotal += $addonPrice;
                        }
                    }
                }
            }
        }

        // G. Update Harga Transport (Hitung ulang di backend untuk keamanan, atau ambil dari frontend)
        // Disini saya set default 0 atau ambil logic geocode jika diperlukan. 
        // Agar konsisten, kita update total berdasarkan subtotal dulu.
            $order->update([
            'subtotal' => $subtotal,
            // TOTAL = Subtotal Jasa + Biaya Transport
            'total' => $subtotal + $transportFee
        ]);

        return redirect()->route('order.create')->with('success', 'Booking berhasil dibuat!' );
    }


    // ==========================================
    // 3. HELPER METHODS
    // ==========================================

    private function isSlotAvailable($date, $startTime, $duration)
    {
        $start = Carbon::createFromFormat('H:i', $startTime);
        $end = (clone $start)->addHours($duration);
        $limit = Carbon::createFromFormat('H:i', '17:00');

        if ($end->gt($limit)) {
            return [
                'available' => false,
                'message' => "Waktu selesai ({$end->format('H:i')}) melebihi jam operasional 17:00."
            ];
        }

        // Generate array jam yang diminta user
        $requestedHours = [];
        for ($i = 0; $i < $duration; $i++) {
            $requestedHours[] = (clone $start)->addHours($i)->format('H:i');
        }

        // Cek DB
        $orders = Order::where('date', $date)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->get();

        $occupancy = [];
        foreach ($orders as $order) {
            $orderHours = $this->orderOccupiedHours($order->time_slot);
            foreach ($orderHours as $h) {
                if (!isset($occupancy[$h])) $occupancy[$h] = 0;
                $occupancy[$h]++;
            }
        }

        // Cek limit 3 groomer
        foreach ($requestedHours as $h) {
            if (($occupancy[$h] ?? 0) >= 3) {
                return [
                    'available' => false,
                    'message' => "Jam {$h} sudah penuh (3/3 antrian). Mohon pilih waktu lain."
                ];
            }
        }

        return ['available' => true];
    }

    private function orderOccupiedHours(string $timeSlot)
    {
        $timeSlot = trim($timeSlot);
        if (strpos($timeSlot, '-') === false) return [ $timeSlot ];

        [$startStr, $endStr] = explode('-', $timeSlot);
        try {
            $start = Carbon::createFromFormat('H:i', trim($startStr));
            $end = Carbon::createFromFormat('H:i', trim($endStr));
        } catch (\Exception $e) {
            return [];
        }

        $hours = [];
        $cursor = clone $start;
        while ($cursor->lt($end)) {
            $hours[] = $cursor->format('H:i');
            $cursor->addHour();
        }
        return $hours;
    }

    public function geocode(Request $request)
    {
        $address = $request->address;
        $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($address);
        $response = Http::withHeaders(['User-Agent' => 'PitPetApp/1.0'])->get($url);
        return $response->json();
    }
}