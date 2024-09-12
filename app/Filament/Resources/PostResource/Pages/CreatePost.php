<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PostResource;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
