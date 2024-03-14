(function ($) {
  tippy('#hq-tooltip-tenant-token')
  $('#hq-submit-login-button').on('click', function () {
    try {
      login(jQuery)
    } catch (e) {
      alert(e)
    }
  })
})(jQuery)

function onAdvancedActive(selector) {
  jQuery(selector).removeClass('fa-angle-down')
  jQuery(selector).addClass('fa-angle-right')
}

function login($) {
  $('.hq-messages-box-success').slideUp()
  $('.hq-messages-box-failed').slideUp()
  $(".hq-loader").slideDown()

  $.ajax(hqWebsiteURL + '/wp-json/hqrentals/plugin/auth', {
    type: 'GET',
    data: {
      email: $("#hq-email").val(),
      password: $('#hq-password').val()
    },
    dataType: 'json',
    success: function (response) {
      jQuery(".hq-loader").slideUp()
      if (response.data.success) {
        var tenants = response.data.data.tenants
        var user = response.data.data.user
        if (Array.isArray(tenants)) {
          jQuery("#hq-api-user-token").val(user.api_token)
          jQuery("#hq-api-tenant-token").val(tenants[0].api_token)
          jQuery("#hq-api-user-base-url").val(tenants[0].api_link)
          jQuery("#hq-not-connected-indicator").slideUp(400, function () {
            jQuery("#hq-connected-indicator").slideDown()
          })
          jQuery('.hq-messages-box-success').slideDown()
          jQuery('.hq-login-wrapper').toggle(1000)
          onAdvancedActive('#hq-advanced-button-icon')
        }
      } else {
        $('.hq-messages-box-failed').slideDown()
        $('.hq-messages-box-failed .alert-danger').html(
          $('.hq-messages-box-failed .alert-danger').html() + ': ' + response.data.errors.error_message
        )
      }
    },
    error: function () {
      jQuery(".hq-loader").slideUp()
      $('.hq-messages-box-failed').slideDown()
    }
  }).always(function () {
    $('.loading-overlay').hide()
    $(".caag-software-pre-loader").fadeOut("fast")
  })
}
