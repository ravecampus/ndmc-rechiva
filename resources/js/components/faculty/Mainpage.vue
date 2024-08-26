<template>
    <div>
     <nav class="navbar navbar-expand-lg bg-light mb-0 bg-header custom-nav">
        <div class="container-fluid">
            
             <button type="button" class="btn text-white sidebarCollapse d-inline-block d-lg-none">
              <i class="fa fa-bars"></i>
              <span class="sr-only">Toggle Menu</span>
            </button>
            <div class="main-brand">
                <router-link :to="{name:'admin'}" class="d-inline-flex p-0" >
                    <div class="brand-name">
                        <img :src="'/img/ndmc.png'" class="logo-main">
                        <img :src="'/img/research.png'" class="sub-logo">
                    </div>

                    <div class="d-md-inline-flex d-lg-inline-flex">
                        <div class="logo-text d-none d-lg-block d-xl-block d-md-block ">
                             NDMC - RECHIVA</div>
                        <img :src="'/img/rechiva.png'" class="logo-app"> 
                    </div>
                </router-link> 
            </div>
            <button class="btn btn-outline-success d-inline-block d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>
            
            <div class="collapse navbar-collapse " id="navbarSupportedContent">
                
                <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                    <!-- <li class="nav-item nav-cus">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li> -->
                    <li class="nav-item dropdown nav-cus">
                        <a class="nav-link" href="#">
                            <i class="fa fa-upload" aria-hidden="true"></i> Upload
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end user-menu">
                            <li>
                                <router-link  class="dropdown-item" :to="{name:'faculty.upload'}">Upload Research Project</router-link>
                            </li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                        </ul>

                    </li>
                     <li class="nav-item dropdown nav-cus">
                        <a class="nav-link" href="#">
                            <i class="fa fa-bell position-relative notify"> 
                                <span class="position-absolute top-0 start-100 translate-middle bg-danger border border-light rounded-circle notify-badge">
                                    2
                                </span>
                            </i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end user-menu">
                            <li class="dropdown-item user-li">
                                <div class="info-user">
                                    <!-- Lavwin Cabanting Campollo -->
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="d-grid gap-2">
                                <button type="button" class="btn btn-signout btn-sm">Sign Out</button>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="clear-fix"></li> -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button">
                            <img class="img-user" :src="'/img/user.png'" alt="">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end user-menu">
                            <li class="dropdown-item user-li">
                                <div class="info-user">
                                    Faculty Name
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="d-grid gap-2">
                                <button type="button" class="btn btn-signout btn-sm">Sign Out</button>
                            </li>
                        </ul>
                    </li>
                </ul>
              
            </div>
        </div>
    </nav>
    <div class="content">

        <router-view></router-view>
        <!-- <div class="card" >
            <div class="card-body" 
            @dragenter.prevent="onDrag"
            @dragleave.prevent ="onDrag"
            >

            </div>
        </div> -->
        
    </div>

    </div>
</template>
<script setup>
  import { onMounted } from "vue"

  const onDrag = ()=>{
      console.log("hello")
  }
</script>

