//Function to Check is Email
function Check_isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}


jQuery(document).ready(function($) {
  //Subscribe to the newsletter
  $('form.wc-mailchimp-subscribe-form').submit(function() {
    var SelectedForm = $(this);
    var FormFields = SelectedForm.find('.wcmnd-fields input');
    var errors = '';
    var SubmitButton = SelectedForm.find('.newsletter-discount-submit-button');
    SelectedForm.find('.newsletter-discount-validation').hide();
    SelectedForm.find('.newsletter-discount-validation').removeClass('success');
    SelectedForm.find('.newsletter-discount-validation').removeClass('error');

    var FirstName = SelectedForm.find('.wcmnd_fname').val();
    var LastName = SelectedForm.find('.wcmnd_lname').val();
    var Email = SelectedForm.find('.wcmnd_email').val();
    var ExtraFieldsData;

    if( SelectedForm.find('.wcmnd-addon-extra-field').length ) {
        SelectedForm.find('.wcmnd-addon-extra-field').each(function() {
          if( $(this).attr('data-required') == 'yes' ) {
            var Self = $(this);
            var SelfValue = $(this).val();
            var SelfDataType = $(this).attr('data-type');
            var SelfDataValidation = $(this).attr('data-validation');

            if( SelfDataType == 'text' ) {
              if( SelfDataValidation !== '' && SelfValue == '' ) {
                errors += SelfDataValidation + ',';
              }
            }

            if( SelfDataType == 'select' ) {
              if( SelfDataValidation !== '' && SelfValue == '' ) {
                errors += SelfDataValidation + ',';
              }
            }

            if( SelfDataType == 'checkbox' ) {
              if( Self.is(':checked') == false && SelfDataValidation !== '' ) {
                errors += SelfDataValidation + ',';
              }
            }

            if( SelfDataType == 'radio' ) {
              var DataParentValidation = Self.attr('data-validation');
              if( Self.find('input[type=radio]').is(':checked') == false && DataParentValidation !== '' ) {
                errors += SelfDataValidation + ',';
              }
            }
          }
        });
      }

    //Check if Email is valid or not
    if( Check_isEmail(Email) && errors == '' ) {
      if( SelectedForm.find('.wcmnd-addon-extra-field').length ) {
        ExtraFieldsData = SelectedForm.find('.wcmnd-addon-extra-field').serializeArray();
      }

      SubmitButton.text(wcmnd.please_wait);
      SubmitButton.prop("disabled", true);

      $.ajax({
        url: wcmnd.ajax_url,
        method: 'post',
        beforeSend: function(xhr) {
          xhr.setRequestHeader('X-WP-Nonce', wcmnd.nonce);
        },
        data: {
          fname: FirstName,
          lname: LastName,
          email: Email,
          nonce: wcmnd.nonce,
          extraFields: ExtraFieldsData,
          action: 'woocommerce_newsletter_subscribe'
        },
        success: function(data) {
          var response = $.parseJSON(data);
          SubmitButton.text(wcmnd.subscribe_button_label);
          SubmitButton.prop("disabled", false);

          if (typeof response.status !== "undefined" && response.title == 'Invalid Resource') {
            SelectedForm.find('.newsletter-discount-validation').html(response.detail).addClass('error').css('display', 'inline-block');
          } else if (typeof response.status !== "undefined" && response.status == "deleted") {
            SelectedForm.find('.newsletter-discount-validation').html(response.detail).addClass('error').css('display', 'inline-block');
          } else if (typeof response.status !== "undefined" && response.status == 'subscribed') {
            SelectedForm.find('.newsletter-discount-validation').html(wcmnd.userExists).addClass('error').css('display', 'inline-block');
          } else if (typeof response.status !== "undefined" && response.status == 'error' && response.title !== "Invalid Resource") {
            SelectedForm.find('.newsletter-discount-validation').html(response.error).addClass('error').css('display', 'inline-block');
          } else if (typeof response.status !== "undefined" && response.email_response == 'error') {
            SelectedForm.find('.newsletter-discount-validation').html(wcmd.email_sent_error).addClass('error').css('display', 'inline-block');
          } else {
            var SuccessMsg = wcmnd.success_message;

            SelectedForm.find('.newsletter-discount-validation').html(SuccessMsg).addClass('success').css('display', 'inline-block');

            SubmitButton.text(wcmnd.subscribe_button_label);

            if (wcmnd.enable_redirect == 'yes' && wcmnd.redirect_url !== '') {
              window.setTimeout(function() {
                window.location.href = wcmnd.redirect_url;
              }, wcmnd.redirect_timeout * 1000);
            }
          }
        }
      });
    }
    else {
      if( errors.length > 0 ) {
        errorList = errors.replace(/,/g, '<br>');
        SelectedForm.find('.newsletter-discount-validation').html( errorList ).addClass('error').css('display','inline-block');
      }
      else {
        //Show invalid error message for email field
        SelectedForm.find('.newsletter-discount-validation').html( 'Please enter valid email' ).addClass('error').css('display','inline-block');
      }
    }
    return false;
  });

});
