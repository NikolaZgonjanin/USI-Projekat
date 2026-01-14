<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFirmwareVersionRequest;
use App\Http\Requests\UpdateFirmwareVersionRequest;
use App\Models\FirmwareVersion;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
    public function show(Request $request, FirmwareVersion $firmwareVersion): View
    {
        $this->authorize('view', $firmwareVersion);

        $showHidden = $request->boolean('show_hidden', false);

        $firmwareVersion->load(['project', 'documentations']);

        // Učitaj prijave sa filterom
        $supportRequestsQuery = $firmwareVersion->supportRequests()->with(['createdBy', 'assignedTo']);
        if (! $showHidden) {
            $supportRequestsQuery->whereNotIn('status', ['denied', 'closed']);
        }
        $supportRequests = $supportRequestsQuery->latest()->get();

        return view('firmware-versions.show', compact('firmwareVersion', 'supportRequests', 'showHidden'));
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

    /**
     * UC3: Download firmvera (simulacija preuzimanja).
     */
    public function download(FirmwareVersion $firmwareVersion)
    {
        $this->authorize('view', $firmwareVersion);

        $path = $firmwareVersion->file_path ?? 'firmware/dummy.bin';

        if (! Storage::exists($path)) {
            // U slučaju da fajl ne postoji, vratimo 404 sa jasnom porukom na srpskom.
            abort(404, 'Firmware fajl nije pronađen.');
        }

        return Storage::download($path, 'firmware-'.$firmwareVersion->version.'.bin');
    }
}
