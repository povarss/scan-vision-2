<script setup>
import Timer from "@/components/exam/Timer.vue";
import dots from "@/components/exam/Dots.vue";
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
});

const emit = defineEmits([
  "itemSelected",
  "timeout",
  "doubleClickItem",
  "loadTest",
  "finish",
  "restart",
]);

const examData = computed(() => props.examParams.pattern);
const currentNumber = ref(1);
const wrongSelections = ref(new Set());
const properItems = ref([]);
const showRefreshTimer = ref(false);
const refreshTime = ref(0.1);

const setSelected = (selectedNumber, rowKey, colKey) => {
  const items = [...props.selectedItems, `${rowKey}_${colKey}`];
  emit("itemSelected", items);

  const position = `${rowKey}_${colKey}`;
  if (parseInt(selectedNumber) === currentNumber.value) {
    properItems.value.push(`${rowKey}_${colKey}`);
    currentNumber.value++;
  } else {
    if (
      !properItems.value.some((item) => item === position) &&
      props.selectedItems.some((item) => item === position)
    ) {
      emit("doubleClickItem", { rowKey, colKey });
    }
    wrongSelections.value.add(position);
    setTimeout(() => {
      wrongSelections.value.delete(position);
    }, 1000);
  }

  if (
    properItems.value.length ===
    props.examParams.pattern.length * props.examParams.pattern[0].length
  ) {
    if (props.exam.type == 2) {
      showRefreshTimer.value = true;
    } else {
      emit("finish");
    }
  }
};

const isSelected = (rowKey, colKey) =>
  properItems.value.includes(`${rowKey}_${colKey}`);
const loadTest = () => emit("loadTest");
const onTimeout = () => emit("timeout");

const getCellColor = (rowKey, colKey) => {
  if (isSelected(rowKey, colKey)) return "rgba(0, 255, 0, 0.3)";
  if (wrongSelections.value.has(`${rowKey}_${colKey}`))
    return "rgba(255, 0, 0, 0.3)";
  return "#e0e0e0"; // Lighter grey for better contrast
};

const onRefreshTimeout = () => {
  showRefreshTimer.value = false;
  refreshTime.value = 0.1;
  properItems.value = [];
  wrongSelections.value = new Set();
  currentNumber.value = 1;
  emit("restart");
};

onMounted(() => {
  setTimeout(() => {
    loadTest();
  }, 1000);
});
</script>

<template>
  <VCol cols="12" class="d-flex flex-column">
    <template v-if="!showRefreshTimer">
      <Timer :initialTime="exam.time" @timeout="onTimeout" />
    </template>
    <div class="d-flex flex-column" style="height: 20px">
      <template v-if="showRefreshTimer">
        <div class="d-flex flex-row align-center justify-center">
          <div class="text-center me-2">Refresh Time:</div>
          <div>
            <Timer :initialTime="refreshTime" @timeout="onRefreshTimeout" />
          </div>
        </div>
      </template>
    </div>
  </VCol>
  <VCol cols="12" class="d-flex flex-column">
    <div
      ref="container"
      class="number-container"
      :style="{ height: 'calc(100vh - 120px)' }"
    >
      <div class="number-grid">
        <dots v-if="examParams.type == 3" :examParams="examParams" />
        <div v-for="(row, rowKey) in examData" :key="rowKey" class="number-row">
          <div
            v-for="(cellNumber, colKey) in row"
            :key="colKey"
            class="number-block"
            :class="{ 'is-selected': isSelected(rowKey, colKey) }"
            :style="{ backgroundColor: getCellColor(rowKey, colKey) }"
            @click="setSelected(cellNumber, rowKey, colKey)"
          >
            <span class="number-text">{{ cellNumber }}</span>
          </div>
        </div>
      </div>
    </div>
  </VCol>
</template>

<style scoped>
.number-container {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
  position: relative;
}

.number-grid {
  display: flex;
  flex-direction: column;
  gap: 12px;
  padding: 16px;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.9);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.number-row {
  display: flex;
  gap: 12px;
}

.number-block {
  width: 100px;
  height: 100px;
  display: flex;
  justify-content: center;
  align-items: center;
  border: 2px solid transparent;
  border-radius: 8px;
  transition: all 0.3s ease;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.number-block:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.number-block.is-selected {
  border-color: #2196f3;
}

.number-text {
  font-size: 32px;
  font-weight: 600;
  color: #424242;
  user-select: none;
}
</style>
