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
        $ref = Reference::where(['id' => $id])->first();
        return !empty($ref) ? $ref->label : '';
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
            '1. Неврологічні порушення',
            '1. Неврологічні порушення G45.0 – Транзиторна ішемічна атака (TIA) та супутні синдроми.',
            '1. Неврологічні порушення G46.0 – Синдром середньомозкової артерії.',
            '1. Неврологічні порушення G46.3 – Синдром задньої мозкової артерії.',
            '1. Неврологічні порушення G46.4 – Синдром мозочка (гемісферного).',
            '1. Неврологічні порушення G46.5 – Синдром стовбурової мозкової артерії.',
            '1. Неврологічні порушення G46.6 – Синдром базилярної артерії.',
            '1. Неврологічні порушення G46.7 – Інші уточнені судинні ураження мозку.',
            '1. Неврологічні порушення G81.9 – Геміплегія неуточнена.',
            '1. Неврологічні порушення G82.1 – Спастична параплегія.',
            '1. Неврологічні порушення G83.9 – Параліч неуточнений.',
            '2. Зорові порушення',
            '2. Зорові порушення	H53.4 – Дефекти поля зору.',
            '2. Зорові порушення	H53.8 – Інші уточнені функціональні порушення зору.',
            '2. Зорові порушення	H53.9 – Функціональні порушення зору неуточнені.',
            '2. Зорові порушення	H54.0 – Сліпота одного ока.',
            '2. Зорові порушення	H54.1 – Сліпота обох очей.',
            '2. Зорові порушення	H54.4 – Значне зниження зору обох очей.',
            '2. Зорові порушення	H55.0 – Ністагм та інші порушення рухів очей.',
            '2. Зорові порушення	H58.8 – Інші уточнені захворювання ока.',
            '3. Порушення, пов\'язані з ігноруванням сторони або втратою частини поля зору (схожі до візуального неглекту)',
            '3. Порушення, пов\'язані з ігноруванням сторони або втратою частини поля зору (схожі до візуального неглекту) R48.1 – Агнозія (зокрема, зорово-просторові розлади, пов\'язані з ігноруванням).',
            '3. Порушення, пов\'язані з ігноруванням сторони або втратою частини поля зору (схожі до візуального неглекту) R48.2 – Алекція (нездатність ідентифікувати символи в частині поля зору).',
            '3. Порушення, пов\'язані з ігноруванням сторони або втратою частини поля зору (схожі до візуального неглекту) R41.8 – Інші уточнені симптоми когнітивного порушення (може включати неглект).',
            '3. Порушення, пов\'язані з ігноруванням сторони або втратою частини поля зору (схожі до візуального неглекту) R41.9 – Когнітивне порушення неуточнене.',
            '3. Порушення, пов\'язані з ігноруванням сторони або втратою частини поля зору (схожі до візуального неглекту) R47.8 – Інші уточнені розлади мови та мови, включаючи сприйняття.',
            '3. Порушення, пов\'язані з ігноруванням сторони або втратою частини поля зору (схожі до візуального неглекту) H53.4 – Часткові дефекти поля зору (включає квадранопсію).',
        ];
    }
}
