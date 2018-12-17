<template>

    <loading-view :loading="loading">
        <heading class="mb-3">{{__('New')}} {{ singularName }}</heading>
        <form v-if="fields" @submit.prevent="createResource">
            <!-- Validation Errors -->
            <validation-errors :errors="validationErrors"/>
            <div class="flex">
                <card class="overflow-hidden w-2/3 h-full m-2" style="background-color: #eef1f4;">

                    <div v-for="fieldset in mainFieldSets" v-if="fieldset.name != 'hidden'" :key="fieldset.id" class="rounded bg-white mb-4">
                        <div class="px-4 py-4"  v-if="fieldset.label">
                            <div class="font-bold text-xl mb-2">{{fieldset.label}}</div>
                        </div>
                        <div :class="fieldset.class" :style="fieldset.style">
                            <div v-for="field in fieldset.fields">
                                <component :style='field.type=="hidden"?"display:none;":""'
                                    :is="'form-' + field.component"
                                    :errors="validationErrors"
                                    :resource-name="resourceName"
                                    :field="field"
                                    :via-resource="viaResource"
                                    :via-resource-id="viaResourceId"
                                    :via-relationship="viaRelationship"
                                />
                            </div>
                        </div>
                    </div>
                </card>
                <card class="overflow-hidden w-1/3 h-full m-2"  style="background-color: #eef1f4;">
                    <!-- Publish Card-->
                    <div class="rounded bg-white mb-4 w-full max-w-md">
                        <div class="px-6 py-4">
                            <div class="font-bold text-xl mb-2">Publish</div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <button type="button" dusk="create-button" @click="saveAndContinueEditing" class="ml-auto btn btn-default btn-primary mr-3">{{__('Save Draft')}}</button>
                        </div>
                        <!--<div>-->
                        <!--<p class="text-grey-dark text-xs italic">Status: </p>-->
                        <!--</div>-->
                        <div class="flex flex-wrap -mx-3 mb-6 bg-30 px-8 py-4">
                            <div class="w-1/2">
                            </div>
                            <div class="w-1/2">
                                <!---->
                                <button type="button" dusk="create-button" @click="saveAndPublish" class="ml-auto btn btn-default btn-primary btn-icon">
                                    <icon type="play" class="text-80" /> &nbsp; {{__('Publish')}}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-for="fieldset in sideFieldSets" v-if="fieldset.name != 'hidden'" :key="fieldset.id"  class="rounded bg-white mb-4">
                        <div class="px-6 py-4" v-if="fieldset.label">
                            <div class="font-bold text-xl mb-2">{{fieldset.label}}</div>
                        </div>
                        <div :class="fieldset.class" :style="fieldset.style">
                            <div v-for="field in fieldset.fields">
                                <component :style='field.type=="hidden"?"display:none;":""'
                                    :is="'form-' + field.component"
                                    :errors="validationErrors"
                                    :resource-name="resourceName"
                                    :field="field"
                                    :via-resource="viaResource"
                                    :via-resource-id="viaResourceId"
                                    :via-relationship="viaRelationship"
                                />
                            </div>
                        </div>
                    </div>
                </card>

            </div>
            <!--<card >-->
                <!--<div class="bg-30 flex px-8 py-4">-->
                    <!--<button dusk="create-and-add-another-button" type="button" @click="createAndAddAnother" class="ml-auto btn btn-default btn-primary mr-3">-->
                        <!--{{__('Create &amp; Add Another')}}-->
                    <!--</button>-->

                    <!--<button dusk="create-button" class="btn btn-default btn-primary">-->
                        <!--{{__('Create')}} {{ singularName }}-->
                    <!--</button>-->
                <!--</div>-->
            <!--</card>-->
        </form>
    </loading-view>
</template>

<script>
    import { Errors} from 'laravel-nova'
    import Create from '@nova/views/Create'

    export default {
        mixins: [Create],
        data: () => ({
            publishing: 0
        }),


        computed: {
            /**
             * Get the available field sets.
             */
            mainFieldSets() {
                var fieldSets = [];

                this.fields.forEach(field => {
                    if (field.position == 'main') {
                        fieldSets.push(field)
                    }
                })

                return _.toArray(fieldSets)
            },
            sideFieldSets() {
                var fieldSets = [];

                this.fields.forEach(field => {
                    if (field.position == 'side') {
                        fieldSets.push(field)
                    }
                })

                return _.toArray(fieldSets)
            }
        },

        methods: {
            async saveAndPublish() {
                this.publishing = 1;
                try {
                    const response = await this.createRequest()

                    this.$toasted.show(
                        this.__('The :resource was created!', {
                            resource: this.resourceInformation.singularLabel.toLowerCase(),
                        }),
                        { type: 'success' }
                    )

                    this.$router.push({
                        name: 'edit',
                        params: {
                            resourceName: this.resourceName,
                            resourceId: response.data.id,
                        },
                    })
                } catch (error) {
                    if (error.response.status == 422) {
                        this.validationErrors = new Errors(error.response.data.errors);
                    }
                }
            },

            async saveAndContinueEditing() {
                this.publishing = 0;
                try {
                    const response = await this.createRequest()

                    this.$toasted.show(
                        this.__('The :resource was created!', {
                            resource: this.resourceInformation.singularLabel.toLowerCase(),
                        }),
                        { type: 'success' }
                    )

                    this.$router.push({
                        name: 'edit',
                        params: {
                            resourceName: this.resourceName,
                            resourceId: response.data.id,
                        },
                    })
                } catch (error) {
                    if (error.response.status == 422) {
                        this.validationErrors = new Errors(error.response.data.errors);
                    }
                }
            },

            createResourceFormData() {
                return _.tap(new FormData(), formData => {

                    _.each(this.fields, fieldset => {
                        _.each(fieldset.fields, field => {
                            if(!(field.type && field.type == 'button' || field.type == 'hidden')) {
                                field.fill(formData)
                            }
                        })
                    })
                    formData.set('status', 'draft');
                    formData.set('published', this.publishing);
                    formData.append('viaResource', this.viaResource)
                    formData.append('viaResourceId', this.viaResourceId)
                    formData.append('viaRelationship', this.viaRelationship)
                })
            },
        }

    }
</script>

