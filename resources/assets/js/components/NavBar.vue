<template>
    <div class="nav-bar ui menu" ref="navBar" style="border-radius: 0 !important">
        <router-link class="header item" to="/">
            <img src="../../../assets/logo.png" alt="">
            <span style="margin-left: 10px">FinkiScholar</span>
        </router-link>
        <router-link v-if="isUserAuth" to="/profile" class="header item">My profile</router-link>
        <router-link v-if="isUserAuth" to="/archive" class="header item">Archive</router-link>

        <div class="right menu">
            <div class="header item">{{currentDate}}</div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import toastr from 'toastr';
    import 'toastr/build/toastr.css'

    export default {
        name: 'NavBar',
        data() {
            return {
                moment: moment,
                isUserAuth: false,
            }
        },
        components: {},
        computed: {
            currentDate() {
                return moment().format('DD.MM.YYYY')
            },
        },
        methods: {
            checkIfUserLoggedIn() {
                let vm = this;
                fetch(`http://localhost:8000/users/check`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed checking whether any user is logged in'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Failed checking whether any user is logged in. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.isUserAuth = data;
                    }
                    return data
                })
            },
        },
        mounted() {
            this.checkIfUserLoggedIn();
        }
    }
</script>

<style scoped>
</style>
