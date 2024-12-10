<script setup>
const props = defineProps({
  svgs: {
    type: [Array],
  },
  width: { type: [String, Number] },
});
</script>
<template>
  <div class="d-md-block v-col-12">
    <template v-for="svg in svgs">
      <img
        class="misc-footer-img d-none d-md-block"
        :src="svg"
        :width="width"
        :height="width"
      />
    </template>
  </div>
</template>
