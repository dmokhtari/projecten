<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent max-width="320">
            <v-card>
                <v-card-title class="error title white--text">Verwijderen Bevestigen</v-card-title>
                <v-card-text>
                    <slot></slot>
                </v-card-text>
                <v-card-actions>
                    <v-btn text @click="dialog = false">Annuleer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="error" text @click="confirm">Ja, verwijder</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>
<script>
    export default {
        name: 'DeletePermanent',
        props: {
            strTitle: { required: false }
        },
        data() {
            return {
                dialog: false,
            }
        },
        created() {
            eventHub.$on('show-delete-permanent', this.onShow)
        },
        beforeDestroy() {
            eventHub.$off('show-delete-permanent', this.onShow)
        },
        methods: {
            onShow() {
                this.dialog = true
            },
            confirm() {
                this.$emit('confirmed')
                this.dialog = false
            }
        }
    }
</script>
