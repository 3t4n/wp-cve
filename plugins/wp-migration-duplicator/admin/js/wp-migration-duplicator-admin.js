(function ($) {
    'use strict';
    $(function () {
        cloudStorageFunctions.set();
        var wf_tab_view =
                {
                    Set: function () {
                        this.subTab();
                        this.accordion();
                        $('[name="wt_mgdp_schedule_export_btn"],[name="wt_mgdp_schedule_export_btn_edit"]').off('click').on('click', function(){
                            cloudStorageFunctions.authenticateCloudStorage($('select[name="wt_mgdb_export_option_schedule"]'));
                            wf_tab_view.file_tree();
                            if ($('[name="cron_export_type_files"]').prop('checked') == false) {
                                jQuery('.exclude_folder').hide();
                            }
                            var $re = jQuery('#accordion-wrapper');
                            $re.removeClass('accordion-wrapper');
                            $re.toggleClass('accordionsc-wrapper');
                            $('.wt_backup_schedule_data').show();
                            $('.wt_backup_data').hide();
                            var $this = jQuery('#schedule_class >.wt-migrator-accordion-tab > a');
                            $this.toggleClass('accordion-active');
                            $this.closest('.wt-migrator-accordion-tab').toggleClass('accordion-active');
                            $this.next().toggleClass('accordion-active');
                            $this.next().slideToggle(350);
                            $('html, body').stop(true, true).animate({
                                scrollTop: $("#backup_schedule_class").offset().top
                            }, 2000);

                        });
                        
                        var wf_nav_tab = $('.wt-mgdp-tab-head .nav-tab');
                        if (wf_nav_tab.length > 0) {
                            wf_nav_tab.off('click').on('click', function () {
                                var wf_tab_hash = $(this).attr('href');
                                wf_nav_tab.removeClass('nav-tab-active');
                                $(this).addClass('nav-tab-active');
                                wf_tab_hash = wf_tab_hash.charAt(0) == '#' ? wf_tab_hash.substring(1) : wf_tab_hash;
                                var wf_tab_elm = $('div[data-id="' + wf_tab_hash + '"]');
                                $('.wf-tab-content').hide();
                                if (wf_tab_elm.length > 0 && wf_tab_elm.is(':hidden')) {
                                    wf_tab_elm.stop(true, true).fadeIn();
                                }
                            });
                            $(window).on('hashchange', function (e) {
                                var location_hash = window.location.hash;
                                if (location_hash != "") {
                                    wf_tab_view.showTab(location_hash);
                                }
                            }).trigger('hashchange');

                            var location_hash = window.location.hash;
                            if (location_hash != "") {
                                wf_tab_view.showTab(location_hash);
                            } else {
                                wf_nav_tab.eq(0).click();
                            }
                        }
                    },
                    
                    file_tree: function () {
                        var ajx_dta = {};
                        ajx_dta['settings_data'] = {};
                        ajx_dta['action'] = 'mgdp_plugin_file_tree';
                        jQuery.ajax({
                            url: wtMigratorObject.ajax_url,
                            type: 'POST',
                            data: ajx_dta,
                            dataType: "json",
                            success: function (response)
                            {
                                if (response.data.data !== '') {
                                    var item = response.data.data;
                                    $.each(item, function (i, val) {
                                        $(".exclude_folder input[value='" + val + "']").prop('checked', false);

                                    });
                                }

                            },
                        });

                    },
                    
                    showTab: function (location_hash) {
                        var wf_tab_hash = location_hash.charAt(0) == '#' ? location_hash.substring(1) : location_hash;
                        if (wf_tab_hash != "") {
                            var wf_tab_hash_arr = wf_tab_hash.split('#');
                            wf_tab_hash = wf_tab_hash_arr[0];
                            var wt_feed_tab = $('.feed-active');
                            wt_feed_tab.removeClass('feed-active');
                            $('div[data-id="' + wf_tab_hash + '"]').addClass('feed-active');
                            var wf_tab_elm = $('div[data-id="' + wf_tab_hash + '"]');
                            if (wf_tab_elm.length > 0 && wf_tab_elm.is(':hidden')) {
                                $('a[href="#' + wf_tab_hash + '"]').click();
                                if (wf_tab_hash_arr.length > 1) {
                                    var wf_sub_tab_link = wf_tab_elm.find('.wf_sub_tab');
                                    if (wf_sub_tab_link.length > 0) /* subtab exists  */ {
                                        var wf_sub_tab = wf_sub_tab_link.find('li[data-target=' + wf_tab_hash_arr[1] + ']');
                                        wf_sub_tab.click();
                                    }
                                }
                            }
                        }
                    },
                    
                    subTab: function () {
                        $('.wf_sub_tab li').click(function () {
                            var trgt = $(this).attr('data-target');
                            var prnt = $(this).parent('.wf_sub_tab');
                            var ctnr = prnt.siblings('.wf_sub_tab_container');
                            prnt.find('li a').css({'color': '#0073aa', 'cursor': 'pointer'});
                            $(this).find('a').css({'color': '#ccc', 'cursor': 'default'});
                            ctnr.find('.wf_sub_tab_content').hide();
                            ctnr.find('.wf_sub_tab_content[data-id="' + trgt + '"]').fadeIn();
                        });
                        $('.wf_sub_tab').each(function () {
                            var elm = $(this).children('li').eq(0);
                            elm.click();
                        });
                    },
                    
                    accordion: function () {

                        if (jQuery('.wt-migrator-accordion-tab').hasClass('accordion-active')) {
                            jQuery('.wt-migrator-accordion-tab.accordion-active').find('.wt-migrator-accordion-content').slideDown(0);
                        }
                         var $this = jQuery('#import_class >.wt-migrator-accordion-tab > a');
                         $this.toggleClass('accordion-active');
                         $this.closest('.wt-migrator-accordion-tab').toggleClass('accordion-active');
                         $this.next().toggleClass('accordion-active');
                         $this.next().slideToggle(350);
                        jQuery(document).on('click', '.wt-migrator-accordion-tab > a', function (e) {
                            e.preventDefault();
                            var $this = jQuery(this);
                            if ($this.next().hasClass('accordion-active')) {
                                $this.removeClass('accordion-active');
                                $this.next().removeClass('accordion-active');
                                $this.closest('.wt-migrator-accordion-tab').removeClass('accordion-active');
                                $this.next().slideUp(350);
                            } else {
                                var active_acc = jQuery(document.querySelectorAll('#wt_backup_data > .postbox div:nth-child(1) > .accordion-active'));
                                if (active_acc.length !== 0) {
                                    active_acc.removeClass('accordion-active');
                                    active_acc.next().removeClass('accordion-active');
                                    active_acc.closest('.wt-migrator-accordion-tab').removeClass('accordion-active');
                                    active_acc.next().slideUp(350);
                                } else {
                                    var active_accr = jQuery(document.querySelectorAll('.wf-tab-content >.accordion-wrapper > .postbox div:nth-child(1) > .accordion-active'));
                                    if (active_accr.length !== 0) {
                                        active_accr.removeClass('accordion-active');
                                        active_accr.next().removeClass('accordion-active');
                                        active_accr.closest('.wt-migrator-accordion-tab').removeClass('accordion-active');
                                        active_accr.next().slideUp(350);
                                    }

                                }
                                /*var active_acc_im = jQuery(document.querySelectorAll('#wt_import > .postbox div:nth-child(1) > .accordion-active'));
                                if (active_acc_im.length !== 0) {
                                    active_acc_im.removeClass('accordion-active');
                                    active_acc_im.next().removeClass('accordion-active');
                                    active_acc_im.closest('.wt-migrator-accordion-tab').removeClass('accordion-active');
                                    active_acc_im.next().slideUp(350);
                                }*/
                                var acctive_sche = jQuery('#schedule_class >.wt-migrator-accordion-tab >.accordion-active');
                                if (acctive_sche.length !== 0) {
                                    acctive_sche.removeClass('accordion-active');
                                    acctive_sche.next().removeClass('accordion-active');
                                    acctive_sche.closest('.wt-migrator-accordion-tab').removeClass('accordion-active');
                                    acctive_sche.next().slideUp(350);
                                }
                                 var active_accr = jQuery(document.querySelectorAll('.wf-tab-content > .postbox div:nth-child(1) > .accordion-active'));
                                    if (active_accr.length !== 0) {
                                        active_accr.removeClass('accordion-active');
                                        active_accr.next().removeClass('accordion-active');
                                        active_accr.closest('.wt-migrator-accordion-tab').removeClass('accordion-active');
                                        active_accr.next().slideUp(350);
                                    }
                                $this.toggleClass('accordion-active');
                                $this.closest('.wt-migrator-accordion-tab').toggleClass('accordion-active');
                                $this.next().toggleClass('accordion-active');
                                $this.next().slideToggle(350);

                            }
                        });

                    }
                }
        wf_tab_view.Set();

    });
})(jQuery);

