<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\RelationManagers\ReceiptsRelationManager;
use App\Filament\Resources\EmployeeResource\RelationManagers\TimeTrackingRelationManager;
use App\Models\Site;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Tables;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\EmployeeResource\Pages;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;
use Icetalker\FilamentStepper\Forms\Components\Stepper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $recordTitleAttribute = 'name';

    public static ?string $label = 'Model';

//    public static function getEloquentQuery(): Builder
//    {
//        if(!auth()->user()->can('view_employee') && auth()->user()->is_writer) {
//            return parent::getEloquentQuery()->where('writer_id', auth()->user()->id);
//        }
//        return parent::getEloquentQuery();
//    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Tabs')
                ->columnSpanFull()
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Receipts')
                        ->schema([
                            \Njxqlus\Filament\Components\Forms\RelationManager::make()->manager(ReceiptsRelationManager::class)->lazy(true)
                        ])->hidden(fn ($record): bool => !$record?->receipts),
                    Forms\Components\Tabs\Tab::make('Timesheet')
                        ->schema([
                            \Njxqlus\Filament\Components\Forms\RelationManager::make()->manager(TimeTrackingRelationManager::class)->lazy(true)
                        ])->hidden(fn ($record): bool => !$record?->timeTracking),
                    Forms\Components\Tabs\Tab::make('About')
                        ->columns(12)
                        ->schema([
                            TextInput::make('name')
                                ->rules(['max:255', 'string'])
                                ->required()
                                ->columnSpan(4)
                                ->placeholder('Name'),

                            TextInput::make('phone')
                                ->rules(['max:255', 'string'])
                                ->nullable()
                                ->columnSpan(4)
                                ->placeholder('Phone'),

                            Forms\Components\DatePicker::make('date_start')
                                ->required()
                                ->maxDate(now())
                                ->native(false)
                                ->columnSpan(4),

                            Select::make('writer_id')
                                ->rules(['exists:users,id'])
                                ->required()
                                ->relationship('writer', 'name')
                                ->searchable()
                                ->preload()
                                ->disabled(fn() => !auth()->user()->roles()->exists())
                                ->columnSpan(4),

                            Select::make('location_id')
                                ->rules(['exists:locations,id'])
                                ->required()
                                ->relationship('location', 'name')
                                ->searchable()
                                ->preload()
                                ->disabled(fn() => !auth()->user()->roles()->exists())
                                ->columnSpan(4),

                            Select::make('shift_id')
                                ->rules(['exists:shifts,id'])
                                ->required()
                                ->relationship('shift', 'name')
                                ->searchable()
                                ->preload()
                                ->disabled(fn() => !auth()->user()->roles()->exists())
                                ->columnSpan(4),

//                            Forms\Components\TimePicker::make('check_in')
//                                ->seconds(false)
//                                ->rules(['date_format:H:i'])
//                                ->required()
//                                ->columnSpan(4)
//                                ->placeholder('Check In'),

                            TimePickerField::make('check_in')
                                ->columnSpan(4),


                            Select::make('principal_site_id')
                                ->rules(['exists:sites,id'])
                                ->relationship('principal_site', 'name')
                                ->searchable()
                                ->preload()
                                ->columnSpan(4)
                                ->placeholder('Principal Site')
                            ,

                            Select::make('typology_id')
                                ->rules(['exists:typologies,id'])
                                ->relationship('typology', 'name')
                                ->searchable()
                                ->preload()
                                ->columnSpan(4)
                                ->placeholder('Typology'),

                            Select::make('en_write')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->options([
                                    'Deloc' => 'Deloc',
                                    'Putin' => 'Putin',
                                    'Mediu' => 'Mediu',
                                    'Bine' => 'Bine',
                                    'Foarte bine' => 'Foarte bine',
                                ])
                                ->columnSpan(6)
                                ->placeholder('En Write'),

                            Select::make('en_speak')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->options([
                                    'Deloc' => 'Deloc',
                                    'Putin' => 'Putin',
                                    'Mediu' => 'Mediu',
                                    'Bine' => 'Bine',
                                    'Foarte bine' => 'Foarte bine',
                                ])
                                ->columnSpan(6)
                                ->placeholder('En Speak'),

                            RichEditor::make('writer_relationship')
                                ->rules(['max:255', 'string'])
                                ->nullable()
                                ->columnSpanFull()
                                ->columnSpan(6)
                                ->placeholder('Writer Relationship'),

                            RichEditor::make('attitude')
                                ->rules(['max:255', 'string'])
                                ->nullable()
                                ->columnSpan(6)
                                ->placeholder('Attitude'),

                            Stepper::make('write')
                                ->label('Write (%)')
                                ->minValue(0)
                                ->maxValue(100)
                                ->columnSpan(6)
                                ->default(0),

                            Stepper::make('tips_reaction_speed')
                                ->label('Tips reaction speed (%)')
                                ->minValue(0)
                                ->maxValue(100)
                                ->columnSpan(6)
                                ->default(0),

                            RichEditor::make('notes')
                                ->rules(['max:255', 'string'])
                                ->nullable()
                                ->columnSpan(12)
                                ->placeholder('Notes'),
                        ]),
                    Forms\Components\Tabs\Tab::make('Sites')
                        ->columns(12)
                        ->schema([
                            Forms\Components\Repeater::make('sites')
                                ->relationship('employeeSite')
                                ->label('')
                                ->addActionLabel('Add site')
                                ->maxItems(Site::all()->count())
                                ->schema([
                                    Forms\Components\Select::make('site_id')
                                        ->relationship('site', 'name')
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->columnSpan(4),
                                    Forms\Components\TextInput::make('username')
                                        ->prefixAction(CopyAction::make()->copyable(fn ($record) => $record?->username))
                                        ->columnSpan(4),

                                    Forms\Components\TextInput::make('password')
                                        ->prefixAction(CopyAction::make()->copyable(fn ($record) => $record?->password))
                                        ->columnSpan(4),
                                ])
                                ->columns(12)
                                ->columnSpan(12)
                        ]),
                    Forms\Components\Tabs\Tab::make('Intern email')
                        ->columns(12)
