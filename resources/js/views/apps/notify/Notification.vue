<script setup>
// interface Emit {
//   (e: "update:isDrawerOpen", value: boolean): void;
// }
const emit = defineEmits(["update:isDrawerOpen", "userData"]);

// const props = defineProps({
//   isDrawerOpen: {
//     type: Boolean,
//     required: true,
//   },
// })

// interface Props {
//   isDrawerOpen: boolean;
//   title: string | null;
//   message: string | null;
// }

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  title: { type: String },
  message: { type: String },
});
// const emit = defineEmits<Emit>();

const close = () => {
  emit("update:isDrawerOpen", false);
};
</script>

<template>
  <VDialog
  class=""
    :model-value="props.isDrawerOpen"
    @update:model-value="(val) => $emit('update:isDrawerOpen', val)"
    width="500"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="close" class="" />

    <!-- Dialog Content -->
    <VCard :title="props.title" class="">
      <VCardText v-if="props.message">
        {{ props.message }}
      </VCardText>

      <VCardText class="d-flex justify-end"> </VCardText>
    </VCard>
  </VDialog>
</template>
