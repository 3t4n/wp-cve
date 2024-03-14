function Zoom_Slide_rtl_next_button(form_id,current){

    var next = '';
    jQuery('#gform_ajax_frame_' + form_id).contents().find('.gform_page').each(function(){
        if(jQuery(this).css('display') !== 'none'){
            next = jQuery(this).attr('id').replace("gform_page_"+form_id+"_", "");
            return false
        }
    })

    var currentHeight = jQuery("#gform_page_" + form_id + "_" + current).height()
    jQuery("#gform_page_" + form_id + "_" + next).css({opacity:"0",display:"block",width:"100%"})
    var nextHeight = jQuery("#gform_page_" + form_id + "_" + next).height()
    jQuery("#gform_page_" + form_id + "_" + next).removeAttr("style")
    jQuery("#gform_page_" + form_id + "_" + next).css("display","none")

    jQuery("#gform_page_" + form_id + "_" + current).css("height",currentHeight)
    jQuery("#gform_page_" + form_id + "_" + current).find(".gform_page_footer").addClass("stick_bottom")

    var pageCNT = jQuery("#gform_"+form_id).find(".gform_body").children().length
    var percent = Math.floor((next/pageCNT)*100)
    if (percent<98){
        percent += "%"
    }else {
        percent = "100%"
    }

    jQuery("#gform_" + form_id).find(".gf_progressbar_percentage").css({ "width": percent ,transition:"all 0.7s linear"})
    jQuery(document).trigger('sibg_animation_time', [form_id, current, next, '0.7'])
	if (currentHeight >= nextHeight) {
        setTimeout(function () {
            jQuery("#gform_page_" + form_id + "_" + current).css({ height: nextHeight,transition:"height 0.3s ease-in-out" })
        },  400)
    } else {
        setTimeout(function () {
            jQuery("#gform_page_" + form_id + "_" + current).css({ height: nextHeight,transition:"height 0.3s ease-in-out"})
        },1)
    }

    jQuery("#gform_page_"+form_id+"_"+current).find(".gform_page_fields").css({position: "relative",width:"100%"})
    jQuery("#gform_page_"+form_id+"_"+next).css({display:"block",position: "absolute",width:"100%",right:"0",top:"0"})
    jQuery("#gform_page_"+form_id+"_"+next).find(".gform_page_fields").css("opacity","0")
    jQuery("#gform_page_"+form_id+"_"+next).find(".gform_page_footer").css("opacity","0")
    jQuery(document).trigger('nextPageCreated')
    setTimeout(function () {
        jQuery("#gform_page_"+form_id+"_"+current).find(".gform_page_fields").addClass("s2body-rtl-next-go")
    },1)
    setTimeout(function () {
        jQuery("#gform_page_"+form_id+"_"+next).find(".gform_page_fields").addClass("s2body-rtl-next-come")
    },300)
    setTimeout(function () {
        var form_content = jQuery('#gform_ajax_frame_'+form_id).contents().find('#gform_wrapper_'+form_id);
        jQuery('#gform_wrapper_' + form_id).html(form_content.html())
		// call this function for avoid bounce form
        renderRecaptcha()
        setTimeout(function () {
            jQuery('#gform_page_' + form_id + '_' + next).find(".gform_page_footer").find("input").removeClass("bg_disabled")
        },8)

        jQuery(document).trigger('gform_page_loaded', [form_id, next]);
        jQuery(document).trigger('bg_page_loaded')
        jQuery(document).trigger('gform_post_render', [form_id, next])

        if (window['gformInitDatepicker']) {
            gformInitDatepicker();
        }
        if (window['gformInitPriceFields']) {
            gformInitPriceFields();
        }
    },730)
}
function Zoom_Slide_rtl_prev_button(form_id,current){

    var prev = '';
    jQuery('#gform_ajax_frame_' + form_id).contents().find('.gform_page').each(function(){
        if(jQuery(this).css('display') !== 'none'){
            prev = jQuery(this).attr('id').replace("gform_page_"+form_id+"_", "");
            return false
        }
    })

    var pageCNT = jQuery("#gform_"+form_id).find(".gform_body").children().length
    var percent = Math.floor((prev/pageCNT)*100)+"%"


    var currentHeight = jQuery("#gform_page_" + form_id + "_" + current).height()
    jQuery("#gform_page_" + form_id + "_" + prev).css({opacity:"0",display:"block",width:"100%"})
    var prevHeight = jQuery("#gform_page_" + form_id + "_" + prev).height()
    jQuery("#gform_page_" + form_id + "_" + prev).removeAttr("style")
    jQuery("#gform_page_" + form_id + "_" + prev).css("display","none")

    jQuery("#gform_page_" + form_id + "_" + current).css("height",currentHeight)
    jQuery("#gform_page_" + form_id + "_" + current).find(".gform_page_footer").addClass("stick_bottom")


    jQuery("#gform_" + form_id).find(".gf_progressbar_percentage").css({ "width": percent ,transition:"all 0.7s linear"})
    jQuery(document).trigger('sibg_animation_time', [form_id, current, prev, '0.7'])
	if (currentHeight >= prevHeight) {
        setTimeout(function () {
            jQuery("#gform_page_" + form_id + "_" + current).css({ height: prevHeight,transition:"height 0.3s ease-in-out" })
        },  400)
    } else {
        setTimeout(function () {
            jQuery("#gform_page_" + form_id + "_" + current).css({ height: prevHeight,transition:"height 0.3s ease-in-out"})
        },1)
    }

    jQuery("#gform_page_"+form_id+"_"+current).find(".gform_page_fields").css({position: "relative",width:"100%"})
    jQuery("#gform_page_"+form_id+"_"+prev).css({display:"block",position: "absolute",width:"100%",right:"0",top:"0"})
    jQuery("#gform_page_"+form_id+"_"+prev).find(".gform_page_fields").css("opacity","0")
    jQuery("#gform_page_"+form_id+"_"+prev).find(".gform_page_footer").css("opacity","0")
    jQuery(document).trigger('nextPageCreated')
    setTimeout(function () {
        jQuery("#gform_page_"+form_id+"_"+current).find(".gform_page_fields").addClass("s2body-rtl-prev-go")
    },1)
    setTimeout(function () {
        jQuery("#gform_page_"+form_id+"_"+prev).find(".gform_page_fields").addClass("s2body-rtl-prev-come")
    },300)
    setTimeout(function () {
        var form_content = jQuery('#gform_ajax_frame_'+form_id).contents().find('#gform_wrapper_'+form_id);
        jQuery('#gform_wrapper_' + form_id).html(form_content.html())
		// call this function for avoid bounce form
        renderRecaptcha()
        setTimeout(function () {
            jQuery('#gform_page_' + form_id + '_' + prev).find(".gform_page_footer").find("input").removeClass("bg_disabled")
        },8)

        jQuery(document).trigger('gform_page_loaded', [form_id, prev]);
        jQuery(document).trigger('bg_page_loaded')
        jQuery(document).trigger('gform_post_render', [form_id, prev])

        if (window['gformInitDatepicker']) {
            gformInitDatepicker();
        }
        if (window['gformInitPriceFields']) {
            gformInitPriceFields();
        }
    },730)

}



