<template>
    <v-container grid-list-lg>
        <v-layout row wrap>
            <v-flex v-for="(file, index) in files" :key="index" xs6 md3>
                <v-card @click="goToFile(file.id)" class="text-xs-center pa-5 ma-3" hover>
                    <v-card-title>
                        <h3>{{ file.title }}</h3>
                    </v-card-title>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
    export default {
        name: 'Home',
        data() {
            return {
                files: null,
            }
        },
        created() {
            this.getFiles()
        },
        methods: {
            getFiles() {
                axios.get('/api/files')
                    .then(response => this.files = response.data.data)
                    .catch(response => console.error(response))
            },
            goToFile(id) {
                this.$router.push({ name: 'file', params: {id: id} })
            }
        }
    }
</script>
