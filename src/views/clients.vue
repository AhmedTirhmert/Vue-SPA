<template>
  <div>
    <b-container>
      <b-row cols="1">
        <div class="mb-3 px-2">
          <b-row align-h="center">
            <b-col>
              <h3>
                Clients
                <b-badge variant="primary">{{ Clients.length }}</b-badge>
              </h3>
            </b-col>
            <b-col lg="" class="">
              <b-form-group
                label="Search"
                label-cols-md="3"
                label-align-md="right"
                label-size="md"
                label-for="filterInput"
                class="mb-0"
              >
                <b-input-group size="md">
                  <b-form-input
                    v-model="filter"
                    disabled
                    type="search"
                    id="filterInput"
                    placeholder="Type to Search a Client"
                  ></b-form-input>
                </b-input-group>
              </b-form-group>
            </b-col>
            <b-col cols="auto" class="ml-auto">
              <b-button size="md" variant="success" @click="AddClientModal()">
                <b-icon
                  icon="person-plus-fill"
                  class="light"
                ></b-icon></b-button
            ></b-col>
          </b-row>
        </div>

        <b-table
          table-variant="light"
          responsive
          :busy="isBasy"
          striped
          head-variant="light"
          outlined
          :items="Clients"
          :fields="fields"
          :per-page="perpage"
          :current-page="CurrentPage"
          :filter="filter"
          @filtered="onFiltered"
          :filter-included-fields="filterOn"
        >
          <template #cell(Options)="row">
            <div>
              <b-button-group size="mx-1">
                <b-button
                  size="sm"
                  @click="EditClientModal(row)"
                  variant="info"
                >
                  <b-icon icon="pencil-fill" variant="light"></b-icon>
                </b-button>
                <b-button size="sm" @click="DeleteClient(row)" variant="danger">
                  <b-icon icon="trash-fill" variant="light"></b-icon>
                </b-button>
                <b-button size="sm" @click="Profile(row)" variant="dark">
                  <b-icon icon="grid-fill" variant="light"></b-icon>
                </b-button>
              </b-button-group>
            </div>
          </template>
          <template #table-busy>
            <div class="text-danger text-center my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>Loading ...</strong>
            </div>
          </template>
        </b-table>

        <b-pagination
          pills
          :per-page="perpage"
          v-model="CurrentPage"
          :total-rows="totalRows"
          variant="info"
          align="right"
          small
        ></b-pagination>
      </b-row>
      <!-- ADDClient Modal -->
      <add-client-modal />
      <!-- EditClient Modal -->
      <edit-client-modal :SelectedClient="SelectedClient" />
      <!-- Profile Modal -->
      <client-profile :SelectedProfile="SelectedProfile" />
      <!-- commnade Modal -->
      <!-- <b-modal
        id="commande_details"
        centered
        size="xl"
        scrollable
        button-size="sm"
        header-bg-variant="secondary"
        header-text-variant="light"
        footer-bg-variant="secondary"
        footer-text-variant="light"
        ok-only
        static
      >
        <template #modal-header>
          <b-row class="container">
            <b-col cols="auto px-0">
              <b-avatar size="4rem">
                <img
                  :src="`https://ui-avatars.com/api/?name=${SelectedProfile.name}&background=random&bold=true&color=fff`"
                  alt=""
                />
              </b-avatar>
            </b-col>

            <b-col class="py-2" cols="auto">
              <h6 class="font-weight-bold">{{ SelectedProfile.name }}</h6>
              <h6 class="font-weight-bold">{{ SelectedProfile.email }}</h6>
            </b-col>

            <b-col class="py-2" cols="7 text-right">
              <h6 class="font-weight-bold">{{ SelectedCommande.Date }}</h6>
              <h6 class="font-weight-bold">
                {{ new Date().toLocaleDateString() }}
              </h6>
            </b-col>
          </b-row>
        </template>
        <template #default>
          <b-table
            small
            table-variant="light"
            responsive
            :busy="isDetailsBusy"
            striped
            head-variant="dark"
            outlined
            bordered
            :items="CommandeDetails"
            :fields="CommandeDetailsFields"
          >
            <template #cell(prix_total)="data">
              {{ (data.item.Quantity * data.item.Price).toFixed(2) }}
            </template>
            <template #table-busy>
              <div class="text-danger text-center my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading ...</strong>
              </div>
            </template>
          </b-table>
        </template>
        <template #modal-footer>
          <b-row class="container p-0">
            <b-col class="p-0" cols="6">
              <h6 class="font-weight-light py-1 m-0">
                {{ Articles + " Article" }}
              </h6>
            </b-col>

            <b-col class="p-0" cols="5 text-right">
              <h6 class="font-weight-light py-1 m-0">
                {{ SelectedCommande.Date }}
              </h6>
            </b-col>
            <b-col class="p-0" cols="1 text-right">
              <b-button size="sm" variant="dark" @click="print()">
                <b-icon icon="printer-fill" class="light"></b-icon
              ></b-button>
            </b-col>
          </b-row>
        </template>
      </b-modal> -->
    </b-container>
  </div>
</template>

