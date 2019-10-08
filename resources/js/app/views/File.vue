<template>
    <v-container>
        <v-layout>
            <v-flex xs12>
                <v-card v-if="file">
                    <v-toolbar :class="file.background_color">
                        <font-awesome-icon :icon="['fas', 'file']" class="title"></font-awesome-icon>

                        <v-toolbar-title>{{ file.title }}</v-toolbar-title>

                        <v-spacer></v-spacer>

                        <v-btn icon>
                            <v-icon>search</v-icon>
                        </v-btn>
                    </v-toolbar>

                    <v-list two-line>
                        <v-subheader>Modules</v-subheader>
                        <template v-for="(mod, index) in file.modules">

                            <v-list-item
                                :key="index"
                                ripple
                                @click="goToModule(mod.id)"
                            >
                                <v-list-item-avatar color="grey">
                                    <v-avatar>{{ index+1 }}</v-avatar>
                                </v-list-item-avatar>

                                <v-list-item-content>
                                    <v-list-item-title>{{ mod.title }}</v-list-item-title>
                                    <v-list-item-subtitle>{{ mod.subtitle }} &mdash; {{ mod.text }}</v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                            <v-divider
                                v-if="mod.length > 1"
                                :key="index"
                            ></v-divider>
                        </template>
                    </v-list>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
    export default {
        name: 'File',
        title: 'File',
        data() {
            return {
                file: null,
            }
        },
        created() {
            this.get()
        },
        methods: {
            get() {
                axios.get(`/api/files/${this.$route.params.id}`)
                    .then(response => this.file = response.data.data)
                    .catch(response => console.errors(response))
            },
            goToModule(id) {
                this.$router.push({ name: 'studentModule', params: { fileId: this.$route.params.id, moduleId: id } })
            }
        }
    }
</script>
