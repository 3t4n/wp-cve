var xlwcty_admin_change_content = null;
var current_pro_slug = null;
var Formchanges = false;
var XLWCTY_ContentLoaded = false;
var swal_pass = false;
var isEmptyLayout = false;
var cmb2_select_last_interact = null;

function show_modal_pro(defaultVal) {
    current_pro_slug = defaultVal;
    current_pro_slug = current_pro_slug.replace("#", '');
    var defaults = {
        title: "",
        icon: 'dashicons dashicons-lock',
        content: "",
        confirmButton: buy_pro_helper.call_to_action_text,
        columnClass: 'modal-wide',
        closeIcon: true,
        confirm: function () {
            var replaced = buy_pro_helper.buy_now_link.replace("{current_slug}", current_pro_slug);
            window.open(replaced, '_blank');
        }
    };
    var popData;
    if (buy_pro_helper.popups[defaultVal] !== "undefined") {
        popData = buy_pro_helper.popups[defaultVal];
        popData = jQuery.extend(true, {}, defaults, popData);
    } else {
        popData = {};
        popData = jQuery.extend(true, {}, defaults, popData);
    }

    jQuery.xlAlert(popData);
}

function xlwcty_show_tb(title, id) {
    xlwcty_modal_show(title, "#xlwcty_MB_inline?height=500&amp;width=1000&amp;inlineId=" + id + "");
}

function xlwcty_getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function xlwcty_manage_radio_active($) {
    if ($(".cmb-row.xlwcty_radio_btn").length > 0) {
        $(".cmb-row.xlwcty_radio_btn").each(function () {
            var $this = $(this);
            $this.find("li.radio-active").removeClass("radio-active");
            $this.find("input[type='radio']:checked").parent("li").addClass("radio-active");
        });
    }
}

