<?php

namespace App\Services;

use App\Dto\ExamResultDto;

class GetRecommendationService
{
    public function __construct(public ExamResultDto $examResultDto) {}
    public $messages = [];
    public $recommendations = [];
    public function getInformation()
    {
        if ($this->examResultDto->examType == 2) {
            return [
                'messages' => $this->messages,
                'recommendations' => $this->recommendations
            ];
        }

        $this->addMessage($this->getLeftCorrect());
        $this->addMessage($this->getRightCorrect());

        $this->addMessage($this->getLeftIncorrectClicked());
        $this->addMessage($this->getRightIncorrectClicked());

        $this->addMessage($this->getIssue1());
        $this->addMessage($this->getIssue2());
        $this->addMessage($this->getIssue3());
        $this->addMessage($this->getIssue4());
        $this->addMessage($this->getIssue5());

        $this->addRecommend($this->getRecommend1());
        $this->addRecommend($this->getRecommend2());
        $this->addRecommend($this->getRecommend3());
        $this->addRecommend($this->getRecommend4());
        $this->addRecommend($this->getRecommend5());

        return [
            'messages' => $this->messages,
            'recommendations' => $this->recommendations
        ];
    }

    public function addMessage($message)
    {
        if (!empty($message)) {
            $this->messages[] = $message;
        }
    }

    public function addRecommend($recommends)
    {
        if (!empty($recommends)) {
            $this->recommendations = array_merge($this->recommendations, $recommends);
        }
    }

    public function getLeftCorrect()
    {
        $text = match (true) {
            $this->examResultDto->leftCorrectRemainedCount == 1 => "Пропущено 1 цільовий стимул із лівого боку. Це незначна кількість, яка відповідає нормі, але потребує подальшого скринінгу",
            $this->examResultDto->leftCorrectRemainedCount == 2 => "Пропущено 2 цільові стимули із лівого боку. Це може вказувати на незначні труднощі у сприйнятті візуальної інформації з лівого боку та наявність незначного просторового неглекту.",
            $this->examResultDto->leftCorrectRemainedCount == 3 => "Пропущено 3 цільові стимули із лівого боку. Це свідчить про наявність помірного просторового неглекту.",
            $this->examResultDto->leftCorrectRemainedCount == 4 => "Пропущено 4 цільові стимули із лівого боку. Це свідчить про наявність значних труднощів у сприйнятті лівої частини поля зору, що характерно для просторового неглекту.",
            $this->examResultDto->leftCorrectRemainedCount >= 5 => "Пропущено 5 цільових стимулів із лівого боку. Це свідчить про наявність явного просторового неглекту.",
        };
        return $text;
    }

    public function getRightCorrect()
    {
        $text = match (true) {
            $this->examResultDto->rightCorrectRemainedCount == 1 => "Пропущено 1 цільовий стимул із правого боку. Це відповідає нормі, але потребує подальшого скринінгу",
            $this->examResultDto->rightCorrectRemainedCount == 2 => "Пропущено 2 цільові стимули із правого боку. Це може вказувати на незначні труднощі у сприйнятті правої частини поля зору.",
            $this->examResultDto->rightCorrectRemainedCount == 3 => "Пропущено 3 цільові стимули із правого боку. Це може свідчити про труднощі в обробці інформації на правій стороні.",
            $this->examResultDto->rightCorrectRemainedCount == 4 => "Пропущено 4 цільові стимули із правого боку. Це може бути пов’язане із когнітивними порушеннями., як ігнорування правої сторони простору або наявність правосторонньої геміанопсії",
            $this->examResultDto->rightCorrectRemainedCount >= 5 => "Пропущено 5 та\або більше цільових стимулів із правого боку. Це свідчить про значне порушення уваги до правого боку. Може вказувати на правосторонній просторовий неглект, або наявність геміанопсії",
        };
        return $text;
    }

    public function getLeftIncorrectClicked()
    {
        // dd($this->examResultDto->leftIncorrectClickedCount);
        $text = match (true) {
            $this->examResultDto->leftIncorrectClickedCount == 1 => "Виявлено 1 фальшиву ціль із лівого боку. Це відповідає нормі, але потребує подальшого скринінгу.",
            $this->examResultDto->leftIncorrectClickedCount == 2 => "Виявлено 2 фальшиві цілі із лівого боку. Це може свідчити про початкові труднощі у розпізнаванні об’єктів та вказувати на незачний прояв обʼєктного неглекту.",
            $this->examResultDto->leftIncorrectClickedCount == 3 => "Виявлено 3 фальшиві цілі із лівого боку. Це може свідчити про помірні порушення в обробці візуальної інформації, характерні для об’єктного неглекту.",
            $this->examResultDto->leftIncorrectClickedCount == 4 => "Виявлено 4 фальшиві цілі із лівого боку. Це свідчить про наявність об’єктного неглекту.",
            $this->examResultDto->leftIncorrectClickedCount >= 5 => "Виявлено 5 та більше фальшивих цілей із лівого боку. Це явна ознака об’єктного неглекту.",
            default => '',
        };
        return $text;
    }

