import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from '../plugins/axios'

export const useUserStore = defineStore('user', () => {
    const products = ref('')

    function getProducts() {
        axios.get('/products')
            .then(response => {
                products.value = response.data.products
            })
    }

    return { products, getProducts }
})