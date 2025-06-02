<script setup>
import Timer from "@/components/exam/Timer.vue";
import dots from "@/components/exam/Dots.vue";
import { correctSvgList } from "./../testUtil";
import levels from "./../levels";

const props = defineProps({
  exam: {
    type: [Object],
    required: true,
  },
  examParams: {
    type: [Object],
    required: true,
  },
  references: {
    type: [Object],
  },
  selectedItems: {
    type: [Array],
    required: false,
  },
  // selectedPoints: {
  //   type: [Array],
  //   required: false,
  // },
});

const emit = defineEmits([
  "itemSelected",
  "timeout",
  "doubleClickItem",
  "loadTest",
]);

const showCorrect = ref(true);
const container = ref();
const previewCorrectTime = ref(5);

let examData = computed(() => {
  return props.examParams.pattern;
});

const setSelected = (rowKey, colKey) => {
  console.log(props.selectedItems, "props.selectedItems");
  if (!props.selectedItems.includes(rowKey + "_" + colKey)) {
    let items = [...props.selectedItems, rowKey + "_" + colKey];
    // props.selectedItems.push(rowKey + "_" + colKey);
    emit("itemSelected", items);
    // console.log(examData.value,)
    // let item = examData.value[rowKey][colKey];
    // props.selectedPoints.value.push({
    //   x: item.x + item.width / 2,
    //   y: item.y + item.height / 2,
    // });
  } else {
    emit("doubleClickItem", { rowKey, colKey });
  }
};

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

const getSectionColor = (imgItem, rowKey, colKey) => {
  let color = null;
  return color;
};
const isSelected = (rowKey, colKey) => {
  console.log(props.selectedItems, "props.selectedItems");
  return props.selectedItems.includes(rowKey + "_" + colKey);
};

const svgList = computed(() => {
  return correctSvgList(props.exam.mode, props.references);
});

const stimulSize = computed(() => {
  const svgSm = levels.find((v) => v.value == props.exam.level).size;
  return (svgSm * props.exam.svg_size) / 100;
});

const loadTest = () => {
  emit("loadTest");
  showCorrect.value = false;
};

const onTimeout = () => {
  emit("timeout");
};

onMounted(() => {
  showCorrect.value = true;
  setTimeout(loadTest, previewCorrectTime.value * 1000);
});
</script>
<template>
  <CorrectSvg
    v-if="showCorrect"
    :svgs="svgList"
    :width="stimulSize"
    :timer="previewCorrectTime"
  ></CorrectSvg>

  <template v-if="!showCorrect">
    <VCol cols="12" class="d-flex flex-column">
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
          height: 'calc(100vh - 120px)',
        }"
      >
        <div>
          <template v-if="examParams.type == 3">
            <dots :examParams="examParams"></dots>
          </template>
          <template v-for="(row, rowKey) in examData">
            <template v-for="(imgItem, colKey) in row">
              <img
                @click="setSelected(rowKey, colKey)"
                :src="
                  '/images/' + references.folder + '/' + imgItem.type + '.svg'
                "
                :width="imgItem.width"
                :height="imgItem.height"
                style="
                  position: absolute;
                  padding: 2px;
                  box-sizing: content-box;
                  border: 2px solid transparent;
                  z-index: 90;
                "
                :class="{
                  'rounded-blue': isSelected(rowKey, colKey),
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
  </template>
</template>
