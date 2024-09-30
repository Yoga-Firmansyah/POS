const error = [
    {
        path: '/',
        component: () => import('../layouts/MainLayout.vue'),
        children: [
            {
                path: '404',
                name: '404',
                component: () => import('../views/users/404.vue'),
                meta: {
                    title: '404',
                    requiresAuth: true
                },
            },
        ]
    }
]

export default error