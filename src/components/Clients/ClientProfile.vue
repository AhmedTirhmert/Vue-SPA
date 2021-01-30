<template>
  <b-modal
    :v-if="loaded"
    id="Client_Profile"
    centered
    size="xl"
    scrollable
    button-size="sm"
    header-bg-variant="dark"
    header-text-variant="light"
    footer-bg-variant="dark"
    footer-text-variant="light"
    ok-only
  >
    <template #modal-header>
      <b-row class="container">
        <b-col cols="auto">
          <b-avatar size="5rem">
            <img
              :src="`https://ui-avatars.com/api/?name=${SelectedProfile.name}&background=random&bold=true&color=fff&size=80%&rounded=true`"
              alt=""
            />
          </b-avatar>
        </b-col>
        <b-col class="ml-auto">
          <b-row>
            <b-col cols="auto">
              <small class="text-justify">Name </small><br />
              <small class="text-justify">Email </small><br />
              <small class="text-justify">Created at </small><br />
            </b-col>
            <b-col cols="auto">
              <b class="font-weight-bold">{{ SelectedProfile.name }}</b
              ><br />
              <b class="font-weight-bold">{{ SelectedProfile.email }}</b
              ><br />
              <b class="font-weight-bold"> {{ SelectedProfile.createdAt }} </b
              ><br />
            </b-col>
          </b-row>
        </b-col>
        <b-col class="ml-auto">
          <b-row>
            <b-col cols="5">
              <small class="text-justify">Total Commandes </small><br />
              <small class="text-justify">Total Products </small><br />
              <small class="text-justify">Total Transactins</small><br />
            </b-col>
            <b-col cols="7">
              <b class="font-weight-bold">{{
                ClientCommandes ? ClientCommandes.length : 0
              }}</b
              ><br />
              <b class="font-weight-bold">{{
                SelectedProfile.total_products
              }}</b
              ><br />
              <b class="font-weight-bold">
                {{ SelectedProfile.Transactions }} </b
              ><br />
            </b-col>
          </b-row>
        </b-col>
      </b-row>
    </template>
    <template #default>
      <!-- <b-table
        table-variant="light"
        responsive
        :busy="iscommandesBasy"
        striped
        head-variant="light"
        outlined
        bordered
        :items="ClientCommandes"
        :fields="commandesFields"
      >
        <template #cell(Details)="commande">
          <div>
            <b-button
              size="sm"
              @click="commande_details(commande)"
              variant="info"
            >
              <b-icon icon="info-circle-fill" variant="light"></b-icon>
            </b-button>
          </div>
        </template>
        <template #table-busy>
          <div class="text-danger text-center my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading ...</strong>
          </div>
        </template>
      </b-table> -->
    </template>
    <template #modal-footer> </template>
  </b-modal>
</template>

<script>
import axios from "@/axios";
export default {
  name: "ClientProfile",
  props: {
    SelectedProfile: {
      type: Object,
      required: true,
    },
  },
  methods: {
    getClientDets() {
      axios({
        method: "get",
        url: "profiledetails",
        params: {
          id: this.SelectedProfile.id,
        },
      })
        .then((response) => {
          this.getClientCommandes();
          let temp = new Date(response.data.profile.created_at);
          this.SelectedProfile.createdAt = temp.toLocaleDateString();
          this.SelectedProfile.total_products =
            response.data.stats.original.Products;
          this.SelectedProfile.Transactions = this.convert(
            response.data.stats.original.Transactions
          );
          this.loaded = true;
          this.iscommandesBasy = true;
        })
        .catch((err) => {
          err;
        });
    },
    getClientCommandes() {
      axios({
        method: "get",
        url: "clientcommandes",
        params: {
          id_client: this.SelectedProfile.id,
        },
      })
        .then((response) => {
          this.ClientCommandes = response.data;
          for (const key in this.ClientCommandes) {
            if (this.ClientCommandes[key].state == "confirmé") {
              this.ClientCommandes[key]._cellVariants = { state: "success" };
            } else {
              this.ClientCommandes[key]._cellVariants = { state: "warning" };
            }
          }
          this.iscommandesBasy = false;
        })
        .catch((err) => {
          err;
        });
    },
    commande_details(commande) {
      this.$root.$emit("cmnd-dets", commande.item.cmnd_id);
      //   this.isDetailsBusy = true;
      //   this.SelectedCommande = commande.item;
      //   this.CommandeDetails = {};
      //   axios({
      //     method: "get",
      //     url: "commandeDetails",
      //     params: {
      //       commandeId: commande.item.cmnd_id,
      //     },
      //   })
      //     .then((response) => {
      //       this.CommandeDetails = response.data;
      //       this.isDetailsBusy = false;
      //     })
      //     .catch((err) => {
      //       console.log(err);
      //     });

      //   this.$bvModal.show("commande_details");
    },
    mounted() {},
    created() {
      console.log("ClientProfile Mounted");
      console.log(this.loaded);
      this.getClientDets();
    },
    data: function () {
      return {
        loaded: false,
        commandesFields: [
          {
            key: "cmnd_id",
            label: "REF",
            sortable: false,
          },
          {
            key: "Date",
            label: "Date",
            sortable: true,
          },
          {
            key: "admin",
            label: "Reviewed By ",
            sortable: false,
          },
          {
            key: "state",
            label: "State",
            sortable: true,
          },
          {
            key: "Message",
            label: "Admin's Replay ",
            sortable: false,
          },
          {
            key: "Details",
            label: "",
            sortable: false,
          },
        ],
        iscommandesBasy: false,
        ClientCommandes: {},
      };
    },
    computed: {},
  },
};
</script>

<style>
</style>