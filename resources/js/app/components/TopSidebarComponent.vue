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

                    <v-list-tile v-if="userRole === 1" @click="goToDashboard">
                        <v-list-tile-action>
                            <font-awesome-icon :icon="['fas', 'user-cog']"></font-awesome-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Dashboard</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-list-tile>
                        <v-list-tile-action>
                            <v-switch v-model="theme" value="dark"></v-switch>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Donker theme</v-list-tile-title>
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
            mini-variant
        >
            <v-list dense>
                <v-list-tile v-for="(item, index) in navigationMenu"
                             :key="index"
                             :to="item.slug"
                             active-class="active-page"
                             :class="activePage === item.slug ? 'active-page' : ''"
                >
                    <v-tooltip right>
                        <v-list-tile-action slot="activator">
                            <font-awesome-icon class="title" :icon="['fas', item.icon]"></font-awesome-icon>
                        </v-list-tile-action>
                        <span>{{ item.text }}</span>
                    </v-tooltip>
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
                    { slug: '/home', icon: 'home', text: 'Home' },
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
        created() {
            this.getUserRole()
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
            }
        }
    }
</script>