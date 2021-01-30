<template>
  <div>
    <b-container>
      <b-row cols="1">
        <div class="mb-3 px-0">
          <b-row align-h="end">
            <b-col cols="auto" class="mr-auto">
              <h3>
                Commandes
                <b-badge variant="warning">{{ Commandes.length }}</b-badge>
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
                <b-select-option :value="Commandes.length">All</b-select-option>
              </b-select>
            </b-col>
            <b-col cols="auto" class="px-1">
              <b-form-group class="mb-0">
                <b-input-group size="md">
                  <b-form-input
                    v-model="filter"
                    type="search"
                    id="filterInput"
                    placeholder="Search Commande"
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
              <b-button size="md" variant="primary" @click="Refresh()">
                <b-icon icon="arrow-repeat"></b-icon>
              </b-button>
            </b-col>
            <b-col cols="auto" class="px-1 pr-3">
              <b-button size="md" variant="success" v-b-modal.AddCommande>
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
          borderless
          striped
          head-variant="light"
          outlined
          selectable
          select-mode="single"
          @row-dblclicked="ShowCommandeModal"
          :items="Commandes"
          :fields="fields"
          :per-page="perpage"
          :current-page="CurrentPage"
          :filter="filter"
          @filtered="onFiltered"
          :filter-included-fields="filterOn"
        >
          <template #table-busy>
            <div class="text-danger text-center my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>Loading ...</strong>
            </div>
          </template>
          <template #cell(options)="data">
            <b-button-group>
              <b-button variant="prpl" size="sm" @click="EditCmndMdl(data)">
                <b-icon icon="pencil"></b-icon>
              </b-button>
              <b-button variant="rca" size="sm" @click="DeleteCommande(data)">
                <b-icon icon="trash"></b-icon>
              </b-button>
            </b-button-group>
          </template>
        </b-table>
        
      </b-row>
      <!-- AddCommande Modal -->
      <b-modal
        scrollable
        no-close-on-backdrop
        id="AddCommande"
        header-bg-variant="success"
        header-text-variant="light"
        ok-only
        hide-footer
        button-size="sm"
        body-class="px-0 vh-100"
        size="xl"
      >
        <b-row class="mx-1">
          <b-col cols="9">
            <b-col class="p-0">
              <b-alert
                variant="danger"
                :show="error.ArticlesHasError"
                fade
                v-html="error.ArticlesErroeHtml"
              >
              </b-alert>
            </b-col>

            <b-table
              small
              :busy="isBasy"
              borderless
              striped
              head-variant="light"
              outlined
              selectable
              select-mode="single"
              :items="NewCommande.Articles"
              :fields="ArticlesFields"
            >
              <template #cell(qty)="data">
                <b-form-input
                  type="number"
                  min="0"
                  :max="
                    data.item.product != null ? data.item.product.quantite : 1
                  "
                  v-model="data.item.qty"
                  :value="data.item.qty == 0 ? 1 : data.item.qty"
                  required
                />
              </template>
              <template #cell(des)="data">
                <cool-select
                  v-model="data.item.product"
                  :items="Products"
                  @select="Selected(data.item.product, 'new')"
                  item-text="designation"
                  placeholder="Select name"
                />
              </template>
              <template #cell(ref)="data">
                <cool-select
                  v-model="data.item.product"
                  :items="Products"
                  @select="Selected(data.item.product, 'new')"
                  item-text="Reference"
                  placeholder="Select name"
                />
              </template>
              <template #cell(price)="data">
                <b-form-input
                  disabled
                  :value="
                    data.item.product ? data.item.product.prix_unitaire : 0
                  "
                />
              </template>
              <template #cell(total)="data">
                <b-form-input
                  disabled
                  :value="
                    data.item.product
                      ? data.item.product.prix_unitaire * data.item.qty
                      : 0
                  "
                />
              </template>
              <template #table-busy>
                <div class="text-danger text-center my-2">
                  <b-spinner class="align-middle"></b-spinner>
                  Chargement ...
                </div>
              </template>
              <template #bottom-row class="mt-2">
                <b-td
                  colspan="3"
                  class="text-left p-2 bg-secondary font-weight-bold font-italic text-white"
                  >{{ Articles }}
                  Articles
                  <small>({{ NewCommande.Articles ? Units : 0 }} Units)</small>
                </b-td>
                <b-td
                  colspan="2"
                  class="p-2 bg-secondary p-2 font-weight-bold font-italic text-white"
                >
                  {{ TotalPrice }} $</b-td
                >
                <b-td
                  colspan="2"
                  class="p-2 bg-secondary p-2 font-weight-bold font-italic text-white"
                >
                  <b-button
                    size="sm"
                    class="ml-auto"
                    variant="light"
                    @click="AddArticle('add')"
                  >
                    <b-icon icon="plus" font-scale="1"></b-icon
                  ></b-button>
                </b-td>
              </template>
              <template #cell(options)="row">
                <b-button
                  size="sm"
                  @click="DeleteArticle('add', row)"
                  variant="dark"
                >
                  <b-icon icon="trash" variant="light"></b-icon>
                </b-button>
              </template>
            </b-table>
          </b-col>
          <b-col cols="3" class="pl-0">
            <b-col class="p-0">
              <b-alert variant="danger" :show="error.InfosHasError" fade>{{
                error.InfosErrMsg
              }}</b-alert>
              <b-form>
                <cool-select
                  v-model="NewCommande.Client"
                  :value="1"
                  :items="Clients"
                  item-text="name"
                  item-value="id"
                  placeholder="Select name"
                  required
                />
                <b-input-group class="mt-3">
                  <b-form-datepicker
                    v-model="NewCommande.date"
                    placeholder="Commande Date"
                    type="date"
                    required
                  ></b-form-datepicker>
                </b-input-group>
                <b-input-group class="mt-3" size="lg">
                  <b-form-checkbox
                    switch
                    v-model="NewCommande.payed"
                    :checked="true"
                  >
                    <b>{{ NewCommande.payed ? "" : "Not" }} Payed</b>
                  </b-form-checkbox>
                </b-input-group>
                <b-input-group class="mt-3" size="lg">
                  <b-form-textarea
                    v-model="NewCommande.note"
                    placeholder="Note"
                    rows="6"
                    lazy
                  >
                  </b-form-textarea>
                </b-input-group>
                <b-input-group class="mt-3" size="lg">
                  <b-button
                    variant="success"
                    size="md"
                    class="py-auto w-100"
                    :disabled="error.ArticlesHasError || Busy.Adding"
                    @click="saveCommande('new')"
                  >
                    {{ !Busy.Adding ? "Save Commande" : "" }}
                    <b-spinner small v-if="Busy.Adding"></b-spinner>
                  </b-button>
                </b-input-group>
              </b-form>
            </b-col>
          </b-col>
          <hr />
        </b-row>
      </b-modal>
      <!-- Update Commande Modal  -->
      <b-modal
        scrollable
        id="EditCommande"
        header-bg-variant="info"
        header-text-variant="light"
        ok-only
        hide-footer
        button-size="sm"
        body-class="px-0 vh-100"
        size="xxl"
      >
        <b-row class="mx-1">
          <b-col cols="9">
            <b-col class="p-0">
              <b-alert
                variant="danger"
                :show="error.ArticlesHasError"
                fade
                v-html="error.ArticlesErroeHtml"
              >
              </b-alert>
            </b-col>

            <b-table
              small
              :busy="isBasy"
              borderless
              striped
              head-variant="light"
              outlined
              selectable
              select-mode="single"
              :items="
                typeof EditableCmnd.Articles != 'undefined'
                  ? EditableCmnd.Articles
                  : null
              "
              :fields="ArticlesFields"
            >
              <template #cell(qty)="data">
                <b-form-input
                  type="number"
                  min="0"
                  v-model="data.item.qty"
                  required
                />
              </template>
              <template #cell(des)="data">
                <cool-select
                  v-model="data.item.code_produit"
                  :value="data.item.code_produit"
                  :items="Products"
                  @select="Selected(data.item.product, 'update')"
                  item-text="designation"
                  item-value="code_produit"
                />
              </template>
              <template #cell(ref)="data">
                <cool-select
                  v-model="data.item.code_produit"
                  :value="data.item.code_produit"
                  :items="Products"
                  @select="Selected(data.item.productn, 'ipdate')"
                  item-text="Reference"
                  item-value="code_produit"
                />
              </template>
              <template #cell(price)="data">
                <b-form-input
                  disabled
                  :value="data.item.code_produit | ProductPrice(Products)"
                />
              </template>
              <template #cell(total)="data">
                <b-form-input
                  disabled
                  :value="data.item | ProductPT(Products)"
                />
              </template>
              <template #table-busy>
                <div class="text-danger text-center my-2">
                  <b-spinner class="align-middle"></b-spinner>
                  Chargement ...
                </div>
              </template>
              <template #bottom-row class="mt-2">
                <b-td
                  colspan="3"
                  class="text-left p-2 bg-secondary font-weight-bold font-italic text-white"
                >
                  Articles
                  <small>({{ EditableCmnd.Articles ? Units : 0 }} Units)</small>
                </b-td>
                <b-td
                  colspan="2"
                  class="p-2 bg-secondary p-2 font-weight-bold font-italic text-white"
                >
                  {{}} $</b-td
                >
                <b-td
                  colspan="2"
                  class="p-2 bg-secondary p-2 font-weight-bold font-italic text-white"
                >
                  <b-button
                    size="sm"
                    class="ml-auto"
                    variant="light"
                    @click="AddArticle('edit')"
                  >
                    <b-icon icon="plus" font-scale="1"></b-icon
                  ></b-button>
                </b-td>
              </template>
              <template #cell(options)="row">
                <b-button
                  size="sm"
                  @click="DeleteArticle('edit', row)"
                  variant="dark"
                >
                  <b-icon icon="trash" variant="light"></b-icon>
                </b-button>
              </template>
            </b-table>
          </b-col>
          <b-col cols="3" class="pl-0">
            <b-col class="p-0">
              <b-alert variant="danger" :show="error.InfosHasError" fade>{{
                error.InfosErrMsg
              }}</b-alert>
              <b-form>
                <cool-select
                  v-model="EditableCmnd.client_id"
                  :value="
                    typeof EditableCmnd.client_id != 'undefined'
                      ? EditableCmnd.client_id
                      : null
                  "
                  :items="Clients"
                  item-text="name"
                  item-value="id"
                  placeholder="Select name"
                  required
                />
                <b-input-group class="mt-3">
                  <b-form-datepicker
                    v-model="EditableCmnd.date"
                    placeholder="Commande Date"
                    type="date"
                    required
                  ></b-form-datepicker>
                </b-input-group>
                <b-input-group class="mt-3" size="lg">
                  <b-form-checkbox
                    switch
                    v-model="EditableCmnd.payed"
                    :checked="true"
                  >
                    <b
                      >{{
                        EditableCmnd.state != "undefined" ? "" : "Not"
                      }}
                      Payed</b
                    >
                  </b-form-checkbox>
                </b-input-group>
                <b-input-group class="mt-3" size="lg">
                  <b-form-textarea
                    v-model="EditableCmnd.note"
                    placeholder="Note"
                    rows="6"
                    lazy
                  >
                  </b-form-textarea>
                </b-input-group>
                <b-input-group
                  class="mt-3 d-flex justify-content-between"
                  size="lg"
                >
                  <b-button
                    variant="success"
                    size="md"
                    class="py-auto w-100"
                    :disabled="error.ArticlesHasError || Busy.Updating"
                    @click="saveCommande('update')"
                  >
                    {{ !Busy.Updating ? "Update Commande" : "" }}
                    <b-spinner small v-if="Busy.Updating"></b-spinner>
                  </b-button>
                </b-input-group>
              </b-form>
            </b-col>
          </b-col>
          <hr />
        </b-row>
      </b-modal>

      <!-- Show Commande modal  -->
      <b-modal
        id="ShowCommandeModal"
        centered
        size="xxl"
        hide-footer
        header-bg-variant="info"
        body-class="py-2 px-4 rounded-pill"
      >
        <template #default>
          <b-row class="">
            <b-col class="pl-4" cols="3">
              <b-row cols="1">
                <div class="cardino">
                  <b-col
                    class="mt-1 mb-3 header pt-2 d-flex justify-content-between"
                  >
                    <h5 class="font-weight-bold text-secondary">Client</h5>
                  </b-col>
                  <b-col class="d-flex justify-content-between">
                    <p class="text-start">Name :</p>
                    <b class="font-italic">
                      {{ SelectedCommande.client.name }}
                    </b>
                  </b-col>
                  <b-col class="d-flex justify-content-between">
                    <p class="text-start">Email :</p>
                    <b class="font-italic">{{
                      SelectedCommande.client.email
                    }}</b>
                  </b-col>
                </div>

                <div class="cardino">
                  <b-col
                    class="mt-1 mb-3 header pt-2 d-flex justify-content-between"
                  >
                    <h5 class="font-weight-bold text-secondary">Commande</h5>
                  </b-col>
                  <b-col class="d-flex justify-content-between">
                    <p class="text-start">N° :</p>
                    <b class="font-italic">{{
                      SelectedCommande.numero_cmnd
                    }}</b>
                  </b-col>
                  <b-col class="d-flex justify-content-between">
                    <p class="text-start">Date :</p>
                    <b class="font-italic">{{
                      SelectedCommande.date_effectue
                    }}</b>
                  </b-col>
                  <b-col class="d-flex justify-content-between">
                    <p class="text-start">State :</p>
                    <b class="font-italic">{{ SelectedCommande.state.etat }}</b>
                  </b-col>
                  <b-col class="d-flex justify-content-between">
                    <b-col class="pl-0" cols="3">
                      <p class="text-start">Note :</p>
                    </b-col>
                    <b
                      :class="
                        SelectedCommande.Message != null
                          ? 'font-italic  '
                          : 'font-italic font-weight-normal text-muted'
                      "
                      >{{
                        SelectedCommande.Message != null
                          ? SelectedCommande.Message
                          : "No Note"
                      }}</b
                    >
                  </b-col>
                </div>
                <div class="cardino">
                  <b-col class="mt-1 mb-3 header pt-2">
                    <h5 class="font-weight-bold text-secondary">Infos</h5>
                  </b-col>
                  <b-col class="d-flex justify-content-between">
                    <p class="text-start">Articles :</p>
                    <b class="font-italic">{{
                      SelectedCommande.details | Length
                    }}</b>
                  </b-col>
                  <b-col class="d-flex justify-content-between">
                    <p class="text-start">Unites :</p>
                    <b class="font-italic">{{
                      SelectedCommande.details | Units
                    }}</b>
                  </b-col>
                  <b-col class="d-flex justify-content-between">
                    <p class="text-start">Total :</p>
                    <b class="font-italic"
                      >{{ SelectedCommande.details | TotalPrice }} $</b
                    >
                  </b-col>
                </div>
                <b-col class="my-2 px-0">
                  <b-button
                    variant="warning"
                    size="sm"
                    class="w-100"
                    @click="Close('ShowCommandeModal')"
                  >
                    Close
                  </b-button>
                </b-col>
              </b-row>
            </b-col>
            <b-col cols="9" class="">
              <b-table
                small
                tbody-tr-class="text-right"
                thead-tr-class="text-center"
                head-variant="light"
                responsive
                select-mode="single"
                selectable
                bordered
                caption-top
                caption="Details"
                :items="SelectedCommande.details"
                :fields="CommandeFields"
              >
                <template #table-busy>
                  <div class="text-danger text-center my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    Chargement ...
                  </div>
                </template>
                <template #cell(PT)="data">
                  {{
                    (
                      data.item.qte_cmnd * data.item.product[0].prix_unitaire
                    ).toFixed(2)
                  }}
                </template>
              </b-table>
            </b-col>
          </b-row>
        </template>
        <template #modal-footer> </template>
      </b-modal>

      <!-- Edit Product Modal -->
      <!-- <b-modal
        id="EditCommande"
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
                  v-model="SelectedCommande.amount"
                  placeholder="Commande Amount"
                  type="number"
                ></b-form-input>
              </b-input-group>

              <b-input-group class="mt-3">
                <b-form-datepicker
                  v-model="SelectedCommande.Exp_date"
                  placeholder="Commande Date"
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
                  v-model="SelectedCommande.Banque_id"
                  :options="banques"
                >
                  <template #first>
                    <b-form-select-option :value="null" disabled
                      >-- Please select a Bank --</b-form-select-option
                    >
                  </template></b-form-select
                >
              </b-input-group>

              <b-input-group class="mt-3" size="lg">
                <b-form-checkbox switch v-model="SelectedCommande.Payed">
                  <b>{{ SelectedCommande.Payed ? "" : "Not" }} Payed</b>
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
              @click="EditCommande()"
            >
              Edit
            </b-button>
          </div>
        </template>
      </b-modal> -->
    </b-container>
  </div>
