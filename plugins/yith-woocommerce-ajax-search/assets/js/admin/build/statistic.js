/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************************!*\
  !*** ./assets/js/admin/src/statistic.js ***!
  \******************************************/
/* global ywcas_admin_params */
var YWCAS_Admin_Statistic = function YWCAS_Admin_Statistic() {
  var fromDate = document.querySelector('#ywcas_statistic_from');
  var toDate = document.querySelector('#ywcas_statistic_to');
  var filterButton = document.querySelector('#post-query-submit');
  var resetButton = document.querySelector('#post-query-reset');
  var container = document.querySelector('.ywcas-statistic-wrapper');
  var requestAjax = false;
  var showReset = fromDate.value.length > 0 && toDate.value.length > 0;
  !showReset && jQuery(resetButton).hide();
  var blockParams = {
    message: null,
    overlayCSS: {
      background: '#fff',
      opacity: 0.7
    },
    ignoreIfBlocked: true
  };
  var init = function init() {
    container = document.querySelector('.ywcas-statistic-wrapper');
    window.onbeforeunload = null;
    jQuery(document).on('change', fromDate, changeToDate);
    jQuery(document).on('change', toDate, changeFromDate);
    jQuery(document).on('click', '#post-query-submit', filterByDate);
    jQuery(document).on('click', '#post-query-reset', resetDate);
    checkFilterButton();
  };
  var initFilter = function initFilter() {
    fromDate = document.querySelector('#ywcas_statistic_from');
    toDate = document.querySelector('#ywcas_statistic_to');
    filterButton = document.querySelector('#post-query-submit');
    resetButton = document.querySelector('#post-query-reset');
  };
  var resetDate = function resetDate(e) {
    e.preventDefault();
    jQuery(toDate).datepicker('setDate', null);
    jQuery(fromDate).datepicker('setDate', null);
    filterButton.click();
  };
  var changeToDate = function changeToDate() {
    jQuery(toDate).datepicker('option', 'minDate', new Date(fromDate.value));
    checkFilterButton();
  };
  var changeFromDate = function changeFromDate() {
    jQuery(fromDate).datepicker('option', 'maxDate', new Date(toDate.value));
    checkFilterButton();
  };
  var checkFilterButton = function checkFilterButton() {
    initFilter();
    var emptyState = fromDate.value == '' && toDate.value == '';
    filterButton.disabled = emptyState;
    if (!showReset) {
      jQuery(resetButton).hide();
    } else {
      jQuery(resetButton).show();
    }
  };
  var filterByDate = function filterByDate(e) {
    e.stopPropagation();
    e.preventDefault();
    showReset = true;
    if (requestAjax) {
      requestAjax.abort();
    }
    var form = jQuery(e.target.closest('form'));
    var parentForm = form.closest('.ywcas-statistic-filter');
    var isDetail = typeof jQuery(parentForm).data('type') !== 'undefined';
    var formData = new FormData();
    jQuery.each(form.serializeArray(), function (i, field) {
      formData.append(field.name, field.value);
    });
    formData.append('isDetail', isDetail);
    isDetail && formData.append('view_all', jQuery(parentForm).data('type'));
    formData.append('security', ywcas_admin_params.statisticNonce);
    formData.append('action', 'yith_wcas_filter_statistic');
    jQuery(container).block(blockParams);
    requestAjax = jQuery.ajax({
      url: ywcas_admin_params.ajaxurl,
      data: formData,
      dataType: 'json',
      contentType: false,
      processData: false,
      type: 'POST',
      success: function success(response) {
        var _response$data;
        if (response !== null && response !== void 0 && (_response$data = response.data) !== null && _response$data !== void 0 && _response$data.content) {
          jQuery(document).find('#yith-plugin-fw-panel-custom-tab-statistic.yith-plugin-fw-panel-custom-tab-container').html(jQuery(response.data.content));
          jQuery(document).trigger('yith-plugin-fw-tabs-init');
          jQuery(document).trigger('yith_fields_init');
          window.onbeforeunload = null;
          init();
        }
      },
      complete: function complete() {
        jQuery(container).unblock();
      }
    });
    return false;
  };
  init();
};
YWCAS_Admin_Statistic();
var __webpack_export_target__ = window;
for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ })()
;
//# sourceMappingURL=statistic.js.map