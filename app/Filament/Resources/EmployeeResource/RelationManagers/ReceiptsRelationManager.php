<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use App\Filament\Resources\ReceiptResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReceiptsRelationManager extends RelationManager
{
    protected static string $relationship = 'receipts';

    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema(
                ReceiptResource::formData()
            );
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->recordTitleAttribute('name')
            ->columns(
                ReceiptResource::tableData()
            )
            ->filters(
                ReceiptResource::filtersData(), layout: Tables\Enums\FiltersLayout::AboveContent
            )
            ->filtersFormColumns(12)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->disabled(fn() => $this->ownerRecord->deleted_at)
//                    ->label(fn() => !$this->ownerRecord->finishedWorkedToday() ? 'Add timesheet first!' : 'Add Receipt'),
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
