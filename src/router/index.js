import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const routes = [
  {
    path: "/Clients",
    name: "Clients",
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/clients.vue"),
  },
  {
    path: "/products",
    name: "products",
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/products.vue"),
  },
  {
    path: "/fournisseurs",
    name: "fournisseurs",
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/fournisseurs.vue"),
  },
  {
    path: "/Admins",
    name: "Admins",
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/admins.vue"),
  },
  {
    path: "/Payments",
    name: "Payments",
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/payments.vue"),
  },
  {
    path: "/Employees",
    name: "Employees",
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Employees.vue"),
  },
  {
    path: "/Commandes",
    name: "Commandes",
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Commandes.vue"),
  },
  {
    path: "/charts",
    name: "Charts",
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/charts.vue"),
  },
  // {
  //   path: "/about",
  //   name: "About",
  //   // route level code-splitting
  //   // this generates a separate chunk (about.[hash].js) for this route
  //   // which is lazy-loaded when the route is visited.
  //   component: () =>
  //     import(/* webpackChunkName: "about" */ "../views/About.vue")
  // }
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

export default router;
