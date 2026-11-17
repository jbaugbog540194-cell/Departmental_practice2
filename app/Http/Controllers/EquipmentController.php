<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    public function index(): View
    {
        return view('equipment.index', [
            'equipment' => Equipment::query()->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('equipment.create', [
            'categories' => Equipment::CATEGORIES,
            'statuses' => Equipment::AVAILABILITY_STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedEquipment($request);

        // The relationship assigns user_id from the authenticated session, never from form input.
        $request->user()->equipment()->create($validated);

        return redirect()->route('equipment.index')->with('success', 'Equipment added successfully.');
    }

    public function show(Equipment $equipment): View
    {
        return view('equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment): View
    {
        return view('equipment.edit', [
            'equipment' => $equipment,
            'categories' => Equipment::CATEGORIES,
            'statuses' => Equipment::AVAILABILITY_STATUSES,
        ]);
    }

    public function update(Request $request, Equipment $equipment): RedirectResponse
    {
        $equipment->update($this->validatedEquipment($request));

        return redirect()->route('equipment.show', $equipment)->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Equipment $equipment): RedirectResponse
    {
        $equipment->delete();

        return redirect()->route('equipment.index')->with('success', 'Equipment deleted successfully.');
    }

    /**
     * Validate server-side so every create and update uses the same inventory rules.
     *
     * @return array{equipment_name: string, category: string, unit_price: string, description: string, availability_status: string}
     */
    private function validatedEquipment(Request $request): array
    {
        return $request->validate([
            'equipment_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', Rule::in(Equipment::CATEGORIES)],
            'unit_price' => ['required', 'numeric', 'min:0', 'max:99999999.99', 'decimal:0,2'],
            'description' => ['required', 'string'],
            'availability_status' => ['required', 'string', Rule::in(Equipment::AVAILABILITY_STATUSES)],
        ]);
    }
}
