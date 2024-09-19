

<template>
    <div class="container mt-3">
          
        <ul class="nav nav-underline me-3">
            <li class="nav-item">
                <router-link class="nav-link nav-line-link" :to="{name:'faculty.published'}">Published </router-link>
            </li>
            <li class="nav-item">
               <router-link class="nav-link nav-line-link" :to="{name:'faculty.archived'}">Archived</router-link>
            </li>
            <li class="nav-item">
               <router-link class="nav-link nav-line-link" :to="{name:'faculty.canceled'}">Canceled</router-link>
            </li>
        </ul>
            <div class="row mt-5">
                <div class="col-md-12">
                    <router-view></router-view>
                </div>
            </div>

    </div>
</template>

<style lang="scss" scoped>
.nav-line-link.router-link-exact-active {
    font-weight: 700;
    color: var(--bs-nav-underline-link-active-color);
    border-bottom-color: currentcolor;

}
</style>

