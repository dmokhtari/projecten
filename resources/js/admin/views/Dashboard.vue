<template>
    <v-app light>
        <top-sidebar></top-sidebar>
    </v-app>
</template>
<script>
    import TopSidebar from './../components/TopSidebarComponent'
    export default {
        name: 'Dashboard',
        title: 'Admin Page',
        components: {
            TopSidebar
        },
        data() {
            return {

            }
        },
        created() {
            this.getTest()
        },
        methods: {
            getTest() {
                axios.get('/api/testing')
                    .then(response => console.log(response))
                    .catch(response => console.log(response))
            }
        }
    }
</script>