var wf_progress_bar =
        {
            Set: function (vl, elm, inner_text, no_animate) {
                if (elm) {
                    var bar_inner = elm.find('.wf_progress_bar_inner');
                    if (inner_text) {
                        elm.find('.wf_progress_bar_label').html(inner_text);
                    }
                } else {
                    var bar_inner = jQuery('.wf_progress_bar_inner');
                }
                bar_inner.parent('.wf_progress_bar').show();
                if (no_animate || vl == 0) {
                    bar_inner.css({'width': (vl + '%')}).html(vl + '%').attr({'data-val': vl});
                } else {
                    bar_inner.stop(true, true).animate({'width': (vl + '%')}, 200).html(vl + '%').attr({'data-val': vl});
                }
            },
            updateLabel: function (elm, inner_text) {
                if (elm) {
                    var bar_inner = elm.find('.wf_progress_bar_inner');
                    if (inner_text) {
                        elm.find('.wf_progress_bar_label').html(inner_text);
                    }
                }
            },
            Reset: function (vl, elm, inner_text) {
                this.Set(vl, elm, inner_text, true);
            }
        }
var popup_handler =
        {
            hidePopup: function ()
            {
                jQuery('.wt_mgdp_popup_close').click();
            },
            hide_export_info_box: function ()
            {
                jQuery('.wt_mgdp_loader_info_box').hide();
            },
        }

