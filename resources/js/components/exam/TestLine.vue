<script setup>
import { ref, onMounted } from "vue";
import Timer from "@/components/exam/Timer.vue";

const props = defineProps({
  points: {
    type: [Array],
    required: false,
    default: () => [],
  },
  width: {
    type: [String, Number],
    required: true,
  },
  height: {
    type: [String, Number],
    required: true,
  },
});

const linePoints = computed(() =>
  props.points.map((point) => `${point.x},${point.y}`).join(" ")
);
const circleColor = (index) => {
  if (index == 0) {
    return "red";
  }
  if (index == props.points.length - 1) {
    return "#82ff00";
  }
  return "#b7b7b7";
};

const color = "#b7b7b7";
</script>

<template>
  <div style="z-index: 90; position: absolute">
    <svg :width="width" :height="height" style="border: none">
      <polyline
        :points="linePoints"
        :stroke="color"
        fill="none"
        stroke-width="2"
      />
      <circle
        v-for="(point, index) in points"
        :key="index"
        :cx="point.x"
        :cy="point.y"
        r="10"
        :fill="circleColor(index)"
      />
    </svg>
  </div>
</template>

<style></style>
