jQuery(document).ready(function(jQuery){
   console.log('RFA loaded!');
    // Initialize the media uploader when the button is clicked
    jQuery('#recaptcha-for-all-media-uploader-button').click(function(e) {
       console.log('RFA click M U B');   
        var clsname = e.target.className;
        var idname = e.target.id;
      e.preventDefault();
      e.stopImmediatePropagation();
      console.log('clicou...');
      var mediaUploader = wp.media({
        title: 'Select Image',
        multiple: false
      });
      // Handle image selection
      mediaUploader.on('select', function() {
        var attachment = mediaUploader.state().get('selection').first().toJSON();
        var imageUrl = attachment.url;
        console.log(imageUrl);
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
              action : 'recaptcha_for_all_image_select',
              recaptcha_image_url : imageUrl,
              recaptcha_my_plugin_nonce: recaptcha_my_data.recaptcha_my_nonce
            },
            success: function (data) {
                //console.log(data);
                jQuery('#recaptcha_for_all_custom_image_background').attr("src",imageUrl);
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
      });
      // Open the media uploader
      mediaUploader.open();
    });
    // console.log(window.location.hostname);
    jQuery("#recaptcha_for_all").click(function (e) {
        jQuery('#recaptcha_for_all').fadeOut(250).fadeIn(250); 
        jQuery('#recaptcha_for_all').attr("disabled", true);
        e.preventDefault();
        e.stopImmediatePropagation();
        // console.log("Handler for .click() called.");
        /*
        grecaptcha.ready(function () {
           // console.log('17');
            var $sitekey = jQuery("#sitekey").val();
            grecaptcha.execute($sitekey, { action: 'recaptcha_for_all_main' }).then(function (token) {
               // console.log('23');
                // console.log('Token: ' + token);
                jQuery('#recaptcha_for_all').prepend('<input type="hidden" name="token" value="' + token + '">');
                jQuery('#recaptcha_for_all').prepend('<input type="hidden" name="action" value="recaptcha_for_all">');
                jQuery("#recaptcha_for_all").submit();
            });
        });
        */
        grecaptcha.ready(function () {
          // console.log('17');
          try {
              var $sitekey = jQuery("#sitekey").val();
              grecaptcha.execute($sitekey, { action: 'recaptcha_for_all_main' }).then(function (token) {
                  // console.log('23');
                  // console.log('Token: ' + token);
                  jQuery('#recaptcha_for_all').prepend('<input type="hidden" name="token" value="' + token + '">');
                  jQuery('#recaptcha_for_all').prepend('<input type="hidden" name="action" value="recaptcha_for_all">');
                  jQuery("#recaptcha_for_all").submit();
              });
          } catch (error) {
              console.error('Error in reCAPTCHA execution:', error.message);
          }
      });
    });
});