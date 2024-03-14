/**
 * @author      WPLoyalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net/
 * */

if (typeof (wlpe_jquery) == 'undefined') {
    wlpe_jquery = jQuery.noConflict();
}

wlpe = window.wlpe || {};
(function (wlpe) {
    wlpe.saveSettings = function () {
        /*let editor = tinyMCE.activeEditor.getContent();*/
        let data = wlpe_jquery('#wlpe-main #wlpe-settings #wlpe-settings_form').serializeArray();
        /*wlpe_jquery.each(data, function (i, field) {
            if (field.name == 'email_template') {
                field.value = editor;
            }
        });*/
        wlpe_jquery('#wlpe-main #wlpe-settings #wlpe-setting-submit-button').attr('disabled', true);
        wlpe_jquery('#wlpe-main #wlpe-settings .wlpe-error').remove();
        wlpe_jquery("#wlpe-main #wlpe-settings #wlpe-setting-submit-button span").html(wlpe_localize_data.saving_button_label);
        wlpe_jquery("#wlpe-main #wlpe-settings .wlpe-button-block .spinner").addClass("is-active");
        wlpe_jquery.ajax({
            data: data,
            type: 'post',
            url: wlpe_localize_data.ajax_url,
            error: function (request, error) {
            },
            success: function (json) {
                alertify.set('notifier', 'position', 'top-right');
                wlpe_jquery('#wlpe-main #wlpe-settings #wlpe-setting-submit-button').attr('disabled', false);
                wlpe_jquery("#wlpe-main #wlpe-settings #wlpe-setting-submit-button span").html(wlpe_localize_data.saved_button_label);
                wlpe_jquery("#wlpe-main #wlpe-settings .wlpe-button-block .spinner").removeClass("is-active");
                if (json.error) {
                    if (json.message) {
                        alertify.error(json.message);
                    }

                    if (json.field_error) {
                        wlpe_jquery.each(json.field_error, function (index, value) {
                            //alertify.error(value);
                            wlpe_jquery(`#wlpe-main #wlpe-settings #wlpe-settings_form .wlpe_${index}_value_block`).after('<span class="wlpe-error" style="color: red;">' + value + '</span>');
                        });
                    }
                } else {
                    alertify.success(json.message);
                    setTimeout(function () {
                        wlpe_jquery("#wlpe-main #wlpe-settings .wlpe-button-block .spinner").removeClass("is-active");
                        location.reload();
                    }, 800);
                }
                if (json.redirect) {
                    window.location.href = json.redirect;
                }
            }
        });
    };

    /**
     * show filter status block
     */
    wlpe.showFilter = function (e) {
        if (wlpe_jquery('#wlpe-main #wlpe-expire-points .wlpe-filter-status-block').is(":visible")) {
            wlpe_jquery('#wlpe-main #wlpe-expire-points .wlpe-filter-status-block').hide()
        } else {
            wlpe_jquery('#wlpe-main #wlpe-expire-points .wlpe-filter-status-block').show()
        }
    }
    wlpe_jquery(document).on('click', '#wlpe-main  #wlpe-expire-points', function (e) {
        if (e.target.className !== 'wlpe-filter-section') {
            wlpe_jquery('#wlpe-main #wlpe-expire-points .wlpe-filter-status-block').hide()
        }
    })
    /**
     * filter status form action block
     */
    wlpe.filterPoints = function (form_id, value) {
        wlpe_jquery(form_id + " input[name=\"point_sort\"]").val(value);
        wlpe_jquery(form_id).submit();
    }
    /**
     * email expiry enable
     */
    wlpe.toggleSection = function () {
        let value = wlpe_jquery('#wlpe-main #wlpe-settings #wlpe_enable_expire_email:checked').val();
        if (value == 1) {
            wlpe_jquery('#wlpe-main #wlpe-settings .wlpe-email-notification').css('display', 'block');
            wlpe_jquery('#wlpe-main #wlpe-settings .wlpe-email-expiry-editor').css('display', 'flex');
        } else {
            wlpe_jquery('#wlpe-main #wlpe-settings .wlpe-email-notification').hide();
            wlpe_jquery('#wlpe-main #wlpe-settings .wlpe-email-expiry-editor').hide();
        }
    };

    wlpe.enableExpiryPoint = function (id) {
        if (wlpe_jquery('#wlpe-main #wlpe-settings #' + id).is(':checked')) {
            wlpe_jquery('#wlpe-main #wlpe-settings #' + id).val('yes')
            wlpe_jquery('#wlpe-main #wlpe-settings #' + id + '_section').css('display', 'block');
        } else {
            wlpe_jquery('#wlpe-main #wlpe-settings #' + id).val('no')
            wlpe_jquery('#wlpe-main #wlpe-settings #' + id + '_section').css('display', 'none');
        }
    }
    /**
     * show date picker
     * @param type
     * @param row_id
     * @param date
     */

    wlpe.showWlpeDatePicker = function (type, row_id, date) {
        wlpe_jquery(`#wlpe-main #wlpe-expire-points ${'.' + type}-update-date-${row_id}`).show();
        wlpe_jquery(`#wlpe-main #wlpe-expire-points ${'.' + type}-expiery-date-${row_id}`).hide()
    }

    /**
     * close date picker
     * @param type
     * @param row_id
     */

    wlpe.closeWlpeDatePicker = function (type, row_id) {
        wlpe_jquery(`#wlpe-main #wlpe-expire-points ${'.' + type}-update-date-${row_id}`).hide();
        wlpe_jquery(`#wlpe-main #wlpe-expire-points ${'.' + type}-expiery-date-${row_id}`).show()
    }

    /**
     * update expiry date field in manage point page
     */

    wlpe.updatePointExpieryDate = function (row_id,type="") {
        let status = wlpe.validateUpdateDate(row_id,type);
        if (!status) return;
        var updated_date = wlpe_jquery(`#wlpe-main #wlpe-expire-points .wlpe-update-point-expiery-date-${row_id}`).val()
        if (!updated_date) return
        wlpe_jquery(`#wlpe-main #wlpe-expire-points .point-update-date-${row_id} .spinner`).addClass('is-active')
        wlpe_jquery.ajax({
            type: 'post',
            url: wlpe_localize_data.ajax_url,
            data: {
                action_type: 'point',
                row_id: row_id,
                action: 'wlpe_update_expire_date',
                expiry_point_date: updated_date,
                wlpe_nonce: wlpe_localize_data.update_expiry_date_nonce
            },

            success: function (res) {
                alertify.set('notifier', 'position', 'top-right');
                if (res.status) {
                    alertify.success(res.data.message);
                    location.reload()
                } else {
                    alertify.error(res.data.message)
                }
            },
            error: function (error) {
            }
        });
    }
    wlpe.validateUpdateDate = function (row_id,type = "") {
        var emailExpiryDate = wlpe_jquery('#wlpe-main #wlpe-expire-points #wlpe-email-expiry-date-picker-' + row_id).val()
        var expiryDate = wlpe_jquery('#wlpe-main #wlpe-expire-points #wlpe-point-expiry-date-picker-' + row_id).val()
        wlpe_jquery('#wlpe-main #wlpe-expire-points').removeClass('wlpe-error');
        let today_date = new Date();
        today_date = today_date.getFullYear() + '-' + wlpe.padZero(today_date.getMonth() + 1) + '-' + wlpe.padZero(today_date.getDate());
        let status = true;
        if (type === 'point-expiry-date' && (expiryDate === "" || !(expiryDate >= today_date))){
            wlpe_jquery('#wlpe-main #wlpe-expire-points #wlpe-point-expiry-date-picker-' + row_id).addClass('wlpe-error');
            status = false;
        }
        if (type === 'email-expiry-date' && (emailExpiryDate === "" || !(emailExpiryDate >= today_date) || !(emailExpiryDate <= expiryDate ))){
            wlpe_jquery('#wlpe-main #wlpe-expire-points #wlpe-email-expiry-date-picker-' + row_id).addClass('wlpe-error');
            status = false;
        }
        return status;
    }
    wlpe.padZero = function(number) {
        if (number < 10) {
            return '0' + number;
        }
        return number;
    }
    /**
     * update email expity date field in manage point page
     * @param row_id
     */

    wlpe.updateEmailExpiryDate = function (row_id,type="") {
        let status = wlpe.validateUpdateDate(row_id,type);
        if (!status) return;
        var emailExpiryDate = wlpe_jquery('#wlpe-main #wlpe-expire-points .wlpe-update-email-expiery-date-' + row_id).val()
        if (!emailExpiryDate) return;
        wlpe_jquery(`#wlpe-main #wlpe-expire-points .email-update-date-${row_id} .spinner`).addClass('is-active')
        wlpe_jquery.ajax({
            type: 'post',
            url: wlpe_localize_data.ajax_url,
            data: {
                action_type: 'email',
                row_id: row_id,
                action: 'wlpe_update_expire_date',
                expiry_email_date: emailExpiryDate,
                wlpe_nonce: wlpe_localize_data.update_expiry_date_nonce,
            },

            success: function (result) {
                alertify.set('notifier', 'position', 'top-right');
                if (result.status) {
                    alertify.success(result.data.message);
                    location.reload()
                } else {
                    alertify.error(result.data.message)
                }
            },
            error: function (error) {
            }
        })
    }
})
(wlpe);