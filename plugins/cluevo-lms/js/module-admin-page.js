var cluevo_module_install_type = "file";
var cluevo_last_upload_form = null;

jQuery(document).ready(function() {
  jQuery("p.cluevo-add-demos a").click(function(e) {
    if (!confirm(strings.msg_install_demos)) {
      e.preventDefault();
      return;
    }
  });

  jQuery("#submit").click(function(e) {
    if (!jQuery(this).hasClass("disabled")) {
      jQuery(this).parents("form:first").submit();
    }
  });

  jQuery("#add-module-form").submit(function(e) {
    //e.preventDefault();
    //return false;
  });

  jQuery(".del-module.delete").click(function(e) {
    if (confirm(strings.confirm_module_delete) !== true) {
      e.preventDefault();
    }
  });

  jQuery("#module-file-upload").on("change", function(e) {
    jQuery("#module-dl-url").val("");
    jQuery(".cluevo-selected-file").html(cluevoEncodeHTML(e.target.value));
    jQuery("#selected-file").val(cluevoEncodeHTML(e.target.value));
    if (jQuery(this).val() != "") {
      jQuery("#submit").removeClass("disabled");
    } else {
      jQuery("#submit").addClass("disabled");
    }
  });

  jQuery("#selected-file").on("input", function(e) {
    if (jQuery("#module-file-upload").val() != "") {
      jQuery(this).val("");
      jQuery("#submit").addClass("disabled");
      jQuery("#module-file-upload").val("");
    } else {
      if (jQuery(this).val() != "") {
        jQuery("#submit").removeClass("disabled");
      } else {
        jQuery("#submit").addClass("disabled");
      }
    }
  });

  jQuery("#module-dl-url").on("input", function(e) {
    if (jQuery("#module-file-upload").val() != "") {
      jQuery("#module-file-upload").val("");
    }

    if (jQuery(this).val() != "") {
      jQuery("#submit").removeClass("disabled");
    } else {
      jQuery("#submit").addClass("disabled");
    }
  });

  jQuery(".cluevo-admin-notice.is-dismissible").each(function(i, notice) {
    jQuery(notice).append('<button type="button" class="notice-dismiss" />');
    jQuery(notice).on("click", "button", function() {
      jQuery(this).parents(".cluevo-admin-notice:first").fadeOut();
    });
  });

  jQuery(".row-actions .edit-module-name").click(function(e) {
    var old = jQuery(this).parents("tr:first").find(".cluevo-module-name").text().trim();
    var name = prompt(strings.rename_module_prompt, old);
    name = name?.trim?.();
    if (name && name != old && name != "") {
      var id = jQuery(this).data("id");
      var url =
        cluevoWpApiSettings.root +
        "cluevo/v1/modules/" +
        parseInt(id, 10) +
        "/name";
      var cell = jQuery(this).parents("tr:first").find("td.title");
      jQuery.ajax({
        url: url,
        method: "POST",
        data: JSON.stringify({ name: cluevoEncodeHTML(name) }),
        contentType: "application/json",
        dataType: "json",
        beforeSend: function(xhr) {
          xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
        },
        success: function(response) {
          if (response === true) {
            jQuery(cell).text(name);
          } else {
            alert(strings.rename_module_error);
            console.error("failed to rename module");
          }
        },
      });
    }
  });

  jQuery(".cluevo-edit-module-tags").click(function(e) {
    var old = jQuery(this).parents("tr:first").find("div.cluevo-module-tags").text().trim();
    var tags = prompt(strings.tag_module_prompt, old);
    tags = tags?.trim?.();
    if (tags && tags != old) {
      var id = jQuery(this).parents("tr:first").data("module-id");
      if (isNaN(id)) {
        alert("invalid id: " + id);
        return;
      }
      var url =
        cluevoWpApiSettings.root +
        "cluevo/v1/modules/" +
        parseInt(id, 10) +
        "/tag";
      var cell = jQuery(this).parents("tr:first").find("div.cluevo-module-tags");
      jQuery.ajax({
        url: url,
        method: "POST",
        data: JSON.stringify({ tags: cluevoEncodeHTML(tags) }),
        contentType: "application/json",
        dataType: "json",
        beforeSend: function(xhr) {
          xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
        },
        success: function(response) {
          if (response === true) {
            const newTags = tags
              ?.split(",")
              ?.map((t) => {
                t = t.trim();
                t = cluevoEncodeHTML(t);
                return t;
              })
              ?.filter((t) => t !== "") ?? '';
            const unique = [...new Set(newTags)];
            jQuery(cell).text(unique);
          } else {
            alert(strings.rename_module_error);
            console.error("failed to tag module");
          }
        },
      });
    }
  });

  jQuery(".cluevo-add-module-overlay").click(function(e) {
    e.stopPropagation();
    if (e.target != this) return;

    jQuery(this).fadeOut();
    reset_module_upload_ui();
  });

  jQuery(".cluevo-add-module-overlay button.close").click(function(e) {
    jQuery(this).parents(".cluevo-add-module-overlay:first").fadeOut();
    reset_module_upload_ui();
  });

  jQuery(".cluevo-add-module-overlay .module-list .module-type").click(
    function(e) {
      var index = jQuery(this).data("moduleIndex");
      jQuery(".cluevo-add-module-overlay .module-type-selection").hide();
      jQuery(".cluevo-add-module-overlay .button.select-type").css(
        "display",
        "inline-block"
      );
      jQuery(
        ".cluevo-add-module-overlay .module-description-container .module-type"
      ).css("display", "none");
      jQuery(
        '.cluevo-add-module-overlay .module-description-container .module-type[data-module-index="' +
        index +
        '"]'
      ).show();
    }
  );

  jQuery(".button.add-module").click(function(e) {
    jQuery("#cluevo-add-module-overlay").fadeIn();
  });

  jQuery(
    '.cluevo-add-module-overlay .module-description-container .module-type input[type="submit"], .cluevo-add-module-overlay .cluevo-update-module-content-container input[type="submit"]'
  ).click(handle_module_update_upload);
  jQuery(".cluevo-add-module-overlay .button.force").click(function() {
    jQuery(
      ".cluevo-add-module-overlay .upload-progress .result-container"
    ).html("");
    jQuery(".cluevo-add-module-overlay .progress-container").show();
    jQuery(".cluevo-add-module-overlay .button.continue").hide();
    jQuery(".cluevo-add-module-overlay .button.force").hide();
    jQuery(".cluevo-add-module-overlay .upload-progress .progress-text").text(
      ""
    );
    jQuery(
      ".cluevo-add-module-overlay .upload-progress .result-container"
    ).html("");
    handle_module_upload(true, cluevo_last_upload_form);
  });

  jQuery(".cluevo-add-module-overlay .button.select-type").click(
    function() {
      reset_module_upload_ui();
      return;
      jQuery(this).hide();
      jQuery(".cluevo-add-module-overlay .module-type-selection").show();
      jQuery(
        ".cluevo-add-module-overlay .module-description-container .module-type"
      ).hide();
    }
  );

  jQuery(".cluevo-add-module-overlay .button.continue").click(
    reset_module_upload_ui
  );

  jQuery(
    '.cluevo-add-module-overlay .module-description-container .module-type input[name="module-file"], .cluevo-add-module-overlay .cluevo-update-module-content-container input[name="module-file"]'
  ).on("change", function(e) {
    var fileField = jQuery(this)
      .parents(".input-switch:first")
      .find('input[name="module-file"]');
    var urlField = jQuery(this)
      .parents(".input-switch:first")
      .find('input[name="module-dl-url"]');
    var submitButton = jQuery(this)
      .parents(".module-type:first")
      .find('input[type="submit"]');
    let max = jQuery(this)
      .parents(".cluevo-add-module-overlay:first")
      .data("max-upload-size");
    urlField.val(e.target.value);
    jQuery(this)
      .parents(".module-type:first")
      .find(".cluevo-notice.cluevo-filesize")
      .addClass("hidden");
    if (max < this.files[0].size) {
      jQuery(this)
        .parents(".module-type:first")
        .find(".cluevo-notice.cluevo-filesize")
        .removeClass("hidden");
      return;
    }
    if (jQuery(this).val() != "") {
      submitButton.removeClass("disabled");
      submitButton.attr("disabled", false);
    } else {
      submitButton.addClass("disabled");
      submitButton.attr("disabled", "disabled");
    }
  });

  jQuery(
    '.cluevo-add-module-overlay .module-description-container .module-type input[name="module-dl-url"], .cluevo-add-module-overlay .module-description-container .module-type textarea[name="module-dl-url"], .cluevo-add-module-overlay .cluevo-update-module-content-container input[name="module-dl-url"]'
  ).on("input", function(e) {
    var fileField = jQuery(this)
      .parents("form:first")
      .find('input[name="module-file"]');
    var urlField = jQuery(this);
    var submitButton = jQuery(this)
      .parents("form:first")
      .find('input[type="submit"]');
    if (fileField.length > 0 && fileField.val() != "") {
      jQuery(fileField).val("");
      fileField.val("");
    }
    if (urlField.val() != "" && isUrl(urlField.val())) {
      submitButton.removeClass("disabled");
      submitButton.attr("disabled", false);
    } else {
      submitButton.addClass("disabled");
      submitButton.attr("disabled", "disabled");
    }
  });

  jQuery(".cluevo-pending-module").click(function(e) {
    jQuery.ajax({
      type: "POST",
      url: ajaxurl + "?action=cluevo-install-pending-module",
      data: {
        action: "cluevo-install-pending-module",
        "cluevo-pending-module": jQuery(this).data("module"),
        "cluevo-pending-module-nonce": cluevoWpApiSettings.pendingModuleNonce,
      },
      success: function(resp) {
        if (resp == 1) {
          location.reload();
        } else {
          alert(strings.pending_install_error);
        }
      },
    });
  });

  jQuery(".cluevo-pending-module-delete").click(function(e) {
    jQuery.ajax({
      type: "POST",
      url: ajaxurl + "?action=cluevo-delete-pending-module",
      data: {
        action: "cluevo-delete-pending-module",
        "cluevo-pending-module": jQuery(this).data("module"),
        "cluevo-pending-module-nonce":
          cluevoWpApiSettings.deletePendingModuleNonce,
      },
      success: function(resp) {
        if (resp && resp.data == true && resp.success === true) {
          location.reload();
        } else {
          alert(strings.pending_delete_error);
        }
      },
    });
  });

  jQuery(".row-actions .update-module").click(function(e) {
    const moduleId = jQuery(this).parents("tr:first").data("module-id");
    jQuery(".cluevo-add-module-content-container").hide();
    jQuery(".cluevo-update-module-content-container").show();
    jQuery("#cluevo-add-module-overlay").data("module-id", moduleId);
    jQuery("#cluevo-add-module-overlay").fadeIn();
  });
});

