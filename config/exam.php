<?php

use App\Models\PatientExamOptions;

return [
    1 => [
        'id' => 1,
        'folder' => 'vision',
        'svgs' => [
            1 => ['1'],
            2 => ['1', '2'],
        ],
        'allSvgs' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11'],
        'levels' => [1 => ['size' => 76], 2 => ['size' => 57], 3 => ['size' => 38]],
        'default_size' => 76,
        'default_max_count' => 8,
        'modes' => [
            [
                'title' => "Single",
                'desc' => "Один цільовий стимул",
                'value' => "1",
                'icon' => ['icon' => "tabler-box-multiple-1", 'size' => "28"],
                'description' =>
                " <h1 style='text-align: center'>Особливості Single Task</h1>\n" .
                    "    \n" .
                    "    <p>Пацієнт має ідентифікувати один цільовий стимул.</p>\n" .
                    "    \n" .
                    "    <h2>Підходить для пацієнтів, які:</h2>\n" .
                    "    <ul>\n" .
                    "        <li>\n" .
                    "            <strong>На початковому етапі реабілітації:</strong> Пацієнти з важкими когнітивними чи моторними порушеннями, \n" .
                    "            для яких необхідна базова оцінка уваги та здатності до концентрації.\n" .
                    "        </li>\n" .
                    "        <li>\n" .
                    "            <strong>Зі значним візуально-просторовим неглектом:</strong> Коли пацієнт ігнорує цілу частину поля зору і потребує простих завдань \n" .
                    "            для відновлення базової навички розпізнавання об’єктів.\n" .
                    "        </li>\n" .
                    "        <li>\n" .
                    "            <strong>З хронічними симптомами неглекту:</strong> Якщо пацієнт демонструє низький рівень уваги, Single Task дозволяє \n" .
                    "            тренувати точність і зосередженість на одній задачі.\n" .
                    "        </li>\n" .
                    "        <li>\n" .
                    "            <strong>Потребують поступового підвищення навантаження:</strong> Виконання однієї задачі допомагає уникнути перевантаження когнітивної системи.\n" .
                    "        </li>\n" .
                    "    </ul>",
            ],
            [
                'title' => "Dual",
                'desc' => "Два цільових стимули",
                'value' => "2",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' =>
                " <h1 style='text-align: center'>Особливості Dual Task</h1>\n" .
                    "    \n" .
                    "    <p>Пацієнт має знайти та викреслити два типи цільових стимулів одночасно.</p>\n" .
                    "    \n" .
                    "    <h2>Підходить для пацієнтів, які:</h2>\n" .
                    "    <ul>\n" .
                    "        <li>\n" .
                    "            <strong>На середньому або пізньому етапі реабілітації:</strong> Пацієнти, які вже досягли прогресу в одночасній роботі \n" .
                    "            уваги і рухів, але потребують складніших тренувань.\n" .
                    "        </li>\n" .
                    "        <li>\n" .
                    "            <strong>З легким або залишковим неглектом:</strong> Dual Task допомагає тренувати здатність розрізняти різні стимули, \n" .
                    "            обробляти інформацію та реагувати в багатозадачних ситуаціях.\n" .
                    "        </li>\n" .
                    "        <li>\n" .
                    "            <strong>Для відновлення багатозадачності:</strong> Пацієнти, які мають труднощі в реальних життєвих ситуаціях, \n" .
                    "            таких як розмова під час ходьби або виконання складних завдань.\n" .
                    "        </li>\n" .
                    "        <li>\n" .
                    "            <strong>Для адаптації до реального життя:</strong> Dual Task допомагає моделювати умови, які зустрічаються в побуті чи роботі, \n" .
                    "            коли потрібно одночасно виконувати кілька дій.\n" .
                    "        </li>\n" .
                    "    </ul>",
            ],
        ],
        'mode_type' => 1,
    ],
    2 => [
        'id' => 2,
        'folder' => 'vision2',
        'svgs' => [
            1 => ['Alpaca1'],
            2 => ['Deer2'],
            3 => ['Horse3'],
            4 => ['Monkey4'],
            5 => ['Pig5'],
        ],
        'allSvgs' => ['Alpaca1', 'Deer2', 'Horse3', 'Monkey4', 'Pig5', 'Pig6', 'Quail7', 'Rabbit8', 'Sheep9', 'Turtle10', 'Turtle11'],
        'levels' => [1 => ['count' => 15, 'size' => 76], 2 => ['count' => 22, 'size' => 57], 3 => ['count' => 30, 'size' => 38]],
        'default_size' => 76,
        'default_max_count' => 8,
        'modes' => [
            [
                'title' => "1",
                'image' => 'vision2/Alpaca1.svg',
                'desc' =>  "1",
                'value' => "1",
                'icon' => ['icon' => "tabler-box-multiple-1", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "2",
                'value' => "2",
                'image' => 'vision2/Deer2.svg',
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "3",
                'image' => 'vision2/Horse3.svg',
                'value' => "3",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "4",
                'image' => 'vision2/Monkey4.svg',
                'value' => "4",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "5",
                'image' => 'vision2/Pig5.svg',
                'value' => "5",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
        ],
        'mode_type' => 2,
    ],
    3 => [
        'id' => 3,
        'folder' => 'vision3',
        'svgs' => [
            1 => ['circle'],
            2 => ['penta'],
            3 => ['quad'],
            4 => ['romb'],
            5 => ['star'],
        ],
        'allSvgs' => ['circle', 'penta', 'quad', 'romb', 'star'],
        'levels' => [1 => ['count' => 15, 'size' => 76], 2 => ['count' => 22, 'size' => 57], 3 => ['count' => 30, 'size' => 38]],
        'default_size' => 76,
        'default_max_count' => 8,
        'modes' => [
            [
                'title' => "1",
                'image' => 'vision3/circle.svg',
                'desc' =>  "1",
                'value' => "1",
                'icon' => ['icon' => "tabler-box-multiple-1", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "2",
                'value' => "2",
                'image' => 'vision3/penta.svg',
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "3",
                'image' => 'vision3/quad.svg',
                'value' => "3",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "4",
                'image' => 'vision3/romb.svg',
                'value' => "4",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "5",
                'image' => 'vision3/star.svg',
                'value' => "5",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
        ],
        'mode_type' => 3,
    ],
    4 => [
        'id' => 4,
        'folder' => 'shulte_table',
        'levels' => [
            1 => [
                'x' => 3,
                'y' => 3,
                'title' => "Легкий",
                'desc' => "сітка 3х3",
                'value' => "1",
                'icon' => ['icon' => "tabler-rocket", 'size' => "28"],
                'description' =>       " <h1 style='text-align: center'>Легкий рівень</h1>\n" .
                    "    \n" .
                    "    <h2>Кому підходить?</h2>\n" .
                    "    <ul>\n" .
                    "        <li>Пацієнтам із помірними когнітивними або зорово-моторними порушеннями.</li>\n" .
                    "        <li>Тим, хто вже досяг прогресу на легкому рівні.</li>\n" .
                    "        <li>Для пацієнтів із залишковими симптомами неглекту.</li>\n" .
                    "    </ul>\n" .
                    "    \n" .
                    "    <h2>Характеристики тесту:</h2>\n" .
                    "    <ul>\n" .
                    "        <li>Використовується <strong>Single Task</strong> або <strong>Dual Task</strong>, залежно від цілей терапії.</li>\n" .
                    "        <li>Збільшена кількість стимулів на дошці, які розташовані щільніше.</li>\n" .
                    "        <li>Візуальні стимули більш схожі один на одного, включаючи відволікаючі елементи.</li>\n" .
                    "    </ul>\n" .
                    "    \n" .
                    "    <h2>Мета:</h2>\n" .
                    "    <ul>\n" .
                    "        <li>Тренувати здатність обробляти більшу кількість інформації.</li>\n" .
                    "        <li>Покращувати швидкість реакції та вибіркову увагу.</li>\n" .
                    "    </ul>",
            ],
            2 => [
                'x' => 4,
                'y' => 4,
                'title' => "Середній",
                'desc' => "сітка 4х4",
                'value' => "2",
                'icon' => ['icon' => "tabler-star", 'size' => "28"],
                'description' =>       " <h1 style='text-align: center'>Середній рівень</h1>\n" .
                    "    \n" .
                    "    <h2>Кому підходить?</h2>\n" .
                    "    <ul>\n" .
                    "        <li>Пацієнтам із помірними когнітивними або зорово-моторними порушеннями.</li>\n" .
                    "        <li>Тим, хто вже досяг прогресу на легкому рівні.</li>\n" .
                    "        <li>Для пацієнтів із залишковими симптомами неглекту.</li>\n" .
                    "    </ul>\n" .
                    "    \n" .
                    "    <h2>Характеристики тесту:</h2>\n" .
                    "    <ul>\n" .
                    "        <li>Використовується <strong>Single Task</strong> або <strong>Dual Task</strong>, залежно від цілей терапії.</li>\n" .
                    "        <li>Збільшена кількість стимулів на дошці, які розташовані щільніше.</li>\n" .
                    "        <li>Візуальні стимули більш схожі один на одного, включаючи відволікаючі елементи.</li>\n" .
                    "    </ul>\n" .
                    "    \n" .
                    "    <h2>Мета:</h2>\n" .
                    "    <ul>\n" .
                    "        <li>Тренувати здатність обробляти більшу кількість інформації.</li>\n" .
                    "        <li>Покращувати швидкість реакції та вибіркову увагу.</li>\n" .
                    "    </ul>",
            ],
            3 => [
                'x' => 5,
                'y' => 5,
                'title' => "Важкий",
                'desc' => "сітка 5х5",
                'value' => "3",
                'icon' => ['icon' => "tabler-crown", 'size' => "28"],
                'description' =>       " <h1 style='text-align: center'>Середній рівень</h1>\n" .
                    "    \n" .
                    "    <h2>Кому підходить?</h2>\n" .
                    "    <ul>\n" .
                    "        <li>Пацієнтам із помірними когнітивними або зорово-моторними порушеннями.</li>\n" .
                    "        <li>Тим, хто вже досяг прогресу на легкому рівні.</li>\n" .
                    "        <li>Для пацієнтів із залишковими симптомами неглекту.</li>\n" .
                    "    </ul>\n" .
                    "    \n" .
                    "    <h2>Характеристики тесту:</h2>\n" .
                    "    <ul>\n" .
                    "        <li>Використовується <strong>Single Task</strong> або <strong>Dual Task</strong>, залежно від цілей терапії.</li>\n" .
                    "        <li>Збільшена кількість стимулів на дошці, які розташовані щільніше.</li>\n" .
                    "        <li>Візуальні стимули більш схожі один на одного, включаючи відволікаючі елементи.</li>\n" .
                    "    </ul>\n" .
                    "    \n" .
                    "    <h2>Мета:</h2>\n" .
                    "    <ul>\n" .
                    "        <li>Тренувати здатність обробляти більшу кількість інформації.</li>\n" .
                    "        <li>Покращувати швидкість реакції та вибіркову увагу.</li>\n" .
                    "    </ul>",

            ],
        ],
    ],
    5 => [
        'id' => 5,
        'folder' => 'vision5',
        'svgs' => [
            1 => ['A'],
            2 => ['O'],
            3 => ['U'],
            4 => ['B'],
            5 => ['M'],
            6 => ['P'],
        ],
        'allSvgs' => ['A', 'O', 'U', 'B', 'M'],
        'levels' => [1 => ['count' => 15, 'size' => 76], 2 => ['count' => 22, 'size' => 57], 3 => ['count' => 30, 'size' => 38]],
        'default_size' => 76,
        'default_max_count' => 8,
        'modes' => [
            [
                'title' => "1",
                'image' => 'vision5/A.svg',
                'desc' =>  "1",
                'value' => "1",
                'icon' => ['icon' => "tabler-box-multiple-1", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "2",
                'value' => "2",
                'image' => 'vision5/O.svg',
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "3",
                'image' => 'vision5/U.svg',
                'value' => "3",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "4",
                'image' => 'vision5/B.svg',
                'value' => "4",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "5",
                'image' => 'vision5/M.svg',
                'value' => "5",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "6",
                'image' => 'vision5/P.svg',
                'value' => "6",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
        ],
        'mode_type' => 5,
    ],
    6 => [
        'id' => 6,
        'folder' => 'vision6',
        'svgs' => [
            1 => ['1'],
            2 => ['2'],
            3 => ['3'],
            4 => ['4'],
            5 => ['5'],
            6 => ['6'],
            7 => ['7'],
            8 => ['8'],
            9 => ['9'],
        ],
        'allSvgs' => ['1', '2', '3', '4', '5', '6', '7', '8', '9'],
        'levels' => [1 => ['count' => 15, 'size' => 76], 2 => ['count' => 22, 'size' => 57], 3 => ['count' => 30, 'size' => 38]],
        'default_size' => 76,
        'default_max_count' => 8,
        'modes' => [
            [
                'title' => "1",
                'image' => 'vision6/1.svg',
                'desc' =>  "1",
                'value' => "1",
                'icon' => ['icon' => "tabler-box-multiple-1", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "2",
                'value' => "2",
                'image' => 'vision6/2.svg',
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "3",
                'image' => 'vision6/3.svg',
                'value' => "3",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "4",
                'image' => 'vision6/4.svg',
                'value' => "4",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "5",
                'image' => 'vision6/5.svg',
                'value' => "5",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "6",
                'image' => 'vision6/6.svg',
                'value' => "6",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "7",
                'image' => 'vision6/7.svg',
                'value' => "7",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "8",
                'image' => 'vision6/8.svg',
                'value' => "8",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
            [
                'title' => "9",
                'image' => 'vision6/9.svg',
                'value' => "9",
                'icon' => ['icon' => "tabler-box-multiple-2", 'size' => "28"],
                'description' => ''
            ],
        ],
        'mode_type' => 6,
    ],
];
