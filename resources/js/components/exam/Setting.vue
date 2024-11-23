<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import miscMaskDark from "@images/card.png";
import type { CustomInputContent } from "@core/types";

const sizeTicksLabels = { 75: "75%", 100: "100%", 125: "125%" };
const pixelWidths: Record<number, number> = { 75: 420, 100: 480, 125: 540 }; // Ширини в пікселях для кожного значення
const cardWidth = ref(480); // Ширини в пікселях для кожного значення

const ticksLabels = { 5: "5хв", 10: "10хв", 15: "15хв", 20: "20хв" };
const levels: CustomInputContent[] = [
  {
    title: "Легкий",
    desc: "Розмір стимулів 2см",
    value: "1",
    size: 76,
    icon: { icon: "tabler-rocket", size: "28" },
    description:
      " <h1 style='text-align: center'>Легкий рівень</h1>\n" +
      "    \n" +
      "    <h2>Кому підходить?</h2>\n" +
      "    <ul>\n" +
      "        <li>Пацієнтам на початковому етапі реабілітації.</li>\n" +
      "        <li>Тим, хто має значні порушення уваги або когнітивні проблеми.</li>\n" +
      "        <li>Для первинної діагностики візуально-просторового або об’єктного неглекту.</li>\n" +
      "    </ul>\n" +
      "    \n" +
      "    <h2>Характеристики тесту:</h2>\n" +
      "    <ul>\n" +
      "        <li>Використовується тільки <strong>Single Task</strong>: пацієнт виконує одне завдання.</li>\n" +
      "        <li>Мінімальна кількість стимулів на дошці, щоб уникнути перевантаження.</li>\n" +
      "        <li>Візуальні стимули розташовані просто та зрозуміло, без складних відволікаючих елементів.</li>\n" +
      "        <li>Інтервали між появою стимулів довші, що дозволяє пацієнту реагувати без поспіху.</li>\n" +
      "    </ul>\n" +
      "    \n" +
      "    <h2>Мета:</h2>\n" +
      "    <ul>\n" +
      "        <li>Розвивати базові навички уваги та розпізнавання.</li>\n" +
      "        <li>Підготувати пацієнта до складніших рівнів.</li>\n" +
      "    </ul>",
  },
  {
    title: "Середній",
    desc: "Розмір стимулів 1.5см",
    value: "2",
    size: 57,
    icon: { icon: "tabler-star", size: "28" },
    description:
      " <h1 style='text-align: center'>Середній рівень</h1>\n" +
      "    \n" +
      "    <h2>Кому підходить?</h2>\n" +
      "    <ul>\n" +
      "        <li>Пацієнтам із помірними когнітивними або зорово-моторними порушеннями.</li>\n" +
      "        <li>Тим, хто вже досяг прогресу на легкому рівні.</li>\n" +
      "        <li>Для пацієнтів із залишковими симптомами неглекту.</li>\n" +
      "    </ul>\n" +
      "    \n" +
      "    <h2>Характеристики тесту:</h2>\n" +
      "    <ul>\n" +
      "        <li>Використовується <strong>Single Task</strong> або <strong>Dual Task</strong>, залежно від цілей терапії.</li>\n" +
      "        <li>Збільшена кількість стимулів на дошці, які розташовані щільніше.</li>\n" +
      "        <li>Візуальні стимули більш схожі один на одного, включаючи відволікаючі елементи.</li>\n" +
      "    </ul>\n" +
      "    \n" +
      "    <h2>Мета:</h2>\n" +
      "    <ul>\n" +
      "        <li>Тренувати здатність обробляти більшу кількість інформації.</li>\n" +
      "        <li>Покращувати швидкість реакції та вибіркову увагу.</li>\n" +
      "    </ul>",
  },
  {
    title: "Важкий ",
    desc: "Розмір стимулів 1см",
    value: "3",
    size: 38,
    icon: { icon: "tabler-crown", size: "28" },
    description:
      " <h1 style='text-align: center'>Важкий рівень</h1>\n" +
      "    \n" +
      "    <h2>Кому підходить?</h2>\n" +
      "    <ul>\n" +
      "        <li>Пацієнтам на пізньому етапі реабілітації.</li>\n" +
      "        <li>Тим, хто готовий працювати з інтенсивними когнітивними навантаженнями.</li>\n" +
      "        <li>Для моделювання складних багатозадачних ситуацій, що зустрічаються в повсякденному житті.</li>\n" +
      "    </ul>\n" +
      "    \n" +
      "    <h2>Характеристики тесту:</h2>\n" +
      "    <ul>\n" +
      "        <li>Використовується переважно <strong>Dual Task</strong> для підвищення когнітивного навантаження.</li>\n" +
      "        <li>Висока щільність стимулів: велика кількість цільових і фальшивих елементів.</li>\n" +
      "        <li>Візуально складні стимули з мінімальними відмінностями між цільовими та фальшивими елементами.</li>\n" +
      "        <li>Малий розмір стимулів.</li>\n" +
      "    </ul>\n" +
      "    \n" +
      "    <h2>Мета:</h2>\n" +
      "    <ul>\n" +
      "        <li>Тренувати багатозадачність та адаптацію до реальних життєвих умов.</li>\n" +
      "        <li>Покращувати здатність обробляти складну інформацію під час стресових ситуацій.</li>\n" +
      "    </ul>",
  },
];
const modes: CustomInputContent[] = [
  {
    title: "Single",
    desc: "Один цільовий стимул",
    value: "1",
    icon: { icon: "tabler-box-multiple-1", size: "28" },
    description: " <h1 style='text-align: center'>Особливості Single Task</h1>\n" +
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
      "    </ul>"

  },
  {
    title: "Dual",
    desc: "Два цільових стимули",
    value: "2",
    icon: { icon: "tabler-box-multiple-2", size: "28" },
    description: " <h1 style='text-align: center'>Особливості Dual Task</h1>\n" +
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
      "    </ul>"

  },
];
const correctSvgList: string[] = ["1", "2"];
const normalSize = ref(50);
const formData = ref({
  svg_size: 100,
  time: 20,
  level: "1",
  mode: "1",
});
const stimulSize = computed(() => {
  const svgSm = levels.find((v) => v.value == formData.value.level).size;
  return (svgSm * formData.value.svg_size) / 100;
});

