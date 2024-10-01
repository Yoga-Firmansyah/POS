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
            {
                path: 'sales',
                name: 'sales',
                component: () => import('../views/users/sales/Index.vue'),
                meta: {
                    title: 'Sales Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },
            {
                path: 'sales/:id',
                name: 'detailSale',
                component: () => import('../views/users/sales/Show.vue'),
                meta: {
                    title: 'Sales Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },
            {
                path: 'sales/add',
                name: 'addSale',
                component: () => import('../views/users/sales/Create.vue'),
                meta: {
                    title: 'Sales Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },
        ]
    }
]

export default dashboard