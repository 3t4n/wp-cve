jQuery('document').ready(function () {
    clickDisable();
    if( jQuery('#custom_css').length ) {
        if(bdlite_js.wp_version >= '4.9') {
            var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
            editorSettings.codemirror = _.extend(
                {},editorSettings.codemirror,{indentUnit:2,tabSize:2,mode:'css'}
            );
            var editor = wp.codeEditor.initialize( jQuery('#custom_css'), editorSettings );
        }   
    }

    jQuery("#content_fontsize,#template_postContentfontsizeInput,#template_titlefontsize,#template_postTitlefontsizeInput").slider({
        range: "min",value: 1,step: 1,min: 0,max: 100,
        slide: function (event, ui) {
            jQuery(this).parents('.bd-typography-content').find('input.range-slider__value').val(ui.value);
        }
    });

    var content_fontsize = jQuery('#content_fontsize').closest('tr').find('input.range-slider__value').val()
    jQuery("#content_fontsize").slider("value", content_fontsize);
    var author_title_fontsize = jQuery('#template_titlefontsize').parents('.bd-typography-content').find('input.range-slider__value').val()
    jQuery("#template_titlefontsize").slider("value", author_title_fontsize);

    jQuery(".range-slider__value").change(function () {
        var value = this.value;
        var max = 100;
        if (value > max) {
            jQuery(this).parents('.bd-typography-content').find('.range_slider_fontsize').slider("value", '100');
            jQuery(this).val('100');
        } else{
            jQuery(this).parents('.bd-typography-content').find('.range_slider_fontsize').slider("value", parseInt(value));
        }
    });
    var post_title_fontsize = jQuery('#template_postContentfontsizeInput').parents('.bd-typography-content').find('input.range-slider__value').val()
    jQuery("#template_postContentfontsizeInput").slider("value", post_title_fontsize);
    var post_title_fontsize = jQuery('#template_postTitlefontsizeInput').parents('.bd-typography-content').find('input.range-slider__value').val()
    jQuery("#template_postTitlefontsizeInput").slider("value", post_title_fontsize);

    jQuery('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>').insertAfter('.quantity input');
    jQuery('.quantity').each(function () {
        var spinner = jQuery(this),
                input = spinner.find('input[type="number"]'),
                btnUp = spinner.find('.quantity-up'),
                btnDown = spinner.find('.quantity-down'),
                min = input.attr('min'),
                max = input.attr('max');

        btnUp.click(function () {
            var oldValue = parseFloat(input.val());
            if (oldValue >= max) {
                var newVal = oldValue;
            } else{
                var newVal = oldValue + 1;
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
        });

        btnDown.click(function () {
            var oldValue = parseFloat(input.val());
            if (oldValue <= min) {
                var newVal = oldValue;
            } else{
                var newVal = oldValue - 1;
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
        });

    });
    jQuery('#template_ftcolor,#template_fthovercolor,#template_bgcolor,#grid_hoverback_color,#template_alterbgcolor,#template_titlecolor,#template_titlehovercolor,#template_titlebackcolor,#template_contentcolor,#template_readmorecolor,#template_readmorebackcolor,#template_color,#template_labeltextcolor').wpColorPicker();

    if (jQuery("input[name='rss_use_excerpt']:checked").val() == 1) {
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();

        jQuery('.excerpt_length').removeClass('bd-hidden');
        jQuery('.read_more_on').removeClass('bd-hidden');
        if (jQuery("input[name='readmore_on']:checked").val() == 0) {
            jQuery('.read_more_text').hide();
            jQuery('.read_more_text_color').hide();
            jQuery('.read_more_text_background').hide();

            jQuery('.read_more_text').addClass('bd-hidden');
            jQuery('.read_more_text_color').addClass('bd-hidden');
            jQuery('.read_more_text_background').addClass('bd-hidden');
        } else if (jQuery("input[name='readmore_on']:checked").val() == 1) {
            jQuery('.read_more_text').show();
            jQuery('.read_more_text_color').show();
            jQuery('.read_more_text_background').hide();

            jQuery('.read_more_text').removeClass('bd-hidden');
            jQuery('.read_more_text_color').removeClass('bd-hidden');
            jQuery('.read_more_text_background').addClass('bd-hidden');
        } else{
            jQuery('.read_more_text').show();
            jQuery('.read_more_text_color').show();
            jQuery('.read_more_text_background').show();

            jQuery('.read_more_text').removeClass('bd-hidden');
            jQuery('.read_more_text_color').removeClass('bd-hidden');
            jQuery('.read_more_text_background').removeClass('bd-hidden');
        }
    } else{
        jQuery('.excerpt_length').hide();
        jQuery('.read_more_on').hide();
        jQuery('.read_more_text').hide();
        jQuery('.read_more_text_color').hide();
        jQuery('.read_more_text_background').hide();

        jQuery('.excerpt_length').addClass('bd-hidden');
        jQuery('.read_more_on').addClass('bd-hidden');
        jQuery('.read_more_text').addClass('bd-hidden');
        jQuery('.read_more_text_color').addClass('bd-hidden');
        jQuery('.read_more_text_background').addClass('bd-hidden');
    }

    if (jQuery("input[name='readmore_on']").is(':visible')) {
        if (jQuery("input[name='readmore_on']:checked").val() == 0) {
            jQuery('.read_more_text').hide();
            jQuery('.read_more_text_color').hide();
            jQuery('.read_more_text_background').hide();

            jQuery('.read_more_text').addClass('bd-hidden');
            jQuery('.read_more_text_color').addClass('bd-hidden');
            jQuery('.read_more_text_background').addClass('bd-hidden');
        } else if (jQuery("input[name='readmore_on']:checked").val() == 1) {
            jQuery('.read_more_text').show();
            jQuery('.read_more_text_color').show();
            jQuery('.read_more_text_background').hide();

            jQuery('.read_more_text').removeClass('bd-hidden');
            jQuery('.read_more_text_color').removeClass('bd-hidden');
            jQuery('.read_more_text_background').addClass('bd-hidden');
        } else{
            jQuery('.read_more_text').show();
            jQuery('.read_more_text_color').show();
            jQuery('.read_more_text_background').show();

            jQuery('.read_more_text').removeClass('bd-hidden');
            jQuery('.read_more_text_color').removeClass('bd-hidden');
            jQuery('.read_more_text_background').removeClass('bd-hidden');
        }
    }

    jQuery("input[name='template_alternativebackground']").change(function () {
        if (jQuery(this).val() == 0) {
            jQuery('.alternative-color-tr').show();
            jQuery('.alternative-color-tr').removeClass('bd-hidden');
        } else{
            jQuery('.alternative-color-tr').hide();
            jQuery('.alternative-color-tr').addClass('bd-hidden');
        }
        bdAltBackground();
    });

    if (jQuery("input[name='social_share']:checked").val() != 1) {
        jQuery('.bd-social-share-options').hide();
        jQuery('.bd-social-share-options').addClass('bd-hidden');
    } else{
        jQuery('.bd-social-share-options').show();
        jQuery('.bd-social-share-options').removeClass('bd-hidden');
    }
    jQuery("input[name='social_share']").change(function () {
        if (jQuery(this).val() == 0) {
            jQuery('.bd-social-share-options').hide();
            jQuery('.bd-social-share-options').addClass('bd-hidden');
        } else{
            jQuery('.bd-social-share-options').show();
            jQuery('.bd-social-share-options').removeClass('bd-hidden');
        }
    });
    jQuery('.blog-sallet-slider-tr').hide();
    if (jQuery('#template_name').val() == 'classical' || jQuery('#template_name').val() == 'spektrum' || jQuery('#template_name').val() == 'timeline' || jQuery('#template_name').val() == 'news' || jQuery('#template_name').val() == 'nicy') {
        jQuery('.blog-template-tr').hide();
        jQuery('.blog-columns-tr').hide();
        jQuery('.bd-readmore-display-on').show();
        jQuery('.alternative-color-tr').hide();
        jQuery('.blog-template-tr').addClass('bd-hidden');
        jQuery('.alternative-color-tr').addClass('bd-hidden');
        jQuery('.hoverbackcolor-tr').hide();
        jQuery('.design-type-tr').hide();
        jQuery('.label_text_color_tr').hide();
        jQuery('.ticker_label_tr').hide();
        jQuery('.bd-setting-handle li').each(function () {
            var hide = jQuery(this).attr('data-show');
            if (hide == 'bdpslider') {
                jQuery(this).addClass('clickDisable');
            }
        });
    } else if (jQuery('#template_name').val() == 'boxy-clean') { 
        jQuery('.bd-setting-handle li').each(function () {
            var hide = jQuery(this).attr('data-show');
            if (hide == 'bdpslider') {
                jQuery(this).addClass('clickDisable');
            }
        });
        jQuery('.blog-template-tr').hide();
        jQuery('.blog-columns-tr').show();
        jQuery('.alternative-tr').hide();
        jQuery('.hoverbackcolor-tr').show();
        jQuery('.blog-templatecolor-tr').show();
        jQuery('.alternative-color-tr').hide();
        jQuery('.bd-readmore-display-on').show();
        jQuery('.blog-template-tr').addClass('bd-hidden');
        jQuery('.alternative-color-tr').addClass('bd-hidden');
        jQuery('.design-type-tr').hide();
        jQuery('.label_text_color_tr').hide();
        jQuery('.ticker_label_tr').hide();
    }  else if (jQuery('#template_name').val() == 'glossary') { 
        jQuery('.bd-setting-handle li').each(function () {
            var hide = jQuery(this).attr('data-show');
            if (hide == 'bdpslider') {
                jQuery(this).addClass('clickDisable');
            }
        });
        jQuery('.blog-template-tr').hide();
        jQuery('.blog-columns-tr').show();
        jQuery('.alternative-tr').hide();
        jQuery('.blog-templatecolor-tr').show();
        jQuery('.hoverbackcolor-tr').hide();
        jQuery('.design-type-tr').hide();
        jQuery('.label_text_color_tr').hide();
        jQuery('.ticker_label_tr').hide();
        jQuery('.alternative-color-tr').hide();
        jQuery('.bd-readmore-display-on').hide();
        jQuery('label[for=readmore_on_1]').css('display','none');
        jQuery('label[for=readmore_on_2]').css({'border-bottom-left-radius' : '5px','border-top-left-radius' : '5px','border-left' : '1px solid #B6B6B6'});
        jQuery('.blog-template-tr').addClass('bd-hidden');
        jQuery('.alternative-color-tr').addClass('bd-hidden');
    }  else if (jQuery('#template_name').val() == 'crayon_slider') {
        jQuery('.bd-setting-handle li').each(function () {
            var hide = jQuery(this).attr('data-show');
            if (hide == 'bdpslider') {
                jQuery(this).removeClass('clickDisable');
            }
            if (hide == 'bdppagination') {
                jQuery(this).addClass('clickDisable');
            }
        });
        jQuery('.blog-template-tr').hide();
        jQuery('.blog-columns-tr').hide();
        jQuery('.alternative-color-tr').hide();
        jQuery('.hoverbackcolor-tr').hide();
        jQuery('.design-type-tr').show();
        jQuery('.label_text_color_tr').hide();
        jQuery('.ticker_label_tr').hide();
        jQuery('.blog-template-tr').addClass('bd-hidden');
        jQuery('.alternative-color-tr').addClass('bd-hidden');
        jQuery('.bd-readmore-display-on').show();
        jQuery('.blog-templatecolor-tr').hide();
    } else if (jQuery('#template_name').val() == 'sallet_slider') {
        jQuery('.bd-setting-handle li').each(function () {
            var hide = jQuery(this).attr('data-show');
            if (hide == 'bdpslider') {
                jQuery(this).removeClass('clickDisable');
            }
            if (hide == 'bdppagination') {
                jQuery(this).addClass('clickDisable');
            }
        });
        jQuery('.blog-template-tr').show();
        jQuery('.blog-columns-tr').hide();
        jQuery('.alternative-color-tr').hide();
        jQuery('.hoverbackcolor-tr').hide();
        jQuery('.design-type-tr').hide();
        jQuery('.label_text_color_tr').hide();
        jQuery('.ticker_label_tr').hide();
        jQuery('.alternative-tr').hide();
        jQuery('.blog-sallet-slider-tr').show();
        jQuery('.alternative-color-tr').addClass('bd-hidden');
        jQuery('.bd-readmore-display-on').show();
    } else if (jQuery('#template_name').val() == 'media-grid') {
        jQuery('.bd-setting-handle li').each(function () {
            var hide = jQuery(this).attr('data-show');
            if (hide == 'bdpslider') {
                jQuery(this).addClass('clickDisable');
            }
        });
        jQuery('.blog-template-tr').hide();
        jQuery('.blog-columns-tr').show();
        jQuery('.alternative-tr').hide();
        jQuery('.hoverbackcolor-tr').show();
        jQuery('.design-type-tr').hide();
        jQuery('.label_text_color_tr').hide();
        jQuery('.ticker_label_tr').hide();
        jQuery('.blog-templatecolor-tr').show();
        jQuery('.alternative-color-tr').hide();
        jQuery('.bd-readmore-display-on').show();
        jQuery('.blog-template-tr').addClass('bd-hidden');
        jQuery('.alternative-color-tr').addClass('bd-hidden');
    } else if (jQuery('#template_name').val() == 'blog-carousel') {
        jQuery('.bd-setting-handle li').each(function () {
            var hide = jQuery(this).attr('data-show');
            if (hide == 'bdpslider') {
                jQuery(this).removeClass('clickDisable');
            }
            if (hide == 'bdppagination') {
                jQuery(this).addClass('clickDisable');
            }
        });
        jQuery('.blog-template-tr').hide();
        jQuery('.blog-columns-tr').hide();
        jQuery('.alternative-color-tr').hide();
        jQuery('.hoverbackcolor-tr').show();
        jQuery('.design-type-tr').hide();
        jQuery('.label_text_color_tr').hide();
        jQuery('.ticker_label_tr').hide();
        jQuery('.blog-template-tr').addClass('bd-hidden');
        jQuery('.alternative-color-tr').addClass('bd-hidden');
        jQuery('.bd-readmore-display-on').show();
    } else if (jQuery('#template_name').val() == 'blog-grid-box') {
        jQuery('.bd-setting-handle li').each(function () {
            var hide = jQuery(this).attr('data-show');
            if (hide == 'bdpslider') {
                jQuery(this).addClass('clickDisable');
            }
        });
        jQuery('.blog-template-tr').hide();
        jQuery('.blog-columns-tr').hide();
        jQuery('.alternative-tr').hide();
        jQuery('.hoverbackcolor-tr').hide();
        jQuery('.design-type-tr').hide();
        jQuery('.label_text_color_tr').hide();
        jQuery('.ticker_label_tr').hide();
        jQuery('.blog-templatecolor-tr').show();
        jQuery('.alternative-color-tr').hide();
        jQuery('.bd-readmore-display-on').show();
        jQuery('.blog-template-tr').addClass('bd-hidden');
        jQuery('.alternative-color-tr').addClass('bd-hidden');
        jQuery('#pagination_type option[value="load_more_btn"]').hide();
    } else if (jQuery('#template_name').val() == 'ticker') {
        jQuery('.bd-setting-handle li').each(function () {
            var hide = jQuery(this).attr('data-show');
            if (hide == 'bdpmedia') {
                jQuery(this).addClass('clickDisable');
            }
            if (hide == 'bdpslider') {
                jQuery(this).addClass('clickDisable');
            }
            if (hide == 'bdppagination') {
                jQuery(this).addClass('clickDisable');
            }
            if (hide == 'bdpsocial') {
                jQuery(this).addClass('clickDisable');
            }
            if (hide == 'bdpads') {
                jQuery(this).addClass('clickDisable');
            }
            if (hide == 'bdpcontent') {
                jQuery(this).addClass('clickDisable');
            }
        });
        jQuery('.blog-template-tr').hide();
        jQuery('.blog-columns-tr').hide();
        jQuery('.alternative-tr').hide();
        jQuery('.hoverbackcolor-tr').hide();
        jQuery('.design-type-tr').hide();
        jQuery('.blog-templatecolor-tr').show();
        jQuery('.alternative-color-tr').hide();
        jQuery('.bd-readmore-display-on').hide();
        jQuery('.blog-template-tr').addClass('bd-hidden');
        jQuery('.alternative-color-tr').addClass('bd-hidden');
        jQuery('.link-color-tr').addClass('bd-hidden');
        jQuery('.link-hovercolor-tr').addClass('bd-hidden');
        jQuery('#pagination_type option[value="load_more_btn"]').hide();
        jQuery('#bdpgeneral .bd-display-settings.bd-gray').addClass('bd-hidden');
        jQuery('.label_text_color_tr').show();
        jQuery('.ticker_label_tr').show();
    } else if (jQuery('#template_name').val() == 'evolution') {
        jQuery('.design-type-tr').hide();
        jQuery('.label_text_color_tr').hide();
        jQuery('.ticker_label_tr').hide();
        jQuery('.blog-template-tr').hide();
        jQuery('.alternative-tr').show();
        jQuery('.bd-setting-handle li').each(function () {
            var hide = jQuery(this).attr('data-show');
            if (hide == 'bdpslider') {
                jQuery(this).addClass('clickDisable');
            }
        });
     } else if (jQuery('#template_name').val() == 'lightbreeze') {
        jQuery('.design-type-tr').hide();
        jQuery('.label_text_color_tr').hide();
        jQuery('.ticker_label_tr').hide();
        jQuery('.blog-columns-tr').hide();
        jQuery('.blog-template-tr').hide();
        jQuery('.bd-setting-handle li').each(function () {
            var hide = jQuery(this).attr('data-show');
            if (hide == 'bdpslider') {
                jQuery(this).addClass('clickDisable');
            }
        });
     } else {
        jQuery('.bd-setting-handle li').each(function () {
            var hide = jQuery(this).attr('data-show');
            if (hide == 'bdpslider') {
                jQuery(this).addClass('clickDisable');
            }
        });
        jQuery('.blog-template-tr').show();
        jQuery('.blog-columns-tr').hide();
        jQuery('.hoverbackcolor-tr').hide();
        jQuery('.bd-readmore-display-on').show();
        jQuery('.blog-template-tr').removeClass('bd-hidden');
        if (jQuery("input[name='template_alternativebackground']:checked").val() == 0) {
            jQuery('.alternative-color-tr').show();
            jQuery('.alternative-color-tr').removeClass('bd-hidden');
        } else{
            jQuery('.alternative-color-tr').hide();
            jQuery('.alternative-color-tr').addClass('bd-hidden');
        }
    }
    if (jQuery('#template_name').val() == 'timeline') {
        jQuery('.blog-template-tr').hide();
        jQuery('.alternative-color-tr').hide();
        jQuery('.blog-templatecolor-tr').show();
        jQuery('.design-type-tr').hide();
        jQuery('.blog-template-tr').addClass('bd-hidden');
        jQuery('.alternative-color-tr').addClass('bd-hidden');
        jQuery('.blog-templatecolor-tr').removeClass('bd-hidden');
        jQuery('.bd-setting-handle li').each(function () {
            var hide = jQuery(this).attr('data-show');
            if (hide == 'bdpslider') {
                jQuery(this).addClass('clickDisable');
            }
        });
    }

    jQuery("input[name='rss_use_excerpt']").change(function () {

        if (jQuery(this).val() == 1) {
            jQuery('.excerpt_length').css('display', 'inline-block');
            jQuery('.read_more_on').show();
            jQuery('.read_more_text').show();
            jQuery('.read_more_text_color').show();
            jQuery('.read_more_text_background').show();

            jQuery('.excerpt_length').removeClass('bd-hidden');
            jQuery('.read_more_on').removeClass('bd-hidden');
            jQuery('.read_more_text').removeClass('bd-hidden');
            jQuery('.read_more_text_color').removeClass('bd-hidden');
            jQuery('.read_more_text_background').removeClass('bd-hidden');
        } else{
            jQuery('.excerpt_length').hide();
            jQuery('.read_more_on').hide();
            jQuery('.read_more_text').hide();
            jQuery('.read_more_text_color').hide();
            jQuery('.read_more_text_background').hide();

            jQuery('.excerpt_length').addClass('bd-hidden');
            jQuery('.read_more_on').addClass('bd-hidden');
            jQuery('.read_more_text').addClass('bd-hidden');
            jQuery('.read_more_text_color').addClass('bd-hidden');
            jQuery('.read_more_text_background').addClass('bd-hidden');
        }
        bdAltBackground();
    });

    jQuery("input[name='readmore_on']").change(function () {
        if (jQuery(this).val() == 0) {
            jQuery('.read_more_text').hide();
            jQuery('.read_more_text_color').hide();
            jQuery('.read_more_text_background').hide();

            jQuery('.read_more_text').addClass('bd-hidden');
            jQuery('.read_more_text_color').addClass('bd-hidden');
            jQuery('.read_more_text_background').addClass('bd-hidden');
        } else if (jQuery(this).val() == 1) {
            jQuery('.read_more_text').show();
            jQuery('.read_more_text_color').show();
            jQuery('.read_more_text_background').hide();
            if( 'sallet_slider' == jQuery('#template_name').val() ) {
                jQuery('#template_readmorecolor').val('#3e8563');
            }

            jQuery('.read_more_text').removeClass('bd-hidden');
            jQuery('.read_more_text_color').removeClass('bd-hidden');
            jQuery('.read_more_text_background').addClass('bd-hidden');
        } else{
            jQuery('.read_more_text').show();
            jQuery('.read_more_text_color').show();
            jQuery('.read_more_text_background').show();
            if( 'sallet_slider' == jQuery('#template_name').val() ) {
                jQuery('#template_readmorecolor').val('#ffffff');
            }

            jQuery('.read_more_text').removeClass('bd-hidden');
            jQuery('.read_more_text_color').removeClass('bd-hidden');
            jQuery('.read_more_text_background').removeClass('bd-hidden');
        }
        bdAltBackground();
    });

    jQuery('link').each(function () {
        var href = jQuery(this).attr('href');
        if (href.search('jquery-ui.css') !== -1 || href.search('jquery-ui.min.css') !== -1) {
            jQuery(this).remove('link');
        }
    });

    /*Set Default value for each template*/
    jQuery('.bd-form-class .bdp-restore-default').click(function () {
        if (confirm(bdlite_js.reset_data)) {
            var template = jQuery('#template_name').val();
            default_data(template);
            jQuery('form.bd-form-class')[0].submit();
        } else{
            return false;
        }
    });

    /*Getting Started Page*/
    jQuery('.blog-designer-panel-list li a').on('click', function(e) {
        e.preventDefault();
        if(jQuery(this).closest('li').hasClass('active')) {return;}
        jQuery('.blog-designer-panel-list li').removeClass('active');
        jQuery(this).closest('li').addClass('active');
        jQuery('.blog-designer-panel-wrap > div').hide();
        var dateId = jQuery(this).data('id');
        if(jQuery('.blog-designer-panel-wrap > div').hasClass(dateId)) {
            jQuery('.blog-designer-panel-wrap > div.'+dateId).show();
        }

    });

    /*Create Test Page*/
    jQuery('.create-test-page').on('click', function(e) {
        e.preventDefault();
        jQuery(this).closest('h4.do-create-test-page').find('img').show();
        jQuery(this).closest('h4.do-create-test-page').css({"opacity": "0.5", "cursor": "progress"})
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'bd_create_sample_layout',
            },
            success: function (response) {
                jQuery('.do-create-test-page').hide();
                jQuery('.done-create-test-page').show();
                console.log(response);
                jQuery('.done-create-test-page').find('a').attr('href', response);
            }
        });
    });

});