function sibg_rtl_next_Zoom_Slide(form_id, current, next){
	
	var current_html = jQuery("#sibg_page_" + form_id + "_" + current).clone()
	var next_html    = jQuery("#sibg_page_" + form_id + "_" + next).clone()
	
	jQuery("#gform_"+form_id).find(".sibg-btn").prop("disabled",true)
	jQuery("#gform_"+form_id).find(".sibg-btn").addClass("bg_disabled")
	
	var currentHeight = jQuery("#sibg_page_" + form_id + "_" + current).height()
    jQuery("#sibg_page_" + form_id + "_" + next).css({opacity:"0",display:"block",width:"100%"})
    var nextHeight = jQuery("#sibg_page_" + form_id + "_" + next).height()
    jQuery("#sibg_page_" + form_id + "_" + next).removeAttr("style")
    jQuery("#sibg_page_" + form_id + "_" + next).css("display","none")

    jQuery("#sibg_page_" + form_id + "_" + current).css("height",currentHeight)
    jQuery("#sibg_page_" + form_id + "_" + current).find(".gform_page_footer").addClass("stick_bottom")

    var pageCNT = jQuery("#gform_"+form_id).find(".gform_body").children().length
    var percent = Math.floor((next/pageCNT)*100)
    if (percent<98){
        percent += "%"
    }else {
        percent = "100%"
    }

    jQuery("#gform_" + form_id).find(".gf_progressbar_percentage").css({ "width": percent ,transition:"all 0.7s linear"})
	jQuery(document).trigger('sibg_animation_time', [form_id, current, next, '0.7'])
	if (currentHeight >= nextHeight) {
        setTimeout(function () {
            jQuery("#sibg_page_" + form_id + "_" + current).css({ height: nextHeight,transition:"height 0.3s ease-in-out" })
        },  400)
    } else {
        setTimeout(function () {
            jQuery("#sibg_page_" + form_id + "_" + current).css({ height: nextHeight,transition:"height 0.3s ease-in-out"})
        },1)
    }

	jQuery("#sibg_page_"+form_id+"_"+current).find(".gform_page_fields").css({position: "relative",width:"100%"})
    jQuery("#sibg_page_"+form_id+"_"+next).css({display:"block",position: "absolute",width:"100%",right:"0",top:"0"})
    jQuery("#sibg_page_"+form_id+"_"+next).find(".gform_page_fields").css("opacity","0")
    jQuery("#sibg_page_"+form_id+"_"+next).find(".gform_page_footer").css("opacity","0")
	jQuery(document).trigger('nextPageCreated')
	
    setTimeout(function () {
        jQuery("#sibg_page_"+form_id+"_"+current).find(".gform_page_fields").addClass("s2body-rtl-next-go")
    },1)
    setTimeout(function () {
        jQuery("#sibg_page_"+form_id+"_"+next).find(".gform_page_fields").addClass("s2body-rtl-next-come")
    },300)
    setTimeout(function () {
		jQuery("#sibg_page_"+form_id+"_"+current).replaceWith(current_html)
		jQuery("#sibg_page_"+form_id+"_"+current).css("display","none")
		jQuery("#sibg_page_"+form_id+"_"+next).replaceWith(next_html)
		jQuery("#sibg_page_"+form_id+"_"+next).css("display","block")
		jQuery("#gform_"+form_id).find(".sibg-btn").prop("disabled",false)
		jQuery("#gform_"+form_id).find(".sibg-btn").removeClass("bg_disabled")
        jQuery(document).trigger('gform_page_loaded', [form_id, next]);
        jQuery(document).trigger('bg_page_loaded')
        jQuery(document).trigger('gform_post_render', [form_id, next])
        if (window['gformInitDatepicker']) {
            gformInitDatepicker();
        }
        if (window['gformInitPriceFields']) {
            gformInitPriceFields();
        }
		

    },730)
	
}


