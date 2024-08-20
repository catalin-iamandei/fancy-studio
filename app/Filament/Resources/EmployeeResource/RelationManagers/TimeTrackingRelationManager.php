<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimeTrackingRelationManager extends RelationManager
{
    protected static string $relationship = 'timeTracking';

    protected static ?string $title = 'Timesheet';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TimePicker::make('check_in')
                    ->label('Check in time')
                    ->default(now())
                    ->native(false)
                    ->required(),
                Forms\Components\TimePicker::make('check_out')
                    ->label('Check out time')
                    ->default(now())
                    ->native(false)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('check_in', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('check_in')->dateTime('d.m.Y H:i'),
                Tables\Columns\TextColumn::make('check_out')->dateTime('d.m.Y H:i'),
                Tables\Columns\TextColumn::make('duration')
                    ->getStateUsing(fn(Model $record) => $record->check_in && $record->check_out ? $record->check_out->diff($record->check_in)->format('%H:%I') : ' - '),
            ])
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('checkIn')
                    ->label('Check In')
                    ->form([
                        Forms\Components\TimePicker::make('checkin')
                            ->label('Check in time')
                            ->default(now())
                            ->native(false)
                            ->required(),
                    ])
                    ->modalSubmitActionLabel('Check in')
                    ->action(function (array $data, $record) {
                        $this->ownerRecord->checkIn($data['checkin']);
                    })
                    ->hidden(function (array $data, $record) {
                        return $this->ownerRecord->isOnline();
                    })
                    ->button()
                    ->color('success')
                    ->modalWidth('xl'),

                Tables\Actions\Action::make('checkOut')
                    ->label('Check Out')
                    ->form([
                        Forms\Components\TimePicker::make('checkout')
                            ->label('Check out time')
                            ->default(now())
                            ->native(false)
                            ->required(),
                    ])
                    ->modalSubmitActionLabel('Check out')
                    ->action(function (array $data, $record) {
                        $this->ownerRecord->checkOut($data['checkout']);
                    })
                    ->hidden(function (array $data, $record) {
                        return !$this?->ownerRecord?->isOnline();
                    })
                    ->button()
                    ->color('danger')
                    ->modalWidth('xl'),
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
