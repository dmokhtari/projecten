<template>
    <v-dialog
        v-model="dialog"
        width="300"
        persistent
    >
        <v-card>
            <v-card-title class="headline accent justify-center">
                Excel file uploaden
                <v-btn flat icon absolute right @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>
            </v-card-title>
            <form @submit.prevent="form.id ? put(form.id) : post()" class="px-4 py-4" @keydown="form.errors.clear($event.target.name)">
                <v-text-field
                    outline
                    type="text"
                    label="Upload een excel file"
                    prepend-inner-icon="file_upload"
                    :rules="[form.errors.get('excel_file')]"
                    :errors="form.errors.has('excel_file')"
                    v-model="form.excel_file_name"
                    @click="onClickFileInput"
                ></v-text-field>
                <input type="file" ref="file" style="display:none" @change="onFileChange">
                <v-card-actions>
                    <v-btn color="grey" @click="onCancel" flat>Annuleer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" type="submit">Opslaan</v-btn>
                </v-card-actions>
            </form>
        </v-card>
    </v-dialog>
</template>
<script>
    import { Form } from "../../shared/helpers/Form";

    export default {
        name: 'UploadUserDialog',
        data() {
            return {
                dialog: false,
                form: new Form({
                    excel_file: '',
                    excel_file_name: ''
                })
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
            },
            onClickFileInput() {
                this.$refs.file.click()
            },
            onFileChange(e) {
                let file = e.target.files[0]
                this.form.excel_file = file
                this.form.excel_file_name = file.name
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
                    .catch(response => eventHub.$emit('show-message', response.data.status, response.data.data))
            }
        }
    }
</script>
