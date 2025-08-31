<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\ContactUs;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ContactUsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ContactUsResource\RelationManagers;

class ContactUsResource extends Resource
{
    protected static ?string $model = ContactUs::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    public static function getNavigationGroup(): ?string
    {
        return __('filament/forms/FormsInstructors.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament/forms/ContactUS.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/forms/ContactUS.plural');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('fullName')
                    ->label(__('filament/forms/ContactUS.fields.name'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('filament/forms/ContactUS.fields.email'))
                    ->required()
                    ->email(),
                TextInput::make('phone')
                    ->label(__('filament/forms/ContactUS.fields.phone'))
                    ->required(),
                Textarea::make('message')
                    ->label(__('filament/forms/ContactUS.fields.message'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fullName')
                    ->label(__('filament/forms/ContactUS.fields.name')),
                TextColumn::make('email')
                    ->label(__('filament/forms/ContactUS.fields.email')),
                TextColumn::make('phone')
                    ->label(__('filament/forms/ContactUS.fields.phone')),
                TextColumn::make('message')
                    ->label(__('filament/forms/ContactUS.fields.message')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListContactUs::route('/'),
            // 'create' => Pages\CreateContactUs::route('/create'),
            'view' => Pages\ViewContactUs::route('/{record}'),
            // 'edit' => Pages\EditContactUs::route('/{record}/edit'),
        ];
    }
}
