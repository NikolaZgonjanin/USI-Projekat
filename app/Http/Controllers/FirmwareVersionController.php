<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFirmwareVersionRequest;
use App\Http\Requests\UpdateFirmwareVersionRequest;
use App\Models\FirmwareVersion;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FirmwareVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $firmwareVersions = FirmwareVersion::with(['project', 'supportRequests'])
                ->latest()
                ->paginate(15);
        } else {
            // Klijenti i inženjeri vide samo verzije projekata kojima imaju pristup
            $projectIds = $user->projects()->pluck('projects.id');
            $firmwareVersions = FirmwareVersion::whereIn('project_id', $projectIds)
                ->with(['project', 'supportRequests'])
                ->latest()
                ->paginate(15);
        }

        return view('firmware-versions.index', compact('firmwareVersions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', FirmwareVersion::class);

        $user = $request->user();
        $projects = $user->isAdmin()
            ? Project::all()
            : $user->projects;

        return view('firmware-versions.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFirmwareVersionRequest $request): RedirectResponse
    {
        $firmwareVersion = FirmwareVersion::create($request->validated());

        return redirect()->route('projects.show', $firmwareVersion->project_id)
            ->with('success', 'Firmver verzija je uspešno kreirana.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FirmwareVersion $firmwareVersion): View
    {
        $this->authorize('view', $firmwareVersion);

        $firmwareVersion->load(['project', 'documentations', 'supportRequests.createdBy', 'supportRequests.assignedTo']);

        return view('firmware-versions.show', compact('firmwareVersion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FirmwareVersion $firmwareVersion, Request $request): View
    {
        $this->authorize('update', $firmwareVersion);

        $user = $request->user();
        $projects = $user->isAdmin()
            ? Project::all()
            : $user->projects;

        return view('firmware-versions.edit', compact('firmwareVersion', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFirmwareVersionRequest $request, FirmwareVersion $firmwareVersion): RedirectResponse
    {
        $firmwareVersion->update($request->validated());

        return redirect()->route('firmware-versions.show', $firmwareVersion)
            ->with('success', 'Firmver verzija je uspešno ažurirana.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FirmwareVersion $firmwareVersion): RedirectResponse
    {
        $this->authorize('delete', $firmwareVersion);

        $projectId = $firmwareVersion->project_id;
        $firmwareVersion->delete();

        return redirect()->route('projects.show', $projectId)
            ->with('success', 'Firmver verzija je uspešno obrisana.');
    }
}
