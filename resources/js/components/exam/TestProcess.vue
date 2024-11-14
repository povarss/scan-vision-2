<script setup>
import { ref, onMounted } from "vue";
import Timer from "@/components/exam/Timer.vue";

const props = defineProps({
  exam: {
    type: [Object],
    required: true,
  },
  isReadonly: {
    type: [Object],
    required: false,
    default: false,
  },
});

const emit = defineEmits(["itemSelected"]);

let examData = ref([]);
const loadTest = async (id) => {
  const { data } = await useApi(`/exam/test-pattern/` + props.exam.id);
  if (data.value) {
    examData.value = data.value.pattern;
    selectedItems.value = data.value.selected.map(
      (pos) => pos[0] + "_" + pos[1]
    );
  }
  console.log(data.value, examData.value, "data.value");
};

const getSectionColor = (imgItem, rowKey, colKey) => {
  let color = null;
  // switch (imgItem.section) {
  //   case 1:color = '#c7c7f5';break;
  //   case 2:color = '#eac7f5';break;
  //   case 3:color = '#cf88f3';break;
  //   case 4:color = '#8476ff';break;
  //   case 5:color = '#c7f5ef';break;
  //   case 6:color = '#dbf5c7';break;
  //   case 7:color = '#e5da4e';break;
  //   case 8:color = '#fb5151';break;
  //   default:
  //     break;
  // }
  if (props.isReadonly) {
    if (imgItem.isCorrect == 1) {
      color = "#00ff72";
    }
    if (selectedItems.value.includes(rowKey + "_" + colKey)) {
      color = "#dde6ff";
    }
  }
  return color;
};
const isSelected = (rowKey, colKey) => {
  return selectedItems.value.includes(rowKey + "_" + colKey);
};

const selectedItems = ref([]);
onMounted(() => {
  loadTest();
});
const setSelected = (rowKey, colKey) => {
  if (props.isReadonly) {
    return;
  }
  if (!selectedItems.value.includes(rowKey + "_" + colKey)) {
    selectedItems.value.push(rowKey + "_" + colKey);
    emit("itemSelected", selectedItems.value);
  }
};
</script>

<template>
  <VCard>
    <VCardText class="d-flex flex-wrap gap-4">
      <VRow>
        <VCol cols="12" class="d-flex flex-column" v-if="!isReadonly">
          <Timer :initialTime="exam.time" />
        </VCol>
        <VCol cols="12" class="d-flex flex-column">
          <div style="position: relative; width: 400px; height: 500px">
            <template v-for="(row, rowKey) in examData">
              <template v-for="(imgItem, colKey) in row">
                <img
                  @click="setSelected(rowKey, colKey)"
                  :src="'/images/vision/' + imgItem.type + '.svg'"
                  :width="imgItem.width"
                  :height="imgItem.height"
                  style="position: absolute"
                  :class="{ 'rounded-blue': isSelected(rowKey, colKey) }"
                  :style="{
                    top: imgItem.y + 'px',
                    left: imgItem.x + 'px',
                    backgroundColor: getSectionColor(imgItem, rowKey, colKey),
                  }"
                />
              </template>
            </template>
          </div>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>

<style>
.v-slider-thumb__label {
  min-width: 63px !important;
}

.rounded-blue {
  border-radius: 50%;
  border: 2px solid #95b1ff;
}
</style>
