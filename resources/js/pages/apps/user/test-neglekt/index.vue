<script setup lang="ts">

const value = ref(100)

import miscMaskDark from '@images/Screenshot_1.png'


const numberedSteps = [
  {
    title: 'Налаштування',
    // subtitle: 'Відкорегуйте налаштування для тесту',
  },
  {
    title: 'Тест',
    // subtitle: 'Add personal info',
  },
  {
    title: 'Результати',
    // subtitle: 'Add social links',
  },
]

const currentStep = ref(0)
const isPasswordVisible = ref(false)
const isCPasswordVisible = ref(false)

const formData = ref({
  username: '',
  email: '',
  password: '',
  cPassword: '',
  firstName: '',
  lastName: '',
  country: undefined,
  language: undefined,
  twitter: '',
  facebook: '',
  googlePlus: '',
  LinkedIn: '',

})





import { ref, onMounted } from 'vue';

const symbols = ref<{ type: string; x: number; y: number; [key: string]: any }[]>([]);
const symbolSize = 50; // розмір кожного символу в пікселях
const selectedSymbol = ref<number | null>(null);

const areaWidth = 500; // Ширина області
const areaHeight = 500; // Висота області

const createSymbol = (type: string, x: number, y: number) => {
  let symbolProps: { [key: string]: any } = { x, y };

  switch (type) {
    case 'rect':
      symbolProps = { ...symbolProps, x: 5, y: 5 };
      break;
    case 'circle':
      symbolProps = { ...symbolProps, cx: symbolSize / 2, cy: symbolSize / 2, r: symbolSize / 2 - 5 };
      break;
    case 'polygon':
      symbolProps = { ...symbolProps, points: '25,5 45,25 25,45 5,25' }; // ромб
      break;
    case 'path':
      symbolProps = {
        ...symbolProps,
        d: `M 5,${symbolSize / 2} A ${symbolSize / 2},${symbolSize / 2} 0 0 1 ${symbolSize - 5},${symbolSize / 2}`,
      }; // півколо
      break;
  }

  return { type, ...symbolProps };
};

const generateSymbols = () => {
  const columns = 10; // Кількість колонок
  const rows = 10; // Кількість рядків
  const horizontalSpacing = areaWidth / columns;
  const verticalSpacing = areaHeight / rows;

  let index = 0;

  for (let row = 0; row < rows; row++) {
    for (let col = 0; col < columns; col++) {
      let type;
      if (index < 30) type = 'rect';
      else if (index < 50) type = 'circle';
      else if (index < 80) type = 'polygon';
      else type = 'path';

      const x = col * horizontalSpacing;
      const y = row * verticalSpacing;
      symbols.value.push(createSymbol(type, x, y));
      index++;
    }
  }
};

const selectSymbol = (index: number) => {
  selectedSymbol.value = index === selectedSymbol.value ? null : index;
};

onMounted(() => {
  generateSymbols();
});
</script>



<template>
  <VCard class="mb-6">
    <VCardItem class="pb-4">
      <VCardTitle>Неглект тест</VCardTitle>
    </VCardItem>
  </VCard>

  <VCard>
    <VCardText>
      <!-- 👉 Stepper -->
      <AppStepper
        v-model:current-step="currentStep"
        :items="numberedSteps"
        class="stepper-icon-step-bg"
      />
    </VCardText>

    <VDivider />

    <VCardText>
      <!-- 👉 stepper content -->
      <VForm>
        <VWindow
          v-model="currentStep"
          class="disable-tab-transition"
        >
          <VWindowItem>

                    <VRow>

                      <VCol
                        cols="12"
                      >
                        <h5 class="text-h5 mb-5">
                          Час тесту
                        </h5>
                        <VSlider
                          v-model="value"
                          :step="10"
                          thumb-label="always"
                          show-ticks
                        />
                      </VCol>

                      <VCol
                        cols="6"
                      >
                        <h5 class="text-h5 mb-1">
                          Опис налаштувань
                        </h5>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusantium alias aspernatur atque
                        consequatur culpa cum debitis ducimus earum error est ex excepturi exercitationem hic in inventore natus
                        necessitatibus nisi omnis perspiciatis possimus quam quas, quia soluta voluptate voluptatibus
                        voluptatum?


                      </VCol>
                      <VCol
                        cols="6"
                      >
                        <img
                          class="misc-footer-img d-none d-md-block"
                          :src="miscMaskDark"
                          alt="misc-footer-img"
                          width="100%"
                        >

                        <div class="d-flex align-center justify-center flex-column">
                          <div class="w-100">
                            <VSlider
                              v-model="value"
                              :step="10"
                              thumb-label="always"
                              show-ticks
                            />
                          </div>
                          <VBtn
                            size="large"

                          >

                            Почати
                            <VIcon
                              end
                              icon="tabler-play"
                            />
                          </VBtn>
                        </div>
                      </VCol>


                    </VRow>

          </VWindowItem>

          <VWindowItem>

