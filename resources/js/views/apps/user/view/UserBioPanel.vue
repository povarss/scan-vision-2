<script setup>
import pdf from "@images/icons/project-icons/pdf.png"
import avatar1 from "@images/avatars/avatar-1.png"
import avatar2 from "@images/avatars/avatar-2.png"
import avatar3 from "@images/avatars/avatar-3.png"

const props = defineProps({
  userData: {
    type: Object,
    required: true,
  },
})

const standardPlan = {
  plan: 'Standard',
  price: 99,
  benefits: [
    '10 Users',
    'Up to 10GB storage',
    'Basic Support',
  ],
}

const isUserInfoEditDialogVisible = ref(false)
const isUpgradePlanDialogVisible = ref(false)

const resolveUserRoleVariant = role => {
  if (role === 'subscriber')
    return {
      color: 'warning',
      icon: 'tabler-user',
    }
  if (role === 'author')
    return {
      color: 'success',
      icon: 'tabler-circle-check',
    }
  if (role === 'maintainer')
    return {
      color: 'primary',
      icon: 'tabler-chart-pie-2',
    }
  if (role === 'editor')
    return {
      color: 'info',
      icon: 'tabler-pencil',
    }
  if (role === 'admin')
    return {
      color: 'secondary',
      icon: 'tabler-server-2',
    }

  return {
    color: 'primary',
    icon: 'tabler-user',
  }
}
</script>

