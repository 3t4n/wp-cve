
jQuery(document).ready(function () {
    'use strict';
    function handleDropdown() {
        jQuery('.vi-ui.dropdown').unbind().dropdown();
        jQuery('.woo-sctr-time-separator').dropdown({
            onChange: function (val) {
                switch (val) {
                    case 'dot':
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-time-separator').html('.');
                        break;
                    case 'comma':
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-time-separator').html(',');
                        break;
                    case 'colon':
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-time-separator').html(':');
                        break;
                    default:
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-time-separator').html('');
                }
            }
        });
        jQuery('.woo-sctr-count-style').dropdown({
            onChange: function (val) {
                switch (val) {
                    case '1':
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-date-text').html('days');
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-hour-text').html('hrs');
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-minute-text').html('mins');
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-second-text').html('secs');
                        break;
                    case '2':
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-date-text').html('days');
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-hour-text').html('hours');
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-minute-text').html('minutes');
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-second-text').html('seconds');
                        break;
                    case '3':
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-date-text').html('');
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-hour-text').html('');
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-minute-text').html('');
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-second-text').html('');
                        break;
                    default:
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-date-text').html('d');
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-hour-text').html('h');
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-minute-text').html('m');
                        jQuery(this).parent().parent().parent().find('.woo-sctr-shortcode-countdown-second-text').html('s');
                }
            }
        });
        jQuery('.woo-sctr-progress-bar-order-status').dropdown({
            onChange: function (val) {
                jQuery(this).parent().parent().find('.woo-sctr-progress-bar-order-status-hidden').val(val);
            }
        })
        jQuery('.woo-sctr-datetime-unit-position').dropdown({
            onChange: function (val) {
                if(val==='top'){
                    jQuery(this).parent().parent().parent().find('.woo-sctr-datetime-unit-position-top').show();
                    jQuery(this).parent().parent().parent().find('.woo-sctr-datetime-unit-position-bottom').hide();
                }else{
                    jQuery(this).parent().parent().parent().find('.woo-sctr-datetime-unit-position-top').hide();
                    jQuery(this).parent().parent().parent().find('.woo-sctr-datetime-unit-position-bottom').show();
                }

            }
        })
    }

    handleDropdown();

    function copyShortcode() {
        jQuery('.woo-sctr-short-description-copy-shortcode').unbind().on('click', function (event) {
            var value = '[sales_countdown_timer id="' + jQuery(this).parent().parent().parent().find('.woo-sctr-id').val() + '"]';
            var $temp = jQuery("<input>");
            jQuery("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            alert('Shortcode copied to clipboard: ' + value);
            event.stopPropagation();
        });
    }

    copyShortcode();


    // change name
    function handleNameChange() {
        jQuery('.woo-sctr-name').unbind().on('keyup', function () {
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-accordion-name').html(jQuery(this).val());
        })
    }

    handleNameChange();

    // handle checkbox to save
    function handleCheckbox() {
        jQuery('input[type="checkbox"]').unbind().not('.woo-sctr-display-type-checkbox').on('click', function () {
            if (jQuery(this).prop('checked')) {
                jQuery(this).parent().find('input[type="hidden"]').val('1');
            } else {
                jQuery(this).parent().find('input[type="hidden"]').val('');
            }
        });
        jQuery('.woo-sctr-display-type-checkbox').on('click', function () {
            if (jQuery(this).prop('checked')) {
                jQuery(this).parent().parent().parent().parent().parent().parent().parent().find('.woo-sctr-display-type').val(jQuery(this).val());
            } else {
                jQuery(this).parent().parent().parent().parent().parent().parent().parent().find('.woo-sctr-display-type').val(jQuery(this).val());
            }
        })
    }

    handleCheckbox();
    // duplicate item
    duplicateItem();

    function duplicateItem() {
        jQuery('.woo-sctr-button-edit-duplicate').unbind().on('click', function (e) {
            e.stopPropagation();
            let new_id = jQuery('.woo-sctr-accordion-wrap').length;
            let inline_style = jQuery('#sales-countdown-timer-admin-inline-css').html();
            var current = jQuery(this).parent().parent().parent();
            var newRow = current.clone();
            newRow.find('.vi-ui.checkbox').unbind().checkbox();
            for (let i = 0; i < newRow.find('.vi-ui.dropdown').length; i++) {
                let selected = current.find('.vi-ui.dropdown').eq(i).dropdown('get value');
                newRow.find('.vi-ui.dropdown').eq(i).dropdown('set selected', selected);
            }
            inline_style += '.woo-sctr-accordion-wrap[data-accordion_id="' + new_id + '"] .woo-sctr-shortcode-countdown-style-4 .woo-sctr-shortcode-countdown-1 .woo-sctr-progress-circle:after{background:' + newRow.find('.woo-sctr-datetime-value-bg-color').val() + ';}';
            jQuery('#sales-countdown-timer-admin-inline-css').html(inline_style);
            var $now = Date.now();
            newRow.attr('data-accordion_id', new_id);
            newRow.find('.woo-sctr-id').val($now);
            newRow.find('.woo-sctr-shortcode-text').html('[sales_countdown_timer id="' + $now + '"]');

            newRow.find('.iris-picker').remove();
            newRow.find('.color-picker').iris({
                change: function (event, ui) {
                    jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                },
                hide: true,
                border: true
            }).on('click', function (ev) {
                jQuery('.iris-picker').hide();
                jQuery(this).parent().find('.iris-picker').show();
                ev.stopPropagation();
            });
            newRow.insertAfter(jQuery(this).parent().parent().parent());
            duplicateItem();
            removeItem();
            handleCheckbox();
            handleNameChange();
            copyShortcode();
            handleDropdown();
            handleColorPicker();
            handleFontChange();
            jQuery('.vi-ui.accordion')
                .accordion('refresh')
            ;
            e.stopPropagation();
        });

    }

    // remove item
    function removeItem() {
        jQuery('.woo-sctr-button-edit-remove').unbind().on('click', function (e) {
            if (jQuery('.woo-sctr-button-edit-remove').length === 1) {
                alert('You can not remove the last item.');
                return false;
            }
            if (confirm("Would you want to remove this?")) {
                jQuery(this).parent().parent().parent().remove();
            }
            e.stopPropagation();
        });
    }

    removeItem();

    function handleColorPicker() {
        jQuery('.color-picker').unbind().iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
            },
            hide: true,
            border: true
        }).on('click', function (e) {
            jQuery('.iris-picker').hide();
            jQuery(this).parent().find('.iris-picker').show();
            e.stopPropagation();
        });
        jQuery('.woo-sctr-countdown-timer-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap:not(.woo-sctr-shortcode-wrap-wrap-inline) .woo-sctr-shortcode-countdown-1').css({color: ui.color.toString()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-inline').css({color: ui.color.toString()})
            }
        }).on('keyup', function () {
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap:not(.woo-sctr-shortcode-wrap-wrap-inline) .woo-sctr-shortcode-countdown-1').css({'color': jQuery(this).val()});
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-inline').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-timer-bg-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap:not(.woo-sctr-shortcode-wrap-wrap-inline) .woo-sctr-shortcode-countdown-1').css({backgroundColor: ui.color.toString()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-inline').css({backgroundColor: ui.color.toString()})
            }
        }).on('keyup', function () {
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap:not(.woo-sctr-shortcode-wrap-wrap-inline) .woo-sctr-shortcode-countdown-1').css({'background': jQuery(this).val()});
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-inline').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-timer-border-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap:not(.woo-sctr-shortcode-wrap-wrap-inline) .woo-sctr-shortcode-countdown-1').css({'border': '1px solid ' + ui.color.toString()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-inline').css({'border': '1px solid ' + ui.color.toString()})
            }
        }).on('keyup', function () {
            if (jQuery(this).val() !== '') {
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap:not(.woo-sctr-shortcode-wrap-wrap-inline) .woo-sctr-shortcode-countdown-1').css({'border': '1px solid ' + jQuery(this).val()})
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-inline').css({'border': '1px solid ' + jQuery(this).val()})
            } else {
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-1').css({'border': 'none'});
                jQuery(this).parent().find('.color-picker').css({'background': 'none'});
            }
        });
        jQuery('.woo-sctr-countdown-timer-item-border-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-1 .woo-sctr-shortcode-countdown-unit').css({'border': '1px solid ' + ui.color.toString()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-2 .woo-sctr-shortcode-countdown-value').css({'border': '1px solid ' + ui.color.toString()})
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-4 .woo-sctr-value-bar').css({'border-color': ui.color.toString()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-4 .woo-sctr-first50-bar').css({'background-color': ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-4 .woo-sctr-value-bar').css({'border-color': jQuery(this).val()})
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-4 .woo-sctr-first50-bar').css({'background-color': jQuery(this).val()})
            if (jQuery(this).val() !== '') {
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-1 .woo-sctr-shortcode-countdown-unit').css({'border': '1px solid ' + jQuery(this).val()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-2 .woo-sctr-shortcode-countdown-value').css({'border': '1px solid ' + jQuery(this).val()})
            } else {
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-1 .woo-sctr-shortcode-countdown-unit').css({'border': 'none'});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-2 .woo-sctr-shortcode-countdown-value').css({'border': 'none'});
                jQuery(this).parent().find('.color-picker').css({'background': 'none'});
            }
        });
        jQuery('.woo-sctr-datetime-value-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-value').css({color: ui.color.toString()})
            }
        }).on('keyup', function () {
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-value').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-datetime-value-bg-color').iris({
            change: function (event, ui) {
                let parent_accordion = jQuery(this).parent().parent().parent().parent().parent().parent();
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-value').not('.woo-sctr-shortcode-countdown-style-4 .woo-sctr-shortcode-countdown-value').css({backgroundColor: ui.color.toString()});
                let str = jQuery('#sales-countdown-timer-admin-inline-css').html();
                let reg_str = '.woo-sctr-accordion-wrap[data-accordion_id="' + parent_accordion.attr('data-accordion_id') + '"] .woo-sctr-shortcode-countdown-style-4 .woo-sctr-shortcode-countdown-1 .woo-sctr-progress-circle:after{background:([\\s\\S]*?);}';
                let reg = new RegExp(reg_str, 'igm');
                let match = reg.exec(str);
                if (match) {
                    jQuery('#sales-countdown-timer-admin-inline-css').html(str.replace(match[0], '.woo-sctr-accordion-wrap[data-accordion_id="' + parent_accordion.attr('data-accordion_id') + '"] .woo-sctr-shortcode-countdown-style-4 .woo-sctr-shortcode-countdown-1 .woo-sctr-progress-circle:after{background:' + ui.color.toString() + ';}'));
                } else {
                    jQuery('#sales-countdown-timer-admin-inline-css').html(str + '.woo-sctr-accordion-wrap[data-accordion_id="' + parent_accordion.attr('data-accordion_id') + '"] .woo-sctr-shortcode-countdown-style-4 .woo-sctr-shortcode-countdown-1 .woo-sctr-progress-circle:after{background:' + ui.color.toString() + ';}');
                }
            }
        }).on('keyup', function () {
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-value').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-datetime-unit-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-text').css({color: ui.color.toString()})
            }
        }).on('keyup', function () {
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-text').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-datetime-unit-bg-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-text').css({backgroundColor: ui.color.toString()})
            }
        }).on('keyup', function () {
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-text').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('body').on('click', function () {
            jQuery('.iris-picker').hide();
        });
    }

    handleColorPicker();

    function handleFontChange() {
        jQuery('.woo-sctr-datetime-value-font-size').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-value').not('.woo-sctr-shortcode-countdown-style-4 .woo-sctr-shortcode-countdown-value').css({'font-size': jQuery(this).val() + 'px'});
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-progress-circle').css({'font-size': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-datetime-unit-font-size').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-text').css({'font-size': jQuery(this).val() + 'px'})
        });
        jQuery('.woo-sctr-countdown-timer-border-radius').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap:not(.woo-sctr-shortcode-wrap-wrap-inline) .woo-sctr-shortcode-countdown-1').css({'border-radius': jQuery(this).val() + 'px'})
            jQuery(this).parent().parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-inline').css({'border-radius': jQuery(this).val() + 'px'})
        });
        jQuery('.woo-sctr-countdown-timer-padding').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap:not(.woo-sctr-shortcode-wrap-wrap-inline) .woo-sctr-shortcode-countdown-1').css({'padding': jQuery(this).val() + 'px'});
            jQuery(this).parent().parent().parent().parent().parent().find('.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-inline').css({'padding': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-timer-item-height').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-1 .woo-sctr-shortcode-countdown-unit').css({
                'height': jQuery(this).val() + 'px'
            });
            jQuery(this).parent().parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-2 .woo-sctr-shortcode-countdown-value').css({
                'height': jQuery(this).val() + 'px'
            })
        });
        jQuery('.woo-sctr-countdown-timer-item-width').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-1 .woo-sctr-shortcode-countdown-unit').css({
                'width': jQuery(this).val() + 'px'
            });
            jQuery(this).parent().parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-2 .woo-sctr-shortcode-countdown-value').css({
                'width': jQuery(this).val() + 'px'
            })
        });
        jQuery('.woo-sctr-countdown-timer-item-border-radius').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-1 .woo-sctr-shortcode-countdown-unit').css({
                'border-radius': jQuery(this).val() + 'px'
            });
            jQuery(this).parent().parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-style-2 .woo-sctr-shortcode-countdown-value').css({
                'border-radius': jQuery(this).val() + 'px'
            })
        });
        jQuery('.woo-sctr-message').unbind().on('keyup', function () {
            var textBefore, textAfter, message = jQuery(this).val();
            var temp = message.split('{countdown_timer}');
            if (temp.length < 2) {
                jQuery('.woo-sctr-warning-message-countdown-timer').removeClass('woo-sctr-hidden-class');
            } else {
                jQuery('.woo-sctr-warning-message-countdown-timer').addClass('woo-sctr-hidden-class');
                textBefore = temp[0];
                textAfter = temp[1];
            }
            jQuery(this).parent().parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-text-before').html(textBefore);
            jQuery(this).parent().parent().parent().parent().parent().find('.woo-sctr-shortcode-countdown-text-after').html(textAfter);
        });
        jQuery('.woo-sctr-sale-from-date').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.woo-sctr-short-description-from-date').html(jQuery(this).val());
        });
        jQuery('.woo-sctr-sale-from-time').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.woo-sctr-short-description-from-time').html(jQuery(this).val());
        });
        jQuery('.woo-sctr-sale-to-date').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.woo-sctr-short-description-to-date').html(jQuery(this).val());
        });
        jQuery('.woo-sctr-sale-to-time').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.woo-sctr-short-description-to-time').html(jQuery(this).val());
        });
    }

    handleFontChange();
    jQuery('.vi-ui.accordion')
        .accordion()
    ;
    saveDataAjax();
    var myDebugVar = 0;

    function myDebug() {
        console.log(myDebugVar);
        myDebugVar++;
    }

    function saveDataAjax() {
        jQuery('body').on('click', '.woo-sctr-save', function () {
            jQuery(this).addClass('woo-sctr-adding');
            var nameArr = jQuery('input[name="woo_ctr_name[]"]');
            var z, v;
            for (z = 0; z < nameArr.length; z++) {
                if (!jQuery('input[name="woo_ctr_name[]"]').eq(z).val()) {
                    alert('Name cannot be empty!');
                    jQuery('input[name="woo_ctr_name[]"]').eq(z).focus();
                    if (!jQuery('.woo-sctr-accordion').eq(z).hasClass('woo-sctr-active-accordion')) {
                        jQuery('.woo-sctr-accordion').eq(z).addClass('woo-sctr-active-accordion');
                        jQuery('.woo-sctr-panel').eq(z).css({'max-height': jQuery('.woo-sctr-panel').eq(z).prop('scrollHeight') + 'px'})
                    }
                    jQuery('.woo-sctr-save').removeClass('woo-sctr-adding');
                    return false;
                }
            }
            for (z = 0; z < nameArr.length - 1; z++) {
                for (v = z + 1; v < nameArr.length; v++) {
                    if (jQuery('input[name="woo_ctr_name[]"]').eq(z).val() === jQuery('input[name="woo_ctr_name[]"]').eq(v).val()) {
                        alert("Names are unique!");
                        jQuery('input[name="woo_ctr_name[]"]').eq(v).focus();
                        if (!jQuery('.woo-sctr-accordion').eq(v).hasClass('woo-sctr-active-accordion')) {
                            jQuery('.woo-sctr-accordion').eq(v).addClass('woo-sctr-active-accordion');
                            jQuery('.woo-sctr-panel').eq(v).css({'max-height': jQuery('.woo-sctr-panel').eq(v).prop('scrollHeight') + 'px'})
                        }
                        jQuery('.woo-sctr-save').removeClass('woo-sctr-adding');
                        return false;
                    }
                }
            }
            var myArr = ['woo_ctr_active',
                'woo_ctr_name',
                'woo_ctr_id',
                'woo_ctr_sale_from_date',
                'woo_ctr_sale_from_time',
                'woo_ctr_sale_to_date',
                'woo_ctr_sale_to_time',
                'woo_ctr_count_type',
                'woo_ctr_count_auto_switch',
                'woo_ctr_count_auto_switch_value',
                'woo_ctr_time_separator',
                'woo_ctr_count_style',
                'woo_ctr_display_type',
                'woo_ctr_display_type_0',
                'woo_ctr_countdown_timer_hide_zero',
                'woo_ctr_countdown_timer_color',
                'woo_ctr_countdown_timer_bg_color',
                'woo_ctr_countdown_timer_padding',
                'woo_ctr_countdown_timer_border_radius',
                'woo_ctr_countdown_timer_border_color',
                'woo_ctr_countdown_timer_item_border_color',
                'woo_ctr_countdown_timer_item_border_radius',
                'woo_ctr_countdown_timer_item_height',
                'woo_ctr_countdown_timer_item_width',
                'woo_ctr_datetime_value_color',
                'woo_ctr_datetime_value_bg_color',
                'woo_ctr_datetime_value_font_size',
                'woo_ctr_datetime_unit_color',
                'woo_ctr_datetime_unit_bg_color',
                'woo_ctr_datetime_unit_font_size',
                'woo_ctr_message',
                'woo_ctr_position',
                'woo_ctr_archive_page_position',
                'woo_ctr_shop_page',
                'woo_ctr_category_page',
                'woo_ctr_size_on_archive_page',
                'woo_ctr_upcoming',
                'woo_ctr_upcoming_type',
                'woo_ctr_upcoming_auto_switch',
                'woo_ctr_upcoming_auto_switch_value',
                'woo_ctr_upcoming_message',
                'woo_ctr_progress_bar_message',
                'woo_ctr_progress_bar_type',
                'woo_ctr_progress_bar_order_status',
                'woo_ctr_progress_bar_position',
                'woo_ctr_progress_bar_width',
                'woo_ctr_progress_bar_height',
                'woo_ctr_progress_bar_bg_color',
                'woo_ctr_progress_bar_color',
                'woo_ctr_progress_bar_border_radius',
                'woo_ctr_datetime_unit_position',
                'woo_ctr_animation_style',
                'woo_ctr_circle_smooth_animation',
                'woo_ctr_stick_to_top',
            ];
            var myData = {};
            var temp;
            for (var eleName in myArr) {
                temp = [];
                jQuery('[name="' + myArr[eleName] + '[]"]').map(function () {
                    temp.push(jQuery(this).val());
                });
                myData[myArr[eleName]] = temp;
            }
            myData['woo_ctr_nonce_field'] = jQuery('#woo_ctr_nonce_field').val();
            jQuery.ajax({
                type: 'post',
                dataType: 'json',
                url: 'admin-ajax.php?action=woo_sctr_save_settings',
                data: myData,
                success: function (response) {
                    jQuery('.woo-sctr-save').removeClass('woo-sctr-adding');
                    if (response.status === 'successful') {
                        jQuery('.woo-sctr-save-sucessful-popup').animate({'top': '45px'}, 500);
                        setTimeout(function () {
                            jQuery('.woo-sctr-save-sucessful-popup').animate({'top': '-300px'}, 200);
                        }, 5000)
                    } else {
                        alert(response.message);
                        location.reload();
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        })
    }

});