function sibg_rtl_prev_Zoom_Slide (form_id, current, prev){

	var current_html = jQuery("#sibg_page_" + form_id + "_" + current).clone()
	var prev_html    = jQuery("#sibg_page_" + form_id + "_" + prev).clone()
	
	jQuery("#gform_"+form_id).find(".sibg-btn").prop("disabled",true)
	jQuery("#gform_"+form_id).find(".sibg-btn").addClass("bg_disabled")
    var currentHeight = jQuery("#sibg_page_" + form_id + "_" + current).height()
    jQuery("#sibg_page_" + form_id + "_" + prev).css({opacity:"0",display:"block",width:"100%"})
    var prevHeight = jQuery("#sibg_page_" + form_id + "_" + prev).height()
    jQuery("#sibg_page_" + form_id + "_" + prev).removeAttr("style")
    jQuery("#sibg_page_" + form_id + "_" + prev).css("display","none")

    jQuery("#sibg_page_" + form_id + "_" + current).css("height",currentHeight)
    jQuery("#sibg_page_" + form_id + "_" + current).find(".gform_page_footer").addClass("stick_bottom")


	var pageCNT = jQuery("#gform_"+form_id).find(".gform_body").children().length
    var percent = Math.floor((prev/pageCNT)*100)+"%"
    jQuery("#gform_" + form_id).find(".gf_progressbar_percentage").css({ "width": percent ,transition:"all 0.7s linear"})
	jQuery(document).trigger('sibg_animation_time', [form_id, current, prev, '0.7'])
    if (currentHeight >= prevHeight) {
        setTimeout(function () {
            jQuery("#sibg_page_" + form_id + "_" + current).css({ height: prevHeight,transition:"height 0.3s ease-in-out" })
        },  400)
    } else {
        setTimeout(function () {
            jQuery("#sibg_page_" + form_id + "_" + current).css({ height: prevHeight,transition:"height 0.3s ease-in-out"})
        },1)
    }

    jQuery("#sibg_page_"+form_id+"_"+current).find(".gform_page_fields").css({position: "relative",width:"100%"})
    jQuery("#sibg_page_"+form_id+"_"+prev).css({display:"block",position: "absolute",width:"100%",right:"0",top:"0"})
    jQuery("#sibg_page_"+form_id+"_"+prev).find(".gform_page_fields").css("opacity","0")
    jQuery("#sibg_page_"+form_id+"_"+prev).find(".gform_page_footer").css("opacity","0")
    jQuery(document).trigger('nextPageCreated')
	
    setTimeout(function () {
        jQuery("#sibg_page_"+form_id+"_"+current).find(".gform_page_fields").addClass("s2body-rtl-prev-go")
    },1)
    setTimeout(function () {
        jQuery("#sibg_page_"+form_id+"_"+prev).find(".gform_page_fields").addClass("s2body-rtl-prev-come")
    },300)
    setTimeout(function () {
		jQuery("#sibg_page_"+form_id+"_"+current).replaceWith(current_html)
		jQuery("#sibg_page_"+form_id+"_"+current).css("display","none")
		jQuery("#sibg_page_"+form_id+"_"+prev).replaceWith(prev_html)
		jQuery("#sibg_page_"+form_id+"_"+prev).css("display","block")
		jQuery("#gform_"+form_id).find(".sibg-btn").prop("disabled",false)
		jQuery("#gform_"+form_id).find(".sibg-btn").removeClass("bg_disabled")
        jQuery(document).trigger('gform_page_loaded', [form_id, prev]);
        jQuery(document).trigger('bg_page_loaded')
        jQuery(document).trigger('gform_post_render', [form_id, prev])
        if (window['gformInitDatepicker']) {
            gformInitDatepicker();
        }
        if (window['gformInitPriceFields']) {
            gformInitPriceFields();
        }
		
    },730)
}