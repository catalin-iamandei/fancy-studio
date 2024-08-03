<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Traits\AfterSaveRedirectToIndex;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\EmployeeResource;

class EditEmployee extends EditRecord
{
    use AfterSaveRedirectToIndex;

    protected static string $resource = EmployeeResource::class;
}
