<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupportRequestRequest;
use App\Http\Requests\UpdateSupportRequestRequest;
use App\Models\FirmwareVersion;
use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $showHidden = $request->boolean('show_hidden', false);

        $baseQuery = null;
        if ($user->isAdmin()) {
            $baseQuery = SupportRequest::query();
        } elseif ($user->isEngineer()) {
            // Inženjeri vide sve prijave za projekte kojima imaju pristup
            $projectIds = $user->projects()->pluck('projects.id');
            $firmwareVersionIds = FirmwareVersion::whereIn('project_id', $projectIds)->pluck('id');
            $baseQuery = SupportRequest::whereIn('firmware_version_id', $firmwareVersionIds);
        } else {
            // Klijenti vide samo svoje prijave
            $baseQuery = $user->createdSupportRequests();
        }

        // Po defaultu sakrij denied i closed prijave
        if (! $showHidden) {
            $baseQuery->whereNotIn('status', ['denied', 'closed']);
        }

        $supportRequests = $baseQuery
            ->with(['firmwareVersion.project', 'createdBy', 'assignedTo'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('support-requests.index', compact('supportRequests', 'showHidden'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $user = $request->user();
        $firmwareVersionId = $request->query('firmware_version_id');

        // Klijenti i inženjeri mogu da kreiraju prijave
        if ($user->isAdmin()) {
            $firmwareVersions = FirmwareVersion::with('project')->get();
        } else {
            $projectIds = $user->projects()->pluck('projects.id');
            $firmwareVersions = FirmwareVersion::whereIn('project_id', $projectIds)
                ->with('project')
                ->get();
        }

        return view('support-requests.create', compact('firmwareVersions', 'firmwareVersionId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupportRequestRequest $request): RedirectResponse
    {
        $user = $request->user();
        $firmwareVersion = FirmwareVersion::findOrFail($request->firmware_version_id);

        // Provera da li korisnik ima pristup projektu
        if (! $user->isAdmin()) {
            $hasAccess = $user->projects()
                ->where('projects.id', $firmwareVersion->project_id)
                ->exists();

            if (! $hasAccess) {
                abort(403, 'Nemate pristup ovom projektu.');
            }
        }

        $supportRequest = SupportRequest::create([
            'firmware_version_id' => $request->firmware_version_id,
            'created_by' => $user->id,
            'title' => $request->title,
            'request_text' => $request->request_text,
            'steps_to_reproduce' => $request->steps_to_reproduce,
            'status' => 'pending',
        ]);

        return redirect()->route('support-requests.show', $supportRequest)
            ->with('success', 'Prijava greške je uspešno kreirana.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SupportRequest $supportRequest): View
    {
        $this->authorize('view', $supportRequest);

        $supportRequest->load(['firmwareVersion.project', 'createdBy', 'assignedTo']);

        // Za inženjere, učitaj sve inženjere za dodelu
        $engineers = null;
        if (auth()->user()?->isEngineer() || auth()->user()?->isAdmin()) {
            $engineers = User::whereIn('role', ['engineer', 'administrator'])->get();
        }

        return view('support-requests.show', compact('supportRequest', 'engineers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupportRequest $supportRequest): View
    {
        $this->authorize('update', $supportRequest);

        $supportRequest->load(['firmwareVersion.project']);
        $engineers = User::whereIn('role', ['engineer', 'administrator'])->get();

        return view('support-requests.edit', compact('supportRequest', 'engineers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupportRequestRequest $request, SupportRequest $supportRequest): RedirectResponse
    {
        $data = [
            'title' => $request->title,
            'request_text' => $request->request_text,
            'steps_to_reproduce' => $request->steps_to_reproduce,
        ];

        // Samo inženjeri i admin mogu da menjaju status i assigned_to
        if ($request->user()->isEngineer() || $request->user()->isAdmin()) {
            if ($request->has('status')) {
                $data['status'] = $request->status;
            }
            if ($request->has('assigned_to')) {
                $data['assigned_to'] = $request->assigned_to;
            }
        }

        $supportRequest->update($data);

        return redirect()->route('support-requests.show', $supportRequest)
            ->with('success', 'Prijava greške je uspešno ažurirana.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupportRequest $supportRequest): RedirectResponse
    {
        $this->authorize('delete', $supportRequest);

        $supportRequest->delete();

        return redirect()->route('support-requests.index')
            ->with('success', 'Prijava greške je uspešno obrisana.');
    }
}
