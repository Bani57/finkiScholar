<template>
    <div id="app">
        <nav-bar id="header"></nav-bar>
        <router-view id="content"/>
        <div id="footer" class="ui segments">
            <div class="ui vertical segment">
                <div>Icons made by <a href="https://www.freepik.com/" title="Freepik">Freepik</a> from <a
                        href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a
                        href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0"
                        target="_blank">CC
                    3.0 BY</a></div>
            </div>
            <div class="ui vertical segment">
                <div>
                    <span class="text">&copy; Faculty of Computer Science and Engineering - Skopje 2019</span>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import Vue from 'vue'
    import NavBar from './NavBar.vue'

    export default Vue.extend({
        name: 'App',
        components: {
            NavBar
        },
    })
</script>

<style>
    #app {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        height: 100%;
    }

    #header {
        margin: 0;
        height: 4em;
    }

    #content {
        margin: 0 !important;
        height: calc(100% - 4em - 7em);
    }

    #footer {
        margin: 0;
        height: 7em;
        width: 100%;
    }
</style>