<script setup>
const props = defineProps({
  svgs: {
    type: [Array],
  },
  width: { type: [String] },
});
</script>
<template>
  <div class="d-md-block v-col-12">
    <template v-for="svg in svgs">
      <img
        class="misc-footer-img d-none d-md-block"
        :src="'/images/vision/' + svg + '.svg'"
        :width="width"
        :height="width"
      />
    </template>
  </div>
</template>
