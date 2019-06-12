<template>
    <div>
        <v-toolbar
            app
            clipped-left
            scroll-off-screen
        >
            <v-btn flat icon @click="sidebar = !sidebar">
                <font-awesome-icon class="title" :icon="['fas', 'bars']"></font-awesome-icon>
            </v-btn>
            <v-toolbar-title>
                <a href="/">
                    <img src="/assets/img/logo.png" width="100">
                </a>
            </v-toolbar-title>

            <!-- Breadcrumb -->
            <v-chip class="ml-5 hidden-xs-only">
                <v-breadcrumbs :items="breadcrumbList" large divider=">">
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
            <v-menu offset-y>
                <v-btn icon large slot="activator">
                    <v-avatar color="grey lighten-1" size="42" class="elevation-1">
                        <font-awesome-icon :icon="['fas', 'user']"></font-awesome-icon>
                    </v-avatar>
                </v-btn>
                <v-list>
                    <v-list-tile v-for="(item, i) in userMenu" :key="i" :to="item.slug">
                        <v-list-tile-action>
                            <font-awesome-icon :icon="['fas', item.icon]"></font-awesome-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.text }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile @click="goToHome">
                        <v-list-tile-action>
                            <font-awesome-icon :icon="['fas', 'user-graduate']"></font-awesome-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Student Home Pagina</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-list-tile v-for="(item, i) in logoutMenu" :key="i+1" @click="requestLogOut(item.slug)">
                        <v-list-tile-action>
                            <font-awesome-icon :icon="['fas', item.icon]"></font-awesome-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.text }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-menu>
        </v-toolbar>

        <v-navigation-drawer
            fixed
            clipped
            v-model="sidebar"
            app
            width="220px"
        >
            <v-list dense>
                <v-list-tile v-for="(item, index) in navigationMenu"
                             :key="index"
                             :to="item.slug"
                             active-class="active-page"
                             :class="activePage === item.slug ? 'active-page' : ''"
                >
                    <v-list-tile-action>
                        <font-awesome-icon class="title" :icon="['fas', item.icon]"></font-awesome-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>{{ item.text }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-list-group
                    prepend-icon="settings"
                >
                    <template v-slot:activator>
                        <v-list-tile>
                            <v-list-tile-title>Instellingen</v-list-tile-title>
                        </v-list-tile>
                    </template>
                    <v-list-tile
                        v-for="(setting, i) in settings"
                        :key="i"
                        :to="setting.slug"
                    >
                        <v-list-tile-action class="pl-4">
                            <font-awesome-icon :icon="['fas', setting.icon]"></font-awesome-icon>
                        </v-list-tile-action>
                        <v-list-tile-title class="pl-3" v-text="setting.text"></v-list-tile-title>
                    </v-list-tile>
                </v-list-group>

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
                    { slug: '/admin/dashboard', icon: 'home', text: 'Dashboard' },
                    { slug: '/admin/files', icon: 'file', text: 'Bestanden' },
                    { slug: '/admin/modules', icon: 'cubes', text: 'Modules' },
                    { slug: '/admin/elements', icon: 'puzzle-piece', text: 'Elementen' },
                    { slug: '/admin/users', icon: 'users', text: 'Gebruikers' },
                ],
                settings: [
                    { slug: '/admin/settings/icons', icon: 'sign', text: 'Icons' }
                ],
                userMenu: [
                    { slug: '/admin/profile', icon: 'user-circle', text: 'Profiel' },
                ],
                logoutMenu: [
                    { slug: '/logout', icon: 'sign-out-alt', text: 'Uitloggen' }
                ],
                breadcrumbList: [],
                userRole: 0,
            }
        },
        computed: {
            theme() {
                if(localStorage.getItem('theme')){
                    return localStorage.getItem('theme')
                }
                return 'light'
            }
        },
        watch: {
            '$route'() {
                this.updateBreadcrumb()
            }
        },
        created() {
            this.updateBreadcrumb()
        },
        methods: {
            requestLogOut(page) {
                axios.post(page)
                    .then(() => location.reload())
                    .catch(response => console.log(response))
            },
            goToHome() {
                window.location.href = '/home'
            },
            updateBreadcrumb() {
                this.breadcrumbList = []
                this.breadcrumbList = this.$route.meta.breadcrumb
                // Object.entries(this.$route.params).forEach(([key, value], index) => {
                //     this.breadcrumbList[index+1].text = (this.breadcrumbList[index+1].name + ' (' + value + ')')
                //     this.breadcrumbList[index+1].to = ('/' + this.breadcrumbList[index+1].name.toLowerCase() + '/' + value)
                // })
            }

        }
    }
</script>
