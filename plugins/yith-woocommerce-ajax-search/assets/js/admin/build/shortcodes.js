/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************************!*\
  !*** ./assets/js/admin/src/shortcodes.js ***!
  \*******************************************/
var YWCAS_Admin_Shortcodes = function YWCAS_Admin_Shortcodes() {
  var security = ywcas_admin_params.shortcodeNonce;
  var target_deps = [];
  var target_deps_id = [];
  var init = function init() {
    jQuery(document).on('click', '.yith-plugin-fw__action-button--edit-action', editShortcode);
    jQuery(document).on('click', '.ywcas-save-shortcode', submitForm);
    jQuery(document).on('click', '.ywcas-add-shortcode', addNewShortcode);
    jQuery(document).on('click', '.yith-plugin-fw__action-button--trash-action', deleteShortcode);
    jQuery(document).on('click', '.yith-plugin-fw__action-button--duplicate-action', cloneShortcode);
    jQuery(document).on('change', '.ywcas-shortcode-field', toggleTab);
    initFields();
    handleFieldsChange();
  };
  var initFields = function initFields() {
    var fields = jQuery(document).find('[data-ywcas-deps]');
    fields.each(function () {
      var t = jQuery(this);
      handleField(t);
    });
  };
  var toggleTab = function toggleTab(event) {
    var field = jQuery(event.target),
      value = event.target.value,
      tabs = field.parents('.ywcas-shortcode__options__form').find('.yith-plugin-fw__tabs'),
      tab = tabs.find('li.submit-button');
    if ('classic' !== value) {
      tab.hide();
    } else {
      tab.show();
    }
  };
  var handleField = function handleField(field) {
    var parent = field.closest('.yith-plugin-fw__panel__option__content'),
      deps = field.data('ywcas-deps'),
      show = true;
    jQuery.each(deps, function (i, dep) {
      var target_dep = jQuery('#' + dep.id),
        compare = typeof dep.compare === 'undefined' ? '==' : dep.compare,
        property = typeof dep.property === 'undefined' ? false : dep.property,
        current_value;

      // it's a radio button.
      if (target_dep.hasClass('yith-plugin-fw-radio')) {
        current_value = target_dep.find('input[type="radio"]').filter(':checked').val();
      } else if (target_dep.hasClass('yith-plugin-fw-checkbox-array')) {
        var checked = target_dep.find('input[type="checkbox"]').filter(':checked');
        var values = [];
        jQuery.each(checked, function (i, check) {
          values.push(jQuery(check).val());
        });
        current_value = values;
      } else if (target_dep.hasClass('yith-plugin-fw-select') || target_dep.hasClass('yith-post-search') || target_dep.hasClass('wc-enhanced-select')) {
        current_value = target_dep.val();
      } else if (target_dep.hasClass('yith-plugin-fw-onoff-container')) {
        current_value = target_dep.find('input[type="checkbox"]').is(':checked') ? 'yes' : 'no';
      } else {
        current_value = target_dep.is(':checked') ? 'yes' : 'no';
      }
      if (target_deps_id.indexOf(dep.id) < 0) {
        target_deps.push(target_dep);
        target_deps_id.push(dep.id);
      }
      if (show) {
        var value = dep.value.split(',');
        var isArray = Array.isArray(current_value);
        switch (compare) {
          case '==':
          case '===':
            if (isArray) {
              var filteredArray = value.filter(function (val) {
                return current_value.includes(val);
              });
              show = filteredArray.length > 0;
            } else {
              show = value.indexOf(current_value) >= 0;
            }
            break;
          case '!=':
          case '!==':
            if (isArray) {
              var _filteredArray = value.filter(function (val) {
                return current_value.includes(val);
              });
              show = _filteredArray.length === 0;
            } else {
              show = value.indexOf(current_value) < 0;
            }
            break;
        }
      }
    });
    if (show) {
      parent.show();
    } else {
      parent.hide();
    }
  };
  var handleFieldsChange = function handleFieldsChange() {
    jQuery.each(target_deps, function (i, field) {
      field.on('change', function () {
        initFields();
      });
    });
  };
  var editShortcode = function editShortcode(event) {
    event.preventDefault();
    var row = jQuery(event.target).parents('.ywcas-row');
    var options = row.find('.ywcas-edit');
    if (row.is('.ywcas-row-opened')) {
      row.removeClass('ywcas-row-opened');
      options.slideUp();
    } else {
      row.addClass('ywcas-row-opened');
      options.slideDown();
    }
  };
  var getNewSlug = function getNewSlug() {
    var shortcodes = document.querySelectorAll('.ywcas-edit');
    var suff = 'presets-';
    var slug = '';
    var i = 1;
    var exists = true;
    do {
      slug = suff + (shortcodes.length + i++);
      exists = slugExists(slug);
    } while (exists);
    return slug;
  };
  var slugExists = function slugExists(slug) {
    var shortcodes = document.querySelectorAll('.ywcas-edit');
    var exists = false;
    shortcodes.forEach(function (s) {
      var currentSlug = s.dataset.target;
      if (currentSlug === slug) {
        exists = true;
        return;
      }
    });
    return exists;
  };
  var getSlug = function getSlug(name) {
    return name.toLowerCase().trim().replace(/[^\w\s-]/g, '').replace(/[\s_-]+/g, '-').replace(/^-+|-+$/g, '');
  };
  var submitForm = function submitForm(event) {
    event.preventDefault();
    event.stopPropagation();
    var form = jQuery(event.target.closest('form'));
    var slug = form.data('preset');
    var formData = new FormData();
    var block_params = {
      message: null,
      overlayCSS: {
        background: '#fff',
        opacity: 0.6
      },
      ignoreIfBlocked: true
    };
    jQuery.each(form.serializeArray(), function (i, field) {
      formData.append(field.name, field.value);
    });
    formData.append('slug', slug);
    formData.append('security', security);
    formData.append('action', 'yith_wcas_save_shortcode');
    jQuery.ajax({
      url: ywcas_admin_params.ajaxurl,
      data: formData,
      dataType: 'json',
      contentType: false,
      processData: false,
      type: 'POST',
      beforeSend: function beforeSend() {
        form.block(block_params);
      },
      success: function success(response) {
        var _response$data;
        if (response !== null && response !== void 0 && (_response$data = response.data) !== null && _response$data !== void 0 && _response$data.content) {
          jQuery(document).find('.ywcas-shortcodes-list').replaceWith(jQuery(response.data.content));
          jQuery(document).trigger('yith-plugin-fw-tabs-init');
          jQuery(document).trigger('yith_fields_init');
          jQuery(document).trigger('yith-add-box-button-toggle');
          jQuery(document).trigger('yith-plugin-fw-panel-init-deps');
          target_deps_id = [];
          target_deps = [];
          initFields();
          handleFieldsChange();
          window.onbeforeunload = null;
        }
        form.unblock();
      }
    });
  };
  var addNewShortcode = function addNewShortcode(e) {
    e.preventDefault();
    e.stopPropagation();
    var newSlug = getNewSlug();
    jQuery.ajax({
      url: ywcas_admin_params.ajaxurl,
      data: {
        security: security,
        action: 'yith_wcas_add_new_shortcode',
        slug: newSlug
      },
      type: 'POST',
      success: function success(response) {
        if (response.data.content) {
          jQuery(document).find('.ywcas-body').append(response.data.content);
          jQuery(document).trigger('yith-plugin-fw-tabs-init');
          jQuery(document).trigger('yith_fields_init');
          jQuery(document).trigger('yith-plugin-fw-panel-init-deps');
          initFields();
          handleFieldsChange();
        }
      }
    });
  };
  var deleteShortcode = function deleteShortcode(event) {
    event.preventDefault();
    event.stopPropagation();
    var container = event.target.closest('.ywcas-row');
    var form = jQuery(container).find('form');
    var slug = form.data('preset');
    yith.ui.confirm({
      title: ywcas_admin_params.shortcode_message_alert.title,
      message: ywcas_admin_params.shortcode_message_alert.desc,
      confirmButton: ywcas_admin_params.shortcode_message_alert.confirmButton,
      closeAfterConfirm: true,
      classes: {
        wrap: 'ywcas-warning-popup'
      },
      onConfirm: function onConfirm() {
        jQuery.ajax({
          url: ywcas_admin_params.ajaxurl,
          data: {
            action: 'yith_wcas_delete_shortcode',
            security: security,
            slug: slug
          },
          type: 'POST',
          success: function success(response) {
            if (response !== null && response !== void 0 && response.success) {
              container.remove();
            }
          }
        });
      }
    });
  };
  var cloneShortcode = function cloneShortcode(event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var container = event.target.closest('.ywcas-row');
    var form = jQuery(container).find('form');
    var slug = form.data('preset');
    var newSlug = getNewSlug(slug);
    jQuery.ajax({
      url: ywcas_admin_params.ajaxurl,
      data: {
        security: security,
        action: 'yith_wcas_clone_shortcode',
        newSlug: newSlug,
        slug: slug
      },
      type: 'POST',
      success: function success(response) {
        if (response.data.content) {
          var target = jQuery(response.data.content).find('.ywcas-edit').data('target');
          jQuery(document).find('.ywcas-body').append(response.data.content);
          document.querySelector('#' + target).scrollIntoView({
            behavior: 'smooth'
          });
          jQuery(document).trigger('yith-plugin-fw-tabs-init');
          jQuery(document).trigger('yith_fields_init');
          jQuery(document).trigger('yith-plugin-fw-panel-init-deps');
          initFields();
          handleFieldsChange();
        }
      }
    });
  };
  init();
};
YWCAS_Admin_Shortcodes();
var __webpack_export_target__ = window;
for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ })()
;
//# sourceMappingURL=shortcodes.js.map