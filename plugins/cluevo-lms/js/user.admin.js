var bus = new Vue({});

Vue.prototype.$misc_strings = window.misc_strings;

Vue.component("user-group-badge", {
  props: ["group", "edit"],
  methods: {
    handle_click: function (group) {
      this.$emit("quick-edit-group-membership");
    },
  },
  computed: {
    is_email_group: function () {
      if (this.group && this.group.hasOwnProperty("group_name")) {
        return this.group.group_name.indexOf("@") === 0;
      }
      return false;
    },
  },
  template: `<div class="cluevo-group-badge-container" @click="handle_click(this.group)" :class="{ 'trainer': this.group.is_trainer }">
    <span class="cluevo-group-badge">{{ this.group.group_name }}</span>
    <div class="editor" v-show="edit">
      <button type="button" @click.stop="handle_click(this.group)"><span class="dashicons dashicons-yes"></span></button>
      <button
        v-if="!is_email_group"
        type="button" 
        @click.stop="$emit('remove-user-from-group', this.group)"
      >
          <span class="dashicons dashicons-no"></span>
      </button>
      <button
        v-if="this.group.is_trainer"
        type="button" 
        @click.stop="$emit('demote-user', this.group)"
      >
          <span class="dashicons dashicons-welcome-learn-more"></span>
          <span class="dashicons dashicons-arrow-down-alt"></span>
          {{ __("Demote to user", "cluevo") }}
        </button>
      <button
        v-if="!this.group.is_trainer"
        type="button" 
        @click.stop="$emit('promote-user', this.group)"
      >
          <span class="dashicons dashicons-welcome-learn-more"></span>
          <span class="dashicons dashicons-arrow-up-alt"></span>
          {{ __("Promote to trainer", "cluevo") }}
        </button>
    </div>
  </div>
  `,
});

Vue.component("cluevo-spinner", {
  template: `
    <div class="cluevo-spinner">
      <div class="cluevo-spinner-segment cluevo-spinner-segment-pink"></div>
      <div class="cluevo-spinner-segment cluevo-spinner-segment-purple"></div>
      <div class="cluevo-spinner-segment cluevo-spinner-segment-teal"></div>
    </div>
  `,
});

Vue.component("group-container", {
  props: ["group"],
  data: function () {
    return {
      adding_group: false,
      edit_group: null,
    };
  },
  computed: {
    tags: function () {
      return this.group?.tags?.join?.(", ") ?? this.group.tags ?? "";
    },
  },
  methods: {
    ...({ __ } = wp.i18n),
  },
  template: `
    <tr>
      <td>{{ group.group_id }}</td>
      <td class="left primary has-row-actions">
        {{ group.group_name }}
        <div class="row-actions">
            <span class="delete">
              <a href="#" v-if="group.protected != 1" v-on:click.prevent="$emit('del-group', group)">{{ __('Delete', 'cluevo')}}</a>
              <span class="disabled" v-else>{{ __('Delete', 'cluevo') }}</span>
            </span> |
            <span class="edit">
              <a href="#" v-on:click.prevent="$emit('edit-group', group)">{{ __('Edit', 'cluevo') }}</a>
            </span> |
            <span class="permissions">
              <a href="#" v-on:click.prevent="$emit('edit-group-perms', group)">{{ __('Permissions', 'cluevo') }}</a>
            </span>
        </div>
      </td>
      <td class="left">{{ group.group_description }}</td>
      <td class="left">{{ tags }}</td>
      <td>{{ group.users.length }}</td>
      <td>{{ group.trainers.length }}</td>
      <td>{{ group.date_added }}</td>
      <td>{{ group.date_modified }}</td>
    </tr>
  `,
});

