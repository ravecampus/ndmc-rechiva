<script setup>
    import { ref, reactive, onMounted } from "vue"
    import { useRouter } from "vue-router"


    const router = useRouter()
    const btncap = ref("Submit")
    const departments = ref([])
    let errors = ref([])

    const form = reactive({
        email:"",
        password:"",
        password_confirmation:"",
        first_name:"",
        middle_initial:"",
        last_name:"",
        college_department:"",
    })

onMounted(()=>{
    ListofDepartment()
})

    const signupRequest = ()=>{
            btncap.value ="Submitting..."
        axios.post('/api/auth', form).then((res)=>{
            toast.fire({
				icon:'success',
				title:'Sign up Sucess!'
			})
            btncap.value ="Submit"
            router.push({name:'signin'})
            errors.value = []
        }).catch((err)=>{
            btncap.value ="Submit"
            if(err.response.status == 422){
                errors.value = err.response.data.errors
            }
        })
    }

    const ListofDepartment = ()=>{
        axios.get('/api/list-departments').then((res)=>{
            departments.value = res.data
        })
    }


</script>

<template>
<div class="container mt-5 text-start min-h">
    <div class="rowc d-flex justify-content-center">
    <div class="col-8">
     <div class="card login">
         <div class="card-header header-login d-flex justify-content-center p-0">
             <img class="img-login" :src="'/img/rechiva.png'">
         </div>
         <div class="card-body">
                <!-- <div class="login-title p-0">
                    <p class="p-0 m-0">Notre Dame of Midsayap College</p>
                    <h2>RECHIVA</h2>

                </div> -->
                 <h2>SIGN-UP</h2>
                <div class="login-body mt-4">
                <div class="alert alert-danger" v-if="errors.errs"> {{ errors.errs[0]}}

                </div>
                <!-- <form> -->
                <div class="row mb-3">
                    <div class="form-group col-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-type"></i>
                            </span>
                            <input type="text" v-model="form.first_name" class="form-control" placeholder="Enter First name">
                        </div>
                        <span class="text-danger" v-if="errors.first_name">{{ errors.first_name[0]}}</span>
                    </div>

                      <div class="form-group col-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-type"></i>
                            </span>
                            <input type="text" v-model="form.middle_initial" class="form-control" placeholder="Enter Middle Initial">
                        </div>
                        <span class="text-danger" v-if="errors.middle_initial">{{ errors.middle_initial[0]}}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="form-group col-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-type"></i>
                            </span>
                            <input type="text" v-model="form.last_name" class="form-control" placeholder="Enter Last name">
                        </div>
                        <span class="text-danger" v-if="errors.last_name">{{ errors.last_name[0]}}</span>
                    </div>
                      <div class="form-group col-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <!-- <i class="bi bi-type"></i> -->
                                Dept.
                            </span>
                            <select class="form-select" v-model="form.college_department" placeholder="Enter College Department">
                                <option v-for="(list, index) in departments" :key="index" :value="list.id">{{ list.description }}</option>
                            </select>
                        </div>
                        <span class="text-danger" v-if="errors.college_department">{{ errors.college_department[0]}}</span>
                    </div>
                </div>
              


                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope-at"></i>
                    </span>
                    <input type="email" v-model="form.email" class="form-control" placeholder="Enter Email">
                </div>
                <span class="text-danger" v-if="errors.email">{{ errors.email[0]}}</span>

                <div class="input-group mt-3">
                    <span class="input-group-text">
                         <i class="bi bi-braces-asterisk"></i>
                    </span>
                    <input type="password" v-model="form.password" class="form-control" placeholder="Enter Password">
                </div>
                <span  class="text-danger" v-if="errors.password">{{ errors.password[0]}}</span>

                <div class="input-group mt-3">
                    <span class="input-group-text">
                         <i class="bi bi-braces-asterisk"></i>
                    </span>
                    <input type="password" v-model="form.password_confirmation" class="form-control" placeholder="Enter Password Confirmation">
                </div>
                <span  class="text-danger" v-if="errors.password_confirmation">{{ errors.password_confirmation[0]}}</span>
                <div class="row mb-4">
                </div>

                <!-- Submit button -->
                <button type="button" @click.prevent="signupRequest" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">{{ btncap }}</button>
                </div>
                <!-- Register buttons -->
                <!-- <div class="text-center">
                    <p>Not a member? <a href="#!">Register</a></p>
                </div> -->
                <!-- </form> -->
            </div>
     </div>
    </div>
    </div>
</div>
</template>
<style lang="scss">
    .login{
        margin-top: 4rem;
        margin-bottom: 20px;
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
         border:#dfdfdf6b solid 1px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }
</style>