<script>
import axios from "@/axios";
// import { EventBus } from "@/EventBus/index.js";
import AddClientModal from "../components/Clients/AddClient";
import EditClientModal from "../components/Clients/EditClient";
import ClientProfile from "../components/Clients/ClientProfile";
export default {
  name: "Clients",
  props: {},
  components: {
    ClientProfile,
    AddClientModal,
    EditClientModal,
  },
  methods: {
    print() {
      window.print();
    },

    Profile(client) {
      this.SelectedProfile = client.item;
      this.$bvModal.show("Client_Profile");
    },
    convert(val) {
      // thousands, millions, billions etc..
      var s = ["", "K", "M", "B", "T"];
      val = Math.trunc(val);

      // dividing the value by 3.
      var sNum = Math.floor(("" + val).length / 3);

      // calculating the precised value.
      var sVal = parseFloat(
        (sNum != 0 ? val / Math.pow(1000, sNum) : val).toPrecision(2)
      );

      if (sVal % 1 != 0) {
        sVal = sVal.toFixed(1);
      }

      // appending the letter to precised val.
      return sVal + s[sNum] + "   $";
    },

    // AddNewClient() {
    //   axios
    //     .get("adduser", {
    //       params: {
    //         user: this.NewClient,
    //       },
    //     })
    //     .then((res) => {
    //       console.log(res);
    //       this.$bvModal.msgBoxOk(res.data.message, {
    //         title: "Response",
    //         size: "md",
    //         buttonSize: "md",
    //         okVariant: "info",
    //         headerClass: "p-2 border-bottom-0",
    //         footerClass: "p-2 border-top-0",
    //         centered: false,
    //       });
    //       this.$bvModal.hide("AddClient");
    //       this.getusers();
    //       this.CurrentPage = Math.floor(this.rows() / this.perpage) + 1;
    //       this.overlay = false;
    //     })
    //     .catch((err) => {
    //       console.log(err);
    //       this.$bvModal.msgBoxOk(err, {
    //         title: "Response",
    //         size: "md",
    //         buttonSize: "md",
    //         okVariant: "info",
    //         headerClass: "p-2 border-bottom-0",
    //         footerClass: "p-2 border-top-0",
    //         centered: false,
    //       });
    //       this.overlay = false;
    //     });
    // },
    // ClearForm() {
    //   this.NewClient = {
    //     name: "",
    //     email: "",
    //     password: "",
    //     confirmPassword: "",
    //   };
    //   this.overlay = false;
    //   this.editoverlay = false;
    //   this.SelectedClient = {};
    // },
    // Subbmited() {
    //   this.overlay = !this.overlay;
    //   this.NewClient.type = 2;
    //   this.AddNewClient();
    // },
    AddClientModal() {
      this.$bvModal.show("AddClient");
    },
    EditClientModal(user) {
      this.SelectedClient = user.item;
      this.$bvModal.show("EditClient");
    },
    DeleteClient(user) {
      this.$bvModal
        .msgBoxConfirm(
          "U sure u wanna delete \n " + user.item.name.toUpperCase(),
          {
            title: "Confirmation",
            size: "sm",
            headerVariant: "danger",
            buttonSize: "sm",
            okVariant: "danger",
            headerClass: "p-2 border-bottom-0 bg-danger text-white",
            footerClass: "p-2 border-top-0 ",
            centered: true,
          }
        )
        .then((value) => {
          if (value) {
            axios({
              method: "get",
              url: "deleteUser",
              params: {
                id: user.item.id,
              },
            })
              .then((response) => {
                this.$bvToast.toast(response.data.message, {
                  title: "Deleted",
                  toaster: "toaster b-toaster-top-center",
                  variant: "success",
                  solid: true,
                  appendtoast: false,
                  centered: true,
                });
                this.getusers();
              })
              .catch((err) => {
                console.log(err);
              });
          }
        })
        .catch((err) => {
          // An error occurred
          err;
        });
    },
    searcheClient(name) {
      this.filter = name;
    },
    getusers() {
      this.isBasy = true;
      axios
        .get("getusers", {
          params: {
            type: 2,
          },
        })
        .then((response) => {
          this.Clients = response.data;
          this.isBasy = false;
          this.totalRows = this.Clients.length;
        })
        .catch(() => {
          this.$bvToast.toast("Can't Connect to The Server", {
            title: "Error",
            toaster: "toaster b-toaster-top-center",
            variant: "danger",
            solid: true,
            appendtoast: false,
          });
        });
    },
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
  },
  watch: {
    "SelectedClient.newpass": function () {
      this.buttonstate();
    },
  },
  filters: {
    ModalTitle: function (value, op) {
      return `${op} The user ${value}`;
    },
  },
  data: function () {
    return {
      overlay: false,

      filter: null,
      totalRows: 1,
      filterOn: [],

      Clients: [],
      formlabelcols: 3,
      ModalOperation: null,
      SelectedClient: {},
      SelectedProfile: {},
      perpage: 10,
      CurrentPage: null,

      fields: [
        {
          key: "name",
          sortable: false,
        },

        {
          key: "email",
          sortable: false,
        },

        {
          key: "Options",
          label: "",
          sortable: false,
        },
      ],
      isBasy: false,
      SelectedCommande: {},

      isDetailsBusy: false,
      CommandeDetails: {},
      CommandeDetailsFields: [
        {
          key: "Reference",
          sortable: false,
        },
        {
          key: "Desegnation",
          sortable: false,
        },
        {
          key: "Price",
          label: "P.U",
          sortable: false,
          formatter: (value) => {
            return (0 + value).toFixed(2);
          },
        },
        {
          key: "Quantity",
          label: "QTY",
          sortable: false,
        },

        {
          key: "prix_total",
          label: "P.T",
          sortable: false,
        },
        {
          key: "Category",
          sortable: false,
        },
        {
          key: "Fournisseur",
          sortable: false,
        },
      ],
    };
  },
  mounted() {
    this.$root.$on("Refresh", () => {
      this.getusers();
    });
    this.$root.$on("cmnd-dets", (id) => {
      console.log(id);
    });
    this.getusers();
  },
  computed: {
    Articles() {
      var tmp = 0;
      for (var det in this.CommandeDetails) {
        tmp += this.CommandeDetails[det].Quantity;
      }
      return tmp;
    },
  },
};
</script>

<style scoped></style>
