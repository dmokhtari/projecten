<template>
    <v-container>
        <v-layout row wrap v-if="fileModule">
            <v-flex xs12 class="text-xs-center">
                <div class="btn-group">
                    <v-btn v-for="(mod, index) in fileModule.elements.length" :key="index" outline>{{ index+1 }}</v-btn>
                </div>
            </v-flex>
            <v-flex xs12 class="mt-5">
                <p>{{ fileModule.text }}</p>
            </v-flex>
        </v-layout>


        <template v-if="element">
            <v-layout row wrap v-for="(sub, index) in element.subelements" :key="index">
                <v-flex xs12>
                    <v-img v-if="sub.icons.length" :src="'/storage/' + sub.icons[0].src" width="80"></v-img>
                </v-flex>
                <template v-if="sub.type === 'video'">
                    <v-flex xs12>
                        <v-btn>Video bekijken</v-btn>
                    </v-flex>
                </template>
                <template v-if="sub.type === 'binary'">
                    <v-flex xs12 v-html="sub.binary"></v-flex>
                </template>
            </v-layout>
        </template>
    </v-container>
</template>
<script>
    export default {
        name: 'Module',
        data() {
            return {
                fileModule: null,
                element: null
            }
        },
        created() {
            this.getModule()
            this.hideSidebar()
        },
        methods: {
            getModule() {
                axios.get(`/api/modules/${this.$route.params.id}`)
                    .then(response => {
                        this.fileModule = response.data.data
                        this.getElement(this.fileModule.elements[0].id)
                    })
                    .catch(response => console.error(response))
            },
            getElement(id) {
                axios.get('/api/elements/' + id)
                    .then(response => this.element = response.data.data)
                    .catch(response => console.error(response))
            },
            hideSidebar() {
                eventHub.$emit('toggle-sidebar')
            }
        }
    }
</script>
<style lang="scss" scoped>
    .btn-group {
        .v-btn {
            min-width: 70px;
        }
    }
</style>
