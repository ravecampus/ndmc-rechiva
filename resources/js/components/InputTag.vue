<script setup>
    import { ref, reactive, onMounted } from "vue"

    const emit = defineEmits(['showData'])

    const props = defineProps({
            keywords: {
            type: Object,
            required: false
          },
    })

    onMounted(()=>{
         tags.value = (props.keywords == undefined ? [] :props.keywords)
    })


    const tags = ref([])
    const inputTag = ref("")
    // const inp = defineProps(['inputList'])
    
    const removeItem = (obj, data)=>{

         if(data.id != undefined){

            Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#26884b",
            cancelButtonColor: "#ffc107",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.delete('/api/doc-keyword/'+data.id).then((res)=>{
                    tags.value.splice(obj, 1)
                    emit('showData', tags.value)
                     Swal.fire({
                        title: "Deleted!",
                        text: "Keyword has been deleted.",
                        icon: "success",
                        confirmButtonColor: "#26884b",
                        
                    });
                })
            }
        });
            
        }else{
           tags.value.splice(obj, 1)
            emit('showData', tags.value)
        }
        
    }
    // tags.value = inp.inputList
    const addTag = ()=>{
        const chk = tags.value.filter(e=>e.description == inputTag.value);
        if(inputTag.value != "" && chk.length == 0){
            tags.value.push({"description":inputTag.value})
            inputTag.value = ""
            emit('showData', tags.value)
        }

    }
</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="input-tag">
                <div v-for="(item, index) in tags" :key="index" class="badge rounded-pill text-white me-1 mt-1 tag">
                    {{ item.description }}
                    <i class="bi bi-x-circle close-tag" @click="removeItem(index, item)"></i>
                </div>
                <input type="text" class="text-tag p-0 m-0" @keydown.,.prevent="addTag" v-model="inputTag" placeholder="Enter keywords..."/>
            </div>
        </div>
    </div>
</template>
 <style lang="scss">

    .input-tag{
        display: flex;
        flex-wrap: wrap;
        background-color: #fff;
        border: 1px solid #d8d8d8;
        border-radius: 0.25rem;;
        // padding: .4rem;
        text-align: left;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        min-height: calc(1.5em + 0.5rem + calc(var(--bs-border-width)* 2));
        // position: relative;
        &:hover{
            border-color:#26884b !important;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 253, 113, 0.25) !important;
        }
    }

    .text-tag{
        // position: absolute;
        outline: none;
        font-size: 0.875rem;
        border:none;
        margin: 0 !important;
        width: 50%;
    }
    .tag{
        background-color:#26884b;
        font-size: 14px;
    }
    .close-tag{
        cursor: pointer;
        margin-left: 4px;
        font-weight: bold;
    }
</style>

