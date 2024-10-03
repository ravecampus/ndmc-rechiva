<script setup>
	import { reactive, ref, onMounted, watch } from "vue"
	import dayjs from 'dayjs'

	let listData = ref([])
	const input = ref(null)

	let searchData = ref('')

	let links = ref([])
	const fdata = () => ({
		first_name:"", 
		middle_name:"", 
		last_name:"", 
		id:null
		})
	const form = reactive(fdata())
	const resetform = () => Object.assign(form, fdata())
	const editMode = ref(false)

	const formatDate = (dateString)=>{
		const date = dayjs(dateString)
		return date.format('MMMM D, YYYY | hh:mm a')
	}

	onMounted(async()=>{
		getListData()

	})

	watch(searchData, ()=>{
		getListData()
	})

	const changePage = (link)=>{
        if(!link.url || link.active){
            return
        }
        axios.get(link.url).then((res)=>{
            listData.value = res.data.data
            links.value = res.data.linkslinks.value = res.data.links
        })
    }


	const addForm = ()=>{
		btnsave.value = "saving..."
		axios.post('/api/setting-author', form).then((res)=>{
			toast.fire({
				icon:'success',
				title:'Description Added Successfully!'
			})
			errors.value = []
			btnsave.value = "save"
			getListData()
			resetform()
		}).catch((err)=>{
			errors.value = err.response.data.errors
			btnsave.value = "save"
		})
	}

	const updateForm = ()=>{
		btnsave.value = "updating..."
		axios.put(`/api/setting-author/${form.id}`, form).then((res)=>{
			getListData()
			toast.fire({
				icon:'success',
				title:'Description Updated Successfully!'
			})
			errors.value = []
			btnsave.value = "save"
			editMode.value = false
			resetform()
		}).catch((err)=>{
			errors.value = err.response.data.errors
			btnsave.value = "update"
		})
	}


	const getListData = async()=>{
		let data = await axios.get('/api/setting-author?search='+searchData.value).then((res)=>{
			listData.value = res.data.data
			links.value = res.data.links
		})
	}
	
	const submitData = ()=>{
		if(editMode.value === true){
			updateForm()
		}else{
			addForm()
		}
	}

	const editData = (data)=>{
		input.value.focus()
		btnsave.value = "update"
		editMode.value = true
		axios.get(`/api/setting-author/${data.id}`).then((res)=>{
			form.first_name = res.data.first_name
			form.middle_name = res.data.middle_name
			form.last_name = res.data.last_name
			form.id = res.data.id

		})
	}

	const clearForm = () =>{
		btnsave.value = "save";
		resetform()
		editMode.value = false
		errors.value = ""
	}

	let errors = ref([])

	const btnsave = ref("Save")

	const deleteData = (data) =>{
        Swal.fire({
            title: "Are you sure? "+data.description,
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#26884b",
            cancelButtonColor: "#ffc107",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/api/setting-author/${data.id}`).then((res)=>{
                    Swal.fire({
                        title: "Deleted!",
                        text: "Description has been deleted.",
                        icon: "success",
						confirmButtonColor: "#26884b",
                    });
                    getListData();
                })
                
            }
        });
	}
	const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }
</script>

<template>
    <div class="container text-start">
        <h2> <i class="fa fa-cogs"></i> Authors</h2>
        <div class="row">
            <div class="col-md-4 pt-4 mb-2">
                <div class="form-group">
                    <label>First name</label>
                    <input type="text" ref="input" v-model="form.first_name" class="form-control form-control form-control-sm" placeholder="Enter First name">
                    <span class="text-danger" v-if="errors.first_name">{{errors.first_name[0]}}</span>
                </div>
				<div class="form-group">
                    <label>Middle name</label>
                    <input type="text" v-model="form.middle_name" class="form-control form-control form-control-sm" placeholder="Enter Middle name">
                    <span class="text-danger" v-if="errors.middle_name">{{errors.middle_name[0]}}</span>
                </div>
				<div class="form-group mb-3">
                    <label>Last name</label>
                    <input type="text" v-model="form.last_name" class="form-control form-control form-control-sm" placeholder="Enter Last name">
                    <span class="text-danger" v-if="errors.last_name">{{errors.last_name[0]}}</span>
                </div>
				<div class="btn-group btn-sm">
					<button type="button" @click="submitData" class="btn btn-primary btn-sm">{{ btnsave }}</button>
					<button type="button" @click="clearForm" class="btn btn-warning text-white btn-sm">Clear</button>
				</div>

            </div>
            <div class="col-md-8">
				<div class="card custom-card">
				<div class="card-body">

					<div class="input-group input-group-sm mb-3">
						<input type="text" class="form-control" placeholder="Search..." aria-label="Example text with button addon" v-model="searchData" aria-describedby="button-addon1">
						<button class="btn btn-primary" type="button" id="button-addon1">
							<i class="fa fa-search"></i>
						</button>
					</div>
                <div class="table-responsive mt-3">
					<table class="table table-striped table-hover table-sm mb-0">
						<thead>
							<tr class="text-success">
								<th>Description <i class="bx bx-up-arrow-alt ms-2"></i>
								</th>
								<th>Last Modified</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(item, index) in listData" :key="index">
								<td>
									<div class="d-flex align-items-center">
										<div class="font-weight-bold text-primary">
											<strong>
											{{ item.first_name }} {{ item.middle_name }} {{ item.last_name }}
											</strong>
										</div>
									</div>
								</td>
								<td>{{ formatDate(item.updated_at) }}</td>
								<td>
									<div class="input-group">
									<a href="#" @click="editData(item)" class="btn btn-outline-success btn-sm">
										<i class="fa fa-edit"></i>
									</a>
									<a href="#" @click="deleteData(item)" class="btn btn-outline-success btn-sm">
										<i class="fa fa-trash"></i>
									</a>
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
				</div>

				<nav class="mt-2" aria-label="..." v-if="noData(listData)">
					<ul class="pagination pagination-sm custom-page">
						<li class="page-item" aria-current="page" v-for="(link, index) in links" :key="index">
							<a class="page-link text-sm"
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
