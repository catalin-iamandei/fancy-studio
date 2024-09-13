<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Filament\Resources\PostResource\Pages\ViewPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required(),
                        TinyEditor::make('content')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsDirectory('uploads')
                            ->profile('default|simple|full|minimal|none|custom')
                            ->columnSpan('full')
                            ->required()
                    ])
                    ->columnSpan(2),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\FileUpload::make('banner')
                            ->label('Image')
                            ->image()
                            ->maxSize(config('filament-blog.banner.maxSize', 5120))
                            ->imageCropAspectRatio(config('filament-blog.banner.cropAspectRatio', '16:9'))
                            ->disk(config('filament-blog.banner.disk', 'public'))
                            ->directory(config('filament-blog.banner.directory', 'blog')),

//                        self::getContentEditor('content'),

                        Forms\Components\Select::make('blog_category_id')
                            ->relationship(name: 'category', titleAttribute: 'name')
                            ->preload()
                            ->searchable()
                            ->required(),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('banner')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable(),
            ])->defaultSort('created_at', 'DESC')
            ->actions([
//                \Filament\Tables\Actions\ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'view' => ViewPost::route('/{record}'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
