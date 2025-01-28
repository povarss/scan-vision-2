<script setup>
import { VForm } from "vuetify/components/VForm";
import { useNotifyStore } from "@/views/apps/notify/useNotifyStore";

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(["update:isDialogVisible", "saved"]);
const emptyForm = {
  promoCode: null,
};
const formData = ref(emptyForm);
const errors = ref({});
const refForm = ref();
const notifyStore = useNotifyStore();

const formReset = () => {
  refForm.value?.reset();
  formData.value = emptyForm;
  errors.value = {};
};

const afterSave = () => {
  formReset();
  emit("saved");
  emit("update:isDialogVisible", false);
};

const store = async () => {
  try {
    const res = await $api("/promo/activate", {
      method: "POST",
      body: formData.value,
      onResponseError({ response }) {
        errors.value = response._data.errors;
      },
    });

    if (res.activated) {
      emit("update:isDialogVisible", false);
      notifyStore.showNotification("", res.message);
      setTimeout(() => {
        afterSave();
      }, 3000);
    }
  } catch (err) {
    console.error(err);
  }
};
const onSubmit = () => {
  refForm.value?.validate().then(({ valid: isValid }) => {
    if (isValid) store();
  });
};

const onReset = () => {
  emit("update:isDialogVisible", false);
  formReset();
};
</script>

<template>
  <VDialog
    v-model="props.isDialogVisible"
    max-width="600"
    @update:model-value="onReset"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="onReset" />

    <!-- Dialog Content -->
    <VForm ref="refForm" @submit.prevent="onSubmit">
      <VCard :title="$t('promo.activate')">
        <VCardText>
          <VRow>
            <VCol cols="12" sm="12">
              <AppTextField
                v-model="formData.promoCode"
                :label="$t('promo.code')"
                :error-messages="errors.promoCode"
              />
            </VCol>
          </VRow>
        </VCardText>

        <VCardText class="d-flex justify-end flex-wrap gap-3">
          <!-- <VBtn variant="tonal" color="secondary" @click="onReset">
            {{ $t("btnLabel.cancel") }}
          </VBtn> -->
          <VBtn @click="onSubmit">
            {{ $t("btnLabel.ok") }}
          </VBtn>
        </VCardText>
      </VCard>
    </VForm>
  </VDialog>
  <!-- Confirm Dialog -->
</template>
