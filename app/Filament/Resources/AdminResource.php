<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\Admin;
use App\Rules\PersianMobileNumber;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Password;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DateTimePicker;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('lastname')
                    ->label("Last Name")
                    ->required()
                    ->maxLength(255),
                TextInput::make('username')
                    ->label("UserName")
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->label('Password')
                    ->required()
                    ->rules([
                        'string',
                        'min:8',
                        'regex:/[@$!%*?&#]/',
                    ])
                    ->validationMessages([
                        'regex' => 'The password must contain at least one special character (@, $, !, %, *, ?, &, #).',
                    ]),
                TextInput::make('mobile')
                    ->label("Mobile")
                    ->maxLength(20)
                    ->required()
                    ->rules([new PersianMobileNumber()]),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255),

                DatePicker::make('birth_date')->required()
                    ->jalali(),
                Toggle::make('super_admin')
                    ->label("Super Admin")
                    ->inline(false),
                FileUpload::make('attachment')
                    ->directory('form-attachments')
                    ->visibility('private')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
