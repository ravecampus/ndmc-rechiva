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
        licenseKey:'6-3kWdKQjo06m1E_ZJaSbz9dWJm6SwIs0w-A_kKbNN3qRasmwHurJjEcglXs91t8XXBTH0A1Um1ixUXx2eHaDx3TcQqGaXs7svxxvmcEbbprr_rHcXDtnToGrkGQq5GZcqswRS8TMaReiIZXwrFBi35nvwCNTsEU4uRTWJe7YJ11v78ypX0kDw4mSGm0kvEuVdJTHbo79gVSvyqdbkvagisQev4itZUJx-XDWLuRZQJVP-QTUes1d_nKAHteomebnru9JafDVQX6Q8CDe1X9_HVdvnFvq7yqY-ILgnmDtRVE7doZchiy9rWewqFC0XahNBtBXy0myZMNB_oPU2dCUj-03LWTAXp2Alcc2qpXn4-3bCnHg9q1e0IErBhoZ_9uEg',
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

