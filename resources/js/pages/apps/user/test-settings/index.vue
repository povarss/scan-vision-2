<!--<script lang="ts" setup>-->
<!--const value = ref(20)-->
<!--const timeSlider = ref(20)-->

<!--const ticksLabels = {  5: '5хв', 10: '10хв', 15: '15хв', 20: '20хв' }-->

<!--import miscMaskDark from '@images/Screenshot_1.png'-->
<!--// const authThemeMask = miscMaskDark-->


<!--import type { CustomInputContent } from '@core/types'-->

<!--const radioContent: CustomInputContent[] = [-->
<!--  {-->
<!--    title: 'Легкий',-->
<!--    desc: 'Розмір стимулів 2см',-->
<!--    value: 'starter',-->
<!--    icon: { icon: 'tabler-rocket', size: '28' },-->
<!--  },-->
<!--  {-->
<!--    title: 'Середній',-->
<!--    desc: 'Розмір стимулів 1.5см',-->
<!--    value: 'personal',-->
<!--    icon: { icon: 'tabler-star', size: '28' },-->
<!--  },-->
<!--  {-->
<!--    title: 'Важкий ',-->
<!--    desc: 'Розмір стимулів 1см',-->
<!--    value: 'enterprise',-->
<!--    icon: { icon: 'tabler-crown', size: '28' },-->
<!--  },-->
<!--]-->




<!--const selectedRadioLevel = ref('starter')-->


<!--const radioContentLevel: CustomInputContent[] = [-->
<!--  {-->
<!--    title: 'Single',-->
<!--    desc: 'Один цільовий стимул',-->
<!--    value: 'starter',-->
<!--    icon: { icon: 'tabler-box-multiple-1', size: '28' },-->
<!--  },-->
<!--  {-->
<!--    title: 'Dual',-->
<!--    desc: 'Два цільових стимули',-->
<!--    value: 'personal',-->
<!--    icon: { icon: 'tabler-box-multiple-2', size: '28' },-->

<!--  },-->
<!--]-->




<!--const selectedRadio = ref('starter')-->

<!--</script>-->

<!--<template>-->
<!--  <section>-->
<!--    <h4 class="text-h4 mb-1">-->
<!--      Налаштування тесту-->
<!--    </h4>-->


<!--    <VRow>-->
<!--      &lt;!&ndash; 👉 Select Role &ndash;&gt;-->


<!--      <VCol-->
<!--        cols="12"-->
<!--        sm="12"-->
<!--      >-->
<!--        <VCard class="mb-6">-->

<!--          <VCardText>-->
<!--            <VRow>-->

<!--              <VCol-->
<!--                cols="12"-->
<!--              >-->
<!--                <h5 class="text-h5 mb-5">-->
<!--                  Тривалість скринінгу-->
<!--                </h5>-->
<!--                <VSlider-->
<!--                  v-model="timeSlider"-->
<!--                  :step="5"-->
<!--                  :min="5"-->
<!--                  :max="20"-->


<!--                  :ticks="ticksLabels"-->
<!--                  show-ticks="always"-->
<!--                  tick-size="4"-->
<!--                >-->
<!--                  <template #thumb-label="{ modelValue }">-->
<!--                    <div>{{ modelValue + ' хв'}}</div>-->
<!--                  </template>-->
<!--                </VSlider>-->
<!--              </VCol>-->

<!--              <VCol-->
<!--                cols="6"-->
<!--              >-->
<!--              <div class="mb-4">-->
<!--                <h5 class="text-h5 mb-1">-->
<!--                  Опис налаштувань-->
<!--                </h5>-->
<!--                Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusantium alias aspernatur atque-->
<!--                consequatur culpa cum debitis ducimus earum error est ex excepturi exercitationem hic in inventore natus-->
<!--                necessitatibus nisi omnis perspiciatis possimus quam quas, quia soluta voluptate voluptatibus-->
<!--                voluptatum?-->
<!--              </div>-->


<!--                <div class="mb-4">-->

<!--                <h5 class="text-h5 mb-1">-->
<!--                    Рівень скринінгу-->
<!--                  </h5>-->
<!--                  <CustomRadiosWithIcon-->
<!--                    v-model:selected-radio="selectedRadio"-->
<!--                    :radio-content="radioContent"-->
<!--                    :grid-column="{ sm: '4', cols: '12' }"-->
<!--                  />-->
<!--                </div>-->
<!--                <div class="mb-4">-->

<!--                  <h5 class="text-h5 mb-1">-->
<!--                    Режим скринінгу-->
<!--                  </h5>-->
<!--                  <CustomRadiosWithIcon-->
<!--                    v-model:selected-radio="selectedRadioLevel"-->
<!--                    :radio-content="radioContentLevel"-->
<!--                    :grid-column="{ sm: '6', cols: '12' }"-->
<!--                  />-->
<!--                </div>-->




<!--              </VCol>-->
<!--              <VCol-->
<!--                cols="6"-->
<!--              >-->
<!--                <img-->
<!--                  class="misc-footer-img d-none d-md-block"-->
<!--                  :src="miscMaskDark"-->
<!--                  alt="misc-footer-img"-->
<!--                  width="100%"-->
<!--                >-->

<!--                <div class="d-flex align-center justify-center flex-column">-->
<!--                  <div class="w-100">-->
<!--                    <VSlider-->
<!--                      v-model="value"-->
<!--                      :step="10"-->
<!--                      thumb-label="always"-->
<!--                      show-ticks-->
<!--                    />-->
<!--                  </div>-->
<!--&lt;!&ndash;                  <VBtn&ndash;&gt;-->
<!--&lt;!&ndash;                    size="large"&ndash;&gt;-->

<!--&lt;!&ndash;                  >&ndash;&gt;-->

<!--&lt;!&ndash;                    Почати&ndash;&gt;-->
<!--&lt;!&ndash;                    <VIcon&ndash;&gt;-->
<!--&lt;!&ndash;                      end&ndash;&gt;-->
<!--&lt;!&ndash;                      icon="tabler-play"&ndash;&gt;-->
<!--&lt;!&ndash;                    />&ndash;&gt;-->
<!--&lt;!&ndash;                  </VBtn>&ndash;&gt;-->
<!--                </div>-->
<!--              </VCol>-->


<!--            </VRow>-->

<!--          </VCardText>-->
<!--        </VCard>-->

<!--      </VCol>-->
<!--    </VRow>-->

<!--    <VDivider/>-->


<!--    &lt;!&ndash; 👉 Add New User &ndash;&gt;-->


<!--  </section>-->
<!--</template>-->

<!--<style>-->
<!--.v-slider-thumb__label {-->

<!--  min-width: 63px!important;-->
<!--}-->
<!--</style>-->
