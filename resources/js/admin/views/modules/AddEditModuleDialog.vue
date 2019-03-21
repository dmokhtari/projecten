<template>
    <v-dialog
        v-if="form"
        v-model="dialog"
        width="400"
        persistent
    >
        <v-card>
            <v-card-title class="headline accent justify-center">
                {{ form.id ? 'Module wijzigen' : 'Module toevoegen' }}
                <v-btn flat icon absolute right @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>
            </v-card-title>
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
</template>
<script>
    import { Form } from "../../../shared/helpers/Form";
    export default {
        name: 'AddEditModuleDialog',
        data() {
            return {
                dialog: false,
                form: null,
            }
        },
        created() {
            eventHub.$on('add-edit-module-dialog', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('add-edit-module-dialog')
        },
        methods: {
            onShow(obj) {
                this.initForm()
                this.dialog = true
                if(obj) {
                    for(let key in obj) {
                        if(obj[key] !== null) {
                            this.form[key] = obj[key]
                        }
                    }
                }
            },
            initForm() {
                this.form = new Form({
                    id: null,
                    title: '',
                    subtitle: '',
                    background_image: '',
                    background_image_name: '',
                    background_color: '',
                })
            },
            onCancel() {
                this.dialog = false
                this.form = new Form({})
            },
            onClickImageInput() {
                this.$refs.imageInput.click()
            },
            onImageChange(e) {
                let image = e.target.files[0]
                this.form.background_image = image
                this.form.background_image_name = image.name
            },
            post() {
                this.form.post('/api/modules')
                    .then(response => {
                        this.onCancel()
                        this.$emit('posted')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => eventHub.$emit('show-message', response.data.status,  response.data.data))
            },
            put(id) {
                this.form.put('/api/modules/' + id)
                    .then(response => {
                        this.onCancel()
                        this.$emit('update')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => eventHub.$emit('show-message', response.data.status,  response.data.data))
            },
        }
    }
</script>
