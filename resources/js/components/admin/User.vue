<script setup>
    import { ref, onMounted, watch, reactive} from "vue"
    import dayjs from 'dayjs'
    import genstatus from "../GenStatus.vue"
    import { Modal } from "bootstrap";

    const listData = ref([])
    let links = ref([])
    let searchData = ref('')
    let filterData = ref('')
    let filterDept = ref('')
    let errors = ref([])

    const typeofpapers = ref([])

    const fdata = () => ({
        first_name:"", 
        middle_initial:"", 
        last_name:"", 
        email:"", 
        department:"", 
        role:null, 
        password:"", 
        password_confirmation:"", 
        id:null
        })
	const form = reactive(fdata())
	const resetform = () => Object.assign(form, fdata())

   
    const modalEle = ref(null)
    let thisModalObj = null;

    const inform = ref(null)
    let modalinfo = null;

    const resetPass = ref(null)
    let modalresetpass = null;

    onMounted(()=>{
        getData()
        getTypeOfPapers()
        ListofDepartment()
        admicontact();
        thisModalObj = new Modal(modalEle.value);
        modalinfo = new Modal(inform.value);
        modalresetpass = new Modal(resetPass.value);
    })

    const formatDate = (dateString)=>{
		const date = dayjs(dateString)
		return date.format('MMMM D, YYYY | hh:mm a')
	}

    const getData = ()=>{
        axios.get('/api/users?search='+searchData.value+'&filter='+filterData.value+'&dept='+filterDept.value).then((res)=>{
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

    const actionFilter = ()=>{
        getData();
    }

    const extractDep = (data)=>{
        if(data != undefined){
            return data.description;
        }
    }
    
    const activateUser = (data)=>{
        Swal.fire({
            title:"User Activation",
            text: data.activate == 0 ? ' Do you want to Activate' : 'Do you want to Deactivate' +" this account!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#26884b",
            cancelButtonColor: "#ffc107",
            confirmButtonText: "Yes"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.get(`/api/users/${data.id}`).then((res)=>{
                    Swal.fire({
                        title: data.activate == 0? 'Activated':'Deactivated',
                        text: data.activate == 0? "User has been activate":"User has been deactivate",
                        icon: "success",
						confirmButtonColor: "#26884b",
                    });
                    getData();
                })
                
            }
        });
    }

    const addUser = ()=>{
         thisModalObj.show()
    }
    const depview = ref(true)
    const adminSelect = (data)=>{
        if(data == 2){
            depview.value = false
        }else{
            depview.value = true
        }
    }

    const adduserDB = ()=>{
        axios.post('/api/users', form).then((res)=>{
            toast.fire({
				icon:'success',
				title:'User added Successfully!'
            })
            thisModalObj.hide()
            errors.value = []
            resetform()
            getData()
        }).catch((err)=>{
            errors.value = err.response.data.errors
        })
    }
    const info = reactive({
        contact_number:'',
        information:'',
    })
    const admicontact = ()=>{
        axios.get('/api/admin-contact').then((res)=>{
            const data = res.data;
            info.contact_number = data.contact_number
            info.information = data.information
        })
    }

    const admicontactsave = ()=>{
        axios.post('/api/admin-contact',info).then((res)=>{
             toast.fire({
				icon:'success',
				title:'Admin contact information has been modified!'
            })
            modalinfo.hide()
            admicontact()
        })
    }

    const editInfo =()=>{
        modalinfo.show();
    }

    const resetPassword = (data)=>{
        form.first_name = data.first_name
        form.middle_initial = data.middle_initial
        form.last_name = data.last_name
        form.id = data.id
        modalresetpass.show();
    }

    const changePassword = ()=>{
        btnpass.value = "Saving..."
        axios.put('/api/change-password/'+form.id, form).then((res)=>{
            toast.fire({
				icon:'success',
				title:"User's password has been changed!"
            })
            btnpass.value = "Save Changes"
            modalresetpass.hide()
            errors.value = []
            resetform()
            getData()
        }).catch((err)=>{
            btnpass.value = "Save Changes"
            errors.value = err.response.data.errors
        })
    }

    const btnpass = ref("Save Changes")

</script>


<template>
  <div class="container mt-2">
    <div class="row">
        <div class="col-md-12 text-start">
            <h4 class="m-0">Users</h4>
            <p>{{ formatDate(new Date()) }}</p>
            <hr class="m-0">
        </div>
        <div class="col-md-12 mb-3">
            <div class="contact-information w-100">
                <div class="d-flex">
                    <label>Contact #:</label>
                    <div class="fw-bold">&nbsp; {{ info.contact_number}} </div>
                     <a href="#" @click="editInfo()" class="text-end ms-5">
                        <i class="bi bi-pencil-square"></i>
                        Edit
                    </a>
                </div>
                <div class="d-flex">
                    <label>Information:</label>
                    <div class="fw-bold text-start text-truncate w-50">&nbsp;
                        {{ info.information }} 
                    </div>
                </div>
               
            </div>
        </div>
        <div class="col-12 mb-3 mb-lg-5">
            <div class="overflow-hidden card table-nowrap table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">List</h5>
            
                    <div class=""> 
                        <button type="button" @click="addUser()" class="btn btn-outline-success btn-sm">
                            Add user
                        </button>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="input-group input-group-sm p-2">
                        <input type="text" class="form-control" placeholder="Search..." aria-label="Example text with button addon" v-model="searchData" aria-describedby="button-addon1">
                        <button class="btn btn-primary" type="button" id="button-addon1">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                     <div class="input-group input-group-sm p-2">
                        <select class="form-select" id="inputGroupSelect02" v-model="filterDept" @change="actionFilter">
                            <option :value="''">Choose...</option>
                            <option v-for="(list, index) in departments" :key="index" :value="list.id">{{ list.description }}</option>
                        </select>
                        <label class="input-group-text bg-select"  for="inputGroupSelect02">Department</label>
                    </div>
                    <div class="input-group input-group-sm p-2">
                        <select class="form-select" id="inputGroupSelect02" v-model="filterData" @change="actionFilter">
                            <option :value="''">Choose...</option>
                            <option :value="2">Admin</option>
                            <option :value="1">Faculty</option>
                        </select>
                        <label class="input-group-text bg-select"  for="inputGroupSelect02">Role</label>
                    </div>
                </div>
                <div class="table-responsive">
                    
                    <table class="table mb-0">
                        <thead class="small text-uppercase bg-body text-muted">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Role</th>
                                <th>Status</th>
                                <!-- <th>Date</th> -->
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="align-middle" v-for="(list,index) in listData" :key="index">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-bounding-box me-2 icon-file"></i>
                                       <strong class="text-primary"> 
                                            {{ list.first_name }}
                                            {{ list.middle_initial }}
                                            {{ list.last_name }}
                                           
                                         </strong>
                                    </div>
                                </td>
                                <td>{{ list.email }}</td>
                                <td>{{ extractDep(list.department) }}</td>
                                <td>{{ list.role == 1 ? 'Faculty' :'Admin'}}</td>
                                <td>{{ list.activate == 0 ? 'inactive' : 'activated' }}</td>
                              
                                <!-- <td>{{ formatDate(list.created_at) }}</td> -->
                               
                                <!-- <td></td> -->
                                <td class="text-end">
                                    <div class="drodown">
                                        <a data-bs-toggle="dropdown" href="#" class="btn p-1" aria-expanded="false" v-if="list.role == 1">
                                            <i class="fa fa-bars" aria-hidden="true"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dd-bg" >
                                            <a href="#" class="dropdown-item dd-item" @click="activateUser(list)">{{ list.activate == 0 && list.role == 1 ?'Activate':'Deactivate' }}</a>
                                            <a href="#" class="dropdown-item dd-item" @click="resetPassword(list)">Reset Password</a>
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
        <!-- Modal -->
    <div class="modal fade" id="userActivate" ref="modalEle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start mb-3 row">
                <div class="form-group col-6">
                    <label>First name</label>
                    <input type="text" ref="input" v-model="form.first_name" class="form-control form-control form-control-sm" placeholder="Enter First name">
                    <span class="text-danger" v-if="errors.first_name">{{errors.first_name[0]}}</span>
                </div>

                <div class="form-group col-6">
                    <label>Middle Initial</label>
                    <input type="text" ref="input" v-model="form.middle_initial" class="form-control form-control form-control-sm" placeholder="Enter Middle Initial">
                    <span class="text-danger" v-if="errors.middle_initial">{{errors.middle_initial[0]}}</span>
                </div>
                <div class="form-group col-6">
                    <label>Last name</label>
                    <input type="text" ref="input" v-model="form.last_name" class="form-control form-control form-control-sm" placeholder="Enter Last name">
                    <span class="text-danger" v-if="errors.last_name">{{errors.last_name[0]}}</span>
                </div>
                 <div class="form-group col-6">
                    <label>Role</label>
                    <select class="form-select form-select-sm" v-model="form.role" @change="adminSelect(form.role)">
                        <option :value="''">Choose...</option>
                        <option :value="2">Admin</option>
                        <option :value="1">Faculty</option>
                    </select>
                    <span class="text-danger" v-if="errors.role">{{errors.role[0]}}</span>
                </div>
                <div class="form-group col-6" v-if="depview">
                    <label>Department</label>
                    <select class="form-select form-select-sm" v-model="form.department">
                        <option :value="''">Choose...</option>
                        <option v-for="(list, index) in departments" :key="index" :value="list.id">{{ list.description }}</option>
                    </select>
                    <span class="text-danger" v-if="errors.department">{{errors.department[0]}}</span>
                </div>

                <div class="form-group col-12">
                    <label>Email</label>
                    <input type="text" ref="input" v-model="form.email" class="form-control form-control form-control-sm" placeholder="Enter Email">
                    <span class="text-danger" v-if="errors.email">{{errors.email[0]}}</span>
                </div>
                
                <div class="form-group col-6">
                    <label>Password</label>
                    <input type="password" ref="input" v-model="form.password" class="form-control form-control form-control-sm" placeholder="Enter Password">
                    <span class="text-danger" v-if="errors.password">{{errors.password[0]}}</span>
                </div>
                <div class="form-group col-6">
                    <label>Password Confirmation</label>
                    <input type="password" ref="input" v-model="form.password_confirmation" class="form-control form-control form-control-sm" placeholder="Enter Password Confirmation">
                    <span class="text-danger" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" @click.prevent="adduserDB()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

      <div class="modal fade" id="info" ref="inform" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start mb-3 row">
                <div class="form-group col-12">
                    <label>Contact Number</label>
                    <input type="text" ref="input" v-model="info.contact_number" class="form-control form-control form-control-sm" placeholder="Enter Contact Number">
                    <span class="text-danger" v-if="errors.contact_number">{{errors.contact_number[0]}}</span>
                </div>

                <div class="form-group col-12">
                    <label>Information</label>
                    <textarea ref="input" v-model="info.information" class="form-control form-control form-control-sm" placeholder="Enter Information"></textarea>
                    <span class="text-danger" v-if="errors.information">{{errors.information[0]}}</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" @click.prevent="admicontactsave()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="info" ref="resetPass" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start mb-3 row">
                <div class="fs-5">Please enter new Password</div>
                <div class="fw-bold"> {{ form.first_name }} {{ form.middle_initial}} {{ form.last_name }}</div>
                <div class="form-group col-12 mb-2">
                    <label>Password</label>
                    <input type="password" ref="input" v-model="form.password" class="form-control form-control form-control-sm" placeholder="Enter Password">
                    <span class="text-danger" v-if="errors.password">{{errors.password[0]}}</span>
                </div>
                <div class="form-group col-12">
                    <label>Password Confirmation</label>
                    <input type="password" ref="input" v-model="form.password_confirmation" class="form-control form-control form-control-sm" placeholder="Enter Password Confirmation">
                    <span class="text-danger" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" @click.prevent="changePassword()" class="btn btn-primary">{{ btnpass }}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
</div>
</template>
