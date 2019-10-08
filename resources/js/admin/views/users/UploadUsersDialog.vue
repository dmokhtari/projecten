<template>
    <v-dialog
        v-model="dialog"
        width="350"
        persistent
    >
        <v-card v-if="form">
            <v-card-title class="headline accent justify-center">
                Excel file uploaden
                <v-btn text icon absolute right @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>
            </v-card-title>
            <v-form @submit.prevent="form.id ? put(form.id) : post()"
                    class="px-4 py-4"
                    @keydown="form.errors.clear($event.target.name)"
                    ref="userUploadForm"
            >
                <v-file-input
                    label="upload een excel file"
                    outlined
                    :rules="[form.errors.get('excel_file')]"
                    :errors="form.errors.has('excel_file')"
                    v-model="form.excel_file"
                ></v-file-input>
                <v-card-actions>
                    <v-btn color="grey" @click="onCancel" text>Annuleren</v-btn>
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
        name: 'UploadUserDialog',
        data() {
            return {
                dialog: false,
                form: null
            }
        },
        created() {
            eventHub.$on('upload-user-dialog', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('upload-user-dialog')
        },
        methods: {
            onShow() {
                this.dialog = true
                this.initForm()
            },
            initForm() {
                this.form = new Form({
                    excel_file: []
                })
            },
            onCancel() {
                this.dialog = false
                this.form = new Form({})
            },
            post() {
                this.form.post('/api/users/import')
                    .then(response => {
                        this.onCancel()
                        this.$emit('posted')
                        eventHub.$emit('show-message', response.status, response.data)
                    })
                    .catch(response => {
                        console.error(response)
                        this.$refs.userUploadForm.validate()
                        //eventHub.$emit('show-message', response.data.status, response.data.data)
                    })
            }
        }
    }
</script>
