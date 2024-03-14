<?php

echo "<div class='cg_form_div cg_recaptcha_not_a_robot_field' >";
echo "<div class='cg_recaptcha_in_gallery_form cg_recaptcha_form' id='cgRecaptchaForm$galeryIDuser'>";

echo "</div>";
echo "<p class='cg_input_error cg_hide cg_recaptcha_not_valid_in_gallery_form_error' id='cgRecaptchaNotValidInGalleryFormError$galeryIDuser'></p>";
echo "</div>";
?>
<pre>
    <script type="text/javascript">


        var galeryID = "<?php echo $galeryIDuser; ?>";

        var cgRecaptchaCallbackUploadFormRendered = false;

        if(typeof cgRecaptchaCallbackRendered == 'undefined'){

            cgRecaptchaCallbackRendered = true;
            cgRecaptchaCallbackUploadFormRendered = true;

            cgOnloadCallbackInGallery = function() {

                var ReCaKey = "<?php echo (!empty($fieldGoogleRecaptcha['ReCaKey'])) ? $fieldGoogleRecaptcha['ReCaKey'] : '1' ; ?>";
                var cgRecaptchaNotValidInGalleryFormError = "<?php echo 'cgRecaptchaNotValidInGalleryFormError'.$galeryIDuser.''; ?>";
                var cgRecaptchaInGalleryForm = "<?php echo 'cgRecaptchaForm'.$galeryIDuser.''; ?>";

                // callback when clicking recaptcha
                cgCaRoReCallbackInGallery = function() {

                    /*                    var element = document.getElementById(cgRecaptchaNotValidInGalleryFormError);
                                        //element.parentNode.removeChild(element);
                                        element.classList.remove("cg_recaptcha_not_valid_in_gallery_form_error");
                                        element.classList.add("cg_hide");*/
                    // jQuery must be already available when google api makes callback
                    // multiple recaptcha buttons can not appear anymore because of global $cgGlobalGoogleRecaptchaRendered check integrated in 21.0.0
                    var $element = jQuery('#'+cgRecaptchaNotValidInGalleryFormError);
                    $element.addClass("cg_recaptcha_not_valid_in_gallery_form_error_success cg_hide");
                    $element.closest(".cg_form_div").removeClass('cg_form_div_error');
                };

                if(typeof cgRecaptchaFormNormalRendered == 'undefined'){
                    cgRecaptchaFormNormalRendered = true;
                    grecaptcha.render(cgRecaptchaInGalleryForm, {
                        'sitekey' : ReCaKey,
                        'callback' : 'cgCaRoReCallbackInGallery'
                    });
                }

            };

        }

</script>
</pre>
<pre>
    <script src="https://www.google.com/recaptcha/api.js?onload=cgOnloadCallbackInGallery&render=explicit&hl=<?php echo (!empty($fieldGoogleRecaptcha['ReCaLang'])) ? $fieldGoogleRecaptcha['ReCaLang'] : 'en'; ?>"
            async defer>
    </script>
</pre>
<?php

?>