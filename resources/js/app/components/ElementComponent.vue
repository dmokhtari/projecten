<template>
    <v-card v-if="element">
        <v-layout row wrap v-for="(sub, index) in element.subelements" :key="index">
            <v-flex xs12>
                <v-toolbar>
                    <div v-for="(icon, i) in sub.icons" :key="i">
                        <v-img v-if="sub.icons.length" :src="'/storage/' + icon.src" width="50px"></v-img>
                    </div>
                </v-toolbar>
                <v-divider></v-divider>
            </v-flex>
            <template v-if="sub.type === 'read/write'">
                <v-flex xs12 class="px-5 py-3">
                    <p v-if="sub.description">{{ sub.description }}</p>
                </v-flex>
            </template>

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
