<template>
    <v-dialog
        v-if="form"
        v-model="dialog"
        width="500"
        persistent
    >
        <v-card>
            <v-card-title class="headline accent justify-center">
                {{ form.id ? 'Module wijzigen' : 'Module toevoegen' }}
                <v-btn flat icon absolute right @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>
            </v-card-title>
            <v-form @submit.prevent="form.id ? put(form.id) : post()"
                    class="px-4 py-4"
                    @keydown.native="form.errors.clear($event.target.name)"
                    ref="moduleForm"
            >
                <v-text-field
                    outline
                    label="Title*"
                    :rules="[form.errors.get('title')]"
                    :errors="form.errors.has('title')"
                    v-model="form.title"
                    autofocus
                ></v-text-field>
                <v-text-field
                    outline
                    label="Subtitle"
                    :rules="[form.errors.get('subtitle')]"
                    :errors="form.errors.has('subtitle')"
                    v-model="form.subtitle"
                ></v-text-field>
                <v-textarea
                    outline
                    label="Text"
                    rows="7"
                    :rules="[form.errors.get('text')]"
                    :errors="form.errors.has('text')"
                    v-model="form.text"
                ></v-textarea>
                <v-select
                    outline
                    clearable
                    label="Koppel aan een file"
                    :items="files"
                    item-text="title"
                    item-value="id"
                    v-model="form.file_id"
                    :rules="[form.errors.get('file_id')]"
                    :errors="form.errors.has('file_id')"
                    :return-object="false"
                ></v-select>

                <v-card-actions>
                    <v-btn color="grey" @click="onCancel" flat>Annuleren</v-btn>
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
        name: 'AddEditModuleDialog',
        data() {
            return {
                dialog: false,
                form: null,
                files: []
            }
        },
        mounted() {
            eventHub.$on('add-edit-module-dialog', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('add-edit-module-dialog')
        },
        methods: {
            onShow(obj) {
                this.dialog = true
                this.getFiles()
                this.initForm()
                if(obj) {
                    for(let key in obj) {
                        if(obj[key] !== null) {
                            this.form[key] = obj[key]
                        }
                    }
                    if(obj.hasOwnProperty('files')) {
                        if(obj.files.length > 0) {
                            this.form.file_id = obj.files[0].id
                        }
                    }
                }
            },
            initForm() {
                this.form = new Form({
                    id: null,
                    file_id: '',
                    title: '',
                    subtitle: '',
                    text: ''
                })
            },
            onCancel() {
                this.dialog = false
                this.form = new Form({})
            },
            post() {
                this.form.post('/api/modules')
                    .then(response => {
                        this.onCancel()
                        this.$emit('module-posted')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => {
                        this.$refs.moduleForm.validate()
                        console.error(response)
                    })
            },
            put(id) {
                this.form.put('/api/modules/' + id)
                    .then(response => {
                        this.onCancel()
                        this.$emit('module-updated')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => {
                        this.$refs.moduleForm.validate()
                        console.error(response)
                    })
            },
            getFiles() {
                axios.get('/api/files')
                    .then(response => this.files = response.data.data)
                    .catch(response => console.error(response))
            }
        }
    }
</script>
