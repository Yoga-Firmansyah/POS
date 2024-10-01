import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from '../plugins/axios'

export const useProductsStore = defineStore('product', () => {
    const products = ref('')

    function getProducts() {
        axios.get('/products')
            .then(response => {
                products.value = response.products
            })
    }

    function deleteData(id) {
        axios.delete(`/products/${id}`)
            .then(() => {
                axios.get('/products')
                    .then(response => {
                        products.value = response.products
                    })

                swal({
                    title: "DELETED!",
                    text: "Your Data Has Been Deleted!",
                    icon: "success",
                    timer: 1000,
                    buttons: false,
                });
            }).catch((err) => {
                console.log("Failed to Delete", err);
                let error;
                if (err.message) {
                    error = err.message;
                } else {
                    error = err.data.message;
                }
                swal({
                    title: "ERROR!",
                    text: error,
                    icon: "error",
                    timer: 5000,
                    buttons: false,
                });
            })
    }

    return { products, getProducts, deleteData }
})