onMounted(() => {});
const emit = defineEmits(["settingsSaved"]);
const storeSetting = () => {
  emit("settingsSaved", formData.value);
};

const levelDialogInfo = ref(false);
const infoText = ref("");
const infoClicked = (level) => {
  console.log("clicked");
  levelDialogInfo.value = true;
  infoText.value = level.description;
};
</script>

<template>
  <VRow>
    <VCol cols="12">
      <h5 class="text-h5 mb-5">Тривалість скринінгу</h5>
      <VSlider
        v-model="formData.time"
        :step="5"
        :min="5"
        :max="20"
        :ticks="ticksLabels"
        show-ticks="always"
        tick-size="4"
      >
        <template #thumb-label="{ modelValue }">
          <div>{{ modelValue + " хв" }}</div>
        </template>
      </VSlider>
    </VCol>

    <VCol cols="6">
      <div class="mb-4">
        <h5 class="text-h5 mb-1">Опис налаштувань</h5>
        Для ефективної діагностики та реабілітації тест на чутливість адаптовано
        до трьох рівнів складностію. Кожен рівень відповідає певним можливостям
        пацієнта і дозволяє поступово підвищувати когнітивне та моторне
        навантаження.
      </div>

      <VDialog v-model="levelDialogInfo" width="500">
        <!-- Dialog close btn -->
        <DialogCloseBtn @click="levelDialogInfo = !levelDialogInfo" />

        <!-- Dialog Content -->
        <VCard>
          <VCardText>
            <div v-html="infoText"></div>
          </VCardText>

<!--          <VCardText class="d-flex justify-end">-->
<!--            <VBtn @click="levelDialogInfo = false"> I accept </VBtn>-->
<!--          </VCardText>-->
        </VCard>
      </VDialog>

      <div class="mb-4">
        <h5 class="text-h5 mb-1" @click="levelDialogInfo = !levelDialogInfo">
          Рівень скринінгу
        </h5>
        <CustomRadiosWithIcon
          v-model:selected-radio="formData.level"
          @info-clicked="infoClicked($event)"
          :radio-content="levels"
          :grid-column="{ sm: '4', cols: '12' }"
        />
      </div>
      <div class="mb-4">
        <h5 class="text-h5 mb-1">Режим скринінгу</h5>
        <CustomRadiosWithIcon
          v-model:selected-radio="formData.mode"
          @info-clicked="infoClicked($event)"
          :radio-content="modes"
          :grid-column="{ sm: '6', cols: '12' }"
        />
      </div>
    </VCol>
    <VCol cols="6">
      <h5 class="text-h5 mb-4 mt-4">Калібрування розмірів</h5>
      <div class="d-flex align-center justify-center flex-column">
        <div class="w-100">
          <VSlider
            v-model="formData.svg_size"
            :step="1"
            :min="75"
            :max="125"
            :ticks="sizeTicksLabels"
            show-ticks="always"
            tick-size="4"
          >
            <template #thumb-label="{ modelValue }">
              <div>{{ modelValue + " %" }}</div>
            </template>
          </VSlider>
        </div>
      </div>

      <div class="d-flex align-center justify-center mt-5 p-5" style="">
        <div class="v-row">
          <div class="d-md-block v-col-12">
            <img
              class="misc-footer-img d-none d-md-block"
              :src="miscMaskDark"
              alt="misc-footer-img"
              :style="{ width: (cardWidth * formData.svg_size) / 100 + 'px' }"
            />
          </div>
          <div class="d-md-block v-col-12">
            <template v-for="svg in correctSvgList">
              <img
                class="misc-footer-img d-none d-md-block"
                :src="'/images/vision/' + svg + '.svg'"
                :width="stimulSize"
                :height="stimulSize"
              />
            </template>
          </div>
        </div>
        <!-- <img
          class="misc-footer-img d-none d-md-block"
          :src="miscMaskDark"
          alt="misc-footer-img"
          :style="{ width: pixelWidths[formData.sizeSlider] + 'px' }"
        /> -->
      </div>
    </VCol>

    <VCol cols="12" class="d-flex align-center justify-center">
      <VBtn size="large" class="mt-5" @click="storeSetting">
        Почати
        <VIcon end icon="tabler-play" />
      </VBtn>
    </VCol>
  </VRow>
</template>

<style>
.v-slider-thumb__label {
  min-width: 63px !important;
}

.text-h6 {
}
ul {
  margin-left: 20px; /* Лівий відступ для списків */
}
</style>
