<?php

namespace Tests\Feature;

use App\Models\FirmwareVersion;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FirmwareVersionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test da inženjer može da doda novu firmver verziju projektu.
     * Kreira se FirmwareVersion sa validnim podacima.
     */
    public function test_engineer_can_add_new_firmware_version_to_project(): void
    {
        // Kreiranje test podataka
        $engineer = User::factory()->create(['role' => 'engineer']);
        $project = Project::factory()->create();
        
        // Dodela projekta inženjeru
        $engineer->projects()->attach($project->id);

        // Dodavanje nove verzije
        $response = $this->actingAs($engineer)->post(route('firmware-versions.store'), [
            'project_id' => $project->id,
            'version' => '1.2.1',
            'is_stable' => true,
            'changelog' => 'Ispravljena greška sa rotacijom na zglobu 1. Dodata podrška za novi senzor.',
            'file_path' => 'firmware/dummy.bin',
            'released_at' => now()->format('Y-m-d H:i:s'),
        ]);

        // Provera da je verzija kreirana
        $this->assertDatabaseHas('firmware_versions', [
            'project_id' => $project->id,
            'version' => '1.2.1',
            'is_stable' => true,
        ]);

        // Provera da je korisnik preusmeren na stranicu projekta
        $firmwareVersion = FirmwareVersion::where('project_id', $project->id)
            ->where('version', '1.2.1')
            ->first();
        
        $response->assertRedirect(route('projects.show', $project));
        $response->assertSessionHas('success');
    }

    /**
     * Test da klijent ne može da doda novu firmver verziju (samo inženjeri i admin).
     */
    public function test_client_cannot_add_firmware_version(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $project = Project::factory()->create();
        
        $client->projects()->attach($project->id);

        // Pokušaj dodavanja verzije
        $response = $this->actingAs($client)->post(route('firmware-versions.store'), [
            'project_id' => $project->id,
            'version' => '1.2.1',
            'is_stable' => false,
            'changelog' => 'Test changelog',
        ]);

        // Provera da je zahtev odbijen (403)
        $response->assertStatus(403);
        
        $this->assertDatabaseMissing('firmware_versions', [
            'project_id' => $project->id,
            'version' => '1.2.1',
        ]);
    }

    /**
     * Test validacije pri dodavanju nove verzije (obavezna polja).
     */
    public function test_firmware_version_requires_valid_data(): void
    {
        $engineer = User::factory()->create(['role' => 'engineer']);
        $project = Project::factory()->create();
        
        $engineer->projects()->attach($project->id);

        // Pokušaj dodavanja verzije bez obaveznih polja
        $response = $this->actingAs($engineer)->post(route('firmware-versions.store'), [
            'project_id' => $project->id,
            // version je obavezno, ali nije poslato
        ]);

        // Provera da validacija ne prolazi
        $response->assertSessionHasErrors(['version']);
        
        $this->assertDatabaseMissing('firmware_versions', [
            'project_id' => $project->id,
        ]);
    }
}
