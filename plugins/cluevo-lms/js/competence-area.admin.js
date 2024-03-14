Vue.component("cluevo-spinner", {
  template: `
    <div class="cluevo-spinner">
      <div class="cluevo-spinner-segment cluevo-spinner-segment-pink"></div>
      <div class="cluevo-spinner-segment cluevo-spinner-segment-purple"></div>
      <div class="cluevo-spinner-segment cluevo-spinner-segment-teal"></div>
    </div>
  `,
});

Vue.component("competence-area-container", {
  props: ["area", "active"],
  methods: {
    ...({ __ } = wp.i18n),
  },
  template: `
    <tr v-on:click="$emit('select-comp', area.competence_area_id)" v-bind:class="{ active: active }">
      <td>{{ area.competence_area_id }}</td>
      <td class="left primary has-row-actions">{{ area.competence_area_name }}
        <div class="row-actions">
          <span class="delete">
            <a href="#" v-on:click="$emit('del-area', area)">{{ __('Delete', 'cluevo') }}</a>
          </span> |
          <span class="edit">
            <a href="#" v-on:click="$emit('edit-area', area)">{{ __('Edit', 'cluevo') }}</a>
          </span> |
          <span class="post">
            <a :href="'/wp-admin/post.php?post=' + area.metadata_id + '&action=edit'">{{ __('Edit Post', 'cluevo') }}</a>
          </span>
        </div>
      </td>
      <td>{{ area.competences.length }}</td>
      <td>{{ area.modules.length }}</td>
      <td class="left">{{ area.user_added }}</td>
      <td>{{ area.date_added }}</td>
      <td class="left">{{ area.user_modified }}</td>
      <td>{{ area.date_modified }}</td>
    </tr>
  `,
});

Vue.component("competence-area-app-header", {
  template: "#competence-area-app-header-template",
});

Vue.component("competence-area-editor", {
  props: ["area", "creating"],
  data: function () {
    return {
      editing_modules: false,
      editing_comps: false,
      modules: [],
      comps: [],
      edited: false,
      busy: false,
    };
  },
  template: "#competence-area-editor-template",
  created: function () {
    var editor = this;
    return get_comps().then(function (data) {
      var tmpList = editor.area.competences.map(function (c, i) {
        return c.competence_id;
      });
      var list = data.map(function (c, i) {
        c.checked = tmpList.indexOf(c.competence_id) > -1 ? true : false;
        return c;
      });
      editor.comps = list;
    });
  },
  methods: {
    ...({ __ } = wp.i18n),
    toggle_edit_comps: function () {
      this.editing_comps = !this.editing_comps;
    },
    save_area: function () {
      var editor = this;
      this.edited = true;
      this.busy = true;
      fetch(
        compApiSettings.root +
          "cluevo/v1/competence/areas/" +
          this.area.competence_area_id,
        {
          method: "POST",
          credentials: "include",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": compApiSettings.nonce,
          },
          body: JSON.stringify(this.area),
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data === true) {
            editor.$emit("updated", editor.area);
          }
        })
        .finally(function () {
          editor.busy = false;
        });
    },
    save_comps: function () {
      this.edited = true;
      var editor = this;
      var comps = [];
      this.busy = true;
      this.comps.forEach(function (c) {
        if (c.checked) comps.push(c.competence_id);
      });
      fetch(
        compApiSettings.root +
          "cluevo/v1/competence/areas/" +
          this.area.competence_area_id +
          "/competences",
        {
          method: "PUT",
          credentials: "include",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": compApiSettings.nonce,
          },
          body: JSON.stringify(comps),
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          editor.editing_comps = false;
          if (data === true) {
            fetch(
              compApiSettings.root +
                "cluevo/v1/competence/areas/" +
                editor.area.competence_area_id,
              {
                method: "GET",
                credentials: "include",
                headers: {
                  "Content-Type": "application/json; charset=utf-8",
                  "X-WP-Nonce": compApiSettings.nonce,
                },
              },
            )
              .then(function (response) {
                return response.json();
              })
              .then(function (data) {
                editor.area.modules = data.modules;
                editor.area.competences = data.competences;
                editor.editing_comps = false;
                editor.$emit("updated", editor.area);
              });
          }
        })
        .catch(function (error) {
          console.error(error);
        })
        .finally(function () {
          editor.busy = false;
        });
    },
    create_area: function () {
      var editor = this;
      this.busy = true;
      this.area.competences = this.comps.filter(function (c) {
        return c.checked === true;
      });
      fetch(compApiSettings.root + "cluevo/v1/competence/areas", {
        method: "PUT",
        credentials: "include",
        headers: {
          "Content-Type": "application/json; charset=utf-8",
          "X-WP-Nonce": compApiSettings.nonce,
        },
        body: JSON.stringify(this.area),
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data == false) {
            alert(
              editor.__(
                "Failed to create competence group. A competence group with this name may already exist.",
                "cluevo",
              ),
            );
          } else {
            if (data?.competence_area_id) {
              editor.area = data;
              editor.$emit("created", editor.area);
            }
          }
        })
        .catch(function (error) {
          console.error(error);
        })
        .finally(function () {
          editor.busy = false;
        });
    },
  },
});

