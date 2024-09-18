<script setup>
    import { ref, onMounted, watch} from "vue"
    import dayjs from 'dayjs'
    import genstatus from "../GenStatus.vue"

    const listData = ref([])
    let links = ref([])
    let searchData = ref('')

    const typeofpapers = ref([])

   

    onMounted(()=>{
        getData()
        getTypeOfPapers()
        ListofDepartment()
    })

    const formatDate = (dateString)=>{
		const date = dayjs(dateString)
		return date.format('MMMM d, YYYY | hh:mm a')
	}

    const getData = ()=>{
        axios.get('/api/admin-document-archived?search='+searchData.value).then((res)=>{
            listData.value = res.data.data
            links.value = res.data.links
        })
    }

     watch(searchData, ()=>{
		getData()
	})

    const changePage = (link)=>{
        if(!link.url || link.active){
            return
        }
        axios.get(link.url).then((res)=>{
            listData.value = res.data.data
            links.value = res.data.links
        })
    }

    const getTypeOfPapers = ()=>{
        axios.get('/api/list-topapers').then((res)=>{
            typeofpapers.value = res.data
        })
    }

    const extractTOP = (data)=>{
        let ret = "";

        typeofpapers.value.forEach(val => {
            if(data.type_of_paper_id === val.id){
                ret = val.description
            }
        });

        return ret;
    }

     const extractDepartment = (data)=>{
        let ret = "";

        departments.value.forEach(val => {
            if(data.department_id === val.id){
                ret = val.description
            }
        });

        return ret;
    }

    const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }
    const departments = ref([])
    const ListofDepartment = ()=>{
        axios.get('/api/list-departments').then((res)=>{
            departments.value = res.data
        })
    }
    const userExtract = (data)=>{
        if(data != undefined){
             return data.first_name+ " "+ data.last_name
        }
       
    }

</script>


<template>
  <div class="container mt-2">
    <div class="row">
        <div class="col-md-12 text-start">
            <h4 class="m-0">Archived</h4>
            <p>{{ formatDate(new Date()) }}</p>
            <hr>
        </div>
        <div class="col-12 mb-3 mb-lg-5">
            <div class="overflow-hidden card table-nowrap table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Documents</h5>
                </div>
                <div class="input-group input-group-sm p-2">
                    <input type="text" class="form-control" placeholder="Search..." aria-label="Example text with button addon" v-model="searchData" aria-describedby="button-addon1">
                    <button class="btn btn-primary" type="button" id="button-addon1">
                        <i class="fa fa-search"></i>
                    </button>
				</div>
                <div class="table-responsive">
                    
                    <table class="table mb-0">
                        <thead class="small text-uppercase bg-body text-muted">
                            <tr>
                                <th>Title</th>
                                <th>College Department</th>
                                <th>Uploaded By</th>
                                <th>Type of Papers</th>
                                <!-- <th>Upload Type</th> -->
                                <th>Date uploaded</th>
                                <!-- <th>Status</th> -->
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="align-middle" v-for="(list,index) in listData" :key="index">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-text me-2 icon-file"></i>
                                       <strong class="text-primary"> {{ list.title }} </strong>
                                    </div>
                                </td>
                                <td>{{ list.department.description}}</td>
                                <td>{{ userExtract(list.userdoc) }}</td>
                                <td>{{ extractTOP(list) }}</td>
                                <td>{{ formatDate(list.created_at) }}</td>
                                <td class="text-end">
                                    <div class="drodown">
                                        <a data-bs-toggle="dropdown" href="#" class="btn p-1" aria-expanded="false">
                                  <i class="fa fa-bars" aria-hidden="true"></i>
                                </a>
                                        <div class="dropdown-menu dropdown-menu-end dd-bg" style="">
                                            <router-link :to="{name:'admin.requestdoc', params:{'id':list.id}}"  class="dropdown-item dd-item">View</router-link>
                                            <!-- <a href="#!" @click="deleteDocs(list)" class="dropdown-item dd-item">Delete </a> -->
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6" v-show="!noData(listData)">
                                    No Result Found!
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                    <nav class="mt-2 d-flex justify-content-end me-2" aria-label="..." v-if="noData(listData)">
                        <ul class="pagination pagination-sm custom-page">
                            <li class="page-item" aria-current="page" v-for="(link, index) in links" :key="index">
                                <a class="page-link"
                                href="#"
                            
                                v-html="link.label"
                                :class="{active:link.active, disabled: !link.url }"

                                @click="changePage(link)"
                                ></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
