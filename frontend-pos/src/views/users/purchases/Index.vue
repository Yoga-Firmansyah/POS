<template>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Purchases</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right"></ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col d-flex justify-content-start">
                  <RouterLink
                    :to="{ name: 'addPurchase' }"
                    class="btn btn-sm btn-primary"
                    >Add Purchase</RouterLink
                  >
                </div>
              </div>
            </div>
            <div class="card-body p-2">
              <table class="table table-striped table-bordered rounded">
                <thead class="thead-dark text-center">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">DATE</th>
                    <th scope="col">ITEMS</th>
                    <th scope="col">PRICE</th>
                    <th scope="col">DISCOUNT</th>
                    <th scope="col">GRAND TOTAL</th>
                    <th scope="col">DETAIL</th>
                    <th scope="col">ACTION</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  <tr v-for="(purchase, index) in purchases" :key="purchase.id">
                    <th>{{ index + 1 }}</th>
                    <td>
                        {{ purchase.updated_at }}
                    </td>
                    <td>{{ purchase.total_item }}</td>
                    <td style="color: blue">
                      Rp{{ moneyFormat(purchase.total_price) }}
                    </td>
                    <td style="color: red">
                      Rp{{ moneyFormat(purchase.discount) }}
                    </td>
                    <td style="color: green">
                      Rp{{ moneyFormat(purchase.grand_total) }}
                    </td>
                    <td>
                      <ul v-for="(item) in purchase.purchase_details" :key="item.id">
                        <li>{{ item.product.name }} ({{ item.qty }}) (Rp{{ moneyFormat(item.subtotal) }})</li>
                      </ul>
                    </td>
                    <td>
                      <RouterLink
                        :to="{
                          name: 'editPurchase',
                          params: { id: purchase.id },
                        }"
                        class="btn btn-sm btn-primary mr-2"
                        ><i class="fa fa-pencil-alt"></i
                      ></RouterLink>
                      <button
                        @click.prevent="deleteData(purchase.id)"
                        class="btn btn-sm btn-danger"
                      >
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { ref, reactive, onBeforeMount, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import axios from "../../../plugins/axios";
import { usePurchasesStore } from "../../../stores/purchases";
export default {
  setup() {
    const purchasesStore = usePurchasesStore();

    const purchases = computed(() => purchasesStore.purchases);


    onBeforeMount(() => {
      purchasesStore.getPurchases();
    });

    onMounted(() => {});

    function deleteData(id) {
      swal({
        title: "Are You Sure?",
        text: "This Data Will Be Deleted!",
        icon: "warning",
        buttons: ["Cancel", "Yes, Delete It!"],
        dangerMode: true,
      }).then(function (isConfirm) {
        if (isConfirm) {
          purchasesStore.deleteData(id);
        } else {
          return true;
        }
      });
    }

    return {
      purchases,
      deleteData,
    };
  },
};
</script>
