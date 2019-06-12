<template>
    <v-app :dark="darkTheme">
        <top-sidebar></top-sidebar>
        <v-content>
            <router-view></router-view>
        </v-content>
        <message-box></message-box>
    </v-app>
</template>
<script>
    import TopSidebar from './../components/TopSidebarComponent';
    import MessageBox from './../../shared/components/MessageBox';
    export default {
        name: 'Admin',
        components: {
            TopSidebar,
            MessageBox
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
