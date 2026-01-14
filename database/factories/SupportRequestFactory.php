<?php

namespace Database\Factories;

use App\Models\FirmwareVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupportRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'firmware_version_id' => FirmwareVersion::factory(),
            'created_by' => User::factory()->create()->created_by,
            'assigned_to' => User::factory()->create()->assigned_to,
            'title' => fake()->sentence(4),
            'status' => fake()->randomElement(["pending","accepted","denied","closed"]),
            'request_text' => fake()->text(),
            'steps_to_reproduce' => fake()->text(),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
            'belongsTo' => fake()->word(),
        ];
    }
}
