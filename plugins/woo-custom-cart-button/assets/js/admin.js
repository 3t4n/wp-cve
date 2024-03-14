/*check for button show*/
jQuery('input[class^="button"]').click(function() {
    "use strict";
    var jQuerythis = jQuery(this);
    if (jQuerythis.is(".button1")) {
        if (jQuery(this).prop("checked") === true) {
            jQuery(".button2").prop({ disabled: false, checked: true });
            jQuery(".button3").prop({ disabled: false, checked: true });
        } else if (jQuery(this).prop("checked") === false) {
            jQuery(".button2").prop({ disabled: false, checked: false });
            jQuery(".button3").prop({ disabled: false, checked: false });
        }
    } else if (jQuerythis.is(".button2") || jQuerythis.is(".button3")) {

        if (jQuery('#wcatcbll_add2_cart').prop("checked") === true && jQuery('#wcatcbll_custom').prop("checked") === true) {

            jQuery(".button1").prop({ disabled: false, checked: true });

        } else if (jQuery('#wcatcbll_add2_cart').prop("checked") === false && jQuery('#wcatcbll_custom').prop("checked") === false) {

            jQuery(".button1").prop({ disabled: false, checked: false });

        } else {
            jQuery(".button1").prop({ disabled: false, checked: false });
        }
    }
});

/*Check for global setting*/
jQuery('input[class^="class"]').click(function() {
    "use strict";
    var jQuerythis = jQuery(this);
    if (jQuerythis.is(".class1")) {
        if (jQuery(this).prop("checked") === true) {
            jQuery(".class2").prop({ disabled: false, checked: true });
            jQuery(".class3").prop({ disabled: false, checked: true });
        } else if (jQuery(this).prop("checked") === false) {
            jQuery(".class2").prop({ disabled: false, checked: false });
            jQuery(".class3").prop({ disabled: false, checked: false });
        }
    } else if (jQuerythis.is(".class2") || jQuerythis.is(".class3")) {

        if (jQuery('#wcatcbll_cart_shop').prop("checked") === true && jQuery('#wcatcbll_cart_single_product').prop("checked") === true) {

            jQuery(".class1").prop({ disabled: false, checked: true });

        } else if (jQuery('#wcatcbll_cart_shop').prop("checked") === false && jQuery('#wcatcbll_cart_single_product').prop("checked") === false) {

            jQuery(".class1").prop({ disabled: false, checked: false });

        } else {
            jQuery(".class1").prop({ disabled: false, checked: false });
        }
    }
});

