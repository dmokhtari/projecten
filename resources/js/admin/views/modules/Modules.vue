<template>
    <v-container>
        <v-layout>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="headline">
                        <font-awesome-icon :icon="['fas', 'book']" class="mr-2"></font-awesome-icon>
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
        <add-edit-module-dialog @posted="get" @update="get"></add-edit-module-dialog>
    </v-container>
</template>
<script>
    import AddEditModuleDialog from './AddEditModuleDialog';
    import vuetifyColors from 'vuetify/es5/util/colors';
    export default {
        name: 'adminModules',
        title: 'Modules - admin',
        components: {
            AddEditModuleDialog
        },
        data() {
            return {
                table: {
                    loading: false,
                    search: '',
                    headers: [
                        { text: 'Title', sortable: true, value: 'title' },
                        { text: 'Subtitle', sortable: false, value: 'subtitle' },
                        { text: 'Acties', sortable: false, value: '', width: '30' }
                    ],
                    items: [],
                    actions: [
                        { title: 'Wijzigen', value: 'edit' },
                        { title: 'Verwijderen', value: 'delete' },
                    ]
                },
                colors: [],

            }
        },
        created() {
            this.get()
            this.getColors()
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
                        this.delete(obj.id)
                }
            },
            onAddEditModule(obj = null) {
                eventHub.$emit('add-edit-module-dialog', obj)
            },
            getColors() {
                let col = Object.entries(vuetifyColors)
                console.log(col)
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
            delete(id) {
                axios.delete('/api/modules/' + id)
                    .then(response => {
                        this.get()
                        eventHub.$emit('show-message', response.data.status, response.data.data)
                    })
                    .catch(response => eventHub.$emit('show-message', response.data.status, response.data.data))
            }
        }
    }
</script>
