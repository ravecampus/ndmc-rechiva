<script setup>
    import { ref, onMounted, reactive, watch } from "vue"
    import { useRoute, useRouter } from "vue-router"
    import dayjs from "dayjs"
    import genstatus from "../GenStatus.vue"
    import pdfViewer from "../PDFViewer.vue"

    const route = useRoute();
    const router = useRouter();
    const doc_id = route.params.id
    const typeofpaper = ref({})

    let errors = ref([])

    const docData = ref({})
    const file = ref({})
    const pdffile = ref("")
    const viewpdf = ref(false)
     
    watch(()=> route.params.id,(newId, oldId)=>{
        getDocInformation(newId)
        sendMessage()
    })


    onMounted(()=>{
        getDocInformation(doc_id)
    })

    const getDocInformation = (id)=>{
        axios.get('/api/document/'+id).then((res)=>{
            docData.value = res.data
            file.value = docData.value.document_file
            typeofpaper.value = res.data.typeofpaper
            docFile(file.value)
        })
    }

    const formatDate = (dateString)=>{
		const date = dayjs(dateString)
		return date.format('MMMM D, YYYY')
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

    const docFile = (data)=>{
        const blob = b64toBlob(data.base64, data.mime_type);
 
        pdffile.value =  URL.createObjectURL(blob);
        // pdffile.value = "data:application/pdf;base64,"+data.base64;
        // window.URL.revokeObjectURL(blob);
    
    }

    const handleLoaded = (instance)=>{
        console.log(instance)
    }

    const viewReport = ()=>{
        viewpdf.value = !viewpdf.value
    }
    const showLink = (data)=>{
        // window.location.href = data;
        window.open(data, '_blank');
    }

    const btnSend = ref("Send")
    const trun = ref(true)
    const clickTrun = ()=>{
        trun.value = !trun.value
    }
    const form = reactive({
        message:"",
        document_id:doc_id,
        prove:0
    })
    const sendMessage = ()=>{
        btnSend.value = "Sending..."
         axios.post('/api/admin-feedback', form).then((res)=>{
            getDocInformation(doc_id)
            toast.fire({
				icon:'success',
				title:'Feedback Message sent!'
            })
            btnSend.value = "Send"
            form.message = ""
        }).catch((err)=>{
            btnSend.value = "Send"
            errors.value = err.response.data.errors
        })
    }
    const extractUser = (data)=>{
        if(data.role == 2){
            return "Administrator"
        }else{
            return data.first_name+ " "+data.last_name
        }
    }

</script>
<template>
    <div class="container mt-3 mb-5">
        <div class="row bg-white p-2 m-1 rounded">
            <div class="col-md-12">
                <h4 class="text-start m-0 mb-3">
                    <span class="bi bi-file-earmark-richtext"></span>
                    Document
                </h4>
            </div>
            <hr>
            <div class="col-md-4 col-lg-4">
                <div class="card doc-card custom-border">
                 <div class="side-badge w-auto">
                     <genstatus :uploadDataView="docData"></genstatus>
                 </div>
                 <div class="text-end p-2">
                    <button type="button" @click="viewReport" class="btn btn-outline-success btn-sm"> <i class="bi" :class="viewpdf ? 'bi-eye-slash':'bi-eye'"></i></button>
                 </div>
                    <div class="card-body">
                        <div class="main-img mt-2 mb-0">
                            <img  class="img-pdf" :src="'/img/pdf.jpg'">
                        </div>
                        <div class="upload-status border p-2">
                            <strong>{{ typeofpaper.description }}</strong>
                            <genstatus :statusData="docData.status"></genstatus>
                        </div>
                        <hr class="p-0" v-if="docData.upload_type == 0">
                        <div class="text-start txt-grey" v-if="docData.upload_type == 0">
                             <span class="fw-bold"> DOI:</span> <a @click="showLink(docData.doi)"  href="#">{{ docData.doi}}</a>
                        </div>
                        <hr class="mt-0 p-0" v-if="docData.upload_type == 0">
                        <div class="text-start txt-grey" v-if="docData.upload_type == 0">
                           <span class="fw-bold"> Volume/Issue no.:</span> {{ docData.doi}}
                        </div>
                        <hr class="mt-0 p-0" v-if="docData.upload_type == 0">
                         <div class="text-start txt-grey" v-if="docData.upload_type == 0">
                           <span class="fw-bold"> Publication:</span> {{ docData.publisher }}
                        </div>
                        <hr class="mt-0 p-0" v-if="docData.upload_type == 0">
                        <div class="text-start txt-grey" v-if="docData.upload_type == 0">
                           <span class="fw-bold"> Department:</span> {{ docData.department != null ? docData.department.description : ""}}
                        </div>
                        <hr class="mt-0 p-0" v-if="docData.upload_type == 0">
                    </div>
                    <div class="card-footer bg-white p-0">
                        <a href="#" class="link-download" @click="downloadFile(file)">
                            <span class="bi bi-download"></span>&nbsp;<small class="text-download">{{ file.original_name }}</small>
                        </a>
                    </div>
                    <div class="card-footer bg-pub">
                        <div class="author-list text-start">
                            <i>Author/s:</i>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item text-primary p-1" 
                                v-for="(list, index) in docData.authors" :key="index">
                                {{ list.first_name }}
                                {{ list.middle_name }}
                                {{ list.last_name }}
                                </li>
                            
                            </ul>
                        </div>
                    </div>

                </div>
                
            </div>
            <div class="col-md-8 col-lg-8">
                
                <div class="card doc-card">
                    <div class="card-body">
                        <div v-show="viewpdf" class="content-doc text-primary border border-success border-2 pt-2">
                            Infomation
                            <pdfViewer :pdfFile="pdffile" @loaded="handleLoaded"></pdfViewer>
                        </div>
                        <div class="info-doc" v-show="!viewpdf">
                            <h5 class="head-title text-start">{{ docData.title }}</h5>
                            <div class="abstract">
                                <blockquote class="blockquote" v-if="docData.upload_type == 0">
                                <small class="text-primary">Abstract: </small> 
                                    {{ docData.abstract }}
                                </blockquote>
                                <!-- <div class="link-doi mt-3" v-if="docData.upload_type == 0">
                                    <button type="button" class="btn btn-success" @click="showLink(docData.doi)">
                                        <i class="bi bi-link-45deg"></i>
                                        Visit >>
                                    </button>
                                </div> -->
                               <hr>
                                <div class="d-flex flex-wrap pub-date" v-if="docData.upload_type == 0">
                                    <span class="label">
                                        Publication date :
                                    </span>
                                    <div class="content"> {{ formatDate(docData.publication_date) }}</div>
                                    <div class="d-flex position-absolute custom-w justify-content-end">
                                       <span class="key-title">Keywords:</span>
                                        <span class="badge bg-success me-1" 
                                        v-for="(list, index) in docData.keywords" :key="index" >{{ list.description }}</span>
                                        
                                    </div>
                                    
                                </div>
                                 
                            </div>
                        </div>
                    </div>
                </div>
 
            </div>
        </div>
        <div class="row">
            <div class="col-md-12"> 
                <div class="card doc-card mt-3">
                    <div class="card-body text-start" v-for="(list, index) in docData.feedback" :key="index">
                        <div class="d-flex flex-start align-items-center">
                            <img class="rounded-circle shadow-1-strong me-3"
                                  :src="list.sender.image == null ? '/img/user.png' : 'data:'+list.sender.extension+';base64,'+list.sender.image"
                                alt="avatar" width="60"
                                height="60" />
                            <div>
                                <h6 class="fw-bold text-primary mb-1">
                                    {{ extractUser(list.sender)}}
                                </h6>
                                <p class="text-muted small mb-0">
                                Sent -
                                 <timeago :datetime="list.created_at"/>
                                </p>
                            </div>
                        </div>

                            <p class="mt-2 pb-0">
                                {{ list.message }}
                            </p>

                            <hr class="p-0 m-0">
                    </div>
                    <div class="card-body text-start">
                        <h4>Comment & Suggestions</h4>
                        <div class="form-group">
                            <textarea v-model="form.message" class="form-control w-75" placeholder="Enter Message"></textarea>
                            <span class="text-danger" v-if="errors.message">{{errors.message[0]}}</span>
                        </div>

                        <div class="btn-group mt-3">
                            <button type="button" @click.prevent="sendMessage()" class="btn btn-primary">
                              <i class="bi bi-send"></i>  {{btnSend}}
                            </button>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    .txt-grey{
        color: #868686;
    }
    .doc-card{
        margin-bottom: 20px;
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
        //  border:#dfdfdf6b solid 1px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0);
    }
    .custom-border{
        border:#dfdfdf6b solid 1px;
    }
    .img-pdf{
        // width: 100%;
        width:55%;
        height:100%;
        object-fit:cover;
    }
    .head-title{
        font-size: 1.5rem;
    }
    .abstract{
        text-align: start;
        font-size: 20px;
        color: #535353;
        .pub-date{
            display: block;
            // margin-top: 25px;
            font-weight: bold;
            color: #868686;
            .label{
                font-size: 11px !important;
                font-style: italic;
                margin-right: 5px;
            }
            .content{
                font-size: 16px;
                margin-top: -5px;
            }
            
        }
    }

    .side-badge{
        padding: 4px 15px;
        text-align: start;
        width: auto !important;
        font-size: 16px;
        color:#fff;
        font-style: italic;
        font-weight: bold;
        background: #26884b;
        position: absolute;
        border-bottom-right-radius: 25%40%;
    }

    .bg-pub{
        background-color: #ffffff !important;
        color: #797979;
    }

    .link-download{
        font-size: 2em;
        font-weight: bold !important;
        .text-download{
            font-size: .6em !important;
        }
    }
    .author-list{
        font-weight: 600 !important;
    }
     .key-title{
        font-size: 12px;
        font-style: italic;
        margin-right: .5rem;
    }
    
</style>
