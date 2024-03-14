/**
 * @author      Flycart (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.flycart.org
 * */

if (typeof (wlr_jquery) == 'undefined') {
    wlr_jquery = jQuery.noConflict();
}

wlr = window.wlr || {};
(function (wlr) {
    wlr.show_notice = function (html_element, $target) {
        if (!$target) {
            $target = wlr_jquery('.woocommerce-notices-wrapper:first') ||
                wlr_jquery('.cart-empty').closest('.woocommerce') ||
                wlr_jquery('.woocommerce-cart-form');
        }
        $target.prepend(html_element);
    };
    wlr.copyLink = function (link_id) {
        /* Get the text field */
        var copyText = document.getElementById(link_id);
        /* Select the text field */
        copyText.select();
        copyText.focus();
        copyText.select();
        document.execCommand('copy');
    };
    let click_social_status = [];
    wlr.socialShare = function (url, action) {
        wlr_localize_data.social_share_window_open ? window.open(url, action, 'width=626, height=436') : window.open(url, '_blank');
        if (!click_social_status.includes(action)) {
            var data = {
                action: 'wlr_social_' + action,
                wlr_nonce: wlr_localize_data.apply_share_nonce
            };
            wlr.award_social_point(data);
            click_social_status.push(action);
        }
    };

    wlr.followUpShare = function (id, url, action) {
        wlr_localize_data.followup_share_window_open ? window.open(url, action, 'width=626, height=436') : window.open(url, '_blank');
        if (!click_social_status.includes(action)) {
            var data = {
                action: 'wlr_follow_' + action,
                wlr_nonce: wlr_localize_data.apply_share_nonce,
                id: id
            };
            wlr.award_social_point(data);
            click_social_status.push(action);
        }
    };
    wlr.award_social_point = function (data) {
        wlr_jquery.ajax({
            data: data,
            type: 'post',
            url: wlr_localize_data.ajax_url,
            error: function (request, error) {
                /*alertify.error(error);*/
            },
            success: function (json) {
            }
        });
    };

    wlr_jquery(document).on('revertEnable', function (e, id) {
        wlr_jquery(".wlr-myaccount-page .wlr-revert-tool i").toggleClass("wlrf-arrow-down wlrf-arrow_right");
        wlr_jquery(id).toggleClass("wlr-revert-active  ");
    });
    wlr_jquery(document).on('wlr_copy_link', function (e, link_id) {
        var copyText = document.getElementById(link_id);
        /* Select the text field */
        copyText.disabled = false;
        copyText.select();
        copyText.focus();
        copyText.select();
        document.execCommand('copy');
        copyText.disabled = true;
    });

    wlr_jquery(document).on('readMoreLessContent', function (e, id) {
        wlr_jquery('.wlr-myaccount-page ' + id).toggleClass("show-less-content show-more-content");
    })
    wlr_jquery(document).on('wlr_my_reward_section', function (e, type, is_new_template = false, page_type = '') {
        wlr_jquery('.wlr-myaccount-page .wlr-my-rewards-title').removeClass('active');
        wlr_jquery('.wlr-myaccount-page .wlr-user-reward-contents .active').removeClass('active');
        wlr_jquery('.wlr-myaccount-page .wlr-' + type + '-title').addClass('active');
        wlr_jquery('.wlr-myaccount-page .wlr-' + type + '-container').addClass('active');
        if (is_new_template) {
            const url = window.location.href;
            const indexOfSegment = url.indexOf("loyalty_reward");
            if (indexOfSegment !== -1) {
                const newUrl = url.substring(0, indexOfSegment + "loyalty_reward".length);
                history.pushState({}, document.title, newUrl);
            }
        }
        window.location.href = "#wlr-your-reward";
    });
    wlr_jquery(document).on('click', '.wlr-coupons-expired-title', function () {
        wlr_jquery('.wlr-myaccount-page .wlr-toggle-arrow').toggleClass('wlrf-arrow-down wlrf-arrow-up');
        wlr_jquery('.wlr-myaccount-page .wlr-user-expired-reward-section').toggleClass('active ');
    });
    /*  wlr_jquery(document).on('wlr_disable_birthday_date_edit', function (e) {
          e.preventDefault();
      });*/
    wlr_jquery(document).on('wlr_get_used_reward', function (e) {
        let used_reward = wlr_jquery(".wlr-myaccount-page #wlr-points #wlr_currency_list").data("user-used-reward");
        let used_reward_count = wlr_jquery(".wlr-myaccount-page #wlr-points #wlr_currency_list").data("user-used-reward-count");
        let currency = wlr_jquery(".wlr-myaccount-page #wlr-points #wlr_currency_list").val();
        wlr_jquery(".wlr-myaccount-page #wlr-used-reward-value-count").html(used_reward_count[currency]);
        wlr_jquery(".wlr-myaccount-page #wlr-used-reward-value").html(used_reward[currency]);
    });
    wlr_jquery(document).on('wlr_copy_coupon', function (e, coupon_id, icon_id) {
        var temp = wlr_jquery("<input>");
        wlr_jquery("body").append(temp);
        temp.val(wlr_jquery(coupon_id).text()).select();
        document.execCommand("copy");
        alertify.set('notifier', 'position', 'top-right');
        // alertify.success("copied");
        wlr_jquery('.wlr-myaccount-page .wlr-coupon-card .wlrf-save').toggleClass("wlrf-copy wlrf-save");
        wlr_jquery(icon_id).toggleClass("wlrf-copy wlrf-save");
        temp.remove();
    });
    wlr_jquery(document).on('wlr_enable_email_sent', function (e, id) {
        let enable_sent_mail = wlr_jquery("#" + id).is(':checked') ? 1 : 0;
        wlr_jquery.ajax({
            url: wlr_localize_data.ajax_url,
            type: "POST",
            dataType: 'json',
            data: {
                action: "wlr_enable_email_sent",
                wlr_nonce: wlr_localize_data.enable_sent_email_nonce,
                is_allow_send_email: enable_sent_mail,
            },
            success: function (json) {
                window.location.reload();
            }
        });
    });

    let click_status = [];
    wlr_jquery(document).on('wlr_apply_reward_action', function (e, id, type, button_id = "", is_redirect_to_url = false, url = "") {
        if (!click_status.includes(id)) {
            if (button_id !== "") {
                wlr.disableButton(button_id);
            }
            var data = {
                action: "wlr_apply_reward",
                wlr_nonce: wlr_localize_data.wlr_reward_nonce,
                reward_id: id,
                type: type
            };
            wlr_jquery.ajax({
                type: "POST",
                url: wlr_localize_data.ajax_url,
                data: data,
                dataType: "json",
                before: function () {

                },
                success: function (json) {
                    (is_redirect_to_url && url !== '' && window.location.href !== url && json.data.is_coupon_exist) ? window.location.href = url : window.location.reload();
                }
            });
            click_status.push(id);
        }
    });
    wlr_jquery(document).on('wlr_redirect_url', function (e, url) {
        window.location.href = url;
    });
    wlr_jquery(document).on('wlr_my_rewards_pagination', function (e, type, page_number, page_type) {
        let endpoint_url = wlr_jquery('.wlr-myaccount-page .wlr-coupons-list #wlr-endpoint-url').data('url');
        wlr_jquery.ajax({
            type: "POST",
            url: wlr_localize_data.ajax_url,
            data: {
                type: type,
                page_number: page_number,
                page_type: page_type,
                action: 'wlr_my_rewards_pagination',
                wlr_nonce: wlr_localize_data.pagination_nonce,
                endpoint_url: endpoint_url,
            },
            dataType: "json",
            before: function () {

            },
            success: function (res) {
                if (res.status) {
                    var contentToReplace = wlr_jquery('.wlr-' + type + '-container');
                    contentToReplace.css('opacity', 0);
                    setTimeout(function () {
                        contentToReplace.html(res.data.html);
                        contentToReplace.css('opacity', 1);
                    }, 350);
                }
            }
        });
    });
    wlr_jquery(document).on('wlr_apply_point_conversion_reward', function (e, id, type, point, input_id = "", button_id = "", is_redirect_to_url = false, url = "") {
        if (button_id !== "") {
            wlr.disableButton(button_id);
        }
        let value = wlr_jquery(input_id).val();
        let data = {
            action: "wlr_apply_reward",
            wlr_nonce: wlr_localize_data.wlr_reward_nonce,
            reward_id: id,
            type: type,
            points: value,
        };
        wlr_jquery.ajax({
            type: "POST",
            url: wlr_localize_data.ajax_url,
            data: data,
            dataType: "json",
            before: function () {

            },
            success: function (json) {
                (is_redirect_to_url && url !== '' && window.location.href !== url && json.data.is_coupon_exist) ? window.location.href = url : window.location.reload();
            }
        });
    });
    wlr_jquery(document).on('wlr_apply_point_conversion_reward_action', function (e, id, type, point, button_id = "") {
        if (button_id !== "") {
            wlr.disableButton(button_id);
        }
        alertify.prompt(wlr_localize_data.point_popup_message, point, function (evt, value) {
            var data = {
                action: "wlr_apply_reward",
                wlr_nonce: wlr_localize_data.wlr_reward_nonce,
                reward_id: id,
                type: type,
                points: value,
            };
            wlr_jquery.ajax({
                type: "POST",
                url: wlr_localize_data.ajax_url,
                data: data,
                dataType: "json",
                before: function () {

                },
                success: function (json) {
                    window.location.reload();
                    /*if (json['data']['redirect']) {
                        window.location = json['data']['redirect'];
                    }*/
                }
            });
        }).setHeader("").set('labels', {
            ok: wlr_localize_data.popup_ok,
            cancel: wlr_localize_data.popup_cancel
        }).set('oncancel', function () {
            window.location.reload();
        });
    });
    wlr.disableButton = function (button_id, is_revoke_coupon = false) {
        let buttons = wlr_jquery('.wlr-myaccount-page').find('.wlr-button');
        (!is_revoke_coupon) ? wlr_jquery(button_id).toggleClass("wlr-button-action wlr-button-spinner") : wlr_jquery(button_id).addClass("wlr-button-spinner");
        wlr_jquery.each(buttons, function (index, val) {
            if (wlr_jquery(val).hasClass('wlr-button-action')) {
                wlr_jquery(val).css("background", "#cccccc");
            }
            val.setAttribute('disabled', true);
            val.removeAttribute('onclick');
        });
        if (wlr_jquery(button_id).hasClass('wlr-button-spinner')) {
            wlr_jquery(button_id).html('<div class="wlr-spinner">\n' +
                '    <span class="spinner" style="border-top: 4px ' + wlr_localize_data.theme_color + ' solid;"></span>\n' +
                '</div>');
        }
    }
    wlr_jquery(document).on('wlr_calculate_point_conversion', function (e, id, response_id) {
        let data = wlr_jquery('.wlr-myaccount-page #' + id);
        let require_point = data.attr('data-require-point');
        require_point = isNaN(parseInt(require_point)) ? 0 : parseInt(require_point);
        let discount_value = data.attr('data-discount-value');
        discount_value = isNaN(parseFloat(discount_value)) ? 0 : parseFloat(discount_value);
        let available_point = data.attr('data-available-point');
        available_point = isNaN(parseInt(available_point)) ? 0 : parseInt(available_point);
        let input_point = isNaN(parseInt(data.val())) ? 0 : parseInt(data.val());
        if (input_point > available_point || input_point === 0) {
            wlr_jquery('.wlr-myaccount-page .wlr-input-point-section #' + id).css({
                "outline": "1px solid red",
                "border-radius": "6px 0 0 6px"
            });
            wlr_jquery('.wlr-myaccount-page .wlr-point-conversion-section #' + id + '_button').hide();
        } else {
            wlr_jquery('.wlr-myaccount-page .wlr-input-point-section #' + id).css({"outline": "unset"});
            wlr_jquery('.wlr-myaccount-page .wlr-point-conversion-section #' + id + '_button').show();
        }
        let input_value = (input_point / require_point) * discount_value;
        input_value = input_value.toFixed(2);
        wlr_jquery('.wlr-myaccount-page #' + response_id).text(input_value);
    });
    wlr_jquery(document).on('wlr_apply_social_share', function (e, url, action) {
        wlr.socialShare(url, action);
    });
    wlr_jquery(document).on('wlr_apply_followup_share', function (e, id, url, action) {
        wlr.followUpShare(id, url, action);
    });
    wlr_jquery(document).on("click", "#wlr-reward-link", function (e) {
        var data = {
            action: "wlr_show_loyalty_rewards",
            wlr_nonce: wlr_localize_data.wlr_redeem_nonce,
        };
        wlr_jquery(this).css('pointer-events', 'none');
        wlr_jquery(this).after('<div class="wlr-dot-pulse"></div>');
        wlr_jquery.ajax({
            type: "POST",
            url: wlr_localize_data.ajax_url,
            data: data,
            dataType: "json",
            before: function () {

            },
            success: function (json) {
                alertify.defaults.defaultFocusOff = true;
                wlr_jquery('.wlr-dot-pulse').remove();
                wlr_jquery('#wlr-reward-link').css('pointer-events', '');
                alertify.alert(json.data.html).setHeader('').set('label', wlr_localize_data.popup_ok);
            }
        });
    });
    wlr_jquery(document).on("click", "#wlr_point_apply_discount_button", function (e) {
        var is_partial = wlr_jquery("#wlr_is_partial").val();
        if (is_partial === 1) {
            e.preventDefault();
            return false;
        }
        var is_checkout = wlr_jquery("#wlr_is_checkout").val();
        var data = {
            action: "wlr_apply_loyal_discount",
            discount_amount: wlr_jquery("#wlr_discount_point").val(),
            wlr_nonce: wlr_localize_data.wlr_discount_none,
        };
        wlr_jquery.ajax({
            type: "POST",
            url: wlr_localize_data.ajax_url,
            data: data,
            dataType: "json",
            success: function (json) {
                if (is_checkout == 1) {
                    if (json.success) {
                        wlr_jquery(document.body).trigger("update_checkout", {update_shipping_method: true});
                    }
                } else {
                    wlr_jquery('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();
                    if (json.data.message) {
                        wlr.show_notice(json.data.message);
                    }
                    wlr_jquery(document.body).trigger('wc_update_cart', true);
                }
            }
        });
        return false;
    });
    wlr_jquery(document).ready(function () {
        wlr_jquery(document).on("click", ".wlr_change_product", function () {
            var product_id = wlr_jquery(this).attr('data-pid');
            var rule_unique_id = wlr_jquery(this).attr('data-rule_id');
            var parent_id = wlr_jquery(this).attr('data-parent_id');

            var data = {
                action: 'wlr_change_reward_product_in_cart',
                variant_id: product_id,
                rule_unique_id: rule_unique_id,
                product_id: parent_id,
                wlr_nonce: wlr_localize_data.wlr_reward_nonce,
            };
            wlr_jquery.ajax({
                url: wlr_localize_data.ajax_url,
                data: data,
                type: 'POST',
                success: function (response) {
                    if (response.success == true) {
                        wlr_jquery("[name='update_cart']").removeAttr('disabled');
                        wlr_jquery("[name='update_cart']").trigger("click");
                    }
                },
                error: function (response) {
                }
            });
        });

        wlr_jquery(document).on("click", '.wlr-select-free-variant-product-toggle', function (e) {
            e.preventDefault();
            this.classList.toggle("wlr-select-free-variant-product-toggle-active");
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                wlr_jquery(panel).slideUp(1000);
            } else {
                wlr_jquery(panel).slideDown(1000);
            }
        });
    });

    wlr_jquery(document).on('wlr_update_birthday_action', function (e, id, date_id, type) {
        var data = {
            action: "wlr_update_birthday",
            wlr_nonce: wlr_localize_data.wlr_reward_nonce,
            campaign_id: id,
            birth_date: wlr_jquery(date_id).val()
        };
        wlr_jquery.ajax({
            type: "POST",
            url: wlr_localize_data.ajax_url,
            data: data,
            dataType: "json",
            before: function () {

            },
            success: function (json) {
                window.location.reload();
            }
        });
    });
    wlr.checkDateEmpty = function (data, type) {
        if ((typeof data !== "object" && Object.keys(data).length === 0) || (typeof type !== "string")) return false;
        return (wlr.validateDate(data, type));
    }
    wlr.validateDate = function (dom_data, type) {
        if ((typeof dom_data !== "object" && Object.keys(dom_data).length === 0) || (typeof type !== "string")) return false;
        let value = parseInt(dom_data.value);
        let status;
        switch (type) {
            case 'Y':
                let current_year = new Date().getFullYear();
                status = (isNaN(value) || (value < 1823) || (value > current_year) || (dom_data.value.length !== 4)) ? wlr.validationError(dom_data, true) :
                    wlr.validationError(dom_data, false);
                break;
            case 'm':
                status = (isNaN(value) || (value < 1) || (value > 12)) ? wlr.validationError(dom_data, true) :
                    wlr.validationError(dom_data, false);
                break;
            case 'd':
                status = (isNaN(value) || (value < 1) || (value > 31)) ? wlr.validationError(dom_data, true) :
                    wlr.validationError(dom_data, false);
                break;
        }
        return status;
    }
    wlr.validationError = function (dom_data, is_error = true) {
        if ((typeof dom_data !== "object" && Object.keys(dom_data).length === 0) || (typeof is_error !== "boolean")) return false;
        let status = false;
        if (is_error) {
            dom_data.style.border = "3px solid red";
        } else {
            status = true;
            dom_data.style.border = "";
        }
        return status;
    }

    wlr_jquery(document).on('wlr_update_birthday_date_action', function (e, id, campaign_id, type) {
        const day = document.getElementById("wlr-customer-birth-date-day-" + campaign_id);
        const month = document.getElementById("wlr-customer-birth-date-month-" + campaign_id);
        const year = document.getElementById("wlr-customer-birth-date-year-" + campaign_id);
        if (!wlr.checkDateEmpty(day, 'd') || !wlr.checkDateEmpty(month, 'm') || !wlr.checkDateEmpty(year, 'Y')) {
            return;
        }
        let date = (year.value + "-" + month.value + "-" + day.value);
        let data = {
            action: "wlr_update_birthday",
            wlr_nonce: wlr_localize_data.wlr_reward_nonce,
            campaign_id: id,
            birth_date: date,
        };
        wlr_jquery.ajax({
            type: "POST",
            url: wlr_localize_data.ajax_url,
            data: data,
            dataType: "json",
            before: function () {

            },
            success: function (json) {
                window.location.reload();
            }
        });
    });
    wlr_jquery(document).on('wlr_revoke_coupon', function (e, id, code) {
        wlr.disableButton("#wlr-" + id + "-" + code, true);
        wlr_jquery("#wlr-" + id + "-" + code).removeAttr("onclick");
        alertify.confirm().set({'closable': false});
        alertify.confirm('<span>' + wlr_localize_data.revoke_coupon_message + '</span>', function (evt, value) {
            var data = {
                action: "wlr_revoke_coupon",
                wlr_nonce: wlr_localize_data.revoke_coupon_nonce,
                user_reward_id: id
            };
            wlr_jquery.ajax({
                type: "POST",
                url: wlr_localize_data.ajax_url,
                data: data,
                dataType: "json",
                before: function () {

                },
                success: function (json) {
                    window.location.reload();
                }
            });
        }, function (evt, value) {
            wlr_jquery(document).trigger('revertEnable', ['#wlr-' + id + '-' + code]);
            window.location.reload();
        }).setHeader("").set('labels', {
            ok: wlr_localize_data.popup_ok,
            cancel: wlr_localize_data.popup_cancel
        });
    });
    if (wlr_localize_data.is_pro) {
        let wlr_ref = localStorage.getItem('wployalty_referral_code');
        if (wlr_ref) {
            let data = {
                action: "wlr_update_referral_code",
                wlr_nonce: wlr_localize_data.wlr_reward_nonce,
                referral_code: localStorage.getItem('wployalty_referral_code')
            };
            wlr_jquery.ajax({
                type: "POST",
                url: wlr_localize_data.ajax_url,
                data: data,
                dataType: "json",
                before: function () {

                },
                success: function (json) {

                }
            });
        }
    }
})(wlr_jquery);
