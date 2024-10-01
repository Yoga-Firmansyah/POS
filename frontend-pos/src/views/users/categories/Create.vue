<template>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Categories</h1>
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
                <i class="fas fa-plus mr-2"></i>Add Category
              </h6>
            </div>

            <div class="card-body">
              <form @submit.prevent="addData">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Name</label>
                      <input
                        name="name"
                        autocomplete="name"
                        type="text"
                        v-model="category.name"
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
                        <div class="custom-file">
                          <input
                            type="file"
                            accept="image/*"
                            class="custom-file-input"
                            id="exampleInputFile"
                            @change="handleFileChange($event)"
                          />
                          <label
                            class="custom-file-label"
                            for="exampleInputFile"
                            >Choose file</label
                          >
                          <div class="input-group-append">
                            <span class="input-group-text">Upload</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div
                      v-if="validation.image"
                      class="mt-2 alert alert-danger"
                    >
                      {{ validation.image[0] }}
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
</template>

<script>
import { reactive, ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "../../../plugins/axios";

export default {
  setup() {
    const router = useRouter();

    const category = reactive({
      name: "",
      image: "",
    });

    const validation = ref([]);

    //method for handle file changes
    const handleFileChange = (e) => {
      //assign file to state
      category.image = e.target.files[0];
    };

    onMounted(() => {
      $(function () {
        bsCustomFileInput.init();
      });
    });

    async function addData() {
      let formData = new FormData();

      //assign state value to formData
      formData.append("name", category.name);
      formData.append("image", category.image);
      //send server with axios
      await axios
        .post("/categories", formData)
        .then((response) => {
          swal({
            title: "SUCCESS!",
            text: response.message,
            icon: "success",
            timer: 5000,
            buttons: false,
          });

          return router.push({
            name: "categories",
          });
        })
        .catch((error) => {
          validation.value = error.data;
        });
    }

    return {
      category,
      validation,
      addData,
      handleFileChange,
    };
  },
};
</script>
