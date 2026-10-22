<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\HotelFacility;
use Illuminate\Support\Str;

class HotelDataSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. HOTEL FACILITIES
        // ============================================================
        $facilityData = [
            ['name' => 'Swimming Pool',    'icon' => '🏊'],
            ['name' => 'Free WiFi',        'icon' => '📶'],
            ['name' => 'Gym & Fitness',    'icon' => '🏋️'],
            ['name' => 'Restaurant',       'icon' => '🍽️'],
            ['name' => 'Spa & Wellness',   'icon' => '💆'],
            ['name' => 'Breakfast',        'icon' => '🍳'],
            ['name' => 'Parking',          'icon' => '🅿️'],
            ['name' => 'Air Conditioning', 'icon' => '❄️'],
            ['name' => 'Room Service',     'icon' => '🛎️'],
            ['name' => 'Bar & Lounge',     'icon' => '🍸'],
            ['name' => 'Bathtub',          'icon' => '🛁'],
            ['name' => 'City View',        'icon' => '🌆'],
            ['name' => 'Sea View',         'icon' => '🌊'],
            ['name' => 'TV & Streaming',   'icon' => '📺'],
            ['name' => 'Mini Bar',         'icon' => '🥤'],
        ];

        foreach ($facilityData as $f) {
            HotelFacility::firstOrCreate(['name' => $f['name']], $f);
        }

        $facilities = HotelFacility::all()->keyBy('name');

        // ============================================================
        // 2. HOTELS + ROOMS
        // ============================================================
        $hotels = [
            // ── Jakarta ───────────────────────────────────────────────
            [
                'name'        => 'The Langham Jakarta',
                'city'        => 'Jakarta',
                'address'     => 'Jl. Sudirman No. 1, Jakarta Pusat',
                'country'     => 'Indonesia',
                'star_rating' => 5,
                'description' => 'Hotel bintang 5 mewah di pusat kota Jakarta dengan pemandangan skyline yang memukau dan fasilitas kelas dunia.',
                'phone'       => '+62 21 5555 1001',
                'email'       => 'info@langhamjakarta.com',
                'rooms'       => [
                    ['name' => 'Deluxe Room', 'type' => 'deluxe', 'price' => 1800000, 'capacity' => 2, 'total' => 50,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Mini Bar', 'Room Service']],
                    ['name' => 'Premier Suite', 'type' => 'suite', 'price' => 4500000, 'capacity' => 2, 'total' => 20,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Mini Bar', 'Room Service', 'Bathtub', 'City View']],
                    ['name' => 'Grand Suite', 'type' => 'presidential', 'price' => 12000000, 'capacity' => 4, 'total' => 5,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Mini Bar', 'Room Service', 'Bathtub', 'City View', 'Bar & Lounge']],
                ],
            ],
            [
                'name'        => 'Aryaduta Jakarta',
                'city'        => 'Jakarta',
                'address'     => 'Jl. Prapatan No. 44-48, Jakarta Pusat',
                'country'     => 'Indonesia',
                'star_rating' => 5,
                'description' => 'Hotel bisnis premium dengan lokasi strategis di jantung Jakarta, dekat pusat perbelanjaan dan kawasan bisnis.',
                'phone'       => '+62 21 5555 1002',
                'email'       => 'info@aryadutajakarta.com',
                'rooms'       => [
                    ['name' => 'Superior Room', 'type' => 'standard', 'price' => 950000, 'capacity' => 2, 'total' => 80,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming']],
                    ['name' => 'Deluxe City View', 'type' => 'deluxe', 'price' => 1500000, 'capacity' => 2, 'total' => 40,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'City View', 'Mini Bar']],
                    ['name' => 'Executive Suite', 'type' => 'suite', 'price' => 3800000, 'capacity' => 3, 'total' => 15,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'City View', 'Mini Bar', 'Bathtub', 'Room Service']],
                ],
            ],
            [
                'name'        => 'Fave Hotel Gatot Subroto',
                'city'        => 'Jakarta',
                'address'     => 'Jl. Gatot Subroto No. 23, Jakarta Selatan',
                'country'     => 'Indonesia',
                'star_rating' => 3,
                'description' => 'Hotel budget nyaman dan modern di kawasan bisnis Gatot Subroto, pilihan tepat untuk perjalanan bisnis.',
                'phone'       => '+62 21 5555 1003',
                'email'       => 'info@favegatsu.com',
                'rooms'       => [
                    ['name' => 'Standard Room', 'type' => 'standard', 'price' => 350000, 'capacity' => 2, 'total' => 100,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming']],
                    ['name' => 'Deluxe Room', 'type' => 'deluxe', 'price' => 550000, 'capacity' => 2, 'total' => 40,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Breakfast']],
                ],
            ],

            // ── Bali ──────────────────────────────────────────────────
            [
                'name'        => 'The Mulia Bali',
                'city'        => 'Bali',
                'address'     => 'Jl. Raya Nusa Dua Selatan, Nusa Dua, Bali',
                'country'     => 'Indonesia',
                'star_rating' => 5,
                'description' => 'Resort mewah tepi pantai di Nusa Dua Bali dengan kolam renang infinity dan pemandangan Samudra Hindia yang spektakuler.',
                'phone'       => '+62 361 5555 2001',
                'email'       => 'info@themuliabali.com',
                'rooms'       => [
                    ['name' => 'Ocean Suite', 'type' => 'suite', 'price' => 5500000, 'capacity' => 2, 'total' => 30,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Sea View', 'Bathtub', 'Mini Bar', 'Room Service']],
                    ['name' => 'Villa Pool', 'type' => 'presidential', 'price' => 15000000, 'capacity' => 4, 'total' => 10,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Sea View', 'Bathtub', 'Mini Bar', 'Room Service', 'Swimming Pool', 'Bar & Lounge']],
                ],
            ],
            [
                'name'        => 'Kuta Paradiso Hotel',
                'city'        => 'Bali',
                'address'     => 'Jl. Kartika Plaza, Kuta, Bali',
                'country'     => 'Indonesia',
                'star_rating' => 4,
                'description' => 'Hotel nyaman di Kuta Bali, berjarak 5 menit dari pantai Kuta dan dekat dengan pusat perbelanjaan Beachwalk.',
                'phone'       => '+62 361 5555 2002',
                'email'       => 'info@kutaparadiso.com',
                'rooms'       => [
                    ['name' => 'Superior Room', 'type' => 'standard', 'price' => 680000, 'capacity' => 2, 'total' => 60,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Breakfast']],
                    ['name' => 'Deluxe Pool View', 'type' => 'deluxe', 'price' => 950000, 'capacity' => 2, 'total' => 30,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Breakfast', 'Swimming Pool']],
                    ['name' => 'Family Room', 'type' => 'family', 'price' => 1500000, 'capacity' => 4, 'total' => 15,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Breakfast', 'Swimming Pool', 'Room Service']],
                ],
            ],
            [
                'name'        => 'Ibis Bali Kuta',
                'city'        => 'Bali',
                'address'     => 'Jl. Dewi Sri No. 5, Legian, Bali',
                'country'     => 'Indonesia',
                'star_rating' => 3,
                'description' => 'Hotel budget terpercaya di Legian Bali dengan akses mudah ke pantai dan kehidupan malam Kuta.',
                'phone'       => '+62 361 5555 2003',
                'email'       => 'info@ibisbali.com',
                'rooms'       => [
                    ['name' => 'Standard Room', 'type' => 'standard', 'price' => 450000, 'capacity' => 2, 'total' => 120,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming']],
                    ['name' => 'Deluxe Room', 'type' => 'deluxe', 'price' => 650000, 'capacity' => 2, 'total' => 50,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Swimming Pool']],
                ],
            ],

            // ── Surabaya ──────────────────────────────────────────────
            [
                'name'        => 'JW Marriott Surabaya',
                'city'        => 'Surabaya',
                'address'     => 'Jl. Embong Malang No. 85-89, Surabaya',
                'country'     => 'Indonesia',
                'star_rating' => 5,
                'description' => 'Hotel bisnis mewah di pusat Surabaya dengan fasilitas lengkap dan layanan kelas dunia.',
                'phone'       => '+62 31 5555 3001',
                'email'       => 'info@marriottsurabaya.com',
                'rooms'       => [
                    ['name' => 'Deluxe Room', 'type' => 'deluxe', 'price' => 1600000, 'capacity' => 2, 'total' => 60,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Mini Bar', 'Room Service']],
                    ['name' => 'Junior Suite', 'type' => 'suite', 'price' => 3200000, 'capacity' => 2, 'total' => 20,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Mini Bar', 'Room Service', 'Bathtub', 'City View']],
                ],
            ],
            [
                'name'        => 'Novotel Surabaya',
                'city'        => 'Surabaya',
                'address'     => 'Jl. Ngagel No. 173-175, Surabaya',
                'country'     => 'Indonesia',
                'star_rating' => 4,
                'description' => 'Hotel modern dengan fasilitas lengkap di kawasan Ngagel Surabaya, ideal untuk wisata dan bisnis.',
                'phone'       => '+62 31 5555 3002',
                'email'       => 'info@novotelsurabaya.com',
                'rooms'       => [
                    ['name' => 'Superior Room', 'type' => 'standard', 'price' => 750000, 'capacity' => 2, 'total' => 80,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Breakfast']],
                    ['name' => 'Deluxe Room', 'type' => 'deluxe', 'price' => 1100000, 'capacity' => 2, 'total' => 40,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Breakfast', 'Mini Bar', 'Swimming Pool']],
                    ['name' => 'Family Suite', 'type' => 'family', 'price' => 2200000, 'capacity' => 4, 'total' => 10,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Breakfast', 'Mini Bar', 'Swimming Pool', 'Room Service']],
                ],
            ],

            // ── Yogyakarta ────────────────────────────────────────────
            [
                'name'        => 'Royal Ambarrukmo Yogyakarta',
                'city'        => 'Yogyakarta',
                'address'     => 'Jl. Laksda Adisucipto No. 81, Yogyakarta',
                'country'     => 'Indonesia',
                'star_rating' => 5,
                'description' => 'Hotel bersejarah dan mewah di Yogyakarta dengan sentuhan budaya Jawa yang kental dan taman yang indah.',
                'phone'       => '+62 274 5555 4001',
                'email'       => 'info@ambarrukmo.com',
                'rooms'       => [
                    ['name' => 'Deluxe Garden View', 'type' => 'deluxe', 'price' => 1200000, 'capacity' => 2, 'total' => 40,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Breakfast', 'Swimming Pool']],
                    ['name' => 'Junior Suite', 'type' => 'suite', 'price' => 2800000, 'capacity' => 2, 'total' => 15,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Breakfast', 'Swimming Pool', 'Bathtub', 'Room Service']],
                ],
            ],
            [
                'name'        => 'Ibis Styles Yogyakarta',
                'city'        => 'Yogyakarta',
                'address'     => 'Jl. Dagen No. 6, Malioboro, Yogyakarta',
                'country'     => 'Indonesia',
                'star_rating' => 3,
                'description' => 'Hotel budget ceria dan modern di kawasan Malioboro, lokasi sempurna untuk wisata budaya Yogyakarta.',
                'phone'       => '+62 274 5555 4002',
                'email'       => 'info@ibisstylesyogya.com',
                'rooms'       => [
                    ['name' => 'Standard Room', 'type' => 'standard', 'price' => 380000, 'capacity' => 2, 'total' => 100,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Breakfast']],
                    ['name' => 'Deluxe Room', 'type' => 'deluxe', 'price' => 550000, 'capacity' => 2, 'total' => 40,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Breakfast', 'Swimming Pool']],
                ],
            ],

            // ── Singapura ─────────────────────────────────────────────
            [
                'name'        => 'Marina Bay Sands',
                'city'        => 'Singapura',
                'address'     => '10 Bayfront Avenue, Singapore 018956',
                'country'     => 'Singapura',
                'star_rating' => 5,
                'description' => 'Ikon Singapura dengan infinity pool di atas gedung 57 lantai dan pemandangan kota yang tak tertandingi.',
                'phone'       => '+65 6688 8888',
                'email'       => 'info@marinabaysands.com',
                'rooms'       => [
                    ['name' => 'Deluxe Room', 'type' => 'deluxe', 'price' => 4500000, 'capacity' => 2, 'total' => 200,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Mini Bar', 'City View']],
                    ['name' => 'Club Room', 'type' => 'suite', 'price' => 8500000, 'capacity' => 2, 'total' => 80,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Mini Bar', 'City View', 'Bathtub', 'Room Service', 'Bar & Lounge']],
                    ['name' => 'Chairman Suite', 'type' => 'presidential', 'price' => 35000000, 'capacity' => 6, 'total' => 5,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Mini Bar', 'City View', 'Bathtub', 'Room Service', 'Bar & Lounge', 'Swimming Pool', 'Spa & Wellness']],
                ],
            ],

            // ── Kuala Lumpur ──────────────────────────────────────────
            [
                'name'        => 'Mandarin Oriental Kuala Lumpur',
                'city'        => 'Kuala Lumpur',
                'address'     => 'Kuala Lumpur City Centre, 50088 KL',
                'country'     => 'Malaysia',
                'star_rating' => 5,
                'description' => 'Hotel mewah dengan pemandangan langsung ke Menara Petronas, simbol keanggunan di jantung Kuala Lumpur.',
                'phone'       => '+60 3 2380 8888',
                'email'       => 'info@mokul.com',
                'rooms'       => [
                    ['name' => 'Deluxe KLCC View', 'type' => 'deluxe', 'price' => 3200000, 'capacity' => 2, 'total' => 100,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Mini Bar', 'City View', 'Room Service']],
                    ['name' => 'Grand Suite', 'type' => 'suite', 'price' => 8000000, 'capacity' => 3, 'total' => 30,
                     'facilities' => ['Free WiFi', 'Air Conditioning', 'TV & Streaming', 'Mini Bar', 'City View', 'Room Service', 'Bathtub', 'Spa & Wellness']],
                ],
            ],
        ];

        // ============================================================
        // 3. INSERT HOTELS + ROOMS
        // ============================================================
        foreach ($hotels as $hotelData) {
            $hotel = Hotel::firstOrCreate(
                ['name' => $hotelData['name']],
                [
                    'slug'        => Str::slug($hotelData['name']),
                    'city'        => $hotelData['city'],
                    'address'     => $hotelData['address'],
                    'country'     => $hotelData['country'],
                    'star_rating' => $hotelData['star_rating'],
                    'description' => $hotelData['description'],
                    'phone'       => $hotelData['phone'],
                    'email'       => $hotelData['email'],
                    'is_available'=> true,
                ]
            );

            foreach ($hotelData['rooms'] as $roomData) {
                $room = HotelRoom::firstOrCreate(
                    ['hotel_id' => $hotel->id, 'name' => $roomData['name']],
                    [
                        'type'           => $roomData['type'],
                        'price_per_night'=> $roomData['price'],
                        'capacity'       => $roomData['capacity'],
                        'total_rooms'    => $roomData['total'],
                        'is_available'   => true,
                    ]
                );

                $facilityIds = collect($roomData['facilities'])
                    ->map(fn($name) => $facilities->get($name)?->id)
                    ->filter()
                    ->toArray();

                $room->facilities()->syncWithoutDetaching($facilityIds);
            }
        }

        $this->command->info('✅ Hotel Seeder selesai!');
        $this->command->info('Fasilitas : ' . HotelFacility::count());
        $this->command->info('Hotels    : ' . Hotel::count());
        $this->command->info('Rooms     : ' . HotelRoom::count());
    }
}