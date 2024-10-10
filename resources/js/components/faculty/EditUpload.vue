<script setup>
    import { ref, reactive, watch, onMounted } from "vue"
    import { useRouter, useRoute, viewDepthKey } from "vue-router"
    import DropZone from "./SingleDrop.vue"
    import dayjs from "dayjs"

    import textkeyword from "../InputTag.vue"
    import author from "../AuthorEdit.vue"
    import pdfViewer from "../PDFViewer.vue"

    // import { useBase64 } from '@vueuse/core'

    // const listInput = ref([])

    const filename = ref(null)
    const authors = ref([])
    const authors_ = ref([])
    const keywords = ref([])
 
    const pdfFile = ref({})
    let errors = ref([])
    const errs = ref(false)
    const formData = new FormData();
    const headtitle = ref("")

    const router = useRouter()
    const route = useRoute()
    const uploadtype = ref(true)

    const viewpdf = ref(false)

    const format = (d) => {
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();

        return  month+ "/" + day  + "/" + year;
    }

    onMounted(()=>{
        const id = route.params.id
        getDocs(id)
        ListTypeofPapers()
        ListofDepartment()
        setheader()
    })

   
    watch(route, ()=>{
        setheader()
    })

    const setheader = ()=>{
        if(route.name === 'faculty.editupload_p'){
            uploadtype.value = true
            headtitle.value = "PUBLISH"
            resetform()
            pdfFile.value = {};
            formData.delete("authors[]")
            formData.delete("keywords[]")
            errors.value = []
            enableButton()
        }else{
            headtitle.value = "ARCHIVE"
            uploadtype.value = false
            resetform()
            pdfFile.value = {};
            formData.delete("authors[]")
            formData.delete("keywords[]")
            errors.value = []
            enableButton()
        }
    }
    const fdata = () => ({
        title:"", 
        type_of_paper:"", 
        college_department:"",
        publication_date: "",
        issue_numbers:"",
        abstract:"",
        doi:"",
        publisher:"",
        keywords:[],
        authors:[],
        })
	const form = reactive(fdata())
    const resetform = () => Object.assign(form, fdata())
    
    const btnsave = ref("save changes")
    const btnenable = ref(false)
    const setPdf = ref("")

     const getDocs = (id)=>{
        axios.get('/api/document/'+id).then((res)=>{
            let data = res.data
            authors_.value = data.authors
            keywords.value = data.keywords
            form.id = data.id
            form.title = data.title
            form.type_of_paper = data.type_of_paper_id
            form.publication_date = data.publication_date
            form.abstract = data.abstract
            form.doi = data.doi
            form.issue_numbers = data.issue_numbers
            form.publisher = data.publisher
        })
    }

    const openFile = (e)=>{
        let data = e.dataTransfer.files;
        filterData(data)
        loadPdf(data)
    }

    const clickFile = ()=>{
        let data = document.querySelector('.fileupload').files
        filterData(data)
        loadPdf(data)
    }
    const filterData = (data)=>{
        if(['application/pdf'].includes(data[0].type)){
                pdfFile.value = data[0]
                errs.value = false;
        }else{
            errs.value = true;
        }
        enableButton()
    }

  const enableButton = ()=>{
      if(pdfFile.value.name != undefined){
            btnenable.value = true;
        }else{
             btnenable.value = false;
        }
  }

    const removeFile = (e)=>{
         
        let data = document.querySelector('.fileupload')
        data.value = ""
        // filesList.value.splice(e, 1)
        pdfFile.value = {};
        enableButton()
        setPdf.value = ""
        viewpdf.value = false
    }

    const keyInput = (data)=>{
        form.keywords = data
        // formData.delete("keywords[]")
        // keywords.value = data
    }
    const loadAuthor = (data)=>{
        form.authors = data
    //    formData.delete("authors[]")
    //    authors.value = data

    }

    const saveChanges = ()=>{
        console.log(form)
    }

    // const submitData = ()=>{
    //     btnsave.value = "processing..."
    //     formData.append('upload_type',uploadtype.value)
    //     formData.append('file',pdfFile.value)
    //     formData.append('authors[]', authors.value.length > 0 ? JSON.stringify(authors.value) : [])
    //     formData.append('keywords[]', keywords.value.length > 0 ? JSON.stringify(keywords.value): [])
    //     formData.append('title',form.title)
    //     formData.append('college_department',form.college_department)
    //     formData.append('type_of_paper',form.type_of_paper)
    //     formData.append('abstract',form.abstract)
    //     formData.append('issue_numbers',form.issue_numbers)
    //     formData.append('publication_date',form.publication_date =="" ? "" :  format(form.publication_date))
    //     formData.append('doi',form.doi)
    //     formData.append('publisher',form.publisher)
    //     axios.post('/api/document', formData,
    //             {
    //                  headers: {
    //                     'Content-Type': 'multipart/form-data'
    //                 },
    //             }).then((res)=>{
    //                 btnsave.value = "save changes"
    //                 // filesList.value = []
    //                 resetform()
    //                 formData.delete("authors[]")
    //                 formData.delete("keywords[]")
    //                 toast.fire({
    //                     icon:'success',
    //                     title:'File Documents Uploaded Successfully!'
    //                 })
    //                 router.push('/faculty/documents')
	// 		        errors.value = []
    //             }).catch((err)=>{
    //                 errors.value = err.response.data.errors
    //                 // console.log(errors.value)
	// 		        btnsave.value = "save changes"
    //             })
    // }

    const preventUpload = ()=>{
        errs.value = false
    }

    const typeofpapers = ref([])
    const departments = ref([])
    const ListTypeofPapers = ()=>{
        axios.get('/api/list-topapers').then((res)=>{
            typeofpapers.value = res.data
        })
    }

    const ListofDepartment = ()=>{
        axios.get('/api/list-departments').then((res)=>{
            departments.value = res.data
        })
    }

    const loadPdf = (data)=>{
      setPdf.value = window.URL.createObjectURL(data[0])
    }

    const viewReport = ()=>{
        viewpdf.value = !viewpdf.value
    }

   

