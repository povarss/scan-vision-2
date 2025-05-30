<script setup>
import { ref, onMounted } from "vue";
import { useNotifyStore } from "@/views/apps/notify/useNotifyStore";
import { isSvgExam } from "./testUtil";
const { t } = useI18n();

const props = defineProps({
  exam: {
    type: [Object],
    required: true,
  },
  references: {
    type: [Object],
  },
});

const resultData = ref({
  totalMinute: 0,
  testMinute: 0,
  testSecond: 0,
  correctCount: {
    selected: 0,
    total: 0,
    left: 0,
    right: 0,
  },
  incorrectCount: {
    total: 0,
    left: 0,
    right: 0,
    double_total: 0,
    left_double: 0,
    right_double: 0,
  },
  typeLabel: "",
  analyzeInfo: {
    messages: [],
    recommendations: [],
  },
});
const emit = defineEmits(["dataLoaded"]);
const notifyStore = useNotifyStore();

const getResult = async () => {
  const { data } = await useApi(`/exam/info/` + props.exam.id);
  if (data.value) {
    resultData.value = data.value;
    emit("dataLoaded", data.value);
    if (resultData.value.showTimeNotification == 1) {
      notifyStore.showNotification("", t("promo.AccessMinutesExceeded"));
    }
  }
  console.log(resultData.value, "resultData");
};

onMounted(() => {
  getResult();
});
</script>

<template>
  <VRow>
    <VCol cols="12" md="12">
      <VCardText>
        <VList class="card-list">
          <VListItem>
            <template #prepend>
              <VAvatar
                color="info"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-clock-hour-1" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Час скринінгу
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">
                  {{ resultData.testMinute }}хв {{ resultData.testSecond }}сек з
                  {{ resultData.totalMinute }}хв
                </div>
              </div>
            </template>
          </VListItem>

          <VListItem>
            <template #prepend>
              <VAvatar
                color="success"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-circle-check" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Кількість правильних стимулів:
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">
                  {{ resultData.correctCount.selected }} з
                  {{ resultData.correctCount.total }}
                </div>
              </div>
            </template>
          </VListItem>
          <VListItem>
            <template #prepend>
              <VAvatar
                color="secondary"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-arrow-big-right" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Кількість правильних цілей з правого боку
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">
                  {{ resultData.correctCount.right }}
                </div>
              </div>
            </template>
          </VListItem>
          <VListItem>
            <template #prepend>
              <VAvatar
                color="secondary"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-arrow-big-left" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Кількість правильних цілей з лівого боку
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">
                  {{ resultData.correctCount.left }}
                </div>
              </div>
            </template>
          </VListItem>

          <VListItem>
            <template #prepend>
              <VAvatar
                color="error"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-exclamation-circle" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Кількість неправильних стимулів:
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">
                  {{ resultData.incorrectCount.total }}
                </div>
              </div>
            </template>
          </VListItem>

          <VListItem>
            <template #prepend>
              <VAvatar
                color="secondary"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-arrow-big-right" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Кількість помилкових цілей з правого боку
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">
                  {{ resultData.incorrectCount.right }}
                </div>
              </div>
            </template>
          </VListItem>

          <VListItem>
            <template #prepend>
              <VAvatar
                color="secondary"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-arrow-big-left" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Кількість помилкових цілей з лівого боку
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">
                  {{ resultData.incorrectCount.left }}
                </div>
              </div>
            </template>
          </VListItem>

          <VListItem>
            <template #prepend>
              <VAvatar
                color="error"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-exclamation-circle" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Кількість неправильних стимулів з подвійним кліком:
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">
                  {{ resultData.incorrectCount.double_total }}
                </div>
              </div>
            </template>
          </VListItem>

          <VListItem>
            <template #prepend>
              <VAvatar
                color="secondary"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-arrow-big-right" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Кількість помилкових цілей з правого боку з подвійним кліком
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">
                  {{ resultData.incorrectCount.right_double }}
                </div>
              </div>
            </template>
          </VListItem>

          <VListItem>
            <template #prepend>
              <VAvatar
                color="secondary"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-arrow-big-left" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Кількість помилкових цілей з лівого боку з подвійним кліком
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">
                  {{ resultData.incorrectCount.left_double }}
                </div>
              </div>
            </template>
          </VListItem>
        </VList>
      </VCardText>
    </VCol>
    <VCol cols="12" md="12">
      <div>
        <table style="color: #0058a0; padding: 0 0 0 0 !important">
          <tr
            style="padding: 0 0 0 0 !important"
            v-for="message in resultData.analyzeInfo.messages"
          >
            <td style="padding: 0 0 0 0 !important">
              {{ message }}
            </td>
          </tr>
        </table>
        <table
          style="
            color: #0058a0;
            padding: 0 0 0 0 !important;
            font-style: italic;
          "
        >
          <tr
            style="padding: 0 0 0 0 !important"
            v-for="recommendation in resultData.analyzeInfo.recommendations"
          >
            <td style="padding: 0 0 0 0 !important">
              {{ recommendation }}
            </td>
          </tr>
        </table>
      </div>
    </VCol>

    <VCol cols="12" md="12" v-if="isSvgExam(resultData)">
      <div class="symbol-container border rounded mt-5 pa-5">
        <TestMapView :exam="exam" :references="references" />
      </div>
    </VCol>
  </VRow>
</template>

<style></style>
