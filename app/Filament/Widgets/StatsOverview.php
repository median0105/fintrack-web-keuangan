<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;


class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $pemasukan = Transaction::incomes()->get()->sum('amount');
        $pengeluaran = Transaction::expenses()->get()->sum('amount');
        
        return [
            Card::make('Total Pemasukan', $this->formatRupiah($pemasukan)), 
            Card::make('Total Pengeluaran', $this->formatRupiah($pengeluaran)), 
            Card::make('Sisa', $this->formatRupiah($pemasukan - $pengeluaran)), 
        ];
    }

    private function formatRupiah($amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.'); 
    }
}


