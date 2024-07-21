<?php

namespace App\Filament\App\Resources\RightMoveSettingsResource\Pages;

use App\Filament\App\Resources\RightMoveSettingsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRightMoveSettings extends EditRecord
{
    protected static string $resource = RightMoveSettingsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}