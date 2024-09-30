const admin = [
    {
        path: '/admin',
        component: () => import('../layouts/MainLayout.vue'),
        children: [
            {
                path: 'index',
                name: 'admin-index',
                component: () => import('../views/users/admin/Index.vue'),
                meta: {
                    title: 'Admin Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },

        ]
    }
]
export default admin;