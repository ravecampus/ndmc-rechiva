<script setup>
    import { ref, onMounted } from "vue"
    import dayjs from 'dayjs'

    const formatDate = (dateString)=>{
		const date = dayjs(dateString)
		return date.format('MMMM D, YYYY | hh:mm a')
    }
    

    const user = ref({});


    onMounted(()=>{
        getAuthUser()
        const user = window.winsdev.user;
        if(window.winsdev.isLoggedin && user.activate == 0){
            getInfo()
        }else{
            recentData();
        }
    })


    const getAuthUser = ()=>{
        axios.get('/api/user').then((res)=>{
            user.value = res.data;
        })
    }
    const activated = (data)=>{
        let ret = false;
        if(data.activate === 1){
            ret = true
        }

        return ret;
    }
    const info = ref({})

    const getInfo = ()=>{
        axios.get('/api/admin-contact').then((res)=>{
            info.value = res.data;
        })
    }
    const recents = ref([]);
    const recentData = ()=>{
        axios.get('/api/faculty-recent').then((res)=>{
            recents.value = res.data
        })
    }

</script>

<template>
    <div class="container bg-white mt-3 rounded vh-100">
        <div class="row">
            <div class="col-md-12 text-start me-4 mt-3">
                <h4 class="m-0">Dashboard</h4>
                <p>{{ formatDate(new Date()) }}</p>
                <hr>
            </div>
            <div class="col-md-12">
                <div v-if="user.activate == 0" class="alert alert-warning alert-dismissible mt-3 text-start" role="alert">
                    
                    <h4 class="alert-heading">You need to activate your account!</h4>
                    <p>{{ info.information }}</p>
                    <hr>
                    <p class="mb-0"> Contact #:&nbsp; <span class="fw-bold">{{ info.contact_number }}</span></p>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="my-recent text-start" v-if="activated(user)">
                    <p class="">MY RECENT UPLOAD</p>

                         
                    <table class="table mb-0">
                        <tbody>
                            <tr class="align-middle" v-for="(list,index) in recents" :key="index">
                                <td>
                                    <router-link  :to="{name:'faculty.document',params:{id:list.id}}">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-text me-2 icon-file"></i>
                                    <strong class="text-primary"> {{ list.title }} </strong>
                                    </div>
                                      <timeago :class="'sub-ago'" :datetime="list.created_at"/>
                                    </router-link>
                                </td>
                                <td class="text-abstract w-50 ">
                                    <span class="text-truncate-message">
                                    {{ list.abstract }}
                                    </span>
                                </td>
                                <td>
                                 
                                </td>
                                
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .text-abstract{
        color:#4b4b4b;
        font-weight: 300;
    }
    .sub-ago{
        font-size: .7rem;
        color: #4b4b4b;
    }
     .text-truncate-message{
        -webkit-line-clamp: 2;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

