<template>
    <v-app :dark="darkTheme">
        <top-sidebar @theme-changed="getTheme"></top-sidebar>
        <v-content>
            <router-view></router-view>
        </v-content>
    </v-app>
</template>
<script>
    import TopSidebar from '../components/TopSidebarComponent';
    export default {
        name: 'App',
        components: {
            TopSidebar
        },
        data() {
            return {
                darkTheme: false,
            }
        },
        created() {
            this.getTheme()
        },
        methods: {
            getTheme(event) {
                setTimeout(() => {
                    this.darkTheme = localStorage.getItem('theme') === 'dark'
                }, 200)
            },
        }
    }
</script>
