import {createRouter, createWebHistory } from "vue-router";

import pageOne from '../components/page/Main.vue'
import document from '../components/page/Two.vue'
import listDocument from '../components/page/ListOfDocs.vue'
import docViewer from '../components/page/Viewer.vue'
import signUp from '../components/page/Registration.vue'
import notFound from '../components/NotFound.vue'
import Admin from '../components/admin/Dashboard.vue'
import Settings from '../components/admin/Settings.vue'
import SettingAuthors from '../components/admin/SettingAuthor.vue'
import SettingCategories from '../components/admin/SettingCategory.vue'
import SettingTypeOfPapers from '../components/admin/SettingTypeOfPaper.vue'

import Faculty from '../components/faculty/Mainpage.vue'
import Upload from '../components/faculty/FileUpload.vue'

const routes = [
    {
        path:'/',
        name:'page.one',
        component: pageOne 
    },
    {
        path:'/document',
        name:'document',
        component: document 
    },
    {
        path:'/document/:id',
        name:'document.edit',
        component: document 
    },
    {
        path:'/list-documents',
        name:'listDocument',
        component: listDocument 
    },
    {
        path:'/sign-up',
        name:'signup',
        component: signUp 
    },
    {
        path:'/doc-viewer',
        name:'docviewer',
        component: docViewer 
    },
    {
        path:'/admin',
        name:'admin',
        component: Admin,
        children:[
            {
                path:'settings',
                name:'settings',
                component: Settings,
                children:[
                    {
                        path:'',
                        name:'setting.authors',
                        component: SettingAuthors,
                    },
                    {
                        path:'categories',
                        name:'setting.categories',
                        component: SettingCategories,
                    },
                    {
                        path:'type-of-papers',
                        name:'setting.typeofpapers',
                        component: SettingTypeOfPapers,
                    }
                ]
            }

        ]
    },
    {
        path:'/faculty',
        name:'faculty',
        component: Faculty,
        children:[
            // {
            //     path:'',
            //     name:'faculty.home',
            //     component: Faculty,
            // },
            {
                path:'upload',
                name:'faculty.upload',
                component: Upload,
            }
        ]
    },

    {
        path:'/:pathMatch(.*)*',
        name:'notfound',
        component: notFound 
    }

]

const router  = createRouter({
    history:createWebHistory(),
    routes
})

const openRoutes = [
    'signup',
    'page.one',
    'notfound'
];

const adminRoutes = [
    'listDocument',
    'page.one',
    'document',
    'document.edit',
    'notfound',
    'docviewer',
    
];

console.log(window.winsdev)

// router.beforeEach((to, from, next)=>{
//     if(!window.winsdev.isLoggedin){
//         if(openRoutes.includes(to.name)){
//             return next();
//         }
//     }else{
//         let user = window.winsdev.user;
//         if(adminRoutes.includes(to.name)){
//             return next();
//         }
//     }

    
   
// });



export default router