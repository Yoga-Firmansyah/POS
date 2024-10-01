import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from '../plugins/axios'

export const usePurchasesStore = defineStore('purchase', () => {
    const purchases = ref('')

    function getPurchases() {
        axios.get('/purchases')
            .then(response => {
                purchases.value = response.purchases
            })
    }

    function deleteData(id) {
        axios.delete(`/purchases/${id}`)
            .then(() => {
                axios.get('/purchases')
                    .then(response => {
                        purchases.value = response.purchases
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

    return { purchases, getPurchases, deleteData }
})