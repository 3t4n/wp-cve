// jQuery(document).ready(function () {
//
// })

function bg_form_animation(button,animation,form_id,current_page) {
    var val = null
    if (button == "next"){
        val = current_page + 1
    } else if (button == "prev"){
        val = current_page - 1
    }

    jQuery("#gform_target_page_number_"+form_id).val(val);
    jQuery("#gform_"+form_id).trigger("submit",[true]);
	
		
	var main_color = jQuery("#gform_wrapper_"+form_id).find("form").attr("data-color")
		var is_svg = jQuery("#gform_"+form_id).find("#gform_page_"+form_id+"_"+current_page).find(".gform_page_footer").find(".bg_footer_container").find('svg[id^=sibg-loader-]').length
		if(is_svg === 0){
			jQuery("#gform_"+form_id).find(".gform_page").find(".gform_page_footer").find(".bg_footer_container").prepend('<svg version="1.1" id="sibg-loader-'+button+'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">'+
			'<path fill="#000" d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z" transform="rotate(330.352 25 25)">'+
			'<animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite"></animateTransform>'+
			'</path></svg>')
			jQuery("#gform_"+form_id).find(".gform_page").find(".gform_page_footer").find(".bg_footer_container").find("svg").find("path").css("fill",main_color)	
		}
        

    setTimeout(function () {
        jQuery("#gform_"+form_id).find(".gform_page").find(".gform_page_footer").find("input").prop("disabled",true)
        jQuery("#gform_"+form_id).find(".gform_page").find(".gform_page_footer").find("input").addClass("bg_disabled")
    },50)

    jQuery('#gform_ajax_frame_'+form_id).unbind("load").on('load', function() {

        // Added from version 1.4.1 for fixed conflict with Auto Advanced plugin
        jQuery('#gform_' + form_id).find('.gform_page').each(function(){
            if(jQuery(this).css('display') !== 'none'){
                current_page = jQuery(this).attr('id').replace("gform_page_"+form_id+"_", "");
                return false
            }
        })
		
        var form_content = jQuery('#gform_ajax_frame_' + form_id).contents().find('#gform_wrapper_' + form_id);
        jQuery(form_content).find(".gform_page").find(".gform_page_footer").each(function () {
            var footerHTML = jQuery(this).html()
            jQuery(this).html('<div class="bg_footer_container">' + footerHTML + '</div>')
        })
        jQuery(form_content).find("#gform_"+form_id).find(".gform_page").find(".gform_page_footer").find("input").addClass("bg_disabled")
        var contents = jQuery(this).contents().find('*').html();
        var is_postback = contents.indexOf('GF_AJAX_POSTBACK') >= 0;
        if (!is_postback) {
            return;
        }
        var form_content = jQuery(this).contents().find('#gform_wrapper_'+form_id);
        var is_confirmation = jQuery(this).contents().find('#gform_confirmation_wrapper_'+form_id).length > 0;
        var is_redirect = contents.indexOf('gformRedirect(){') >= 0;
        var is_form = form_content.length > 0 && !is_redirect && !is_confirmation;
        if (is_form) {
            if (form_content.hasClass('gform_validation_error')) {
                jQuery('#gform_wrapper_' + form_id).addClass('gform_validation_error');
                jQuery('#gform_wrapper_' + form_id).html(form_content.html())
                setTimeout(function () {
					jQuery("#gform_"+form_id).find(".gform_page").find(".gform_page_footer").find("input").removeClass("bg_disabled")
                },8)

                jQuery(document).trigger('gform_page_loaded', [form_id, current_page]);
                jQuery(document).trigger('bg_page_loaded')
                jQuery(document).trigger('gform_post_render', [form_id, current_page])

                if (window['gformInitDatepicker']) {
                    gformInitDatepicker();
                }
                if (window['gformInitPriceFields']) {
                    gformInitPriceFields();
                }

            } else {
                jQuery('#gf_progressbar_wrapper_'+form_id).find(".gf_progressbar").find("span").css("visibility","hidden")
                jQuery("#gform_"+form_id).find(".gform_page").find(".gform_page_footer").find("i").remove()
                jQuery('#gf_progressbar_wrapper_'+form_id).find("h3").css("visibility","hidden")
                var animation_function = animation + "_"+button+"_button(" + form_id + "," + current_page + ")"
                eval(animation_function)
            }

            setTimeout(function () {
                /* delay the scroll by 50 milliseconds to fix a bug in chrome */
            }, 50);

            // jQuery(document).trigger('gform_page_loaded', [1, current_page]);
            window['gf_submitting_' + form_id] = false;
        }
        else if (!is_redirect) {
            var confirmation_content = jQuery(this).contents().find('.GF_AJAX_POSTBACK').html();
            if (!confirmation_content) {
                confirmation_content = contents;
            }
            var currentHeight = jQuery('#gform_wrapper_' + form_id).height()
            jQuery('#gform_wrapper_' + form_id).html(confirmation_content)
            var subHeight = jQuery('#gform_wrapper_' + form_id).height()
            jQuery('#gform_wrapper_' + form_id).css({transition:"height 0.5s ease-in-out",height:currentHeight,opacity:"0"})
            setTimeout(function () {
                jQuery('#gform_wrapper_' + form_id).css({opacity:"1","height":subHeight})
            },10)



            setTimeout(function() {
                jQuery(document).trigger('gform_confirmation_loaded', [1]);
                window['gf_submitting_'+form_id] = false;
            }, 50);
        } else {
			if(is_redirect){
               
				sibg_gf_redirect = jQuery('#gform_ajax_frame_' + form_id).contents().find('body').find('script').html()
				sibg_gf_redirect = sibg_gf_redirect.replace("function gformRedirect(){", "");
				sibg_gf_redirect = sibg_gf_redirect.replace('}', '');
                eval(sibg_gf_redirect)
                
			}
                
            
            
        }
        // jQuery(document).trigger('gform_post_render', [1, current_page]);
    });
}


