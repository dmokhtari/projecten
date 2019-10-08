<template>
    <v-app light>
        <v-layout>
            <v-flex md8 class="d-none d-md-flex">
                <v-carousel height="100vh" :show-arrows="false">
                    <v-carousel-item
                        v-for="(item, i) in items"
                        :key="i"
                        :src="item.src"
                    >
                    </v-carousel-item>
                </v-carousel>
            </v-flex>

            <v-flex xs12 md4>
                <v-card class="px-5 elevation-0 transparent pt-10">
                    <v-layout justify-center>
                        <v-img src="/assets/img/logo.png" alt="Summa College logo" max-width="200"></v-img>
                    </v-layout>
                    <v-card-title class="title">
                        {{ windowTitle }}
                    </v-card-title>
                    <v-window v-model="page">
                        <v-window-item :value="1">
                            <v-card-text>
                                <v-form @submit.prevent="requestLogin" @keydown.native="form.errors.clear($event.target.name)" ref="loginForm">
                                    <v-text-field
                                        :errors="form.errors.has('email')"
                                        :rules="[form.errors.get('email')]"
                                        v-model="form.email"
                                        label="Email"
                                        type="email"
                                        outlined
                                    ></v-text-field>
                                    <v-text-field
                                        :errors="form.errors.has('password')"
                                        :rules="[form.errors.get('password')]"
                                        v-model="form.password"
                                        label="Wachtwoord"
                                        type="password"
                                        outlined
                                    ></v-text-field>
                                    <v-btn class="mt-4" type="submit" large :disabled="form.errors.any()">Inloggen</v-btn>
                                    <v-btn class="mt-4" @click="page = 2" text color="primary">Wachtwoord herstellen?</v-btn>
                                </v-form>
                            </v-card-text>
                        </v-window-item>
                        <v-window-item :value="2">
                            <v-card-text>
                                <v-form @submit.prevent="requestEmailLink" @keydown.native="emailForm.errors.clear($event.target.name)" ref="resetPassword">
                                    <v-text-field
                                        :errors="emailForm.errors.has('email')"
                                        :rules="[emailForm.errors.get('email')]"
                                        v-model="emailForm.email"
                                        label="Email"
                                        type="email"
                                        outlined
                                    ></v-text-field>
                                    <v-btn class="mt-4" type="submit" large :disabled="emailForm.errors.any()">Email verzenden</v-btn>
                                    <v-btn class="mt-4" @click="page = 1" text color="primary">Terug naar Inloggen</v-btn>
                                </v-form>
                            </v-card-text>
                        </v-window-item>
                    </v-window>
                </v-card>
            </v-flex>
        </v-layout>
        <message-box></message-box>
    </v-app>
</template>

<script>
    import { Form } from "../../shared/helpers/Form";
    import MessageBox from '../../shared/components/MessageBox';
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
                }),
                items: [
                    {
                        src: '/assets/img/item1.png'
                    },
                    {
                        src: '/assets/img/item2.png'
                    },
                    {
                        src: '/assets/img/item3.png'
                    }
                ]
            }
        },
        computed: {
            windowTitle() {
                switch (this.page) {
                    case 1: return 'Inloggen'
                    case 2: return 'Wachtwoord Vergeten'
                }
            }
        },
        methods: {
            requestLogin() {
                this.form.post('/login')
                    .then(response => location.reload())
                    .catch(error => {
                        this.$refs.loginForm.validate()
                        console.error(error)
                    })
            },
            requestEmailLink() {
                this.emailForm.post('/passwords/email')
                    .then(response => eventHub.$emit('show-message', response.status, response.data))
                    .catch(error => {
                        this.$refs.resetPassword.validate()
                        console.error(error)
                    })
            }
        }
    }
</script>
