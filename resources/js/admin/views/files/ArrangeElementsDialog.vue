<template>
    <v-dialog
            v-model="dialog"
            width="500"
            persistent
    >
        <v-card v-if="form">
            <v-card-title class="headline accent justify-center">
                <span>Rangschikken</span>
                <v-btn text icon absolute right @click="onCancel">
                    <font-awesome-icon class="title" :icon="['far', 'times-circle']"></font-awesome-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <v-list>
                    <draggable v-model="list" @sort="onSort">
                        <transition-group type="transition" name="flip-list">
                            <v-list-item v-for="(item, i) in list" :key="item.id" class="mb-1 border-2" style="cursor: move">
                                <v-list-item-avatar color="grey">
                                    <v-avatar>{{ i+1 }}</v-avatar>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                    <font-awesome-icon :icon="['fas', 'arrows-alt-v']"></font-awesome-icon>
                                </v-list-item-action>
                            </v-list-item>
                        </transition-group>
                    </draggable>
                </v-list>
            </v-card-text>
            <v-card-actions>
                <v-btn color="grey" @click="onCancel" text>Annuleren</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="requestArrange">Opslaan</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import draggable from 'vuedraggable';
    import {Form} from "../../../shared/helpers/Form";

    export default {
        name: 'ArrangeElementsDialog',
        components: {
            draggable
        },
        data() {
            return {
                dialog: false,
                form: null,
                list: [],
                rank: null,
                fileModule: null
            }
        },
        created() {
            eventHub.$on('arrange-elements-dialog', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('arrange-elements-dialog')
        },
        methods: {
            onShow(obj) {
                this.dialog = true
                this.initForm()
                this.fileModule = obj

                if(this.fileModule.ranking) {
                    this.rank = this.fileModule.ranking.ranking.split(",")

                    this.rank.forEach((item, index) => {
                        this.list[index] = _.find(this.fileModule.elements, (item) => {
                            return item.id == this.rank[index]
                        })
                    })
                } else {
                    this.list = this.fileModule.elements
                }
            },
            initForm() {
                this.form = new Form({
                    ranking: []
                })
            },
            onCancel() {
                this.dialog = false
                this.form = new Form({})
            },
            onSort() {
                this.form.ranking = []
                this.list.forEach((item) => {
                    this.form.ranking.push(item.id)
                })
            },
            requestArrange() {
                this.form.put(`/api/modules/ranking/${this.fileModule.id}`)
                    .then(response => {
                        console.log(response)
                        this.onCancel()
                        this.$emit('arrange-elements-updated')
                        eventHub.$emit('show-message', response.status,  response.data)
                    })
                    .catch(response => {
                        console.error(response)
                    })
            }
        }
    }
</script>
<style lang="scss">
    .flip-list-move {
        transition: transform 0.5s;
    }
    .no-move {
        transition: transform 0s;
    }
</style>
