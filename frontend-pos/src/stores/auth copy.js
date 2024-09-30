import { ref, } from 'vue'
import { defineStore } from 'pinia'
import axios from '../plugins/axios'

export const useAuthStore = defineStore('user', () => {
    const token = ref('')
    const user = ref(null)
    const role = ref(null)

    if (localStorage.getItem("token")) {
        token.value = localStorage.getItem('token');
        axios.get('/user')
            .then(response => {
                user.value = response.user
                role.value = user.name
            })
    }

    function login(user_data, data_token) {
        console.log('USER Is', user_data)
        localStorage.setItem('token', data_token)
        user.value = user_data
        token.value = data_token
    }

    function setToken() {
        const tokenLs = localStorage.getItem('token')
        if (tokenLs) {
            token.value = tokenLs
        }
    }

    function getAuth() {
        if (token) {
            axios.get('/user')
                .then(response => {
                    user.value = response.user
                    role.value = response.user.roles.name
                })
        }
    }

    function logout() {
        localStorage.removeItem('token')
        token.value = ''
        user.value = null
        role.value = null
    }

    return { user, token, role, login, logout, setToken, getAuth }
})