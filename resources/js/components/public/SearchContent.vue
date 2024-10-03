<script setup>
    import { ref, watch } from "vue"

    const author = ref([])
    const typeofpaper = ref([])
    const listData2 = ref([])
    const listData2D = ref([])
    const listData = ref([])
    const listDataD = ref([])
    const searchAuth = ref("")
    const department = ref("")
    const nores = ref(true)
    let links = ref([])
    const props = defineProps(['authors', 'typeofpapers', 'departments', 'filterResult', 'links','infomessage'])
    const emit = defineEmits(['search', 'authorfeedback','typeoffeedback', 'changePage', 'departmentselected', 'dateyear', 'reload'])
    

    const from_year = ref(new Date().getFullYear());
    const to_year = ref(new Date().getFullYear());

    const checkAuthor = (data, list)=>{
        data.forEach((val, idx) => {
            if(val){
                const chk = listDataD.value.filter(e=>e.id == list.id);
                if(idx == list.id && chk.length == 0){
                    listDataD.value.push(list)
                    listData.value.push(list.id)
                }
            }

            if(!val && idx == list.id){
                listDataD.value.splice(listDataD.value.indexOf(list), 1)
                listData.value.splice(listData.value.indexOf(list.id), 1)
            }
            
        });

        emit("authorfeedback", listData.value)
    }

    
    const checkTypeof = (data, list)=>{
        data.forEach((val, idx) => {
            if(val){
                const chk = listData2D.value.filter(e=>e.id == list.id);
                if(idx == list.id && chk.length == 0){
                    listData2.value.push(list.id)
                    listData2D.value.push(list)
                    
                }
            }

            if(!val && idx == list.id){
                listData2.value.splice(listData2.value.indexOf(list.id), 1)
                listData2D.value.splice(listData2D.value.indexOf(list), 1)
            }
            
        });

        emit("typeoffeedback", listData2.value)
    }

    const removeAuthor = (data)=>{
        listDataD.value.splice(listDataD.value.indexOf(data), 1)
        author.value[data.id] = false;

        listData.value.splice(listData.value.indexOf(data.id), 1)
        emit("authorfeedback", listData.value)
    }
    const removeTypeof = (data)=>{
        listData2D.value.splice(listData2D.value.indexOf(data), 1)
        typeofpaper.value[data.id] = false; 

        listData2.value.splice(listData2.value.indexOf(data.id), 1)
        emit("typeoffeedback", listData2.value)
        
    }


    const searchAuthor = (data)=>{
        emit("search", data)
    }

    watch(props,()=>{
       noResult();
    })

    const noResult = (data)=>{
        return (props.filterResult).length == 0 ? nores.value = true : nores.value = false;
    }

    const noData = (data)=>{
        return  (data.length > 0) ? true : false;
    }

    const changePageF = (data)=>{
        emit('changePage', data)
    }

    const resetFilter = ()=>{
        department.value = 0
        emit('departmentselected', department.value)
        listData.value = [];
        listDataD.value.forEach(val => {
            author.value[val.id] = false; 
        });
        listDataD.value = [];
        emit("authorfeedback", listData.value)

        listData2.value = [];
        listData2D.value.forEach(val => {
            typeofpaper.value[val.id] = false; 
        });
        listData2D.value = [];
        emit("typeoffeedback", listData2.value)
        to_year.value = new Date().getFullYear()
        from_year.value = new Date().getFullYear()
        emit("dateyear", {
                to:null, 
                from:null
                })
         datemessage.value =""
        
    }

    const selectDepartment = (data)=>{
        emit('departmentselected', data)
    }

    const datemessage = ref("")
    const selectedYear = ()=>{
        datemessage.value =""
        if(from_year.value >= to_year.value){
            to_year.value = ""
             datemessage.value ="From year must greater than To year!"
        }else{
            emit("dateyear", {
                to:to_year.value, 
                from:from_year.value
                })
        }
    }

    const emptyTo = ()=>{
        to_year.value = new Date().getFullYear()
    }

    const reload = ()=>{
        emit('reload', null)
        resetFilter();

    }
</script>


