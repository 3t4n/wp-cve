jQuery(document).ready(function ($) {
  $('#woocommerce_revolut_cc_restore_button').hide()
  const revolutMode = $('#woocommerce_revolut_mode')
  const apiDevKey = $('#woocommerce_revolut_api_key_dev').parents().closest('tr')
  const apiSandboxKey = $('#woocommerce_revolut_api_key_sandbox').parents().closest('tr')
  const apiLiveKey = $('#woocommerce_revolut_api_key').parents().closest('tr')
  const payment_action = $('#woocommerce_revolut_payment_action')

  const capture_checkbox = $('#woocommerce_revolut_accept_capture')
  const capture_checkbox_container = capture_checkbox.parents().closest('tr')

  const customize_capture_status_checkbox = $(
    '#woocommerce_revolut_customise_capture_status',
  )
  const customize_capture_status_checkbox_container = customize_capture_status_checkbox
    .parents()
    .closest('tr')

  const capture_status = $('#woocommerce_revolut_selected_capture_status_list')
    .parents()
    .closest('tr')
  $('#woocommerce_revolut_payment_request_onboard_applepay').hide()

  if (!capture_checkbox.is(':checked')) {
    customize_capture_status_checkbox_container.hide()
    capture_status.hide()
  } else {
    customize_capture_status_checkbox_container.show()
  }

  if (!customize_capture_status_checkbox.is(':checked')) {
    capture_status.hide()
  }

  if (revolutMode.val() == 'sandbox') {
    change('sandbox')
  } else if (revolutMode.val() == 'dev') {
    change('dev')
  } else {
    change('live')
  }

  if (payment_action.val() == 'authorize') {
    capture_checkbox_container.show()
  } else {
    capture_checkbox_container.hide()
    customize_capture_status_checkbox_container.hide()
    capture_status.hide()
  }

  customize_capture_status_checkbox.on('change', function () {
    capture_status.toggle($(this).is(':checked'))
    if (!$(this).is(':checked')) {
      $('#woocommerce_revolut_selected_capture_status_list').val(null).trigger('change')
    }
  })

  capture_checkbox.on('change', function () {
    customize_capture_status_checkbox_container.toggle($(this).is(':checked'))
    customize_capture_status_checkbox.change()

    if (!$(this).is(':checked')) {
      capture_status.hide()
    }
  })

  revolutMode.on('change', function () {
    var mode = $(this).val()

    if (mode == 'sandbox') {
      change('sandbox')
    } else if (mode == 'dev') {
      change('dev')
    } else {
      change('live')
    }
  })

  // change payment action
  payment_action.on('change', function () {
    if (payment_action.val() == 'authorize') {
      capture_checkbox_container.show()
      capture_checkbox.change()
    } else {
      capture_checkbox_container.hide()
      customize_capture_status_checkbox_container.hide()
      capture_status.hide()
    }
  })

  // change API mode
  function change(mode) {
    if (mode == 'sandbox') {
      apiSandboxKey.show()
      apiLiveKey.hide()
      apiDevKey.hide()
    } else if (mode == 'dev') {
      apiSandboxKey.hide()
      apiLiveKey.hide()
      apiDevKey.show()
      apiLiveKey.hide()
    } else {
      apiSandboxKey.hide()
      apiDevKey.hide()
      apiLiveKey.show()
    }
  }

  // onboard apple pay domain
  $('.setup-applepay-domain').click(function (event) {
    event.preventDefault()

    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {
        action: 'wc_revolut_onboard_applepay_domain',
        _wpnonce: default_options.nonce.wc_revolut_onboard_applepay_domain,
      },
      success: function (response) {
        let message = ''
        const setup_button = $('.setup-applepay-domain')
        if (!response.success && response.message) {
          message = response.message
        }

        if (response.success) {
          setup_button.text('Success')
          setup_button.prop('disabled', true)
          return true
        }

        $('.setup-applepay-domain-error')
          .show()
          .text('Setup Failed: ' + message)
      },
    })
  })

  $(document).on('change', '.revolut_styling_option_enable', function () {
    if (!$('.revolut_styling_option_enable').is(':checked')) {
      $('.revolut_styling_option').parents('tr').hide()
      restoreStylinOptions()
    } else {
      $('.revolut_styling_option').parents('tr').show()
    }
  })

  $(document).on('click', '.revolut_style_restore', function () {
    restoreStylinOptions()
  })

  if (!$('.revolut_styling_option_enable').is(':checked')) {
    $('.revolut_styling_option').parents('tr').hide()
  }

  function restoreStylinOptions() {
    $('#woocommerce_revolut_cc_widget_background_color')
      .val(default_options.default_bg_color)
      .trigger('change')
    $('#woocommerce_revolut_cc_widget_text_color')
      .val(default_options.default_text_color)
      .trigger('change')
    $('#woocommerce_revolut_cc_revolut_logo_color').prop('selectedIndex', 0).val()
  }

  adjustCardWidgetStylingOptions()

  $(document).on('change', '#woocommerce_revolut_cc_card_widget_type', function () {
    adjustCardWidgetStylingOptions()
  })

  function adjustCardWidgetStylingOptions() {
    if ($('#woocommerce_revolut_cc_card_widget_type').val() === 'popup') {
      $('#woocommerce_revolut_cc_styling_title').hide()
      $('#woocommerce_revolut_cc_widget_styling').parents('table').hide()
      $('#woocommerce_revolut_cc_enable_cardholder_name').parents('tr').hide()
    } else {
      $('#woocommerce_revolut_cc_styling_title').show()
      $('#woocommerce_revolut_cc_widget_styling').parents('table').show()
      $('#woocommerce_revolut_cc_enable_cardholder_name').parents('tr').show()
    }
  }

  $('#woocommerce_revolut_advanced_settings_clear_unused_order_records').hide()

  const consent_clear_unused_order_records = $(
    '#woocommerce_revolut_advanced_settings_consent_clear_unused_order_records',
  )

  consent_clear_unused_order_records.change(function () {
    if ($(this).is(':checked')) {
      consent_clear_unused_order_records.removeAttr('style')
    } else {
      consent_clear_unused_order_records.css({ borderColor: 'red' })
    }
  })

  $('.revolut_clear_unused_order_records').click(function (e) {
    e.preventDefault()
    self = $(this)

    if (!consent_clear_unused_order_records.is(':checked')) {
      consent_clear_unused_order_records.css({ borderColor: 'red' })
      alert(
        'Please indicate that you have read and agree to the consequences of this operation.',
      )
      return false
    }

    $.blockUI({
      message: null,
      overlayCSS: { background: '#fff', opacity: 0.6 },
    })

    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {
        action: 'wc_revolut_clear_records',
        _wpnonce: default_options.nonce.wc_revolut_clear_records,
      },
      success: function (response) {
        $.unblockUI()
        if (response && !response.success) {
          return alert(response.message)
        }

        alert('Operation completed successfully.')
      },
      error: function (jqXHR, textStatus, errorThrown) {
        $.unblockUI()
        if (jqXHR && jqXHR.responseText) {
          let response = jqXHR.responseText.match(/{(.*?)}/)
          if (response.length > 0 && response[0]) {
            try {
              response = JSON.parse(response[0])
              if (response.message) {
                alert(response.message)
                return false
              }
            } catch (e) {
              // swallow error and handle in generic block below
            }
          }
        }
        alert(errorThrown)
      },
    })
  })
})
