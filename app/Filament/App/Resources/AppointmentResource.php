<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Appointment;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Components\BelongsToSelect;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Filament\Resources\AppointmentResource\Pages\EditAppointment;
use App\Filament\Resources\AppointmentResource\Pages\ListAppointments;
use App\Filament\Resources\AppointmentResource\Pages\CreateAppointment;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('agent_id')
                    ->label('Agent ID')
                    ->disabled(),
                BelongsToSelect::make('property_id')
                    ->relationship('property', 'title')
                    ->required(),
                DatePicker::make('appointment_date')
                    ->label('Appointment Date')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'requested' => 'Requested',
                        'confirmed' => 'Confirmed',
                        'canceled' => 'Canceled',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->label('User ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('agent_id')
                    ->label('Agent ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('property_id')
                    ->label('Property ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('appointment_date')
                    ->date('d/m/Y')->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
