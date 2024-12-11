<script setup>
const props = defineProps({
  svgs: {
    type: [Array],
  },
  width: { type: [String, Number] },
});
</script>
<template>
  <div class="correct-wrapper">
    <template v-for="svg in svgs">
      <img
        class=""
        :src="svg"
        :width="width"
        :height="width"
      />
    </template>
  </div>
</template>
<style>
.correct-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh; /* Adjust height as needed */
  width: 100vw;
}

.centered-item {
  /* Additional styles for the centered item */
}
</style>
