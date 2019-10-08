<template>
    <v-dialog
        v-model="dialog"
        width="500"
        persistent
    >
        <v-card v-if="form">
            <v-card-title class="headline accent justify-center">
                {{ form.id ? 'Gebruiker wijzigen' : 'Gebruiker toevoegen' }}
                <v-btn text icon absolute right @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>
            </v-card-title>
            <v-form @submit.prevent="form.id ? put(form.id) : post()"
                    class="px-4 py-4"
                    @keydown.native="form.errors.clear($event.target.name)"
                    ref="userForm"
            >
                <v-text-field
                    outlined
                    type="email"
                    label="Email*"
                    :rules="[form.errors.get('email')]"
                    :errors="form.errors.has('email')"
                    v-model="form.email"
                    autofocus
                ></v-text-field>
                <v-select
                    outlined
                    :items="roles"
                    item-text="display_title"
                    item-value="id"
                    label="Rollen"
                    v-model="form.role"
                ></v-select>
                <v-layout row wrap justify-center>
                    <v-flex sm5 xs12 class="pr-md-6">
                        <v-text-field
                            outlined
                            type="text"
                            label="Voornaam"
                            :rules="[form.errors.get('forename')]"
                            :errors="form.errors.has('forename')"
                            v-model="form.forename"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6>
                        <v-text-field
                            outlined
                            type="text"
                            label="Achternaam"
                            :rules="[form.errors.get('surname')]"
                            :errors="form.errors.has('surname')"
                            v-model="form.surname"
                        ></v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout>
                    <v-flex sm4 xs12 class="pr-3">
                        <v-text-field
                            outlined
                            type="text"
                            label="Klas"
                            :rules="[form.errors.get('class')]"
                            :errors="form.errors.has('class')"
                            v-model="form.class"
                        ></v-text-field>
                    </v-flex>
                    <v-flex sm8 xs12>
                        <v-menu
                            ref="menu"
                            v-model="dateMenu"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            :return-value.sync="form.end_date_study"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on }">
                                <v-text-field
                                    label="Kies een datum"
                                    v-model="form.end_date_study"
                                    readonly
                                    prepend-inner-icon="event"
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="form.end_date_study" no-title scrollable>
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" @click="dateMenu = false">Annuleer</v-btn>
                                <v-btn text color="primary" @click="$refs.menu.save(form.end_date_study)">OK</v-btn>
                            </v-date-picker>
                        </v-menu>
                    </v-flex>
                </v-layout>
                <v-card-actions>
                    <v-btn color="grey" @click="onCancel" text>Annuleer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" type="submit">Opslaan</v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>
<script>
    import { Form } from "../../../shared/helpers/Form";
    export default {
        name: 'AddEditUserDialog',
        data() {
            return {
                dialog: false,
                form: null,
                dateMenu: false,
                roles: [],
            }
        },
        created() {
            eventHub.$on('add-edit-user-dialog', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('add-edit-user-dialog')
        },
        methods: {
            onShow(obj) {
                this.dialog = true
                this.initForm()
                this.getRoles()
                if(obj) {
                    for(let key in obj) {
                        if(obj[key] !== null) {
                            this.form[key] = obj[key]
                        }
                    }
                    this.form.role = obj.roles[0].pivot.role_id
                }
            },
            initForm() {
                this.form = new Form({
                    id: null,
                    email: '',
                    forename: '',
                    surname: '',
                    class: '',
                    role: '',
                    end_date_study: new Date().toISOString().substr(0, 10),
                })
            },
            onCancel() {
                this.dialog = false
                this.form = new Form({})
            },
            getRoles() {
                axios.get('/api/roles')
                    .then(response => this.roles = response.data.data)
                    .catch(response => console.error(response))
            },
            post() {
                this.form.post('/api/users')
                    .then(response => {
                        this.onCancel()
                        this.$emit('posted')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => {
                        this.$refs.userForm.validate()
                        console.error(response)
                        //eventHub.$emit('show-message', response.data.status,  response.data.data)
                    })
            },
            put(id) {
                this.form.put('/api/users/' + id)
                    .then(response => {
                        this.onCancel()
                        this.$emit('updated')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => {
                        this.$refs.userForm.validate()
                        console.error(response)
                        //eventHub.$emit('show-message', response.data.status,  response.data.data)
                    })
            },
        }
    }
</script>
