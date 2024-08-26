<script setup>
    import { ref, reactive } from "vue"
    import DropZone from "./Dropzone.vue"
    // import { useBase64 } from '@vueuse/core'

    const filename = ref(null)
    const filesList = ref([])
    

    const openFile = (e)=>{
        let data = e.dataTransfer.files;
        filterData(data)
    }

    const clickFile = ()=>{
        let data = document.querySelector('.fileupload').files
        filterData(data)
    }

    const filterData = (data)=>{
        for( var i = 0; i < data.length; i++ ){
            let file = data[i];

            // if(filesList.value.length === 0){
            //     filesList.value.push(file)
            // }
           const mes =  filesList.value.find((i)=>i.name === file.name)
           if(mes === undefined){
                filesList.value.push(file)
           }
        }
    }

    
    const removeFile = (e)=>{
        filesList.value.splice(e, 1)
    }
</script>

<template>
    <div class="container mt-5">
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-6">
                <DropZone @drop="openFile" @remove-file="removeFile" :ListFiles="filesList" @change="clickFile"></DropZone>
                
            </div>
            <div class="col-4">
                <div class="card">
                        <div class="card-body"></div>
                </div>
            </div>
        </div>
    </div>
</template>

