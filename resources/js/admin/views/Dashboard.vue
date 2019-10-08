<template>
    <v-container grid-list-lg>
        <v-layout>
            <v-flex xs12 sm4>
                <v-card v-if="users" hover>
                    <v-card-title class="justify-center">
                        <font-awesome-icon class="display-4" :icon="['fas', 'users']"></font-awesome-icon>
                    </v-card-title>
                    <v-list avatar>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>Totaal gebruikers:</v-list-item-title>
                            </v-list-item-content>
                            <v-list-item-avatar><v-chip>{{ users.total }}</v-chip></v-list-item-avatar>
                        </v-list-item>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>Totaal gebruikers online:</v-list-item-title>
                            </v-list-item-content>
                            <v-list-item-avatar><v-chip>{{ users.online }}</v-chip></v-list-item-avatar>
                        </v-list-item>
                    </v-list>
                </v-card>
            </v-flex>
            <v-flex xs12 sm4>
                <v-card v-if="users" hover>
                    <v-card-title class="justify-center">
                        <font-awesome-icon class="display-4" :icon="['fas', 'users']"></font-awesome-icon>
                    </v-card-title>
                    <v-list avatar>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>Totaal gebruikers:</v-list-item-title>
                            </v-list-item-content>
                            <v-list-item-avatar><v-chip>{{ users.total }}</v-chip></v-list-item-avatar>
                        </v-list-item>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>Totaal gebruikers:</v-list-item-title>
                            </v-list-item-content>
                            <v-list-item-avatar><v-chip>{{ users.online }}</v-chip></v-list-item-avatar>
                        </v-list-item>
                    </v-list>
                </v-card>
            </v-flex>
            <v-flex xs12 sm4>
                <v-card v-if="users" hover>
                    <v-card-title class="justify-center">
                        <font-awesome-icon class="display-4" :icon="['fas', 'users']"></font-awesome-icon>
                    </v-card-title>
                    <v-list avatar>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>Totaal gebruikers:</v-list-item-title>
                            </v-list-item-content>
                            <v-list-item-avatar><v-chip>{{ users.total }}</v-chip></v-list-item-avatar>
                        </v-list-item>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>Totaal gebruikers:</v-list-item-title>
                            </v-list-item-content>
                            <v-list-item-avatar><v-chip>{{ users.online }}</v-chip></v-list-item-avatar>
                        </v-list-item>
                    </v-list>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
    export default {
        name: 'Dashboard',
        title: 'Admin dashboard',
        data() {
            return {
                users: null,
            }
        },
        created() {
            this.getUsers()
        },
        methods: {
            getUsers() {
                axios.get('/api/dashboard/users')
                    .then(response => {
                        console.log(response)
                        this.users = response.data.data
                    })
                    .catch(response => console.error(response))
            }
        }
    }
</script>
