<script setup>
import { VForm } from "vuetify/components/VForm";

const props = defineProps({
  initialTime: {
    type: [Number],
    default: 0,
  },
  isCountdown: {
    type: Boolean,
    default: true,
  },
});

const remainingTime = ref(0);
const interval = ref();

const formattedTime = computed(() => {
  const minutes = Math.floor(remainingTime.value / 60)
    .toString()
    .padStart(2, "0");
  const seconds = (remainingTime.value % 60).toString().padStart(2, "0");
  return `${minutes}:${seconds}`;
});

const startTimer = () => {
  if (remainingTime.value > 0) {
    interval.value = setInterval(() => {
      if (remainingTime.value > 0) {
        remainingTime.value -= 1;
      } else {
        this.stopTimer();
      }
    }, 1000);
  }
};

const stopTimer = () => {
  clearInterval(interval.value);
};

onMounted(() => {
  remainingTime.value = props.initialTime;
  startTimer();
});

onBeforeUnmount(() => {
  stopTimer();
});
</script>
<template>
  <div>
    <h2>Countdown Timer</h2>
    <p>{{ formattedTime }}</p>
  </div>
</template>