Vue.component("user-container", {
  props: ["user", "strings", "active"],
  data: function () {
    return {
      adding_group: false,
      edit_group: null,
    };
  },
  computed: {
    possible_groups: function () {
      let result = [];
      let user = this.user;
      lodash.forEach(this.$groups, function (g) {
        let found = false;
        lodash.forEach(user.groups, function (curGroup) {
          if (curGroup.group_id == g.group_id) found = true;
        });
        if (!found && g && g.group_name && g.group_name.indexOf("@") != 0)
          result.push(g);
      });
      return result;
    },
  },
  methods: {
    demote_user: function (user, group) {
      let comp = this;
      return fetch(
        cluevoWpApiSettings.root +
          "cluevo/v1/admin/users/" +
          user.user_id +
          "/groups/" +
          group.group_id +
          "/demote",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": cluevoWpApiSettings.nonce,
          },
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          comp.$emit("refresh", user);
          comp.$emit("refresh-group", group);
        });
    },
    promote_user: function (user, group) {
      let comp = this;
      return fetch(
        cluevoWpApiSettings.root +
          "cluevo/v1/admin/users/" +
          user.user_id +
          "/groups/" +
          group.group_id +
          "/promote",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": cluevoWpApiSettings.nonce,
          },
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          comp.$emit("refresh", user);
        });
    },
    handle_badge_click: function (g) {
      if (this.edit_group == g.group_id) this.edit_group = null;
      else this.edit_group = g.group_id;
    },
    handle_add_user_to_group(event) {
      if (event.target.value == 0) return;

      let comp = this;
      return fetch(
        cluevoWpApiSettings.root +
          "cluevo/v1/admin/users/" +
          this.user.user_id +
          "/groups/" +
          event.target.value +
          "/add",
        {
          method: "POST",
          credentials: "include",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": cluevoWpApiSettings.nonce,
          },
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          comp.adding_group = false;
          comp.edit_group = null;
          comp.$emit("refresh", comp.user);
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    remove_user_from_group: function (user, group) {
      let comp = this;
      return fetch(
        cluevoWpApiSettings.root +
          "cluevo/v1/admin/users/" +
          this.user.user_id +
          "/groups/" +
          group.group_id +
          "/remove",
        {
          method: "DELETE",
          credentials: "include",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": cluevoWpApiSettings.nonce,
          },
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          comp.adding_group = false;
          comp.edit_group = null;
          comp.$emit("refresh", comp.user);
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    ...({ __ } = wp.i18n),
  },
  template: `
    <tr>
      <td>{{ user.user_id }}</td>
      <td class="left has-row-actions primary">
        {{ user.display_name }}
        <div class="row-actions">
          <span class="delete">
            <a href="#" v-on:click.prevent="$emit('del-user', user)">{{ __('Delete', 'cluevo') }}</a>
          </span> |
          <span class="scorm-parms">
            <a :href="'?page=' + this.$misc_strings.reporting_page + '&tab=' + this.$misc_strings.scorm_tab + '&user=' + user.user_id">{{ __('SCORM Parameter', 'cluevo') }}</a>
          </span> |
          <span class="progress">
            <a :href="'?page=' + this.$misc_strings.reporting_page + '&tab=' + this.$misc_strings.progress_tab + '&user=' + user.user_id">{{ __('Progress', 'cluevo') }}</a>
          </span> |
          <span class="reset-progress">
            <a href="#" v-on:click.prevent="$emit('reset-progress', user)">{{ __('Reset Progress', 'cluevo') }}</a>
          </span> |
          <span class="permissions">
            <a href="#" v-on:click="$emit('edit-user-perms', user)">{{ __('Permissions', 'cluevo') }}</a>
          </span> |
          <span class="add-group">
            <a v-if="!adding_group && possible_groups.length > 0"
              href="#"
              @click.prevent="adding_group = !adding_group"
            >{{ __('Add Group', 'cluevo') }}
            </a>
          </span>
        </div>
      </td>
      <td class="left">
        <div class="user-groups-cell">
          <user-group-badge v-for="(g, i) in user.groups"
            :group="g" :key="'badege_u_' + user.user_id + '_g_' + g.group_id"
            :edit="edit_group == g.group_id"
            v-on:remove-user-from-group="remove_user_from_group(user, g)"
            v-on:demote-user="demote_user(user, g)"
            v-on:promote-user="promote_user(user, g)"
            v-on:quick-edit-group-membership="handle_badge_click(g)"
            v-on:add-user-to-group="handle_add_user_to_group"></user-group-badge>
          <select v-if="possible_groups.length > 0 && adding_group" @change="handle_add_user_to_group">
            <option :value="0">{{ __("Select a Group", "cluevo") }}</option>
            <option v-for="group in possible_groups" :value="group.group_id">{{ group.group_name }}</option>
          </select>
          <div 
            v-if="adding_group && possible_groups.length > 0"
            @click="adding_group = !adding_group"
            class="button auto"
          >
              {{ __("Cancel", "cluevo") }}
          </div>
        </div>
      </td>
      <!-- <td class="left">{{ user.role_display_name }}</td>
      <td class="left">{{ user.role_since }}</td> -->
      <td class="left">{{ user.date_last_seen }}</td>
      <td class="left">{{ user.date_added }}</td>
      <td class="left">{{ user.date_modified }}</td>
    </tr>
  `,
});

Vue.component("user-app-header", {
  template: `<tr>
      <th>#</th>
      <th class="left">{{ __("Username", "cluevo") }}</th>
      <th class="left">{{ __("Groups", "cluevo") }}</th>
      <th class="left">{{ __("Last Seen", "cluevo") }}</th>
      <th class="left">{{ __("Date Added", "cluevo") }}</th>
      <th class="left">{{ __("Date Modified", "cluevo") }}</th>
    </tr>`,
});

Vue.component("group-header", {
  template: `<tr>
      <th class="cluevo-mini">#</th>
      <th class="left">{{ __("Group Name", "cluevo") }}</th>
      <th class="left">{{ __("Description", "cluevo") }}</th>
      <th class="left">{{ __("Tags", "cluevo") }}</th>
      <th>{{ __("Members", "cluevo") }}</th>
      <th>{{ __("Trainers", "cluevo") }}</th>
      <th>{{ __("Date Added", "cluevo") }}</th>
      <th>{{ __("Date Modified", "cluevo") }}</th>
    </tr>`,
});

Vue.component("tab", {
  props: {
    id: { default: null },
    title: { required: true },
  },
  data: function () {
    return {
      isActive: false,
      isVisible: true,
    };
  },
  template: `<section class="cluevo-tab" v-show="isActive">
    <slot />
  </section>`,
});

Vue.component("tabs", {
  data: function () {
    return {
      tabs: [],
    };
  },
  props: ["activeTabId"],
  methods: {
    selectTab: function (index) {
      this.selectTabById(this.tabs[index].id);
    },
    selectTabById: function (id) {
      for (let tab of this.tabs) {
        tab.isActive = tab.id == id;
        if (tab.isActive) {
          this.$emit("tab-changed", tab.id);
        }
      }
    },
  },
  created: function () {
    this.tabs = this.$children;
  },
  mounted: function () {
    bus.$on("change-tab", this.selectTabById);
    if (this.activeTabId) {
      this.selectTabById(this.activeTabId);
    } else {
      this.selectTab(0);
    }
  },
  template: `<div class="cluevo-tabs">
    <h2 class="nav-tab-wrapper cluevo">
      <a v-for="(tab, index) in tabs" @click="selectTab(index, $event)" class="nav-tab" :class="{ 'nav-tab-active': tab.isActive }">{{ tab.title }}</a>
    </h2>
    <div class="tabs">
      <slot />
    </div>
  </div>`,
});

Vue.component("user-add-dialog", {
  data: function () {
    return {
      users: [],
      checked_users: [],
      search: "",
      checked_all: false,
    };
  },
  computed: {
    checked_usernames: function () {
      return (
        this.users
          ?.filter?.((user) => this.checked_users.includes(user.user_id))
          ?.map?.((user) => user.display_name)
          ?.join?.(", ") ?? ""
      );
    },
  },
  template: `<transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper" v-on:click.self="$emit('close')">
          <div class="modal-container">

            <div class="modal-header">
              <h3>{{ __("Add User", "cluevo") }}</h3>
              <button type="button" class="close" @click="$emit('close')"><span class="dashicons dashicons-no-alt"></span></button>
            </div>

            <div class="modal-body">
              <input type="text" name="search" v-model="search" @keyup="search_users(search)" :placeholder="__('Find User', 'cluevo')" />
              <transition name="fade">
                <table class="wp-list-table widefat striped limit" v-if="users.length > 0">
                  <thead>
                    <tr>
                      <th class="check">
                        <input type="checkbox" name="check-all" @click="toggle_all" />
                      </th>
                      <th class="id">#</th>
                      <th class="left name">{{ __("Name", "cluevo") }}</th>
                    </tr>
                  </thead>
                  <transition-group name="fade" tag="tbody">
                   <tr v-for="user in users" :key="user.data.ID">
                    <td class="check"><input type="checkbox" :value="user.data.ID" v-model="checked_users"/></td>
                    <td class="id">{{ user.data.ID }}</td>
                    <td class="name left">{{ user.data.display_name }}</td>
                   </tr> 
                  </transition-group>
                </table>
              </transition>
              <p v-show="checked_users.length > 0">{{ __("Selected Users:", "cluevo") }} {{ checked_usernames }}</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="button button-primary auto" v-on:click="$emit('add-lms-users', checked_users)"">{{ checked_users.length }} {{ __("Add Users", "cluevo") }}</button>
            </div>
          </div>
        </div>
      </div>
      </transition>`,
  created: function () {
    this.search_users("");
  },
  methods: {
    search_users: lodash.debounce(function (input) {
      const app = this;
      return fetch(
        cluevoWpApiSettings.root + "cluevo/v1/admin/users/wordpress",
        {
          method: "POST",
          credentials: "include",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": cluevoWpApiSettings.nonce,
          },
          body: JSON.stringify({ search: input }),
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          app.users = data;
        })
        .catch(function (error) {
          console.error(error);
        });
    }, 300),
    toggle_all() {
      this.checked_all = !this.checked_all;
      if (this.checked_all) {
        this.checked_users = [
          ...new Set(
            this.users.map(function (u, i) {
              return u.data.ID;
            }),
          ),
        ];
      } else {
        this.checked_users = [];
      }
    },
  },
});

