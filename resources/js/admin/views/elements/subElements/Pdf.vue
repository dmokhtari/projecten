<template>
    <v-dialog
            v-if="form"
            v-model="dialog"
            width="500"
            persistent
    >
        <v-card>
            <v-card-title class="headline accent justify-center">
                {{ form.id ? 'Bestand wijzigen' : 'Bestand toevoegen' }}
                <v-btn text icon absolute right @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>
            </v-card-title>
            <v-form @submit.prevent="form.id ? put(form.id) : post()"
                    class="px-4 py-4"
                    @keydown="form.errors.clear($event.target.name)"
                    ref="pdfForm"
            >
                <v-select
                        outlined
                        label="Icon"
                        :items="icons"
                        item-value="id"
                        item-text="title"
                        multiple
                        chip
                        v-model="form.icon_id"
                        :return-object="false"
                >
                    <template v-slot:selection="data">
                        <v-chip
                                close
                                :key="JSON.stringify(data.item)"
                                :input-value="data.selected"
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
                <v-textarea
                        outlined
                        label="Tekst"
                        :rules="[form.errors.get('description')]"
                        :errors="form.errors.has('description')"
                        v-model="form.description"
                ></v-textarea>
                <v-text-field
                        outlined
                        type="text"
                        label="Upload een pdf of foto"
                        prepend-inner-icon="file_upload"
                        :rules="[form.errors.get('excel_file')]"
                        :errors="form.errors.has('excel_file')"
                        v-model="form.excel_file_name"
                        @click="onClickFileInput"
                ></v-text-field>
                <input type="file" ref="file" style="display:none" @change="onFileChange">
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
    import { Form } from "../../../../shared/helpers/Form";

    export default {
        name: 'PdfSubElementDialog',
        data() {
            return {
                dialog: false,
                form: null,
                icons: []
            }
        },
        mounted() {
            eventHub.$on('add-edit-subelement-pdf-dialog', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('add-edit-subelement-pdf-dialog')
        },
        methods: {
            onShow(objOrId) {
                this.dialog = true
                this.getIcons()
                this.initForm()
                console.log(objOrId)
                if(objOrId) {
                    if(typeof objOrId === 'number') {
                        this.form.element_id = objOrId
                    } else {
                        for(let key in objOrId) {
                            if(objOrId[key] !== null) {
                                this.form[key] = objOrId[key]
                            }
                        }
                        if(objOrId.icons) {
                            objOrId.icons.forEach(icon => {
                                this.form.icon_id.push(icon.id)
                            })
                        }
                    }
                }
            },
            initForm() {
                this.form = new Form({
                    id: null,
                    element_id: null,
                    icon_id: [],
                    type: 'file',
                    pdf_file: '',
                    pdf_file_name: '',
                    description: '',
                })
            },
            onCancel() {
                this.dialog = false
                this.form = new Form({})
            },
            onClickFileInput() {
                this.$refs.file.click()
            },
            onFileChange(e) {
                let file = e.target.files[0]
                this.form.pdf_file = file
                this.form.pdf_file_name = file.name
            },
            post() {
                this.form.post('/api/subelements')
                    .then(response => {
                        this.onCancel()
                        this.$emit('pdf-posted')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => {
                        this.$refs.pdfForm.validate()
                        console.error(response)
                    })
            },
            put(id) {
                this.form.put('/api/subelements/' + id)
                    .then(response => {
                        this.onCancel()
                        this.$emit('pdf-updated')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => {
                        this.$refs.pdfForm.validate()
                        console.error(response)
                    })
            },
            getIcons() {
                axios.get('/api/settings/icons')
                    .then(response => this.icons = response.data.data)
                    .catch(response => console.error(response))
            }
        }
    }
</script>
