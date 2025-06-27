<?php

namespace App\Filament\Resources;

use App\Enum\Gender;
use App\Filament\Resources\AdminResource\Pages;
use App\Models\Admin;
use App\Models\City;
use App\Rules\PersianMobileNumber;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Password;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

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
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->email()
                    ->maxLength(255),

                DatePicker::make('birth_date')->required()
                    ->jalali(),
                Toggle::make('super_admin')
                    ->label("Super Admin")
                    ->inline(false),
                FileUpload::make('pic')
                    ->columnSpan('full')
                    ->directory('avatar')
                    ->image(),
                Select::make("gender")
                    ->enum(Gender::class)
                    ->options(Gender::class),
                Select::make('province_id')
                    ->label('Province')
                    ->relationship('province', 'name')
                    ->live()
                    ->required()
                    ->searchable()
                    ->afterStateUpdated(function (callable $set) {
                        return $set('city_id', null);
                    }),

                Select::make('city_id')
                    ->label('City')
                    ->relationship('city', 'name')
                    ->required()
                    ->live()
                    ->searchable()
                    ->rules(fn(callable $get) => [
                        Rule::exists('cities', 'id')
                            ->where('province_id', $get('province_id')),
                    ])
                    ->options(function (callable $get) {
                        $provinceId = $get('province_id');
                        if (!$provinceId) {
                            return [];
                        }
                        return City::query()->where('province_id', $provinceId)->pluck('name', 'id');
                    })
                    ->reactive()
                    ->disabled(fn(callable $get) => !$get('province_id'))
                    ->editOptionForm(City::getForm())
                    ->createOptionForm(City::getForm()),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextInputColumn::make("name")->rules(["required"])->sortable()->searchable(),
                TextColumn::make("lastname"),
                ImageColumn::make('pic')->defaultImageUrl(function ($record){
                    return sprintf("https://ui-avatars.com/api/?email=%s",$record["email"]);
                }),
                TextColumn::make("mobile"),
                TextColumn::make("email"),
                TextColumn::make("province.name"),
                TextColumn::make("city.name"),
                TextColumn::make("birth_date")->jalaliDate('Y-m-d'),
                TextColumn::make('created_at')
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
