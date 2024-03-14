/* global ywcas_admin_params */
const YWCAS_Admin_Statistic = () => {

  let fromDate = document.querySelector('#ywcas_statistic_from');
  let toDate = document.querySelector('#ywcas_statistic_to');
  let filterButton = document.querySelector('#post-query-submit');
  let resetButton = document.querySelector('#post-query-reset');
  let container = document.querySelector('.ywcas-statistic-wrapper');
  let requestAjax = false;
  let showReset = fromDate.value.length > 0 && toDate.value.length > 0;
  !showReset && jQuery( resetButton ).hide();
  var blockParams = {
    message: null,
    overlayCSS: {background: '#fff', opacity: 0.7},
    ignoreIfBlocked: true,
  };

  const init = () => {
    container = document.querySelector('.ywcas-statistic-wrapper');
    window.onbeforeunload = null;
    jQuery(document).on('change', fromDate, changeToDate);
    jQuery(document).on('change', toDate, changeFromDate);
    jQuery(document).on('click', '#post-query-submit', filterByDate);
    jQuery(document).on('click', '#post-query-reset', resetDate);
    checkFilterButton();
  };

  const initFilter = () => {
    fromDate = document.querySelector('#ywcas_statistic_from');
    toDate = document.querySelector('#ywcas_statistic_to');
    filterButton = document.querySelector('#post-query-submit');
    resetButton = document.querySelector('#post-query-reset');
  };

  const resetDate = (e) => {
    e.preventDefault();
    jQuery(toDate).datepicker('setDate', null);
    jQuery(fromDate).datepicker('setDate', null);
    filterButton.click();
  };

  const changeToDate = () => {
    jQuery(toDate).datepicker('option', 'minDate', new Date(fromDate.value));
    checkFilterButton();
  };

  const changeFromDate = () => {
    jQuery(fromDate).datepicker('option', 'maxDate', new Date(toDate.value));
    checkFilterButton();
  };

  const checkFilterButton = () => {
    initFilter();
    const emptyState = fromDate.value == '' && toDate.value == '';
    filterButton.disabled = emptyState;
    if( !showReset ) {
      jQuery(resetButton).hide();
    }else{
      jQuery(resetButton).show();
    }
  };

  const filterByDate = (e) => {
    e.stopPropagation();
    e.preventDefault();
    showReset = true;
    if (requestAjax) {
      requestAjax.abort();
    }

    const form = jQuery(e.target.closest('form'));
    const parentForm = form.closest('.ywcas-statistic-filter');
    const isDetail = typeof jQuery(parentForm).data('type') !== 'undefined';

    const formData = new FormData();
    jQuery.each(form.serializeArray(), function(i, field) {
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
      success: function(response) {
        if (response?.data?.content) {
          jQuery(document).find('#yith-plugin-fw-panel-custom-tab-statistic.yith-plugin-fw-panel-custom-tab-container').html(jQuery(response.data.content));
          jQuery(document).trigger('yith-plugin-fw-tabs-init');
          jQuery(document).trigger('yith_fields_init');
          window.onbeforeunload = null;
          init();
        }

      },
      complete: function() {
        jQuery(container).unblock();
      },
    });

    return false;

  };

  init();
};

YWCAS_Admin_Statistic();