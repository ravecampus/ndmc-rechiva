<script setup>
    import {ref, onMounted } from "vue"

    const auth = ref(false)

    onMounted(()=>{
        
    if(window.winsdev.isLoggedin){
        auth.value = true
    }else{
        auth.value = false
    }
    })

</script>
<template>
    <div class="footer d-prnt-none">
     <div class="row">
         
         <div class="col-md-6 text-start">
             <div class="site-name">
                 RECHIVA | NDMC Research and Department Center
             </div>
             Quezon Avenue, Midsayap, Cotabato 9410
         </div>
        <div class="col-md-6 p-0 text-end">
           
            <section class="mb-4 text-white">
            <!-- Facebook -->
            <a
                data-mdb-ripple-init class="btn btn-link btn-floating btn-lg text-light m-1"
                href="#!"
                role="button"
                data-mdb-ripple-color="dark"
                ><i class="bi bi-facebook"></i
            ></a>

            <!-- Twitter -->
            <a
                data-mdb-ripple-init class="btn btn-link btn-floating btn-lg text-light m-1"
                href="#!"
                role="button"
                data-mdb-ripple-color="dark"
                ><i class="bi bi-twitter"></i
            ></a>

            <!-- Google -->
            <a
                data-mdb-ripple-init class="btn btn-link btn-floating btn-lg text-light m-1"
                href="#!"
                role="button"
                data-mdb-ripple-color="dark"
                ><i class="bi bi-google"></i
            ></a>

            <!-- Instagram -->
            <a
                data-mdb-ripple-init class="btn btn-link btn-floating btn-lg text-light m-1"
                href="#!"
                role="button"
                data-mdb-ripple-color="dark"
                ><i class="bi bi-instagram"></i
            ></a>

            <!-- Linkedin -->
            <a
                data-mdb-ripple-init class="btn btn-link btn-floating btn-lg text-light m-1"
                href="#!"
                role="button"
                data-mdb-ripple-color="dark"
                ><i class="bi bi-linkedin"></i
            ></a>
            
            </section>
             <div class="btn-group dropup" v-if="!auth">
                <!-- <button type="button" class="btn btn-sm btn-outline-light"></button> -->
                <button type="button" class="btn btn-sm btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                
                    Join us &nbsp;
                </button>
                    <ul class="dropdown-menu">
                                        
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
            </div>

         </div>
     </div>
     <!-- <div class="buttom-footer">

     </div> -->
    </div>
</template>
<style lang="scss" scoped>
    .footer{
        background-color: #26884b;
        padding: 10px 20px;
        min-height: 8rem;
        font-weight: 600;
        color:#fff;
        border-top: #fff 1px solid;
        border-bottom: #00501f 10px solid;

        .site-name{
            font-size: 1.3rem;
        }


    }
    .buttom-footer{
        background: #01752e;
        position: absolute;
        padding:5px;
    }
</style>

