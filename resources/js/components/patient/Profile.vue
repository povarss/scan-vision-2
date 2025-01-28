<script setup>
import AddEditPatientDialog from "@/components/patient/AddEditPatientDialog.vue";
import PatientArchiveDialog from "@/components/patient/PatientArchiveDialog.vue";
import EditPatientAccess from "@/components/patient/EditPatientAccess.vue";
import { hasAccessRole } from "@layouts/plugins/casl";
import { useNotifyStore } from "@/views/apps/notify/useNotifyStore";
const { t } = useI18n();

const props = defineProps({
  patientId: {
    type: [String, Number],
    required: false,
    default: () => null,
  },
  showPatientDetail: {
    type: Boolean,
    required: false,
    default: () => true,
  },
});

const patientData = ref({});
const notifyStore = useNotifyStore();

const isAddPatientVisible = ref(false);
const isArchiveDialogVisible = ref(false);
const isEditAccessOpen = ref(false);
const router = useRouter();
const clickedTypes = ref([]);
const userData = useCookie("userData");

const loadPatientData = async (id) => {
  const { data } = await useApi(`/patient/${id}`);
  if (data.value) patientData.value = data.value.data;
};

const reloadData = () => {
  loadPatientData(props.patientId);
};
const returnToPatientList = () => {
  router.push({ name: hasAccessRole(["admin"]) ? "patients-all" : "patients" });
};

const types = ref([
  {
    id: 1,
    label: "Тест",
  },
  {
    id: 2,
    label: "Тренування",
  },
  {
    id: 3,
    label: "Оптокінетичне тренування",
  },
]);

const startTest = (type, examId) => {
  if (patientData.value.accessDetail.has_access) {
    if (patientData.value.accessDetail.has_minutes > 0) {
      makeDraft(type, examId);
    } else {
      notifyStore.showNotification("", t("promo.AccessMinutesExceeded"));
    }
  } else {
    notifyStore.showNotification(
        t("AccessExpired"),
        t("AccessExpiredDetail"),
        true,
        true
      );
    // notifyStore.showNotification("", t("promo.userAccessExpired"));
  }
};

const makeDraft = async (type, examId) => {
  try {
    const res = await $api("/exam/make-draft", {
      method: "POST",
      body: {
        patient_id: props.patientId,
        exam_id: examId,
        type: type,
      },
      onResponseError({ response }) {
        console.error(response._data.errors);
      },
    });

    if (res.id) {
      goToTestSettings(res.id);
    }
  } catch (err) {
    console.error(err);
  }
};

const goToTestSettings = (id) => {
  router.push({ name: "test-id", params: { id: id } });
};

const openEdit = () => {
  if (hasAccessRole(["admin"])) {
    if (patientData.value.doctor_id == 0) {
      isEditAccessOpen.value = true;
    }
    if (patientData.value.doctor_id == userData.value.id) {
      isAddPatientVisible.value = true;
    }
  } else {
    isAddPatientVisible.value = true;
  }
};

const onTypeClicked = (typeId) => {
  if (clickedTypes.value.some((v) => v == typeId)) {
    clickedTypes.value = clickedTypes.value.filter((v) => v != typeId);
  } else {
    clickedTypes.value.push(typeId);
  }
};
onMounted(() => {
  loadPatientData(props.patientId);
});
</script>

