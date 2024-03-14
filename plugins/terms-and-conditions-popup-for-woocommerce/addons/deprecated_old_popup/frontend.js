var br_term_and_cond_popup_load;
(function ($){
    var br_term_timer = 0;
    var br_term_timer_timeout;
    br_term_and_cond_popup_load = function() {
        $('.br_term_and_cond_shortcode').parents('form').submit(function(event) {
            if($(this).find('.br_term_and_cond_shortcode').length > 0) {
                if(! $(this).find('.br_term_and_cond_shortcode').find('#terms').prop('checked')) {
                    event.preventDefault();
                }
            }
        });
        var $term = $( "#payment .terms a, .br_term_and_cond_shortcode .terms a, .woocommerce-terms-and-conditions-link" );
        $term.click(function(event) {
            event.preventDefault();
            var old_tb_remove = window.tb_remove;
            window.tb_remove = function() {
                if( br_term_timer <= 0 || isNaN(br_term_timer) ) {
                    if( $('#TB_closeAjaxWindow').length > 0 ) {
                        $( '#terms' ).prop( 'checked', true );
                    }
                    old_tb_remove();
                    window.tb_remove = old_tb_remove;
                }
            };
            setTimeout(function() {
                $('#TB_window').addClass('br_terms_cond_popup_window');
                $('#TB_overlay').addClass('br_terms_cond_popup_window_bg');
                if( br_term_timer > 0 ) {
                    $('.br-woocommerce-terms-conditions-popup-agree').hide();
                    $('#TB_closeWindowButton').hide();
                    if( $('.br_woocommerce_terms_conditions_popup_timer').length == 0 ) {
                        if( $('#TB_closeAjaxWindow').length == 0 ) {
                            $('.br-woocommerce-terms-conditions-popup-agree').first().after('<span class="br_woocommerce_terms_conditions_popup_timer"></span>');
                        } else {
                            $('#TB_closeAjaxWindow').append('<span class="br_woocommerce_terms_conditions_popup_timer"></span>');
                        }
                    }
                    $('.br_woocommerce_terms_conditions_popup_timer').text(br_term_timer);
                }
            }, 50);
            if( br_term_timer > 0 ) {
                br_term_timer_timeout = setInterval(function() {
                    br_term_timer--;
                    if( br_term_timer > 0 ) {
                        $('#TB_closeWindowButton').hide();
                        $('.br-woocommerce-terms-conditions-popup-agree').hide();
                        if( $('.br_woocommerce_terms_conditions_popup_timer').length == 0 ) {
                            if( $('#TB_closeAjaxWindow').length == 0 ) {
                                $('.br-woocommerce-terms-conditions-popup-agree').first().after('<span class="br_woocommerce_terms_conditions_popup_timer"></span>');
                            } else {
                                $('#TB_closeAjaxWindow').append('<span class="br_woocommerce_terms_conditions_popup_timer"></span>');
                            }
                        }
                        $('.br_woocommerce_terms_conditions_popup_timer').text(br_term_timer);
                    } else {
                        clearInterval(br_term_timer_timeout);
                        $('.br_woocommerce_terms_conditions_popup_timer').remove();
                        $('#TB_closeWindowButton').show();
                        $('.br-woocommerce-terms-conditions-popup-agree').show();
                    }
                }, 1000);
            }
        });
        var width = $( window ).width() * 0.9;
        var height = $( window ).height() * 0.9;
        if( the_terms_cond_popup_js_data.styles.height_paddings ) {
            if( the_terms_cond_popup_js_data.styles.height_paddings.indexOf('%') != -1 ) {
                var multiple_height = parseInt(the_terms_cond_popup_js_data.styles.height_paddings.replace('%', ''));
                height = height * (100 - multiple_height) / 100;
            } else {
                var multiple_height = parseInt(the_terms_cond_popup_js_data.styles.height_paddings);
                height = height - multiple_height;
            }
        }
        if( the_terms_cond_popup_js_data.styles.width_paddings ) {
            if( the_terms_cond_popup_js_data.styles.width_paddings.indexOf('%') != -1 ) {
                var multiple_width = parseInt(the_terms_cond_popup_js_data.styles.width_paddings.replace('%', ''));
                width = width * (100 - multiple_width) / 100;
            } else {
                var multiple_width = parseInt(the_terms_cond_popup_js_data.styles.width_paddings);
                width = width - multiple_width;
            }
        }
        if( the_terms_cond_popup_js_data.popup_width && the_terms_cond_popup_js_data.popup_width < width ) {
            width = the_terms_cond_popup_js_data.popup_width;
        }
        if( the_terms_cond_popup_js_data.popup_height && the_terms_cond_popup_js_data.popup_height < height ) {
            height = the_terms_cond_popup_js_data.popup_height;
        }
        width = parseInt(width);
        height = parseInt(height);
        var link = "#TB_inline?width=" + width + "&height=" + height + "&inlineId="+the_terms_cond_popup_js_data.id;
        if( the_terms_cond_popup_js_data.agree_button ) {
            link += '&modal=true';
        }
        $term.attr( 'href', link ).addClass('thickbox').attr('title', the_terms_cond_popup_js_data.title);
        if( the_terms_cond_popup_js_data.checkbox_rm ) {
            $( '#terms' ).hide();
            $('.terms label').attr('for', '');
        }
    };

    $(document).ready( function () {
        br_term_timer = parseInt(the_terms_cond_popup_js_data.timer);
        $( 'body' ).bind( 'updated_checkout', function() {
            br_term_and_cond_popup_load();
        });
        $('.br-woocommerce-terms-conditions-popup-agree').on('click', function(event) {
           window.tb_remove();
           if($(this).data('type') == 'agree') {
               $( '#terms' ).prop( 'checked', true );
           } else {
               $( '#terms' ).prop( 'checked', false );
           }
        });
    });
})(jQuery);
