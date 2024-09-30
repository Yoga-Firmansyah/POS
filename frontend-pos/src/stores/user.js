import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', () => {
    const user = ref(null)
    const token = ref('')
    function login(user_data, data_token) {
        console.log('USER Is', user_data)
        localStorage.setItem('user', JSON.stringify(user_data))
        localStorage.setItem('token', data_token)
        user.value = user_data
        token.value = data_token
    }
    function setAuth() {
        const userLs = JSON.parse(localStorage.getItem('user'))
        const tokenLs = localStorage.getItem('token')
        if (userLs && tokenLs) {
            user.value = userLs
            token.value = tokenLs
        }
    }
    function logout() {
        localStorage.removeItem('user')
        localStorage.removeItem('token')
        user.value = null
        token.value = ''
    }

    return { user, token, login, logout, setAuth }
})