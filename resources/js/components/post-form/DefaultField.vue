<template>
    <field-wrapper>
        <div class="w-1/5 py-3 px-4" v-if="field.label == null || field.label != false">
            <slot>
                <form-label :for="field.name" :class="{'mb-2': field.helpText && showHelpText }">
                    {{ fieldLabel }}
                </form-label>
            </slot>
        </div>
        <div class="py-3 px-4" :class="fieldClasses">
            <slot name="field"/>

            <help-text class="error-text mt-2 text-danger" v-if="hasError && showErrors">
                {{ firstError }}
            </help-text>

            <help-text class="help-text mt-2" v-if="showHelpText">
                {{ field.helpText }}
            </help-text>
        </div>
    </field-wrapper>
</template>

<script>
import { HandlesValidationErrors, Errors } from 'laravel-nova'

export default {
    mixins: [HandlesValidationErrors],

    props: {
        field: { type: Object, required: true },
        fieldName: { type: String },
        showHelpText: { type: Boolean, default: true },
        showErrors: { type: Boolean, default: true },
        fullWidthContent: { type: Boolean, default: true },
    },

    computed: {
        fieldLabel() {
            // If the field name is purposefully an empty string, then
            // let's show it as such
            if (this.fieldName === '') {
                return ''
            }

            return this.field.label || this.fieldName || this.field.singularLabel || this.field.name
        },

        fieldClasses() {
            return this.fullWidthContent ? ((this.field.label == null || this.field.label != false)? 'w-4/5' : 'w-full') : 'w-4/5'
        },
    },
}
</script>
