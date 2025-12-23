<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    public function definition(): array
    {
        $groupId = null;
        $sender_id = null;
        $receiver_id = null;

        // 50% chance → Group message
        if ($this->faker->boolean(50)) {

            $group = Group::inRandomOrder()->first();

            if ($group && $group->users->count() > 0) {
                $groupId = $group->id;
                $sender_id = $group->users->random()->id;
                $receiver_id = null;
            }

        } else {
            // User to User message
            $sender_id = User::inRandomOrder()->first()->id;
            $receiver_id = User::where('id', '!=', $sender_id)
                                ->inRandomOrder()
                                ->first()
                                ->id;
        }

        return [
            'sender_id'   => $sender_id,
            'receiver_id' => $receiver_id,
            'group_id'    => $groupId,
            'message'     => $this->faker->realText(200),
            'created_at'  => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
