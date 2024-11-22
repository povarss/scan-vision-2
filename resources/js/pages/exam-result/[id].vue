<script setup lang="ts">
import TestProcess from "@/components/exam/TestProcess.vue";
import Result from "@/components/exam/Result.vue";
import Setting from "@/components/exam/Setting.vue";
import { ref, onMounted } from "vue";

const route = useRoute("exam-result-id");

const numberedSteps = [
  {
    title: "Налаштування",
  },
  // {
  //   title: "Скринінг",
  // },
  {
    title: "Результати",
  },
];

const currentStep = ref(1);
const exam = ref({ id: null });
onMounted(() => {
  exam.value.id = route.params.id;
});
const isLoading = ref(false);

const result = ref([]);
const patientId = ref("0");

const onDataLoad = (data) => {
  patientId.value = data.patientId;
};
</script>

<template>
  <template v-if="isLoading">
    <VSkeletonLoader v-for="i in 3" :key="i" type="list-item-two-line" />
  </template>

  <VCard>
    <VCardItem class="pb-4">
      <VCardTitle>Неглект скринінг</VCardTitle>
    </VCardItem>
    <VDivider />

    <VCardText>
      <!-- 👉 Stepper -->
      <AppStepper
        v-model:current-step="currentStep"
        :items="numberedSteps"
        :isActiveStepFreeze="true"
        class="stepper-icon-step-bg"
      />
    </VCardText>

    <VDivider />

    <VCardText>
      <!-- 👉 stepper content -->
      <VForm>
        <VWindow v-model="currentStep" class="disable-tab-transition">
          <VWindowItem> </VWindowItem>


          <VWindowItem>
            <Result
              :exam="exam"
              v-if="currentStep == 1 && exam.id"
              @dataLoaded="onDataLoad"
            />
          </VWindowItem>
        </VWindow>

        <div
          class="d-flex flex-wrap gap-4 justify-sm-space-between justify-center mt-8"
        >
          <VBtn
            color="secondary"
            variant="tonal"
            :disabled="true"
            @click="currentStep--"
            class="mr-auto"
          >
            <VIcon icon="tabler-arrow-left" start class="flip-in-rtl" />
            Назад
          </VBtn>

          <VBtn
            :to="{ name: 'patients-card-id', params: { id: patientId } }"
            color="success"
          >
            Перейти на картку клієнта
          </VBtn>
          <PdfButton :btnLabel="'Друк PDF'" :url="'/exam/print/' + exam.id" />
<!--          <VBtn-->
<!--            v-if="numberedSteps.length - 1 === currentStep"-->
<!--            :disabled="true"-->
<!--            color="success"-->
<!--          >-->
<!--            Завершити-->
<!--          </VBtn>-->
        </div>
      </VForm>
    </VCardText>
  </VCard>
</template>

<style>
.v-slider-thumb__label {
  min-width: 63px !important;
}

.text-h6 {
}
</style>
