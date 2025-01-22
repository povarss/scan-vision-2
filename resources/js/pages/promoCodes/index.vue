<script setup>
import AddPromoCodeDialog from "@/components/promoCodes/AddPromoCodeDialog.vue";
const { t } = useI18n();

// 👉 Store
const searchQuery = ref("");

// Data table options
const itemsPerPage = ref(10);
const page = ref(1);
const sortBy = ref();
const orderBy = ref();
const selectedRows = ref([]);
const isAddModalVisible = ref(false);
// const patientId = ref(null);
const showActionButtons = ref(false);

const updateOptions = (options) => {
  sortBy.value = options.sortBy[0]?.key;
  orderBy.value = options.sortBy[0]?.order;
};

// Headers
const headers = [
  {
    title: t("promo.status"),
    key: "status",
  },
  {
    title: t("promo.code"),
    key: "code",
  },
  {
    title: t("promo.created_at"),
    key: "created_at",
  },
  {
    title: t("promo.activated_at"),
    key: "activated_at",
  },
  {
    title: t("promo.user_email"),
    key: "user_email",
  },
  {
    title: t("promo.days"),
    key: "days",
  },
  {
    title: t("promo.minutes"),
    key: "minutes",
  },
  // {
  //   title: "",
  //   key: "actions",
  //   sortable: false,
  // },
];

const { data: itemsData, execute: fetchItems } = await useApi(
  createUrl("/promo-code", {
    query: {
      q: searchQuery,
      itemsPerPage,
      page,
      sortBy,
      orderBy,
    },
  })
);

const items = computed(() => itemsData.value.data);
const total = computed(() => itemsData.value.recordsTotal);

const deleteUser = async (id) => {
  await $api(`/api/users/${id}`, { method: "DELETE" });

  // Delete from selectedRows
  const index = selectedRows.value.findIndex((row) => row === id);
  if (index !== -1) selectedRows.value.splice(index, 1);

  // Refetch User
  fetchItems();
};

const afterSave = () => {
  fetchItems();
};

const openAdd = () => {
  isAddModalVisible.value = true;
};
</script>

<template>
  <AddPromoCodeDialog
    v-model:is-dialog-visible="isAddModalVisible"
    @saved="afterSave"
  />

  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>{{ $t("promo.promoCodes") }}</VCardTitle>
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

          <!-- 👉 Add user button -->
          <VBtn prepend-icon="tabler-plus" @click="openAdd()">
            {{ $t("promo.addTitle") }}
          </VBtn>
        </div>
      </VCardText>

      <VDivider />

      <!-- SECTION datatable -->
      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="items"
        item-value="id"
        :items-length="total"
        :headers="headers"
        class="text-no-wrap"
        :show-select="false"
        @update:options="updateOptions"
      >
        <!-- Actions -->
        <!-- <template #item.actions="{ item }">
          <IconBtn @click="deleteUser(item.id)" v-if="showActionButtons">
            <VIcon icon="tabler-trash" />
          </IconBtn>

          <IconBtn>
            <VIcon icon="tabler-pencil" @click="openEditUser(item.id)" />
          </IconBtn>
        </template> -->

        <!-- pagination -->
        <template #bottom>
          <TablePagination
            v-model:page="page"
            :items-per-page="itemsPerPage"
            :total-items="total"
          />
        </template>
      </VDataTableServer>
      <!-- SECTION -->
    </VCard>
  </section>
</template>
