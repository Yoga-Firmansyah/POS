import { createRouter, createWebHistory } from "vue-router";
import auth from "./authRouter";
import dashboard from "./dashboard"
import users from "./users";
import admin from "./adminRouter";
import superAdmin from "./superAdminRouter";
import cashier from "./cashierRouter";
import error from "./errorRouter";
import { useAuthStore } from '../stores/auth'
import axios from '../plugins/axios'

const routes = [...auth, ...dashboard, ...users, ...admin, ...cashier, ...error, ...superAdmin]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title + " - Point Of Sales";

    const userAuth = useAuthStore();

    axios.get('/user');

    if (to.meta.canntoAccessAfterLogin) {
        if (userAuth.token) {
            next('/')
        }
    }

    if (to.meta.requiresAuth) {
        if (!userAuth.token) {
            console.log(userAuth.token)
            next('/auth/login')
        }

    }

    if (to.meta.requiresRoleAdmin) {
        if (userAuth.role == 'Super Admin' || userAuth.role == 'Admin') {
            next()
        } else {
            next('/404')
        }
    }

    if (to.meta.requiresRoleSuperAdmin) {
        if (userAuth.role == 'Super Admin') {
            next()
        } else {
            next('/404')
        }
    }

    if (to.meta.requiresRoleCashier) {
        if (userAuth.role != 'Cashier') {
            next('/404')
        }
    }

    if (!to.matched.length) {
        next('/404');
    } else {
        next();
    }
})
export default router