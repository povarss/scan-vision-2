<script setup>
import { ref, onMounted } from "vue";
import Timer from "@/components/exam/Timer.vue";
import TestLine from "@/components/exam/TestLine.vue";

const props = defineProps({
  exam: {
    type: [Object],
    required: true,
  },
  isReadonly: {
    type: [Boolean],
    required: false,
    default: false,
  },
});

const emit = defineEmits(["itemSelected", "timeout"]);

let examData = ref([]);
const loadTest = async (id) => {
  const { data } = await useApi(`/exam/test-pattern/` + props.exam.id);
  if (data.value) {
    examData.value = data.value.pattern;
    selectedItems.value = data.value.selected.map(
      (pos) => pos[0] + "_" + pos[1]
    );
    selectedPoints.value = data.value.selected.map((pos) => {
      let item = examData.value[pos[0]][pos[1]];
      return {
        x: item.x + item.width / 2,
        y: item.y + item.height / 2,
      };
    });
  }
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
    if (selectedItems.value.includes(rowKey + "_" + colKey)) {
      if (imgItem.isCorrect == 1) {
        color = "#00ff72";
      } else {
        color = "#ff6f6f";
      }
    } else {
      if (imgItem.isCorrect == 1) {
        color = "#fbf525";
      }
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
    // console.log(examData.value,)
    let item = examData.value[rowKey][colKey];
    selectedPoints.value.push({
      x: item.x + item.width / 2,
      y: item.y + item.height / 2,
    });
  }
};
const onTimeout = () => {
  emit("timeout");
};

const selectedPoints = ref([]);
const myCanvas = ref();
</script>

<template>
  <VRow>
    <VCol cols="12" class="d-flex flex-column" v-if="!isReadonly">
      <Timer :initialTime="exam.time" @timeout="onTimeout" />
    </VCol>
    <VCol cols="12" class="d-flex flex-column">
      <div style="  display: flex;justify-content: center;">
        <div style="position: relative; width: 400px; height: 500px">
          <TestLine
            v-if="isReadonly"
            :points="selectedPoints"
            :width="500"
            :height="500"
          />
          <template v-for="(row, rowKey) in examData">
            <template v-for="(imgItem, colKey) in row">
              <img
                @click="setSelected(rowKey, colKey)"
                :src="'/images/vision/' + imgItem.type + '.svg'"
                :width="imgItem.width"
                :height="imgItem.height"
                style="
                  position: absolute;
                  padding: 2px;
                  box-sizing: content-box;
                  border: 2px solid transparent;
                "
                :class="{
                  'rounded-blue': !isReadonly && isSelected(rowKey, colKey),
                }"
                :style="{
                  top: imgItem.y + 'px',
                  left: imgItem.x + 'px',
                  backgroundColor: getSectionColor(imgItem, rowKey, colKey),
                }"
              />
            </template>
          </template>
        </div>
      </div>
    </VCol>
  </VRow>
</template>

<style>
.v-slider-thumb__label {
  min-width: 63px !important;
}

.rounded-blue {
  border-radius: 50%;
  border-color: #857e7e !important;
}
</style>
