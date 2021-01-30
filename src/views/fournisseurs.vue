<template>
  <div>
    <b-container>
      <b-row cols="1">
        <div class="mb-3 px-2">
          <b-row align-h="between">
            <b-col cols="3">
              <h3>
                Fournisseurs
                <b-badge variant="primary">{{ Fournisseurs.length }}</b-badge>
              </h3>
            </b-col>
            <b-col cols="3">
              <b-pagination
                :per-page="perpage"
                v-model="CurrentPage"
                :total-rows="totalRows"
                align="fill"
                variant="dark"
                small
              ></b-pagination>
            </b-col>
            <b-col cols="auto" class="ml-auto">
              <b-select v-model="perpage">
                <b-select-option value="5">5</b-select-option>
                <b-select-option value="10">10</b-select-option>
                <b-select-option value="20">20</b-select-option>
                <b-select-option value="50">50</b-select-option>
              </b-select>
            </b-col>
            <b-col cols="3" class="">
              <b-form-group class="mb-0">
                <b-input-group size="md">
                  <b-form-input
                    v-model="filter"
                    type="search"
                    id="filterInput"
                    placeholder="Search a Fournisseur"
                  ></b-form-input>
                  <template #prepend>
                    <b-button class="dark" disabled>
                      <b-icon icon="search"></b-icon>
                    </b-button>
                  </template>
                </b-input-group>
              </b-form-group>
            </b-col>
            <b-col cols="auto" class=" px-1">
              <b-button size="md" variant="primary" @click="getFournisseurs()">
                <b-icon icon="arrow-repeat"></b-icon>
              </b-button>
            </b-col>
            <b-col cols="auto" class="">
              <b-button size="md" variant="success" @click="AddFrnsModal()">
                <b-icon
                  icon="person-plus-fill"
                  class="light"
                ></b-icon></b-button
            ></b-col>
          </b-row>
        </div>

        <b-table
          table-variant="light"
          small
          responsive
          :busy="isBasy"
          striped
          head-variant="light"
          outlined
          selectable
          select-mode="single"
          :items="Fournisseurs"
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
                <b-button size="sm" @click="EditFrnsModal(row)" variant="info">
                  <b-icon icon="pencil-fill" variant="light"></b-icon>
                </b-button>
                <b-button size="sm" @click="DeleteFrns(row)" variant="danger">
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
      </b-row>
      <!-- AddFrns Modal -->
      <b-modal
        id="AddFrns"
        centered
        header-bg-variant="success"
        header-text-variant="light"
        title="Add Fournisseur "
      >
        <b-overlay :show="overlay" rounded="md">
          <b-form ref="form" @submit.stop.prevent>
            <b-form-group
              label="Name"
              label-for="NameInput"
              :label-cols="formlabelcols"
            >
              <b-form-input id="NameInput" name="name" v-model="NewFrns.name">
              </b-form-input>
            </b-form-group>
            <b-form-group
              label="Email"
              label-for="EmailInput"
              :label-cols="formlabelcols"
            >
              <b-form-input
                id="EmailInput"
                v-model="NewFrns.email"
                type="email"
                required
              >
              </b-form-input>
            </b-form-group>
            <b-form-group
              label="Address"
              label-for="AddressInput"
              :label-cols="formlabelcols"
            >
              <b-form-textarea
                id="AddressInput"
                name="address"
                v-model="NewFrns.adresse"
              >
              </b-form-textarea>
            </b-form-group>
            <b-form-group
              label="Phone"
              label-for="PhoneInput"
              :label-cols="formlabelcols"
            >
              <b-form-input
                id="PhoneInput"
                v-model="NewFrns.phone"
                name="phone"
              >
              </b-form-input>
            </b-form-group> </b-form
        ></b-overlay>
        <template #modal-footer>
          <div class="w-100">
            <b-button
              variant="success"
              size="sm"
              class="float-right"
              @click="AddNewFrns()"
            >
              Add
            </b-button>
          </div>
        </template>
      </b-modal>
      <!-- EditFrns Modal -->
      <b-modal
        id="EditFrns"
        centered
        header-bg-variant="info"
        header-text-variant="light"
        :title="`Edit  ${SelectedFrns.name}`"
      >
        <b-overlay :show="editoverlay" rounded="lg">
          <b-form ref="form">
            <b-form-group label="Name" label-for="NameInput">
              <b-form-input id="NameInput" v-model="SelectedFrns.name" lazy>
              </b-form-input>
            </b-form-group>
            <b-form-group label="Email" label-for="EmailInput">
              <b-form-input
                id="EmailInput"
                lazy
                v-model="SelectedFrns.email"
                type="email"
              >
              </b-form-input>
            </b-form-group>
            <b-form-group label="Addresse" label-for="AddresseInput">
              <b-form-textarea
                id="AddresseInput"
                v-model="SelectedFrns.adresse"
                lazy
              >
              </b-form-textarea>
            </b-form-group>
            <b-form-group label="Phone" label-for="PhoneInput">
              <b-form-input
                id="PhoneInput"
                v-model="SelectedFrns.telephone"
                lazy
              >
              </b-form-input>
            </b-form-group>
          </b-form>
        </b-overlay>
        <template #modal-footer>
          <div class="w-100">
            <b-button
              variant="primary"
              size="sm"
              class="float-right"
              @click="Eddited()"
            >
              Edit
            </b-button>
          </div>
        </template>
      </b-modal>
      <!-- Profile Modal -->
      <b-modal
        id="Frns_Profile"
        centered
        size="xl"
        hide-footer
        hide-header
        body-class="py-2 px-4 rounded-pill"
        class="rounded-pill"
      >
        <template #modal-header>
          <b-row class="container">
            <b-col cols="auto"> </b-col>
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
                  <b class="font-weight-bold">
                    {{ SelectedProfile.createdAt }} </b
                  ><br />
                </b-col>
              </b-row>
            </b-col>
            <b-col class="ml-auto">
              <b-row>
                <b-col cols="5">
                  <small class="text-justify">Total Products </small><br />
                  <small class="text-justify">Total Transactins</small><br />
                </b-col>
                <b-col cols="7">
                  <b class="font-weight-bold">{{ FrnsProducts.length }}</b
                  ><br />
                  ><br />
                  <b class="font-weight-bold"> {{ SelectedProfile }} </b><br />
                </b-col>
              </b-row>
            </b-col>
          </b-row>
        </template>
        <template #default>
          <b-row>
            <b-col
              cols="4"
              class="text-center align-items-center text-white d-flex flex-column p-2 bodyy"
            >
              <b-avatar size="5rem" class="mb-3">
                <img
                  :src="
                    `https://ui-avatars.com/api/?name=${SelectedProfile.name}&background=random&bold=true&color=fff&size=80%&rounded=true`
                  "
                  alt=""
                />
              </b-avatar>

              <h3>{{ SelectedProfile.name }}</h3>
              <br class="mb-1" />
              <p class="mb-0">{{ SelectedProfile.email }}</p>
              <hr class="mb-1" />
              <code class="mb-0 text-white">{{
                SelectedProfile.telephone
              }}</code>
              <hr class="mb-1" />
              <p class="mb-0">{{ SelectedProfile.adresse }}</p>
              <hr class="mb-1" />
              <p class="mb-0 font-weight-light">
                {{ SelectedProfile.products.length }} Product
              </p>
              <hr class="mb-1" />
            </b-col>
            <b-col cols="8" class="">
              <b-table
                table-variant="light"
                responsive
                small
                :busy="isProductsBusy"
                striped
                head-variant="dark"
                outlined
                selectable
                select-mode="single"
                bordered
                :items="SelectedProfile.products"
                :fields="ProductsFields"
              >
                <template #table-busy>
                  <div class="text-danger text-center my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Loading ...</strong>
                  </div>
                </template>
              </b-table>
            </b-col>
          </b-row>
        </template>
        <template #modal-footer> </template>
      </b-modal>
    </b-container>
  </div>
