<template>
	<div>
	<div class="doc-container adjust-size"></div>
	
	</div>
</template>

<script setup>

import { ref, onBeforeUnmount, onMounted, watch } from "vue"
 
const emit = defineEmits(['loaded'])
const props = defineProps({
        pdfFile: {
            type: String,
            required: true
        },
})

onMounted(()=>{
    loadPSPDFKit().then((instance)=>{
        emit('loaded', instance);
    })
})



const  loadPSPDFKit = async()=>{
    PSPDFKit.unload('.doc-container');
    const initialViewState = new PSPDFKit.ViewState({
        readOnly: true,
    });

   

    return await PSPDFKit.load({
        // licenseKey:'ATi46klU5_QpWWkkfceNCcIkMuN6chgNJ5QOkbLZ7QmbfS1V2qgdRWRHyH2f3gTDcvCjTTUQPa3GqgWzEPC6DKkjTvzbZv75wRbuYAniIHLRddCgA2zP7AHnNMCGms_7Pl9WOe9A5hcbEiF_EpgJQsDRpvEHm3VxcYz2uvWJFecL-wAWzlYAGpE6_sppFu4r66dxAfn4vgEEclJ4',

        document: props.pdfFile,
        container: '.doc-container',
        initialViewState,
        }).then((instance) => {
           const items = instance.toolbarItems;
            // Hide the toolbar item with the type "export-pdf" by removing it from the array of items.
            instance.setToolbarItems(
            items.filter((item) => item.type !== "export-pdf")
            );
            URL.revokeObjectURL(props.pdfFile);
           

        });
}

    watch(props, ()=>{
        if(props.pdfFile){
            loadPSPDFKit()

        }
    })

onBeforeUnmount(()=>{
    PSPDFKit.unload('.doc-container');
})

</script>

<style scoped>
.doc-container {
	height: 100vh;
	/* width: 200px; */
}


</style>

