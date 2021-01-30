<template>
  <b-modal
    id="AddClient"
    header-bg-variant="success"
    header-text-variant="light"
    title="Add Client "
  >
    <b-overlay :show="overlay" rounded="md">
      <b-form ref="form" @submit.stop.prevent="Subbmited()">
        <b-form-group
          label="Name"
          label-for="NameInput"
          :label-cols="formlabelcols"
        >
          <b-form-input id="NameInput" v-model="NewClient.name" lazy>
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
            v-model="NewClient.email"
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
            v-model="NewClient.password"
            type="password"
            lazy
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
            v-model="NewClient.confirmPassword"
            type="password"
          >
          </b-form-input>
        </b-form-group> </b-form
    ></b-overlay>
    <template #modal-footer>
      <div class="w-100">
        <b-button
          variant="primary"
          size="sm"
          class="float-right"
          @click="Subbmited()"
        >
          Submit
        </b-button>
        <b-button
          variant="danger"
          size="sm"
          class="float-right mx-2"
          @click="close()"
        >
          Cancel
        </b-button>
      </div>
    </template>
  </b-modal>
</template>

<script>
import axios from "@/axios";
export default {
  name: "AddClientModal",
  props: {},

  data: function () {
    return {
      NewClient: {
        name: "",
        email: "",
        password: "",
        confirmPassword: "",
        type: 2,
      },
      overlay: false,
      formlabelcols: 3,
    };
  },
  methods: {
    Subbmited() {
      this.overlay = !this.overlay;
      this.AddNewClient();
    },
    AddNewClient() {
      axios
        .get("adduser", {
          params: {
            user: this.NewClient,
          },
        })
        .then((res) => {
          console.log(res.data);
          this.$bvToast.toast(res.data.type + res.data.Message, {
            title: "Success",
            toaster: "toaster b-toaster-top-right",
            variant: "success",
            solid: true,
            appendtoast: true,
          });
          this.ClearForm();
          this.Refresh();
          this.overlay = false;
        })
        .catch((err) => {
          for (const elm in err.response.data.errosmessage) {
            this.$bvToast.toast(err.response.data.errosmessage[elm], {
              title: elm,
              toaster: "toaster b-toaster-top-right",
              variant: "danger",
              solid: true,
              appendtoast: false,
            });
          }

          this.overlay = false;
        });
    },
    ClearForm() {
      this.NewClient = {
        name: "",
        email: "",
        password: "",
        confirmPassword: "",
        type: 2,
      };
      this.overlay = false;
    },
    Refresh() {
      this.$root.$emit("Refresh");
    },
    close() {
      this.ClearForm();
      this.$bvModal.hide("AddClient");
    },
  },
  mounted() {},
};
</script>

<style>
</style>