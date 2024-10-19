<script setup>
    import { ref } from "vue"
    import VuePdfApp from "vue3-pdf-app";
    import "vue3-pdf-app/dist/icons/main.css";

    const config = ref( {
        toolbar: {
          toolbarViewerRight: {
            download: false,
            openFile:false
          }
        }
      })

    const props = defineProps({
        pdfFile: {
            type: Object,
            required: true
        },
    })
</script>
<template>
        <VuePdfApp class="pdf-index" v-if="pdfFile != undefined"  :page-scale="page-fit" :config="config" style="height: 100vh;"  :pdf="pdfFile" ></VuePdfApp>
</template>
<style lang="scss" scoped>
    .pdf-app.dark {
        --pdf-toolbar-color: #26884b !important;
    }

   .pdf-app .secondaryToolbar {
        z-index: 10 !important;
    }
</style>
