jQuery(document).ready(function(){
    
    /*jQuery('#showBorder').on('change', function() {
        var radioValue = jQuery("input[name='accordion-slider-settings[showBorder]']:checked").val();
        if(radioValue==1){
            jQuery('.borderWidth').show('slow');
            jQuery('.borderColor').show('slow');
        }
        else{
            jQuery('.borderWidth').hide('slow');
            jQuery('.borderColor').hide('slow');
        }
    });

    jQuery('#mainHideTitle').on('change', function() {
        var radioValue = jQuery("input[name='accordion-slider-settings[mainHideTitle]']:checked").val();
        if(radioValue==1){
            jQuery('.mainTitleColor').hide('slow');
            jQuery('.mainTitleBgColor').hide('slow');
            jQuery('.mainTitleFontSize').hide('slow');
            jQuery('.mainTitleBgTransparency').hide('slow');
        }
        else{
            jQuery('.mainTitleColor').show('slow');
            jQuery('.mainTitleBgColor').show('slow');
            jQuery('.mainTitleFontSize').show('slow');
            jQuery('.mainTitleBgTransparency').show('slow');
        }
    });

    jQuery('#hide_title').on('change', function() {
        var radioValue = jQuery("input[name='accordion-slider-settings[hide_title]']:checked").val();
        if(radioValue==1){
            jQuery('.titleColor').hide('slow');
            jQuery('.titleBgColor').hide('slow');
            jQuery('.titleFontSize').hide('slow');
        }
        else{
            jQuery('.titleColor').show('slow');
            jQuery('.titleBgColor').show('slow');
            jQuery('.titleFontSize').show('slow');
        }
    });

    jQuery('#hide_description').on('change', function() {
        var radioValue = jQuery("input[name='accordion-slider-settings[hide_description]']:checked").val();
        if(radioValue==1){
            jQuery('.captionColor').hide('slow');
            jQuery('.captionBgColor').hide('slow');
            jQuery('.captionFontSize').hide('slow');
        }
        else{
            jQuery('.captionColor').show('slow');
            jQuery('.captionBgColor').show('slow');
            jQuery('.captionFontSize').show('slow');
        }
    });

    jQuery('#hide_title, #hide_description').on('change', function() {
        var hide_title = jQuery("input[name='accordion-slider-settings[hide_title]']:checked").val();
        var hide_description = jQuery("input[name='accordion-slider-settings[hide_description]']:checked").val();
        if(hide_title==1 && hide_description==1){
            jQuery('.titleBgTransparency').hide('slow');
        }else{
            jQuery('.titleBgTransparency').show('slow');
        }
    });

    jQuery('#hide_morebtn').on('change', function() {
        var radioValue = jQuery("input[name='accordion-slider-settings[hide_morebtn]']:checked").val();
        if(radioValue==1){
            jQuery('.moreBtnColor').hide('slow');
            jQuery('.moreBtnBgColor').hide('slow');
            jQuery('.btnBorderWidth').hide('slow');
            jQuery('.moreBtnBorderColor').hide('slow');
            jQuery('.moreBtnBorderRadius').hide('slow');
            jQuery('.moreBtnBgTransparency').hide('slow');
            jQuery('.moreBtnFontSize').hide('slow');
        }
        else{
            jQuery('.moreBtnColor').show('slow');
            jQuery('.moreBtnBgColor').show('slow');
            jQuery('.btnBorderWidth').show('slow');
            jQuery('.moreBtnBorderColor').show('slow');
            jQuery('.moreBtnBorderRadius').show('slow');
            jQuery('.moreBtnBgTransparency').show('slow');
            jQuery('.moreBtnFontSize').show('slow');
        }
    });
    
    var navValue = jQuery("input[name='accordion-slider-settings[showNav]']:checked").val();
    if(navValue==1){
        jQuery('.navBorderWidth').show('slow');
        jQuery('.navBorderColor').show('slow');
        jQuery('.navBtnSize').show('slow');
        jQuery('.navBtnBorderRadius').show('slow');
        jQuery('.navColor').show('slow');
        jQuery('.navSelColor').show('slow');
        jQuery('.navAlign').show('slow');
    }

    jQuery('#showNav').on('change', function() {
        var radioValue = jQuery("input[name='accordion-slider-settings[showNav]']:checked").val();
        if(radioValue==1){
            jQuery('.navBorderWidth').show('slow');
            jQuery('.navBorderColor').show('slow');
            jQuery('.navBtnSize').show('slow');
            jQuery('.navBtnBorderRadius').show('slow');
            jQuery('.navColor').show('slow');
            jQuery('.navSelColor').show('slow');
            jQuery('.navAlign').show('slow');
        }
        else{
            jQuery('.navBorderWidth').hide('slow');
            jQuery('.navBorderColor').hide('slow');
            jQuery('.navBtnSize').hide('slow');
            jQuery('.navBtnBorderRadius').hide('slow');
            jQuery('.navColor').hide('slow');
            jQuery('.navSelColor').hide('slow');
            jQuery('.navAlign').hide('slow');
        }
    });

    var shadowValue = jQuery("input[name='accordion-slider-settings[shadow]']:checked").val();
    if(shadowValue==1){
        jQuery('.shadowSize').show('slow');
        jQuery('.shadowColor').show('slow');
    }

    jQuery('#shadow').on('change', function() {
        var radioValue = jQuery("input[name='accordion-slider-settings[shadow]']:checked").val();
        if(radioValue==1){
            jQuery('.shadowSize').show('slow');
            jQuery('.shadowColor').show('slow');
        }
        else{
            jQuery('.shadowSize').hide('slow');
            jQuery('.shadowColor').hide('slow');
        }
    });*/

    var autoPlayValue = jQuery("input[name='ras-accordion-slider-settings[slider-autoplay]']:checked").val();
    if(autoPlayValue==1){
        jQuery('#slider-delay').show('slow');
        jQuery('#slider-direction').show('slow');
    }

    jQuery('#slider-autoplay').on('change', function() {
        var radioValue = jQuery("input[name='ras-accordion-slider-settings[slider-autoplay]']:checked").val();
        if(radioValue==1){
            jQuery('#slider-delay').show('slow');
            jQuery('#slider-direction').show('slow');
        }
        else{
            jQuery('#slider-delay').hide('slow');
            jQuery('#slider-direction').hide('slow');
        }
    });

    jQuery('#hide-title').on('change', function() {
        var radioValue = jQuery("input[name='ras-accordion-slider-settings[hide-title]']:checked").val();
        if(radioValue==1){
            jQuery('#titleColor').hide('slow');
            jQuery('#titleBgColor').hide('slow');
            jQuery('#titleFontSize').hide('slow');
        }
        else{
            jQuery('#titleColor').show('slow');
            jQuery('#titleBgColor').show('slow');
            jQuery('#titleFontSize').show('slow');
        }
    });

    jQuery('#hide-description').on('change', function() {
        var radioValue = jQuery("input[name='ras-accordion-slider-settings[hide-description]']:checked").val();
        if(radioValue==1){
            jQuery('#captionColor').hide('slow');
            jQuery('#captionBgColor').hide('slow');
            jQuery('#captionFontSize').hide('slow');
        }
        else{
            jQuery('#captionColor').show('slow');
            jQuery('#captionBgColor').show('slow');
            jQuery('#captionFontSize').show('slow');
        }
    });

    jQuery('#hide-button').on('change', function() {
        var radioValue = jQuery("input[name='ras-accordion-slider-settings[hide-button]']:checked").val();
        if(radioValue==1){
            jQuery('#buttonFontSize').hide('slow');
            jQuery('#buttonBorder').hide('slow');
            jQuery('#buttonTextColor').hide('slow');
            jQuery('#buttonBgColor').hide('slow');
        }
        else{
            jQuery('#buttonFontSize').show('slow');
            jQuery('#buttonBorder').show('slow');
            jQuery('#buttonTextColor').show('slow');
            jQuery('#buttonBgColor').show('slow');
        }
    });
});