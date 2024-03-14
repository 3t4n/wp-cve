// custom event polyfill
(function () {
  if (typeof window.CustomEvent === "function") return false;

  function CustomEvent(event, params) {
    params = params || { bubbles: false, cancelable: false, detail: undefined };
    var evt = document.createEvent("CustomEvent");
    evt.initCustomEvent(
      event,
      params.bubbles,
      params.cancelable,
      params.detail
    );
    return evt;
  }

  CustomEvent.prototype = window.Event.prototype;

  window.CustomEvent = CustomEvent;
})();

var cluevoSubmitTimer = null;
jQuery(document).ready(function () {
  jQuery(".cluevo-form-submit-btn").click(function () {
    jQuery(this).parents("form:first").submit();
  });
  jQuery(".cluevo-btn.disabled").click(function (e) {
    e.preventDefault();
  });

  jQuery(".cluevo-filter-text").keydown(function (e) {
    if (e.key === "Enter" || e.key === "Return") {
      jQuery(this).parents("form:first").submit();
    }
  });

  let inputs = jQuery(".cluevo-autocomplete-container");
  if (inputs && inputs.length > 0) {
    for (let el of inputs) {
      let name = jQuery(el).data("name");
      let initial = jQuery(el).data("initial");
      let type = jQuery(el).data("type");
      let min = parseInt(jQuery(el).data("min"), 10);
      let value = jQuery(el).data("value");
      let propsData = {
        name: name,
        initialDisplay: initial,
        type: type,
        initialValue: value,
      };

      if (!isNaN(min)) propsData.min = min;
      let comp = new cluevo_autocomplete_input({
        propsData,
      });

      comp.$mount();

      comp.$on("selected", function (e) {
        let event = new CustomEvent("selected", {
          detail: {
            value: e,
          },
        });
        el.dispatchEvent(event);
      });

      jQuery(el)
        .parents("form")
        .find(".cluevo-reset-filters:first")
        .click(function () {
          comp.$emit("reset");
          jQuery("select.cluevo-filter-input").each(function (index, el) {
            jQuery(el).val(jQuery(el).find("option:first").val());
          });
          jQuery(".cluevo-filter-text").each(function (index, el) {
            jQuery(el).val(null);
          });
          if (!cluevoSubmitTimer) {
            cluevoSubmitTimer = setTimeout(function () {
              jQuery(el).parents("form:first").submit();
            }, 50);
          }
        });
      jQuery(el).append(comp.$el);
    }
  }

  jQuery(".cluevo-notice-dismiss").click(function () {
    let key = jQuery(this).data("key");
    const el = jQuery(this);
    jQuery.ajax({
      type: "POST",
      url: ajaxurl,
      data: {
        action: "cluevo-dismiss-notice",
        "cluevo-notice-key": key,
        "cluevo-notice-nonce": cluevoWpCommonApiSettings.noticeNonce,
      },
      success: function () {
        el.parents(".cluevo-notice.cluevo-is-dismissible:first").remove();
      },
    });
  });

  jQuery(".cluevo-ts-to-locale-date").each(function (i, el) {
    jQuery(el).text(
      new Date(parseInt(jQuery(el).text(), 10) * 1000).toLocaleString()
    );
  });
});

jQuery(".toggle-credit").click(function () {
  let parent = jQuery(this).parents("tr:first");
  let userId = jQuery(parent).data("user-id");
  let moduleId = jQuery(parent).data("module-id");
  let attemptId = jQuery(parent).data("attempt-id");
  let cell = jQuery(this);
  jQuery.ajax({
    url: cluevoWpCommonApiSettings.ajax_url + "?action=toggle-progress-credit",
    method: "POST",
    data: JSON.stringify({
      action: "toggle-progress-credit",
      user_id: parseInt(userId, 10),
      module_id: parseInt(moduleId, 10),
      attempt_id: parseInt(attemptId, 10),
    }),
    contentType: "application/json",
    dataType: "json",
    beforeSend: function (xhr) {
      xhr.setRequestHeader("X-WP-Nonce", cluevoWpCommonApiSettings.nonce);
    },
    success: function (response) {
      let symbol = response === "credit" ? "🗸" : "✘";
      jQuery(cell).find(".cluevo-btn").text(symbol);
    },
  });
});

function cluevoEncodeHTML(s) {
  if (!s) return "";
  return s
    .toString()
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}
