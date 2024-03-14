<template>
  <div class="home">
    <h2>PARGO CREDENTIALS</h2>
    <p>When you are ready to move to production and start shipping real orders, select ‘no’, and ensure you update your
      username, password and map token with production credentials from Pargo.</p>
    <form>
      <div class="pargo_form_group pargo_form_group--testing">
        <label class="control-label" for="pargo_using_test">
          Are you using Test / Staging Credentials?
        </label>
        <label for="pargo_using_test_yes">
          <input
              id="pargo_using_test_yes"
              :checked="pargoUser.urlEndPoint === 'staging'"
              aria-describedby="pargo_api_urlHelpBlock"
              name="pargo_using_test"
              placeholder="Pargo API Url"
              type="radio"
              value="staging"
              @input="event => {pargoUser.urlEndPoint = event.target.value; pargoUser.mapToken = '';}"
          >
          Yes
        </label>
        <label for="pargo_using_test_no">
          <input
              id="pargo_using_test_no"
              :checked="pargoUser.urlEndPoint === 'production' "
              aria-describedby="pargo_api_urlHelpBlock"
              name="pargo_using_test"
              placeholder="Pargo API Url"
              type="radio"
              value="production"
              @input="event => {pargoUser.urlEndPoint = event.target.value; pargoUser.mapToken = '';}"
          >
          No
        </label>
        <p id="pargo_using_testHelpBlock" class="help-block">Select if you are using testing / staging credentials for
          debugging purposes.</p>
      </div>
      <div class="pargo_form_group pargo_form_group--username">
        <div>
          <label class="control-label" for="pargo_username">Pargo Username</label>
          <input
              id="pargo_username"
              :value="pargoUser.username"
              aria-describedby="pargo_usernameHelpBlock"
              autocomplete="off"
              name="pargo_username"
              placeholder="Pargo Username"
              required="required"
              type="text"
              @input="event => pargoUser.username = event.target.value"
          >
          <p id="pargo_usernameHelpBlock" class="help-block">Please enter your Pargo Account Username</p>
        </div>
        <a v-if="!pargoUser.username && pargoUser.storeCountryCode === 'ZA' && pargoUser.urlEndPoint === 'production'" class="button button-pargo"
           href="https://mypargo.pargo.co.za/mypargo/auth/sign-up?source=woocomm" target="_blank">Sign Up</a>
      </div>
      <div class="pargo_form_group pargo_form_group--password">
        <label class="control-label" for="pargo_password">Pargo Password</label>
        <input
            id="pargo_password"
            :value="pargoUser.password"
            aria-describedby="pargo_passwordHelpBlock"
            autocomplete="off"
            name="pargo_password"
            placeholder="Pargo Password"
            required="required"
            type="password"
            @input="event => pargoUser.password = event.target.value"
        >
        <p id="pargo_passwordHelpBlock" class="help-block">Please enter your Pargo Account Password</p>
      </div>

      <div class="pargo_form_group pargo_form_group--map_token">
        <label class="control-label" for="pargo_map_token_field">Pargo Map Token</label>
        <input
            id="pargo_map_token_field"
            :value="pargoUser.mapToken"
            aria-describedby="pargo_map_tokenHelpBlock"
            autocomplete="off"
            name="pargo_map_token"
            placeholder="Pargo Map Token"
            type="text"
            @input="event => pargoUser.mapToken = event.target.value"
        >
        <p id="pargo_map_tokenHelpBlock" class="help-block">Please enter your Pargo Account Map Token, if you do not
          have a token leave this field empty and the default token will be used.</p>
      </div>
      <button class="button button-secondary" type="button" @click="$emit('test-map-token', pargoUser)">Test Your Map
        Token
      </button>

      <br>
      <div class="pargo_form_group pargo_form_group--analytics">
        <label for="pargo_using_analytics" class="control-label">Usage Insights</label>

        <label for="pargo_using_analytics" class="control-label">
          <input
              id="pargo_using_analytics"
              name="pargo_using_analytics"
              type="checkbox"
              aria-describedby="pargo_using_analyticsHelpBlock"
              :checked="pargoUser.usageTrackingEnabled === 'true'"
              @input="event => { pargoUser.usageTrackingEnabled = event.target.checked; }"
          >
          Enable Tracking
        </label>

        <p id="pargo_using_analyticsHelpBlock" class="help-block">
          Gathering usage data allows us to make the Pargo Pickup Points Plugin better — your store will be considered as we evaluate new features, judge the quality of an update, or determine if an improvement makes sense.
          To opt out, uncheck this box.
        </p>
      </div>
      <div class="pargo_form_group pargo_form_group--api_token">
        <label for="pargo_api_token" class="control-label">Pargo order status update API token</label>
        <textarea id="pargo_api_token" readOnly rows="10" @click="copyAPIToken">{{pargoUser.api_token}}</textarea>
        <button class="button button-pargo" type="button" @click="generateNewTokenRequest">Generate New Token</button>
        <p class="help-block">
          Click on the text above to copy and provide this to your Pargo Support Representative to enable status updates.
        </p>
      </div>

      <div class="pargo_form_group pargo_form_group--multistore">
        <div>
          <label class="control-label" for="pargo_supplier_id">Multiple stores setup with Pargo<br />(leave blank if not provided)</label>
          <input
              id="pargo_supplier_id"
              :value="pargoUser.supplierId"
              aria-describedby="pargo_supplier_idHelpBlock"
              autocomplete="off"
              name="pargo_supplier_id"
              placeholder="supXXXX"
              required="required"
              type="text"
              @input="event => pargoUser.supplierId = event.target.value"
          >
          <p id="pargo_supplier_idHelpBlock" class="help-block">Supplier ID provided by Pargo.</p>
        </div>
      </div>

      <div v-if="errors.length">
        <ul class="errors">
          <li v-bind:key="i" v-for="(error,i) in errors">{{ error }}</li>
        </ul>
      </div>
      <div v-if="messages.length">
        <ul class="success">
          <li v-bind:key="i" v-for="(message,i) in messages">{{ message }}</li>
        </ul>
      </div>
      <hr />
      <button class="button button-primary" type="submit" @click.prevent="saveVerifyCredentials">Save and Verify Credentials</button>

    </form>

  </div>
