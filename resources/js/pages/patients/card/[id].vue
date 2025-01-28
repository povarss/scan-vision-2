<script setup>
import Profile from "@/components/patient/Profile.vue";
const userData = useCookie("userData");
const router = useRouter();

const route = useRoute("patients-card-id");
onMounted(() => {
  if (userData.value.role == "patient") {
    router.push({ name: "profile" });
  }
});
</script>

<template>
  <Profile :patient-id="route.params.id" />
</template>

<style scoped>
.card-list .v-list-item:not(:last-child) {
  padding-bottom: 10px !important;
}
.scrollable {
  height: 260px;
  overflow-y: auto;
  //border: 1px solid #ccc;
  padding: 6px 10px 0px 0;
  box-sizing: border-box;
}
</style>
