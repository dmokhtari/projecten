<template>
    <v-container>
        <v-layout>
            <v-flex xs12>
                <v-card v-if="file" class="mt-5 text-xs-center">
                    <v-card-title class="justify-center">
                        <font-awesome-icon :icon="['fas', 'file']" class="display-4" style="margin-top:-50px;"></font-awesome-icon>
                    </v-card-title>
                    <v-card-title class="justify-center">
                        <h1>{{ file.title }}</h1>
                    </v-card-title>

                    <v-card-text>
                        <p>Gekoppelde modules</p>
                        <font-awesome-icon :icon="['fas', 'arrow-down']"></font-awesome-icon>
                    </v-card-text>

                    <v-expansion-panel class="elevation-3 center-x" style="margin-bottom:-40px">
                        <v-expansion-panel-content v-for="(mod, i) in file.modules" :key="i">
                            <template v-slot:actions>
                                <v-icon color="primary" class="subheading">$vuetify.icons.expand</v-icon>
                            </template>
                            <template v-slot:header>
                                <div>{{ mod.title }}{{ mod.subtitle ? '(' + mod.subtitle + ')' : '' }}</div>
                            </template>
                            <v-card>
                                <v-card-text class="accent">
                                    <v-layout>
                                        <v-flex xs12 md10>
                                            {{ mod.text }}
                                        </v-flex>
                                        <v-flex xs12 md2 class="text-xs-right">
                                            <div class="btn-group">
                                                <v-btn icon small @click="onAddEditModule(mod)">
                                                    <font-awesome-icon :icon="['fas', 'pen']"></font-awesome-icon>
                                                </v-btn>
                                                <v-btn icon small @click="onGoToModule(mod.id)">
                                                    <font-awesome-icon :icon="['fas', 'eye']"></font-awesome-icon>
                                                </v-btn>
                                                <v-btn icon small @click="onDestroyModule(mod.id)">
                                                    <font-awesome-icon :icon="['fas', 'trash-alt']"></font-awesome-icon>
                                                </v-btn>
                                            </div>
                                        </v-flex>
                                    </v-layout>
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-card>
                <div v-else class="text-xs-center justify-center">
                    <v-progress-circular indeterminate color="primary" size="100" width="10"></v-progress-circular>
                </div>
            </v-flex>
        </v-layout>

        <add-edit-module-dialog @module-posted="get" @module-updated="get"></add-edit-module-dialog>
        <delete-permanent-dialog @confirmed="destroyModule">
            <p>Weet u zeker dat u deze module wil verwijderen?</p>
        </delete-permanent-dialog>

    </v-container>
</template>
<script>
    import addEditModuleDialog from './../modules/AddEditModuleDialog';
    import deletePermanentDialog from './../../../shared/components/DeletePermanent';
    export default {
        name: 'AdminFile',
        title: 'Bestand - Admin',
        components: {
            deletePermanentDialog,
            addEditModuleDialog
        },
        data() {
            return {
                file: null,
                selectedModule: null
            }
        },
        created() {
            this.get()
        },
        methods: {
            get() {
                axios.get(`/api/files/${this.$route.params.id}`)
                    .then(response => this.file = response.data.data)
                    .catch(response => console.error(response))
            },
            onAddEditModule(obj) {
                setTimeout(() => {
                    obj["files"]=[this.file]
                    eventHub.$emit('add-edit-module-dialog', obj)
                }, 20)
            },
            onGoToModule(id) {
                this.$router.push({ name: 'module', params: { id: id } })
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
