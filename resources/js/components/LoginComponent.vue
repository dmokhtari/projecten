<template>
    <v-app light>
        <v-img
            src="/assets/img/childcare.jpg"
            height="100vh"
            gradient="rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)"
        >
            <v-layout align-center justify-center style="margin-top:70px">
                <v-flex xs10 md4>
                    <v-stepper v-model="page" class="elevation-5">
                        <v-stepper-items>
                            <v-stepper-content step="1">
                                <v-card>
                                    <v-card-title class="justify-center mb-3">
                                        <h1>Inloggen</h1>
                                    </v-card-title>
                                    <form @submit.prevent="requestLogin" @keydown="form.errors.clear($event.target.name)" class="px-3">
                                        <v-text-field
                                            outline
                                            :errors="form.errors.has('email')"
                                            :rules="[form.errors.get('email')]"
                                            v-model="form.email"
                                            label="Email"
                                            type="email"
                                        ></v-text-field>
                                        <v-text-field
                                            outline
                                            :errors="form.errors.has('password')"
                                            :rules="[form.errors.get('password')]"
                                            v-model="form.password"
                                            label="Wachtwoord"
                                            type="password"
                                        ></v-text-field>
                                        <v-btn block type="submit" large :disabled="form.errors.any()">Inloggen</v-btn>
                                    </form>
                                    <v-card-actions class="justify-center pt-5">
                                        <v-btn @click="page = 2" small flat color="primary">Wachtwoord vergeten?</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>

                            <v-stepper-content step="2" class="grey lighten-3">
                                <v-card color="grey lighten-3">
                                    <v-card-title class="justify-center mb-3">
                                        <h1>Wachtwoord Wijzigen</h1>
                                    </v-card-title>
                                    <form @submit.prevent="requestEmailLink" @keydown="emailForm.errors.clear($event.target.name)" class="px-3">
                                        <v-text-field
                                            outline
                                            :errors="emailForm.errors.has('email')"
                                            :rules="[emailForm.errors.get('email')]"
                                            v-model="emailForm.email"
                                            label="Email"
                                            type="email"
                                        ></v-text-field>
                                        <v-btn block type="submit" large :disabled="emailForm.errors.any()">Email verzenden</v-btn>
                                    </form>
                                    <v-card-actions class="justify-center pt-5">
                                        <v-btn @click="page = 1" small flat color="primary">Inloggen</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>
                        </v-stepper-items>
                    </v-stepper>
                </v-flex>
            </v-layout>
        </v-img>
        <message-box></message-box>
    </v-app>
</template>

<script>
    import { Form } from "../helpers/Form";
    import MessageBox from './MessageBox';
    import axios from 'axios';
    export default {
        name: 'LoginComponent',
        components: {
            MessageBox
        },
        data() {
            return {
                page: 1,
                form: new Form({
                    email: null,
                    password: null
                }),
                emailForm: new Form({
                    email: null
                })
            }
        },
        methods: {
            requestLogin() {
                this.form.post('/login')
                    .then(response => location.reload())
                    .catch(response => console.log(response))
            },
            requestEmailLink() {
                this.emailForm.post('/passwords/email')
                    .then(response => eventHub.$emit('show-message', response.status, response.data))
                    .catch(response => eventHub.$emit('show-message', response.status, response.data))
            }
        }
    }
</script>
<style scoped lang="scss">

</style>
