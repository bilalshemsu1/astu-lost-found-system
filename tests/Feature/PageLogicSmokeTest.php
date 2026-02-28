<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Item;
use App\Models\SimilarityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PageLogicSmokeTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $studentUser;
    private User $otherStudentUser;
    private Item $lostItem;
    private Item $foundItem;
    private Claim $claim;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = $this->createUser('admin', 'admin');
        $this->studentUser = $this->createUser('student', 'student');
        $this->otherStudentUser = $this->createUser('student', 'owner');

        $this->lostItem = Item::create([
            'title' => 'Black Backpack',
            'description' => 'Contains notebooks and charger.',
            'category' => 'bag',
            'type' => 'lost',
            'status' => 'active',
            'verification_status' => 'approved',
            'location' => 'Library',
            'item_date' => now()->subDay()->toDateString(),
            'user_id' => $this->studentUser->id,
            'share_phone' => true,
            'share_telegram' => true,
        ]);

        $this->foundItem = Item::create([
            'title' => 'Black Backpack',
            'description' => 'Found near the library entrance.',
            'category' => 'bag',
            'type' => 'found',
            'status' => 'active',
            'verification_status' => 'approved',
            'location' => 'Library',
            'item_date' => now()->toDateString(),
            'user_id' => $this->otherStudentUser->id,
            'share_phone' => true,
            'share_telegram' => true,
            'return_location_preference' => 'admin_office',
        ]);

        $similarity = SimilarityLog::create([
            'lost_item_id' => $this->lostItem->id,
            'found_item_id' => $this->foundItem->id,
            'similarity_percentage' => 92.5,
            'title_match' => 95,
            'category_match' => 100,
            'description_match' => 88,
            'location_match' => 90,
            'date_match' => 85,
            'notified' => false,
        ]);

        $this->claim = Claim::create([
            'item_id' => $this->foundItem->id,
            'user_id' => $this->studentUser->id,
            'similarity_log_id' => $similarity->id,
            'similarity_score' => 92.5,
            'similarity_details' => ['source' => 'similarity_match'],
            'proof' => 'Has owner sticker inside and exact contents listed.',
            'status' => 'pending',
        ]);
    }

    public function test_student_pages_render_successfully(): void
    {
        $this->actingAs($this->studentUser);

        $this->get(route('student.dashboard'))->assertOk();
        $this->get(route('student.items'))->assertOk();
        $this->get(route('student.lost'))->assertOk();
        $this->get(route('student.found'))->assertOk();
        $this->get(route('student.my-items'))->assertOk();
        $this->get(route('student.matches'))->assertOk();
        $this->get(route('student.claims'))->assertOk();
    }

    public function test_admin_pages_render_successfully(): void
    {
        $this->actingAs($this->adminUser);

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.items.pending'))->assertOk();
        $this->get(route('admin.items'))->assertOk();
        $this->get(route('admin.items.found.create'))->assertOk();
        $this->get(route('admin.matches'))->assertOk();
        $this->get(route('admin.claims'))->assertOk();
        $this->get(route('admin.claims.review', $this->claim))->assertOk();
        $this->get(route('admin.users'))->assertOk();
        $this->get(route('admin.users.create'))->assertOk();
        $this->get(route('admin.reports'))->assertOk();
        $this->get(route('admin.statistics'))->assertOk();
    }

    public function test_registration_accepts_optional_telegram_username(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'New Student',
            'student_id' => 'UGR/12345/26',
            'email' => 'new-student@example.test',
            'phone' => '+251911111111',
            'telegram_username' => '@new_student_01',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'email' => 'new-student@example.test',
            'telegram_username' => 'new_student_01',
        ]);
    }

    private function createUser(string $role, string $slug): User
    {
        return User::create([
            'name' => ucfirst($slug) . ' User',
            'student_id' => strtoupper($slug) . '/1000/26',
            'email' => $slug . '@example.test',
            'phone' => '+2519' . str_pad(substr((string) abs(crc32($slug)), 0, 8), 8, '0'),
            'password' => Hash::make('password123'),
            'role' => $role,
            'trust_score' => 0,
        ]);
    }
}
