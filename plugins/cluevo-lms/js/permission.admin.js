var bus=new Vue({});

Vue.component('cluevo-spinner', {
  template: `
    <div class="cluevo-spinner">
      <div class="segment pink"></div>
      <div class="segment purple"></div>
      <div class="segment teal"></div>
    </div>
  `
});

Vue.component('group-selector', {
  props: [ "groups", "value" ],
  template: `
    <label> {{ this.$lang_strings.group_selector_label }}
      <select name="group-selector" v-bind:value="value" @change="$emit('input', $event.target.value)">
        <option :value="null"></option>
        <option v-for="group in groups" :value="group.group_id" v-html="group.group_name"></option>
      </select>
    </label>
  `
});

Vue.component('user-selector', {
  props: [ "users", "value" ],
  template: `
    <label> {{ this.$lang_strings.user_selector_label }}
      <select name="user-selector" v-bind:value="value" @change="$emit('input', $event.target.value)">
        <option :value="null"></option>
        <option v-for="user in users" :value="user.user_id" v-html="user.display_name"></option>
      </select>
    </label>
  `
});

Vue.component('access-level', {
  props: [ "levels", "value" ],
  methods: {
    handleInput: function(value) {
      if (value != this.value) {
        this.$emit('input', value);
        this.$emit('access-level-changed', value);
      }
    }
  },
  template: `
    <div class="access-level-container">
      <input v-bind:value="value">
        <label v-for="level in levels" :class="{ 'active': level == value }" @click="handleInput(level)">
          <span v-if="level == 0" class="dashicons dashicons-lock"></span>
          <span v-if="level == 1" class="dashicons dashicons-visibility"></span>
          <span v-if="level == 2" class="dashicons dashicons-unlock"></span>
        </label>
  </div>`
});

Vue.component('lms-perm-item', {
  props: [ "item", "parent_access_level" ],
  methods: {
    handle_level_change: function(event) {
      this.item.access_level = event;
      this.$emit('perm-changed', this.item);
      bus.$emit('perm-changed', this.item);
    },
    handle_child_level_change: function(item) {
      if (this.item.access_level < 2 && item.access_level > 0) {
        this.handle_level_change(2);
      }
    }
  },
  watch: {
    parent_access_level: function(newLevel, oldLevel) {
      if (newLevel < 2) {
        this.handle_level_change(0);
        this.item.children = this.item.children.map(function(c, i) {
          c.access_level = 0;
          return c;
        });
      }
    }
  },
  template: `
    <div class="lms-perm-item" :class="'level-' + item.level"">
      <div class="perm-title">
        <access-level :levels="[0, 1, 2]" v-model="item.access_level" @access-level-changed="handle_level_change" />
        <div class="perm-item-name">{{ item.name }}</div>
        <div class="module-warning" v-if="item.access_level < 2 && item.type == 'module'"><span class="dashicons dashicons-warning"></span> {{ this.$lang_strings.module_not_executable_warning }}</div>
      </div>
      <div class="perm-children">
        <lms-perm-item v-if="item.children.length > 0" v-for="child in item.children" :item="child" :key="child.item_id" @perm-changed="handle_child_level_change" :parent_access_level="item.access_level"></lms-perm-item>
        <slot></slot>
      </div>
    </div>
  `
});

Vue.component('lms-access-preview', {
  props: [ "items" ],
  template: `
    <div class="access-preview-container">
    </div>
  `
});

Vue.component('lms-preview-item', {
  props: [ "item" ],
  template:`
    <div class="lms-preview-item" :class="'level-' + item.level" v-if="item.access_level >= 1">
      <div class="item-name">{{ item.name }}</div>
      <div class="item-children">
        <div v-for="child in item.children" :key="child.item_id">
          <lms-preview-item v-if="child.access_level >= 1" :item="child"></lms-preview-item>
          <slot></slot>
        </div>
      </div>
    </div>
  `
});