<template>
  <VCard v-if="props.userData" class="mb-4">
    <VCardText class=" pt-12">
      <VRow v-if="userData">
        <VCol
          cols="5"
          class="d-flex flex-column"
        >
          <div class="d-flex justify-start align-start">
            <VAvatar
              rounded
              :size="100"
              :color="!props.userData.avatar ? 'primary' : undefined"
              :variant="!props.userData.avatar ? 'tonal' : undefined"
            >
              <VImg
                :src="props.userData.avatar"
              />
            </VAvatar>
            <div class="d-flex flex-column align-start justify-start ms-4">
              <h5 class="text-h5  ">
                {{ props.userData.fullName }}
              </h5>
              <h6 class="text-h6 ">
                +380508465592
              </h6>
              <div class="d-flex justify-center gap-x-4 mt-2">
                <VBtn
                  variant="elevated"
                  @click="isUserInfoEditDialogVisible = true"
                >
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
      <VCard title="Неглект тест">
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


  <VRow>
    <!-- SECTION User Details -->
    <VCol cols="12">
      <VCard v-if="props.userData">
        <VCardText class="text-center pt-12">
          <!-- 👉 Avatar -->
          <VAvatar
            rounded
            :size="100"
            :color="!props.userData.avatar ? 'primary' : undefined"
            :variant="!props.userData.avatar ? 'tonal' : undefined"
          >
            <VImg
              v-if="props.userData.avatar"
              :src="props.userData.avatar"
            />
          </VAvatar>

          <!-- 👉 User fullName -->
          <h5 class="text-h5 mt-4">
            {{ props.userData.fullName }}
          </h5>

          <!-- 👉 Role chip -->
          <VChip
            label
            :color="resolveUserRoleVariant(props.userData.role).color"
            size="small"
            class="text-capitalize mt-4"
          >
            {{ props.userData.role }}
          </VChip>
        </VCardText>

        <VCardText>
          <div class="d-flex justify-space-around gap-x-6 gap-y-2 flex-wrap mb-6">
            <!-- 👉 Done task -->
            <div class="d-flex align-center me-8">
              <VAvatar
                :size="40"
                rounded
                color="primary"
                variant="tonal"
                class="me-4"
              >
                <VIcon
                  icon="tabler-checkbox"
                  size="24"
                />
              </VAvatar>
              <div>
                <h5 class="text-h5">
                  {{ `${(props.userData.taskDone / 1000).toFixed(2)}k` }}
                </h5>

                <span class="text-sm">Task Done</span>
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
                  icon="tabler-briefcase"
                  size="24"
                />
              </VAvatar>
              <div>
                <h5 class="text-h5">
                  {{ kFormatter(props.userData.projectDone) }}
                </h5>
                <span class="text-sm">Project Done</span>
              </div>
            </div>
          </div>

          <!-- 👉 Details -->
          <h5 class="text-h5">
            Details ввв
          </h5>

          <VDivider class="my-4"/>

          <!-- 👉 User Details list -->
          <VList class="card-list mt-2">
            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Username:
                  <div class="d-inline-block text-body-1">
                    {{ props.userData.fullName }}
                  </div>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <span class="text-h6">
                  Billing Email:
                </span>
                <span class="text-body-1">
                  {{ props.userData.email }}
                </span>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Status:
                  <div class="d-inline-block text-body-1 text-capitalize">
                    {{ props.userData.status }}
                  </div>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Role:
                  <div class="d-inline-block text-capitalize text-body-1">
                    {{ props.userData.role }}
                  </div>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Tax ID:
                  <div class="d-inline-block text-body-1">
                    {{ props.userData.taxId }}
                  </div>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Contact:
                  <div class="d-inline-block text-body-1">
                    {{ props.userData.contact }}
                  </div>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Language:
                  <div class="d-inline-block text-body-1">
                    {{ props.userData.language }}
                  </div>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Country:
                  <div class="d-inline-block text-body-1">
                    {{ props.userData.country }}
                  </div>
                </h6>
              </VListItemTitle>
            </VListItem>
          </VList>
        </VCardText>

        <!-- 👉 Edit and Suspend button -->
        <VCardText class="d-flex justify-center gap-x-4">
          <VBtn
            variant="elevated"
            @click="isUserInfoEditDialogVisible = true"
          >
            Edit
          </VBtn>

          <VBtn
            variant="tonal"
            color="error"
          >
            Suspend
          </VBtn>
        </VCardText>
      </VCard>
    </VCol>
    <!-- !SECTION -->

    <!-- SECTION Current Plan -->
    <VCol cols="12">
      <VCard>
        <VCardText class="d-flex">
          <!-- 👉 Standard Chip -->
          <VChip
            label
            color="primary"
            size="small"
            class="font-weight-medium"
          >
            Popular
          </VChip>

          <VSpacer/>

          <!-- 👉 Current Price  -->
          <div class="d-flex align-center">
            <sup class="text-h5 text-primary mt-1">$</sup>
            <h1 class="text-h1 text-primary">
              99
            </h1>
            <sub class="mt-3"><h6 class="text-h6 font-weight-regular mb-n1">/ month</h6></sub>
          </div>
        </VCardText>

        <VCardText>
          <!-- 👉 Price Benefits -->
          <VList class="card-list">
            <VListItem
              v-for="benefit in standardPlan.benefits"
              :key="benefit"
            >
              <div class="d-flex align-center gap-x-2">
                <VIcon
                  size="10"
                  color="secondary"
                  icon="tabler-circle-filled"
                />
                <div class="text-medium-emphasis">
                  {{ benefit }}
                </div>
              </div>
            </VListItem>
          </VList>

          <!-- 👉 Days -->
          <div class="my-6">
            <div class="d-flex justify-space-between mb-1">
              <h6 class="text-h6">
                Days
              </h6>
              <h6 class="text-h6">
                26 of 30 Days
              </h6>
            </div>

            <!-- 👉 Progress -->
            <VProgressLinear
              rounded
              rounded-bar
              :model-value="65"
              color="primary"
            />

            <p class="mt-1">
              4 days remaining
            </p>
          </div>

          <!-- 👉 Upgrade Plan -->
          <div class="d-flex gap-4">
            <VBtn
              block
              @click="isUpgradePlanDialogVisible = true"
            >
              Upgrade Plan
            </VBtn>
          </div>
        </VCardText>
      </VCard>
    </VCol>
    <!-- !SECTION -->
  </VRow>

  <!-- 👉 Edit user info dialog -->
  <UserInfoEditDialog
    v-model:isDialogVisible="isUserInfoEditDialogVisible"
    :user-data="props.userData"
  />

  <!-- 👉 Upgrade plan dialog -->
  <UserUpgradePlanDialog v-model:isDialogVisible="isUpgradePlanDialogVisible"/>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 0.5rem;
}

.text-capitalize {
  text-transform: capitalize !important;
}
</style>
