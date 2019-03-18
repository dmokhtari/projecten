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
                    Logo
                </a>
            </v-toolbar-title>
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
                    { slug: '/admin/modules', icon: 'book', text: 'Modules' },
                    { slug: '/admin/elements', icon: 'puzzle-piece', text: 'Elementen' },
                    { slug: '/admin/users', icon: 'users', text: 'Gebruikers' },
                ],
                userMenu: [
                    { slug: '/auth/user', icon: 'user-circle', text: 'Profiel' },
                ],
                logoutMenu: [
                    { slug: '/logout', icon: 'sign-out-alt', text: 'Uitloggen' }
                ],
                snackbar: {
                    snackbar: false,
                    color: 'success',
                    text: ''
                },
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
        created() {
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

        }
    }
</script>
