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
    const KEY_DIAGNOSE = 'diagnose';

    public function getAvailableKeys()
    {
        return [
            self::KEY_REGION,
            self::KEY_DIAGNOSE,
        ];
    }
    public function getRefItems($refKey)
    {
        return Reference::where(['key_' => $refKey])->get()->map(function ($item) {
            return ['title' => $item->label, 'value' => $item->id];
        });
    }

    public static function getRefLabel($id)
    {
        $ref =  Reference::where(['id' => $id])->first();
        return !empty($ref) ?  $ref->label : '';
    }

    public static function getInitValues()
    {
        return [
            self::KEY_REGION => self::getDefinedRegions(),
            self::KEY_DIAGNOSE => self::getDefinedDiagnoses(),
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

    public static function getDefinedDiagnoses()
    {
        return [
            'Диагноз',
        ];
    }
}
