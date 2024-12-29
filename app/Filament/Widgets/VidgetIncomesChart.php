<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
class VidgetIncomesChart extends LineChartWidget
{
    protected static ?string $heading = 'Pemasukan';
 

    protected function getData(): array
    {
        $data = Trend::query(Transaction::incomes())
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->sum('amount');
 
    return [
        'datasets' => [
            [
                'label' => 'Pemasukan Per Bulan',
                'backgroundColor' => '#36A2EB',
                'borderColor' => '#9BD0F5',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
