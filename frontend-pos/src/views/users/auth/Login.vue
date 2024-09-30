<template>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="card card-outline card-primary">
        <div class="card-header text-center">
          <a href="#" class="h1"><b>Login</b></a>
        </div>
        <div class="card-body">
          <p class="login-box-msg">Sign in to start your session</p>
          <form @submit.prevent="login">
            <div class="input-group mb-3">
              <input
                name="email"
                v-model="user.email"
                type="email"
                class="form-control"
                placeholder="Email"
                autocomplete="email"
                required
              />
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input
                name="password"
                v-model="user.password"
                type="password"
                class="form-control"
                placeholder="Password"
                autocomplete="current-password"
              />
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                  Submit
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</template>

<script>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import axios from "../../../plugins/axios";
import { useAuthStore } from "../../../stores/auth";

export default {
  setup() {
    //inisialisasi vue router on Composition API
    const router = useRouter();

    //inisialisasi store
    const store = useAuthStore();

    //state user
    const user = reactive({
      email: "",
      password: "",
    });

    //method login
    async function login() {
      //define variable
      let email = user.email;
      let password = user.password;

      console.log(user);

      //send server with axios
      await axios
        .post("/login", {
          email,
          password,
        })
        .then((response) => {
          console.log("Login Success", response.user);
          store.login(response.user, response.token);
          store.getAuth();
          swal({
            title: "SUCCESS!",
            text: response.message,
            icon: "success",
            timer: 5000,
            buttons: false,
          });
          //redirect ke halaman dashboard
          return router.push({
            name: "home",
          });
        })
        .catch((err) => {
          console.log("Failed to Login", err);
          let error;
          if (err.message) {
            error = err.message;
          } else {
            error = err.data.message;
          }

          swal({
            title: "ERROR!",
            text: error,
            icon: "error",
            timer: 5000,
            buttons: false,
          });
        });
    }

    return {
      user, // <-- state user
      login, // <-- method login
    };
  },
};
</script>
