<script setup>
import { hasAccessRole } from "@layouts/plugins/casl";

const props = defineProps({
  accessDetail: {
    type: [Object],
    required: false,
    default: () => null,
  },
});

const isShowPromoDialog = ref(false);

const promoActivated = () => {
  location.reload();
};

const openActivation = () => {
  isShowPromoDialog.value = true;
};
</script>

<template>
  <div>
    <h6 class="text-h6" v-if="accessDetail && hasAccessRole(['admin'])">
      <span v-if="accessDetail?.has_access">
        {{ $t("promo.endDate") }}:
        {{ accessDetail.end_date }} <br />
        {{ $t("promo.today") }}:
        {{ accessDetail.used_minutes }} хв<br />
        {{ $t("promo.LimitTimeToOneDay") }}:
        {{ accessDetail.minutes }} хв
      </span>
      <span v-else>
        {{ $t("promo.userAccessExpired") }}
      </span>
    </h6>
    <h6 class="text-h6" v-if="hasAccessRole(['patient'])">
      <VRow>
        <VCol cols="10">
          <span v-if="accessDetail?.has_access">
            {{ $t("promo.todayEnd") }}: {{ accessDetail.end_minutes }} <br />
          </span>
          <span v-else>
            {{ $t("promo.youHaveNotAccess") }}
          </span>
        </VCol>
        <VCol cols="2">
          <VBtn size="small" @click="openActivation">
            {{ $t("promo.ActivatePromoCode") }}
          </VBtn>
        </VCol>
      </VRow>
    </h6>
    <ActivatePromoCodeDialog
      v-model:isDialogVisible="isShowPromoDialog"
      @saved="promoActivated"
    />
  </div>
</template>
