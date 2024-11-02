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

    const user =  ref({})
    
    onMounted(() => {
        ListofDepartment()
        ListTypeofPapers()
        user.value = window.winsdev.user
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
                         (data == 4) ? "USERS" :
                         (data == 5) ? "RECHIVA SYSTEM STATISTICS REPORT" : ""
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

    const mosdown = ref([])
    let dataset = {}
    let pieset = null
    let label = []
    let label2 = []
    let datastat = []
    let datastat2 = []
    let data2 = {}
    const genstats = ()=>{
         label = []
         datastat = []
         axios.get('/api/statistic-report',{params:filter}).then((res)=>{
            let data = res.data.downloadstat
            data2 = res.data.statistic
            mosdown.value = data
            data.forEach(val => {
                label.push(val.title.substring(0, 20) + "...")
                datastat.push(val.download)
            });

            dataset = {
                labels:label,
                datasets: [ { 
                    label: 'Most Download Documents',
                    backgroundColor: '#ffc107',
                    data:datastat
                    },      
                    ]
            }

            pieset = {
                labels: ['Visitors', 'Collections', 'Downloads', 'Members'],
                datasets: [
                    {
                    backgroundColor: ['#41B883', '#E46651', '#00D8FF', '#DD1B16'],
                    data: [data2.visitors, data2.collections, data2.downloads, data2.members]
                    }
                ]
            }

        })
    }

</script>

<script>
   import { Bar, Doughnut } from 'vue-chartjs'
   import { Chart as
        ChartJS,
        CategoryScale,
        LinearScale,
        PointElement,
        LineElement,
        Title,
        Tooltip,
        Legend,
        ArcElement,
        BarElement,
 
  } from 'chart.js'
    ChartJS.register(
         CategoryScale,
        LinearScale,
        PointElement,
        LineElement,
        Title,
        Tooltip,
        Legend,
        ArcElement,
        BarElement,
        )
        export default {
            components: {
                Bar, Doughnut
            },
            data(){
                return{
                     chartOptions: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                }
            }
        }
</script>



<template>
    <div class="container">
        <div class="row d-print-block d-none mt-2">
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
        </div>
        <div class="row bg-white rounded mt-3">
       
           <h4  class="text-start mt-3 d-print-none">Summary Report</h4>
           <div class="col-lg-3 d-print-none">
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
                               <option value="5">STATISTICS REPORT</option>
                        </select>
                       </div>
                       <div class="form-group input-group-sm mb-3" v-if="filter.report != 5">
                            <label >College Department</label>
                            <select class="form-select" v-model="filter.department" @change="selectDept(filter.department)">
                                <option value="0">ALL DEPARTMENT</option>
                                <option v-for="(list, index) in departments" :key="index" :value="list.id">{{ list.description }}</option>
                            </select>
                        </div>

                         <div class="form-group input-group-sm mb-3" v-if="filter.report != 4 && filter.report != 5">
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
                            <button type="button" v-if="filter.report != 5" @click="generate()" class="btn btn-success btn-sm">
                                <i class="bi bi-bullseye"></i>
                                {{ btngenerate }}
                            </button>
                            <button type="button" v-if="filter.report == 5" @click="genstats()" class="btn btn-success btn-sm">
                                <i class="bi bi-bullseye"></i>
                               Generate stats
                            </button>
                            <button type="button" @click="print()" class="btn btn-warning btn-sm">
                                <i class="bi bi-printer"></i>
                                Print
                            </button>

                        </div>
                   </div>
               </div>
           </div>

            <div class="col-md-12 col-lg-9 text-start">
               
                <div class="text-start">
                <h4>{{ reptitle }}</h4>
                <div class="fw-bold">
                    Date:
                    <span class="text-danger" v-if="filter.date_to != '' && filter.date_from"> {{ formatDate(filter.date_from) }} - {{ formatDate(filter.date_to) }} </span>
                </div>
                <div class="row" v-if="showrep == 5">
                    <div class="col-md-12 mt-2 text-center mb-4" v-if="pieset != null">
                        <div></div>
                      <table class="table table-bordered">
                          <thead>
                              <tr>
                                  <th>NUMBER OF VISITORS</th>
                                  <th>NUMBER OF COLLECTIONS</th>
                                  <th>NUMBER OF DOWNLOADS</th>
                                  <th>NUMBER OF MEMBERS</th>
                                  
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                    <td>{{ data2.visitors }}</td>
                                    <td>{{ data2.collections }}</td>
                                    <td>{{ data2.downloads }}</td>
                                    <td>{{ data2.members }}</td>
                              </tr>
                          </tbody>
                      </table>

                            <!-- <Doughnut
                            :options="chartOptions"
                            :data="pieset" ></Doughnut> -->
                     
                    </div>
                      <!-- <div class=""> -->

                        <!-- <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                    
                                    </tr>
                                </tbody>
                            </table> -->
                            <!-- </div> -->
                     <hr>
                    <div class="col-md-12 mt-1 h-25 text-center mb-5" v-if="mosdown.length > 0">
                      <div>MOST DOWNLOAD DOCUMENTS</div>
                      <table class="table table-bordered text-start">
                          <thead>
                              <tr>
                                  <th>TITLE</th>
                                  <th>NO. OF DOWNLOADS</th>
                                  
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="(list, index) in mosdown" :key="index">
                                  <td>{{ list.title }}</td>
                                  <td>{{ list.download }}</td>
                              </tr>
                          </tbody>
                      </table>
                             <!-- <Bar  
                                :class="'col-md-12'"
                                :options="chartOptions"
                                :data="dataset"
                                ></Bar> -->
                     
                    </div>
                   
                    
                    
                    
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
                            <td colspan="5" class="text-start fw-bold">TOTAL PUBLISHED</td>
                            <td  class="fw-bold">{{ list_published != undefined ? list_published.length : 0}}</td>
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
                           <td colspan="5" class="text-start fw-bold">TOTAL ARCHIVED</td>  
                           <td class="fw-bold">{{ list_archived != undefined ? list_archived.length : 0}} </td> 
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
                            <td colspan="2" class="text-start fw-bold">TOTAL USERS</td>
                            <td class="fw-bold">{{ users != undefined ? users.length : 0}}</td>
                        </tr>
                    </tbody>
                </table>


            </div>
    
            <div class="text-start mt-4 fw-bold d-print-block d-none">
                Prepared by: <span class="f-sig text-uppercase">
                    {{ user.first_name }} {{ user.middle_initial }} {{ user.last_name }}
                </span>
            </div>
            <div class="text-start mt-1 fw-bold d-print-block d-none">
                Printed date: <span class="fc">{{ format(new Date())}}</span>
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

    .text-start{
        color:#000;
    }

    .fc{
        font-style: italic;
        font-weight: 500;
    }
    .f-sig{
        text-decoration: underline;
        text-decoration-thickness: 2px;
    }
@media print {
    body{
      align-content: flex-start;         
    }
}
</style>
