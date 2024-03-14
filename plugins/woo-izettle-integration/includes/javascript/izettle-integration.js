
jQuery(function ($) {

  var function_name = 'wciz_processing_button';

  function setButtonText(button_id, response) {

    if (response.button_text) {
      $('#' + button_id).text(`${response.button_text}`);
    }

  }

  function checkQueue(button_id) {

    jQuery.post(ajaxurl, { action: function_name, id: button_id, nonce: izettledata.nonce, task: 'check' }, function (response) {
      setButtonText(button_id, response);
      displayStatus(button_id, response)
      if (!response.ready) {
        setTimeout(function () { checkQueue(button_id); }, 3000);
      } else {
        removeStatus(button_id, response);
        displayMessage(button_id, response);
      }
    });
  }

  function displayStatus(button_id, response) {
    if (response.status_message) {
      var message = jQuery(`.${button_id}_status`);
      if (0 !== message.length) {
        message.html('<p>' + response.status_message + '</p>');
      } else {
        var message = jQuery(`<div id="message" class="updated ${button_id}_status"><p>${response.status_message}</p></div>`);
        message.hide();
        message.insertBefore(jQuery(`#${button_id}_titledesc`));
        message.fadeIn('fast');
      }
    }
  }

  function removeStatus(button_id, response) {
    var message = jQuery(`.${button_id}_status`);
    message.remove()
  }

  function displayMessage(button_id, response) {
    var message = jQuery(`<div id="message" class="updated"><p>${response.message}</p></div>`)
    message.hide()
    message.insertBefore(jQuery(`#${button_id}_titledesc`))
    message.fadeIn('fast', function () {
      setTimeout(function () {
        message.fadeOut('fast', function () {
          message.remove()
        })
      }, 5000)
    })
  }

  $('.' + function_name).on('click', function (e) {
    e.preventDefault()
    var button_id = $(this).attr('id');
    $.post(ajaxurl, { action: function_name, id: button_id, nonce: izettledata.nonce, task: 'start' }, function (response) {
      displayMessage(button_id, response);
      setButtonText(button_id, response);
      if (!response.ready) {
        checkQueue(button_id);
      }
    })
  });

  /**
   * Check if we have a sync ongoing when loading the page
   */
  let processing_status = $('.wciz_processing_status');
  if (processing_status.length) {
    var button_id = processing_status.attr('name');
    checkQueue(button_id);
  };

  $('#wciz_tokenchange').on('click', function (e) {
    e.preventDefault()
    jQuery.post(ajaxurl, { action: 'izettle_force_new_token', nonce: izettledata.nonce }, function (response) {
      window.location.reload();
    })
  })

  $('#wciz_clear_products').on('click', function (e) {
    e.preventDefault()
    if(confirm(izettledata.clear_meta_data_warning)){
      jQuery.post(ajaxurl, { action: 'izettle_clear_product_meta_data', nonce: izettledata.nonce }, function (response) {
        window.location.reload();
      })
    }
  })

  // Dismiss notice
  $('.notice-dismiss').on('click', function (e) {
    var is_iz_notice = jQuery(e.target).parents('div').hasClass('iz_notice');
    if (is_iz_notice) {
      var parents = jQuery(e.target).parent().prop('className');
      jQuery.post(ajaxurl, { action: 'izettle_clear_notice', nonce: izettledata.nonce, parents: parents }, function (response) { })
    }
  });

  // Authorize by redirecting to Zettle
  $('#wciz_authorize').on('click', function (e) {
    e.preventDefault()
    var username = jQuery('#izettle_username')
    jQuery.post(ajaxurl, { action: 'izettle_get_state', email: username.val(), nonce: izettledata.nonce }, function (response) {
      if (username.val() == '' || response.state == 'mismatch') {
        alert(izettledata.email_warning)
      } else {
        if (confirm(izettledata.redirect_warning)) {
          window.location.replace(response.state)
        }
      }
    })
  })

  $('#woocommerce-product-data').on('click', '.izettle_generate_barcode', function () {
    var input_id = $(this).attr('id').replace('button', '');
    var product_id = $(this).attr('name');
    jQuery.post(ajaxurl, { action: 'izettle_generate_barcode', nonce: izettledata.nonce, product_id: product_id }, function (response) {
      $('#' + input_id).val(response).change();
    });
  });


  // Show processing information
  $('.izettle-tip').tipTip({
    'attribute': 'data-tip',
    'fadeIn': 50,
    'fadeOut': 50,
    'delay': 200,
    'maxWidth': 'auto'
  });


  $('.wciz_stocklevel').on('change', function (e) {
    $(`.wciz_stocklevel_object`).closest('tr').hide();
    $(`.wciz_stocklevel_${$(this).val()}`).closest('tr').show();
  })

  $('.wciz_action').on('change', function (e) {
    $(`.wciz_action_object`).closest('tr').hide();
    $(`.wciz_action_${$(this).val()}`).closest('tr').show();
  })

  $('.wciz_barcode').on('change', function (e) {
    $(`.wciz_barcode_object`).closest('tr').hide();
    $(`.wciz_barcode_${$(this).val()}`).closest('tr').show();
  })

  $('.wciz_ean13').on('change', function (e) {
    $(`.wciz_ean13_object`).closest('tr').hide();
    $(`.wciz_ean13_${$(this).val()}`).closest('tr').show();
  })


  $(document).ready(function () {
    $(window).load(function () {
      $(`.wciz_objects`).closest('tr').hide();
      $(`.wciz_stocklevel_${$('.wciz_stocklevel').val()}`).closest('tr').show();
      $(`.wciz_action_${$('.wciz_action').val()}`).closest('tr').show();
      $(`.wciz_ean13_${$('.wciz_ean13').val()}`).closest('tr').show();
      $(`.wciz_barcode_${$('.wciz_barcode').val()}`).closest('tr').show();
    })
  });

});