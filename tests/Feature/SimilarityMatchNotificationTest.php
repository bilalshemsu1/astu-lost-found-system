<?php

namespace Tests\Feature;

use App\Mail\SimilarityMatchFoundMail;
use App\Models\Item;
use App\Models\SimilarityLog;
use App\Models\User;
use App\Services\ItemMatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SimilarityMatchNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_queues_match_emails_once_and_marks_log_notified(): void
    {
        Mail::fake();

        $lostOwner = $this->createUser('lost-owner');
        $foundOwner = $this->createUser('found-owner');

        $lostItem = Item::create([
            'title' => 'Blue Backpack',
            'description' => 'With two notebooks inside.',
            'category' => 'bags',
            'type' => 'lost',
            'verification_status' => 'approved',
            'status' => 'active',
            'location' => 'Library',
            'item_date' => now()->subDay()->toDateString(),
            'user_id' => $lostOwner->id,
        ]);

        $foundItem = Item::create([
            'title' => 'Blue Backpack',
            'description' => 'Found near Library gate.',
            'category' => 'bags',
            'type' => 'found',
            'verification_status' => 'approved',
            'status' => 'active',
            'location' => 'Library',
            'item_date' => now()->toDateString(),
            'user_id' => $foundOwner->id,
        ]);

        $matcher = app(ItemMatcher::class);

        $matcher->saveMatches($lostItem, [[
            'candidate' => $foundItem,
            'scores' => [
                'total' => 91.2,
                'title' => 95.0,
                'category' => 100.0,
                'description' => 84.0,
                'location' => 87.0,
                'date' => 90.0,
                'attributes' => 70.0,
            ],
        ]]);

        $log = SimilarityLog::where('lost_item_id', $lostItem->id)
            ->where('found_item_id', $foundItem->id)
            ->first();

        $this->assertNotNull($log);
        $this->assertTrue((bool) $log->notified);
        Mail::assertQueued(SimilarityMatchFoundMail::class, 2);

        Mail::fake();

        $matcher->saveMatches($lostItem, [[
            'candidate' => $foundItem,
            'scores' => [
                'total' => 92.8,
                'title' => 96.0,
                'category' => 100.0,
                'description' => 86.0,
                'location' => 88.0,
                'date' => 91.0,
                'attributes' => 72.0,
            ],
        ]]);

        $this->assertTrue((bool) $log->fresh()->notified);
        Mail::assertNothingQueued();
    }

    private function createUser(string $slug): User
    {
        return User::create([
            'name' => ucfirst($slug),
            'student_id' => strtoupper($slug) . '/1000/26',
            'email' => $slug . '@example.test',
            'phone' => '+2519' . str_pad(substr((string) abs(crc32($slug)), 0, 8), 8, '0'),
            'password' => Hash::make('password123'),
            'role' => 'student',
            'trust_score' => 0,
        ]);
    }
}
