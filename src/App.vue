<template>
  <!-- <side-bare /> -->
  <div class="wrapper">
    <sidebar-menu
      :disableHover="false"
      :menu="menu"
      width="180px"
      theme="white-dark"
      :collapsed="true"
    >
      <div slot="logo">LOGO</div>
      <!-- <div slot="footer">footer</div> -->
      <span slot="toggle-icon"
        ><b-icon icon="arrows-expand" rotate="90"></b-icon
      ></span>
      <!-- <span slot="dropdown-icon">dropdown-icon</span> -->
    </sidebar-menu>
    <div class="containner">
      <div id="content" class="pt-0">
        <nav class="navbar navbar-expand-lg navbar-light bg-customdrak mb-5">
          <div class="container-fluid my-2 px-5">
            <router-link class="headrelogo pl-3" to="/">
              Dashboard
            </router-link>
          </div>
        </nav>
      </div>
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
// import SideBare from "./components/SideBare.vue";
import { SidebarMenu } from "vue-sidebar-menu";
export default {
  components: {
    SidebarMenu,
    // SideBare,
  },
  data() {
    return {
      menu: [
        {
          class: "my-4",
          disabled: true,
          hiddenOnCollapse: !true,
          hidden: !false,
        },
        {
          title: "Header",
          header: true,
          element: "b-img",
          props: {
            src: "../assets/logo.png",
          },
          hiddenOnCollapse: true,
          hidden: false,
          class: "p-4 ",
          attributes: {},
        },
        {
          href: {
            path: "/",
          },
          title: "Dashboard",
          // badge: {
          //   text: "12",
          //   element: "b-badge",
          //   attributes: {
          //     variant: "warning",
          //     pill: true,
          //   },
          // },
          icon: {
            element: "b-icon",
            class: "p-1",
            attributes: {
              icon: "house-fill",
            },
          },
        },
        {
          href: {
            path: "clients",
          },
          title: "Clients",
          // badge: {
          //   text: "12",
          //   element: "b-badge",
          //   attributes: {
          //     variant: "warning",
          //     pill: true,
          //   },
          // },
          icon: {
            element: "b-icon",
            class: "p-1",
            attributes: {
              icon: "person-circle",
            },
          },
        },
        {
          href: {
            path: "admins",
          },
          title: "Admins",
          // badge: {
          //   text: "12",
          //   element: "b-badge",
          //   attributes: {
          //     variant: "warning",
          //     pill: true,
          //   },
          // },
          icon: {
            element: "b-icon",
            class: "p-1",
            attributes: {
              icon: "person-square",
            },
          },
        },
        {
          href: {
            path: "products",
          },
          title: "Products",
          // badge: {
          //   text: "12",
          //   element: "b-badge",
          //   attributes: {
          //     variant: "warning",
          //     pill: true,
          //   },
          // },
          icon: {
            element: "b-icon",
            class: "p-1",
            attributes: {
              icon: "cart-fill",
            },
          },
        },
        {
          href: {
            path: "fournisseurs",
          },
          title: "Fournisseurs",
          // badge: {
          //   text: "12",
          //   element: "b-badge",
          //   attributes: {
          //     variant: "warning",
          //     pill: true,
          //   },
          // },
          icon: {
            element: "b-icon",
            class: "p-1",
            attributes: {
              icon: "truck",
            },
          },
        },
        {
          href: {
            path: "payments",
          },
          title: "Payments",
          // badge: {
          //   text: "12",
          //   element: "b-badge",
          //   attributes: {
          //     variant: "warning",
          //     pill: true,
          //   },
          // },
          icon: {
            element: "b-icon",
            class: "p-1",
            attributes: {
              icon: "cash",
            },
          },
        },
        {
          href: {
            path: "employees",
          },
          title: "Employees",
          // badge: {
          //   text: "12",
          //   element: "b-badge",
          //   attributes: {
          //     variant: "warning",
          //     pill: true,
          //   },
          // },
          icon: {
            element: "b-icon",
            class: "p-1",
            attributes: {
              icon: "person-lines-fill",
            },
          },
        },
        {
          href: {
            path: "commandes",
          },
          title: "Commandes",
          // badge: {
          //   text: "12",
          //   element: "b-badge",
          //   attributes: {
          //     variant: "warning",
          //     pill: true,
          //   },
          // },
          icon: {
            element: "b-icon",
            class: "p-1",
            attributes: {
              icon: "receipt",
            },
          },
        },
        {
          href: {
            path: "charts",
          },
          title: "Charts",
          // badge: {
          //   text: "12",
          //   element: "b-badge",
          //   attributes: {
          //     variant: "warning",
          //     pill: true,
          //   },
          // },
          icon: {
            element: "b-icon",
            class: "p-1",
            attributes: {
              icon: "pie-chart-fill",
            },
          },
        },
      ],
    };
  },
};
</script>

<style></style>
