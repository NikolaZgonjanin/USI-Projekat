<?php

namespace Database\Factories;

use App\Models\FirmwareVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'firmware_version_id' => FirmwareVersion::factory(),
            'title' => fake()->sentence(4),
            'file_path' => fake()->word(),
            'description' => fake()->text(),
            'belongsTo' => fake()->word(),
        ];
    }
}
