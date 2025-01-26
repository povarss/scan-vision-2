<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import Timer from "@/components/exam/Timer.vue";
import TestLine from "@/components/exam/TestLine.vue";
import dots from "@/components/exam/Dots.vue";
import CorrectSvg from "@/components/exam/CorrectSvg.vue";
import levels from "./levels.js";
import { nextTick } from "vue";
import { correctSvgList } from "./testUtil";

const props = defineProps({
  exam: {
    type: [Object],
    required: true,
  },
  references: {
    type: [Object],
  },
});

const emit = defineEmits(["itemSelected", "timeout"]);

let examData = ref([]);
const loadTest = async () => {
  showCorrect.value = false;
  await nextTick();
  startTimer();
  const { data } = await useApi(
    `/exam/test-pattern/` +
      props.exam.id +
      "?width=" +
      container.value.clientWidth +
      "&height=" +
      container.value.clientHeight +
      "&isStart=1"
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
  return color;
};
const isSelected = (rowKey, colKey) => {
  return selectedItems.value.includes(rowKey + "_" + colKey);
};

const selectedItems = ref([]);
const examParams = ref({});
const container = ref();
const showCorrect = ref(false);
const previewCorrectTime = ref(5);
const timer = ref();
onMounted(() => {
  showCorrect.value = true;
  openFullscreen();
  setTimeout(loadTest, previewCorrectTime.value * 1000);
});
const setSelected = (rowKey, colKey) => {
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

const openFullscreen = () => {
  // const elem = testBlock.value;
  var elem = document.getElementById("myvideo");
  console.log(elem, "elem");
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.webkitRequestFullscreen) {
    /* Safari */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) {
    /* IE11 */
    elem.msRequestFullscreen();
  }
};

const svgList = computed(() => {
  return correctSvgList(props.exam.mode, props.references);
});

const stimulSize = computed(() => {
  const svgSm = levels.find((v) => v.value == props.exam.level).size;
  return (svgSm * props.exam.svg_size) / 100;
});

const setExamTime = async () => {
  try {
    let resp = await $api("/exam/set-time/" + props.exam.id, {
      method: "POST",
      onResponseError({ response }) {
        console.error(response._data.errors);
      },
    });
    if(resp.left_seconds <=0 ){
      emit("timeout");
    }
  } catch (err) {
    console.error(err);
  }
};

const startTimer = () => {
  timer.value = setInterval(() => {
    setExamTime();
  }, 1000); // 30 seconds in milliseconds
};

onBeforeUnmount(() => {
  if (timer.value) {
    clearInterval(timer.value);
  }
});
</script>

<template>
  <VRow>
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
.custom-cursor img {
  user-select: none;
}
.custom-cursor {
  cursor: url("@images/cursor.svg") 30 30, move;
}
</style>
