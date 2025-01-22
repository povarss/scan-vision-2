<script setup>
import Profile from "@/components/patient/Profile.vue";
const userData = useCookie("userData");
</script>

<template>
  <div v-if="userData.id">
    <Profile :patient-id="userData.id" :show-patient-detail="false" />
  </div>
</template>

<style scoped>
.card-list .v-list-item:not(:last-child) {
  padding-bottom: 10px !important;
}
.scrollable {
  height: 260px;
  overflow-y: auto;
  padding: 6px 10px 0px 0;
  box-sizing: border-box;
}
</style>
