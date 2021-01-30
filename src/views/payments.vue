<template>
  <div>
    <b-container>
      <b-row cols="1">
        <div class="mb-3 px-0">
          <b-row align-h="end">
            <b-col cols="auto" class="mr-auto">
              <h3>
                Payments
                <b-badge variant="warning">{{ Payments.length }}</b-badge>
              </h3>
            </b-col>
            <b-col cols="auto" class="m-auto">
              <b-pagination
                :per-page="perpage"
                v-model="CurrentPage"
                :total-rows="totalRows"
                align="fill"
                variant="dark"
                small
              ></b-pagination>
            </b-col>
            <b-col cols="auto">
              <b-select v-model="perpage">
                <b-select-option value="5">5</b-select-option>
                <b-select-option value="10">10</b-select-option>
                <b-select-option value="20">20</b-select-option>
                <b-select-option value="50">50</b-select-option>
              </b-select>
            </b-col>
            <b-col cols="auto" class="px-1">
              <b-form-group class="mb-0">
                <b-input-group size="md">
                  <b-form-input
                    v-model="filter"
                    type="search"
                    id="filterInput"
                    placeholder="Search Payment"
                  ></b-form-input>
                  <template #prepend>
                    <b-button class="dark" disabled>
                      <b-icon icon="search"></b-icon>
                    </b-button>
                  </template>
                </b-input-group>
              </b-form-group>
            </b-col>
            <b-col cols="auto" class="px-1">
              <b-button size="md" variant="primary" @click="getPayments()">
                <b-icon icon="arrow-repeat"></b-icon>
              </b-button>
            </b-col>
            <b-col cols="auto" class="px-1 pr-3">
              <b-button size="md" variant="success" v-b-modal.AddPayment>
                <b-icon
                  icon="plus-circle-fill"
                  class="light"
                ></b-icon></b-button
            ></b-col>
          </b-row>
        </div>

        <b-table
          tbody-tr-class="text-right"
          thead-tr-class="text-center"
          small
          responsive
          :busy="isBasy"
          borderless
          striped
          head-variant="light"
          outlined
          selectable
          select-mode="single"
          @row-dblclicked="EditPaymentModal"
          :items="Payments"
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
                  @click="EditPaymentModal(row)"
                  variant="info"
                >
                  <b-icon icon="pencil-fill" variant="light"></b-icon>
                </b-button>
                <b-button
                  size="sm"
                  @click="DeletePayment(row)"
                  variant="danger"
                >
                  <b-icon icon="trash-fill" variant="light"></b-icon>
                </b-button>
                <b-button size="sm" @click="details(row)" variant="dark">
                  <b-icon icon="info" variant="light"></b-icon>
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
      </b-row>
      <!-- AddPayment Modal -->
      <b-modal
        id="AddPayment"
        header-bg-variant="success"
        header-text-variant="light"
        title="Add New Payment "
        size="md"
      >
        <b-overlay :show="overlay" rounded="md">
          <b-row>
            <b-col class="ml-auto">
              <b-input-group class="mt-3" append="$">
                <b-form-input
                  v-model="NewPayment.amount"
                  placeholder="Payment Amount"
                  type="number"
                ></b-form-input>
              </b-input-group>

              <b-input-group class="mt-3">
                <b-form-datepicker
                  v-model="NewPayment.exp_date"
                  placeholder="Payment Date"
                  type="date"
                ></b-form-datepicker>
              </b-input-group>

              <b-input-group prepend="Banque" class="mt-3">
                <template #append>
                  <b-button v-b-modal.AddBanque size="sm" variant="info"
                    ><b-icon icon="plus-square-fill"></b-icon
                  ></b-button>
                </template>
                <b-form-select v-model="NewPayment.banque" :options="Banques">
                  <template #first>
                    <b-form-select-option :value="null" disabled
                      >-- Please select a Bank --</b-form-select-option
                    >
                  </template></b-form-select
                >
              </b-input-group>

              <b-input-group class="mt-3" size="lg">
                <b-form-checkbox
                  switch
                  v-model="NewPayment.payed"
                  :checked="true"
                >
                  <b>{{ NewPayment.payed ? "" : "Not" }} Payed</b>
                </b-form-checkbox>
              </b-input-group>
            </b-col>
          </b-row>
        </b-overlay>
        <template #modal-footer>
          <div class="w-100">
            <b-button
              variant="success"
              size="sm"
              class="float-right"
              @click="AddNewPayment()"
            >
              Add Payment
            </b-button>
          </div>
        </template>
      </b-modal>
      <!-- add Banque modal  -->
      <b-modal
        id="AddBanque"
        header-bg-variant="dark"
        header-text-variant="light"
        title="Add New Banque "
        size="sm"
        centered
      >
        <b-overlay :show="overlay" rounded="md">
          <b-input-group prepend="Name" class="mt-3">
            <b-form-input
              v-model="NewBanque"
              placeholder="New Banque"
            ></b-form-input>
          </b-input-group>
        </b-overlay>
        <template #modal-footer>
          <div class="w-100">
            <b-button
              variant="success"
              size="sm"
              class="float-right"
              @click="AddBanque()"
            >
              Add Banque
            </b-button>
          </div>
        </template>
      </b-modal>
      <!-- add fournss modal  -->
      <b-modal
        id="AddFrns"
        header-bg-variant="secondary"
        header-text-variant="light"
        title="Add New Fournisseur "
        size="md"
        centered
      >
        <b-overlay :show="overlay" rounded="md">
          <b-input-group prepend="Name" class="mt-3">
            <b-form-input
              v-model="NewFrns.name"
              placeholder="New Fournisseur's Name"
            ></b-form-input>
          </b-input-group>

          <b-input-group prepend="Addresse" class="mt-3">
            <b-form-textarea
              v-model="NewFrns.adresse"
              placeholder="New Fournisseur's Adresse"
              name="address"
              rows="3"
              max-rows="10"
            ></b-form-textarea>
          </b-input-group>

          <b-input-group prepend="Email" class="mt-3">
            <b-form-input
              v-model="NewFrns.email"
              placeholder="New Fournisseur's Eamil"
              type="email"
            ></b-form-input>
          </b-input-group>

          <b-input-group prepend="Phone" class="mt-3">
            <b-form-input
              v-model="NewFrns.telephone"
              placeholder="New Fournisseur's Phone"
              name="phone"
            ></b-form-input>
          </b-input-group>
        </b-overlay>
        <template #modal-footer>
          <div class="w-100">
            <b-button
              variant="success"
              size="sm"
              class="float-right"
              @click="addFrns()"
            >
              Add Fournisseur
            </b-button>
          </div>
        </template>
      </b-modal>
      <!-- Edit Product Modal -->
      <b-modal
        id="EditPayment"
        header-bg-variant="info"
        header-text-variant="light"
        title="Edit : "
        size="md"
      >
        <b-overlay :show="overlay" rounded="md">
          <b-row>
            <b-col class="ml-auto">
              <b-input-group class="mt-3" append="$">
                <b-form-input
                  v-model="SelectedPayment.amount"
                  placeholder="Payment Amount"
                  type="number"
                ></b-form-input>
              </b-input-group>

              <b-input-group class="mt-3">
                <b-form-datepicker
                  v-model="SelectedPayment.Exp_date"
                  placeholder="Payment Date"
                  type="date"
                ></b-form-datepicker>
              </b-input-group>

              <b-input-group prepend="Banque" class="mt-3">
                <template #append>
                  <b-button v-b-modal.AddBanque size="sm" variant="info"
                    ><b-icon icon="plus-square-fill"></b-icon
                  ></b-button>
                </template>
                <b-form-select
                  v-model="SelectedPayment.Banque_id"
                  :options="Banques"
                >
                  <template #first>
                    <b-form-select-option :value="null" disabled
                      >-- Please select a Bank --</b-form-select-option
                    >
                  </template></b-form-select
                >
              </b-input-group>

              <b-input-group class="mt-3" size="lg">
                <b-form-checkbox switch v-model="SelectedPayment.Payed">
                  <b>{{ SelectedPayment.Payed ? "" : "Not" }} Payed</b>
                </b-form-checkbox>
              </b-input-group>
            </b-col>
          </b-row>
        </b-overlay>
        <template #modal-footer>
          <div class="w-100">
            <b-button
              variant="info"
              size="sm"
              class="float-right"
              @click="EditPayment()"
            >
              Edit
            </b-button>
          </div>
        </template>
      </b-modal>
      <!-- details Modal -->

      <!-- commnade Modal -->

    </b-container>
  </div>
