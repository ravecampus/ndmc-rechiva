<script setup>
    import { ref, reactive, onMounted } from "vue"
    import dayjs from "dayjs"

    const fdata = () => ({
            date_from:"",
            date_to:""
        })
    const formatDate = (dateString)=>{
		const date = dayjs(dateString)
		return date.format('MMMM D, YYYY')
	}
    const report = ref([])
    const members = ref([])
    const submission = ref([])
    const btngenerate = ref("generate")
	const form = reactive(fdata())
    const resetform = () => Object.assign(form, fdata())
    
    onMounted(() => {
        ListofDepartment()
        ListTypeofPapers()
    })

     const filter = reactive({
        report: null,
        department: 0,
        typeofpapers:null,
        date_to: null,
        date_from:null

    })

    const showrep = ref(0)
    const reptitle = ref("")

    const filterReport = (data)=>{
        reptitle.value = (data == 1) ? "ALL DOCUMENTS" :
                         (data == 2) ? "PUBLISHED" :
                         (data == 3) ? "ARCHIVED" :
                         (data == 4) ? "USERS" : ""
        showrep.value = data;
    }

    const format = (d) => {
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();
        return  month+ "/" + day  + "/" + year;
    }

    const print = ()=>{
        window.print()
    }
    const published = ref([])
    const archived = ref([])
    const faculty = ref([])
    const list_published = ref([])
    const list_archived = ref([])
    const users = ref([])
    const generate = ()=>{
        btngenerate.value = "generating..."
        axios.get('/api/report',{params:filter}).then((res)=>{
            btngenerate.value = "generate"
            published.value = res.data.published
            archived.value = res.data.archived
            faculty.value = res.data.faculty
            list_published.value = res.data.published_list
            list_archived.value = res.data.archived_list
            users.value = res.data.users
            
        })
    }

    const extractMember = (data)=>{
        let ret = 0; 
        members.value.forEach(val => {
            if(data.department_id == val.department_id){
                ret = val.number_of_member
            }
        });

        return ret
        
    }


    const totalMem = ()=>{
        let ret = 0
        members.value.forEach(val => {
            
            ret += val.number_of_member
            
        });

        return ret
    }

    const totalPublished = ()=>{
        let ret = 0
        published.value.forEach(val=>{
            if(filter.department == val.department_id){
                ret += val.published
            }else{
                 ret += val.published
            }
        })
        return ret;
    }

    const totalArchived = ()=>{
        let ret = 0
        archived.value.forEach(val=>{
            if(filter.department == val.department_id){
                ret += val.archived
            }else{
                 ret += val.archived
            }
        })
        return ret;
    }

    const totalFaculty = ()=>{
        let ret = 0
        faculty.value.forEach(val=>{
            if(filter.department == val.department_id){
                ret += val.faculty
            }else{
                 ret += val.faculty
            }
        })
        return ret;
    }

    const clear = ()=>{
        form.date_to = ""
    }

    const typeofpapers = ref([])
    const departments = ref([])
    const ListTypeofPapers = ()=>{
        axios.get('/api/list-topapers').then((res)=>{
            typeofpapers.value = res.data
        })
    }
    const deplist = ref([])
    const ListofDepartment = ()=>{
        axios.get('/api/list-departments').then((res)=>{
            departments.value = res.data
            deplist.value = res.data
        })
    }

    const selectDept = (id)=>{
        if(id != 0){
            deplist.value =  departments.value.filter(a=>a.id == id)
            return
        }
        ListofDepartment()
    }

    const extractPublished = (id)=>{
        let ret = 0
        published.value.forEach(val => {
            if(val.department_id == id){
                ret = val.published
            }
        });

        return ret;
    }

    const extractArchived = (id)=>{
        let ret = 0
        archived.value.forEach(val => {
            if(val.department_id == id){
                ret = val.archived
            }
        });

        return ret;
    }

    const extractFaculty = (id)=>{
        let ret = 0
        faculty.value.forEach(val => {
            if(val.department_id == id){
                ret = val.faculty
            }
        });

        return ret;
    }

</script>