<template>
<div class="card mt-3 card-search mh-card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-start">
                <div class="d-flex justify-content-between">
                <h4>Filters :</h4> 
                <button type="button" @click="resetFilter()" class="btn btn-success btn-sm">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
                </div>
                <div class="text-start mb-4 filter-card mt-2 p-2">
                    <small>College Department</small>
                    <select class="form-select form-select-sm" v-model="department" @change.prevent="selectDepartment(department)">
                        <option value="0" selected>All Department</option>
                        <option v-for="(list, index) in props.departments" :key="index" :value="list.id">{{ list.description }}</option>
                        
                    </select>
                </div>
                <div class="card filter-card p-0">
                    <div class="card-body p-1">
                        <small>Authors</small>
                        <div class="author-tag">
                            <div class="badge rounded-pill text-white me-1 mt-1 tag sub-a-tag" v-for="(list,idx) in listDataD" :key="idx">
                                 {{ list.first_name }}
                                 {{ list.middle_name }}
                                 {{ list.last_name }}
                                  <i class="bi bi-x-circle close-tag" @click="removeAuthor(list)"></i>
                            </div>
                        </div>
                        <ul class="author-filter overflow-auto mt-1">
                            <li v-for="(list, index) in props.authors" :key="index">
                                <div class="form-check">
                                    <input class="form-check-input" v-model="author[list.id]"  @change="checkAuthor(author, list)" type="checkbox" :value="list.id" :id="list.id">
                                    <label class="form-check-label" :for="list.id">
                                        {{ list.fist_name }}
                                        {{ list.middle_name }}
                                        {{ list.last_name }}
                                    </label>
                                </div>
                            </li>
                            <li v-if="props.authors.length == 0">
                                No result found!
                            </li>
                         
                        </ul>
                    </div>
                     <div class="card-footer">
                        <div class="input-group input-group-sm">
                            <input type="text" v-model="searchAuth" @keyup.prevent="searchAuthor(searchAuth)" class="form-control" placeholder="Search..." >
                            <button class="btn btn-primary" @click="searchAuthor(searchAuth)" type="button" id="button-addon2">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card filter-card p-0">
                    <div class="card-body p-1">
                        <small>Type of Papers</small>
                        <div class="author-tag">
                            <div class="badge rounded-pill text-white me-1 mt-1 tag sub-a-tag" v-for="(list,idx) in listData2D" :key="idx">
                                 {{ list.description }}
                                  <i class="bi bi-x-circle close-tag" @click="removeTypeof(list)"></i>
                            </div>
                        </div>
                        <ul class="author-filter overflow-auto mt-1">
                            <li v-for="(list, index) in props.typeofpapers" :key="index">
                                <div class="form-check">
                                    <input class="form-check-input" v-model="typeofpaper[list.id]"  @change="checkTypeof(typeofpaper, list)" type="checkbox" :value="list.id" :id="'top'+list.id">
                                    <label class="form-check-label" :for="'top'+list.id">
                                        {{ list.description }}
                                    </label>
                                </div>
                            </li>
                            <li v-if="props.typeofpapers.length == 0">
                                No result found!
                            </li>
                         
                        </ul>
                    </div>
                </div>

                 <div class="card filter-card p-0">
                    <div class="card-body p-1 pb-5">
                        <small>Publication Date</small>
                        <div>
                            <span class="text-danger">{{ datemessage }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="form-group me-1">
                                <label>From:</label>
                                <VueDatePicker :class="'me-1'" v-model="from_year" year-picker @update:model-value="emptyTo()"/>
                            </div>
                            <div class="form-group">
                                <label>To:</label>
                                <VueDatePicker v-model="to_year" year-picker  @update:model-value="selectedYear()"/>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-9 mt-2">
                <div class="row mb-5">
                    <div class="col-md-12">
                        {{ props.infomessage }}
                    </div>
                </div>
                <div class="content-message-group" v-for="(lst,idx) in props.filterResult" :key="idx">
                    <router-link :to="{name:'main.document', params:{id:lst.id}}" class="title">
                        {{ lst.title }}
                   </router-link>
                    <div class="message text-truncate-message" style="max-width: 100em;">
                       {{ lst.abstract }}
                    </div>
                    <div class="keyword">
                        <span class="kword" v-for="(ls, ix) in lst.keywords" :key="ix">{{ ls.description }},</span>
                     
                    </div>
                </div>
                <div class="card filter-card" v-if="noResult(props.filterResult)">
                    <div class="card-body">
                        <h4>No result found! </h4>
                        <button type="button" @click="reload()" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-arrow-clockwise"></i>
                            Reload
                        </button>
                    </div>
                </div>

                <nav class="mt-4" v-show="noData(props.filterResult)">
					<ul class="pagination pagination-sm custom-page custom-paginate">
						<li class="page-item" aria-current="page" v-for="(link, index) in props.links" :key="index">
							<a class="page-link text-sm"
							href="#"
						
							v-html="link.label"
							:class="{active:link.active, disabled: !link.url }"

							@click="changePageF(link)"
							></a>
						</li>
					</ul>
				</nav>

            </div>
        </div>
    </div>
</div>
    
</template>
<style lang="scss" scoped>
    .card-search{
        margin-bottom: 20px;
        background-color: #fdfdfde3;
        border: 1px solid transparent;
        border-radius: 2px;
         border:#dfdfdf6b solid 1px;
        -webkit-box-shadow: 0 1px 1px #0000000d;
        box-shadow: 0 1px 1px #0000000d;
    }
    .mh-card{
        max-height: 75% !important;
    }
    .filter-card{
        background-color: #fff;
        border: 2px solid #26884b !important;
        border-radius: 4px !important;
         border:#dfdfdf6b solid 1px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }

    .author-tag{
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
    }

    .sub-a-tag{
        background-color:#26884b;
        font-size: 14px;
    }

    .author-filter{  
        max-height: 8em;
        height: 8em;
        min-width: 10rem;
        padding: .5rem;
        margin: 0;
        font-size: 1rem;
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        // border: 1px solid rgba(0, 0, 0, .15);
        // border-radius: .25rem;
        // box-shadow: 0 4px 6px 0 rgba(0, 0, 0, 0.3) !important;
    }

    .content-message-group{
        text-align: left !important;
        // margin-top:4rem;
        padding: 0px 10px;
        padding-bottom: 20px !important;
        border: 1px solid transparent;
        &:hover{
            border: 1px solid #dfdfdfbb;
            border-radius: .2rem;
            box-shadow: 0 2px 2px 0 #cecece56 !important;
            background-color: #fff;
        }
        .title{
            font-size: 1.5rem;
            font-weight: 700;
            padding: 0;
            margin: 0;
            &:hover{
                text-decoration: underline !important;
            }
        }

        .message{
            font-size: 1.1rem;
            padding: 0;
            margin: 0;
            color:#777777;
        }
        .keyword::before{
            content: "\2014 \00A0";
        }
        .keyword{
              color: #adadad;
            .kword{
                margin-right: 3px;
                color: #adadad;
            }
        }
    }

    .text-truncate-message{
        -webkit-line-clamp: 3;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .custom-paginate{
        border: none !important;
        .page-link{
            border: none !important;
            font-weight: 600 !important;
        }
    }
</style>
