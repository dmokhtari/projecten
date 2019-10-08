<template>
    <v-container>
        <v-layout>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="headline">
                        <font-awesome-icon :icon="['fas', 'file']" class="mr-2"></font-awesome-icon>
                        Bestanden
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
                        ></v-text-field>
                        <v-spacer></v-spacer>
                        <v-btn color="secondary" fab @click="onAddEditFile()">
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
                        <template v-slot:body="{ items }">
                            <tbody>
                                <tr v-for="item in items" :key="item.id">
                                    <td><a @click="goToFile(item.id)">{{ item.title }}</a></td>
                                    <td>{{ item.subtitle }}</td>
                                    <td class="text-xs-center">
                                        <v-menu bottom left>
                                            <template v-slot:activator="{ on }">
                                                <v-btn icon v-on="on">
                                                    <font-awesome-icon class="subheading" :icon="['fas', 'ellipsis-v']"></font-awesome-icon>
                                                </v-btn>
                                            </template>
                                            <v-list>
                                                <v-list-item v-for="(action, i) in table.actions"
                                                             :key="i"
                                                             @click="onAction(action.value, item)"
                                                >
                                                    <v-list-item-title>{{ action.title }}</v-list-item-title>
                                                </v-list-item>
                                            </v-list>
                                        </v-menu>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
        </v-layout>
        <add-edit-file-dialog @file-posted="get" @file-updated="get"></add-edit-file-dialog>
        <delete-permanent-dialog @confirmed="deleteFile">
            <p>Weet u zeker dat u dit <strong>bestand</strong> wil verwijderen?</p>
        </delete-permanent-dialog>
    </v-container>
</template>
<script>
    import AddEditFileDialog from './AddEditFileDialog';
    import DeletePermanentDialog from './../../../shared/components/DeletePermanent';
    export default {
        name: 'Files',
        title: 'Files - admin',
        components: {
            AddEditFileDialog,
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
                        { text: 'Acties', sortable: false, value: 'action', width: '30' }
                    ],
                    items: [],
                    actions: [
                        { title: 'Tonen', value: 'show' },
                        { title: 'Wijzigen', value: 'edit' },
                        { title: 'Verwijderen', value: 'delete' },
                    ]
                },
                selectedFile: null
            }
        },
        created() {
            this.get()
        },
        methods: {
            onAction(action, obj) {
                switch (action) {
                    case 'show':
                        this.goToFile(obj.id)
                        break;
                    case 'edit':
                        this.onAddEditFile(obj)
                        break;
                    case 'delete':
                        this.onDeleteFile(obj.id)
                }
            },
            onAddEditFile(obj = null) {
                eventHub.$emit('add-edit-file-dialog', obj)
            },
            onDeleteFile(id) {
                this.selectedFile = id
                eventHub.$emit('show-delete-permanent')
            },
            goToFile(id) {
                this.$router.push({ name: 'file', params: {id: id} })
            },
            get() {
                this.table.loading = true
                axios.get('/api/files')
                    .then(response => this.table.items = response.data.data)
                    .catch(response => console.error(response))
                    .finally(() => this.table.loading = false)
            },
            deleteFile() {
                axios.delete(`/api/files/${this.selectedFile}`)
                    .then(response => {
                        this.get()
                        eventHub.$emit('show-message', response.data.status, response.data.data)
                    })
                    .catch(response => console.error(response))
            }
        }
    }
</script>
