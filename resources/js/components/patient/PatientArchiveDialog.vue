<script setup>
import { VForm } from "vuetify/components/VForm";

const props = defineProps({
  id: {
    type: [String, Number],
    required: true,
  },
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(["update:isDialogVisible", "archived"]);

const afterSave = () => {
  emit("archived", props.id);
  emit("update:isDialogVisible", false);
};

const archive = async () => {
  try {
    const res = await $api("/patient/archive", {
      method: "POST",
      body: { id: props.id },
    });

    afterSave();
  } catch (err) {
    console.error(err);
  }
};

const onReset = () => {
  emit("update:isDialogVisible", false);
};
</script>

<template>
  <VDialog
    v-model="props.isDialogVisible"
    class="v-dialog-sm"
    @update:model-value="onReset"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="onReset" />

    <!-- Dialog Content -->
    <VCard title="Ви впевнені що хочете архівувати користувача?">
      <VCardText> Після архівації користувача не можна повернути. </VCardText>

      <VCardText class="d-flex justify-end gap-3 flex-wrap">
        <VBtn color="secondary" variant="tonal" @click="onReset">
          {{ $t("btnLabel.cancel") }}
        </VBtn>
        <VBtn @click="archive">
          {{ $t("btnLabel.archiveConfirm") }}
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>
</template>
