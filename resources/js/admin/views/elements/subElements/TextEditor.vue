<template>
    <v-dialog
        v-if="form"
        v-model="dialog"
        width="900"
        persistent
    >
        <v-card>
            <v-card-title class="headline accent justify-center">
                {{ form.id ? 'Gebruiker wijzigen' : 'Gebruiker toevoegen' }}
                <v-btn flat icon absolute right @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>
            </v-card-title>
            <form @submit.prevent="form.id ? put(form.id) : post()" class="px-4 py-4" @keydown="form.errors.clear($event.target.name)">
                <textarea ref="textEditor" v-model="form.binary"></textarea>
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
    import { Form } from "../../../../shared/helpers/Form";
    import svgIcons from 'trumbowyg/dist/ui/icons.svg';
    import trumbowyg from 'trumbowyg';
    export default {
        name: 'TextEditorSubElementDialog',
        data() {
            return {
                dialog: false,
                form: null,
            }
        },
        mounted() {
            eventHub.$on('add-edit-subelement-text-editor-dialog', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('add-edit-subelement-text-editor-dialog')
        },
        methods: {
            onShow(objOrId) {
                setTimeout(() => this.initTextEditor(), 100)
                this.initForm()
                this.dialog = true
                if(objOrId) {
                    if(typeof objOrId === 'number') {
                        this.form.element_id = objOrId
                    } else {
                        for(let key in objOrId) {
                            if(objOrId[key] !== null) {
                                this.form[key] = objOrId[key]
                            }
                        }
                    }
                }
            },
            initForm() {
                this.form = new Form({
                    id: null,
                    element_id: null,
                    type: 'binary',
                    binary: '',
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
                    this.form.binary = e.target.value
                })
            },
            onCancel() {
                $(this.$refs.textEditor).trumbowyg('destroy')
                this.dialog = false
                this.form = new Form({})
            },
            post() {
                console.log('posting')
                this.form.post('/api/subelements')
                    .then(response => {
                        this.onCancel()
                        this.$emit('posted')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => console.log(response))
                    // .catch(response => eventHub.$emit('show-message', response.data.status,  response.data.data))
            },
            put(id) {
                this.form.put('/api/subelements/' + id)
                    .then(response => {
                        this.onCancel()
                        this.$emit('updated')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => eventHub.$emit('show-message', response.data.status,  response.data.data))
            },
        }
    }
</script>
