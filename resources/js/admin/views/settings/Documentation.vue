<template>
    <v-container>
        <v-layout row justify-center>
            <v-flex xs12 md10>
                <v-card>
                    <v-card-title class="headline">
                        <font-awesome-icon :icon="['fas', 'passport']" class="mr-2"></font-awesome-icon>
                        Documentaties
                        <v-divider
                                class="mx-3"
                                inset
                                vertical
                        ></v-divider>
                        <v-spacer></v-spacer>
                        <v-btn color="secondary" fab @click="onAddEditDoc()">
                            <font-awesome-icon :icon="['fas', 'plus']"></font-awesome-icon>
                        </v-btn>
                    </v-card-title>
                    <v-card-text>
                        <v-expansion-panel v-if="docs">
                            <v-expansion-panel-content
                                    v-for="(doc,i) in docs"
                                    :key="doc.id"
                            >
                                <template v-slot:actions>
                                    <v-icon color="primary" class="subheading">$vuetify.icons.expand</v-icon>
                                </template>
                                <template v-slot:header>
                                    <div class="title">{{ doc.title }}</div>
                                </template>
                                <v-card>
                                    <v-card-text>
                                        <v-layout row wrap>
                                            <v-flex xs12 md10>
                                                <div v-html="doc.text"></div>
                                            </v-flex>
                                            <v-flex xs12 md2 class="text-xs-right">
                                                <div class="btn-group">
                                                    <v-btn icon small @click="onAddEditDoc(doc)">
                                                        <font-awesome-icon :icon="['fas', 'pen']"></font-awesome-icon>
                                                    </v-btn>
                                                    <v-btn icon small @click="onDestroyDoc(doc.id)">
                                                        <font-awesome-icon :icon="['fas', 'trash-alt']"></font-awesome-icon>
                                                    </v-btn>
                                                </div>
                                            </v-flex>
                                        </v-layout>
                                    </v-card-text>
                                </v-card>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
        <add-edit-documentation-dialog @documentation-posted="get" @documentation-updated="get"></add-edit-documentation-dialog>
        <delete-permanent-dialog @confirmed="deleteDoc">
            <p>Weet u zeker dat u deze <strong>Documentatie</strong> wil verwijderen?</p>
        </delete-permanent-dialog>
    </v-container>
</template>
<script>
    import AddEditDocumentationDialog from './AddEditDocumentation';
    import DeletePermanentDialog from './../../../shared/components/DeletePermanent';
    export default {
        name: 'Documentation',
        components: {
            AddEditDocumentationDialog,
            DeletePermanentDialog
        },
        data() {
            return {
                docs: null,
                selectedDoc: null
            }
        },
        created() {
            this.get()
        },
        methods: {
            get() {
                axios.get('/api/settings/documentations')
                    .then(response => {
                        this.docs = response.data.data
                    })
                    .catch(error => console.error(error))
            },
            onAddEditDoc(obj = null) {
                eventHub.$emit('add-edit-documentation-dialog', obj)
            },
            onDestroyDoc(id) {
                this.selectedDoc = id
                eventHub.$emit('show-delete-permanent')
            },
            deleteDoc() {
                axios.delete(`/api/settings/documentations/${this.selectedDoc}`)
                    .then(response => {
                        this.get()
                        eventHub.$emit('show-message', response.data.status, response.data.data)
                    })
                    .catch(error => console.error(error))
            }
        }
    }
</script>