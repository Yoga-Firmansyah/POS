<template>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Users</h1>
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
                <i class="fas fa-plus mr-2"></i>Edit User
              </h6>
            </div>

            <div class="card-body">
              <form @submit.prevent="editUser">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Name</label>
                      <input
                        type="text"
                        v-model="user.name"
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
                      <label>Email address</label>
                      <input
                        type="email"
                        v-model="user.email"
                        class="form-control"
                        placeholder="Email Address"
                        required
                      />
                    </div>
                    <div
                      v-if="validation.email"
                      class="mt-2 alert alert-danger"
                    >
                      {{ validation.email[0] }}
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>New Password</label>
                      <input
                        type="password"
                        v-model="user.password"
                        class="form-control"
                        placeholder="Password"
                      />
                    </div>
                    <div
                      v-if="validation.password"
                      class="mt-2 alert alert-danger"
                    >
                      {{ validation.password[0] }}
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Konfirmasi Password</label>
                      <input
                        type="password"
                        v-model="user.password_confirmation"
                        class="form-control"
                        placeholder="Konfirmasi Password"
                      />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Role</label>
                      <select v-model="user.role" class="form-control" required>
                        <option value="" disabled>Select Role</option>
                        <option
                          v-for="(role, index) in roles"
                          :key="index"
                          :value="role"
                        >
                          {{ role }}
                        </option>
                      </select>
                    </div>
                    <div v-if="validation.role" class="mt-2 alert alert-danger">
                      {{ validation.role[0] }}
                    </div>
                  </div>
                </div>
                <div class="d-flex justify-content-center">
                  <button class="btn btn-primary mr-1 btn-submit" type="submit">
                    <i class="fa fa-paper-plane"></i>Submit
                  </button>
                  <button
                    class="btn btn-secondary btn-reset"
                    @click="gotoUsers"
                  >
                    <i class="fa fa-times"></i>Cancel
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
import { reactive, ref, onBeforeMount, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import axios from "../../../plugins/axios";

export default {
  setup() {
    //inisialisasi vue router on Composition API
    const router = useRouter();
    const route = useRoute();

    //state user
    const user = reactive({
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      role: "",
    });

    //state validation
    const validation = ref([]);

    const roles = ref([]);

    onBeforeMount(async () => {
      await axios.get(`/users/${route.params.id}/edit`).then((response) => {
        user.name = response.data.name;
        user.email = response.data.email;
        user.role = response.data.roles[0].name;
      });
      await axios.get(`/roles`).then((response) => {
        roles.value = response.roles;
      });
    });

    function gotoUsers() {
      return router.push({
        name: "users",
      });
    }

    //method register
    async function editUser() {
      //init formData
      let formData = new FormData();

      //assign state value to formData
      formData.append("name", user.name);
      formData.append("email", user.email);
      formData.append("password", user.password);
      formData.append("password_confirmation", user.password_confirmation);
      formData.append("role", user.role);
      formData.append("_method", "PUT");

      //store data with API
      await axios
        .post(`/users/${route.params.id}`, formData)
        .then(() => {
          return router.push({
            name: "users",
          });
        })
        .catch((error) => {
          //assign response error data to state "errors"
          validation.value = error.data;
        });
    }

    return {
      user,
      validation,
      editUser,
      roles,
      gotoUsers,
    };
  },
};
</script>
