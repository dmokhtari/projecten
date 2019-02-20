<template>
    <v-app light>
        <top-sidebar></top-sidebar>
    </v-app>
</template>
<script>
    import TopSidebar from './../components/TopSidebarComponent';
    export default {
        name: 'Home',
        components: {
            TopSidebar
        },
        data() {
            return {

            }
        }
    }
</script>
