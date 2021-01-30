<template>
  <div>
    <b-container>
      <b-row cols="1">
        <div class="mb-3 px-0">
          <b-row align-h="end">
            <b-col cols="auto" class="mr-auto">
              <h3>
                Employees
                <b-badge variant="warning">{{ Employees.length }}</b-badge>
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
                <b-select-option :value="Employees.length">All</b-select-option>
              </b-select>
            </b-col>
            <b-col cols="auto" class="px-1">
              <b-form-group class="mb-0">
                <b-input-group size="md">
                  <b-form-input
                    v-model="filter"
                    type="search"
                    id="filterInput"
                    placeholder="Search Employee"
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
              <b-button size="md" variant="primary" @click="getEmployees()">
                <b-icon icon="arrow-repeat"></b-icon>
              </b-button>
            </b-col>
            <b-col cols="auto" class="px-1 pr-3">
              <b-button size="md" variant="success" v-b-modal.AddEmployee>
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
          @row-dblclicked="EditEmployeeModal"
          :items="Employees"
          :fields="fields"
          :per-page="perpage"
          :current-page="CurrentPage"
          :filter="filter"
          @filtered="onFiltered"
          :filter-included-fields="filterOn"
        >
          <template #cell(payments)="row">
            <div>
              <b-button-group size="mx-1">
                <b-button size="sm" @click="Payments(row)" variant="dark">
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
      <!-- AddEmployee Modal -->
      <b-modal
        no-close-on-backdrop
        id="AddEmployee"
        header-bg-variant="success"
        header-text-variant="light"
        body-class="p-1"
        size="md"
      >
        <b-card no-body>
          <b-tabs card v-model="tabs">
            <b-tab title="Employee" active>
              <b-overlay :show="overlay" rounded="md">
                <b-input-group class="mt-3">
                  <b-form-input
                    v-model="NewEmployee.name"
                    placeholder="Employee name"
                  ></b-form-input>
                </b-input-group>
                <b-input-group class="mt-3">
                  <b-form-input
                    v-model="NewEmployee.cin"
                    placeholder="Employee CIN"
                    name="barcode"
                  ></b-form-input>
                </b-input-group>
                <b-input-group class="mt-3">
                  <b-form-input
                    v-model="NewEmployee.phone"
                    placeholder="Employee Phone"
                    name="phone"
                  ></b-form-input>
                </b-input-group>
                <b-input-group class="mt-3">
                  <b-form-input
                    v-model="NewEmployee.salary"
                    placeholder="Employee Salary"
                    type="number"
                  ></b-form-input>
                </b-input-group>
                <b-input-group class="mt-3">
                  <b-form-textarea
                    v-model="NewEmployee.work_place"
                    placeholder="Employee Work Place"
                    name="address"
                  ></b-form-textarea>
                </b-input-group>
              </b-overlay>
            </b-tab>
            <b-tab title="Payment">
              <v-select
                label="name"
                v-model="NewPayment.employee_id"
                :options="Employees"
                :reduce="(name) => name.id"
                autocomplete
                placeholder="Select an Employee"
              ></v-select>
              <b-input-group class="mt-3" append="$">
                <b-form-input
                  v-model="NewPayment.payment"
                  placeholder="Payment Amount"
                  type="number"
                ></b-form-input>
              </b-input-group>
              <b-input-group class="mt-3">
                <b-form-datepicker
                  v-model="NewPayment.date"
                  placeholder="Payment Date"
                  type="date"
                ></b-form-datepicker>
              </b-input-group>
            </b-tab>
          </b-tabs>
        </b-card>

        <template #modal-footer>
          <div class="w-100">
            <b-button
              variant="success"
              size="sm"
              class="float-right"
              @click="Add()"
            >
              Add
            </b-button>
          </div>
        </template>
      </b-modal>
      <!-- add fournss modal  -->
      <b-modal
        id="EmployeeProfile"
        centered
        size="lg"
        hide-footer
        header-bg-variant="info"
        body-class="py-2 px-4 rounded-pill"
        class="rounded-pill"
      >
        <template #default>
          <b-row>
            <b-col
              cols="5"
              class="text-center align-items-center d-flex flex-column p-2 bodyy"
            >
              <b-avatar size="5rem" class="mb-3">
                <img
                  :src="`https://ui-avatars.com/api/?name=${SelectedEmployee.name}&background=random&bold=true&color=fff&size=80%&rounded=true`"
                  alt=""
                />
              </b-avatar>

              <h3>{{ SelectedEmployee.name }}</h3>
              <br class="mb-1" />
              <p class="mb-0">{{ SelectedEmployee.phone }}</p>
              <hr class="mb-1" />
              <p class="mb-0 text-dark font-weight-bold">
                {{ SelectedEmployee.salary }}
              </p>
              <hr class="mb-1" />
              <p class="mb-0">{{ SelectedEmployee.work_place }}</p>
              <hr class="mb-1" />
              <p class="mb-0 font-weight-light">
                {{ SelectedEmployee.paymentsCount }} Payment
              </p>
              <hr class="mb-1" />
            </b-col>
            <b-col cols="7" class="">
              <b-table
                table-variant="light"
                responsive
                :busy="ProfileBusy"
                small
                tbody-tr-class="text-right"
                thead-tr-class="text-center"
                head-variant="light"
                outlined
                fixed
                caption-top
                caption="Payments"
                :items="SelectedEmployee.payments"
                :fields="PaymentsFields"
              >
                <template #table-busy>
                  <div class="text-danger text-center my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    Chargement ...
                  </div>
                </template>
                <template #bottom-row>
                  <b-td
                    class="text-left p-2 bg-secondary font-weight-bold font-italic text-white"
                    >Le Rest :
                  </b-td>
                  <b-td
                    colspan="2"
                    class="p-2 bg-secondary font-weight-bold font-italic text-white"
                  >
                    {{ rest }}</b-td
                  >
                </template>
              </b-table>
            </b-col>
          </b-row>
        </template>
        <template #modal-footer> </template>
      </b-modal>
      <!-- Edit Product Modal -->
      <!-- <b-modal
        id="EditEmployee"
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
                  v-model="SelectedEmployee.amount"
                  placeholder="Employee Amount"
                  type="number"
                ></b-form-input>
              </b-input-group>

              <b-input-group class="mt-3">
                <b-form-datepicker
                  v-model="SelectedEmployee.Exp_date"
                  placeholder="Employee Date"
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
                  v-model="SelectedEmployee.Banque_id"
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
                <b-form-checkbox switch v-model="SelectedEmployee.Payed">
                  <b>{{ SelectedEmployee.Payed ? "" : "Not" }} Payed</b>
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
              @click="EditEmployee()"
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
import numeral from "numeral";

