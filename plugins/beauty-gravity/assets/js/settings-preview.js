jQuery(document).ready(function () {
    var color            = jQuery("#bg-color-picker").val()
    var selected_tooltip = jQuery(".bg-tooltip-select").val()

    if (selected_tooltip === "BG_tooltip_9"){
        jQuery(".gf_tooltip_body.BG_tooltip_9").each(function () {
            jQuery(this).find("span").css("border-color",color)
        })
    }

    jQuery("body").on("change",".bg-tooltip-select",function(){
        var tooltipType = jQuery(this).val()
        if (tooltipType === "None") {
            jQuery(".gf_tooltip_body").each(function () {
                jQuery(this).remove()
            })
        }else if (tooltipType !== "BG_tooltip_9") {
            jQuery(".gf_tooltip_body").each(function () {
                jQuery(this).remove()
            })
            jQuery(".tooltip_pos_body").each(function () {
                var pos   = jQuery(this).attr("data-position")
                var theme = jQuery(".ga-site-theme-select").val()
                var text  = jQuery(this).attr("data-text")
                theme = theme == "Dark" ? "Light":"Dark"
                jQuery(this).append('<span class="gf_tooltip_body '+tooltipType+' '+theme+'" data-position="'+pos+'"><span>'+text+'</span></span>')
            })
        }else if (tooltipType === "BG_tooltip_9") {
            jQuery(".gf_tooltip_body").each(function () {
                jQuery(this).remove()
            })
            jQuery(".tooltip_pos_body").each(function () {
                var pos   = jQuery(this).attr("data-position")
                var theme = jQuery(".ga-site-theme-select").val()
                var text  = jQuery(this).attr("data-text")

                theme = theme === "Dark" ? "Light":"Dark"
                jQuery(this).append('<span class="gf_tooltip_body '+tooltipType+' '+theme+'" data-position="'+pos+'"><span style="border-color:'+color+';">'+text+'</span></span>')
            })
        }
    })

    jQuery("body").on("change",".ga-site-theme-select",function(){

        var theme = jQuery(this).val()
        jQuery(".gf_tooltip_body").each(function () {
            jQuery(this).removeClass("Dark")
            jQuery(this).removeClass("Light")
            if (theme == "Dark"){
                jQuery(this).addClass("Light")
            } else {
                jQuery(this).addClass("Dark")
            }
        })
    })
	



    jQuery('body').on("click",function (e) {
        var sor_container = jQuery(".iris-picker");
        if (!sor_container.is(e.target) && sor_container.has(e.target).length === 0) {
            var form_color = jQuery('.bg_form_color').find(".my-color-field").val()
			var font_color = jQuery('.bg_font_color').find(".font-color-field").val()
            if(form_color == "" || form_color=="#" || form_color=="undifined"){
                jQuery("#bg-color-picker").val("#fff")		
            }else{
				jQuery("#bg-color-picker").val(form_color)
			}
			if(font_color == "" || font_color=="#" || font_color=="undifined"){
				jQuery("#bg-font-color-picker").val("#000")
			}else{
				jQuery("#bg-font-color-picker").val(font_color)
			}
        }

    })
	
	jQuery("body").on("change",".ga-tooltip-icon-select",function(){
		var tooltipType = jQuery(this).val()
		if(tooltipType === "Icon"){
			jQuery(".bg-tooltip-icon-type").fadeIn(300)
		}else{
			jQuery(".bg-tooltip-icon-type").fadeOut(300)
		}
	})
	
	
	
	jQuery('.my-color-field').wpColorPicker();
	jQuery('.my-color-field').iris({
		hide: false,
		palettes: ['#ff4f81', '#0389ff', '#1cc7d0', '#ffc20e', '#8e43e7', '#123962','#2baf2b','#706357']
	});


	jQuery('.font-color-field').wpColorPicker();
	jQuery('.font-color-field').iris({
		hide: false,
		palettes: ['#ff4f81', '#0389ff', '#1cc7d0', '#ffc20e', '#8e43e7', '#123962','#2baf2b','#706357']
	});

                            

})