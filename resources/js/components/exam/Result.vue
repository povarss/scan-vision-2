<script setup lang="ts">
import { ref, onMounted } from "vue";
import testImg from "@images/test_img.png";

onMounted(() => {});

</script>

<template>
  <VRow>
    <VCol cols="12" md="6">
      <VCardText>
        <VList class="card-list">
          <VListItem>
            <template #prepend>
              <VAvatar
                color="info"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-clock-hour-1" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Час скринінгу
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">13хв з 20хв</div>
              </div>
            </template>
          </VListItem>

          <VListItem>
            <template #prepend>
              <VAvatar
                color="success"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-circle-check" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Кількість правильних стимулів:
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">10</div>
              </div>
            </template>
          </VListItem>
          <VListItem>
            <template #prepend>
              <VAvatar
                color="error"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-exclamation-circle" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Кількість неправильних стимулів:
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">6</div>
              </div>
            </template>
          </VListItem>

          <VListItem>
            <template #prepend>
              <VAvatar
                color="secondary"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-arrow-big-right" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Кількість файльш цілей з правого боку
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">6</div>
              </div>
            </template>
          </VListItem>

          <VListItem>
            <template #prepend>
              <VAvatar
                color="secondary"
                variant="tonal"
                size="34"
                rounded
                class="me-1"
              >
                <VIcon icon="tabler-arrow-big-left" size="22" />
              </VAvatar>
            </template>
            <VListItemTitle class="font-weight-medium me-4">
              Кількість файльш цілей з лівого боку
            </VListItemTitle>
            <template #append>
              <div class="d-flex gap-x-4">
                <div class="text-body-1">6</div>
              </div>
            </template>
          </VListItem>
        </VList>
      </VCardText>
    </VCol>

    <VCol cols="12" md="6">
      <div class="symbol-container border rounded mt-5 pa-5">
        <img
          class="misc-footer-img d-none d-md-block"
          :src="testImg"
          alt="misc-footer-img"
          width="100%"
        />
      </div>
    </VCol>
  </VRow>
</template>

<style>
</style>
