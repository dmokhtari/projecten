<template>
    <v-app :dark="darkTheme">
        <top-sidebar @theme-changed="getTheme"></top-sidebar>
        <v-content>
            <v-container class="pb-0">
                <v-layout>
                    <v-flex xs12>
                        <v-chip>
                            <v-breadcrumbs>
                                <v-breadcrumbs-item v-for="(breadcrumb, index) in breadcrumbList"
                                                    :key="index"
                                                    :disabled="breadcrumb.disabled"
                                                    :to="breadcrumb.to"
                                                    :exact="true">
                                    <span v-if="breadcrumb.text !== 'Home'">{{ breadcrumb.text }}</span>
                                    <span v-else>
                                        <font-awesome-icon :icon="['fas', 'home']"></font-awesome-icon>
                                    </span>
                                </v-breadcrumbs-item>
                            </v-breadcrumbs>
                        </v-chip>
                    </v-flex>
                </v-layout>
            </v-container>
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
                breadcrumbList: []
            }
        },
        watch: {
            '$route'() {
                this.updateBreadcrumb()
            }
        },
        created() {
            this.getTheme()
            this.updateBreadcrumb()
        },
        methods: {
            getTheme(event) {
                setTimeout(() => {
                    this.darkTheme = localStorage.getItem('theme') === 'dark'
                }, 200)
            },
            updateBreadcrumb() {
                this.breadcrumbList = this.$route.meta.breadcrumb
            }
        }
    }
</script>
