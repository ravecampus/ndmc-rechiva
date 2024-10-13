<script setup>
    import dayjs from 'dayjs'
    import { ref, onMounted, reactive } from "vue"

    const statistic = ref({})
  

    const formatDate = (dateString)=>{
		const date = dayjs(dateString)
		return date.format('MMMM D, YYYY | hh:mm a')
    }
    
    onMounted(()=>{
        // let user = window.winsdev.user
        // if(window.winsdev.isLoggedin && user.role === 2){
        //     router.push("/admin")
        // }
        getStatistic();
        getDeptStat()
        getRecentSub()
        mostdownload()
    })


const getStatistic = ()=>{
    axios.get('/api/statistic').then((res)=>{
        statistic.value = res.data;
    })
}
const deps = ref([])
let dataset = {}
let label = []
let datastat = []
const downloads = ref([])
const getDeptStat = ()=>{
     axios.get('/api/department-stat').then((res)=>{
         deps.value = res.data
         let data = res.data

         data.forEach(val => {
             label.push(val.abbreviation)
             datastat.push(val.docs.length)
         });

    })
}
const recents = ref([])
const getRecentSub = ()=>{
    axios.get('/api/recent-submission').then((res)=>{
        recents.value = res.data;
    });
}

const mostdownload = ()=>{
    axios.get('/api/most-download').then((res)=>{
        downloads.value = res.data
    });
}
</script>

<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row mt-2">
                    <div class="col-md-3 text-left">
                        <img :src="'/img/rechiva.png'" width="130" height="130">
                    </div>
                    <div class="col-md-9 text-start text-white mt-2 rechiva-cap pe-5">
                        <h4 class="title">RECHIVA</h4>
                        <p class="caption">
                            The Institutional Repository of Notre Dame of Midsayap College
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-2 recent-submission ps-5">
                <div class="container rs-content">
                    <div class="rs-content-head">
                        Recent Submissions
                    </div>
                    <div class="rs-content-body" v-for="(list, index) in recents" :key="index">
                        <div class="title">
                            <router-link :to="{name:'main.document', params:{id:list.id}}" class="link">
                            {{ list.title }}
                           </router-link>
                        </div>
                        <div class="content rs-truncate">
                            {{ list.abstract }}
                        </div>
                    </div>
                  
                </div>
            </div>
            <div class="col-md-6 mt-2">
                <div class="row">
                    <div class="col-md-12 p-0">
                        <div class="link-fb">
                            <a target="_blank" href="https://www.facebook.com/NDMCResearchandDevelopmentCenter"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Facebook Page NDMC RDC">
                                <img class="img-link" :src="'/img/school.png'"/>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="most-download text-start mt-4 bg-white p-2">
                            <h4 class="text-uppercase">Top most download documents</h4>
                            <ul class="most-list">
                                <li class="d-flex justify-content-between" v-for="(list,index) in downloads" :key="index">
                                   <router-link :to="{name:'main.document', params:{id:list.document_id }}">
                                       <!-- <i class="bi bi-cloud-arrow-down"></i> -->
                                     
                                      <span class="most-title text-truncate-message">  <span class="square"></span> {{ list.title}}</span>
                                    </router-link> 
                                    <span class="most-count">
                                       
                                        {{ parseFloat(list.download).toFixed(2) }}
                                         <i class="bi bi-cloud-arrow-down"></i>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                     <div class="col-md-12 mt-5 d-flex justify-content-around mb-5">
                        <div class="round-info">
                            <div class="round-icon">
                                <i class="bi bi-people"></i>
                                <div class="round-count">{{ statistic.visitors }}</div>
                            </div>
                            <div class="round-title text-start">Total Visitors</div>
                        </div>
                        
                        <div class="round-info">
                            <div class="round-icon">
                                <i class="bi bi-folder-check"></i>
                                <div class="round-count">{{ statistic.collections }}</div>
                            </div>
                            <div class="round-title text-start">Total Collections</div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-5 d-flex justify-content-around">
                        <div class="round-info">
                            <div class="round-icon">
                                <i class="bi bi-box-arrow-in-down"></i>
                                <div class="round-count">{{ statistic.downloads }}</div>
                            </div>
                            <div class="round-title text-start">Total Downloads</div>
                        </div>
                        
                        <div class="round-info">
                            <div class="round-icon">
                                <i class="bi bi-people"></i>
                                <div class="round-count">{{ statistic.members }}</div>
                            </div>
                            <div class="round-title text-start">Total Members</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>

    .round-info{
         margin-right: 1rem;
         margin: 0 auto;
         text-align: center;
        .round-icon{
            text-align: center;
            margin-left: .9rem;
            width: 130px;
            height: 130px;
            background: #26884b;
            border-radius: 50%;
            border:#dfdfdf6b solid 1px;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
           
            .bi{
                position: absolute;
                font-size: 3rem;
                margin-left:-1.4rem !important;;
                color: #fff;
            }
            .round-count{
                font-size: 2rem;
                color: #fff;
                margin-top: 3.5rem;
             
            }

        }
        .round-title {
            text-align: center !important;
            font-size: 1.5rem;
            color: #056836;
            font-weight: 600;
        }
    }

    .rechiva-cap{
        border-left: 2px #fff solid;
        .title{
            color: #f0f0f0 !important;
            font-size: 1.5rem;
        }

        .caption{
            font-size: 1.2rem !important;
        }
    }
    .rs-content{
        background-color: #63a369;
        min-height: 80rem;
        position: relative;
        padding: 1rem;
        padding-top: 2rem;
        .rs-content-head{
            width: 50%;
            text-align: left;
            background-color: #fff;
            font-size: 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            padding: 0 10px;
            margin-bottom: 1rem;
            
        }
        .rs-content-body{
            text-align: left;
            color:#fff;
            border-bottom: #48714c solid 2px;
            margin-right: 1rem;
            .title{
                font-size: 1.5rem;
                font-weight: 600 !important;
                .link{
                    color: #fff;
                    &:hover{
                        text-decoration: underline !important;
                    }
                }
            }
            .content{
                font-size: 1rem;
                margin-bottom: 1rem;
            }

        }
    }

    .rs-truncate{
        -webkit-line-clamp: 5;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .link-fb{
        margin-top: 2.5rem;
    }
    .img-link{
        border: #fff solid 3px;
        border-radius: 10px;
        width: 40rem;
    }

    .most-list{
        list-style-type: none;
        color: #056836;
    }
    .bi{
        font-size: 2rem;
    }

    .most-count{
        font-size: 1.2rem;
        margin-right: 5rem;
        font-weight: 600;
    }

    .most-title{
        margin-left: 3px;
        font-size: 1.1rem;
        margin-top: 20px !important;
        font-weight: 600;
    }

    .text-truncate-message{
        -webkit-line-clamp: 1;
        display: -webkit-box;
        width: 25rem;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .square{
        padding: 4px;
        width: 2px;
        height: 2px;
        background-color: #056836;
        margin-right: 10px;
    }
</style>
