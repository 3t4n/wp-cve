jQuery(document).ready(function ($) {
  var intervalId = setInterval(payamito_woocommerce_init_otp, 3000);

  function payamito_woocommerce_init_otp() {
    setTimeout(function () {
      clearInterval(intervalId);
      $id = PAYAMITO_WC_OTP_CONFIG.configs.phone_field_id;
      let $phone_field;
      if ($id.includes('|')) {
        $id = $id.split('|');
        for (let index = 0; index < $id.length; index++) {
          $phone_field = $(`#${$id[index]}`);
          if (
            typeof $phone_field.append === 'function' && $phone_field.length >=1 && $('#payamito_wc_send_otp').length < 1
          ) {
            $phone_field
              .parent()
              .append(
                `<div class="wc-block-checkout__actions_row" style='padding:1px ;text-align: center;'><button type="button" id='payamito_wc_send_otp' class="components-button wc-block-components-button wp-element-button " style= width: 30%;font-size: small; text-align: center;margin: 1px;padding: 10px;min-width: 50%;'><span class="wc-block-components-button__text" style="color:white">${PAYAMITO_WC_OTP_CONFIG.configs.otp_field_config.send_btn_text}</span></button></div>`
              );
            break;
          }
        }
      } else {
        $phone_field = $(`#${PAYAMITO_WC_OTP_CONFIG.configs.phone_field_id}`);
        if (
          typeof $phone_field.append === 'function' &&
          $phone_field.length > 0
        ) {
          $phone_field
            .parent()
            .append(
              `<div class="wc-block-checkout__actions_row" style='padding:1px ; text-align: center;'><button type="button" id='payamito_wc_send_otp' class="components-button wc-block-components-button wp-element-button " style='width: 30%;font-size: small; text-align: center;margin: 1px;padding: 10px;min-width: 50%;'><span class="wc-block-components-button__text">${PAYAMITO_WC_OTP_CONFIG.configs.otp_field_config.send_btn_text}</span></button></div>`
            );
        }
      }
      $('#payamito_wc_send_otp').on('click', function () {
        Spinner((type = 'start'));
        $.ajax({
          url: general.ajaxurl,
          type: 'POST',
          data: {
            action: 'payamito_woocommerce',
            nonce: general.nonce,
            phone_number: $phone_field.val(),
          },
        })
          .done(function (r, s) {
            if (
              s == 'success' &&
              r != '0' &&
              r != '' &&
              typeof r === 'object'
            ) {
              notification(r.e, r.message);
              if (r.e == 1) {
                timer();
                if ($('#payamito_otp').length < 1) {
                  $('#payamito_wc_send_otp').after(
                    `<div class="wc-block-components-text-input  is-active"><label for="otp" >${PAYAMITO_WC_OTP_CONFIG.configs.otp_field_config.title}</label> <input type="number" required style='text-align: center;margin: 1px;min-width: 40%;padding: 10px;' name='otp' id="payamito_otp" autocapitalize="off" aria-invalid="false" placeholder="${PAYAMITO_WC_OTP_CONFIG.configs.otp_field_config.placeholder}" >`
                  );
                }
              }
            }
          })
          .always(function (r, s) {
            Spinner((type = 'close'));
          });
      });

      function notification(ty = -1, m) {
        switch (ty) {
          case (ty = -1):
            iziToast.error({
              timeout: 10000,
              title: general.error,
              message: m,
              displayMode: 2,
            });
            break;
          case (ty = 0):
            iziToast.warning({
              timeout: 10000,
              title: general.warning,
              message: m,
              displayMode: 2,
            });
            break;
          case (ty = 1):
            iziToast.success({
              timeout: 10000,
              title: general.success,
              message: m,
              displayMode: 2,
            });
        }
      }

      function Spinner(type = 'start') {
        if (type == 'start') {
          $.LoadingOverlay('show', { progress: true });
          $('form').bind('keypress', function (e) {
            if (e.keyCode == 13) {
              return false;
            }
          });
        } else {
          $.LoadingOverlay('hide');
        }
      }

      function timer() {
        var timer = PAYAMITO_WC_OTP_CONFIG.configs.resend_time;
        var send_btn = $('#payamito_wc_send_otp');
        var innerhtml = send_btn.html();
        send_btn.prop('disabled', true);
        var Interval = setInterval(function () {
          let seconds = parseInt(timer);
          seconds = seconds < 10 ? '0' + seconds : seconds;
          send_btn.html(seconds + ':' + general.second);
          if (--timer <= 0) {
            timer = 0;
            send_btn.removeAttr('disabled');
            send_btn.html(innerhtml);
            clearInterval(Interval);
          }
        }, 1000);
      }
    }, 500);
  }
});
