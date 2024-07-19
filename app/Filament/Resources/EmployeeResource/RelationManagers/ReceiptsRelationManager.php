<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReceiptsRelationManager extends RelationManager
{
    protected static string $relationship = 'receipts';

    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->default(today())
                    ->maxDate(today())
                    ->native(false)
                    ->columnSpan(4),
                Select::make('site_id')
                    ->rules(['exists:sites,id'])
                    ->required()
                    ->relationship('site', 'name')
                    ->searchable()
                    ->preload()
                    ->columnSpan(4)
                    ->placeholder('Site'),
                Forms\Components\TextInput::make('amount')
                    ->prefix('$')
//                    ->prefixIcon('heroicon-o-currency-dollar')
                    ->columnSpan(4)
                    ->numeric()
                    ->required()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('date')->date(),
                Tables\Columns\TextColumn::make('site.name'),
                Tables\Columns\TextColumn::make('amount'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->disabled(fn() => !$this->ownerRecord->finishedWorkedToday())
                    ->label(fn() => !$this->ownerRecord->finishedWorkedToday() ? 'Add timesheet first!' : 'Add Receipt'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
