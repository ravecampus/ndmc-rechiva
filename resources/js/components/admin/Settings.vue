<template>
    <div class="container mt-2">
        <div class="col-md-12 text-start">
            <h4 class="m-0">Data Management</h4>
            <p>{{ formatDate(new Date()) }}</p>
            <hr>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <router-link :to="{name:'setting.authors'}" class="nav-link nav-cus-link" aria-current="page">Authors</router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{name:'setting.departments'}" class="nav-link nav-cus-link">College Departments</router-link>
            </li>
            <li class="nav-item">
                <router-link  class="nav-link nav-cus-link" :to="{name:'setting.typeofpapers'}">Type of Papers</router-link>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
            </li> -->
        </ul>
        <div class="content bg-white">
            <router-view class="pt-4"></router-view>
        </div>
    </div>
</template>
<script setup>

import { onMounted } from "vue"
import $ from "jquery"
import dayjs from 'dayjs'

const formatDate = (dateString)=>{
    const date = dayjs(dateString)
    return date.format('MMMM D, YYYY | hh:mm a')
}

</script>

<style lang="scss">

.nav-link{
    // font-weight: bold !important;
    color:#26884b !important;
}
.nav-cus-link.router-link-exact-active {
    font-weight: bold;
    color: #272727 !important;
    background-color: #fff !important;
    border-color: #dee2e6 #dee2e6 #fff !important;

}

             
        
    


</style>

