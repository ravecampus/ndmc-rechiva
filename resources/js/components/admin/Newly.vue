<script setup>
    import { ref, onMounted, reactive, watch  } from "vue"
    import { useRouter, useRoute  } from "vue-router"

   const router = useRouter();
    const route = useRoute();
    onMounted(()=>{
        const id = route.params.id;
        getAuthUser(id)
    })


    watch(()=> route.params.id,(newId, oldId)=>{
        getAuthUser(newId)
    })

 

    const user = ref({})
    let errors = ref([])

    const fdata = () => ({
        first_name:"",
        middle_initial:"",
        last_name:"",
        email:"",
        id:null,
        password:"",
        password_confirmation:"",
         })
	const form = reactive(fdata())
	const resetform = () => Object.assign(form, fdata())

    const getAuthUser = (id)=>{
        axios.get('/api/users-faculty/'+id).then((res)=>{
            user.value = res.data;
            let data = res.data
            form.first_name = data.first_name;
            form.middle_initial = data.middle_initial;
            form.last_name = data.last_name;
            form.email = data.email;
            form.id = data.id;
        })
    }

    const changeAccess = (data)=>{
        Swal.fire({
            title:"User Activation",
            text: data.activate == 0 ? ' Do you want to Activate' : 'Do you want to Deactivate' +" this account!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#26884b",
            cancelButtonColor: "#ffc107",
            confirmButtonText: "Yes"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.get(`/api/users/${data.id}`).then((res)=>{
                    Swal.fire({
                        title: data.activate == 0? 'Activated':'Deactivated',
                        text: data.activate == 0? "User has been activate":"User has been deactivate",
                        icon: "success",
						confirmButtonColor: "#26884b",
                    });
                    getAuthUser(data.id);
                })
                
            }
        });
    }

</script>


<template>
<div class="container rounded bg-white mb-5 mt-3">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img class="rounded-circle" width="150px" :src="'/img/user.png'">
                <span class="font-weight-bold fw-bold">
                    {{ user.first_name }}
                    {{ user.middle_initial }}.
                    {{ user.last_name }}
                </span>
                <span class="text-black-50">{{ user.email }}</span>
                <span> </span></div>
        </div>
        <div class="col-md-5 border-right text-start">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">User Activation</h4>
                </div>
                <div class="badge bg-success fs-3 p-2">
                    {{ user.activate == 0 ? " Deactivated" : 'Activated'}}
                </div>
                <div class="mt-5">
                    <button class="btn btn-outline-success" @click.prevent="changeAccess(user)" type="button">{{ user.activate == 0 ? " Activate?" : 'Deactivate?' }}</button>
                </div>
            </div>
        </div>
    
    </div>
</div>

</template>
<style lang="scss" scoped>
    .font-weight-bold{
        font-size: 1.2rem;
    }
    .text-black-50{
        font-size: 1rem;
    }
</style>


