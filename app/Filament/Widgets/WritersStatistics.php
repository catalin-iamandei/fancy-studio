<?php

namespace App\Filament\Widgets;

use App\Models\Receipt;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WritersStatistics extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $isWriter = auth()?->user()?->is_writer;

        if(!$isWriter || !auth()->user()?->commission) {
            return [];
        }

        // Widget incasari astazi
        $receiptsToday = Receipt::where('writer_id', auth()->user()->id)->whereDate('date', now()->today());

        $receiptsTodayStat = Stat::make('Incasari writer zi curenta',  '$'.$receiptsToday->sum('amount') / auth()->user()?->commission)
            ->icon('heroicon-o-banknotes');

        // Widget incasari perioada curenta
        if(now()->day <= 15) {
            $receiptsThisPeriod = Receipt::where('writer_id', auth()->user()->id)->whereBetween('date', [now()->startOfMonth(), now()->startOfMonth()->addDays(14)]);
        } else {
            $receiptsThisPeriod = Receipt::where('writer_id', auth()->user()->id)->whereBetween('date', [now()->startOfMonth()->addDays(15), now()->endOfMonth()]);
        }

        $receiptsThisPeriodStat = Stat::make('Incasari writer perioada curenta',  '$'.$receiptsThisPeriod->sum('amount') / auth()->user()?->commission)
            ->icon('heroicon-o-banknotes');

        // Widget incasari perioada anterioara
        if(now()->day <= 15) {
            $receiptsLastPeriod = Receipt::where('writer_id', auth()->user()->id)->whereBetween('date', [now()->startOfMonth()->subMonth()->addDays(15), now()->subMonth()->endOfMonth()]);
        } else {
            $receiptsLastPeriod = Receipt::where('writer_id', auth()->user()->id)->whereBetween('date', [now()->startOfMonth(), now()->startOfMonth()->addDays(14)]);
        }

        $receiptsLastPeriodStat = Stat::make('Incasari writer perioada anterioara',  '$'.$receiptsLastPeriod->sum('amount') / auth()->user()?->commission)
            ->icon('heroicon-o-banknotes');

        return [
            $receiptsTodayStat, $receiptsThisPeriodStat, $receiptsLastPeriodStat
        ];
    }
}
