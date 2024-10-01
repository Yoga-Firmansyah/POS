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
            {
                path: 'categories',
                name: 'categories',
                component: () => import('../views/users/categories/Index.vue'),
                meta: {
                    title: 'Categories Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },
            {
                path: 'categories/add',
                name: 'addCategory',
                component: () => import('../views/users/categories/Create.vue'),
                meta: {
                    title: 'Categories Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },
            {
                path: 'categories/edit/:id',
                name: 'editCategory',
                component: () => import('../views/users/categories/Edit.vue'),
                meta: {
                    title: 'Categories Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },
            {
                path: 'products',
                name: 'products',
                component: () => import('../views/users/products/Index.vue'),
                meta: {
                    title: 'Products Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },
            {
                path: 'products/add',
                name: 'addProduct',
                component: () => import('../views/users/products/Create.vue'),
                meta: {
                    title: 'Products Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },
            {
                path: 'products/edit/:id',
                name: 'editProduct',
                component: () => import('../views/users/products/Edit.vue'),
                meta: {
                    title: 'Products Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },
            {
                path: 'purchases',
                name: 'purchases',
                component: () => import('../views/users/purchases/Index.vue'),
                meta: {
                    title: 'Purchases Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },
            {
                path: 'purchases/add',
                name: 'addPurchase',
                component: () => import('../views/users/purchases/Create.vue'),
                meta: {
                    title: 'Purchases Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },
            {
                path: 'purchases/edit/:id',
                name: 'editPurchase',
                component: () => import('../views/users/purchases/Edit.vue'),
                meta: {
                    title: 'Purchases Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },
            {
                path: 'purchases/:id',
                name: 'detailPurchase',
                component: () => import('../views/users/purchases/Show.vue'),
                meta: {
                    title: 'Purchases Page',
                    requiresAuth: true,
                    requiresRoleAdmin: true,
                }
            },
        ]
    }
]
export default admin;