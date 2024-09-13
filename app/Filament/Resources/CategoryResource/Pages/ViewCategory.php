<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCategory extends ViewRecord
{
    protected static string $resource = CategoryResource::class;

    protected static string $view = 'filament.app.view-category';

    public function getTitle(): string
    {
        return '';
    }

    public function getHeaderActions(): array
    {
        return [EditAction::make()];
    }

}
