<script setup>
const props = defineProps({
  btnLabel: {
    type: [String],
    required: true,
  },
  url: {
    type: String,
    required: true,
  },
});

onMounted(() => {});

const downloadPDF = async () => {
  try {
    console.log(props.url, "props.url");
    const response = await $api(props.url, {
      method: "GET",
      // headers: { "Content-Type": "multipart/form-data" },
      responseType: "blob",
      onResponseError({ response }) {
        console.error(response._data.errors);
      },
    });

    console.log(response,'data')
    const url = window.URL.createObjectURL(
      new Blob([response], { type: "application/pdf" })
    );
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", "test.pdf"); // Set desired filename
    document.body.appendChild(link);
    link.click();
    link.parentNode.removeChild(link);
  } catch (error) {
    console.error("Error downloading PDF:", error);
  }
};
</script>
<template>
  <VBtn @click="downloadPDF" color="primary"> {{ props.btnLabel }} </VBtn>
</template>
