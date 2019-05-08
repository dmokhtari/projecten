<template>
    <v-dialog
        v-if="form"
        v-model="dialog"
        persistent
        fullscreen hide-overlay transition="dialog-bottom-transition"
    >
        <v-card>
            <v-card-title class="headline accent justify-center">
                <v-btn flat icon absolute left @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>

                {{ form.id ? 'Text wijzigen' : 'Text toevoegen' }}

                <v-btn color="primary" absolute right @click="form.id ? put(form.id) : post()">Opslaan</v-btn>
            </v-card-title>
            <form @submit.prevent="form.id ? put(form.id) : post()"
                  class="px-4 py-4"
                  @keydown="form.errors.clear($event.target.name)"
            >
                <v-layout row wrap>
                    <v-flex sm6 xs12 class="pr-3">
                        <v-text-field
                            outline
                            label="Title"
                            :rules="[form.errors.get('title')]"
                            :errors="form.errors.has('title')"
                            v-model="form.title"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6>
                        <v-select
                            outline
                            label="Icon"
                            :items="icons"
                            item-value="id"
                            item-text="title"
                            multiple
                            chip
                            :rules="[form.errors.get('icon_id')]"
                            :errors="form.errors.has('icon_id')"
                            v-model="form.icon_id"
                            :return-object="false"
                        >
                            <template v-slot:selection="data">
                                <v-chip
                                    close
                                    :key="JSON.stringify(data.item)"
                                    :selected="data.selected"
                                    :disabled="data.disabled"
                                    class="v-chip--select-multi"
                                    @input="data.parent.selectItem(data.item)"
                                >
                                    <v-avatar
                                        class="accent white--text"
                                    >
                                        <img :src="'/storage/' + data.item.src" :alt="data.item.title">
                                    </v-avatar>
                                    {{ data.item.title }}
                                </v-chip>
                            </template>
                        </v-select>
                    </v-flex>
                </v-layout>
                <textarea ref="textEditor" v-model="form.binary" rows="15"></textarea>
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
                icons: []
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
                this.getIcons()
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
                    icon_id: null,
                    title: '',
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
                this.form.post('/api/subelements')
                    .then(response => {
                        this.onCancel()
                        this.$emit('text-posted')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => console.log(response))
            },
            put(id) {
                this.form.put('/api/subelements/' + id)
                    .then(response => {
                        this.onCancel()
                        this.$emit('text-updated')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => console.error(response))
            },
            getIcons() {
                axios.get('/api/settings/icons')
                    .then(response => this.icons = response.data.data)
                    .catch(response => console.error(response))
            }
        }
    }
</script>
<style scoped lang="scss">
    .trumbowyg .trumbowyg-editor {
        min-height: 400px;
    }
</style>
