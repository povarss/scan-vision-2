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

const patient = ref({
  full_name: null,
  phone: null,
  born_date: null,
  gender: 1,
  tags: [],
  field: null,
  region_id: null,
  clinic_diagnose: null,
});
const formRules = ref({
  full_name: [],
  phone: [],
  born_date: [],
  gender: [],
  tags: [],
  field: [],
  region_id: [],
  clinic_diagnose: [],
});

const errors = ref({});
const refPatientForm = ref();
const regions = ref([]);

const tagItems = ref([]);

const loadReferences = async () => {
  const res = await $api("/reference-by-key", {
    method: "POST",
    body: { keys: ["region", "tag"] },
  });

  regions.value = res.region;
  tagItems.value = res.tag;
};

const formReset = () => {
  refPatientForm.value?.reset();
  patient.value.gender = 1;
  patient.value.born_date = null;
  errors.value = {};
};

const afterSave = (patientId) => {
  formReset();
  emit("saved", patientId);
  emit("update:isDialogVisible", false);
};

const store = async () => {
  try {
    console.log(patient.value, "patient.value");
    const res = await $api("/patient", {
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
  const { data } = await useApi(`/patient/${id}`);
  if (data.value) {
    const patientData = data.value.data;
    patient.value = {
      ...patientData,
      tags: patientData.tags.map((v) => v.label),
    };
  }
};

watch(
  () => props.isDialogVisible,
  () => {
    if (props.isDialogVisible) {
      loadReferences();
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
                :rules="formRules.full_name"
                :error-messages="errors.full_name"
              />
            </VCol>
            <VCol cols="12" sm="5">
              <AppTextField
                v-model="patient.phone"
                :label="$t('patient.phone')"
                :rules="formRules.phone"
                :error-messages="errors.phone"
              />
            </VCol>
            <VCol cols="12" sm="6">
              <AppDateTimePicker
                v-model="patient.born_date"
                :label="$t('patient.born_date')"
                :rules="formRules.born_date"
                :error-messages="errors.born_date"
              />
            </VCol>
            <VCol cols="12" sm="6">
              <label
                class="v-label mb-1 text-body-2 text-wrap"
                for="app-text-field-Вік-1fk3v"
                style="line-height: 15px"
              >
              </label>
              <VRadioGroup
                v-model="patient.gender"
                inline
                :rules="formRules.gender"
                :error-messages="errors.gender"
              >
                <VRadio :label="$t('gender.man')" :value="1" />
                <VRadio :label="$t('gender.woman')" :value="0" />
              </VRadioGroup>
            </VCol>
            <VCol cols="12">
              <AppCombobox
                v-model="patient.tags"
                :items="tagItems"
                placeholder=""
                :label="$t('patient.tags')"
                multiple
                chips
                :rules="formRules.tags"
                :error-messages="errors.tags"
              />
            </VCol>
            <VCol cols="12" sm="6">
              <AppTextField
                :label="$t('patient.field')"
                v-model="patient.field"
                :rules="formRules.field"
                :error-messages="errors.field"
              />
            </VCol>
            <VCol cols="12" sm="6">
              <AppSelect
                :items="regions"
                v-model="patient.region_id"
                :label="$t('patient.region')"
                :rules="formRules.region_id"
                :error-messages="errors.region_id"
              />
            </VCol>
            <VCol cols="12">
              <AppTextField
                :label="$t('patient.clinic_diagnose')"
                v-model="patient.clinic_diagnose"
                :rules="formRules.clinic_diagnose"
                :error-messages="errors.clinic_diagnose"
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
