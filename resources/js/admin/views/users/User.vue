<template>
    <v-container>
        <v-layout>
            <v-flex>
                <v-card v-if="user" class="mt-5">
                    <v-card-title class="justify-center">
                        <font-awesome-icon :icon="['far', 'user-circle']" class="display-4" style="margin-top:-50px;"></font-awesome-icon>
                    </v-card-title>
                    <v-card-title class="justify-center">
                        {{ user.forename }}
                    </v-card-title>
                    <v-card-title class="justify-center">
                        {{ user.email }}
                    </v-card-title>
                </v-card>
                <div v-else class="text-xs-center justify-center">
                    <v-progress-circular indeterminate color="primary" size="100" width="10"></v-progress-circular>
                </div>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
    export default {
        name: 'User',
        data() {
            return {
                user: null
            }
        },
        created() {
            this.get()
        },
        methods: {
            get() {
                axios.get(`/api/users/${this.$route.params.id}`)
                    .then(response => {
                        this.user = response.data.data
                    })
                    .catch(error => console.error(error))
            }
        }
    }
</script>