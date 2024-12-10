<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import miscMaskDark from "@images/card.png";
// import type { CustomInputContent } from "@core/types";
import levels from "./levels.js";
import modes from "./modes.js";
import { correctSvgList } from "./testUtil.js";
import { useRoute } from "vue-router";
const sizeTicksLabels = { 75: "75%", 100: "100%", 125: "125%" };
const cardWidth = ref(480); // Ширини в пікселях для кожного значення
const route = useRoute("test-type-id");

const ticksLabels = { 5: "5хв", 10: "10хв", 15: "15хв", 20: "20хв" };

const props = defineProps({
  references: {
    type: [Object],
  },
});

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

const svgList = computed(() => {
  return correctSvgList(formData.value.mode, props.references);
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
          :radio-content="modes[route.params.type]"
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
            <template v-for="svg in svgList">
              <img
                class="misc-footer-img d-none d-md-block"
                :src="svg"
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
