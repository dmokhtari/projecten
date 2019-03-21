<template>
    <v-container>
        <v-layout>
            <v-flex xs12 sm4>
                <v-card v-if="users">
                    <v-card-title class="justify-center">
                        <font-awesome-icon class="display-4" :icon="['fas', 'users']"></font-awesome-icon>
                    </v-card-title>
                    <v-card-text class="text-xs-center">
                        <h2>Gebruikers</h2>
                        <p>Totaal gebruikers: <v-chip>{{ users.total }}</v-chip></p>
                        <p>Online gebruikers: <v-chip>{{ users.online }}</v-chip></p>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
    export default {
        name: 'Dashboard',
        title: 'Admin Page',
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
