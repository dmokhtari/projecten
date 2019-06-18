<template>
    <v-dialog
            v-if="form"
            v-model="dialog"
            width="600"
            persistent
    >
        <v-card>
            <v-card-title class="headline accent justify-center">
                {{ form.id ? 'Lezen wijzigen' : 'Lezen toevoegen' }}
                <v-btn flat icon absolute right @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>
            </v-card-title>
            <v-form @submit.prevent="form.id ? put(form.id) : post()"
                    class="px-4 py-4"
                    @keydown="form.errors.clear($event.target.name)"
                    ref="readForm"
            >
                <v-select
                        outline
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
                <v-textarea
                        outline
                        label="Tekst"
                        :rules="[form.errors.get('description')]"
                        :errors="form.errors.has('description')"
                        v-model="form.description"
                        rows="7"
                ></v-textarea>
                <v-card-actions>
                    <v-btn color="grey" @click="onCancel" flat>Annuleer</v-btn>
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
        name: 'ReadSubElementDialog',
        data() {
            return {
                dialog: false,
                form: null,
                icons: []
            }
        },
        mounted() {
            eventHub.$on('add-edit-subelement-read-dialog', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('add-edit-subelement-read-dialog')
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
                    type: 'read/write',
                    description: '',
                })
            },
            onCancel() {
                this.dialog = false
                this.form = new Form({})
            },
            post() {
                this.form.post('/api/subelements')
                    .then(response => {
                        this.onCancel()
                        this.$emit('read-posted')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => {
                        this.$refs.readForm.validate()
                        console.error(response)
                    })
            },
            put(id) {
                this.form.put('/api/subelements/' + id)
                    .then(response => {
                        this.onCancel()
                        this.$emit('read-updated')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => {
                        this.$refs.readForm.validate()
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
