<template>
    <v-dialog
            v-model="dialog"
            persistent
            fullscreen hide-overlay transition="dialog-bottom-transition"
    >
        <v-card v-if="form">
            <v-card-title class="headline accent justify-center">
                <v-btn text icon absolute left @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>

                {{ form.id ? 'Documentatie wijzigen' : 'Documentatie toevoegen' }}

                <v-btn color="primary" absolute right @click="form.id ? put(form.id) : post()">Opslaan</v-btn>
            </v-card-title>
            <v-form @submit.prevent="form.id ? put(form.id) : post()"
                    class="px-4 py-4"
                    @keydown="form.errors.clear($event.target.name)"
                    ref="textForm"
            >
                <v-layout row justify-center>
                    <v-flex xs12 md6>
                        <v-text-field
                                outlined
                                label="Titel"
                                :rules="[form.errors.get('title')]"
                                :errors="form.errors.has('title')"
                                v-model="form.title"
                        ></v-text-field>
                    </v-flex>
                </v-layout>
                <textarea ref="textEditor" v-model="form.text" rows="15"></textarea>
            </v-form>
        </v-card>
    </v-dialog>
</template>
<script>
    import { Form } from "./../../../shared/helpers/Form";
    import svgIcons from 'trumbowyg/dist/ui/icons.svg';
    import trumbowyg from 'trumbowyg';
    export default {
        name: 'AddEditDocumentationDialog',
        data() {
            return {
                dialog: false,
                form: null,
            }
        },
        mounted() {
            eventHub.$on('add-edit-documentation-dialog', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('add-edit-documentation-dialog')
        },
        methods: {
            onShow(obj) {
                setTimeout(() => this.initTextEditor(), 100)
                this.initForm()
                this.dialog = true
                if (obj) {
                    for (let key in obj) {
                        if (obj[key] !== null) {
                            this.form[key] = obj[key]
                        }
                    }
                }
            },
            initForm() {
                this.form = new Form({
                    id: null,
                    title: '',
                    text: '',
                })
            },
            initTextEditor() {
                $(this.$refs.textEditor).trumbowyg({
                    autogrow: true,
                    svgPath: '/images/vendor/trumbowyg/dist/ui/icons.svg',
                    btns: [
                        // ['viewHTML'],
                        ['undo', 'redo'], // Only supported in Blink browsers
                        ['formatting'],
                        ['strong', 'em', 'del'],
//                    ['superscript', 'subscript'],
                        ['link'],
                        ['insertImage'],
                        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                        ['unorderedList', 'orderedList'],
//                    ['horizontalRule'],
                        ['removeformat'],
                        ['fullscreen']
                    ]

                }).on('tbwchange', (e) => {
                    this.form.text = e.target.value
                })
            },
            onCancel() {
                $(this.$refs.textEditor).trumbowyg('destroy')
                this.dialog = false
                this.form = new Form({})
            },
            post() {
                this.form.post('/api/settings/documentations')
                    .then(response => {
                        this.onCancel()
                        this.$emit('documentation-posted')
                        eventHub.$emit('show-message', response.status, response.data)
                    })
                    .catch(response => {
                        this.$refs.textForm.validate()
                        console.error(response)
                    })
            },
            put(id) {
                this.form.put(`/api/settings/documentations/${id}`)
                    .then(response => {
                        this.onCancel()
                        this.$emit('documentation-updated')
                        eventHub.$emit('show-message', response.status, response.data)
                    })
                    .catch(response => {
                        this.$refs.textForm.validate()
                        console.error(response)
                    })
            }
        }
    }
</script>
<style lang="scss">
    .trumbowyg-box .trumbowyg-editor {
        min-height: 60vh;
    }
</style>