</template>

<script>
import axios from "@/axios";
import numeral from "numeral";
export default {
  name: "Payments",
  components: {},
  methods: {
    AddBanque() {
      axios({
        method: "get",
        url: "addbanque",
        params: {
          name: this.NewBanque
        }
      })
        .then(response => {
          this.$bvToast.toast(response.data.message, {
            title: "success",
            toaster: "toaster b-toaster-top-right",
            variant: "success",
            solid: false,
            appendtoast: true
          });
          this.NewBanque = "";
          this.LoadData();
        })
        .catch(err => {
          this.$bvToast.toast(err.response.data.errors["name"], {
            title: "Banque ",
            toaster: "toaster b-toaster-top-right",
            variant: "danger",
            solid: true,
            appendtoast: false
          });
        });
    },
    EditPayment() {
      axios({
        method: "get",
        url: "updatepayment",
        params: {
          id: this.SelectedPayment.id,
          Exp_date: this.SelectedPayment.Exp_date,
          Banque_id: this.SelectedPayment.Banque_id,
          Payed: this.SelectedPayment.Payed,
          amount: this.SelectedPayment.amount
        }
      })
        .then(res => {
          this.$bvToast.toast(res.data.message, {
            title: "success",
            toaster: "toaster b-toaster-top-right",
            variant: "success",
            solid: false,
            appendtoast: true
          });
          this.$bvModal.hide("EditPayment");
        })
        .catch(err => {
          for (const error in err.response.data.errors) {
            this.$bvToast.toast(err.response.data.errors[error], {
              title: error,
              toaster: "toaster b-toaster-top-right",
              variant: "danger",
              solid: true,
              appendtoast: false
            });
          }
        });
    },
    AddNewPayment() {
      axios
        .get("addpayment", {
          params: {
            Exp_date: this.NewPayment.exp_date,
            Banque_id: this.NewPayment.banque,
            Payed: this.NewPayment.payed,
            amount: this.NewPayment.amount
          }
        })
        .then(res => {
          this.$bvToast.toast(res.data.message, {
            title: "success",
            toaster: "toaster b-toaster-top-right",
            variant: "success",
            solid: false,
            appendtoast: true
          });
        })
        .catch(err => {
          for (const error in err.response.data.errors) {
            this.$bvToast.toast(err.response.data.errors[error], {
              title: error,
              toaster: "toaster b-toaster-top-right",
              variant: "danger",
              solid: true,
              appendtoast: false
            });
          }
          this.overlay = false;
        });
    },
    ClearForm() {
      this.NewPayment = { payed: false };
      this.overlay = false;
      this.editoverlay = false;
      this.SelectedPayment = {};
    },
    AddPaymentModal() {
      this.LoadData();
      this.$bvModal.show("AddPayment");
    },
    LoadData() {
      axios({
        method: "get",
        url: "banques"
      })
        .then(response => {
          this.Banques = [];
          for (const data in response.data) {
            this.Banques.push(response.data[data]);
          }
          this.NewPayment.banque = null;
        })
        .catch(err => {
          console.log(err);
        });
    },
    EditPaymentModal(row) {
      this.LoadData();
      this.SelectedPayment = row;
      this.SelectedPayment.Payed = !!this.SelectedPayment.Payed;
      this.$bvModal.show("EditPayment");
    },
    getPayments() {
      this.isBasy = true;
      axios
        .get("getpayments")
        .then(response => {
          this.Payments = response.data;
          //   for (const key in this.Payments) {
          //     if (
          //       this.Payments[key].quantite < 300 &&
          //       this.Payments[key].quantite >= 100
          //     ) {
          //       this.Payments[key]._cellVariants = { quantite: "warning" };
          //     } else if (this.Payments[key].quantite < 100) {
          //       this.Payments[key]._cellVariants = { quantite: "danger" };
          //     }
          //   }
          this.isBasy = false;
          this.totalRows = this.Payments.length;
        })
        .catch(() => {
          this.$bvToast.toast("Can't Connect to The Server", {
            title: "Error",
            toaster: "toaster b-toaster-top-center",
            variant: "danger",
            solid: true,
            appendtoast: false
          });
        });
    },
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length;
      this.currentPage = 5;
    }
  },
  watch: {},
  filters: {
    ModalTitle: function(value, op) {
      return `${op} The user ${value}`;
    }
  },
  data: function() {
    return {
      NewBanque: "",
      NewFrns: {},
      NewPayment: {
        banque: null,
        payed: false
      },
      overlay: false,
      editoverlay: false,

      filter: null,
      totalRows: 1,
      filterOn: [],

      Payments: [],

      SelectedPayment: {
        amount: null
      },

      Productdetails: {},
      perpage: 5,
      CurrentPage: 1,

      fields: [
        {
          key: "id",
          label: "#ID",
          sortable: true
        },
        {
          key: "amount",
          label: "Amount ",
          sortable: true,
          formatter: value => {
            return numeral(value).format("0,0.00");
          }
        },
        {
          key: "Exp_date",
          label: "Experation Date",
          sortable: true
        },
        {
          key: "banque.name",
          label: "Banque",
          sortable: true
        },
        {
          key: "Payed",
          label: "Payed",
          sortable: true,
          formatter: value => {
            return value ? "Yes" : "Not Yet";
          },
          _cellVariants: value => {
            return value ? "success" : "warning";
          }
        }
      ],
      isBasy: false,
      Banques: []
    };
  },
  mounted() {
    this.getPayments();
    this.LoadData();
    this.$root.$on("bv::modal::hide", (bnEvent, modalId) => {
      if (modalId != "AddBanque") {
        this.getPayments();
        this.ClearForm();
      }
    });
  },
  computed: {
    Articles() {
      var tmp = 0;
      for (var det in this.CommandeDetails) {
        tmp += this.CommandeDetails[det].Quantity;
        console.log(this.CommandeDetails[det].Quantity);
      }
      return tmp;
    },
    rows() {
      return this.Payments.length;
    }
  }
};
</script>

<style scoped></style>
