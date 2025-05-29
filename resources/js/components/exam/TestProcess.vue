<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { nextTick } from "vue";
import SvgPattern from "@/components/exam/Patterns/SvgPattern.vue";
import NumberFinderPattern from "@/components/exam/Patterns/NumberFinderPattern.vue";
import { isNumberFinderExam, isSvgExam } from "./testUtil";

const props = defineProps({
  exam: {
    type: [Object],
    required: true,
  },
  references: {
    type: [Object],
  },
});

const emit = defineEmits(["itemSelected", "timeout", "doubleClickItem"]);
const selectedItems = ref([]);
const examParams = ref({});
const container = ref();
const timer = ref();

const loadTest = async () => {
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
    // examData.value = data.value.pattern;
    examParams.value = data.value;
    selectedItems.value = data.value.selected.map(
      (pos) => pos[0] + "_" + pos[1]
    );
    selectedPoints.value = data.value.selected.map((pos) => {
      let item = examParams.value.pattern[pos[0]][pos[1]];
      return {
        x: item.x + item.width / 2,
        y: item.y + item.height / 2,
      };
    });
  }
};

onMounted(() => {
  openFullscreen();
});

const onTimeout = () => {
  emit("timeout");
};

const selectedPoints = ref([]);

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

const handleItemSelected = (items) => {
  selectedItems.value = items;
  emit("itemSelected", items);
};

const handleDoubleClickItem = (items) => {
  emit("doubleClickItem", items);
};

const setExamTime = async () => {
  try {
    let resp = await $api("/exam/set-time/" + props.exam.id, {
      method: "POST",
      onResponseError({ response }) {
        console.error(response._data.errors);
      },
    });
    if (resp.left_seconds <= 0) {
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
    <div
      ref="container"
      class="custom-cursor"
      style="
        /* display: flex; */
        justify-content: center;
        align-items: center;
        position: relative;
        width: 100%;
        padding: 20px;
      "
      :style="{
        height: 'calc(100vh - 120px)',
      }"
    >
      <SvgPattern
        :exam="exam"
        :examParams="examParams"
        :references="references"
        :selectedItems="selectedItems"
        @itemSelected="handleItemSelected"
        @doubleClickItem="handleDoubleClickItem"
        @loadTest="loadTest"
        v-if="isSvgExam(exam)"
      />
      <NumberFinderPattern
        :exam="exam"
        :examParams="examParams"
        :references="references"
        :selectedItems="selectedItems"
        @itemSelected="handleItemSelected"
        @doubleClickItem="handleDoubleClickItem"
        @loadTest="loadTest"
        @finish="onTimeout"
        v-if="isNumberFinderExam(exam)"
      />
    </div>
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
