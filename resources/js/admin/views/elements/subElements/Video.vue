<template>
    <v-dialog
        v-if="form"
        v-model="dialog"
        width="500"
        persistent
    >
        <v-card>
            <v-card-title class="headline accent justify-center">
                {{ form.id ? 'Video wijzigen' : 'Video toevoegen' }}
                <v-btn flat icon absolute right @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>
            </v-card-title>
            <v-form @submit.prevent="form.id ? put(form.id) : post()"
                    class="px-4 py-4"
                    @keydown="form.errors.clear($event.target.name)"
                    ref="videoForm"
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
                <v-text-field
                    outline
                    type="text"
                    label="Url"
                    :rules="[form.errors.get('url')]"
                    :errors="form.errors.has('url')"
                    v-model="form.url"
                ></v-text-field>
                <v-textarea
                    outline
                    label="Text"
                    :rules="[form.errors.get('description')]"
                    :errors="form.errors.has('description')"
                    v-model="form.description"
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
        name: 'VideoSubElementDialog',
        data() {
            return {
                dialog: false,
                form: null,
                icons: []
            }
        },
        created() {
            eventHub.$on('add-edit-subelement-video-dialog', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('add-edit-subelement-video-dialog')
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
                    type: 'video',
                    url: '',
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
                        this.$emit('video-posted')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => {
                        this.$refs.videoForm.validate()
                        console.error(response)
                        //eventHub.$emit('show-message', response.data.status,  response.data.data)
                    })
            },
            put(id) {
                this.form.put('/api/subelements/' + id)
                    .then(response => {
                        this.onCancel()
                        this.$emit('video-updated')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => {
                        this.$refs.videoForm.validate()
                        console.error(response)
                        //eventHub.$emit('show-message', response.data.status,  response.data.data)
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
