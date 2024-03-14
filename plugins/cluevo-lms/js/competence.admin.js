Vue.component("cluevo-spinner", {
  template: `
    <div class="cluevo-spinner">
      <div class="cluevo-spinner-segment cluevo-spinner-segment-pink"></div>
      <div class="cluevo-spinner-segment cluevo-spinner-segment-purple"></div>
      <div class="cluevo-spinner-segment cluevo-spinner-segment-teal"></div>
    </div>
  `,
});

Vue.component("competence-container", {
  props: ["competence", "active"],
  template: `
    <tr v-on:click="$emit('select-comp', competence.competence_id)" v-bind:class="{ active: active }">
      <td>{{ competence.competence_id }}</td>
      <td class="left primary has-row-actions">
        {{ competence.competence_name }}
        <div class="row-actions">
          <span class="delete">
            <a href="#" @click="$emit('del-comp', competence)">
              {{ __('Delete', 'cluevo') }}
            </a>
          </span> |
          <span class="edit">
            <a href="#" @click="$emit('edit-comp', competence)">{{ __('Edit', 'cluevo') }}</a>
          </span> |
          <span class="post">
            <a :href="'/wp-admin/post.php?post=' + competence.metadata_id + '&action=edit'">{{ __('Edit Post', 'cluevo') }}</a>
          </span>
        </div>
      </td>
      <td>{{ competence.areas.length }}</td>
      <td>{{ competence.modules.length }}</td>
      <td :class="{
      'cluevo-coverage-warning': competence.total_coverage < 1 && competence.total_coverage > 0,
      'cluevo-coverage-error': competence.total_coverage == 0,
      'cluevo-coverage-ok': competence.total_coverage == 1
      }">{{ (competence.total_coverage * 100).toFixed(2) }}%</td>
      <td class="left">{{ competence.user_added }}</td>
      <td>{{ competence.date_added }}</td>
      <td class="left">{{ competence.user_modified }}</td>
      <td>{{ competence.date_modified }}</td>
    </tr>
  `,
});

Vue.component("competence-app-header", {
  template: "#competence-app-header-template",
});

