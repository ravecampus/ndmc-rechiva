<script setup>
    import dayjs from 'dayjs'
    import { ref, onMounted, reactive } from "vue"

    const statistic = ref({})
  

    const formatDate = (dateString)=>{
		const date = dayjs(dateString)
		return date.format('MMMM D, YYYY | hh:mm a')
    }
    
    onMounted(()=>{
        getStatistic();
        getDeptStat()
        getRecentSub()
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
                    <div class="col-md-12">

                    </div>
                     <div class="col-md-12 mt-5 d-flex justify-content-around">
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
        min-height: 38rem;
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
</style>
