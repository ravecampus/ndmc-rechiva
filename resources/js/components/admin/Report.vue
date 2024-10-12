<script setup>
    import { ref, reactive } from "vue"

    const fdata = () => ({
            date_from:"",
            date_to:""
        })
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

</script>


<template>
    <div class="container">
        <div class="row bg-white rounded mt-3 d-print-none">
           <h4  class="text-start mt-3">Summary Report</h4>
           <div class="col-md-3">
               <div class="card border">
                   <div class="card-body text-start">
                        <div class="form-group mb-3">
                            <label>From :</label>
                            <VueDatePicker v-model="form.date_from" :format="format" placeholder="Enter Date From"></VueDatePicker>
            
                        </div>
                         <div class="form-group mb-3">
                            <label>To :</label>
                            <VueDatePicker v-model="form.date_to" :format="format" placeholder="Enter Date To"></VueDatePicker>
            
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success btn-sm">
                                <i class="bi bi-bullseye"></i>
                                Generate
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
                   <table class="table table-bordered">
                       <thead>
                           <tr>
                               <th>College Department</th>
                               <th>Number of Submission</th>
                               <th>Number of Downloads</th>
                               <th>Number of Members</th>
                           </tr>
                       </thead>
                       <tbody>
                           <tr>
                               <td>222</td>
                               <td>333</td>
                               <td>333</td>
                               <td>3333</td>
                           </tr>
                           <tr>
                              
                               <td class="text-start fw-bold">TOTAL</td>
                               <td>3333</td>
                               <td>3333</td>
                               <td>3333</td>
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
                   <div class="fs-6 fw-bold">System Report</div>
                   <div>Date: Feb 1, 2024 - Dec 9, 2024</div>
                   <table class="table table-bordered">
                       <thead>
                           <tr>
                               <th>College Department</th>
                               <th>Number of Submission</th>
                               <th>Number of Downloads</th>
                               <th>Number of Members</th>
                           </tr>
                       </thead>
                       <tbody>
                           <tr>
                               <td>222</td>
                               <td>333</td>
                               <td>333</td>
                               <td>3333</td>
                           </tr>
                           <tr>
                              
                               <td class="text-start fw-bold">TOTAL</td>
                               <td>3333</td>
                               <td>3333</td>
                               <td>3333</td>
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
