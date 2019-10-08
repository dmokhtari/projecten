<template>
    <v-dialog
        v-model="dialog"
        width="400"
        persistent
    >
        <v-card v-if="form">
            <v-card-title class="headline accent justify-center">
                {{ form.id ? 'Icon wijzigen' : 'Icon toevoegen' }}
                <v-btn text icon absolute right @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>
            </v-card-title>
            <form @submit.prevent="form.id ? put(form.id) : post()" class="px-4 py-4" @keydown="form.errors.clear($event.target.name)">
                <v-text-field
                    outlined
                    label="Titel*"
                    :rules="[form.errors.get('title')]"
                    :errors="form.errors.has('title')"
                    v-model="form.title"
                    autofocus
                ></v-text-field>
                <v-text-field
                    outlined
                    label="Upload een image(icon)*"
                    :rules="[form.errors.get('icon')]"
                    :errors="form.errors.has('icon')"
                    v-model="form.icon_name"
                    @click="onClickImageInput"
                ></v-text-field>
                <input type="file" ref="imageInput" style="display:none" @change="onImageChange">

                <v-card-actions>
                    <v-btn color="grey" @click="onCancel" text>Annuleer</v-btn>
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
        name: 'AddEditIconDialog',
        data() {
            return {
                dialog: false,
                form: null,
            }
        },
        created() {
            eventHub.$on('add-edit-icon-dialog', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('add-edit-icon-dialog')
        },
        methods: {
            onShow(obj) {
                this.dialog = true
                this.initForm()
                if(obj) {
                    for(let key in obj) {
                        if(obj[key] !== null) {
                            this.form[key] = obj[key]
                        }
                    }
                    this.form.icon_name = obj.name
                }
            },
            initForm() {
                this.form = new Form({
                    id: null,
                    icon: '',
                    title: '',
                    icon_name: '',
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
                this.form.icon = image
                this.form.icon_name = image.name
            },
            post() {
                this.form.post('/api/settings/icons')
                    .then(response => {
                        this.onCancel()
                        this.$emit('posted')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => console.error(response))
            },
            put(id) {
                this.form.put('/api/settings/icons/' + id)
                    .then(response => {
                        this.onCancel()
                        this.$emit('update')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => console.error(response))
            }
        }
    }
</script>
