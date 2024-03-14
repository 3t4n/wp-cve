jQuery(document).ready(function () {
  jQuery(".pctag-alert").on("click", ".closebtn", function () {
    jQuery(this).closest(".pctag-alert").fadeOut(); //.css('display', 'none');
  });
  jQuery(".pctag-boost-robot-label input").on("click", function () {
    jQuery(".pctag-boost-robot").slideToggle();
  });
  jQuery(".pctag-boost-alt-label input").on("click", function () {
    jQuery(".pctag-boost-alt").slideToggle();
  });
  jQuery(".pctag-mobi-label input").on("click", function () {
    jQuery(".pctag-mobi").slideToggle();
  });
  jQuery(".pctag-bigta-label input").on("click", function () {
    jQuery(".pctag-bigta").slideToggle();
  });
  jQuery(".pctag-vidseo-label input").on("click", function () {
    jQuery(".pctag-vidseo").slideToggle();
  });

  jQuery("#fs_connect button[type=submit]").on("click", function (e) {
    console.log("open verify window");
    window.open(
      "https://better-robots.com/subscribe.php?plugin=pinterest-tags",
      "pinterest-tags.s",
      "resizable,height=400,width=700"
    );
  });
});

// console.log(data);

PetiteVue.createApp({
  enable_signup: data.enable_signup ? true : false,
  signup_events: data?.signup_events ? data.signup_events : [],
  enable_watchVideo: data.enable_watchVideo ? true : false,
  watchVideo_events: data?.watchVideo_events ? data.watchVideo_events : [],
  enable_lead: data.enable_lead ? true : false,
  lead_events: data?.lead_events ? data.lead_events : [],
  enable_custom: data.enable_custom ? true : false,
  custom_events: data?.custom_events ? data.custom_events : [],
  addEvent(event) {
    if (event === "signup") {
      this.signup_events.push({
        type: "",
        value: "",
      });
    } else if (event === "watchVideo") {
      this.watchVideo_events.push({
        type: "",
        value: "",
      });
    } else if (event === "lead") {
      this.lead_events.push({
        type: "",
        value: "",
      });
    } else if (event === "custom") {
      this.custom_events.push({
        type: "",
        value: "",
      });
    }
  },
  removeEvent(id, event) {
    if (confirm("Are you sure?")) {
      if (event === "signup") {
        this.signup_events.splice(id, 1);
      } else if (event === "watchVideo") {
        this.watchVideo_events.splice(id, 1);
      } else if (event === "lead") {
        this.lead_events.splice(id, 1);
      } else if (event === "custom") {
        this.custom_events.splice(id, 1);
      }
    }
  },
}).mount("#pctags_app");
