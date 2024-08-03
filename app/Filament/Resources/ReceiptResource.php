<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceiptResource\Pages;
use App\Filament\Resources\ReceiptResource\RelationManagers;
use App\Models\Receipt;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ReceiptResource extends Resource
{
    protected static ?string $model = Receipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema(
                self::formData(false)
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordTitleAttribute('name')
            ->columns(
                self::tableData(false)
            )
            ->filters(
                self::filtersData(false), layout: Tables\Enums\FiltersLayout::AboveContent
            )
            ->filtersFormColumns(12)
            ->headerActions([
//                Tables\Actions\CreateAction::make()
//                    ->disabled(fn() => !$this->ownerRecord->finishedWorkedToday())
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReceipts::route('/'),
//            'create' => Pages\CreateReceipt::route('/create'),
//            'edit' => Pages\EditReceipt::route('/{record}/edit'),
        ];
    }

    public static function tableData($fromRelation = true)
    {
        return [
            Tables\Columns\TextColumn::make('employee.name')
                ->label('Model')
                ->hidden($fromRelation),
            Tables\Columns\TextColumn::make('employee.location.name')
                ->hidden($fromRelation),
            Tables\Columns\TextColumn::make('employee.writer.name')
                ->hidden($fromRelation),
            Tables\Columns\TextColumn::make('date')->date(),
            Tables\Columns\TextColumn::make('site.name'),
            Tables\Columns\TextColumn::make('amount')
                ->prefix('$')
                ->summarize(Tables\Columns\Summarizers\Sum::make()
                    ->label('Total')
                    ->numeric()
                ),
        ];
    }

    public static function formData($fromRelation = true)
    {
        return [
            Select::make('employee_id')
                ->columnSpan(3)
                ->hidden($fromRelation)
                ->relationship('employee', 'name'),
            Forms\Components\DatePicker::make('date')
                ->required()
                ->default(today())
                ->maxDate(today())
                ->native(false)
                ->columnSpan($fromRelation ? 4 : 3),
            Select::make('site_id')
                ->rules(['exists:sites,id'])
                ->required()
                ->relationship('site', 'name')
                ->searchable()
                ->preload()
                ->columnSpan($fromRelation ? 4 : 3)
                ->placeholder('Site'),
            Forms\Components\TextInput::make('amount')
                ->prefix('$')
//                    ->prefixIcon('heroicon-o-currency-dollar')
                ->columnSpan($fromRelation ? 4 : 3)
                ->numeric()
                ->required()
        ];
    }

    public static function filtersData($fromRelation = true)
    {
        return [
            Tables\Filters\SelectFilter::make('location')
                ->multiple()
                ->preload()
                ->hidden($fromRelation)
                ->columnSpan(4)
                ->relationship('employee.location', 'name'),
            Tables\Filters\SelectFilter::make('writer')
                ->multiple()
                ->preload()
                ->hidden($fromRelation)
                ->columnSpan(4)
                ->relationship('employee.writer', 'name'),
            Tables\Filters\SelectFilter::make('employee')
                ->label('Model')
                ->multiple()
                ->preload()
                ->hidden($fromRelation)
                ->columnSpan(4)
                ->relationship('employee', 'name'),
            DateRangeFilter::make('date')
                ->ranges(config('cwd.date_picker_range'))
                ->columnSpan($fromRelation ? 6 : 4),
            Tables\Filters\SelectFilter::make('site')
                ->relationship('site', 'name')
                ->columnSpan($fromRelation ? 6 : 4)
                ->multiple()
                ->preload()
        ];
    }
}
