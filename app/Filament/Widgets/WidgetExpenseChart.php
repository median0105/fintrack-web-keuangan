<?php

namespace App\Filament\Widgets;
use App\Models\Transaction;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
class WidgetExpenseChart extends LineChartWidget
{
    protected static ?string $heading = 'Pengeluaran';
  

    protected function getData(): array
    {
        $data = Trend::query(Transaction::expenses())
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->sum('amount');
 
    return [
        'datasets' => [
            [
                'label' => 'Pengeluaran Per Bulan',
                'backgroundColor' => '#eb6d87',
                'borderColor' => '#d61941',
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
