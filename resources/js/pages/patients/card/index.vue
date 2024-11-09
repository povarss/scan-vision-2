<script setup>
// import userAvatar from '@images/user.png'
import userAvatar from "@images/avatars/avatar-1.png"

// const isUserInfoEditDialogVisible = ref(false)
// import miscMaskDark from '@images/Screenshot_1.png'



const isAddPatientVisible = ref(false)
const inlineRadio = ref('radio-1')

const region = [
  'Автономна Республіка Крим',
  'Вінницька',
  'Волинська',
  'Дніпропетровська',
  'Донецька',
  'Житомирська',
  'Закарпатська',
  'Запорізька',
  'Івано-Франківська',
  'Київська',
  'Кіровоградська',
  'Луганська',
  'Львівська',
  'Миколаївська',
  'Одеська',
  'Полтавська',
  'Рівненська',
  'Сумська',
  'Тернопільська',
  'Харківська',
  'Херсонська',
  'Хмельницька',
  'Черкаська',
  'Чернівецька',
  'Чернігівська',
]

const selectedItem = ref(['Новий', ])
const items = ['Новий', 'Пріоритетний']


const isArchiveDialogVisible = ref(false)
</script>

<template>
  <VDialog
    v-model="isArchiveDialogVisible"
    persistent
    class="v-dialog-sm"
  >


    <!-- Dialog close btn -->
    <DialogCloseBtn @click="isArchiveDialogVisible = !isArchiveDialogVisible" />

    <!-- Dialog Content -->
    <VCard title="Ви впевнені що хочете архівувати користувача?">
      <VCardText>
         Після архівації користувача не можна повернути.
      </VCardText>

      <VCardText class="d-flex justify-end gap-3 flex-wrap">
        <VBtn
          color="secondary"
          variant="tonal"
          @click="isArchiveDialogVisible = false"
        >
          Відмінити
        </VBtn>
        <VBtn @click="isArchiveDialogVisible = false">
          Так, архівувати
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>

  <VDialog
    v-model="isAddPatientVisible"
    max-width="600"
  >


    <!-- Dialog close btn -->
    <DialogCloseBtn @click="isAddPatientVisible = !isAddPatientVisible"/>

    <!-- Dialog Content -->
    <VCard title="Редагувати користувача">
      <VCardText>
        <VRow>
          <VCol
            cols="12"
            sm="7"
          >
            <AppTextField
              v-model="firstName"
              label="ПІБ"
            />
          </VCol>
          <VCol
            cols="12"
            sm="5"

          >
            <AppTextField
              v-model="middleName"
              label="Телефон"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"

          >
            <AppTextField
              v-model="age"
              label="Вік"
              type="number"
              placeholder="18"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
          >
            <label class="v-label mb-1 text-body-2 text-wrap" for="app-text-field-Вік-1fk3v" style="line-height: 15px;"
            > </label>
            <VRadioGroup
              v-model="inlineRadio"
              inline
            >
              <VRadio
                label="Чоловік"
                value="radio-1"
              />
              <VRadio
                label="Жінка"
                value="radio-2"
              />
            </VRadioGroup>
          </VCol>
          <VCol cols="12">

            <AppCombobox
              v-model="selectedItem"
              :items="items"
              placeholder=""
              label="Теги"
              multiple
              chips
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
          >
            <AppTextField
              label="Сфера"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
          >
            <AppSelect
              :items="region"
              label="Область"
              placeholder=""
            />
          </VCol>
          <VCol cols="12">
            <AppTextField
              label="Клінічний діагноз"
            />
          </VCol>



        </VRow>
      </VCardText>

      <VCardText class="d-flex justify-end flex-wrap gap-3">
        <VBtn
          variant="tonal"
          color="secondary"
          @click="isAddPatientVisible = false"
        >
          Відмінити
        </VBtn>
        <VBtn @click="isAddPatientVisible = false">
          Зберегти
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>



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
              <VImg :src="userAvatar"/>


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
                  @click="isAddPatientVisible = !isAddPatientVisible"
                >
                  Редагувати
                </VBtn>

                <VBtn
                  variant="tonal"
                  color="error"
                  @click="isArchiveDialogVisible = !isArchiveDialogVisible"
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
          cols="3"
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
          <VList class="card-list mt-2">
            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6 text-left d-flex flex-column">

                  Клінічний діагноз:
                  <div class="d-inline-block text-body-1 text-capitalize">
                    Діагноз
                  </div>
                </h6>
              </VListItemTitle>
            </VListItem>


          </VList>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>

  <VRow>
    <VCol cols="6">
      <!-- 👉 User Activity timeline -->
      <VCard>
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
                <span class="app-timeline-meta">12.11.2024</span>
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
                <span class="app-timeline-meta">10.11.2024</span>
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
                  Результат:   <VChip color="error">20%</VChip>
                </span>
                <span class="app-timeline-meta">8.11.2024</span>
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
                <span class="app-timeline-meta">7.11.2024</span>
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