function cluevo_update_module_table(module) {
  var rows = jQuery(
    'table.cluevo-scorm-modules tr[data-module-id="' + module.module_id + '"]'
  );
  if (rows.length > 0) {
  } else {
    var row = jQuery(
      '<tr data-module-id="' +
      module.module_id +
      '">' +
      "<td>" +
      module.module_id +
      "</td>" +
      '<td class="title left column-title has-row-actions column-primary" data-id="' +
      module.module_id +
      '">' +
      module.module_name +
      "</td>" +
      '<td class="type left">' +
      module.type_name +
      "</td>" +
      '<td colspan="2">' +
      strings.refresh_to_enable +
      "</td>" +
      "</tr>"
    ).appendTo(jQuery("table.cluevo-scorm-modules tbody"));
  }
}

function isUrl(string) {
  let url;

  try {
    url = new URL(string);
  } catch (_) {
    return false;
  }

  return url.protocol === "http:" || url.protocol === "https:";
}

function reset_module_upload_ui() {
  jQuery(
    ".cluevo-add-module-overlay .upload-progress .cluevo-progress-container"
  ).show();
  jQuery(".cluevo-add-module-overlay .module-type-selection").show();
  jQuery(".cluevo-add-module-overlay .upload-progress .progress-text").text("");
  jQuery(".cluevo-add-module-overlay .upload-progress .result-container").html(
    ""
  );
  jQuery(".cluevo-add-module-overlay").data("module-id", null);
  jQuery(".cluevo-add-module-content-container").show();
  jQuery(".cluevo-update-module-content-container").hide();
  jQuery(".cluevo-add-module-overlay .upload-progress").hide();
  jQuery(".cluevo-add-module-overlay .button.select-type").hide();
  jQuery(".cluevo-add-module-overlay .module-description-container").show();
  jQuery(
    ".cluevo-add-module-overlay .module-description-container .module-type"
  ).hide();
  jQuery(".cluevo-add-module-overlay form").trigger("reset");
  jQuery(".cluevo-add-module-overlay .button.continue").hide();
  jQuery(".cluevo-add-module-overlay .button.force").hide();
  jQuery(".cluevo-add-module-overlay .cluevo-notice.cluevo-filesize").hide();
  jQuery('.cluevo-add-module-overlay input[type="submit"]').addClass(
    "disabled"
  );
  jQuery('.cluevo-add-module-overlay input[type="submit"]').attr(
    "disabled",
    true
  );
}

