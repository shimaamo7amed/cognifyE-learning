<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function getModelLabel(): string
    {
        return __('filament/Categories/Categories.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/Categories/Categories.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                        TextInput::make('name.en')
                        ->label(__('filament/Categories/Categories.name_en'))
                        ->required(),
                        TextInput::make('name.ar')
                        ->label(__('filament/Categories/Categories.name_ar'))
                        ->required(),
                        // FileUpload::make('image')
                        // ->required()
                        // ->label(__('filament/Categories/Categories.image'))
                        // ->disk('public')
                        // ->directory('CategoryImage'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('code'),
                TextColumn::make('name.en')->label(__('filament/Categories/Categories.name_en')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
