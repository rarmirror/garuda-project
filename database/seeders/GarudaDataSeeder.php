<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Facility;
use App\Models\Flight;
use App\Models\FlightSegment;
use App\Models\FlightClass;
use App\Models\FlightSeat;
use App\Models\PromoCode;
use Carbon\Carbon;

class GarudaDataSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. AIRLINES — tambah 7, sudah ada: Singapore, Ana, Emirates
        // ============================================================
        
        $airlines = [
            ['name' => 'Garuda Indonesia', 'code' => 'GA',  'logo' => 'airline/garuda-indonesia.png'],
            ['name' => 'Lion Air',         'code' => 'JT',  'logo' => 'airline/lion-air.png'],
            ['name' => 'Batik Air',        'code' => 'ID',  'logo' => 'airline/batik-air.png'],
            ['name' => 'Citilink',         'code' => 'QG',  'logo' => 'airline/citilink.png'],
            ['name' => 'Air Asia',         'code' => 'QZ',  'logo' => 'airline/air-asia.png'],
            ['name' => 'Qatar Airways',    'code' => 'QR',  'logo' => 'airline/qatar-airways.png'],
            ['name' => 'Korean Air',       'code' => 'KE',  'logo' => 'airline/korean-air.png'],
        ];

        foreach ($airlines as $airline) {
            Airline::firstOrCreate(
                ['name' => $airline['name']],
                [
                    'code' => $airline['code'],
                    'logo' => $airline['logo'],
                ]
            );
        }       

        // ============================================================
        // 2. AIRPORTS — tambah 15, sudah ada: CGK, DPS, SUB, UPG, PDG
        // ============================================================
        $airports = [
            // Domestik
            ['iata_code' => 'JOG', 'name' => 'Bandara Internasional Yogyakarta',          'city' => 'Yogyakarta',    'country' => 'Indonesia', 'image' => 'airports/default.png'],
            ['iata_code' => 'BPN', 'name' => 'Bandara Internasional Sultan Aji Muhammad', 'city' => 'Balikpapan',   'country' => 'Indonesia', 'image' => 'airports/default.png'],
            ['iata_code' => 'BDJ', 'name' => 'Bandara Syamsudin Noor',                    'city' => 'Banjarmasin',  'country' => 'Indonesia', 'image' => 'airports/default.png'],
            ['iata_code' => 'PLM', 'name' => 'Bandara Internasional Sultan Mahmud Badar', 'city' => 'Palembang',    'country' => 'Indonesia', 'image' => 'airports/default.png'],
            ['iata_code' => 'MDC', 'name' => 'Bandara Sam Ratulangi',                     'city' => 'Manado',       'country' => 'Indonesia', 'image' => 'airports/default.png'],
            ['iata_code' => 'AMQ', 'name' => 'Bandara Internasional Pattimura',           'city' => 'Ambon',        'country' => 'Indonesia', 'image' => 'airports/default.png'],
            ['iata_code' => 'PKU', 'name' => 'Bandara Sultan Syarif Kasim II',            'city' => 'Pekanbaru',    'country' => 'Indonesia', 'image' => 'airports/default.png'],
            ['iata_code' => 'SOC', 'name' => 'Bandara Adi Soemarmo',                      'city' => 'Solo',         'country' => 'Indonesia', 'image' => 'airports/default.png'],
            ['iata_code' => 'TIM', 'name' => 'Bandara Mozes Kilangin',                    'city' => 'Timika',       'country' => 'Indonesia', 'image' => 'airports/default.png'],
            // Internasional
            ['iata_code' => 'SIN', 'name' => 'Bandara Changi',                            'city' => 'Singapura',    'country' => 'Singapura',      'image' => 'airports/default.png'],
            ['iata_code' => 'KUL', 'name' => 'Bandara Internasional Kuala Lumpur',        'city' => 'Kuala Lumpur', 'country' => 'Malaysia',       'image' => 'airports/default.png'],
            ['iata_code' => 'BKK', 'name' => 'Bandara Suvarnabhumi',                      'city' => 'Bangkok',      'country' => 'Thailand',       'image' => 'airports/default.png'],
            ['iata_code' => 'HND', 'name' => 'Bandara Haneda',                            'city' => 'Tokyo',        'country' => 'Jepang',         'image' => 'airports/default.png'],
            ['iata_code' => 'ICN', 'name' => 'Bandara Internasional Incheon',             'city' => 'Seoul',        'country' => 'Korea Selatan',  'image' => 'airports/default.png'],
            ['iata_code' => 'DOH', 'name' => 'Bandara Internasional Hamad',               'city' => 'Doha',         'country' => 'Qatar',          'image' => 'airports/default.png'],
        ];

        foreach ($airports as $airport) {
            Airport::firstOrCreate(
                ['iata_code' => $airport['iata_code']],
                [
                    'name'    => $airport['name'],
                    'city'    => $airport['city'],
                    'country' => $airport['country'],
                    'image'   => $airport['image'],
                ]
            );
        }

        // ============================================================
        // 3. FACILITIES — sudah lengkap, ambil saja ID-nya
        // ============================================================
        $facilities = Facility::all()->keyBy('name');

        // ============================================================
        // 4. PROMO CODES
        // ============================================================
        $promos = [
            ['code' => 'GARUDA10',  'discount_type' => 'percentage', 'discount' => 10, 'valid_until' => Carbon::now()->addMonths(3)],
            ['code' => 'HEMAT50K',  'discount_type' => 'fixed',      'discount' => 50000,  'valid_until' => Carbon::now()->addMonths(2)],
            ['code' => 'LIBURAN20', 'discount_type' => 'percentage', 'discount' => 20, 'valid_until' => Carbon::now()->addMonths(1)],
            ['code' => 'NEWUSER',   'discount_type' => 'fixed',      'discount' => 100000, 'valid_until' => Carbon::now()->addMonths(6)],
            ['code' => 'FLASH15',   'discount_type' => 'percentage', 'discount' => 15, 'valid_until' => Carbon::now()->addDays(30)],
        ];

        foreach ($promos as $promo) {
            PromoCode::firstOrCreate(['code' => $promo['code']], array_merge($promo, ['is_used' => false]));
        }

        // ============================================================
        // 5. FLIGHTS — 30+ rute realistis
        // ============================================================
        $allAirlines = Airline::all()->keyBy('name');
        $allAirports = Airport::all()->keyBy('iata_code');

        $flightRoutes = [
            // ── Garuda Indonesia ──────────────────────────────────────
            [
                'airline' => 'Garuda Indonesia', 'number' => 'GA-401',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-15 06:00:00'],
                    ['from' => 'DPS', 'time' => '2026-10-15 07:50:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 1200000,  'seats' => 120, 'facilities' => ['Wi-fi Onboard', 'Baggages']],
                    ['type' => 'business', 'price' => 4500000,  'seats' => 20,  'facilities' => ['Wi-fi Onboard', 'Baggages', 'Heavy Meals', 'Entertainment']],
                ],
            ],
            [
                'airline' => 'Garuda Indonesia', 'number' => 'GA-402',
                'segments' => [
                    ['from' => 'DPS', 'time' => '2026-10-15 09:30:00'],
                    ['from' => 'CGK', 'time' => '2026-10-15 11:20:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 1250000, 'seats' => 120, 'facilities' => ['Wi-fi Onboard', 'Baggages']],
                    ['type' => 'business', 'price' => 4700000, 'seats' => 20,  'facilities' => ['Wi-fi Onboard', 'Baggages', 'Heavy Meals', 'Entertainment']],
                ],
            ],
            [
                'airline' => 'Garuda Indonesia', 'number' => 'GA-500',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-16 07:00:00'],
                    ['from' => 'SUB', 'time' => '2026-10-16 08:10:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 900000,  'seats' => 150, 'facilities' => ['Wi-fi Onboard', 'Baggages']],
                    ['type' => 'business', 'price' => 3200000, 'seats' => 24,  'facilities' => ['Wi-fi Onboard', 'Baggages', 'Heavy Meals', 'Entertainment', 'USB C Port']],
                ],
            ],
            [
                'airline' => 'Garuda Indonesia', 'number' => 'GA-715',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-17 08:00:00'],
                    ['from' => 'HND', 'time' => '2026-10-17 18:00:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 7500000,  'seats' => 200, 'facilities' => ['Wi-fi Onboard', 'Baggages', 'Heavy Meals', 'Entertainment']],
                    ['type' => 'business', 'price' => 25000000, 'seats' => 30,  'facilities' => ['Wi-fi Onboard', 'Baggages', 'Heavy Meals', 'Entertainment', 'USB C Port']],
                ],
            ],

            // ── Lion Air ──────────────────────────────────────────────
            [
                'airline' => 'Lion Air', 'number' => 'JT-501',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-15 05:30:00'],
                    ['from' => 'UPG', 'time' => '2026-10-15 08:30:00'],
                ],
                'classes' => [
                    ['type' => 'economy', 'price' => 1100000, 'seats' => 180, 'facilities' => ['Baggages']],
                ],
            ],
            [
                'airline' => 'Lion Air', 'number' => 'JT-680',
                'segments' => [
                    ['from' => 'SUB', 'time' => '2026-10-15 10:00:00'],
                    ['from' => 'CGK', 'time' => '2026-10-15 11:15:00'],
                ],
                'classes' => [
                    ['type' => 'economy', 'price' => 750000, 'seats' => 180, 'facilities' => ['Baggages']],
                ],
            ],
            [
                'airline' => 'Lion Air', 'number' => 'JT-302',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-16 06:30:00'],
                    ['from' => 'BPN', 'time' => '2026-10-16 09:00:00'],
                ],
                'classes' => [
                    ['type' => 'economy', 'price' => 1300000, 'seats' => 180, 'facilities' => ['Baggages', 'Wi-fi Onboard']],
                ],
            ],

            // ── Batik Air ─────────────────────────────────────────────
            [
                'airline' => 'Batik Air', 'number' => 'ID-6181',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-15 13:00:00'],
                    ['from' => 'JOG', 'time' => '2026-10-15 14:05:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 700000,  'seats' => 150, 'facilities' => ['Baggages', 'Wi-fi Onboard']],
                    ['type' => 'business', 'price' => 2000000, 'seats' => 20,  'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals']],
                ],
            ],
            [
                'airline' => 'Batik Air', 'number' => 'ID-6500',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-16 14:00:00'],
                    ['from' => 'SOC', 'time' => '2026-10-16 15:10:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 680000,  'seats' => 150, 'facilities' => ['Baggages']],
                    ['type' => 'business', 'price' => 1900000, 'seats' => 20,  'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals']],
                ],
            ],

            // ── Citilink ──────────────────────────────────────────────
            [
                'airline' => 'Citilink', 'number' => 'QG-888',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-15 07:00:00'],
                    ['from' => 'PLM', 'time' => '2026-10-15 08:10:00'],
                ],
                'classes' => [
                    ['type' => 'economy', 'price' => 600000, 'seats' => 180, 'facilities' => ['Baggages']],
                ],
            ],
            [
                'airline' => 'Citilink', 'number' => 'QG-677',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-16 09:00:00'],
                    ['from' => 'PKU', 'time' => '2026-10-16 10:30:00'],
                ],
                'classes' => [
                    ['type' => 'economy', 'price' => 750000, 'seats' => 180, 'facilities' => ['Baggages', 'Wi-fi Onboard']],
                ],
            ],

            // ── Air Asia ──────────────────────────────────────────────
            [
                'airline' => 'Air Asia', 'number' => 'QZ-7512',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-15 08:30:00'],
                    ['from' => 'KUL', 'time' => '2026-10-15 11:30:00'],
                ],
                'classes' => [
                    ['type' => 'economy', 'price' => 1500000, 'seats' => 180, 'facilities' => ['Baggages', 'Wi-fi Onboard']],
                ],
            ],
            [
                'airline' => 'Air Asia', 'number' => 'QZ-7580',
                'segments' => [
                    ['from' => 'DPS', 'time' => '2026-10-16 07:00:00'],
                    ['from' => 'KUL', 'time' => '2026-10-16 10:30:00'],
                ],
                'classes' => [
                    ['type' => 'economy', 'price' => 1400000, 'seats' => 180, 'facilities' => ['Baggages', 'Wi-fi Onboard']],
                ],
            ],
            [
                'airline' => 'Air Asia', 'number' => 'QZ-8075',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-17 06:00:00'],
                    ['from' => 'BKK', 'time' => '2026-10-17 09:30:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 2200000, 'seats' => 180, 'facilities' => ['Baggages', 'Wi-fi Onboard']],
                    ['type' => 'business', 'price' => 5500000, 'seats' => 20,  'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment']],
                ],
            ],

            // ── Singapore Airline (sudah ada) ─────────────────────────
            [
                'airline' => 'Singapore Airline', 'number' => 'SQ-953',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-15 10:00:00'],
                    ['from' => 'SIN', 'time' => '2026-10-15 12:45:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 3500000,  'seats' => 200, 'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment']],
                    ['type' => 'business', 'price' => 15000000, 'seats' => 30,  'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment', 'USB C Port']],
                ],
            ],
            [
                'airline' => 'Singapore Airline', 'number' => 'SQ-955',
                'segments' => [
                    ['from' => 'SIN', 'time' => '2026-10-16 14:00:00'],
                    ['from' => 'CGK', 'time' => '2026-10-16 15:00:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 3600000,  'seats' => 200, 'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment']],
                    ['type' => 'business', 'price' => 15500000, 'seats' => 30,  'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment', 'USB C Port']],
                ],
            ],

            // ── Qatar Airways (sudah ada Emirates, tambah Qatar) ───────
            [
                'airline' => 'Qatar Airways', 'number' => 'QR-956',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-18 23:00:00'],
                    ['from' => 'DOH', 'time' => '2026-10-19 05:00:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 8500000,  'seats' => 250, 'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment']],
                    ['type' => 'business', 'price' => 35000000, 'seats' => 40,  'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment', 'USB C Port']],
                ],
            ],

            // ── Korean Air ────────────────────────────────────────────
            [
                'airline' => 'Korean Air', 'number' => 'KE-628',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-19 09:00:00'],
                    ['from' => 'ICN', 'time' => '2026-10-19 18:30:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 7000000,  'seats' => 220, 'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment']],
                    ['type' => 'business', 'price' => 28000000, 'seats' => 36,  'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment', 'USB C Port']],
                ],
            ],

            // ── Ana Airline (sudah ada) ───────────────────────────────
            [
                'airline' => 'Ana Airline', 'number' => 'NH-836',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-20 10:00:00'],
                    ['from' => 'HND', 'time' => '2026-10-20 19:30:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 8000000,  'seats' => 200, 'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment']],
                    ['type' => 'business', 'price' => 30000000, 'seats' => 32,  'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment', 'USB C Port']],
                ],
            ],

            // ── Rute Transit (multi-segment) ──────────────────────────
            [
                'airline' => 'Garuda Indonesia', 'number' => 'GA-855',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-15 06:00:00'],
                    ['from' => 'UPG', 'time' => '2026-10-15 08:00:00'],
                    ['from' => 'AMQ', 'time' => '2026-10-15 10:00:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 2800000, 'seats' => 150, 'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals']],
                    ['type' => 'business', 'price' => 8000000, 'seats' => 20,  'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment']],
                ],
            ],
            [
                'airline' => 'Lion Air', 'number' => 'JT-790',
                'segments' => [
                    ['from' => 'SUB', 'time' => '2026-10-16 07:00:00'],
                    ['from' => 'UPG', 'time' => '2026-10-16 08:30:00'],
                    ['from' => 'MDC', 'time' => '2026-10-16 10:30:00'],
                ],
                'classes' => [
                    ['type' => 'economy', 'price' => 1800000, 'seats' => 180, 'facilities' => ['Baggages']],
                ],
            ],
            [
                'airline' => 'Singapore Airline', 'number' => 'SQ-317',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-17 08:00:00'],
                    ['from' => 'SIN', 'time' => '2026-10-17 11:00:00'],
                    ['from' => 'HND', 'time' => '2026-10-17 18:00:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 9500000,  'seats' => 200, 'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment']],
                    ['type' => 'business', 'price' => 40000000, 'seats' => 30,  'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment', 'USB C Port']],
                ],
            ],

            // ── Rute tambahan domestik ────────────────────────────────
            [
                'airline' => 'Batik Air', 'number' => 'ID-7301',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-18 06:00:00'],
                    ['from' => 'MDC', 'time' => '2026-10-18 09:30:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 1600000, 'seats' => 150, 'facilities' => ['Baggages', 'Wi-fi Onboard']],
                    ['type' => 'business', 'price' => 4200000, 'seats' => 20,  'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals']],
                ],
            ],
            [
                'airline' => 'Citilink', 'number' => 'QG-199',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-18 11:00:00'],
                    ['from' => 'BDJ', 'time' => '2026-10-18 13:00:00'],
                ],
                'classes' => [
                    ['type' => 'economy', 'price' => 1100000, 'seats' => 180, 'facilities' => ['Baggages']],
                ],
            ],
            [
                'airline' => 'Garuda Indonesia', 'number' => 'GA-360',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-19 07:30:00'],
                    ['from' => 'TIM', 'time' => '2026-10-19 12:00:00'],
                ],
                'classes' => [
                    ['type' => 'economy',  'price' => 3500000, 'seats' => 120, 'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals']],
                    ['type' => 'business', 'price' => 9000000, 'seats' => 20,  'facilities' => ['Baggages', 'Wi-fi Onboard', 'Heavy Meals', 'Entertainment']],
                ],
            ],
            [
                'airline' => 'Air Asia', 'number' => 'QZ-8210',
                'segments' => [
                    ['from' => 'DPS', 'time' => '2026-10-20 08:00:00'],
                    ['from' => 'SIN', 'time' => '2026-10-20 11:00:00'],
                ],
                'classes' => [
                    ['type' => 'economy', 'price' => 2000000, 'seats' => 180, 'facilities' => ['Baggages', 'Wi-fi Onboard']],
                ],
            ],
            [
                'airline' => 'Lion Air', 'number' => 'JT-111',
                'segments' => [
                    ['from' => 'CGK', 'time' => '2026-10-20 15:00:00'],
                    ['from' => 'JOG', 'time' => '2026-10-20 16:10:00'],
                ],
                'classes' => [
                    ['type' => 'economy', 'price' => 550000, 'seats' => 180, 'facilities' => ['Baggages']],
                ],
            ],
        ];

        // ============================================================
        // 6. GENERATE FLIGHTS + SEGMENTS + CLASSES + SEATS
        // ============================================================
        foreach ($flightRoutes as $route) {
            $airline = $allAirlines->get($route['airline']);
            if (!$airline) continue;

            // Cek flight sudah ada atau belum
            $flight = Flight::firstOrCreate(
                ['flight_number' => $route['number']],
                ['airline_id' => $airline->id]
            );

            // Segments
            foreach ($route['segments'] as $seq => $seg) {
                $airport = $allAirports->get($seg['from']);
                if (!$airport) continue;

                FlightSegment::firstOrCreate(
                    ['flight_id' => $flight->id, 'sequence' => $seq + 1],
                    ['airport_id' => $airport->id, 'time' => $seg['time']]
                );
            }

            // Classes + Seats
            foreach ($route['classes'] as $classData) {
                $flightClass = FlightClass::firstOrCreate(
                    ['flight_id' => $flight->id, 'class_type' => $classData['type']],
                    ['price' => $classData['price'], 'total_seats' => $classData['seats']]
                );

                // Attach facilities
                $facilityIds = collect($classData['facilities'])
                    ->map(fn($name) => $facilities->get($name)?->id)
                    ->filter()
                    ->toArray();
                $flightClass->facilities()->syncWithoutDetaching($facilityIds);

                // Generate seats kalau belum ada
                $existingSeats = FlightSeat::where('flight_id', $flight->id)
                    ->where('class_type', $classData['type'])
                    ->count();

                if ($existingSeats === 0) {
                    $flight->generateSeats();
                }
            }
        }

        $this->command->info('✅ Seeder selesai!');
        $this->command->info('Airlines  : ' . Airline::count());
        $this->command->info('Airports  : ' . Airport::count());
        $this->command->info('Flights   : ' . Flight::count());
        $this->command->info('Promos    : ' . PromoCode::count());
    }
}