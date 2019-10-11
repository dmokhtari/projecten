<template>
    <v-container>
        <v-layout>
            <v-flex xs12>
                <v-card v-if="file" class="mt-5 text-xs-center">
                    <v-toolbar :class="file.background_color">
                        <font-awesome-icon :icon="['fas', 'file']" class="display-2"></font-awesome-icon>

                        <v-toolbar-title>{{ file.title }}</v-toolbar-title>
                        <v-spacer></v-spacer>

                        <v-menu bottom left>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on">
                                    <font-awesome-icon :icon="['fas', 'ellipsis-v']"></font-awesome-icon>
                                </v-btn>
                            </template>

                            <v-list>
                                <v-list-item
                                        v-for="(item, i) in fileMenu"
                                        :key="i"
                                        @click="onFileMenuClick(item.id)"
                                >
                                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>

                        <v-btn icon @click="onAddEditModule()">
                            <font-awesome-icon :icon="['fas', 'plus']"></font-awesome-icon>
                        </v-btn>
                    </v-toolbar>


                    <v-list v-if="orderedModules.length > 0">
                        <v-subheader>Modules</v-subheader>
                        <template v-for="(mod, index) in orderedModules">

                            <v-list-item
                                    :key="index"
                                    ripple
                                    @click=""
                            >
                                <v-list-item-avatar color="grey">
                                    <v-avatar>{{ index+1 }}</v-avatar>
                                </v-list-item-avatar>

                                <v-list-item-content @click="goToFileElements(mod.id)">
                                    <v-list-item-title>{{ mod.title }}</v-list-item-title>
                                    <v-list-item-subtitle>{{ mod.subtitle }} &mdash; {{ mod.text }}</v-list-item-subtitle>
                                </v-list-item-content>
                                <v-list-item-action>
                                    <v-btn icon ripple @click="onDestroyModule(mod.id)">
                                        <font-awesome-icon :icon="['fas', 'trash-alt']"></font-awesome-icon>
                                    </v-btn>
                                </v-list-item-action>
                            </v-list-item>
                        </template>
                    </v-list>
                    <v-list v-else>
                        <v-subheader>Geen Thema's</v-subheader>
                    </v-list>
                </v-card>
                <div v-else class="text-xs-center justify-center">
                    <v-progress-circular indeterminate color="primary" size="100" width="10"></v-progress-circular>
                </div>
            </v-flex>
        </v-layout>

        <add-edit-module-dialog @module-posted="get" @module-updated="get"></add-edit-module-dialog>
        <add-edit-file-dialog @file-posted="get" @file-updated="get"></add-edit-file-dialog>
        <arrange-modules-dialog @arrange-modules-updated="get"></arrange-modules-dialog>
        <delete-permanent-dialog @confirmed="destroyModule">
            <p>Weet u zeker dat u deze module wil verwijderen?</p>
        </delete-permanent-dialog>

    </v-container>
</template>
<script>
    import addEditModuleDialog from './../modules/AddEditModuleDialog';
    import addEditFileDialog from './AddEditFileDialog';
    import arrangeModulesDialog from './ArrangeModulesDialog';
    import deletePermanentDialog from './../../../shared/components/DeletePermanent';
    export default {
        name: 'AdminFile',
        title: 'Bestand - Admin',
        components: {
            deletePermanentDialog,
            addEditModuleDialog,
            addEditFileDialog,
            arrangeModulesDialog,
        },
        data() {
            return {
                file: null,
                selectedModule: null,
                orderedModules: [],
                rank: null,
                fileMenu: [
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
                axios.get(`/api/files/${this.$route.params.id}`)
                    .then(response => {
                        this.file = response.data.data
                        this.$root.fileColor = this.file.background_color

                        if(this.file.ranking) {
                            this.rank = this.file.ranking.ranking.split(",")

                            this.rank.forEach((item, index) => {
                                this.orderedModules[index] = _.find(this.file.modules, (item) => {
                                    return item.id == this.rank[index]
                                })
                            })
                        } else {
                            this.orderedModules = this.file.modules
                        }
                    })
                    .catch(response => console.error(response))
            },
            onFileMenuClick(id) {
                switch (id) {
                    case 1:
                        this.onAddEditFile()
                        break;
                    case 2:
                        this.onArrangeModules()
                        break;
                }
            },
            onAddEditFile() {
                eventHub.$emit('add-edit-file-dialog', this.file)
            },
            onArrangeModules() {
                eventHub.$emit('arrange-modules-dialog', this.file)
            },
            onAddEditModule() {
                eventHub.$emit('add-edit-module-dialog', {file_id: this.file.id})
            },
            goToFileElements(id) {
                this.$router.push({ name: 'fileElements', params: { fileId: this.$route.params.id, moduleId: id } })
            },
            onDestroyModule(id) {
                this.selectedModule = id
                eventHub.$emit('show-delete-permanent')
            },
            destroyModule() {
                axios.delete(`/api/modules/${this.selectedModule}`)
                    .then(response => {
                        this.get()
                        eventHub.$emit('show-message', response.data.status, response.data.data)
                    })
                    .catch(response => console.error(response))
            }
        }
    }
</script>
