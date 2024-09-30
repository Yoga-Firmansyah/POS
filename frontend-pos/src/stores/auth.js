import { ref, } from 'vue'
import { defineStore } from 'pinia'
import axios from '../plugins/axios'

export const useAuthStore = defineStore('user', () => {
    const token = ref(localStorage.getItem('token') || '')
    const user = ref(JSON.parse(localStorage.getItem('user')) || null)
    const role = ref(JSON.parse(localStorage.getItem('role')) || null)

    if (token.value != '') {
        axios.get('/user')
            .then(response => {
                localStorage.setItem('user', JSON.stringify(response.user))
                localStorage.setItem('role', JSON.stringify(response.user.roles[0].name))
                user.value = response.user
                role.value = response.user.roles[0].name
            })
    }

    function login(user_data, data_token) {
        console.log('USER Is', user_data)
        localStorage.setItem('token', data_token)
        localStorage.setItem('user', JSON.stringify(user_data))
        localStorage.setItem('role', JSON.stringify(user_data.roles[0].name))
        user.value = user_data
        role.value = user_data.roles[0].name
        token.value = data_token
    }

    async function getAuth() {
        await axios.get('/user')
            .then(response => {
                localStorage.setItem('user', JSON.stringify(response.user))
                localStorage.setItem('role', JSON.stringify(response.user.roles[0].name))
                user.value = response.user
                role.value = response.user.roles[0].name
            })
    }

    function logout() {
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        localStorage.removeItem('role')
        token.value = ''
        user.value = null
        role.value = null
    }

    return { user, token, role, login, logout, getAuth }
})