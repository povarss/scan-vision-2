<script lang="ts" setup>
const value = ref(100)
// const rules = [(v: number) => v <= 40 || 'Only 40 in stock']

import miscMaskDark from '@images/Screenshot_1.png'
// const authThemeMask = miscMaskDark

</script>

<template>
  <section>
    <h4 class="text-h4 mb-1">
      Налаштування тесту
    </h4>


    <VRow>
      <!-- 👉 Select Role -->


      <VCol
        cols="12"
        sm="12"
      >
        <VCard class="mb-6">

          <VCardText>
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

          </VCardText>
        </VCard>

      </VCol>
    </VRow>

    <VDivider/>


    <!-- 👉 Add New User -->


  </section>
</template>
