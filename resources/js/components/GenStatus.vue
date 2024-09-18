<script setup>
 import { ref, onMounted, watch } from "vue"
 
const badge = ref("");
const divstatus = ref(false)
const props = defineProps({
        statusData: {
            type: Number,
            required: false
        },
        uploadData: {
            type: Number,
            required: false
        },
        uploadDataView: {
            type: Object,
            required: false
        },
})

watch(props, ()=>{
    console.log(props.statusData != undefined)
    if(props.statusData != undefined || props.uploadData != undefined){
        divstatus.value = true;
        statusSet()
        UploadSet()

    }else if(props.uploadDataView != undefined){
        divstatus.value = false;
        UploadViewSet()
    }
  
   
})

onMounted(()=>{
     if(props.statusData != undefined || props.uploadData != undefined){
        divstatus.value = true;
        statusSet()
        UploadSet()

    }else if(props.uploadDataView != undefined){
        divstatus.value = false;
        UploadViewSet()
    }
})
const statusSet = ()=>{
    const val = props.statusData;

    if(val === 0){
        badge.value = "On Checking..."
    }else if(val === 1){
        badge.value = "Approved"
    }else if(val === 2){
        badge.value = "Disapproved"
    }
}

const UploadSet = ()=>{
    const val = props.uploadData;

    if(val === 0){
        badge.value = "Publish"
    }else if(val === 1){
        badge.value = "Archive"
    }
}

const UploadViewSet = ()=>{
    const val = props.uploadDataView;
    if(val.status === 1 && val.upload_type === 0){
        badge.value = "Published"
    }else if(val.status === 1 && val.upload_type === 1){
        badge.value = "Archived"
    }else if(val.status === 0 && val.upload_type === 0){
        badge.value = "Publish"
    }else if(val.status === 0 && val.upload_type === 1){
        badge.value = "Archive"
    }else if(val.status === 2 && val.upload_type === 1){
        badge.value = "Not Archived"
    }else if(val.status === 2 && val.upload_type === 0){
        badge.value = "Unplublished"
    }
    
}
</script>


<template>
    <div>
        <div v-if="divstatus" class="badge rounded-pill " :class="props.statusData == 2 ? 'bg-danger' : 'bg-secondary'">
            {{ badge }}
        </div>
        <div v-if="!divstatus">
            {{ badge }}
        </div>
    </div>
</template>
