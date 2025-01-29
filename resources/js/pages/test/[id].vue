<script setup>
import TestProcess from "@/components/exam/TestProcess.vue";
import Result from "@/components/exam/Result.vue";
import Setting from "@/components/exam/Setting.vue";
import { ref, onMounted } from "vue";

const isTestVisible = ref(false);
const router = useRouter();
const route = useRoute("test-type-id");

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

const currentStep = ref(0);
const exam = ref(null);
// onMounted(() => {});
const isLoading = ref(false);

const store = async (setting) => {
  try {
    const res = await $api("/exam/setting", {
      method: "POST",
      body: {
        id: route.params.id,
        ...setting,
      },
      onResponseError({ response }) {
        console.error(response._data.errors);
      },
    });

    if (res.exam) {
      exam.value = res.exam;
    }
  } catch (err) {
    console.error(err);
  }
};

const storeResult = async () => {
  try {
    const res = await $api("/exam/result", {
      method: "POST",
      body: {
        id: exam.value?.id || "",
        result: result.value,
        double_clicks: doubleCLicks.value,
      },
      onResponseError({ response }) {
        console.error(response._data.errors);
      },
    });

    if (res.exam) {
      exam.value = res.exam;
    }
  } catch (err) {
    console.error(err);
  }
};

const result = ref([]);
const doubleCLicks = ref([]);
const onItemSelected = (items) => {
  result.value = items.map((pos) => {
    const [part1, part2] = pos.split("_");
    const intValue1 = parseInt(part1, 10);
    const intValue2 = parseInt(part2, 10);
    return [intValue1, intValue2];
  });
};

const onDoubleClicked = (item) => {
  const existingClick = doubleCLicks.value.find(
    (v) => v[0] == item.rowKey && v[1] == item.colKey
  );
  if (existingClick) {
    existingClick[2]++;
  } else {
    doubleCLicks.value.push([parseInt(item.rowKey), parseInt(item.colKey), 1]);
  }
};

const startTest = async (setting) => {
  isLoading.value = true;
  await store(setting);
  isLoading.value = false;
  isTestVisible.value = true;
};

const finishStep = 1;

const finishTestProcces = async () => {
  await storeResult();
  isTestVisible.value = false;
  currentStep.value = finishStep;
};

const returnToSettings = () => {
  exam.value = null;
  currentStep.value = 0;
  isTestVisible.value = false;
};

const referenceData = ref();
const loadExamReference = async () => {
  const { data } = await useApi(`/exam/refrences/${route.params.id}`);
  if (data.value) referenceData.value = data.value;
};

onMounted(() => {
  loadExamReference();
});
</script>

<template>
  <VDialog
    v-model="isTestVisible"
    fullscreen
    :scrim="false"
    id="myvideo"
    transition="dialog-bottom-transition"
  >
    <!-- Dialog Content -->
    <VCard>
      <div>
        <VToolbar color="primary">
          <VBtn icon variant="plain" @click.stop="returnToSettings">
            <VIcon color="white" icon="tabler-x" />
          </VBtn>

          <VToolbarTitle>Скринінг</VToolbarTitle>

          <VSpacer />

          <VToolbarItems>
            <VBtn variant="text" @click.stop="finishTestProcces">
              Завершити
            </VBtn>
          </VToolbarItems>
        </VToolbar>
      </div>

      <div class="d-flex align-center justify-center">
        <TestProcess
          v-if="exam"
          :exam="exam"
          :references="referenceData"
          @itemSelected="onItemSelected"
          @doubleClickItem="onDoubleClicked"
          @timeout="finishTestProcces"
        />
      </div>
    </VCard>
  </VDialog>

  <template v-if="isLoading">
    <VSkeletonLoader v-for="i in 3" :key="i" type="list-item-two-line" />
  </template>

  <VCard>
    <VCardItem class="pb-4">
      <VCardTitle>{{ referenceData?.title }}</VCardTitle>
    </VCardItem>
    <VDivider />

    <VCardText>
      <!-- 👉 Stepper -->
      <AppStepper
        v-model:current-step="currentStep"
        :items="numberedSteps"
        :isActiveStepFreeze="currentStep > -1"
        class="stepper-icon-step-bg"
      />
    </VCardText>

    <VDivider />

    <VCardText>
      <!-- 👉 stepper content -->
      <VForm>
        <VWindow v-model="currentStep" class="disable-tab-transition">
          <VWindowItem>
            <Setting @settingsSaved="startTest" :references="referenceData" />
          </VWindowItem>

          <VWindowItem>
            <Result
              :exam="exam"
              :references="referenceData"
              v-if="currentStep == finishStep"
            />
          </VWindowItem>
        </VWindow>

        <div
          class="d-flex flex-wrap gap-4 justify-sm-space-between justify-center mt-8"
        >
          <VBtn
            color="secondary"
            variant="tonal"
            :disabled="currentStep === 0 || currentStep == finishStep"
            @click="currentStep--"
            class="mr-auto"
          >
            <VIcon icon="tabler-arrow-left" start class="flip-in-rtl" />
            Назад
          </VBtn>

          <VBtn
            v-if="exam && exam.patient_id"
            :to="{ name: 'patients-card-id', params: { id: exam.patient_id } }"
            color="success"
          >
            Перейти на картку клієнта
          </VBtn>
          <PdfButton
            v-if="exam"
            :btnLabel="'Друк PDF'"
            :url="'/exam/print/' + exam.id"
          />

          <!--          <VBtn-->
          <!--            v-if="numberedSteps.length - 1 === currentStep"-->
          <!--            :disabled="currentStep == finishStep"-->
          <!--            color="success"-->
          <!--          >-->
          <!--            Завершити-->
          <!--          </VBtn>-->

          <!-- <VBtn v-else @click="currentStep++">
            Далі

            <VIcon icon="tabler-arrow-right" end class="flip-in-rtl" />
          </VBtn> -->
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
