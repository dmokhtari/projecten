<template>
    <v-container>
        <v-layout>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="headline">
                        <font-awesome-icon :icon="['fas', 'sign']" class="mr-2"></font-awesome-icon>
                        Icons
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
                        <v-btn color="secondary" fab @click="onAddEditIcon()">
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
                        <template v-slot:body="{items}">
                            <tbody>
                            <tr v-for="item in items" :key="item.id">
                                <td>{{ item.title }}</td>
                                <td>
                                    <v-avatar
                                        :tile="false"
                                    >
                                        <img :src="'/storage/' + item.src" :alt="item.name">
                                    </v-avatar>
                                </td>
                                <td>
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
        <add-edit-icon-dialog @posted="get" @update="get"></add-edit-icon-dialog>
        <delete-permanent-dialog @confirmed="deleteIcon">
            <p>Weet u zeker dat u deze <strong>icon</strong> wil verwijderen?</p>
        </delete-permanent-dialog>
    </v-container>
</template>
<script>
    import AddEditIconDialog from './AddEditIconDialog';
    import DeletePermanentDialog from './../../../shared/components/DeletePermanent';
    export default {
        name: 'adminSettingsIcon',
        title: 'Icons - admin',
        components: {
            AddEditIconDialog,
            DeletePermanentDialog
        },
        data() {
            return {
                table: {
                    loading: false,
                    search: '',
                    headers: [
                        { text: 'Title', sortable: true, value: 'title' },
                        { text: 'Icon', sortable: false, value: 'subtitle' },
                        { text: 'Acties', sortable: false, value: 'action', width: '30' }
                    ],
                    items: [],
                    actions: [
                        { title: 'Tonen', value: 'show' },
                        { title: 'Wijzigen', value: 'edit' },
                        { title: 'Verwijderen', value: 'delete' },
                    ]
                },
                selectedIcon: null,
            }
        },
        created() {
            this.get()
        },
        methods: {
            onAction(action, obj) {
                switch (action) {
                    case 'show':
                        this.onShowIcon(obj.src)
                        break;
                    case 'edit':
                        this.onAddEditIcon(obj)
                        break;
                    case 'delete':
                        this.onDeleteIcon(obj.id)
                }
            },
            onAddEditIcon(obj = null) {
                eventHub.$emit('add-edit-icon-dialog', obj)
            },
            onShowIcon(src) {
                window.open('/' + src, '_blank')
            },
            get() {
                this.table.loading = true
                axios.get('/api/settings/icons')
                    .then(response => this.table.items = response.data.data)
                    .catch(response => console.error(response))
                    .finally(() => this.table.loading = false)
            },
            onDeleteIcon(id) {
                this.selectedIcon = id
                eventHub.$emit('show-delete-permanent')
            },
            deleteIcon(id) {
                axios.delete('/api/settings/icons/' + id)
                    .then(response => {
                        this.get()
                        eventHub.$emit('show-message', response.data.status, response.data.data)
                    })
                    .catch(error => console.error(error))
            }
        }
    }
</script>
