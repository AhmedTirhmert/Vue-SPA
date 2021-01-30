import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import { BootstrapVue, BootstrapVueIcons } from "bootstrap-vue";
import Popper from "popper.js";
import axios from "axios";
import numeral from "numeral";
import vSelect from "vue-select";
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import "vue-select/dist/vue-select.css";
import "vue-sidebar-menu/dist/vue-sidebar-menu.css";
import "./scss/main.scss";
import { CoolSelectPlugin } from "vue-cool-select";
import("vue-cool-select/dist/themes/bootstrap.css");
Vue.use(CoolSelectPlugin);

Popper.Defaults.modifiers.computeStyle.gpuAcceleration = false;
Vue.config.productionTip = false;
Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons);
Vue.use(axios);
Vue.use(numeral);
Vue.component("v-select", vSelect);

new Vue({
  router,
  store,
  render: (h) => h(App),
}).$mount("#app");