var areaApp = new Vue({
  el: "#competence-area-admin-app",
  data: function () {
    return {
      competences: [],
      areas: [],
      cur_area: null,
      editing: false,
      creating: false,
      busy: false,
    };
  },
  template: `
    <div class="competence-area-app">
      <h2><span v-if="areas.length > 0">{{ areas.length }}</span> {{ __("Competence Areas", "cluevo") }}</h2>
      <div class="buttons">
        <button type="button" class="button auto button-primary" @click="create_area">{{ __("Create Competence Area", "cluevo") }}</button>
      </div>
      <cluevo-spinner v-if="busy" />
      <table v-else-if="!busy && areas.length > 0" class="wp-list-table widefat striped">
        <thead>
          <competence-area-app-header />
        </thead>
        <tbody>
          <competence-area-container
            v-for="area in areas"
            v-bind:area="area"
            v-bind:key="area.competence_area_id"
            v-bind:active="area == cur_area"
            v-on:del-area="delete_area"
            v-on:edit-area="edit_area"
            v-on:edit-metadata="edit_metadata"
          />
        </tbody>
      </table>
      <div class="cluevo-admin-notice cluevo-notice-info" v-else>
        <p>{{ __("No Competence Areas Found.", "cluevo") }}</p>
      </div>
      <competence-area-editor
        v-if="cur_area !== null && editing === true"
        v-bind:area="cur_area"
        v-bind:creating="creating"
        v-on:close="cancel_editing"
        v-on:updated="updated"
        v-on:created="created"
      />
    </div>
  `,
  created: function () {
    this.init();
  },
  methods: {
    ...({ __ } = wp.i18n),
    init: async function () {
      await this.load_areas();
    },
    load_areas: function () {
      this.busy = true;
      const app = this;
      return fetch(compApiSettings.root + "cluevo/v1/competence/areas/", {
        credentials: "include",
        headers: {
          "Content-Type": "application/json; charset=utf-8",
          "X-WP-Nonce": compApiSettings.nonce,
        },
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          this.areaApp.areas = data;
        })
        .catch(function (error) {
          console.error(error);
        })
        .finally(function () {
          app.busy = false;
        });
    },
    delete_area: function (area) {
      if (
        confirm(
          this.__(
            "Really delete the competence group {name}?",
            "cluevo",
          ).formatUnicorn({
            name: area.competence_area_name,
          }),
        )
      ) {
        console.warn("deleting", area.competence_area_name);
        var app = this;
        this.busy = true;
        fetch(
          compApiSettings.root +
            "cluevo/v1/competence/areas/" +
            area.competence_area_id,
          {
            method: "DELETE",
            credentials: "include",
            headers: {
              "Content-Type": "application/json; charset=utf-8",
              "X-WP-Nonce": compApiSettings.nonce,
            },
          },
        )
          .then(function (response) {
            return response.json();
          })
          .then(function (data) {
            if (data > 0) {
              var result = app.areas.filter(function (a) {
                return a.competence_area_id != area.competence_area_id;
              });
              app.areas = result;
            }
          })
          .catch(function (error) {
            console.error(error);
          })
          .finally(function () {
            app.busy = false;
          });
      }
    },
    edit_area: function (area) {
      var app = this;
      fetch(
        compApiSettings.root +
          "cluevo/v1/competence/areas/" +
          area.competence_area_id,
        {
          credentials: "include",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": compApiSettings.nonce,
          },
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          app.cur_area = data;
          app.editing = true;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    updated: function (area) {
      var app = this;
      this.areas.forEach(function (a, i) {
        if (a.competence_area_id == area.competence_area_id) {
          app.areas[i] = area;
        }
      });
      this.cancel_editing();
    },
    created: function (area) {
      console.log("created", area);
      this.areas.push(area);
      this.cancel_editing();
    },
    cancel_editing: function () {
      this.editing = false;
      this.creating = false;
      this.cur_area = null;
    },
    create_area: function () {
      var app = this;
      fetch(compApiSettings.root + "cluevo/v1/competence/areas/new", {
        method: "GET",
        credentials: "include",
        headers: {
          "Content-Type": "application/json; charset=utf-8",
          "X-WP-Nonce": compApiSettings.nonce,
        },
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          app.cur_area = data;
          app.editing = true;
          app.creating = true;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    edit_metadata: function (area) {
      window.location =
        "/wp-admin/post.php?post=" + area.metadata_id + "&action=edit";
    },
  },
});
