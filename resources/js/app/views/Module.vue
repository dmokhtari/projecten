<template>
    <v-container>
        <h1>module</h1>
    </v-container>
</template>
<script>
    export default {
        name: 'Module',
        title: 'Module',
        components: {
        },
        data() {
            return {
            }
        },
        created() {
            this.get()
        },
        methods: {
            get() {
                axios.get(`/api/modules/${this.$route.params.id}`)
                    .then(response => console.log(response))
                    .catch(response => console.errors(response))
            }
        }
    }
</script>
