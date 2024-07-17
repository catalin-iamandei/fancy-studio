<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\EmployeeResource;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    public function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
