<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class FirmwareVersionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'version' => fake()->word(),
            'is_stable' => fake()->boolean(),
            'changelog' => fake()->text(),
            'file_path' => fake()->word(),
            'released_at' => fake()->dateTime(),
            'hasMany' => fake()->word(),
            'belongsTo' => fake()->word(),
        ];
    }
}