var wp_migration_duplicator_notify_msg =
        {
            error: function (message) {
                var er_elm = jQuery('<div class="notify_msg" style="background:#f8d7da; border:solid 1px #f5c6cb; color:  #721c24">' + message + '</div>');
                this.setNotify(er_elm);
            },
            success: function (message) {
                var suss_elm = jQuery('<div class="notify_msg" style="background:#d4edda; border:solid 1px #c3e6cb; color: #155724;">' + message + '</div>');
                this.setNotify(suss_elm);
            },
            setNotify: function (elm) {
                jQuery('body').append(elm);
                elm.stop(true, true).animate({'opacity': 1, 'top': '50px'}, 1000);

                jQuery('body').click(function () {
                    elm.animate({'opacity': 0, 'top': '100px'}, 1000, function () {
                        elm.remove();
                    });
                });
            }
        }
var cloudStorageFunctions = {
    loader: '',
    authenticateCloudStorage: function (element) {
        currentElement = element;
        jQuery('#wt_mgdp_import_btn').css({'visibility': 'visible'});
        var cloudStorage = currentElement.find('option:selected').val();
        if (cloudStorage !== 'local') {
            jQuery('.wt_instruction_box').hide();
        } else {
            jQuery('.wt_instruction_box').show();
        }
        this.loader = currentElement.closest('.wt-migrator-select-container').find('.spinner');
        var optionType = currentElement.attr('data-option-type');
        var targetElement = jQuery('.wt_mgdb_' + optionType + '_option_' + cloudStorage);
        var targetElement_cron = jQuery('.wt_mgdb_' + optionType + '_option_' + cloudStorage + '_schedule');
        var commonElement = jQuery('.child-wt_mgdb_' + optionType + '_option');
        commonElement.slideUp(100);
        this.loader.show().css({'visibility': 'visible'});
        var ajaxurl = wtMigratorObject.ajax_url;
        var data = {
            'action': 'wp_mgdp_check_authentication',
            'cloud_storage': cloudStorage,
            '_wpnonce': wtMigratorObject.nonce
        };
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                settingsElement = targetElement.find('.wt-migrator-cloud-settings-link');
                settingsText = settingsElement.text();
                disabled = true;
                if (response.success === true) {

                    targetElement.addClass('wt-migrator-authenticated');
                    targetElement_cron.addClass('wt-migrator-authenticated');
                    disabled = false;
                    if (optionType === "import") {
                        cloudStorageFunctions.populateBackups(cloudStorage, targetElement);
                    } else {
                        targetElement.slideDown(500);
                        cloudStorageFunctions.loader.hide().css({'visibility': 'hidden'});
                    }

                } else {
                    targetElement.addClass('wt-migrator-disconnected');
                    targetElement.slideDown(500);
                    cloudStorageFunctions.loader.hide().css({'visibility': 'hidden'});
                    if(cloudStorage == 'googledrive' || cloudStorage == 's3bucket' ){
                       jQuery('#wt_mgdp_import_btn').css({'visibility': 'hidden'});
                    }
                }

                targetElement.find('.wt-migrator-file').prop('disabled', disabled);

            },
            error: function () {
                targetElement.addClass('wt-migrator-disconnected');
                targetElement.slideDown(500);
                cloudStorageFunctions.loader.hide().css({'visibility': 'hidden'});
            }
        });

    },
    set: function () {
        jQuery('select[name="wt_mgdb_export_option"],select[name="wt_mgdb_import_option"]').on('change', function (e) {
            e.preventDefault();
            cloudStorageFunctions.authenticateCloudStorage(jQuery(this));
        });
        jQuery('select[name="wt_mgdb_export_option_schedule"]').on('change', function (e) {
            e.preventDefault();
            cloudStorageFunctions.authenticateCloudStorage(jQuery(this));
        });

        jQuery('.wt-migrator-cloud-import-file').on('select2:select', function (e) {
            var cloudlocation = jQuery(this).attr('data-hidden-name');
            var data = e.params.data;
            jQuery('input[type="hidden"][name="' + cloudlocation + '"]').val(data.id);

        });
    },
    
    populateBackups: function (cloudStorage, targetElement) {

        var ajaxurl = wtMigratorObject.ajax_url;
        var data = {
            'action': 'wp_mgdp_populate_cloud_files',
            'cloud_storage': cloudStorage,
            '_wpnonce': wtMigratorObject.nonce
        };
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    jQuery('.wt-migrator-cloud-import-file').html('');
                    jQuery('.wt-migrator-cloud-import-file').append('<option value="-1">' + wtMigratorObject.messages.select_backup + '</option>');
                    response.data.forEach(function (item) {
                        jQuery('.wt-migrator-cloud-import-file').append('<option value="' + item.file + '">' + item.name + '</option>')
                        targetElement.slideDown(500);
                    });
                } else {
                    jQuery('.wt-migrator-cloud-import-file').html('');
                    jQuery('.wt-migrator-cloud-import-file').append('<option value="-1">' + wtMigratorObject.messages.no_backups + '</option>');
                    targetElement.slideDown(500);
                }
                cloudStorageFunctions.loader.hide().css({'visibility': 'hidden'});
            },
            error: function () {
                jQuery('.wt-migrator-cloud-import-file').html('');
                jQuery('.wt-migrator-cloud-import-file').append('<option value="-1">' + wtMigratorObject.messages.no_backups + '</option>');
                targetElement.slideDown(500);
                cloudStorageFunctions.loader.hide().css({'visibility': 'hidden'});
            }
        });
    }

}

