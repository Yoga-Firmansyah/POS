import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from '../plugins/axios'

export const useUsersStore = defineStore('users', () => {
    const users = ref(null)

    function getUsers() {
        axios.get('/users')
            .then(response => {
                users.value = response.users
            })
    }

    function deleteUser(userId) {
        axios.delete(`/users/${userId}`)
            .then(() => {
                axios.get('/users')
                    .then(response => {
                        users.value = response.users
                    })
            })
    }

    return { users, getUsers, deleteUser }
})