<template>
    <div class="container">
        <div class="row bg-white rounded mt-3 d-print-none">
           <h4  class="text-start mt-3">Summary Report</h4>
           <div class="col-md-3">
               <div class="card border">
                   <div class="card-header text-start">Date Range</div>
                   <div class="card-body text-start">

                       <div class="form-group input-group-sm mb-3">
                           <label>Reports</label>
                           <select class="form-select" v-model="filter.report" @change="filterReport(filter.report)">
                               <option value="1">ALL DOCUMENTS</option>
                               <option value="2">PUBLISHED</option>
                               <option value="3">ARCHIVED</option>
                               <option value="4">USERS</option>
                        </select>
                       </div>
                       <div class="form-group input-group-sm mb-3">
                            <label >College Department</label>
                            <select class="form-select" v-model="filter.department" @change="selectDept(filter.department)">
                                <option value="0">ALL DEPARTMENT</option>
                                <option v-for="(list, index) in departments" :key="index" :value="list.id">{{ list.description }}</option>
                            </select>
                        </div>

                         <div class="form-group input-group-sm mb-3" v-if="filter.report != 4">
                            <label>Type of Papers</label>
                            <select class="form-select" v-model="filter.type_of_paper">
                                     <option value="0">ALL TYPE OF PAPERS</option>
                                    <option class="option" v-for="(list, index) in typeofpapers" :key="index"
                                    
                                    :value="list.id">
                                        {{ list.description }}
                                    </option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>From :</label>
                            <VueDatePicker v-model="filter.date_from"
                            @update:model-value="clear()"
                            :format="format" placeholder="Enter Date From"></VueDatePicker>
            
                        </div>
                         <!-- @update:model-value="generate()" -->
                         <div class="form-group mb-3">
                            <label>To :</label>
                            <VueDatePicker v-model="filter.date_to"
                           
                             :format="format" placeholder="Enter Date To"></VueDatePicker>
            
                        </div>
                        <div class="btn-group">
                            <button type="button" @click="generate()" class="btn btn-success btn-sm">
                                <i class="bi bi-bullseye"></i>
                                {{ btngenerate }}
                            </button>
                            <button type="button" @click="print()" class="btn btn-warning btn-sm">
                                <i class="bi bi-printer"></i>
                                Print
                            </button>

                        </div>
                   </div>
               </div>
           </div>

            <div class="col-md-9 text-start">
                <div class="table-responsive text-start">
                <h4>{{ reptitle }}</h4>
                <div class="fw-bold">
                    Date:
                    <span class="text-danger" v-if="filter.date_to != '' && filter.date_from"> {{ formatDate(filter.date_from) }} - {{ formatDate(filter.date_to) }} </span>
                </div>
                <table class="table table-bordered" v-if="showrep == 1">
                    <thead>
                        <tr>
                            <th>DEPARTMENT</th>
                            <th>NO. OF PUBLISHED</th>
                            <th>NO. OF ARCHIVED</th>
                            <th>NO. OF FACULTY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list, index) in deplist" :key="index">
                            <td>{{ list.description }}</td>
                            <td>{{ parseFloat(extractPublished(list.id)).toFixed(2) }}</td>
                            <td>{{ parseFloat(extractArchived(list.id)).toFixed(2) }}</td>
                            <td>{{ parseFloat(extractFaculty(list.id)).toFixed(2) }}</td>
                        </tr>
                        <tr class="fw-bold">
                            <td class="text-start fw-bold">TOTAL</td>
                            <td>{{ parseFloat(totalPublished()).toFixed(2) }}</td>
                            <td>{{ parseFloat(totalArchived()).toFixed(2) }}</td>
                            <td>{{ parseFloat(totalFaculty()).toFixed(2) }}</td>
                        </tr>
                    </tbody>
                </table>
                <!-- PUBLISHED -->
                <table class="table table-bordered" v-if="showrep == 2">
                    <thead>
                        <tr>
                            <th>TITLE</th>
                            <th>TYPE OF PAPER</th>
                            <th>AUTHOR</th>
                            <th>DATE UPLOADED</th>
                            <th>DEPARTMENT</th>
                            <th>UPLOADED BY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list, index) in list_published" :key="index">
                            <td>{{ list.title }}</td>
                            <td>{{ list.typeofpaper != null ? list.typeofpaper.description :'' }}</td>
                            <td>
                                <div class="d-flex justify-content-start" v-for="(lst, idx) in list.authors" :key="idx">
                                    {{lst.first_name }}
                                    {{lst.middle_name }}
                                    {{lst.last_name }}
                                </div>
                            </td>
                            <td>{{ formatDate(list.created_at) }}</td>
                            <td>{{ list.department != null ? list.department.description :'' }}</td>

                            <td>{{ list.userdoc != null ? 
                                list.userdoc.first_name+' '+list.userdoc.middle_initial+' '+list.userdoc.last_name
                                :'' }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-start">{{ list_published.length }} item/s</td>
                        </tr>
                    </tbody>
                </table>
                <!-- ARCHIVED -->
                <table class="table table-bordered" v-if="showrep == 3">
                    <thead>
                        <tr>
                            <th>TITLE</th>
                            <th>TYPE OF PAPER</th>
                            <th>AUTHOR</th>
                            <th>DATE UPLOADED</th>
                            <th>DEPARTMENT</th>
                            <th>UPLOADED BY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list, index) in list_archived" :key="index">
                            <td>{{ list.title }}</td>
                            <td>{{ list.typeofpaper != null ? list.typeofpaper.description :'' }}</td>
                            <td>
                                <div class="d-flex" v-for="(lst, idx) in list.authors" :key="idx">
                                    {{lst.first_name }}
                                    {{lst.middle_name }}
                                    {{lst.last_name }}
                                </div>
                            </td>
                            <td>{{ formatDate(list.created_at) }}</td>
                            <td>{{ list.department != null ? list.department.description :'' }}</td>

                            <td>{{ list.userdoc != null ? 
                                list.userdoc.first_name+' '+list.userdoc.middle_initial+' '+list.userdoc.last_name
                                :'' }}</td>
                        </tr>
                         <tr>
                            <td colspan="6" class="text-start">{{ list_archived.length }} item/s</td>
                        </tr>
                    </tbody>
                </table>

                <!-- USERS -->
                <table class="table table-bordered" v-if="showrep == 4">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>GMAIL</th>
                            <th>DEPARTMENT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list, index) in users" :key="index">
                            <td>
                                {{ list.first_name }} 
                                {{ list.middle_initial}}
                                {{ list.last_name}}
                            </td>
                            <td>{{ list.email }}</td>
                            <td>{{ list.department != null ? list.department.description : '' }}</td>
                        </tr>
                         <tr>
                            <td colspan="6" class="text-start">{{ users.length }} item/s</td>
                        </tr>
                    </tbody>
                </table>

            </div>
               
           </div>

        </div>

        <div class="row bg-white rounded mt-3 d-print-block d-none">
            <div class="col-md-12 d-flex justify-content-between">
                <div class="logo">
                    <img class="img-logo" :src="'/img/ndmc.png'"/>
                </div>
                <div class="text-head">
                    RECHIVA | NDMC Research and Department Center
                    <p class="sub-text">Quezon Avenue, Midsayap, Cotabato 9410</p>
                </div>
                <div class="logo">
                    <img class="img-logo-2" :src="'/img/logo_.png'"/>
                </div>
            </div>
            <div class="col-md-12">
                <div class="report-card">
                   <div class="fs-6 fw-bold">{{ reptitle }}</div>
                    <div class="fw-bold">
                       Date:
                        <span class="text-danger" v-if="filter.date_to != '' && filter.date_from"> {{ formatDate(filter.date_from) }} - {{ formatDate(filter.date_to) }} </span>
                   </div>
                  
                    <table class="table table-bordered text-start" v-if="showrep == 1">
                    <thead>
                        <tr>
                            <th>DEPARTMENT</th>
                            <th>NO. OF PUBLISHED</th>
                            <th>NO. OF ARCHIVED</th>
                            <th>NO. OF FACULTY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list, index) in deplist" :key="index">
                            <td>{{ list.description }}</td>
                            <td>{{ parseFloat(extractPublished(list.id)).toFixed(2) }}</td>
                            <td>{{ parseFloat(extractArchived(list.id)).toFixed(2) }}</td>
                            <td>{{ parseFloat(extractFaculty(list.id)).toFixed(2) }}</td>
                        </tr>
                        <tr class="fw-bold">
                            <td class="text-start fw-bold">TOTAL</td>
                            <td>{{ parseFloat(totalPublished()).toFixed(2) }}</td>
                            <td>{{ parseFloat(totalArchived()).toFixed(2) }}</td>
                            <td>{{ parseFloat(totalFaculty()).toFixed(2) }}</td>
                        </tr>
                    </tbody>
                </table>
                <!-- PUBLISHED -->
                <table class="table table-bordered text-start" v-if="showrep == 2">
                    <thead>
                        <tr>
                            <th>TITLE</th>
                            <th>TYPE OF PAPER</th>
                            <th>AUTHOR</th>
                            <th>DATE UPLOADED</th>
                            <th>DEPARTMENT</th>
                            <th>UPLOADED BY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list, index) in list_published" :key="index">
                            <td>{{ list.title }}</td>
                            <td>{{ list.typeofpaper != null ? list.typeofpaper.description :'' }}</td>
                            <td>
                                <div class="d-flex" v-for="(lst, idx) in list.authors" :key="idx">
                                    {{lst.first_name }}
                                    {{lst.middle_name }}
                                    {{lst.last_name }}
                                </div>
                            </td>
                            <td>{{ formatDate(list.created_at) }}</td>
                            <td>{{ list.department != null ? list.department.description :'' }}</td>

                            <td>{{ list.userdoc != null ? 
                                list.userdoc.first_name+' '+list.userdoc.middle_initial+' '+list.userdoc.last_name
                                :'' }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-start">{{ list_published.length }} item/s</td>
                        </tr>
                    </tbody>
                </table>
                <!-- ARCHIVED -->
                <table class="table table-bordered text-start" v-if="showrep == 3">
                    <thead>
                        <tr>
                            <th>TITLE</th>
                            <th>TYPE OF PAPER</th>
                            <th>AUTHOR</th>
                            <th>DATE UPLOADED</th>
                            <th>DEPARTMENT</th>
                            <th>UPLOADED BY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list, index) in list_archived" :key="index">
                            <td>{{ list.title }}</td>
                            <td>{{ list.typeofpaper != null ? list.typeofpaper.description :'' }}</td>
                            <td>
                                <div class="d-flex" v-for="(lst, idx) in list.authors" :key="idx">
                                    {{lst.first_name }}
                                    {{lst.middle_name }}
                                    {{lst.last_name }}
                                </div>
                            </td>
                            <td>{{ formatDate(list.created_at) }}</td>
                            <td>{{ list.department != null ? list.department.description :'' }}</td>

                            <td>{{ list.userdoc != null ? 
                                list.userdoc.first_name+' '+list.userdoc.middle_initial+' '+list.userdoc.last_name
                                :'' }}</td>
                        </tr>
                         <tr>
                            <td colspan="6" class="text-start">{{ list_archived.length }} item/s</td>
                        </tr>
                    </tbody>
                </table>

                <!-- USERS -->
                <table class="table table-bordered text-start" v-if="showrep == 4">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>GMAIL</th>
                            <th>DEPARTMENT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list, index) in users" :key="index">
                            <td>
                                {{ list.first_name }} 
                                {{ list.middle_initial}}
                                {{ list.last_name}}
                            </td>
                            <td>{{ list.email }}</td>
                            <td>{{ list.department != null ? list.department.description : '' }}</td>
                        </tr>
                         <tr>
                            <td colspan="6" class="text-start">{{ users.length }} item/s</td>
                        </tr>
                    </tbody>
                </table>


                   <div class="text-start">
                        Printed date: {{ format(new Date())}}
                   </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    .img-logo{
        width: 4rem;
    }
     .img-logo-2{
        width: 6rem;
    }

    .text-head{
        font-size: 16px;
        font-weight: bold;
        .sub-text{
            font-size: 14px;
            font-weight:normal; 
        }
    }
</style>
