<script setup>
    import { ref, reactive, onMounted } from "vue"
    import { useRouter } from "vue-router"


    const router = useRouter()
    const btncap = ref("Login")

    const form = reactive({
        email:"",
        password:""
    })

    let errors = ref([])

    const loginRequest = ()=>{
            btncap.value = "Logging..."
            axios.post("/api/auth/in", form)
            .then((res)=>{
                let user = res.data
                if(user.role === 1){
                      window.location.href="/faculty";
                }

                 if(user.role === 2){
                      window.location.href="/admin";
                }
                btncap.value = "Login"
              
                errors.value = []
            
            }).catch((err)=>{
                btncap.value = "Login"
                errors.value = err.response.data.errors
            })
    }

    onMounted(()=>{
        let user = window.winsdev.user
        if(window.winsdev.isLoggedin && user.role === 1){
            router.push("/faculty")
        }
         if(window.winsdev.isLoggedin && user.role === 2){
            router.push("/admin")
        }
    })


</script>

<template>
<div class="container mt-5 text-start min-h">
    <div class="rowc d-flex justify-content-center">
    <div class="col-4">
     <div class="card login mt-5">
         <div class="card-header header-login d-flex justify-content-center p-0">
             <img class="img-login" :src="'/img/rechiva.png'">
         </div>
         <div class="card-body">
                <!-- <div class="login-title p-0">
                    <p class="p-0 m-0">Notre Dame of Midsayap College</p>
                    <h2>RECHIVA</h2>

                </div> -->
                 <h2>SIGN-IN</h2>
                <div class="login-body mt-4">
                <div class="badge text-danger mb-2" v-if="errors.errs"> 
                    {{ errors.errs[0]}}

                </div>
                <!-- <form> -->
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope-at"></i>
                    </span>
                    <input type="email" @keyup.enter.prevent="loginRequest" v-model="form.email" class="form-control" placeholder="Enter Email">
                </div>
                <span class="text-danger"  v-if="errors.email">{{ errors.email[0]}}</span>

                <div class="input-group mt-3">
                    <span class="input-group-text">
                         <i class="bi bi-braces-asterisk"></i>
                    </span>
                    <input type="password" @keyup.enter.prevent="loginRequest" v-model="form.password" class="form-control" placeholder="Enter Password">
                </div>
                <span  class="text-danger" v-if="errors.password">{{ errors.password[0]}}</span>
                <div class="row mb-4">
                    <div class="col-md-6 d-flex justify-content-center">
                    </div>

                    <div class="col-md-6 d-flex justify-content-center">
                    </div>
                </div>

                <!-- Submit button -->
                <button type="button" @click.prevent="loginRequest" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">{{ btncap }}</button>
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
<style lang="scss" scope>
    .login{
        margin-bottom: 20px;
        margin-top: 4rem;
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
         border:#dfdfdf6b solid 1px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }
</style>

