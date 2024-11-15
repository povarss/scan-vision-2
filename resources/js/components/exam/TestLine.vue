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
const color = "black";
const mainPointColor = "red";
</script>

<template>
  <div style="z-index: 90; position: absolute;">
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
        r="3"
        :fill="
          index == 0 || index == points.length - 1 ? mainPointColor : color
        "
      />
    </svg>
  </div>
</template>

<style></style>