</template>

<script>
export default {

  name: 'Home',
  props: {
    urlEndPoint: String,
    username: String,
    password: String,
    mapToken: String,
    usageTrackingEnabled: String,
    api_token: String,
    supplierId: String,
    storeCountryCode: String
  },
  data () {
    return {
      errors: [],
      messages: [],
      pargoUser: {},
      mapUrl: ""
    }
  },

  async mounted() {
    this.pargoUser = {
      urlEndPoint: this.urlEndPoint,
      username: this.username,
      password: this.password,
      mapToken: this.mapToken,
      usageTrackingEnabled: this.usageTrackingEnabled,
      api_token: this.api_token,
      supplierId: this.supplierId,
      storeCountryCode: this.storeCountryCode
    }
  },

  methods: {
    async generateNewTokenRequest() {
      this.messages = [];
      this.errors = [];

      if (confirm('You will need to notify Pargo of your new token, continue?')) {
          await fetch(`${OBJ.api_url}pargo/v1/regenerate-token`, {
            method: "POST",
            headers: {
              'X-WP-Nonce': OBJ.nonce,
            },
          })
              .then(async response => {
                if (!response.ok) {
                  let errorResponse;
                  await response.json().then((json) => errorResponse = json);
                  if (errorResponse.message) {
                    throw Error(errorResponse.message);
                  } else {
                    throw Error(response.statusText);
                  }
                }
                return response.json();
              })
              .then(async (response) => {
                if (response.data && response.data.status) {
                  if (response.data.status === 500) {
                    this.errors.push(response.message);
                  }
                }

                if (response.api_token) {
                  this.messages.push(response.message);
                  if (response.api_token) {
                    this.pargoUser.api_token = response.api_token;
                    await this.copyAPIToken();
                  }
                }
              })
              .catch((error) => {
                if (error.toString().includes('TypeError')) {
                  this.errors.push('Please try again or contact support@pargo.co.za');
                } else {
                  this.errors.push(error);
                }
                console.error(error);
              });
      }
    },
    async copyAPIToken() {
      this.messages = [];
      await navigator.clipboard.writeText(this.pargoUser.api_token).then(() => {
        this.messages.push('Copied Webhook Auth token');
      }).catch((error) => {
        this.errors.push('Failed to copy Webhook Auth token to clipboard, copy manually or contact support@pargo.co.za for assistance.');
      });
    },
    async saveVerifyCredentials() {
      this.messages = [];
      this.errors = [];
      if (!this.pargoUser.username) {
        this.errors.push('Your Pargo username is required.')
      }

      if (!this.pargoUser.password) {
        this.errors.push('Your Pargo password is required.')
      }

      if (!this.pargoUser.urlEndPoint) {
        this.errors.push('The Pargo API url is required.')
      }

      if (this.pargoUser.supplierId.length > 0 && !this.pargoUser.supplierId.startsWith('sup')) {
        this.errors.push('The Pargo Supplier ID must start with "sup".')
      }

      if (this.errors.length === 0) {
        this.messages.push('Step 1. Saving Credentials...');
        const data = new FormData();
        data.append('pargo_username', this.pargoUser.username);
        data.append('pargo_password', this.pargoUser.password);
        data.append('pargo_url_endpoint', this.pargoUser.urlEndPoint);
        data.append('pargo_map_token', this.pargoUser.mapToken);
        data.append('pargo_usage_tracking_enabled', this.pargoUser.usageTrackingEnabled);
        data.append('pargo_supplier_id', this.pargoUser.supplierId);

        await fetch(`${OBJ.api_url}pargo/v1/store-credentials`, {
          method: "POST",
          body: data,
          headers: {
            'X-WP-Nonce': OBJ.nonce,
          },
        })
        .then(response => response.json())
        .then(async (data) => {
          this.messages.push(data.message);
          this.messages.push('Step 2. Verifying Credentials...');
          await fetch(`${OBJ.api_url}pargo/v1/verify-credentials`, {
            method: "POST",
            headers: {
              'X-WP-Nonce': OBJ.nonce,
            },
          })
          .then(response => response.json())
          .then(data => {
            if (data.code === 'success') {
              this.messages.push(data.message);
            } else {
              this.errors.push(data.message);
            }
          })
        })
        .catch(error => {
          console.error(error);
        });
      }
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
form {
  padding: 1rem;
}

form label {
  display: block;
  font-weight: bolder;
  padding: 0.4rem 0rem;
}

form .help-block {
  font-style: italic;
}

form textarea {
  background-color: white;
  display: block;
}

ul.errors {
  color: red;
}
ul.success {
  color: green;
}

form div.pargo_form_group.pargo_form_group--testing {
  border: thin solid #333;
  padding: 1rem;
  background-color: white;
  border-radius: 0.4rem;
}
form div.pargo_form_group.pargo_form_group--username {
  width: 100%;
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 2rem;
}
button.button {
  margin-top: 0.5rem;
  margin-bottom: 0.5rem;
}
.button.button-pargo {
  background-color: #FFF200;
  box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.195772);
  border-radius: 4px;
  border: none;
  color: #000;
  padding: 0.2rem 2rem;
  text-transform: uppercase;
  font-weight: bold;
}
.button.button-pargo:hover {
  background-color: #DDD465;
  box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.5);
}
</style>
