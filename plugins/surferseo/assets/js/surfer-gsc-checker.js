jQuery(function ($) {
  function check_gsc_result() {
    var data = {
      action: 'surfer_test_gsc_traffic_gatherer',
      debug: 1,
      _surfer_nonce: surfer_lang._surfer_nonce,
    }

    $('.surfer-test-gsc-connection-box__result').text('Loading...')

    $.ajax({
      url: surfer_lang.ajaxurl,
      type: 'POST',
      data: data,
      dataType: 'json',
      async: true,
      success: function (response) {
        if (typeof response === 'object' && response !== null) {
          let content = ''
          $.each(response, function (key, value) {
            content += value + '\r\n'
          })
          $('.surfer-test-gsc-connection-box__result').text(content)
        } else {
          $('.surfer-test-gsc-connection-box__result').text(response)
        }
      },
    })
  }

  $('.surfer-perform-gsc-connection-test').on('click', function (event) {
    event.preventDefault()
    check_gsc_result()
  })

  function transfer_data_to_new_format() {
    var data = {
      action: 'surfer_transfer_gsc_data_to_new_format',
      _surfer_nonce: surfer_lang._surfer_nonce,
    }

    $.ajax({
      url: surfer_lang.ajaxurl,
      type: 'POST',
      data: data,
      dataType: 'json',
      async: true,
      success: function (response) {
        $('.surfer-gsc-transfer-data-box__result').text(response)
      },
    })
  }

  $('.surfer-gsc-transfer-data-box__button').on('click', function (event) {
    event.preventDefault()
    transfer_data_to_new_format()
  })
})
