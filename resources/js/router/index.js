import {createRouter, createWebHistory, useRouter } from "vue-router";

import pageOne from '../components/page/Main.vue'
import document from '../components/page/Two.vue'
import listDocument from '../components/page/ListOfDocs.vue'
import docViewer from '../components/page/Viewer.vue'
// import signUp from '../components/page/Registration.vue'
import notFound from '../components/NotFound.vue'

import Mainpage from '../components/public/Mainpage.vue'
import Signin from '../components/public/Signin.vue'
import Signup from '../components/public/Signup.vue'
import Search from '../components/public/SearchData.vue'
import PublicDocument from '../components/public/ViewDocument.vue'
import PublicPage from '../components/public/PublicPage.vue'
import About from '../components/public/About.vue'

import Admin from '../components/admin/Dashboard.vue'
import Dashboard from '../components/admin/Landing.vue'
import Settings from '../components/admin/Settings.vue'
import SettingAuthors from '../components/admin/SettingAuthor.vue'
import SettingDepartments from '../components/admin/SettingDepartment.vue'
import SettingTypeOfPapers from '../components/admin/SettingTypeOfPaper.vue'
import Request from '../components/admin/Request.vue'
import Published from '../components/admin/Published.vue'
import Archived from '../components/admin/Archived.vue'
import Canceled from '../components/admin/Canceled.vue'
import Account from '../components/admin/Account.vue'
import RequestDoc from '../components/admin/ViewRequestDoc.vue'
import Users from '../components/admin/User.vue'
import Newly from '../components/admin/Newly.vue'

import InputTag from '../components/AuthorFilter.vue'

import Faculty from '../components/faculty/Mainpage.vue'
import Upload from '../components/faculty/FileUpload.vue'
import UploadedFile from '../components/faculty/UploadedFile.vue'
import ViewDocument from '../components/faculty/ViewDocument.vue'
import ApprovedDoc from '../components/faculty/ApprovedDoc.vue'
import FacultyPublished from '../components/faculty/Published.vue'
import FacultyArchived from '../components/faculty/Archived.vue'
import FacultyCanceled from '../components/faculty/Canceled.vue'
import MyAccount from '../components/faculty/Myaccount.vue'

const routes = [
    {
        path:'/',
        name:'rechiva',
        component: Mainpage,
        children:[
            {
                path:'',
                name:'publicpage',
                component: PublicPage 
            },
           
            {
                path:'/sign-in',
                name:'signin',
                component: Signin 
            },
            {
                path:'about',
                name:'about',
                component: About
            },
            {
                path:'/sign-up',
                name:'signup',
                component: Signup
            },
            {
                path:'/search',
                name:'search',
                component: Search
            },
            {
                path:'/document/:id',
                name:'main.document',
                component: PublicDocument
            },
        ]
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
                path:'',
                name:'dashboard',
                component: Dashboard 
            },
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
                        path:'departments',
                        name:'setting.departments',
                        component: SettingDepartments,
                    },
                    {
                        path:'type-of-papers',
                        name:'setting.typeofpapers',
                        component: SettingTypeOfPapers,
                    }
                ],
            },
            {
                path:'request',
                name:'admin.request',
                component: Request,
            },
            {
                path:'request-document/:id',
                name:'admin.requestdoc',
                component: RequestDoc,
            },
            {
                path:'published',
                name:'admin.published',
                component: Published,
            },
            {
                path:'archived',
                name:'admin.archived',
                component: Archived,
            },
            {
                path:'canceled',
                name:'admin.canceled',
                component: Canceled,
            },
            {
                path:'account',
                name:'admin.account',
                component: Account,
            },
            {
                path:'users',
                name:'admin.users',
                component: Users,
            }, 
            {
                path:'user/:id',
                name:'admin.user',
                component: Newly,
            }
            

        ]
    },
    {
        path:'/faculty',
        name:'faculty',
        component: Faculty,
        children:[
            {
                path:'upload-publish',
                name:'faculty.upload_p',
                component: Upload,
            },
            {
                path:'upload-archive',
                name:'faculty.upload_a',
                component: Upload,
            },
            {
                path:'documents',
                name:'faculty.documents',
                component: UploadedFile,
            },
            {
                path:'document/:id',
                name:'faculty.document',
                component: ViewDocument,
            },
            {
                path:'myaccount',
                name:'faculty.myaccount',
                component: MyAccount,
            },
            {
                path:'approved-docs',
                name:'faculty.approved',
                component: ApprovedDoc,
                children:[
                    {
                        path:'published',
                        name:'faculty.published',
                        component: FacultyPublished,
                    },
                    {
                        path:'archived',
                        name:'faculty.archived',
                        component: FacultyArchived,
                    },
                    {
                        path:'canceled',
                        name:'faculty.canceled',
                        component: FacultyCanceled,
                    }
                ]
            },
            {
                path:'input-tag',
                name:'faculty.inputtag',
                component: InputTag,
            },
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
    'about',
    'publicpage',
    'rechiva',
    'signin',
    'search',
    'signup',
    'main.document',
    'notfound'
    
];

const adminRoutes = [
    'rechiva',
    'admin',
    'dashboard',
    'settings',
    'setting.authors',
    'setting.departments',
    'setting.typeofpapers',
    'admin.request',
    'admin.requestdoc',
    'admin.published',
    'admin.archived',
    'admin.account',
    'admin.canceled',
    'notfound',
    'admin.users',
    'admin.user'
    
];

const facultyRoutes = [
    'about',
    'rechiva',
    'publicpage',
    'signin',
    'search',
    'signup',
    'main.document',
    'notfound',
    'faculty',
    'faculty.upload_a',
    'faculty.upload_p',
    'faculty.documents',
    'faculty.document',
    'faculty.approved',
    'faculty.published',
    'faculty.canceled',
    'faculty.archived',
    'faculty.myaccount'
    
];

router.beforeEach((to, from, next)=>{
    console.log(window.winsdev.user)
    if(!window.winsdev.isLoggedin){
        if(openRoutes.includes(to.name)){
            return next();
        }else{
            return next({ name: 'notfound' })
        }
    }else{
        let user = window.winsdev.user;
        if(adminRoutes.includes(to.name) && user.role === 2){
            return next();
        }else if(facultyRoutes.includes(to.name) && user.role === 1){
            return next(); 
        }else{
            return next({ name: 'notfound' })
        }
    }
});



export default router