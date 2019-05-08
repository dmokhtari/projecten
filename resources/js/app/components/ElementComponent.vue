<template>
    <v-card v-if="element">
        <v-layout row wrap v-for="(sub, index) in element.subelements" :key="index">
            <v-flex xs12>
                <v-card-title class="py-1 px-3 accent">
                    <v-avatar size="60">
                        <v-img v-if="sub.icons.length" :src="'/storage/' + sub.icons[0].src"></v-img>
                    </v-avatar>
                </v-card-title>
                <v-divider></v-divider>
            </v-flex>
            <template v-if="sub.type === 'video'">
                <v-flex xs12 class="px-5 py-3">
                    <p v-if="sub.description">{{ sub.description }}</p>
                    <v-btn @click="showVideo(sub.url)">Video bekijken</v-btn>
                </v-flex>
            </template>
            <template v-if="sub.type === 'binary'">
                <v-flex xs12 class="px-5 py-3" v-html="sub.binary"></v-flex>
            </template>
        </v-layout>
        <video-dialog-component></video-dialog-component>
    </v-card>
</template>
<script>
    import VideoDialogComponent from './VideoDialogComponent';
    export default {
        name: 'ElementComponent',
        props: {
            objElement: { required: true }
        },
        components: {
            VideoDialogComponent
        },
        data() {
            return {
                element: null
            }
        },
        watch: {
            objElement(val, oldVal) {
                console.log(val)
                this.element = val
            }
        },
        methods: {
            showVideo(url) {
                eventHub.$emit('show-video', url)
            }
        }
    }
</script>
