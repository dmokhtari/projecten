<template>
    <v-dialog
        v-model="dialog"
        width="600"
        persistent
    >
        <v-card v-if="form">
            <v-card-title class="headline accent justify-center">
                {{ form.id ? 'Opdracht wijzigen' : 'Opdracht aanmaken' }}
                <v-btn text icon absolute right @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>
            </v-card-title>
            <v-form @submit.prevent="form.id ? put(form.id) : post()"
                    class="px-4 py-4"
                    @keydown.native="form.errors.clear($event.target.name)"
                    ref="elementForm"
            >
                <v-select
                    outlined
                    :items="modules"
                    item-text="title"
                    item-value="id"
                    label="Koppel aan een module"
                    :return-object="false"
                    v-model="form.module_id"
                ></v-select>
                <v-text-field
                    outlined
                    label="Titel*"
                    :rules="[form.errors.get('title')]"
                    :errors="form.errors.has('title')"
                    v-model="form.title"
                ></v-text-field>
                <v-textarea
                    outlined
                    label="Subtitel"
                    :rules="[form.errors.get('subtitle')]"
                    :errors="form.errors.has('subtitle')"
                    v-model="form.subtitle"
                ></v-textarea>
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
        name: 'AddEditElementDialog',
        data() {
            return {
                dialog: false,
                form: null,
                modules: null
            }
        },
        created() {
            eventHub.$on('add-edit-element-dialog', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('add-edit-element-dialog', this.onShow)
        },
        methods: {
            onShow(obj) {
                this.dialog = true
                this.getModules()
                this.initForm()
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
                    module_id: ''
                })
            },
            onCancel() {
                this.dialog = false
                this.form = new Form({})
            },
            post() {
                this.form.post('/api/elements')
                    .then(response => {
                        this.onCancel()
                        this.$emit('element-posted')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => {
                        this.$refs.elementForm.validate()
                        console.error(response)
                        //eventHub.$emit('show-message', response.data.status,  response.data.data)
                    })
            },
            put(id) {
                this.form.put('/api/elements/' + id)
                    .then(response => {
                        this.onCancel()
                        this.$emit('element-updated')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => {
                        this.$refs.elementForm.validate()
                        console.error(response)
                        //eventHub.$emit('show-message', response.data.status,  response.data.data)
                    })
            },
            getModules() {
                axios.get('/api/modules')
                    .then(response => this.modules = response.data.data)
                    .catch(response => console.error(response))
            }
        }
    }
</script>
