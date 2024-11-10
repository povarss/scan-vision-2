<script setup>
import { VForm } from "vuetify/components/VForm";

const props = defineProps({
  id: {
    type: [String, Number],
    required: false,
    default: () => null,
  },
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(["update:isDialogVisible", "saved"]);

const doctor = ref({
  name: null,
  email: null,
  password: null,
  expire_at: null,
});
const errors = ref({});
const refDoctorForm = ref();

const formReset = () => {
  refDoctorForm.value?.reset();
  doctor.value.expire_at = null;
  errors.value = {};
};

const afterSave = (patientId) => {
  formReset();
  emit("saved", patientId);
  emit("update:isDialogVisible", false);
};

const store = async () => {
  try {
    const res = await $api("/doctor", {
      method: "POST",
      body: { id: props.id, ...doctor.value },
      onResponseError({ response }) {
        errors.value = response._data.errors;
      },
    });

    if (res.user) {
      afterSave(res.user.id);
    }
  } catch (err) {
    console.error(err);
  }
};
const onSubmit = () => {
  refDoctorForm.value?.validate().then(({ valid: isValid }) => {
    if (isValid) store();
  });
};

const onReset = () => {
  emit("update:isDialogVisible", false);
  formReset();
};

const loadDoctorData = async (id) => {
  const { data } = await useApi(`/doctor/${id}`);
  if (data.value) {
    doctor.value = data.value.data;
  }
};

watch(
  () => props.isDialogVisible,
  () => {
    if (props.isDialogVisible) {
      if (props.id) {
        loadDoctorData(props.id);
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
    <VForm ref="refDoctorForm" @submit.prevent="onSubmit">
      <VCard :title="$t('doctor.' + (props.id ? 'editTitle' : 'addTitle'))">
        <VCardText>
          <VRow>
            <VCol cols="12" sm="7">
              <AppTextField
                v-model="doctor.name"
                :label="$t('doctor.name')"
                :error-messages="errors.name"
              />
            </VCol>
            <VCol cols="12" sm="5">
              <AppTextField
                v-model="doctor.email"
                :label="$t('doctor.email')"
                :error-messages="errors.email"
              />
            </VCol>
            <VCol cols="12" sm="7">
              <AppTextField
                v-model="doctor.password"
                :label="$t('doctor.password')"
                :error-messages="errors.password"
              />
            </VCol>
            <VCol cols="12" sm="5">
              <AppDateTimePicker
                v-model="doctor.expire_at"
                :label="$t('doctor.expire_at')"
                :error-messages="errors.expire_at"
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
