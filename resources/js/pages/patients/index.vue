<script setup>
import AddEditPatientDialog from "@/components/patient/AddEditPatientDialog.vue";

// 👉 Store
const searchQuery = ref("");

// Data table options
const itemsPerPage = ref(10);
const page = ref(1);
const sortBy = ref();
const orderBy = ref();
const selectedRows = ref([]);
const isAddPatientVisible = ref(false);
const patientId = ref(null);
const showActionButtons = ref(false);

const updateOptions = (options) => {
  sortBy.value = options.sortBy[0]?.key;
  orderBy.value = options.sortBy[0]?.order;
};

// Headers
const headers = [
  {
    title: "Користувач",
    key: "user",
  },
  {
    title: "Телефон",
    key: "role",
  },
  {
    title: "Перший тест",
    key: "first_test",
  },
  {
    title: "Останній тест",
    key: "last_test",
  },
  {
    title: "",
    key: "actions",
    sortable: false,
  },
];

const { data: usersData, execute: fetchPatients } = await useApi(
  createUrl("/patient", {
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

const addNewUser = async (userData) => {
  await $api("/apps/users", {
    method: "POST",
    body: userData,
  });

  // Refetch User
  fetchPatients();
};

const deleteUser = async (id) => {
  await $api(`/apps/users/${id}`, { method: "DELETE" });

  // Delete from selectedRows
  const index = selectedRows.value.findIndex((row) => row === id);
  if (index !== -1) selectedRows.value.splice(index, 1);

  // Refetch User
  fetchPatients();
};
</script>

<template>
  <AddEditPatientDialog
    :id="patientId"
    v-model:is-dialog-visible="isAddPatientVisible"
    @saved="fetchPatients"
  />

  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>Користувачі</VCardTitle>
      </VCardItem>

      <VDivider />

      <VCardText class="d-flex flex-wrap gap-4">
        <div class="me-3 d-flex gap-3">
          <AppSelect
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
          />
        </div>
        <VSpacer />

        <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
          <!-- 👉 Search  -->
          <div style="inline-size: 15.625rem">
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
          <VBtn prepend-icon="tabler-plus" @click="isAddPatientVisible = true">
            Додати користувача
          </VBtn>
        </div>
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
        <template #item.user="{ item }">
          <div class="d-flex align-center gap-x-4">
            <div class="d-flex flex-column">
              <h6 class="text-base">
                <RouterLink
                  :to="{ name: 'patients-card', params: { id: item.id } }"
                  class="font-weight-medium text-link"
                >
                  {{ item.full_name }}
                </RouterLink>
              </h6>
            </div>
          </div>
        </template>

        <!-- 👉 Role -->
        <template #item.role="{ item }">
          <div class="d-flex align-center gap-x-2">
            <div class="text-capitalize text-high-emphasis text-body-1">
              {{ item.phone }}
            </div>
          </div>
        </template>

        <!-- Plan -->
        <template #item.first_test="{ item }">
          <div class="text-body-1 text-high-emphasis text-capitalize">
            {{ item.first_test }}
          </div>
        </template>

        <!-- Status -->
        <template #item.last_test="{ item }"> {{ item.last_test }}</template>

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