jQuery(window).load(function () {    
    // deactivation popup code
    var bd_plugin_admin = jQuery('.documentation_bd_plugin').closest('div').find('.deactivate').find('a');
    jQuery('.bd-deactivation').on('click', function() {
        window.location.href = bd_plugin_admin.attr('href');
    });
    bd_plugin_admin.click(function (event) {
        event.preventDefault();
        jQuery('#deactivation_thickbox_bd').trigger('click');
        jQuery('#TB_window').removeClass('thickbox-loading');
        change_thickbox_size();
    });
    jQuery('.bd-deactivation').on('click', function() {
        window.location.href = bd_plugin_admin.attr('href');
    });    
    checkOtherDeactivate();
    jQuery('.sol_deactivation_reasons').click(function () {
        checkOtherDeactivate();
    });
    jQuery('#sbtDeactivationFormClosebd').click(function (event) {
        event.preventDefault();
        jQuery("#TB_closeWindowButton").trigger('click');
    })
    function checkOtherDeactivate() {
        var selected_option_de = jQuery('input[name=sol_deactivation_reasons_bd]:checked', '#frmDeactivationbd').val();
        if (selected_option_de == '9') {
            jQuery('.sol_deactivation_reason_other_bd').val('');
            jQuery('.sol_deactivation_reason_other_bd').show();
        }
        else{
            jQuery('.sol_deactivation_reason_other_bd').val('');
            jQuery('.sol_deactivation_reason_other_bd').hide();
        }
        if (selected_option_de == '3') {
            jQuery('.sol_deactivation_reasons_solution').show();
        }
        else{
            jQuery('.sol_deactivation_reasons_solution').hide();
        }
    }
    
    function change_thickbox_size() {
        jQuery(document).find('#TB_window').width('750').height('500').css('margin-left', -700 / 2);
        jQuery(document).find('#TB_ajaxContent').width('700');
        var doc_height = jQuery(window).height();
        var doc_space = doc_height - 500;
        if (doc_space > 0) {
            jQuery(document).find('#TB_window').css('margin-top', doc_space / 2);
        }
    }
});


