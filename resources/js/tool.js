Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'nova-blog-tool',
            path: '/nova-blog-tool',
            component: require('./components/Tool'),
        },
    ])
})
