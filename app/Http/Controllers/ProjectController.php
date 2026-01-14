<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     * Klijenti i inženjeri vide samo projekte kojima imaju pristup.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $projects = Project::withCount(['firmwareVersions', 'supportRequests'])->latest()->paginate(15);
        } else {
            // Klijenti i inženjeri vide samo projekte kojima su dodeljeni
            $projects = $user->projects()
                ->withCount(['firmwareVersions', 'supportRequests'])
                ->latest()
                ->paginate(15);
        }

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Project::class);

        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = Project::create($request->validated());

        return redirect()->route('projects.index')
            ->with('success', 'Projekat je uspešno kreiran.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): View
    {
        $this->authorize('view', $project);

        $project->load(['firmwareVersions' => function ($query) {
            $query->latest()->limit(10);
        }, 'firmwareVersions.supportRequests']);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        return redirect()->route('projects.index')
            ->with('success', 'Projekat je uspešno ažuriran.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Projekat je uspešno obrisan.');
    }
}
