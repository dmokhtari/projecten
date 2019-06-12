<template>
    <v-app light>
        <v-img
            src="/assets/img/childcare.jpg"
            height="100vh"
            gradient="rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)"
        >
            <v-layout align-center justify-center style="margin-top:70px">
                <v-flex xs10 md4>
                    <v-card color="grey lighten-3" class="py-2">
                        <v-card-title class="justify-center">
                            <h1>Wachtwoord wijzigen</h1>
                        </v-card-title>
                        <form @submit.prevent="requestChangePassword" @keydown="form.errors.clear($event.target.name)" class="px-5">
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
                            <v-text-field
                                outline
                                :errors="form.errors.has('password_confirmation')"
                                :rules="[form.errors.get('password_confirmation')]"
                                v-model="form.password_confirmation"
                                label="Wachtwoord"
                                type="password"
                            ></v-text-field>
                            <v-btn block type="submit" large :disabled="form.errors.any()">Wachtwoord wijzigen</v-btn>
                        </form>
                        <v-card-actions class="justify-center">
                            <a href="/passwords/email" class="pt-3">wachtwoord vergeten</a>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-img>
        <message-box></message-box>
    </v-app>
</template>
<script>
    import { Form } from "../../shared/helpers/Form";
    import MessageBox from '../../shared/components/MessageBox';
    export default {
        name: 'ResetPasswordComponent',
        props: ['strToken', 'strEmail'],
        components: {
            MessageBox
        },
        data() {
            return {
                form: new Form({
                    token: this.strToken,
                    email: this.strEmail,
                    password: null,
                    password_confirmation: null
                })
            }
        },
        methods: {
            requestChangePassword() {
                this.form.post('/passwords/reset')
                    .then(response => {
                        eventHub.$emit('show-message', response.status, response.data)
                        setTimeout(() => {
                            window.location.href = '/home'
                        }, 1500)
                    })
                    .catch(response => {
                        eventHub.$emit('show-message', response.status, response.data)
                        console.error(response)
                    })
            }
        }
    }
</script>