</template>

<script>
import axios from "@/axios";
export default {
  name: "Fournisseurs",
  components: {},
  methods: {
    Profile(Fournisseur) {
      this.SelectedProfile = Fournisseur.item;
      this.isProductsBusy = true;

      axios({
        method: "get",
        url: "frnsprofile",
        params: {
          id: this.SelectedProfile.code_fournisseur
        }
      })
        .then(response => {
          this.SelectedProfile.products = response.data.products.product;
          this.$bvModal.show("Frns_Profile");
          this.isProductsBusy = false;
        })
        .catch(err => {
          err;
        });
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
    Eddited() {
      this.editoverlay = !this.editoverlay;
      axios({
        method: "get",
        url: "updatefrns",
        params: {
          id: this.SelectedFrns.code_fournisseur,
          name: this.SelectedFrns.name,
          adresse: this.SelectedFrns.adresse,
          email: this.SelectedFrns.email,
          telephone: this.SelectedFrns.telephone
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
          this.getFournisseurs();
        })
        .catch(err => {
          for (const error in err.response.data.errors) {
            this.$bvToast.toast(err.response.data.errors[error], {
              title: error,
              toaster: "toaster b-toaster-top-right",
              variant: "danger",
              solid: true,
              appendtoast: true
            });
          }
        });
      this.editoverlay = false;
    },
    AddNewFrns() {
      this.overlay = true;
      axios({
        method: "get",
        url: "addfrns",
        params: {
          name: this.NewFrns.name,
          adresse: this.NewFrns.adresse,
          email: this.NewFrns.email,
          telephone: this.NewFrns.phone
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
          this.getFournisseurs();
          this.NewFrns = {};
        })
        .catch(err => {
          for (const error in err.response.data.errors) {
            this.$bvToast.toast(err.response.data.errors[error], {
              title: error,
              toaster: "toaster b-toaster-top-right",
              variant: "danger",
              solid: true,
              appendtoast: true
            });
          }
        });
      this.overlay = false;
    },
    ClearForm() {
      //this.getFournisseurs();
      this.NewFrns = {};
      this.overlay = false;
      this.editoverlay = false;
      this.SelectedFrns = {};
    },
    AddFrnsModal() {
      this.$bvModal.show("AddFrns");
    },
    EditFrnsModal(user) {
      this.SelectedFrns = user.item;
      this.$bvModal.show("EditFrns");
    },
    DeleteFrns(user) {
      this.$bvModal
        .msgBoxConfirm(
          "U sure u wanna delete  " + user.item.name.toUpperCase(),
          {
            title: "Confirmation",
            size: "sm",
            headerVariant: "danger",
            buttonSize: "sm",
            okVariant: "danger",
            headerClass: "p-2 border-bottom-0 bg-danger text-white",
            footerClass: "p-2 border-top-0 ",
            centered: true
          }
        )
        .then(value => {
          if (value) {
            axios({
              method: "get",
              url: "deletefrns",
              params: {
                id: user.item.code_fournisseur
              }
            })
              .then(response => {
                this.$bvToast.toast(response.data.message, {
                  title: "Deleted",
                  toaster: "toaster b-toaster-top-center",
                  variant: "success",
                  solid: true,
                  appendtoast: false,
                  centered: true
                });
                this.getFournisseurs();
              })
              .catch(() => {
                this.$bvToast.toast("Can't Delete This Fournisseur", {
                  title: "error",
                  toaster: "toaster b-toaster-top-right",
                  variant: "danger",
                  solid: true,
                  appendtoast: true
                });
              });
          }
        });
    },
    getFournisseurs() {
      this.isBasy = true;
      axios
        .get("getfrnss")
        .then(response => {
          this.Fournisseurs = response.data;
          this.isBasy = false;
          this.totalRows = this.Fournisseurs.length;
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
      this.currentPage = 1;
    }
  },
  watch: {
    "SelectedFrns.newpass": function() {
      this.buttonstate();
    }
  },
  filters: {
    ModalTitle: function(value, op) {
      return `${op} The user ${value}`;
    }
  },
  data: function() {
    return {
      NewFrns: {},
      overlay: false,
      editoverlay: false,

      filter: null,
      totalRows: 1,
      filterOn: [],

      Fournisseurs: [],
      editdisabled: true,
      add_disabled: true,
      formlabelcols: 3,
      ModalOperation: null,

      SelectedFrns: {},
      SelectedProfile: {},
      perpage: 10,
      CurrentPage: 1,

      fields: [
        {
          key: "name",
          sortable: false
        },

        {
          key: "email",
          sortable: false
        },
        {
          key: "telephone",
          label: "Phone",
          sortable: false
        },
        {
          key: "adresse",
          label: "Address",
          sortable: false,
          formatter: value => {
            if (value.length > 30) {
              return value.substring(0, 29) + " ...";
            } else {
              return value;
            }
          }
        },

        {
          key: "Options",
          label: "",
          sortable: false
        }
      ],
      isBasy: false,
      SelectedCommande: {},
      isProductsBusy: false,
      FrnsProducts: {},
      ProductsFields: [
        {
          key: "Reference",
          label: "#REF",
          sortable: false
        },
        {
          key: "category.libelle",
          label: "Category",
          sortable: false
        },
        {
          key: "designation",
          label: "Designation",
          sortable: false
        },
        {
          key: "prix_unitaire",
          label: "Price ",
          sortable: false
        },
        {
          key: "quantite",
          label: "Qty",
          sortable: false
        }
      ],
      isDetailsBusy: false
    };
  },
  mounted() {
    this.getFournisseurs();
    this.$root.$on("bv::modal::hide", () => {
      this.ClearForm();
    });
  },
  computed: {
    Articles() {
      var tmp = 0;
      for (var det in this.CommandeDetails) {
        tmp += this.CommandeDetails[det].Quantity;
        console.log(this.CommandeDetails[det].Quantity);
      }
      console.log(tmp);
      return tmp;
    }
  }
};
</script>

<style scoped>
.bodyy {
  background-image: linear-gradient(
    to top,
    #6a4194,
    #5a317c,
    #492166,
    #391250,
    #29033b
  ) !important;
  border-radius: 4px;
}
</style>
