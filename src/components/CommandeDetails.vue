<template>
  <b-modal
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
              :src="`https://ui-avatars.com/api/?name=${Client.name}&background=random&bold=true&color=fff`"
              alt=""
            />
          </b-avatar>
        </b-col>

        <b-col class="py-2" cols="auto">
          <h6 class="font-weight-bold">{{ Client.name }}</h6>
          <h6 class="font-weight-bold">{{ Client.email }}</h6>
        </b-col>

        <b-col class="py-2" cols="7 text-right">
          <h6 class="font-weight-bold">{{ Commande.Date }}</h6>
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
            {{ Commande.Date }}
          </h6>
        </b-col>
        <b-col class="p-0" cols="1 text-right">
          <b-button size="sm" variant="dark" >
            <b-icon icon="printer-fill" class="light"></b-icon
          ></b-button>
        </b-col>
      </b-row>
    </template>
  </b-modal>
</template>

<script>

//import axios from "@/axios";
export default {
  name: "CommandeDetails",
  props: ["SProfile", "SCommande"],
  methods: {

    commande_details() {
    //   this.isDetailsBusy = true;
    //   this.Commande = this.SCommande.item
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

    alert('Yea I\'m Here Working' )
    },

  },

  filters: {
    ModalTitle: function (value, op) {
      return `${op} The user ${value}`;
    },
  },
  data: function () {
    return {
      Profile: {},
      perpage: 10,
      CurrentPage: null,


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
      this.commande_details()
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

<style>
</style>