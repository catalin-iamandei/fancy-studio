<?php

namespace App\Filament\Resources\ShiftResource\Pages;

use App\Filament\Resources\ShiftResource;
use App\Traits\AfterSaveRedirectToIndex;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateShift extends CreateRecord
{
    use AfterSaveRedirectToIndex;

    protected static string $resource = ShiftResource::class;
}
