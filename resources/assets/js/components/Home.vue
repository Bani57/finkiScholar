<template>
    <div id="home">
        <img src="../../logo.png" class="ui centered medium image"/>
        <div v-show="isUserAuth" style="margin-top: 30px">
            <div class="ui huge header">
                {{'Welcome, ' + currentUserName + '!'}}
                <div class="sub header" style="margin-top: 20px">
                    Click on <b>My profile</b> to view your info, submit a paper if you're an author or manage your
                    reviews if you're a reviewer.<br/><br/>
                    Click on <b>Archive</b> to browse our huge library of submitted papers, you might find just what you
                    need!
                </div>
            </div>
            <div @click="logout()" class="huge ui blue button" style="margin-top: 50px; margin-right: 50px">
                Logout
            </div>
            <div @click="showDeleteAccountModal()" class="huge ui blue button" style="margin-top: 50px">
                Delete account
            </div>
            <div class="ui small modal" ref="deleteAccountModal">
                <div class="header">Are you sure?</div>
                <div class="content">
                    You won't be able to revert this and all of your submitted papers/reviews will be deleted.
                </div>
                <div class="actions">
                    <div class="ui green approve button">Yes</div>
                    <div class="ui red cancel button">No</div>
                </div>
            </div>
        </div>
        <div v-show="!isUserAuth" style="margin-top: 30px">
            <div class="ui huge header">
                Welcome to the FINKI Scholar site!
                <div class="sub header" style="margin-top: 20px">To proceed please <b>Login</b> if you already have an
                    account or click <b>Register</b> to join as an author or reviewer!
                </div>
            </div>
            <router-link to="/login-page" class="huge ui blue button" style="margin-top: 50px; margin-right: 50px">
                Login
            </router-link>
            <router-link to="/register-page" class="huge ui blue button" style="margin-top: 50px">
                Register
            </router-link>
        </div>

    </div>
</template>

<script>
    import toastr from 'toastr';
    import 'toastr/build/toastr.css';

    $(document).ready(function () {
        $('.no-sticky-animation').on('mousedown', function (event) {
            event.preventDefault()
        })
    });

    export default {
        name: "Home",
        data() {
            return {
                isUserAuth: null,
                currentUser: null,
                csrf: null,
            }
        },
        computed: {
            currentUserName() {
                if (this.currentUser)
                    return this.currentUser.username;
                else return null
            }
        },
        methods: {
            showDeleteAccountModal() {
                $(this.$refs.deleteAccountModal).modal('hide');
                let vm = this;
                $(this.$refs.deleteAccountModal).modal({
                    inline: true,
                    closable: false,
                    onApprove: function () {
                        vm.deleteUser()
                    }
                }).modal('show')
            },
            getCsrfToken() {
                let vm = this;
                fetch(`http://localhost:8000/csrf`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting CSRF token'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch CSRF token. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.csrf = data;
                    }
                    return data
                })
            },
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
            getCurrentUser() {
                let vm = this;
                fetch(`http://localhost:8000/users/current`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting current user'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch current user. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.currentUser = data;
                    }
                    return data
                })
            },
            logout() {
                fetch(`http://localhost:8000/users/logout`, {
                    method: 'POST',
                    body: JSON.stringify({'_token': this.csrf}),
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed to logout user'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Failed to logout user.', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        toastr.options.preventDuplicates = true;
                        toastr.success('Logout successful. Bye bye!', 'SUCCESS');
                        window.location.href = '/';
                    }
                    return data
                })
            },
            deleteUser() {
                let vm = this;
                fetch(`http://localhost:8000/users/${this.currentUser.id}/delete`, {
                    method: 'DELETE',
                    credentials: 'include',
                }).then((response) => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed deleting user account.'))
                    }
                }, (reason) => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Failed deleting user account.', 'ERROR');
                    return Promise.reject(reason)
                }).then((data) => {
                    if (data) {
                        toastr.options.preventDuplicates = true;
                        toastr.success('Goodbye ' + vm.currentUser.username + '!', 'SUCCESS');
                        $(this.$refs.deleteAccountModal).modal('hide');
                        vm.logout();
                    }
                    return data
                })
            },
        },
        mounted() {
            this.getCsrfToken();
            this.checkIfUserLoggedIn();
            this.getCurrentUser();
        },
        beforeDestroy: function () {
            $(this.$refs.deleteAccountModal).modal('hide')
        }
    }
</script>

<style scoped>
    #home {
        height: 100%;
    }
</style>