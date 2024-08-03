<?php

namespace App\Filament\Resources\TypologyResource\Pages;

use App\Filament\Resources\TypologyResource;
use App\Traits\AfterSaveRedirectToIndex;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTypology extends CreateRecord
{
    use AfterSaveRedirectToIndex;

    protected static string $resource = TypologyResource::class;
}