    public function getRightIncorrectClicked()
    {
        $text = match (true) {
            $this->examResultDto->rightIncorrectClickedCount == 1 => "Виявлено 1 фальшиву ціль із правого боку. Це відповідає нормі, потребує подальшого скринінгу",
            $this->examResultDto->rightIncorrectClickedCount == 2 => "Виявлено 2 фальшиві цілі із правого боку. Це може вказувати на незначні труднощі у розпізнаванні об’єктів.",
            $this->examResultDto->rightIncorrectClickedCount == 3 => "Виявлено 3 фальшиві цілі із правого боку. Це може свідчити про когнітивні труднощі сприймання об’єктів з правої сторони.",
            $this->examResultDto->rightIncorrectClickedCount == 4 => "Виявлено 4 фальшиві цілі із правого боку. Це свідчить про порушення у розпізнаванні об’єктів з правої сторони.",
            $this->examResultDto->rightIncorrectClickedCount >= 5 => "Виявлено 5та більше  фальшивих цілей із правого боку. Це може свідчити про значне порушення когнітивних функцій та сприймання цілісного образу обʼєкту. Потребує подальшого скринінгу.",
            default => '',
        };
        return $text;
    }

    public function getIssue1()
    {
        $text = match (true) {
            $this->examResultDto->examType == 1 && $this->examResultDto->leftCorrectRemainedCount >= 5 && $this->examResultDto->incorrectCount == 0  => "Пропущено 5 та більше цільові стимули із лівого боку. Це свідчить про наявність просторового неглекту із явним ігноруванням стимулів у лівій частині поля зору.",
            $this->examResultDto->examType == 1 && $this->examResultDto->leftCorrectRemainedCount >= 5 && $this->examResultDto->incorrectCount == 1 => "Пропущено 5 цільових стимулів із лівого боку та виявлено 1 фальшиву ціль. Це свідчить про значний просторовий неглект з невеликими труднощами у розпізнаванні об’єктів.",
            default => '',
        };
        return $text;
    }

    public function getIssue2()
    {
        $text = match (true) {
            $this->examResultDto->examType == 2  && $this->examResultDto->correctRemainedCount == 0 &&  $this->examResultDto->leftIncorrectClickedCount == 4 => "Виявлено 3 фальшиві цілі із правого боку. Це може свідчити про когнітивні труднощі сприймання об’єктів з правої сторони.",
            $this->examResultDto->examType == 2  && $this->examResultDto->correctRemainedCount == 1 &&  $this->examResultDto->leftIncorrectClickedCount >= 5 => "Пропущено 1 цільовий стимул та виявлено 5 фальшивих цілей із лівого боку. Це свідчить про об’єктний неглект, що проявляється значними труднощами у розпізнаванні частин об’єктів.",
            default => '',
        };
        return $text;
    }

    public function getIssue3()
    {
        $text = match (true) {
            $this->examResultDto->leftCorrectRemainedCount >= 4 && $this->examResultDto->leftIncorrectClickedCount == 2  => "Пропущено 4 та більше  цільові стимули та виявлено 2 фальшиві цілі із лівого боку. Це свідчить про наявність як просторового, так і об’єктного неглекту, що значно впливає на сприйняття лівої частини поля зору.",
            $this->examResultDto->leftCorrectRemainedCount >= 4 && $this->examResultDto->leftIncorrectClickedCount == 4  => "Пропущено 4 та більше цільових стимулів та виявлено 4 фальшиві цілі із лівого боку. Це явна ознака комбінованого порушення — просторового та об’єктного неглекту.",
            default => '',
        };
        return $text;
    }

    public function getIssue4()
    {
        $text = match (true) {
            $this->examResultDto->rightCorrectRemainedCount == 3 && $this->examResultDto->rightIncorrectClickedCount == 4  => "Пропущено 3 цільові стимули та виявлено 4 фальшиві цілі із правого боку. Це свідчить про когнітивні труднощі, які проявляються у порушенні уваги та розпізнаванні об’єктів.",
            $this->examResultDto->rightCorrectRemainedCount == 2 && $this->examResultDto->rightIncorrectClickedCount == 5  => "Пропущено 2 цільові стимули та виявлено 5 фальшивих цілей із правого боку. Це свідчить про значні когнітивні труднощі у розпізнаванні правосторонніх об’єктів.",
            default => '',
        };
        return $text;
    }
    public function getIssue5()
    {
        $text = match (true) {
            $this->examResultDto->correctRemainedCount == 0 && $this->examResultDto->leftIncorrectClickedCount == 1  => "Пропущених цільових стимулів немає, а виявлено лише 1 фальшиву ціль із лівого боку. Це відповідає нормі.",
            $this->examResultDto->rightCorrectRemainedCount == 1 && $this->examResultDto->rightIncorrectClickedCount == 2  => "Пропущено 1 цільовий стимул та виявлено 2 фальшиві цілі із правого боку. Це може бути варіантом норми або вказувати на незначні труднощі у концентрації.",
            default => '',
        };
        return $text;
    }

