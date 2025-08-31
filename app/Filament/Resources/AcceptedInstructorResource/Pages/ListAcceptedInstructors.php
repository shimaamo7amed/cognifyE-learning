<?php

namespace App\Filament\Resources\AcceptedInstructorResource\Pages;

use App\Filament\Resources\AcceptedInstructorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcceptedInstructors extends ListRecords
{
    protected static string $resource = AcceptedInstructorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
