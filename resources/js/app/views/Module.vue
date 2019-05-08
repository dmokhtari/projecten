<template>
    <v-container>
        <v-layout row wrap v-if="fileModule">
            <v-flex xs12 class="text-xs-center" style="margin-bottom: 100px;">
                <div class="element-btn">
                    <v-btn v-if="fileModule.text" :color="isActive(0)" @click="getModuleIntro">Intro</v-btn>
                    <v-btn v-for="(mod, index) in fileModule.elements"
                           :key="index"
                           :color="isActive(mod.id)"
                           @click="getElement(mod.id)"
                    >{{ index+1 }}</v-btn>
                </div>
            </v-flex>
            <v-flex xs12 v-if="!element">
                <p class="title">{{ fileModule.text }}</p>
            </v-flex>
        </v-layout>

        <element-component :obj-element="element"></element-component>

    </v-container>
</template>
<script>
    import ElementComponent from './../components/ElementComponent';
    export default {
        name: 'Module',
        components: {
            ElementComponent
        },
        data() {
            return {
                fileModule: null,
                element: null,
                activeElement: 0
            }
        },
        computed: {
            isActive(id) {
                return (id) => {
                    if(id === this.activeElement) {
                        return 'primary'
                    }
                }
            }
        },
        created() {
            this.getModule()
            this.hideSidebar()
        },
        methods: {
            getModule() {
                axios.get(`/api/modules/${this.$route.params.moduleId}`)
                    .then(response => {
                        this.fileModule = response.data.data
                        // this.getElement(this.fileModule.elements[0].id)
                    })
                    .catch(response => console.error(response))
            },
            getElement(id) {
                if(id === this.activeElement) {
                    return
                }
                this.activeElement = id
                axios.get('/api/elements/' + id)
                    .then(response => this.element = response.data.data)
                    .catch(response => console.error(response))
            },
            getModuleIntro() {
                this.element = null
                this.activeElement = 0
            },
            hideSidebar() {
                eventHub.$emit('toggle-sidebar')
            }
        }
    }
</script>
<style lang="scss" scoped>
    .element-btn {
        .v-btn {
            min-width: 60px;
        }
    }
</style>
