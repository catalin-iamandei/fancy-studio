<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Resources\SiteResource;
use App\Traits\AfterSaveRedirectToIndex;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSite extends CreateRecord
{
    use AfterSaveRedirectToIndex;

    protected static string $resource = SiteResource::class;
}
