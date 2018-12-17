<template>
    <div v-if="!loading">
        <heading class="mb-3">{{__('Edit')}} {{ singularName }}</heading>


        <form v-if="fields" @submit.prevent="updateResource">
            <!-- Validation Errors -->
            <validation-errors :errors="validationErrors" />

            <div class="flex">
                <card class="overflow-hidden w-2/3 h-full m-2" style="background-color: #eef1f4;">
                    <div v-for="fieldset in mainFieldSets" v-if="fieldset.name != 'hidden'" :key="fieldset.id" class="rounded bg-white mb-4">
                        <div class="px-6 py-4" v-if="fieldset.label">
                            <div class="font-bold text-xl mb-2">{{fieldset.label}}</div>
                        </div>
                        <div :class="fieldset.class" :style="fieldset.style">
                            <div v-for="(field, fieldName) in fieldset.fields">
                                <component :style='field.type=="hidden"?"display:none;":""'
                                    :is="'form-' + field.component"
                                    @file-deleted="updateLastRetrievedAtTimestamp"
                                    :errors="validationErrors"
                                    :resource-name="resourceName"
                                    :resource-id="resourceId"
                                    :field="field"
                                />
                            </div>
                        </div>
                    </div>
                </card>
                <card class="overflow-hidden w-1/3 h-full m-2"  style="background-color: #eef1f4;">
                    <div class="rounded bg-white mb-4 w-full max-w-md">
                        <div class="px-6 py-4">
                            <div class="font-bold text-xl mb-2">Publish</div>
                        </div>
                        <div class="flex flex-wrap">
                            <div class="w-1/2 ml-3">
                            <button v-if="!published" type="button" dusk="update-and-continue-editing-button" @click="updateAndContinueEditing" class="ml-auto btn btn-default btn-primary mr-3">{{__('Save Draft')}}</button>
                            </div>
                        </div>
                        <div class="flex flex-wrap">
                            <component
                                       :is="'form-' + stt.component"
                                       @file-deleted="updateLastRetrievedAtTimestamp"
                                       :errors="validationErrors"
                                       :resource-name="resourceName"
                                       :resource-id="resourceId"
                                       :field="stt"
                            />
                        </div>
                        <!--<div class="flex flex-wrap">-->
                            <!--<div class="flex border-b border-40" :resourceid="resourceId">-->
                                <!--<div class="w-1/5 py-3 px-4">-->
                                    <!--<label class="inline-block text-80 pt-2 leading-tight">status</label>-->
                                <!--</div>-->
                                <!--<div class="py-3 px-4 w-4/5">-->
                                    <!--<select id="status" class="w-full form-control form-select">-->
                                        <!--<option value="" :selected="status.value == ''" disabled="disabled">Choose an option</option>-->
                                        <!--<option value="draft" :selected="status.value == 'draft'">Draft</option>-->
                                        <!--<option value="published" :selected="status.value == 'published'">Published</option>-->
                                    <!--</select>-->
                                <!--</div>-->
                            <!--</div>-->
                        <!--</div>-->
                        <!--<div>-->
                            <!--<p class="text-grey-dark text-xs italic">Status: </p>-->
                        <!--</div>-->
                        <div class="flex flex-wrap -mx-3 mb-6 bg-30 px-8 py-4">
                            <div class="w-1/2">
                                <!-- delete button -->
                                <button v-if="!resource.softDeleted"
                                    data-testid="open-delete-modal"
                                    dusk="open-delete-modal-button"
                                    @click.prevent="openDeleteModal"
                                    class="btn btn-default btn-icon btn-white mr-3"
                                    title="Delete"
                                >
                                    <icon type="delete" class="text-80" />
                                </button>

                                <portal to="modals">
                                    <transition name="fade">
                                        <delete-resource-modal
                                            v-if="deleteModalOpen"
                                            @confirm="confirmDelete"
                                            @close="closeDeleteModal"
                                            mode="delete"
                                        />
                                    </transition>
                                </portal>

                            </div>
                            <div class="w-1/2">
                                <!---->
                                <button v-if="published" type="button" @click="updateAndContinue" class="ml-auto btn btn-default btn-primary btn-icon">
                                    <icon type="edit" class="text-80" /> &nbsp; {{__('Update')}}
                                </button>
                                <button v-if="!published" type="button" @click="updateAndPublish" class="ml-auto btn btn-default btn-primary btn-primary">
                                    {{'Publish'}}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-for="fieldset in sideFieldSets" v-if="fieldset.name != 'hidden'" :key="fieldset.id"  class="rounded bg-white mb-4">
                        <div class="px-6 py-4">
                            <div class="font-bold text-xl mb-2">{{fieldset.name}}</div>
                        </div>
                        <div :class="fieldset.class" :style="fieldset.style">
                            <div v-for="field in fieldset.fields">
                                <component :style='field.type=="hidden"?"display:none;":""'
                                    :is="'form-' + field.component"
                                    @file-deleted="updateLastRetrievedAtTimestamp"
                                    :errors="validationErrors"
                                    :resource-name="resourceName"
                                    :resource-id="resourceId"
                                    :field="field"
                                />
                            </div>
                        </div>
                    </div>
                </card>

            </div>





            <!-- Fields -->
            <div v-for="field in fields">
                <component
                    @file-deleted="updateLastRetrievedAtTimestamp"
                    :is="'form-' + field.component"
                    :errors="validationErrors"
                    :resource-id="resourceId"
                    :resource-name="resourceName"
                    :field="field"
                />
            </div>

            <!-- Update Button -->
            <!--<div class="bg-30 flex px-8 py-4">-->
                <!--<button type="button" dusk="update-and-continue-editing-button" @click="updateAndContinueEditing" class="ml-auto btn btn-default btn-primary mr-3">-->
                    <!--{{__('Update &amp; Continue Editing')}}-->
                <!--</button>-->

                <!--<button dusk="update-button" class="btn btn-default btn-primary">-->
                    <!--{{__('Update')}} {{ singularName }}-->
                <!--</button>-->
            <!--</div>-->
        </form>

    </div>
