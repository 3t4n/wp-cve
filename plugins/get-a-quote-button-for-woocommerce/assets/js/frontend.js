+(function($) {

    /**
     * Fire The Popup
     */

    $(document).on("click", ".wpb-get-a-quote-button-form-fire", function(e) {

        e.preventDefault();

        var button              = $(this),
        id                      = button.attr('data-id'),
        post_id                 = button.attr('data-post_id'),
        form_style              = button.attr('data-form_style') ? !0 : !1,
        allow_outside_click     = button.attr('data-allow_outside_click') ? !0 : !1,
        width                   = button.attr('data-width');

        wp.ajax.send( {
            ajax_option : 'fire_wpb_gqb_contact_form',
            data: {
                action: 'fire_contact_form',
                contact_form_id: id,
                wpb_post_id: post_id,
                _wpnonce: WPB_GQB_Vars.nonce
            },
            beforeSend : function ( xhr ) {
                button.addClass('wpb-gqf-btn-loading');
            },
            success: function( res ) {
                button.removeClass('wpb-gqf-btn-loading');
                Swal.fire({
                    html: res,
                    showConfirmButton: false,
                    customClass: {
                        container: 'wpb-gqf-form-style-' + form_style,
                    },
                    padding: '30px',
                    width: width,
                    showCloseButton: true,
                    backdrop: true,
                    allowOutsideClick: allow_outside_click,
                });

                
                // For CF7 5.3.1 and before
                if( typeof wpcf7.initForm === "function" ){
                    wpcf7.initForm( $('.wpcf7-form') );
                }

                // For CF7 5.4 and after
                if( typeof wpcf7.init === "function" ){
                    document.querySelectorAll(".wpcf7 > form").forEach(function (e) {
                        return wpcf7.init(e);
                    });
                }

                // Add support for - Simple Cloudflare Turnstile – CAPTCHA Alternative
                if( typeof turnstile  !== "undefined" ){

                    var cf_turnstile_id = $($.parseHTML(res)).find('.cf-turnstile').attr('id');
                    if (document.getElementById(cf_turnstile_id)) {
                        setTimeout(function() {
                            turnstile.render('#' + cf_turnstile_id);
                        }, 10);
                    }
                }

                // Add support for - Drag and Drop Multiple File Upload – Contact Form 7
                if( typeof initDragDrop === "function" ){
                    window.initDragDrop();
                }

                // ReCaptcha v2 for Contact Form 7 - By IQComputing
                if( typeof recaptchaCallback === "function" ){
                    recaptchaCallback();
                }

                // Add support for - Conditional Fields for Contact Form 7
                if ( typeof wpcf7cf !== 'undefined' ) {
                    wpcf7cf.initForm( $('.wpcf7-form') );
                }

                // Add post ID to the popup form
                $("[name='_wpcf7_container_post']").val( post_id );

                // Adding any custom JS code on form init
                if( typeof wpb_gqf_on_cf7_form_init === "function" ){
                    wpb_gqf_on_cf7_form_init();
                }

            },
            error: function(error) {
                alert( error );
            }
        });

    });


    /**
     * Hide if variation has no stock 
     */
    
    $(document).on( 'found_variation', 'form.variations_form', function( event, variation ) { 
        if( !variation.is_in_stock ){
            $('.wpb-gqb-product-type-variable').addClass('wpb-gqb-product-type-variable-show');
        }else{
            $('.wpb-gqb-product-type-variable').removeClass('wpb-gqb-product-type-variable-show');
        }
    });

    $(document).on( 'click', '.reset_variations', function( event ) {
        $('.wpb-gqb-product-type-variable').removeClass('wpb-gqb-product-type-variable-show');
    });

})(jQuery);