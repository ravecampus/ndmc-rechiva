<template>
<div>
    	
    <nav class="navbar navbar-expand-lg bg-light mb-0 bg-header custom-nav">
        <div class="container-fluid">
            
             <button type="button" class="btn text-white sidebarCollapse d-inline-block d-lg-none">
              <i class="fa fa-bars"></i>
              <span class="sr-only">Toggle Menu</span>
            </button>
            <div class="main-brand">
                <router-link :to="{name:'faculty'}" class="d-inline-flex p-0" >
                    <div class="brand-name">
                        <img :src="'/img/ndmc.png'" class="logo-main">
                        <img :src="'/img/research.png'" class="sub-logo">
                    </div>

                    <div class="d-md-inline-flex d-lg-inline-flex">
                        <div class="logo-text d-none d-lg-block d-xl-block d-md-block p-0 ">
                            <!-- <p class="school-name m-0 p-0">Notre Dame of Midsayap College</p> -->
                            NDMC - RECHIVA
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
                    <!-- <li class="nav-item nav-cus">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li> -->
                    <!-- <li class="nav-item nav-cus">
                        <a class="nav-link" href="#">
                            <i class="fa fa-upload" aria-hidden="true"></i> Upload
                        </a>
                    </li> -->
                     <li class="nav-item nav-cus">
                        <a class="nav-link" href="#">
                            <i class="fa fa-bell position-relative notify"> 
                                <span class="position-absolute top-0 start-100 translate-middle bg-danger rounded-circle notify-badge">
                                    11
                                </span>
                            </i>
                        </a>
                    </li>
                    <li class="nav-item dropdown justify-content-center">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                       <div class="d-flex">
                            <div class="admin-info d-none d-lg-block d-xl-block">
                             Admin Name
                                <div class="description">Admin</div>
                            </div>
                            <img class="img-user" :src="'/img/user.png'" alt="">
                       </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end user-menu">
                        <li class="dropdown-item user-li d-inline-block d-lg-none">
                            <div class="info-user">
                                Admin Name (Admin)
                                <!-- <div class="description">Admin</div> -->
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
                    <router-link :to="{name:'admin'}"><span class="fa fa-dashboard"></span> Dashboard</router-link>
                </li>
                <li>
                    <a href="#"><span class="fa fa-user"></span> Acount</a>
                </li>
                <li>
                    <a href="#"><span class="fa fa-users"></span> Users</a>
                </li>
                <li>
                    <router-link :to="{name:'setting.authors'}"><span class="fa fa-cogs"></span> Settings</router-link>
                </li>
                <li>
                    <a href="#"><span class="fa fa-file"></span> Documents</a>
                </li>
            </ul>

            <div class="footer">
                <p>
                        <!-- Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib.com</a> -->
                </p>
            </div>
            </nav>

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5">

        <router-view></router-view>

      </div>
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

<style lang="scss">


</style>
