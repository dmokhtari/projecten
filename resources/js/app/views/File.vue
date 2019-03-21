<template>
    <v-container>
        <h1>file</h1>
    </v-container>
</template>
<script>
    export default {
        name: 'File',
        title: 'File',
        data() {
            return {
            }
        },
        created() {
            this.get()
        },
        methods: {
            get() {
                axios.get(`/api/files/${this.$route.params.id}`)
                    .then(response => console.log(response))
                    .catch(response => console.errors(response))
            }
        }
    }
</script>
