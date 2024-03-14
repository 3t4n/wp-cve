<script>
import Home from './pages/Home.vue';
import Settings from './pages/Settings.vue';
import PargoInfo from '../components/PargoInfo.vue';
import PargoMap from '../components/PargoMap.vue';

export default {
  name: 'App',
  data () {
    return {
      loading: false,
      page: "",
      logoPath: `${OBJ.asset_url}/images/pargo_logo.png`,
      testingToken: false,
      pargoUser: {
        username: "",
        password: "",
        mapToken: "",
        urlEndPoint: "production",
        usageTrackingEnabled: "true",
        api_token: "",
        supplierId: "",
      },
    }
  },
  async mounted() {
  const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    this.page = urlParams.get('page');
    if (this.page === 'pargo-wp') {
      this.loading = true;
      await fetch(`${OBJ.api_url}pargo/v1/get-credentials`, {
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': OBJ.nonce,
          },
        })
        .then(response => response.json())
        .then((response) => {
            const {data} = response;
            this.pargoUser.username = data.pargo_username;
            this.pargoUser.password = data.pargo_password;
            this.pargoUser.usageTrackingEnabled = data.pargo_usage_tracking_enabled
            this.pargoUser.supplierId = data.supplier_id;
            this.pargoUser.storeCountryCode = data.pargo_store_country_code;
            if (data.pargo_url_endpoint) {
              this.pargoUser.urlEndPoint = data.pargo_url_endpoint;
            } else if (data.pargo_url.length > 0 && data.pargo_url_endpoint.length == 0) {
              if (data.pargo_url.match('staging')) {
                this.pargoUser.urlEndPoint = 'staging';
              }
            } else {
              this.pargoUser.urlEndPoint = "production";
            }
            this.pargoUser.mapToken = data.pargo_map_token;
            this.pargoUser.api_token = data.api_token;
          })
          .catch(error => {
            console.error(error);
          })
          .finally(() => {
            this.loading = false;
          });
    }
  },
    watch: {
        'pargoUser.usageTrackingEnabled': function(val, oldVal) {
            if (val == '') {
                this.pargoUser.usageTrackingEnabled  = 'true'
            }
        }
    },
  methods: {
    testMapToken(pargoUser) {
      if (this.testingToken) {
        this.testingToken = false; // Force a refresh
      }
      this.pargoUser.mapToken = pargoUser.mapToken;
      this.pargoUser.urlEndPoint = pargoUser.urlEndPoint;
      this.testingToken = true;
    }
  },

  components: {
    Home,
    Settings,
    PargoInfo,
    PargoMap
  }
}
</script>

<style>
#pargo-backend-app {
  padding: 1rem;
}
.p-a-container {
  display: flex;
  flex-direction: column;
  flex-wrap: wrap;
  gap: 1rem;
}

.p-a-main {
  flex-basis: 0;
  flex-grow: 2;
  min-inline-size: 35%;
}

.p-a-map {
  display: flex;
  flex-direction: column;
  flex-basis: 0;
  flex-grow: 999;
  min-inline-size: 30%;
}

.p-a-map .close-btn {
  align-self: flex-end;
}

.p-a-map iframe {
  min-height: 80vh;
}

.p-a-aside {
  flex-basis: 80rem;
  flex-grow: 1;
  align-self: flex-end;
}

@media only screen and (min-width: 768px) {
  .p-a-container {

    flex-direction: row;

  }
}
</style>

<template>
  <div id="pargo-backend-app">
    <h1><img :src="logoPath" alt="Pargo" /></h1>


    <div class="p-a-container" v-if="page === 'pargo-wp'">
      <div class="p-a-main">
        <Home v-bind="pargoUser" @test-map-token="testMapToken" v-if="!loading" />
        <div v-else>Loading...</div>
      </div>
      <div class="p-a-map">
        <button v-if="testingToken" class="button button-secondary close-btn" v-on:click="testingToken = false">Close
          Map
        </button>
        <PargoMap v-if="testingToken" :mapToken="this.pargoUser.mapToken" :urlEndPoint="this.pargoUser.urlEndPoint"/>
      </div>
      <aside class="p-a-aside">
        <PargoInfo />
      </aside>
    </div>
    <div class="p-a-container" v-if="page === 'pargo-wp-settings'">
      <div class="p-a-main">
        <Settings />
      </div>
      <aside class="p-a-aside">
        <PargoInfo />
      </aside>
    </div>
  </div>
</template>
