<template>
    <v-container>
        <v-layout justify-end>
            <v-flex xs12 md6>
                <v-toolbar>
                    <v-toolbar-items>
                        <v-btn @click="onVideo()">video</v-btn>
                        <v-btn>notitie maken</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
            </v-flex>
        </v-layout>

        <v-layout class="mt-5">
            <v-flex xs12>
                <v-card class="text-xs-center">
                    <v-card-title class="title">aldjfalsdjkflafdjalsfj</v-card-title>
                    <v-divider></v-divider>
                    <v-card-text>
                        lajflsjadflasjdf
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>




        <video-sub-element-dialog></video-sub-element-dialog>
        <add-edit-element-dialog :element-posted="get" :element-updated="get"></add-edit-element-dialog>
    </v-container>
</template>
<script>
    import videoSubElementDialog from './subElements/Video';
    import addEditElementDialog from './AddEditElementDialog';
    export default {
        name: 'AddElement',
        components: {
            videoSubElementDialog,
            addEditElementDialog
        },
        data() {
            return {

            }
        },
        mounted() {
            setTimeout(() => this.onAddEditElement(), 500)
        },
        methods: {
            onVideo(obj = null) {
                eventHub.$emit('add-edit-subelement-video-dialog', obj)
            },
            onAddEditElement(obj = null) {
                eventHub.$emit('add-edit-element-dialog', obj)
            },
            get(id) {
                axios.get('/api/elements/' + id)
                    .then(response => {
                        console.log(response)
                    })
                    .catch(response => console.error(response))
            }
        }
    }
</script>
