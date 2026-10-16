<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CourtTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $penyewa;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@court.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081200000001',
        ]);

        $this->penyewa = User::create([
            'name' => 'Penyewa',
            'email' => 'penyewa@court.test',
            'password' => bcrypt('password'),
            'role' => 'penyewa',
            'phone' => '081200000002',
        ]);
    }

    protected function validData(array $override = []): array
    {
        return array_merge([
            'name' => 'Arena Futsal Baru',
            'category' => 'futsal',
            'price_per_hour' => 120000,
            'status' => 'available',
            'facilities' => 'Lampu LED, Ruang Ganti, Parkir Luas',
            'description' => 'Lapangan futsal indoor.',
        ], $override);
    }

    protected function makeCourt(array $override = []): Court
    {
        return Court::create(array_merge([
            'name' => 'Court Contoh',
            'category' => 'badminton',
            'price_per_hour' => 80000,
            'status' => 'available',
            'facilities' => 'Lantai Kayu',
        ], $override));
    }

    public function test_guest_can_view_court_catalog_and_detail(): void
    {
        $court = $this->makeCourt();

        $this->get(route('courts.index'))->assertOk()->assertSee('Court Contoh');
        $this->get(route('courts.show', $court))->assertOk()->assertSee('Lantai Kayu');
    }

    public function test_guest_cannot_manage_courts(): void
    {
        $court = $this->makeCourt();

        $this->get(route('admin.courts.create'))->assertRedirect(route('login'));
        $this->post(route('admin.courts.store'), $this->validData())->assertRedirect(route('login'));
        $this->delete(route('admin.courts.destroy', $court))->assertRedirect(route('login'));
    }

    public function test_penyewa_cannot_manage_courts(): void
    {
        $court = $this->makeCourt();

        $this->actingAs($this->penyewa)->get(route('admin.courts.create'))->assertForbidden();
        $this->actingAs($this->penyewa)->post(route('admin.courts.store'), $this->validData())->assertForbidden();
        $this->actingAs($this->penyewa)->put(route('admin.courts.update', $court), $this->validData())->assertForbidden();
        $this->actingAs($this->penyewa)->delete(route('admin.courts.destroy', $court))->assertForbidden();

        $this->assertDatabaseCount('courts', 1);
    }

    public function test_admin_sees_management_table(): void
    {
        $this->makeCourt();

        $this->actingAs($this->admin)
            ->get(route('courts.index'))
            ->assertOk()
            ->assertSee('Kelola Data Lapangan')
            ->assertSee('Court Contoh');
    }

    public function test_admin_can_create_court_without_photo(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.courts.store'), $this->validData());

        $court = Court::where('name', 'Arena Futsal Baru')->firstOrFail();

        $response->assertRedirect(route('courts.show', $court));
        $this->assertNull($court->image);
        $this->assertEquals(120000, $court->price_per_hour);
    }

    public function test_admin_can_create_court_with_photo(): void
    {
        $this->actingAs($this->admin)->post(route('admin.courts.store'), $this->validData([
            'image' => UploadedFile::fake()->image('lapangan.jpg', 800, 600),
        ]))->assertSessionHasNoErrors();

        $court = Court::where('name', 'Arena Futsal Baru')->firstOrFail();

        $this->assertNotNull($court->image);
        Storage::disk('public')->assertExists($court->image);
        $this->assertNotNull($court->image_url);
    }

    public function test_create_court_validation_errors(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.courts.store'), [])
            ->assertSessionHasErrors(['name', 'category', 'price_per_hour', 'status']);

        $this->actingAs($this->admin)
            ->post(route('admin.courts.store'), $this->validData(['price_per_hour' => 5000]))
            ->assertSessionHasErrors('price_per_hour');

        $this->actingAs($this->admin)
            ->post(route('admin.courts.store'), $this->validData(['category' => 'basket']))
            ->assertSessionHasErrors('category');

        $this->assertDatabaseCount('courts', 0);
    }

    public function test_photo_must_be_an_image_within_size_limit(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.courts.store'), $this->validData([
                'image' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
            ]))
            ->assertSessionHasErrors('image');

        $this->actingAs($this->admin)
            ->post(route('admin.courts.store'), $this->validData([
                'image' => UploadedFile::fake()->image('besar.jpg')->size(3000),
            ]))
            ->assertSessionHasErrors('image');

        $this->assertDatabaseCount('courts', 0);
    }

    public function test_court_name_must_be_unique(): void
    {
        $this->makeCourt(['name' => 'Arena Futsal Baru']);

        $this->actingAs($this->admin)
            ->post(route('admin.courts.store'), $this->validData())
            ->assertSessionHasErrors('name');
    }

    public function test_admin_can_open_edit_form(): void
    {
        $court = $this->makeCourt();

        $this->actingAs($this->admin)
            ->get(route('admin.courts.edit', $court))
            ->assertOk()
            ->assertSee('Court Contoh');
    }

    public function test_admin_can_update_court_keeping_same_name(): void
    {
        $court = $this->makeCourt();

        $this->actingAs($this->admin)
            ->put(route('admin.courts.update', $court), $this->validData([
                'name' => 'Court Contoh',
                'category' => 'badminton',
                'price_per_hour' => 95000,
            ]))
            ->assertRedirect(route('courts.index'))
            ->assertSessionHasNoErrors();

        $this->assertEquals(95000, $court->fresh()->price_per_hour);
    }

    public function test_updating_photo_replaces_the_old_file(): void
    {
        $this->actingAs($this->admin)->post(route('admin.courts.store'), $this->validData([
            'image' => UploadedFile::fake()->image('lama.jpg'),
        ]));

        $court = Court::firstOrFail();
        $oldPath = $court->image;

        $this->actingAs($this->admin)->put(route('admin.courts.update', $court), $this->validData([
            'image' => UploadedFile::fake()->image('baru.png'),
        ]))->assertSessionHasNoErrors();

        $court->refresh();

        $this->assertNotEquals($oldPath, $court->image);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($court->image);
    }

    public function test_admin_can_remove_existing_photo(): void
    {
        $this->actingAs($this->admin)->post(route('admin.courts.store'), $this->validData([
            'image' => UploadedFile::fake()->image('foto.jpg'),
        ]));

        $court = Court::firstOrFail();
        $path = $court->image;

        $this->actingAs($this->admin)->put(route('admin.courts.update', $court), $this->validData([
            'remove_image' => 1,
        ]));

        $this->assertNull($court->fresh()->image);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_admin_can_set_court_to_maintenance(): void
    {
        $court = $this->makeCourt();

        $this->actingAs($this->admin)->put(route('admin.courts.update', $court), $this->validData([
            'name' => $court->name,
            'status' => 'maintenance',
        ]));

        $this->assertEquals('maintenance', $court->fresh()->status);
        $this->get(route('courts.show', $court))->assertSee('Tidak dapat dibooking saat ini');
    }

    public function test_admin_can_delete_court_and_its_photo(): void
    {
        $this->actingAs($this->admin)->post(route('admin.courts.store'), $this->validData([
            'image' => UploadedFile::fake()->image('foto.jpg'),
        ]));

        $court = Court::firstOrFail();
        $path = $court->image;

        $this->actingAs($this->admin)
            ->delete(route('admin.courts.destroy', $court))
            ->assertRedirect(route('courts.index'));

        $this->assertModelMissing($court);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_court_with_active_booking_cannot_be_deleted(): void
    {
        $court = $this->makeCourt();

        Booking::create([
            'user_id' => $this->penyewa->id,
            'court_id' => $court->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '19:00',
            'end_time' => '21:00',
            'total_price' => 160000,
            'status' => 'approved',
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.courts.destroy', $court))
            ->assertRedirect(route('courts.index'))
            ->assertSessionHas('error');

        $this->assertModelExists($court);
    }

    public function test_search_does_not_bypass_category_filter(): void
    {
        $this->makeCourt(['name' => 'Lapangan Mawar', 'category' => 'badminton']);
        $this->makeCourt(['name' => 'Lapangan Melati', 'category' => 'futsal']);

        $this->get(route('courts.index', ['search' => 'Lapangan', 'category' => 'futsal']))
            ->assertSee('Lapangan Melati')
            ->assertDontSee('Lapangan Mawar');
    }

    public function test_courts_can_be_sorted_by_price(): void
    {
        $this->makeCourt(['name' => 'Murah Sekali', 'price_per_hour' => 50000]);
        $this->makeCourt(['name' => 'Mahal Sekali', 'price_per_hour' => 300000]);

        $this->get(route('courts.index', ['sort' => 'price_high']))
            ->assertSeeInOrder(['Mahal Sekali', 'Murah Sekali']);
    }

    public function test_unknown_court_shows_custom_not_found_page(): void
    {
        $this->get('/courts/9999')->assertNotFound()->assertSee('Halaman Tidak Ditemukan');
    }
}