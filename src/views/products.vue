<template>
  <div>
    <b-container>
      <b-row cols="1">
        <div class="mb-3 px-0">
          <b-row align-h="end">
            <b-col cols="auto" class="mr-auto">
              <h3>
                Products
                <b-badge variant="warning">{{ Products.length }}</b-badge>
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
                    placeholder="Search Product"
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
              <b-button size="md" variant="primary" @click="getPorducts()">
                <b-icon icon="arrow-repeat"></b-icon>
              </b-button>
            </b-col>
            <b-col cols="auto" class=" px-1 pr-3">
              <b-button size="md" variant="success" @click="AddProductModal()">
                <b-icon
                  icon="plus-circle-fill"
                  class="light"
                ></b-icon></b-button
            ></b-col>
          </b-row>
        </div>

        <b-table
          small
          responsive
          :busy="isBasy"
          bordered
          head-variant="light"
          outlined
          selectable
          select-mode="single"
          :items="Products"
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
                  @click="EditProductModal(row)"
                  variant="info"
                >
                  <b-icon icon="pencil-fill" variant="light"></b-icon>
                </b-button>
                <b-button
                  size="sm"
                  @click="DeleteProduct(row)"
                  variant="danger"
                >
                  <b-icon icon="trash-fill" variant="light"></b-icon>
                </b-button>
                <b-button size="sm" @click="History(row)" variant="dark">
                  <b-icon icon="clock-history" variant="light"></b-icon>
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
      <!-- AddProduct Modal -->
      <b-modal
        id="AddProduct"
        header-bg-variant="success"
        header-text-variant="light"
        title="Add New Product "
        size="md"
      >
        <b-overlay :show="overlay" rounded="md">
          <b-input-group class="mt-3">
            <template #append>
              <b-button @click="RandomRef('Add')" size="sm" variant="secondary"
                ><b-icon icon="plus-square-fill"></b-icon
              ></b-button>
            </template>
            <b-form-input
              v-model="NewProduct.ref"
              placeholder="Product Reference"
            ></b-form-input>
            <b-form-invalid-feedback id="input-live-feedback">
              Enter at least 3 letters
            </b-form-invalid-feedback>
          </b-input-group>

          <b-input-group class="mt-3">
            <b-form-input
              v-model="NewProduct.dsg"
              placeholder="Product Designation"
            ></b-form-input>
          </b-input-group>

          <b-input-group prepend="Fournisseur :" class="mt-3">
            <template #append>
              <b-button v-b-modal.AddFrns size="sm" variant="secondary"
                ><b-icon icon="plus-square-fill"></b-icon
              ></b-button>
            </template>
            <b-form-select
              v-model="NewProduct.frns"
              :options="fournisseurs"
            ></b-form-select>
          </b-input-group>

          <b-input-group prepend="Category :" class="mt-3">
            <template #append>
              <b-button v-b-modal.AddCat size="sm" variant="secondary"
                ><b-icon icon="plus-square-fill"></b-icon
              ></b-button>
            </template>
            <b-form-select
              v-model="NewProduct.cat"
              :options="categories"
              placeholder="Product Price"
            ></b-form-select>
          </b-input-group>

          <b-input-group append="$" class="mt-3">
            <b-form-input
              v-model="NewProduct.price"
              type="number"
              min="1"
              placeholder="Product Price"
            ></b-form-input>
          </b-input-group>

          <b-input-group append="Unit" class="mt-3">
            <b-form-input
              v-model="NewProduct.qty"
              type="number"
              min="1"
              placeholder="Product Quantity"
            ></b-form-input>
          </b-input-group>
        </b-overlay>
        <template #modal-footer>
          <div class="w-100">
            <b-button
              variant="success"
              size="sm"
              class="float-right"
              @click="Subbmited()"
            >
              Add Product
            </b-button>
            <b-button
              variant="info"
              size="sm"
              class="float-right mx-2"
              @click="ClearForm()"
            >
              Clear
            </b-button>
          </div>
        </template>
      </b-modal>
      <!-- add cat modal  -->
      <b-modal
        id="AddCat"
        header-bg-variant="secondary"
        header-text-variant="light"
        title="Add New Category "
        size="sm"
        centered
      >
        <b-overlay :show="overlay" rounded="md">
          <b-input-group prepend="Libelle" class="mt-3">
            <b-form-input
              v-model="NewCat"
              placeholder="New Category"
            ></b-form-input>
          </b-input-group>
        </b-overlay>
        <template #modal-footer>
          <div class="w-100">
            <b-button
              variant="success"
              size="sm"
              class="float-right"
              @click="addCat()"
            >
              Add Category
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
        id="EditProduct"
        header-bg-variant="info"
        header-text-variant="light"
        title="Edit : "
        size="md"
      >
        <b-overlay :show="editoverlay" rounded="md">
          <b-input-group class="mt-3">
            <template #append>
              <b-button @click="RandomRef('Edit')" size="sm" variant="secondary"
                ><b-icon icon="plus-square-fill"></b-icon
              ></b-button>
            </template>
            <b-form-input
              v-model="SelectedProduct.Reference"
              placeholder="Product Reference"
            ></b-form-input>
            <b-form-invalid-feedback id="input-live-feedback">
              Enter at least 3 letters
            </b-form-invalid-feedback>
          </b-input-group>

          <b-input-group class="mt-3">
            <b-form-input
              v-model="SelectedProduct.designation"
              placeholder="Product Designation"
            ></b-form-input>
          </b-input-group>

          <b-input-group prepend="Fournisseur :" class="mt-3">
            <template #append>
              <b-button v-b-modal.AddFrns size="sm" variant="secondary"
                ><b-icon icon="plus-square-fill"></b-icon
              ></b-button>
            </template>
            <b-form-select
              v-model="SelectedProduct.code_fournisseur"
              :options="fournisseurs"
            ></b-form-select>
          </b-input-group>

          <b-input-group prepend="Category :" class="mt-3">
            <template #append>
              <b-button v-b-modal.AddCat size="sm" variant="secondary"
                ><b-icon icon="plus-square-fill"></b-icon
              ></b-button>
            </template>
            <b-form-select
              v-model="SelectedProduct.code_categorie"
              :options="categories"
              placeholder="Product Price"
            ></b-form-select>
          </b-input-group>

          <b-input-group append=" $ " class="mt-3">
            <b-form-input
              v-model="SelectedProduct.prix_unitaire"
              type="number"
              min="1"
              placeholder="Product Price"
            ></b-form-input>
          </b-input-group>

          <b-input-group append="Unit" class="mt-3">
            <b-form-input
              v-model="SelectedProduct.quantite"
              disabled
              type="number"
              min="1"
              placeholder="Product Quantity"
            ></b-form-input>
          </b-input-group>
        </b-overlay>
        <template #modal-footer>
          <div class="w-100">
            <b-button
              variant="info"
              size="sm"
              class="float-right"
              @click="EditProduct()"
            >
              Edit
            </b-button>
          </div>
        </template>
      </b-modal>
      <!-- History Modal -->
      <b-modal
        id="ProductHistory"
        centered
        size="lg"
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
            <b-col cols="auto" class="">
              <b-avatar size="5rem" class="mx-6">
                <img
                  :src="
                    `https://ui-avatars.com/api/?name=${ProductHistory.name}&background=random&bold=true&color=fff&size=80%&rounded=true`
                  "
                  alt=""
                />
              </b-avatar>
            </b-col>
            <b-col class="ml-auto">
              <h3>{{ ProductHistory.Reference }}</h3>
              <h4>{{ ProductHistory.designation }}</h4>
            </b-col>
          </b-row>
        </template>
        <template #default>
          <b-row>
            <b-col cols="6" class="pr-0">
              <b-col>
                <b-row>
                  <b-col cols="6" class="px-0">
                    <b>REF </b><br />
                    <b>Designation </b><br />
                    <b>Category</b><br />
                    <b>Fournisseur</b><br />
                    <b>Prix Unit</b><br />
                    <b>Stock</b><br />
                  </b-col>
                  <b-col cols="6" class="text-right px-0">
                    <p class="mb-0">{{ ProductHistory.Reference }}</p>
                    <p class="mb-0">{{ ProductHistory.designation }}</p>
                    <p class="mb-0">{{ ProductHistory.category.libelle }}</p>
                    <p class="mb-0">{{ ProductHistory.fournisseur.name }}</p>
                    <p class="mb-0">{{ ProductHistory.prix_unitaire }}</p>
                    <p class="mb-0">{{ ProductHistory.quantite }}</p>
                  </b-col>
                </b-row>
              </b-col>
              <hr />
              <b-col>
                <b-row>
                  <b-col cols="6" class="px-0">
                    <small><b>Total Units Sold </b></small><br />
                    <small><b>Total Transactions </b></small><br />
                    <small><b>AVG Month Selling</b></small
                    ><br />
                    <small><b>Last QTY Sold</b></small
                    ><br />
                    <small><b>Last Client</b></small
                    ><br />
                  </b-col>
                  <b-col cols="6" class="text-right px-0">
                    <small>{{ ProductHistory.prix_unitaire }}</small
                    ><br />
                    <small>{{ ProductHistory.prix_unitaire }}</small
                    ><br />
                    <small>{{ ProductHistory.prix_unitaire }}</small
                    ><br />
                    <small>{{ ProductHistory.prix_unitaire }}</small
                    ><br />
                    <small>{{ ProductHistory.prix_unitaire }}</small
                    ><br />
                  </b-col>
                </b-row>
              </b-col>
            </b-col>

            <b-col cols="6">
              <b-table
                small
                table-variant="light"
                responsive
                :busy="(ProductHistory.busy = false)"
                striped
                head-variant="light "
                outlined
                borderless
                :items="ProductHistory.details"
                :fields="ProductFields"
              >
                <template #cell(Commande)="commande">
                  <b-button
                    size="sm"
                    @click="commande_details(commande)"
                    variant="info"
                  >
                    <b-icon icon="info-circle-fill" variant="light"></b-icon>
                  </b-button>
                </template>
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
      <!-- commnade Modal -->
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
        <template #default>
          <b-table
            small
            table-variant="light"
            responsive
            :busy="isDetailsBusy"
            head-variant="dark"
            outlined
            selectable
            select-mode="single"
            borderless
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
    addFrns() {
      axios({
        method: "get",
        url: "addfrns",
        params: {
          name: this.NewFrns.name,
          adresse: this.NewFrns.adresse,
          email: this.NewFrns.email,
          telephone: this.NewFrns.telephone
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
          this.NewFrns = {};
          this.LoadData();
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
    },
    addCat() {
      axios({
        method: "get",
        url: "addcat",
        params: {
          libelle: this.NewCat
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
          this.NewCat = "";
          this.LoadData();
        })
        .catch(err => {
          this.$bvToast.toast(err.response.data.errors["libelle"], {
            title: "error",
            toaster: "toaster b-toaster-top-right",
            variant: "danger",
            solid: true,
            appendtoast: false
          });
        });
    },
    RandomRef(oper) {
      if (oper == "Add") {
        this.NewProduct.ref = Math.random()
          .toString(36)
          .substring(2, 10)
          .toUpperCase();
      } else {
        this.SelectedProduct.Reference = Math.random()
          .toString(36)
          .substring(2, 10)
          .toUpperCase();
      }
    },
    print() {
      window.print();
    },
    commande_details(commande) {
      this.isDetailsBusy = true;
      this.CommandeDetails = {};
      axios({
        method: "get",
        url: "commandeDetails",
        params: {
          commandeId: commande.item.numero_cmnd
        }
      })
        .then(response => {
          this.CommandeDetails = response.data;
          this.isDetailsBusy = false;
        })
        .catch(err => {
          console.log(err);
        });

      this.$bvModal.show("commande_details");
      console.log(commande);
    },
    History(Product) {
      this.ProductHistory = Product.item;
      this.ProductHistory.busy = true;

      axios({
        method: "get",
        url: "producthistory",
        params: {
          code: this.ProductHistory.code_produit
        }
      })
        .then(response => {
          this.ProductHistory.details = response.data;
          for (const key in this.ProductHistory.details) {
            if (this.ProductHistory.details[key].commande[0].id_etat == 1) {
              this.ProductHistory.details[key]._cellVariants = {
                "commande[0].state.etat": "success"
              };
            } else {
              this.ProductHistory.details[key]._cellVariants = {
                "commande[0].state.etat": "warning"
              };
            }
          }
          this.$bvModal.show("ProductHistory");
        })
        .catch(err => {
          console.log(err);
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
    getClientCommandes() {
      axios({
        method: "get",
        url: "clientcommandes",
        params: {
          id_client: this.ProductHistory.id
        }
      })
        .then(response => {
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
        .catch(err => {
          err;
        });
    },
    btnDesabled() {
      if (
        this.SelectedProduct.name != "" &&
        this.SelectedProduct.email != "" &&
        this.SelectedProduct.currentpass != "" &&
        this.SelectedProduct.newpass != "" &&
        this.SelectedProduct.confnewpass != ""
      ) {
        this.editdisabled = !this.editdisabled;
      }
      if (
        this.NewProduct.ref != "" &&
        this.NewProduct.category != "" &&
        this.NewProduct.designation != "" &&
        this.NewProduct.fournisseur != "" &&
        this.NewProduct.price != "" &&
        this.NewProduct.qty != ""
      ) {
        this.add_disabled = false;
      }
    },
    closeeditClient() {
      this.ClearForm();
      this.$bvModal.hide("EditClient");
      this.getPorducts();
    },
    EditProduct() {
      this.editoverlay = !this.editoverlay;
      axios({
        method: "get",
        url: "updateproduct",
        params: {
          id: this.SelectedProduct.code_produit,
          Reference: this.SelectedProduct.Reference,
          designation: this.SelectedProduct.designation,
          category: this.SelectedProduct.code_categorie,
          fournisseur: this.SelectedProduct.code_fournisseur,
          price: this.SelectedProduct.prix_unitaire
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
          this.editoverlay = false;
          this.$bvModal.hide("EditProduct");
          this.getPorducts();
          this.CurrentPage = Math.floor(this.rows() / this.perpage) + 1;
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

          this.editoverlay = false;
        });
    },
    AddNewProduct() {
      axios
        .get("addproduct", {
          params: {
            Reference: this.NewProduct.ref,
            designation: this.NewProduct.dsg,
            category: this.NewProduct.cat,
            fournisseur: this.NewProduct.frns,
            price: this.NewProduct.price,
            quantity: this.NewProduct.qty
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
          this.ClearForm();
          this.getPorducts();
          this.overlay = false;
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
      this.NewProduct = {
        ref: "",
        dsg: "",
        cat: "",
        frns: "",
        price: "",
        qty: ""
      };
      this.add_disabled = true;
      this.overlay = false;
      this.editoverlay = false;
      this.SelectedProduct = {};
    },
    Subbmited() {
      this.overlay = !this.overlay;
      this.NewProduct.type = 2;
      this.AddNewProduct();
    },
    AddProductModal() {
      this.LoadData();
      this.$bvModal.show("AddProduct");
    },
    LoadData() {
      axios({
        method: "get",
        url: "catsfrnss"
      })
        .then(response => {
          console.log(response);
          this.fournisseurs = response.data.fournisseurs;
          this.categories = response.data.categories;
        })
        .catch(err => {
          console.log(err);
        });
    },
    EditProductModal(product) {
      this.SelectedProduct = product.item;
      this.LoadData();
      this.$bvModal.show("EditProduct");
    },
    DeleteProduct(Product) {
      this.$bvModal
        .msgBoxConfirm(
          "U sure u wanna delete \n " + Product.item.designation.toUpperCase(),
          {
            title: "Confirmation",
            size: "sm",
            headerVariant: "danger",
            buttonSize: "sm",
            okVariant: "danger",
            headerClass: "p-2 border-bottom-0 bg-danger text-white text-center",
            footerClass: "p-2 border-top-0 ",
            centered: true
          }
        )
        .then(value => {
          if (value) {
            axios({
              method: "get",
              url: "deleteproduct",
              params: {
                id: Product.item.code_produit
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
                this.getPorducts();
              })
              .catch(err => {
                console.log(err);
              });
          }
        })
        .catch(err => {
          // An error occurred
          err;
        });
    },
    getPorducts() {
      this.isBasy = true;
      axios
        .get("products", {})
        .then(response => {
          this.Products = response.data;
          for (const key in this.Products) {
            if (
              this.Products[key].quantite < 300 &&
              this.Products[key].quantite >= 100
            ) {
              this.Products[key]._cellVariants = { quantite: "warning" };
            } else if (this.Products[key].quantite < 100) {
              this.Products[key]._cellVariants = { quantite: "danger" };
            }
          }
          this.isBasy = false;
          this.totalRows = this.Products.length;
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
      NewCat: "",
      NewFrns: {},
      NewProduct: {
        ref: "",
        dsg: "",
        cat: "",
        frns: "",
        price: "",
        qty: ""
      },
      overlay: false,
      editoverlay: false,

      filter: null,
      totalRows: 1,
      filterOn: [],

      Products: [],
      editdisabled: true,
      add_disabled: false,
      formlabelcols: 3,
      ModalOperation: null,

      SelectedProduct: {},
      ProductFields: [
        {
          key: "numero_cmnd",
          label: "N°",
          sortable: false
        },
        {
          key: "commande[0].date_effectue",
          label: "Date",
          sortable: false
        },
        {
          key: "qte_cmnd",
          label: "Quantity",
          sortable: false
        },
        {
          key: "commande[0].state.etat",
          label: "State"
        },
        {
          key: "Commande",
          label: ""
        }
      ],
      ProductHistory: {},
      perpage: 18,
      CurrentPage: 1,

      fields: [
        {
          key: "Reference",
          label: "REF",
          sortable: false,
          class: "muted"
        },
        {
          key: "designation",
          Label: "Desegnation",
          sortable: true
        },
        {
          key: "category.libelle",
          label: "Category",
          sortable: true
        },
        {
          key: "fournisseur.name",
          label: "Fournisseur",
          sortable: true
        },
        {
          key: "prix_unitaire",
          label: "Prix Unitaire",
          sortable: true,
          formatter: value => {
            return (0 + value).toFixed(2);
          },
          class: "text-right"
        },
        {
          key: "quantite",
          label: "Stock (Qte)",
          sortable: true,
          class: "text-right"
        },
        {
          key: "Options",
          label: "",
          sortable: false,
          class: "text-center"
        }
      ],
      isBasy: false,
      SelectedCommande: {},
      iscommandesBasy: false,
      ClientCommandes: {},
      CommandeDetails: [],
      CommandeDetailsFields: [
        {
          key: "Reference",
          sortable: false
        },
        {
          key: "Desegnation",
          sortable: false
        },
        {
          key: "Price",
          label: "P.U",
          sortable: false,
          formatter: value => {
            return (0 + value).toFixed(2);
          }
        },
        {
          key: "Quantity",
          label: "QTY",
          sortable: false
        },

        {
          key: "prix_total",
          label: "P.T",
          sortable: false
        },
        {
          key: "Category",
          sortable: false
        },
        {
          key: "Fournisseur",
          sortable: false
        }
      ],

      fournisseurs: {},
      categories: {}
    };
  },
  mounted() {
    this.getPorducts();
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
      return tmp;
    },
    rows() {
      return this.Products.length;
    }
  }
};
</script>

<style scoped></style>
