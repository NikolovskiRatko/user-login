import type { RouteRecordRaw } from "vue-router";

// Layouts
const AuthBase = () =>
  import(
    /* webpackChunkName: "auth-base" */
    /* webpackPrefetch: true */
    "@/components/AuthBase/AuthBase.vue"
  );

// Pages
const Login = () =>
  import(
    /* webpackChunkName: "login" */
    /* webpackPrefetch: true */
    "@/pages/Login/LoginPage.vue"
  );

const Register = () =>
  import(
    /* webpackChunkName: "register" */
    /* webpackPrefetch: true */
    "@/pages/Register/RegisterPage.vue"
  );

export const authPaths: RouteRecordRaw = {
  path: "/",
  component: AuthBase,
  children: [
    {
      path: "",
      name: "home",
      component: Login,
    },
    {
      path: "login",
      name: "login",
      component: Login,
    },
    {
      path: "register",
      name: "register",
      component: Register,
    },
  ],
};
