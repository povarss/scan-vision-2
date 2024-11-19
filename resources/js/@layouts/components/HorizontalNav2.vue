<script setup>
import { HorizontalNavGroup, HorizontalNavLink } from "@layouts/components";

const props = defineProps({
  navItems: {
    type: null,
    required: true,
  },
});

const resolveNavItemComponent = (item) => {
  if ("children" in item) return HorizontalNavGroup;

  return HorizontalNavLink;
};
</script>

<template>
  <div class="layout-horizontal-nav">
    <ul class="nav-items d-flex">
      <Component
        :is="resolveNavItemComponent(item)"
        v-for="(item, index) in navItems"
        :key="index"
        :item="item"
      />
    </ul>
  </div>
</template>

<style lang="scss">
.layout-wrapper.layout-nav-type-horizontal {
  .nav-items {
    display: flex;
    flex-wrap: wrap;
  }
}
</style>
