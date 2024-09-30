import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from '../plugins/axios'

export const useUsersStore = defineStore('users', () => {
    const users = ref(null)

    function getUsers() {
        axios.get('/users')
            .then(response => {
                user.value = response.data.user
                role.value = response.data.user.roles.name
            })
    }

    return { users, getUsers }
})