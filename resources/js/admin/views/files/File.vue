<template>
    <v-container>
        <v-layout>
            <v-flex xs12>
                <h1>file</h1>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
    export default {
        name: 'File',
        data() {
            return {

            }
        },
        created() {
            console.log(this.$route.params.id)
        }
    }
</script>
