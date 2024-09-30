const users = [
    {
        path: '/user',
        component: () => import('../layouts/MainLayout.vue'),
        children: [
            {
                path: 'profile',
                name: 'profile',
                component: () => import('../views/users/Profile.vue'),
                meta: {
                    title: 'User Page',
                    requiresAuth: true
                },
            }
        ]
    }
]

export default users