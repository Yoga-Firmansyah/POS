<template>
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <Navbar></Navbar>
      <Sidebar></Sidebar>
      <div class="content-wrapper">
        <router-view></router-view>
      </div>
      <Footer></Footer>
      <ControlSidebar></ControlSidebar>
    </div>
  </body>
</template>

<script>
import Navbar from "../components/Navbar.vue";
import Sidebar from "../components/Sidebar.vue";
import Footer from "../components/Footer.vue";
import ControlSidebar from "../components/ControlSide.vue";

export default {
  components: {
    Navbar,
    Sidebar,
    Footer,
    ControlSidebar,
  },
};
</script>

<style>
@import url("https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback");
</style>