</template>

<script>
    import Update from '@nova/views/Update';

    import { Deletable, Minimum } from 'laravel-nova';

    export default {
        mixins: [Update, Deletable],

        data: () => ({
            resource: null,
            deleteModalOpen: false,
            published: false,
            status: {},
            publishing: 0
        }),

        watch: {
            fields: {
                immediate: true,
                handler: function (fields) {
                    if (fields) {
                        this.$nextTick(()=> {
                            _.each(this.fields, fieldset => {
                                _.each(fieldset.fields, field => {
                                    if(field.attribute  == 'published') {
                                        this.published = (field.value)? true : false;
                                    }
                                    if(field.attribute  == 'status') {
                                        this.status = field;
                                    }
                                })
                            })
                        });
                    }
                    //console.log(this.statusField);
                }
            }
        },

        computed: {

            stt() {
                return this.fields[0].fields.status
            },

            newStatus() {
                return this.status
            },
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
            },

            updateResourceFormData() {
                return _.tap(new FormData(), formData => {
                    _.each(this.fields, fieldset => {
                        _.each(fieldset.fields, field => {
                            if(!(field.type && (field.type == 'button' || field.type == 'hidden'))) {
                                field.fill(formData)
                            }
                        })
                    })
                    formData.set('status', this.publishing? 'published' : 'draft');
                    formData.set('published', this.publishing);
                    formData.append('_method', 'PUT')
                    formData.append('_retrieved_at', this.lastRetrievedAt)
                })
            },
        },
        methods: {
            openDeleteModal() {
                this.deleteModalOpen = true
            },

            closeDeleteModal() {
                this.deleteModalOpen = false
            },

            getResource() {
                this.resource = null

                return Minimum(
                    Nova.request().get('/nova-api/' + this.resourceName + '/' + this.resourceId)
                )
                    .then(({ data: { panels, resource } }) => {
                        this.resource = resource
                    })
                    .catch(error => {
                        if (error.response.status >= 500) {
                            Nova.$emit('error', error.response.data.message)
                            return
                        }

                        if (error.response.status === 404 && this.initialLoading) {
                            this.$router.push({ name: '404' })
                            return
                        }

                        if (error.response.status === 403) {
                            this.$router.push({ name: '403' })
                            return
                        }

                        this.$toasted.show(this.__('This resource no longer exists'), { type: 'error' })

                        this.$router.push({
                            name: 'index',
                            params: { resourceName: this.resourceName },
                        })
                    })
            },

            async confirmDelete() {
                this.deleteResources([this.resource], () => {
                    this.$toasted.show(
                        this.__('The :resource was deleted!', {
                            resource: this.resourceInformation.singularLabel.toLowerCase(),
                        }),
                        { type: 'success' }
                    )

                    this.$router.push({
                        name: 'index',
                        params: { resourceName: this.resourceName },
                    })
                    return
                })
            },

            async updateAndPublish() {
                this.publishing = 1;
                this.updateAndContinueEditing();
            },
            async updateAndContinue() {
                this.publishing = 0;
                this.updateAndContinueEditing();
            },
        },
        async created() {

            this.getResource();
        }
    }
</script>
