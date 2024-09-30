<template>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a
          id="navbarDropdown"
          class="nav-link dropdown-toggle"
          data-toggle="dropdown"
          role="button"
        >
          <strong>{{ user?.name }}</strong>
        </a>
        <div class="dropdown-menu dropdown-menu-right bg-secondary text-center">
          <button class="dropdown-item bg-secondary" v-on:click="logout">
            <strong>Logout</strong>
          </button>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
</template>

<script>
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import axios from "../plugins/axios";
import { useAuthStore } from "../stores/auth";
export default {
  setup() {
    //state token

    //inisialisasi vue router on Composition API
    const router = useRouter();

    //inisialisasi store
    const userAuth = useAuthStore();

    //state user
    const user = computed(() => userAuth.user);
    const role = computed(() => userAuth.role);

    //mounted properti
    onMounted(() => {
    });

    //method logout
    function logout() {
      //logout
      axios
        .post("/logout")
        .then((response) => {
          if (response.success) {
            swal({
              title: "SUCCESS!",
              text: response.message,
              icon: "success",
              timer: 5000,
              buttons: false,
            });
            userAuth.logout();
            //redirect ke halaman login
            return router.push({
              name: "login",
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    }

    return {
      user,
      role,
      logout,
    };
  },
};
</script>

<style></style>
