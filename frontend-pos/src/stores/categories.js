import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from '../plugins/axios'

export const useCategoriesStore = defineStore('category', () => {
    const categories = ref('')

    function getCategories() {
        axios.get('/categories')
            .then(response => {
                categories.value = response.categories
            })
    }

    function deleteData(id) {
        axios.delete(`/categories/${id}`)
            .then(() => {
                axios.get('/categories')
                    .then(response => {
                        categories.value = response.categories
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

    return { categories, getCategories, deleteData }
})