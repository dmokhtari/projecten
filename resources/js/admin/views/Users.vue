<template>
    <v-container>
        <v-layout>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="headline">
                        <font-awesome-icon :icon="['fas', 'users']" class="mr-2"></font-awesome-icon>
                        Gebruikers
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
                        <v-btn color="secondary" fab @click="onAddEditUser()">
                            <font-awesome-icon :icon="['fas', 'plus']"></font-awesome-icon>
                        </v-btn>
                        <v-btn color="secondary" fab @click="onUploadUser">
                            <font-awesome-icon :icon="['fas', 'file-upload']"></font-awesome-icon>
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
                            <td><a @click="goToModule(props.item.id)">{{ props.item.email }}</a></td>
                            <td>{{ props.item.forename }}</td>
                            <td><v-chip>{{ props.item.roles[0].display_title }}</v-chip></td>
                            <td class="text-xs-center">
                                <v-menu bottom left>
                                    <v-btn slot="activator" icon>
                                        <font-awesome-icon class="subheading" :icon="['fas', 'ellipsis-v']"></font-awesome-icon>
                                    </v-btn>
                                    <v-list>
                                        <v-list-tile v-for="(action, i) in table.actions"
                                                     :key="i"
                                                     @click="editOrDelete(action.value, props.item)"
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

        <add-edit-user-dialog @posted="get" @updated="get"></add-edit-user-dialog>
        <upload-user-dialog @posted="get"></upload-user-dialog>
    </v-container>
</template>
<script>
    import vuetifyColors from 'vuetify/es5/util/colors';
    import addEditUserDialog from "./../components/AddEditUserDialog";
    import uploadUserDialog from "./../components/UploadUsersDialog";
    export default {
        name: 'adminUsers',
        title: 'Users - admin',
        components: {
            addEditUserDialog,
            uploadUserDialog
        },
        data() {
            return {
                table: {
                    loading: false,
                    search: '',
                    headers: [
                        { text: 'Email', sortable: true, value: 'email' },
                        { text: 'Voornaam', sortable: false, value: 'subtitle' },
                        { text: 'Role', sortable: false, value: 'role' },
                        { text: 'Acties', sortable: false, value: '', width: '30' }
                    ],
                    items: [],
                    actions: [
                        { title: 'Tonen', value: 'edit' },
                        { title: 'Wijzigen', value: 'edit' },
                        { title: 'Verwijderen', value: 'delete' },
                    ]
                }
            }
        },
        computed: {

        },
        created() {
            this.get()
            this.getColors()
        },
        methods: {
            editOrDelete(action, obj) {
                if(action === 'edit') {
                    this.onAddEditUser(obj)
                } else {
                    this.delete(obj.id)
                }
            },
            onAddEditUser(obj = null) {
                eventHub.$emit('add-edit-user-dialog', obj)
            },
            onUploadUser() {
                eventHub.$emit('upload-user-dialog')
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
                axios.get('/api/users')
                    .then(response => this.table.items = response.data.data)
                    .catch(response => console.error(response))
                    .finally(() => this.table.loading = false)
            },
            delete(id) {
                axios.delete('/api/users/' + id)
                    .then(response => {
                        this.get()
                        eventHub.$emit('show-message', response.data.status, response.data.data)
                    })
                    .catch(response => eventHub.$emit('show-message', response.data.status, response.data.data))
            }
        }
    }
</script>
