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
    <div class="footer d-print-none">
     <div class="row">
         
         <div class="col-md-6 text-start">
             <div class="site-name">
                 RECHIVA | NDMC Research and Development Center
             </div>
             Quezon Avenue, Midsayap, Cotabato 9410
             <div class="fw-bold fs-6 mt-3">
                 Capstone Project of College of Information Technology and Engineering (CITE)
             </div>
         </div>
        <div class="col-md-4 p-0 text-start">
       
            <section class="text-white">
                <span>Follow us:</span>
            <div>
                <a data-mdb-ripple-init class="btn p-0 ps-3 btn-float btn-lg text-light"
                    href="mailto:rdc@ndmc.edu.ph"
                    target="_blank"
                    role="button"
                    data-mdb-ripple-color="dark"
                    ><i class="bi bi-envelope-at"></i> 
                    <span class="s-name ms-2">rdc@ndmc.edu.ph</span>
                    </a>
            </div>
            <div>
                <a data-mdb-ripple-init class="btn p-0 ps-3 btn-float btn-lg text-light"
                    target="_blank"
                    href="https://www.facebook.com/NDMCResearchandDevelopmentCenter"
                    role="button"
                    data-mdb-ripple-color="dark"><i class="bi bi-facebook"></i>
                     <span class="s-name ms-1"> Facebook</span>
                     </a>
            </div>
            
            </section>
           

         </div>
        <div class="col-md-2 text-end ">
                <div class="btn-group dropup" v-if="!auth">
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

    .s-name{
        font-size: 16px;
    }
</style>

