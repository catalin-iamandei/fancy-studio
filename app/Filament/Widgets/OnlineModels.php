<?php

namespace App\Filament\Widgets;

use App\Models\Location;
use Filament\Widgets\ChartWidget;

class OnlineModels extends ChartWidget
{
    protected static ?string $maxHeight = '200px';
    protected static ?string $pollingInterval = '60s';
    protected function getData(): array
    {
        $isWriter = auth()?->user()?->is_writer;

        if($isWriter) {
            $onlineModelsPerLocation = Location::get()->map(function ($location) {
                $countModels = $location->employees()->where('writer_id', auth()?->user()->id)->whereRelation('timeTracking', function ($query) {
                    $query->whereDate('check_in', today())
                        ->where(function ($query) {
                            $query->whereDate('check_out', '!=', today())
                                ->orWhereNull('check_out');
                        });
                })->count();
                return [
                    $location->name.' - '.$countModels => $countModels
                ];
            })->toArray();
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
                    $location->name.' - '.$countModels => $countModels
                ];
            })->toArray();
        }

        $onlineModelsPerLocation = array_merge(...$onlineModelsPerLocation);

        return [
            'datasets' => [
                [
                    'label' => 'Online Models',
                    'data' => array_values($onlineModelsPerLocation),
                ],
            ],
            'labels' => array_keys($onlineModelsPerLocation),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