Vue.prototype.$lang_strings = window.lang_strings;
var permApp = new Vue({
  el: '#permission-admin-app',
  data: function() {
    return {
      users: [],
      groups: [],
      cur_group: null,
      cur_user: null,
      permissions: [],
      loading: true
    };
  },
  mounted: function() {
    bus.$on('perm-changed', this.save_perm);
  },
  template: `
    <div class="permission-admin-app">
      <div class="selectors">
        <group-selector
          v-if="groups.length > 0"
          :groups="groups"
          v-model="cur_group"
          @input="load_perms('group', cur_group)"
        />
        <user-selector
          v-if="users.length > 0"
          :users="users"
          v-model="cur_user"
          @input="load_perms('user', cur_user)"
        />
        <fieldset>
          <legend>Berechtigungsstufen</legend>
          <div>
            <span class="dashicons dashicons-lock"></span> {{ this.$lang_strings.legend_locked }}
          </div>
          <div>
            <span class="dashicons dashicons-visibility"></span> {{ this.$lang_strings.legend_visible }}
          </div>
          <div>
            <span class="dashicons dashicons-unlock"></span> {{ this.$lang_strings.legend_unlocked }}
          </div>
          <div>
            <span class="square course"></span> {{ this.$lang_strings.legend_course }}
          </div>
          <div>
            <span class="square chapter"></span> {{ this.$lang_strings.legend_chapter }}
          </div>
          <div>
            <span class="square module"></span> {{ this.$lang_strings.legend_module }}
          </div>
        </fieldset>
      </div>
      <transition-group name="fade">
        <div class="permissions-container" v-if="!loading && (cur_user !== null || cur_group !== null)" key="perm-container">
          <div class="perm-items" v-for="item in permissions" :key="item.item_id">
            <div class="main-item-container">
              <access-level :levels="[0, 1, 2]" v-model="item.access_level" @access-level-changed="handle_level_change(item, $event)" />
              <h2>{{ item.name }}</h2>
            </div>
            <lms-perm-item v-for="child in item.children" :item="child" :key="child.item_id" @perm-changed="handle_child_level_change(item, child)" :parent_access_level="item.access_level"></lms-perm-item>
          </div>
        </div>
        <cluevo-spinner v-if="loading" key="perm-spinner" />
        <div class="cluevo-admin-notice notice-info" v-if="cur_group === null && cur_user === null" key="perm-notice">
          <p>{{ this.$lang_strings.select_group_or_user }}</p>
        </div>
      </transition-group>
    </div>
  `,
  created: function() {
    return this.load_users()
      .then(this.load_groups())
      .catch(function(error) {
        console.error(error);
      });
  },
  methods: {
    load_groups: function() {
      let vm = this;
      this.loading = true;
      return fetch(cluevoWpApiSettings.root + 'cluevo/v1/admin/users/groups')
        .then(function (response) {
          return response.json();
        })
        .then(function(data) {
          vm.groups = data;
          vm.loading = false;
        })
        .catch(function(error) {
          console.error(error);
        });
    },
    load_users: function() {
      let vm = this;
      this.loading = true;
      return fetch(cluevoWpApiSettings.root + 'cluevo/v1/admin/users')
        .then(function (response) {
          return response.json();
        })
        .then(function(data) {
          vm.users = data;
          vm.loading = false;
        })
        .catch(function(error) {
          console.error(error);
        });
    },
    load_perms: function(type, value) {
      if (value) {
        let url = '';
        switch(type) {
          case "group":
            url = cluevoWpApiSettings.root + 'cluevo/v1/admin/permissions/groups/' + value
            this.cur_user = null;
            break;
          case "user":
            url = cluevoWpApiSettings.root + 'cluevo/v1/admin/permissions/users/' + value;
            this.cur_group = null;
            break;
          default:
            url = null;
        }
        let vm = this;
        this.loading = true;
        return fetch(url, {
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            'X-WP-Nonce': cluevoWpApiSettings.nonce
          },
        })
          .then(function (response) {
            return response.json();
          })
          .then(function(data) {
            vm.permissions = data;
            vm.loading = false;
          })
          .catch(function(error) {
            console.error(error);
          });
      } else {
        this.permissions = [];
      }
    },
    handle_level_change(item, level) {
      item.access_level = level;
      bus.$emit('perm-changed', item);
    },
    save_perm: function(item) {
      return fetch(cluevoWpApiSettings.root + 'cluevo/v1/admin/permissions/save', {
        method: 'POST',
        headers: {
          "Content-Type": "application/json; charset=utf-8",
          'X-WP-Nonce': cluevoWpApiSettings.nonce
        },
        body: JSON.stringify({ perm: item})
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data === true) {
            console.log("success");
          } else {
            console.warn("error");
          }
        })
    },
    handle_child_level_change(parent, child) {
      if (parent.access_level < 2 && child.access_level > 0) {
        this.handle_level_change(parent, 2);
      }
    }
  }
});
      //<div class="preview-container">
        //<div class="perm-items" v-for="item in permissions" :key="item.item_id">
          //<lms-preview-item :item="item" />
        //</div>
      //</div>
