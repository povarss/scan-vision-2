const modes = {
  1: [
    {
      title: "Single",
      desc: "Один цільовий стимул",
      value: "1",
      icon: { icon: "tabler-box-multiple-1", size: "28" },
      description:
        " <h1 style='text-align: center'>Особливості Single Task</h1>\n" +
        "    \n" +
        "    <p>Пацієнт має ідентифікувати один цільовий стимул.</p>\n" +
        "    \n" +
        "    <h2>Підходить для пацієнтів, які:</h2>\n" +
        "    <ul>\n" +
        "        <li>\n" +
        "            <strong>На початковому етапі реабілітації:</strong> Пацієнти з важкими когнітивними чи моторними порушеннями, \n" +
        "            для яких необхідна базова оцінка уваги та здатності до концентрації.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Зі значним візуально-просторовим неглектом:</strong> Коли пацієнт ігнорує цілу частину поля зору і потребує простих завдань \n" +
        "            для відновлення базової навички розпізнавання об’єктів.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>З хронічними симптомами неглекту:</strong> Якщо пацієнт демонструє низький рівень уваги, Single Task дозволяє \n" +
        "            тренувати точність і зосередженість на одній задачі.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Потребують поступового підвищення навантаження:</strong> Виконання однієї задачі допомагає уникнути перевантаження когнітивної системи.\n" +
        "        </li>\n" +
        "    </ul>",
    },
    {
      title: "Dual",
      desc: "Два цільових стимули",
      value: "2",
      icon: { icon: "tabler-box-multiple-2", size: "28" },
      description:
        " <h1 style='text-align: center'>Особливості Dual Task</h1>\n" +
        "    \n" +
        "    <p>Пацієнт має знайти та викреслити два типи цільових стимулів одночасно.</p>\n" +
        "    \n" +
        "    <h2>Підходить для пацієнтів, які:</h2>\n" +
        "    <ul>\n" +
        "        <li>\n" +
        "            <strong>На середньому або пізньому етапі реабілітації:</strong> Пацієнти, які вже досягли прогресу в одночасній роботі \n" +
        "            уваги і рухів, але потребують складніших тренувань.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>З легким або залишковим неглектом:</strong> Dual Task допомагає тренувати здатність розрізняти різні стимули, \n" +
        "            обробляти інформацію та реагувати в багатозадачних ситуаціях.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Для відновлення багатозадачності:</strong> Пацієнти, які мають труднощі в реальних життєвих ситуаціях, \n" +
        "            таких як розмова під час ходьби або виконання складних завдань.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Для адаптації до реального життя:</strong> Dual Task допомагає моделювати умови, які зустрічаються в побуті чи роботі, \n" +
        "            коли потрібно одночасно виконувати кілька дій.\n" +
        "        </li>\n" +
        "    </ul>",
    },
  ],
  2: [
    {
      title: "1",
      image:'vision2/Alpaca1.svg',
      desc: "1",
      value: "1",
      icon: { icon: "tabler-box-multiple-1", size: "28" },
      description:
        " <h1 style='text-align: center'>Особливості Single Task</h1>\n" +
        "    \n" +
        "    <p>Пацієнт має ідентифікувати один цільовий стимул.</p>\n" +
        "    \n" +
        "    <h2>Підходить для пацієнтів, які:</h2>\n" +
        "    <ul>\n" +
        "        <li>\n" +
        "            <strong>На початковому етапі реабілітації:</strong> Пацієнти з важкими когнітивними чи моторними порушеннями, \n" +
        "            для яких необхідна базова оцінка уваги та здатності до концентрації.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Зі значним візуально-просторовим неглектом:</strong> Коли пацієнт ігнорує цілу частину поля зору і потребує простих завдань \n" +
        "            для відновлення базової навички розпізнавання об’єктів.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>З хронічними симптомами неглекту:</strong> Якщо пацієнт демонструє низький рівень уваги, Single Task дозволяє \n" +
        "            тренувати точність і зосередженість на одній задачі.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Потребують поступового підвищення навантаження:</strong> Виконання однієї задачі допомагає уникнути перевантаження когнітивної системи.\n" +
        "        </li>\n" +
        "    </ul>",
    },
    {
      title: "2",
      value: "2",
      image:'vision2/Deer2.svg',
      icon: { icon: "tabler-box-multiple-2", size: "28" },
      description:
        " <h1 style='text-align: center'>Особливості Dual Task</h1>\n" +
        "    \n" +
        "    <p>Пацієнт має знайти та викреслити два типи цільових стимулів одночасно.</p>\n" +
        "    \n" +
        "    <h2>Підходить для пацієнтів, які:</h2>\n" +
        "    <ul>\n" +
        "        <li>\n" +
        "            <strong>На середньому або пізньому етапі реабілітації:</strong> Пацієнти, які вже досягли прогресу в одночасній роботі \n" +
        "            уваги і рухів, але потребують складніших тренувань.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>З легким або залишковим неглектом:</strong> Dual Task допомагає тренувати здатність розрізняти різні стимули, \n" +
        "            обробляти інформацію та реагувати в багатозадачних ситуаціях.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Для відновлення багатозадачності:</strong> Пацієнти, які мають труднощі в реальних життєвих ситуаціях, \n" +
        "            таких як розмова під час ходьби або виконання складних завдань.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Для адаптації до реального життя:</strong> Dual Task допомагає моделювати умови, які зустрічаються в побуті чи роботі, \n" +
        "            коли потрібно одночасно виконувати кілька дій.\n" +
        "        </li>\n" +
        "    </ul>",
    },
    {
      title: "3",
      image:'vision2/Horse3.svg',
      value: "3",
      icon: { icon: "tabler-box-multiple-2", size: "28" },
      description:
        " <h1 style='text-align: center'>Особливості Dual Task</h1>\n" +
        "    \n" +
        "    <p>Пацієнт має знайти та викреслити два типи цільових стимулів одночасно.</p>\n" +
        "    \n" +
        "    <h2>Підходить для пацієнтів, які:</h2>\n" +
        "    <ul>\n" +
        "        <li>\n" +
        "            <strong>На середньому або пізньому етапі реабілітації:</strong> Пацієнти, які вже досягли прогресу в одночасній роботі \n" +
        "            уваги і рухів, але потребують складніших тренувань.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>З легким або залишковим неглектом:</strong> Dual Task допомагає тренувати здатність розрізняти різні стимули, \n" +
        "            обробляти інформацію та реагувати в багатозадачних ситуаціях.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Для відновлення багатозадачності:</strong> Пацієнти, які мають труднощі в реальних життєвих ситуаціях, \n" +
        "            таких як розмова під час ходьби або виконання складних завдань.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Для адаптації до реального життя:</strong> Dual Task допомагає моделювати умови, які зустрічаються в побуті чи роботі, \n" +
        "            коли потрібно одночасно виконувати кілька дій.\n" +
        "        </li>\n" +
        "    </ul>",
    },
    {
      title: "4",
      image:'vision2/Monkey4.svg',
      value: "4",
      icon: { icon: "tabler-box-multiple-2", size: "28" },
      description:
        " <h1 style='text-align: center'>Особливості Dual Task</h1>\n" +
        "    \n" +
        "    <p>Пацієнт має знайти та викреслити два типи цільових стимулів одночасно.</p>\n" +
        "    \n" +
        "    <h2>Підходить для пацієнтів, які:</h2>\n" +
        "    <ul>\n" +
        "        <li>\n" +
        "            <strong>На середньому або пізньому етапі реабілітації:</strong> Пацієнти, які вже досягли прогресу в одночасній роботі \n" +
        "            уваги і рухів, але потребують складніших тренувань.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>З легким або залишковим неглектом:</strong> Dual Task допомагає тренувати здатність розрізняти різні стимули, \n" +
        "            обробляти інформацію та реагувати в багатозадачних ситуаціях.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Для відновлення багатозадачності:</strong> Пацієнти, які мають труднощі в реальних життєвих ситуаціях, \n" +
        "            таких як розмова під час ходьби або виконання складних завдань.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Для адаптації до реального життя:</strong> Dual Task допомагає моделювати умови, які зустрічаються в побуті чи роботі, \n" +
        "            коли потрібно одночасно виконувати кілька дій.\n" +
        "        </li>\n" +
        "    </ul>",
    },
    {
      title: "5",
      image:'vision2/Pig5.svg',
      value: "5",
      icon: { icon: "tabler-box-multiple-2", size: "28" },
      description:
        " <h1 style='text-align: center'>Особливості Dual Task</h1>\n" +
        "    \n" +
        "    <p>Пацієнт має знайти та викреслити два типи цільових стимулів одночасно.</p>\n" +
        "    \n" +
        "    <h2>Підходить для пацієнтів, які:</h2>\n" +
        "    <ul>\n" +
        "        <li>\n" +
        "            <strong>На середньому або пізньому етапі реабілітації:</strong> Пацієнти, які вже досягли прогресу в одночасній роботі \n" +
        "            уваги і рухів, але потребують складніших тренувань.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>З легким або залишковим неглектом:</strong> Dual Task допомагає тренувати здатність розрізняти різні стимули, \n" +
        "            обробляти інформацію та реагувати в багатозадачних ситуаціях.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Для відновлення багатозадачності:</strong> Пацієнти, які мають труднощі в реальних життєвих ситуаціях, \n" +
        "            таких як розмова під час ходьби або виконання складних завдань.\n" +
        "        </li>\n" +
        "        <li>\n" +
        "            <strong>Для адаптації до реального життя:</strong> Dual Task допомагає моделювати умови, які зустрічаються в побуті чи роботі, \n" +
        "            коли потрібно одночасно виконувати кілька дій.\n" +
        "        </li>\n" +
        "    </ul>",
    },
  ],
};
export default modes;