//                        ->visible(fn() => auth()->user()->hasRole('super_admin'))
                        ->schema([
                            Forms\Components\TextInput::make('intern_mail')
                                ->prefixAction(CopyAction::make())
                                ->columnSpan(6),

                            Forms\Components\TextInput::make('intern_mail_password')
                                ->prefixAction(CopyAction::make())
                                ->columnSpan(6),
                        ])
                ]),
        ]);
    }

    public static function table(Table $table, $fromRelationManager = false): Table
    {
        return $table
            ->paginated(false)
            ->poll('60s')
            ->modifyQueryUsing(function (Builder $query) use ($fromRelationManager) {
                if (!$fromRelationManager) {
                    return self::getEloquentQuery();
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('writer.name')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('location.name')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('shift.name')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('phone')
                    ->toggleable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('principal_site.name')
                    ->toggleable()
                    ->limit(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('location')
                    ->multiple()
                    ->preload()
                    ->columnSpan(3)
                    ->relationship('location', 'name'),
                Tables\Filters\SelectFilter::make('shift')
                    ->multiple()
                    ->preload()
                    ->columnSpan(3)
                    ->relationship('shift', 'name'),
                Tables\Filters\SelectFilter::make('writer')
                    ->multiple()
                    ->preload()
                    ->columnSpan(3)
                    ->relationship('writer', 'name'),
                Tables\Filters\TrashedFilter::make()
                    ->columnSpan(3)
                    ->label('Deleted')
                ,
//                SelectFilter::make('principal_site_id')
//                    ->relationship('principal_site', 'name')
//                    ->indicator('Principal Site')
//                    ->multiple()
//                    ->preload()
//                    ->label('Site'),

//                SelectFilter::make('typology_id')
//                    ->relationship('typology', 'name')
//                    ->indicator('Typology')
//                    ->multiple()
//                    ->preload()
//                    ->label('Typology'),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)->filtersFormColumns(12)
            ->actions([
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
                        $record->checkIn($data['checkin']);
                    })
                    ->hidden(function (array $data, $record) {
                        return $record->isOnline() || $record->deleted_at;
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
                        $record->checkOut($data['checkout']);
                    })
                    ->hidden(function (array $data, $record) {
                        return !$record->isOnline() || $record->deleted_at;
                    })
                    ->button()
                    ->color('danger')
                    ->modalWidth('xl'),

                Tables\Actions\Action::make('receipts')
                    ->label('New Receipt')
                    ->form([
                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->default(today())
                            ->maxDate(today())
                            ->displayFormat('d.m.Y')
                            ->closeOnDateSelection()
                            ->native(false)
                            ->columnSpan(6),

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
                    ])
                    ->modalSubmitActionLabel('Save')
                    ->action(function (array $data, $record) {
                        $record->newReceipt($data);
                    })
                    ->hidden(function (array $data, $record) {
                        return $record->isOnline() || $record->deleted_at;
                    })
                    ->button()
                    ->color('info')
                    ->modalWidth('4xl'),
                Tables\Actions\RestoreAction::make()->color('info')->button(),
                Tables\Actions\ActionGroup::make([
//                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->headerActions(!$fromRelationManager ? [CreateAction::make()] : []);
    }

    public static function getRelations(): array
    {
        return [
//            TimeTrackingRelationManager::make()
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