    public function getRecommend1()
    {
        $text = match (true) {
            $this->examResultDto->examType == 1 && $this->examResultDto->leftCorrectRemainedCount == 4 && $this->examResultDto->incorrectCount == 0  => [
                "Виконувати вправи на активацію уваги до лівої частини поля зору (наприклад, пошук стимулів у лівій частині простору).",
                "Використовувати зорово-моторні тренування для компенсації неглекту.",
                "Проводити повторні оцінки через 2-4 тижні для оцінки динаміки.",
            ],
            $this->examResultDto->examType == 1 && $this->examResultDto->leftCorrectRemainedCount == 5 && $this->examResultDto->incorrectCount == 1  => [
                "Розробити індивідуальний реабілітаційний план із включенням візуальних вправ, що акцентуються на лівій стороні.",
                "Використовувати техніки зорового сканування для стимуляції лівого зорового поля.",
                "Проводити реабілітацію під наглядом спеціаліста 2-3 рази на тиждень.",
            ],
            default => [],
        };
        return $text;
    }

    public function getRecommend2()
    {
        $text = match (true) {
            $this->examResultDto->examType == 2 && $this->examResultDto->correctRemainedCount == 0 && $this->examResultDto->leftIncorrectClickedCount == 4  => [
                "Використовувати тренування з розпізнавання об’єктів у лівій частині зорового поля (наприклад, пошук схожих об’єктів).",
                "Застосовувати завдання з підвищеною складністю для покращення уваги до деталей.",
                "Проводити вправи на когнітивну інтеграцію зорової інформації.",
            ],
            $this->examResultDto->examType == 2 && $this->examResultDto->correctRemainedCount == 1 && $this->examResultDto->leftIncorrectClickedCount >= 5  => [
                "Використовувати вправи на точне розпізнавання об’єктів із різними характеристиками (розмір, форма, колір).",
                "Залучати вправи на сканування та ідентифікацію об’єктів у лівій частині поля зору.",
                "Розробити програму інтенсивної терапії з включенням когнітивних тренувань.",
            ],
            default => [],
        };
        return $text;
    }

    public function getRecommend3()
    {
        $text = match (true) {
            $this->examResultDto->leftCorrectRemainedCount == 4 && $this->examResultDto->incorrectCount == 2  => [
                "Використовувати комбіновані вправи, що стимулюють і увагу до простору, і розпізнавання об’єктів.",
                "Проводити вправи на пошук цільових стимулів у лівій частині поля зору зі збільшенням складності завдання.",
                "Використовувати оптичну корекцію або спеціальні пристрої для компенсації неглекту.",
            ],
            $this->examResultDto->leftCorrectRemainedCount >= 5 && $this->examResultDto->incorrectCount == 4  => [
                "Провести консультацію з неврологом для уточнення ступеня ураження.",
                "Застосовувати інтегровані підходи до терапії, що включають когнітивні, зорові та моторні тренування.",
                "Використовувати адаптовані методики, наприклад, оптокінетичне тренування.",
            ],
            default => [],
        };
        return $text;
    }

    public function getRecommend4()
    {
        $text = match (true) {
            $this->examResultDto->rightCorrectRemainedCount == 3 && $this->examResultDto->incorrectCount == 4  => [
                'Використовувати когнітивні тренування, спрямовані на покращення концентрації та уваги.',
                'Включити вправи на розвиток зорово-моторної координації.',
                'Проводити заняття 2-3 рази на тиждень із поступовим збільшенням складності завдань.',
            ],
            $this->examResultDto->rightCorrectRemainedCount == 2 && $this->examResultDto->incorrectCount == 5  => [
                "Включити вправи з підвищеною когнітивною складністю для тренування розпізнавання об’єктів у правій частині поля зору.",
                "Проводити комплексну терапію із залученням спеціалістів з когнітивної реабілітації.",
                "Перевірити супутні неврологічні порушення та адаптувати реабілітаційний план.",
            ],
            default => [],
        };
        return $text;
    }

    public function getRecommend5()
    {
        $text = match (true) {
            $this->examResultDto->correctRemainedCount == 0 && $this->examResultDto->leftIncorrectClickedCount == 1  => [
                "Ніякої спеціальної терапії не потрібно. Рекомендується проводити контрольні оцінки через 6 місяців."
            ],
            $this->examResultDto->rightCorrectRemainedCount == 1 && $this->examResultDto->incorrectCount == 2  => [
                "Виконувати вправи для покращення концентрації та уваги.",
                "Контрольні тести через 2-3 місяці для відстеження динаміки.",
            ],
            default => [],
        };
        return $text;
    }
}
