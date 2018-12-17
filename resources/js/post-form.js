Nova.booting((Vue, router) => {
    Vue.component('post-form-create', require('./components/post-form/Create'))
    Vue.component('post-form-edit', require('./components/post-form/Edit'))
    Vue.component('default-field', require('./components/post-form/DefaultField'))
    Vue.component('field-set', require('./components/post-form/FieldSet'))
})
