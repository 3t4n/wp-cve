var moduleSelector = new Vue({
  el: '#cluevo-module-selector',
  data: function() {
    return {
      modules: [],
      strings: window.lang_strings,
      active: false,
      filter: null,
      search: '',
      itemId: null
    }
  },
  mounted: function() {
    this.loadModules();
  },
  computed: {
    types: function() {
      var list = [];
      if (modules) {
        for (var m of modules) {
          if (!list.includes(m.type_name)) {
            list.push(m.type_name);
          }
        }

      }
      return list;
    },
    results: function() {
      var vm = this;
      return this.modules.filter(function(m) {
        return (m.module_name.toLowerCase().includes(vm.search.toLowerCase()) || vm.search == '') && (m.type_name == vm.filter || vm.filter == null);
      });
    }
  },
  methods: {
    loadModules: function() {
      var vm = this;
      return fetch(cluevoApiSettings.root + 'cluevo/v1/modules', {
        method: "GET",
        headers: {
          "Content-Type": "application/json; charset=utf-8",
          'X-WP-Nonce': cluevoWpApiSettings.nonce
        }
      })
        .then(function (response) {
          return response.json();
        })
        .then(function(data) {
          vm.modules = data;
        })
        .catch(function(error) {
          console.error(error);
        });
    },
    handleModuleClick(data) {
      window.eventBus.$emit('module-change', {
        itemId: this.itemId,
        module: data
      });
    }
  },
  template: `
    <div :class="[ active ? 'active' : '', 'module-selector-overlay', 'modal-mask' ]">
      <div class="module-selector-container modal-wrapper" @click.self="active = false">
        <div class="modal-container">
          <div class="modal-header">
            <h3>{{ strings.insert_module }}</h3>
            <button @click.prevent="active = false"><span class="dashicons dashicons-no-alt"></span></button>
          </div>
          <div class="modal-body">
            <div class="filter-container">
              <ul>
                <li @click="filter = null" :class="[ !filter ? 'active' : '']">{{ strings.filter_tile_all }}</li>
                <li v-for="type in types" :key="type" @click="filter = type" :class="[ filter == type ? 'active' : '']">{{ type }}</li>
              </ul>
              <div class="search-container">
                <label>{{ strings.label_search }} <input type="text" v-model="search" :placeholder="strings.placeholder_modulename" /></label>
                <div class="cluevo-module-result-count">{{ results.length }} {{ strings.module_search_result_count }}</div>
              </div>
            </div>
            <div class="module-table-container">
              <table v-if="results.length > 0" class="cluevo-admin-table">
                <thead>
                  <tr>
                    <th class="right">#</th>
                    <th class="name left">Name</th>
                    <th class="left">Type</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="module in results" :key="module.module_id">
                    <tr @click="handleModuleClick(module)">
                      <td class="right">{{ module.module_id }}</td>
                      <td class="name left">{{ module.module_name }}</td>
                      <td class="left uppercase type">{{ module.type_name }}</td>
                    </tr>
                  </template>
                </tbody>
              </table>
              <div v-else>no results</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  `,
});
