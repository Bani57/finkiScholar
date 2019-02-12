<template>
    <div class="ui form" style="width: 50%; margin: 0 auto !important;  padding-top: 3em;">
        <div class="ui large header">Login</div>
        <div v-show="showMessage" class="ui error message">Incorrect email or password!</div>
        <div class="required field">
            <label>Email</label>
            <input type="email" name="email" v-model="email" placeholder="Enter email here..."/>
        </div>
        <div class="required field">
            <label>Password</label>
            <input type="password" name="password" v-model="password" placeholder="Enter password here..."/>
        </div>
        <div class="field">
            <div class="ui checkbox">
                <input type="checkbox" v-model="remember">
                <label>Remember me</label>
            </div>
        </div>
        <div @click="login()" class="ui blue submit button field">
            Login
        </div>
        <div @click="clearLoginFields()" class="ui clear button field" style="margin-left: 15px">
            Clear
        </div>
    </div>
</template>

<script>
    import toastr from 'toastr';
    import 'toastr/build/toastr.css';

    export default {
        name: "Login",
        data() {
            return {
                email: null,
                password: null,
                remember: false,
                showMessage: false,
            }
        },
        methods: {
            clearLoginFields() {
                this.email = null;
                this.password = null;
                this.remember = false;
            },
            checkIfUserLoggedIn() {
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
                    if (data)
                        window.location.href = '/';
                    return data
                });
            },
            login() {
                let user_data = {'email': this.email, 'password': this.password, 'remember': this.remember};
                let vm = this;
                fetch(`http://localhost:8000/users/login`, {
                    method: 'POST',
                    body: JSON.stringify(user_data),
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed to login user.'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error(reason.json(), 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data === "User logged in successfully.") {
                        vm.showMessage = false;
                        toastr.options.preventDuplicates = true;
                        toastr.success('User logged in successfully!', 'SUCCESS');
                        window.location.href = '/';
                    } else {
                        vm.showMessage = true;
                    }
                    return data
                })
            }
        },
        mounted() {
            this.checkIfUserLoggedIn();
        }
    }
</script>

<style scoped>
</style>