<?php

namespace App\Filament\Resources\LocationResource\RelationManagers;

use App\Filament\Resources\EmployeeResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    public static ?string $title = 'Models';

    public function form(Form $form): Form
    {
        return EmployeeResource::form($form);
    }

    public function table(Table $table): Table
    {
        return EmployeeResource::table($table, true);
    }
}
