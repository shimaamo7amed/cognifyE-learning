<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Course;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\CourseResource\Pages;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['name']) && is_array($data['name'])) {
            $data['name'] = $data['name'];
        }

        if (isset($data['desc']) && is_array($data['desc'])) {
            $data['desc'] = $data['desc'];
        }

        if (isset($data['course_goals']) && is_array($data['course_goals'])) {
            $data['course_goals'] = $data['course_goals'];
        }

        if (isset($data['target_audience']) && is_array($data['target_audience'])) {
            $data['target_audience'] = $data['target_audience'];
        }

        // if (isset($data['learning_format'])) {
        //     if (is_array($data['learning_format'])) {
        //         $data['learning_format'] = $data['learning_format'];
        //     } else {
        //         // If it's coming from the form fields, create array structure
        //         $data['learning_format'] = [
        //             'en' => $data['en'] ?? '',
        //             'ar' => $data['ar'] ?? ''
        //         ];
        //         // Remove the individual fields
        //         unset($data['en'], $data['ar']);
        //     }
        // }

        if (isset($data['tags'])) {
            $data['_tags'] = $data['tags'];
            unset($data['tags']);
        }

        if (isset($data['modules'])) {
            $data['_modules'] = $data['modules'];
            unset($data['modules']);
        }

        return $data;
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        return static::mutateFormDataBeforeSave($data);
    }

    public static function afterCreate(Course $record, array $data): void
    {
        if (isset($data['_tags'])) {
            $record->tags()->sync($data['_tags']);
        }
    }
    public static function afterSave(Course $record, array $data): void
    {
        if (isset($data['_tags'])) {
            $record->tags()->sync($data['_tags']);
        }
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Course Form')
                ->tabs([
                    // General Info Tab
                    Tabs\Tab::make(__('General Info'))->schema([
                        Section::make(__('Basic Info'))->schema([
                            TextInput::make('name.en')
                                ->label(__('filament/courses/courses.name') . ' (English)')
                                ->required(),
                            TextInput::make('name.ar')
                                ->label(__('filament/courses/courses.name') . ' (Arabic)')
                                ->required(),
                            Textarea::make('desc.en')
                                ->label(__('filament/courses/courses.description') . ' (English)')
                                ->required(),
                            Textarea::make('desc.ar')
                                ->label(__('filament/courses/courses.description') . ' (Arabic)')
                                ->required(),
                        ])->columns(2),
                    ]),

                    // Pricing Tab
                    Tabs\Tab::make(__('Pricing'))->schema([
                        Section::make(__('Pricing'))->schema([
                            TextInput::make('old_price')
                                ->label(__('Current Price'))
                                ->numeric()
                                ->required()
                                ->reactive(),

                            TextInput::make('discount_percentage')
                                ->label(__('Discount'))
                                ->numeric()
                                ->suffix('%')
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                    $oldPrice = $get('old_price');
                                    if ($oldPrice && $state !== null) {
                                        $discounted = $oldPrice - ($oldPrice * ($state / 100));
                                        $set('price', round($discounted, 2));
                                        $set('sale', $state > 0);
                                    } elseif ($oldPrice) {
                                        $set('price', $oldPrice);
                                        $set('sale', false);
                                    }
                                }),
                            TextInput::make('price')
                                ->label(__('Price After Discount'))
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->afterStateHydrated(function (callable $set, callable $get) {
                                    $price = $get('price');
                                    $oldPrice = $get('old_price');
                                    $discount = $get('discount_percentage');

                                    if (!$discount && $oldPrice && !$price) {
                                        $set('price', $oldPrice);
                                    }
                                }),
                            Toggle::make('sale')
                                ->label(__('On Sale'))
                                ->disabled()
                                ->dehydrated(fn () => true),
                        ])->columns(3),
                    ]),

                    // Classification Tab
                    Tabs\Tab::make(__('Classification'))->schema([
                        Section::make(__('Status & Category & Tags'))->schema([
                                Select::make('payment_status')
                                    ->label(__('filament/courses/courses.status'))
                                    ->required()
                                    ->options([
                                        'paid' => 'Paid',
                                        'free' => 'Free',
                                    ]),
                                Select::make('delivery_method')
                                    ->label(__('filament/courses/courses.delivery_method'))
                                    ->required()
                                    ->options([
                                        'live' => 'Live',
                                        'recorded' => 'Recorded',
                                    ]),
                                Select::make('category_id')
                                    ->label(__('filament/courses/courses.category'))
                                    ->required()
                                    ->relationship('category', 'name')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name['en'] ?? '')
                                    ->reactive(),
                                Select::make('tags')
                                ->label(__('Tags'))
                                ->multiple()
                                ->relationship('tags', 'id')
                                ->getOptionLabelFromRecordUsing(fn ($record) => $record->name['en'] ?? '')
                                ->preload()
                                ->searchable(),

                        ])->columns(3),

                        Section::make(__('Instructor'))->schema([
                                Select::make('instructor_id')
                                    ->label(__('filament/courses/courses.instructor'))
                                    ->options(
                                        User::where('user_type', 'instructor')
                                            ->pluck('name_en', 'id')
                                    )
                                    ->searchable()
                                    ->required(),
                        ]),
                    ]),

                    // Media Tab
                    Tabs\Tab::make(__('Media'))->schema([
                        Section::make(__('Media'))->schema([
                            FileUpload::make('image')
                                    ->label(__('filament/courses/courses.image'))
                                    ->disk('public')
                                    ->directory('CoursesImage')
                                    ->required(),
                            TextInput::make('main_video')
                                    ->label(__('filament/courses/courses.main_video_url'))
                                    ->required()
                                    ->placeholder(__('filament/courses/courses.paste_cloudinary_video_url_here')),
                            TextInput::make('free_video')
                                    ->label(__('filament/courses/courses.free_video_url'))
                                    ->placeholder(__('filament/courses/courses.paste_cloudinary_video_url_here')),
                        ])->columns(2),
                    ]),

                    // Goals & Users Tab
                    Tabs\Tab::make(__('Goals & Users'))->schema([
                        Section::make(__('Goals'))->schema([
                            Repeater::make('course_goals')
                                    ->label(__('filament/courses/courses.goals'))
                                    ->schema([
                                        TextInput::make('en')
                                            ->label(__('filament/courses/courses.goals') . ' (English)')
                                            ->required(),
                                        TextInput::make('ar')
                                            ->label(__('filament/courses/courses.goals') . ' (Arabic)')
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->minItems(1)
                                    ->addActionLabel('Add New Goal'),
                        ]),

                        Section::make(__('Target Audience'))->schema([
                            Repeater::make('target_audience')
                                    ->label(__('filament/courses/courses.users'))
                                    ->schema([
                                        TextInput::make('en')
                                            ->label(__('filament/courses/courses.users') . ' (English)')
                                            ->required(),
                                        TextInput::make('ar')
                                            ->label(__('filament/courses/courses.users') . ' (Arabic)')
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->minItems(1)
                                    ->addActionLabel('Add New Audience'),
                        ]),

                        // Section::make(__('Learning Formats'))->schema([
                        //     TextInput::make('learning_format.en')
                        //             ->label(__('filament/courses/courses.learning_format') . ' (English)')
                        //             ,
                        //     TextInput::make('learning_format.ar')
                        //             ->label(__('filament/courses/courses.learning_format') . ' (Arabic)')
                        //             ,
                        // ])->columns(2),
                    ]),

                    Tabs\Tab::make(__('Modules'))->schema([
                        Section::make(__('Course Modules'))->schema([
                            Repeater::make('modules')
                                ->relationship('modules')
                                ->schema([
                                    TextInput::make('title.en')
                                        ->label('Module Title (EN)')
                                        ->required(fn ($state) => filled($state))
                                        ->reactive(),
                                    TextInput::make('title.ar')
                                        ->label('Module Title (AR)')
                                        ->required(fn ($state) => filled($state))
                                        ->reactive(),

                                    Repeater::make('moduleItems')
                                        ->relationship('moduleItem')
                                        ->schema([
                                            TextInput::make('content.en')
                                                ->label('Item Title (EN)')
                                                ->required(fn ($state) => filled($state))
                                                ->reactive(),
                                            TextInput::make('content.ar')
                                                ->label('Item Title (AR)')
                                                ->required(fn ($state) => filled($state))
                                                ->reactive(),

                                            TextInput::make('video_path')
                                                ->label(__('filament/courses/courses.video_module'))
                                                ->placeholder(__('filament/courses/courses.paste_cloudinary_video_module_url_here'))
                                                ->required(),
                                            TextInput::make('duration')
                                                ->label('Duration')
                                                ->placeholder('HH:MM:SS')
                                                ->required()
                                                ->rule('regex:/^\d{2}:\d{2}:\d{2}$/')
                                                ->helperText('Enter duration in HH:MM:SS format, e.g., 00:04:15'),


                                        ])
                                        ->collapsed()
                                        ->addActionLabel('Add Module Item')
                                        ->deleteAction(fn ($action) => $action->requiresConfirmation()),
                                ])
                                ->collapsed()
                                ->addActionLabel('Add Module')
                                ->deleteAction(fn ($action) => $action->requiresConfirmation()),
                        
                    ]),
            ]),
            ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name.en')
                    ->label(__('filament/courses/courses.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('instructor.name_en')
                    ->label(__('filament/courses/courses.instructor')),
                TextColumn::make('price')
                    ->label(__('filament/courses/courses.price'))
                    ->sortable()
                    ->money('EGP'),
                TextColumn::make('discount_percentage')
                    ->label(__('filament/courses/courses.discount'))
                    ->sortable()
                    ->suffix('%'),
                TextColumn::make('category.name.en')
                    ->label(__('filament/courses/courses.category')),
                TextColumn::make('payment_status')
                    ->label(__('filament/courses/courses.status'))
                    ->formatStateUsing(fn ($state) => $state === 'paid' ? 'Paid' : 'Free'),
                TextColumn::make('delivery_method')
                    ->label(__('filament/courses/courses.delivery_method'))
                    ->formatStateUsing(fn ($state) => $state === 'live' ? 'Live' : 'Recorded'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'view' => Pages\ViewCourse::route('/{record}'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