export default {
  name: "Employees",
  components: {},
  methods: {
    Payments(row) {
      this.SelectedEmployee = row.item;
      this.EmployeeProfile();
      this.$bvModal.show("EmployeeProfile");
    },
    EmployeeProfile() {
      this.ProfileBusy = true;
      axios({
        method: "get",
        url: "employeeprofile",
        params: {
          id: this.SelectedEmployee.id,
        },
      })
        .then((response) => {
          this.SelectedEmployee.payments = response.data.Payments;
          this.SelectedEmployee.paymentsCount = this.SelectedEmployee.payments.length;
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
    EditEmployee() {
      axios({
        method: "get",
        url: "updateEmployee",
        params: {
          id: this.SelectedEmployee.id,
          Exp_date: this.SelectedEmployee.Exp_date,
          Banque_id: this.SelectedEmployee.Banque_id,
          Payed: this.SelectedEmployee.Payed,
          amount: this.SelectedEmployee.amount,
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
          this.$bvModal.hide("EditEmployee");
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
    AddEmployee() {
      axios
        .get("addemployee", {
          params: {
            name: this.NewEmployee.name,
            cin: this.NewEmployee.cin,
            salary: this.NewEmployee.salary,
            phone: this.NewEmployee.phone,
            work_place: this.NewEmployee.work_place,
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
          this.NewEmployee = {};
          this.getEmployees();
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
    AddEmployeePayment() {
      axios
        .get("addemployeepayment", {
          params: {
            Payment: this.NewPayment.payment,
            date: this.NewPayment.date,
            employee_id: this.NewPayment.employee_id,
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
          this.NewEmployee = {};
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
        this.AddEmployee();
      } else {
        this.AddEmployeePayment();
      }
    },
    ClearForm() {
      this.NewEmployee = {};
      this.SelectedEmployee = {};
    },
    AddEmployeeModal() {
      this.$bvModal.show("AddEmployee");
    },
    EditEmployeeModal(row) {
      this.SelectedEmployee = row;
      this.$bvModal.show("EditEmployee");
    },
    getEmployees() {
      this.isBasy = true;
      axios
        .get("getemployees")
        .then((response) => {
          this.Employees = response.data;
          //   for (const key in this.Employees) {
          //     if (
          //       this.Employees[key].quantite < 300 &&
          //       this.Employees[key].quantite >= 100
          //     ) {
          //       this.Employees[key]._cellVariants = { quantite: "warning" };
          //     } else if (this.Employees[key].quantite < 100) {
          //       this.Employees[key]._cellVariants = { quantite: "danger" };
          //     }
          //   }
          this.isBasy = false;
          this.totalRows = this.Employees.length;
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
  },
  watch: {},
  filters: {
    ModalTitle: function (value, op) {
      return `${op} The user ${value}`;
    },
  },
  data: function () {
    return {
      NewPayment: {},
      NewEmployee: {},
      overlay: false,
      editoverlay: false,

      filter: null,
      totalRows: 1,
      filterOn: [],

      Employees: [],

      SelectedEmployee: {
        payments: [],
      },

      ProductPayments: {},
      perpage: 10,
      CurrentPage: 1,
      tabs: null,
      fields: [
        {
          key: "id",
          label: "#",
          sortable: true,
        },
        {
          key: "name",
          label: "Name",
          sortable: true,
        },
        {
          key: "cin",
          label: "CIN",
        },
        {
          key: "phone",
          label: "Phone",
        },
        {
          key: "salary",
          label: "Salary",
          sortable: true,
          formatter: (value) => {
            return numeral(value).format("0,0.00");
          },
        },
        {
          key: "work_place",
          label: "Working At",
          formatter: (value) => {
            if (value.length > 30) {
              return value.substring(0, 29) + " ...";
            } else {
              return value;
            }
          },
        },
        {
          key: "payments",
          label: "Payments",
        },
      ],
      PaymentsFields: [
        {
          key: "date",
          label: "Date",
          class: "font-italic",
        },
        {
          key: "credit",
          label: "Credit",
        },
        {
          key: "debut",
          label: "Debut",
        },
      ],
      isBasy: false,
      ProfileBusy: false,
    };
  },
  mounted() {
    this.getEmployees();
    this.$root.$on("bv::modal::hide", () => {
      this.ClearForm();
    });
  },
  computed: {
    rows() {
      return this.Employees.length;
    },
    rest() {
      var c = 0;
      var d = 0;
      this.SelectedEmployee.payments.forEach((element) => {
        c += element.credit;
        d += element.debut;
      });
      return c - d;
    },
  },
};
</script>

<style scoped>
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
</style>