<!--            <div class="border rounded mt-5 pa-5" style="min-height: 300px"></div>-->
            <div ref="container" class="symbol-container border rounded mt-5 pa-5" style="position: relative; width: 780px; height: 600px;">
              <svg
                v-for="(symbol, index) in symbols"
                :key="index"
                :width="symbolSize"
                :height="symbolSize"
                :x="symbol.x"
                :y="symbol.y"
                @click="selectSymbol(index)"
              >
                <component
                  :is="symbol.type"
                  :cx="symbol.cx"
                  :cy="symbol.cy"
                  :rx="symbol.rx"
                  :ry="symbol.ry"
                  :r="symbol.r"
                  :points="symbol.points"
                  :width="symbolSize - 10"
                  :height="symbolSize - 10"
                  stroke="black"
                  fill="none"
                />
                <!-- Виділення червоним кругом -->
                <circle
                  v-if="selectedSymbol === index"
                  :cx="symbol.cx || symbol.x + symbolSize / 2"
                  :cy="symbol.cy || symbol.y + symbolSize / 2"
                  :r="symbolSize / 2 + 5"
                  stroke="red"
                  fill="none"
                />
              </svg>
            </div>




          </VWindowItem>

          <VWindowItem>
            <VRow>
              <VCol
                cols="12"
                md="6"
              >
              <VCardText>
                <VList class="card-list">
                  <VListItem

                  >
                    <template #prepend>
                      <VAvatar
                        color="info"
                        variant="tonal"
                        size="34"
                        rounded
                        class="me-1"
                      >
                        <VIcon
                          icon="tabler-clock-hour-1"
                          size="22"
                        />
                      </VAvatar>
                    </template>
                    <VListItemTitle class="font-weight-medium me-4">
                      Час тестування
                    </VListItemTitle>
                    <template #append>
                      <div class="d-flex gap-x-4">
                        <div class="text-body-1">
                          20хв

                        </div>
<!--                        <div class="text-error">-->
<!--                          0.3%-->
<!--                        </div>-->
                      </div>
                    </template>
                  </VListItem>

                  <VListItem

                  >
                    <template #prepend>
                      <VAvatar
                        color="success"
                        variant="tonal"
                        size="34"
                        rounded
                        class="me-1"
                      >
                        <VIcon
                          icon="tabler-circle-check"
                          size="22"
                        />
                      </VAvatar>
                    </template>
                    <VListItemTitle class="font-weight-medium me-4">
                      Кількість правильних стимулів:
                    </VListItemTitle>
                    <template #append>
                      <div class="d-flex gap-x-4">
                        <div class="text-body-1">
                          10

                        </div>
                        <!--                        <div class="text-error">-->
                        <!--                          0.3%-->
                        <!--                        </div>-->
                      </div>
                    </template>
                  </VListItem>
                  <VListItem

                  >
                    <template #prepend>
                      <VAvatar
                        color="error"
                        variant="tonal"
                        size="34"
                        rounded
                        class="me-1"
                      >
                        <VIcon
                          icon="tabler-exclamation-circle"
                          size="22"
                        />
                      </VAvatar>
                    </template>
                    <VListItemTitle class="font-weight-medium me-4">
                      Кількість неправильних стимулів:
                    </VListItemTitle>
                    <template #append>
                      <div class="d-flex gap-x-4">
                        <div class="text-body-1">
                          6

                        </div>
                        <!--                        <div class="text-error">-->
                        <!--                          0.3%-->
                        <!--                        </div>-->
                      </div>
                    </template>
                  </VListItem>


                  <VListItem

                  >
                    <template #prepend>
                      <VAvatar
                        color="secondary"
                        variant="tonal"
                        size="34"
                        rounded
                        class="me-1"
                      >
                        <VIcon
                          icon="tabler-arrow-big-right"
                          size="22"
                        />
                      </VAvatar>
                    </template>
                    <VListItemTitle class="font-weight-medium me-4">
                      Кількість  файльш цілей з правого боку
                    </VListItemTitle>
                    <template #append>
                      <div class="d-flex gap-x-4">
                        <div class="text-body-1">
                          6

                        </div>
                        <!--                        <div class="text-error">-->
                        <!--                          0.3%-->
                        <!--                        </div>-->
                      </div>
                    </template>
                  </VListItem>

                  <VListItem

                  >
                    <template #prepend>
                      <VAvatar
                        color="secondary"
                        variant="tonal"
                        size="34"
                        rounded
                        class="me-1"
                      >
                        <VIcon
                          icon="tabler-arrow-big-left"
                          size="22"
                        />
                      </VAvatar>
                    </template>
                    <VListItemTitle class="font-weight-medium me-4">
                      Кількість  файльш цілей з лівого боку
                    </VListItemTitle>
                    <template #append>
                      <div class="d-flex gap-x-4">
                        <div class="text-body-1">
                          6

                        </div>
                        <!--                        <div class="text-error">-->
                        <!--                          0.3%-->
                        <!--                        </div>-->
                      </div>
                    </template>
                  </VListItem>

                </VList>
              </VCardText>

              </VCol>






              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="formData.LinkedIn"

                  label="Коментар"
                />
              </VCol>
            </VRow>
          </VWindowItem>
        </VWindow>

        <div class="d-flex flex-wrap gap-4 justify-sm-space-between justify-center mt-8">
          <VBtn
            color="secondary"
            variant="tonal"
            :disabled="currentStep === 0"
            @click="currentStep--"
          >
            <VIcon
              icon="tabler-arrow-left"
              start
              class="flip-in-rtl"
            />
            Назад
          </VBtn>

          <VBtn
            v-if="numberedSteps.length - 1 === currentStep"
            color="success"

          >
            Завершити
          </VBtn>

          <VBtn
            v-else
            @click="currentStep++"
          >
            Далі

            <VIcon
              icon="tabler-arrow-right"
              end
              class="flip-in-rtl"
            />
          </VBtn>
        </div>
      </VForm>
    </VCardText>
  </VCard>
</template>

<style>

</style>
