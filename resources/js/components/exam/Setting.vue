<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import miscMaskDark from "@images/card.png";
import type { CustomInputContent } from "@core/types";

const sizeTicksLabels = { 75: "75%", 100: "100%", 125: "125%" };
const pixelWidths: Record<number, number> = { 75: 420, 100: 480, 125: 540 }; // Ширини в пікселях для кожного значення
const cardWidth = ref(480); // Ширини в пікселях для кожного значення

const ticksLabels = {5: "5хв", 10: "10хв", 15: "15хв", 20: "20хв" };
const levels: CustomInputContent[] = [
  {
    title: "Легкий",
    desc: "Розмір стимулів 2см",
    value: "1",
    size: 50,
    icon: { icon: "tabler-rocket", size: "28" },
  },
  {
    title: "Середній",
    desc: "Розмір стимулів 1.5см",
    value: "2",
    size: 37,
    icon: { icon: "tabler-star", size: "28" },
  },
  {
    title: "Важкий ",
    desc: "Розмір стимулів 1см",
    value: "3",
    size: 25,
    icon: { icon: "tabler-crown", size: "28" },
  },
];
const modes: CustomInputContent[] = [
  {
    title: "Single",
    desc: "Один цільовий стимул",
    value: "1",
    icon: { icon: "tabler-box-multiple-1", size: "28" },
  },
  {
    title: "Dual",
    desc: "Два цільових стимули",
    value: "2",
    icon: { icon: "tabler-box-multiple-2", size: "28" },
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
  const svgSm = levels.find( v=> v.value == formData.value.level).size;
  return svgSm * formData.value.svg_size /100 ;
})

onMounted(() => {});
const emit = defineEmits(["settingsSaved"]);
const storeSetting = () => {
  emit("settingsSaved", formData.value);
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
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusantium
        alias aspernatur atque consequatur culpa cum debitis ducimus earum error
        est ex excepturi exercitationem hic in inventore natus necessitatibus
        nisi omnis perspiciatis possimus quam quas, quia soluta voluptate
        voluptatibus voluptatum?
      </div>

      <div class="mb-4">
        <h5 class="text-h5 mb-1">Рівень скринінгу</h5>
        <CustomRadiosWithIcon
          v-model:selected-radio="formData.level"
          :radio-content="levels"
          :grid-column="{ sm: '4', cols: '12' }"
        />
      </div>
      <div class="mb-4">
        <h5 class="text-h5 mb-1">Режим скринінгу</h5>
        <CustomRadiosWithIcon
          v-model:selected-radio="formData.mode"
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
              :style="{ width: cardWidth * formData.svg_size/100  + 'px' }"
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
</style>
