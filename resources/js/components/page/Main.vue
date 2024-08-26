<script setup>
    import { ref, reactive, onMounted } from "vue"
    import { useRouter } from "vue-router"


    const router = useRouter()

    const form = reactive({
        email:"",
        password:""
    })

    let errors = ref([])

    const loginRequest = ()=>{

            axios.post("/api/auth/in", form)
            .then((res)=>{
                window.location.href="/";
                errors.value = []
            
            }).catch((err)=>{
                errors.value = err.response.data.errors
            })
    }

    onMounted(()=>{
        if(window.winsdev.isLoggedin){
            router.push("/document")
        }
    })
</script>

<template>
<div class="container mt-5 text-start">
    <div class="rowc d-flex justify-content-center">
    <div class="col-4">
     <div class="card login">
         <div class="card-header header-login d-flex justify-content-center p-0">
             <img class="img-login" :src="'/img/rechiva.png'">
         </div>
         <div class="card-body">
                <div class="login-title p-0">
                    <p class="p-0 m-0">Notre Dame of Midsayap College</p>
                    <h2>RECHIVA</h2>

                </div>
                <div class="login-body mt-2">
                <div class="alert alert-danger" v-if="errors.errs"> {{ errors.errs[0]}}

                </div>
                <!-- <form> -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <label class="form-label" for="loginName">Email</label>
                    <input type="email" v-model="form.email" id="loginName" class="form-control" />
                    
                    <span class="text-danger" v-if="errors.email">{{ errors.email[0]}}</span>
                </div>
                <div data-mdb-input-init class="form-outline mb-4">
                    <label class="form-label" for="loginPassword">Password</label>
                    <input type="password" v-model="form.password" id="loginPassword" class="form-control" />
                   
                    <span  class="text-danger" v-if="errors.password">{{ errors.password[0]}}</span>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6 d-flex justify-content-center">
                    <!-- Checkbox -->
                    <!-- <div class="form-check mb-3 mb-md-0">
                        <input class="form-check-input" type="checkbox" value="" id="loginCheck" checked />
                        <label class="form-check-label" for="loginCheck"> Remember me </label>
                    </div> -->
                    </div>

                    <div class="col-md-6 d-flex justify-content-center">
                    <!-- Simple link -->
                    <!-- <a href="#!">Forgot password?</a> -->
                    </div>
                </div>

                <!-- Submit button -->
                <button type="button" @click="loginRequest" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Sign in</button>
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

