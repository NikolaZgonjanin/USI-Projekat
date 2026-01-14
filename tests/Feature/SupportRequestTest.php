<?php

namespace Tests\Feature;

use App\Models\FirmwareVersion;
use App\Models\Project;
use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test da klijent može da prijavi problem za firmver verziju.
     * Kreira se SupportRequest sa status=pending.
     */
    public function test_client_can_report_issue_for_firmware_version(): void
    {
        // Kreiranje test podataka
        $client = User::factory()->create(['role' => 'client']);
        $project = Project::factory()->create();

        // Dodela projekta klijentu
        $client->projects()->attach($project->id);

        $firmwareVersion = FirmwareVersion::factory()->create([
            'project_id' => $project->id,
            'is_stable' => true,
        ]);

        // Prijava problema
        $response = $this->actingAs($client)->post(route('support-requests.store'), [
            'firmware_version_id' => $firmwareVersion->id,
            'title' => 'Problem sa rotacijom na zglobu 1',
            'request_text' => 'Firmver pravi prekomernu rotaciju na zglobu 1 kada se pokrene komanda za pokret.',
            'steps_to_reproduce' => "1. Pokreni firmver\n2. Izvrši komandu za pokret\n3. Posmatraj zglob 1",
        ]);

        // Provera da je prijava kreirana
        $this->assertDatabaseHas('support_requests', [
            'firmware_version_id' => $firmwareVersion->id,
            'created_by' => $client->id,
            'title' => 'Problem sa rotacijom na zglobu 1',
            'status' => 'pending',
        ]);

        // Provera da je korisnik preusmeren na stranicu prijave
        $supportRequest = SupportRequest::where('firmware_version_id', $firmwareVersion->id)
            ->where('created_by', $client->id)
            ->first();

        $response->assertRedirect(route('support-requests.show', $supportRequest));
        $response->assertSessionHas('success');
    }

    /**
     * Test da klijent ne može da prijavi problem za verziju projekta kojem nema pristup.
     */
    public function test_client_cannot_report_issue_for_unauthorized_project(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $project = Project::factory()->create();

        // Klijent NIJE dodeljen projektu

        $firmwareVersion = FirmwareVersion::factory()->create([
            'project_id' => $project->id,
        ]);

        // Pokušaj prijave problema
        $response = $this->actingAs($client)->post(route('support-requests.store'), [
            'firmware_version_id' => $firmwareVersion->id,
            'title' => 'Problem',
            'request_text' => 'Opis problema',
        ]);

        // Provera da je prijava odbijena (403 ili validacija)
        $response->assertStatus(403);

        $this->assertDatabaseMissing('support_requests', [
            'firmware_version_id' => $firmwareVersion->id,
            'created_by' => $client->id,
        ]);
    }
}