Vue.component("group-add-dialog", {
  props: ["group", "editing"],
  data: function () {
    return {
      name: "",
      description: "",
      users: [],
      tags: "",
      checked_users: [],
      search: "",
      checked_all: false,
      loading: false,
      busy: false,
    };
  },
  computed: {
    checked_usernames: function () {
      return (
        this.users
          ?.filter?.((user) => this.checked_users.includes(user.user_id))
          ?.map?.((user) => user.display_name)
          ?.join(", ") ?? ""
      );
    },
    is_email_group: function () {
      return this.name.indexOf("@") === 0;
    },
  },
  methods: {
    ...({ __ } = wp.i18n),
  },
  template: `<transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper" v-on:click.self="$emit('close')">
          <div class="modal-container">

            <div class="modal-header">
              <h3 v-if="!editing">{{ __('Add Group', 'cluevo') }}</h3>
              <h3 v-else>{{ __('Edit Group', 'cluevo') }}</h3>
              <button type="button" class="close" @click="$emit('close')"><span class="dashicons dashicons-no-alt"></span></button>
            </div>

            <div class="modal-body">
              <h4>{{ __("Group Information", "cluevo") }}</h4>
              <div v-if="is_email_group" class="cluevo-notice">{{ __("This is a e-mail group. Every user that has an e-mail address from this domain is automatically a member of this group.", "cluevo") }}</div>
              <div class="group-info">
                <label>{{ __("Name", "cluevo") }}
                  <input type="text" name="name" v-model="name" />
                </label>
                <label>{{ __("Description", "cluevo") }}
                  <input type="text" name="description" v-model="description" />
                </label>
                <label>{{ __("Tags", "cluevo") }}
                  <input type="text" name="tags" v-model="tags" />
                </label>
              </div>
              <hr />
              <h4>{{ __("Members", "cluevo") }}</h4>
              <input type="text" name="search" v-model="search" @keyup="search_users(search)" :placeholder="__('Find User', 'cluevo')" />
              <transition name="fade">
                <cluevo-spinner v-if="loading" key="spinner" />
                <template v-else>
                  <table class="wp-list-table widefat striped limit cluevo-table" v-if="users.length > 0">
                    <thead>
                      <tr>
                        <th class="cb">
                          <input type="checkbox" name="check-all" @click="toggle_all" :disabled="is_email_group" />
                        </th>
                        <th class="id">#</th>
                        <th class="left name">{{ __("Name", "cluevo") }}</th>
                      </tr>
                    </thead>
                    <transition-group name="fade" tag="tbody">
                     <tr v-for="user in users" :key="user.user_id">
                      <td class="check-column">
                        <input type="checkbox" :value="user.user_id" v-model="checked_users" :disabled="is_email_group"/>
                      </td>
                      <td class="id">{{ user.user_id }}</td>
                      <td class="name left primary">{{ user.display_name }}</td>
                     </tr> 
                    </transition-group>
                  </table>
                </template>
              </transition>
              <p v-show="checked_users.length > 0">{{ __("Selected Users:", "cluevo") }} {{ checked_usernames }}</p>
            </div>
            <div class="modal-footer">
              <button
                v-if="group === null"
                :disabled="busy"
                type="button"
                class="button button-primary auto"
                @click="handle_add_group"
              >{{ __("Add Group", "cluevo") }}</button>
              <button
                v-if="group !== null"
                :disabled="busy"
                type="button"
                class="button button-primary auto"
                @click="handle_edit_group"
              >{{ __("Edit Group", "cluevo") }}</button>
            </div>
          </div>
        </div>
      </div>
      </transition>`,
  created: function () {
    if (this.group) {
      let vm = this;
      this.tags = this.group?.tags?.join?.(", ") ?? this.group.tags ?? "";
      this.search_users_now("").then(function () {
        vm.name = vm.group.group_name;
        vm.description = vm.group.group_description;
        const users = [];
        for (const u of vm.group.users) {
          for (const user of vm.users) {
            if (user.user_id == u) {
              users.push(user.user_id);
            }
          }
        }
        vm.checked_users = users;
      });
    } else {
      this.search_users("");
    }
  },
  methods: {
    search_users_now: function (input) {
      const app = this;
      this.loading = true;
      return fetch(cluevoWpApiSettings.root + "cluevo/v1/admin/users", {
        method: "POST",
        credentials: "include",
        headers: {
          "Content-Type": "application/json; charset=utf-8",
          "X-WP-Nonce": cluevoWpApiSettings.nonce,
        },
        body: JSON.stringify({ search: input }),
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          app.users = data;
        })
        .catch(function (error) {
          console.error(error);
        })
        .finally(function () {
          app.loading = false;
        });
    },
    search_users: lodash.debounce(function (input) {
      this.search_users_now(input);
    }, 300),
    handle_add_group: function () {
      let vm = this;
      let users = this.checked_users;
      this.busy = true;
      return fetch(
        cluevoWpApiSettings.root + "cluevo/v1/admin/users/groups/create",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": cluevoWpApiSettings.nonce,
          },
          body: JSON.stringify({
            users: users,
            name: vm.name,
            description: vm.description,
            tags: vm.tags,
          }),
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data !== false) {
            vm.$emit("group-added");
          } else {
            // display error, false = group creation failed, or users could not be added to group
          }
        })
        .catch(function (error) {
          console.error(error);
        })
        .finally(function () {
          vm.busy = false;
        });
    },
    toggle_all() {
      this.checked_all = !this.checked_all;
      if (this.checked_all) {
        this.checked_users = [
          ...new Set(
            this.users.map(function (u, i) {
              return u.user_id;
            }),
          ),
        ];
      } else {
        this.checked_users = [];
      }
    },
    handle_edit_group: function () {
      const vm = this;
      let group = this.group;
      group.group_name = this.name;
      group.group_description = this.description;
      group.users = this.checked_users.map(function (u, i) {
        return u.ID;
      });
      group.tags = this.tags;
      vm.busy = true;
      return fetch(cluevoWpApiSettings.root + "cluevo/v1/admin/users/groups", {
        method: "POST",
        headers: {
          "Content-Type": "application/json; charset=utf-8",
          "X-WP-Nonce": cluevoWpApiSettings.nonce,
        },
        body: JSON.stringify(group),
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          this.userApp.wp_users = data;
          vm.$emit("refresh", vm.group);
        })
        .catch(function (error) {
          console.error(error);
        })
        .finally(function () {
          vm.busy = false;
        });
    },
  },
});