</template>

<script>
import axios from "@/axios";
import { EventBus } from "@/EventBus";
import { CoolSelect } from "vue-cool-select";

import numeral from "numeral";

export default {
  name: "Commandes",
  components: {
    CoolSelect,
  },
  methods: {
    SearchClient(name) {
      console.log(name);
      console.log(EventBus);
      EventBus.$emit("SearchClient", name);
    },
    DeleteCommande(data) {
      this.$bvModal
        .msgBoxConfirm(`Delete Commande N°: ${data.item.numero_cmnd}`, {
          title: "Delete",
          size: "md",
          headerVariant: "danger",
          buttonSize: "sm",
          okVariant: "danger",
          okTitle: "Yes",
          cancelTitle: "No",
          headerClass: "p-2 border-bottom-0 bg-danger text-white",
          footerClass: "p-2 border-top-0 ",
          centered: true,
        })
        .then((value) => {
          if (value) {
            axios({
              method: "get",
              url: "deletecommande",
              params: {
                cmnd_id: data.item.numero_cmnd,
              },
            })
              .then((response) => {
                this.$bvToast.toast(response.data, {
                  title: "Deleted",
                  toaster: "toaster b-toaster-top-right",
                  variant: "danger",
                  solid: true,
                  appendtoast: true,
                });
                this.Refresh();
              })
              .catch((err) => {
                this.$bvToast.toast(err.response.data.errorMsg, {
                  title: "ERROR",
                  toaster: "toaster b-toaster-top-center",
                  variant: "danger",
                  solid: true,
                  appendtoast: true,
                });
              });
          }
        });
    },
    Close(modal) {
      this.$bvModal.hide(modal);
    },
    EditCmndMdl(data) {
      this.SelectedCommande = data.item;
      this.EditableCmnd.client_id = this.SelectedCommande.client.id;
      this.EditableCmnd.numero_cmnd = this.SelectedCommande.numero_cmnd;
      this.EditableCmnd.note = this.SelectedCommande.Message;
      this.EditableCmnd.date = this.SelectedCommande.date_effectue;
      this.EditableCmnd.payed =
        this.SelectedCommande.state.id_etat == 2 ? false : true;
      for (const pro of this.SelectedCommande.details) {
        this.EditableCmnd.Articles.push({
          qty: pro.qte_cmnd,
          code_produit: pro.product[0].code_produit,
        });
      }
      this.$bvModal.show("EditCommande");
    },
    Selected(item, op) {
      if (op == "new") {
        var reftable = this.NewCommande.Articles.map(function (item) {
          return item.product.Reference;
        });
        var removeIndex = reftable.indexOf(item.Reference);
        if (
          removeIndex != -1 &&
          removeIndex != this.NewCommande.Articles.length - 1
        ) {
          this.error.ArticlesHasError = true;
          this.error.ArticlesErroeHtml = ` <b>${item.Reference} / ${item.designation}</b> Already Selected`;
        } else {
          this.error.ArticlesHasError = false;
        }

        var Unique = [];
        this.NewCommande.Articles.forEach((elm) => {
          if (!Unique.includes(elm.product.Reference)) {
            Unique.push(elm.product.Reference);
            this.error.ArticlesHasError = false;
          } else {
            this.error.ArticlesHasError = true;
            this.error.ArticlesErroeHtml = ` <b>${item.Reference} / ${item.designation}</b> Already Selected`;
          }
        });
      }
      if (op == "update") {
        console.log(op);
      }
    },
    saveCommande(op) {
      if (op == "new") {
        if (this.NewCommande.Articles.length == 0) {
          this.error.ArticlesHasError = true;
          this.error.ArticlesErroeHtml = `<b> Select Some Products </b>  `;
          clearTimeout();
          setTimeout(() => {
            this.error.ArticlesHasError = false;
          }, 2000);
          return;
        }
        for (const elm of this.NewCommande.Articles) {
          if (elm.qty == 0 || !elm.qty) {
            this.error.ArticlesHasError = true;
            this.error.ArticlesErroeHtml = `<b> ${elm.product.Reference} / ${
              elm.product.designation
            }<small>(Row ${
              this.NewCommande.Articles.indexOf(elm) + 1
            })</small></b> Has No Quantity `;
            clearTimeout();
            setTimeout(() => {
              this.error.ArticlesHasError = false;
            }, 2000);
            return;
          }
        }
        if (!this.NewCommande.Client) {
          this.error.InfosHasError = true;
          this.error.InfosErrMsg = "Select Client";
          clearTimeout();
          setTimeout(() => {
            this.error.InfosHasError = false;
          }, 2000);
          return;
        }
        this.Busy.Adding = true;
        this.prepareCommande();
      } else if (op == "update") {
        if (this.EditableCmnd.Articles.length == 0) {
          this.error.ArticlesHasError = true;
          this.error.ArticlesErroeHtml = `<b> Select Some Products </b>  `;
          clearTimeout();
          setTimeout(() => {
            this.error.ArticlesHasError = false;
          }, 2000);
          return;
        }
        for (const elm of this.EditableCmnd.Articles) {
          if (elm.qty == 0) {
            this.error.ArticlesHasError = true;
            this.error.ArticlesErroeHtml = `<b> ${
              this.Products[elm.code_produit - 1].Reference
            } / ${this.Products[elm.code_produit - 1].designation}<small>(Row ${
              this.EditableCmnd.Articles.indexOf(elm) + 1
            })</small></b> Has No Quantity `;
            clearTimeout();
            setTimeout(() => {
              this.error.ArticlesHasError = false;
            }, 2000);
            return;
          }
        }
        this.UpdateCommande(this.EditableCmnd);
      }
    },
    UpdateCommande(cmnd) {
      this.Busy.Updating = true;
      axios({
        method: "get",
        url: "updatecommande",
        params: {
          commande: cmnd,
        },
      })
        .then((response) => {
          this.$bvToast.toast(response.data, {
            title: "Success",
            toaster: "toaster b-toaster-top-right",
            variant: "success",
            solid: true,
            appendtoast: true,
          });
          this.Busy.Updating = false;
          this.getCommandes();
          clearTimeout();
          setTimeout(() => {
            this.$bvModal.hide("EditCommande");
          }, 200);
        })
        .catch((err) => {
          this.$bvToast.toast(err.response.data.errorMsg, {
            title: "Duplication",
            toaster: "toaster b-toaster-top-center",
            variant: "danger",
            solid: true,
            appendtoast: true,
          });
          this.Busy.Updating = false;
        });
    },
    prepareCommande() {
      let FinalCommande = {
        date: new Date().toISOString().split("T")[0],
        payed: false,
        note: null,
      };
      FinalCommande.client_id = this.NewCommande.Client;
      if (this.NewCommande.date) {
        FinalCommande.date = this.NewCommande.date;
      }
      if (this.NewCommande.payed) {
        FinalCommande.payed = this.NewCommande.payed;
      }
      if (this.NewCommande.note) {
        FinalCommande.note = this.NewCommande.note;
      }
      FinalCommande.products = [];
      var product;
      for (const elm of this.NewCommande.Articles) {
        product = {};
        product.qty = elm.qty;
        product.code_produit = elm.product.code_produit;
        FinalCommande.products.push(product);
      }
      this.InsertCommand(FinalCommande);
    },
    InsertCommand(FinalCommande) {
      axios({
        method: "get",
        url: "addnewcommande",
        params: {
          commande: FinalCommande,
        },
      })
        .then((response) => {
          this.$bvToast.toast(response.data, {
            title: "Success",
            toaster: "toaster b-toaster-top-right",
            variant: "success",
            solid: true,
            appendtoast: true,
          });
          this.NewCommande = { Articles: [] };
          this.Refresh();
        })
        .catch((err) => {
          console.log(err);
          this.$bvToast.toast(err.response.data.errorMsg, {
            title: "Duplication",
            toaster: "toaster b-toaster-top-center",
            variant: "danger",
            solid: true,
            appendtoast: true,
          });
        });
      this.Busy.Adding = false;
    },
    AddArticle(op) {
      if (op == "edit") {
        this.EditableCmnd.Articles.push({ code_produit: 1, qty: 1 });
      }
      if (op == "add") {
        this.NewCommande.Articles.push({});
      }
    },
    DeleteArticle(op, row) {
      if (op == "edit") {
        this.EditableCmnd.Articles.splice(row.index, 1);
      }
      if (op == "add") {
        this.NewCommande.Articles.splice(row.index, 1);
      }
    },
    Payments(row) {
      this.SelectedCommande = row.item;
      this.CommandeProfile();
      this.$bvModal.show("CommandeProfile");
    },
    CommandeProfile() {
      this.ProfileBusy = true;
      axios({
        method: "get",
        url: "Commandeprofile",
        params: {
          id: this.SelectedCommande.id,
        },
      })
        .then((response) => {
          this.SelectedCommande.payments = response.data.Payments;
          this.SelectedCommande.paymentsCount = this.SelectedCommande.payments.length;
          this.ProfileBusy = false;
        })
        .catch((err) => {
          err;
        });
    },
    AddBanque() {
      axios({
        method: "get",
        url: "addbanque",
        params: {
          name: this.NewBanque,
        },
      })
        .then((response) => {
          this.$bvToast.toast(response.data.message, {
            title: "success",
            toaster: "toaster b-toaster-top-right",
            variant: "success",
            solid: false,
            appendtoast: true,
          });
        })
        .catch((err) => {
          this.$bvToast.toast(err.response.data.errors["name"], {
            title: "Banque ",
            toaster: "toaster b-toaster-top-right",
            variant: "danger",
            solid: true,
            appendtoast: false,
          });
        });
    },
    EditCommande() {
      axios({
        method: "get",
        url: "updateCommande",
        params: {
          id: this.SelectedCommande.id,
          Exp_date: this.SelectedCommande.Exp_date,
          Banque_id: this.SelectedCommande.Banque_id,
          Payed: this.SelectedCommande.Payed,
          amount: this.SelectedCommande.amount,
        },
      })
        .then((res) => {
          this.$bvToast.toast(res.data.message, {
            title: "success",
            toaster: "toaster b-toaster-top-right",
            variant: "success",
            solid: false,
            appendtoast: true,
          });
          this.$bvModal.hide("EditCommande");
        })
        .catch((err) => {
          for (const error in err.response.data.errors) {
            this.$bvToast.toast(err.response.data.errors[error], {
              title: error,
              toaster: "toaster b-toaster-top-right",
              variant: "danger",
              solid: true,
              appendtoast: false,
            });
          }
        });
    },
    AddCommande() {
      axios
        .get("addCommande", {
          params: {
            name: this.NewCommande.name,
            cin: this.NewCommande.cin,
            salary: this.NewCommande.salary,
            phone: this.NewCommande.phone,
            work_place: this.NewCommande.work_place,
          },
        })
        .then((res) => {
          this.$bvToast.toast(res.data.message, {
            title: "success",
            toaster: "toaster b-toaster-top-right",
            variant: "success",
            solid: false,
            appendtoast: false,
          });
          this.NewCommande = {};
          this.getCommandes();
        })
        .catch((err) => {
          for (const error in err.response.data.errors) {
            this.$bvToast.toast(err.response.data.errors[error], {
              title: error,
              toaster: "toaster b-toaster-top-right",
              variant: "danger",
              solid: true,
              appendtoast: false,
            });
          }
          this.overlay = false;
        });
    },
    AddCommandePayment() {
      axios
        .get("addCommandepayment", {
          params: {
            Payment: this.NewArticle.payment,
            date: this.NewArticle.date,
            Commande_id: this.NewArticle.Commande_id,
          },
        })
        .then((res) => {
          this.$bvToast.toast(res.data.message, {
            title: "success",
            toaster: "toaster b-toaster-top-right",
            variant: "success",
            solid: false,
            appendtoast: false,
          });
          this.NewCommande = {};
        })
        .catch((err) => {
          for (const error in err.response.data.errors) {
            this.$bvToast.toast(err.response.data.errors[error], {
              title: error,
              toaster: "toaster b-toaster-top-right",
              variant: "danger",
              solid: true,
              appendtoast: false,
            });
          }
          this.overlay = false;
        });
    },
    Add() {
      if (this.tabs == 0) {
        this.AddCommande();
      } else {
        this.AddCommandePayment();
      }
    },
    ClearForm() {
      this.NewCommande = {};
      this.SelectedCommande = {};
    },
    AddCommandeModal() {
      this.$bvModal.show("AddCommande");
    },
    ShowCommandeModal(row) {
      this.SelectedCommande = row;
      this.$bvModal.show("ShowCommandeModal");
    },
    Refresh() {
      this.getCommandes();
      this.LoadData();
    },
    getCommandes() {
      this.isBasy = true;
      axios
        .get("getcommandes")
        .then((response) => {
          this.Commandes = response.data;
          for (const key in this.Commandes) {
            if (this.Commandes[key].state.etat == "confirmé") {
              this.Commandes[key]._cellVariants = { "state.etat": "success" };
            } else {
              this.Commandes[key]._cellVariants = { "state.etat": "warning" };
            }
          }
          this.isBasy = false;
          this.totalRows = this.Commandes.length;
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
    },
    LoadData() {
      axios({
        method: "get",
        url: "newcommandeProdutsClients",
      })
        .then((response) => {
          this.Products = response.data.Products;
          this.Clients = response.data.Clients;
        })
        .catch((err) => {
          err;
        });
    },
  },
  watch: {},

  data: function () {
    return {
      Busy: {
        Updating: false,
        Adding: false,
      },
      EditableCmnd: {
        Articles: [],
      },

      error: {
        ArticlesHasError: false,
        ArticlesErroeHtml: "",
        InfosHasError: false,
        InfosErrMsg: "",
      },
      Products: [],
      Clients: [],
      NewArticle: {},
      NewCommande: {
        Articles: [],
      },
      overlay: false,
      editoverlay: false,

      filter: null,
      totalRows: 1,
      filterOn: [],

      Commandes: [],

      SelectedCommande: {},

      CommandeFields: [
        {
          key: "product[0].Reference",
          label: "REF",
          class: "w-10",
        },
        {
          key: "product[0].designation",
          label: "Designation",
          sortable: true,
        },
        {
          key: "product[0].category.libelle",
          label: "category",
        },
        {
          key: "qte_cmnd",
          label: "Quantity",
          class: "w-15",
        },
        {
          key: "product[0].prix_unitaire",
          label: "PU",
          formatter: (value) => {
            return (0 + value).toFixed(2);
          },
          class: "w-15",
        },
        {
          key: "PT",
          label: "PT",
          class: "w-15",
        },
      ],
      perpage: 10,
      CurrentPage: 1,
      tabs: null,
      fields: [
        {
          key: "numero_cmnd",
          label: "#",
          sortable: true,
        },
        {
          key: "date_effectue",
          label: "Date",
          sortable: true,
        },
        {
          key: "client.name",
          label: "Client",
        },
        {
          key: "admin.name",
          label: "By",
        },
        {
          key: "Message",
          label: "Note",
          class: "font-italic text-muted",
        },
        {
          key: "state.etat",
          label: "state",
          sortable: true,
        },
        {
          key: "details.length",
          label: "Units",
          sortable: true,
        },
        // {
        //   key: "salary",
        //   label: "Salary",
        //   sortable: true,
        //   formatter: (value) => {
        //     return numeral(value).format("0,0.00");
        //   },
        // },
        // {
        //   key: "work_place",
        //   label: "Working At",
        //   formatter: (value) => {
        //     if (value.length > 30) {
        //       return value.substring(0, 29) + " ...";
        //     } else {
        //       return value;
        //     }
        //   },
        // },
        {
          key: "options",
          label: "",
        },
      ],
      ArticlesFields: [
        {
          key: "ref",
          label: "#",
          class: "w-15",
        },
        {
          key: "des",
          label: "Designation",
          sortable: true,
        },
        {
          key: "qty",
          label: "Quantity",
          class: "w-15",
        },
        {
          key: "price",
          label: "Price",
          class: "w-15",
        },
        {
          key: "total",
          label: "Total",
          class: "w-15",
        },
        {
          key: "Options",
          label: "",
        },
      ],

      isBasy: false,
      ProfileBusy: false,
    };
  },
  mounted() {
    this.getCommandes();
    this.LoadData();
    this.$root.$on("bv::modal::hide", (bvEvent, modalId) => {
      if (modalId == "EditCommande") {
        this.EditableCmnd = { Articles: [] };
        //   if (CommandeWasEdited) {
        //     this.getCommandes();
        //   }
      }
      if (modalId == "AddCommande") {
        this.NewCommande = { Articles: [] };
      }
      // if (modalId == "ShowCommandeModal") {
      //   this.LoadData();
      // }
    });

    this.SelectedCommande = this.Commandes;
  },
  filters: {
    ProductPrice(val, array) {
      let tmp = array.map((elm) => elm.prix_unitaire);
      return tmp[val - 1];
    },
    ProductPT(val, array) {
      let tmp = array.map((elm) => elm.prix_unitaire);
      return (tmp[val.code_produit - 1] * val.qty).toFixed(2);
    },
    Length: (array) => {
      return array.length;
    },
    Units: (array) => {
      let tmp = 0;
      for (const val of array) {
        tmp += val.qte_cmnd;
      }
      return tmp;
    },

    TotalPrice: (array) => {
      let tmp = 0;
      for (const val of array) {
        tmp += val.qte_cmnd * val.product[0].prix_unitaire;
      }
      return tmp;
    },
  },
  computed: {
    rows() {
      return this.Commandes.length;
    },
    Articles() {
      return this.NewCommande.Articles.length;
    },
    Units() {
      var total = 0;
      if (this.NewCommande.Articles.length > 0) {
        this.NewCommande.Articles.forEach((elm) => {
          total += Number(elm.qty);
        });
      }
      return total;
    },
    TotalPrice() {
      var total = 0;
      if (this.NewCommande.Articles.length > 0) {
        this.NewCommande.Articles.forEach((elm) => {
          if (elm.product) {
            total += elm.qty * elm.product.prix_unitaire;
          }
        });
      }
      return numeral(total).format("0,0.00");
    },
  },
};
</script>

<style scoped lang='scss'>
.bodyy {
  /* background-image: linear-gradient(
    to top,
    #F2F2F2,
    #DBDBDB,
    #EAEAEA
  ) !important; */
  border-radius: 4px;
  border: gainsboro solid 1px;
}
.cardino {
  background-color: #e8ebeea1;
  margin: 10px 0px;
  border-radius: 5px;
  box-shadow: 0px 2px 5px #2e303159;
  .header {
    border-bottom: #6c757d59 0.3rem Solid;
    box-shadow: 0px 2px 15px #6c757d59;
  }
}
</style>
