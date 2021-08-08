Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'microservice-monitor',
            path: '/microservice-monitor',
            component: require('./components/Tool'),
        },
    ])
})