var wt_mgdp_log_handler = (function ($) {
    var wt_mgdp_log_handler =
            {
                log_offset: 0,
                Set: function ()
                {
                    this.reg_view_log();

                },
                reg_view_log: function ()
                {
                    jQuery(document).on('click', ".wt_mgdp_view_log_btn", function () {

                        wt_mgdp_log_handler.show_log_popup();

                        var log_file = $(this).attr('data-log-file');
                        if (log_file != "")
                        {
                            wt_mgdp_log_handler.view_raw_log(log_file);
                        }

                    });
                },
                view_raw_log: function (log_file)
                {
                    $('.wt_mgdp_log_container').html('<div class="wt_mgdp_log_loader">' + wtMigratorObject.messages.loading + '</div>');
                    $.ajax({
                        url: wtMigratorObject.ajax_url,
                        data: {'action': 'wp_mgdp_populate_popup', _wpnonce: wtMigratorObject.nonces, 'history_action': 'view_log', 'log_file': log_file, 'data_type': 'json'},
                        type: 'post',
                        dataType: "json",
                        success: function (response)
                        {
                            if (response.success === true)
                            {
                                $('.wt_mgdp_log_container').html(response.data.html);
                            } else
                            {
                                wp_migration_duplicator_notify_msg.error(wtMigratorObject.messages.error);
                            }
                        },
                        error: function ()
                        {
                            wp_migration_duplicator_notify_msg.error(wtMigratorObject.messages.error);
                        }
                    });
                },
                show_log_popup: function ()
                {
                    var pop_elm = $('.wt_mgdp_view_log');
                    var ww = $(window).width();
                    pop_w = (ww < 1300 ? ww : 1300) - 200;
                    pop_w = (pop_w < 200 ? 200 : pop_w);
                    pop_elm.width(pop_w);

                    wh = $(window).height();
                    pop_h = (wh >= 400 ? (wh - 200) : wh);
                    $('.wt_mgdp_log_container').css({'max-height': pop_h + 'px', 'overflow': 'auto'});
                    wt_mgdp_log_popup.showPopup(pop_elm);
                },

            }
    return wt_mgdp_log_handler;

})(jQuery);


