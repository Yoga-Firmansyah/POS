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
          <div class="card border-0 shadow">
            <div class="card-header">
              <h6 class="m-0 font-weight-bold">
                <i class="fas fa-plus mr-2"></i>Add Product
              </h6>
            </div>

            <div class="card-body">
              <form @submit.prevent="addData">
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
                          name="code"
                          autocomplete="code"
                          type="text"
                          v-model="product.code"
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
                      <input
                        name="name"
                        autocomplete="name"
                        type="text"
                        v-model="product.name"
                        class="form-control"
                        placeholder="Name"
                        required
                      />
                    </div>
                    <div v-if="validation.name" class="mt-2 alert alert-danger">
                      {{ validation.name[0] }}
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Image</label>
                      <div class="input-group">
                        <input
                          type="file"
                          accept="image/*"
                          @change="handleFileChange($event)"
                        />
                      </div>
                    </div>
                    <div
                      v-if="validation.image"
                      class="mt-2 alert alert-danger"
                    >
                      {{ validation.image[0] }}
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Purchase Price</label>
                      <input
                        name="purchase"
                        autocomplete="purchase"
                        type="number"
                        min="0"
                        v-model="product.purchase_price"
                        class="form-control"
                        placeholder="Purchase Price"
                        required
                      />
                    </div>
                    <div
                      v-if="validation.purchase_price"
                      class="mt-2 alert alert-danger"
                    >
                      {{ validation.purchase_price[0] }}
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Sale Price</label>
                      <input
                        name="sale"
                        autocomplete="sale"
                        type="number"
                        min="0"
                        v-model="product.sale_price"
                        class="form-control"
                        placeholder="Sale Price"
                        required
                      />
                    </div>
                    <div
                      v-if="validation.sale_price"
                      class="mt-2 alert alert-danger"
                    >
                      {{ validation.sale_price[0] }}
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Discount</label>
                      <input
                        name="discount"
                        autocomplete="discount"
                        type="number"
                        min="0"
                        v-model="product.discount"
                        class="form-control"
                        placeholder="Discount Price"
                      />
                    </div>
                    <div
                      v-if="validation.discount"
                      class="mt-2 alert alert-danger"
                    >
                      {{ validation.discount[0] }}
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Qty</label>
                      <input
                        name="qty"
                        autocomplete="qty"
                        type="number"
                        min="0"
                        v-model="product.qty"
                        class="form-control"
                        placeholder="Qty"
                      />
                    </div>
                    <div
                      v-if="validation.discount"
                      class="mt-2 alert alert-danger"
                    >
                      {{ validation.discount[0] }}
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Category</label>
                      <select
                        v-model="product.category_id"
                        class="form-control"
                        required
                      >
                        <option value="" disabled>Select Category</option>
                        <option
                          v-for="(category, index) in categories"
                          :key="index"
                          :value="category.id"
                        >
                          {{ category.name }}
                        </option>
                      </select>
                    </div>
                    <div
                      v-if="validation.category_id"
                      class="mt-2 alert alert-danger"
                    >
                      {{ validation.category_id[0] }}
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
  </section>
  <CameraScanner
    v-model="product.code"
    v-model:open-modal="barcodeModalState"
  />
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
</script>
