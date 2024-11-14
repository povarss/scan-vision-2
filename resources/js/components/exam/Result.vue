<script setup lang="ts">
import { ref, onMounted } from "vue";
import testImg from "@images/test_img.png";

const props = defineProps({
  exam: {
    type: [Object],
    required: true,
  },
});

const resultData = ref({
  totalMinute: 0,
  testMinute: 0,
  correctCount: {
    total: 0,
    left: 0,
    right: 0,
  },
  incorrectCount: {
    total: 0,
    left: 0,
    right: 0,
  },
});

const getResult = async () => {
  const { data } = await useApi(`/exam/info/` + props.exam.id);
  if (data.value) {
    resultData.value = data.value;
  }
  console.log(resultData.value, "resultData");
};

onMounted(() => {
  getResult();
});
</script>

<template>
  <VRow>
    <VCol cols="12" md="6">
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
                  {{ resultData.testMinute }}хв з {{ resultData.totalMinute }}хв
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
                  {{ resultData.correctCount.total }}
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
              Кількість файльш цілей з правого боку
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
              Кількість файльш цілей з лівого боку
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">
                  {{ resultData.incorrectCount.left }}
                </div>
              </div>
            </template>
          </VListItem>
        </VList>
      </VCardText>
    </VCol>

    <VCol cols="12" md="6">
      <div class="symbol-container border rounded mt-5 pa-5">
        <TestProcess :exam="exam" is-readonly="true" />

        <!-- <img
          class="misc-footer-img d-none d-md-block"
          :src="testImg"
          alt="misc-footer-img"
          width="100%"
        /> -->
      </div>
    </VCol>
  </VRow>
</template>

<style></style>
