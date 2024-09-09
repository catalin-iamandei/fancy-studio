<?php

namespace App\Filament\Resources\LocationResource\RelationManagers;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions\CreateAction;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShiftsRelationManager extends RelationManager
{
    protected static string $relationship = 'shifts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
//                Select::make('shift_id')
//                    ->relationship('shifts', 'name')
//                    ->columnSpan(6)
//                    ->label('Shift'),
//                Select::make('location.user_id')
//                    ->relationship('users', 'name')
//                    ->columnSpan(6)
//                    ->label('User'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('user.name'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
//                \Filament\Tables\Actions\CreateAction::make()
                AttachAction::make()
                    ->form(fn (AttachAction $action): array => [
//                        $action->getRecordSelect(),
                        TextInput::make('recordId')
                            ->default(fn(Livewire $livewire) => $livewire->ownerRecord->id)
                            ->readOnly()
                            ->dehydrated()
                            ->required(),
                        Select::make('shift_id')
                            ->relationship('shifts', 'name')
                            ->columnSpan(6)
                            ->label('Shift'),
                        Select::make('user_id')
                            ->relationship('users', 'name')
                            ->columnSpan(6)
                            ->label('User'),
                    ])
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }
}
