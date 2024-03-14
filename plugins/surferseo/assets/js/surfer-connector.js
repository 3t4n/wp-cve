jQuery(function ($) {
  var connected = surfer_connection_lang.connected

  if (connected) {
    $('.surfer-connected').show()
    $('.surfer-not-connected').hide()
  } else {
    $('.surfer-connected').hide()
    $('.surfer-not-connected').show()
  }

  var connection_check

  function check_connection_success() {
    var data = {
      action: 'check_connection_status',
      _surfer_nonce: surfer_connection_lang._surfer_nonce,
    }

    $.ajax({
      url: surfer_connection_lang.ajaxurl,
      type: 'POST',
      data: data,
      dataType: 'json',
      async: true,
      success: function (response) {
        if (true === response.connection) {
          $('#surfer-connection-spinner').hide()

          $('.surfer-connected').show()
          $('.surfer-not-connected').hide()

          $('#surfer-organization-name').html(
            response.details.organization_name
          )
          $('#surfer-via-email').html(response.details.via_email)

          clearInterval(connection_check)
        }
      },
    })
  }

  function make_disconnection() {
    var data = {
      action: 'disconnect_surfer',
      _surfer_nonce: surfer_connection_lang._surfer_nonce,
    }

    $.ajax({
      url: surfer_connection_lang.ajaxurl,
      type: 'POST',
      data: data,
      dataType: 'text',
      async: true,
      success: function (response) {
        $('#surfer-reconnection-spinner').hide()

        $('.surfer-connected').hide()
        $('.surfer-not-connected').show()
      },
    })
  }

  function make_connection() {
    var data = {
      action: 'generate_connection_url',
      auth_user_id: $('#surfer-auth-user').val(),
      _surfer_nonce: surfer_connection_lang._surfer_nonce,
    }

    $.ajax({
      url: surfer_connection_lang.ajaxurl,
      type: 'POST',
      data: data,
      dataType: 'json',
      async: true,
      success: function (response) {
        var win = window.open(response.url, '_blank')
        if (win) {
          connection_check = setInterval(check_connection_success, 5000)
          win.focus()
        } else {
          alert(surfer_connection_lang.popup_block_error)
        }
      },
    })
  }

  $('#surfer_reconnect').click(function (event) {
    event.preventDefault()

    $('#surfer-reconnection-spinner').show()
    make_disconnection()

    $('#surfer-connection-spinner').show()
    make_connection()
  })

  $('.surfer_make_connection').click(function (event) {
    event.preventDefault()

    $('#surfer-connection-spinner').show()
    make_connection()
  })

  $('#surfer_disconnect').click(function (event) {
    event.preventDefault()

    $('#surfer-reconnection-spinner').show()
    make_disconnection()
  })
})
