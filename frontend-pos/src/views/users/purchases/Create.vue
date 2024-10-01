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
          <div class="card border-0 shadow">
            <div class="card-header">
              <h6 class="m-0 font-weight-bold">
                <i class="fas fa-plus mr-2"></i>Add Purchase
              </h6>
            </div>

            <div class="card-body">
              <form @submit.prevent="addCart(productData.id)">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Code</label>
                      <div class="input-group mb-3">
                        <div
                          class="input-group-prepend btn btn-sm btn-primary text-center"
                          @click="barcodeModalState = true"
                          style="cursor: pointer"
                        >
                          <SvgIcon type="mdi" :path="icons.mdiBarcodeScan" />
                        </div>
                        <input
                          @change="getProduct"
                          name="code"
                          autocomplete="code"
                          type="text"
                          v-model="productCode"
                          class="form-control"
                          placeholder="Code"
                          required
                        />
                      </div>
                    </div>
                    <div v-if="validation.code" class="mt-2 alert alert-danger">
                      {{ validation.code[0] }}
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Name</label>
                      <div class="form-control">
                        {{ productData.name }}
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Price @pcs</label>
                      <div class="form-control">
                        Rp{{ moneyFormat(productData.sale_price || 0) }}
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Discount @pcs</label>
                      <div class="form-control">
                        Rp{{ moneyFormat(productData.discount || 0) }}
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Qty ({{ productData.qty }})</label>
                      <input
                        name="qty"
                        autocomplete="qty"
                        type="number"
                        :min="1"
                        :max="productData.qty"
                        v-model="qty"
                        class="form-control"
                      />
                    </div>
                    <div
                      v-if="validation.purchase_price"
                      class="mt-2 alert alert-danger"
                    >
                      {{ validation.purchase_price[0] }}
                    </div>
                  </div>
                </div>

                <div class="d-flex justify-content-center">
                  <button class="btn btn-primary mr-1 btn-submit" type="submit">
                    <i class="fa fa-paper-plane"></i>Submit
                  </button>
                  <button class="btn btn-warning btn-reset" type="reset">
                    <i class="fa fa-redo"></i>Reset
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col d-flex justify-content-start">
                  <RouterLink
                    :to="{ name: 'addPurchase' }"
                    class="btn btn-sm btn-shopping-cart"
                    >Cart</RouterLink
                  >
                </div>
              </div>
            </div>
            <div class="card-body p-2">
              {{ cart }}
              <table class="table table-striped table-bordered rounded">
                <thead class="thead-dark text-center">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">NAME</th>
                    <th scope="col">QTY</th>
                    <th scope="col">SUBTOTAL</th>
                    <th scope="col">ACTION</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  <tr v-for="(item, index) in cart.id" :key="index">
                    <th>
                      {{ index + 1 }}
                    </th>
                    <td>
                      {{ cart.name[index] }}
                    </td>
                    <td>
                      {{ cart.qty[index] }}
                    </td>
                    <td>
                      {{ cart.subtotal[index] }}
                    </td>
                    <td>
                      <button
                        @click.prevent="deleteCart(index)"
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
  <CameraScanner v-model="productCode" v-model:open-modal="barcodeModalState" />
</template>

<script setup>
import { reactive, ref, onMounted, onBeforeMount } from "vue";
import { useRouter } from "vue-router";
import axios from "../../../plugins/axios";
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiBarcodeScan } from "@mdi/js";
import CameraScanner from "../../../components/CameraScanner.vue";

const router = useRouter();
const barcodeModalState = ref(false);
const icons = ref({
  mdiBarcodeScan,
});

const product = reactive({
  code: "",
  name: "",
  image: "",
  purchase_price: "",
  sale_price: "",
  discount: "",
  category_id: "",
  qty: "",
});

const cart = reactive({
  id: [],
  name: [],
  qty: [],
  subtotal: [],
});

const qty = ref("1");

const productCode = ref("");
const productData = reactive({
  id: "",
  name: "",
  sale_price: "",
  discount: "",
  qty: "",
});

const validation = ref([]);
const categories = ref([]);

//method for handle file changes
const handleFileChange = (e) => {
  //assign file to state
  product.image = e.target.files[0];
};

onBeforeMount(async () => {
  await axios.get(`/categories`).then((response) => {
    categories.value = response.categories;
  });
});

onMounted(() => {
  $(function () {
    bsCustomFileInput.init();
  });
});

async function getProduct() {
  await axios.get(`/products/${productCode.value}`).then((response) => {
    productData.id = response.data[0].id;
    productData.name = response.data[0].name;
    productData.sale_price = response.data[0].sale_price;
    productData.discount = response.data[0].discount;
    productData.qty = response.data[0].qty;
  });
}

async function addData() {
  let formData = new FormData();

  //assign state value to formData
  formData.append("code", product.code);
  formData.append("name", product.name);
  formData.append("image", product.image);
  formData.append("purchase_price", product.purchase_price);
  formData.append("sale_price", product.sale_price);
  formData.append("discount", product.discount);
  formData.append("category_id", product.category_id);
  formData.append("qty", product.qty);
  //send server with axios
  await axios
    .post("/products", formData)
    .then((response) => {
      swal({
        title: "SUCCESS!",
        text: response.message,
        icon: "success",
        timer: 5000,
        buttons: false,
      });

      return router.push({
        name: "products",
      });
    })
    .catch((error) => {
      validation.value = error.data;
    });
}

function addCart(data) {
  console.log("ini", data);
  let exist = cart.id.indexOf(data);
  console.log("ini", exist);
  if (exist < 0) {
    cart.id.push(productData.id);
    cart.name.push(productData.name);
    cart.qty.push(qty.value);
    cart.subtotal.push(
      productData.sale_price * qty.value - productData.discount * qty.value
    );

    console.log("this", cart);
  } else {
    swal({
      title: "ERROR!",
      text: "Duplicate Item",
      icon: "error",
      timer: 5000,
      buttons: false,
    });
  }
}

function deleteCart(index) {
  cart.id.splice(index, 1);
  cart.name.splice(index, 1);
  cart.qty.splice(index, 1);
  cart.subtotal.splice(index, 1);
}
</script>
