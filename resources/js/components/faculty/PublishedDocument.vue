<script setup>
    import { ref, onMounted } from "vue"
    import dayjs from 'dayjs'

    const listData = ref([])

    onMounted(()=>{
        getData()
    })

    const formatDate = (dateString)=>{
		const date = dayjs(dateString)
		return date.format('MMMM d, YYYY | hh:mm a')
	}

    const getData = ()=>{
        axios.get('/api/document').then((res)=>{
            console.log(res.data.data)
            listData.value = res.data.data
        })
    }



</script>


<template>
  <div class="container mt-5">
    <div class="row">
        <div class="col-12 mb-3 mb-lg-5">
            <div class="overflow-hidden card table-nowrap table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">For Publishing</h5>
                    <router-link :to="{name:'faculty.upload'}" class="btn btn-primary btn-sm ">
                        <i class="fa fa-plus"></i>
                        Add documents
                    </router-link>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="small text-uppercase bg-body text-muted">
                            <tr>
                                <th>Title</th>
                                <th>Document Files</th>
                                <th>Date uploaded</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="align-middle" v-for="(list,index) in listData" :key="index">
                                <td>
                                    <div class="d-flex align-items-center">
                                       <strong class="text-primary"> {{ list.title }} </strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-start">
                                        <div class="img-uploaded d-flex mr-5" v-for="(sublist, idx) in list.document_file" :key="idx">
                                                <img class="table-img d-flex" :src="sublist.mime_type == 'application/pdf' ? '/img/pdf.jpg' : '/img/image.jpg'"/>
                                                <span class="title truncate">{{ sublist.original_name }}</span>

                                                
                                        </div>
                                        {{sublist.mime_type}}
                                    </div>
                                </td>
                                <td>{{ formatDate(list.created_at) }}</td>
                                <td></td>
                                <td class="text-end">
                                    <div class="drodown">
                                        <a data-bs-toggle="dropdown" href="#" class="btn p-1" aria-expanded="false">
                                  <i class="fa fa-bars" aria-hidden="true"></i>
                                </a>
                                        <div class="dropdown-menu dropdown-menu-end dd-bg" style="">
                                            <a href="#!" class="dropdown-item dd-item">Download files</a>
                                            <a href="#!" class="dropdown-item dd-item">Delete </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
