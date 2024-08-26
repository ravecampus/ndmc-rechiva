<script setup>
    import { ref, reactive, watch } from "vue"
    import DropZone from "./Dropzone.vue"
    // import { useBase64 } from '@vueuse/core'

    const filename = ref(null)
    const filesList = ref([])
    let errors = ref([])
    const formData = new FormData();

    const fdata = () => ({title:""})
	const form = reactive(fdata())
    const resetform = () => Object.assign(form, fdata())
    
    const btnsave = ref("Save")
    const btnenable = ref(false)
    

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
        enableButton()
    }

  const enableButton = ()=>{
      if(filesList.value.length > 0){
            btnenable.value = true;
        }else{
             btnenable.value = false;
        }
  }

    const removeFile = (e)=>{
        filesList.value.splice(e, 1)
    }

    const submitData = ()=>{
        btnsave.value = "Processing..."
        filesList.value.forEach(data => {
            formData.append("files[]", data);
        });
        console.log(filesList.value)

        formData.append('title',form.title)
        console.log(formData)
        axios.post('/api/document', formData,
                {
                     headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                }).then((res)=>{
                    btnsave.value = "submit"
                    filesList.value = []
                    resetform()
                    formData.delete("files[]")
                    toast.fire({
                        icon:'success',
                        title:'File Documents Uploaded Successfully!'
                    })
			errors.value = []
                }).catch((err)=>{
                    errors.value = err.response.data.errors
			        btnsave.value = "submit"
                })
    }
</script>

<template>
    <div class="container mt-5">
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-6">
                <DropZone @drop="openFile" @remove-file="removeFile" :ListFiles="filesList" @change="clickFile"></DropZone>
                
            </div>
            <div class="col-4">
                <div class="card text-start">
                        <div class="card-header bg-primary">
                         <strong> Form</strong>
                        </div>
                        <div class="card-body">
                            
                            <div class="form-group mb-3">
                                <label>Title</label>
                                <input type="text" ref="input" v-model="form.title" class="form-control form-control" placeholder="Enter Title">
                                <span class="text-danger" v-if="errors.title">{{errors.title[0]}}</span>
                            </div>
                            <div class="btn-group btn-sm mt-3">
                                <button type="button" v-if="btnenable" @click="submitData" class="btn btn-primary">{{ btnsave}}</button>
        
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</template>

