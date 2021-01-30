<template>
  <div>
    <b-container>
      <b-row cols="1">
        <div class="mb-3 px-2">
          <b-row>
            <b-col>
              <h3>
                Admins <b-badge variant="primary">{{ Admins.length }}</b-badge>
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
                    type="search"
                    id="filterInput"
                    placeholder="Type to Search an Admin"
                  ></b-form-input>
                </b-input-group>
              </b-form-group>
            </b-col>
            <b-col cols="auto" class="ml-auto">
              <b-button
                size="md"
                variant="success"
                @click="AddAdminModal('Admin')"
              >
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
          :items="Admins"
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
                <b-button size="sm" @click="EditAdminModal(row)" variant="info">
                  <b-icon icon="pencil-fill" variant="light"></b-icon>
                </b-button>
                <b-button size="sm" @click="DeleteAdmin(row)" variant="danger">
                  <b-icon icon="trash-fill" variant="light"></b-icon>
                </b-button>
              </b-button-group>
            </div>
          </template>
          <!-- <template #cell(id_type)="data">
              <b-icon
                v-if="data.item.id_type == 1"
                icon="person-fill"
                variant="success"
                font-scale="1.7"
              ></b-icon>
              <b-icon
                v-else
                icon="person-fill"
                variant="info"
                font-scale="1.7"
              ></b-icon>
            </template> -->
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
          variant="danger"
          align="right"
          small
        ></b-pagination>
      </b-row>
      <!-- ADDADMIN Modal -->
      <b-modal
        id="AddAdmin"
        centered
        header-bg-variant="success"
        header-text-variant="light"
        title="Add Admin "
      >
        <b-overlay :show="overlay" rounded="md">
          <b-form ref="form" @submit.stop.prevent="Subbmited()">
            <b-form-group
              label="Name"
              label-for="NameInput"
              :label-cols="formlabelcols"
            >
              <b-form-input id="NameInput" v-model="NewAdmin.name">
              </b-form-input>
            </b-form-group>
            <b-form-group
              label="Email"
              label-for="EmailInput"
              :label-cols="formlabelcols"
            >
              <b-form-input
                lazy
                id="EmailInput"
                v-model="NewAdmin.email"
                type="email"
                required
              >
              </b-form-input>
            </b-form-group>
            <b-form-group
              label="Password"
              label-for="PasswordInput"
              :label-cols="formlabelcols"
            >
              <b-form-input
                id="PasswordInput"
                v-model="NewAdmin.password"
                type="password"
              >
              </b-form-input>
            </b-form-group>
            <b-form-group
              label="Confirm Password"
              label-for="ConfirmPasswordInput"
              :label-cols="formlabelcols"
            >
              <b-form-input
                id="ConfirmPasswordInput"
                v-model="NewAdmin.confirmPassword"
                type="password"
              >
              </b-form-input>
            </b-form-group> </b-form
        ></b-overlay>
        <template #modal-footer>
          <div class="w-100">
            <b-button
              variant="success"
              size="md"
              class="float-right"
              @click="Subbmited()"
              :disabled="addbutondisabled()"
            >
              Submit
            </b-button>
            <b-button
              variant="info"
              size="md"
              class="float-right mx-2"
              @click="ClearForm()"
            >
              Reset
            </b-button>
          </div>
        </template>
      </b-modal>
      <!-- EditAdmin Modal -->
      <b-modal
        id="EditAdmin"
        centered
        header-bg-variant="info"
        header-text-variant="light"
        :title="`Edit  ${SelectedAdmin.name}`"
      >
        <b-overlay :show="editoverlay" rounded="md">
          <b-form ref="form">
            <b-form-group label="Name" label-for="NameInput">
              <b-form-input id="NameInput" v-model="SelectedAdmin.name">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Email" label-for="EmailInput">
              <b-form-input
                id="EmailInput"
                v-model="SelectedAdmin.email"
                type="email"
              >
              </b-form-input>
            </b-form-group>
            <b-form-group label="Current password" label-for="oldpassinput">
              <b-form-input
                id="oldpassinput"
                v-model="SelectedAdmin.currentpass"
                type="password"
              >
              </b-form-input>
            </b-form-group>
            <b-form-group label="New password" label-for="newpassinput">
              <b-form-input
                id="newpassinput"
                v-model="SelectedAdmin.newpass"
                type="password"
              >
              </b-form-input>
            </b-form-group>
            <b-form-group
              label="Confirm new password"
              label-for="newpassconfinput"
            >
              <b-form-input
                id="newpassconfinput"
                v-model="SelectedAdmin.confnewpass"
                type="password"
              >
              </b-form-input>
            </b-form-group>
          </b-form>
        </b-overlay>
        <template #modal-footer>
          <div class="w-100">
            <b-button
              variant="info"
              size="md"
              class="float-right"
              @click="Eddited()"
              :disabled="editbutondisabled()"
            >
              Edit
            </b-button>
            <b-button
              variant="danger"
              size="md"
              class="float-right mx-2"
              @click="closeeditadmin()"
            >
              Close
            </b-button>
          </div>
        </template>
      </b-modal>
    </b-container>
  </div>
</template>

