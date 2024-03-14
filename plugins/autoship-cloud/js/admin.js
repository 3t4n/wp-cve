
// Disable connect button till key up on client id/secret fields
document.addEventListener('DOMContentLoaded', function () {
    var clientInputs = [
        '#autoship_client_secret',
        '#autoship_client_id'
    ].map(function (id) {
        return document.querySelector(id);
    });

    clientInputs.forEach(function (input) {
        if (input !== null) {
            input.addEventListener('keyup', function () {
                var connectBtn = document.querySelector('#autoship_connect_autoship_button');
                connectBtn.style.display = 'none';
            });
        }
    });
});

jQuery(document).ready(function ($) {

  // HACK: Prevent "Leaving Page" Browser notice
  $(document).on('click', '#autoship-update-sync', function(e) {
    window.onbeforeunload = null;
    e.preventDefault();
    var submit = $(this).closest("form").find("#publish");
    submit.trigger('click');
  });

  // Show / Hide Tab Content on Admin Reports
  $(document).on('click', '.autoship-admin-reports .nav-tab', function(e) {

    e.preventDefault();

    if ( $(this).hasClass('active') )
    return;

    // Which option group to hide.
    var target = $(this).attr('data-target');
    var $target = $( '#' + target );

    var tabs = $('.autoship-admin-reports .nav-tab');
    var panes = $('.autoship-admin-reports .tab-pane');

    tabs.removeClass('active');
    panes.removeClass('active');
    $(this).addClass('active');
    $target.addClass('active');
  });

  // Show / Hide Frequency Overrie Options based on checkbox
  $(document).on('click', '.autoship_hide_show_toggler', function(e) {

    // Which option group to hide.
    var target = $(this).attr('data-target');
    $(document).find( target ).fadeToggle('fast');

  });

  // Show / Hide Frequency Overrie Options based on checkbox
  $(document).on('click', '.autoship_trigger', function(e) {

    // Which option group to hide.
    var targetShow = $(this).attr('data-show-target');
    var targetHide = $(this).attr('data-hide-target');

    if ( typeof targetHide != 'undefined' ){

      $(document).find( targetHide ).fadeOut('fast', function() {

        if ( typeof targetShow != undefined )
        $(document).find( targetShow ).fadeIn('fast');

      });

    } else {

      $(document).find( targetShow ).fadeIn('fast');

    }

  });

  // Adjust view for select
  $(document).on( 'change', '#autoship_product_info_display', function(e){

    var toggles = {
      modal: {
        show:'#autoship_product_info_modal_size, #autoship_product_info_btn_type_block, #autoship_product_info_html_block',
        hide:'#autoship_product_info_url_block, #autoship_product_info_mobile_tooltip_block'
      },
      link: {
        show:'#autoship_product_info_url_block, #autoship_product_info_btn_type_block',
        hide:'#autoship_product_info_modal_size, #autoship_product_info_html_block, #autoship_product_info_mobile_tooltip_block'
      },
      none: {
        show:'',
        hide:'#autoship_product_info_url_block, #autoship_product_info_modal_size, #autoship_product_info_html_block, #autoship_product_info_btn_type_block, #autoship_product_info_mobile_tooltip_block'
      },
      tooltip: {
        show:'#autoship_product_info_modal_size, #autoship_product_info_btn_type_block, #autoship_product_info_html_block, #autoship_product_info_mobile_tooltip_block',
        hide:'#autoship_product_info_url_block'
      }
    }

    var val = $( this ).val();

    $( toggles[val].hide ).fadeOut('fast', function(){
      $( toggles[val].show ).fadeIn('fast');
    });

  });

  /**
  * Adjust Classes for new Active Option
  * Allows for Show/Hide of dynamically created Variations
  */
  $(document).on('click', '#_autoship_sync_active_enabled', function(e) {

    // Grab the overal WC Product Data element.
    var target = $('#woocommerce-product-data');
    var target_inner = $('#autoship_product_data');
    if ($(this).prop("checked") == true){
      target.addClass('autoship-active');
      target_inner.addClass('autoship-active');
    }
    else if($(this).prop("checked") == false){
      target.removeClass('autoship-active');
      target_inner.removeClass('autoship-active');
    }

  });

  /**
   * On Product Type Change Launch Modal Warning
   */
  $( document ).on( 'woocommerce-product-type-change', function( e, selected ){

    if ( typeof autoship_product_type == 'undefined' || autoship_product_type == selected )
    return;

    if ( $('#autoship_product_data').hasClass('autoship-active') )
    autoship_modal_toggle ( '#autoship_product_summary' );

  });

  /**
   * On Product Status or Visibility Change Launch Modal Warning
   */
  $( document ).on( 'click', '.edit-post-status, .edit-visibility', function( e, selected ){

    if ( typeof autoship_product_type == 'undefined' )
    return;

    if ( $('#autoship_product_data').hasClass('autoship-active') )
    autoship_modal_toggle ( '#autoship_product_summary' );

  });

  // Copies the Link to the Clipboard
  function autoship_copy_link_clipboard( target ) {
    var $target = $( target );
    var value = $target.select();
    var help = $target.attr('data-help-result');

    document.execCommand("copy");

    $('.autoship-link-help-text').html(help).fadeIn('fast', function(e){
      $(this).delay(2000).fadeOut('slow');
    });

  }

  // Generates the Add to Cart or Add to Schedule Link.
  function autoship_link_builder_link( element ){

    var builder = element.closest('.autoship-schedule-link-builder');
    var product_type = element.attr('data-product-type');
    var action = element.attr('data-link-action');

    var result = builder.find('#autoship-link-builder-product-select option:selected').val();
    var holder = builder.find('.autoship-link-value');

    var schedule = builder.find('#autoship-link-frequency-select option:selected').val();
    if ( !result ){
      holder.val('');
      return false;
    }

    schedule = jQuery.parseJSON( builder.find('#autoship-link-frequency-select option:selected').val() );
    var freqtype = schedule.frequency_type;
    var freq = schedule.frequency;

    var freqtype = schedule.frequency_type;
    var freq = schedule.frequency;

    var urltype = builder.find("input[name='autoship-link-type']:checked").val();
    var qty = builder.find("input[name='autoship-link-qty']").val();
    var schedule = jQuery.parseJSON( builder.find('#autoship-link-frequency-select option:selected').val() );

    if ( 'addtocart' == urltype ){

      var help = autoship_addtocart_help;

      baseurl = autoship_cart_url;

      baseurl += '?add-to-cart=' + result;
      baseurl += '&autoship_frequency_type=' + encodeURIComponent( freqtype );
      baseurl += '&autoship_frequency=' + freq;
      baseurl += '&quantity=' + qty;

    } else {

      var freqtype = schedule.frequency_type;
      var freq = schedule.frequency;

      var ignoreSchedule = builder.find("#autoship-ignore-schedule").is(':checked');

      var min = builder.find("input[name='autoship-link-min-cycle']").val();
      var max = builder.find("input[name='autoship-link-max-cycle']").val();
      var includeCycles = builder.find("#autoship-ignore-cycles").is(':checked');

      var help = autoship_addtoschedule_help;

      baseurl = autoship_scheduled_orders_url;

      baseurl += '?action=' + encodeURIComponent( action );
      baseurl += '&item=' + result;

      if ( ! ignoreSchedule ){
        baseurl += '&freqtype=' + encodeURIComponent( freqtype );
        baseurl += '&freq=' + freq;
      }

      baseurl += '&qty=' + qty;

      if ( includeCycles ){
        baseurl += '&min=' + min;
        baseurl += '&max=' + max;
      }

    }

    holder.val( baseurl );

  }

  // Autoship Link Builder
  $(document).on('click', '.autoship-link-generate', function(e) {

    e.preventDefault();
    autoship_link_builder_link( $(this) );

  });

  // Copy Link Button
  $(document).on('click', '.autoship-link-copy', function(e) {

    e.preventDefault();
    autoship_copy_link_clipboard( $(this).attr('data-target') );

  });

  // Toggle for Add to Cart vs Add to Schedule
  $(document).on('click', '.autoship-schedule-link-toggler', function(e) {

    e.preventDefault();
    var target = $(this).attr('data-target');
    target = $( target ).toggleClass('hidden');

  });

  // Cycles Toggler
  $(document).on('click', '#autoship-ignore-cycles, #autoship-ignore-schedule', function(e){

    var target = $(this).attr('data-target');
    target = $( target ).fadeToggle('slow');

    var builder = $(this).closest('.autoship-schedule-link-builder');
    autoship_link_builder_link( builder.find('.autoship-link-generate') );

  });

  // Adjust on Link Builder type change
  $(document).on('click', ".autoship-schedule-link-builder input[name='autoship-link-type']", function(e) {

    var builder = $(this).closest('.autoship-schedule-link-builder');

    var ignoreSchedule = builder.find('.ignore-schedule');
    var ignoreScheduleOption = builder.find("#autoship-ignore-schedule");

    if ( 'addtocart' == $(this).val() ){

      builder.find('.help-text').fadeOut('slow', function() {
        $(this).html( autoship_addtocart_help ).fadeIn('slow');
      });

      ignoreScheduleOption.attr('checked', false);
      ignoreSchedule.fadeOut('slow');

      $('.autoship-schedule').fadeIn('slow');
      builder.find('.cycles').fadeOut('slow');


    } else {

      builder.find('.help-text').fadeOut('slow', function() {
        $(this).html( autoship_addtoschedule_help ).fadeIn('slow');
      });
      builder.find('.cycles').fadeIn('slow');
      ignoreSchedule.fadeIn('slow');

    }

    autoship_link_builder_link( builder.find('.autoship-link-generate') );


  });

  // Order Action Toggle
  $(document).on('click', '.autoship-order-actions-btn', function(e) {

    e.preventDefault();
    var order_actions = $("select[name='wc_order_action']");
    var submit = order_actions.closest("form").find("button[name='save']");
    var action = $(this).attr('data-target-action');

    order_actions.val(action).change();
    submit.trigger('click');

  });

  // Autoship modal
  function autoship_modal_toggle( target_modal ){

    var targetModal = $( target_modal );
    targetModal.toggleClass('open');
    $( 'body' ).toggleClass('autoship-modal-open');

  }

  $(document).on('click', ".autoship-modal-trigger", function( e ){
    var modal = $(this).attr('data-modal-toggle');
    autoship_modal_toggle ( modal );
  });

  $(document).on('click', ".autoship-modal .close", function( e ) {

    $(this).closest('.autoship-modal').removeClass('open');
    $( 'body' ).removeClass('autoship-modal-open');

  });

  $(document).on('click', ".autoship-modal", function( e ) {

    if ( $( e.target ).hasClass('autoship-modal-content') )
    return;

    $(this).closest('.autoship-modal').removeClass('open');
    $( 'body' ).removeClass('autoship-modal-open');

  });

  /**
   * Trigger Migration Ajax form submit
   */
  var migrationFormsubmit = function( e ) {

    e.preventDefault();

    var form = $(this).closest('form');
    form.removeClass('active');

    var migration_targets = [];

    migration_targets.form = $(this).closest('form');
    migration_targets.target_btn = form.find('button.action');
    migration_targets.target_cancel_btn = form.find('button.cancel-action');
    migration_targets.target_notice = form.find('.autoship-bulk-notice');
    migration_targets.target_subnotice = form.find('.autoship-bulk-subnotice');
    migration_targets.target_success_action = form.find('.autoship-bulk-success-action')

    migration_targets.form.addClass('active');
    migration_targets.data = { "action":"autoship_initiate_schedule_export" };
    migration_targets.target_subnotice.fadeIn('slow');

    $.ajax({
      type: 'POST',
      dataType: 'json',
      data: migration_targets.data,
      url: ajaxurl,
      success: function(response) {
        migration_targets.target_notice.fadeOut('slow', function() {
          if ( response.success ){
            $(this).html(response.notice).fadeIn('slow');
            migration_targets.target_success_action.fadeIn('slow');
          } else {
            $(this).html('<span style="color:red;">An Issue was encountered exporting the Scheduled Orders.</span>').fadeIn('slow');
            migration_targets.target_success_action.fadeOut('slow');
          }
        });
      },
      failure: function(response) {
        migration_targets.target_notice.fadeOut('slow', function() {
          response.success = false;
          $(this).html(response.notice).fadeIn('slow');
          migration_targets.target_success_action.fadeOut('slow');
        });
      }
    });

  };

  /**
   * Initiates the Scheduled CSV Export
   */
  $('#autoship-bulk-export-csv').on( 'click', 'button.autoship-export-action', migrationFormsubmit );

  /**
   * Log file download link toggler
   */
  function selectLogDownloadLink() {

    var link = $(document).find('#_autoship_log_file_select').val();

    if ( typeof link === undefined || null === link )
    return;

    $(document).find('.log-files .autoship-action').attr( "href", link );

  }
  $(document).find('#_autoship_log_file_select').on( 'change' , selectLogDownloadLink );

});
