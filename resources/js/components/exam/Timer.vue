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

const emit = defineEmits(["timeout"]);

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
        stopTimer();
      }
    }, 1000);
  }
};

const stopTimer = (isEmit = true) => {
  clearInterval(interval.value);
  if (isEmit) {
    emit("timeout");
  }
};

onMounted(() => {
  remainingTime.value = props.initialTime * 60;
  startTimer();
});

onBeforeUnmount(() => {
  stopTimer(false);
});
</script>
<template>
  <div>
    <p class="text-center">{{ formattedTime }}</p>
  </div>
</template>
