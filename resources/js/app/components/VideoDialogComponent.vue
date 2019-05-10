<template>
    <v-dialog
        v-model="dialog"
        max-width="840"
        persistent
        content-class="elevation-0"
    >
        <v-card style="background-color: transparent">
            <v-card-title style="position: relative; background-color:transparent">
                <v-btn icon absolute style="top: 0; right:0;" color="primary" @click="onClose">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-card-title>
            <div class="mx-3 px-0 text-xs-center elevation-5">
                <iframe width="800" height="500" :src="videoSrc" frameborder="0" allowfullscreen ref="videoFrame"></iframe>
            </div>
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
            },
            onClose() {
                let iframeSrc = this.$refs.videoFrame.src
                this.$refs.videoFrame.src = iframeSrc
                this.dialog = false
            }
        }
    }
</script>
<style lang="scss" scoped>

</style>