function handle_module_update_upload(e) {
  const handler = handle_module_upload.bind(jQuery(this));
  return handler(false, false, true);
}
function handle_module_upload(force, useForm, isUpdate) {
  jQuery(".cluevo-add-module-overlay .module-description-container").hide();
  jQuery(
    ".cluevo-add-module-overlay .cluevo-update-module-content-container"
  ).hide();
  jQuery(
    ".cluevo-add-module-overlay .upload-progress .cluevo-progress-container"
  ).show();
  jQuery(
    ".cluevo-add-module-overlay .upload-progress .cluevo-progress-container"
  ).removeClass("indeterminate");
  jQuery(".cluevo-add-module-overlay .upload-progress").show();
  jQuery(
    ".cluevo-add-module-overlay .upload-progress .cluevo-progress-container span.cluevo-progress"
  ).width("0%");
  useForm = useForm || false;
  if (!useForm) {
    var form = jQuery(this).parents("form:first");
  } else {
    var form = useForm;
  }
  cluevo_last_upload_form = form;
  var formData = new FormData(form[0]);
  const moduleId = jQuery(this)
    .parents(".cluevo-add-module-overlay:first")
    .data("module-id");
  if (moduleId) {
    formData.append("module-id", moduleId);
  }
  if (force === true) formData.append("force", true);
  jQuery.ajax({
    type: "POST",
    url: cluevoWpApiSettings.root + "cluevo/v1/modules/upload",
    data: formData,
    enctype: "multipart/form-data",
    beforeSend: function(xhr) {
      xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
    },
    xhr: function() {
      // Custom XMLHttpRequest
      var appXhr = jQuery.ajaxSettings.xhr();

      // Check if upload property exists, if "yes" then upload progress can be tracked otherwise "not"
      if (appXhr.upload) {
        // Attach a function to handle the progress of the upload
        appXhr.upload.addEventListener(
          "progress",
          function(e) {
            if (e.lengthComputable) {
              var currentProgress = (e.loaded / e.total) * 100; // Amount uploaded in percent
              jQuery(
                ".cluevo-add-module-overlay .upload-progress .cluevo-progress-container span.cluevo-progress"
              ).width(100 - currentProgress + "%");

              if (currentProgress == 100) {
                jQuery(
                  ".cluevo-add-module-overlay .upload-progress .cluevo-progress-container"
                ).toggleClass("indeterminate");
                jQuery(
                  ".cluevo-add-module-overlay .upload-progress .progress-text"
                ).text(strings.upload_success);
              }
            }
          },
          false
        );
      }
      return appXhr;
    },
    processData: false,
    contentType: false,
    cache: false,
    success: function(result) {
      if (result) {
        var notices = [];
        var errors = [];
        if (result.handled) {
          if (result.messages && result.messages.length > 0) {
            notices = result.messages.map(function(t) {
              return (
                '<div class="cluevo-notice cluevo-notice-notice"><p>' +
                t +
                "</p></div>"
              );
            });
          }
          if (result.errors && result.errors.length > 0) {
            errors = result.errors.map(function(t) {
              return (
                '<div class="cluevo-notice cluevo-notice-error"><p>' +
                t +
                "</p></div>"
              );
            });
          }
          notices = notices.concat(errors).join("\n");
          jQuery(
            ".cluevo-add-module-overlay .upload-progress .cluevo-progress-container"
          ).hide();
          jQuery(
            ".cluevo-add-module-overlay .upload-progress .progress-text"
          ).text(strings.module_upload_finished);
          jQuery(
            ".cluevo-add-module-overlay .upload-progress .result-container"
          ).html(notices);
          jQuery(
            ".cluevo-add-module-overlay .upload-progress .result-container"
          ).show();
          jQuery(".cluevo-add-module-overlay .button.continue").css(
            "display",
            "inline-block"
          );
          cluevo_last_upload_form = null;
          if (!isUpdate) {
            cluevo_update_module_table(result.module);
          }
        } else {
          var error =
            '<div class="cluevo-notice cluevo-notice-error"><p>' +
            strings.upload_error +
            "</p></div>";
          jQuery(
            ".cluevo-add-module-overlay .upload-progress .progress-text"
          ).text(strings.module_upload_failed);
          jQuery(
            ".cluevo-add-module-overlay .upload-progress .result-container"
          ).html(error);
          jQuery(
            ".cluevo-add-module-overlay .upload-progress .result-container"
          ).show();
          jQuery(".cluevo-add-module-overlay .button.continue").css(
            "display",
            "inline-block"
          );
          jQuery(".cluevo-add-module-overlay .button.force").css(
            "display",
            "inline-block"
          );
        }
      }
    },
    error: function(error) {
      if (error.responseJSON) {
        let notices = [];
        let messages = [];
        let errors = [];
        if (error.responseJSON.errors && error.responseJSON.errors.length > 0) {
          errors = error.responseJSON.errors.map((t) => (
            '<div class="cluevo-notice cluevo-notice-error"><p>' +
            t +
            "</p></div>"
          ));
        }
        if (error.responseJSON.messages && error.responseJSON.messages.length > 0) {
          messages = error.responseJSON.messages.map((t) => (
            '<div class="cluevo-notice cluevo-notice-error"><p>' +
            t +
            "</p></div>"
          ));
        }
        notices = [...errors, ...messages];
        jQuery(
          ".cluevo-add-module-overlay .upload-progress .cluevo-progress-container"
        ).hide();
        jQuery(
          ".cluevo-add-module-overlay .upload-progress .progress-text"
        ).text(strings.module_upload_failed);
        jQuery(
          ".cluevo-add-module-overlay .upload-progress .result-container"
        ).html(notices);
        jQuery(
          ".cluevo-add-module-overlay .upload-progress .result-container"
        ).show();
        jQuery(".cluevo-add-module-overlay .button.continue").css(
          "display",
          "inline-block"
        );
        jQuery(".cluevo-add-module-overlay .button.force").css(
          "display",
          "inline-block"
        );
      } else {
        jQuery(
          ".cluevo-add-module-overlay .upload-progress .cluevo-progress-container"
        ).hide();
        jQuery(
          ".cluevo-add-module-overlay .upload-progress .progress-text"
        ).text(strings.module_upload_failed);
        var error =
          '<div class="cluevo-notice cluevo-notice-error"><p>' +
          strings.upload_error +
          "</p></div>";
        jQuery(
          ".cluevo-add-module-overlay .upload-progress .result-container"
        ).html(error);
        jQuery(
          ".cluevo-add-module-overlay .upload-progress .result-container"
        ).show();
        jQuery(".cluevo-add-module-overlay .button.continue").css(
          "display",
          "inline-block"
        );
      }
    },
  });
  return false;
}
