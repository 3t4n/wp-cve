jQuery('document').ready(function($){
    
    /* mail send */
    $('.elementinvader_addons_for_elementor_f').on('submit', function(e){
        e.preventDefault();
        var this_form = $(this);
        var $config = this_form.find('.config');
        var conf_link = $config.attr('data-url') || 0;
        var load_indicator = this_form.find('.ajax-indicator-masking');
        var box_alert = this_form.find('.elementinvader_addons_for_elementor_f_box_alert').html('');
        load_indicator.css('display', 'inline-block');
        
        var data = this_form.serializeArray();
        if(typeof data['action'] == 'undefined')
        data.push({ name: 'action', value: "elementinvader_addons_for_elementor_forms_send_form" });
        
            $.post(conf_link, data, 
                function(data){
                if(data.message)
                    box_alert.html(data.message)
                    
                if(data.success)
                {
                    if(typeof data.no_clear_from =='undefined' || data.no_clear_from =='') {
                        this_form.find('input:not([type="checkbox"]):not([name="element_id"]):not([type="radio"]):not([type="hidden"]),textarea,select').val('');
                        this_form.find('select').val(jQuery(this).find('option:first').val()); 
                        this_form.find('input[type="checkbox"]').prop('checked', false); 
                        
                        /* if exists default value, after reset set default value, from attr data-default */
                          this_form.find('input[data-default],textarea[data-default],select[data-default]').each(function(){
                            if($(this).attr('type') == 'checkbox' || $(this).attr('type') == 'radio')
                            {
                                if ($(this).attr('data-default') && $(this).prop('checked'))
                                {
                                    $(this).prop('checked', 'checked');
                                }
                            }
                            else
                            {
                                $(this).val($(this).attr('data-default'));
                            }
                        });
                    }

                    if(typeof data.redirect !='undefined' && data.redirect !='') {
                        window.location = data.redirect;
                    } else {
                        if(typeof grecaptcha != 'undefined') {
                            if(jQuery("div.g-recaptcha").length > 0) {
                                grecaptcha.reset();
                            } else {
                                //There's no container, there should be no captcha
                            }
                        }
                    }
                    
                    if(this_form.attr('scroll-disabled') != 'disabled')
                    jQuery("html, body").animate({
                        scrollTop: (+this_form.offset().top)-50
                    }, 500); 
                    
                } else {
                    if(typeof grecaptcha != 'undefined') {
                        if(jQuery("div.g-recaptcha").length > 0) {
                            grecaptcha.reset();
                        } else {
                            //There's no container, there should be no captcha
                        }
                    }
                }
            }).always(function(data) {
                load_indicator.css('display', 'none');
            });

        return false;
    });
    /* end mail send */
    
    /* Start menu dropdown */
    var _w = $(window);
    $('.elementinvader-addons-for-elementor .wl-nav-menu--dropdown .menu-item-has-children > a').each(function() {
        $(this).append('<span class="eli-caret"></span>');
    });
    /* End menu dropdown */
    
    $('.elementinvader-addons-for-elementor .wl-nav-menu--main .wl-nav-menu .menu-item > a').on('mouseover focus', function(e){
        e.preventDefault();
        e.stopPropagation();
        if(!$(this).parent().hasClass('active')) {
            $(this).parent().siblings().removeClass('active').find('.menu-item').removeClass('active');
            $(this).parent().toggleClass('active');
            var eli_el = $(this).closest('.elementinvader-addons-for-elementor');
            $(document).unbind('mouseout').on('mouseout', function(e){
                if(!$(e.target).closest('.elementinvader-addons-for-elementor').length) {
                    eli_el.find('.menu-item').removeClass('active');
                    $(document).unbind('mouseout');
                }
            })
        }
    })
        
    $("html").on("click", function(){
        $('.elementinvader-addons-for-elementor .wl-nav-menu--main .wl-nav-menu .menu-item-has-children').removeClass("active");
    });
    
    $('.elementinvader-addons-for-elementor .wl-nav-menu--main .wl-nav-menu .menu-item-has-children > a').on("mouseover", function(e) {
        e.stopPropagation();
    });

    var eli_menu_is_focus = false;
    $('.elementinvader-addons-for-elementor .wl-nav-menu--dropdown .menu-item.menu-item-has-children>a .eli-caret').on('click', function(e){
        e.preventDefault();
        if(!eli_menu_is_focus){
            $('.wl-nav-menu .menu-item-has-children').not($(this).parent().parent()).removeClass('active');
            $(this).parent().parent().toggleClass('active');
        }
    })
    
    $('.elementinvader-addons-for-elementor .wl-nav-menu--dropdown .menu-item.menu-item-has-children>a .eli-caret').on('click', function(e){
        e.stopPropagation();
    });

    $('.elementinvader-addons-for-elementor .wl-nav-menu--dropdown .menu-item.menu-item-has-children>a').on('keydown', function(e){
        var keyCode = e.keyCode || e.which; 

        if (!e.shiftKey && keyCode == 9) {
            if($(this).parent().hasClass('active')  &&  ($(this).attr('href') != '' &&  $(this).attr('href') != '#')){
                /* open link */

            } else {
                e.preventDefault();
                $(this).parent().siblings().removeClass('active').find('.menu-item').removeClass('active');
                $(this).parent().addClass('active');
                $(this).parent().find("ul.sub-menu > li").first().find('a').eq(0).attr('tabindex', -1).trigger('focus');
            }
        }
    })

    $('.elementinvader-addons-for-elementor .wl-nav-menu--dropdown .menu-item.menu-item-has-children>a').on('click', function(e){
        if($(this).parent().hasClass('active')  &&  ($(this).attr('href') != '' &&  $(this).attr('href') != '#')){
            /* open link */

        } else {
            e.preventDefault();
            $(this).parent().siblings().removeClass('active').find('.menu-item').removeClass('active');
            $(this).parent().toggleClass('active');
        }
    })
    
    $('.eli-menu .wl-menu-toggle,.wl_close-menu,.elementinvader-addons-for-elementor .wl_nav_mask').on('click', function (e) {
        e.preventDefault();
     
        var menu_widg = $(this).closest('.elementor-widget-eli-menu');
        menu_widg.toggleClass('wl_nav_show');
    });

    jQuery(".eli-menu .wl-menu-toggle").on('keydown', function(e) { 
        var keyCode = e.keyCode || e.which; 
        if (keyCode == 13) { 
            e.preventDefault(); 
            var menu_widg = $(this).closest('.elementor-widget-eli-menu');
            menu_widg.toggleClass('wl_nav_show');

            
            setTimeout (function(){
                jQuery(".elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container ul.wl-nav-menu > li").first().find('a').eq(0).attr('tabindex', -1).trigger('focus');
            },500);
                
        }
                
        if (keyCode == 9 && !jQuery(".wl_nav_show").length) {
            var current_link = jQuery(".elementinvader-addons-for-elementor .wl-nav-menu--dropdown a").last()[0];
            var flag = false;
            jQuery('a').each(function () {
                if (flag) {
                    jQuery(this).last().eq(0).attr('tabindex', -1).trigger('focus');
                    return false;
                }
                if (jQuery(this)[0] == current_link) {
                    flag = true;
                }
            });
        }
    });
    
    jQuery(".elementinvader-addons-for-elementor.wl-nav-menu--dropdown a").last().on('keydown', function(e) { 
        var keyCode = e.keyCode || e.which; 
        if (keyCode == 9) { 
          e.preventDefault(); 
          jQuery(".wl_close-menu").eq(0).attr('tabindex', -1).trigger('focus');
        } 
    });

    /* first menu item, when nav pre element, trigger to close btn */
    jQuery(".elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container ul.wl-nav-menu > li").first().find('a').on('keydown', function(e) { 
        var keyCode = e.keyCode || e.which; 
        if(e.shiftKey && keyCode == 9) { 
            //shift was down when tab was pressed
            e.preventDefault(); 
            jQuery(".wl_close-menu").eq(0).attr('tabindex', -1).trigger('focus');
        }
    });

    
    /* first menu item, when nav next element, trigger to close btn */
    jQuery(".elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container ul.wl-nav-menu a").last().on('keydown', function(e) { 
        var keyCode = e.keyCode || e.which; 
        if (!e.shiftKey && keyCode == 9) { 
            e.preventDefault(); 
            jQuery(".wl_close-menu").eq(0).attr('tabindex', -1).trigger('focus');
        } 
    });
    
    /* keyboard nav from close btn, trigger to first/last menu element */
    jQuery(".elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container a.wl_close-menu").on('keydown', function(e) { 
        var keyCode = e.keyCode || e.which; 
        if (!e.shiftKey && keyCode == 9) { 
            e.preventDefault(); 
            jQuery(".elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container ul.wl-nav-menu > li").first().find('a').eq(0).attr('tabindex', -1).trigger('focus');
        } else if(e.shiftKey && keyCode == 9) {
            e.preventDefault(); 
            jQuery(".elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container ul.wl-nav-menu > li").last().find('a').eq(0).attr('tabindex', -1).trigger('focus');
        }
    });
    
    /* End menu dropdown */

    $('.elementinvader-addons-for-elementor .wl-nav-menu--dropdown .menu-item.current-menu-parent').addClass('active');

    /* live edit elementor custom css */
    if(typeof elementor != 'undefined'){
        function addCustomCss(css, context) {
            if (!context) {
                return;
            }
            var model = context.model,
                customCSS = model.get('settings').get('custom_css');
            var selector = '.elementor-element.elementor-element-' + model.get('id');

            if ('document' === model.get('elType')) {
                selector = elementor.config.document.settings.cssWrapperSelector;
            }

            if (customCSS) {
                css += customCSS.replace(/selector/g, selector);
            }

            return css;
            }

        elementor.hooks.addFilter('editor/style/styleText', addCustomCss);

        function addPageCustomCss() {

            var customCSS = elementor.settings.page.model.get('custom_css');
            if (customCSS) {
                customCSS = customCSS.replace(/selector/g, elementor.config.settings.page.cssWrapperSelector);
                elementor.settings.page.getControlsCSS().elements.$stylesheetElement.append(customCSS);
            }
        }
        // elementor.settings.page.model.on('change', addPageCustomCss);
        elementor.on('preview:loaded', addPageCustomCss);
    }

    if(typeof $.fn.WdkScrollMobileSwipe == 'function') {
        jQuery('.EliScrollMobileSwipe_enable').WdkScrollMobileSwipe();
    }
})