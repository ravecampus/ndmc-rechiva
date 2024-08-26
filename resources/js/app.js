import './bootstrap';
import * as bootstrap from 'bootstrap';

import {createApp } from 'vue'

import app from './components/App.vue'
import router from './router'
import axios from 'axios'

import PSPDFKit from 'pspdfkit';

// PSPDFKit.defaults.withCredentials = true;
// PSPDFKit.defaults.baseURL = '/';

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

createApp(app).use(router).mount("#app")