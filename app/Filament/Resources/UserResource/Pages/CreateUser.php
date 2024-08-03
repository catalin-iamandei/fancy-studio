<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Traits\AfterSaveRedirectToIndex;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    use AfterSaveRedirectToIndex;

    protected static string $resource = UserResource::class;
}