<script>
import axios from "@/axios";
export default {
  name: "Admins",
  components: {},

  methods: {
    editbutondisabled() {
      if (
        this.SelectedAdmin.name != null &&
        this.SelectedAdmin.email != null &&
        this.SelectedAdmin.currentpass != null &&
        this.SelectedAdmin.newpass != null &&
        this.SelectedAdmin.confnewpass != null
      ) {
        return false;
      } else {
        return true;
      }
    },
    addbutondisabled() {
      if (
        this.NewAdmin.name != "" &&
        this.NewAdmin.email != "" &&
        this.NewAdmin.password != "" &&
        this.NewAdmin.confirmPassword != ""
      ) {
        return false;
      } else {
        return true;
      }
    },
    buttonstate() {
      if (
        this.SelectedAdmin.name != "" &&
        this.SelectedAdmin.email != "" &&
        this.SelectedAdmin.currentpass != "" &&
        this.SelectedAdmin.newpass != "" &&
        this.SelectedAdmin.confnewpass != ""
      ) {
        this.editdisabled = !this.editdisabled;
      }
    },
    closeeditadmin() {
      this.ClearForm();
      this.$bvModal.hide("EditAdmin");
      this.getusers();
    },
    Eddited() {
      this.editoverlay = !this.editoverlay;
      axios({
        method: "get",
        url: "UpdateUser",
        params: {
          user: this.SelectedAdmin
        }
      }).then(response => {
        if (response.data.Success) {
          this.$bvToast.toast(response.data.Message, {
            title: "Updated",
            toaster: "toaster b-toaster-top-right",
            variant: "success",
            solid: true,
            appendtoast: true
          });
          this.editoverlay = !this.editoverlay;
          this.ClearForm();
        } else {
          for (var msg in response.data.errosmessage) {
            this.$bvToast.toast(response.data.errosmessage[msg], {
              title: msg,
              toaster: "toaster b-toaster-top-center",
              variant: "danger",
              solid: true,
              appendtoast: true
            });
          }
          this.editoverlay = !this.editoverlay;
        }
      });
    },
    Datafetch(res) {
      this.Admins = [];
      res.forEach(user => {
        if (user.id_type == 1) {
          this.Admins.push(user);
        }
      });
    },
    AddNewAdmin() {
      this.NewAdmin.type = 1;
      axios
        .get("adduser", {
          params: {
            user: this.NewAdmin
          }
        })
        .then(response => {
          if (response.data.Success) {
            this.$bvToast.toast(
              `${response.data.type} ${response.data.Message}`,
              {
                title: "Success",
                toaster: "toaster b-toaster-top-center",
                variant: "success",
                solid: true,
                appendtoast: true
              }
            );
            this.ClearForm();
            this.CurrentPage = Math.floor(this.rows() / this.perpage) + 1;
            this.overlay = false;
          } else {
            for (var msg in response.data.errosmessage) {
              this.$bvToast.toast(response.data.errosmessage[msg], {
                title: msg,
                toaster: "toaster b-toaster-top-center",
                variant: "danger",
                solid: true,
                appendtoast: true
              });
            }
          }
        })
        .catch(err => {
          this.$bvModal.msgBoxOk(err, {
            title: "Response",
            size: "md",
            buttonSize: "md",
            okVariant: "info",
            headerClass: "p-2 border-bottom-0",
            footerClass: "p-2 border-top-0",
            centered: false
          });
          this.overlay = false;
        });
    },
    ClearForm() {
      this.NewAdmin = {
        name: "",
        email: "",
        password: "",
        confirmPasword: ""
      };
      this.SelectedAdmin = {};
      this.overlay = false;
      this.editoverlay = false;
    },
    Subbmited() {
      this.overlay = !this.overlay;
      this.AddNewAdmin();
    },
    AddAdminModal(adminoruser) {
      this.AdminOrUser = adminoruser;
      this.$bvModal.show("AddAdmin");
    },
    EditAdminModal(user) {
      this.SelectedAdmin = user.item;
      console.log(this.SelectedAdmin);
      this.$bvModal.show("EditAdmin");
    },
    DeleteAdmin(user) {
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
            centered: true
          }
        )
        .then(value => {
          if (value) {
            axios({
              method: "get",
              url: "deleteUser",
              params: {
                id: user.item.id
              }
            })
              .then(response => {
                this.$bvToast.toast(response.data.message, {
                  title: "Deleted",
                  toaster: "toaster b-toaster-top-center",
                  variant: "success",
                  solid: false,
                  appendtoast: false,
                  centered: true
                });
                this.getusers();
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
    rows() {
      return this.Admins.length;
    },
    getusers() {
      this.isBasy = true;
      axios
        .get("getusers", {
          params: {
            type: 1
          }
        })
        .then(response => {
          this.Admins = response.data;
          this.totalRows = this.Admins.length;
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
      setTimeout(() => {
        this.isBasy = false;
      }, 1200);
    },
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    }
  },
  watch: {
    "SelectedAdmin.newpass": function() {
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
      NewAdmin: {
        name: "",
        email: "",
        password: "",
        confirmPassword: ""
      },
      filter: null,
      totalRows: 1,
      filterOn: [],
      overlay: false,
      editoverlay: false,
      Admins: [],
      editdisabled: true,
      addisabled: true,
      formlabelcols: 3,
      ModalOperation: null,
      SelectedAdmin: {},
      perpage: 10,
      CurrentPage: 1,
      fields: [
        // {
        //   key: "id_type",
        //   label: "",
        //   sortable: true,
        //   Variant applies to the whole column, including the header and footer
        // },
        {
          key: "name",
          sortable: false
        },

        {
          key: "email",
          sortable: false
        },

        {
          key: "Options",
          label: "",
          sortable: false
        }
      ],
      isBasy: false
    };
  },
  mounted() {
    this.getusers();
    this.$root.$on("bv::modal::hide", () => {
      this.ClearForm();
      this.getusers();
    });
  },
  computed: {}
};
</script>

<style scoped></style>
