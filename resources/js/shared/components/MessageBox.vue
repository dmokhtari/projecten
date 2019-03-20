<template>
    <v-snackbar
        v-model="snackbar"
        :color="color"
        right
        top
    >
        {{ text }}
        <v-btn
            icon
            flat
            @click="snackbar = false"
        >
            <font-awesome-icon :icon="['fas', 'times']"></font-awesome-icon>
        </v-btn>
    </v-snackbar>
</template>
<script>
    export default {
        name: 'MessageBox',
        data() {
            return {
                snackbar: false,
                color: 'error',
                text: ''
            }
        },
        created() {
            eventHub.$on('show-message', this.show)
        },
        beforeDestroy() {
            eventHub.$off('show-message')
        },
        methods: {
            show(color, text) {
                console.warn('i heard inside show')
                console.log(color, text)
                this.snackbar = true
                this.color = color
                this.text = text
            }
        }
    }
</script>
