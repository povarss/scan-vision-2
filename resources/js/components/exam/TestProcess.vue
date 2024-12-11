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
  const { data } = await useApi(
    `/exam/test-pattern/` +
      props.exam.id +
      "?width=" +
      container.value.clientWidth +
      "&height=" +
      container.value.clientHeight
  );
  if (data.value) {
    examData.value = data.value.pattern;
    examParams.value = data.value;
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
const examParams = ref({});
const container = ref();
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
const canvasHeight = ref(500);

const scale = computed(() => {
  if (!examParams.value.width) {
    return 0;
  }
  return (
    (container.value ? container.value.clientWidth : 0) / examParams.value.width
  );
});

const wrapperHeight = computed(() => {
  if (!examParams.value.width) {
    return 500;
  }
  return examParams.value.height * scale.value;
});
</script>

<template>
  <VRow>
    <VCol cols="12" class="d-flex flex-column" v-if="!isReadonly">
      <Timer :initialTime="exam.time" @timeout="onTimeout" />
    </VCol>
    <VCol cols="12" class="d-flex flex-column">
      <div
        ref="container"
        class="custom-cursor"
        style="
          display: flex;
          justify-content: center;
          align-items: center;
          position: relative;
          padding: 20px;
        "
        :style="{
          height: !isReadonly ? 'calc(100vh - 120px)' : wrapperHeight + 'px',
        }"
      >
        <div
          :style="{
            position: isReadonly ? 'absolute' : '',
            top: isReadonly ? '0' : '',
            left: isReadonly ? '0' : '',
            scale: isReadonly ? scale : '',
          }"
        >
          <TestLine
            v-if="isReadonly && examParams"
            :points="selectedPoints"
            :width="examParams.width"
            :height="examParams.height"
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
.custom-cursor img{
  user-select: none;

}
.custom-cursor{

  //cursor: all-scroll;
  cursor: url("@images/cursor.svg") 42 42, move;
}
</style>
