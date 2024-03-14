<template>
  <div class="settings">
    <h2>PARGO GENERAL SETTINGS</h2>
    <div v-if="!loading && !errors.length">
      <codemirror
          v-model="styling"
          :autofocus="true"
          :extensions="extensions"
          :indent-with-tab="true"
          :style="{ height: 'auto' }"
          :tab-size="2"
          placeholder="Pargo Pickup Styling goes here..."
          @change="this.styling =  $event"
      />
      <hr/>
      <div v-if="messages.length">
        <ul class="success">
          <li v-for="message,i in messages" v-bind:key="i">{{ message }}</li>
        </ul>
      </div>
      <button class="button button-primary" type="submit" @click.prevent="saveSettingStyling">Save Styles</button>
      <button class="button button-secondary" type="button" @click.prevent="saveSettingStyling">Reset to Default
        Styles
      </button>
    </div>
    <div v-else-if="errors.length">
      <ul class="errors">
        <li v-for="error,i in errors" v-bind:key="i">{{ error }}</li>
      </ul>
    </div>
    <div v-else>Loading...</div>
  </div>
</template>

<script>
import {Codemirror} from 'vue-codemirror';
import {css} from '@codemirror/lang-css';
import {oneDark} from '@codemirror/theme-one-dark';
import beautify from 'js-beautify';

export default {

  name: 'Settings',
  components: {
    Codemirror,
  },

  data() {
    return {
      loading: false,
      styling: "",
      errors: [],
      messages: [],
    }
  },

  async mounted() {
    this.loading = true;
    await fetch(`${OBJ.api_url}pargo/v1/get-setting-styling`, {
      headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': OBJ.nonce,
      },
    })
        .then(response => response.json())
        .then((response) => {
          if (response.code === 'success') {
            this.styling = beautify.css(response.styling, {});
          } else {
            this.errors.push(response.message);
          }
        })
        .catch(error => {
          console.error(error);
        })
        .finally(() => {
          this.loading = false;
        });
  },

  methods: {
    async saveSettingStyling() {
      this.messages = [];
      this.errors = [];
      if (!this.styling) {
        this.errors.push('At least leave a CSS comment...')
      }

      if (this.errors.length == 0) {
        this.messages.push('Saving Styling...');
        const data = new FormData();
        data.append('pargo_setting_styling', this.styling);

        // save the credentials
        await fetch(`${OBJ.api_url}pargo/v1/store-setting-styling`, {
          method: "POST",
          body: data,
          headers: {
            'X-WP-Nonce': OBJ.nonce,
          },
        })
            .then(response => response.json())
            .then((data) => {
              this.messages = [];
              this.messages.push(data.message);
            })
            .catch(error => {
              console.error(error);
            });
      }
    }
  },

  setup() {
    const extensions = [css(), oneDark];

    return {
      extensions,
    }
  }
}
</script>

<style>
ul.errors {
  color: red;
}

ul.success {
  color: green;
}
</style>
