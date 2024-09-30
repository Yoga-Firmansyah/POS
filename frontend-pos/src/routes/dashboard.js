const dashboard = [
    {
        path: '/',
        component: () => import('../layouts/MainLayout.vue'),
        children: [
            {
                path: '',
                name: 'home',
                component: () => import('../views/users/Home.vue'),
                meta: {
                    title: 'Dashboard',
                    requiresAuth: true
                },
            },
        ]
    }
]

export default dashboard