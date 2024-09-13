<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected static string $view = 'filament.app.view-post';

    public function getTitle(): string
    {
        return '';
    }

    public function getHeaderActions(): array
    {
        return [EditAction::make()];
    }

}