//add some command in form submit onclick attribute when form animation is enable
//add this function with javascript because of fixed bug in show submit value in rtl mode
function sibg_add_submit_onclick() {
    if(typeof sibg_add_submit_on_click != 'undefined'){
        jQuery( sibg_add_submit_on_click ).each(function (form_id, add_onclick) {
            if(add_onclick){
                var loading     = ''
                var new_onclick = ''
                var onclick     = jQuery(document).find('#gform_'+form_id).find('.gform_button[type="submit"]').attr('onclick')
                var onkey       = jQuery(document).find('#gform_'+form_id).find('.gform_button[type="submit"]').attr('onkeypress')


                loading      = '<svg version="1.1" id="sibg-loader-sub" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve"><path fill="#000" d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z" transform="rotate(330.352 25 25)"><animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite"></animateTransform></path></svg>';
                new_onclick  = onclick
                new_onclick += "var main_color = jQuery('#gform_wrapper_"+form_id+"').find('form').attr('data-color');"
                new_onclick += "jQuery('#gform_"+form_id+"').find('.gform_page').find('.gform_page_footer').find('input[type=button]').prop('disabled',true);"
                new_onclick += "jQuery('#gform_"+form_id+"').find('.gform_page').find('.gform_page_footer').find('.bg_footer_container').prepend('"+loading+"');"
                new_onclick += "jQuery('#gform_"+form_id+"').find('.gform_page').find('.gform_page_footer').find('.bg_footer_container').find('svg').find('path').css('fill',main_color);"
                new_onclick += "jQuery('#gform_"+form_id+"').find('.gform_page').find('.gform_page_footer').find('input').addClass('bg_disabled');"

                var new_onkey = "if( event.keyCode == 13 ){"+new_onclick+"}";
                jQuery(document).find('#gform_'+form_id).find('.gform_button[type="submit"]').attr('onclick', new_onclick)
                jQuery(document).find('#gform_'+form_id).find('.gform_button[type="submit"]').attr('onkeypress', new_onkey)
            }
        })
    }
}


jQuery(document).on('gform_post_render',function(){
    sibg_add_submit_onclick()
})
