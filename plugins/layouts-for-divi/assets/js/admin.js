jQuery(document).ready(function () {

    var lef_cur_url = window.location.href;
    var lef_res = lef_cur_url.substring(0, lef_cur_url.lastIndexOf("/") + 1);

    //Install layouts popup
    jQuery('.installbtn').each(function (idx, item) {
        var winnerId = "install-" + idx;
        this.id = winnerId;
        jQuery(this).click(function () {
            jQuery(".lfd-msg").show();
            jQuery(".lfd-msg").text('Import this template via one click');
            jQuery(".lfd-page-create, .lfd-create-page-btn").show();
            jQuery('input[type=text]').show();
            jQuery('.lfd-import-btn').bind('click');
            jQuery('.lfd-create-page-btn').bind('click');
            var btn = jQuery("#install-" + idx);
            var span = jQuery(".lfd-close-icon");
            var popId = jQuery('#content-in-' + idx);
            jQuery(popId).addClass('on');
            jQuery('body').addClass('install-popup');
            span.click(function () {
                jQuery(popId).removeClass('on');
                jQuery('body').removeClass('install-popup');
            });
        });
    });

    //Preview layouts popup
    jQuery('.previewbtn').each(function (idx, item) {

        var winnerId = "preview-" + idx;
        this.id = winnerId;
        jQuery(this).click(function () {
            jQuery(".lfd-msg").show();
            jQuery(".lfd-msg").text('Import this template via one click');
            jQuery(".lfd-page-create").show();
            jQuery(".lfd-page-create, .lfd-create-page-btn").show();
            jQuery('input[type=text]').show();
            jQuery('.lfd-import-btn').bind('click');
            jQuery('.lfd-buy-btn').bind('click');
            jQuery('.lfd-create-page-btn').bind('click');
            jQuery('#preview-in-' + idx + " iframe").attr("src", jQuery(this).attr('data-url'));
            var btn = jQuery("#preview-" + idx);
            var span = jQuery(".lfd-close-icon");
            var popId = jQuery('#preview-in-' + idx);
            jQuery(popId).addClass('on');
            jQuery('body').addClass('preview-popup');
            span.click(function () {
                jQuery(popId).removeClass('on');
                jQuery('body').removeClass('preview-popup');
            });
        });
    });

    //Filter layouts category js
    jQuery.fn.categoryFilter = function (selector) {
        this.click(function () {
            var categoryValue = jQuery(this).attr('data-filter');
            jQuery(this).addClass('active');
            jQuery(this).parent().siblings().children().removeClass('active');

            if (categoryValue == "all") {
                jQuery('.lfd_filter').show(800);
            } else {
                jQuery(".lfd_filter").not('.' + categoryValue).hide('800');
                jQuery('.lfd_filter').filter('.' + categoryValue).show('800');
            }
        });
    }

    jQuery('.lfd-category-filter').categoryFilter();

    jQuery(".lfd-close-icon").click(function () {
        jQuery(".lfd-import-btn").show();
        jQuery(".lfd-edit-template").hide();
        jQuery(".lfd-msg").hide();
        jQuery(".lfd-page-edit").hide();
        jQuery('.lfd-create-page-btn').removeClass('lfd-disabled');
        jQuery('.lfd-import-btn').removeClass('lfd-disabled');
        jQuery('input[type=text]').val('');
    });

    //sync latest template
    jQuery(".lfd-sync-btn").on('click', function () {

        jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'handle_sync',
            },
            beforeSend: function () {
                jQuery('.lfd-sync-btn').text(js_object.lfd_sync);
            },
            success: function (res) {
                var res = res.slice(0, -1);
                if (res == 'success') {
                    setTimeout(function () {
                        Toastify({
                            text: js_object.lfd_sync_suc,
                            gravity: "right",
                            duration: 4500,
                            close: true,
                            backgroundColor: "linear-gradient(135deg, rgb( 99, 89, 241 ) 0%, rgb( 49, 181, 251 ) 100%)",
                        }).showToast();
                    }, 2000);
                    setTimeout(function () {
                        window.location.href = lef_cur_url;
                    }, 5000);
                } else {
                    setTimeout(function () {
                        Toastify({
                            text: js_object.lfd_sync_fai,
                            gravity: "right",
                            duration: 4500,
                            close: true,
                            backgroundColor: "linear-gradient(135deg, rgb( 99, 89, 241 ) 0%, rgb( 49, 181, 251 ) 100%)",
                        }).showToast();
                    }, 2000);
                    setTimeout(function () {
                        window.location.href = lef_cur_url;
                    }, 5000);
                }
            },

        });
    });

    //Import Template js
    jQuery(".lfd-import-btn").on('click', function () {
        jQuery(".lfd-loader").show();
        var template_id = jQuery(this).attr("data-template-id");
        var with_page = jQuery(".lfd-page-name-" + template_id).val();
        jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'handle_import',
                template_id: template_id,
                with_page: with_page,
            },
            beforeSend: function () {
                jQuery('.lfd-create-page-btn').addClass('lfd-disabled');
                jQuery(".lfd-import-btn").hide();
                jQuery(".lfd-loader").html("<div class='lfd-gradient-loader'></div>");
            },
            success: function (result) {
                jQuery(".lfd-loader").hide();
                if (result == 0) {
                    jQuery(".lfd-msg").text(js_object.lfd_error);
                } else {
                    jQuery(".lfd-msg").text(js_object.lfd_tem_msg);
                    jQuery(".lfd-edit-template").show().attr("href", lef_res + 'post.php?post=' + result + "&action=edit");
                }
            },
            setTimeout: 1000,
        });
    });

    //Import Template with page name js
    jQuery(".lfd-create-page-btn").on('click', function () {
        var template_id = jQuery(this).attr("data-template-id");
        var crtbtn = jQuery(this).attr("data-name");
        jQuery('.lfd-loader-page').show();

        if (crtbtn == 'crtbtn') {
            var with_page = jQuery(".lfd-page-" + template_id).val();
        } else {
            var with_page = jQuery(this).siblings(".lfd-page-name-" + template_id).val();
        }

        //check page name not empty
        if (with_page == "") {
            alert(js_object.lfd_crt_page);
            jQuery(".lfd-page-name-" + template_id).addClass("lef-required");
            jQuery(".lfd-page-" + template_id).addClass("lef-required");
            return false;
        }

        jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'handle_import',
                template_id: template_id,
                with_page: with_page,
            },
            beforeSend: function () {
                jQuery('.lfd-import-btn').addClass('lfd-disabled');
                jQuery(".lfd-create-page-btn, .lfd-page-name-" + template_id).hide();
                jQuery(".lfd-page-" + template_id).hide();
                jQuery(".lfd-loader-page").html("<div class='lfd-gradient-loader'></div>");
            },
            success: function (result) {
                jQuery(".lfd-page-create, .lfd-loader-page").hide();
                if (typeof result == 'string') {
                    if (jQuery.isNumeric(result)) {
                        if (result == 0) {
                            jQuery(".lfd-page-error").show();
                            jQuery(".lfd-error").text(js_object.lfd_error);
                        } else {
                            jQuery(".lfd-page-edit").show();
                            jQuery(".lfd-edit-page").attr("href", lef_res + 'post.php?post=' + result + "&action=edit");
                        }
                    } else {
                        jQuery(".lfd-page-error").show();
                        jQuery(".lfd-error").text(result);
                    }
                }
            },
            setTimeout: 1000,
        });
    });

});

function closeProgressIndicator() {
    jQuery(".lfeProgressIndicator").hide();
}
