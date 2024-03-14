jQuery(document).ready(function () {
  jQuery(".pmt-alert").on("click", ".closebtn", function () {
    jQuery(this).closest(".pmt-alert").fadeOut(); //.css('display', 'none');
  });

  jQuery("#fs_connect button[type=submit]").on("click", function (e) {
    console.log("open verify window");
    window.open(
      "https://better-robots.com/subscribe.php?plugin=meta-tags",
      "meta-tags",
      "resizable,height=400,width=700"
    );
  });
});

if (typeof options !== "undefined") {
  console.log(options);
  new Vue({
    el: "#meta_app",
    data: {
      fields: [],
      custom_tags: false,
      custom_tags_area: options.pmt_custom_tags_area
        ? options.pmt_custom_tags_area
        : "",
      hideDesc: true,
    },
    mounted() {
      if (options.meta_tags) {
        this.fields = options.meta_tags;
      }
      //console.log(options.meta_tags);
    },
    methods: {
      addMeta() {
        this.fields.push({
          type: "name",
          value: "keywords",
          post_type: "everywhere",
          focus_keyword: "",
        });
      },
      removeMeta(id) {
        var remove = confirm("Are you sure you want to remove this meta tag?");
        if (remove) {
          this.fields.splice(id, 1);
        }
      },
      duplicateMeta(id) {
        var cloneItem = Object.assign({}, this.fields[id]);
        this.fields.splice(id, 0, cloneItem);
        console.log(cloneItem);
      },
      showDesc() {
        this.hideDesc = !this.hideDesc;
      },
      pro_only() {
        alert("Get pro version to enable this feature");
      },
    },
  });
}

if (typeof meta !== "undefined") {
  // console.log(meta);
  new Vue({
    el: "#meta_app",
    data: {
      fields: [],
      custom_tags: meta.custom_tags ? true : false,
      disable_tags: meta.disable_tags ? true : false,
    },
    mounted() {
      if (meta.meta_tags) {
        this.fields = meta.meta_tags;
      }
      console.log(meta.meta_tags);
    },
    methods: {
      addMeta() {
        this.fields.push({
          type: "name",
          value: "keywords",
          focus_keyword: "",
        });
      },
      removeMeta(id) {
        var remove = confirm("Are you sure you want to remove this meta tag?");
        if (remove) {
          this.fields.splice(id, 1);
        }
      },
      duplicateMeta(id) {
        var cloneItem = Object.assign({}, this.fields[id]);
        this.fields.splice(id, 0, cloneItem);
        //console.log(this.fields)
      },
    },
  });
}
