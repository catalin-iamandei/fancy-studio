<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Traits\AfterSaveRedirectToIndex;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\EmployeeResource;

class CreateEmployee extends CreateRecord
{
    use AfterSaveRedirectToIndex;

    protected static string $resource = EmployeeResource::class;
}
