<script setup>
import {ref, onMounted, watch } from "vue"
import { useRouter } from "vue-router"

const user = ref({})
const notifications = ref([])
const router = useRouter()

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

onMounted(()=>{
    getAuthUser();
    getNotification();
})

watch(notifications,()=>{
    if(notifications.length > 0){
        getNotification()
    } 
})

const getAuthUser = ()=>{
    axios.get('/api/user').then((res)=>{
        user.value = res.data;
    })
}

const getNotification = ()=>{
    axios.get('/api/notification').then((res)=>{
        notifications.value = res.data
        console.log(res.data)
    })
}

const extractSender = (data)=>{
    if(data != undefined){
        return data.first_name+" "+(data.last_name != null ? data.last_name : "")
    }
}

const notiCount = (data)=>{
    return data.length
}
const showNotification = (data)=>{
    axios.put('/api/notification/'+data.id,{'status': 1}).then((res)=>{
        getNotification()
        if(data.role == 0 || data.role == 1){
            router.push({name:'admin.requestdoc', params:{id:data.document_id}});
        }else if(data.role == 2){
            router.push({name:'admin.user', params:{id:data.sender_id}});
        }
        

    })
}
</script>


<template>
<div>	
    <nav class="navbar navbar-expand-lg bg-light mb-0 bg-header custom-nav">
        <div class="container-fluid">
            
             <button type="button" class="btn text-white sidebarCollapse d-inline-block d-lg-none">
              <i class="fa fa-bars"></i>
              <span class="sr-only">Toggle Menu</span>
            </button>
            <div class="main-brand">
                <router-link :to="{name:'dashboard'}" class="d-inline-flex p-0" >
                    <div class="brand-name">
                        <img :src="'/img/ndmc.png'" class="logo-main">
                        <img :src="'/img/research.png'" class="sub-logo">
                    </div>

                    <div class="d-md-inline-flex d-lg-inline-flex">
                        <div class="logo-text d-none d-lg-block d-xl-block d-md-block p-0 ">
                            <!-- <p class="school-name m-0 p-0">Notre Dame of Midsayap College</p> -->
                            RECHIVA
                            </div>
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
                        <router-link class="nav-link" :to="{name:'admin.upload_p'}">
                                <span class="bi bi-plus-circle"></span> Publish
                        </router-link>
                    </li>
                     <li class="nav-item nav-cus mb-nav">
                        <router-link class="nav-link" :to="{name:'admin.upload_a'}">
                                <span class="bi bi-plus-circle"></span> Archive
                        </router-link>
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
                                       
                                                :src="list.sender.image == null ? '/img/user.png' : 'data:'+list.sender.extension+';base64,'+list.sender.image"
                                                alt="avatar" width="40"
                                                height="40" />
                                            <div class="arrow text-success w-100 position-absolute text-end">
                                                <i class="bi bi-arrow-right-circle icon-mar"></i>
                                            </div>
                                            <small  class="arrow text-success w-100 position-absolute text-end time-ago">
                                                <timeago :class="'sub-ago'" :datetime="list.created_at"/>
                                            </small>
                                            <div>
                                                <h6 class="fw-bold text-primary mb-1">
                                                    <!-- {{ list.sender.first_name }} --> 
                                                    {{ extractSender(list.sender) }}
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
                    <li class="nav-item dropdown justify-content-center">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                       <div class="d-flex">
                            <div class="admin-info d-none d-lg-block d-xl-block">
                             {{ user.first_name }}
                                <!-- <div class="description">Admin</div> -->
                            </div>
                            <img class="img-user" :src="'/img/user.png'" alt="">
                       </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end user-menu">
                        <li class="dropdown-item user-li d-inline-block d-lg-none">
                            <div class="info-user">
                                {{ user.first_name }}
                                <!-- <div class="description">Admin</div> -->
                            </div>
                        </li>
                        <!-- <li><a class="dropdown-item" href="#">Action</a></li> -->
                        <li><router-link :to="{name:'admin.account'}" class="dropdown-item">Profile</router-link></li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="d-grid gap-2">
                            <button type="button"  @click.prevent="signout()" class="btn btn-signout btn-sm">Sign Out</button>
                        </li>
                    </ul>
                    </li>
                </ul>
              
            </div>
        </div>
    </nav>
        
		<div class="wrapper d-flex align-items-stretch">
            
            <nav id="sidebar" class="active">
           
            <ul class="list-unstyled components mb-5">
                <li class="ms-auto"> 
                     <a  href="#" class=" sidebarCollapse d-none d-lg-block d-xl-block ">
                        <i class="fa fa-bars"></i>
                        <!-- <span class="sr-only">Toggle Menu</span> -->
                    </a>
                </li>
                <li>
                    <router-link :to="{name:'dashboard'}"><span class="bi bi-speedometer"></span> Dashboard</router-link>
                </li>
                 <li>
                    <router-link :to="{name:'admin.request'}"><span class="bi bi-file-earmark-arrow-down"></span> Submission</router-link>
                </li>
                <li>
                    <router-link :to="{name:'admin.published'}"><span class="bi bi-file-earmark-pdf"></span> Published</router-link>
                </li>
                <li>
                    <router-link :to="{name:'admin.archived'}"><span class="bi bi-archive"></span> Archived</router-link>
                </li>
                <li>
                    <router-link :to="{name:'admin.canceled'}"><span class="bi bi-file-earmark-excel"></span> Canceled</router-link>
                </li>
                
                <!-- <li>
                    <router-link :to="{name:'admin.account'}"><span class="bi bi-person-circle"></span> Account</router-link>
                </li> -->
                <li>
                    <router-link :to="{name:'admin.users'}"><span class="bi bi-people"></span> Users</router-link>
                </li>
                <li>
                    <router-link :to="{name:'setting.authors'}"><span class="bi bi-gear"></span> Data Mgmt.</router-link>
                </li>
             
            </ul>

            <div class="footer">
                <p>
                        <!-- Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib.com</a> -->
                </p>
            </div>
            </nav>

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5 pt-md-2">

        <router-view></router-view>

      </div>
	</div>
    <div class="footer">
        <ul class="nav justify-content-center border-bottom bg-footer">
        <li class="nav-item"><a href="#" class="nav-link px-2 text-white"></a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary"></a></li>
        </ul>
        <!-- <p class="text-center text-body-secondary">© 2024 Company, Inc</p> -->
    </div>
</div>
</template>
<script>
import $ from "jquery";
export default {
    mounted() {
            var fullHeight = function() {
                $('.js-fullheight').css('height', $(window).height());
                $(window).resize(function(){
                    $('.js-fullheight').css('height', $(window).height());
                });

            };
            fullHeight();

            $('.sidebarCollapse').on('click', function () {
                    $('#sidebar').toggleClass('active');
            });

    },
}
</script>
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
    .bg-footer{
        background-color: #133921 !important;
        height: 5rem;
    }


</style>



