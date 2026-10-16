<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@sportcourt.test'],
            [
                'name' => 'Administrator SportCourt',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );

        // 2. Akun Penyewa 1 (Faris)
        $faris = User::firstOrCreate(
            ['email' => 'faris@sportcourt.test'],
            [
                'name' => 'Faris Kurniawan',
                'password' => Hash::make('password'),
                'role' => 'penyewa',
                'phone' => '081298765432',
            ]
        );

        // 3. Akun Penyewa 2 (Dinda)
        $dinda = User::firstOrCreate(
            ['email' => 'dinda@sportcourt.test'],
            [
                'name' => 'Dinda Putri',
                'password' => Hash::make('password'),
                'role' => 'penyewa',
                'phone' => '081377889900',
            ]
        );

        // 4. Data Lapangan (Courts)
        $courtFutsalA = Court::firstOrCreate(
            ['name' => 'Arena Futsal A (Vinyl Pro)'],
            [
                'category' => 'futsal',
                'price_per_hour' => 150000,
                'status' => 'available',
                'facilities' => 'Rumput Vinyl Interlock, Lampu LED Stadium, Ruang Ganti Ber-AC, Papan Skor Digital',
                'description' => 'Lapangan futsal kualitas kompetisi dengan lantai vinyl interlock empuk dan anti-selip.',
            ]
        );

        $courtFutsalB = Court::firstOrCreate(
            ['name' => 'Arena Futsal B (Sintetis Premium)'],
            [
                'category' => 'futsal',
                'price_per_hour' => 130000,
                'status' => 'available',
                'facilities' => 'Rumput Sintetis Monofilament, Bola & Rompi Gratis, Tribun Penonton',
                'description' => 'Lapangan rumput sintetis nyaman untuk sparing malam atau latihan mingguan.',
            ]
        );

        $courtBadminton1 = Court::firstOrCreate(
            ['name' => 'Court Badminton 1 (Kayu Jati)'],
            [
                'category' => 'badminton',
                'price_per_hour' => 80000,
                'status' => 'available',
                'facilities' => 'Lantai Kayu Parquet Standar BWF, Net Yonex Original, Pencahayaan Anti-Silau',
                'description' => 'Court badminton indoor berstandar turnamen dengan sirkulasi udara sejuk.',
            ]
        );

        $courtBadminton2 = Court::firstOrCreate(
            ['name' => 'Court Badminton 2 (Karpet Li-Ning)'],
            [
                'category' => 'badminton',
                'price_per_hour' => 90000,
                'status' => 'maintenance',
                'facilities' => 'Karpet Vinyl Hijau Standar Internasional, Wasit Seat, Loker Pemain',
                'description' => 'Sedang dalam pemeliharaan berkala untuk penggantian net dan pelapisan karpet.',
            ]
        );

        // 5. Data Tim / Komunitas Penyewa (Teams - Fitur CRUD Faris)
        $teamFaris1 = Team::firstOrCreate(
            ['name' => 'Rajawali Futsal Club', 'user_id' => $faris->id],
            [
                'captain_name' => 'Faris Kurniawan',
                'captain_phone' => '081298765432',
                'members_count' => 10,
                'description' => 'Komunitas futsal mahasiswa teknik informatika & alumni.',
            ]
        );

        $teamFaris2 = Team::firstOrCreate(
            ['name' => 'Smash Squad Badminton', 'user_id' => $faris->id],
            [
                'captain_name' => 'Faris Kurniawan',
                'captain_phone' => '081298765432',
                'members_count' => 6,
                'description' => 'Klub badminton santai setiap akhir pekan.',
            ]
        );

        $teamDinda = Team::firstOrCreate(
            ['name' => 'Garuda Muda FC', 'user_id' => $dinda->id],
            [
                'captain_name' => 'Dinda Putri',
                'captain_phone' => '081377889900',
                'members_count' => 12,
                'description' => 'Tim persiapan turnamen antar fakultas.',
            ]
        );

        // 6. Data Booking Lapangan
        $tomorrow = Carbon::tomorrow()->toDateString();
        $dayAfterTomorrow = Carbon::tomorrow()->addDay()->toDateString();

        Booking::firstOrCreate(
            [
                'court_id' => $courtFutsalA->id,
                'booking_date' => $tomorrow,
                'start_time' => '19:00',
            ],
            [
                'user_id' => $faris->id,
                'team_id' => $teamFaris1->id,
                'end_time' => '21:00',
                'total_price' => 300000,
                'status' => 'approved',
                'admin_notes' => 'Pemesanan disetujui, harap datang 15 menit sebelum kick-off.',
            ]
        );

        Booking::firstOrCreate(
            [
                'court_id' => $courtBadminton1->id,
                'booking_date' => $dayAfterTomorrow,
                'start_time' => '16:00',
            ],
            [
                'user_id' => $faris->id,
                'team_id' => $teamFaris2->id,
                'end_time' => '18:00',
                'total_price' => 160000,
                'status' => 'pending',
                'admin_notes' => null,
            ]
        );

        Booking::firstOrCreate(
            [
                'court_id' => $courtFutsalB->id,
                'booking_date' => $tomorrow,
                'start_time' => '16:00',
            ],
            [
                'user_id' => $dinda->id,
                'team_id' => $teamDinda->id,
                'end_time' => '18:00',
                'total_price' => 260000,
                'status' => 'pending',
                'admin_notes' => null,
            ]
        );
    }
}
