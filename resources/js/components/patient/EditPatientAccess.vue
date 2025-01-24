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

const emit = defineEmits(["update:isDialogVisible", "saved"]);

const patient = ref({
  full_name: null,
  email: null,
  password: null,
  expire_date: null,
  minutes: null,
  comment: null,
});

const errors = ref({});
const refPatientForm = ref();

const formReset = () => {
  refPatientForm.value?.reset();
  errors.value = {};
};

const afterSave = (patientId) => {
  formReset();
  emit("saved", patientId);
  emit("update:isDialogVisible", false);
};

const store = async () => {
  try {
    const res = await $api("/patient-access", {
      method: "POST",
      body: { id: props.id, ...patient.value },
      onResponseError({ response }) {
        errors.value = response._data.errors;
      },
    });

    if (res.patient) {
      afterSave(res.patient.id);
    }
  } catch (err) {
    console.error(err);
  }
};
const onSubmit = () => {
  refPatientForm.value?.validate().then(({ valid: isValid }) => {
    if (isValid) store();
  });
};

const onReset = () => {
  emit("update:isDialogVisible", false);
  formReset();
};

const loadPatientData = async (id) => {
  const { data } = await useApi(`/patient-access/${id}`);
  if (data.value) {
    const patientData = data.value.data;
    patient.value = patientData;
  }
};

watch(
  () => props.isDialogVisible,
  () => {
    if (props.isDialogVisible) {
      if (props.id) {
        loadPatientData(props.id);
      }
    }
  }
);
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
    <VForm ref="refPatientForm" @submit.prevent="onSubmit">
      <VCard :title="$t('patient.' + (props.id ? 'editTitle' : 'addTitle'))">
        <VCardText>
          <VRow>
            <VCol cols="12" sm="7">
              <AppTextField
                v-model="patient.full_name"
                :label="$t('patient.full_name')"
                :error-messages="errors.full_name"
              />
            </VCol>
            <VCol cols="12" sm="5">
              <AppTextField
                v-model="patient.email"
                :label="$t('patient.email')"
                :error-messages="errors.email"
              />
            </VCol>
            <VCol cols="12" sm="6">
              <AppTextField
                v-model="patient.password"
                :label="$t('patient.password')"
                :error-messages="errors.password"
              />
            </VCol>
            <VCol cols="12" sm="6">
              <AppDateTimePicker
                v-model="patient.expire_at"
                :label="$t('patient.expire_at')"
                :error-messages="errors.expire_at"
              />
            </VCol>
            <VCol cols="12" sm="6">
              <AppTextField
                v-model="patient.minutes"
                :label="$t('patient.minutes')"
                :error-messages="errors.minutes"
              />
            </VCol>
            <VCol cols="12" sm="6">
              <AppTextField
                v-model="patient.comment"
                :label="$t('patient.comment')"
                :error-messages="errors.comment"
              />
            </VCol>
          </VRow>
        </VCardText>

        <VCardText class="d-flex justify-end flex-wrap gap-3">
          <VBtn variant="tonal" color="secondary" @click="onReset">
            {{ $t("btnLabel.cancel") }}
          </VBtn>
          <VBtn @click="onSubmit">
            {{ $t("btnLabel." + (props.id ? "save" : "add")) }}
          </VBtn>
        </VCardText>
      </VCard>
    </VForm>
  </VDialog>
</template>
