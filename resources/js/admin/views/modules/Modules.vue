<template>
    <v-container>
        <v-layout>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="headline">
                        <font-awesome-icon :icon="['fas', 'cubes']" class="mr-2"></font-awesome-icon>
                        Modules
                        <v-divider
                            class="mx-3"
                            inset
                            vertical
                        ></v-divider>
                        <v-text-field
                            v-model="table.search"
                            label="Zoeken"
                            append-icon="search"
                            single-line
                            hide-details
                        >
                        </v-text-field>
                        <v-spacer></v-spacer>
                        <v-btn color="secondary" fab @click="onAddEditModule()">
                            <font-awesome-icon :icon="['fas', 'plus']"></font-awesome-icon>
                        </v-btn>
                    </v-card-title>
                    <v-data-table
                        :headers="table.headers"
                        :items="table.items"
                        :loading="table.loading"
                        :search="table.search"
                    >
                        <v-progress-linear slot="progress" indeterminate></v-progress-linear>
                        <template slot="items" slot-scope="props">
                            <td><a @click="goToModule(props.item.id)">{{ props.item.title }}</a></td>
                            <td>{{ props.item.subtitle }}</td>
                            <td>{{ showFileTitle(props.item.files) }}</td>
                            <td><v-chip>{{ props.item.elements_count }}</v-chip></td>
                            <td class="text-xs-center">
                                <v-menu bottom left>
                                    <v-btn slot="activator" icon>
                                        <font-awesome-icon class="subheading" :icon="['fas', 'ellipsis-v']"></font-awesome-icon>
                                    </v-btn>
                                    <v-list>
                                        <v-list-tile v-for="(action, i) in table.actions"
                                                     :key="i"
                                                     @click="onAction(action.value, props.item)"
                                        >
                                            <v-list-tile-title>{{ action.title }}</v-list-tile-title>
                                        </v-list-tile>
                                    </v-list>
                                </v-menu>
                            </td>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
        </v-layout>
        <add-edit-module-dialog @module-posted="get" @module-updated="get"></add-edit-module-dialog>
        <delete-permanent-dialog @confirmed="deleteModule">
            <p>Weet u zeker dat u deze module wil verwijderen?</p>
        </delete-permanent-dialog>
    </v-container>
</template>
<script>
    import AddEditModuleDialog from './AddEditModuleDialog';
    import DeletePermanentDialog from './../../../shared/components/DeletePermanent';
    export default {
        name: 'adminModules',
        title: 'Modules - admin',
        components: {
            AddEditModuleDialog,
            DeletePermanentDialog
        },
        data() {
            return {
                table: {
                    loading: false,
                    search: '',
                    headers: [
                        { text: 'Title', sortable: true, value: 'title' },
                        { text: 'Subtitle', sortable: false, value: 'subtitle' },
                        { text: 'Gekoppeld Bestanden', sortable: false, value: 'file' },
                        { text: 'Antal elementen', sortable: false, value: 'elements_count', width: '30' },
                        { text: 'Acties', sortable: false, value: '', width: '30' }
                    ],
                    items: [],
                    actions: [
                        { title: 'Tonen', value: 'show' },
                        { title: 'Wijzigen', value: 'edit' },
                        { title: 'Verwijderen', value: 'delete' },
                    ]
                },
                selectedModule: null
            }
        },
        computed: {
            showFileTitle(item) {
                return (item) => {
                    if(item.length > 0) {
                        return item[0].title
                    } else {
                        return 'Geen bestanden gekoppeld'
                    }
                }
            }
        },
        created() {
            this.get()
        },
        methods: {
            onAction(action, obj) {
                switch (action) {
                    case 'show':
                        this.goToModule(obj.id)
                        break;
                    case 'edit':
                        this.onAddEditModule(obj)
                        break;
                    case 'delete':
                        this.onDeleteModule(obj.id)
                }
            },
            onAddEditModule(obj = null) {
                eventHub.$emit('add-edit-module-dialog', obj)
            },
            onDeleteModule(id) {
                this.selectedModule = id
                eventHub.$emit('show-delete-permanent')
            },
            goToModule(id) {
                this.$router.push({ name: 'module', params: {id: id} })
            },
            get() {
                this.table.loading = true
                axios.get('/api/modules')
                    .then(response => this.table.items = response.data.data)
                    .catch(response => console.error(response))
                    .finally(() => this.table.loading = false)
            },
            deleteModule() {
                axios.delete(`/api/modules/${this.selectedModule}`)
                    .then(response => {
                        this.get()
                        eventHub.$emit('show-message', response.data.status, response.data.data)
                    })
                    .catch(error => console.error(error))
            }
        }
    }
</script>
