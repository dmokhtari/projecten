<template>
    <v-container>
        <v-layout justify-center>
            <v-flex xs12 md6 lg6>
                <v-card>
                    <v-img
                    src="https://i.imgur.com/gyBWTvq.jpg"
                    aspect-ratio="10"
                    ></v-img>

                    <v-toolbar
                        card
                        color="purple"
                        >
                        <v-icon>mdi-account</v-icon>
                        <v-toolbar-title class=".font-weight-medium"><span style="color:white">Profile</span></v-toolbar-title>
                        <v-spacer></v-spacer>
                        
                        <v-btn
                            color="secondary"
                            fab
                            small
                            @click="isEditing = !isEditing"
                        >
                        <font-awesome-icon :icon="['far', 'edit']"></font-awesome-icon>
                        </v-btn>

                         <v-btn
                            color="secondary"
                            fab
                            small
                            @click="passDialog = true"
                        >
                        <font-awesome-icon :icon="['fas', 'key']"></font-awesome-icon>
                        </v-btn>

                    </v-toolbar>
                    <form>
                        <v-card-text>
                            <v-text-field
                                :disabled="!isEditing"
                                label="Naam"
                                :rules="[userForm.errors.get('forename')]"
                                :errors="userForm.errors.has('forename')"
                                v-model="userForm.forename"
                            ></v-text-field>
                            <v-text-field
                                disabled       
                                label="E-mail"
                                v-model="userForm.email"
                            ></v-text-field>
                        </v-card-text>
                        <v-divider></v-divider>

                        <v-card-actions>
                            <v-btn flat color="primary">Update</v-btn>
                        </v-card-actions>
                    </form>

                </v-card>
            </v-flex>
        </v-layout>
        <v-dialog v-model="passDialog" width="400px" persistent>
            <v-card>
                <v-card-title class="headline accent justify-center">
                    Wachtwoord Wijzigen
                    <v-btn flat icon absolute right @click="onCancel">
                        <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                    </v-btn>
                </v-card-title>

                    <br>
                    <form class="px-3" @keydown="form.errors.clear($event.target.name)" @submit.prevent="updatePassword"> 

                        <v-text-field
                            type="password"
                            autocomplete="off"
                            outline
                            label="Oud Wachtwoord"
                            :rules="[form.errors.get('old_password')]"
                            :errors="form.errors.has('old_password')"
                            v-model="form.old_password"
                            autofocus
                        ></v-text-field>

                        <v-text-field
                            type="password"
                            autocomplete="off"
                            outline
                            label="Nieuw Wachtwoord"
                            :rules="[form.errors.get('password')]"
                            :errors="form.errors.has('password')"
                            v-model="form.password"
                        ></v-text-field>
                        <v-text-field
                            type="password"
                            autocomplete="off"
                            outline
                            label="Verifieer Nieuw Wachtwoord"
                            :rules="[form.errors.get('password_confirmation')]"
                            :errors="form.errors.has('password_confirmation')"
                            v-model="form.password_confirmation"
                        ></v-text-field>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-btn color="grey" @click="onCancel" flat>Annuleer</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" type="submit">Update</v-btn>
                        </v-card-actions>
                    </form>
            </v-card>
        </v-dialog>
    </v-container>
</template>
<script>
import { Form } from "../../../shared/helpers/Form";
export default {
    name: 'Profile',
    data() {
        return {
            hasSaved: false,
            isEditing: null,
            model: null,
            active: null,
            passDialog: false,
            userForm: new Form({
                forename: '',
                surname: '',
                email: ''
            }),
            form: new Form({
                old_password: '',
                password: '',
                password_confirmation: '',
            }),
            
      }
    },
    created() {
        this.showUser()
    },
    methods: {
        next () {
        const active = parseInt(this.active)
        this.active = (active < 2 ? active + 1 : 0)
        },
        onCancel() {
                this.passDialog = false
                this.form = new Form({})
            },
          
        customFilter (item, queryText, itemText) {
        const textOne = item.name.toLowerCase()
        const textTwo = item.abbr.toLowerCase()
        const searchText = queryText.toLowerCase()

        return textOne.indexOf(searchText) > -1 ||
          textTwo.indexOf(searchText) > -1
      },
      save () {
        this.isEditing = !this.isEditing
        this.hasSaved = true
      },
      updatePassword() {
        this.form('/api/auth/user/update_password')
            .then(response => {
                this.onCancel()
                eventHub.$emit('show-message', response.status,  response.data)
            })
            .catch(error => console.error(error))
      },
     showUser(){
         axios.get('/api/auth/user/show')
            .then(response => {
                this.userForm.forename = response.data.data.forename
                this.userForm.surname = response.data.data.surname
                this.userForm.email = response.data.data.email
                
            })
            .catch(error => console.error(error))
     },
     

    }

}
</script>