Vue.component("group-selector", {
  props: ["groups", "value"],
  methods: {
    update: function (key, value) {
      const result = this.groups.filter(function (g) {
        return g.group_id == value;
      });
      if (result.length == 1) {
        this.$emit("input", result.pop());
      }
    },
  },
  template: `
    <label> {{ __("Group", "cluevo") }}
      <select name="group-selector" v-bind:value="value" @input="update('input', $event.target.value)">
        <option :value="null"></option>
        <option v-for="group in groups" :value="group.group_id" :selected="value && value.group_id == group.group_id" >{{ group.group_name }}</option>
      </select>
    </label>
  `,
});

Vue.component("user-selector", {
  props: ["users", "value", "display"],
  data: function () {
    return {
      results: [],
      val: "",
      timer: null,
      hide: false,
    };
  },
  watch: {
    value(n, o) {
      if (!n) {
        this.val = "";
      }
    },
    display(n) {
      this.val = n;
    },
  },
  methods: {
    search: function (e) {
      this.val = e.target.value;
      this.hide = false;
      if (e.target.value.length < 3) return;
      clearTimeout(this.timer);
      let vm = this;
      this.timer = setTimeout(async function () {
        let res = await fetch(
          cluevoWpApiSettings.root + "cluevo/v1/admin/users",
          {
            method: "POST",
            body: JSON.stringify({ search: e.target.value }),
            headers: {
              "Content-Type": "application/json; charset=utf-8",
              "X-WP-Nonce": cluevoWpApiSettings.nonce,
            },
          },
        );
        if (res.ok) {
          try {
            const data = await res.json();
            vm.results = data;
          } catch (error) {
            console.error("failed to search users", error);
          }
        }
      }, 400);
    },
    select(user) {
      this.val = user.display_name;
      this.hide = true;
      this.$emit("input", user.user_id);
    },
  },
  template: `
    <label class="cluevo-user-selector" @mouseleave="hide = true"> {{ __("User", "cluevo") }}
      <input type="text" name="user-selector" :value="val" @input="search" />
      <div
        v-if="results && results.length > 0 && !hide"
        class="cluevo-user-selector-results"
      >
        <ul>
          <li v-for="user of results" @click="select(user)">{{ user.display_name }}</li>
        </ul>
      </div>
    </label>
  `,
});

