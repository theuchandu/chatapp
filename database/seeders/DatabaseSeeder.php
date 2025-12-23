<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Create Other Users
        User::factory(10)->create();

        // Create Groups and attach users
        for ($i = 0; $i < 5; $i++) {
            $group = Group::factory()->create([
                'owner_id' => $admin->id
            ]);

            $users = User::inRandomOrder()
                ->limit(rand(2, 5))
                ->pluck('id')
                ->toArray();

            $group->users()->attach(
                array_unique(array_merge([$admin->id], $users))
            );
        }

        // Create Messages
        Message::factory(1000)->create();

        // Fetch user-to-user messages only
        $messages = Message::whereNull('group_id')
            ->orderBy('created_at')
            ->get(); // ✅ IMPORTANT

        // Build Conversations
        $conversations = $messages
            ->groupBy(function ($message) {
                return collect([
                    $message->sender_id,
                    $message->receiver_id
                ])->sort()->implode('_');
            })
            ->map(function ($groupedMessages) {
                return [
                    'user_id1' => $groupedMessages->first()->sender_id,
                    'user_id2' => $groupedMessages->first()->receiver_id,
                    'last_message_id' => $groupedMessages->last()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            })
            ->values()
            ->toArray();

        // Insert conversations safely
        DB::table('conversations')->insertOrIgnore($conversations);
    }
}
