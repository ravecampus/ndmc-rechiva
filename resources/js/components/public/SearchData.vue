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

    const listsearch = ref([])

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
        getPublicDoc();
        listsearch.value = []
        if(form.search != ""){
            storeLS();
        }
       
    }
    const local = ref([])
    const storeLS = ()=>{
       
        let data = JSON.parse(localStorage.getItem("search_history"))
        local.value  = data
        let chk = data.filter(e=>e.title == form.search);
        if(chk.length == 0){
                if(local.value.length > 0 ){
                    local.value.push({title:form.search})
                    localStorage.setItem("search_history",JSON.stringify(local.value))
                }else{
                    local.value.push({title:form.search})
                    localStorage.setItem("search_history",JSON.stringify(local.value))
                }
        }
       
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
    const vhis = ref(false)
    const searchDis = ref("")
    const seachList = (e)=>{
        if(e.key != 'Enter'){
            axios.get('/api/search-list', {params:form}).then((res)=>{
                if(form.search == ""){
                    vhis.value = true
                    let data = JSON.parse(localStorage.getItem("search_history"))
                    if(data.length != null){
                        listsearch.value = data.reverse()
                    }
                   
                    // .sort((a,b) => a.title.localeCompare(b.title))
                }else{
                    listsearch.value = res.data
                    vhis.value = false;
                }
              
            })
        }else{
            searchDis.value = form.search
        }

        
       
    }

    const selectSearch =(data)=>{
        form.search = data;
        findData();
        searchDis.value = data
      
    }

    const clearHis = ()=>{
        localStorage.setItem("search_history",JSON.stringify([]))
         listsearch.value = []
    }

</script>

<template>
    <div class="container-fluid mb-5">
        <div class="d-flex justify-content-center">
            <div class="search w-50 mt-3 position-absolute">
            <div class="input-group input-group-lg  border-white">
                <input type="text" class="form-control " v-model="form.search" 
                @keyup.prevent="seachList" 
                @focus="seachList" 
                @keydown.enter.prevent="findData()" placeholder ="Search..." >
                <button class="btn btn-primary" @click.prevent="findData()" type="button" id="button-addon2">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            <div class="search-history" v-if="listsearch.length > 0">
                <div class="clear-search d-flex justify-content-between p-1" v-if="vhis">
                    <span>Search History</span>
                    <a href="#" @click="clearHis">clear</a>

                </div>
                <ul class="dropdown-con">
                    <li class="list" v-for="(list, index) in listsearch" :key="index">
                        <a class="link" @click.prevent="selectSearch(list.title)" href="#">{{ list.title }}</a>
                    </li>
                   
                </ul>
            </div>
            </div>
        </div>
        <div class="d-flex justify-content-center search-display">
           <div class="message w-100">
               <div class="title">
                   {{ searchDis}}
               </div>
               <span v-if="searchDis!=''">{{  filterResult.length }} item/s found for "{{ searchDis }}"</span>
           </div>


        </div>
        <searchContent  :class="'content-output'"
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
    .search-history{
        background-color: #fff;
        text-align: start;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        padding:5px;
        border:#dfdfdf6b solid 1px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        border-bottom: #0aa35c solid 1px;
        border-left: #0aa35c solid 1px;
        border-right: #0aa35c solid 1px;
       
    }

    .search{
        z-index: 9999;
    }

    .dropdown-con{
        list-style: none;
        top: 3.2em;
        width: 100%;
        padding: 0;
        overflow-y: scroll;
        max-height: 10rem;
     
       .list{
           .link{
                display: block;
                padding: 0.1em 0.5em;
                color: #26884b;
                margin: 0.1em 0;
                font-size: 1.1rem;
                font-weight: 500;
                text-decoration: none !important;
           }

           &:hover{
               background-color: #e4e4e480;
           }
       }
    }

    .content-output{
        margin-top: 1rem !important;
    }

     ::-webkit-scrollbar {
        width: .2rem;
    }
    ::-webkit-scrollbar-track {
        background: #0aa35c;
     }
     .clear-search{
         background-color: #ececec;
     }
     .search-display{
         margin-top: 5.2rem !important;
     }
     .message{
         background-color: #fff;
         color:#26884b;
         padding: 5px 15px;
         border-radius: 5px;
        //  text-align: start;
         height: 5rem;

         .title{
             font-size: 1.2rem;
             font-weight: 500;
         }
     }
</style>
