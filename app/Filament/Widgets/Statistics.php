<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ReceiptResource;
use App\Models\Receipt;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Statistics extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $isWriter = auth()?->user()?->is_writer;

        // Widget incasari astazi
        $receiptsToday = Receipt::whereDate('date', now()->today());

        if($isWriter && !auth()?->user()?->hasRole('Manager')) {
            $receiptsToday->where('writer_id', auth()->user()?->id);
        }

        $receiptsTodayStat = Stat::make('Incasari modele zi curenta',  '$'.$receiptsToday->sum('amount'))
//            ->chartColor($statsToday >= $statsYesterday ? 'success' : 'danger')
            ->url(auth()->user()->can('view_receipt') ? ReceiptResource::getUrl() : null)
            ->icon('heroicon-o-banknotes');
//            ->chart([1, 6, 3, 8, 2, 5, 1, 10]);

        // Widget incasari perioada curenta
        if(now()->day <= 15) {
            $receiptsThisPeriod = Receipt::whereBetween('date', [now()->startOfMonth(), now()->startOfMonth()->addDays(14)]);
        } else {
            $receiptsThisPeriod = Receipt::whereBetween('date', [now()->startOfMonth()->addDays(15), now()->endOfMonth()]);
        }

        if($isWriter && !auth()?->user()?->hasRole('Manager')) {
            $receiptsThisPeriod->where('writer_id', auth()->user()?->id);
        }

        $receiptsThisPeriodStat = Stat::make('Incasari modele perioada curenta',  '$'.$receiptsThisPeriod->sum('amount'))
            ->url(auth()->user()->can('view_receipt') ? ReceiptResource::getUrl() : null)
            ->icon('heroicon-o-banknotes');

        // Widget incasari perioada anterioara
        if(now()->day <= 15) {
            $receiptsLastPeriod = Receipt::whereBetween('date', [now()->startOfMonth()->subMonth()->addDays(15), now()->subMonth()->endOfMonth()]);
        } else {
            $receiptsLastPeriod = Receipt::whereBetween('date', [now()->startOfMonth(), now()->startOfMonth()->addDays(14)]);
        }
        if($isWriter && !auth()?->user()?->hasRole('Manager')) {
            $receiptsLastPeriod->where('writer_id', auth()->user()?->id);
        }

        $receiptsLastPeriodStat = Stat::make('Incasari modele perioada anterioara',  '$'.$receiptsLastPeriod->sum('amount'))
            ->url(auth()->user()->can('view_receipt') ? ReceiptResource::getUrl() : null)
            ->icon('heroicon-o-banknotes');

        return [
            $receiptsTodayStat, $receiptsThisPeriodStat, $receiptsLastPeriodStat
        ];
    }
}