Vue.component("access-level", {
  props: ["levels", "value", "effective"],
  methods: {
    handleInput: function (value) {
      if (value != this.value) {
        this.$emit("input", value);
        this.$emit("access-level-changed", value);
      }
    },
  },
  template: `
    <div class="access-level-container">
      <input v-bind:value="value">
        <label v-for="level in levels" :class="[{ 'active': (level == value || (level == 0 && value === null)), 'effective': effective && effective.access_level == level }]" @click="handleInput(level)">
          <span v-if="level == 0" class="dashicons dashicons-lock"></span>
          <span v-if="level == 1" class="dashicons dashicons-visibility"></span>
          <span v-if="level == 2" class="dashicons dashicons-unlock"></span>
        </label>
        <label @click="handleInput(null)">
          <span class="dashicons dashicons-no-alt"></span>
        </label>
      </input>
  </div>`,
});

Vue.component("lms-perm-item", {
  props: ["item", "parent_access_level", "parent_date_expired"],
  data: function () {
    return {
      settingExpire: false,
    };
  },
  computed: {
    perms_overridden: function () {
      if (
        typeof this.effective_access_level == "object" &&
        this.effective_access_level !== null
      ) {
        if (
          this.item &&
          this.effective_access_level.access_level &&
          this.effective_access_level.access_level >= this.item.access_level
        )
          return true;
      }
      return false;
    },
    effective_access_level: function () {
      if (this.item && this.item.highest_group_access_level) {
        if (
          this.item.access_level <=
          this.item.highest_group_access_level.access_level
        ) {
          return this.item.highest_group_access_level;
        }
      }
      return this.item.access_level;
    },
    canExpire: function () {
      return this.item.access_level > 0;
    },
    date: function () {
      if (!this.item.date_expired) return null;
      let date = null;
      if (isNaN(this.item.date_expired)) {
        date = new Date(`${this.item.date_expired}`.replace(" ", "T"));
      } else {
        date = new Date(this.item.date_expired * 1000);
      }
      return new Date(date.getTime() - date.getTimezoneOffset() * 60000)
        .toISOString()
        .slice(0, -8);
    },
    dateTimestamp: function () {
      if (!this.item.date_expired) return null;
      if (isNaN(this.item.date_expired)) {
        return +new Date(this.item.date_expired);
      }
      return this.item.date_expired;
    },
  },
  methods: {
    handle_level_change: function (event) {
      this.item.access_level = event;
      this.$emit("perm-changed", this.item);
      bus.$emit("perm-changed", this.item);
    },
    handle_child_level_change: function (item) {
      if (this.item.access_level < 2 && item.access_level > 0) {
        this.handle_level_change(2);
      }
    },
    switch_to_group: function () {
      bus.$emit("switch-to-group", this.effective_access_level.group.group_id);
    },
    toggleSettingExpire: function () {
      if (!this.canExpire) return;
      this.settingExpire = !this.settingExpire;
    },
    setExpiration: function (value) {
      this.item.date_expired = value;
      this.$emit("perm-changed", this.item);
      bus.$emit("perm-changed", this.item);
    },
    handleExpireChange: function (e) {
      this.setExpiration(+new Date(e.target.value) / 1000);
    },
    clearExpire: function () {
      this.setExpiration(null);
    },
    toDateString(d) {
      return new Date(d * 1000).toLocaleString();
    },
  },
  watch: {
    canExpire: function (n) {
      if (n) return;
      this.clearExpire();
      this.settingExpire = false;
    },
    parent_date_expired: function (n) {
      if (!n) return;
      if (!this.canExpire) return;
      if (!this.date) return;
      if (n < this.dateTimestamp) {
        this.setExpiration(n);
      }
    },
    parent_access_level: function (newLevel, oldLevel) {
      if (newLevel === null) {
        this.handle_level_change(null);
        this.item.children = this.item.children.map(function (c, i) {
          c.access_level = null;
          return c;
        });
      } else {
        if (newLevel < 2) {
          this.handle_level_change(0);
          this.item.children = this.item.children.map(function (c, i) {
            c.access_level = 0;
            return c;
          });
        }
      }
    },
  },
  template: `
    <div class="lms-perm-item" :class="'level-' + item.level"">
      <div class="perm-title">
        <access-level :levels="[0, 1, 2]" v-model="item.access_level" @access-level-changed="handle_level_change" :effective="effective_access_level || null" />
        <label class="cluevo-perm-expire" :class="{disabled: !canExpire}">
          <span @click="toggleSettingExpire" class="dashicons dashicons-calendar"></span>
          <input v-if="item.date_expired || settingExpire" :value="date" @change="handleExpireChange" type="datetime-local" />
          <span v-if="item.date_expired" @click="clearExpire" class="dashicons dashicons-remove"></span>
        </label>
        <div class="perm-item-name">{{ item.name }}</div>
        <div class="module-warning" v-if="perms_overridden">
          <span class="dashicons dashicons-warning"></span>
          <span>{{ __("Warning: This permission is currently overridden by a group permission: ", "cluevo") }}</span>
          <span class="effective-group" v-if="this.effective_access_level.group" @click="switch_to_group">{{ this.effective_access_level.group.group_name }}</span>
          <span v-if="effective_access_level.date_expired">({{ __("Until: ", "cluevo") }} {{ toDateString(effective_access_level.date_expired) }})</span>
        </div>
        <div class="module-warning" v-if="item.access_level == 1 && item.type == 'module'">
          <span class="dashicons dashicons-warning"></span>
          <span>{{ __("Warning: This module is visible but can't be opened by this user/group.", "cluevo") }}</span>
        </div>
      </div>
      <div class="perm-children" v-if="item.children.length > 0">
        <lms-perm-item
          v-for="child in item.children"
          :item="child" :key="child.item_id"
          @perm-changed="handle_child_level_change"
          :parent_access_level="item.access_level"
          :parent_date_expired="item.date_expired"
        ></lms-perm-item>
        <slot></slot>
      </div>
    </div>
  `,
});

