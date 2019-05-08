<template>
    <v-dialog
        v-model="dialog"
        max-width="800"
        persistent
    >
        <v-card>
<!--            <v-card-title style="position: relative">-->
                <v-btn icon absolute style="top: -5px; right: -5px;" color="primary">
                    <v-icon>close</v-icon>
                </v-btn>
<!--            </v-card-title>-->
            <iframe width="800" height="500" :src="videoSrc" frameborder="0" allowfullscreen></iframe>
        </v-card>
    </v-dialog>
</template>
<script>
    export default {
        name: 'VideoDialogComponent',
        data() {
            return {
                dialog: false,
                videoSrc: ''
            }
        },
        created() {
            eventHub.$on('show-video', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('show-video', this.onShow)
        },
        methods: {
            onShow(url) {
                let uri = url.replace('watch?v=', 'embed/')
                this.videoSrc = uri
                this.dialog = true
                console.log(url)
            }
        }
    }
</script>
