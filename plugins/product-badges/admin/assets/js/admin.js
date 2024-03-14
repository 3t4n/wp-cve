jQuery(document).ready(function($) {

    // Tabs
    jQuery(document).on('click', 'ul.tabs li', function() {
        var tab_id = jQuery(this).attr('data-tab');
        jQuery(this).parent().find('li').removeClass('current');
        jQuery(this).parent().parent().find('.tab-content').removeClass('current');

        jQuery(this).addClass('current');
        jQuery("#" + tab_id).addClass('current');
    });

    // Horizontal tabs
    jQuery(document).on('click', 'ul.horizontal-tabs li', function() {
        var tab_id = jQuery(this).attr('data-tab');
        jQuery(this).parent().find('li').removeClass('current');
        jQuery(this).parent().parent().find('.horizontal-tabs-content').find('.h-tab-content').removeClass('current');

        jQuery(this).addClass('current');
        jQuery("#" + tab_id).addClass('current');
    });

    // Set defaults
    if( jQuery('.post-new-php').length ) {
        lion_badges_admin_set_defaults();
    }

    // Color picker
    jQuery('.lion-badges-color-picker').wpColorPicker({
        change: function(event, ui) {
            var element = event.target;
            var color = ui.color.toString();

            jQuery(this).attr('value', color).trigger('change');
        }
    });

    jQuery('.js-lion-badges-get-products').select2({
        ajax: {
            url: ajaxurl,
            method: 'POST',
            dataType: 'json',
            data: function(params) {
                var data = {
                    action: 'lion_badges_get_products',
                    search: params.term,
                    security: jQuery('#lion_badges_get_products_nonce').val()
                }

                return data;
            },
            processResults: function(data, page) {
                return {
                    results: data
                };
            }
        },
        minimumInputLength: 2
    });

    jQuery('.js-lion-badges-get-product-categories').select2({
        ajax: {
            url: ajaxurl,
            method: 'POST',
            dataType: 'json',
            data: function(params) {
                var data = {
                    action: 'lion_badges_get_product_categories',
                    search: params.term,
                    security: jQuery('#lion_badges_get_product_categories_nonce').val()
                }

                return data;
            },
            processResults: function(data, page) {
                return {
                    results: data
                };
            }
        },
        minimumInputLength: 2
    });

    lion_badges_admin_input_events();
});

function lion_badges_admin_set_defaults() {

    var defaults = {
        'shape' : 'circle',
        'shape_style' : {
            'background' : '#f6533e',
            'height' : '100',
            'width' : '100',
        },
        'text' : {
            'text' : 'SALE',
            'font_family' : 'Arial',
            'font_size' : '21',
            'color' : '#FFFFFF',
            'text_align' : 'center',
            'padding_top' : '35',
            'padding_right' : '0',
            'padding_bottom' : '0',
            'padding_left' : '0'
        },
        'position' : {
            'top' : '0',
            'right' : '0',
            'left' : '0',
        }
    };

    // Preview
    jQuery('#badge-preview-inner').find('div').first().attr('id', defaults.shape);
    jQuery("input[name='badge[shape][badge]'][value='" + defaults.shape + "']").prop("checked","true");

    jQuery('#badge-preview-inner').find('div').first().css('background', defaults.shape_style.background);
    jQuery('#badge-preview-inner').find('div').css('height', defaults.shape_style.height+'px');
    jQuery('#badge-preview-inner').find('div').css('width', defaults.shape_style.width+'px');

    jQuery('#badge-preview-text').text(defaults.text.text);
    jQuery('#badge-preview-text').css('font-family', defaults.text.font_family);
    jQuery('#badge-preview-text').css('font-size', defaults.text.font_size+'px');
    jQuery('#badge-preview-text').css('color', defaults.text.color);
    jQuery('#badge-preview-text').css('text-align', defaults.text.text_align);

    jQuery('#badge-preview-text').css('padding-top', defaults.text.padding_top + 'px');
    jQuery('#badge-preview-text').css('padding-right', defaults.text.padding_right + 'px');
    jQuery('#badge-preview-text').css('padding-bottom', defaults.text.padding_bottom + 'px');
    jQuery('#badge-preview-text').css('padding-left', defaults.text.padding_left + 'px');

    jQuery('#badge-preview-inner').find('div').css('top', defaults.shape_style.top + 'px');
    jQuery('#badge-preview-inner').find('div').css('left', defaults.shape_style.left + 'px');

    // Vals
    jQuery('.js-shape_style-background').val(defaults.shape_style.background);
    jQuery('.js-shape-style-size').val(defaults.shape_style.height);

    jQuery('.js-text-text').val(defaults.text.text);
    jQuery('.js-text-font-family').val(defaults.text.font_family.toLowerCase());
    jQuery('.js-text-font-size').val(defaults.text.font_size);
    jQuery('.js-text-color').val(defaults.text.color);
    jQuery('.js-text-align').val(defaults.text.text_align);
    jQuery('.js-text-padding-top').val(defaults.text.padding_top);
    jQuery('.js-text-padding-right').val(defaults.text.padding_right);
    jQuery('.js-text-padding-bottom').val(defaults.text.padding_bottom);
    jQuery('.js-text-padding-left').val(defaults.text.padding_left);

    jQuery('.js-position-top').val(defaults.position.top);
    jQuery('.js-position-right').val(defaults.position.right);
    jQuery('.js-position-left').val(defaults.position.left);
}

