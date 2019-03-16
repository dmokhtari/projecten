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
                            label="zoeken"
                            append-icon="search"
                            single-line
                            hide-details
                        >
                        </v-text-field>
                        <v-spacer></v-spacer>
                        <v-btn color="secondary" fab @click="addEditDialog = true">
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

        <v-dialog
            v-model="addEditDialog"
            width="400"
            persistent
        >
            <v-card>
                <v-card-title class="headline accent justify-center" v-text="form.id ? 'Module wijzigen' : 'Module toevoegen'"></v-card-title>
                <form @submit.prevent="form.id ? put(form.id) : post()" class="px-4 py-4" @keydown="form.errors.clear($event.target.name)">
                    <v-text-field
                        outline
                        type="text"
                        label="Title"
                        :rules="[form.errors.get('title')]"
                        :errors="form.errors.has('title')"
                        v-model="form.title"
                        autofocus
                    ></v-text-field>
                    <v-text-field
                        outline
                        type="text"
                        label="Subtitle"
                        :rules="[form.errors.get('subtitle')]"
                        :errors="form.errors.has('subtitle')"
                        v-model="form.subtitle"
                    ></v-text-field>

                    <v-text-field
                        outline
                        type="text"
                        label="Upload een photo"
                        :rules="[form.errors.get('background_image')]"
                        :errors="form.errors.has('background_image')"
                        v-model="form.background_image_name"
                        @click="onClickImageInput"
                    ></v-text-field>
                    <input type="file" ref="image" style="display:none" @change="onImageChange">
                    <v-card-actions>
                        <v-btn color="grey" @click="onCancel" flat>Annuleer</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" type="submit">Opslaan</v-btn>
                    </v-card-actions>
                </form>
            </v-card>
        </v-dialog>
    </v-container>
</template>
<script>
    import { Form } from "../../shared/helpers/Form";
    import vuetifyColors from 'vuetify/es5/util/colors';
    export default {
        name: 'adminModules',
        title: 'Modules - admin',
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
                addEditDialog: false,
                colors: [],
                form: new Form({
                    id: null,
                    title: '',
                    subtitle: '',
                    background_image: '',
                    background_image_name: null,
                    background_color: null,
                })
            }
        },
        created() {
            this.get()
            this.getColors()
        },
        methods: {
            editOrDelete(action, obj) {
                if(action === 'edit') {
                    for(let key in obj) {
                        if(obj[key] !== null) {
                            this.form[key] = obj[key]
                        }
                    }
                    this.addEditDialog = true
                } else {
                    this.delete(obj.id)
                }
            },
            onClickImageInput() {
                this.$refs.imageInput.click()
            },
            onImageChange(e) {
                let image = e.target.files[0]
                this.form.background_image = image
                this.form.background_image_name = image.name
            },
            onCancel() {
                this.addEditDialog = false
                this.form = new Form({})
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
            post() {
                this.form.post('/api/modules')
                    .then(response => {
                        this.addEditDialog = false
                        this.get()
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => eventHub.$emit('show-message', response.data.status,  response.data.data))
            },
            put(id) {
                this.form.put('/api/modules/' + id)
                    .then(response => {
                        this.addEditDialog = false
                        this.get()
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => eventHub.$emit('show-message', response.data.status,  response.data.data))
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
