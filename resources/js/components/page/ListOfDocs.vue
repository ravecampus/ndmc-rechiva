<script setup>
    import { ref, onMounted } from "vue"
    import { useRouter } from "vue-router"

    let docs = ref([])

    let links = ref([])

    const router = useRouter();

    onMounted(async() => {
        getDocs()
    })

    const getDocs = async() => {
        let res = await axios.get('/api/document').then((res)=>{
            docs.value = res.data.data
            links.value = res.data.links
        })
    }

    const changePage = (link)=>{

        if(!link.url || link.active){
            return
        }

        axios.get(link.url).then((res)=>{
            docs.value = res.data.data
            links.value = res.data.links
        })

    }

    const editData = (id)=>{
        router.push("/document/"+id)
    }

    const deleteData = (data) =>{
        Swal.fire({
            title: "Are you sure? "+data.title,
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/api/document/${data.id}`).then((res)=>{
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success",
                        
                    });

                    getDocs();
                })
                
            }
        });
    }

</script>


<template>
    <div class="container mt-5 mb-3">
        <div class="list-group" >
            
            <a href="#" class="list-group-item" v-for="doc in docs" :key="doc.id">
                <h4 class="list-group-item-heading">{{doc.title}}</h4>
                <p class="list-group-item-text">{{ doc.description }}</p>
                <div class="">
                    <button type="button" @click="editData(doc.id)" class="btn btn-primary">Edit</button>
                    <button type="button" @click="deleteData(doc)" class="btn btn-danger">Delete</button>
                </div>
            </a>

        </div>
        <nav class="mt-2" aria-label="...">
            <ul class="pagination">
                <!-- <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li> -->
                <li class="page-item" aria-current="page" v-for="(link, index) in links" :key="index">
                    <a class="page-link"
                     href="#"
                   
                     v-html="link.label"
                    :class="{active:link.active, disabled: !link.url }"

                    @click="changePage(link)"
                     ></a>
                </li>
                <!-- <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                <a class="page-link" href="#">Next</a>
                </li> -->
            </ul>
        </nav>
    </div>
    
</template>
