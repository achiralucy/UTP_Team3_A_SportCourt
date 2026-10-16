<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SportCourtTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $penyewa;
    protected Court $court;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081200000000',
        ]);

        $this->penyewa = User::create([
            'name' => 'Penyewa Test',
            'email' => 'penyewa@test.com',
            'password' => bcrypt('password'),
            'role' => 'penyewa',
            'phone' => '081211111111',
        ]);

        $this->court = Court::create([
            'name' => 'Court Test A',
            'category' => 'futsal',
            'price_per_hour' => 100000,
            'status' => 'available',
            'facilities' => 'Lantai Interlock, LED',
        ]);
    }

    public function test_welcome_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SportCourt');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Administrator');
    }

    public function test_penyewa_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->penyewa)->get(route('admin.dashboard'));
        $response->assertStatus(403);
        $response->assertSee('Akses Tidak Diizinkan');
    }

    public function test_penyewa_can_create_team(): void
    {
        $response = $this->actingAs($this->penyewa)->post(route('teams.store'), [
            'name' => 'Garuda Muda FC',
            'captain_name' => 'Faris',
            'captain_phone' => '081298765432',
            'members_count' => 10,
            'description' => 'Tim futsal kampus',
        ]);

        $response->assertRedirect(route('teams.index'));
        $this->assertDatabaseHas('teams', [
            'name' => 'Garuda Muda FC',
            'user_id' => $this->penyewa->id,
        ]);
    }

    public function test_penyewa_can_update_team(): void
    {
        $team = Team::create([
            'user_id' => $this->penyewa->id,
            'name' => 'Old Team Name',
            'captain_name' => 'Captain Old',
            'captain_phone' => '081299999999',
            'members_count' => 8,
        ]);

        $response = $this->actingAs($this->penyewa)->put(route('teams.update', $team), [
            'name' => 'New Team Name',
            'captain_name' => 'Captain New',
            'captain_phone' => '081288888888',
            'members_count' => 12,
        ]);

        $response->assertRedirect(route('teams.index'));
        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => 'New Team Name',
            'members_count' => 12,
        ]);
    }

    public function test_penyewa_can_delete_team(): void
    {
        $team = Team::create([
            'user_id' => $this->penyewa->id,
            'name' => 'Delete FC',
            'captain_name' => 'Captain',
            'captain_phone' => '081277777777',
            'members_count' => 5,
        ]);

        $response = $this->actingAs($this->penyewa)->delete(route('teams.destroy', $team));
        $response->assertRedirect(route('teams.index'));
        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
    }

    public function test_penyewa_can_create_booking(): void
    {
        $bookingDate = now()->addDays(2)->toDateString();

        $response = $this->actingAs($this->penyewa)->post(route('bookings.store'), [
            'court_id' => $this->court->id,
            'booking_date' => $bookingDate,
            'start_time' => '19:00',
            'end_time' => '21:00',
        ]);

        $response->assertRedirect(route('bookings.index'));
        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->penyewa->id,
            'court_id' => $this->court->id,
            'total_price' => 200000,
            'status' => 'pending',
        ]);
    }

    public function test_booking_conflict_prevention(): void
    {
        $bookingDate = now()->addDays(3)->toDateString();

        // Booking pertama sudah ada dan disetujui (19:00 - 21:00)
        Booking::create([
            'user_id' => $this->penyewa->id,
            'court_id' => $this->court->id,
            'booking_date' => $bookingDate,
            'start_time' => '19:00',
            'end_time' => '21:00',
            'total_price' => 200000,
            'status' => 'approved',
        ]);

        // Coba pesan di jam yang bertabrakan (20:00 - 22:00)
        $response = $this->actingAs($this->penyewa)->post(route('bookings.store'), [
            'court_id' => $this->court->id,
            'booking_date' => $bookingDate,
            'start_time' => '20:00',
            'end_time' => '22:00',
        ]);

        $response->assertSessionHas('error');
        // Pastikan tidak ada booking kedua yang tersimpan
        $this->assertEquals(1, Booking::where('court_id', $this->court->id)->count());
    }

    public function test_admin_can_approve_booking(): void
    {
        $booking = Booking::create([
            'user_id' => $this->penyewa->id,
            'court_id' => $this->court->id,
            'booking_date' => now()->addDays(4)->toDateString(),
            'start_time' => '18:00',
            'end_time' => '20:00',
            'total_price' => 200000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.bookings.approve', $booking), [
            'admin_notes' => 'Disetujui, silakan main tepat waktu.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'approved',
            'admin_notes' => 'Disetujui, silakan main tepat waktu.',
        ]);
    }

    public function test_admin_can_reject_booking_with_reason(): void
    {
        $booking = Booking::create([
            'user_id' => $this->penyewa->id,
            'court_id' => $this->court->id,
            'booking_date' => now()->addDays(5)->toDateString(),
            'start_time' => '15:00',
            'end_time' => '17:00',
            'total_price' => 200000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.bookings.reject', $booking), [
            'admin_notes' => 'Maaf, lapangan sedang disewa pemda.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'rejected',
            'admin_notes' => 'Maaf, lapangan sedang disewa pemda.',
        ]);
    }
}
