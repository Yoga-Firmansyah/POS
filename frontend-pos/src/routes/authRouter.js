const auth = [
    {
        path: '/auth',
        children: [
            {
                path: 'login',
                name: 'login',
                component: () => import('../views/users/auth/Login.vue'),
                meta: {
                    title: 'Login Page',
                    canntoAccessAfterLogin: true
                }
            },

        ]
    }
]
export default auth;