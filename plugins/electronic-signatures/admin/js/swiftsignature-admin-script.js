
/*------------ swift form -----------------*/
var $compain_var = getUrlVars()['utm_source'];
/*Set cookie if compaign vars exists*/
if ($compain_var === undefined) {
    //do nothing
} else {
    setCookie('compain_var', window.location.href);
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}
/*Cookie functions*/
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1);
        if (c.indexOf(name) == 0)
            return c.substring(name.length, c.length);
    }
    return "";
}
/*---- ----*/
jQuery(document).ready(function ($) {
    /* swift form */
    if (jQuery('#SC_fh_timezone').size() > 0) {
        jQuery('#SC_fh_timezone').val(jstz.determine().name());
    }
    if (jQuery('#SC_fh_capturepage').size() > 0) {
        jQuery('#SC_fh_capturepage').val(window.location.origin + window.location.pathname);
    }
    if (jQuery('#SC_fh_language').size() > 0) {
        jQuery('#SC_fh_language').val(window.navigator.userLanguage || window.navigator.language);
    }
    jQuery("#referer").val(document.URL);
    /*check if cookie exists then add the values in variable*/
    if (getCookie('compain_var')) {
        jQuery('#trackingvars').val(getCookie('compain_var'));
    }

    /* plugin activation notice dismis.*/
    jQuery(".ttip").tooltip();
    jQuery(".ssing-notice .notice-dismiss").on('click', function () {
        var data = {
            'action': 'ssign_dismiss_notice'
        };
        jQuery.post(ssign_ajax_object.ajax_url, data, function (response) {

        });
    });

    jQuery(".ssign-btn-add-new,.ssign-edit").on('click', function (e) {
        var modalID = jQuery(this).attr('data-modal');
        var btnType = jQuery(this).attr('data-btn');

        var modalTitleText = btnType === "edit" ? 'Edit' : 'Add';
        var modalBtn = btnType === "edit" ? 'Update' : 'Add';
        jQuery(".ssign_modal_title").text('');
        jQuery(".ssign_modal_title").text(modalTitleText);
        jQuery(".ssign-modal-btn").text(modalBtn);
        jQuery(".ssign-modal-btn").val(modalBtn);

        if (btnType == 'edit') {
            e.preventDefault();
            var pdf_id = jQuery(this).attr('data-pdf-id');

            var data = {
                'action': 'swift_sign_edit_pdf',
                'data': pdf_id
            };
            jQuery.post(ajaxurl, data, function (response) {
                var res = jQuery.parseJSON(response);
                jQuery("#pdf_name").val(res['pdf_name']);
                jQuery("#frm_ssign_pdfs").append('<input class="edit-hidden" type="hidden" name="pdf_url" value="' + res['pdf_url'] + '" />');
                jQuery("#frm_ssign_pdfs").append('<input class="edit-hidden" type="hidden" name="pdf_id" value="' + res['pdf_id'] + '" />');
                jQuery("#frm_ssign_pdfs").append('<input class="edit-hidden" type="hidden" name="pdf_path" value="' + res['pdf_path'] + '" />');
                jQuery(modalID).fadeIn();
            });
        } else {
            jQuery(modalID).fadeIn();
        }

    });

    //Delete pdf
    jQuery(".ssign-delete").on('click', function (e) {
        e.preventDefault();
        if (confirm("Are you sure you want to delete this PDF?")) {
            var del_pdf_id = jQuery(this).attr('data-pdf-id');
            if (del_pdf_id) {
                jQuery(this).after('<input type="hidden" name="pdf_id" value="' + del_pdf_id + '" />');
                jQuery("#frm_table_pdf").submit();
            }
        }
    });
    //modal close
    jQuery(".ssign_modal_close").on('click', function () {
        jQuery('.ssign_modal_container form').trigger("reset");
        jQuery('.edit-hidden').remove();
        jQuery(".ssign_modal").fadeOut();
    });

    /* Dashboard */
    // subscribe form submit
    jQuery(".ssign-subscribe").on("click", function (e) {
        var error = '';
        jQuery(".ssign-error").remove();
        if (jQuery.trim(jQuery("#email").val()) === '') {
            jQuery("#frm_ssign_subscribe").after('<span class="ssign-error" style="color:red;margin-bottom:10px;">Email is required.</span>');
            error++;
        }

        if (error > 0) {
            e.preventDefault();
        } else {
            var data = {
                'action': 'swiftsign_subscribe',
                'data': jQuery("#frm_ssing_subscribe").serialize()
            };
            jQuery.post(ajaxurl, data, function (response) {
                if (response == 1) {
                    jQuery(".dashboard-subscribe-block").fadeOut();
                }
            });
        }
    });

    //subscribe box close
    jQuery(".close-subscribe-block").on("click", function () {
        var data = {
            'action': 'swiftsign_close_subscribe',
        };
        jQuery.post(ajaxurl, data, function (response) {
            if (response == 1) {
                jQuery(".dashboard-subscribe-toggle").fadeIn();
                jQuery(".dashboard-subscribe-block").fadeOut();
            }
        });
    });

    //subscribe checkbox
    jQuery("#swift_signature_subscribe").on("click", function () {
        jQuery(this).attr("disabled", "disabled");
        jQuery('.dashboard-subscribe-toggle spinner').css('visibility', 'visible');
        var check = jQuery("#swift_signature_subscribe:checked").val();
        if (check == 1) {
            var data = {
                'action': 'swiftsign_subscribe_checkbox',
            };
            jQuery.post(ajaxurl, data, function (response) {
                if (response == 1) {
                    jQuery(".swift_signature_subscribe").removeAttr("checked");
                    jQuery(".swift_signature_subscribe").removeAttr("disabled");
                    jQuery(".dashboard-subscribe-toggle").fadeOut();
                    jQuery(".dashboard-subscribe-block").fadeIn();
                }
            });
        }
    });

    /** envelope **/

    // jQuery(".ssign-btn-add-new-envelope,.ssign-envelope-edit").on('click', function(e) {
    jQuery(".ssign-btn-add-new-envelope").on('click', function (e) {
        var modalID = jQuery(this).attr('data-modal');
        jQuery(modalID).fadeIn();
    });

    if (jQuery("time.timeago").length > 0) {
        jQuery("time.timeago").timeago();
    }

    /* swiftcloud upgrade notice */
    jQuery(document).on('click', '.swiftsign_upgrade_notice .notice-dismiss', function () {
        jQuery.ajax({
            url: ssign_ajax_object.ajax_url,
            data: {
                action: 'ss_upgrade_dismiss_notice'
            }
        });
    });
});