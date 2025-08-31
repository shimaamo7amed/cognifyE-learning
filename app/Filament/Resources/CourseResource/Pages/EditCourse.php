<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load tags for editing
        if ($this->record->tags) {
            $data['tags'] = $this->record->tags->pluck('id')->toArray();
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return CourseResource::mutateFormDataBeforeSave($data);
    }

    protected function afterSave(): void
    {
        CourseResource::afterSave($this->record, $this->data);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
