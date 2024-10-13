<script setup>
    import { ref, reactive } from "vue"
    import dayjs from "dayjs"

    const fdata = () => ({
            date_from:"",
            date_to:""
        })
    const formatDate = (dateString)=>{
		const date = dayjs(dateString)
		return date.format('MMMM D, YYYY')
	}
    const report = ref([])
    const members = ref([])
    const submission = ref([])
    const btngenerate = ref("generate")
	const form = reactive(fdata())
    const resetform = () => Object.assign(form, fdata())
    
    const format = (d) => {
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();
        return  month+ "/" + day  + "/" + year;
    }

    const print = ()=>{
        window.print()
    }

    const generate = ()=>{
        btngenerate.value = "generating..."
        axios.get('/api/report',{params:form}).then((res)=>{
            btngenerate.value = "generate"
            report.value = res.data
            submission.value = res.data.upload
            members.value = res.data.members
        })
    }

    const extractMember = (data)=>{
        let ret = 0; 
        members.value.forEach(val => {
            if(data.department_id == val.department_id){
                ret = val.number_of_member
            }
        });

        return ret
        
    }


    const totalMem = ()=>{
        let ret = 0
        members.value.forEach(val => {
            
            ret += val.number_of_member
            
        });

        return ret
    }

    const totalSub = ()=>{
        let ret = 0
        submission.value.forEach(val=>{
            ret += val.number_of_submission
        })

        return ret;
    }

    const clear = ()=>{
        form.date_to = ""
    }

</script>


<template>
    <div class="container">
        <div class="row bg-white rounded mt-3 d-print-none">
           <h4  class="text-start mt-3">Summary Report</h4>
           <div class="col-md-3">
               <div class="card border">
                   <div class="card-header text-start">Date Range</div>
                   <div class="card-body text-start">
                        <div class="form-group mb-3">
                            <label>From :</label>
                            <VueDatePicker v-model="form.date_from"
                            @update:model-value="clear()"
                            :format="format" placeholder="Enter Date From"></VueDatePicker>
            
                        </div>
                         <!-- @update:model-value="generate()" -->
                         <div class="form-group mb-3">
                            <label>To :</label>
                            <VueDatePicker v-model="form.date_to"
                           
                             :format="format" placeholder="Enter Date To"></VueDatePicker>
            
                        </div>
                        <div class="btn-group">
                            <button type="button" @click="generate()" class="btn btn-success btn-sm">
                                <i class="bi bi-bullseye"></i>
                                {{ btngenerate }}
                            </button>
                            <button type="button" @click="print()" class="btn btn-warning btn-sm">
                                <i class="bi bi-printer"></i>
                                Print
                            </button>

                        </div>
                   </div>
               </div>
           </div>
           <div class="col-md-9 text-start">
                   <div class="fw-bold">
                       Date:
                       <span class="text-danger" v-if="form.date_to != '' && form.date_from"> {{ formatDate(form.date_from) }} - {{ formatDate(form.date_to) }} </span>
                   </div>
                   <table class="table table-bordered">
                       <thead>
                           <tr>
                               <th>College Department</th>
                               <th>Number of Submissions</th>
                               <th>Number of Members</th>
                           </tr>
                       </thead>
                       <tbody>
                           <tr v-for="(list, index) in report.upload" :key="index">
                               <td>{{ list.description }}</td>
                               <td>{{ parseFloat(list.number_of_submission).toFixed(2) }}</td>
                               <td>{{ parseFloat(extractMember(list)).toFixed(2) }}</td>
                           </tr>
                           <tr class="fw-bold">
                               <td class="text-start fw-bold">TOTAL</td>
                               <td>{{ parseFloat(totalSub()).toFixed(2) }}</td>
                               <td>{{ parseFloat(totalMem()).toFixed(2) }}</td>
                           </tr>
                       </tbody>

                   </table>
           </div>
        </div>

        <div class="row bg-white rounded mt-3 d-print-block d-none">
            <div class="col-md-12 p-2 d-flex justify-content-between">
                <div class="logo">
                    <img class="img-logo" :src="'/img/ndmc.png'"/>
                </div>
                <div class="text-head">
                    RECHIVA | NDMC Research and Department Center
                    <p class="sub-text">Quezon Avenue, Midsayap, Cotabato 9410</p>
                </div>
                <div class="logo">
                    <img class="img-logo-2" :src="'/img/logo_.png'"/>
                </div>
            </div>
            <div class="col-md-12">
                <div class="report-card p-3">
                   <div class="fs-6 fw-bold">System Summary Report</div>
                    <div class="fw-bold">
                       Date:
                       <span class="text-danger" v-if="form.date_to != ''"> {{ formatDate(form.date_from) }} - {{ formatDate(form.date_to) }} </span>
                   </div>
                   <table class="table table-bordered text-start">
                       <thead>
                           <tr>
                               <th>College Department</th>
                               <th>Number of Submissions</th>
                               <!-- <th>Number of Downloads</th> -->
                               <th>Number of Members</th>
                           </tr>
                       </thead>
                       <tbody>
                          <tr v-for="(list, index) in report.upload" :key="index">
                               <td>{{ list.description }}</td>
                               <td>{{ parseFloat(list.number_of_submission).toFixed(2) }}</td>
                               <td>{{ parseFloat(extractMember(list)).toFixed(2) }}</td>
                           </tr>
                           <tr class="fw-bold">
                              
                               <td class="text-start fw-bold">TOTAL</td>
                               <td>{{ parseFloat(totalSub()).toFixed(2) }}</td>
                               <td>{{ parseFloat(totalMem()).toFixed(2) }}</td>
                           </tr>
                       </tbody>

                   </table>
                   <div class="text-start">
                        Printed date: {{ format(new Date())}}
                   </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    .img-logo{
        width: 4rem;
    }
     .img-logo-2{
        width: 6rem;
    }

    .text-head{
        font-size: 16px;
        font-weight: bold;
        .sub-text{
            font-size: 14px;
            font-weight:normal; 
        }
    }
</style>
