<script setup>
import { ref, onMounted } from "vue"
import { useRouter } from "vue-router"

const user = ref({})
const auth = ref(false)

const getUserAuth = ()=>{
    user.value = window.winsdev.user
}

const dashboard = ()=>{
      router.push("/faculty")
}
    
const router = useRouter()
onMounted(()=>{
    getUserAuth()
    visit()

    if(window.winsdev.isLoggedin){
        auth.value = true
    }else{
        auth.value = false
    }
    
    let user = window.winsdev.user
    // if(window.winsdev.isLoggedin && user.role === 1){
    //     router.push("/faculty")
    // }
    if(window.winsdev.isLoggedin && user.role === 2){
        router.push("/admin")
    }
 })

 const visit = ()=>{
    axios.post('/api/visitor').then((res)=>{
        
    })
 }
</script>

<template>
    <div class="bg-header-main">
     <nav class="navbar navbar-expand-lg bg-light mb-0 custom-nav main-nav">
        <div class="container-fluid">
            <div class="main-brand">
                <router-link :to="{name:'publicpage'}" class="d-inline-flex p-0" >
                    <div class="brand-name">
                        <img :src="'/img/ndmc.png'" class="logo-main">
                        <img :src="'/img/research.png'" class="sub-logo">
                    </div>

                    <div class="d-md-inline-flex d-lg-inline-flex">
                        <div class="logo-text d-none d-lg-block d-xl-block d-md-block ">
                             RECHIVA</div>
                        <img :src="'/img/rechiva.png'" class="logo-app"> 
                    </div>
               </router-link> 
            </div>
            <button class="btn btn-outline-success d-inline-block d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>
            
            <div class="collapse navbar-collapse " id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                    <li class="nav-item nav-cus mb-nav">
                        <router-link class="nav-link" :to="{name:'publicpage'}">
                             Home
                        </router-link>
                    </li>
                    <li class="nav-item nav-cus mb-nav">
                        <router-link  class="nav-link" :to="{name:'about'}">
                            About
                        </router-link>
                    </li>
                    <li class="nav-item nav-cus mb-nav">
                        <router-link :to="{name:'search'}" class="nav-link">
                           Search
                        </router-link>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                    <li class="nav-item dropdown nav-cus mb-nav">
                        <button type="button" class="btn btn-outline-success" v-if="auth" @click="dashboard()">Dashboard</button>
                        <a class="nav-link dropdown-toggle" href="#" v-if="!auth">
                            Join us
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end user-menu" v-if="!auth">
                         
                            <li>
                                <router-link  class="dropdown-item" :to="{name:'signin'}">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                  Sign In
                                </router-link>
                            </li>
                            <li>
                                <router-link  class="dropdown-item" :to="{name:'signup'}">
                                    <i class="bi bi-box-arrow-in-left"></i>
                                    Sign Up
                                </router-link>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        </ul>

                    </li>
                </ul>
              
            </div>
        </div>
    </nav>
    <div class="content">
        <router-view>
        </router-view>
    </div>

    <div class="footer">
        <ul class="nav justify-content-center border-bottom bg-footer">
            <li class="nav-item">
                <router-link :to="{name:'publicpage'}" class="nav-link px-2 text-white"> Home</router-link> 
            </li>
            <li class="nav-item">
                <router-link :to="{name:'about'}" class="nav-link px-2 text-white"> About</router-link> 
            </li>
        </ul>
    </div>
    </div>
</template>
<style lang="scss">
    .bg-header-main{
        background-image: url('/img/background2.png'); 
        // background-attachment: fixed;
        width:100% !important; 
        height:15rem; 
        background-repeat: no-repeat; 
        background-size:cover;
    }

    .main-nav{
        background-color: #133921d0 !important;
        
        .nav-link{
		   color: #fff !important;
	    }
    }
    .bg-footer{
        background-color: #133921 !important;
        height: 5rem;
        color:#fff;
    }

    .text-white{
        color:#fff !important;
    }

</style>
