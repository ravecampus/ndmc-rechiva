<script setup>
    import { ref, reactive, watch, onMounted } from "vue"

    const emit = defineEmits(['loadFilter'])

     const props = defineProps({
          urlString: {
            type: String,
            required: true
          },
          authors: {
            type: Array,
            required: false
          },
    })


    const listdata = ref([])
   	const fdata = () => ({
           first_name:"",
           middle_name:"",
           last_name:"",
            id:null
        })
	const form = reactive(fdata())
	const resetform = () =>{
         search.value = ""
         Object.assign(form, fdata())
    } 
    const search = ref("")
    const tags = ref([])
    const inputTag = ref("")
    const error = ref("")
    let errors = ref([])
    // const inp = defineProps(['inputList'])
    
    onMounted(()=>{
        tags.value = props.authors

    })

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
                axios.delete('/api/doc-author/'+data.id).then((res)=>{
                    tags.value.splice(obj, 1)
                    error.value = ""
                    emit('loadFilter', tags.value)
                     Swal.fire({
                        title: "Deleted!",
                        text: "Author has been deleted.",
                        icon: "success",
                        confirmButtonColor: "#26884b",
                        
                    });
                })
            }
        });
            
        }else{
            tags.value.splice(obj, 1)
            error.value = ""
            emit('loadFilter', tags.value)
        }
      
    }
    // tags.value = inp.inputList
    const addTag = (data)=>{
        const chk = tags.value.filter(e=>e.author_id == data.id);
        if(chk.length == 0){
            tags.value.push({
                "author_id":data.id, 
                "first_name":data.first_name,
                "middle_name":data.middle_name,
                "last_name":data.last_name
                })
            search.value = ""
            error.value = ""
            emit('loadFilter', tags.value)
           
        }else{
            error.value = "Duplicate Entry!"
        }

    }
    watch(search, ()=>{
        getDBList(props.urlString)
    })

    const getDBList = (url)=>{
        axios.get(url+'?search='+search.value).then((res)=>{
            listdata.value = res.data.data;
        })
    }

    const addDBList = (url = props.urlString)=>{
        axios.post(url,form).then((res)=>{
            getDBList(props.urlString)
            errors.value = []
            resetform();
        }).catch((err)=>{
            errors.value = err.response.data.errors
        })
    }
    
    const closeDdown = ()=>{
        listdata.value = []
        error.value = ""
        // search.value = ""
    }
    const loadList = ()=>{
        if(search.value != ""){
            getDBList(props.urlString)
        }
      
    }

    const setText = ()=>{
        form.description = search.value
    }

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="input-tag">
                <div v-for="(item, index) in tags" :key="index" class="badge rounded-pill text-white me-1 mt-1 tag">
                    {{ item.first_name }} {{ item.middle_name }} {{ item.last_name }}
                    <i class="bi bi-x-circle-fill close-tag" @click="removeItem(index, item)"></i>
                </div>
                <input type="text" class="text-tag p-0 m-0" @focus="loadList" @keyup="setText" v-model="search" placeholder="Enter Author name..."/>
               
            </div>
            <ul class="search-data" v-if="listdata.length != 0 || search != '' " >
                <li>
                   <div class="item-data-none">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-primary text-white" id="inputGroup-sizing-default">Add Author: </span>
                            <input type="text" v-model="form.first_name" class="form-control" placeholder="Enter First name">
                            <input type="text" v-model="form.middle_name" class="form-control" placeholder="Enter Middle name">
                            <input type="text" v-model="form.last_name" class="form-control" placeholder="Enter Last name">
                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" @click.prevent="resetform()" data-bs-placement="top" title="clear" type="button">
                                <i class="bi bi-x"></i>
                            </button>
                            <button class="btn btn-outline-secondary" @click.prevent="addDBList()" data-bs-toggle="tooltip" data-bs-placement="top" title="Save author's name" type="button">
                                <i class="bi bi-floppy"></i>
                            </button>
                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Close" @click="closeDdown"  type="button">
                                <i class="bi bi-x-octagon"></i>
                            </button>
                        </div>
                        <span class="text-danger error-message" v-if="errors.first_name">{{ errors.first_name[0] }}</span>&nbsp;
                        <span class="text-danger error-message" v-if="errors.middle_name">{{ errors.middle_name[0] }}</span>&nbsp;
                        <span class="text-danger error-message" v-if="errors.last_name">{{ errors.last_name[0] }}</span>
                   </div>
                </li>
                <li><hr class="m-0"></li>
                <li v-for="(item, index) in listdata" :key="index"  v-if="listdata.length != 0">
                    <a class="item-data start-100" href="#" @click.prevent="addTag(item)"><i class="bi bi-plus-circle me-auto"></i> {{ item.first_name }} {{ item.middle_name }} {{ item.last_name}}</a>
                </li>
                <li>
                    <span class="item-data text-danger error-message">{{ error }}</span>
                </li>
             
            </ul>
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

    .search-data{
        position: absolute;
        z-index: 1000;
        // display: none;
        min-width: 10rem;
        padding: .5rem 0;
        margin: 0;
        font-size: 1rem;
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, .15);
        border-radius: .25rem;
        box-shadow: 0 4px 6px 0 rgba(0, 0, 0, 0.3) !important;
    }
    .item-data{
            display: block;
            width: 100%;
            padding: .25rem 1rem;
            clear: both;
            font-weight: 400;
            color: #212529;
            text-align: inherit;
            text-decoration: none;
            white-space: nowrap;
            background-color: transparent;
            border: 0;
            &:hover{
                background-color: #26884b;
                color: #fff !important;
            
            }
    }
    .item-data-none{
            display: block;
            width: 100%;
            padding: .30rem;
            clear: both;
            font-weight: 400;
            color: #212529;
            text-align: inherit;
            text-decoration: none;
            white-space: nowrap;
            background-color: transparent;
            border: 0;
    }
    

    .card-search{
        // padding: .5rem;
        // border: 1px solid #26884b;
    }

    .error-message{
        font-size: 11px;
    }
</style>

