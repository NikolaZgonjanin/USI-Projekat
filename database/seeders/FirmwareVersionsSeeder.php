<?php

namespace Database\Seeders;

use App\Models\FirmwareVersion;
use App\Models\Project;
use Illuminate\Database\Seeder;

class FirmwareVersionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            // Kreiraj nekoliko verzija za svaki projekat
            $versions = [
                [
                    'version' => '1.0.0',
                    'is_stable' => true,
                    'changelog' => 'Prva stabilna verzija. Implementirane su osnovne funkcionalnosti sistema.',
                    'released_at' => now()->subMonths(6),
                ],
                [
                    'version' => '1.1.0',
                    'is_stable' => true,
                    'changelog' => 'Dodate nove funkcije i ispravljene poznate greške. Poboljšana performansa.',
                    'released_at' => now()->subMonths(3),
                ],
                [
                    'version' => '1.2.1',
                    'is_stable' => true,
                    'changelog' => 'Ispravljene kritične greške u komunikaciji. Dodata podrška za novi hardver.',
                    'released_at' => now()->subDays(5),
                ],
                [
                    'version' => '2.0.0-beta',
                    'is_stable' => false,
                    'changelog' => 'Beta verzija sa novim arhitekturom. Neki delovi još uvek nisu stabilni.',
                    'released_at' => now()->subDays(2),
                ],
            ];

            foreach ($versions as $versionData) {
                FirmwareVersion::create([
                    'project_id' => $project->id,
                    'version' => $versionData['version'],
                    'is_stable' => $versionData['is_stable'],
                    'changelog' => $versionData['changelog'],
                    'file_path' => 'firmware/dummy.bin',
                    'released_at' => $versionData['released_at'],
                ]);
            }
        }
    }
}
