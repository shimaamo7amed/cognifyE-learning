<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Instructor;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Mail\InstructorAccepted;
use App\Mail\InstructorRejected;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use App\Jobs\SendInstructorAcceptedEmail;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InstructorResource\Pages;
use App\Filament\Resources\InstructorResource\RelationManagers;

class InstructorResource extends Resource
{
    protected static ?string $model = Instructor::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    public static function getNavigationGroup(): ?string
    {
        return __('filament/forms/FormsInstructors.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament/forms/FormsInstructors.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/forms/FormsInstructors.plural');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name_en')
                ->label(__('filament/forms/FormsInstructors.name_en')),
                TextInput::make('name_ar')
                ->label(__('filament/forms/FormsInstructors.name_ar')),
                TextInput::make('email')
                ->label(__('filament/forms/FormsInstructors.email')),
                TextInput::make('phone')
                ->label(__('filament/forms/FormsInstructors.phone')),
                TextInput::make('experince')
                ->label(__('filament/forms/FormsInstructors.experince')),
                TextInput::make('linkedIn')
                ->label(__('filament/forms/FormsInstructors.linkedIn')),
                TextInput::make('facebook')
                ->label(__('filament/forms/FormsInstructors.facebook')),
                TextInput::make('message')
                ->label(__('filament/forms/FormsInstructors.message')),
                FileUpload::make('cv')
                ->label(__('filament/forms/FormsInstructors.cv'))
                ->visibility('public')
                ->downloadable()
                ->openable()
                ->preserveFilenames(),
                FileUpload::make('image')
                ->required()
                            ->label(__('filament/forms/FormsInstructors.image'))
                            ->disk('public')
                            ->imageEditor()
                            ->imageEditorMode(2)
                            ->downloadable()
                            ->directory('InstructorImages'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('id'),
            TextColumn::make('name_en')->label(__('filament/forms/FormsInstructors.name_en')),
            TextColumn::make('email')->label(__('filament/forms/FormsInstructors.email')),
            TextColumn::make('phone')->label(__('filament/forms/FormsInstructors.phone')),
            TextColumn::make('experince')->label(__('filament/forms/FormsInstructors.experince')),
            TextColumn::make('message')->label(__('filament/forms/FormsInstructors.message')),
            ImageColumn::make("image")->label(__('filament/forms/FormsInstructors.image')),
            TextColumn::make('status')
                ->label(__('Status'))
                ->getStateUsing(fn($record) => $record->status ?? 'Pending')
            ->color(fn($record) => match($record->status ?? 'Pending') {
                'Accepted' => 'success',
                'Rejected' => 'danger',
                default => 'primary',
                }),
            ])
            ->actions([
        Tables\Actions\ViewAction::make(),
            Action::make('accept')
                ->label(__('filament/forms/FormsInstructors.accept'))
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->visible(fn($record) => $record->status !== 'Accepted' && $record->status !== 'Rejected')
                ->action(function ($record) {
                $password = Str::random(8);
                User::create([
                    'name' => $record->name_en,
                    'name_en' => $record->name_en,
                    'name_ar' => $record->name_ar,
                    'email' => $record->email,
                    'phone' => $record->phone,
                    'experience' => $record->experince,
                    'linkedIn' => $record->linkedIn,
                    'facebook' => $record->facebook,
                    'cv' => $record->cv,
                    'image' => $record->image,
                    'password' => Hash::make($password),
                    'user_type' => 'instructor',
                    'status' => 'approved'
                ]);

                $record->status = 'Accepted';
                $record->save();


                dispatch(new SendInstructorAcceptedEmail(
                    $record->email,
                    $record->name_en,
                    $password
                ));
            }),

        Action::make('reject')
            ->label(__('filament/forms/FormsInstructors.reject'))
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->requiresConfirmation()
            ->visible(fn($record) => $record->status !== 'Accepted' && $record->status !== 'Rejected')
            ->action(function ($record) {
                User::where('id', $record->id)->update([
                    'status' => 'rejected'
                ]);
                $record->status = 'Rejected';
                $record->save();
                Mail::to($record->email)->send(new InstructorRejected($record->name_en, $record->email));
            }),
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
            'index' => Pages\ListInstructors::route('/'),
            'create' => Pages\CreateInstructor::route('/create'),
            'view' => Pages\ViewInstructor::route('/{record}'),
            'edit' => Pages\EditInstructor::route('/{record}/edit'),
        ];
    }
}
