<?php

namespace Database\Seeders;

use App\Models\FirmwareVersion;
use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupportRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firmwareVersions = FirmwareVersion::with('project')->get();
        $users = User::all();
        $clients = $users->where('role', 'client');
        $engineers = $users->where('role', 'engineer');

        $issues = [
            [
                'title' => 'Sistem se resetuje nakon 24 sata rada',
                'request_text' => 'Nakon kontinuiranog rada od 24 sata, sistem se automatski resetuje. Greška se javlja konzistentno.',
                'steps_to_reproduce' => "1. Pokreni sistem\n2. Ostavi ga da radi 24 sata\n3. Sistem će se resetovati",
                'status' => 'pending',
            ],
            [
                'title' => 'Komunikacija sa senzorom prekida se povremeno',
                'request_text' => 'Tokom rada, komunikacija sa temperaturnim senzorom se prekida na nekoliko sekundi, a zatim se vraća.',
                'steps_to_reproduce' => "1. Pokreni sistem sa temperaturnim senzorom\n2. Posmatraj komunikaciju tokom 1 sata\n3. Primećuje se prekid svakih 15-20 minuta",
                'status' => 'accepted',
            ],
            [
                'title' => 'Greška pri čitanju konfiguracije sa SD kartice',
                'request_text' => 'Sistem ne može da učita konfiguraciju sa SD kartice. Fajl postoji, ali se javlja greška prilikom čitanja.',
                'steps_to_reproduce' => "1. Umetni SD karticu sa konfiguracijom\n2. Pokreni sistem\n3. Greška se javlja pri pokušaju čitanja",
                'status' => 'accepted',
            ],
            [
                'title' => 'Nedostaje funkcionalnost za eksport podataka',
                'request_text' => 'U dokumentaciji je navedeno da postoji opcija za eksport podataka u CSV format, ali opcija nije dostupna u meniju.',
                'steps_to_reproduce' => "1. Otvori glavni meni\n2. Potraži opciju 'Eksport podataka'\n3. Opcija ne postoji",
                'status' => 'closed',
            ],
            [
                'title' => 'Sistem ne reaguje na daljinski upravljač',
                'request_text' => 'Nakon ažuriranja na verziju 1.2.1, sistem više ne reaguje na komande sa daljinskog upravljača.',
                'steps_to_reproduce' => "1. Ažuriraj sistem na verziju 1.2.1\n2. Pokušaj da koristiš daljinski upravljač\n3. Sistem ne reaguje",
                'status' => 'pending',
            ],
            [
                'title' => 'Visoka potrošnja energije u sleep modu',
                'request_text' => 'Sistem troši previše energije kada je u sleep modu. Očekivana potrošnja je 5mA, ali stvarna je 50mA.',
                'steps_to_reproduce' => "1. Staviti sistem u sleep mod\n2. Izmeriti potrošnju struje\n3. Potrošnja je 10x veća od očekivane",
                'status' => 'denied',
            ],
        ];

        $issueIndex = 0;
        foreach ($firmwareVersions as $firmwareVersion) {
            // Za svaku verziju, kreiraj 1-2 prijave
            $numIssues = rand(1, 2);

            for ($i = 0; $i < $numIssues && $issueIndex < count($issues); $i++) {
                $issue = $issues[$issueIndex];

                // Odaberi klijenta koji ima pristup projektu
                $projectUsers = $firmwareVersion->project->users()->where('role', 'client')->get();
                if ($projectUsers->isEmpty()) {
                    $projectUsers = $firmwareVersion->project->users()->get();
                }

                $createdBy = $projectUsers->random() ?? $clients->random();

                // Ponekad dodeli inženjera
                $assignedTo = null;
                if (rand(0, 1) && $engineers->isNotEmpty()) {
                    $projectEngineers = $firmwareVersion->project->users()->whereIn('role', ['engineer', 'administrator'])->get();
                    if ($projectEngineers->isNotEmpty()) {
                        $assignedTo = $projectEngineers->random();
                    }
                }

                SupportRequest::create([
                    'firmware_version_id' => $firmwareVersion->id,
                    'created_by' => $createdBy->id,
                    'assigned_to' => $assignedTo?->id,
                    'title' => $issue['title'],
                    'status' => $issue['status'],
                    'request_text' => $issue['request_text'],
                    'steps_to_reproduce' => $issue['steps_to_reproduce'],
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);

                $issueIndex++;
            }
        }
    }
}
