<template>
    <div>
        <v-app-bar
            app
            clipped-left
            scroll-off-screen
        >
            <v-btn text icon @click="sidebar = !sidebar">
                <font-awesome-icon class="title" :icon="['fas', 'bars']"></font-awesome-icon>
            </v-btn>
            <v-toolbar-title>
                <a href="/">
                    <img src="/assets/img/logo.png" width="100" alt="Summa College logo">
                </a>
            </v-toolbar-title>
            <v-chip class="ml-5 hidden-xs-only">
                <v-breadcrumbs :items="breadcrumbList" divider=">">
                    <v-breadcrumbs-item slot="item"
                                        slot-scope="{ item }"
                                        :disabled="item.disabled"
                                        :to="item.to"
                                        :exact="true">
                        <span v-if="item.text !== 'Home'">{{ item.text }}</span>
                        <span v-else>
                            <font-awesome-icon :icon="['fas', 'home']"></font-awesome-icon>
                        </span>
                    </v-breadcrumbs-item>
                </v-breadcrumbs>
            </v-chip>
            <v-spacer></v-spacer>
            <v-menu offset-y :nudge-width="200" :close-on-content-click="false">
                <template v-slot:activator="{ on }">
                    <v-btn icon large v-on="on">
                        <v-avatar color="primary" size="40">
                            <font-awesome-icon :icon="['fas', 'user']" class="white--text"></font-awesome-icon>
                        </v-avatar>
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item v-for="(item, i) in userMenu" :key="i" :to="item.slug">
                        <v-list-item-action>
                            <font-awesome-icon :icon="['fas', item.icon]"></font-awesome-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>{{ item.text }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item v-if="userRole === 1" @click="goToDashboard">
                        <v-list-item-action>
                            <font-awesome-icon :icon="['fas', 'user-cog']"></font-awesome-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Dashboard</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item>
                        <v-list-item-action>
                            <v-switch v-model="theme" value="dark"></v-switch>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Donker theme</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item v-for="(item, i) in logoutMenu" :key="i+1" @click="requestLogOut(item.slug)">
                        <v-list-item-action>
                            <font-awesome-icon :icon="['fas', item.icon]"></font-awesome-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>{{ item.text }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                </v-list>
            </v-menu>
        </v-app-bar>


        <v-navigation-drawer
            clipped
            v-model="sidebar"
            app
            width="200"
        >
            <v-list dense>
                <v-list-item v-for="(item, index) in navigationMenu"
                             :key="index"
                             :to="item.slug"
                             active-class="active-page"
                             :class="activePage === item.slug ? 'active-page' : ''"
                >
                    <v-tooltip right>
                        <template v-slot:activator="{ on }">
                            <v-list-item-action v-on="on">
                                <font-awesome-icon class="title" :icon="['fas', item.icon]"></font-awesome-icon>
                            </v-list-item-action>
                        </template>
                        <span>{{ item.text }}</span>
                    </v-tooltip>
                </v-list-item>
            </v-list>

        </v-navigation-drawer>

    </div>
</template>
<script>
    export default {
        name: 'TopSideBarComponent',
        props: {
            activePage: {
                type: String,
            }
        },
        data() {
            return {
                sidebar: true,
                navigationMenu: [
                    { slug: '/home', icon: 'home', text: 'Home' },
                ],
                userMenu: [
                    { slug: '/auth/user', icon: 'user-circle', text: 'Profiel' },
                ],
                logoutMenu: [
                    { slug: '/logout', icon: 'sign-out-alt', text: 'Uitloggen' }
                ],
                breadcrumbList: [],
                userRole: 0,
            }
        },
        computed: {
            theme: {
                get() {
                    if(localStorage.getItem('theme')){
                        return localStorage.getItem('theme')
                    }
                    return 'light'
                },
                set(value) {
                    this.$emit('theme-changed', value)
                    localStorage.setItem('theme', value)
                }
            }
        },
        watch: {
            '$route'() {
                this.updateBreadcrumb()
            }
        },
        created() {
            this.getUserRole()
            this.updateBreadcrumb()
            eventHub.$on('toggle-sidebar', this.toggleSidebar)
        },
        beforeDestroy() {
            eventHub.$off('toggle-sidebar',  this.toggleSidebar)
        },
        methods: {
            requestLogOut(page) {
                axios.post(page)
                    .then(() => location.reload())
                    .catch(response => console.log(response))
            },
            getUserRole() {
                axios.get('/api/auth/user/role')
                    .then(response => this.userRole = response.data.data.role)
                    .catch(response => console.log(response))
            },
            goToDashboard() {
                window.location.href = '/admin/dashboard'
            },
            updateBreadcrumb() {
                this.breadcrumbList = []
                this.breadcrumbList = this.$route.meta.breadcrumb
                Object.entries(this.$route.params).forEach(([key, value], index) => {
                    this.breadcrumbList[index+1].text = (this.breadcrumbList[index+1].name + ' (' + value + ')')
                    this.breadcrumbList[index+1].to = ('/' + this.breadcrumbList[index+1].name.toLowerCase() + '/' + value)
                })
            },
            toggleSidebar() {
                console.warn('going to hide sidebar')
                setTimeout(() => {
                    this.sidebar = false
                }, 50)
            }
        }
    }
</script>
