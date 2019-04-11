<template>
    <v-container grid-list-lg>
        <v-layout row wrap>
            <v-flex v-for="(file, index) in files" :key="index" xs6 md4>
                <v-card @click="goToFile(file.id)" hover style="border-radius:10px">
                    <v-layout style="min-height:150px; margin-left:0" row wrap>
                        <v-flex xs2 md3 :class="file.background_color"></v-flex>
                        <v-flex xs10 md9>
                            <h3 class="grey--text">{{ file.title }}</h3>
                            <p>{{ file.subtitle }}</p>
                        </v-flex>
                    </v-layout>
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
                this.$router.push({ name: 'studentFile', params: { id: id } })
            }
        }
    }
</script>
