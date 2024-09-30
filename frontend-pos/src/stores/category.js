import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from '../plugins/axios'

export const useCategoryStore = defineStore('category', () => {
    const categories = ref('')

    function getCategories() {
        axios.get('/categories')
            .then(response => {
                categories.value = response.data.categories
            })
    }

    return { categories, getCategories }
})