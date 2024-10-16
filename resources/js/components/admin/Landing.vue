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
    dataset = {
        labels:label,
        datasets: [ { 
            label: 'College Department Submission',
            backgroundColor: '#26884b',
            data:datastat
            } ]
    }
 
}

</script>
<script>
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {
  name: 'BarChart',
  components: { Bar },
  data() {
    return {
     chartData: {
        labels: [ 'cite', 'cas', 'cce', 'cbe', 'coe' ],
        datasets: [ { 
            label: 'College Department Submission',
            backgroundColor: '#26884b',
            data: [40, 20, 12] 
            } ]
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false
      }
    }
  }
}
</script>


<template>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12 text-start me-4">
                <h4 class="m-0">Dashboard</h4>
                <p>{{ formatDate(new Date()) }}</p>
                <hr>
            </div>
            
            <div class="col-md-12 d-flex justify-content-between ">
                
                <div class="card w-100 me-4 panel-card">
                    <div class="card-body pb-2">
                        <div class="d-flex justify-content-between head-panel">
                            <div class="count-panel">
                                {{  statistic.visitors }}
                            </div>
                            <div class="icon-panel">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="title-panel">
                            Total Visitors
                        </div>
                    </div>
                </div>
                 <div class="card w-100 me-4 panel-card">
                    <div class="card-body pb-2">
                        <div class="d-flex justify-content-between head-panel">
                            <div class="count-panel">
                                {{ statistic.collections }}
                            </div>
                            <div class="icon-panel">
                                <i class="bi bi-folder-check"></i>
                            </div>
                        </div>
                        <div class="title-panel">
                            Total Collections
                        </div>
                    </div>
                </div>
                 <div class="card w-100 me-4 panel-card">
                    <div class="card-body pb-2">
                        <div class="d-flex justify-content-between head-panel">
                            <div class="count-panel">
                                {{ statistic.downloads }}
                            </div>
                            <div class="icon-panel">
                                <i class="bi bi-box-arrow-in-down"></i>
                            </div>
                        </div>
                        <div class="title-panel">
                            Total Downloads
                        </div>
                    </div>
                </div>
                 <div class="card w-100 me-4 panel-card">
                    <div class="card-body pb-2">
                        <div class="d-flex justify-content-between head-panel">
                            <div class="count-panel">
                                {{ statistic.members }}
                            </div>
                            <div class="icon-panel">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="title-panel">
                            Total Members
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-12 text-start">
                
                <div class="card me-4 card-report ">
                    <div class="card-header pb-1 bg-white">
                        <h4>Report</h4>
                    </div>
                    
                    <div class="card-body">
                       <div class="verticaltext position-absolute">
                          <h4> Number of Submission</h4>
                        </div>
                        <div class="h-100">
                        <Bar :class="'ms-5'" v-if="deps.length > 0"
                        id="my-chart-id"
                        :options="chartOptions"
                        :data="dataset"
                        />
                        </div>
                        
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .panel-card{
        max-height: 8rem;
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
        border:#26884b solid 1px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        .head-panel{
            padding: 0;
            margin: 0;
            .count-panel{
                font-size: 1.5rem;
                font-weight: bold;
                color: #575656;
            }
        }

        .title-panel{
            text-align: start;
            font-size: 1.2rem;
            font-weight: 500;
        }

        .icon-panel{
            // position: absolute;
            background-color: #26884b;
            // padding: 1.5rem;
            height: 4rem;
            width: 4rem;
            border-radius: 50%;
            .bi{
                font-size: 2rem;
                color: #fff;
            }
        }
    }

    .card-report{
        border:#26884b solid 1px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        height: 30em;
    }

    .verticaltext{
        margin-top: 18rem;
        transform: rotate(-90deg);
        transform-origin: left top 0;
    }
</style>

