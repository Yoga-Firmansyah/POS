import { defineStore } from 'pinia'
import axios from '../plugins/axios'

export const useAuthStore = defineStore('user', {
    state: () => ({
        token: localStorage.getItem('token') || '',
        user: JSON.parse(localStorage.getItem('user')) || null,
        role: JSON.parse(localStorage.getItem('role')) || null,
    }),


    getters: {
        currentAuth(state) {
            if (state.token) {
                axios.get('/user')
                    .then(response => {
                        state.user = response.user
                        state.role = this.user.roles.name
                    })

                return state.user, state.role
            }
        },
    },


    actions: {
        login(user_data, data_token) {
            console.log('USER Is', user_data)
            localStorage.setItem('token', data_token)
            localStorage.setItem('user', JSON.stringify(user_data))
            localStorage.setItem('role', JSON.stringify(user_data.roles[0].name))
            this.user = user_data
            this.role = user_data.roles[0].name
            this.token = data_token
        },

        logout() {
            localStorage.removeItem('token')
            localStorage.removeItem('user')
            localStorage.removeItem('role')
            this.token = ''
            this.user = null
            this.role = null
        },

        getAuth() {
            axios.get('/user')
                .then(response => {
                    this.user = response.user
                    this.role = this.user.roles.name
                })
        },
    },

})