//change instant button style
jQuery(document).ready(function() {
    'use strict';

    /* On page load add button css */
    var btn_fsize = jQuery('#catcbll_btn_fsize').val();
    var btn_brd_size = jQuery('#catcbll_border_size').val();
    var btn_brdr_rds = jQuery('#catcbll_btn_radius').val();
    var catcbll_ptm = jQuery('#catcbll_padding_top_bottom').val();
    var catcbll_plr = jQuery('#catcbll_padding_left_right').val();
    var ready_to_use = jQuery('#ready_to_use').val();
    if (ready_to_use) {
        jQuery("#btn_prvw").removeAttr('class');
        jQuery("#btn_prvw").attr('class', ready_to_use);
    }

    jQuery("#btn_prvw").css({ "font-size": btn_fsize, "border": btn_brd_size + "px solid", "border-radius": btn_brdr_rds, "padding": catcbll_ptm + 'px ' + catcbll_plr + 'px' });

    /* Save option data using ajax */
    jQuery('#submit_settings').click(function(e) {
        jQuery("#wcbnl_overlay").fadeIn(300);
        //jQuery("#wcbnl_overlay").fadeIn(300);　
        e.preventDefault();
        var security_nonce = catcbll_vars.ajax_public_nonce;
        var form = jQuery("#wcatbltn_option_save").serialize(); // this will resolve to the form submitted		
        jQuery.ajax({
            type: "POST",
            global: false,
            dataType: "json",
            url: catcbll_vars.ajaxurl,
            data: { form_data: form, security_nonce: security_nonce, action: 'catcbll_save_option' }, //only input
            success: function(response) {
                if (response) {
                    location.reload();
                }
            }
        }).done(function() {
            setTimeout(function() {
                jQuery("#wcbnl_overlay").fadeOut(300);
            }, 500);
        });
    });

    //use for select button 2dTransitions
    jQuery("#wcatcbll_btn_2Dhvr").change(function() {
        var btn_2danmtn = jQuery(this).children('option:selected').val();
        var hdn_cls_pre = jQuery('#hide_2d_trans').val();
        jQuery("#btn_prvw").removeClass(hdn_cls_pre).addClass(btn_2danmtn);
        jQuery('#hide_2d_trans').val(btn_2danmtn);

    });
    //use for select button bg Transitions
    jQuery("#wcatcbll_btn_bghvr").change(function() {
        var brdr_radius_all = jQuery('#brdr_rds').html();
        var btn_hvrclr = jQuery('#catcbll_btn_hvrclr').val();
        var btn_bghvr = jQuery(this).children('option:selected').val();
        var hdn_cls_pre = jQuery('#hide_btn_bghvr').val();
        var btn_brdr_size = jQuery('#ccbtn_border_size').html();
        var btn_brdrclr = jQuery('#catcbll_btn_border_clr').val();
        if (btn_brdrclr) { var font_clr = '#fff'; } else { var font_clr = '#000'; }
        if (hdn_cls_pre) {
            jQuery("#btn_prvw").find("style").remove();
            jQuery('.btn_preview_div style').remove();
            jQuery('<style>.' + btn_bghvr + ':before{border-radius:' + brdr_radius_all + '!important;background:' + btn_hvrclr + '!important} .wccbtn{border:' + btn_brdr_size + ' solid #000;background:#fff;}</style>').insertBefore('#btn_prvw');
        } else {
            jQuery("#btn_prvw").find("style").remove();
            jQuery("#btn_prvw").append(jQuery("<style>.wccbtn:hover{background:" + btn_hvrclr + "!important;color:" + font_clr + " !important;border:" + btn_brdr_size + " solid " + btn_brdrclr + ";}</style>"));
        }
        jQuery("#btn_prvw").removeClass(hdn_cls_pre).addClass(btn_bghvr);
        jQuery('#hide_btn_bghvr').val(btn_bghvr);


    });
    //use for select button radius
    jQuery("#wcatcll_font_icon").change(function() {
        var btn_icon = jQuery(this).children('option:selected').val();
        var btn_iconpsn = jQuery('#wcatcbll_btn_icon_psn').children('option:selected').val();
        if (btn_iconpsn === 'right') {
            jQuery("#btn_prvw").html('Add to Cart <i class="fa ' + btn_icon + '"></i>');
        } else {
            jQuery("#btn_prvw").html('<i class="fa ' + btn_icon + '"></i> Add to Cart');
        }

    });
    //use for select button radius
    jQuery("#wcatcbll_btn_icon_psn").change(function() {
        var btn_iconpsn = jQuery(this).children('option:selected').val();
        var btn_icon = jQuery('#wcatcll_font_icon').children('option:selected').val();
        if (btn_iconpsn === 'right') {
            jQuery("#btn_prvw").html('Add to Cart <i class="fa ' + btn_icon + '"></i>');
        } else {
            jQuery("#btn_prvw").html('<i class="fa ' + btn_icon + '"></i> Add to Cart');
        }

    });
    //use for button padding
    jQuery(".btnpd_st input[type=number]").change(function() {
        var btn_p = jQuery(this).attr('class');

        var btn_pval = jQuery(this).val();
        jQuery(this).attr('value', btn_pval);
        if (btn_p == 'btn_pv') {
            jQuery("#btn_prvw").css({ "padding-top": btn_pval + 'px', "padding-bottom": btn_pval + 'px' });
        } else if (btn_p == 'btn_ph') {
            jQuery("#btn_prvw").css({ "padding-left": btn_pval + 'px', "padding-right": btn_pval + 'px' });
        }
    });

});
jQuery(document).ready(function(jQuery) {
    var btn_brdr_size = jQuery('#ccbtn_border_size').html();
    var brdr_radius_all = jQuery('#brdr_rds').html();
    var bg_clr = jQuery('#catcbll_btn_bg').val();
    jQuery('#btn_prvw').css("background", bg_clr);
    var btn_fclr = jQuery('#catcbll_btn_fclr').val();
    jQuery('#btn_prvw').css("color", btn_fclr);
    var btn_hvrclr = jQuery('#catcbll_btn_hvrclr').val();
    var btn_brdrclr = jQuery('#catcbll_btn_border_clr').val();

    var bg_transition = jQuery('#hide_btn_bghvr').val();
    if (btn_brdrclr) { var font_clr = '#fff'; } else { var font_clr = '#000'; }
    if (bg_transition) {
        jQuery("#btn_prvw").find("style").remove();
        jQuery('.btn_preview_div style').remove();
        jQuery('<style>.' + bg_transition + ':before{border-radius:' + brdr_radius_all + '!important;background:' + btn_hvrclr + '!important} .wccbtn{border:' + btn_brdr_size + ' solid #000;background:#fff;}</style>').insertBefore('#btn_prvw');
    } else {
        jQuery("#btn_prvw").find("style").remove();
        jQuery("#btn_prvw").append(jQuery("<style>.wccbtn:hover{background:" + btn_hvrclr + "!important;color:" + font_clr + " !important;border:" + btn_brdr_size + " solid " + btn_brdrclr + ";}</style>"));
    }

    jQuery('.color-picker').wpColorPicker({
        change: function(event, ui) {
            /* Button styling */
            var ranger_color = ui.color.to_s();
            /*Check selected color picker id*/
            if (event.target.id == 'catcbll_btn_bg') {
                jQuery('#btn_prvw').css("background", ranger_color);
            } else if (event.target.id == 'catcbll_btn_fclr') {
                jQuery('#btn_prvw').css("color", ranger_color);
            } else if (event.target.id == 'catcbll_btn_border_clr') {
                jQuery('#btn_prvw').css("border-color", ranger_color);
            } else if ((event.target.id == 'catcbll_btn_hvrclr')) {
                var bg_transition = jQuery('#hide_btn_bghvr').val();
                if (ranger_color) { var font_clr = '#fff'; } else { var font_clr = '#000'; }
                if (bg_transition) {
                    jQuery("#btn_prvw").find("style").remove();
                    jQuery('.btn_preview_div style').remove();
                    jQuery('<style>.' + bg_transition + ':before{border-radius:' + brdr_radius_all + '!important;background:' + ranger_color + '!important} .wccbtn{border:' + btn_brdr_size + ' solid #000;background:#fff;}</style>').insertBefore('#btn_prvw');
                } else {
                    jQuery("#btn_prvw").find("style").remove();
                    jQuery("#btn_prvw").append(jQuery("<style>.wccbtn:hover{background:" + ranger_color + "!important;color:" + font_clr + " !important;border:" + btn_brdr_size + " solid " + ranger_color + ";}</style>"));
                }

            }
        },
        clear: function(event) {
            //var element = jQuery(event.target).siblings('.wp-color-picker')[0];
            var element = jQuery(event.target);
            var id = element[0].previousSibling.lastChild.id;
            var btn_brdr_size = jQuery('#ccbtn_border_size').html();
            var brdr_radius_all = jQuery('#brdr_rds').html();
            jQuery('.wp-color-result').removeAttr('style');
            /*Check selected color picker id*/
            if (id == 'catcbll_btn_bg') {
                jQuery('#btn_prvw').css("background", '');
            } else if (id == 'catcbll_btn_fclr') {
                jQuery('#btn_prvw').css("color", "");
            } else if (id == 'catcbll_btn_border_clr') {
                jQuery('#btn_prvw').css("border-color", "");
            } else if ((id == 'catcbll_btn_hvrclr')) {
                var bg_transition = jQuery('#hide_btn_bghvr').val();
                if (bg_transition) {
                    jQuery("#btn_prvw").find("style").remove();
                    jQuery('.btn_prvw style').remove();
                    jQuery('<style>.' + bg_transition + ':before{border-radius:' + brdr_radius_all + '!important;background:' + "" + '!important} .wccbtn{border:' + btn_brdr_size + ' solid #000;background:#fff;}</style>').insertBefore('#btn_prvw');
                } else {
                    jQuery("#btn_prvw").find("style").remove();
                    jQuery("#btn_prvw").append(jQuery("<style>.wccbtn:hover{background:" + "" + ";color:#fff !important}</style>"));
                }

            }
        }
    });


    /*Ready To Use Button */
    jQuery('.btn_card').each(function() {
        // jQuery(this).removeClass('active');
        jQuery(this).click(function() {
            jQuery(".btn_card").removeClass('active');
            var ready_btn_c = jQuery(this).find('a').attr('class');
            jQuery(this).addClass('active');
            jQuery('#ready_to_use').val(ready_btn_c);
            jQuery('#btn_prvw').removeAttr('class');
            jQuery('#btn_prvw').removeAttr('style');
            jQuery('#btn_prvw').attr('class', ready_btn_c);
            jQuery('#catcbll_btn_radius').val('1');
            jQuery('#brdr_rds').html('1px');
            jQuery('.color-picker').wpColorPicker({
                clear: function(event) {},
            });
            jQuery('.wp-picker-clear').trigger('click');
        });
    });

    /*Ready to use modal */
    jQuery('.rdtuse').click(function() {
        var ready_btn_c = jQuery('#ready_to_use').val();
        jQuery('.btn_card').each(function() {
            var btn_card = jQuery(this).find('a').attr('class');
            if (btn_card == ready_btn_c) {
                jQuery(this).addClass('active');
            }
        });
    });
    /* Clear selection */
    jQuery('.clear-selection').click(function() {
        jQuery('#ready_to_use').val('');
        jQuery('.btn_card').removeClass('active');
    });

    jQuery('#banners').owlCarousel({
        loop:true,
        margin:10,
        nav:false,
        dots: false,
       
        autoplay:true,
        autoPlaySpeed: 5000,
        autoplayTimeout:5000,
        autoplayHoverPause:true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });
    jQuery('#kinsta_banners').owlCarousel({
        loop:true,
        margin:10,
        nav:false,
        dots: false,
        autoplay:true,
        autoPlaySpeed: 5000,
        autoplayTimeout:5000,
        autoplayHoverPause:true,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 2
            },
            1400: {
                items: 3
            }
        }
    });
    
});

