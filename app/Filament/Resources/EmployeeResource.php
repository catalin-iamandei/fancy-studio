<?php

namespace App\Filament\Resources;

use App\Models\Site;
use Filament\Forms;
use Filament\Tables;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\EmployeeResource\Pages;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;
use Icetalker\FilamentStepper\Forms\Components\Stepper;
use Illuminate\Database\Eloquent\Builder;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $recordTitleAttribute = 'name';

    public static ?string $label = 'Model';

    public static function getEloquentQuery(): Builder
    {
        if(!auth()->user()->can('view_employee') && auth()->user()->is_writer) {
            return parent::getEloquentQuery()->where('writer_id', auth()->user()->id);
        }
        return parent::getEloquentQuery();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Tabs')
                ->columnSpanFull()
                ->tabs([
                    Forms\Components\Tabs\Tab::make('About')
                        ->columns(12)
                        ->schema([
                            TextInput::make('name')
                                ->rules(['max:255', 'string'])
                                ->required()
                                ->columnSpan(6)
                                ->placeholder('Name'),

                            TextInput::make('phone')
                                ->rules(['max:255', 'string'])
                                ->nullable()
                                ->columnSpan(6)
                                ->placeholder('Phone'),

                            Select::make('writer_id')
                                ->rules(['exists:users,id'])
                                ->required()
                                ->relationship('writer', 'name')
                                ->searchable()
                                ->preload()
                                ->columnSpan(6),

                            Select::make('location_id')
                                ->rules(['exists:location,id'])
                                ->required()
                                ->relationship('location', 'name')
                                ->searchable()
                                ->preload()
                                ->columnSpan(6),

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
                                ->required()
                                ->relationship('principal_site', 'name')
                                ->searchable()
                                ->preload()
                                ->columnSpan(4)
                                ->placeholder('Principal Site')
                            ,

                            Select::make('typology_id')
                                ->rules(['exists:typologies,id'])
                                ->required()
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
                                        ->required()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->columnSpan(4),
                                    Forms\Components\TextInput::make('username')
//                                        ->prefixAction(CopyAction::make())
                                        ->required()
                                        ->columnSpan(4),

                                    Forms\Components\TextInput::make('password')
                                        ->required()
                                        ->columnSpan(4),
                                ])
                                ->columns(12)
                                ->columnSpan(12)
                        ]),

                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->query(self::getEloquentQuery())
            ->columns([
                Tables\Columns\TextColumn::make('writer.name')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('principal_site.name')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('typology.name')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('phone')
                    ->toggleable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('writer_relationship')
                    ->toggleable()
                    ->searchable()
                    ->limit(50),
//                Tables\Columns\TextColumn::make('en_write')
//                    ->toggleable()
//                    ->searchable()
//                    ->enum([
//                        'Deloc' => 'Deloc',
//                        'Putin' => 'Putin',
//                        'Mediu' => 'Mediu',
//                        'Bine' => 'Bine',
//                        'Foarte bine' => 'Foarte bine',
//                    ]),
//                Tables\Columns\TextColumn::make('en_speak')
//                    ->toggleable()
//                    ->searchable()
//                    ->enum([
//                        'Deloc' => 'Deloc',
//                        'Putin' => 'Putin',
//                        'Mediu' => 'Mediu',
//                        'Bine' => 'Bine',
//                        'Foarte bine' => 'Foarte bine',
//                    ]),
                Tables\Columns\TextColumn::make('check_in')
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('attitude')
                    ->toggleable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('write')
                    ->toggleable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('tips_reaction_speed')
                    ->toggleable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('notes')
                    ->toggleable()
                    ->searchable()
                    ->limit(50),
            ])
            ->filters([
                SelectFilter::make('principal_site_id')
                    ->relationship('principal_site', 'name')
                    ->indicator('Site')
                    ->multiple()
                    ->preload()
                    ->label('Site'),

                SelectFilter::make('typology_id')
                    ->relationship('typology', 'name')
                    ->indicator('Typology')
                    ->multiple()
                    ->preload()
                    ->label('Typology'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([CreateAction::make()]);
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
