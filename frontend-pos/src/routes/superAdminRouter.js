const superAdmin = [
    {
        path: '/admin',
        component: () => import('../layouts/MainLayout.vue'),
        children: [
            {
                path: 'users',
                name: 'users',
                component: () => import('../views/users/users/Index.vue'),
                meta: {
                    title: 'User Page',
                    requiresAuth: true,
                    requiresRoleSuperAdmin: true,
                }
            },
            {
                path: 'users/add',
                name: 'addUser',
                component: () => import('../views/users/users/Create.vue'),
                meta: {
                    title: 'User Page',
                    requiresAuth: true,
                    requiresRoleSuperAdmin: true,
                }
            },
            {
                path: 'users/edit/:id',
                name: 'editUser',
                component: () => import('../views/users/users/Edit.vue'),
                meta: {
                    title: 'User Page',
                    requiresAuth: true,
                    requiresRoleSuperAdmin: true,
                }
            },
        ]
    }
]
export default superAdmin;