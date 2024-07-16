<?php

namespace App\Filament\Resources\TypologyResource\Pages;

use App\Filament\Resources\TypologyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTypologies extends ListRecords
{
    protected static string $resource = TypologyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
