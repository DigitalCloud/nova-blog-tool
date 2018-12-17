<template>
    <div>
        <card class="mb-6 py-3 px-6">
            <div class="px-4 py-4"  v-if="name">
                <div class="font-bold text-xl mb-2">{{name}}</div>
            </div>
            <slot>
                <component
                    :class="{'remove-bottom-border': index == fields.length - 1}"
                    :key="index"
                    v-for="(field, index) in fields"
                    :is="resolveComponentName(field)"
                    :resource-name="resourceName"
                    :resource-id="resourceId"
                    :field="field"
                    @actionExecuted="actionExecuted"
                />
            </slot>
        </card>
    </div>
</template>

<script>

    export default {

        props: ['name', 'fields', 'resourceName', 'resourceId', ],
        methods: {
            /**
             * Resolve the component name.
             */
            resolveComponentName(field) {
                return field.prefixComponent ? 'form-' + field.component : field.component
            },
        },
    }
</script>
