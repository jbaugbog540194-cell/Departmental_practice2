<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EquipmentReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);

        return view('reports.index', [
            'equipment' => $this->filteredEquipment($filters)->latest()->paginate(15)->withQueryString(),
            'filters' => $filters,
            'categories' => Equipment::CATEGORIES,
            'statuses' => Equipment::AVAILABILITY_STATUSES,
            'availableCount' => Equipment::query()->where('availability_status', 'available')->count(),
            'unavailableCount' => Equipment::query()->where('availability_status', 'unavailable')->count(),
        ]);
    }

    public function pdf(Request $request): Response
    {
        $filters = $this->validatedFilters($request);
        $equipment = $this->filteredEquipment($filters)->orderBy('equipment_name')->get();

        return Pdf::loadView('reports.pdf', compact('equipment', 'filters'))
            ->setPaper('a4', 'landscape')
            ->download('pickleball-equipment-report.pdf');
    }

    public function excel(Request $request): Response
    {
        $filters = $this->validatedFilters($request);
        $equipment = $this->filteredEquipment($filters)->orderBy('equipment_name')->get();
        $headers = ['Equipment name', 'Category', 'Unit price (PHP)', 'Availability status'];
        $rows = [$headers];

        foreach ($equipment as $item) {
            $rows[] = [
                $item->equipment_name,
                ucfirst($item->category),
                $item->unit_price,
                ucfirst($item->availability_status),
            ];
        }

        $xmlRows = collect($rows)->map(function (array $row, int $index): string {
            $cells = collect($row)->map(function (string $value, int $cellIndex) use ($index): string {
                $type = $index > 0 && $cellIndex === 2 ? 'Number' : 'String';
                $style = $type === 'Number' ? ' ss:StyleID="Currency"' : '';
                $escapedValue = htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');

                return '<Cell'.$style.'><Data ss:Type="'.$type.'">'.$escapedValue.'</Data></Cell>';
            })->implode('');

            return '<Row>'.$cells.'</Row>';
        })->implode('');

        $workbook = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" '
            .'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
            .'<Styles><Style ss:ID="Currency"><NumberFormat ss:Format="&quot;₱&quot;#,##0.00"/></Style></Styles>'
            .'<Worksheet ss:Name="Equipment"><Table>'.$xmlRows.'</Table></Worksheet></Workbook>';

        return response($workbook, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="pickleball-equipment-report.xls"',
        ]);
    }

    /**
     * @return array{category: string|null, availability_status: string|null}
     */
    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'category' => ['nullable', 'string', Rule::in(Equipment::CATEGORIES)],
            'availability_status' => ['nullable', 'string', Rule::in(Equipment::AVAILABILITY_STATUSES)],
        ]);
    }

    /**
     * Apply the same validated filters to the page and every export.
     *
     * @param  array{category: string|null, availability_status: string|null}  $filters
     */
    private function filteredEquipment(array $filters): Builder
    {
        return Equipment::query()
            ->when($filters['category'] ?? null, fn (Builder $query, string $category) => $query->where('category', $category))
            ->when($filters['availability_status'] ?? null, fn (Builder $query, string $status) => $query->where('availability_status', $status));
    }
}