(function ($) {
    'use strict';
    /**
     * Set up the functionality for CMB2 conditionals.
     */
    window.xlwcty_cmb2ConditionalsInit = function (changeContext, conditionContext) {
        var loopI, requiredElms, uniqueFormElms, formElms;

        if ('undefined' === typeof changeContext) {
            changeContext = 'body';
        }
        changeContext = $(changeContext);

        if ('undefined' === typeof conditionContext) {
            conditionContext = 'body';
        }
        conditionContext = $(conditionContext);
        window.xlwcty_admin_change_content = conditionContext;
        changeContext.on('change', 'input, textarea, select', function (evt) {

            if (XLWCTY_ContentLoaded === true) {
                Formchanges = true;
            }
            var elm = $(this),
                fieldName = $(this).attr('name'),
                dependants,
                dependantsSeen = [],
                checkedValues,
                elmValue;

            dependants = $('[data-xlwcty-conditional-id="' + fieldName + '"]', conditionContext);
            if (!elm.is(":visible")) {
                return;
            }

            // Only continue if we actually have dependants.
            if (dependants.length > 0) {

                // Figure out the value for the current element.
                if ('checkbox' === elm.attr('type')) {
                    checkedValues = $('[name="' + fieldName + '"]:checked').map(function () {
                        return this.value;
                    }).get();
                } else if ('radio' === elm.attr('type')) {
                    if ($('[name="' + fieldName + '"]').is(':checked')) {
                        elmValue = elm.val();
                    }
                } else {
                    elmValue = evt.currentTarget.value;
                }

                dependants.each(function (i, e) {
                    var loopIndex = 0,
                        current = $(e),
                        currentFieldName = current.attr('name'),
                        requiredValue = current.data('xlwcty-conditional-value'),
                        currentParent = current.parents('.cmb-row:first'),
                        shouldShow = false;

                    // Only check this dependant if we haven't done so before for this parent.
                    // We don't need to check ten times for one radio field with ten options,
                    // the conditionals are for the field, not the option.
                    if ('undefined' !== typeof currentFieldName && '' !== currentFieldName && $.inArray(currentFieldName, dependantsSeen) < 0) {
                        dependantsSeen.push = currentFieldName;

                        if ('checkbox' === elm.attr('type')) {
                            if ('undefined' === typeof requiredValue) {
                                shouldShow = (checkedValues.length > 0);
                            } else if ('off' === requiredValue) {
                                shouldShow = (0 === checkedValues.length);
                            } else if (checkedValues.length > 0) {
                                if ('string' === typeof requiredValue) {
                                    shouldShow = ($.inArray(requiredValue, checkedValues) > -1);
                                } else if (Array.isArray(requiredValue)) {
                                    for (loopIndex = 0; loopIndex < requiredValue.length; loopIndex++) {
                                        if ($.inArray(requiredValue[loopIndex], checkedValues) > -1) {
                                            shouldShow = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        } else if ('undefined' === typeof requiredValue) {
                            shouldShow = (elm.val() ? true : false);
                        } else {
                            if ('string' === typeof requiredValue) {
                                shouldShow = (elmValue === requiredValue);
                            }
                            if ('number' === typeof requiredValue) {
                                shouldShow = (elmValue == requiredValue);
                            } else if (Array.isArray(requiredValue)) {
                                shouldShow = ($.inArray(elmValue, requiredValue) > -1);
                            }
                        }

                        // Handle any actions necessary.
                        currentParent.toggle(shouldShow);

                        window.xlwcty_admin_change_content.trigger("xlwcty_internal_conditional_runs", [current, currentFieldName, requiredValue, currentParent, shouldShow, elm, elmValue]);

                        if (current.data('conditional-required')) {
                            current.prop('required', shouldShow);
                        }

                        // If we're hiding the row, hide all dependants (and their dependants).
                        if (false === shouldShow) {
                            // CMB2ConditionalsRecursivelyHideDependants(currentFieldName, current, conditionContext);
                        }

                        // If we're showing the row, check if any dependants need to become visible.
                        else {
                            if (1 === current.length) {
                                current.trigger('change');
                            } else {
                                current.filter(':checked').trigger('change');
                            }
                        }
                    } else {
                        /** Handling for */
                        if (current.hasClass("dtheme-cmb2-tabs") || current.hasClass("cmb2-xlwcty_html")) {

                            if ('checkbox' === elm.attr('type')) {
                                if ('undefined' === typeof requiredValue) {
                                    shouldShow = (checkedValues.length > 0);
                                } else if ('off' === requiredValue) {
                                    shouldShow = (0 === checkedValues.length);
                                } else if (checkedValues.length > 0) {
                                    if ('string' === typeof requiredValue) {
                                        shouldShow = ($.inArray(requiredValue, checkedValues) > -1);
                                    } else if (Array.isArray(requiredValue)) {
                                        for (loopIndex = 0; loopIndex < requiredValue.length; loopIndex++) {
                                            if ($.inArray(requiredValue[loopIndex], checkedValues) > -1) {
                                                shouldShow = true;
                                                break;
                                            }
                                        }
                                    }
                                }
                            } else if ('undefined' === typeof requiredValue) {
                                shouldShow = (elm.val() ? true : false);
                            } else {
                                if ('string' === typeof requiredValue) {
                                    shouldShow = (elmValue === requiredValue);
                                }
                                if ('number' === typeof requiredValue) {
                                    shouldShow = (elmValue == requiredValue);
                                } else if (Array.isArray(requiredValue)) {
                                    shouldShow = ($.inArray(elmValue, requiredValue) > -1);
                                }
                            }

                            currentParent.toggle(shouldShow);
                            window.xlwcty_admin_change_content.trigger("xlwcty_internal_conditional_runs", [current, currentFieldName, requiredValue, currentParent, shouldShow, elm, elmValue]);

                        } else if (current.hasClass("xlwcty_custom_wrapper_group") || current.hasClass("xlwcty_custom_wrapper_wysiwyg")) {
                            if ('checkbox' === elm.attr('type')) {
                                if ('undefined' === typeof requiredValue) {
                                    shouldShow = (checkedValues.length > 0);
                                } else if ('off' === requiredValue) {
                                    shouldShow = (0 === checkedValues.length);
                                } else if (checkedValues.length > 0) {
                                    if ('string' === typeof requiredValue) {
                                        shouldShow = ($.inArray(requiredValue, checkedValues) > -1);
                                    } else if (Array.isArray(requiredValue)) {
                                        for (loopIndex = 0; loopIndex < requiredValue.length; loopIndex++) {
                                            if ($.inArray(requiredValue[loopIndex], checkedValues) > -1) {
                                                shouldShow = true;
                                                break;
                                            }
                                        }
                                    }
                                }
                            } else if ('undefined' === typeof requiredValue) {
                                shouldShow = (elm.val() ? true : false);
                            } else {
                                if ('string' === typeof requiredValue) {
                                    shouldShow = (elmValue === requiredValue);
                                }
                                if ('number' === typeof requiredValue) {
                                    shouldShow = (elmValue == requiredValue);
                                } else if (Array.isArray(requiredValue)) {
                                    shouldShow = ($.inArray(elmValue, requiredValue) > -1);
                                }
                            }

                            current.toggle(shouldShow);
                            window.xlwcty_admin_change_content.trigger("xlwcty_internal_conditional_runs", [current, currentFieldName, requiredValue, currentParent, shouldShow, elm, elmValue]);
                        }
                    }
                });
            }
            var icon_font_icon_val;
            if (elm.hasClass('xlwcty_icon_select')) {
                icon_font_icon_val = $(this).val();
                if (icon_font_icon_val != '') {
                    if (icon_font_icon_val.indexOf("dashicons") >= 0) {
                        icon_font_icon_val += ' dashicons';
                    } else if (icon_font_icon_val.indexOf("xlwcty-fa") >= 0) {
                        icon_font_icon_val += ' xlwcty-fa';
                    }
                    elm.next('.xlwcty_icon_preview').html('<i class="xlwcty_custom_icon ' + icon_font_icon_val + '"></i>');
                }
            }

            if (elm.hasClass('xlwcty_map_icon_select')) {
                icon_font_icon_val = $(this).val();
                if (icon_font_icon_val != '') {
                    elm.next('.xlwcty_icon_preview').html('<img src="' + xlwctyParams.plugin_url + '/assets/img/map-pins/' + icon_font_icon_val + '.png" />');
                }
            }
        });

        window.xlwcty_admin_change_content.on("xlwcty_conditional_runs", function (e, current, currentFieldName, requiredValue, currentParent, shouldShow, elm, elmValue) {

            var loopIndex = 0;
            var checkedValues;
            shouldShow = false;
            if (typeof current.attr('data-xlwcty-conditional-value') == "undefined") {
                return;
            }

            elm = $("[name='" + current.attr('data-xlwcty-conditional-id') + "']", changeContext).eq(0);

            if (!elm.is(":visible")) {

                return;
            }
            // Figure out the value for the current element.
            if ('checkbox' === elm.attr('type')) {
                checkedValues = $('[name="' + current.attr('data-xlwcty-conditional-id') + '"]:checked').map(function () {
                    return this.value;
                }).get();
            } else if ('radio' === elm.attr('type')) {
                elmValue = $('[name="' + current.attr('data-xlwcty-conditional-id') + '"]:checked').val();

            }

            requiredValue = current.data('xlwcty-conditional-value');

            // Only check this dependant if we haven't done so before for this parent.
            // We don't need to check ten times for one radio field with ten options,
            // the conditionals are for the field, not the option.
            if ('undefined' !== typeof currentFieldName && '' !== currentFieldName) {

                if ('checkbox' === elm.attr('type')) {
                    if ('undefined' === typeof requiredValue) {
                        shouldShow = (checkedValues.length > 0);
                    } else if ('off' === requiredValue) {
                        shouldShow = (0 === checkedValues.length);
                    } else if (checkedValues.length > 0) {
                        if ('string' === typeof requiredValue) {
                            shouldShow = ($.inArray(requiredValue, checkedValues) > -1);
                        } else if (Array.isArray(requiredValue)) {
                            for (loopIndex = 0; loopIndex < requiredValue.length; loopIndex++) {
                                if ($.inArray(requiredValue[loopIndex], checkedValues) > -1) {
                                    shouldShow = true;
                                    break;
                                }
                            }
                        }
                    }
                } else if ('undefined' === typeof requiredValue) {
                    shouldShow = (elm.val() ? true : false);
                } else {

                    if ('string' === typeof requiredValue) {
                        shouldShow = (elmValue === requiredValue);
                    }
                    if ('number' === typeof requiredValue) {
                        shouldShow = (elmValue == requiredValue);
                    } else if (Array.isArray(requiredValue)) {

                        shouldShow = ($.inArray(elmValue, requiredValue) > -1);
                    }
                }

                // Handle any actions necessary.
                currentParent.toggle(shouldShow);
                window.xlwcty_admin_change_content.trigger("xlwcty_internal_conditional_runs", [current, currentFieldName, requiredValue, currentParent, shouldShow, elm, elmValue]);

                if (current.data('conditional-required')) {
                    current.prop('required', shouldShow);
                }

                // If we're hiding the row, hide all dependants (and their dependants).
                if (false === shouldShow) {
                    // CMB2ConditionalsRecursivelyHideDependants(currentFieldName, current, conditionContext);
                }

                // If we're showing the row, check if any dependants need to become visible.
                else {
                    if (1 === current.length) {
                        current.trigger('change');
                    } else {
                        current.filter(':checked').trigger('change');
                    }
                }
            } else {
                if (current.hasClass("dtheme-cmb2-tabs") || current.hasClass("cmb2-xlwcty_html")) {
                    if ('checkbox' === elm.attr('type')) {
                        if ('undefined' === typeof requiredValue) {
                            shouldShow = (checkedValues.length > 0);
                        } else if ('off' === requiredValue) {
                            shouldShow = (0 === checkedValues.length);
                        } else if (checkedValues.length > 0) {
                            if ('string' === typeof requiredValue) {
                                shouldShow = ($.inArray(requiredValue, checkedValues) > -1);
                            } else if (Array.isArray(requiredValue)) {
                                for (loopIndex = 0; loopIndex < requiredValue.length; loopIndex++) {
                                    if ($.inArray(requiredValue[loopIndex], checkedValues) > -1) {
                                        shouldShow = true;
                                        break;
                                    }
                                }
                            }
                        }
                    } else if ('undefined' === typeof requiredValue) {
                        shouldShow = (elm.val() ? true : false);
                    } else {
                        if ('string' === typeof requiredValue) {
                            shouldShow = (elmValue === requiredValue);
                        }
                        if ('number' === typeof requiredValue) {
                            shouldShow = (elmValue == requiredValue);
                        } else if (Array.isArray(requiredValue)) {
                            shouldShow = ($.inArray(elmValue, requiredValue) > -1);
                        }
                    }

                    currentParent.toggle(shouldShow);
                    window.xlwcty_admin_change_content.trigger("xlwcty_internal_conditional_runs", [current, currentFieldName, requiredValue, currentParent, shouldShow, elm, elmValue]);


                } else if (current.hasClass("xlwcty_custom_wrapper_group") || current.hasClass("xlwcty_custom_wrapper_wysiwyg")) {
                    if ('checkbox' === elm.attr('type')) {
                        if ('undefined' === typeof requiredValue) {
                            shouldShow = (checkedValues.length > 0);
                        } else if ('off' === requiredValue) {
                            shouldShow = (0 === checkedValues.length);
                        } else if (checkedValues.length > 0) {
                            if ('string' === typeof requiredValue) {
                                shouldShow = ($.inArray(requiredValue, checkedValues) > -1);
                            } else if (Array.isArray(requiredValue)) {
                                for (loopIndex = 0; loopIndex < requiredValue.length; loopIndex++) {
                                    if ($.inArray(requiredValue[loopIndex], checkedValues) > -1) {
                                        shouldShow = true;
                                        break;
                                    }
                                }
                            }
                        }
                    } else if ('undefined' === typeof requiredValue) {
                        shouldShow = (elm.val() ? true : false);
                    } else {
                        if ('string' === typeof requiredValue) {
                            shouldShow = (elmValue === requiredValue);
                        }
                        if ('number' === typeof requiredValue) {
                            shouldShow = (elmValue == requiredValue);
                        } else if (Array.isArray(requiredValue)) {
                            shouldShow = ($.inArray(elmValue, requiredValue) > -1);
                        }
                    }

                    current.toggle(shouldShow);
                    window.xlwcty_admin_change_content.trigger("xlwcty_internal_conditional_runs", [current, currentFieldName, requiredValue, currentParent, shouldShow, elm, elmValue]);

                }
            }
        });

        $('[data-xlwcty-conditional-id]', conditionContext).not(".xlwcty_custom_wrapper_group").parents('.cmb-row:first').hide({
            "complete": function () {
                $("body").trigger("xlwcty_w_trigger_conditional_on_load");

                uniqueFormElms = [];
                $(':input', changeContext).each(function (i, e) {
                    var elmName = $(e).attr('name');
                    if ('undefined' !== typeof elmName && '' !== elmName && -1 === $.inArray(elmName, uniqueFormElms)) {
                        uniqueFormElms.push(elmName);
                    }
                });

                for (loopI = 0; loopI < uniqueFormElms.length; loopI++) {
                    formElms = $('[name="' + uniqueFormElms[loopI] + '"]');
                    if (1 === formElms.length || !formElms.is(':checked')) {
                        formElms.trigger('change');
                    } else {
                        formElms.filter(':checked').trigger('change');
                    }
                }

            }
        });
        $(document).on('xl_cmb2_options_tabs_activated', function (e, event, ui) {
            var uniqueFormElms = [];
            $(':input', ".ui-tabs-panel").each(function (i, e) {
                var elmName = $(e).attr('name');
                if ('undefined' !== typeof elmName && '' !== elmName && -1 === $.inArray(elmName, uniqueFormElms) && $(e).is(":visible")) {
                    uniqueFormElms.push(elmName);
                }
            });
            for (loopI = 0; loopI < uniqueFormElms.length; loopI++) {
                formElms = $('[name="' + uniqueFormElms[loopI] + '"]');
                if (1 === formElms.length || !formElms.is(':checked')) {
                    formElms.trigger('change');
                } else {
                    formElms.filter(':checked').trigger('change');
                }
            }
        });

        $(document).on('xlwcty_acc_toggled', function (e, elem) {
            var uniqueFormElms = [];

            $(elem).next(".cmb2_xlwcty_wrapper_ac_data").find("input").each(function (i, e) {
                var elmName = $(e).attr('name');
                if ('undefined' !== typeof elmName && '' !== elmName && -1 === $.inArray(elmName, uniqueFormElms) && $(e).is(":visible")) {
                    uniqueFormElms.push(elmName);
                }
            });
            for (loopI = 0; loopI < uniqueFormElms.length; loopI++) {
                formElms = $('[name="' + uniqueFormElms[loopI] + '"]');
                if (1 === formElms.length || !formElms.is(':checked')) {
                    formElms.trigger('change');
                } else {
                    formElms.filter(':checked').trigger('change');
                }
            }
        });
    };

    $('input[name="_xlwcty_social_coupons_personalize"]').on('change', function () {

        var val = $('input[name="_xlwcty_social_coupons_personalize"]:checked').val();
        var parent = $('input[name="_xlwcty_social_coupons_enable"]:checked').val();
        var uts = $('input[name="_xlwcty_social_coupons_locked_coupon"]:checked').val();

        if (parent == '0') {
            $(".cmb2-id--xlwcty-social-coupons-format").hide();
            $(".cmb2-id--xlwcty-social-coupons-expiry").hide();
            return;
        }
        if (uts === "no") {
            $(".cmb2-id--xlwcty-social-coupons-format").hide();
            $(".cmb2-id--xlwcty-social-coupons-expiry").hide();
            return;
        }
        if (val == "yes") {

            $(".cmb2-id--xlwcty-social-coupons-format").show();
            $(".cmb2-id--xlwcty-social-coupons-expiry").show();
        } else {

            $(".cmb2-id--xlwcty-social-coupons-format").hide();
            $(".cmb2-id--xlwcty-social-coupons-expiry").hide();
        }
    });

    if ($(".cmb2-wrap.xlwcty_options_common").length > 0) {
        xlwctyCMB2ConditionalsInit('.postbox .cmb2-wrap.xlwcty_options_common', '.postbox .cmb2-wrap.xlwcty_options_common');
        xlwcty_cmb2ConditionalsInit('.postbox .cmb2-wrap.xlwcty_options_common', '.postbox .cmb2-wrap.xlwcty_options_common');

        xlwctyCMB2ConditionalsInit('.xlwcty_global_option .cmb2-wrap.xlwcty_options_common', '.xlwcty_global_option .cmb2-wrap.xlwcty_options_common');
        xlwcty_cmb2ConditionalsInit('.xlwcty_global_option .cmb2-wrap.xlwcty_options_common', '.xlwcty_global_option .cmb2-wrap.xlwcty_options_common');
    }

    $('.xlwcty_global_option .xlwcty_options_page_left_wrap').removeClass('dispnone');

    if (window.xlwcty_admin_change_content) {
        window.xlwcty_admin_change_content.on("xlwcty_internal_conditional_runs", function (e, current, currentFieldName, requiredValue, currentParent, shouldShow, elm, elmValue) {

            if (currentFieldName == "_xlwcty_social_coupons_format" || currentFieldName == "_xlwcty_social_coupons_expiry") {
                if (shouldShow === true) {
                    var val = $('input[name="_xlwcty_social_coupons_personalize"]:checked').val();
                    if (val == "yes") {
                        currentParent.show();
                    } else {
                        currentParent.hide();
                    }
                } else {
                    currentParent.hide();
                }
            }

        });
    }

    $(window).on('load', function () {
        if ($(".xlwcty_screen_load").length > 0) {
            setTimeout(function () {
                if (xlwctyCookieVal !== "") {
                    $(".xlwcty_field_btn[data-slug='" + xlwctyCookieVal + "']").eq(0).trigger("click");
                } else if ($(".xlwcty_field_btn.xlwcty_selected").length > 0) {
                    $(".xlwcty_field_btn.xlwcty_selected").eq(0).trigger("click");
                } else {
                    $(".xlwcty_field_btn").eq(0).trigger("click");
                }
            }, 100);
            setTimeout(function () {
                $(".xlwcty_screen_load").fadeOut(400);
                setTimeout(function () {
                    $(".xlwcty_screen_load").remove();
                }, 500);
                setTimeout(function () {
                    XLWCTY_ContentLoaded = true;
                }, 1000);
            }, 150);
        }
        $("body").on("click", ".cmb2_xlwcty_acc_head", function () {
            var currentOpened = $(this).parent(".cmb2_xlwcty_wrapper_ac").attr('data-slug');

            if ($(this).hasClass("active")) {
                $(this).next(".cmb2_xlwcty_wrapper_ac_data").toggle(false);
                $(this).parents(".cmb2_xlwcty_wrapper_ac").removeClass('opened');
            } else {
                $(this).next(".cmb2_xlwcty_wrapper_ac_data").toggle(true);
                $(this).parents(".cmb2_xlwcty_wrapper_ac").addClass('opened');
            }
            $(this).toggleClass("active");
            $(document).trigger("xlwcty_acc_toggled", [this]);
        });

        $("body").on("click", ".xlwcty_field_btn", function () {
            XLWCTY_ContentLoaded = false;
            var $this = $(this), uniqueID;
            /* if click perform on add more component element */
            if ($this.hasClass("xlwcty_add_more_ui")) {
                return;
            }
            uniqueID = $this.attr("data-slug");
            if (buy_pro_helper.proacc.indexOf(uniqueID) !== -1) {
                show_modal_pro(uniqueID);
                return false;
            }
            /* start - checking if add more item already open */
            var addMoreItemElem = $(".xlwcty_add_more_items");
            if (addMoreItemElem.hasClass('xlwcty_btn_open')) {
                addMoreItemElem.removeClass('xlwcty_btn_open');
                addMoreItemElem.closest('.xlwcty_field_btn').find('.xlwcty_btn_layouts').slideUp();
            }
            /* close - checking if add more item already open */

            uniqueID = $this.attr("data-slug");
            var formElemGif = $("#xlwcty_metabox_customizer_settings").find(".xlwcty_freeze_screen");
            $('.xlwcty_field_btn').removeClass('active');
            $this.addClass('active');
            var targetElem = $(".cmb2_xlwcty_wrapper_ac[data-slug='" + uniqueID + "']");

            if (targetElem.length > 0) {
                if (targetElem.hasClass("opened")) {
                } else {
                    formElemGif.show();
                    $('.cmb2_xlwcty_wrapper_ac.opened').removeClass('opened').children('.cmb2_xlwcty_wrapper_ac_data').hide();
                    $('.cmb2_xlwcty_acc_head.active').removeClass('active');
                    targetElem.children(".cmb2_xlwcty_acc_head").trigger("click");
                }
            }
            var d = new Date();
            d.setTime(d.getTime() + (60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            xlwctyPostid = ($("input[name='object_id']").val());
            document.cookie = "xlwcty_cook_post_tab_open_" + xlwctyPostid + "" + "=" + uniqueID + ";" + expires + ";path=/";
            setTimeout(function () {
                formElemGif.hide();
                XLWCTY_ContentLoaded = true;
                if ($(".xlwcty_builder_wide_wrap .postbox.xlwcty_inner_height").eq(1).length > 0) {
                    $(".xlwcty_builder_wide_wrap .postbox.xlwcty_inner_height").eq(1).mCustomScrollbar("scrollTo", "top", {
                        scrollInertia: 1500
                    });
                }
            }, 1000);

        });
        if ($("select.xlwcty_icon_select").length > 0) {
            $("select.xlwcty_icon_select").each(function () {
                $(this).trigger("change");
            });
        }
        if ($(".xlwcty_below_indication").length > 0) {
            setTimeout(function () {
                $(".xlwcty_below_indication").fadeOut(500);
            }, 6000);
        }
        $("body").on("click", ".xlwcty_admin_sticky_bar a.button.builder_fields_save", function () {
            if (isEmptyLayout === true) {
                new swal({
                    "title": xlwcty_localized_texts.no_component.title,
                    "text": xlwcty_localized_texts.no_component.text,
                    "icon": "error",
                    showConfirmButton: true
                });
            } else {
                new swal({
                    "title": xlwcty_localized_texts.saving.title,
                    "text": xlwcty_localized_texts.saving.text,
                    "icon": "success",
                    showConfirmButton: false
                });

                setTimeout(function () {
                    $(".xlwcty_admin_sticky_bar .xlwcty_fl_rt").addClass("xlwcty_submit_load");
                    $("form#xlwcty_builder_settings").find("input[type='submit']").trigger("click");
                }, 1000);
            }
        });
        $("body").on("click", "li.xlwcty_layout_components", function () {
            var $this = $(this);
            var $thisSlug = $this.attr("data-slug");
            if ($(".xlwcty_field_btn[data-slug='" + $thisSlug + "']").length > 0) {
                $(".xlwcty_field_btn[data-slug='" + $thisSlug + "']").trigger("click");
            }
        });
        $("body").on("click", ".xlwcty_detect_checkbox_change input[type='checkbox']", function () {
            var $this = $(this);
            var $wrap = $(this).parents(".xlwcty_detect_checkbox_change");
            if ($wrap.hasClass("xlwcty_gif_location")) {
                $(".xlwcty_load_spin.xlwcty_load_tab_location").addClass("xlwcty_load_active");
                setTimeout(function () {
                    $(".xlwcty_load_spin.xlwcty_load_tab_location").removeClass("xlwcty_load_active");
                }, 2000);
            }
            if ($wrap.hasClass("xlwcty_gif_appearance")) {
                $(".xlwcty_load_spin.xlwcty_load_tab_appearance").addClass("xlwcty_load_active");
                setTimeout(function () {
                    $(".xlwcty_load_spin.xlwcty_load_tab_appearance").removeClass("xlwcty_load_active");
                }, 2000);
            }
        });
        $("body").on("click", ".xlwcty_detect_radio_change input[type='radio']", function () {
            var $this = $(this);
            var $wrap = $(this).parents(".xlwcty_detect_radio_change");
            if ($wrap.hasClass("xlwcty_gif_appearance")) {
                $(".xlwcty_load_spin.xlwcty_load_tab_appearance").addClass("xlwcty_load_active");
                setTimeout(function () {
                    $(".xlwcty_load_spin.xlwcty_load_tab_appearance").removeClass("xlwcty_load_active");
                }, 2000);
            }
        });
        $("body").on("click", ".xlwcty_thickbox", function () {
            var $this = $(this), screenW = $(window).width(), screenH = $(window).height(), modalW = 1000, modalH = 350;
            var $container_id = $(this).attr("data-id");
            var $thickbox_title = $(this).attr("data-title");

            if (screenW < 1000) {
                modalW = parseInt(screenW * 0.8);
            }
            if (screenH < 350) {
                modalH = parseInt(screenH * 0.8);
            }
            if ($("#" + $container_id).length > 0) {
                tb_show($thickbox_title, '#TB_inline?width=' + modalW + '&height=' + modalH + '&inlineId=' + $container_id, false);
                return false;
            }
        });
        $(".xlwcty_cmb2_chosen select").xlChosen({});
        if ($(".xlwcty_cmb2_coupon select").length > 0) {
            $(".xlwcty_cmb2_coupon select").xlAjaxChosen({
                type: 'POST',
                minTermLength: 3,
                afterTypeDelay: 500,
                data: {
                    'action': 'get_coupons_cmb2'
                },
                url: ajaxurl,
                dataType: 'json'
            }, function (data) {
                var results = [];
                $.each(data, function (i, val) {
                    results.push({value: val.value, text: val.text});
                });
                return results;
            }).change(function () {
                var $this = $(this);
                var descElem = $this.parents(".cmb-td").children(".cmb2-metabox-description");
                if (descElem.length > 0) {
                    var descElemVal = 'NextMove reveals the selected Coupon code. If you choose to <em>personalize</em> this coupon, all the settings of this coupon will be copied to generate a new personalized Coupon. Don\'t forget to check coupon\'s <a href="{coupon_link}" target="_blank">usage restrictions</a>.';
                    if ($this.val()) {
                        var newVal = parseInt($this.val());
                        if (newVal > 0) {
                            descElemVal = descElemVal.replace('{coupon_link}', xlwctyParams.admin_url + 'post.php?post=' + newVal + '&action=edit#usage_restriction_coupon_data');
                            descElem.html(descElemVal);
                            descElem.css("display", "block");
                        } else {
                            descElemVal = descElemVal.replace('{coupon_link}', "javascript:void(0)");// jshint ignore:line
                            descElem.html(descElemVal);
                            descElem.css("display", "none");
                        }
                    } else {
                        descElemVal = descElemVal.replace('{coupon_link}', "javascript:void(0)");// jshint ignore:line
                        descElem.html(descElemVal);
                        descElem.css("display", "none");
                    }
                }
            });
        }
        if ($(".xlwcty_product_cmb2_chosen select").length > 0) {
            $(".xlwcty_product_cmb2_chosen select").xlAjaxChosen({
                type: 'POST',
                minTermLength: 3,
                afterTypeDelay: 500,
                data: {
                    'action': 'get_product_cmb2'
                },
                url: ajaxurl,
                dataType: 'json'
            }, function (data) {
                var results = [];
                $.each(data, function (i, val) {
                    results.push({value: val.value, text: val.text});
                });
                return results;
            }).change(function () {
                var $this = $(this);
                var descElem = $this.parents(".cmb-td").children(".cmb2-metabox-description");
                if (descElem.length > 0) {
                    var descElemVal = 'NextMove reveals the selected Coupon code. If you choose to <em>personalize</em> this coupon, all the settings of this coupon will be copied to generate a new personalized Coupon. Don\'t forget to check coupon\'s <a href="{coupon_link}" target="_blank">usage restrictions</a>.';
                    if ($this.val()) {
                        var newVal = parseInt($this.val());
                        if (newVal > 0) {
                            descElemVal = descElemVal.replace('{coupon_link}', xlwctyParams.admin_url + 'post.php?post=' + newVal + '&action=edit#usage_restriction_coupon_data');
                            descElem.html(descElemVal);
                            descElem.css("display", "block");
                        } else {
                            descElemVal = descElemVal.replace('{coupon_link}', 'javascript:void(0)');// jshint ignore:line
                            descElem.html(descElemVal);
                            descElem.css("display", "none");
                        }
                    } else {
                        descElemVal = descElemVal.replace('{coupon_link}', 'javascript:void(0)');// jshint ignore:line
                        descElem.html(descElemVal);
                        descElem.css("display", "none");
                    }
                }
            });
        }
        if ($("#order_select").length > 0) {
            $("#order_select").xlAjaxChosen({
                type: 'POST',
                minTermLength: 3,
                afterTypeDelay: 500,
                data: {
                    'action': 'xlwcty_get_orders_cmb2'
                },
                url: ajaxurl,
                dataType: 'json'
            }, function (data) {
                var results = [];
                $.each(data, function (i, val) {
                    results.push({value: val.value, text: val.text});
                });
                return results;
            });
        }
        $(".cmb-row.xlwcty_radio_btn").find("input[type='radio']").on("change", function () {
            var $this = $(this);
            $this.parents("ul").find("li.radio-active").removeClass("radio-active");
            $this.parent("li").addClass("radio-active");
        });
        /* mCustomScrollbar script */
        if ($(".xlwcty_builder_wide_wrap .postbox.xlwcty_inner_height").length > 0) {
            $(".xlwcty_builder_wide_wrap .postbox.xlwcty_inner_height").mCustomScrollbar({
                theme: "minimal"
            });
        }

        /* component builder loading script */
        var xlwctyPostid = ($("input[name='object_id']").val());
        var xlwctyCookieName = "xlwcty_cook_post_tab_open_" + xlwctyPostid;
        var xlwctyCookieVal = xlwcty_getCookie(xlwctyCookieName);
        var CookieVal = xlwcty_getCookie('xlwcty_preview_data');
        if (xlwcty_getCookie('xlwcty_preview_data') !== "") {
            var d = new Date();
            d.setTime(d.getTime() + (1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = "xlwcty_preview_data  = ;" + expires + ";path=/";

            setTimeout(function () {
                window.open(CookieVal, '', 'menubar=no,status=no,width=1000,height=700,resizable=yes');
            }, 600);
        }
    });

    /** FUNCTIONS DECLARATION STARTS **/

    if ($(".xlwcsy_button_wrap").length > 0) {
        var publishElem = $("#publishing-action input#publish");
        publishElem.before($(".xlwcsy_button_wrap").html());
    }

    $(document).on("click", ".xlwcsy_nav_editor", function (e) {
        e.preventDefault();
        document.location.href = builder_page_url[0] + "&id=" + $("input[name='post_ID']").val();
        return false;
    });

    $("select.ajax_chosen_select_products").xlAjaxChosen({
        method: 'GET',
        url: xlwctyParams.ajax_url,
        dataType: 'json',
        afterTypeDelay: 100,
        data: {
            action: 'woocommerce_json_search_products',
            security: xlwctyParams.search_products_nonce
        }
    }, function (data) {
        var terms = {};
        $.each(data, function (i, val) {
            terms[i] = val;
        });
        return terms;
    });

    xlwcty_manage_radio_active($);

    $(".cmb2_debug_order_select select").on('change', function () {
        var elem;
        var order_id = this.value;
        if (order_id == "") {
            elem = $(".page_select_area_inner");
            elem.html("");
            return;
        }
        if (order_id.indexOf("||") >= 0) {
            /* pipe found in order */
            var res = order_id.split("||");
            order_id = res[1];
        }
        elem = $(".page_select_area_inner");
        var _temp_loader = wp.template('xlwcty-debug-page-loader-template');
        elem.html(_temp_loader());

        $.post(ajaxurl, {"action": "xlwcty_get_pages_for_order", "nonce": xlwcty_nonces.xlwcty_get_pages_for_order, "order": order_id}, function (result) {
            var elem = $(".page_select_area_inner");
            var _temp = wp.template('xlwcty-debug-page-template');
            var _errorTemp = wp.template('xlwcty-debug-page-error-template');
            if (result.result == "success") {
                elem.html(_temp(result.page));
            } else {
                elem.html(_errorTemp({"error_text": result.error_text}));
            }
        });
    });
    $("form#order_preview_form").on('submit', function (e) {

        var getFormData = $("form#order_preview_form").serialize();
        var url = $("form#order_preview_form").attr("action") + "?" + getFormData;

        //handling for case when we do not have any orders to display
        if ($("input[name='order_count']").val() == "0") {
            new swal({
                "title": xlwcty_localized_texts.no_orders.title,
                "text": xlwcty_localized_texts.no_orders.text,
                "icon": "error",
                showConfirmButton: true
            });
            e.preventDefault();
            return false;
        }

        //Handling for the case when no component is turned on
        if (isEmptyLayout === true) {
            new swal({
                "title": xlwcty_localized_texts.no_component.title,
                "text": xlwcty_localized_texts.no_component.text,
                "icon": "error",
                showConfirmButton: true
            });
            e.preventDefault();
            return false;
        }

        //handling for the case when we have changes made in the system
        if (Formchanges === true && swal_pass === false) {
            e.preventDefault();
            new swal({
                    title: xlwcty_localized_texts.changes.title,
                    text: xlwcty_localized_texts.changes.text,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: xlwcty_localized_texts.changes.confirmButtonText,
                    closeOnConfirm: false
                },
                function (isConfirm) {
                    if (isConfirm === true) {
                        var d = new Date();
                        d.setTime(d.getTime() + (5 * 60 * 1000));
                        var expires = "expires=" + d.toUTCString();
                        document.cookie = "xlwcty_preview_data  =" + url + ";" + expires + ";path=/";
                        swal_pass = true;
                        $(".xlwcty_admin_sticky_bar a.button.builder_fields_save").trigger("click");
                    }
                });
            return false;
        }
    });

    var postID, d, expires;
    if (pagenow === "xlwcty_thankyou" && adminpage === "post-new-php") {
        $("#edit-slug-box").remove();
        postID = $("input[name='post_ID']").val();
        d = new Date();
        d.setTime(d.getTime() + (5 * 60 * 1000));
        expires = "expires=" + d.toUTCString();

        document.cookie = "xlwcty_is_new_post_" + postID + "" + "=yes;" + expires + ";path=/";
    }

    /**
     * handling cookie to not redirect user after he lands to builder page
     */
    if (pagenow === "toplevel_page_xlwcty_builder") {
        postID = $("input[name='object_id']").val();
        d = new Date();
        d.setTime(d.getTime() + (1 * 1000));
        expires = "expires=" + d.toUTCString();

        document.cookie = "xlwcty_is_new_post_" + postID + "" + "=yes;" + expires + ";path=/";

        //removing all the notices coming on our page
        $("#wpbody-content").children().each(function () {
            if ($(this).hasClass('xlwcty_builder_wide_wrap') === false) {

                $(this).remove();
            }
        });

    }

    if ($('.xlwcty_status_support_text').length > 0) {
        $('.xlwcty_status_support_text').parents(".xlwcty_side_content").removeClass("xlwcty_side_yellow");
        $.post(ajaxurl, {action: 'xlwcty_quick_view'}, function (result) {
            var elem = $(".xlwcty-quick-view-ajaxwrap");
            var _temp = wp.template('xlwcty-quick-view-template');

            if (result.status == "success") {
                elem.html(_temp({"html": result.html}));
                $('.xlwcty_status_support_text').html(result.after_text);
                if (result.nextmove_state == 'failed') {
                    $('.xlwcty_status_support_text').parents(".xlwcty_side_content").addClass("xlwcty_side_yellow");
                    $('.xlwcty_status_support_text').parents(".xlwcty_side_content").find("h3.xlwcty_first_elem").html("NextMove Alert");
                }
            }
        });
    }

    $(document).on('cmb_init', function () {
        $(document).on('cmb_init', function () {
            $(".cmb2-colorpicker").each(function () {
                var oldCb = jQuery(this).iris("option", "change");
                jQuery(this).iris("option", "change", function (event, ui) {
                        oldCb(event, ui);
                        if (XLWCTY_ContentLoaded === true) {
                            Formchanges = true;
                        }
                    }
                );
            });

            var color_result_text = $(".wp-color-result-text");
            if (color_result_text.length > 0) {
                color_result_text.each(function () {
                    $(this).text('Select Color');
                });
            }

        });
    });
})(jQuery);
