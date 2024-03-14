import {
  batchFetch,
  bulkAdd,
  bulkStop,
  deleteItem,
  selectAll,
  deleteLogItem,
  bulkDeleteLogs,
  convertTimestamp,
} from "./modules/methods.js?2332";

// import Multiselect from "../../vendor/js/multiselect-2.6.2.js";

const appEl = document.querySelector("#afkw__app");

const app = Vue.createApp({
  data() {
    return {
      syncRequired: [],
      logs: [],
      sync_logs: [],
      ids: [],
      total_pages: 0,
      total_items: 0,
      itemsProcessed: 0,
      fetchingProgress: 0,
      storingProgress: 0,
      deletingProgress: 0,
      search: "",
      selectAllCheckbox: false,
      sync: false,
      disabled: false,
      stopFlag: false,
      stopFetchBtn: false,
      stopStoreBtn: false,
      stopDeleteBtn: false,
      syncDate: "",
      errors: [],
      blacklist: data && data.blacklist ? data.blacklist : [],
      posts: data && data.posts ? data.posts : [],
    };
  },
  mounted() {
    console.log(data);

    if (
      typeof data.total_pages_and_items === "object" &&
      data.total_pages_and_items !== null
    ) {
      this.total_items = data.total_pages_and_items.items;
      this.total_pages = data.total_pages_and_items.pages;
    }

    if (data && data.sync_logs.length > 0) {
      this.sync_logs = data.sync_logs;
    }

    if (data && data.syncDate) {
      this.syncDate = data.syncDate;
    }
  },
  methods: {
    batchFetch,
    bulkAdd,
    bulkStop,
    deleteItem,
    selectAll,
    deleteLogItem,
    bulkDeleteLogs,
    convertTimestamp,
  },
  computed: {
    filteredItems() {
      if (this.search) {
        return this.sync_logs.filter(item => {
          return item.post_title.toLowerCase().match(this.search.toLowerCase());
        });
      } else {
        return this.sync_logs;
      }
    },
  },
});
app.component("Multiselect", VueformMultiselect);
app.mount(appEl);

jQuery(document).ready(function () {
  jQuery(".afkw-alert").on("click", ".closebtn", function () {
    jQuery(this).closest(".afkw-alert").fadeOut(); //.css('display', 'none');
  });
  jQuery(".promotion-container").on("click", "input", function () {
    jQuery(this).parent().parent().find(".promotion").slideToggle();
  });

  jQuery("#fs_connect button[type=submit]").on("click", function (e) {
    console.log("open verify window");
    window.open(
      "https://better-robots.com/subscribe.php?plugin=auto-keyword",
      "auto-keyword",
      "resizable,height=400,width=700"
    );
  });
});
