<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = [
        'key_',
        'label'
    ];

    const KEY_REGION = 'region';

    public function getAvailableKeys()
    {
        return [
            self::KEY_REGION
        ];
    }
    public function getRefItems($refKey)
    {
        return Reference::where(['key_' => $refKey])->get()->map(function ($item) {
            return ['title' => $item->label, 'value' => $item->id];
        });
    }
    public static function getInitValues()
    {
        return [
            self::KEY_REGION => self::getDefinedRegions()
        ];
    }
    public static function getDefinedRegions()
    {
        return [
            'Автономна Республіка Крим',
            'Вінницька',
            'Волинська',
            'Дніпропетровська',
            'Донецька',
            'Житомирська',
            'Закарпатська',
            'Запорізька',
            'Івано-Франківська',
            'Київська',
            'Кіровоградська',
            'Луганська',
            'Львівська',
            'Миколаївська',
            'Одеська',
            'Полтавська',
            'Рівненська',
            'Сумська',
            'Тернопільська',
            'Харківська',
            'Херсонська',
            'Хмельницька',
            'Черкаська',
            'Чернівецька',
            'Чернігівська',
        ];
    }
}
