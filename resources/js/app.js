import './bootstrap';
// import bootstrap from 'bootstrap';

import {createApp } from 'vue'
import 'bootstrap-icons/font/bootstrap-icons.css'

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

import App from './components/App.vue'
import router from './router'
import axios from 'axios'

import PSPDFKit from 'pspdfkit';
import timeago from 'vue-timeago3'

window.PSPDFKit = PSPDFKit

axios.defaults.withCredentials = true;
axios.defaults.baseURL = '/';

import Swal from 'sweetalert2'
window.Swal  = Swal

const toast = Swal.mixin({
    toast:true,
    position:'center',
    timer:2000,
    showConfirmButton:false,
    timerProgressBar:true
})

window.toast = toast
const app = createApp(App);
app.use(timeago) 
app.component('VueDatePicker', VueDatePicker)
app.use(router).mount("#app")