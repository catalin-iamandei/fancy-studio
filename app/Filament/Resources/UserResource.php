<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Icetalker\FilamentStepper\Forms\Components\Stepper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Users & Roles';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                    ->label('Avatar')
                    ->columnSpan(12)
                    ->conversion('thumb')
                    ->responsiveImages()
                    ->avatar()
                    ->collection('avatars')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->columnSpan(6)
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->columnSpan(6)
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->columnSpan(6)
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->helperText(fn (string $context): string => ($context !== 'create') ? __('Leave blank to keep the current password.') : '')
                    ->label('Password'),

                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->columnSpan(6)
                    ->label('Role'),

                Forms\Components\Toggle::make('is_writer')
                    ->reactive()
                    ->columnSpan(6),

                Stepper::make('commission')
                    ->hidden(
                        fn ($get): bool => !$get('is_writer')
                    )
                    ->label('Commission (%)')
                    ->minValue(0)
                    ->maxValue(100)
                    ->columnSpan(6),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('avatars')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge(),
                IconColumn::make('is_writer')
                    ->boolean()
                    ->label('Is Writer')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),
                TextColumn::make('created_at')
                    ->label('Created at')
                    ->since(),
            ])
            ->filters([
//                Filter::make('is_writer')
//                    ->toggle()
//                    ->label('Writers'),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                \Filament\Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
