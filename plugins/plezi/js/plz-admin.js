jQuery(function($) {
  $(document).ready(function() {
    if($('#plezi-configuration-authentification-form').length) {
      var check_form_submitted = false;

      if($('#plz_authentification_public_key').val().length < 1 || $('#plz_authentification_secret_key').val().length < 1) {
        $('#plezi-configuration-authentification-form #plezi-get-authentification').addClass('disabled');
        $('#plezi-configuration-authentification-form #plezi-get-authentification').attr('disabled', 'disabled');
      }

      $('#plz_authentification_public_key').keyup(function(event) {
      	if($(this).val() && $('#plz_authentification_secret_key').val().length > 0) {
          $('#plezi-configuration-authentification-form #plezi-get-authentification').removeClass('disabled');
          $('#plezi-configuration-authentification-form #plezi-get-authentification').removeAttr('disabled', 'disabled');
        } else {
          $('#plezi-configuration-authentification-form #plezi-get-authentification').addClass('disabled');
          $('#plezi-configuration-authentification-form #plezi-get-authentification').attr('disabled', 'disabled');
        }
      });

      $('#plz_authentification_secret_key').keyup(function(event) {
      	if($(this).val() && $('#plz_authentification_public_key').val().length > 0) {
          $('#plezi-configuration-authentification-form #plezi-get-authentification').removeClass('disabled');
          $('#plezi-configuration-authentification-form #plezi-get-authentification').removeAttr('disabled', 'disabled');
        } else {
          $('#plezi-configuration-authentification-form #plezi-get-authentification').addClass('disabled');
          $('#plezi-configuration-authentification-form #plezi-get-authentification').attr('disabled', 'disabled');
        }
      });

      $('#plezi-configuration-authentification-form #plezi-get-authentification').click(function(event) {
        event.preventDefault();

        $('.plz-loader').show();

        var public = $('#plz_authentification_public_key').val();
        var secret = $('#plz_authentification_secret_key').val();

        $.ajax({
          method: 'POST',
          url: plzapi.plzsetauthentification,
          headers: {"X-WP-Nonce": plzapi.plznonceapi},
          data: {public: public, secret: secret},
          success: function(result, status) {
            if(!result.error && result.success) {
              $('.plz-error-authentification').hide();
              $('.plz-validation-authentification').show();
              $('#plezi-configuration-authentification-form #plezi-get-authentification').hide();
              $('#plezi-configuration-authentification-form #plezi-remove-authentification').show();
              $('.plezi-left-column .plezi-tabs-buttons li.active span').removeClass('status-disable');
              $('.plezi-left-column .plezi-tabs-buttons li.active span').addClass('status-enable');
              $('.plz-loader').hide();
            } else {
              $('.plz-error-authentification').show();
              $('.plz-validation-authentification').hide();
              $('.plz-loader').hide();
            }
          }
        });
      });

      $('#plezi-configuration-authentification-form #plezi-remove-authentification').click(function(event) {
        event.preventDefault();

        $('.plz-loader').show();

        $.ajax({
          method: 'GET',
          url: plzapi.plzremoveauthentification,
          headers: {"X-WP-Nonce": plzapi.plznonceapi},
          success: function(result, status) {
            if(!result.error && result.success) {
              $('.plz-error-authentification').hide();
              $('.plz-validation-authentification').hide();
              $('#plz_authentification_public_key').val('');
              $('#plz_authentification_secret_key').val('');
              $('#plezi-configuration-authentification-form #plezi-get-authentification').addClass('disabled');
              $('#plezi-configuration-authentification-form #plezi-get-authentification').attr('disabled', 'disabled');
              $('#plezi-configuration-authentification-form #plezi-get-authentification').show();
              $('#plezi-configuration-authentification-form #plezi-remove-authentification').hide();
              $('.plezi-left-column .plezi-tabs-buttons li.active span').removeClass('status-enable');
              $('.plezi-left-column .plezi-tabs-buttons li.active span').addClass('status-disable');
              $('.plz-loader').hide();
            }
          }
        });
      });
    }

    if($('.plezi-left-column').length) {
      $('.plezi-left-column ul li a').click(function(event) {
        event.preventDefault();
      });

      $('.plezi-left-column ul li').click(function(event) {
        var id = $(this).find('a').attr('data-tab');

        $('.plezi-left-column ul li').removeClass('active');
        $('.plezi-right-column .plezi-tab-content').hide();
        $('.plezi-right-column #' + id).show();
        $(this).addClass('active');
      });
    }

    if($('#plezi-configuration-tracking-form input.plezi-radio').length) {
      if($('#plezi-configuration-tracking-form #api').prop('checked')) {
        $('.plezi-tracking-api-wrapper').show();
        $('.plezi-tracking-manual-wrapper').hide();
      }

      if($('#plezi-configuration-tracking-form #manual').prop('checked')) {
        $('.plezi-tracking-api-wrapper').hide();
        $('.plezi-tracking-manual-wrapper').show();
      }

      $('#plezi-configuration-tracking-form input.plezi-radio').change(function() {
        if($(this).val() == 'api') {
          $('.plezi-tracking-api-wrapper').show();
          $('.plezi-tracking-manual-wrapper').hide();
        } else {
          $('.plezi-tracking-api-wrapper').hide();
          $('.plezi-tracking-manual-wrapper').show();
        }
      });

      $('#plz_configuration_tracking_enable_manual').change(function() {
        $('#plezi-set-tracking-manual').removeClass('disabled');
        $('#plezi-set-tracking-manual').removeAttr('disabled');
      });

      $('#plezi-set-tracking-manual').click(function(event) {
        event.preventDefault();

        var data;

        $('.plz-loader').show();

        if($('#plezi-configuration-tracking-form input#plz_configuration_tracking_enable_manual').is(':checked')) {
          $('#plezi-set-tracking-manual').addClass('disabled');
          $('#plezi-set-tracking-manual').attr('disabled', 'disabled');
          $('.plezi-left-column .plezi-tabs-buttons li.active span').removeClass('status-disable');
          $('.plezi-left-column .plezi-tabs-buttons li.active span').addClass('status-enable');

          data = 'checked';
        } else {
          $('#plezi-set-tracking-manual').removeClass('disabled');
          $('#plezi-set-tracking-manual').removeAttr('disabled');
          $('.plezi-left-column .plezi-tabs-buttons li.active span').removeClass('status-enable');
          $('.plezi-left-column .plezi-tabs-buttons li.active span').addClass('status-disable');

          data = '';
        }

        $.ajax({
          method: 'POST',
          url: plzapi.plzsettrackingmanualstatus,
          headers: {"X-WP-Nonce": plzapi.plznonceapi},
          data: {status: data},
          success: function(result, status) {
            if($('#plezi-configuration-tracking-form input#plz_configuration_tracking_enable_manual').is(':checked') && result.date) {
              $('.plz-manual-date-validation-wrapper .plz-date-validation span').html(result.date);
              $('.plz-manual-date-validation-wrapper').show();
            } else {
              $('.plz-manual-date-validation-wrapper').hide();
            }

            $('.plz-loader').hide();
          }
        });
      });
    }

    if($('.plezi-tracking-api-wrapper #plezi-get-tracking')) {
      $('.plezi-tracking-api-wrapper #plezi-get-tracking').click(function(event) {
        event.preventDefault();

        $('.plz-loader').show();

        $.ajax({
          method: 'GET',
          url: plzapi.plzgettrackingapi,
          headers: {"X-WP-Nonce": plzapi.plznonceapi},
          success: function(result, status) {
            if(!result.error && result.script) {
              $('#plz_configuration_tracking_enable').removeAttr('disabled');
              $('#plz_configuration_tracking_code').val(result.script);
              $('#plz_configuration_tracking_code').attr('disabled', 'disabled');
              $('#plezi-remove-tracking').removeClass('plz-hidden');
              $('#plezi-get-tracking').addClass('plz-hidden');

              $('.plz-loader').hide();
            } else {
              $('.plz-loader').hide();
            }
          }
        });
      });
    }

    if($('#plz_configuration_tracking_code')) {
      $('#plz_configuration_tracking_code').change(function() {
        $('.plz-loader').show();

        var tracking = $('#plz_configuration_tracking_code').val();

        if (tracking.indexOf('https://brain.plezi.co/api/v1/analytics') >= 0) {
          $.ajax({
            method: 'POST',
            url: plzapi.plzsettrackingapi,
            headers: {"X-WP-Nonce": plzapi.plznonceapi},
            data: {tracking: tracking},
            success: function(result, status) {
              if(!result.error && result.script) {
                $('#plz_configuration_tracking_enable').removeAttr('disabled');
                $('#plz_configuration_tracking_code').attr('disabled', 'disabled');
                $('#plezi-remove-tracking').removeClass('plz-hidden');
                $('#plezi-get-tracking').addClass('plz-hidden');

                $('.plz-loader').hide();
              } else {
                $('.plz-loader').hide();
              }
            }
          });
        } else {
          $('#plz_configuration_tracking_code').val('');
          $('.plz-loader').hide();
        }
      });
    }

    if($('.plezi-tracking-api-wrapper #plezi-remove-tracking')) {
      $('.plezi-tracking-api-wrapper #plezi-remove-tracking').click(function(event) {
        event.preventDefault();

        var result = confirm(plzlabels.plztrackingremovescript);

        if(result) {
          $('.plz-loader').show();

          $.ajax({
            method: 'GET',
            url: plzapi.plzremovetrackingapi,
            headers: {"X-WP-Nonce": plzapi.plznonceapi},
            success: function(result) {
              $('#plz-label-active').hide();
              $('#plz-label-inactive').show();
              $('#plz_configuration_tracking_enable').prop('checked', false);
              $('#plz_configuration_tracking_enable').attr('disabled', 'disabled');
              $('.plz-tracking-confirmation-sentence').hide();
              $('.plezi-tracking-api-wrapper .plz-date-validation').hide();
              $('.plezi-left-column .plezi-tabs-buttons li.active span').removeClass('status-enable');
              $('.plezi-left-column .plezi-tabs-buttons li.active span').addClass('status-disable');
              $('#plz_configuration_tracking_code').val('');
              $('#plz_configuration_tracking_code').removeAttr('disabled');
              $('#plezi-remove-tracking').addClass('plz-hidden');
              $('#plezi-get-tracking').removeClass('plz-hidden');
              $('.plz-loader').hide();
            }
          });
        }
      });
    }

    if($('.plezi-tracking-api-wrapper .plz-switch #plz_configuration_tracking_enable').length) {
      $('.plezi-tracking-api-wrapper .plz-switch #plz_configuration_tracking_enable').change(function() {
        var data;

        $('.plz-loader').show();

        if($(this).is(':checked')) {
          $('#plz-label-active').show();
          $('#plz-label-inactive').hide();
          $('.plz-tracking-confirmation-sentence').show();
          $('.plezi-left-column .plezi-tabs-buttons li.active span').removeClass('status-disable');
          $('.plezi-left-column .plezi-tabs-buttons li.active span').addClass('status-enable');

          data = 'checked';
        } else {
          $('#plz-label-active').hide();
          $('#plz-label-inactive').show();
          $('.plz-tracking-confirmation-sentence').hide();
          $('.plezi-left-column .plezi-tabs-buttons li.active span').removeClass('status-enable');
          $('.plezi-left-column .plezi-tabs-buttons li.active span').addClass('status-disable');

          data = '';
        }

        $.ajax({
          method: 'POST',
          url: plzapi.plzsettrackingapistatus,
          headers: {"X-WP-Nonce": plzapi.plznonceapi},
          data: {status: data},
          success: function(result, status) {
            if($('.plezi-tracking-api-wrapper .plz-switch #plz_configuration_tracking_enable').is(':checked') && result.date) {
              $('.plezi-tracking-api-wrapper .plz-date-validation span').html(result.date);
              $('.plezi-tracking-api-wrapper .plz-date-validation').show();
            } else {
              $('.plezi-tracking-api-wrapper .plz-date-validation').hide();
            }

            $('.plz-loader').hide();
          }
        });
      });
    }

    if($('.plezi-wrap-faq').length) {
      $('.plezi-wrap-faq ul li .plezi-question').each(function(index) {
        $(this).click(function() {
          $(this).parent().toggleClass('active');
        });
      });
    }

    if($('.plz-preview-shortcode').length) {
      $('.plz-preview-shortcode').click(function(event) {
        event.preventDefault();

        var html = '<form id="plz-form-'+$(this).attr('form-id')+'"></form><script async src="https://brain.plezi.co/api/v1/web_forms/scripts?content_web_form_id='+$(this).attr('form-id')+'"></script>';

        $('#plz-popup-preview-wrapper').show();

        setTimeout(function() {
          $('#plz-popup-preview .plz-popup-content').html(html);
        }, 1000);
      });

      $('.plz-popup-close').click(function(event) {
        event.preventDefault();

        var html = '<div class="plz-lds-dual-ring"></div>';

        $('#plz-popup-preview-wrapper').hide();
        $('#plz-popup-preview .plz-popup-content').html(html);
      });
    }

    if($('.plz-copy-shortcode').length) {
      $('.plz-copy-shortcode').click(function(event) {
        event.preventDefault();

        var $temp = $('<input>');

        $('body').append($temp);
        $temp.val('[plezi form='+$(this).attr('form-id')+']').select();
        document.execCommand('copy');
        $temp.remove();
      });
    }
  });
});
