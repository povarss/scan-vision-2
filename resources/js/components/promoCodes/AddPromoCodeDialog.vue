<script setup>
import { VForm } from "vuetify/components/VForm";

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(["update:isDialogVisible", "saved"]);
const emptyForm = {
  days: null,
  minutes: null,
  codes: null,
};
const formData = ref(emptyForm);
const errors = ref({});
const refForm = ref();

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
    const res = await $api("/promo-code", {
      method: "POST",
      body: formData.value,
      onResponseError({ response }) {
        errors.value = response._data.errors;
      },
    });

    if (res.isStored) {
      afterSave();
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
      <VCard :title="$t('promo.addTitle')">
        <VCardText>
          <VRow>
            <VCol cols="12" sm="6">
              <AppTextField
                v-model="formData.days"
                :label="$t('promo.days')"
                :error-messages="errors.days"
              />
            </VCol>
            <VCol cols="12" sm="6">
              <AppTextField
                v-model="formData.minutes"
                :label="$t('promo.minutes')"
                :error-messages="errors.minutes"
              />
            </VCol>
            <VCol cols="12" sm="12">
              <AppTextarea
                v-model="formData.codes"
                :label="$t('promo.promoCodes')"
                :error-messages="errors.codes"
                auto-grow
              />
            </VCol>
          </VRow>
        </VCardText>

        <VCardText class="d-flex justify-end flex-wrap gap-3">
          <VBtn variant="tonal" color="secondary" @click="onReset">
            {{ $t("btnLabel.cancel") }}
          </VBtn>
          <VBtn @click="onSubmit">
            {{ $t("btnLabel.add") }}
          </VBtn>
        </VCardText>
      </VCard>
    </VForm>
  </VDialog>
  <!-- Confirm Dialog -->
</template>
