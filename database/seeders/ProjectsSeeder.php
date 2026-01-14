<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'Serenity',
                'code' => 'SER-001',
                'description' => 'Sistem za upravljanje energijom u pametnim kućama. Omogućava kontrolu osvetljenja, grejanja i klimatizacije preko mobilne aplikacije.',
            ],
            [
                'name' => 'Excalibur',
                'code' => 'EXC-002',
                'description' => 'Firmver za industrijski kontroler sa podrškom za različite protokole komunikacije (Modbus, CAN, Ethernet).',
            ],
            [
                'name' => 'AVA',
                'code' => 'AVA-003',
                'description' => 'Autonomni vozni asistent sa funkcijama za detekciju prepreka i automatsko kočenje.',
            ],
            [
                'name' => 'Sojourner',
                'code' => 'SOJ-004',
                'description' => 'Sistem za praćenje lokacije i telemetriju za logističke kompanije. Podržava GPS i GSM komunikaciju.',
            ],
            [
                'name' => 'ArgoPack',
                'code' => 'ARG-005',
                'description' => 'Firmver za upravljanje baterijskim paketima u električnim vozilima sa funkcijama za balansiranje ćelija.',
            ],
        ];

        $createdProjects = [];
        foreach ($projects as $projectData) {
            $createdProjects[] = Project::create($projectData);
        }

        // Dodeli projekte korisnicima
        $users = User::all();
        $engineers = $users->where('role', 'engineer');
        $clients = $users->where('role', 'client');

        // Prvi inženjer dobija pristup prvom i drugom projektu
        if ($engineers->count() > 0) {
            $engineers->first()->projects()->attach([$createdProjects[0]->id, $createdProjects[1]->id]);
        }

        // Drugi inženjer dobija pristup trećem i četvrtom projektu
        if ($engineers->count() > 1) {
            $engineers->skip(1)->first()->projects()->attach([$createdProjects[2]->id, $createdProjects[3]->id]);
        }

        // Prvi klijent dobija pristup prvom projektu
        if ($clients->count() > 0) {
            $clients->first()->projects()->attach([$createdProjects[0]->id]);
        }

        // Drugi klijent dobija pristup drugom i petom projektu
        if ($clients->count() > 1) {
            $clients->skip(1)->first()->projects()->attach([$createdProjects[1]->id, $createdProjects[4]->id]);
        }
    }
}
