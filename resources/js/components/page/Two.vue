<script setup>
    import { reactive, ref, onMounted } from "vue"
    import { useRouter, useRoute } from "vue-router"

    // import axios from "axios"

    const router = useRouter()

    const form = reactive({
        title:"",
        description:"",
        author:"",
        year:"",
        type_of_paper:"",
        keywords:""

    })

    const editMode = ref(false)

    const route = useRoute();

    onMounted(()=>{
        if(route.name === 'document.edit'){
            getDoc()

            editMode.value = true
        }
    })

    const getDoc = () =>{
        axios.get(`/api/document/${route.params.id}`).then((res)=>{
            form.title = res.data.title
            form.description = res.data.description
        })
    }

    let errors = ref([])
    const saveData = () =>{
        if(editMode.value === true){
            updateDoc()
        }else{
            addDoc()
        }
    
    }


    const addDoc = ()=>{
        axios.post('/api/document/', form).then((res)=>{
            router.push("/list-documents")
            toast.fire({
                icon:"success",
                title:"Documents added successfully!"
            })
        }).then((res)=>{

        }).catch((err)=>{
            if(err.response.status == 422){
                errors.value = err.response.data.errors
            }
        })    
    }


    const updateDoc = ()=>{
        axios.put(`/api/document/${route.params.id}`, form).then((res)=>{
            router.push("/list-documents")
            toast.fire({
                icon:"success",
                title:"Documents update successfully!"
            })
        }).then((res)=>{

        }).catch((err)=>{
            if(err.response.status == 422){
                errors.value = err.response.data.errors
            }
        })    
    }

</script>

<template>
    <div class="container mt-5">

        <div class="card">
            <div class="card-header">
                Add Document
            </div>
            <div class="card-body">
                <div class="mb-3 row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" v-model="form.title" id="staticEmail">
                    <span class="errors-material" v-if="errors.title">{{ errors.title[0]}}</span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                    <input type="text" v-model="form.description" class="form-control">
                      <span class="errors-material" v-if="errors.description">{{ errors.description[0]}}</span>
                    </div>
                </div>

                 <div class="mb-3 row">
                    <label  class="col-sm-2 col-form-label">Author</label>
                    <div class="col-sm-10">
                    <input type="password" v-model="form.author" class="form-control" >
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Year</label>
                    <div class="col-sm-10">
                    <input type="password" v-model="form.year" class="form-control">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Type of Paper</label>
                    <div class="col-sm-10">
                    <input type="password" v-model="form.type_of_paper" class="form-control" >
                    </div>
                </div>
                <button type="button" @click="saveData" class="btn btn-primary">save</button>
            </div>
        </div>
        
    </div>
</template>