function lion_badges_admin_input_events() {
    // Add a class for chosen shape
    jQuery('input[name="badge[shape][badge]"]:checked').parent().addClass('checked');

    // Shape tab
    jQuery(document).on('click', '.badge-row', function() {
        var row = jQuery(this);
        var radio = jQuery(row).find('input[type="radio"]');

        jQuery('.badge-row').removeClass('checked');
        jQuery(row).addClass('checked');

        radio.attr('checked', true);

        jQuery('#badge-preview-inner').find('div').first().attr('id', jQuery(row).find('input[type="radio"]').val());
    });

    // Update value fields
    jQuery(document).on('input', '.range', function() {
        jQuery(this).parent().find('.range-val').val(jQuery(this).val());
    });

    // Shape style tab
    jQuery(document).on('change', '.js-shape_style-background', function() {
        jQuery('#badge-preview-inner').find('div').first().css('background', jQuery(this).val());
    });

    jQuery(document).on('input', '.js-shape-style-size', function() {
        jQuery('#badge-preview-inner').find('div').css('height', jQuery(this).val() + 'px');
        jQuery('#badge-preview-inner').find('div').css('width', jQuery(this).val() + 'px');
    });

    // Text tab
    jQuery(document).on('keyup', '.js-text-text', function() {
        jQuery('#badge-preview-text').text(jQuery(this).val());
    });

    jQuery(document).on('change', '.js-text-font-family', function() {
        var optionSelected = jQuery("option:selected", this);

        if ( jQuery(this).val() == 'default' ) {
            jQuery('#badge-preview-text').css('font-family', '');
        } else {
            jQuery('#badge-preview-text').css('font-family', optionSelected.text());
        }
    });

    jQuery(document).on('input', '.js-text-font-size', function() {
        jQuery('#badge-preview-text').css('font-size', jQuery(this).val() + 'px');
    });

    jQuery(document).on('change', '.js-text-color', function() {
        jQuery('#badge-preview-text').css('color', jQuery(this).val());
    });

    jQuery(document).on('change', '.js-text-align', function() {
        jQuery('#badge-preview-text').css('text-align', jQuery(this).val());
    });

    jQuery(document).on('input', '.js-text-padding-top', function() {
        jQuery('#badge-preview-text').css('padding-top', jQuery(this).val() + 'px');
    });

    jQuery(document).on('input', '.js-text-padding-right', function() {
        jQuery('#badge-preview-text').css('padding-right', jQuery(this).val() + 'px');
    });

    jQuery(document).on('input', '.js-text-padding-bottom', function() {
        jQuery('#badge-preview-text').css('padding-bottom', jQuery(this).val() + 'px');
    });

    jQuery(document).on('input', '.js-text-padding-left', function() {
        jQuery('#badge-preview-text').css('padding-left', jQuery(this).val() + 'px');
    });

    // Position tab
    jQuery(document).on('input', '.js-position-top', function() {
        jQuery('#badge-preview-inner').find('div').css('bottom', '');
        jQuery('#badge-preview-inner').find('div').css('top', jQuery(this).val() + 'px');
    });

    jQuery(document).on('input', '.js-position-right', function() {
        jQuery('#badge-preview-inner').find('div').css('left', '');
        jQuery('.js-position-left').val('0');

        if ( jQuery(this).val() == '0' ) {
            jQuery('#badge-preview-inner').find('div').css('right', '');
        } else {
            jQuery('#badge-preview-inner').find('div').css('right', jQuery(this).val() + 'px');
        }
    });

    jQuery(document).on('input', '.js-position-left', function() {
        jQuery('#badge-preview-inner').find('div').css('right', '');
        jQuery('.js-position-right').val('0');

        jQuery('#badge-preview-inner').find('div').css('left', jQuery(this).val() + 'px');
    });
}