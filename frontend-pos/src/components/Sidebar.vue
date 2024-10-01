<template>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <img
        src="/adminlte/dist/img/AdminLTELogo.png"
        alt="AdminLTE Logo"
        class="brand-image img-circle elevation-3"
        style="opacity: 0.8"
      />
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img
            v-bind:src="'https://ui-avatars.com/api/?name=' + user.name"
            class="img-circle elevation-2"
            alt="User Image"
          />
        </div>
        <div class="info">
          <a class="d-block">Hi - {{ user.name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul
          class="nav nav-pills nav-sidebar flex-column"
          data-widget="treeview"
          role="menu"
          data-accordion="false"
        >
          <li class="nav-item">
            <RouterLink
              :to="{ name: 'home' }"
              :class="$route.name == 'home' ? 'active' : ''"
              class="nav-link"
            >
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Home</p></RouterLink
            >
          </li>

          <li class="nav-item" v-if="role == 'Admin' || role == 'Super Admin'">
            <RouterLink
              :to="{ name: 'categories' }"
              :class="$route.name == 'categories' ? 'active' : ''"
              class="nav-link"
            >
              <i class="nav-icon fas fa-th-list"></i>
              <p>Categories</p></RouterLink
            >
          </li>
          <li class="nav-item" v-if="role == 'Admin' || role == 'Super Admin'">
            <RouterLink
              :to="{ name: 'products' }"
              :class="$route.name == 'products' ? 'active' : ''"
              class="nav-link"
            >
              <i class="nav-icon fas fa-shopping-bag"></i>
              <p>Products</p></RouterLink
            >
          </li>
          <li class="nav-item" v-if="role == 'Admin' || role == 'Super Admin'">
            <RouterLink
              :to="{ name: 'purchases' }"
              :class="$route.name == 'purchases' ? 'active' : ''"
              class="nav-link"
            >
              <i class="nav-icon fas fa-store"></i>
              <p>Purchases</p></RouterLink
            >
          </li>
          <li class="nav-item">
            <RouterLink
              :to="{ name: 'sales' }"
              :class="$route.name == 'sales' ? 'active' : ''"
              class="nav-link"
            >
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Sale</p></RouterLink
            >
          </li>
          <li class="nav-item" v-if="role == 'Super Admin'">
            <RouterLink
              :to="{ name: 'users' }"
              :class="$route.name == 'users' ? 'active' : ''"
              class="nav-link"
            >
              <i class="nav-icon fas fa-users"></i>
              <p>Users</p></RouterLink
            >
          </li>
          <!-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                UI Elements
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>General</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="." class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Icons</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Buttons</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sliders</p>
                </a>
              </li>
            </ul>
          </li> -->
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>
<script>
import { ref, onMounted, computed } from "vue";
import { useAuthStore } from "../stores/auth";
export default {
  setup() {
    //inisialisasi store
    const userAuth = useAuthStore();

    //state user
    const user = computed(() => userAuth.user);
    const role = computed(() => userAuth.role);

    //mounted properti
    onMounted(() => {
      $('[data-widget="treeview"]').Treeview("init");
    });
    return {
      user,
      role
    };
  },
};
</script>
