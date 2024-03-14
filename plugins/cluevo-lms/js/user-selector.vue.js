var cluevo_autocomplete_input_component = Vue.component('cluevo-autocomplete-input', {
  props: {
    name: {
      type: String,
      default: ""
    },
    initialDisplay: {
      type: String,
      default: ""
    },
    initialValue: {
      type: String,
      default: null 
    },
    type: {
      type: String,
      default: ""
    },
    min: {
      type: Number,
      default: 0
    }
  },
  data: function() {
    return {
      results: [],
      value: "",
      display: "",
      timer: null,
      hide: false,
      timer: null
    }
  },
  watch: {
    value(n, o) {
      if (!n) this.value = "";
    }
  },
  mounted: function() {
    this.display = this.initialDisplay;
    this.value = this.initialValue ? this.initialValue : "";
    if (this.type == "") {
      console.error("no autocomplete type set");
      return;
    }
    if (this.display != "") {
      this.search({ target: { value: this.display }});
    } else {
      if (this.min == 0) {
        this.search({ target: { value: "" }});
      }
    }
    this.$on("reset", this.reset);
    this.hide = true;
  },
  methods: {
    search: function(e) {
      if (this.type == "") return;
      if (e.target.value.length < this.min) return;
      this.display = e.target.value;
      this.hide = false;
      clearTimeout(this.timer);
      let vm = this;
      this.timer = setTimeout(async function() {
        let res = await fetch(cluevoWpCommonApiSettings.root + 'cluevo/v1/admin/autocomplete/' + vm.type, {
          method: 'POST',
          body: JSON.stringify({ search: e.target.value }),
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            'X-WP-Nonce': cluevoWpCommonApiSettings.nonce
          }
        })
        if (res.ok) {
          try {
            var data = await res.json();
            vm.results = data;
          } catch (error) {
            console.error("failed to search users", error);
          }
        }
      }, 400);
    },
    select(entry) {
      this.value = entry.id;
      this.display = entry.name;
      this.hide = true;
      clearTimeout(this.timer);
      let vm = this;
      this.timer = setTimeout(function() {
        vm.$emit('selected', entry.id)
      }, 100);
    },
    reset() {
      this.value = "";
      this.hide = true;
      this.display = "";
      this.results = [];
    }
  },
  template: `
    <label class="cluevo-autocomplete-input" @mouseleave="hide = true" @mouseenter="hide = false"> {{ this.title }}
      <input type="text" :name="'cluevo-autocomplete-input-' + name" :value="display" @input="search" />
      <div
        v-if="results && results.length > 0 && !hide"
        class="cluevo-autocomplete-input-results"
      >
        <ul>
          <li v-for="entry of results" @click="select(entry)">{{ entry.name }}</li>
        </ul>
      </div>
      <input type="hidden" :name="name" :value="value"/>
    </label>
  `
});

var cluevo_autocomplete_input = Vue.extend(cluevo_autocomplete_input_component);
