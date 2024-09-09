<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ReceiptResource;
use App\Models\Location;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OnlineModels extends BaseWidget
{
    protected static ?string $maxHeight = '200px';
    protected static ?string $pollingInterval = '60s';
    protected function getStats(): array
    {
        $isWriter = auth()?->user()?->is_writer;

        if(auth()?->user()?->hasRole('Manager')) {
            $onlineModelsPerLocation = Location::whereRelation('users', function ($users) {
                    return $users->where('users.id', auth()->user()->id);
                })->get()->map(function ($location) {
                    $countModels = $location->employees()->whereRelation('timeTracking', function ($query) {
                        $query->whereDate('check_in', today())
                            ->where(function ($query) {
                                $query->whereDate('check_out', '!=', today())
                                    ->orWhereNull('check_out');
                            });
                    })->count();
                    return [
                        $location->name => $countModels
                    ];
                })->toArray();

            $onlineModelsPerLocation = array_merge(...$onlineModelsPerLocation);

            $widgets = [];
            foreach ($onlineModelsPerLocation as $name => $value) {
                $widgets[] = Stat::make('Modele online - ' . $name,  $value)
//            ->chartColor($statsToday >= $statsYesterday ? 'success' : 'danger')
                    ->url(ReceiptResource::getUrl())
                    ->icon('heroicon-o-banknotes');
            }
            return $widgets;
        } else if($isWriter) {
            $onlineModelsPerLocation = Location::get()->map(function ($location) {
                $countModels = $location->employees()
                    ->where('writer_id', auth()?->user()->id)
                    ->whereRelation('timeTracking', function ($query) {
                        $query->whereDate('check_in', today())
                            ->where(function ($query) {
                                $query->whereDate('check_out', '!=', today())
                                    ->orWhereNull('check_out');
                            });
                    })
                    ->count();
                return [
                    $location->name => $countModels
                ];
            })->toArray();

            $onlineModelsPerLocation = array_merge(...$onlineModelsPerLocation);

            $widgets = [];
            foreach ($onlineModelsPerLocation as $name => $value) {
                $widgets[] = Stat::make('Modele online - ' . $name,  $value)
//            ->chartColor($statsToday >= $statsYesterday ? 'success' : 'danger')
                    ->url(ReceiptResource::getUrl())
                    ->icon('heroicon-o-banknotes');
            }
            return $widgets;
        } else {
            $onlineModelsPerLocation = Location::get()->map(function ($location) {
                $countModels = $location->employees()->whereRelation('timeTracking', function ($query) {
                    $query->whereDate('check_in', today())
                        ->where(function ($query) {
                            $query->whereDate('check_out', '!=', today())
                                ->orWhereNull('check_out');
                        });
                })->count();
                return [
                    $location->name => $countModels
                ];
            })->toArray();

            $onlineModelsPerLocation = array_merge(...$onlineModelsPerLocation);

            $widgets = [];
            foreach ($onlineModelsPerLocation as $name => $value) {
                $widgets[] = Stat::make('Modele online - ' . $name,  $value)
//            ->chartColor($statsToday >= $statsYesterday ? 'success' : 'danger')
                    ->url(ReceiptResource::getUrl())
                    ->icon('heroicon-o-banknotes');
            }
            return $widgets;
        }
    }
}
