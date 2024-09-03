<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceiptResource\Pages;
use App\Filament\Resources\ReceiptResource\RelationManagers;
use App\Models\Receipt;
use App\Models\Site;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ReceiptResource extends Resource
{
    protected static ?string $model = Receipt::class;

    public static ?string $label = 'Statistic';

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
            ->defaultGroup('employee.name')
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
                ->sortable()
                ->searchable()
                ->label('Model')
                ->icon(fn($record) => 'heroicon-o-user')
                ->url(fn ($record): string => $record && $record->employee ? route('filament.admin.resources.employees.edit', ['record' => $record->employee]) : '')
                ->color('primary')
                ->hidden($fromRelation),
            Tables\Columns\TextColumn::make('employee.location.name')
                ->sortable()
                ->searchable()
                ->hidden($fromRelation),
            Tables\Columns\TextColumn::make('writer.name')
                ->sortable()
                ->searchable()
                ->hidden($fromRelation),
            Tables\Columns\TextColumn::make('date')->date('d.m.Y'),
            Tables\Columns\ViewColumn::make('sites')
                ->view('filament.tables.columns.receipt_sites'),
            Tables\Columns\TextColumn::make('amount')
                ->label('Total')
                ->prefix('$')
                ->summarize(Tables\Columns\Summarizers\Sum::make()
                    ->label('Total')
                    ->numeric()
                )
//                ->summarize(Tables\Columns\Summarizers\Sum::make()
//                    ->label('Total')
//                    ->numeric()
//                )
            ,
        ];
    }

    public static function formData($fromRelation = true)
    {
        return [
            Select::make('employee_id')
                ->columnSpan(6)
                ->hidden($fromRelation)
                ->required()
                ->relationship('employee', 'name'),
            Forms\Components\DatePicker::make('date')
                ->required()
                ->default(today())
                ->maxDate(today())
                ->displayFormat('d.m.Y')
                ->closeOnDateSelection()
                ->native(false)
                ->columnSpan($fromRelation ? 12 : 6),

            Forms\Components\Repeater::make('sites')
                ->default(Site::all()->toArray())
                ->columnSpanFull()
                ->addable(false)
                ->deletable(false)
                ->reorderableWithDragAndDrop(false)
                ->grid(1)
                ->columns(12)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->readOnly()
                        ->columnSpan(6),
                    Forms\Components\TextInput::make('amount')
                        ->prefix('$')
                        ->numeric()
                        ->columnSpan(6)
                        ->required(),
                ])
        ];
    }

//    public static function sites()
//    {
//        $sites = Site::all()->pluck('name', 'id');
//
//        $res = [];
//            foreach ($sites as $site) {
//                $res[] = [
//                Forms\Components\TextInput::make('sites.site_name')
//                    ->default(fn() => $site)
//                    ->columnSpan(6)
//                    ->required(),
//                Forms\Components\TextInput::make('sites.amount')
//                    ->prefix('$')
//                    ->numeric()
//                    ->columnSpan(6)
//                    ->required(),
//                ];
//            }
//        return array_merge(...$res);
//    }

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
//            Tables\Filters\SelectFilter::make('site')
//                ->relationship('site', 'name')
//                ->columnSpan($fromRelation ? 6 : 4)
//                ->multiple()
//                ->preload()
        ];
    }
}
