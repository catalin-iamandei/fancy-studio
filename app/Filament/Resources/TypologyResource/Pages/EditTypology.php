<?php

namespace App\Filament\Resources\TypologyResource\Pages;

use App\Filament\Resources\TypologyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypology extends EditRecord
{
    protected static string $resource = TypologyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
