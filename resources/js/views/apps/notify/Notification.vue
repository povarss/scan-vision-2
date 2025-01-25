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
  showOk: {
    type: Boolean,
    required: true,
    default: () => false,
  },
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

      <VCardText class="d-flex justify-end">
        <VRow>
          <!-- 👉 Submit and Cancel button -->
          <VCol cols="12" class="text-center" v-if="showOk">
            <VBtn
              class="me-3"
              block
              @click="$emit('update:isDrawerOpen', false)"
            >
              {{ $t("btnLabel.ok") }}
            </VBtn>

            <!-- <VBtn variant="tonal" color="secondary" @click="resetForm">
              Cancel
            </VBtn> -->
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
  </VDialog>
</template>
