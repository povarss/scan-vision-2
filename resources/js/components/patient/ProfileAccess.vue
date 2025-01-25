<script setup>
import { hasAccessRole } from "@layouts/plugins/casl";

const props = defineProps({
  accessDetail: {
    type: [Object],
    required: false,
    default: () => null,
  },
});
</script>

<template>
  <div>
    <h6 class="text-h6" v-if="accessDetail && hasAccessRole(['admin'])">
      <span v-if="$access">
        {{ $t("promo.endDate") }}
        {{ accessDetail.end_date }} <br />
        {{ $t("promo.today") }}
        {{ accessDetail.used_minutes }}<br />
        {{ $t("promo.LimitTimeToOneDay") }}
        {{ accessDetail.minutes }}
      </span>
      <span v-else>
        {{ $t("promo.userAccessExpired") }}
      </span>
    </h6>
    <h6 class="text-h6" v-if="hasAccessRole(['patient'])">
      <span v-if="accessDetail">
        {{ $t("promo.todayEnd") }}: {{ accessDetail.end_minutes }} <br />
      </span>
      <span v-else>
        {{ $t("promo.youHaveNotAccess") }}
      </span>
    </h6>
  </div>
</template>
