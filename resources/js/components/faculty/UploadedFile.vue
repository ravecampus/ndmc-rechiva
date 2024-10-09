<script setup>
    import { ref, onMounted, watch} from "vue"
    import dayjs from 'dayjs'

    import genstatus from "../GenStatus.vue"

    const listData = ref([])
    let links = ref([])
    let searchData = ref('')
    let filterData = ref('')

    const typeofpapers = ref([])

   

    onMounted(()=>{
        getData()
        getTypeOfPapers()
    })

    const formatDate = (dateString)=>{
		const date = dayjs(dateString)
		return date.format('MMMM D, YYYY | hh:mm a')
	}

    const getData = ()=>{
        axios.get('/api/document?search='+searchData.value+'&filter='+filterData.value).then((res)=>{
            listData.value = res.data.data
            links.value = res.data.links
        })
    }

     watch(searchData, ()=>{
		getData()
	})


    const downLoadDoc = (data)=>{
        data.forEach(val => {
            downloadFile(val);
        });
    }

    const downloadFile = (data)=>{
        const blob = b64toBlob(data.base64, data.mime_type);
        const link = document.createElement('a')
        link.href = URL.createObjectURL(blob)
        link.download = data.original_name
        link.click()
        URL.revokeObjectURL(link.href)
    }

    const b64toBlob = (b64Data, contentType='', sliceSize=512) => {
            const byteCharacters = atob(b64Data);
            const byteArrays = [];
            for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                const slice = byteCharacters.slice(offset, offset + sliceSize);

                const byteNumbers = new Array(slice.length);
                for (let i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
                }

                const byteArray = new Uint8Array(byteNumbers);
                byteArrays.push(byteArray);
            }
                
            const blob = new Blob(byteArrays, {type: contentType});
            return blob;
    }
    

    const changePage = (link)=>{
        if(!link.url || link.active){
            return
        }
        axios.get(link.url).then((res)=>{
            listData.value = res.data.data
            links.value = res.data.links
        })
    }

    const getTypeOfPapers = ()=>{
        axios.get('/api/list-topapers').then((res)=>{
            typeofpapers.value = res.data
        })
    }

    const extractTOP = (data)=>{
        let ret = "";

        typeofpapers.value.forEach(val => {
            if(data.type_of_paper_id === val.id){
                ret = val.description
            }
        });

        return ret;
    }

    const deleteDocs = (data)=>{
           Swal.fire({
            title: "Are you sure? "+data.title,
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#26884b",
            cancelButtonColor: "#ffc107",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/api/document/${data.id}`).then((res)=>{
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success",
                        confirmButtonColor: "#26884b",
                        
                    });

                    getData();
                })
                
            }
        });
    }

    const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }
    const actionFilter = ()=>{
            getData();
    }

    // const viewLoad = (data) =>{
    //     console.log(data)
    // }

</script>


<template>
  <div class="container mt-5">
    <div class="row">
        <div class="col-12 mb-3 mb-lg-5">
            <div class="overflow-hidden card table-nowrap table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <!-- <h5 class="mb-0">{{ headerTitle}}</h5> -->
                    <!-- <router-link :to="{name:'faculty.upload_a'}" class="btn btn-primary btn-sm ">
                        <i class="bi bi-plus-circle"></i>
                        Add documents
                    </router-link> -->
                      <h4><i class="bi bi-file-earmark-arrow-up"> </i> &nbsp;Document Request</h4>
                </div>
                <div class="d-flex">
                    <div class="input-group input-group-sm p-2">
                        <input type="text" class="form-control" placeholder="Search..." aria-label="Example text with button addon" v-model="searchData" aria-describedby="button-addon1">
                        <button class="btn btn-primary" type="button" id="button-addon1">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                    <div class="input-group input-group-sm p-2">
                        <select class="form-select" id="inputGroupSelect02" v-model="filterData" @change="actionFilter">
                            <option :value="''">Choose...</option>
                            <option :value="0">Publish</option>
                            <option :value="1">Achive</option>
                        </select>
                        <label class="input-group-text bg-select"  for="inputGroupSelect02">Upload Type</label>
                    </div>

                </div>
                <div class="table-responsive">
                    
                    <table class="table mb-0">
                        <thead class="small text-uppercase bg-body text-muted">
                            <tr>
                                <th>Title</th>
                                <th>Document Files</th>
                                <th>Type of Papers</th>
                                <th>Date uploaded</th>
                                <th>Status</th>
                                <th>Upload Type</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="align-middle" v-for="(list,index) in listData" :key="index">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-text me-2 icon-file"></i>
                                       <strong class="text-primary"> {{ list.title }} </strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-start row">
                                        <div class="col img-uploaded d-flex mr-5">
                                                <img class="table-img d-flex" :src="'/img/pdf.jpg'"/>
                                                <span class="title truncate">{{ list.document_file.original_name }}</span>
                                        </div>
                                    </div>
                                </td> 
                                <td>{{ extractTOP(list) }}</td>
                                <td>{{ formatDate(list.created_at) }}</td>
                               
                                <td>
                                    <genstatus :statusData="list.status"/>
                                </td>
                                <td>
                                    <genstatus :uploadData="list.upload_type"/>
                                </td>
                                <td class="text-end">
                                    <div class="drodown">
                                        <a data-bs-toggle="dropdown" href="#" class="p-1" aria-expanded="false">
                                            <i class="bi bi-list list-primary"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dd-bg" style="">
                                            <router-link :to="{name:'faculty.document', params:{'id':list.id}}" class="dropdown-item dd-item">View Data </router-link>
                                            <!-- <router-link :to="{name:'faculty.editupload_p', params:{'id':list.id}}" class="dropdown-item dd-item">Edit </router-link> -->
                                            <!-- <a href="#" @click="downLoadDoc(list.document_file)" class="dropdown-item dd-item">Download files</a> -->
                                            <a href="#!" @click="deleteDocs(list)" class="dropdown-item dd-item">Delete </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6" v-show="!noData(listData)">
                                    No Result Found!
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                    <nav class="mt-2 d-flex justify-content-end me-2" aria-label="..." v-if="noData(listData)">
                        <ul class="pagination pagination-sm custom-page">
                            <li class="page-item" aria-current="page" v-for="(link, index) in links" :key="index">
                                <a class="page-link"
                                href="#"
                            
                                v-html="link.label"
                                :class="{active:link.active, disabled: !link.url }"

                                @click="changePage(link)"
                                ></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<style lang="scss">
    .bg-select{
        background-color:#26884b !important;
        color:#fff;
    }
    .list-primary{
        // background-color:#26884b !important;
        color:#26884b !important;
        font-size: 24px;
    }
</style>