jQuery('.bd-form-class .bd-setting-handle > li').click(function (event) {
    if (jQuery(this).hasClass('clickDisable')) {
        clickDisable();
    } else{
        var section = jQuery(this).data('show');
        jQuery('.bd-form-class .bd-setting-handle > li').removeClass('bd-active-tab');
        jQuery(this).addClass('bd-active-tab');
        jQuery('.bd-settings-wrappers .postbox').hide();
        jQuery('#' + section).show();
        jQuery.post(ajaxurl, {
            action: 'bd_closed_bdboxes',closed: section,page: 'designer_settings', nonce: bdlite_js.nonce,
        });
    }
});

jQuery(document).ready(function () {
    var config = {
        '.chosen-select': {},
        '.chosen-select-deselect': {allow_single_deselect: true},
        '.chosen-select-no-single': {disable_search_threshold: 10},
        '.chosen-select-no-results': {no_results_text: bdlite_js.nothing_found},
        '.chosen-select-width': {width: "95%"}
    }
    for (var selector in config) {
        jQuery(selector).chosen(config[selector]);
    }
    jQuery('.select-cover select').chosen({no_results_text: bdlite_js.nothing_found});
    jQuery('.buttonset').buttonset();
    jQuery("#bd-submit-button").click(function () {
        jQuery(".save_blogdesign").trigger("click");
        bdAltBackground();
    }); 
    jQuery("#bd-submit-ticker-button").click(function () {
        jQuery(".save_blogdesign_ticker").trigger("click");
        bdAltBackground();
    });  
    jQuery(".bd-settings-wrappers .postbox table tr td:first-child").hover(function () {
        var $parent_height = jQuery(this).height();
        var $height = jQuery(this).children('.bd-title-tooltip').height();
        jQuery(this).children('.bd-title-tooltip').css('top', 'calc(50% - 30px - ' + $height + 'px)');
    });
    jQuery('#blog_page_display').change(function () {
        jQuery.ajax({type: 'POST',url: ajaxurl,data: {action: 'bd_get_page_link',page_id: jQuery(this).val(),},success: function (response) {jQuery('.page_link').html('');jQuery('.page_link').append(response);}});
    });

    // select template code
    jQuery("#bd_popupdiv div.bd-template-thumbnail .bd-popum-select a").on('click', function (e) {
        e.preventDefault();
        jQuery('#bd_popupdiv div.bd-template-thumbnail').removeClass('bd_selected_template');
        jQuery(this).parents('div.bd-template-thumbnail').addClass('bd_selected_template');
    });
    if(jQuery("select[name='pagination_type']").val() == 'load_more_btn') {
        jQuery('.loadmore_btn_option').show();
    } else {
        jQuery('.loadmore_btn_option').hide();
    }
    jQuery("select[name='pagination_type']").change(function () {
        jQuery('.loadmore_btn_option').hide();
        if (jQuery(this).val() == 'load_more_btn') {
            jQuery('.loadmore_btn_option').show();
        }
    });
    jQuery(".bd_select_template").on('click', function (e) {
        e.preventDefault();
        var template_name = jQuery('#template_name').val();
        jQuery("#bd_popupdiv").dialog({
            title: bdlite_js.choose_blog_template,
            dialogClass: 'bd_template_model',
            width: jQuery(window).width() - 100,
            height: jQuery(window).height() - 100,
            modal: true,
            draggable: false,
            resizable: false,
            create: function (e, ui) {
                var pane = jQuery(this).dialog("widget").find(".ui-dialog-buttonpane");
                jQuery("<div class='bp-div-default-style'><label><input id='bp-apply-default-style' class='bp-apply-default-style' type='checkbox'/>" + bdlite_js.default_style_template + "</label></div>").prependTo(pane);
            },
            buttons: [{
                    text: bdlite_js.set_blog_template,
                    id: "btnSetBlogTemplate",
                    click: function () {
                        var template_name = jQuery('#bd_popupdiv div.bd-template-thumbnail.bd_selected_template .bd-template-thumbnail-inner').children('img').attr('src');
                        if (typeof template_name === 'undefined' || template_name === null) {
                            jQuery("#bd_popupdiv").dialog('close');
                            return;
                        }
                        var template_value = jQuery('#bd_popupdiv div.bd-template-thumbnail.bd_selected_template .bd-template-thumbnail-inner').children('img').attr('data-value');
                        jQuery(".bd_selected_template_image > div").empty();
                        jQuery('#template_name').val(template_value);
                        jQuery('.bd-template-name').text(template_value + ' Template');
                        jQuery(".bd_selected_template_image > div").append('<img src="' + template_name + '" alt="' + template_value.replace('_', '-') + ' Template" /><label id="bd_template_select_name">' + template_value.replace('_', '-') + ' Template</label>');
                        jQuery('.bd-setting-handle li').each(function () {
                            var hide = jQuery(this).attr('data-show');
                            if (hide == 'bdppagination') {
                                jQuery(this).removeClass('clickDisable');
                            }
                        });
                        if (template_value == 'classical' || template_value == 'spektrum' || template_value == 'timeline' || template_value == 'news' || template_value == 'nicy') {
                            jQuery('.hoverbackcolor-tr').hide();
                            jQuery('.design-type-tr').hide();
                            jQuery('.blog-template-tr').hide();
                            jQuery('.alternative-color-tr').hide();
                            jQuery('.bd-readmore-display-on').show();
                            jQuery('.blog-columns-tr').hide();
                            jQuery('.blog-template-tr').addClass('bd-hidden');
                            jQuery('.bd-setting-handle li').each(function () {
                                var hide = jQuery(this).attr('data-show');
                                if (hide == 'bdpslider') {
                                    jQuery(this).addClass('clickDisable');
                                }
                            });
                            jQuery('.alternative-color-tr').addClass('bd-hidden');
                        } else if (template_value == 'boxy-clean') {
                            jQuery('.bd-setting-handle li').each(function () {
                                var hide = jQuery(this).attr('data-show');
                                if (hide == 'bdpslider') {
                                    jQuery(this).addClass('clickDisable');
                                }
                            });
                            jQuery('.blog-template-tr').hide();
                            jQuery('.bd-readmore-display-on').show();
                            jQuery('.blog-columns-tr').show();
                            jQuery('.alternative-tr').hide();
                            jQuery('.blog-templatecolor-tr').show();
                            jQuery('.alternative-color-tr').hide();
                            jQuery('.hoverbackcolor-tr').show();
                            jQuery('.design-type-tr').hide();
                            jQuery('.blog-template-tr').addClass('bd-hidden');
                            jQuery('.alternative-color-tr').addClass('bd-hidden');
                        } else if (template_value == 'glossary') {
                            jQuery('.bd-setting-handle li').each(function () {
                                var hide = jQuery(this).attr('data-show');
                                if (hide == 'bdpslider') {
                                    jQuery(this).addClass('clickDisable');
                                }
                            });
                            jQuery('.blog-template-tr').hide();
                            jQuery('.bd-readmore-display-on').hide();
                            jQuery('.blog-columns-tr').show();
                            jQuery('.alternative-tr').hide();
                            jQuery('.blog-templatecolor-tr').show();
                            jQuery('.alternative-color-tr').hide();
                            jQuery('.hoverbackcolor-tr').hide();
                            jQuery('.design-type-tr').hide();
                            jQuery('.blog-template-tr').addClass('bd-hidden');
                            jQuery('.alternative-color-tr').addClass('bd-hidden');
                        } else if (template_value == 'crayon_slider') {
                            jQuery('.blog-template-tr').hide();
                            jQuery('.blog-columns-tr').hide();
                            jQuery('.alternative-color-tr').hide();
                            jQuery('.bd-readmore-display-on').show();
                            jQuery('.blog-template-tr').addClass('bd-hidden');
                            jQuery('.alternative-color-tr').addClass('bd-hidden');
                            jQuery('.hoverbackcolor-tr').hide();
                            jQuery('.design-type-tr').hide();
                            jQuery('.blog-templatecolor-tr').hide();
                            jQuery('.bd-setting-handle li').each(function () {
                                var hide = jQuery(this).attr('data-show');
                                if (hide == 'bdpslider') {
                                    jQuery(this).removeClass('clickDisable');
                                }
                                if (hide == 'bdppagination') {
                                    jQuery(this).addClass('clickDisable');
                                }
                            });
                        }  else if ( template_value == 'sallet_slider' ) {
                            jQuery('.blog-template-tr').show();
                            jQuery('.blog-columns-tr').hide();
                            jQuery('.alternative-color-tr').hide();
                            jQuery('.hoverbackcolor-tr').hide();
                            jQuery('.design-type-tr').hide();
                            jQuery('.alternative-tr').hide();
                            jQuery('.blog-sallet-slider-tr').show();
                            jQuery('.alternative-color-tr').addClass('bd-hidden');
                            jQuery('.bd-readmore-display-on').show();
                            jQuery('.bd-setting-handle li').each(function () {
                                var hide = jQuery(this).attr('data-show');
                                if (hide == 'bdpslider') {
                                    jQuery(this).removeClass('clickDisable');
                                }
                            });
                        } else if ( template_value == 'media-grid' ) {
                            jQuery('.bd-setting-handle li').each(function () {
                                var hide = jQuery(this).attr('data-show');
                                if (hide == 'bdpslider') {
                                    jQuery(this).addClass('clickDisable');
                                }
                            });
                            jQuery('.blog-template-tr').hide();
                            jQuery('.blog-columns-tr').show();
                            jQuery('.alternative-tr').hide();
                            jQuery('.hoverbackcolor-tr').show();
                            jQuery('.design-type-tr').hide();
                            jQuery('.blog-templatecolor-tr').show();
                            jQuery('.alternative-color-tr').hide();
                            jQuery('.bd-readmore-display-on').show();
                            jQuery('.blog-template-tr').addClass('bd-hidden');
                            jQuery('.alternative-color-tr').addClass('bd-hidden');
                        } else if (template_value == 'blog-carousel') {
                            jQuery('.blog-template-tr').hide();
                            jQuery('.blog-columns-tr').hide();
                            jQuery('.alternative-color-tr').hide();
                            jQuery('.bd-readmore-display-on').show();
                            jQuery('.blog-template-tr').addClass('bd-hidden');
                            jQuery('.alternative-color-tr').addClass('bd-hidden');
                            jQuery('.hoverbackcolor-tr').show();
                            jQuery('.design-type-tr').hide();
                            jQuery('.bd-setting-handle li').each(function () {
                                var hide = jQuery(this).attr('data-show');
                                if (hide == 'bdpslider') {
                                    jQuery(this).removeClass('clickDisable');
                                }
                                if (hide == 'bdppagination') {
                                    jQuery(this).addClass('clickDisable');
                                }
                            });
                        } else if ( template_value == 'blog-grid-box' ) {
                            jQuery('.bd-setting-handle li').each(function () {
                                var hide = jQuery(this).attr('data-show');
                                if (hide == 'bdpslider') {
                                    jQuery(this).addClass('clickDisable');
                                }
                            });
                            jQuery('.blog-template-tr').hide();
                            jQuery('.blog-columns-tr').hide();
                            jQuery('.alternative-tr').hide();
                            jQuery('.hoverbackcolor-tr').hide();
                            jQuery('.design-type-tr').hide();
                            jQuery('.blog-templatecolor-tr').show();
                            jQuery('.alternative-color-tr').hide();
                            jQuery('.bd-readmore-display-on').show();
                            jQuery('.blog-template-tr').addClass('bd-hidden');
                            jQuery('.alternative-color-tr').addClass('bd-hidden');
                            jQuery('#pagination_type option[value="load_more_btn"]').hide();
                        } else {
                            jQuery('.bd-setting-handle li').each(function () {
                                var hide = jQuery(this).attr('data-show');
                                if (hide == 'bdpslider') {
                                    jQuery(this).addClass('clickDisable');
                                }
                            });
                            jQuery('.bd-readmore-display-on').show();
                            jQuery('.hoverbackcolor-tr').hide();
                            jQuery('.blog-columns-tr').hide();
                            jQuery('.blog-template-tr').show();
                            jQuery('.blog-template-tr').removeClass('bd-hidden');
                            if (jQuery("input[name='template_alternativebackground']:checked").val() == 0) {
                                jQuery('.alternative-color-tr').show();
                                jQuery('.alternative-color-tr').removeClass('bd-hidden');
                            } else{
                                jQuery('.alternative-color-tr').hide();
                                jQuery('.alternative-color-tr').addClass('bd-hidden');
                            }
                        }
                        if (template_value == 'timeline') {
                            jQuery('.bd-setting-handle li').each(function () {
                                var hide = jQuery(this).attr('data-show');
                                if (hide == 'bdpslider') {
                                    jQuery(this).addClass('clickDisable');
                                }
                            });
                            jQuery('.blog-template-tr').hide();
                            jQuery('.alternative-color-tr').hide();
                            jQuery('.blog-templatecolor-tr').show();
                            jQuery('.design-type-tr').hide();

                            jQuery('.blog-template-tr').addClass('bd-hidden');
                            jQuery('.alternative-color-tr').addClass('bd-hidden');
                            jQuery('.blog-templatecolor-tr').removeClass('bd-hidden');
                        } else{
                            jQuery('.blog-templatecolor-tr').hide();
                            jQuery('.blog-templatecolor-tr').addClass('bd-hidden');
                        }
                        if (jQuery('#bp-apply-default-style').is(":checked")) {
                            default_data(template_value);
                        }
                        bdAltBackground();
                        jQuery("#bd_popupdiv").dialog('close');

                    }
                },
                {text: bdlite_js.close,class: 'bd_template_close',click: function () {jQuery(this).dialog("close");},}
            ],
            open: function (event, ui) {
                jQuery('#bd_popupdiv .bd-template-thumbnail').removeClass('bd_selected_template');
                jQuery('#bd_popupdiv .bd-template-thumbnail').each(function () {
                    if (jQuery(this).children('.bd-template-thumbnail-inner').children('img').attr('data-value') == template_name) {
                        jQuery(this).addClass('bd_selected_template');
                    }
                });
            }
        });
        return false;
    });

    jQuery('.bd_template_tab li a').click(function (e) {
        e.preventDefault();
        var all_template_hide = true;
        jQuery('.bd_template_tab li').removeClass('bd_current_tab');
        jQuery(this).parent('li').addClass('bd_current_tab');
        var href = jQuery(this).attr('href').replace('#', '');
        jQuery('.bd-template-thumbnail').hide();
        if (href == 'all') {
            jQuery('.bd-template-thumbnail').show();
        } else{
            jQuery('.' + href + '.bd-template-thumbnail').show();
        }
        jQuery('.bd-template-thumbnail').each(function () {
            if (jQuery(this).is(':visible')) {
                all_template_hide = false;
            }
        });
        if (all_template_hide) {jQuery('.no-template').show()} else{jQuery('.no-template').hide()}
    });
    jQuery('.slider_autoplay_tr').hide();
    if (jQuery("input[name='slider_autoplay']:checked").val() == 1) {
        jQuery('.slider_autoplay_tr').show();
    }
    jQuery("input[name='slider_autoplay']").on('change', function () {
        if (jQuery(this).val() == 1) {
            jQuery('.slider_autoplay_tr').show()
        } else{
            jQuery('.slider_autoplay_tr').hide()
        }
    });
    if (jQuery("input[name='display_slider_controls']:checked").val() == 1) {
        jQuery(".select_slider_controls_tr").show();
    } else{
        jQuery(".select_slider_controls_tr").hide();
    }
    jQuery("input[name='display_slider_controls']").change(function () {
        if (jQuery(this).val() == 1) {
            jQuery('.select_slider_controls_tr').show();
        } else{
            jQuery('.select_slider_controls_tr').hide();
        }
    });
    jQuery('.pro-feature, .pro-feature ul, .pro-feature input, .pro-feature a, .pro-feature .bdp-upload_image_button, #bd-show-preview, .pro-feature .wp-picker-container').on('click', function (e) {
        e.preventDefault();
        jQuery("#bd-advertisement-popup").dialog({
            resizable:false,draggable:false,modal:true,height:"auto",width:'auto',maxWidth:'100%',dialogClass:'bd-advertisement-ui-dialog',
            buttons: [{text: 'x',"class": 'bd-btn bd-btn-gray',click: function () {jQuery(this).dialog("close")}}],
            open: function (event, ui) {
                jQuery(this).parent().children('.ui-dialog-titlebar').hide();
            },
            hide: {effect: "fadeOut",duration: 500},
            close: function (event, ui) {
                jQuery('#bd-template-search').val('');jQuery("#bd-advertisement-popup").dialog('close');
            },
        });
    });

    jQuery('.ui-widget-overlay').on("click", function () {
        jQuery('#bd-template-search').val('');
        jQuery("#bd-advertisement-popup").dialog('close');
    });
    jQuery('.ads-pro-feature, .ads-pro-feature ul, .ads-pro-feature input, .ads-pro-feature a, .ads-pro-feature .bdp-upload_image_button').on('click', function (e) {
        e.preventDefault();
        jQuery("#bd-ads-advertisement-popup").dialog({
            resizable: false,draggable: false,modal: true,height: "auto",width: 'auto',maxWidth: '100%',dialogClass: 'bd-ads-advertisement-ui-dialog',
            buttons: [{text: 'x',"class": 'bd-btn bd-btn-gray',click: function () {jQuery(this).dialog("close")}}],
            open: function (event, ui) {
                jQuery(this).parent().children('.ui-dialog-titlebar').hide();
            },
            hide: {effect: "fadeOut",duration: 500},
            close: function (event, ui) {
                jQuery('#bd-template-search').val('');
                jQuery("#bd-ads-advertisement-popup").dialog('close');
            },
        });
    });
    jQuery('.ui-widget-overlay').on("click", function () {
        jQuery('#bd-template-search').val('');
        jQuery("#bd-ads-advertisement-popup").dialog('close');
    });
    jQuery('#bd-template-search').keyup(function () {
        var $template_name = jQuery(this).val();
        templateSearch($template_name);
    });
    jQuery('.bd-template-search-clear').on('click', function () {
        jQuery('#bd-template-search').val('');
        var $template_name = '';
        templateSearch($template_name);
    });

});

