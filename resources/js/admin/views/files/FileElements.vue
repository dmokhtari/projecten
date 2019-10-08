<template>
    <v-container>
        <v-layout>
            <v-flex xs12>
                <v-card v-if="fileModule" class="mt-5 text-xs-center">
                    <v-toolbar :class="fileModule.files[0].background_color">
                        <font-awesome-icon :icon="['fas', 'cubes']" class="display-2"></font-awesome-icon>

                        <v-toolbar-title>{{ fileModule.title }}</v-toolbar-title>
                        <v-spacer></v-spacer>

                        <v-menu bottom left>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on">
                                    <font-awesome-icon :icon="['fas', 'ellipsis-v']"></font-awesome-icon>
                                </v-btn>
                            </template>

                            <v-list>
                                <v-list-item
                                        v-for="(item, i) in moduleMenu"
                                        :key="i"
                                        @click="onModuleMenuClick(item.id)"
                                >
                                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>

                        <v-btn icon @click="onAddEditElement()">
                            <font-awesome-icon :icon="['fas', 'plus']"></font-awesome-icon>
                        </v-btn>
                    </v-toolbar>


                    <v-list v-if="orderedElements.length > 0">
                        <v-subheader>Elementen</v-subheader>
                        <template v-for="(element, index) in orderedElements">

                            <v-list-item
                                    :key="index"
                                    ripple
                                    @click=""
                            >
                                <v-list-item-avatar color="grey">
                                    <v-avatar>{{ index+1 }}</v-avatar>
                                </v-list-item-avatar>

                                <v-list-item-content @click="goToFileSubElement(element.id)">
                                    <v-list-item-title>{{ element.title }}</v-list-item-title>
                                    <v-list-item-subtitle>{{ element.subtitle }} &mdash; {{ element.text }}</v-list-item-subtitle>
                                </v-list-item-content>

                                <v-list-item-action>
                                    <v-btn icon ripple @click="onDestroyElement(element.id)">
                                        <font-awesome-icon :icon="['fas', 'trash-alt']"></font-awesome-icon>
                                    </v-btn>
                                </v-list-item-action>
                            </v-list-item>
                        </template>
                    </v-list>
                    <v-list v-else>
                        <v-subheader>Geen Elementen</v-subheader>
                    </v-list>
                </v-card>

                <div v-else class="text-xs-center justify-center">
                    <v-progress-circular indeterminate color="primary" size="100" width="10"></v-progress-circular>
                </div>
            </v-flex>
        </v-layout>

        <add-edit-element-dialog @element-posted="get" @element-updated="get"></add-edit-element-dialog>
        <add-edit-module-dialog @module-posted="get" @module-updated="get"></add-edit-module-dialog>
        <arrange-elements-dialog @arrange-elements-updated="get"></arrange-elements-dialog>
        <delete-permanent-dialog @confirmed="destroyElement">
            <p>Weet u zeker dat u dit <strong>element</strong> wil verwijderen?</p>
        </delete-permanent-dialog>

    </v-container>
</template>
<script>
    import AddEditElementDialog from './../elements/AddEditElementDialog';
    import AddEditModuleDialog from './../modules/AddEditModuleDialog';
    import ArrangeElementsDialog from './ArrangeElementsDialog';
    import deletePermanentDialog from './../../../shared/components/DeletePermanent';
    export default {
        name: 'fileElements',
        components: {
            AddEditElementDialog,
            AddEditModuleDialog,
            ArrangeElementsDialog,
            deletePermanentDialog
        },
        data() {
            return {
                fileModule: null,
                selectedElement: null,
                orderedElements: [],
                rank: null,
                moduleMenu: [
                    { title: 'Wijzigen', id: 1 },
                    { title: 'Rangschikken', id: 2 },
                ],
            }
        },
        created() {
            this.get()
        },
        methods: {
            get() {
                axios.get(`/api/modules/${this.$route.params.moduleId}`)
                    .then(response => {
                        this.fileModule = response.data.data

                        if(this.fileModule.ranking) {
                            this.rank = this.fileModule.ranking.ranking.split(",")

                            this.rank.forEach((item, index) => {
                                this.orderedElements[index] = _.find(this.fileModule.elements, (item) => {
                                    return item.id == this.rank[index]
                                })
                            })
                        } else {
                            this.orderedElements = this.fileModule.elements
                        }
                    })
                    .catch(error => console.error(error))
            },
            onModuleMenuClick(id) {
                switch (id) {
                    case 1:
                        this.onAddEditModule()
                        break;
                    case 2:
                        this.onArrangeElements()
                        break;
                }
            },
            onAddEditModule() {
                console.log('yes')
                eventHub.$emit('add-edit-module-dialog', this.fileModule)
            },
            onAddEditElement() {
                eventHub.$emit('add-edit-element-dialog', {module_id: this.fileModule.id})
            },
            onArrangeElements() {
                eventHub.$emit('arrange-elements-dialog', this.fileModule)
            },
            goToFileSubElement(id) {
                this.$router.push({ name: 'fileSubElements', params: { fileId: this.$route.params.fileId, moduleId: this.$route.params.moduleId, elementId: id } })
            },
            onDestroyElement(id) {
                this.selectedElement = id
                eventHub.$emit('show-delete-permanent')
            },
            destroyElement() {
                axios.delete(`/api/elements/${this.selectedElement}`)
                    .then(response => {
                        this.get()
                        eventHub.$emit('show-message', response.data.status, response.data.data)
                    })
                    .catch(response => console.error(response))
            }
        }
    }
</script>