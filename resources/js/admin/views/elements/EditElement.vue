<template>
    <v-container>
        <v-layout justify-end>
            <v-flex xs12 md6>
                <v-toolbar height="50">
                    <v-toolbar-items>
                        <v-btn @click="onVideo(element.id)">video</v-btn>
                        <v-btn @click="onTextEditor(element.id)">Text editor</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
            </v-flex>
        </v-layout>

        <v-layout class="mt-5" v-if="element">
            <v-flex xs12>
                <v-card class="text-xs-center">
                    <v-card-title class="title">{{ element.title }}</v-card-title>
                    <v-divider></v-divider>
                    <v-card-text v-if="element.subtitle">{{ element.subtitle }}</v-card-text>

                    <v-expansion-panel v-if="element.subelements.length > 0">
                        <v-expansion-panel-content v-for="subElement in element.subelements" :key="subElement.id"
                                                   v-if="subElement.type === 'video'">
                            <template v-slot:actions>
                                <v-icon color="primary" class="subheading">$vuetify.icons.expand</v-icon>
                            </template>
                            <template v-slot:header>
                                <div>{{ subElement.url }}</div>
                            </template>
                            <v-card>
                                <v-card-text class="accent">{{ subElement.description }}</v-card-text>
                            </v-card>
                        </v-expansion-panel-content>

                        <v-expansion-panel-content v-for="subElement in element.subelements" :key="subElement.id"
                                                   v-if="subElement.type === 'binary'">
                            <template v-slot:actions>
                                <v-icon color="primary" class="subheading">$vuetify.icons.expand</v-icon>
                            </template>
                            <template v-slot:header>
                                <div>binary</div>
                            </template>
                            <v-card>
                                <v-card-text v-html="subElement.binary" class="accent"></v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-card>
            </v-flex>
        </v-layout>

        <text-editor-sub-element-dialog></text-editor-sub-element-dialog>
        <video-sub-element-dialog></video-sub-element-dialog>
        <add-edit-element-dialog :element-posted="get" :element-updated="get"></add-edit-element-dialog>
    </v-container>
</template>
<script>
    import videoSubElementDialog from './subElements/Video';
    import addEditElementDialog from './AddEditElementDialog';
    import textEditorSubElementDialog from './subElements/TextEditor';
    export default {
        name: 'EditElement',
        title: 'Edit Element - admin',
        components: {
            videoSubElementDialog,
            textEditorSubElementDialog,
            addEditElementDialog
        },
        data() {
            return {
                element: null
            }
        },
        created() {
            this.get()
        },
        methods: {
            onVideo(objOrId = null) {
                eventHub.$emit('add-edit-subelement-video-dialog', objOrId)
            },
            onTextEditor(objOrId) {
                eventHub.$emit('add-edit-subelement-text-editor-dialog', objOrId)
            },
            onAddEditElement(obj = null) {
                eventHub.$emit('add-edit-element-dialog', obj)
            },
            get() {
                axios.get(`/api/elements/${this.$route.params.id}`)
                    .then(response => {
                        console.log(response)
                        this.element = response.data.data
                    })
                    .catch(response => console.errors(response))
            }
        }
    }
</script>
