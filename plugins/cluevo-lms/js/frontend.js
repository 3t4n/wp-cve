jQuery(window).ready(function () {
  jQuery(".cluevo-blocked").click(function (e) {
    e.preventDefault();
    var item = this;
    jQuery(item)
      .find(".cluevo-meta-item.cluevo-access")
      .addClass("cluevo-alert");
    setTimeout(function () {
      jQuery(item)
        .find(".cluevo-meta-item.cluevo-access")
        .removeClass("cluevo-alert");
    }, 1000);
  });
  jQuery(".cluevo-content-list-style-switch .cluevo-btn").click(function (e) {
    var value = "";
    var prefix = "cluevo-content-list-style-";
    if (jQuery(this).hasClass("cluevo-content-list-style-row")) {
      value = prefix + "row";
      if (
        !jQuery(".cluevo-content-list").hasClass(
          "cluevo-content-list-style-row",
        )
      ) {
        jQuery(".cluevo-content-list").addClass(
          "cluevo-content-list-style-row",
        );
      }
      jQuery(".cluevo-content-list-style-col").removeClass("active");
    }
    if (jQuery(this).hasClass("cluevo-content-list-style-col")) {
      value = prefix + "col";
      jQuery(".cluevo-content-list").removeClass(
        "cluevo-content-list-style-row",
      );
      jQuery(".cluevo-content-list-style-row").removeClass("active");
    }

    if (!jQuery(this).hasClass("active")) {
      jQuery(this).addClass("active");
    }

    var d = new Date();
    d.setTime(d.getTime() + 365 * 24 * 60 * 60 * 1000);
    var expires = "expires=" + d.toUTCString();
    document.cookie =
      "cluevo-content-list-style=" + value + ";" + expires + ";path=/";
  });

  jQuery(".cluevo-content-item-link.access-denied:not(.missing-reqs)").click(
    cluevoDisplayAccessDenied,
  );
  jQuery(".cluevo-content-item-link.access-denied.missing-reqs").click(
    cluevoDisplayMissingDependencies,
  );

  // jQuery('.cluevo-pdf-download-link').click(function(e) {
  //   e.preventDefault();
  //   e.stopPropagation();
  //   window.open(jQuery(this).data('href'));
  // });
});

function cluevoDisplayAccessDenied(e) {
  e.preventDefault();

  let text =
    jQuery(this).data("access-denied-text") &&
    jQuery(this).data("access-denied-text") != ""
      ? jQuery(this).data("access-denied-text")
      : cluevoStrings.message_access_denied;
  cluevoAlert(cluevoStrings.message_title_access_denied, text, "error");
}

function cluevoDisplayMissingDependencies(e) {
  e.preventDefault();

  const itemId = jQuery(this).data("item-id");
  const reqLevel = Number(jQuery(this).data("level-required"));
  const reqPoints = Number(jQuery(this).data("points-required"));
  const userLevel = Number(jQuery(this).data("user-level"));
  const userPoints = Number(jQuery(this).data("user-points"));

  if (!itemId) return cluevoDisplayAccessDenied(e);

  let text =
    jQuery(this).data("access-denied-text") &&
    jQuery(this).data("access-denied-text") != ""
      ? jQuery(this).data("access-denied-text")
      : cluevoStrings.message_access_denied;

  const msg = Vue.createApp(
    {
      props: [
        "itemId",
        "text",
        "requiredLevel",
        "userLevel",
        "requiredPoints",
        "userPoints",
      ],
      data() {
        return {
          dependencies: [],
          strings: null,
        };
      },
      created() {
        this.strings = window.cluevoStrings;
        const deps = document.getElementById(
          `cluevo-missing-dependencies-${this.itemId}`,
        )?.textContent;
        if (!deps) return;
        this.dependencies = JSON.parse(deps);
      },
      computed: {
        levelString() {
          return `${this.strings.message_level_required.replace(
            "%d",
            this.requiredLevel,
          )} ${this.strings.message_your_level.replace("%d", this.userLevel)}`;
        },
        pointsString() {
          return `${this.strings.message_points_required.replace(
            "%d",
            this.requiredPoints,
          )} ${this.strings.message_your_points.replace(
            "%d",
            this.userPoints,
          )}`;
        },
      },
      methods: {
        close() {
          jQuery(this.$el).fadeOut(300, () => {
            this.unmount();
            this.$el.remove();
          });
        },
      },
      template: `
    <div class="cluevo-alert-overlay cluevo-dismiss-click-area error" @click.self="close">
      <div class="cluevo-alert">
          <p class="cluevo-alert-title">{{ strings.message_title_access_denied }}</p>
          <p>{{ text }}</p>
          <template v-if="requiredLevel">
            <p>{{ levelString }}</p>
          </template>
          <template v-if="requiredPoints">
            <p>{{ pointsString }}</p>
          </template>
          <template v-if="dependencies?.length">
            <p>{{ strings.message_missing_dependencies }}</p>
            <ul>
              <li v-for="d, i of dependencies" :key="i">
                <a v-if="d.access" :href="d.url">{{ d.title }}</a>
                <span v-else>{{ d.title }}</span>
              </li>
            </ul>
          </template>
         <div class="cluevo-alert-close-button cluevo-dismiss-click-area">╳</div>
      </div>
    </div>
  `,
    },
    {
      itemId,
      text,
      requiredLevel: reqLevel,
      requiredPoints: reqPoints,
      userLevel,
      userPoints,
    },
  );

  const container = document.createElement("div");
  msg.mount(container);
  document.body.appendChild(container);
  const overlay = container.querySelector(".cluevo-alert-overlay");
  const btn = overlay.querySelector(".cluevo-alert-close-button");
  btn?.addEventListener("click", () => {
    jQuery(overlay).fadeOut(300, () => {
      msg.unmount();
      overlay.remove();
    });
  });
  jQuery(overlay).animate(
    {
      opacity: 1,
    },
    200,
  );
}

function cluevoAlert(title, message, type) {
  jQuery(".cluevo-alert-overlay").remove();
  var box = jQuery(
    '<div class="cluevo-alert-overlay cluevo-dismiss-click-area ' +
      cluevo_encodeHTML(type) +
      '"><div class="cluevo-alert ' +
      cluevo_encodeHTML(type) +
      '"><p class="cluevo-alert-title">' +
      cluevo_encodeHTML(title) +
      "</p><p>" +
      cluevo_nl2br(cluevo_encodeHTML(message)) +
      '</p><div class="cluevo-alert-close-button cluevo-dismiss-click-area">╳</div></div></div>',
  );
  jQuery(box).on("click", ".cluevo-alert-close-button", function (e) {
    e.stopPropagation();
    if (jQuery(this).hasClass("cluevo-dismiss-click-area")) {
      jQuery(".cluevo-alert-overlay").fadeOut(200, function () {
        jQuery(this).remove();
      });
    }
  });
  jQuery(box).on("click", function (e) {
    e.stopPropagation();
    if (e.target != this) return;
    if (jQuery(this).hasClass("cluevo-dismiss-click-area")) {
      jQuery(".cluevo-alert-overlay").fadeOut(200, function () {
        jQuery(this).remove();
      });
    }
  });
  box.appendTo("body").animate(
    {
      opacity: 1,
    },
    200,
  );
}

function cluevo_nl2br(str) {
  if (typeof str === "undefined" || str === null) {
    return "";
  }
  return (str + "").replace(
    /([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
    "$1" + "<br />" + "$2",
  );
}

function cluevo_encodeHTML(s) {
  if (!s) return "";
  return s
    .toString()
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

// const cluevoDependencyList = Vue.extend(cluevoDependencyListComp);
