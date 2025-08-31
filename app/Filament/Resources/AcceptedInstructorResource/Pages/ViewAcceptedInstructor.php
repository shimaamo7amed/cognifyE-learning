<?php

namespace App\Filament\Resources\AcceptedInstructorResource\Pages;

use App\Filament\Resources\AcceptedInstructorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAcceptedInstructor extends ViewRecord
{
    protected static string $resource = AcceptedInstructorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
