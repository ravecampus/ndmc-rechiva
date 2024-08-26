<template>
    <div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <router-link :to="{name:'setting.authors'}" class="nav-link" aria-current="page">Authors</router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{name:'setting.categories'}" class="nav-link">Categories</router-link>
            </li>
            <li class="nav-item">
                <router-link  class="nav-link" :to="{name:'setting.typeofpapers'}">Type of Papers</router-link>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
            </li>
        </ul>
        <div class="content bg-white">
            <router-view class="pt-4"></router-view>
        </div>
    </div>
</template>
<script setup>

import { onMounted } from "vue"
import $ from "jquery"

</script>

<style lang="scss">

.nav-link{
    // font-weight: bold !important;
    color:#26884b !important;
}
.nav-link.router-link-exact-active {
    font-weight: bold;
    color: #272727 !important;
    background-color: #fff !important;
    border-color: #dee2e6 #dee2e6 #fff !important;

}

             
        
    


</style>