Vue.component("competence-editor", {
  props: ["competence", "creating"],
  data: function () {
    return {
      editing_modules: false,
      editing_areas: false,
      modules: [],
      areas: [],
      edited: false,
      busy: false,
    };
  },
  template: "#competence-editor-template",
  created: function () {
    var editor = this;
    get_modules()
      .then(function (data) {
        var tmpList = editor.competence.modules.map(function (m, i) {
          return m.module_id;
        });
        var list = data.map(function (m, i) {
          m.checked = tmpList.indexOf(m.module_id) > -1 ? true : false;
          editor.competence.modules.forEach(function (module) {
            if (module.module_id == m.module_id)
              m.competence_coverage = module.competence_coverage * 100;
          });
          return m;
        });
        editor.modules = list;
        return get_areas();
      })
      .then(function (data) {
        var tmpList = editor.competence.areas.map(function (a, i) {
          return a.competence_area_id;
        });
        var list = data.map(function (a, i) {
          a.checked = tmpList.indexOf(a.competence_area_id) > -1 ? true : false;
          return a;
        });
        editor.areas = list;
      });
  },
  methods: {
    toggle_edit_modules: function () {
      this.editing_modules = !this.editing_modules;
    },
    toggle_edit_areas: function () {
      this.editing_areas = !this.editing_areas;
    },
    save_modules: function () {
      this.edited = true;
      var editor = this;
      var modules = [];
      this.busy = true;
      this.modules.forEach(function (m) {
        if (m.checked) {
          modules.push([m.module_id, m.competence_coverage / 100]);
          editor.competence.modules.forEach(function (c) {
            if (c.module_id === m.module_id) {
              c.competence_coverage = m.competence_coverage / 100;
            }
          });
        }
      });
      fetch(
        compApiSettings.root +
          "cluevo/v1/competence/competences/" +
          this.competence.competence_id +
          "/modules",
        {
          method: "PUT",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": compApiSettings.nonce,
          },
          body: JSON.stringify(modules),
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data === true) {
            editor.editing_modules = false;
            var newList = [];
            let total = 0.0;
            editor.modules.forEach(function (m) {
              if (m.checked) {
                var c = JSON.parse(JSON.stringify(m));
                c.competence_coverage /= 100;
                total += c.competence_coverage;
                newList.push(c);
              }
            });
            const payload = {
              ...JSON.parse(JSON.stringify(editor.competence)),
              modules: newList,
              total_coverage: total,
            };
            editor.$emit("updated", payload);
          }
        })
        .catch(function (error) {
          console.error(error);
        })
        .finally(function () {
          editor.busy = false;
        });
    },
    save_competence: function () {
      const editor = this;
      this.edited = true;
      this.busy = true;
      fetch(
        compApiSettings.root +
          "cluevo/v1/competence/competences/" +
          this.competence.competence_id,
        {
          method: "POST",
          credentials: "include",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": compApiSettings.nonce,
          },
          body: JSON.stringify(this.competence),
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data === true) {
            editor.$emit("updated", {
              ...editor.competence,
              modules: editor.modules.filter((m) => m.checked),
            });
          }
        })
        .finally(function () {
          editor.busy = false;
        });
    },
    save_areas: function () {
      this.edited = true;
      var editor = this;
      var areas = [];
      this.busy = true;
      this.areas.forEach(function (a) {
        if (a.checked) areas.push(a.competence_area_id);
      });
      fetch(
        compApiSettings.root +
          "cluevo/v1/competence/competences/" +
          this.competence.competence_id +
          "/areas",
        {
          method: "PUT",
          credentials: "include",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": compApiSettings.nonce,
          },
          body: JSON.stringify(areas),
        },
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          editor.editing_areas = false;
          if (data === true) {
            var list = [];
            editor.areas.forEach(function (a) {
              if (a.checked) list.push(a);
            });
            editor.competence.areas = list;
            editor.$emit("updated", editor.competence);
          }
        })
        .catch(function (error) {
          console.error(error);
        })
        .finally(function () {
          editor.busy = false;
        });
    },
    create_competence: function () {
      var editor = this;
      this.competence.areas = this.areas.filter(function (a) {
        return a.checked === true;
      });
      var modules = [];
      this.modules.forEach(function (m) {
        if (m.checked) {
          modules.push([m.module_id, m.competence_coverage / 100]);
          editor.competence.modules.forEach(function (c) {
            if (c.module_id === m.module_id) {
              c.competence_coverage = m.competence_coverage / 100;
            }
          });
        }
      });
      this.competence.modules = modules;
      this.busy = true;
      fetch(compApiSettings.root + "cluevo/v1/competence/competences", {
        method: "PUT",
        credentials: "include",
        headers: {
          "Content-Type": "application/json; charset=utf-8",
          "X-WP-Nonce": compApiSettings.nonce,
        },
        body: JSON.stringify(this.competence),
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data == false) {
            alert(
              editor.__(
                "Failed to create competence. A competence with this name may already exist.",
                "cluevo",
              ),
            );
          } else {
            if (data?.competence_id) {
              editor.competence = data;
              editor.$emit("created", editor.competence);
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

var compApp = new Vue({
  el: "#competence-admin-app",
  data: function () {
    return {
      competences: [],
      modules: [],
      areas: [],
      cur_comp: null,
      editing: false,
      creating: false,
      busy: false,
    };
  },
  computed: {
    missingCoverage() {
      return this.competences.filter((c) => c.total_coverage < 1);
    },
  },
  template: `
    <div class="competence-app">
      <h2><span v-if="competences.length > 0">{{ competences.length}}</span> {{ __("Competences", "cluevo") }}</h2>
      <div class="buttons">
        <button type="button" class="button auto button-primary" @click="create_competence">{{ __("Create Competence", "cluevo") }}</button>
      </div>
      <div v-if="missingCoverage && missingCoverage.length" class="cluevo-notice cluevo-notice-warning cluevo-missing-coverage">
        <p class="cluevo-notice-title">{{ __("Warning", "cluevo") }}</p>
        <p>{{ __("Some of your competences are missing coverage. Add more modules that cover these competences or increase the coverage of the already assigned modules.", "cluevo") }}</p>
        <ul>
          <li
            v-for="c of missingCoverage"
            :key="c.competence_id"
            @click="edit_competence(c)"
          >{{ c.competence_name }}: {{ (c.total_coverage * 100).toFixed(2) }}%</li>
        </ul>
      </div>
      <cluevo-spinner v-if="busy" />
      <table v-else-if="!busy && competences.length > 0" class="wp-list-table widefat striped">
        <thead>
          <competence-app-header />
        </thead>
        <tbody>
          <competence-container
            v-for="comp in competences"
            :competence="comp"
            :key="comp.competence_id"
            :active="comp == cur_comp"
            @del-comp="delete_competence"
            @edit-comp="edit_competence"
            @edit-comp-metadata="edit_competence_metadata"
          />
        </tbody>
      </table>
      <div class="cluevo-admin-notice cluevo-notice-info" v-else>
        <p>{{ __("No competences found.", "cluevo") }}</p>
      </div>
      <competence-editor
        v-if="cur_comp !== null && editing === true"
        :competence="cur_comp"
        :creating="creating"
        @close="cancel_editing"
        @updated="updated"
        @created="created"
      />
    </div>
  `,
  created: function () {
    this.init();
  },
  methods: {
    ...({ __ } = wp.i18n),
    init: async function () {
      this.busy = true;
      const promises = [
        this.load_comps(),
        this.load_areas(),
        this.load_modules(),
      ];
      await Promise.allSettled(promises);
      this.busy = false;
    },
    load_comps: function () {
      return fetch(compApiSettings.root + "cluevo/v1/competence/competences/", {
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
          this.compApp.competences = data;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    load_areas: function () {
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
          this.compApp.areas = data;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    load_modules: function () {
      return fetch(compApiSettings.root + "cluevo/v1/modules/", {
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
          this.compApp.modules = data;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    delete_competence: function (comp) {
      if (
        confirm(
          this.__(
            "Are you sure you want to delete the competence {name}",
            "cluevo",
          ).formatUnicorn({
            name: comp.competence_name,
          }),
        )
      ) {
        console.warn("deleting", comp.competence_name);
        var app = this;
        fetch(
          compApiSettings.root +
            "cluevo/v1/competence/competences/" +
            comp.competence_id,
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
              var result = app.competences.filter(function (a) {
                return a.competence_id != comp.competence_id;
              });
              app.competences = result;
            }
          })
          .catch(function (error) {
            console.error(error);
          });
      }
    },
    edit_competence: function (comp) {
      var app = this;
      fetch(
        compApiSettings.root +
          "cluevo/v1/competence/competences/" +
          comp.competence_id,
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
          app.cur_comp = data;
          app.editing = true;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    edit_competence_metadata: function (comp) {
      window.location =
        "/wp-admin/post.php?post=" + comp.metadata_id + "&action=edit";
    },
    updated: function (competence) {
      const vm = this;
      this.competences.forEach(function (c, i) {
        if (c.competence_id == competence.competence_id) {
          Vue.set(vm.competences, i, competence);
        }
      });
      this.cancel_editing();
    },
    created: function (competence) {
      console.log("created", competence);
      this.competences.push(competence);
      this.cancel_editing();
    },
    cancel_editing: function () {
      this.editing = false;
      this.creating = false;
      this.cur_comp = null;
    },
    create_competence: function () {
      var app = this;
      fetch(compApiSettings.root + "cluevo/v1/competence/competences/new", {
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
          app.cur_comp = data;
          app.editing = true;
          app.creating = true;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
  },
});
