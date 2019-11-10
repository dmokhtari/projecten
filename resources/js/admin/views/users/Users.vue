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
                        <template v-if="table.items.length > 0" v-slot:body="{ items }">
                            <tbody>
                                <tr v-for="(item, index) in items">
                                    <td><a @click="goToUser(item.id)">{{ item.email }}</a></td>
                                    <td>{{ item.forename }}</td>
                                    <td><v-chip>{{ item.roles[0].display_title }}</v-chip></td>
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
                        <template v-slot:no-data>
                            <p>Geen gebruikers gevonden!</p>
                        </template>
                    </v-data-table>
                    <!--<v-divider></v-divider>-->
                    <!--<v-layout>-->
                        <!--<v-flex xs12 md3 offset-md6>-->
                            <!--<v-select></v-select>-->
                        <!--</v-flex>-->
                        <!--<v-flex xs12 md3 class="text-xs-right pt-2">-->
                            <!--<span>{{ table.pagination.from }}-{{ table.pagination.to }} van {{ table.pagination.total }}</span>-->
                            <!--<v-btn icon-->
                                   <!--:disabled="table.pagination.prev_page_url == null"-->
                                   <!--@click="get(table.pagination.prev_page_url)"-->
                            <!--&gt;-->
                                <!--<font-awesome-icon :icon="['fas', 'chevron-left']"></font-awesome-icon>-->
                            <!--</v-btn>-->
                            <!--<v-btn icon-->
                                   <!--:disabled="table.pagination.next_page_url == null"-->
                                   <!--@click="get(table.pagination.next_page_url)"-->
                            <!--&gt;-->
                                <!--<font-awesome-icon :icon="['fas', 'chevron-right']"></font-awesome-icon>-->
                            <!--</v-btn>-->
                        <!--</v-flex>-->
                    <!--</v-layout>-->
                </v-card>
            </v-flex>
        </v-layout>

        <add-edit-user-dialog @posted="get" @updated="get"></add-edit-user-dialog>
        <upload-user-dialog @posted="get"></upload-user-dialog>
        <delete-permanent-dialog @confirmed="deleteUser">
            <p>Weet u zeker dat u deze <strong>gebruiker</strong> wil verwijderen?</p>
        </delete-permanent-dialog>
    </v-container>
</template>
<script>
    import addEditUserDialog from "./AddEditUserDialog";
    import uploadUserDialog from "./UploadUsersDialog";
    import deletePermanentDialog from './../../../shared/components/DeletePermanent';
    export default {
        name: 'adminUsers',
        title: 'Users - admin',
        components: {
            addEditUserDialog,
            uploadUserDialog,
            deletePermanentDialog
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
                        { text: 'Acties', sortable: false, value: 'action', width: '30' }
                    ],
                    items: [],
                    actions: [
                        { title: 'Tonen', value: 'show' },
                        { title: 'Wijzigen', value: 'edit' },
                        { title: 'Verwijderen', value: 'delete' },
                    ],
                    pagination: {
                        from: 0,
                        to: 0,
                        next_page_url: null,
                        prev_page_url: null,
                        total: 0,
                    }
                },
                selectedUser: null
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
                        this.onAddEditUser(obj)
                        break;
                    case 'delete':
                        this.onDeleteUser(obj.id)
                }
            },
            onAddEditUser(obj = null) {
                eventHub.$emit('add-edit-user-dialog', obj)
            },
            onUploadUser() {
                eventHub.$emit('upload-user-dialog')
            },
            onDeleteUser(id) {
                this.selectedUser = id
                eventHub.$emit('show-delete-permanent')
            },
            goToUser(id) {
                this.$router.push({ name: 'user', params: {id: id} })
            },
            get(page = null) {
                this.table.loading = true
                console.log('page', page)
                let url = '/api/users'
                url = page ? page : url
                axios.get(url)
                    .then(response => {
                        // for(let key in response.data.data) {
                        //     if(this.table.pagination.hasOwnProperty(key)) {
                        //         this.table.pagination[key] = response.data.data[key]
                        //     }
                        // }
                        this.table.items = response.data.data
                    })
                    .catch(response => console.error(response))
                    .finally(() => this.table.loading = false)
            },
            deleteUser() {
                axios.delete(`/api/users/${this.selectedUser}`)
                    .then(response => {
                        this.get()
                        eventHub.$emit('show-message', response.data.status, response.data.data)
                    })
                    .catch(response => console.error(response))
            }
        }
    }
</script>