Vue.component("lms-access-preview", {
  props: ["items"],
  template: `
    <div class="access-preview-container">
    </div>
  `,
});

Vue.component("lms-preview-item", {
  props: ["item"],
  template: `
    <div class="lms-preview-item" :class="'level-' + item.level" v-if="item.access_level >= 1">
      <div class="item-name">{{ item.name }}</div>
      <div class="item-children">
        <div v-for="child in item.children" :key="child.item_id">
          <lms-preview-item v-if="child.access_level >= 1" :item="child"></lms-preview-item>
          <slot></slot>
        </div>
      </div>
    </div>
  `,
});

Vue.component("perm-comp", {
  data: function () {
    return {
      users: [],
      cur_group: null,
      cur_user: null,
      permissions: [],
      loading: false,
    };
  },
  props: ["user", "group", "groups"],
  watch: {
    group: function (newVal, oldVal) {
      this.cur_group = newVal;
      this.load_perms("group", newVal.group_id);
    },
    user: function (newVal, oldVal) {
      this.cur_user = newVal.user_id;
      this.load_perms("user", newVal.user_id);
    },
  },
  mounted: function () {
    bus.$on("perm-changed", this.save_perm);
    bus.$on("group-added", this.reset);
    bus.$on("user-added", this.reset);
    bus.$on("group-removed", this.reset);
    bus.$on("user-removed", this.reset);
  },
  template: `
    <div class="permission-admin-app">
      <div class="selectors">
        <group-selector
          v-if="groups.length > 0"
          :groups="groups"
          v-model="cur_group"
          @input="load_perms('group', cur_group.group_id)"
        />
        <div class="group-user-separator">{{ __("or", "cluevo") }}</div>
        <user-selector
          v-model="cur_user"
          :display="user ? user.display_name : ''"
          @input="load_perms('user', cur_user)"
        />
        <fieldset>
          <legend>{{ __("Permission Levels", "cluevo") }}</legend>
          <div>
            <span class="dashicons dashicons-lock"></span> {{ __("No Access. The element is not visible and can't be accessed", "cluevo") }}
          </div>
          <div>
            <span class="dashicons dashicons-visibility"></span> {{ __("Visible. THe element is visible but can't be accessed", "cluevo") }}
          </div>
          <div>
            <span class="dashicons dashicons-unlock"></span> {{ __("Open. The element is visible and can be accessed.", "cluevo") }}
          </div>
          <div class="types">
            <div>
              <span class="square course"></span> {{ __("Course", "cluevo") }}
            </div>
            <div>
              <span class="square chapter"></span> {{ __("Chapter", "cluevo") }}
            </div>
            <div>
              <span class="square module"></span> {{ __("Module", "cluevo") }}
            </div>
          </div>
        </fieldset>
      </div>
      <transition name="fade" mode="out-in">
        <div class="cluevo-notice cluevo-notice-info" v-if="cur_group && cur_group.group_description !== ''">
          <p class="cluevo-notice-title">{{ __("Group", "cluevo") }}: {{ cur_group.group_name }}</p>
          <p>{{ cur_group.group_description }}</p>
        </div>
      </transition>
      <transition name="fade" mode="out-in">
        <cluevo-spinner v-if="loading" key="perm-spinner" />
        <div class="permissions-container" v-if="!loading && (cur_user !== null || cur_group !== null)" key="perm-container">
          <div class="perm-items" v-for="item in permissions" :key="item.item_id">
            <div class="main-item-container">
              <access-level :levels="[0, 1, 2]" v-model="item.access_level" @access-level-changed="handle_level_change(item, $event)" />
              <h2>{{ item.name }}</h2>
            </div>
            <lms-perm-item
              v-for="child in item.children"
              :item="child"
              :key="child.item_id"
              @perm-changed="handle_child_level_change(item, child)"
              :parent_access_level="item.access_level"
              :parent_date_expired="item.date_expired"
            ></lms-perm-item>
          </div>
        </div>
        <div class="cluevo-admin-notice cluevo-notice-info" v-if="!loading && cur_group === null && cur_user === null" key="perm-notice">
          <p>{{ __("Select a group or user to create a permissions structure for.", "cluevo") }}</p>
        </div>
      </transition>
    </div>
  `,
  methods: {
    reset: function () {
      //return this.load_groups()
      //.catch(function(error) {
      //console.error(error);
      //});
    },
    load_perms: function (type, value) {
      if (value) {
        let url = "";
        switch (type) {
          case "group":
            url =
              cluevoWpApiSettings.root +
              "cluevo/v1/admin/permissions/groups/" +
              value;
            this.cur_user = null;
            break;
          case "user":
            url =
              cluevoWpApiSettings.root +
              "cluevo/v1/admin/permissions/users/" +
              value;
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
            "X-WP-Nonce": cluevoWpApiSettings.nonce,
          },
        })
          .then(function (response) {
            return response.json();
          })
          .then(function (data) {
            vm.permissions = data;
          })
          .catch(function (error) {
            console.error(error);
          })
          .finally(function () {
            vm.loading = false;
          });
      } else {
        this.permissions = [];
      }
    },
    handle_level_change(item, level) {
      item.access_level = level;
      bus.$emit("perm-changed", item);
    },
    save_perm: function (item) {
      return fetch(
        cluevoWpApiSettings.root + "cluevo/v1/admin/permissions/save",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": cluevoWpApiSettings.nonce,
          },
          body: JSON.stringify({ perm: item }),
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data === true) {
            console.log("success");
          } else {
            console.warn("error");
          }
        });
    },
    handle_child_level_change(parent, child) {
      if (parent.access_level < 2 && child.access_level > 0) {
        this.handle_level_change(parent, 2);
      }
    },
  },
});

