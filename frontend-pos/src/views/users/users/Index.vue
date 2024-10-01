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
          <div class="card">
            <div class="card-header row">
              <div class="col d-flex justify-content-start">
                <RouterLink
                  :to="{ name: 'addUser' }"
                  class="btn btn-sm btn-primary"
                  >Add User</RouterLink
                >
              </div>
            </div>
            <div class="container-fluid">
              <div class="card-body p-2">
                <table class="table table-striped table-bordered">
                  <thead class="thead-dark text-center">
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">NAME</th>
                      <th scope="col">EMAIL</th>
                      <th scope="col">ROLE</th>
                      <th scope="col">ACTION</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    <tr v-for="(user, index) in users" :key="user.id">
                      <th>{{ index + 1 }}</th>
                      <td>{{ user.name }}</td>
                      <td>{{ user.email }}</td>
                      <td v-for="role in user.roles" :key="role.id">
                        {{ role.name }}
                      </td>
                      <td>
                        <RouterLink
                          :to="{ name: 'editUser', params: { id: user.id } }"
                          class="btn btn-sm btn-primary mr-2"
                          ><i class="fa fa-pencil-alt"></i
                        ></RouterLink>
                        <button
                          @click.prevent="deleteUser(user.id)"
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
    </div>
  </section>
</template>

<script>
import { ref, reactive, onBeforeMount, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import axios from "../../../plugins/axios";
import { useUsersStore } from "../../../stores/users";
export default {
  setup() {
    const userStore = useUsersStore();

    const users = computed(() => userStore.users);

    onBeforeMount(() => {
      userStore.getUsers();
    });

    onMounted(() => {});

    function deleteUser(id) {
      swal({
        title: "Are You Sure?",
        text: "This User Will Be Deleted!",
        icon: "warning",
        buttons: ["Cancel", "Yes, Delete It!"],
        dangerMode: true,
      }).then(function (isConfirm) {
        if (isConfirm) {
          userStore.deleteUser(id);
          swal({
            title: "DELETED!",
            text: "Your Data Has Been Deleted!",
            icon: "success",
            timer: 1000,
            buttons: false,
          });
        } else {
          return true;
        }
      });
    }

    return {
      users,
      deleteUser,
    };
  },
};
</script>
