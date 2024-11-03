<script setup>

import avatar1 from "@images/avatars/avatar-1.png"

// const isUserInfoEditDialogVisible = ref(false)
import miscMaskDark from '@images/Screenshot_1.png'

</script>

<template>
  <VCard class="mb-4">
    <VCardText class=" pt-12">
      <VRow>
        <VCol
          cols="5"
          class="d-flex flex-column"
        >
          <div class="d-flex justify-start align-start">
            <VAvatar
              rounded
              :size="100"
            >
              <VImg :src="miscMaskDark"/>
            </VAvatar>
            <div class="d-flex flex-column align-start justify-start ms-4">
              <h5 class="text-h5  ">
                Іванов Іван
              </h5>
              <h6 class="text-h6 ">
                +380508465592
              </h6>
              <div class="d-flex justify-center gap-x-4 mt-2">
                <VBtn
                  variant="elevated"
                  visible="true"
                >
                  <!--                  @click="isUserInfoEditDialogV-->
                  Редагувати
                </VBtn>

                <VBtn
                  variant="tonal"
                  color="error"
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
                <!--            <VAvatar-->
                <!--              :size="40"-->
                <!--              rounded-->
                <!--              color="error"-->
                <!--              variant="tonal"-->
                <!--              class="me-4"-->
                <!--            >-->
                <!--              <VIcon-->
                <!--                icon="tabler-woman"-->
                <!--                size="24"-->
                <!--              />-->

                <!--            </VAvatar>-->
                <VAvatar
                  :size="40"
                  rounded
                  color="primary"
                  variant="tonal"
                  class="me-4"
                >
                  <VIcon
                    icon="tabler-man"
                    size="24"
                  />
                </VAvatar>

                <div>
                  <h6 class="text-h6 text-left">
                    Стать
                  </h6>

                  <span class="text-sm text-left">Чоловіча</span>
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
                  <VIcon
                    icon="tabler-calendar"
                    size="24"
                  />
                </VAvatar>
                <div>
                  <h6 class="text-h6 text-left">
                    Вік
                  </h6>
                  <span class="text-sm text-left">33 роки</span>
                </div>
              </div>
            </div>
          </div>
        </VCol>
        <VCol
          cols="5"
          class="d-flex flex-column align-start justify-start"
        >
          <VList class="card-list mt-2">
            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6 text-left d-flex flex-column">
                  Теги:
                  <div class="d-flex text-body-1 gap-1">
                    <VChip
                      color="primary"
                      variant="outlined"
                    >
                      Тег 1
                    </VChip>
                    <VChip
                      color="primary"
                      variant="outlined"
                    >
                      Тег 2
                    </VChip>
                    <VChip
                      color="primary"
                      variant="outlined"
                    >
                      Тег 3
                    </VChip>
                  </div>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6 text-left d-flex flex-column">
                  Сфера:
                </h6>
                <span class="text-body-1">
                  Нефрологія, офтальмологія
                </span>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6 text-left d-flex flex-column">

                  Область:
                  <div class="d-inline-block text-body-1 text-capitalize">
                    Вінницька
                  </div>
                </h6>
              </VListItemTitle>
            </VListItem>


          </VList>


        </VCol>
        <VCol
          cols="4"
        >
        </VCol>
      </VRow>
    </VCardText>
  </VCard>

  <VRow>
    <VCol cols="6">
      <!-- 👉 User Activity timeline -->
      <VCard >
        <VCardItem class="notification-section">

          <div class="d-flex align-center justify-start">
            <h4 class="text-h4">Неглект тест</h4>

            <VBtn
              variant="elevated"
              visible="true"
              class="ms-4"
              :to="{ name: 'apps-user-test-neglekt' }"
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
              dot-color="error"
              size="x-small"
            >
              <!-- 👉 Header -->
              <div class="d-flex justify-space-between align-center gap-2 flex-wrap mb-2">
                <span class="app-timeline-title">
                  Результат:   <VChip color="error">20%</VChip>
                </span>
                <span class="app-timeline-meta">01.11.2024</span>
              </div>

              <!-- 👉 Content -->
              <div class="app-timeline-text mt-1">
                Тест пройдено без проблем
              </div>
            </VTimelineItem>
            <!-- !SECTION -->

            <VTimelineItem
              dot-color="primary"
              size="x-small"
            >
              <!-- 👉 Header -->
              <div class="d-flex justify-space-between align-center gap-2 flex-wrap mb-2">
                <span class="app-timeline-title">
                  Результат:   <VChip color="primary">50%</VChip>
                </span>
                <span class="app-timeline-meta">01.11.2024</span>
              </div>

              <!-- 👉 Content -->
              <div class="app-timeline-text mt-1">
                Тест пройдено без проблем
              </div>
            </VTimelineItem>
            <VTimelineItem
              dot-color="primary"
              size="x-small"
            >
              <!-- 👉 Header -->
              <div class="d-flex justify-space-between align-center gap-2 flex-wrap mb-2">
                <span class="app-timeline-title">
                  Результат:   <VChip>20%</VChip>
                </span>
                <span class="app-timeline-meta">01.11.2024</span>
              </div>

              <!-- 👉 Content -->
              <div class="app-timeline-text mt-1">
                Тест пройдено без проблем
              </div>
            </VTimelineItem>
            <VTimelineItem
              dot-color="success"
              size="x-small"
            >
              <!-- 👉 Header -->
              <div class="d-flex justify-space-between align-center gap-2 flex-wrap mb-2">
                <span class="app-timeline-title">
                  Результат:   <VChip color="success">100%</VChip>
                </span>
                <span class="app-timeline-meta">01.11.2024</span>
              </div>

              <!-- 👉 Content -->
              <div class="app-timeline-text mt-1">
                Тест пройдено без проблем
              </div>
            </VTimelineItem>
          </VTimeline>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>


  <!-- 👉 Edit user info dialog -->
  <!--  <UserInfoEditDialog-->
  <!--    v-model:isDialogVisible="isUserInfoEditDialogVisible"-->
  <!--  />-->


</template>


