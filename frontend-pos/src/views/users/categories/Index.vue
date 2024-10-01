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
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col d-flex justify-content-start">
                  <RouterLink
                    :to="{ name: 'addCategory' }"
                    class="btn btn-sm btn-primary"
                    >Add Category</RouterLink
                  >
                </div>
              </div>
            </div>
            <div class="card-body p-2">
              <table class="table table-striped table-bordered">
                <thead class="thead-dark text-center">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">NAME</th>
                    <th scope="col">IMAGE</th>
                    <th scope="col">ACTION</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  <tr
                    v-for="(category, index) in categories"
                    :key="category.id"
                  >
                    <th>{{ index + 1 }}</th>
                    <td>{{ category.name }}</td>
                    <td>
                      <img :src="category.image" style="width: 100px" />
                    </td>
                    <td>
                      <RouterLink
                        :to="{
                          name: 'editCategory',
                          params: { id: category.id },
                        }"
                        class="btn btn-sm btn-primary mr-2"
                        ><i class="fa fa-pencil-alt"></i
                      ></RouterLink>
                      <button
                        @click.prevent="deleteData(category.id)"
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
import { useCategoriesStore } from "../../../stores/categories";
export default {
  setup() {
    const categoriesStore = useCategoriesStore();

    const categories = computed(() => categoriesStore.categories);

    onBeforeMount(() => {
      categoriesStore.getCategories();
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
          categoriesStore.deleteData(id);
        } else {
          return true;
        }
      });
    }

    return {
      categories,
      deleteData,
    };
  },
};
</script>
