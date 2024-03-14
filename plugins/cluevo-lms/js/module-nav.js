var depthTypes = ["tree", "course", "chapter", "module"];
var depthLabels = [strings.course, strings.chapter, strings.module]; // defines the labels used for the hierarchy levels
var itemsAdded = 1;
var dirty = false;
var submitTree = false;
window.eventBus = new Vue();

window.eventBus.$on("module-change", function (data) {
  var url =
    cluevoWpApiSettings.root +
    "cluevo/v1/items/" +
    parseInt(data.itemId, 10) +
    "/make-module/" +
    parseInt(data.module.module_id, 10);

  jQuery.ajax({
    url: url,
    method: "GET",
    contentType: "application/json",
    dataType: "json",
    beforeSend: function (xhr) {
      xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
    },
    success: function (response) {
      moduleSelector.active = false;
      jQuery("li#item-" + data.itemId)
        .find(".cluevo-make-module:first")
        .addClass("hidden");
      jQuery("li#item-" + data.itemId).data("module-id", data.module.module_id);
      jQuery("li#item-" + data.itemId)
        .find("div.module-name-label:first")
        .removeClass("hidden");
      jQuery("li#item-" + data.itemId)
        .find("div.module-name-label")
        .data("value", data.module.module_id);
      jQuery("li#item-" + data.itemId)
        .find("div.module-name-label:first .content")
        .text(data.module.module_name);
      jQuery("li#item-" + data.itemId)
        .find(".cluevo-btn.add:first")
        .attr("disabled", "disabled");
      jQuery("li#item-" + data.itemId).addClass("module-assigned");
      jQuery("li#item-" + data.itemId)
        .find("[data-module-only]")
        .each(function (_, el) {
          jQuery(el).removeClass("hidden");
        });
      var tree = getFlatTree();
      buildDependencies(tree);
    },
  });
});

const handleRelocate = function (e, item) {
  // rebuild dependencies and refresh data on relocate
  item = jQuery(item);

  var itemId = jQuery(item.item).data("id");
  var parentId = jQuery("li#item-" + itemId)
    .parents("li:first")
    .data("id");
  tree = getFlatTree(true);
  // we need to reset the item's dependencies because it may have moved to another parent
  var deps = {
    other: { normal: {}, inherited: {}, blocked: {} },
    modules: {},
  };
  var pDeps = jQuery(item.item).parents("li:first").data("dependencies");
  // after resetting we inherit the existing dependencies of the parent
  deps.other.inherited = pDeps.other.normal.concat(
    lodash.without(pDeps.other.inherited, itemId)
  );
  deps.other.blocked = pDeps.other.blocked.concat(
    lodash.without(pDeps.other.blocked, itemId)
  );
  jQuery(itemId).data("dependencies", deps);
  // always save back to tree, because we use this tree to rebuild dependencies in the end
  tree[itemId].data.dependencies = deps;
  // get all items that depend on the new parent
  var pDependents = getDependents(parentId);
  lodash.each(pDependents.normal, function (d) {
    // every item and its children that depend in some way on the new parent now need to inherit the item as dependency
    var tmpDeps = jQuery("li#item-" + d).data("dependencies");
    tmpDeps.other.inherited.push("" + itemId);
    jQuery("li#item-" + d).data("dependencies", tmpDeps);
    tree[d].data.dependencies = tmpDeps;
    var children = getAllChildren(d);
    lodash.each(children, function (c) {
      var cDeps = jQuery("li#item-" + c).data("dependencies");
      cDeps.other.inherited.push("" + itemId);
      jQuery("li#item-" + c).data("dependencies", cDeps);
      tree[c].data.dependencies = cDeps;
    });
  });
  lodash.each(pDependents.inherited, function (d) {
    var tmpDeps = jQuery("li#item-" + d).data("dependencies");
    tmpDeps.other.inherited.push("" + itemId);
    jQuery("li#item-" + d).data("dependencies", tmpDeps);
    tree[d].data.dependencies = tmpDeps;
    var children = getAllChildren(d);
    lodash.each(children, function (c) {
      var cDeps = jQuery("li#item-" + c).data("dependencies");
      cDeps.other.inherited.push("" + itemId);
      jQuery("li#item-" + c).data("dependencies", cDeps);
      tree[c].data.dependencies = cDeps;
    });
  });
  // unblock the item in the new location
  var blockedInParent = getDependencyBlockedLocations(itemId, parentId);
  lodash.each(blockedInParent, function (p) {
    var tmpDeps = jQuery("li#item-" + p).data("dependencies");
    tmpDeps.other.blocked = lodash.without(tmpDeps.other.blocked, "" + itemId);
    tree[p].data.dependencies = tmpDeps;
  });
  // finally we can rebuild the dependencies
  buildDependencies(tree);
  refreshData(); // TODO: check if this is even needed
};

var cluevoTagSelector = new Vue({
  el: "#cluevo-tag-selector",
  data() {
    return {
      tags: [],
      enabled: [],
      disabled: [],
      searchString: "",
      total: 0,
      showing: 0,
      hiding: 0,
      timer: null,
      haveSearched: false,
    };
  },
  mounted() {
    this.getPossibleTags();
    this.restoreSearch();
  },
  computed: {
    tagString() {
      const disabled = this.disabled.map((t) => `-${t}`);
      return [...this.enabled, ...disabled].join(", ");
    },
    tagList() {
      return [...this.enabled, ...this.disabled.map((t) => `-${t}`)];
    },
    displayString() {
      return [this.searchString, this.tagString].join(", ");
    },
  },
  methods: {
    saveSearch() {
      localStorage.setItem(
        "cluevo-tree-filter",
        JSON.stringify({
          searchString: this.searchString,
          enabled: this.enabled,
          disabled: this.disabled,
        })
      );
    },
    restoreSearch() {
      const restore = localStorage.getItem("cluevo-tree-filter");
      if (!restore) return;
      try {
        const data = JSON.parse(restore);
        const enabled = data.enabled.filter((d) => this.tags.includes(d));
        const disabled = data.disabled.filter((d) => this.tags.includes(d));
        const searchString = data.searchString;
        this.enabled = enabled;
        this.disabled = disabled;
        this.searchString = searchString;
        this.search();
      } catch (error) {
        console.error("failed to parse tree filter", error);
      }
    },
    handleSearch() {
      if (this.timer) clearTimeout(this.timer);
      this.timer = setTimeout(this.search, 500);
    },
    search() {
      this.haveSearched = true;
      this.saveSearch();
      jQuery("li.lms-tree-item").removeClass("search-result");
      if (
        this.searchString.trim() === "" &&
        !this.enabled.length &&
        !this.disabled.length
      ) {
        jQuery("li.lms-tree-item").fadeIn();
        this.haveSearched = false;
        return;
      }
      jQuery("li.lms-tree-item").fadeOut();
      this.showing = 0;
      this.enabled.forEach((t) => {
        document
          .querySelectorAll(`input.tags[value*="${t}" i]`)
          .forEach((el) => {
            jQuery(el).parents("li").fadeIn();
            jQuery(el).parents("li:first").addClass("search-result");
            this.showing++;
          });
      });
      this.disabled.forEach((t) => {
        document
          .querySelectorAll(`input.tags:not([value*="${t}" i])`)
          .forEach((el) => {
            jQuery(el).parents("li").fadeIn();
            jQuery(el).parents("li:first").addClass("search-result");
            this.showing++;
          });
      });
      this.searchString
        .split(", ")
        ?.filter?.(
          (s) =>
            !s.startsWith("#") && !s.startsWith("!#") && !s.startsWith("-#")
        )
        ?.forEach((n) => {
          const [t, hide] = [
            n.replace(/^[!-]/, ""),
            n.startsWith("!") || n.startsWith("-"),
          ];
          if (hide) {
            document
              .querySelectorAll(
                `input.module-name[type="text"]:not([value*="${t}" i])`
              )
              .forEach((el) => {
                jQuery(el).parents("li").fadeIn();
                jQuery(el).parents("li:first").addClass("search-result");
                this.showing++;
              });
          } else {
            document
              .querySelectorAll(
                `input.module-name[type="text"][value*="${t}" i]`
              )
              .forEach((el) => {
                jQuery(el).parents("li").fadeIn();
                jQuery(el).parents("li:first").addClass("search-result");
                this.showing++;
              });
          }
        });
    },
    getPossibleTags() {
      const inputs = Array.from(
        document.querySelectorAll('input[type="text"]:not([value=""]).tags')
      );
      let tags = [];
      if (inputs && inputs.length) {
        tags = inputs.flatMap((el) => {
          if (el.value && el.value != "") {
            return el.value
              ?.split(",")
              ?.map?.((t) => t.trim())
              ?.filter?.((t) => t && t != "");
          }
        });
        tags = [...new Set(tags)];
      }
      this.tags = tags;
    },
    enable(tag) {
      this.disabled = this.disabled.filter((t) => t !== tag);
      this.enabled = [...new Set([...this.enabled, tag])];
      this.handleSearch();
    },
    disable(tag) {
      this.enabled = this.enabled.filter((t) => t !== tag);
      this.disabled = [...new Set([...this.disabled, tag])];
      this.handleSearch();
    },
    removeTag(tag) {
      this.enabled = this.enabled.filter((t) => t !== tag);
      this.disabled = this.disabled.filter((t) => t !== tag && `-${t}` !== tag);
      this.handleSearch();
    },
    clearTags() {
      this.enabled = [];
      this.disabled = [];
      this.handleSearch();
    },
    clearSearch() {
      this.searchString = "";
      this.handleSearch();
    },
  },
  template: `
  <div class="cluevo-tree-tag-filter-container">
    <div class="cluevo-search-input-container">
      <div>
        <input v-model="searchString" type="text" class="cluevo-filter-input" @input="handleSearch" :placeholder="window.strings.filter_placeholder"/>
        <span class="dashicons dashicons-trash cluevo-filter-clear-button" @click="clearSearch"/>
      </div>
      <div class="cluevo-tree-tag-container">
        <span
          v-for="t of tagList"
          :key="t"
          class="cluevo-tree-tag"
          :class="{ negated: t.startsWith('-') || t.startsWith('!') }"
          @click="removeTag(t)"
        > {{ t }}</span>
      </div>
    </div>
    <details v-if="tags.length" >
      <summary>{{ tags.length }} {{ window.strings.tags }}</summary>
      <table class="cluevo-admin-table">
        <tr>
          <th>{{ window.strings.tag }}</th>
          <th>{{ window.strings.with_tag }}</th>
          <th>{{ window.strings.without_tag }}</th>
          <th>
            <span class="dashicons dashicons-trash" @click="clearTags"></span>
          </th>
          </tr>
          <tr v-for="t of tags" :key="t">
            <td>{{ t }}</td>
            <td>
              <input :value="t" type="radio" :name="t" :checked="enabled.includes(t)" @change="enable(t)" />
            </td>
            <td>
              <input :value="t" type="radio" :name="t" :checked="disabled.includes(t)" @change="disable(t)" />
            </td>
            <td>
              <span v-if="enabled.includes(t) || disabled.includes(t)" class="dashicons dashicons-trash" @click="removeTag(t)"></span>
            </td>
          </tr>
      </table>
    </details>
    <div v-if="haveSearched">{{ window.strings.results }}: {{ showing }}</div>
  </div>
  `,
});

