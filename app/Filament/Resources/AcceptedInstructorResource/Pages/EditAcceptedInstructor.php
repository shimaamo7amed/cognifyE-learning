<?php

namespace App\Filament\Resources\AcceptedInstructorResource\Pages;

use App\Filament\Resources\AcceptedInstructorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcceptedInstructor extends EditRecord
{
    protected static string $resource = AcceptedInstructorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
