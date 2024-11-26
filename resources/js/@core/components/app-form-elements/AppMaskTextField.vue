<script setup>
import { mask } from "vue-the-mask";
defineOptions({
  name: "AppMaskTextField",
  inheritAttrs: false,
  directives: { mask },
});
// import Vue from 'vue';
// import VueTheMask from 'vue-the-mask';
// Vue.use(VueTheMask);

const elementId = computed(() => {
  const attrs = useAttrs();
  const _elementIdToken = attrs.id || attrs.label;

  return _elementIdToken
    ? `app-text-field-${_elementIdToken}-${Math.random()
        .toString(36)
        .slice(2, 7)}`
    : undefined;
});

const label = computed(() => useAttrs().label);
</script>

<template>
  <div class="app-text-field flex-grow-1" :class="$attrs.class">
    <VLabel
      v-if="label"
      :for="elementId"
      class="mb-1 text-body-2 text-wrap"
      style="line-height: 15px"
      :text="label"
    />
    <VTextField
      placeholder="+38 00 000 0000"
      v-mask="'+38 ## ### ####'"
      v-bind="{
        ...$attrs,
        class: null,
        label: undefined,
        variant: 'outlined',
        id: elementId,
      }"
    >
      <template v-for="(_, name) in $slots" #[name]="slotProps">
        <slot :name="name" v-bind="slotProps || {}" />
      </template>
    </VTextField>
  </div>
</template>