wt_mgdp_log_popup = {
    Set: function ()
    {
        this.regPopupClose();
        jQuery('body').prepend('<div class="wt_mgdp_overlay"></div>');
    },

    showPopup: function (popup_elm)
    {
        var pw = popup_elm.outerWidth();
        var wh = jQuery(window).height();
        var ph = wh - 150;
        popup_elm.css({'margin-left': ((pw / 2) * -1), 'display': 'block', 'top': '20px'}).animate({'top': '50px'});
        popup_elm.find('.wt_mgdp_popup_body').css({'max-height': ph + 'px', 'overflow': 'auto'});
        jQuery('.wt_mgdp_overlay').show();
    },
    hidePopup: function ()
    {
        jQuery('.wt_mgdps_popup_close').click();
    },
    regPopupClose: function (popup_elm)
    {
        jQuery('.wt_mgdps_popup_close').unbind('click').click(function () {
            jQuery('.wt_mgdp_overlay, .wt_mgdp_popup').hide();
        });
    }
}



var wt_mgdp_feedback_handler = (function ($) {
    var wt_mgdp_feedback_handler =
            {
                Set: function ()
                {
                    jQuery(document).on('click', ".wt_sidebar_feedback", function () {
                        $('.wt_mgdp_feedback_loader_info_box').show();

                    });

                    $('.feedback_upload-btn').click(function (e) {
                        e.preventDefault();
                        var image = wp.media({
                            title: 'Upload log files',
                            multiple: false
                        }).open()
                                .on('select', function (e) {
                                    var uploaded_image = image.state().get('selection').first();
                                    var attachment_url = uploaded_image.toJSON().id;
                                    var attachment_urls = uploaded_image.toJSON().url;
                                    $('[name="feedback_attachment_url"').val(attachment_url);
                                    $('.wt_mgdp_import_er').hide().find('td').html('');
                                    $('.wt_mgdp_report_attachment_url').html(attachment_urls).css({'display': 'block'});
                                });
                    });

                    jQuery(document).on('click', ".wt-feedback-cancel", function () {
                        $(".wt_mgdp_report_attachment_url").empty();
                        $('.wt_mgdp_feedback_loader_info_box').hide();
                    });


                    $('[name="wt-feedback-submit"]').click(function (e) {
                        e.preventDefault();
                        if ($('input[name="wt-feedback-terms"]:checked').length == 0) {
                            wp_migration_duplicator_notify_msg.error(wtMigratorObject.messages.term_error);
                            return true;
                        }
                        var feedback_customer_email = $('input[name="wt-feedback-email"]').val();
                        if (feedback_customer_email === '') {
                            feedback_customer_email = $('.feed-active input[name="wt-feedback-email"]').val();
                        }
                        var feedback_customer_email_subject = $('textarea#wt-feedback-message').val();
                        if (feedback_customer_email_subject === '') {
                            feedback_customer_email_subject = $('.feed-active textarea#wt-feedback-message').val();
                        }
                        var file = $('input[name="feedback_attachment_url"]').val();
                        if (file === '') {
                            file = $('.feed-active input[name="feedback_attachment_url"]').val();
                        }
                        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                        if (!re.test(feedback_customer_email)) {
                            wp_migration_duplicator_notify_msg.error("invalid email");
                            return true;
                        }
                        if (feedback_customer_email == '' || feedback_customer_email_subject == '') {
                            wp_migration_duplicator_notify_msg.error(wtMigratorObject.messages.mail_empty_msg);
                            return true;
                        }
                        $('.wf_report_btn_loader').show();
                        $.ajax({
                            url: wtMigratorObject.ajax_url,
                            data: {'action': 'wp_mgdp_populate_feedback', _wpnonce: wtMigratorObject.nonces, 'email': feedback_customer_email, 'message': feedback_customer_email_subject, 'file': file},
                            type: 'post',
                            dataType: "json",
                            success: function (response)
                            {
                                if (response.success === true)
                                {
                                    wp_migration_duplicator_notify_msg.success(wtMigratorObject.messages.mail_msg);
                                    $('input[name="wt-feedback-email"]').val("");
                                    $('textarea#wt-feedback-message').val("");
                                    $('#wt-feedback-terms').prop('checked', false);
                                    $('.wt_mgdp_feedback_loader_info_box').hide();
                                    $('.wf_report_btn_loader').hide();
                                } else
                                {
                                    wp_migration_duplicator_notify_msg.error(wtMigratorObject.messages.error);
                                }
                            },
                            error: function ()
                            {
                                wp_migration_duplicator_notify_msg.error(wtMigratorObject.messages.error);
                            }
                        });
                    });


                },
            }
    return wt_mgdp_feedback_handler;
})(jQuery);

jQuery(function () {
    wt_mgdp_log_handler.Set();
    wt_mgdp_log_popup.Set();

    wt_mgdp_feedback_handler.Set();
});