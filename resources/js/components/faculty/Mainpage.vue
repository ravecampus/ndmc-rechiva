<script setup>
import { ref , onMounted } from "vue"
import { useRouter } from "vue-router"

const router = useRouter()

const user = ref({})
const notifications = ref([])

onMounted(()=>{
    getAuthUser()
    getNotification();
})

const signout = ()=>{
    Swal.fire({
        title:"Sign out",
        text: "Do you want sign out?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#26884b",
        cancelButtonColor: "#ffc107",
        cancelButtonText: "No",
        confirmButtonText: "Yes"
        }).then((result) => {
        if (result.isConfirmed) {
            axios.get('/api/auth/out').then((res)=>{
                if (res.data.success) {
                    window.location.href = "/sign-in"
                } 
            })  
        }
    });
}

const getAuthUser = ()=>{
    axios.get('/api/user').then((res)=>{
        user.value = res.data;
    })
}

const getNotification = ()=>{
    axios.get('/api/faculty-notification').then((res)=>{
        notifications.value = res.data
    })
}

const extractSender = (data)=>{
    if(data != undefined){
        return data.first_name+" "+data.last_name
    }
}

const notiCount = (data)=>{
    return data.length
}
const showNotification = (data)=>{
    axios.put('/api/notification/'+data.id,{'status': 1}).then((res)=>{
        getNotification()
        if(data.role == 0 || data.role==1){
            router.push({name:'faculty.document', params:{id:data.document_id}});
        }else if(data.role == 2){
            window.location.href = "/faculty"
        }
        

    })
}


</script>


<template>
    <div>
     <nav class="navbar navbar-expand-lg bg-light mb-0 bg-header custom-nav">
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
                  
                    <li class="nav-item dropdown nav-cus">
                        <a class="nav-link" href="#">
                            <i class="bi bi-upload" aria-hidden="true"></i> Upload Rechiva
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end user-menu">
                         
                            <li>
                                <router-link  class="dropdown-item" :to="{name:'faculty.upload_p'}">
                                    <i class="bi bi-file-earmark-arrow-up"></i>
                                    For Publishing
                                </router-link>
                            </li>
                            <li>
                                <router-link  class="dropdown-item" :to="{name:'faculty.upload_a'}">
                                    <i class="bi bi-file-earmark-arrow-up-fill"></i>
                                    For Archiving
                                </router-link>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <router-link class="dropdown-item" :to="{name:'faculty.documents'}">
                                    <i class="bi bi-file-earmark-text"></i>
                                    Documents Request
                                </router-link>
                            </li>
                         
                              
                            <li>
                                <router-link class="dropdown-item" :to="{name:'faculty.published'}">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                    Published & Archived
                                </router-link>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        </ul>

                    </li>
                    <li class="nav-item nav-cus cus-pad dropdown">
                        <a class="nav-link" href="#">
                            <i class="bi bi-bell-fill position-relative notify"> 
                                <span v-if="notifications.length > 0" class="position-absolute top-0 start-100 translate-middle notify-badge">
                                    {{ notiCount(notifications) }}
                                </span>
                            </i>
                        </a>
                         <ul class="dropdown-menu dropdown-menu-end user-menu"  >
                            <li v-if="notifications.length > 0">
                                <div class="ps-2 pe-2">
                                Notifications
                                </div>
                            </li>
                            <li v-if="notifications.length > 0"><hr class="dropdown-divider" ></li>
                            <li v-if="notifications.length == 0"><p class="ps-2 pe-2 text-success">No notification available!</p></li>
                            <div class="noti-div position-relative">
                            <li class="dropdown-item user-li" v-for="(list, index) in notifications" :key="index">
                                <a href="#" class="notili" @click.prevent="showNotification(list)">
                                    <div class="notification">
                                        <div class="d-flex flex-start align-items-center">
                                            <img class="rounded-circle shadow-1-strong me-3"
                                                :src="'/img/user.png'" alt="avatar" width="40"
                                                height="40" />
                                            <div class="arrow text-success w-100 position-absolute text-end">
                                                <i class="bi bi-arrow-right-circle icon-mar"></i>
                                            </div>
                                            <small  class="arrow text-success w-100 position-absolute text-end time-ago">
                                                <timeago :class="'sub-ago'" :datetime="list.created_at"/>
                                            </small>
                                            <div>
                                                <h6 class="fw-bold text-primary mb-1">
                                                    Administrator
                                                    <!-- {{ list.sender.first_name }} --> 
                                                    <!-- {{ extractSender(list.sender) }} -->
                                                    </h6>
                                                <p class="text-muted small mb-0">
                                                    {{ list.caption }}
                                                    
                                                </p>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                         
                            </div>
                            <li><hr class="dropdown-divider"></li>
                         </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button">
                            <img class="img-user" :src="'/img/user.png'" alt="">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end user-menu">
                            <li class="dropdown-item user-li">
                                <div class="info-user">
                                    {{ user.first_name }} {{ user.last_name }}
                                </div>
                            </li>
                            <li><router-link class="dropdown-item" :to="{name:'faculty.myaccount'}">My Account</router-link></li>
                            <!-- <li><a class="dropdown-item" href="#">Published documents</a></li> -->
                            <li><hr class="dropdown-divider"></li>
                            <li class="d-grid gap-2">
                                <button type="button" @click.prevent="signout()" class="btn btn-signout btn-sm">Sign Out</button>
                            </li>
                        </ul>
                    </li>
                </ul>
              
            </div>
        </div>
    </nav>
    <div class="content min-h">
        <router-view></router-view>
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
<style lang="scss" scoped>
    .notification{
        width: 22rem !important;
    }
    .icon-mar{
        margin-right: 1.5rem;
        font-size: 1.7rem;
    }

    .noti-div{
        max-height: 15rem;
        width: 22rem !important;
        overflow-y: hidden; /* Hide vertical scrollbar */
        overflow-x: hidden;
        margin: 0 auto;
        &:hover {
            width: 22rem !important;
            overflow-y: scroll;
            margin: 0 auto;
        }
        // width: 22rem !important;
    }
    ::-webkit-scrollbar {
        width: .2rem;
    }
    ::-webkit-scrollbar-track {
        background: #0aa35c;
     }
     .user-li{
         &:hover{
             background-color:  #d6d6d6 !important;
             color: #8b8b8b;
         }
     }

     .time-ago{
         margin-top: -2.1rem !important;
         .sub-ago{
             margin-right: 3rem;
         }
     }
</style>

