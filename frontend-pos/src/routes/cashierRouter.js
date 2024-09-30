const cashier = [
    {
        path: '/cashier',
        component: () => import('../layouts/MainLayout.vue'),
        children: [
            {
                path: 'index',
                name: 'cashier-index',
                component: () => import('../views/users/cashier/Index.vue'),
                meta: {
                    title: 'Admin Page',
                    requiresAuth: true,
                    requiresRoleCashier: true,
                }
            },

        ]
    }
]
export default cashier;