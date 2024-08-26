<script setup>
    import { ref, reactive } from "vue"

    import { useRouter } from "vue-router"

    let errors = ref([])

    const router = useRouter()

    const form = reactive({
        email:"",
        password :""
    })

    const signupRequest = ()=>{
        axios.post('/api/auth', form).then((res)=>{
            router.push("/")
            errors.value = []
        }).catch((err)=>{
            if(err.response.status == 422){
                errors.value = err.response.data.errors
            }
        })
    }

</script>


<template>
    <div class="container">
        <div class="tab-content mt-5 col-md-4">
                <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
                    <form>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="email" v-model="form.email" id="loginName" class="form-control" />
                        <label class="form-label" for="loginName">Email</label>
                        <p v-if="errors.email">{{ errors.email[0]}}</p>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="password" v-model="form.password" id="loginPassword" class="form-control" />
                        <label class="form-label" for="loginPassword">Password</label>
                        <p v-if="errors.password">{{ errors.password[0]}}</p>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="password" v-model="form.password_confirmation" id="loginPassword" class="form-control" />
                        <label class="form-label" for="loginPassword">Password</label>
                        <p v-if="errors.password_confirmation">{{ errors.password_confirmation[0]}}</p>
                    </div>
                    <button type="button" @click="signupRequest" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Sign up</button>


                    </form>
                </div>

        </div>
    </div>
</template>
