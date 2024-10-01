<template>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Products</h1>
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
                    :to="{ name: 'addProduct' }"
                    class="btn btn-sm btn-primary"
                    >Add Product</RouterLink
                  >
                </div>
              </div>
            </div>
            <div class="card-body p-2">
              <table class="table table-striped table-bordered rounded">
                <thead class="thead-dark text-center">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">CODE</th>
                    <th scope="col">NAME</th>
                    <th scope="col">IMAGE</th>
                    <th scope="col">PURCHASE</th>
                    <th scope="col">PRICE</th>
                    <th scope="col">DISCOUNT</th>
                    <th scope="col">GRAND PRICE</th>
                    <th scope="col">CATEGORY</th>
                    <th scope="col">ACTION</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  <tr v-for="(product, index) in products" :key="product.id">
                    <th>{{ index + 1 }}</th>
                    <td>
                      <vue-barcode
                        style="width: 20vh"
                        :value="product.code"
                        :options="{ displayValue: true }"
                      ></vue-barcode>
                    </td>
                    <td>{{ product.name }}</td>
                    <td>
                      <img :src="product.image" class="rounded" style="width: 15vh" />
                    </td>
                    <td style="color: green">
                      Rp{{ moneyFormat(product.purchase_price) }}
                    </td>
                    <td style="color: blue">
                      Rp{{ moneyFormat(product.sale_price) }}
                    </td>
                    <td style="color: red">
                      Rp{{ moneyFormat(product.discount) }}
                    </td>
                    <td>
                      Rp{{ moneyFormat(product.sale_price - product.discount) }}
                    </td>
                    <td>
                      {{ product.category.name }}
                    </td>
                    <td>
                      <RouterLink
                        :to="{
                          name: 'editProduct',
                          params: { id: product.id },
                        }"
                        class="btn btn-sm btn-primary mr-2"
                        ><i class="fa fa-pencil-alt"></i
                      ></RouterLink>
                      <button
                        @click.prevent="deleteData(product.id)"
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
import { useProductsStore } from "../../../stores/products";
import { component as VueNumber } from "@coders-tm/vue-number-format";
export default {
  components: {
    VueNumber,
  },

  setup() {
    const productsStore = useProductsStore();

    const products = computed(() => productsStore.products);

    const number = {
      decimal: ",",
      separator: ".",
      prefix: "Rp",
      masked: false,
    };

    onBeforeMount(() => {
      productsStore.getProducts();
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
          productsStore.deleteData(id);
        } else {
          return true;
        }
      });
    }

    return {
      products,
      deleteData,
      number,
    };
  },
};
</script>
