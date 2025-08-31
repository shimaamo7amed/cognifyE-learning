<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AcceptedInstructorResource\Pages;
use App\Filament\Resources\AcceptedInstructorResource\RelationManagers;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
class AcceptedInstructorResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_type', 'instructor');
    }

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    public static function getModelLabel(): string
    {
        return __('filament/instructors.model');
    }


    public static function getPluralModelLabel(): string
    {
        return __('filament/instructors.plural');
    }

    public static function form(Form $form): Form
    {
            return $form
                ->schema([
                    Section::make(__('filament/instructors.basic_info'))
                        ->description(__('filament/instructors.basic_info_desc'))
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('name_ar')
                                        ->label(__('filament/instructors.name_ar'))
                                        ->placeholder('اكتب الاسم بالعربي...')
                                        ->maxLength(255)
                                        ->required(),
                                    TextInput::make('name_en')
                                        ->label(__('filament/instructors.name_en'))
                                        ->placeholder('Enter name in English...')
                                        ->maxLength(255)
                                        ->required(),
                                    TextInput::make('email')
                                        ->label(__('filament/instructors.email'))
                                        ->placeholder('example@mail.com')
                                        ->email()
                                        ->maxLength(255)
                                        ->required(),
                                    TextInput::make('phone')
                                        ->label(__('filament/instructors.phone'))
                                        ->placeholder('+966500000000')
                                        ->tel()
                                        ->maxLength(20)
                                        ->required(),
                                ]),
                        ]),

                    Section::make(__('filament/instructors.description'))
                        ->collapsible()
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Textarea::make('desc.en')
                                        ->label(__('filament/instructors.desc'))
                                        ->placeholder('Write instructor bio in English...')
                                        ->rows(5)
                                        ->required(),
                                    Textarea::make('desc.ar')
                                        ->label(__('filament/instructors.desc_ar'))
                                        ->placeholder('اكتب نبذة عن المدرس بالعربي...')
                                        ->rows(5)
                                        ->required(),
                                ]),
                        ]),

                    Section::make(__('filament/instructors.social_links'))
                        ->collapsible()
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('linkedIn')
                                        ->label(__('filament/instructors.linkedIn'))
                                        ->url()
                                        ->prefix('🔗')
                                        ->placeholder('https://linkedin.com/in/...'),
                                    TextInput::make('facebook')
                                        ->label(__('filament/instructors.facebook'))
                                        ->url()
                                        ->prefix('🔗')
                                        ->placeholder('https://facebook.com/...'),
                                ]),
                        ]),

                Section::make(__('filament/instructors.additional'))
                    ->collapsible()
                    ->schema([
                    Grid::make(3)
                    ->schema([
                        TextInput::make('experience')
                            ->label(__('filament/instructors.experience'))
                            ->numeric()
                            ->suffix('years')
                            ->placeholder('e.g., 5')
                            ->required()
                            ->columnSpan(1),
                        FileUpload::make('image')
                            ->label(__('filament/instructors.image'))
                            ->image()
                            ->imageEditor()
                            ->directory('instructors/images')
                            ->maxSize(2048)
                            ->required()
                            ->columnSpan(1),
                        FileUpload::make('cv')
                            ->label(__('filament/instructors.cv'))
                            ->directory('instructors/cv')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(4096)
                            ->required()
                            ->columnSpan(1),
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_en')->label(__('filament/instructors.name_en')),
                TextColumn::make('email')->label(__('filament/instructors.email')),
                TextColumn::make('phone')->label(__('filament/instructors.phone')),
                ImageColumn::make('image')->label(__('filament/instructors.image')),
                TextColumn::make('desc.en')->label(__('filament/instructors.desc')),
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
            'index' => Pages\ListAcceptedInstructors::route('/'),
            'create' => Pages\CreateAcceptedInstructor::route('/create'),
            'view' => Pages\ViewAcceptedInstructor::route('/{record}'),
            'edit' => Pages\EditAcceptedInstructor::route('/{record}/edit'),
        ];
    }
}
