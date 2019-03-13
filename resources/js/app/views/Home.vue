<template>
    <v-app :dark="theme">
        <top-sidebar></top-sidebar>
    </v-app>
</template>
<script>
    import TopSidebar from '../components/TopSidebarComponent';
    export default {
        name: 'Home',
        components: {
            TopSidebar
        },
        data() {
            return {
                theme: false
            }
        },
        created() {
            this.getTheme()
            eventHub.$on('theme-changed', this.getTheme)
        },
        beforeDestroy() {
            eventHub.$off('theme-changed')
        },
        methods: {
            getTheme() {
                setTimeout(() => {
                    this.theme = localStorage.getItem('theme') === 'dark'
                }, 200)
            }
        }
    }
</script>
