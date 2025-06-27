<?php

namespace App\Models;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'tel_prefix',
        'lang',
        'province_id',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public static function getForm():array
    {
       return [
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('tel_prefix')->rules(["numeric", 'digits_between:3,5']),
            Select::make('province_id')->label("Province")
                ->relationship('province', 'name')
                ->searchable()
        ];
    }
}
