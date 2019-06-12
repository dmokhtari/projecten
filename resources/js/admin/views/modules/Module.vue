<template>
    <v-container>
        <v-layout>
            <v-flex xs12>
                <v-card v-if="module" class="mt-5 text-xs-center">
                    <v-card-title class="justify-center">
                        <font-awesome-icon :icon="['fas', 'cubes']" class="display-4" style="margin-top:-50px;"></font-awesome-icon>
                    </v-card-title>
                    <v-card-title class="justify-center">
                        <h1>{{ module.title }}</h1>
                    </v-card-title>

                    <v-card-text>
                        <p>Gekoppelde elementen</p>
                        <font-awesome-icon :icon="['fas', 'arrow-down']"></font-awesome-icon>
                    </v-card-text>

                    <v-expansion-panel class="elevation-3 center-x" style="margin-bottom:-40px">
                        <v-expansion-panel-content v-for="(element, i) in module.elements" :key="i">
                            <template v-slot:actions>
                                <v-icon color="primary" class="subheading">$vuetify.icons.expand</v-icon>
                            </template>
                            <template v-slot:header>
                                <div>{{ element.title }}{{ element.subtitle ? '(' + element.subtitle + ')' : '' }}</div>
                            </template>
                            <v-card>
                                <v-card-text class="accent">
                                    <v-layout>
                                        <v-flex xs12 md10>
                                            {{ element.text }}
                                        </v-flex>
                                        <v-flex xs12 md2 class="text-xs-right">
                                            <div class="btn-group">
                                                <v-btn icon small @click="onAddEditElement(element)">
                                                    <font-awesome-icon :icon="['fas', 'pen']"></font-awesome-icon>
                                                </v-btn>
                                                <v-btn icon small @click="onGoToElement(element.id)">
                                                    <font-awesome-icon :icon="['fas', 'eye']"></font-awesome-icon>
                                                </v-btn>
                                                <v-btn icon small @click="onDestroyElement(element.id)">
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

        <add-edit-element-dialog @element-posted="get" @element-updated="get"></add-edit-element-dialog>
        <delete-permanent-dialog @confirmed="destroyElement">
            <p>Weet u zeker dat u deze element wil verwijderen?</p>
        </delete-permanent-dialog>

    </v-container>
</template>
<script>
    import addEditElementDialog from './../elements/AddEditElementDialog';
    import deletePermanentDialog from './../../../shared/components/DeletePermanent';
    export default {
        name: 'AdminModule',
        title: 'Module - Admin',
        components: {
            deletePermanentDialog,
            addEditElementDialog
        },
        data() {
            return {
                module: null,
                selectedElement: null
            }
        },
        created() {
            this.get()
        },
        methods: {
            get() {
                axios.get(`/api/modules/${this.$route.params.id}`)
                    .then(response => this.module = response.data.data)
                    .catch(response => console.error(response))
            },
            onAddEditElement(obj) {
                setTimeout(() => {
                    obj["files"]=[this.file]
                    eventHub.$emit('add-edit-element-dialog', obj)
                }, 20)
            },
            onGoToElement(id) {
                this.$router.push({ name: 'element', params: {id: id} })
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
