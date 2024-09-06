<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class ModelsTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Modele prezente astazi';

    protected function getTableQuery(): Builder
    {
        $query = Employee::query()
            ->with('timeTracking')
            ->whereRelation('timeTracking', function ($query) {
                $query->whereDate('check_in', today());
            });

        $isWriter = auth()?->user()?->is_writer;

        if($isWriter) {
            $query->where('writer_id', auth()->user()->id);
        }

        return $query;
    }

//    protected function applyDefaultSortingToTableQuery(Builder $query): Builder
//    {
//        return $query
//            ->orderBy('check_in');
//    }

    protected function getTableColumns(): array
    {
        return [
            IconColumn::make('is_online')
                ->getStateUsing( function (Employee $record){
                    return $record->isOnline();
                })
                ->label('Online')
                ->boolean()
                ->trueIcon('heroicon-o-check')->trueColor('black')
                ->trueColor('success')
                ->falseColor('danger')
                ->falseIcon('heroicon-o-x-mark')->falseColor('black'),
            Tables\Columns\TextColumn::make('name')
                ->icon(fn($record) => 'heroicon-o-user')
                ->url(fn ($record): string => $record ? route('filament.admin.resources.employees.edit', ['record' => $record]) : '')
                ->color('primary')
                ->label('Name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('writer.name')
                ->icon(fn($record) => 'heroicon-o-user')
                ->url(fn ($record): string => $record ? route('filament.admin.resources.users.edit', ['record' => $record]) : '')
                ->color('primary')
                ->searchable()
                ->toggleable()
                ->limit(50),
            Tables\Columns\TextColumn::make('timeTracking.check_in')
                ->label('Check In')
                ->getStateUsing( function (Employee $record){
                    return $record->timeTracking->last()->check_in;
                })
                ->time()
                ->limit(50),
            Tables\Columns\TextColumn::make('location.name')
                ->toggleable()
                ->searchable()
                ->limit(50),
        ];
    }
}
