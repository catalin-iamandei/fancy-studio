<?php

namespace App\Filament\Resources\TypologyResource\Pages;

use App\Filament\Resources\TypologyResource;
use App\Traits\AfterSaveRedirectToIndex;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypology extends EditRecord
{
    use AfterSaveRedirectToIndex;

    protected static string $resource = TypologyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
