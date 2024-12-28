<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";

const props = defineProps({
  examParams: {
    type: [Object],
    required: true,
  },
});
const interval = ref(null);
const dots = ref([]);
const speed = ref(1);
const accelerated = ref({
  period: 0,
  item: null,
});

const getRandomInt = (max) => {
  return Math.floor(Math.random() * max);
};

const startMovement = () => {
  if (
    props.examParams.type != 3 ||
    !props.examParams.pattern_additional_items.dots
  ) {
    return;
  }
  interval.value = setInterval(function () {
    if (accelerated.value.period > 0) {
      accelerated.value.period -= 10;
    }
    if (accelerated.value.period == 0) {
      accelerated.value.item = getRandomInt(dots.value.length - 1);
      accelerated.value.period = 100;
    }
    dots.value.forEach((div, index) => {
      // console.log(examParams.custom_settings,'examParams.custom_settings')
      let direction = 0;
      if (props.examParams.custom_settings.direction == 1) {
        direction = -speed.value;
      } else if (props.examParams.custom_settings.direction == 2) {
        direction = speed.value;
      } else if (props.examParams.custom_settings.direction == 3) {
        direction = -speed.value;
      } else if (props.examParams.custom_settings.direction == 4) {
        direction = speed.value;
      }

      if (accelerated.value.item == index) {
        direction += accelerated.value.period / 3; // Increase speed for the randomly selected div
      }

      // Update div position based on direction
      if (props.examParams.custom_settings.direction % 2 === 1) {
        // Horizontal movement
        div[0] += direction;
      } else {
        // Vertical movement
        div[1] += direction;
      }

      // Wrap around if div goes beyond container bounds
      if (div[0] > props.examParams.width) {
        div[0] = 0;
      } else if (div[0] < 0) {
        div[0] = props.examParams.width;
      }
      if (div[1] > props.examParams.height) {
        div[1] = 0;
      } else if (div[1] < 0) {
        div[1] = props.examParams.height;
      }
    });
  }, 50);
};

watch(
  () => props.examParams.pattern_additional_items,
  () => {
    dots.value = props.examParams.pattern_additional_items?.dots;
    speed.value = props.examParams.custom_settings?.speed / 20;
    startMovement();
  },
  { immediate: true }
);

onBeforeUnmount(() => {
  if (interval.value) {
    clearInterval(interval.value);
  }
});
</script>

<template>
  <div>
    <template v-if="examParams.type == 3">
      <template v-for="(dot, dotNum) in dots">
        <div
          class="dot"
          :style="{
            left: dot[0] + 'px',
            top: dot[1] + 'px',
            backgroundColor: examParams.custom_settings.color,
          }"
          style="
            position: absolute;
            padding: 2px;
            box-sizing: content-box;
            border: 2px solid transparent;
          "
        ></div>
      </template>
    </template>
  </div>
</template>

<style>
.dot {
  width: 10px;
  height: 10px;
  background-color: black;
  border-radius: 50%;
  display: inline-block;
  z-index: 40;
}
</style>
