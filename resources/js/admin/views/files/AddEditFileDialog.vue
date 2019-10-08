<template>
    <v-dialog
        v-model="dialog"
        width="400"
        persistent
    >
        <v-card v-if="form">
            <v-card-title class="headline accent justify-center">
                {{ form.id ? 'Bestand wijzigen' : 'Bestand aanmaken' }}
                <v-btn text icon absolute right @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>
            </v-card-title>
            <v-form @submit.prevent="form.id ? put(form.id) : post()"
                    class="px-4 py-4"
                    @keydown.native="form.errors.clear($event.target.name)"
                    ref="fileForm"
            >
                <v-text-field
                    label="Titles"
                    :rules="[form.errors.get('title')]"
                    :errors="form.errors.has('title')"
                    v-model="form.title"
                    outlined
                ></v-text-field>
                <v-text-field
                    outlined
                    label="Subtitle"
                    :rules="[form.errors.get('subtitle')]"
                    :errors="form.errors.has('subtitle')"
                    v-model="form.subtitle"
                ></v-text-field>

                <v-select
                    label="Kies een achtergrond kleur"
                    outlined
                    v-model="form.background_color"
                    :items="colors"
                    item-text="name"
                    item-value="value"
                    single-line
                    clearable
                >
                    <template slot="selection" slot-scope="data">
                        <v-avatar :class="data.item.value" class="mr-3" size="35"></v-avatar>
                        <div>{{ data.item.name }}</div>
                    </template>
                    <template slot="item" slot-scope="data">
                        <v-list-item-avatar :class="data.item.value" class="mr-3"></v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title>{{ data.item.name }}</v-list-item-title>
                        </v-list-item-content>
                    </template>
                </v-select>

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
        name: 'AddEditFileDialog',
        data() {
            return {
                dialog: false,
                form: null,
                colors: [
                    { name: 'rood', value: 'linear-red' },
                    { name: 'blauw', value: 'linear-blue' },
                    { name: 'groen', value: 'linear-green' },
                    { name: 'geel', value: 'linear-yellow' },
                    { name: 'paars', value: 'linear-purple' },
                ],
            }
        },
        mounted() {
            eventHub.$on('add-edit-file-dialog', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('add-edit-file-dialog')
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
                    background_color: 'linear-red',
                })
            },
            onCancel() {
                this.dialog = false
                this.form = new Form({})
            },
            post() {
                this.form.post('/api/files')
                    .then(response => {
                        this.onCancel()
                        this.$emit('file-posted')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(error => {
                        this.$refs.fileForm.validate()
                        console.error(error)
                        //eventHub.$emit('show-message', response.data.status,  response.data.data)
                    })
            },
            put(id) {
                this.form.put('/api/files/' + id)
                    .then(response => {
                        this.onCancel()
                        this.$emit('file-updated')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(error => {
                        this.$refs.fileForm.validate()
                        console.error(error)
                        //eventHub.$emit('show-message', response.data.status,  response.data.data)
                    })
            },
        }
    }
</script>

