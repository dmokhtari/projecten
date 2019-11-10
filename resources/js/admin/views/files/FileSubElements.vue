<template>
    <v-container>
        <v-layout>
            <v-flex xs12>
                <v-card v-if="element" class="mt-5 text-xs-center">
                    <v-toolbar :class="$root.fileColor">
                        <font-awesome-icon :icon="['fas', 'puzzle-piece']" class="display-2"></font-awesome-icon>

                        <v-toolbar-title>{{ element.title }}</v-toolbar-title>
                        <v-spacer></v-spacer>

                        <v-menu bottom left>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on">
                                    <font-awesome-icon :icon="['fas', 'ellipsis-v']"></font-awesome-icon>
                                </v-btn>
                            </template>

                            <v-list>
                                <v-list-item
                                        v-for="(item, i) in elementMenu"
                                        :key="i"
                                        @click="onElementMenuClick(item.id)"
                                >
                                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>

                        <v-menu bottom left>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on">
                                    <font-awesome-icon :icon="['fas', 'plus']"></font-awesome-icon>
                                </v-btn>
                            </template>

                            <v-list>
                                <v-list-item
                                        v-for="(item, i) in subElementMenu"
                                        :key="i"
                                        @click="onAddSubElementMenuClick(item.id)"
                                >
                                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-toolbar>


                    <v-list v-if="orderedSubElements.length > 0">
                        <v-subheader>Subelementen</v-subheader>
                        <template v-for="(subelement, index) in orderedSubElements">

                            <v-list-item
                                    :key="index"
                                    ripple
                                    @click=""
                            >
                                <v-list-item-avatar color="grey">
                                    <v-avatar>{{ index+1 }}</v-avatar>
                                </v-list-item-avatar>

                                <v-list-item-content @click="onSubElementClick(subelement)">
                                    <v-list-item-title>{{ subelement.title }} (type: {{ subelement.type }})</v-list-item-title>
                                    <v-list-item-subtitle>{{ subelement.description }}</v-list-item-subtitle>
                                </v-list-item-content>

                                <v-list-item-action>
                                    <v-btn icon ripple @click="onDestroySubElement(subelement.id)">
                                        <font-awesome-icon :icon="['fas', 'trash-alt']"></font-awesome-icon>
                                    </v-btn>
                                </v-list-item-action>
                            </v-list-item>
                            <v-divider
                                    v-if="subelement.length > 1"
                                    :key="index"
                            ></v-divider>
                        </template>
                    </v-list>
                    <v-list v-else>
                        <v-subheader>Geen subelementen</v-subheader>
                    </v-list>
                </v-card>

                <div v-else class="text-xs-center justify-center">
                    <v-progress-circular indeterminate color="primary" size="100" width="10"></v-progress-circular>
                </div>
            </v-flex>
        </v-layout>

        <video-dialog @video-posted="get" @video-updated="get"></video-dialog>
        <read-dialog @read-posted="get" @read-updated="get"></read-dialog>
        <text-editor-dialog @text-posted="get" @text-updated="get"></text-editor-dialog>
        <pdf-dialog @pdf-posted="get" @pdf-updated="get"></pdf-dialog>
        <add-edit-element-dialog @element-posted="get" @element-updated="get"></add-edit-element-dialog>
        <arrange-sub-elements-dialog @arrange-updated="get"></arrange-sub-elements-dialog>
        <delete-permanent-dialog @confirmed="destroyModule">
            <p>Weet u zeker dat u dit subelement wil verwijderen?</p>
        </delete-permanent-dialog>

    </v-container>
</template>
<script>
    import VideoDialog from './../elements/subElements/Video';
    import ReadDialog from './../elements/subElements/Read';
    import TextEditorDialog from './../elements/subElements/TextEditor';
    import PdfDialog from './../elements/subElements/Pdf';
    import AddEditElementDialog from './../elements/AddEditElementDialog';
    import ArrangeSubElementsDialog from './ArrangeSubelementsDialog';
    import deletePermanentDialog from './../../../shared/components/DeletePermanent';
    export default {
        name: 'fileSubElements',
        components: {
            VideoDialog,
            ReadDialog,
            TextEditorDialog,
            PdfDialog,
            deletePermanentDialog,
            AddEditElementDialog,
            ArrangeSubElementsDialog
        },
        data() {
            return {
                element: null,
                selectedSubElement: null,
                subElementMenu: [
                    { title: 'Bekijken', id: 1 },
                    { title: 'Lezen / Noteren', id: 2 },
                    { title: 'Tekst editor', id: 3 },
                    { title: 'Bestand uploaden', id: 4 },
                ],
                elementMenu: [
                    { title: 'Wijzigen', id: 1 },
                    { title: 'Rangschikken', id: 2 },
                ],
                orderedSubElements: [],
                rank: null
            }
        },
        created() {
            this.get()
        },
        methods: {
            get() {
                axios.get(`/api/elements/${this.$route.params.elementId}`)
                    .then(response => {
                        this.element = response.data.data

                        if(this.element.ranking) {
                            this.rank = this.element.ranking.ranking.split(",")

                            this.rank.forEach((item, index) => {
                                this.orderedSubElements[index] = _.find(this.element.subelements, (item) => {
                                    return item.id == this.rank[index]
                                })
                            })
                        } else {
                            this.orderedSubElements = this.element.subelements
                        }

                    })
                    .catch(error => console.error(error))
            },
            onAddEditVideo(obj) {
                eventHub.$emit('add-edit-subelement-video-dialog', obj )
            },
            onAddEditRead(obj) {
                eventHub.$emit('add-edit-subelement-read-dialog', obj)
            },
            onAddEditTextEditor(obj) {
                eventHub.$emit('add-edit-subelement-text-editor-dialog', obj)
            },
            onAddEditPdf(obj) {
                eventHub.$emit('add-edit-subelement-pdf-dialog', obj)
            },
            onAddSubElementMenuClick(id) {
                switch (id) {
                    case 1:
                        this.onAddEditVideo({ element_id: this.element.id })
                        break;
                    case 2:
                        this.onAddEditRead({ element_id: this.element.id })
                        break;
                    case 3:
                        this.onAddEditTextEditor({ element_id: this.element.id })
                        break;
                    case 4:
                        this.onAddEditPdf({ element_id: this.element.id })
                        break;
                }
            },
            onSubElementClick(subelement) {
                switch (subelement.type) {
                    case 'video':
                        this.onAddEditVideo(subelement)
                        break;
                    case 'read/write':
                        this.onAddEditRead(subelement)
                        break;
                    case 'binary':
                        this.onAddEditTextEditor(subelement)
                        break;
                }
            },
            onElementMenuClick(id) {
                switch (id) {
                    case 1:
                        this.onAddEditElement(this.element)
                        break;
                    case 2:
                        this.onArrangeSubElement(this.element)
                        break;
                }
            },
            onAddEditElement(obj) {
                eventHub.$emit('add-edit-element-dialog', obj )
            },
            onArrangeSubElement(obj) {
                eventHub.$emit('arrange-dialog', obj )
            },
            onDestroySubElement(id) {
                this.selectedSubElement = id
                eventHub.$emit('show-delete-permanent')
            },
            destroyModule() {
                axios.delete(`/api/subelements/${this.selectedSubElement}`)
                    .then(response => {
                        this.get()
                        eventHub.$emit('show-message', response.data.status, response.data.data)
                    })
                    .catch(response => console.error(response))
            }
        }
    }
</script>