var userApp = new Vue({
  el: "#user-admin-app",
  data: function () {
    return {
      users: [],
      groups: [],
      wp_users: [],
      strings: {},
      cur_user: null,
      editing: false,
      editing_group: null,
      adding: false,
      adding_group: false,
      ready: false,
      misc_strings: window.misc_strings,
      activeTab: null,
      edit_perm_group: null,
      edit_perm_user: null,
      page: 0,
      perPage: 100,
      pages: 0,
      usersCount: 0,
      groupsLoaded: false,
      search: null,
      searchTimer: null,
    };
  },
  mounted: function () {
    bus.$on("switch-to-group", this.handle_switch_to_group);
  },
  created: function () {
    this.init();
  },
  watch: {
    page: function (n) {
      this.load_users(n);
    },
  },
  methods: {
    ...({ __ } = wp.i18n),
    init: async function () {
      let vm = this;
      vm.ready = false;
      const promises = [
        this.load_users(),
        this.load_wp_users(),
        this.load_groups(),
      ];
      await Promise.allSettled(promises);
      vm.ready = true;
    },
    load_users: function () {
      let search = null;
      search =
        this.search && this.search.trim() !== "" ? this.search.trim() : null;
      return fetch(
        cluevoWpApiSettings.root + "cluevo/v1/admin/users/paged/" + this.page,
        {
          method: search ? "POST" : "GET",
          body: search ? JSON.stringify({ search: search }) : null,
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": cluevoWpApiSettings.nonce,
          },
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          this.userApp.users = data.users;
          if (this.userApp.groupsLoaded) {
            this.userApp.users.forEach((u) => {
              let groups = [];
              u.groups.forEach((g) => {
                if (typeof g === "object") {
                  groups.push(g);
                } else {
                  let group = this.userApp.groups.find((f) => f.group_id == g);
                  if (group) {
                    groups.push(group);
                  }
                }
              });
              u.groups = groups;
            });
          }
          this.userApp.usersCount = data.total;
          this.userApp.pages = parseInt(data.pages, 10);
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    load_wp_users: function () {
      return fetch(
        cluevoWpApiSettings.root + "cluevo/v1/admin/users/wordpress",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": cluevoWpApiSettings.nonce,
          },
          body: JSON.stringify({ search: "" }),
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          this.userApp.wp_users = data;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    load_groups: function () {
      return fetch(cluevoWpApiSettings.root + "cluevo/v1/admin/users/groups", {
        method: "GET",
        headers: {
          "Content-Type": "application/json; charset=utf-8",
          "X-WP-Nonce": cluevoWpApiSettings.nonce,
        },
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          this.userApp.groups = data;
          this.userApp.groupsLoaded = true;
          Vue.prototype.$groups = data;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    add_user: function () {
      this.adding = true;
    },
    add_group: function () {
      this.adding_group = true;
    },
    handle_group_added: function () {
      this.adding_group = false;
      bus.$emit("group-added");
      this.init();
    },
    refresh_user: function (user) {
      let app = this;
      return fetch(
        cluevoWpApiSettings.root + "cluevo/v1/admin/users/" + user.user_id,
        {
          method: "VIEW",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": cluevoWpApiSettings.nonce,
          },
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          let groups = [];
          data.groups.forEach((g) => {
            let group = null;
            if (!Number.isNaN(parseInt(g, 10))) {
              group = app.groups.find((f) => f.group_id == g);
            } else {
              group = g;
            }
            if (group) {
              groups.push(group);
            }
          });
          user.groups = groups;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    add_lms_users: function (users) {
      let app = this;
      let ids = users.map(function (el) {
        return el.ID;
      });
      return fetch(
        cluevoWpApiSettings.root + "cluevo/v1/admin/users/make/many",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": cluevoWpApiSettings.nonce,
          },
          body: JSON.stringify({ users: ids }),
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          return app.load_users();
        })
        .then(function () {
          app.adding = false;
        })
        .catch(function (error) {
          console.error(error);
          this.adding = false;
        });
    },
    edit_user: function (user) {
      //this.editing = true;
    },
    edit_group: function (group) {
      this.editing_group = group;
    },
    edit_group_perms: function (group) {
      this.edit_perm_group = group;
      bus.$emit("change-tab", "perms");
    },
    edit_user_perms: function (user) {
      this.activeTab = "perms";
      this.edit_perm_user = user;
      bus.$emit("change-tab", "perms");
    },
    delete_user: function (user) {
      let app = this;
      if (
        confirm(this.__("Are you sure you want to delete this user?", "cluevo"))
      ) {
        return fetch(
          cluevoWpApiSettings.root + "cluevo/v1/admin/users/delete/" + user.ID,
          {
            method: "DELETE",
            headers: {
              "Content-Type": "application/json; charset=utf-8",
              "X-WP-Nonce": cluevoWpApiSettings.nonce,
            },
          },
        )
          .then(function (response) {
            return response.json();
          })
          .then(function (data) {
            bus.$emit("user-removed");
            return app.init();
          })
          .then(function () {
            app.adding = false;
          })
          .catch(function (error) {
            console.error(error);
            this.adding = false;
          });
      }
    },
    reset_progress: function (user) {
      if (
        confirm(
          this.__(
            "Are you sure you want to reset this user's progress? This action cannot be undone!",
            "cluevo",
          ),
        )
      ) {
        return fetch(
          cluevoWpApiSettings.root +
            "cluevo/v1/admin/users/" +
            user.user_id +
            "/progress/",
          {
            method: "DELETE",
            headers: {
              "Content-Type": "application/json; charset=utf-8",
              "X-WP-Nonce": cluevoWpApiSettings.nonce,
            },
          },
        );
      }
    },
    delete_group: function (group) {
      if (
        confirm(
          this.__("Are you sure you want to delete this group?", "cluevo"),
        )
      ) {
        let app = this;
        return fetch(
          cluevoWpApiSettings.root +
            "cluevo/v1/admin/users/groups/delete/" +
            group.group_id,
          {
            method: "DELETE",
            headers: {
              "Content-Type": "application/json; charset=utf-8",
              "X-WP-Nonce": cluevoWpApiSettings.nonce,
            },
          },
        )
          .then(function (response) {
            return response.json();
          })
          .then(function (data) {
            bus.$emit("group-removed");
            return app.init();
          })
          .catch(function (error) {
            console.error(error);
          });
      }
    },
    refresh_group: function (group) {
      let app = this;
      return fetch(
        cluevoWpApiSettings.root +
          "cluevo/v1/admin/users/groups/" +
          group.group_id,
        {
          method: "GET",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": cluevoWpApiSettings.nonce,
          },
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          const index = app.groups.find((g) => g.group_id == group.group_id);
          if (index > -1) {
            app.groups[i] = data;
          }
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    handle_tab_change: function (id) {
      this.activeTab = id;
    },
    handle_switch_to_group: function (id) {
      this.edit_perm_group = this.groups.find((g) => g.group_id == id);
    },
    handleSearch: function () {
      const vm = this;
      if (this.searchTimer) clearTimeout(this.searchTimer);
      this.searchTimer = setTimeout(function () {
        vm.page = 0;
        vm.load_users();
      }, 1000);
    },
  },
  template: `
    <div class="user-admin-app">
      <div class="cluevo-admin-notice cluevo-notice-error" v-if="users.length == 0">
        <p>{{ __("No users found.", "cluevo") }}</p>
      </div>
      <user-add-dialog v-if="adding" v-on:add-lms-users="add_lms_users" v-on:close="adding = false" />
      <group-add-dialog
        v-if="adding_group || editing_group !== null"
        v-on:group-added="handle_group_added"
        v-on:close="adding_group = false; editing_group = null"
        :group="editing_group"
        :editing="editing_group"
        v-on:refresh="refresh_group"
      />
      <tabs :activeTabId="activeTab" @tab-changed="(tab) => this.activeTab = tab">
        <tab :title="__('Permissions', 'cluevo')" id="perms">
          <perm-comp :group="edit_perm_group" :user="edit_perm_user" :groups="groups" />
        </tab>
        <tab :title="__('Users', 'cluevo')" id="users">
          <div v-if="ready">
            <div class="buttons cluevo-users-tools-list">
              <div id="add-lms-user" class="button auto" v-on:click="add_user">{{ __("Add User", "cluevo") }}</div>
              <div id="add-lms-group" class="button auto" v-on:click="add_group">{{ __("Add Group", "cluevo") }}</div>
              <input v-model="search" type="text" :placeholder="__('Search', 'cluevo')" @input="handleSearch" />
              <div class="legend">
                <div><div class="trainer"></div>{{ __("Trainer", "cluevo") }}</div>
                <div><div class="user"></div>{{ __("Student", "cluevo") }}</div>
              </div>
            </div>
            <table class="wp-list-table widefat striped" v-show="users.length > 0 && !editing">
              <thead>
                <user-app-header />
              </thead>
              <tbody>
                <user-container
                  v-for="user in users"
                  v-bind:user="user"
                  v-bind:key="user.user_id"
                  v-bind:strings="strings"
                  v-bind:active="user== cur_user"
                  v-on:del-user="delete_user"
                  v-on:edit-user="edit_user"
                  v-on:reset-progress="reset_progress"
                  v-on:edit-user-perms="edit_user_perms"
                  v-on:refresh="refresh_user"
                />
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="6" style="text-align: center;">
                    <div class="cluevo-pagination">
                      <span class="pagination-links">
                        <a href="#" class="tablenav-pages-navspan button" :class="[{ disabled: page <= 0 }]" @click="page > 0 ? page-- : null">‹</a>
                      </span>
                      <select v-model="page" size="1">
                        <option
                          v-for="page of pages"
                          :value="page - 1"
                          :key="page"
                        >{{ page }}</option>
                      </select>
                      <span class="pagination-links">
                        <a href="#" class="tablenav-pages-navspan button" :class="[{ disabled: page >= pages - 1 }]" @click="page < pages - 1 ? page++ : null">›</a>
                      </span>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
          <cluevo-spinner v-else />
        </tab>
        <tab :title="__('Groups', 'cluevo')" id="groups">
          <div v-if="ready">
            <div class="buttons cluevo-users-tools-list">
              <div id="add-lms-group" class="button auto" v-on:click="add_group">{{ __("Add Group", "cluevo") }}</div>
            </div>
            <table class="wp-list-table widefat striped" v-show="groups.length > 0">
              <thead>
                <group-header />
              </thead>
              <tbody>
                <group-container
                  v-for="group in groups"
                  v-bind:group="group"
                  v-bind:key="group.group_id"
                  v-on:del-group="delete_group"
                  v-on:edit-group="edit_group"
                  v-on:edit-group-perms="edit_group_perms"
                  v-on:refresh="refresh_group"
                />
              </tbody>
            </table>
          </div>
          <cluevo-spinner v-else />
        </tab>
      </tabs>
    </div>
  `,
});
