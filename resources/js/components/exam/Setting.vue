<script setup>
import { ref, onMounted, computed } from "vue";
import miscMaskDark from "@images/card.png";
// import type { CustomInputContent } from "@core/types";
import levels from "./levels.js";
import { correctSvgList } from "./testUtil.js";
const sizeTicksLabels = { 75: "75%", 100: "100%", 125: "125%" };
const cardWidth = ref(480); // Ширини в пікселях для кожного значення

const ticksLabels = { 5: "5хв", 10: "10хв", 15: "15хв", 20: "20хв" };
const accelTickLabels = { 20: "20", 50: "50", 70: "70", 100: "100" };

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
  color: "#000000",
  direction: 1,
  speed: 50,
  dot_count: 50,
});
const stimulSize = computed(() => {
  const svgSm = levels.find((v) => v.value == formData.value.level).size;
  return (svgSm * formData.value.svg_size) / 100;
});

const svgList = computed(() => {
  return correctSvgList(formData.value.mode, props.references);
});

const modeInfo = computed(() => {
  return props.references?.modes;
});

onMounted(() => {});
const emit = defineEmits(["settingsSaved"]);
const storeSetting = () => {
  emit("settingsSaved", formData.value);
};

const levelDialogInfo = ref(false);
const infoText = ref("");
const infoClicked = (level) => {
  levelDialogInfo.value = true;
  infoText.value = level.description;
};

watch(
  () => props.references,
  () => {
    if (!props.references.storedValues) {
      return;
    }

    if (props.references.storedValues.direction) {
      formData.value.direction = props.references.storedValues.direction;
    }
    if (props.references.storedValues.color) {
      formData.value.color = props.references.storedValues.color;
    }
    if (props.references.storedValues.speed) {
      formData.value.speed = props.references.storedValues.speed;
    }
    if (props.references.storedValues.dot_count) {
      formData.value.dot_count = props.references.storedValues.dot_count;
    }
  }
);
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
      <div class="mb-4" v-if="modeInfo">
        <h5 class="text-h5 mb-1">Режим скринінгу</h5>
        <CustomRadiosWithIcon
          v-model:selected-radio="formData.mode"
          @info-clicked="infoClicked($event)"
          :radio-content="modeInfo"
          :grid-column="{ sm: '6', cols: '12' }"
        />
      </div>
      <div v-if="props.references && props.references.type == 3">
        <div class="mb-4">
          <h5 class="text-h5 mb-1">Напрямок руху</h5>
          <CustomRadiosWithIcon
            v-model:selected-radio="formData.direction"
            @info-clicked="infoClicked($event)"
            :radio-content="props.references.options.directions"
            :grid-column="{ sm: '6', cols: '12' }"
          />
        </div>
        <div class="mb-4">
          <h5 class="text-h5 mb-4 mt-4">Швидкість руху</h5>
          <div class="w-100">
            <VSlider
              v-model="formData.speed"
              :step="10"
              :min="20"
              :max="100"
              :ticks="accelTickLabels"
              show-ticks="always"
              tick-size="4"
            >
              <template #thumb-label="{ modelValue }">
                <div>{{ modelValue }}</div>
              </template>
            </VSlider>
          </div>
        </div>
        <div class="mb-4">
          <h5 class="text-h5 mb-4 mt-4">Кількість точок</h5>
          <div class="w-100">
            <VSlider
              v-model="formData.dot_count"
              :step="10"
              :min="20"
              :max="100"
              :ticks="accelTickLabels"
              show-ticks="always"
              tick-size="4"
            >
              <template #thumb-label="{ modelValue }">
                <div>{{ modelValue }}</div>
              </template>
            </VSlider>
          </div>
        </div>
        <div class="mb-4">
          <h5 class="text-h5 mb-1">колір та прозорість</h5>
          <VColorPicker
            v-model="formData.color"
            mode="hexa"
            :modes="['hexa']"
          />
        </div>
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
