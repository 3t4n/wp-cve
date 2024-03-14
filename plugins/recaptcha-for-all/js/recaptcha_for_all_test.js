jQuery(document).ready(function(jQuery){
  // console.log('loaded test !');
  if (typeof recaptcha_my_data3 !== 'undefined') {
    var recaptchaDebugValue = recaptcha_my_data3.recaptchaDebug;
    var providerValue = recaptcha_my_data3.provider;
    // console.log('recaptchaDebug:', recaptchaDebugValue);
    //console.log('provider:', providerValue);
  } else {
    console.error('A variável recaptcha_my_data3 não está definida.');
    return;
  }
  // google
  if(providerValue !== 'turnstile')
    {
      var $sitekey = jQuery("#sitekey").val();
      var $secretkey = jQuery("#secretkey").val();
      if (!$sitekey || !$secretkey) {
        return;
      }
      var recaptcha_captcha_script = 'https://www.google.com/recaptcha/api.js?render='+$sitekey;
              window.recaptcha_captcha_script = document.createElement('script');
              window.recaptcha_captcha_script.src  = 'https://www.google.com/recaptcha/api.js?render='+$sitekey;
              //script.async = true;
              //script.defer = true;
              document.head.appendChild(window.recaptcha_captcha_script);
              window.recaptcha_captcha_script.onload = function () {
              //console.log('Carregou');
              };
              window.recaptcha_captcha_script.onerror = function () {
                alert('Erro ao carregar script recaptcha!');
              };
      jQuery('#recaptcha_for_all_test_keys').css('display', 'block');
      jQuery("#recaptcha_for_all_test_keys").click(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        jQuery('#recaptcha_for_all_test_keys').fadeOut(250).fadeIn(250); 
        jQuery('#recaptcha_for_all_test_keys').attr("disabled", true);
        // console.log('clicou');
        var $sitekey = jQuery("#sitekey").val();
        var globalToken ='';

        try {

            grecaptcha.execute($sitekey, { action: 'recaptcha_for_all_main' }).then(function (token) {
                // console.log('23');
                // console.log('Token: ' + token);
                globalToken = token;
                jQuery('#recaptcha_for_all').prepend('<input type="hidden" name="token" value="' + token + '">');
                jQuery('#recaptcha_for_all').prepend('<input type="hidden" name="action" value="recaptcha_for_all">');
                jQuery("#recaptcha_for_all").submit();
                    
                jQuery.ajax({
                  url: ajaxurl,
                  type: "POST",
                  data: {
                    action : 'recaptcha_for_all_test_keys_google',
                    sitekey : $sitekey,
                    secretkey: $secretkey,
                    mytoken: globalToken
                  },
                  success: function (data) {
                      // console.log(data);
                      if(data === '1'){
                        alert('Keys Validated!');
                      }
                      else {
                        alert('Keys NOT Validated!');
                      }
                  },
                  error: function (errorThrown) {
                      console.log(errorThrown);
                  }
                });
          }); // end grecaptcha execute...
        } catch (error) {
          console.error('Error in reCAPTCHA execution:', error.message);
          alert('Error in reCAPTCHA execution: '+ error.message);
      }


    });
    }
  if(providerValue === 'turnstile')
    {

      // return;


      var $sitekey = jQuery("#sitekey").val();
      var $secretkey = jQuery("#secretkey").val();
      if (!$sitekey || !$secretkey) {
         return;
      }
      function onloadTurnstileCallback() {
        console.log('Turnstile carregado com sucesso!');
      
            var $sitekey = jQuery("#sitekey").val();


            try{
              
                turnstile.render('body', {
                  sitekey: $sitekey,

                  callback: function(token) {
                      jQuery('#recaptcha_for_all_test_keys').css('display', 'block');
                      // console.log(`Challenge Success`);
                      // enable button test keys
                      jQuery('#recaptcha_for_all_test_keys').css('display', 'block');
                      globalToken = token;
                  }, 
                });

              
              } catch (error) {
                  console.error('Error in turnstile execution:', error.message);
              }

      }
      // Load Turnstyle for test...
      // Dynamically create a script element for Cloudflare Turnstile
      try{
          var script = document.createElement('script');
          script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=implicit';
          script.async = true;
          script.defer = true;
          // Add the onload callback function
          script.onload = onloadTurnstileCallback;
          // Append the script element to the document's head
          document.head.appendChild(script);


          // Global error handler
          window.onerror = function(message, source, lineno, colno, error) {
            if (isTurnstileError(error)) {
                console.error('Unhandled Turnstile error:', message, source, lineno, colno, error);
                displayErrorNotice();
            }
            return true; // Prevent default browser error handling
          };

          function isTurnstileError(error) {
            // Check if the error is a TurnstileError
            return error && error.message && error.message.includes('Turnstile');
          }

          function displayErrorNotice() {
            // Modify this part to display an error notice on the screen as per your UI design
            alert('There was an error loading Turnstile. Please check your keys.');
          }


      } catch (error) {
          console.error('Error in turnstile execution:', error.message);
      }

      jQuery("#recaptcha_for_all_test_keys").click(function (e) {
          e.preventDefault();
          e.stopImmediatePropagation();
          jQuery('#recaptcha_for_all_test_keys').fadeOut(250).fadeIn(250); 
          jQuery('#recaptcha_for_all_test_keys').attr("disabled", true);
          var $sitekey = jQuery("#sitekey").val();
          var $secretkey = jQuery("#secretkey").val();
          // console.log(globalToken);
          jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
              action : 'recaptcha_for_all_test_keys',
              sitekey : $sitekey,
              secretkey: $secretkey,
              mytoken: globalToken
            },
            success: function (data) {
                // console.log(data);
                //console.log(data);
                //console.log('cf-turnstile-response:', document.cookie);
                if(data === 'OK'){
                  alert('Keys Validated!');
                }
                if(data === 'NOT'){
                  alert('Keys NOT Validated!');
                }
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
          });
      }); // clicked button connect
  } // end turnstile
});  // end jQuery