function templateSearch($template_name) {
    var template_name = jQuery('#template_name').val();
    var $template_cat = jQuery('.bd_template_tab').find('.bd_current_tab a').attr('href');
    var $all_template_hide = true;
    if ($template_name.length < 3) {
        $template_name = '';
    }
    jQuery.ajax({
        url: ajaxurl,method: 'POST',data: {'action': 'bd_template_search_result','temlate_name': $template_name, nonce: bdlite_js.nonce,},
        success: function (response) {
            jQuery('.bd-template-cover').html(response);
            var $href = $template_cat.replace('#', '');
            jQuery('.bd-template-thumbnail').hide();
            if ($href == 'all') {
                jQuery('.bd-template-thumbnail').show();
            } else{
                jQuery('.' + $href + '.bd-template-thumbnail').show();
            }
            jQuery('.bd-template-thumbnail').each(function () {
                if (jQuery(this).is(':visible')) {
                    $all_template_hide = false;
                }
            });
            if ($all_template_hide) {
                jQuery('.no-template').show();
            } else{
                jQuery('.no-template').hide();
            }
            jQuery("#bd_popupdiv div.bd-template-thumbnail .bd-popum-select a").on('click', function (e) {
                e.preventDefault();
                jQuery('#bd_popupdiv div.bd-template-thumbnail').removeClass('bd_selected_template');
                jQuery(this).parents('div.bd-template-thumbnail').addClass('bd_selected_template');
            });
        }
    });
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function default_data(template) {
    if(template == 'sallet_slider'){
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", true);
        jQuery("#display_category_1").prop("checked", false);
        jQuery("#display_tag_0").prop("checked", false);
        jQuery("#display_tag_1").prop("checked", true);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", true);
        jQuery("#display_date_1").prop("checked", false);
        jQuery("#display_comment_count_0").prop("checked", false);
        jQuery("#display_comment_count_1").prop("checked", true);
        jQuery('#template_bgcolor').iris('color', '#ffffff');
        jQuery("#template_alternativebackground_0").prop("checked", false);
        jQuery("#template_alternativebackground_1").prop("checked", true);
        jQuery('#template_ftcolor').iris('color', '#555555');
        jQuery('#template_fthovercolor').iris('color', '#3e8563');
        jQuery('#template_titlecolor').iris('color', '#0e663c');
        jQuery('#template_titlebackcolor').iris('color', '');
        jQuery('#template_titlebackcolor').val('');
        jQuery("#template_titlefontsize").val("18");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("30");
        jQuery("#content_fontsize").val("14");
        jQuery("#posts_per_page").val("10");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#555555');
        jQuery("#readmore_on_0").prop("checked", true);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", false);
        jQuery('#txtReadmoretext').val('Read More');
        jQuery('#template_readmorecolor').iris('color', '#ffffff');
        jQuery('#template_color').iris('color', '#3e8563');
        jQuery('#grid_hoverback_color').iris('color', '');
        jQuery('#template_readmorebackcolor').iris('color', '#3e8563');
        jQuery("#social_share_1").prop("checked", false);
        jQuery("#social_share_0").prop("checked", true);
        jQuery("#social_icon_style_1").prop("checked", true);
        jQuery("#social_icon_style_0").prop("checked", false);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#template_slider_columns').val('1');
        jQuery('#template_slider_columns_ipad').val('1');
        jQuery('#template_slider_columns_tablet').val('1');
        jQuery('#template_slider_columns_mobile').val('1');
        jQuery('#display_slider_controls_1').prop("checked", true);
        jQuery('#display_slider_controls_0').prop("checked", true);
        jQuery('#slider_autoplay_intervals').val("3000");
        jQuery('#slider_speed').val("300");
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();  
    }
    if(template == 'crayon_slider'){
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", false);
        jQuery("#display_category_1").prop("checked", true);
        jQuery("#display_tag_0").prop("checked", false);
        jQuery("#display_tag_1").prop("checked", true);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", true);
        jQuery("#display_date_1").prop("checked", false);
        jQuery("#display_comment_count_0").prop("checked", false);
        jQuery("#display_comment_count_1").prop("checked", true);
        jQuery('#template_bgcolor').iris('color', '#ffffff');
        jQuery("#template_alternativebackground_0").prop("checked", false);
        jQuery("#template_alternativebackground_1").prop("checked", true);
        jQuery('#template_ftcolor').iris('color', '#ffffff');
        jQuery('#template_fthovercolor').iris('color', '#ffffff');
        jQuery('#slider_design_type').val('design1');
        jQuery('#template_alterbgcolor').iris('color', '#ffffff');
        jQuery("#template_alterbgcolor").val('#ffffff');
        jQuery('#template_titlecolor').iris('color', '#ffffff');
        jQuery('#template_titlebackcolor').iris('color', '');
        jQuery('#template_titlebackcolor').val('');
        jQuery("#template_titlefontsize").val("18");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("20");
        jQuery("#content_fontsize").val("14");
        jQuery("#posts_per_page").val("10");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#ffffff');
        jQuery("#readmore_on_0").prop("checked", false);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", true);
        jQuery('#txtReadmoretext').val('Read More');
        jQuery('#template_readmorecolor').iris('color', '#ffffff');
        jQuery('#template_color').iris('color', '#000000');
        jQuery('#grid_hoverback_color').iris('color', '');
        jQuery('#template_readmorebackcolor').iris('color', '#ff00ae');
        jQuery("#social_share_1").prop("checked", false);
        jQuery("#social_share_0").prop("checked", true);
        jQuery("#social_icon_style_1").prop("checked", true);
        jQuery("#social_icon_style_0").prop("checked", false);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#template_slider_columns').val('1');
        jQuery('#template_slider_columns_ipad').val('1');
        jQuery('#template_slider_columns_tablet').val('1');
        jQuery('#template_slider_columns_mobile').val('1');
        jQuery('#display_slider_controls_1').prop("checked", true);
        jQuery('#display_slider_controls_0').prop("checked", true);
        jQuery('#slider_autoplay_intervals').val("3000");
        jQuery('#slider_speed').val("300");
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();  
    }
    if (template == 'glossary') {
        jQuery("#template_columns").val('3');
        jQuery("#template_columns_ipad").val('2');
        jQuery("#template_columns_tablet").val('2');
        jQuery("#template_columns_mobile").val('1');
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", false);
        jQuery("#display_category_1").prop("checked", true);
        jQuery("#display_tag_0").prop("checked", false);
        jQuery("#display_tag_1").prop("checked", true);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", true);
        jQuery("#display_date_1").prop("checked", false);
        jQuery("#display_comment_count_0").prop("checked", true);
        jQuery("#display_comment_count_1").prop("checked", false);
        jQuery('#template_bgcolor').iris('color', '#ffffff');

        jQuery("#template_alternativebackground_0").prop("checked", false);
        jQuery("#template_alternativebackground_1").prop("checked", true);
        jQuery('#template_ftcolor').iris('color', '#555555');
        jQuery('#template_fthovercolor').iris('color', '#777777');
        jQuery('#template_alterbgcolor').iris('color', '#ffffff');
        jQuery("#template_alterbgcolor").val('#ffffff');
        jQuery('#template_titlecolor').iris('color', '#222222');
        jQuery('#template_titlebackcolor').iris('color', '#ffffff');
        jQuery("#template_titlefontsize").val("30");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("50");
        jQuery("#content_fontsize").val("14");
        jQuery("#posts_per_page").val("5");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#444444');
        jQuery("#readmore_on_0").prop("checked", false);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", true);
        jQuery('#txtReadmoretext').val('Read More');
        jQuery('#template_readmorecolor').iris('color', '#b3322f');
        jQuery('#template_color').iris('color', '#b3322f');
        jQuery('#grid_hoverback_color').iris('color', '');
        jQuery('#template_readmorebackcolor').iris('color', '#ffffff');
        jQuery("#social_share_1").prop("checked", true);
        jQuery("#social_share_0").prop("checked", false);
        jQuery("#social_icon_style_1").prop("checked", true);
        jQuery("#social_icon_style_0").prop("checked", false);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();
    }
    if (template == 'nicy') {
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", false);
        jQuery("#display_category_1").prop("checked", true);
        jQuery("#display_tag_0").prop("checked", false);
        jQuery("#display_tag_1").prop("checked", true);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", true);
        jQuery("#display_date_1").prop("checked", false);
        jQuery("#display_comment_count_0").prop("checked", true);
        jQuery("#display_comment_count_1").prop("checked", false);
        jQuery('#template_bgcolor').iris('color', '#ffffff');
        jQuery("#template_alternativebackground_0").prop("checked", false);
        jQuery("#template_alternativebackground_1").prop("checked", true);
        jQuery('#template_ftcolor').iris('color', '#e21130');
        jQuery('#template_fthovercolor').iris('color', '#333333');
        jQuery('#template_alterbgcolor').iris('color', '#ffffff');
        jQuery("#template_alterbgcolor").val('#ffffff');
        jQuery('#template_titlecolor').iris('color', '#333333');
        jQuery('#template_titlebackcolor').iris('color', '#ffffff');
        jQuery("#template_titlefontsize").val("30");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("50");
        jQuery("#content_fontsize").val("14");
        jQuery("#posts_per_page").val("5");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#555555');
        jQuery("#readmore_on_0").prop("checked", false);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", true);
        jQuery('#txtReadmoretext').val('Read More');
        jQuery('#template_readmorecolor').iris('color', '#e21130');
        jQuery('#template_color').iris('color', '#e21130');
        jQuery('#grid_hoverback_color').iris('color', '');
        jQuery('#template_readmorebackcolor').iris('color', '#ffffff');
        jQuery("#social_share_1").prop("checked", true);
        jQuery("#social_share_0").prop("checked", false);
        jQuery("#social_icon_style_1").prop("checked", true);
        jQuery("#social_icon_style_0").prop("checked", false);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();
    }
    if (template == 'boxy-clean') {
        jQuery("#template_columns").val('3');
        jQuery("#template_columns_ipad").val('2');
        jQuery("#template_columns_tablet").val('2');
        jQuery("#template_columns_mobile").val('1');
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", false);
        jQuery("#display_category_1").prop("checked", true);
        jQuery("#display_tag_0").prop("checked", false);
        jQuery("#display_tag_1").prop("checked", true);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", true);
        jQuery("#display_date_1").prop("checked", false);
        jQuery("#display_comment_count_0").prop("checked", true);
        jQuery("#display_comment_count_1").prop("checked", false);
        jQuery('#template_bgcolor').iris('color', '#ffffff');
        jQuery("#template_alternativebackground_0").prop("checked", false);
        jQuery("#template_alternativebackground_1").prop("checked", true);
        jQuery('#template_ftcolor').iris('color', '#555555');
        jQuery('#template_fthovercolor').iris('color', '#333333');
        jQuery('#template_alterbgcolor').iris('color', '#ffffff');
        jQuery("#template_alterbgcolor").val('#ffffff');
        jQuery('#template_titlecolor').iris('color', '#333333');
        jQuery('#template_titlebackcolor').iris('color', '');
        jQuery('#template_titlebackcolor').val('');
        jQuery("#template_titlefontsize").val("30");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("50");
        jQuery("#content_fontsize").val("14");
        jQuery("#posts_per_page").val("5");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#333333');
        jQuery("#readmore_on_0").prop("checked", false);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", true);
        jQuery('#txtReadmoretext').val('Read More');
        jQuery('#template_readmorecolor').iris('color', '#e5e5e5');
        jQuery('#template_color').iris('color', '#2e6480');
        jQuery('#grid_hoverback_color').iris('color', '#eef1f2');
        jQuery('#template_readmorebackcolor').iris('color', '#2e6480');
        jQuery("#social_share_1").prop("checked", true);
        jQuery("#social_share_0").prop("checked", false);
        jQuery("#social_icon_style_1").prop("checked", true);
        jQuery("#social_icon_style_0").prop("checked", false);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();
    }
    if (template == 'classical') {
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", true);
        jQuery("#display_category_1").prop("checked", false);
        jQuery("#display_tag_0").prop("checked", true);
        jQuery("#display_tag_1").prop("checked", false);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", true);
        jQuery("#display_date_1").prop("checked", false);
        jQuery("#display_comment_count_0").prop("checked", true);
        jQuery("#display_comment_count_1").prop("checked", false);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('#template_ftcolor').iris('color', '#2a97ea');
        jQuery('#template_fthovercolor').iris('color', '#999999');
        jQuery('#template_titlecolor').iris('color', '#222222');
        jQuery('#template_titlebackcolor').iris('color', '#ffffff');
        jQuery("#template_titlefontsize").val("30");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("50");
        jQuery("#content_fontsize").val("14");
        jQuery("#posts_per_page").val("5");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#999999');
        jQuery('#template_alterbgcolor').iris('color', '');
        jQuery("#template_alterbgcolor").val('');
        jQuery("#readmore_on_0").prop("checked", false);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", true);
        jQuery('#txtReadmoretext').val('Read More');
        jQuery('#template_readmorecolor').iris('color', '#cecece');
        jQuery('#template_readmorebackcolor').iris('color', '#2e93ea');
        jQuery("#social_share_1").prop("checked", true);
        jQuery("#social_share_0").prop("checked", false);
        jQuery("#social_icon_style_1").prop("checked", true);
        jQuery("#social_icon_style_0").prop("checked", false);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();
    }
    if (template == 'lightbreeze') {
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", true);
        jQuery("#display_category_1").prop("checked", false);
        jQuery("#display_tag_0").prop("checked", true);
        jQuery("#display_tag_1").prop("checked", false);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", true);
        jQuery("#display_date_1").prop("checked", false);
        jQuery("#display_comment_count_0").prop("checked", true);
        jQuery("#display_comment_count_1").prop("checked", false);
        jQuery('#template_bgcolor').iris('color', '#ffffff');
        jQuery('#template_alterbgcolor').iris('color', '');
        jQuery("#template_alterbgcolor").val('');
        jQuery("#template_alternativebackground_0").prop("checked", false);
        jQuery("#template_alternativebackground_1").prop("checked", true);
        jQuery('#template_ftcolor').iris('color', '#1eafa6');
        jQuery('#template_fthovercolor').iris('color', '#999999');
        jQuery('#template_titlecolor').iris('color', '#222222');
        jQuery('#template_titlebackcolor').iris('color', '#ffffff');
        jQuery("#template_titlefontsize").val("30");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("50");
        jQuery("#content_fontsize").val("14");
        jQuery("#posts_per_page").val("5");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#999999');
        jQuery("#readmore_on_0").prop("checked", false);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", true);
        jQuery('#txtReadmoretext').val('Continue Reading');
        jQuery('#template_readmorecolor').iris('color', '#f1f1f1');
        jQuery('#template_readmorebackcolor').iris('color', '#1eafa6');
        jQuery("#social_share_1").prop("checked", true);
        jQuery("#social_share_0").prop("checked", false);
        jQuery("#social_icon_style_1").prop("checked", false);
        jQuery("#social_icon_style_0").prop("checked", true);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();
    }
    if (template == 'spektrum') {
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", true);
        jQuery("#display_category_1").prop("checked", false);
        jQuery("#display_tag_0").prop("checked", true);
        jQuery("#display_tag_1").prop("checked", false);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", true);
        jQuery("#display_date_1").prop("checked", false);
        jQuery("#display_comment_count_0").prop("checked", true);
        jQuery("#display_comment_count_1").prop("checked", false);
        jQuery('#template_ftcolor').iris('color', '#2d7fc1');
        jQuery('#template_fthovercolor').iris('color', '#444444');
        jQuery('#template_alterbgcolor').iris('color', '');
        jQuery("#template_alterbgcolor").val('');
        jQuery('#template_titlecolor').iris('color', '#222222');
        jQuery('#template_titlebackcolor').iris('color', '#ffffff');
        jQuery("#template_titlefontsize").val("30");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("50");
        jQuery("#content_fontsize").val("14");
        jQuery("#posts_per_page").val("5");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#444444');
        jQuery("#readmore_on_0").prop("checked", false);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", true);
        jQuery('#txtReadmoretext').val('View More');
        jQuery('#template_readmorecolor').iris('color', '#eaeaea');
        jQuery('#template_readmorebackcolor').iris('color', '#2d7fc1');
        jQuery("#social_share_1").prop("checked", true);
        jQuery("#social_share_0").prop("checked", false);
        jQuery("#social_icon_style_1").prop("checked", true);
        jQuery("#social_icon_style_0").prop("checked", false);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();
    }
    if (template == 'evolution') {
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", true);
        jQuery("#display_category_1").prop("checked", false);
        jQuery("#display_tag_0").prop("checked", true);
        jQuery("#display_tag_1").prop("checked", false);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", true);
        jQuery("#display_date_1").prop("checked", false);
        jQuery("#display_comment_count_0").prop("checked", true);
        jQuery("#display_comment_count_1").prop("checked", false);
        jQuery('#template_bgcolor').iris('color', '#ffffff');
        jQuery("#template_alternativebackground_0").prop("checked", false);
        jQuery("#template_alternativebackground_1").prop("checked", true);
        jQuery('#template_ftcolor').iris('color', '#2e6480');
        jQuery('#template_fthovercolor').iris('color', '#777777');
        jQuery('#template_alterbgcolor').iris('color', '#ffffff');
        jQuery("#template_alterbgcolor").val('#ffffff');
        jQuery('#template_titlecolor').iris('color', '#222222');
        jQuery('#template_titlebackcolor').iris('color', '#ffffff');
        jQuery('#template_color').iris('color', '#FFFFFF');
        jQuery("#template_titlefontsize").val("30");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("50");
        jQuery("#content_fontsize").val("14");
        jQuery("#posts_per_page").val("5");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#777777');
        jQuery("#readmore_on_0").prop("checked", false);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", true);
        jQuery('#txtReadmoretext').val('Read More');
        jQuery('#template_readmorecolor').iris('color', '#e5e5e5');
        jQuery('#template_readmorebackcolor').iris('color', '#2e6480');
        jQuery("#social_share_1").prop("checked", true);
        jQuery("#social_share_0").prop("checked", false);
        jQuery("#social_icon_style_1").prop("checked", true);
        jQuery("#social_icon_style_0").prop("checked", false);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();
    }
    if (template == 'timeline') {
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", true);
        jQuery("#display_category_1").prop("checked", false);
        jQuery("#display_tag_0").prop("checked", true);
        jQuery("#display_tag_1").prop("checked", false);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", true);
        jQuery("#display_date_1").prop("checked", false);
        jQuery("#display_comment_count_0").prop("checked", true);
        jQuery("#display_comment_count_1").prop("checked", false);
        jQuery('#template_color').iris('color', '#db4c59');
        jQuery('#template_ftcolor').iris('color', '#db4c59');
        jQuery('#template_fthovercolor').iris('color', '#444444');
        jQuery('#template_alterbgcolor').iris('color', '');
        jQuery("#template_alterbgcolor").val('');
        jQuery('#template_titlecolor').iris('color', '#222222');
        jQuery('#template_titlebackcolor').iris('color', '#ffffff');
        jQuery("#template_titlefontsize").val("30");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("50");
        jQuery("#content_fontsize").val("14");
        jQuery("#posts_per_page").val("5");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#444444');
        jQuery("#readmore_on_0").prop("checked", false);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", true);
        jQuery('#txtReadmoretext').val('Read More');
        jQuery('#template_readmorecolor').iris('color', '#f1f1f1');
        jQuery('#template_readmorebackcolor').iris('color', '#db4c59');
        jQuery("#social_share_1").prop("checked", true);
        jQuery("#social_share_0").prop("checked", false);
        jQuery("#social_icon_style_1").prop("checked", false);
        jQuery("#social_icon_style_0").prop("checked", true);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();
    }
    if (template == 'news') {
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", true);
        jQuery("#display_category_1").prop("checked", false);
        jQuery("#display_tag_0").prop("checked", true);
        jQuery("#display_tag_1").prop("checked", false);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", true);
        jQuery("#display_date_1").prop("checked", false);
        jQuery("#display_comment_count_0").prop("checked", true);
        jQuery("#display_comment_count_1").prop("checked", false);
        jQuery('#template_bgcolor').iris('color', '#ffffff');
        jQuery("#template_alternativebackground_0").prop("checked", false);
        jQuery("#template_alternativebackground_1").prop("checked", true);
        jQuery('#template_ftcolor').iris('color', '#e84059');
        jQuery('#template_fthovercolor').iris('color', '#444444');
        jQuery('#template_alterbgcolor').iris('color', '');
        jQuery("#template_alterbgcolor").val('');
        jQuery('#template_titlecolor').iris('color', '#333333');
        jQuery('#template_titlebackcolor').iris('color', '#ffffff');
        jQuery('#template_color').iris('color', '#ffffff');
        jQuery("#template_titlefontsize").val("30");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("20");
        jQuery("#content_fontsize").val("14");
        jQuery("#posts_per_page").val("5");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#444444');
        jQuery("#readmore_on_0").prop("checked", false);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", true);
        jQuery('#txtReadmoretext').val('Continue Reading');
        jQuery('#template_readmorecolor').iris('color', '#f1f1f1');
        jQuery('#template_readmorebackcolor').iris('color', '#e84059');
        jQuery("#social_share_1").prop("checked", true);
        jQuery("#social_share_0").prop("checked", false);
        jQuery("#social_icon_style_1").prop("checked", true);
        jQuery("#social_icon_style_0").prop("checked", false);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();
    }
    if (template == 'media-grid') {
        jQuery("#template_columns").val('2');
        jQuery("#template_columns_ipad").val('2');
        jQuery("#template_columns_tablet").val('2');
        jQuery("#template_columns_mobile").val('1');
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", true);
        jQuery("#display_category_1").prop("checked", false);
        jQuery("#display_tag_0").prop("checked", false);
        jQuery("#display_tag_1").prop("checked", true);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", true);
        jQuery("#display_date_1").prop("checked", false);
        jQuery("#display_comment_count_0").prop("checked", true);
        jQuery("#display_comment_count_1").prop("checked", false);
        jQuery('#template_bgcolor').iris('color', '#ffffff');
        jQuery("#template_alternativebackground_0").prop("checked", false);
        jQuery("#template_alternativebackground_1").prop("checked", true);
        jQuery('#template_ftcolor').iris('color', '#a49538');
        jQuery('#template_fthovercolor').iris('color', '#555555');
        jQuery('#template_alterbgcolor').iris('color', '#ffffff');
        jQuery("#template_alterbgcolor").val('#ffffff');
        jQuery('#template_titlecolor').iris('color', '#333333');
        jQuery('#template_titlebackcolor').iris('color', '');
        jQuery('#template_titlebackcolor').val('');
        jQuery("#template_titlefontsize").val("30");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("50");
        jQuery("#content_fontsize").val("14");
        jQuery("#posts_per_page").val("5");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#333333');
        jQuery("#readmore_on_0").prop("checked", false);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", true);
        jQuery('#txtReadmoretext').val('Read More');
        jQuery('#template_readmorecolor').iris('color', '#e5e5e5');
        jQuery('#template_color').iris('color', '#a49538');
        jQuery('#grid_hoverback_color').iris('color', '#caccce');
        jQuery('#template_readmorebackcolor').iris('color', '#a49538');
        jQuery("#social_share_1").prop("checked", true);
        jQuery("#social_share_0").prop("checked", false);
        jQuery("#social_icon_style_1").prop("checked", true);
        jQuery("#social_icon_style_0").prop("checked", false);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();
    }
    if (template == 'blog-carousel') {
        jQuery('#posts_per_page').val('6');
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", true);
        jQuery("#display_category_1").prop("checked", false);
        jQuery("#display_tag_0").prop("checked", false);
        jQuery("#display_tag_1").prop("checked", true);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", false);
        jQuery("#display_date_1").prop("checked", true);
        jQuery("#display_comment_count_0").prop("checked", true);
        jQuery("#display_comment_count_1").prop("checked", false);
        jQuery('#template_bgcolor').iris('color', '#ffffff');
        jQuery("#template_alternativebackground_0").prop("checked", false);
        jQuery("#template_alternativebackground_1").prop("checked", true);
        jQuery('#template_ftcolor').iris('color', '#000000');
        jQuery('#template_fthovercolor').iris('color', '#555555');
        jQuery('#template_alterbgcolor').iris('color', '#000000');
        jQuery("#template_alterbgcolor").val('#000000');
        jQuery('#template_titlecolor').iris('color', '#000000');
        jQuery('#template_titlebackcolor').iris('color', '');
        jQuery('#template_titlebackcolor').val('');
        jQuery("#template_titlefontsize").val("24");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("0");
        jQuery("#content_fontsize").val("14");
        jQuery("#posts_per_page").val("5");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#000000');
        jQuery("#readmore_on_0").prop("checked", false);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", true);
        jQuery('#txtReadmoretext').val('Read More');
        jQuery('#template_readmorecolor').iris('color', '#FFFFFF');
        jQuery('#template_color').iris('color', '#f7f7f7');
        jQuery('#grid_hoverback_color').iris('color', '#FFFFFF');
        jQuery('#template_readmorebackcolor').iris('color', '#000000');
        jQuery("#social_share_1").prop("checked", false);
        jQuery("#social_share_0").prop("checked", true);
        jQuery("#social_icon_style_1").prop("checked", true);
        jQuery("#social_icon_style_0").prop("checked", false);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#template_slider_columns').val('3');
        jQuery('#template_slider_columns_ipad').val('3');
        jQuery('#template_slider_columns_tablet').val('3');
        jQuery('#template_slider_columns_mobile').val('1');
        jQuery('#display_slider_controls_1').prop("checked", true);
        jQuery('#display_slider_controls_0').prop("checked", true);
        jQuery('#slider_autoplay_intervals').val("3000");
        jQuery('#slider_speed').val("300");
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();
    }
    if (template == 'blog-grid-box') {
        jQuery("#display_sticky_0").prop("checked", false);
        jQuery("#display_sticky_1").prop("checked", true);
        jQuery("#display_category_0").prop("checked", true);
        jQuery("#display_category_1").prop("checked", false);
        jQuery("#display_tag_0").prop("checked", false);
        jQuery("#display_tag_1").prop("checked", true);
        jQuery("#display_author_0").prop("checked", true);
        jQuery("#display_author_1").prop("checked", false);
        jQuery("#display_date_0").prop("checked", true);
        jQuery("#display_date_1").prop("checked", false);
        jQuery("#display_comment_count_0").prop("checked", false);
        jQuery("#display_comment_count_1").prop("checked", true);
        jQuery('#template_bgcolor').iris('color', '#ffffff');
        jQuery("#template_alternativebackground_0").prop("checked", false);
        jQuery("#template_alternativebackground_1").prop("checked", true);
        jQuery('#template_ftcolor').iris('color', '#000000');
        jQuery('#template_fthovercolor').iris('color', '#555555');
        jQuery('#template_alterbgcolor').iris('color', '#ffffff');
        jQuery("#template_alterbgcolor").val('#ffffff');
        jQuery('#template_titlecolor').iris('color', '#000000');
        jQuery('#template_titlebackcolor').iris('color', '');
        jQuery('#template_titlebackcolor').val('');
        jQuery("#template_titlefontsize").val("20");
        jQuery("#rss_use_excerpt_0").prop("checked", false);
        jQuery("#rss_use_excerpt_1").prop("checked", true);
        jQuery("#display_html_tags_0").prop("checked", true);
        jQuery("#display_html_tags_1").prop("checked", false);
        jQuery('.excerpt_length').show();
        jQuery('.read_more_on').show();
        jQuery('.read_more_text').show();
        jQuery('.read_more_text_color').show();
        jQuery('.read_more_text_background').show();
        jQuery("#txtExcerptlength").val("15");
        jQuery("#content_fontsize").val("18");
        jQuery("#posts_per_page").val("5");
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#template_contentcolor').iris('color', '#000000');
        jQuery("#readmore_on_0").prop("checked", false);
        jQuery("#readmore_on_1").prop("checked", false);
        jQuery("#readmore_on_2").prop("checked", true);
        jQuery('#txtReadmoretext').val('Read More');
        jQuery('#template_readmorecolor').iris('color', '#FFFFFF');
        jQuery('#template_color').iris('color', '#FFFFFF');
        jQuery('#template_readmorebackcolor').iris('color', '#000000');
        jQuery("#social_share_1").prop("checked", false);
        jQuery("#social_share_0").prop("checked", true);
        jQuery("#social_icon_style_1").prop("checked", true);
        jQuery("#social_icon_style_0").prop("checked", false);
        jQuery("#facebook_link_0").prop("checked", true);
        jQuery("#facebook_link_1").prop("checked", false);
        jQuery("#twitter_link_0").prop("checked", true);
        jQuery("#twitter_link_1").prop("checked", false);
        jQuery("#linkedin_link_0").prop("checked", true);
        jQuery("#linkedin_link_1").prop("checked", false);
        jQuery("#pinterest_link_0").prop("checked", true);
        jQuery("#pinterest_link_1").prop("checked", false);
        jQuery("#instagram_link_0").prop("checked", true);
        jQuery("#instagram_link_1").prop("checked", false);
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('.buttonset').buttonset();
    }
    if (template == 'ticker') {
        jQuery('#posts_per_page').val('10');
        jQuery("#template_category").val("");
        jQuery("#template_tags").val("");
        jQuery("#template_authors").val("");
        jQuery('#bdp_blog_order_by').val('date');
        jQuery('#bdp_blog_order_asc').prop('checked', false);
        jQuery('#bdp_blog_order_desc').prop('checked', true);
        jQuery('#template_color').iris('color', '#2096cd');
        jQuery('#template_labeltextcolor').val('#FFFFFF');
        jQuery('#ticker_label').val('Latest Blog');
        jQuery('#template_titlecolor').iris('color','#2096cd');
        jQuery('.buttonset').buttonset();
    }
    // jQuery('.chosen-select option').prop('selected', false).trigger('chosen:updated');
    jQuery('.chosen-select option').trigger("chosen:updated");
}
jQuery('document').ready(function () {
    bdAltBackground();
});
function bdAltBackground() {
    jQuery('.postbox').each(function() {
        jQuery(this).find('ul.bd-settings > li').removeClass('bd-gray');jQuery(this).find('ul.bd-settings > li:not(.bd-hidden):odd').addClass('bd-gray');
    });
}
function bd_show_hide_permission() {
    jQuery('.bd_permission_cover').slideToggle();
}
function bd_submit_optin(options) {
    result = {};
    result.action = 'bd_submit_optin';
    result.email = jQuery('#bd_admin_email').val();
    result.type = options;
    result.nonce = bdlite_js.nonce;
    if (options == 'submit') {
        if (jQuery('input#bd_agree_gdpr').is(':checked')) {
            jQuery.ajax({url: ajaxurl,type:'POST',data:result,error:function(){},success:function(){window.location.href="admin.php?page=bd_getting_started"},complete:function(){window.location.href="admin.php?page=bd_getting_started"}});
        }
        else{
            jQuery('.bd_agree_gdpr_lbl').css('color', '#ff0000');
        }
    }
    else if (options == 'deactivate') {
        if (jQuery('input#bd_agree_gdpr_deactivate').is(':checked')) {
            var bd_plugin_admin = jQuery('.documentation_bd_plugin').closest('div').find('.deactivate').find('a');
            result.selected_option_de = jQuery('input[name=sol_deactivation_reasons_bd]:checked', '#frmDeactivationbd').val();
            result.selected_option_de_id = jQuery('input[name=sol_deactivation_reasons_bd]:checked', '#frmDeactivationbd').attr("id");
            result.selected_option_de_text = jQuery("label[for='" + result.selected_option_de_id + "']").text();
            result.selected_option_de_other = jQuery('.sol_deactivation_reason_other_bd').val();
            jQuery.ajax({
                url: ajaxurl,type: 'POST',data: result,error: function () { },
                success: function(){window.location.href = bd_plugin_admin.attr('href')},
                complete: function(){window.location.href = bd_plugin_admin.attr('href')}
            });
        }
        else{jQuery('.bd_agree_gdpr_lbl').css('color','#ff0000')}
    }
    else{
        jQuery.ajax({url:ajaxurl,type:'POST',data:result, error:function(){},success:function () {window.location.href = "admin.php?page=bd_getting_started"},complete:function(){window.location.href="admin.php?page=bd_getting_started"}});
    }
}
function clickDisable() {
    jQuery(document).on('click', '.clickDisable', function (e) {
        e.stopPropagation();e.preventDefault();e.stopImmediatePropagation();
        return false;
    });
}

jQuery(document).ready(function() {
    if (jQuery("select[name='template_slider_effect']").val() == "fade") {
        jQuery(".slider_columns_tr").hide();
        jQuery(".slider_scroll_tr").hide();
    } else {
        jQuery(".slider_columns_tr").show();
        jQuery(".slider_scroll_tr").show();
    }
    jQuery("select[name='template_slider_effect']").change(function () {
    if (jQuery(this).val() == "fade") {
        jQuery(".slider_columns_tr").hide();
        jQuery(".slider_scroll_tr").hide();
    } else {
        jQuery(".slider_columns_tr").show();
        jQuery(".slider_scroll_tr").show();
    }
    });

    jQuery("select[name='bdp_blog_order_by']").change(function () {
        if (jQuery(this).val() == "" || jQuery(this).val() == "rand") {
            jQuery("div.blg_order").hide();
        } else {
            jQuery("div.blg_order").show();
        }
    });
    if ( jQuery("select[name='bdp_blog_order_by']").val() == "rand" ) {
        jQuery("div.blg_order").hide();
    } else {
        jQuery("div.blg_order").show();
    }
    if( 'classical' == jQuery('#template_name').val() ) {
        jQuery('.blog-templatecolor-tr').hide();
    }
    if( 'spektrum' == jQuery('#template_name').val() ) {
        jQuery('.blog-templatecolor-tr').hide();
    }
    if (jQuery("input[name='template_alternativebackground']:checked").val() == 0) {
        jQuery('.alternative-color-tr').show();
        jQuery('.alternative-color-tr').removeClass('bd-hidden');
    } else{
        jQuery('.alternative-color-tr').hide();
        jQuery('.alternative-color-tr').addClass('bd-hidden');
    }
});