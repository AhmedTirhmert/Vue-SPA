<template>
  <div>
    <sidebar-menu :menu="menu" />
    <div class="wrapper">
      <!-- Sidebar  -->
      <!-- <nav
        id="sidebar"
        :class="
          active
            ? 'active d-flex flex-column align-content-around'
            : 'd-flex flex-column align-content-around'
        "
      >
        <div class="sidebar-header d-flex align-items-start">
          <h3>Stock Manager</h3>
        </div>

        <ul class="list-unstyled components">
          <li>
            <router-link
              to="/"
              class="d-flex justify-content-between align-items-center px-2 py-2"
              >Dashboard <b-icon icon="grid3x3-gap-fill"></b-icon
            ></router-link>
          </li>
          <li>
            <router-link
              to="/clients"
              class="d-flex justify-content-between align-items-center px-2 py-2"
              >Clients <b-icon icon="people"></b-icon
            ></router-link>
          </li>
          <li>
            <router-link
              to="/admins"
              class="d-flex justify-content-between align-items-center px-2 py-2"
              >admins <b-icon icon="people-fill"></b-icon
            ></router-link>
          </li>
          <li>
            <router-link
              to="/products"
              class="d-flex justify-content-between align-items-center px-2 py-2"
              >Products <b-icon icon="pip-fill"></b-icon
            ></router-link>
          </li>
          <li>
            <router-link
              to="/fournisseurs"
              class="d-flex justify-content-between align-items-center px-2 py-2"
              >Fournisseurs <b-icon icon="truck"></b-icon
            ></router-link>
          </li>
          <li>
            <router-link
              to="/Payments"
              class="d-flex justify-content-between align-items-center px-2 py-2"
              >Payments <b-icon icon="credit-card-fill"></b-icon
            ></router-link>
          </li>
          <li>
            <router-link
              to="/Employees"
              class="d-flex justify-content-between align-items-center px-2 py-2"
              >Employees <b-icon icon="person-lines-fill"></b-icon
            ></router-link>
          </li>
          <li>
            <router-link
              to="/Commandes"
              class="d-flex justify-content-between align-items-center px-2 py-2"
              >Commandes <b-icon icon="card-checklist"></b-icon
            ></router-link>
          </li>
        </ul>
      </nav> -->

      <!-- Page Content  -->
      <div id="content" class="pt-0">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">
            <button
              type="button"
              @click="toggle()"
              class="btn btn-sm btn-secondary px-2"
            >
              <i class="fas fa-align-left"></i>
            </button>
            <div
              class="collapse navbar-collapse"
              id="navbarSupportedContent"
            ></div>
          </div>
        </nav>

        <router-view />
      </div>
    </div>
  </div>
</template>

<script>
import SidebarMenu from "vue-sidebar-menu";
import "vue-sidebar-menu/dist/vue-sidebar-menu.css";
export default {
  components: {
    SidebarMenu,
  },
  name: "SideBare",
  data() {
    return {
      menu: [
        {
          header: true,
          title: "Main Navigation",
          hiddenOnCollapse: false,
        },
        {
          href: "/",
          title: "Dashboard",
          icon: "fa fa-user",
        },
        {
          href: "/charts",
          title: "Charts",
          icon: "fa fa-chart-area",
          child: [
            {
              href: "/charts/sublink",
              title: "Sub Link",
            },
          ],
        },
      ],
    };
  },
  methods: {
    toggle: function () {
      if (this.active == "") {
        this.active = "active";
      } else {
        this.active = "";
      }
      this.toggled = !this.toggled;
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
/*
    DEMO STYLE
*/

@import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";

p {
  font-family: "Poppins", sans-serif;
  font-size: 1.1em;
  font-weight: 300;
  line-height: 1.7em;
  color: #999;
}

a,
a:hover,
a:focus {
  color: inherit;
  text-decoration: none;
  transition: all 0.3s;
}

.navbar {
  padding: 15px 10px;
  background: #fff;
  border: none;
  border-radius: 0;
  margin-bottom: 40px;
  box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
}

.navbar-btn {
  box-shadow: none;
  outline: none !important;
  border: none;
}

.line {
  width: 100%;
  height: 1px;
  border-bottom: 1px dashed #ddd;
  margin: 40px 0;
}

/* ---------------------------------------------------
    SIDEBAR STYLE
----------------------------------------------------- */

.wrapper {
  display: flex;
  width: 100%;
  align-items: stretch;
}

#sidebar {
  min-width: 200px;
  max-width: 200px;
  background: #3b3b3b;
  color: #f7f7f7;
  transition: all 0.3s;
}

#sidebar.active {
  margin-left: -165px;
}

#sidebar .sidebar-header {
  font-family: bebas;
  padding: 20px 10px;
  background: #222222;
}

#sidebar ul.components {
  padding: 20px 0;
}

#sidebar ul p {
  color: #fff;
  padding: 10px;
}

#sidebar ul li a {
  padding: 10px;
  font-size: 1em;
  display: block;
}

#sidebar ul li a:hover {
  color: #1c1c1c;
  background: #fff;
}

#sidebar ul li.active > a,
a[aria-expanded="true"] {
  color: #fff;
  background: #6d7fcc;
}

a[data-toggle="collapse"] {
  position: relative;
}

.dropdown-toggle::after {
  display: block;
  position: absolute;
  top: 50%;
  right: 20px;
  transform: translateY(-50%);
}

ul ul a {
  font-size: 0.9em !important;
  padding-left: 30px !important;
  background: #6d7fcc;
}

ul.CTAs {
  padding: 20px;
}

ul.CTAs a {
  text-align: center;
  font-size: 0.9em !important;
  display: block;
  border-radius: 5px;
  margin-bottom: 5px;
}

a.download {
  background: #fff;
  color: #7386d5;
}

a.article,
a.article:hover {
  background: #6d7fcc !important;
  color: #fff !important;
}

/* ---------------------------------------------------
    CONTENT STYLE
----------------------------------------------------- */

#content {
  width: 100%;
  padding: 20px;
  min-height: 100vh;
  transition: all 0.3s;
}

/* ---------------------------------------------------
    MEDIAQUERIES
----------------------------------------------------- */

@media (max-width: 768px) {
  #sidebar {
    margin-left: -250px;
  }
  #sidebar.active {
    margin-left: 0;
  }
  #sidebarCollapse span {
    display: none;
  }
}

.router-link-exact-active {
  background: linear-gradient(to left, #1e3c72, #2a5298);
}
</style>