jQuery(document).ready(function () {
  jQuery(".cluevo-ext-tease").mouseover(function () {
    jQuery(
      '#toplevel_page_cluevo-lms li a[href="admin.php?page=cluevo-ext-page'
    ).addClass("cluevo-ext-tease-glow");
  });
  jQuery(".cluevo-ext-tease").click(function () {
    cluevo_display_note(
      this,
      window.strings.get_cert_extension,
      800,
      3000,
      "https://wp-lms.cluevo.at/pricing",
      true
    );
  });
  jQuery(".cluevo-ext-tease").mouseleave(function () {
    jQuery(
      '#toplevel_page_cluevo-lms li a[href="admin.php?page=cluevo-ext-page'
    ).removeClass("cluevo-ext-tease-glow");
  });
  jQuery("#cluevo-clear-tags").hide();

  jQuery("#cluevo-clear-tags").click(function () {
    jQuery("#cluevo-tag-filter").val("");
    resetTagFilter();
  });

  jQuery("#cluevo-tag-filter").change(function () {
    const input = jQuery(this).val()?.trim?.();
    if (!input || input === "") {
      resetTagFilter();
      return;
    }
    jQuery("#cluevo-clear-tags").fadeIn();
    let search = input.split(",");
    if (search && search.length) {
      const tags = search
        .map((t) => t.trim().toLowerCase())
        .filter((t) => t.includes("#"))
        .map((t) => t.replace(/#/, ""));
      const other = search
        .map((t) => t.trim().toLowerCase())
        .filter((t) => !t.includes("#"));
      filterTags(tags, other);
    }
  });

  jQuery("#reset-dependencies").click(function () {
    jQuery('.dependency input[type="checkbox"]').prop("checked", false);
    jQuery('.dependency input[type="checkbox"]').prop("disabled", false);
    calcDeps();
  });

  jQuery("ol.sortable .up").click(function (e) {
    const li = jQuery(this).parents("li:first")[0];
    const prev = li.previousElementSibling;
    if (prev) {
      prev.before(li);
    }
  });
  jQuery("ol.sortable .down").click(function (e) {
    const li = jQuery(this).parents("li:first")[0];
    const next = li.nextElementSibling;
    if (next) {
      next.after(li);
    }
  });

  jQuery("ol.sortable").nestedSortable({
    handle: "div.handle .drag-handle",
    items: "li",
    disableParentChange: false,
    forcePlaceholderSize: true,
    toleranceElement: "> div",
    doNotClear: true,
    placeholder: "placeholder",
    opacity: 0.6,
    maxLevels: 3,
    isAllowed: function (placeholder, placeholderParent, currentItem) {
      var newParentId = jQuery(placeholderParent).data("id");
      var itemId = jQuery(currentItem).data("id");
      // always allow when we're not changing parents
      if (
        newParentId ===
        jQuery("li#item-" + itemId)
          .parents("li:first")
          .data("id")
      )
        return true;
      // only allow if we're not changing levels
      if (getLevel(placeholder) === getLevel(currentItem)) {
        var deps = getDependents(itemId);
        jQuery(placeholder).empty();
        // abort if there are dependencies
        if (deps.normal.length !== 0 || deps.inherited.length !== 0) {
          jQuery(placeholder).html(
            "<h1>Fehler</h1><p>Andere Elemente hängen von diesem Element ab, diese Abhängigkeiten müssen aufgelöst werden bevor das Element verschoben werden kann.</p>"
          );
          return false;
        }
        return true;
      } else {
        return false;
      }
    },
  });

  jQuery("select.tree-selection").change(function (e) {
    var url = jQuery(this).data("target");
    window.location = url + "&tree_id=" + jQuery(this).val();
  });

  jQuery("input#lms-tree-new").keyup(function (e) {
    var trees = [];
    jQuery("select.tree-selection option").each(function (i, el) {
      trees.push(jQuery(el).text().toLowerCase());
    });
    var val = jQuery(this).val().toLowerCase().trim();
    if (trees.includes(val) || val === "") {
      jQuery("#cluevo-create-course-structure-btn").attr("disabled", true);
    } else {
      jQuery("#cluevo-create-course-structure-btn").attr("disabled", false);
    }
  });

  // write input to data fields of tree items
  jQuery(".sortable").on("input", 'input[type="text"]', function () {
    var attr = jQuery(this).data("target");
    jQuery(this).parents("li").first().data(attr, jQuery(this).val());
    debouncedBuildDependencies();
    validate();
  });

  jQuery(".sortable").on("keydown", 'input[type="text"]', function (e) {
    if (e.keyCode === 13) {
      e.preventDefault();
    }
  });

  // write selected values to data fields of tree  items
  jQuery(".sortable").on("change", "select", function (e) {
    e.preventDefault();
    dirty = true;

    var attr = jQuery(this).data("target");
    jQuery(this).parents("li").first().data(attr, jQuery(this).val());
  });

  jQuery(".sortable").on(
    "change",
    '.login-required input[type="checkbox"]',
    function (e) {
      var checked = jQuery(this).prop("checked");
      jQuery(this)
        .parents("li:first")
        .find('.login-required input[type="checkbox"]')
        .prop("checked", checked);
    }
  );

  jQuery(".sortable").on(
    "change",
    ".dep-checkbox-container input",
    function (e) {
      //var deps = {modules: [], other: []};
      var itemId = jQuery(this).parents("li").first().data("id");
      var deps = jQuery("li#item-" + itemId).data("dependencies");
      dirty = true;

      var checkedId = jQuery(this).val();
      var isChecked = jQuery(this).prop("checked");
      var tree = getBasicTree();
      var isModule = jQuery(this).hasClass("module");

      var d = null;
      d = jQuery(this).val();

      if (isChecked) {
        // inherit the dependency down the items children
        if (isModule) {
          var moduleName = jQuery(
            "li#item-" + itemId + " select.available-modules:first"
          ).val();

          // get all modules that depend on the current module and disable the module as dependency in those modules
          blockModuleDependency(moduleName, d, itemId, true);

          // inherit the dependencies dependencies
          var depDeps = getModuleDependencies(d);
          lodash.each(depDeps.normal, function (dep) {
            blockModuleDependency(dep, moduleName, itemId, true);
            inheritModuleDependencies(dep, moduleName);
          });
          lodash.each(depDeps.inherited, function (dep) {
            blockModuleDependency(dep, moduleName, itemId, true);
            inheritModuleDependencies(dep, moduleName);
          });

          handleModuleDependency(moduleName, d, isChecked);
        } else {
          inheritDependency(d, itemId, isChecked, itemId);
          inheritDependencyChildren(d, itemId, isChecked);

          // all items that require the current item must now inherit the dependency
          var reverse = getDependents(itemId);
          lodash.each(reverse.normal, function (r) {
            inheritDependency(d, r, isChecked);
            inheritDependencyChildren(d, r, isChecked);

            // inherit the dependencies of the dependency to the item and its children
            var depDependencies = getAllDependencies(d);
            lodash.each(depDependencies, function (dep) {
              inheritDependency(dep, r, isChecked);
              inheritDependencyChildren(dep, r, isChecked);
            });
          });

          lodash.each(reverse.inherited, function (r) {
            inheritDependency(d, r, isChecked);
            inheritDependencyChildren(d, r, isChecked);

            // inherit the dependencies of the dependency to the item and its children
            var depDependencies = getAllDependencies(d);
            lodash.each(depDependencies, function (dep) {
              inheritDependency(dep, r, isChecked);
              inheritDependencyChildren(dep, r, isChecked);
            });
          });

          // the current item and its children need to inherit whatever the dependency depends on
          var depDependencies = getAllDependencies(d);
          for (let dep in depDependencies) {
            inheritDependency(dep, itemId, isChecked);
            inheritDependencyChildren(dep, itemId, isChecked);
          }
        }
      } else {
        if (isModule) {
          var moduleName = jQuery(
            "li#item-" + itemId + " select.available-modules:first"
          ).val();
          blockModuleDependency(d, moduleName, null, false);
          var deps = getModuleDependencies(d);
          lodash.each(deps.normal, function (dep) {
            blockModuleDependency(dep, moduleName, null, false);
            blockModuleDependency(moduleName, dep, null, false);
          });
          lodash.each(deps.inherited, function (dep) {
            blockModuleDependency(dep, moduleName, null, false);
            blockModuleDependency(moduleName, dep, null, false);
          });
        } else {
          removeDependency(d, itemId);
          removeInheritedDependencies(d, itemId);
          removeDependencyChildren(d, itemId);
          unblockItem(d, itemId);
          var blockedChildren = getAllChildren(itemId);
          lodash.each(blockedChildren, function (c) {
            unblockItem(d, c);
          });

          // unblock the current item in all items that inherited this item as blocked dependency
          var reverse = getDependencies(d);
          lodash.each(reverse.other.normal, function (r) {
            unblockItem(r, itemId);
            lodash.each(blockedChildren, function (c) {
              unblockItem(r, c);
            });
          });
          lodash.each(reverse.other.inherited, function (r) {
            unblockItem(r, itemId);
            lodash.each(blockedChildren, function (c) {
              unblockItem(r, c);
            });
          });
        }
      }

      calcDeps();

      return;
    }
  );

  // outline dependency in tree on checkbox hover
  jQuery(".sortable").on(
    "mouseover",
    ".dep-checkbox-container label",
    function (e) {
      outlineDependency(this);
    }
  );

  // remove dependency outline in tree on mouseout
  jQuery(".sortable").on(
    "mouseout",
    ".dep-checkbox-container label",
    function (e) {
      outlineDependency(this);
    }
  );

  // add new items to the tree
  jQuery(".sortable").on("click", ".add", function (e) {
    e.preventDefault();
    if (!jQuery(this).attr("disabled")) {
      addItem(jQuery(this));
    }
  });

  jQuery(".sortable").on(
    "mouseover",
    '.add[disabled!="disabled"]',
    function (e) {
      jQuery(this).parents("li:first").addClass("hover");
    }
  );

  jQuery(".sortable").on("mouseleave", ".add", function (e) {
    jQuery(this).parents("li:first").removeClass("hover");
  });

  // remove items from the tree
  jQuery(".sortable").on("click", ".remove", function (e) {
    e.preventDefault();
    dirty = true;

    // first click changes icon to question mark, click again to confirm deletion
    if (window.confirm(strings.delete_item)) {
      var id = jQuery(this).parents("li:first").data("item-id");
      var url = cluevoWpApiSettings.root + "cluevo/v1/items/" + id + "/delete";
      var btn = jQuery(this);
      jQuery.ajax({
        url: url,
        method: "DELETE",
        contentType: "application/json",
        dataType: "json",
        beforeSend: function (xhr) {
          xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
        },
        success: function (response) {
          if (response === true) {
            var list = jQuery(btn).parents("ol:first");
            jQuery(btn).parents("li:first").remove();
            if (jQuery(list).children("li").length == 0) {
              jQuery(list)
                .parents("li.lms-tree-item:first")
                .find(".cluevo-make-module:first")
                .removeClass("hidden");
            }
          } else {
            alert("An error occurred while trying to delete this item");
            console.error("delete failed");
          }
        },
      });
      debouncedBuildDependencies();
    }
  });

  // expand, collapse tree items
  jQuery(".sortable").on("click", ".expand", function (e) {
    e.preventDefault();
    if (jQuery(this).hasClass("disabled")) return;
    var item = jQuery(this).parents("li").first().find("ol").first();
    jQuery(this).toggleClass("collapsed");
    jQuery(item).toggleClass("collapsed");
  });

  // toggle meta area (dependencies)
  jQuery(".sortable").on("click", ".meta-toggle", function (e) {
    e.preventDefault();
    var container = jQuery(this).parents("li").first().find(".meta").first();
    jQuery(container).toggleClass("active");
    var height = 0;
    if (jQuery(container).hasClass("active")) {
      height = jQuery(container).find(".meta-content-container").outerHeight();
      jQuery(container).animate(
        {
          "min-height": height,
        },
        200,
        function () {
          jQuery(container).css("height", "unset");
        }
      );
    } else {
      jQuery(container).animate(
        {
          height: 0,
        },
        200,
        function () {
          jQuery(container).css("min-height", "unset");
        }
      );
    }
  });

  jQuery(".sortable").on("change", "select.available-modules", function (e) {
    var tree = getFlatTree();
    var id = jQuery(this).parents("li:first").data("item-id");
    buildItemDependencies(tree, id);
  });

  jQuery(".sortable").on(
    "change",
    'li[data-type="module"] .meta-container.global input, .meta-container.global select',
    function (e) {
      var moduleName = jQuery(this)
        .parents("li:first")
        .find("select.available-modules")
        .val();
      var target = jQuery(this).data("target");
      var value = jQuery(this).val();
      var items = jQuery(
        'ol.sortable li select.available-modules option[value="' +
          moduleName +
          '"]:selected'
      );
      lodash.each(items, function (item) {
        var parent = jQuery(item).parents("li:first");
        jQuery(parent)
          .find('[data-target="' + target + '"]')
          .val(value);
      });
    }
  );

  // write tree data to hidden fields to save as wp setting
  jQuery("form#tree-form").submit(function (e) {
    if (jQuery(this).hasClass("warn-rebuild")) {
      if (!confirm(strings.tree_rebuild_warning)) {
        return false;
      }
    }
    refreshData();

    var treeId = jQuery("ol.sortable").data("tree-id");
    var treeName = jQuery("#lms-tree-name-input").val().trim();

    if (treeName === "" || treeName.length < 1) {
      alert(strings.course_group_name_cant_be_empty);
      return false;
    }

    jQuery("#lms-tree-id").val(treeId);
    jQuery("#lms-tree-name").val(treeName);

    var tree = jQuery("ol.sortable").nestedSortable("toHierarchy");
    var obj = [];
    for (var i in tree) {
      if (tree.hasOwnProperty(i)) {
        obj[i] = decamelizeObject(tree[i]);
      }
    }
    var arr = jQuery("ol.sortable").nestedSortable("toArray");

    // tree as object
    jQuery('input[name="lms-tree"]').val(
      JSON.stringify(obj)
    );

    // tree as flat object
    flatObj = getFlatTree(true);
    var result = {};
    for (var key in flatObj) {
      if (flatObj.hasOwnProperty(key)) {
        var settings = getSettings(key);
        result[key] = {
          children: flatObj[key].children,
          depth: flatObj[key].depth,
          id: flatObj[key].id,
          tree_id: treeId,
          tree_name: treeName,
          parent_id: flatObj[key].parent_id,
          path: flatObj[key].path,
          dependencies: flatObj[key].dependencies,
          modules: { id: [], string: [] },
          settings: settings,
          sort_order: jQuery("ol.sortable li").index(
            jQuery("#item-" + flatObj[key].id)
          ),
        };
        for (var d in flatObj[key].data) {
          // move data attributes out of data array
          if (flatObj[key].data.hasOwnProperty(d)) {
            var snakeKey = lodash.snakeCase(d);
            result[key][snakeKey] = flatObj[key].data[d];
          }
        }
      }
    }

    // build a list of contained modules for each hierarchy
    for (var item in result) {
      if (result.hasOwnProperty(item)) {
        let mId = result[item].module_id;
        if (mId && mId > 0) {
          result[item].path.id.forEach(function (t, index) {
            if (result.hasOwnProperty(t)) {
              if (result[t].modules.string.indexOf(result[item].name) === -1) {
                result[t].modules.string.push(result[item].name);
              }
              if (result[t].modules.id.indexOf(mId > 0)) {
                result[t].modules.id.push(mId.toString());
              }
            }
          });
        }
      }
    }

    jQuery('input[name="lms-tree-flat"]').val(
      JSON.stringify(result)
    );
    //return false;

    return true;
  });

  // add new course
  jQuery(".add-course").click(function (e) {
    e.preventDefault();
    addItem(jQuery(".root.sortable"));
  });

  jQuery(".sortable").on("click", ".metadata-edit-link", function (e) {
    if (dirty === true) {
      if (
        !confirm(
          "Seite verlassen? Nicht gespeicherte Änderungen gehen verloren."
        ) === true
      ) {
        e.preventDefault();
      }
    }
  });

  jQuery(".sortable").on("change", ".shortcode-link", function (e) {
    var item = jQuery(this).parents("li").first();

    var str = getShortcode(item);

    jQuery(this)
      .parents("div.shortcode-container")
      .first()
      .find("code")
      .html(cluevoEncodeHTML(str)); // xss?
  });

  jQuery(".sortable").on("change", ".shortcode-no-deps", function (e) {
    var item = jQuery(this).parents("li").first();

    var str = getShortcode(item);

    jQuery(this)
      .parents("div.shortcode-container")
      .first()
      .find("code")
      .html(cluevoEncodeHTML(str)); // xss?
  });

  // copy shortcode to clipboard
  jQuery(".sortable").on("click", ".copy-shortcode", function (e) {
    var textArea = document.createElement("textarea");
    textArea.value =
      '[cluevo item="' +
      jQuery(this).parents("li").first().data("item-id") +
      '"]';
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand("copy");
    document.body.removeChild(textArea);
    var note = document.createElement("div");
    note.className = "shortcode-copy-notification";
    note.innerHTML = cluevoEncodeHTML(strings.shortcode_copied); // xss?
    note.style.position = "absolute";
    note.style.width = "auto";
    var pos = jQuery(this).offset();
    note.style.opacity = 0;
    document.body.appendChild(note);
    note.style.left = pos.left - note.offsetWidth / 2 + "px";
    //note.style.display = 'none';
    var top = pos.top - jQuery(this).height();
    note.style.top = top + "px";
    jQuery(note).animate(
      {
        top: top - 10,
        opacity: 1,
      },
      800,
      function () {
        jQuery(note).fadeOut(300, function () {
          document.body.removeChild(note);
        });
      }
    );
  });

  jQuery(".sortable").on(
    "change",
    ".display-mode-container select",
    function (e) {
      var container = jQuery(this)
        .parents(".meta-container.display-mode")
        .find(".input-container.iframe-position");

      if (
        e.target.value === "iframe" ||
        (e.target.value === "" && jQuery(container).hasClass("forced"))
      ) {
        jQuery(container).removeClass("hidden");
        jQuery(container).addClass("visible");
      } else {
        if (
          e.target.value !== "iframe" ||
          (e.target.value === "" && !jQuery(container).hasClass("forced"))
        ) {
          jQuery(container).addClass("hidden");
          jQuery(container).removeClass("visible");
        }
      }
    }
  );

  jQuery(".sortable").on("click", ".cluevo-btn.publish", function (e) {
    var item = jQuery(this).parents("li:first");
    var published = jQuery(item).data("published");

    jQuery(item).data("published", !published);
    jQuery(this).find(".dashicons:first").toggleClass("dashicons-visibility");
    jQuery(this).find(".dashicons:first").toggleClass("dashicons-hidden");
    jQuery(item).toggleClass("published");
    jQuery(item).toggleClass("draft");
    if (published) {
      jQuery(this).attr("title", strings.draft);
      jQuery(item).find("li.lms-tree-item").data("published", 0);
      jQuery(item).find("li.lms-tree-item").removeClass("published");
      jQuery(item).find("li.lms-tree-item").addClass("draft");
      jQuery(item)
        .find("li.lms-tree-item .cluevo-btn.publish .dashicons")
        .removeClass("dashicons-visibility");
      jQuery(item)
        .find("li.lms-tree-item .cluevo-btn.publish .dashicons")
        .addClass("dashicons-hidden");
    } else {
      jQuery(this).attr("title", strings.published);
      if (!jQuery(item).parents("li:first").data("published")) {
        jQuery(item).parents("li").data("published", 1);
        jQuery(item).parents("li").removeClass("draft");
        jQuery(item)
          .parents("li")
          .find(".cluevo-btn.publish:first .dashicons")
          .addClass("dashicons-visibility");
        jQuery(item)
          .parents("li")
          .find(".cluevo-btn.publish:first .dashicons")
          .removeClass("dashicons-hidden");
      }
      if (e.ctrlKey) {
        jQuery(item).find("li.lms-tree-item").data("published", 1);
        jQuery(item).find("li.lms-tree-item").removeClass("draft");
        jQuery(item).find("li.lms-tree-item").addClass("published");
        jQuery(item)
          .find("li.lms-tree-item .cluevo-btn.publish .dashicons")
          .removeClass("dashicons-hidden");
        jQuery(item)
          .find("li.lms-tree-item .cluevo-btn.publish .dashicons")
          .addClass("dashicons-visibility");
      }
    }
  });

  //jQuery('li.lms-tree-item').on('change-module', function(e) {
  //console.log("module-change");
  //});

  jQuery("ol.course-structure.root").on(
    "click",
    ".cluevo-btn.cluevo-make-module, .cluevo-edit-module",
    function (e) {
      if (jQuery(this).hasClass("disabled")) return;
      var itemId = jQuery(this).parents("li:first").data("id");
      moduleSelector.itemId = itemId;
      moduleSelector.active = true;
    }
  );

  jQuery("ol.course-structure.root").on(
    "click",
    ".title .remove-module",
    function (e) {
      var item = jQuery(this).parents("li:first");
      var id = parseInt(jQuery(item).data("item-id"), 10);
      var url =
        cluevoWpApiSettings.root +
        "cluevo/v1/items/" +
        encodeURIComponent(id) +
        "/remove-module";

      jQuery.ajax({
        url: url,
        method: "GET",
        contentType: "application/json",
        dataType: "json",
        beforeSend: function (xhr) {
          xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
        },
        success: function (response) {
          jQuery(item).find(".cluevo-make-module:first").removeClass("hidden");
          jQuery(item).removeClass("module-assigned");
          jQuery(item).data("module-id", -1);
          jQuery(item).find(".cluevo-btn.add:first").attr("disabled", false);
          jQuery(item).find("div.module-name-label:first").addClass("hidden");
          jQuery(item).find("div.module-name-label:first .content").text("");
          jQuery(item)
            .find("[data-module-only] input")
            .each(function (_, el) {
              jQuery(el).val(null);
              jQuery(el).attr("checked", false);
              jQuery(el).attr("selected", false);
            });
          jQuery(item)
            .find("[data-module-only]")
            .each(function (_, el) {
              jQuery(el).addClass("hidden");
            });
          var tree = getFlatTree();
          buildDependencies(tree);
        },
      });
    }
  );

  jQuery(".sortable").on(
    "keyup",
    '.setting[name="item-is-link"]',
    function (e) {
      if (jQuery(this).val().trim() == "") {
        jQuery(this).parents("li:first").attr("data-item-is-link", 0);
        jQuery(this)
          .parents("li:first")
          .find(".cluevo-make-module:first")
          .removeClass("disabled");
      } else {
        jQuery(this).parents("li:first").attr("data-item-is-link", 1);
        jQuery(this)
          .parents("li:first")
          .find(".cluevo-make-module:first")
          .addClass("disabled");
      }
    }
  );

  // build dependency lists for all tree items
  var tree = getFlatTree();
  try {
    buildDependencies(tree);
  } catch (error) {
    console.error("failed to build tree dependencies", error);
  }
});

// refreshes data attributes of the tree. looks for input fields etc. and writes the values to the item's data attributes
function refreshData() {
  jQuery(
    ".sortable input.sortable-name:visible, .sortable select.available-modules:visible, .sortable .points input, .sortable .level input, .repeating input, .repeating select, .login-required input"
  ).each(function (index, el) {
    var attr = jQuery(el).data("target");
    var val =
      jQuery(el).attr("type") === "checkbox"
        ? jQuery(el).prop("checked")
        : jQuery(el).val();
    jQuery(el).parents("li").first().data(attr, val);
  });
}

// builds dependencies for the the complete tree
function buildDependencies(tree) {
  // we get the complete tree and iterate through all items
  jQuery("ol.sortable .dep-checkbox-container").empty();
  for (var id in tree) {
    buildItemDependencies(tree, id);
  }
  flagDependencies(tree);
}

// build an items dependencies
function buildItemDependencies(tree, id, possible) {
  var html = "";
  if (tree.hasOwnProperty(id)) {
    // get the possible dependencies for the current item and write the options
    var deps = possible || getPossibleDependencies(tree[id], tree);

    if (tree[id].data === undefined) tree[id].data = {};

    if (tree[id].data.dependencies.other.blocked === undefined)
      tree[id].data.dependencies.other.blocked = {};

    depthLabels.forEach(function (label, index) {
      let tmpModuleId = jQuery("#item-" + id).data("module-id");
      //if (tree[id].data.level < 3 && index == 2) {
      if (index == 2 && (!tmpModuleId || tmpModuleId < 0)) {
        return;
      }
      html += "<fieldset><legend>" + cluevoEncodeHTML(label) + "</legend>";
      if (index < depthLabels.length - 1) {
        deps[label].forEach(function (item, i) {
          var checked = "";
          var disabled = "";
          if (tree[id].data) {
            if (tree[id].data.dependencies.other) {
              if (tree[id].data.dependencies.other.normal) {
                checked =
                  tree[id].data.dependencies.other.normal.hasOwnProperty(
                    "" + item.id
                  ) ||
                  tree[id].data.dependencies.other.inherited.hasOwnProperty(
                    "" + item.id
                  )
                    ? "checked"
                    : "";
                if (
                  tree[id].data.dependencies.other.inherited.hasOwnProperty(
                    "" + item.id
                  ) ||
                  tree[id].data.dependencies.other.blocked.hasOwnProperty(
                    "" + item.id
                  )
                ) {
                  disabled = "disabled";
                }
              }
            }
          }
          html +=
            '<label class="dependency"><input class="dependency" type="checkbox" ' +
            checked +
            " " +
            disabled +
            ' value="' +
            cluevoEncodeHTML(item.id) +
            '" />' +
            cluevoEncodeHTML(tree[item.id].path.string.join(" ⯈ ")) +
            "</label>";
        });
      } else {
        let tmpModuleId = jQuery("#item-" + id).data("module-id");
        if (tmpModuleId && tmpModuleId > 0) {
          deps[label].forEach(function (item, i) {
            var moduleId = "" + parseInt(item.module_id, 10);
            var name = cluevoEncodeHTML(item.module_name);
            var checked = "";
            var disabled = "";
            var curModuleId = parseInt(
              jQuery(
                "#item-" + id + " select.available-modules option:selected"
              ).val(),
              10
            );
            if (tree[id].data) {
              if (tree[id].data.dependencies.modules) {
                checked = tree[
                  id
                ].data.dependencies.modules.normal.hasOwnProperty(moduleId)
                  ? "checked"
                  : "";
                if (
                  tree[id].data.dependencies.modules.inherited.hasOwnProperty(
                    moduleId
                  )
                ) {
                  disabled = "disabled";
                }
              }
            }
            if (curModuleId !== moduleId) {
              html +=
                '<label class="dependency module"><input class="dependency module" type="checkbox" ' +
                checked +
                " " +
                disabled +
                ' value="' +
                moduleId +
                '" />' +
                name +
                "</label>";
            }
          });
        }
      }
      html += "</fieldset>";
    });
    jQuery("li#item-" + id)
      .find(".dep-checkbox-container:first") // :first is pretty important... without this it replaces the dependencies of all children...
      .html(html);
  }
}

// creates a flat object from the item tree. returns an object where each property is the key to an item.
function getFlatTree(refresh) {
  var refresh = refresh || false;
  // refresh data so data attributes contain correct data
  if (refresh) {
    refreshData();
  }

  // we create an array to get a flat structure
  var arr = jQuery("ol.sortable").nestedSortable("toArray");
  var flatObj = {};
  // move through the array and get all data attributes for the current item
  arr.map(function (item) {
    item.children = []; // needed for later because the array lists no children but the hierarchy does
    item.data = jQuery("#item-" + item.id).data();
    item.path = { id: [], string: [] };
    var label =
      depthLabels.length > item.depth ? depthTypes[item.depth] : "undefined";
    //if (item.data) item.data['type'] = label;
    if (item.id) flatObj[item.id] = item;
    return item;
  });

  // iterate through the flat object and add the children to the children array
  for (var key in flatObj) {
    if (flatObj.hasOwnProperty(key)) {
      if (flatObj[key].parent_id) {
        if (flatObj.hasOwnProperty(flatObj[key].parent_id)) {
          flatObj[flatObj[key].parent_id].children.push(key);
        }
      }
      if (flatObj[key].data) {
        var treeId = jQuery("ol.sortable").data("tree-id");
        var treeName = jQuery("#lms-tree-name-input").val();
        var path = getPath(flatObj[key], flatObj, {
          id: [treeId, key],
          string: [treeName, flatObj[key].data.name],
        });
        flatObj[key].path.id = path.id.concat(flatObj[key].path.id);
        flatObj[key].path.string = path.string.concat(flatObj[key].path.string);
      }
    }
  }

  return flatObj;
}

function getSettings(key) {
  var settings = {};
  jQuery(".sortable li#item-" + key + " .meta:first")
    .find(".setting")
    .each(function (index, el) {
      var target = jQuery(el).data("target");
      if (jQuery(el).attr("type") === "checkbox") {
        var value = jQuery(el).is(":checked");
      } else {
        var value = jQuery(el).val();
      }
      if (target) {
        settings[target] = value;
      }
    });
  return settings;
}

function getBasicTree() {
  var arr = jQuery("ol.sortable").nestedSortable("toArray");
  var flatObj = {};
  arr.map(function (item) {
    item.children = []; // needed for later because the array lists no children but the hierarchy does
    if (item.id) flatObj[item.id] = item;
  });
  for (var key in flatObj) {
    if (flatObj.hasOwnProperty(key)) {
      if (flatObj[key].parent_id) {
        if (flatObj.hasOwnProperty(flatObj[key].parent_id)) {
          flatObj[flatObj[key].parent_id].children.push(key);
        }
      }
    }
  }
  return flatObj;
}

// returns a list of possible dependencies for a given item
function getPossibleDependencies(item, tree) {
  // get a list of impossible dependencies to check against
  var impossible = getImpossibleDependencies(item, [], tree);

  //var result = {course: [], chapter: [], module: []};
  var result = {};
  depthLabels.forEach(function (label, index) {
    result[label] = [];
  });

  // iterate through the flat object
  for (let id in tree) {
    if (tree.hasOwnProperty(id)) {
      if (impossible.indexOf(id) === -1) {
        if (tree[id].data !== undefined) {
          if (
            tree[id].depth < depthLabels.length - 1 &&
            id !== "0" &&
            id !== item.id
          ) {
            // dependency is valid if it's not a module, the id of the dependency does not match the item id and the dependency is not contained in the impossible dependencies
            result[depthLabels[tree[id].depth]].push(tree[id]);
          }
        }
      }
    }
  }

  // add module dependencies, these are based on the modules themselves not on the tree items that contain them
  if (item.data !== undefined) {
    // if the item is module we need to filter it out of the available modules, it can't be dependent on itself
    if (item.depth === depthLabels.length - 1) {
      var res = modules.filter(function (el) {
        return el !== item.data.name;
      });
      result[depthLabels[depthLabels.length - 1]] = res;
    } else {
      // if the item is not a module it can depend on every available module
      result[depthLabels[depthLabels.length - 1]] = modules;
    }
  }

  return result;
}

// returns a list of impossible dependencies for a given item
function getImpossibleDependencies(item, list, tree) {
  // get all the parents of the given item from the flat object
  var items = getParents(item, tree, []);
  // get all the children of the given item from the flat object
  var children = getChildren(tree[item.id], tree, []);
  //var path = getPath(item, tree, item.data.name);
  return items.concat(children); // concat and return
}

// recursively get's an items parents
function getParents(item, obj, list) {
  list.push(item.parent_id);
  if (obj.hasOwnProperty(item.parent_id)) {
    list.concat(getParents(obj[item.parent_id], obj, list));
  }

  return list;
}

// get the path of a tree item recursively
function getPath(item, obj, path) {
  if (obj.hasOwnProperty(item.parent_id)) {
    var parent = obj[item.parent_id];
    nextPath = getPath(obj[item.parent_id], obj, {
      id: parent.path.id,
      string: parent.path.string,
    });
    path.id = nextPath.id.concat(item.id.toString());
    path.string = nextPath.string.concat(item.data.name);
  }
  return path;
}

// recursively get's an items children
function getChildren(item, obj, list) {
  item.children.forEach(function (key, index) {
    if (obj.hasOwnProperty(key)) {
      list.push(key);
      if (obj[key].children) {
        list.concat(getChildren(obj[key], obj, list));
      }
    }
  });
  return list;
}

// get the highest id in the tree
function getHighestId() {
  var highest = 0;
  var treeId = parseInt(jQuery("ol.sortable").data("tree-id"), 10);
  highest = treeId;
  jQuery(".lms-tree-item").each(function (index, item) {
    var id = jQuery(item).attr("id");
    var count = parseInt(id.replace("item-", ""), 10);
    highest = count > highest ? count : highest;
  });
  return highest;
}

// returns the next id in the tree
function getNextId() {
  return getHighestId() + 1;
  return lodash.uniqueId("new-");
}

// add a new item at the target location
function addItem(target) {
  dirty = true;
  var isRoot = jQuery(target).hasClass("root") ? true : false;
  var id = null;
  if (!isRoot) {
    var parentItem = jQuery(target).parents("li:first");
    var id = jQuery(parentItem).data("item-id");
  } else {
    var id = jQuery("ol.sortable").data("tree-id");
  }
  id = parseInt(id, 10);

  var url =
    cluevoWpApiSettings.root +
    "cluevo/v1/items/" +
    encodeURIComponent(id) +
    "/create";

  jQuery.ajax({
    url: url,
    method: "GET",
    contentType: "application/json",
    dataType: "json",
    beforeSend: function (xhr) {
      xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
    },
    success: function (response) {
      jQuery(target)
        .parents("li.lms-tree-item:first")
        .find(".cluevo-make-module:first")
        .addClass("hidden");
      var nextId = parseInt(response.item_id, 10);
      var metaId = parseInt(response.meta_id, 10);
      if (!isNaN(nextId)) {
        var clone = jQuery("#item-tpl").clone();

        var level = isRoot ? 1 : getLevel(target) + 1;
        nextLevel = level + 1;

        var name = "";
        switch (level) {
          case 1:
            name = strings.new_course;
            break;
          case 2:
            name = strings.new_chapter;
            break;
          case 3:
            name = strings.new_module;
            break;
          default:
            name = strings.new_course;
        }
        // can't access data attributes with data() at this point because the element is not yet in the dom
        jQuery(clone).attr("id", "item-" + parseInt(nextId, 10));
        jQuery(clone).attr("data-id", parseInt(nextId, 10));
        jQuery(clone).attr("data-item-id", parseInt(nextId, 10));
        jQuery(clone).attr("data-metadata-id", parseInt(metaId, 10));
        jQuery(clone).attr("data-type", depthTypes[level - 1]);
        jQuery(clone).attr(
          "data-name",
          name + " (" + parseInt(itemsAdded, 10) + ")"
        );
        jQuery(clone)
          .find("input.sortable-name")
          .val(name + " (" + parseInt(itemsAdded, 10) + ")");
        jQuery(clone).attr("data-level", parseInt(level, 10));
        if (level > 2) {
          jQuery(clone).find(".cluevo-btn.add, .cluevo-btn.expand").remove();
        }
        jQuery(clone)
          .find("ol")
          .first()
          .attr("data-level", parseInt(nextLevel, 10))
          .attr("id", "level-" + parseInt(nextLevel, 10));
        jQuery(clone).find("ol").first().data("level", parseInt(nextLevel, 10));
        jQuery(clone)
          .find(".title .copy-shortcode")
          .text("[" + parseInt(nextId, 10) + "]");
        if (level == depthLabels.length) {
          jQuery(clone).find(".title input, .title select").remove("input");
          var html =
            '<input type="text" data-target="name" class="module-name sortable-name" value="' +
            cluevoEncodeHTML(name) +
            " (" +
            parseInt(itemsAdded, 10) +
            ')" />';
          jQuery(clone).find(".title").prepend(html);
          var metaClone = jQuery(".meta-container.repeating.template");
          jQuery(metaClone).removeClass("template");
          jQuery(clone).find(".meta").append(jQuery(metaClone));
        }
        jQuery(clone).find(".handle .buttons a.cluevo-btn-primary").remove();
        if (!isRoot) {
          // inherit parents dependencies
          var deps = getDependencies(
            jQuery(target).parents("li:first").data("id")
          );
          jQuery(clone).data("dependencies", deps);
          jQuery(target)
            .parents("li")
            .first()
            .find("ol")
            .first()
            .append(jQuery(clone));
        } else {
          jQuery(target).append(jQuery(clone));
        }

        itemsAdded++;

        debouncedBuildDependencies();
      } else {
        alert("An error occurred while trying to a new item");
        console.error("failed to create new item");
      }
    },
  });
}

// highlight the item of the dependency the mouse is over
function outlineDependency(dep) {
  var val = jQuery(dep).find('input[type="checkbox"]').val();
  jQuery(".sortable #item-" + val).toggleClass("outline");
  jQuery(".sortable select.available-modules:visible").each(function (i, el) {
    if (jQuery(el).val() === val) {
      jQuery(el).parents("li").first().toggleClass("outline");
    }
  });
}

// returns the level of an item
function getLevel(item) {
  var str = jQuery(item).parents("ol").first().data("level");
  str = str === undefined ? 0 : str;
  return parseInt(str, 10);
}

// returns the items next level
function getNextLevel(item) {
  var str = jQuery(item).parents("li").first().find("ol").first().data("level");
  str = str === undefined ? 0 : str;
  return parseInt(str, 10);
}

// builds an items shortcode
function getShortcode(item) {
  var link = jQuery("#item-" + item.data("id"))
    .find(".shortcode-link")
    .first()
    .is(":checked")
    ? "display=link"
    : "";
  var attr = "id";
  var name = item.data("id");

  if (item.data("type") === "module") {
    attr = jQuery("#item-" + item.data("id"))
      .find(".shortcode-no-deps")
      .first()
      .is(":checked")
      ? "name"
      : "id";
    name = item.data(attr);
  }
  var item = jQuery(this).parents("li").first();

  var str = "[" + shortcode + " ";

  str += attr + '="' + name + '"';

  if (link !== "") str += " " + link;

  str += "]";

  return str;
}

// basic validation
var validate = lodash.debounce(function () {
  jQuery(".validation-error").removeClass("validation-error");
  jQuery("#submit").removeClass("disabled");
  //jQuery('#submit').prop('disabled', false);

  dirty = true;

  var tree = getFlatTree(true);

  for (let id in tree) {
    if (tree.hasOwnProperty(id)) {
      if (lodash.trim(tree[id].data.name) === "") {
        jQuery("#item-" + id + " input.sortable-name:first").addClass(
          "validation-error"
        );
        //jQuery('#submit').prop('disabled', true);
        jQuery("#submit").addClass("disabled");
      }
    }
  }
}, 300);

var debouncedBuildDependencies = lodash.debounce(function () {
  var tree = getFlatTree(true);
  buildDependencies(tree);
  calcDeps();
}, 300);

function flagDependencies(tree) {
  for (var id in tree) {
    var hasDeps = false;
    if (!tree[id]) continue;
    if (!tree[id].data) continue;
    if (!tree[id].data.dependencies) continue;
    var deps = tree[id].data.dependencies;
    for (var type in deps) {
      for (var key in deps[type]) {
        if (
          (typeof deps[type][key] == "object" &&
            Object.keys(deps[type][key]).length > 0) ||
          deps[type][key].length > 0
        ) {
          hasDeps = true;
          break;
        }
      }
      if (hasDeps) break;
    }
    if (hasDeps) {
      jQuery("li#item-" + id).addClass("has-dependencies");
    } else {
      jQuery("li#item-" + id).removeClass("has-dependencies");
    }
  }
}

function getDependents(id) {
  var deps = { normal: {}, inherited: {} };
  jQuery('ol.sortable input[type="checkbox"][value="' + id + '"]:checked').each(
    function (i, box) {
      if (jQuery(box).attr("disabled") === "disabled")
        deps.inherited[jQuery(box).parents("li:first").data("id")] = false;
      else deps.normal[jQuery(box).parents("li:first").data("id")] = false;
    }
  );
  return deps;
}

function getModuleDependents(module) {
  var deps = { normal: {}, inherited: {} };
  jQuery(
    'ol.sortable input.dependency.module[value="' + module + '"]:checked'
  ).each(function (i, box) {
    if (jQuery(box).attr("disabled") === "disabled")
      deps.inherited[jQuery(box).parents("li:first").data("id")] = false;
    else deps.normal[jQuery(box).parents("li:first").data("id")] = false;
  });
  return deps;
}

function getAllChildren(id) {
  var result = [];
  jQuery("ol.sortable li#item-" + id + " li").each(function (i, c) {
    result.push(jQuery(c).data("id"));
  });
  return result;
}

function inheritDependency(dep, id, state, itemId) {
  itemId = itemId || false;
  jQuery(
    "li#item-" +
      parseInt(id, 10) +
      ' .dependency-container input.dependency:not(.module)[value="' +
      parseInt(dep, 10) +
      '"]'
  ).each(function (i, box) {
    jQuery(box).prop("checked", state);
    jQuery(box)
      .parents("li")
      .each(function (i, p) {
        blockDependency(dep, jQuery(p).data("id"));
      });
  });
  jQuery(
    "li#item-" +
      parseInt(id, 10) +
      ' .dependency-container input.dependency:not(.module)[value="' +
      parseInt(dep, 10) +
      '"]'
  ).each(function (i, box) {
    if (jQuery(box).parents("li:first").data("id") !== itemId) {
      jQuery(box).attr("disabled", state);
    }
  });
}

// inherit a dependencies children as dependencies
function inheritDependencyChildren(dep, id, state) {
  var depChildren = getAllChildren(dep);
  lodash.each(depChildren, function (d) {
    inheritDependency(d, id, state);
  });
}

// block an id as dependency inside a dependency
function blockDependency(dep, id) {
  jQuery(
    "ol.sortable li#item-" +
      parseInt(dep, 10) +
      ' input.dependency:not(.module)[value="' +
      parseInt(id, 10) +
      '"]'
  ).each(function (i, box) {
    jQuery(box).prop("checked", false);
    jQuery(box).attr("disabled", true);
  });
}

// gets an ids dependencies
function getDependencies(id) {
  var result = {
    other: { normal: {}, inherited: {}, blocked: {} },
    modules: { normal: {}, inherited: {}, blocked: {} },
  };
  jQuery(
    "ol.sortable li#item-" +
      parseInt(id, 10) +
      " .dependency-container:first input.dependency:not(.module)"
  ).each(function (i, d) {
    var depId = jQuery(d).val();
    var key = "other";
    if (jQuery(d).hasClass("modules")) {
      key = "modules";
    }
    if (jQuery(d).prop("checked") === true) {
      if (jQuery(d).attr("disabled") === "disabled") {
        if (!result[key].inherited.hasOwnProperty([depId])) {
          result[key].inherited[depId] = false;
        }
      } else {
        if (!result[key].normal.hasOwnProperty(depId)) {
          result[key].normal[depId] = false;
        }
      }
    } else {
      if (jQuery(d).attr("disabled") === "disabled") {
        if (!result[key].blocked.hasOwnProperty(depId)) {
          result[key].blocked[depId] = false;
        }
      }
    }
  });

  return result;
}

// gets all dependencies of an id
function getAllDependencies(id) {
  var result = {};
  jQuery(
    "ol.sortable li#item-" +
      parseInt(id, 10) +
      " .dependency-container:first input.dependency:not(.module):checked"
  ).each(function (i, d) {
    var depId = jQuery(d).val();
    if (!jQuery(d).hasClass("modules")) {
      if (jQuery(d).attr("disabled") === "disabled") {
        if (!result.hasOwnProperty(depId)) {
          result[depId] = false;
        }
      } else {
        if (!result.hasOwnProperty(depId)) {
          result[depId] = false;
        }
      }
    } else {
      if (!result.hasOwnProperty(depId)) {
        result[depId] = false;
      }
    }
  });

  return result;
}

// builds the dependency object from the each items checkboxes
function calcDeps() {
  jQuery("ol.sortable li").each(function (i, item) {
    var curDeps = {
      other: { normal: {}, inherited: {}, blocked: {} },
      modules: { normal: {}, inherited: {}, blocked: {} },
    };
    jQuery(item)
      .find(".dependency-container:first input.dependency")
      .each(function (j, c) {
        var key = "other";
        if (jQuery(c).hasClass("module")) {
          key = "modules";
        }
        if (jQuery(c).prop("checked") === true) {
          if (jQuery(c).attr("disabled") === "disabled") {
            curDeps[key].inherited[jQuery(c).val()] = false;
          } else {
            curDeps[key].normal[jQuery(c).val()] = false;
          }
        } else {
          if (jQuery(c).attr("disabled") === "disabled") {
            curDeps[key].blocked[jQuery(c).val()] = false;
          }
        }
      });
    jQuery(item).data("dependencies", curDeps);
  });
  var tree = getFlatTree();
  flagDependencies(tree);
}

// remove a dependency from an id
function removeDependency(dep, id) {
  jQuery(
    "ol.sortable li#item-" +
      parseInt(id, 10) +
      ' input.dependency:not(.module)[value="' +
      parseInt(dep, 10) +
      '"]:checked'
  ).each(function (j, box) {
    if (jQuery(box).attr("disabled")) {
      jQuery(box).attr("disabled", false);
      jQuery(box).prop("checked", false);
    }
  });
}

// removes inherited dependencies from an id
function removeInheritedDependencies(dep, id) {
  var deps = getDependencies(dep);
  lodash.each(deps.other.normal, function (d) {
    jQuery(
      "ol.sortable li#item-" +
        parseInt(id, 10) +
        ' input.dependency:not(.module)[value="' +
        parseInt(d, 10) +
        '"]:checked'
    ).each(function (j, box) {
      if (jQuery(box).attr("disabled")) {
        jQuery(box).attr("disabled", false);
        jQuery(box).prop("checked", false);
      }
    });
  });
  lodash.each(deps.other.inherited, function (d) {
    jQuery(
      "ol.sortable li#item-" +
        parseInt(id, 10) +
        ' input.dependency:not(.module)[value="' +
        parseInt(d, 10) +
        '"]:checked'
    ).each(function (j, box) {
      if (jQuery(box).attr("disabled")) {
        jQuery(box).attr("disabled", false);
        jQuery(box).prop("checked", false);
      }
    });
  });

  var parentDeps = getDependencies(
    jQuery("ol.sortable li#item-" + parseInt(id, 10))
      .parents("li:first")
      .data("id")
  );
  lodash.each(parentDeps.other.normal, function (d) {
    inheritDependency(d, id, true);
  });
  lodash.each(parentDeps.other.inherited, function (d) {
    inheritDependency(d, id, true);
  });
}

// removes a dependencies children dependencies from an id
function removeDependencyChildren(dep, id) {
  var children = getAllChildren(dep);
  lodash.each(children, function (c) {
    removeDependency(c, id);
  });
}

// unblocks an id as dependency inside a dependency
function unblockItem(dep, id) {
  jQuery(
    "ol.sortable li#item-" +
      parseInt(dep, 10) +
      ' .dependency-container input.dependency:not(.module)[value="' +
      parseInt(id, 10) +
      '"]'
  ).each(function (i, box) {
    if (
      jQuery(box).attr("disabled") === "disabled" &&
      jQuery(box).prop("checked") === false
    ) {
      jQuery(box).attr("disabled", false);
    }
  });
}

// returns a list of items where where a dependency is blocked
function getDependencyBlockedLocations(d, parent) {
  var list = [];
  var results = jQuery(
    "li#item-" +
      parseInt(parent, 10) +
      ' .dependency-container input.dependency:not(.module)[value="' +
      parseInt(d, 10) +
      '"]'
  );
  lodash.each(results, function (r) {
    if (!jQuery(r).attr("checked"))
      list.push(jQuery(r).parents("li:first").data("id"));
  });

  return list;
}

// handle module dependencies, inherit, block, etc.
function handleModuleDependency(module, dep, state) {
  // mirror the dependency to all tree leaves where this module is present
  jQuery(
    'ol.sortable li select.available-modules option[value="' +
      parseInt(module, 10) +
      '"]:selected'
  ).each(function (i, item) {
    var parent = jQuery(item).parents("li:first");
    jQuery(parent)
      .find(
        '.dep-checkbox-container:first input.dependency.module[value="' +
          parseInt(dep, 10) +
          '"]'
      )
      .prop("checked", state);
  });
  // block and uncheck this module to prevent dependency cycles
  jQuery(
    'ol.sortable li select.available-modules option[value="' +
      parseInt(dep, 10) +
      '"]:selected'
  ).each(function (i, item) {
    var parent = jQuery(item).parents("li:first");
    jQuery(parent)
      .find(
        '.dep-checkbox-container:first input.dependency.module[value="' +
          parseInt(module, 10) +
          '"]'
      )
      .prop("checked", !state);
    jQuery(parent)
      .find(
        '.dep-checkbox-container:first input.dependency.module[value="' +
          parseInt(module, 10) +
          '"]'
      )
      .attr("disabled", state);
  });
}

// builds a list of dependencies for a module
function getModuleDependencies(module) {
  var results = { normal: {}, inherited: {}, blocked: {} };
  jQuery(
    'ol.sortable li select.available-modules option[value="' +
      parseInt(module, 10) +
      '"]:selected'
  ).each(function (i, item) {
    jQuery(item)
      .parents("li:first")
      .find("input.dependency.module")
      .each(function (j, box) {
        if (jQuery(box).prop("checked") === true) {
          if (jQuery(box).attr("disabled") === "disabled") {
            results.inherited[jQuery(box).val()] = false;
          } else {
            results.normal[jQuery(box).val()] = false;
          }
        } else {
          if (jQuery(box).attr("disabled") === "disabled") {
            results.blocked[jQuery(box).val()] = false;
          }
        }
      });
  });
  return results;
}

function inheritModuleDependencies(what, where) {
  jQuery(
    'ol.sortable li select.available-modules option[value="' +
      parseInt(where, 10) +
      '"]:selected'
  ).each(function (j, module) {
    jQuery(module)
      .parents("li:first")
      .find(
        '.dep-checkbox-container:first input.dependency.module[value="' +
          parseInt(what, 10) +
          '"]'
      )
      .prop("checked", true);
    jQuery(module)
      .parents("li:first")
      .find(
        '.dep-checkbox-container:first input.dependency.module[value="' +
          parseInt(what, 10) +
          '"]'
      )
      .attr("disabled", true);
  });
}

function blockModuleDependency(where, what, butNot, disable) {
  jQuery(
    'ol.sortable select.available-modules option[value="' +
      parseInt(where, 10) +
      '"]:selected'
  )
    .parents("li:first")
    .find(
      '.dep-checkbox-container input.dependency.module[value="' +
        parseInt(what, 10) +
        '"]'
    )
    .each(function (i, d) {
      if (
        butNot === null ||
        jQuery(d).parents("li:first").attr("id") !==
          "item-" + parseInt(butNot, 10)
      ) {
        jQuery(d).prop("checked", false);
        jQuery(d).attr("disabled", disable);
      }
    });
}

function unblockModuleDependency(where, what, butNot) {}
// block a module inside an id
function blockModule(module, id) {
  jQuery(
    "li#item-" +
      parseInt(id, 10) +
      ' .dep-checkbox-container:first input.dependency:notmodule[value="' +
      parseInt(module, 10) +
      '"]'
  ).prop("checked", false);
  jQuery(
    "li#item-" +
      parseInt(id, 10) +
      ' .dep-checkbox-container:first input.dependency.module[value="' +
      parseInt(module, 10) +
      '"]'
  ).attr("disabled", true);
}

function decamelizeObject(obj) {
  var result = {};
  for (let p in obj) {
    if (obj.hasOwnProperty(p)) {
      var snake = lodash.snakeCase(p);
      result[snake] = obj[p];
    }
  }
  for (var child in result.children) {
    if (result.children.hasOwnProperty(child)) {
      result.children[child] = decamelizeObject(result.children[child]);
    }
  }

  return result;
}

function cluevo_display_note(element, text, duration, timeout, href, newWindow) {
  let note;
  if (href) {
    note = document.createElement("a");
    note.href = href;
    if (newWindow) {
      note.target = '_blank';
    }
  } else {
    note = document.createElement("div");
  }
  note.className = "cluevo-fading-note";
  note.innerHTML = cluevoEncodeHTML(text); // xss?
  note.style.position = "absolute";
  note.style.width = "auto";
  jQuery(note).addClass("cluevo-note-ext-tease");
  const pos = jQuery(element).offset();
  note.style.opacity = 0;
  document.body.appendChild(note);
  note.style.left = pos.left - note.offsetWidth / 2 + "px";
  //note.style.display = 'none';
  const top = pos.top - jQuery(element).height();
  note.style.top = top + "px";
  jQuery(note).animate(
    {
      top: top - 10,
      opacity: 1,
    },
    duration,
    function () {
      jQuery(note).fadeOut(timeout, function () {
        document.body.removeChild(note);
      });
    }
  );
}
