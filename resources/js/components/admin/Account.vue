<script setup>
    import { ref, onMounted, reactive  } from "vue"
    import { useRouter  } from "vue-router"

    onMounted(()=>{
        getAuthUser()
    })

    const router = useRouter();

    const user = ref({})
    let errors = ref([])

    const fdata = () => ({
        first_name:"",
        middle_initial:"",
        last_name:"",
        email:"",
        id:null,
        password:"",
        password_confirmation:"",
         })
	const form = reactive(fdata())
	const resetform = () => Object.assign(form, fdata())

    const getAuthUser = ()=>{
        axios.get('/api/user').then((res)=>{
            user.value = res.data;
            let data = res.data
            form.first_name = data.first_name;
            form.middle_initial = data.middle_initial;
            form.last_name = data.last_name;
            form.email = data.email;
            form.id = data.id;
        })
    }

    const saveProfile = ()=>{
        axios.put('/api/users/'+form.id, form).then((res)=>{
            getAuthUser();
            toast.fire({
				icon:'success',
				title:'Profile has been modified!'
            })
            errors.value = []
        }).catch((err)=>{
            errors.value = err.response.data.errors
        })
    }

    const changePassword = ()=>{
        axios.put('/api/users-password/'+form.id, form).then((res)=>{
            getAuthUser();
            toast.fire({
				icon:'success',
				title:'Password has been changed!'
            })
            window.location.href = '/sign-in'
            // router.push({name:'rechiva'})
            errors.value = []
        }).catch((err)=>{
            errors.value = err.response.data.errors
        })
    }
</script>


<template>
<div class="container-fluid rounded bg-white mb-5 mt-2">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img class="rounded-circle mt-5" width="150px" :src="'/img/user.png'">
                <span class="font-weight-bold">
                    {{ user.first_name }}
                    {{ user.middle_initial }}.
                    {{ user.last_name }}
                </span>
                <span class="text-black-50">{{ user.email }}</span>
                <span> </span></div>
        </div>
        <div class="col-md-5 border-right text-start">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profile Settings</h4>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="labels">First name</label>
                        <input type="text" v-model="form.first_name" class="form-control" placeholder="Enter First name" value="">
                         <span class="text-danger" v-if="errors.first_name">{{errors.first_name[0]}}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="labels">Middle Initial</label>
                        <input type="text" v-model="form.middle_initial" class="form-control" value="" placeholder="Enter Middle initial">
                        <span class="text-danger" v-if="errors.middle_initial">{{errors.middle_initial[0]}}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="labels">Last name</label>
                        <input type="text" v-model="form.last_name" class="form-control" value="" placeholder="Enter Last name">
                        <span class="text-danger" v-if="errors.last_name">{{errors.last_name[0]}}</span>                    
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="labels">Email</label>
                        <input type="text" v-model="form.email" class="form-control" placeholder="Enter Email" value="">
                        <span class="text-danger" v-if="errors.email">{{errors.email[0]}}</span>

                    </div>
                </div>
                <div class="mt-5">
                    <button class="btn btn-primary profile-button" @click.prevent="saveProfile()" type="button">Save Profile</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience">
                    <h4>Change Password</h4>
                    <!-- <span class="border px-3 p-1 add-experience">
                        <i class="fa fa-plus"></i>
                        &nbsp;Experience</span> -->
                </div><br>

                <div class="col-md-12 text-start">
                    <label class="labels">Password</label>
                    <input type="Password" v-model="form.password" class="form-control" placeholder="Enter Password" value="">
                     <span class="text-danger" v-if="errors.password">{{errors.password[0]}}</span>                    
                </div> 
                <div class="col-md-12 text-start">
                    <label class="labels">Password Confirmation</label>
                    <input type="password" v-model="form.password_confirmation" class="form-control" placeholder="Enter Password Confirmation" value="">
                     <span class="text-danger" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>                    
                </div>
                 <div class="mt-5">
                    <button class="btn btn-primary profile-button" @click.prevent="changePassword()" type="button">Change Password</button>
                </div>
            </div>
        </div>
    </div>
</div>

</template>