<template>
  <PatientArchiveDialog
    :id="patientData.id"
    v-model:is-dialog-visible="isArchiveDialogVisible"
    @archived="returnToPatientList"
    v-if="patientData.id"
  />
  <AddEditPatientDialog
    :id="patientData.id"
    v-model:is-dialog-visible="isAddPatientVisible"
    @saved="reloadData"
  />
  <EditPatientAccess
    :id="patientData.id"
    v-model:is-dialog-visible="isEditAccessOpen"
    @saved="reloadData"
  />

  <VCard class="mb-4" v-if="showPatientDetail">
    <VCardText class="pt-12">
      <VRow>
        <VCol cols="3" class="d-flex flex-column">
          <div class="d-flex justify-start align-start">
            <VAvatar
              rounded
              :size="100"
              style="
                background: rgb(115 103 240 / 22%) !important;
                border-radius: 50%;
              "
            >
              <!--              <VImg :src="userAvatar" />-->
              <VIcon icon="tabler-user" color="primary" size="100" />
            </VAvatar>
            <div class="d-flex flex-column align-start justify-start ms-4">
              <h5 class="text-h5">
                {{ patientData.detail_full_name }}
              </h5>
              <h6 class="text-h6">
                {{ patientData.phone }}
              </h6>
              <ProfileAccess :accessDetail="patientData.accessDetail" />
              <div class="d-flex justify-center gap-x-4 mt-2">
                <VBtn
                  variant="elevated"
                  size="small"
                  visible="true"
                  @click="openEdit()"
                >
                  Редагувати
                </VBtn>

                <VBtn
                  variant="tonal"
                  color="error"
                  size="small"
                  @click="isArchiveDialogVisible = !isArchiveDialogVisible"
                >
                  Архівувати
                </VBtn>
              </div>
            </div>
          </div>

          <!--          <div>-->
          <!--            <div class="d-flex justify-start gap-x-6 gap-y-2 flex-wrap mt-3">-->
          <!-- 👉 Done task -->
          <!--              <div class="d-flex align-center me-8">-->
          <!--                <VAvatar-->
          <!--                  :size="40"-->
          <!--                  rounded-->
          <!--                  :color="patientData.gender === 0 ? 'error' : 'primary'"-->
          <!--                  variant="tonal"-->
          <!--                  class="me-4"-->
          <!--                >-->
          <!--                  <VIcon icon="tabler-man" size="24" />-->
          <!--                </VAvatar>-->

          <!--                <div>-->
          <!--                  <h6 class="text-h6 text-left">Стать</h6>-->

          <!--                  <span class="text-sm text-left">{{-->
          <!--                    $t("gender." + (patientData.gender == 0 ? "woman" : "man"))-->
          <!--                  }}</span>-->
          <!--                </div>-->
          <!--              </div>-->

          <!-- 👉 Done Project -->
          <!--              <div class="d-flex align-center me-4">-->
          <!--                <VAvatar-->
          <!--                  :size="38"-->
          <!--                  rounded-->
          <!--                  color="primary"-->
          <!--                  variant="tonal"-->
          <!--                  class="me-4"-->
          <!--                >-->
          <!--                  <VIcon icon="tabler-calendar" size="24" />-->
          <!--                </VAvatar>-->
          <!--                <div>-->
          <!--                  <h6 class="text-h6 text-left">Вік</h6>-->
          <!--                  <span class="text-sm text-left"-->
          <!--                    >{{ patientData.age }} роки</span-->
          <!--                  >-->
          <!--                </div>-->
          <!--              </div>-->
          <!--            </div>-->
          <!--          </div>-->
        </VCol>
        <VCol cols="3" class="d-flex flex-column align-start justify-start">
          <VList class="card-list">
            <VListItem class="pb-2">
              <div class="d-flex align-center justify-start">
                <h6 class="text-h6 text-left d-flex flex-column mr-2">Вік:</h6>
                <div class="d-inline-block text-body-1 text-capitalize">
                  {{ patientData.age }}
                  <span style="text-transform: lowercase">роки(ів)</span>
                </div>
              </div>
            </VListItem>
            <VListItem>
              <VListItemTitle>
                <div class="d-flex align-center justify-start">
                  <h6 class="text-h6 text-left d-flex flex-column mr-2">
                    Стать:
                  </h6>
                  <div class="d-inline-block text-body-1 text-capitalize">
                    {{
                      $t(
                        "gender." + (patientData.gender == 0 ? "woman" : "man")
                      )
                    }}
                  </div>
                </div>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <div class="d-flex align-center justify-start">
                  <h6 class="text-h6 text-left d-flex flex-column mr-2">
                    Теги:
                  </h6>
                  <div class="d-flex text-body-1 gap-1">
                    <VChip
                      color="primary"
                      variant="outlined"
                      v-for="tag in patientData.tags"
                    >
                      {{ tag.label }}
                    </VChip>
                  </div>
                </div>
              </VListItemTitle>
            </VListItem>
          </VList>
        </VCol>
        <VCol cols="3">
          <VList class="card-list">
            <VListItem>
              <VListItemTitle>
                <div class="d-flex align-center justify-start">
                  <h6 class="text-h6 text-left d-flex flex-column mr-2">
                    Сфера:
                  </h6>
                  <div class="d-inline-block text-body-1 text-capitalize">
                    {{ patientData.field }}
                  </div>
                </div>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <div class="d-flex align-center justify-start">
                  <h6 class="text-h6 text-left d-flex flex-column mr-2">
                    Область:
                  </h6>
                  <div class="d-inline-block text-body-1 text-capitalize">
                    {{ patientData.region && patientData.region.label }}
                  </div>
                </div>
              </VListItemTitle>
            </VListItem>
            <VListItem>
              <VListItemTitle>
                <div class="d-flex align-center justify-start">
                  <h6 class="text-h6 text-left d-flex flex-column mr-2">
                    Клінічний діагноз:
                  </h6>
                  <div class="d-inline-block text-body-1 text-capitalize">
                    {{ patientData.clinic_diagnose_title }}
                  </div>
                </div>
              </VListItemTitle>
            </VListItem>
          </VList>
        </VCol>
        <VCol cols="3">
          <table class="w-100">
            <thead>
              <tr>
                <td>
                  {{ $t("promo.code") }}
                </td>
                <td>
                  {{ $t("promo.activated_at") }}
                </td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="promoCode in patientData.promoCodes">
                <td>{{ promoCode.code }}</td>
                <td>
                  {{ promoCode.activated_at }}
                </td>
              </tr>
            </tbody>
          </table>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>

  <VCard class="mb-4" v-if="!showPatientDetail">
    <VCardText class="pt-12">
      <VRow>
        <VCol cols="12" class="d-flex flex-column">
          <ProfileAccess
            :accessDetail="patientData.accessDetail"
          ></ProfileAccess>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
  <VRow>
    <!--    <VCol :cols="12 / patientData.examTypes.length"-->
    <VCol cols="12" v-for="examType in patientData.examTypes">
      <!-- 👉 User Activity timeline -->
      <VCard>
        <VCardItem class="notification-section">
          <div class="d-flex align-center justify-start">
            <h4 class="text-h4">
              {{ examType.label }}
            </h4>
            <template v-for="type in types">
              <VBtn
                variant="elevated"
                visible="true"
                class="ms-4"
                @click="startTest(type.id, examType.id)"
              >
                {{ type.label }}
              </VBtn>
            </template>

            <VBtn
              variant="elevated"
              visible="true"
              class="ms-auto"
              :class="{ 'bg-danger': clickedTypes.includes(examType.id) }"
              @click="onTypeClicked(examType.id)"
            >
              <VIcon
                size="24"
                icon="tabler-chevron-down"
              />
            </VBtn>
          </div>
        </VCardItem>

        <VCardText>
          <div class="scrollable">
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
                v-for="examResult in patientData.exams.filter(
                  (v) => v.exam_id == examType.id
                )"
              >
                <!-- 👉 Header -->
                <div
                  class="d-flex justify-space-between align-center gap-2 flex-wrap mb-2"
                >
                  <span class="app-timeline-title">
                    {{ examResult.type_result_label }}:
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
                    <VChip :color="'error'" class="ml-2">{{
                      examResult.incorrect_count
                    }}</VChip>
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
                    color="secondary"
                  >
                    Переглянути результат
                  </VBtn>
                </div>
              </VTimelineItem>
              <!-- !SECTION -->
            </VTimeline>
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style scoped>
.card-list .v-list-item:not(:last-child) {
  padding-bottom: 10px !important;
}
.scrollable {
  height: 260px;
  overflow-y: auto;
  //border: 1px solid #ccc;
  padding: 6px 10px 0px 0;
  box-sizing: border-box;
}

.scrollable.active {
  height: auto;

}
</style>
