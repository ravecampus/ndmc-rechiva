<script setup>
    import {ref, onMounted, watch, reactive } from "vue"
    import searchContent from "./SearchContent.vue"

    const searchText = ref("")
    
    const filterResult = ref([])
    const authors = ref([])
    const searchMe = ref("")
    const departments = ref([])
    const typeofpapers = ref([])
    const links = ref([])

    const infoMessage = ref("")

    const form = reactive({
        authors: null,
        typeofpapers:null,
        search:"",
        department: 0,
        toyear: null,
        fromyear:null

    })
    onMounted(()=>{
        getAuthors()
        ListofDepartment();
        getTypeofPapers()
        getPublicDoc()
    })
    const getAuthors = ()=>{
        axios.get('/api/list-author?search='+searchMe.value).then((res)=>{
            authors.value = res.data;
        })
    }
    const ListofDepartment = ()=>{
        axios.get('/api/list-departments').then((res)=>{
            departments.value = res.data
        })
    }

    const getTypeofPapers = ()=>{
        axios.get('/api/list-topapers').then((res)=>{
            typeofpapers.value = res.data
        })
    }

    watch(searchMe,()=>{
        getAuthors();
    })
    const searchAuthor = (data)=>{
        searchMe.value = data
         
    }
    const outputAuthor = (data)=>{
        form.authors =  data
        findData();
    }

    const outputTypeof = (data)=>{
        form.typeofpapers = data
        findData();
        
    }

    const findData = ()=>{
        // console.log(data, form)
         getPublicDoc();
        // searchTextCon.value = data;
    }

    const getPublicDoc = ()=>{
        infoMessage.value = "Searching..."
        axios.get('/api/public-document', {params:form}).then((res)=>{
             infoMessage.value = ""
            filterResult.value = res.data.data
            links.value = res.data.links
        })
    }

    const changePage = (link)=>{
        if(!link.url || link.active){
            return
        }
        axios.get(link.url).then((res)=>{
            filterResult.value = res.data.data
            links.value = res.data.links
        })
    }

    const seletedDepartment = (data)=>{
        form.department = data;
        findData();
    }

    const dateYear = (data)=>{
        form.toyear = data.to
        form.fromyear = data.from
        findData()
    }

    const reLoad = ()=>{
        form.search = "";
    }

</script>

<template>
    <div class="container-fluid mb-5">
        <div class="d-flex justify-content-center">
            <div class="input-group input-group-lg w-50 mt-3 border-white">
                <input type="text" class="form-control " v-model="form.search" @keydown.enter.prevent="findData()" placeholder ="Search..." >
                <button class="btn btn-primary" @click.prevent="findData()" type="button" id="button-addon2">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
        
        <searchContent 
        :infomessage="infoMessage"
        :links="links"
        :filterResult="filterResult"
        :authors="authors" 
        :departments="departments"
        :typeofpapers="typeofpapers"
        @search="searchAuthor" 
        @authorfeedback="outputAuthor"
        @typeoffeedback="outputTypeof"
        @changePage="changePage"
        @departmentselected="seletedDepartment"
        @dateyear="dateYear"
        @reload="reLoad"
        >
        </searchContent>
          
    </div>
</template>

<style lang="scss" scoped>
    .border-white{
        border: #fff solid 1px;
        border-radius: 8px;
    }
</style>
