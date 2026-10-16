<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected User $penyewa;
    protected User $penyewa2;
    protected User $admin;
    protected Court $court;
    protected Court $maintenanceCourt;
    protected Team $team;

    protected function setUp(): void
    {
        parent::setUp();

        $this->penyewa = User::create([
            'name' => 'Dinda Putri',
            'email' => 'dinda@test.com',
            'password' => bcrypt('password'),
            'role' => 'penyewa',
            'phone' => '081234567890',
        ]);

        $this->penyewa2 = User::create([
            'name' => 'Faris Kurniawan',
            'email' => 'faris@test.com',
            'password' => bcrypt('password'),
            'role' => 'penyewa',
            'phone' => '081298765432',
        ]);

        $this->admin = User::create([
            'name' => 'Admin SportCourt',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081200000000',
        ]);

        $this->court = Court::create([
            'name' => 'Arena Futsal A (Vinyl Pro)',
            'category' => 'futsal',
            'price_per_hour' => 150000,
            'status' => 'available',
            'facilities' => 'Lantai Interlock, Lampu LED',
        ]);

        $this->maintenanceCourt = Court::create([
            'name' => 'Badminton Court 2',
            'category' => 'badminton',
            'price_per_hour' => 80000,
            'status' => 'maintenance',
            'facilities' => 'Karpet Vinyl',
        ]);

        $this->team = Team::create([
            'user_id' => $this->penyewa->id,
            'name' => 'Garuda Muda FC',
            'captain_name' => 'Dinda Putri',
            'captain_phone' => '081234567890',
            'members_count' => 10,
        ]);
    }

    public function test_penyewa_can_view_bookings_index(): void
    {
        Booking::create([
            'user_id' => $this->penyewa->id,
            'court_id' => $this->court->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '16:00',
            'end_time' => '18:00',
            'total_price' => 300000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->penyewa)->get(route('bookings.index'));

        $response->assertStatus(200);
        $response->assertSee('Booking Lapangan Saya');
        $response->assertSee('Arena Futsal A');
    }

    public function test_penyewa_cannot_view_other_users_booking_ticket(): void
    {
        $otherBooking = Booking::create([
            'user_id' => $this->penyewa2->id,
            'court_id' => $this->court->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '14:00',
            'end_time' => '16:00',
            'total_price' => 300000,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->penyewa)->get(route('bookings.show', $otherBooking));

        $response->assertStatus(403);
    }

    public function test_penyewa_can_create_valid_booking(): void
    {
        $date = now()->addDays(3)->toDateString();

        $response = $this->actingAs($this->penyewa)->post(route('bookings.store'), [
            'court_id' => $this->court->id,
            'team_id' => $this->team->id,
            'booking_date' => $date,
            'start_time' => '18:00',
            'end_time' => '20:00',
        ]);

        $response->assertRedirect(route('bookings.index'));
        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->penyewa->id,
            'court_id' => $this->court->id,
            'team_id' => $this->team->id,
            'total_price' => 300000, 
            'status' => 'pending',
        ]);

        $createdBooking = Booking::where('user_id', $this->penyewa->id)->first();
        $this->assertNotNull($createdBooking);
        $this->assertEquals($date, $createdBooking->booking_date->toDateString());
    }

    public function test_booking_fails_if_court_is_in_maintenance(): void
    {
        $response = $this->actingAs($this->penyewa)->post(route('bookings.store'), [
            'court_id' => $this->maintenanceCourt->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '14:00',
            'end_time' => '16:00',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('bookings', [
            'court_id' => $this->maintenanceCourt->id,
        ]);
    }

    public function test_booking_fails_if_duration_is_less_than_one_hour(): void
    {
        $response = $this->actingAs($this->penyewa)->post(route('bookings.store'), [
            'court_id' => $this->court->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '14:00',
            'end_time' => '14:30', 
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('bookings', [
            'start_time' => '14:00',
            'end_time' => '14:30',
        ]);
    }

    public function test_booking_fails_if_duration_exceeds_six_hours(): void
    {
        $response = $this->actingAs($this->penyewa)->post(route('bookings.store'), [
            'court_id' => $this->court->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '18:00', 
        ]);

        $response->assertSessionHas('error');
    }

    public function test_booking_fails_outside_operational_hours(): void
    {
        $response = $this->actingAs($this->penyewa)->post(route('bookings.store'), [
            'court_id' => $this->court->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '04:00',
            'end_time' => '06:00',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_booking_fails_if_past_time_on_today(): void
    {
        $pastStartTime = Carbon::now()->subHours(2)->format('H:i');
        $pastEndTime = Carbon::now()->subHour()->format('H:i');

        if ($pastStartTime > '06:00' && $pastEndTime > $pastStartTime) {
            $response = $this->actingAs($this->penyewa)->post(route('bookings.store'), [
                'court_id' => $this->court->id,
                'booking_date' => now()->toDateString(),
                'start_time' => $pastStartTime,
                'end_time' => $pastEndTime,
            ]);

            $response->assertSessionHas('error');
        } else {
            $this->assertTrue(true);
        }
    }

    public function test_booking_conflict_prevention_on_store(): void
    {
        $date = now()->addDays(4)->toDateString();

        Booking::create([
            'user_id' => $this->penyewa2->id,
            'court_id' => $this->court->id,
            'booking_date' => $date,
            'start_time' => '16:00',
            'end_time' => '18:00',
            'total_price' => 300000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->penyewa)->post(route('bookings.store'), [
            'court_id' => $this->court->id,
            'booking_date' => $date,
            'start_time' => '17:00',
            'end_time' => '19:00',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, Booking::where('court_id', $this->court->id)->whereDate('booking_date', $date)->count());
    }

    public function test_penyewa_can_edit_and_update_pending_booking(): void
    {
        $booking = Booking::create([
            'user_id' => $this->penyewa->id,
            'court_id' => $this->court->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '14:00',
            'end_time' => '16:00',
            'total_price' => 300000,
            'status' => 'pending',
        ]);

        $editResponse = $this->actingAs($this->penyewa)->get(route('bookings.edit', $booking));
        $editResponse->assertStatus(200);

        $updateResponse = $this->actingAs($this->penyewa)->put(route('bookings.update', $booking), [
            'court_id' => $this->court->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '18:00',
            'end_time' => '21:00',
        ]);

        $updateResponse->assertRedirect(route('bookings.index'));
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'start_time' => '18:00',
            'end_time' => '21:00',
            'total_price' => 450000, 
        ]);
    }

    public function test_penyewa_cannot_update_approved_booking(): void
    {
        $booking = Booking::create([
            'user_id' => $this->penyewa->id,
            'court_id' => $this->court->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '14:00',
            'end_time' => '16:00',
            'total_price' => 300000,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->penyewa)->get(route('bookings.edit', $booking));
        $response->assertRedirect(route('bookings.index'));
        $response->assertSessionHas('error');
    }

    public function test_penyewa_can_cancel_booking_with_reason(): void
    {
        $booking = Booking::create([
            'user_id' => $this->penyewa->id,
            'court_id' => $this->court->id,
            'booking_date' => now()->addDays(3)->toDateString(),
            'start_time' => '19:00',
            'end_time' => '21:00',
            'total_price' => 300000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->penyewa)->post(route('bookings.cancel', $booking), [
            'reason' => 'Ada ujian praktikum mendadak',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);

        $fresh = $booking->fresh();
        $this->assertStringContainsString('Ada ujian praktikum mendadak', $fresh->admin_notes);
    }

    public function test_booked_slots_api_returns_active_slots_correctly(): void
    {
        $date = now()->addDays(5)->toDateString();

        Booking::create([
            'user_id' => $this->penyewa->id,
            'court_id' => $this->court->id,
            'booking_date' => $date,
            'start_time' => '10:00',
            'end_time' => '12:00',
            'total_price' => 300000,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->penyewa)->getJson(route('courts.booked-slots', [
            'court' => $this->court->id,
            'date' => $date,
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'court_id',
            'court_name',
            'date',
            'operating_hours',
            'booked_slots' => [
                '*' => ['id', 'start_time', 'end_time', 'status', 'status_label']
            ]
        ]);
        $response->assertJsonFragment(['start_time' => '10:00', 'end_time' => '12:00']);
    }
}
