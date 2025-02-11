<script setup>
import AddEditPatientDialog from "@/components/patient/AddEditPatientDialog.vue";

// 👉 Store
const searchQuery = ref("");

// Data table options
const itemsPerPage = ref(50);
const page = ref(1);
const sortBy = ref();
const orderBy = ref();
const selectedRows = ref([]);
const isAddPatientVisible = ref(false);
const patientId = ref(null);
const showActionButtons = ref(false);
const router = useRouter();

const updateOptions = (options) => {
  sortBy.value = options.sortBy[0]?.key;
  orderBy.value = options.sortBy[0]?.order;
};

// Headers
const headers = [
  {
    title: "Дійсний до",
    key: "expire_at",
    sortable: false,
  },
  {
    title: "email",
    key: "email",
    sortable: false,
  },
  {
    title: "ПІБ",
    key: "full_name",
    sortable: false,
  },
  {
    title: "Лікар",
    key: "doctor",
    sortable: false,
  },
  {
    title: "скарги",
    key: "answers",
    sortable: false,
  },
  {
    title: "комментарий",
    key: "comment",
    sortable: false,
  },
  {
    title: "",
    key: "actions",
    sortable: false,
  },
];

const { data: usersData, execute: fetchPatients } = await useApi(
  createUrl("/patient-all", {
    query: {
      q: searchQuery,
      itemsPerPage,
      page,
      sortBy,
      orderBy,
    },
  })
);

const users = computed(() => usersData.value.data);
const totalUsers = computed(() => usersData.value.recordsTotal);

const deleteUser = async (id) => {
  await $api(`/apps/users/${id}`, { method: "DELETE" });

  // Delete from selectedRows
  const index = selectedRows.value.findIndex((row) => row === id);
  if (index !== -1) selectedRows.value.splice(index, 1);

  // Refetch User
  fetchPatients();
};

const afterSave = (patientId) => {
  fetchPatients();
  // router.push({ name: "patients-card-id", params: { id: patientId } });
};
</script>

<template>
  <AddEditPatientDialog
    :id="patientId"
    v-model:is-dialog-visible="isAddPatientVisible"
    @saved="afterSave"
  />

  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>Користувачі</VCardTitle>
      </VCardItem>

      <VDivider />

      <VCardText class="d-flex flex-wrap gap-4">
        <div class="d-flex gap-3">
          <!-- <AppSelect
            :model-value="itemsPerPage"
            :items="[
              { value: 10, title: '10' },
              { value: 25, title: '25' },
              { value: 50, title: '50' },
              { value: 100, title: '100' },
              { value: -1, title: 'All' },
            ]"
            style="inline-size: 6.25rem"
            @update:model-value="itemsPerPage = parseInt($event, 10)"
          /> -->
        </div>
        <VRow>
          <VCol cols="10" class="d-flex flex-column">
            <div
              class="app-user-search-filter d-flex align-center flex-wrap gap-4"
            >
              <!-- 👉 Search  -->
              <div style="inline-size: 15.625rem; width: 100%">
                <AppTextField v-model="searchQuery" placeholder="Пошук ..." />
              </div>

              <!--          &lt;!&ndash; 👉 Export button &ndash;&gt;-->
              <!--          <VBtn-->
              <!--            variant="tonal"-->
              <!--            color="secondary"-->
              <!--            prepend-icon="tabler-upload"-->
              <!--          >-->
              <!--            Export-->
              <!--          </VBtn>-->

              <!-- 👉 Add user button -->
            </div>
          </VCol>
          <VCol cols="2" class="d-flex flex-column">
            <!-- <VBtn
              prepend-icon="tabler-plus"
              @click="isAddPatientVisible = true"
            >
              Додати користувача
            </VBtn> -->
          </VCol>
        </VRow>
      </VCardText>

      <VDivider />

      <!-- SECTION datatable -->
      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="users"
        item-value="id"
        :items-length="totalUsers"
        :headers="headers"
        class="text-no-wrap"
        :show-select="false"
        @update:options="updateOptions"
      >
        <!-- User -->
        <template #item.full_name="{ item }">
          <div class="d-flex align-center gap-x-4">
            <div class="d-flex flex-column">
              <h6 class="text-base">
                <RouterLink
                  :to="{ name: 'patients-card-id', params: { id: item.id } }"
                  class="font-weight-medium text-link"
                >
                  {{ item.full_name }}
                </RouterLink>
              </h6>
            </div>
          </div>
        </template>

        <!-- Plan -->
        <template #item.answers="{ item }">
          <div
            class="text-body-1 text-high-emphasis"
            v-html="item.answers"
          ></div>
        </template>

        <!-- Actions -->
        <template #item.actions="{ item }">
          <IconBtn @click="deleteUser(item.id)" v-if="showActionButtons">
            <VIcon icon="tabler-trash" />
          </IconBtn>

          <IconBtn v-if="showActionButtons">
            <VIcon icon="tabler-eye" />
          </IconBtn>
        </template>

        <!-- pagination -->
        <template #bottom>
          <TablePagination
            v-model:page="page"
            :items-per-page="itemsPerPage"
            :total-items="totalUsers"
          />
        </template>
      </VDataTableServer>
      <!-- SECTION -->
    </VCard>
  </section>
</template>
