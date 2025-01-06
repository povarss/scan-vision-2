<script setup>
const props = defineProps({
  svgs: {
    type: [Array],
  },
  timer: {
    type: Number,
  },
  width: { type: [String, Number] },
});
</script>
<template>
  <div>
    <div class="correct-wrapper">
      <template v-for="svg in svgs">
        <img class="" :src="svg" :width="width" :height="width" />
      </template>
      <Timer :initialTime="timer / 100" />
    </div>
  </div>
</template>
<style>
.correct-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh; /* Adjust height as needed */
  width: 100vw;
  flex-direction: column;
}

.centered-item {
  /* Additional styles for the centered item */
}
</style>
