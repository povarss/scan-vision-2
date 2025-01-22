<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Reference;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'title' => 'Чи часто у вас двоїться в очах?',
                'answers' => [
                    'Завжди',
                    'Часто',
                    'Рідко',
                    'Дуже рідко'
                ],
            ],
            [
                'title' => 'Чи помічаєте ви, що ігноруєте об’єкти з одного боку?',
                'answers' => [
                    'Завжди',
                    'Часто',
                    'Рідко',
                    'Ніколи'
                ]
            ],
            [
                'title' => 'Чи важко вам концентруватися на певному завданні?',
                'answers' => [
                    'Так',
                    'Ні',
                ]
            ],
            [
                'title' => 'Чи буває, що ви пропускаєте слова або рядки під час читання?',
                'answers' => [
                    'Завжди',
                    'Часто',
                    'Рідко',
                    'Ніколи'
                ]
            ],
            [
                'title' => 'Чи бувають у вас труднощі з розпізнаванням форм або об’єктів?',
                'answers' => [
                    'Завжди',
                    'Часто',
                    'Рідко',
                    'Ніколи'
                ]
            ],
            [
                'title' => 'Чи відчуваєте ви розмитість зору на одне або обидва ока?',
                'answers' => [
                    'Завжди',
                    'Часто',
                    'Рідко',
                    'Ніколи'
                ]
            ],
            [
                'title' => 'Чи помічаєте ви утруднення у фокусуванні погляду на об’єктах, що знаходяться на різній відстані?',
                'answers' => [
                    'Так',
                    'Ні',
                ]
            ],
            [
                'title' => 'Чи буває у вас втрата периферичного зору (не бачите, що відбувається з боків)?',
                'answers' => [
                    'Завжди',
                    'Часто',
                    'Рідко',
                    'Ніколи'
                ]
            ],
        ];

        foreach ($questions as $question) {
            $questionItem = Question::firstOrNew([
                'content' => $question['title']
            ]);
            $questionItem->save();

            Answer::where('question_id', $questionItem->id)->delete();

            foreach ($question['answers'] as $answer) {
                $answerItem = new Answer();
                $answerItem->content = $answer;
                $answerItem->question_id = $questionItem->id;
                $answerItem->save();
            }
        };
    }
}
