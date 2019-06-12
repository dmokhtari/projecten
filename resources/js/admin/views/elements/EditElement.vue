<template>
    <v-container>
<!--        <v-layout justify-end>-->
<!--            <v-flex xs12 md6>-->
<!--                <v-toolbar class="elevation-1" dense>-->
<!--                    <v-btn flat @click="onVideo(element.id)">-->
<!--                        <font-awesome-icon :icon="['fas', 'eye']" class="mr-2"></font-awesome-icon>-->
<!--                        Video-->
<!--                    </v-btn>-->
<!--                    <v-btn flat @click="onTextEditor(element.id)">-->
<!--                        <font-awesome-icon :icon="['fas', 'text-height']" class="mr-2"></font-awesome-icon>-->
<!--                        Text editor-->
<!--                    </v-btn>-->
<!--                </v-toolbar>-->

<!--            </v-flex>-->
<!--        </v-layout>-->

        <v-layout class="mt-5">
            <v-flex xs12>
                <v-card class="text-xs-center" v-if="element">
                    <v-card-title class="justify-center">
                        <font-awesome-icon :icon="['fas', 'puzzle-piece']" class="display-4" style="margin-top:-50px;"></font-awesome-icon>
                    </v-card-title>
                    <v-card-title class="justify-center">
                        <h1>{{ element.title }}</h1>
                    </v-card-title>

                    <v-card-text>
                        <p>Gekoppelde sub-elementen</p>
                        <font-awesome-icon :icon="['fas', 'arrow-down']"></font-awesome-icon>
                    </v-card-text>

                    <v-expansion-panel v-if="element.subelements.length > 0">

                        <v-expansion-panel-content v-for="subElement in element.subelements" :key="subElement.id"
                                                   v-if="subElement.type === 'video'">
                            <template v-slot:actions>
                                <v-icon color="primary" class="subheading">$vuetify.icons.expand</v-icon>
                            </template>
                            <template v-slot:header>
                                <div>{{ subElement.description }}</div>
                            </template>
                            <v-card>
                                <v-card-text class="accent">
                                    <v-layout>
                                        <v-flex xs12 md10>
                                            {{ subElement.url }}
                                        </v-flex>
                                        <v-flex xs12 md2 class="text-xs-right">
                                            <div class="btn-group">
                                                <v-btn icon small @click="onVideo(subElement)">
                                                    <font-awesome-icon :icon="['fas', 'pen']"></font-awesome-icon>
                                                </v-btn>
                                                <v-btn icon small @click="onDestroy(subElement.id)">
                                                    <font-awesome-icon :icon="['fas', 'trash-alt']"></font-awesome-icon>
                                                </v-btn>
                                            </div>
                                        </v-flex>
                                    </v-layout>
                                </v-card-text>
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
                                <v-layout class="accent">
                                    <v-flex xs12 md10>
                                        <v-card-text v-html="subElement.binary"></v-card-text>
                                    </v-flex>
                                    <v-flex xs12 md2 class="text-xs-right pt-3">
                                        <div class="btn-group">
                                            <v-btn icon small @click="onTextEditor(subElement)">
                                                <font-awesome-icon :icon="['fas', 'pen']"></font-awesome-icon>
                                            </v-btn>
                                            <v-btn icon small @click="onDestroy(subElement.id)">
                                                <font-awesome-icon :icon="['fas', 'trash-alt']"></font-awesome-icon>
                                            </v-btn>
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-card>
                <div v-else class="text-xs-center justify-center">
                    <v-progress-circular indeterminate width="10" color="primary" size="100"></v-progress-circular>
                </div>
            </v-flex>
        </v-layout>
        <v-speed-dial
            v-model="toolbar"
            top
            right
            fixed
            direction="left"
            style="top:15%; right:30px"
            transition="slide-y-reverse-transition"
        >
            <template v-slot:activator>
                <v-btn
                    v-model="toolbar"
                    color="primary"
                    dark
                    fab
                >
                    <v-icon>build</v-icon>
                    <v-icon>close</v-icon>
                </v-btn>
            </template>
            <v-btn flat @click="onVideo(element.id)">
                <font-awesome-icon :icon="['fas', 'eye']" class="mr-2"></font-awesome-icon>
                Video
            </v-btn>
            <v-btn flat @click="onTextEditor(element.id)">
                <font-awesome-icon :icon="['fas', 'text-height']" class="mr-2"></font-awesome-icon>
                Text editor
            </v-btn>
        </v-speed-dial>
        <text-editor-sub-element-dialog @text-posted="get" @text-updated="get"></text-editor-sub-element-dialog>
        <video-sub-element-dialog @video-posted="get" @video-updated="get"></video-sub-element-dialog>
        <add-edit-element-dialog :element-posted="get" :element-updated="get"></add-edit-element-dialog>

        <delete-permanent-dialog @confirmed="destroy">
            <p>Weet u zeker dat u deze sub-element wil verwijderen?</p>
        </delete-permanent-dialog>
    </v-container>
</template>
<script>
    import videoSubElementDialog from './subElements/Video';
    import addEditElementDialog from './AddEditElementDialog';
    import textEditorSubElementDialog from './subElements/TextEditor';
    import deletePermanentDialog from './../../../shared/components/DeletePermanent';
    export default {
        name: 'EditElement',
        title: 'Edit Element - admin',
        components: {
            videoSubElementDialog,
            textEditorSubElementDialog,
            addEditElementDialog,
            deletePermanentDialog
        },
        data() {
            return {
                element: null,
                selectedSubElement: null,
                toolbar: false
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
            onDestroy(id) {
                this.selectedSubElement = id
                eventHub.$emit('show-delete-permanent')
            },
            get() {
                axios.get(`/api/elements/${this.$route.params.id}`)
                    .then(response => {
                        this.element = response.data.data
                    })
                    .catch(response => console.errors(response))
            },
            destroy() {
                axios.delete('/api/subelements/' + this.selectedSubElement)
                    .then(response => {
                        this.get()
                        eventHub.$emit('show-message', response.data.status, response.data.data)
                    })
                    .catch(response => eventHub.$emit('show-message', response.data.status, response.data.data))
            }
        }
    }
</script>
