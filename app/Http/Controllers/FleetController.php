<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FleetController extends Controller
{
    public function index(Request $request)
    {
        $vehicles = Vehicle::query()
            ->when($request->q, fn ($query, $search) => $query->where('serial', 'like', "%{$search}%"))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('fleet.index', [
            'vehicles' => $vehicles,
            'statuses' => Vehicle::STATUSES,
        ]);
    }

    public function create()
    {
        return view('fleet.create', [
            'types' => Vehicle::TYPES,
            'statuses' => Vehicle::STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Vehicle::create($this->validated($request));

        return redirect()->route('fleet.index')->with('status', 'Vehicle added.');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('fleet.edit', [
            'vehicle' => $vehicle,
            'types' => Vehicle::TYPES,
            'statuses' => Vehicle::STATUSES,
        ]);
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $vehicle->update($this->validated($request, $vehicle));

        return redirect()->route('fleet.index')->with('status', 'Vehicle updated.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->delete();

        return redirect()->route('fleet.index')->with('status', 'Vehicle deleted.');
    }

    // ignore($vehicle) lets an edit keep its own serial number.
    private function validated(Request $request, ?Vehicle $vehicle = null): array
    {
        return $request->validate([
            'serial' => ['required', 'string', 'max:12', Rule::unique('vehicles', 'serial')->ignore($vehicle)],
            'type' => ['required', Rule::in(Vehicle::TYPES)],
            'status' => ['required', Rule::in(Vehicle::STATUSES)],
        ]);
    }
}
