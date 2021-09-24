/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')
import Vue from 'vue'
import vuetify from './plugins/vuetify.js'
// import Vuetify from 'vuetify'
import VueRouter from 'vue-router'
import Vuex from "vuex"

import LoginComponent from './components/LoginComponent.vue'
import RegisterComponent from './components/RegisterComponent.vue'
import AppContainerComponent from './components/AppContainerComponent.vue'

window.Vue = require('vue').default

// Vue.use(Vuetify)
Vue.use(VueRouter)

Vue.use(Vuex)


const store = new Vuex.Store({
    state: {
        userToken: ""
    },
    mutations: {
        setToken(token){
            state.userToken = token;
        }
    },
    actions: {}

})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('login-component', LoginComponent);
// Vue.component('register-component', RegisterComponent);
// Vue.component('app-component', AppContainerComponent);


const routes = [
    {path: '/login', component: LoginComponent, name: "Login"},
    {path: '/register', component: RegisterComponent, name: "Register"},
    {path: '/', component: AppContainerComponent, name: "MainApp"}
];

const router = new VueRouter({
    routes
});

const app = new Vue({
    el: '#app',
    vuetify,
    router,
    store,
});