</script>

<template>
    <div class="container mt-3">
        <div class="row">
            <div class="col-12 text-start card head-card">
                <div class="card-body p-1">
                    <h4><i class="bi" :class="uploadtype ? 'bi-tv':'bi-archive'"> </i> &nbsp;{{ headtitle }}</h4>
                </div>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-3">
                
                <DropZone @drop="openFile" :filePdf="pdfFile" @dragenter.prevent="preventUpload"  :class="{'is_error':errs}" @removeFile="removeFile"  @change="clickFile"></DropZone>
            </div>
            <div class="col-md-9">
                <div class="card text-start" v-if="form.id != undefined">
                        <div class="card-header bg-primary d-flex">
                         <strong> Form</strong>
                         <button type="button" v-if="setPdf != ''" @click="viewReport" class="btn btn-outline-success ms-auto">
                             <i class="bi text-white" :class="viewpdf ? 'bi-eye-slash' : 'bi-eye'"></i>
                         </button>
                        </div>
                        <div class="card-body" v-show="setPdf != '' && viewpdf" >
                            <pdfViewer v-if="setPdf != ''" :pdfFile="setPdf"></pdfViewer>
                        </div>
                        <div class="card-body row" v-show="!viewpdf">
                            <div class="form-group mb-3 col-6">
                                <label>Title</label>
                                <input type="text" v-model="form.title" class="form-control form-control-sm" placeholder="Enter Title">
                                <span class="text-danger" v-if="errors.title">{{errors.title[0]}}</span>
                            </div>
                            <div class="form-group mb-3 col-6">
                                <label>Type of Papers</label>
                                <select class="form-select form-select-sm" v-model="form.type_of_paper" >
                                        <option class="option" v-for="(list, index) in typeofpapers" :key="index"
                                        
                                        :value="list.id">
                                            {{ list.description }}
                                        </option>
                                </select>
                                <span class="text-danger" v-if="errors.type_of_paper">{{errors.type_of_paper[0]}}</span>
                            </div>
                            
                            <div class="form-group mb-3 col-6">
                                <label>Authors</label>
                                <author  :urlString="'/api/setting-author'" :authors="authors_" @loadFilter="loadAuthor"></author>
                                <!-- <input type="text" v-model="form.authors" class="form-control form-control-sm" placeholder="Enter Title"> -->
                                <span class="text-danger" v-if="errors['authors.0']">{{errors["authors.0"][0].replace(".0", "")}}</span>
                            </div>
                             <div class="form-group mb-3 col-6" v-if="uploadtype">
                                <label>Publication Date</label>
                                <VueDatePicker v-model="form.publication_date" :format="format" placeholder="Enter Publication date"></VueDatePicker>
                                <span class="text-danger" v-if="errors.publication_date">{{errors.publication_date[0]}}</span>
                            </div>
                             <div class="form-group mb-3 col-6" v-if="uploadtype">
                                <label>Volume/issue numbers</label>
                                <input type="text" v-model="form.issue_numbers" class="form-control form-control-sm" placeholder="Enter Issue Numbers">
                                <span class="text-danger" v-if="errors.issue_numbers">{{errors.issue_numbers[0]}}</span>
                            </div>
                             <div class="form-group mb-3 col-6" v-if="uploadtype">
                                <label>DOI</label>
                                <input type="text" v-model="form.doi" class="form-control form-control-sm" placeholder="Enter DOI">
                                <span class="text-danger" v-if="errors.doi">{{errors.doi[0]}}</span>
                            </div>
                             <div class="form-group mb-3 col-6" v-if="uploadtype">
                                <label>Abstract</label>
                                <textarea v-model="form.abstract" class="form-control form-control-sm" placeholder="Enter Abstract"></textarea>
                                <span class="text-danger" v-if="errors.abstract">{{errors.abstract[0]}}</span>
                            </div>
                            <div class="form-group mb-3 col-6" v-if="uploadtype">
                                <label>Keywords:  ...use Comma Sign to seperate words </label>
                                <textkeyword @showData="keyInput" :keywords="keywords"></textkeyword>
                                <!-- <input type="text" v-model="form.authors" class="form-control form-control-sm" placeholder="Enter Title"> -->
                                <span class="text-danger" v-if="errors['keywords.0']">{{errors["keywords.0"][0].replace(".0", "")}}</span>
                               
                            </div>
                              <div class="form-group mb-3 col-12" v-if="uploadtype">
                                <label>Publisher</label>
                                <input type="text" v-model="form.publisher" class="form-control form-control-sm" placeholder="Enter Publisher">
                                <span class="text-danger" v-if="errors.publisher">{{errors.publisher[0]}}</span>
                            </div>
            
                            <div class="btn-group btn-sm mt-3">
                                <button type="button" @click.prevent="saveChanges()" class="btn btn-primary">{{ btnsave}}</button>
        
                            </div>
                        </div>
                        <div class="card-footer">
                            <router-link :to="{name:'faculty.documents'}" >
                                <i class="bi bi-eye"></i>
                                View uploaded documents
                            </router-link> 
                        </div>
                </div>
            </div>
            
        </div>
    </div>
</template>
<style lang="scss" scoped>
    .head-card{
         margin-bottom: 20px;
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }
    .resize-div{
        overflow: auto;
        resize: horizontal;
    }
    .adjust-size{
        height: 50vh!important;
    }

    .dp__input{
        min-height: calc(1.5em + 0.5rem + calc(var(--bs-border-width)* 2)) !important;
        padding: 0.25rem 0.5rem !important;
        font-size: 0.875rem !important;
        border-radius: var(--bs-border-radius-sm) !important;
    }
</style>


