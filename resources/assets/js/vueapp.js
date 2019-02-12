import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

import App from './components/App';
import Home from "./components/Home";
import Login from "./components/Login";
import Register from "./components/Register";
import Profile from "./components/Profile";
import Publish from "./components/Publish";
import Archive from "./components/Archive";

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/login-page',
            name: 'login',
            component: Login
        },
        {
            path: '/register-page',
            name: 'register',
            component: Register
        },
        {
            path: '/profile',
            name: 'profile',
            component: Profile,
        },
        {
            path: '/publish-page',
            name: 'publish',
            component: Publish,
        },
        {
            path: '/archive',
            name: 'archive',
            component: Archive,
        }
    ],
});

const app = new Vue({
    el: '#app',
    components: {App},
    router,
});