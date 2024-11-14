<script setup>
import userAvatar from "@images/avatars/avatar-1.png";
import AddEditPatientDialog from "@/components/patient/AddEditPatientDialog.vue";
import PatientArchiveDialog from "@/components/patient/PatientArchiveDialog.vue";

const route = useRoute("patients-card-id");
const patientData = ref({});

const isAddPatientVisible = ref(false);
const isArchiveDialogVisible = ref(false);
const router = useRouter();

const loadPatientData = async (id) => {
  const { data } = await useApi(`/patient/${id}`);
  if (data.value) patientData.value = data.value.data;
};
await loadPatientData(route.params.id);

const reloadData = () => {
  loadPatientData(route.params.id);
};
const returnToPatientList = () => {
  router.push({ name: "patients" });
};
</script>

<template>
  <PatientArchiveDialog
    :id="patientData.id"
    v-model:is-dialog-visible="isArchiveDialogVisible"
    @archived="returnToPatientList"
  />
  <AddEditPatientDialog
    :id="patientData.id"
    v-model:is-dialog-visible="isAddPatientVisible"
    @saved="reloadData"
  />

  <VCard class="mb-4">
    <VCardText class="pt-12">
      <VRow>
        <VCol cols="5" class="d-flex flex-column">
          <div class="d-flex justify-start align-start">
            <VAvatar rounded :size="100">
              <VImg :src="userAvatar" />
            </VAvatar>
            <div class="d-flex flex-column align-start justify-start ms-4">
              <h5 class="text-h5">
                {{ patientData.full_name }}
              </h5>
              <h6 class="text-h6">
                {{ patientData.phone }}
              </h6>
              <div class="d-flex justify-center gap-x-4 mt-2">
                <VBtn
                  variant="elevated"
                  visible="true"
                  @click="isAddPatientVisible = !isAddPatientVisible"
                >
                  Редагувати
                </VBtn>

                <VBtn
                  variant="tonal"
                  color="error"
                  @click="isArchiveDialogVisible = !isArchiveDialogVisible"
                >
                  Архівувати
                </VBtn>
              </div>
            </div>
          </div>

          <div>
            <div class="d-flex justify-start gap-x-6 gap-y-2 flex-wrap mt-3">
              <!-- 👉 Done task -->
              <div class="d-flex align-center me-8">
                <VAvatar
                  :size="40"
                  rounded
                  :color="patientData.gender === 0 ? 'error' : 'primary'"
                  variant="tonal"
                  class="me-4"
                >
                  <VIcon icon="tabler-man" size="24" />
                </VAvatar>

                <div>
                  <h6 class="text-h6 text-left">Стать</h6>

                  <span class="text-sm text-left">{{
                    $t("gender." + (patientData.gender == 0 ? "woman" : "man"))
                  }}</span>
                </div>
              </div>

              <!-- 👉 Done Project -->
              <div class="d-flex align-center me-4">
                <VAvatar
                  :size="38"
                  rounded
                  color="primary"
                  variant="tonal"
                  class="me-4"
                >
                  <VIcon icon="tabler-calendar" size="24" />
                </VAvatar>
                <div>
                  <h6 class="text-h6 text-left">Вік</h6>
                  <span class="text-sm text-left"
                    >{{ patientData.age }} роки</span
                  >
                </div>
              </div>
            </div>
          </div>
        </VCol>
        <VCol cols="3" class="d-flex flex-column align-start justify-start">
          <VList class="card-list mt-2">
            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6 text-left d-flex flex-column">
                  Теги:
                  <div class="d-flex text-body-1 gap-1">
                    <VChip
                      color="primary"
                      variant="outlined"
                      v-for="tag in patientData.tags"
                    >
                      {{ tag.label }}
                    </VChip>
                  </div>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6 text-left d-flex flex-column">Сфера:</h6>
                <span class="text-body-1"> {{ patientData.field }} </span>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6 text-left d-flex flex-column">
                  Область:
                  <div class="d-inline-block text-body-1 text-capitalize">
                    {{ patientData.region && patientData.region.label }}
                  </div>
                </h6>
              </VListItemTitle>
            </VListItem>
          </VList>
        </VCol>
        <VCol cols="4">
          <VList class="card-list mt-2">
            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6 text-left d-flex flex-column">
                  Клінічний діагноз:
                  <div class="d-inline-block text-body-1 text-capitalize">
                    {{ patientData.clinic_diagnose }}
                  </div>
                </h6>
              </VListItemTitle>
            </VListItem>
          </VList>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>

  <VRow>
    <VCol cols="6">
      <!-- 👉 User Activity timeline -->
      <VCard>
        <VCardItem class="notification-section">
          <div class="d-flex align-center justify-start">
            <h4 class="text-h4">Неглект тест</h4>

            <VBtn
              variant="elevated"
              visible="true"
              class="ms-4"
              :to="{ name: 'test-id', params: { id: patientData.id } }"
            >
              Почати тест
            </VBtn>
          </div>
        </VCardItem>

        <VCardText>
          <VTimeline
            side="end"
            align="start"
            line-inset="8"
            truncate-line="start"
            density="compact"
          >
            <!-- SECTION Timeline Item: Flight -->
            <VTimelineItem
              :dot-color="
                examResult.final_result < 34
                  ? 'error'
                  : examResult.final_result < 67
                  ? 'primary'
                  : 'success'
              "
              size="x-small"
              v-for="examResult in patientData.exams"
            >
              <!-- 👉 Header -->
              <div
                class="d-flex justify-space-between align-center gap-2 flex-wrap mb-2"
              >
                <span class="app-timeline-title">
                  Результат:
                  <VChip
                    :color="
                      examResult.final_result < 34
                        ? 'error'
                        : examResult.final_result < 67
                        ? 'primary'
                        : 'success'
                    "
                    >{{ examResult.final_result }}%</VChip
                  >
                </span>
                <span class="app-timeline-meta">{{ examResult.date }}</span>
              </div>

              <!-- 👉 Content -->
              <div class="app-timeline-text mt-1">
                <VBtn
                  :to="{
                    name: 'exam-result-id',
                    params: { id: examResult.test_id },
                  }"
                  color="primary"
                >
                  Перейти к результатам
                </VBtn>
              </div>
            </VTimelineItem>
            <!-- !SECTION -->
          </VTimeline>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
