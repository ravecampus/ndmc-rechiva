<script setup>
    import { ref, watch } from "vue"
    import $ from "jquery"

    const active = ref(false)
    const errs = ref(false)

    const pdf = ref({})

    const props = defineProps({
          filePdf: {
            type: Object,
            required: true
          },
    })

    const toggleActive = ()=>{
        active.value = !active.value
    }

    const filterFile = (file) =>{
        if(file.type === 'application/pdf'){

            return true;
        }
        return false
    }
    const emit = defineEmits(['removeFile'])
    const removeFile = (e)=>{
        emit('removeFile', e)
    }

    watch(() => props.filePdf, (newValue, oldValue) => {
           pdf.value =  newValue;
    });

</script>

<template>
       <div class="card">
            <div class="card-body upload-file"
            @dragenter.prevent = "toggleActive"
            @dragleave.prevent = "toggleActive"
            @dragover.prevent
            @drop.prevent= "toggleActive"
            :class="{'is_active':active}"
            >
            <!-- <div class="card-body"> -->
                <label class="btn btn-primary mb-2" for="filename">Click here to select files</label>
                <h3>Drag or Drop files here!</h3>
                <p class="text-muted">Only pdf files are accepted to upload</p>
                <input type="file" class="hidden fileupload" accept=".pdf" id="filename"/>
                <div class="row">
                    <div class="col-md-12 d-flex flex-wrap justify-content-around mt-2 mb-0">

                        <div class="card file-card m-2" v-if="pdf.name != undefined">
                            <div class="card-body p-0">
                                <div class="card-icon" @click="removeFile">
                                    <i class="fa fa-close"></i>
                                </div>
                                <label class="file-title text-muted truncate text-start">{{ pdf.name }}</label>
                                <div class="card-content d-flex justify-content-center position-relative">
                                    <div class="position-absolute file-icon d-flex justify-content-center">
                                        <!-- <img class="img-icon position-absolute" :src="showImage(item).base64"/> -->
                                        <img class="img-icon position-absolute" :src="'/img/pdf.jpg'"/>
                                    </div>
                                </div>

                            </div>
                        </div>
              

                    </div>
                <!-- </div> -->
            </div>
            </div>
        </div> 
</template>
