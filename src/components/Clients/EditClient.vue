<template>
  <b-modal
    id="EditClient"
    centered
    header-bg-variant="info"
    header-text-variant="light"
    :title="`Edit  ${SelectedClient.name}`"
  >
    <b-overlay :show="editoverlay" rounded="md">
      <b-form ref="form">
        <b-form-group label="Name" label-for="NameInput">
          <b-form-input id="NameInput" v-model="SelectedClient.name">
          </b-form-input>
        </b-form-group>
        <b-form-group label="Email" label-for="EmailInput">
          <b-form-input
            id="EmailInput"
            v-model="SelectedClient.email"
            type="email"
          >
          </b-form-input>
        </b-form-group>
        <b-form-group label="Current password" label-for="oldpassinput">
          <b-form-input
            id="oldpassinput"
            v-model="SelectedClient.currentpass"
            type="password"
          >
          </b-form-input>
        </b-form-group>
        <b-form-group label="New password" label-for="newpassinput">
          <b-form-input
            id="newpassinput"
            v-model="SelectedClient.newpass"
            type="password"
          >
          </b-form-input>
        </b-form-group>
        <b-form-group label="Confirm new password" label-for="newpassconfinput">
          <b-form-input
            id="newpassconfinput"
            v-model="SelectedClient.confnewpass"
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
          size="sm"
          class="float-right"
          @click="Eddited()"
        >
          Edit
        </b-button>
        <b-button
          variant="danger"
          size="sm"
          class="float-right mx-2"
          @click="close()"
        >
          Close
        </b-button>
      </div>
    </template>
  </b-modal>
</template>

<script>
import axios from "@/axios";
export default {
  name: "EditClientModal",
  props: {
    SelectedClient: {
      type: Object,
      required: true,
    },
  },
  methods: {
    Eddited() {
      this.editoverlay = !this.editoverlay;
      axios({
        method: "get",
        url: "UpdateUser",
        params: {
          user: this.SelectedClient,
        },
      }).then((response) => {
        if (response.data.Success) {
          this.$bvToast.toast(response.data.Message, {
            title: msg,
            toaster: "toaster b-toaster-top-right",
            variant: "success",
            solid: true,
            appendtoast: true,
          });
          this.closeeditClient();
        } else {
          for (var msg in response.data.errosmessage) {
            this.$bvToast.toast(response.data.errosmessage[msg], {
              title: msg,
              toaster: "toaster b-toaster-top-right",
              variant: "danger",
              solid: true,
              appendtoast: true,
            });
          }
          this.editoverlay = !this.editoverlay;
        }
      });
    },
    ClearForm() {
      this.editoverlay = false;
      this.SelectedClient = {};
    },
    closeeditClient() {
      this.$root.$emit("Refresh");
      this.ClearForm();
      this.$bvModal.hide("EditClient");

      //this.$root.$children[0].$children[2].getusers()
    },
    close() {
      this.$bvModal.hide("EditClient");
    },
  },
  mounted() {
    console.log(this.editoverlay);
  },
  data() {
    return {
      editoverlay: false,
    };
  },
};
</script>

<style>
</style>