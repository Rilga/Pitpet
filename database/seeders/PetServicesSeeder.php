<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PetService;
use App\Models\PetAddon;

class PetServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        /* ============================
           CAT SERVICES
        ============================ */
        $catServices = [
            'Dry Grooming' => [
                'price' => 50000,
                'desc'  => 'Nail Trimming, Ear Cleaning'
            ],
            'Daily Grooming' => [
                'price' => 100000,
                'desc'  => 'Nail Trimming, Ear Cleaning, Degreaser, Shampoo'
            ],
            'Mandi Kutu' => [
                'price' => 110000,
                'desc'  => 'Nail Trimming, Ear Cleaning, Degreaser, Flea & Tick Shampoo'
            ],
            'Mandi Jamur' => [
                'price' => 110000,
                'desc'  => 'Nail Trimming, Ear Cleaning, Degreaser, Anti Fungal Shampoo'
            ],
            'Mandi Kutu & Jamur' => [
                'price' => 150000,
                'desc'  => 'Nail Trimming, Ear Cleaning, Degreaser, Anti Fungal Shampoo, Flea & Tick Shampoo'
            ],
            'Full Package' => [
                'price' => 150000,
                'desc'  => 'Nail Trimming, Ear Cleaning, Degreaser, Deep Cleansing, Premium Shampoo, Final Touch'
            ],
        ];

        foreach ($catServices as $name => $data) {
            PetService::create([
                'pet_type'   => 'cat',
                'name'       => $name,
                'description'=> $data['desc'],
                'price_json' => json_encode(['price' => $data['price']]),
            ]);
        }


        /* ============================
           CAT ADDONS
        ============================ */
        $catAddons = [
            'Lion Cut' => [90000, 'Grooming model potong singa.'],
            'Styling' => [99000, 'Bentuk styling lucu dan artistik.'],
            'Additional Handling' => [50000, 'Untuk kucing galak/agresif.'],
            'Bulu Gimbal & Kusut' => [15000, 'Jika pengerjaan lebih dari 30 menit.'],
            'Cukur Bulu Perut' => [20000, 'Cukur area perut.'],
        ];

        foreach ($catAddons as $name => $data) {
            PetAddon::create([
                'pet_type' => 'cat',
                'name' => $name,
                'description' => $data[1],
                'price_json' => json_encode(['price' => $data[0]]),
            ]);
        }


        /* ============================
           DOG SERVICES
        ============================ */
        $dogServices = [
            'Dry Grooming' => [
                'prices' => ['S'=>75000, 'M'=>110000, 'L'=>135000, 'XL'=>155000],
                'desc' => 'Nail Trimming, Ear Cleaning, Hair Trimming'
            ],
            'Mandi Kutu' => [
                'prices' => ['S'=>139000, 'M'=>179000, 'L'=>199000, 'XL'=>229000],
                'desc' => 'Nail Trimming, Ear Cleaning, Degreaser, Flea & Tick Shampoo, Anal Gland'
            ],
            'Mandi Jamur' => [
                'prices' => ['S'=>139000, 'M'=>179000, 'L'=>199000, 'XL'=>229000],
                'desc' => 'Nail Trimming, Ear Cleaning, Degreaser, Anti Fungal Shampoo, Anal Gland'
            ],
            'Mandi Kutu & Jamur' => [
                'prices' => ['S'=>159000, 'M'=>199000, 'L'=>229000, 'XL'=>249000],
                'desc' => 'Nail Trimming, Ear Cleaning, Degreaser, Flea & Tick Shampoo, Anti Fungal Shampoo, Anal Gland'
            ],
            'Full Package' => [
                'prices' => ['S'=>179000, 'M'=>199000, 'L'=>229000, 'XL'=>249000],
                'desc' => 'Nail Trimming, Ear Cleaning, Degreaser, Deep Cleansing, Premium Shampoo, Final Touch, Anal Gland'
            ],
        ];

        foreach ($dogServices as $name => $data) {
            PetService::create([
                'pet_type' => 'dog',
                'name' => $name,
                'description' => $data['desc'],
                'price_json' => json_encode($data['prices'])
            ]);
        }


        /* ============================
           DOG ADDONS
        ============================ */
        $dogAddons = [
            'Full Shave Cut' => [
                'prices' => ['S'=>99000,'M'=>125000,'L'=>149000,'XL'=>199000],
                'desc' => 'Grooming model botak penuh.'
            ],
            'PitPet Styling' => [
                'prices' => ['price' => 350000],
                'desc' => 'Coat styling menggunakan styling scissors (tanyakan detail ke admin).'
            ],
            'Brushing Teeth' => [
                'prices' => ['price' => 0],
                'desc' => 'Pembersihan gigi (by request).'
            ],
            'Bulu Gimbal & Kusut' => [
                'prices' => ['price' => 30000],
                'desc' => 'Jika pengerjaan lebih dari 30 menit.'
            ],
            'Cukur Bulu Perut' => [
                'prices' => ['price' => 20000],
                'desc' => 'Cukur area perut.'
            ],
        ];

        foreach ($dogAddons as $name => $data) {
            PetAddon::create([
                'pet_type' => 'dog',
                'name' => $name,
                'description' => $data['desc'],
                'price_json' => json_encode($data['prices']),
            ]);
        }
    }
}
