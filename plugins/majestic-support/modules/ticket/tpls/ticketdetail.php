<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="ms-main-up-wrapper">
    <?php
if (majesticsupport::$_config['offline'] == 2) {
    if(isset(majesticsupport::$_data['error_message'])){
        if(majesticsupport::$_data['error_message'] == 1){
            MJTC_layout::MJTC_getUserGuest();
        }elseif(majesticsupport::$_data['error_message'] == 2){
            MJTC_layout::MJTC_getYouAreNotAllowedToViewThisPage();
        }
    }elseif (MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid() != 0 || majesticsupport::$_config['visitor_can_create_ticket'] == 1) {
        MJTC_message::MJTC_getMessage();

        $printflag = false;
        if(isset(majesticsupport::$_data['print']) && majesticsupport::$_data['print'] == 1){
            $printflag = true;
        }
        if($printflag == true){
            wp_head();
        }

        if($printflag == false){
            include_once(MJTC_PLUGIN_PATH . 'includes/header.php');
        }

        if (majesticsupport::$_data['permission_granted'] == true) {
        if (!empty(majesticsupport::$_data[0])) {

        wp_enqueue_script('majesticsupport-file_validate.js', MJTC_PLUGIN_URL . 'includes/js/file_validate.js');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('majesticsupport-jquery.cluetip.min.js', MJTC_PLUGIN_URL . 'includes/js/jquery.cluetip.min.js');
        wp_enqueue_script( 'hoverIntent' );
        wp_enqueue_style('majesticsupport-jquery.cluetip', MJTC_PLUGIN_URL . 'includes/css/jquery.cluetip.css');
        wp_enqueue_script('majesticsupport-timer.js', MJTC_PLUGIN_URL . 'includes/js/timer.jquery.js');
        wp_enqueue_style('majesticsupport-venobox-css', MJTC_PLUGIN_URL . 'includes/css/venobox.css');
        wp_enqueue_script('majesticsupport-venoboxjs',MJTC_PLUGIN_URL.'includes/js/venobox.js');
        $majesticsupport_js ="
            var timer_flag = 0;
            var seconds = 0;
            function getpremade(val) {
                jQuery.post(ajaxurl, {action: 'mjsupport_ajax', val: val,mjsmod: 'cannedresponses', task: 'getpremadeajax', '_wpnonce':'".esc_attr(wp_create_nonce("get-premade-ajax"))."'}, function(data) {
                    if (data) {
                        var append = jQuery('input#append_premade1:checked').length;
                        if (append == 1) {
                            var content = tinyMCE.get('mjsupport_message').getContent();
                            content = content + data;
                            tinyMCE.get('mjsupport_message').execCommand('mceSetContent', false, content);
                        } else {
                            tinyMCE.get('mjsupport_message').execCommand('mceSetContent', false, data);
                        }
                    }

                });
            }

            function changeTimerStatus(val) {
                if (timer_flag == 2) { // to handle stopped timer
                    return;
                }
                if (!jQuery('span.timer-button.cls_' + val).hasClass('selected')) {
                    jQuery('span.timer-button').removeClass('selected');
                    jQuery('span.timer-button.cls_' + val).addClass('selected');
                    if (val == 1) {
                        if (timer_flag == 0) {
                            jQuery('div.timer').timer({
                                format: '%H:%M:%S'
                            });
                        }
                        timer_flag = 1;
                        jQuery('div.timer').timer('resume');
                    } else if (val == 2) {
                        jQuery('div.timer').timer('pause');
                    } else {
                        jQuery('div.timer').timer('remove');
                        timer_flag = 2;
                    }
                }
            }

            jQuery(document).ready(function() {
                changeIconTabs();
                jQuery('.venobox').venobox({
                    infinigall: true,
                    framewidth: 850,
                    titleattr: 'data-title',
                });
            });


            jQuery(function() {
                jQuery('ul li a').click(function(e) {
                    var imgID = jQuery(this).find('img').attr('id');
                    changeIconTabs(imgID);
                });
            });

            function changeIconTabs(tabValue = '') {
                jQuery(document).ready(function() {
                    if (tabValue == '') {
                        tabValue = jQuery('#ul-nav .ui-tabs-active > a > img').attr('id');
                    }
                    if (tabValue == 'post-reply') {
                        jQuery('#internal-note').attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/internal-reply-black.png\");
                        jQuery('#dept-transfer').attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/department-transfer-black.png\"
                        );
                        jQuery('#assign-staff').attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/assign-staff-black.png\");
                        jQuery('#' + tabValue).attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/post-reply-white.png\");
                    } else if (tabValue == 'internal-note') {
                        jQuery('#dept-transfer').attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/department-transfer-black.png\"
                        );
                        jQuery('#assign-staff').attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/assign-staff-black.png\");
                        jQuery('#post-reply').attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/post-reply-black.png\");
                        jQuery('#' + tabValue).attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/internal-reply-white.png\");
                    } else if (tabValue == 'dept-transfer') {
                        jQuery('#internal-note').attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/internal-reply-black.png\");
                        jQuery('#assign-staff').attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/assign-staff-black.png\");
                        jQuery('#post-reply').attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/post-reply-black.png\");
                        jQuery('#' + tabValue).attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/department-transfer-white.png\"
                        );
                    } else if (tabValue == 'assign-staff') {
                        jQuery('#dept-transfer').attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/department-transfer-black.png\"
                        );
                        jQuery('#internal-note').attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/internal-reply-black.png\");
                        jQuery('#post-reply').attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/post-reply-black.png\");
                        jQuery('#' + tabValue).attr('src',
                            \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/assign-staff-white.png\");
                    }

                });
            }
            function changeIconTabsOnMouseover() {
                jQuery(document).ready(function() {
                    jQuery('ul li').hover(function(e) {
                        var imgID = jQuery(this).find('img').attr('id');
                        tabValue = imgID;
                        if (tabValue == '') {
                            tabValue = jQuery('#ul-nav .ui-tabs-active > a > img').attr('id');
                        }
                        if (tabValue == 'post-reply') {
                            jQuery('#' + tabValue).attr('src',
                                \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/post-reply-white.png\"
                            );
                        } else if (tabValue == 'internal-note') {
                            jQuery('#' + tabValue).attr('src',
                                \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/internal-reply-white.png\"
                            );
                        } else if (tabValue == 'dept-transfer') {
                            jQuery('#' + tabValue).attr('src',
                                \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/department-transfer-white.png\"
                            );
                        } else if (tabValue == 'assign-staff') {
                            jQuery('#' + tabValue).attr('src',
                                \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/assign-staff-white.png\"
                            );
                        }

                    });
                });
            }

            function changeIconTabsOnMouseOut() {
                jQuery(document).ready(function() {
                    jQuery('ul li').hover(function(e) {
                        var imgID = jQuery(this).find('img').attr('id');
                        tabValue = imgID;
                        if (tabValue == '') {
                            tabValue = jQuery('#ul-nav .ui-tabs-active > a > img').attr('id');
                        }
                        if (tabValue == 'post-reply' && !jQuery(this).hasClass('ui-tabs-active')) {
                            jQuery('#' + tabValue).attr('src',
                                \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/post-reply-black.png\"
                            );
                        } else if (tabValue == 'internal-note' && !jQuery(this).hasClass('ui-tabs-active')) {
                            jQuery('#' + tabValue).attr('src',
                                \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/internal-reply-black.png\"
                            );
                        } else if (tabValue == 'dept-transfer' && !jQuery(this).hasClass('ui-tabs-active')) {
                            jQuery('#' + tabValue).attr('src',
                                \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/department-transfer-black.png\"
                            );
                        } else if (tabValue == 'assign-staff' && !jQuery(this).hasClass('ui-tabs-active')) {
                            jQuery('#' + tabValue).attr('src',
                                \"". esc_url(MJTC_PLUGIN_URL)."includes/images/ticketdetailicon/assign-staff-black.png\"
                            );
                        }

                    });
                });
            }

            function showEditTimerPopup() {
                jQuery('form#ms-time-edit-form').hide();
                jQuery('form#ms-reply-form').hide();
                jQuery('form#ms-note-edit-form').hide();
                jQuery('div.edit-time-popup').show();
                jQuery('span.timer-button').removeClass('selected');
                if (timer_flag != 0) {
                    jQuery('div.timer').timer('pause');
                }
                ex_val = jQuery('div.timer').html();
                jQuery('input#edited_time').val('');
                jQuery('input#edited_time').val(ex_val.trim());
                jQuery('div.ms-popup-background').show();
                jQuery('div#ms-popup-wrapper').slideDown('slow');
            }

            function updateTimerFromPopup() {
                val = jQuery('input#edited_time').val();
                arr = val.split(':', 3);
                jQuery('div.timer').html(val);
                jQuery('div.ms-popup-background').hide();
                jQuery('div.ms-popup-wrapper').slideUp('slow');
                seconds = parseInt(arr[0]) * 3600 + parseInt(arr[1]) * 60 + parseInt(arr[2]);
                if (seconds < 0) {
                    seconds = 0;
                }
                jQuery('div.timer').timer('remove');
                jQuery('div.timer').timer({
                    format: '%H:%M:%S',
                    seconds: seconds,
                });
                jQuery('div.timer').timer('pause');
                timer_flag = 1;
                desc = jQuery('textarea#t_desc').val();
                jQuery('input#timer_edit_desc').val(desc);
            }
            jQuery(document).ready(function($) {
                jQuery('form').submit(function(e) {
                    if (timer_flag != 0) {
                        jQuery('input#timer_time_in_seconds').val(jQuery('div.timer').data('seconds'));
                    }
                });
                jQuery('div#action-div a.button').click(function(e) {
                    e.preventDefault();
                });";
                if($printflag != true){
                    $majesticsupport_js .=" jQuery('#tabs').tabs();";
                }
                $majesticsupport_js .="
                jQuery('#tk_attachment_add').click(function() {
                    var obj = this;
                    var att_flag = jQuery(this).attr('data-ident');
                    var parentElement = jQuery(this).closest('.mjtc-attachment-field');
                    jQuery(parentElement).addClass('mjtc-attachment-field-selected');
                    var current_files = jQuery('div.mjtc-attachment-field-selected').find('.tk_attachment_value_text').length;
                    var total_allow = ". majesticsupport::$_config['no_of_attachement'].";
                    var append_text =
                        '<span class=\"tk_attachment_value_text\"><input name=\"filename[]\" type=\"file\" onchange=\"MJTC_uploadfile(this,\'". esc_js(majesticsupport::$_config['file_maximum_size'])."\',\'". esc_js(majesticsupport::$_config['file_extension'])."\');\" size=\"20\"  /><span  class=\"tk_attachment_remove\"></span></span>';
                    if (current_files < total_allow) {
                        jQuery('.tk_attachment_value_wrapperform.' + att_flag).append(append_text);
                    } else if ((current_files === total_allow) || (current_files > total_allow)) {
                        alert('". esc_html(__('File upload limit exceeds', 'majestic-support')) ."');
                    }
                });
                jQuery(document).delegate('.tk_attachment_remove', 'click', function(e) {
                    jQuery(this).parent().remove();
                    var current_files = jQuery('input[type=\"file\"]').length;
                    var total_allow = ". majesticsupport::$_config['no_of_attachement'].";
                    if (current_files < total_allow) {
                        jQuery('#tk_attachment_add').show();
                    }
                });
                jQuery('a#showhidedetail').click(function(e) {
                    e.preventDefault();
                    var divid = jQuery(this).attr('data-divid');
                    jQuery('div#' + divid).slideToggle();
                    jQuery(this).find('img').toggleClass('mjtc-hidedetail');
                });

                jQuery('a#showhistory').click(function(e) {
                    e.preventDefault();
                    jQuery('div#userpopup').slideDown('slow');
                    jQuery('div#userpopupblack').show();
                });
                jQuery('a#changepriority').click(function(e) {
                    e.preventDefault();
                    jQuery('div#userpopupforchangepriority').slideDown('slow');
                    jQuery('div#userpopupblack').show();
                });

                jQuery('div#userpopupblack,span.close-history,span.close-credentails').click(function(e) {
                    jQuery('div#userpopup').slideUp('slow');
                    jQuery('div#userpopupforchangepriority').slideUp('slow');
                    jQuery('div#popupfordepartmenttransfer').slideUp('slow');
                    jQuery('#usercredentailspopup').slideUp('slow');
                    setTimeout(function() {
                        jQuery('div#userpopupblack').hide();
                    }, 700);
                });

                jQuery('a#departmenttransfer').click(function(e) {
                    e.preventDefault();
                    jQuery('div#popupfordepartmenttransfer').slideDown('slow');
                    jQuery('div#userpopupblack').show();
                });

                jQuery('a#agenttransfer').click(function(e) {
                    e.preventDefault();
                    jQuery('div#popupforagenttransfer').slideDown('slow');
                    jQuery('.ms-popup-background').show();
                });
                jQuery(document).delegate('div#popupforagenttransfer .popup-header-close-img', 'click', function(e) {
                    jQuery('div#popupforagenttransfer').slideUp('slow');
                    jQuery('div#popup-record-data').html('');
                });
                jQuery(document).delegate('div#popupfordepartmenttransfer .popup-header-close-img', 'click', function(
                    e) {
                    jQuery('div#popupfordepartmenttransfer').slideUp('slow');
                    jQuery('div#popup-record-data').html('');
                });
                jQuery(document).delegate('div#popupforinternalnote .popup-header-close-img', 'click', function(e) {
                    jQuery('div#popupforinternalnote').slideUp('slow');
                    jQuery('div#popup-record-data').html('');
                });

                jQuery('a#internalnotebtn').click(function(e) {
                    e.preventDefault();
                    jQuery('div#popupforinternalnote').slideDown('slow');
                    jQuery('.ms-popup-background').show();
                });

                jQuery(document).delegate('#close-pop, img.close-merge, div.ms-popup-background', 'click', function(e) {
                    jQuery('div#mergeticketselection').slideUp('slow');
                    jQuery('div#popup-record-data').html('');
                    setTimeout(function() {
                        jQuery('div.ms-popup-background').hide();
                    }, 700);
                });

                jQuery('div.popup-header-close-img,input#cancele,div.ms-popup-background,input#cancelee,input#canceleee,input#canceleeee,input#canceleeeee,input#canceleeeeee').click(function(e) {
                    jQuery('div.ms-popup-wrapper').slideUp('slow');
                    jQuery('div#popupforinternalnote').slideUp('slow');
                    jQuery('div.ms-merge-popup-wrapper').slideUp('slow');
                    jQuery('div#popupfordepartmenttransfer').slideUp('slow');
                    jQuery('div#popupforagenttransfer').slideUp('slow');
                    setTimeout(function() {
                        jQuery('div.ms-popup-background').hide();
                        jQuery('div#userpopupblack').hide();
                    }, 700);
                });

                jQuery(document).delegate('#ticketpopupsearch', 'submit', function(e) {
                    var ticketid = jQuery('#ticketidformerge').val();
                    e.preventDefault();
                    var name = jQuery('input#name').val();
                    var email = jQuery('input#email').val();
                    jQuery.post(ajaxurl, {
                        action: 'mjsupport_ajax',
                        mjsmod: 'mergeticket',
                        task: 'getTicketsForMerging',
                        name: name,
                        email: email,
                        ticketid: ticketid,
                        '_wpnonce':'". esc_attr(wp_create_nonce('get-tickets-for-merging'))."'
                    }, function(data) {
                        data = jQuery.parseJSON(data);
                        if (data !== 'undefined') {
                            if (data !== '') {
                                jQuery('div#popup-record-data').html('');
                                jQuery('div#popup-record-data').html(MJTC_msDecodeHTML(data['data']));
                            } else {
                                jQuery('div#popup-record-data').html('');
                            }
                        } else {
                            jQuery('div#popup-record-data').html('');
                        }
                    }); //jquery closed
                });

                jQuery('a#print-link').click(function(e) {
                    e.preventDefault();
                    var href =
                        '". majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'printticket','majesticsupportid'=>majesticsupport::$_data[0]->id))."';
                    print = window.open(href, 'print_win', 'width=1024, height=800, scrollbars=yes');
                });

                //non premium support function
                jQuery('#nonpreminumsupport').change(function() {
                    if (jQuery(this).is(':checked')) {
                        if (1 || confirm(
                                \"". esc_html(__('Are you sure to mark this ticket non-preminum?','majestic-support'))."\"
                            )) {
                            markUnmarkTicketNonPremium(1);
                        } else {
                            jQuery(this).removeAttr('checked');
                        }
                    } else {
                        markUnmarkTicketNonPremium(0);
                    }
                });

                jQuery('#paidsupportlinkticketbtn').click(function() {
                    var ticketid = jQuery('#ticketid').val();
                    var paidsupportitemid = jQuery('#paidsupportitemid').val();
                    if (paidsupportitemid > 0) {
                        jQuery.post(ajaxurl, {
                            action: 'mjsupport_ajax',
                            mjsmod: 'paidsupport',
                            task: 'linkTicketPaidSupportAjax',
                            ticketid: ticketid,
                            paidsupportitemid: paidsupportitemid,
                            '_wpnonce':'". esc_attr(wp_create_nonce('link-ticket-paidsupport-ajax'))."'
                        }, function(data) {
                            window.location.reload();
                        });
                    }
                });

            });

            function markUnmarkTicketNonPremium(mark) {
                var ticketid = jQuery('#ticketid').val();
                var paidsupportitemid = jQuery('#paidsupportitemid').val();
                jQuery.post(ajaxurl, {
                    action: 'mjsupport_ajax',
                    mjsmod: 'paidsupport',
                    task: 'markUnmarkTicketNonPremiumAjax',
                    status: mark,
                    ticketid: ticketid,
                    paidsupportitemid: paidsupportitemid,
                    '_wpnonce':'". esc_attr(wp_create_nonce('mark-unmark-ticket-nonpremium-ajax'))."'
                }, function(data) {
                    window.location.reload();
                });
            }

            function actionticket(action) {
                /*  Action meaning
                 * 1 -> Change Priority
                 * 2 -> Close Ticket
                 * 2 -> Reopen Ticket
                 */
                if (action == 1) {
                    jQuery('#priority').val(jQuery('#prioritytemp').val());
                }
                jQuery('input#actionid').val(action);
                jQuery('form#adminTicketform').submit();
            }

            function getmergeticketid(mergeticketid, mergewithticketid) {
                if (mergewithticketid == 0) {
                    mergewithticketid = jQuery('#mergeticketid').val();
                } else {
                    jQuery('#mergeticketid').val(mergewithticketid);
                }
                if (mergeticketid == mergewithticketid) {
                    alert(\"Primary id must be differ from merge ticket id\");
                    return false;
                }
                jQuery('#mergeticketselection').hide();
                getTicketdataForMerging(mergeticketid, mergewithticketid);
            }

            function getTicketdataForMerging(mergeticketid, mergewithticketid) {
                jQuery.post(ajaxurl, {
                    action: 'mjsupport_ajax',
                    mjsmod: 'mergeticket',
                    task: 'getLatestReplyForMerging',
                    mergeid: mergeticketid,
                    mergewith: mergewithticketid,
                    '_wpnonce':'". esc_attr(wp_create_nonce('get-latest-reply-for-merging'))."'
                }, function(data) {
                    if (data) {
                        data = jQuery.parseJSON(data);
                        jQuery('div#popup-record-data').html('');
                        jQuery('div#popup-record-data').html(MJTC_msDecodeHTML(data['data']));
                    }
                });
            }

            function closePopup() {
                setTimeout(function() {
                    jQuery('div.ms-popup-background').hide();
                    jQuery('div#userpopupblack').hide();
                }, 700);

                jQuery('div.ms-popup-wrapper').slideUp('slow');
                jQuery('div#userpopupforchangepriority').slideUp('slow');
                jQuery('div#popupfordepartmenttransfer').slideUp('slow');
                jQuery('div#userpopup').slideUp('slow');

            }

            function checktinymcebyid(id) {
                var content = tinymce.get(id).getContent({
                    format: 'text'
                });
                if (jQuery.trim(content) == '') {
                    alert(\"". esc_html(__('Some values are not acceptable please retry', 'majestic-support'))."\");
                    return false;
                }
                return true;
            }

            function showTicketCloseReasons(id, internalid){
                jQuery('div.ms-popup-other-reason-box').hide();
                jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'ticketclosereason', task: 'getTicketCloseReasonsForPopup',isadmin:0,id:id, internalid:internalid, '_wpnonce':'". esc_attr(wp_create_nonce('get-ticket-close-reasons-for-popup'))."'}, function (data) {
                    if(data){
                        data=jQuery.parseJSON(data);
                        jQuery('div#popup-record-data').html('');
                        jQuery('div#popup-record-data').html(MJTC_msDecodeHTML(data['data']));
                    }
                });
            }
            
            function resetMergeFrom() {
                var ticketid = jQuery('#ticketidformerge').val();
                var name = '';
                var email = '';
                jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'mergeticket', task: 'getTicketsForMerging', name: name, email: email,ticketid:ticketid, '_wpnonce':'". esc_attr(wp_create_nonce('get-tickets-for-merging'))."'}, function (data) {
                    data=jQuery.parseJSON(data);
                   if(data !== 'undefined') {
                        if(data !== '') {
                            jQuery('div#popup-record-data').html('');
                            jQuery('div#popup-record-data').html(MJTC_msDecodeHTML(data['data']));
                        }else{
                            jQuery('div#popup-record-data').html('');
                        }
                    }else{
                        jQuery('div#popup-record-data').html('');
                    }
                });//jquery closed
            }

            function closeReasonPopup(saveReason, closeTicket){
                var close = 1;
                if (saveReason == 0 && closeTicket == 1) {
                    jQuery('input[name=ms_skip]').val(1);
                    jQuery('form#ms-ticket-close-reason-form').submit();
                }
                if (saveReason == 1 && closeTicket == 1) {
                    if (jQuery('.reason_rb').is(':checked')) {
                        jQuery('input[type=checkbox]:checked').each(function() {
                            var selectedValue = jQuery(this).val();
                            if (selectedValue == 'reason_other') {
                                var other = jQuery('textarea#other_reason_box').val();
                                if (other == '') {
                                    close = 0;
                                    alert(\"". esc_html(__('Add reason in the other box','majestic-support'))."\");
                                    jQuery('div#ticketclosereason').slideDown('slow');
                                    return;
                                }
                            }
                        });
                        jQuery('input[type=radio]:checked').each(function() {
                            var selectedValue = jQuery(this).val();
                            if (selectedValue == 'reason_other') {
                                var other = jQuery('textarea#other_reason_box').val();
                                if (other == '') {
                                    close = 0;
                                    alert(\"". esc_html(__('Add reason in the other box','majestic-support'))."\");
                                    jQuery('div#ticketclosereason').slideDown('slow');
                                    return;
                                }
                            }
                        });
                        if (close == 1) {
                            jQuery('form#ms-ticket-close-reason-form').submit();
                        }
                    } else {
                        alert(\"". esc_html(__('First make some selection!','majestic-support'))."\");
                        jQuery('div#ticketclosereason').slideDown('slow');
                        return;
                    }
                }
                if (close == 1) {
                    jQuery('div#ticketclosereason').slideUp('slow', function () {
                        jQuery('div.ms-popup-background').hide();
                    });
                }
            }

            function hideBoxForOtherReason(){
                jQuery('div.ms-popup-other-reason-box-wrp').slideUp('slow', function () {
                    jQuery('div.ms-popup-other-reason-box').hide();
                });
            }

            function showBoxForOtherSingleReason(){
                jQuery('div.ms-popup-other-reason-box-wrp').slideDown('slow', function () {
                    jQuery('div.ms-popup-other-reason-box').show();
                });
            }

            function showBoxForOtherMultipleReason(){
                jQuery('div.ms-popup-other-reason-box-wrp').slideToggle('slow', function () {
                    jQuery('div.ms-popup-other-reason-box').show();
                });
            }

            function checkSmartReply(ticketSubject) {
                jQuery.post(ajaxurl, {action: 'mjsupport_ajax', ticketSubject: ticketSubject, mjsmod: 'smartreply', task: 'checkSmartReply', '_wpnonce':'". esc_attr(wp_create_nonce("check-smart-reply"))."'}, function (data) {
                    if(data) {
                        data=jQuery.parseJSON(data);
                        jQuery('.smartReplyNotFound').hide();
                        jQuery('.smartReplyFound').show();
                        jQuery('.ms-ticket-detail-smartreply-wrp').removeClass('add-margin');
                        jQuery('.ms-ticket-detail-smartreply-footer-wrp').show();
                        jQuery('.ms-ticket-detail-smartreply-add-wrp').html(MJTC_msDecodeHTML(data));
                    } else {
                        jQuery('.ms-ticket-detail-smartreply-wrp').removeClass('add-margin');
                        jQuery('.ms-ticket-detail-smartreply-footer-wrp').show();
                        jQuery('.smartReplyFound').hide();
                        jQuery('.smartReplyNotFound').show();
                    }
                });
            }

            function getSmartReply(val) {
                jQuery.post(ajaxurl, {action: 'mjsupport_ajax', val: val, mjsmod: 'smartreply', task: 'getSmartReply', '_wpnonce':'". esc_attr(wp_create_nonce("get-smart-reply"))."'}, function (data) {
                    if (data) {
                        var append = jQuery('input#append_smartreply1:checked').length;
                        if (append == 1) {
                            if(jQuery('#wp-mjsupport_message-wrap').hasClass('html-active')){
                                var content = jQuery('#mjsupport_message').val();
                                content = content + data;
                                jQuery('#mjsupport_message').val(content);
                            }else{
                                var content = tinyMCE.get('mjsupport_message').getContent();
                                content = content + data;
                                tinyMCE.get('mjsupport_message').execCommand('mceSetContent', true, content);
                            }


                        } else {
                            if(jQuery('#wp-mjsupport_message-wrap').hasClass('html-active')){
                                jQuery('#mjsupport_message').val(data);
                            }else{
                                tinyMCE.get('mjsupport_message').execCommand('mceSetContent', true, data);
                            }
                        }

                    }
                });
            }

            jQuery(document).delegate('#ticketidcopybtn', 'click', function() {
                var temp = jQuery('<input>');
                jQuery('body').append(temp);
                temp.val(jQuery('#ticketrandomid').val()).select();
                document.execCommand('copy');
                temp.remove();
                jQuery('#ticketidcopybtn').text(jQuery('#ticketidcopybtn').attr('success'));
            });

        ";
        wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
        ?>  
    <div class="mjtc-support-top-sec-header">
        <img class="mjtc-transparent-header-img1" alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
            src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tp-image.png" />
        <div class="mjtc-support-top-sec-left-header">
            <div class="mjtc-support-main-heading">
                <?php echo esc_html(__("Ticket Detail",'majestic-support')); ?>
            </div>
            <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageBreadcrumps('ticketdetail'); ?>
        </div>
    </div>
    <div class="mjtc-support-cont-main-wrapper">
        <div class="mjtc-support-cont-wrapper1">
            <div class="mjtc-support-ticket-detail-wrapper mjtc-support-ticket-detail-wrapper-color">
                <?php
        $yesno = array(
            (object) array('id' => '1', 'text' => esc_html(__('Yes', 'majestic-support'))),
            (object) array('id' => '0', 'text' => esc_html(__('No', 'majestic-support')))
        );
?>
                <div id="userpopupblack" style="display:none;"> </div>
                <?php
                $majesticsupport_js ="
                jQuery(document).ready(function() {
                    jQuery(document).on('submit', '#mjtc-support-usercredentails-form', function(e) {
                        e.preventDefault(); // avoid to execute the actual submit of the form.
                        var fdata = jQuery(this).serialize(); // serializes the form's elements.
                        jQuery.post(ajaxurl, {
                            action: 'mjsupport_ajax',
                            mjsmod: 'privatecredentials',
                            task: 'storePrivateCredentials',
                            formdata_string: fdata,
                            '_wpnonce':'". esc_attr(wp_create_nonce("store-private-credentials")) ."'
                        }, function(data) {
                            if (data) { // ajax executed
                                var return_data = jQuery.parseJSON(data);
                                if (return_data.status == 1) {
                                    jQuery('.mjtc-support-usercredentails-wrp').show();
                                    jQuery('.mjtc-support-usercredentails-form-wrap').hide();
                                    jQuery('.mjtc-support-usercredentails-credentails-wrp').append(MJTC_msDecodeHTML(return_data.content));
                                } else {
                                    alert(return_data.error_message);
                                }
                            } else {
                                jQuery('#usercredentailspopup').slideUp('slow');
                                setTimeout(function() {
                                    jQuery('div#userpopupblack').hide();
                                }, 700);
                            }
                        });
                    })
                });

                function addEditCredentail(ticketid, internalid, uid, cred_id = 0, cred_data = '') {
                    jQuery.post(ajaxurl, {
                        action: 'mjsupport_ajax',
                        mjsmod: 'privatecredentials',
                        task: 'getFormForPrivteCredentials',
                        ticketid: ticketid,
                        internalid: internalid,
                        cred_id: cred_id,
                        cred_data: cred_data,
                        uid: uid,
                        '_wpnonce':'". esc_attr(wp_create_nonce("get-form-for-privte-credentials")) ."'
                    }, function(data) {
                        if (data) { // ajax executed
                            var return_data = jQuery.parseJSON(data);
                            jQuery('.mjtc-support-usercredentails-wrp').hide();
                            jQuery('.mjtc-support-usercredentails-form-wrap').show();
                            jQuery('.mjtc-support-usercredentails-form-wrap').html(MJTC_msDecodeHTML(return_data));
                            if (cred_id != 0) {
                                jQuery('#mjtc-support-usercredentails-single-id-' + cred_id).remove();
                            }
                        }
                    });
                }

                function getCredentails(ticketid, internalid) {
                    jQuery.post(ajaxurl, {
                        action: 'mjsupport_ajax',
                        mjsmod: 'privatecredentials',
                        task: 'getPrivateCredentials',
                        ticketid: ticketid,
                        internalid: internalid,
                        '_wpnonce':'". esc_attr(wp_create_nonce("get-private-credentials")). "'
                    }, function(data) {
                        if (data) { // ajax executed
                            var return_data = jQuery.parseJSON(data);
                            if (return_data.status == 1) {
                                jQuery('#userpopupblack').show();
                                jQuery('#usercredentailspopup').slideDown('slow');
                                jQuery('.mjtc-support-usercredentails-wrp').slideDown('slow');
                                jQuery('.mjtc-support-usercredentails-form-wrap').hide();
                                if (return_data.content != '') {
                                    jQuery('.mjtc-support-usercredentails-credentails-wrp').html('');
                                    jQuery('.mjtc-support-usercredentails-credentails-wrp').append(MJTC_msDecodeHTML(return_data
                                        .content));
                                }
                            }
                        } else {
                            jQuery('#usercredentailspopup').slideUp('slow');
                            setTimeout(function() {
                                jQuery('div#userpopupblack').hide();
                            }, 700);
                        }
                    });
                    return false;
                }

                function removeCredentail(cred_id, internalid) {
                    var params = {
                        action: 'mjsupport_ajax',
                        mjsmod: 'privatecredentials',
                        task: 'removePrivateCredential',
                        cred_id: cred_id,
                        internalid: internalid,
                        '_wpnonce':'". esc_attr(wp_create_nonce("remove-private-credential")) ."'
                    };";
                    if(MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest() && isset(majesticsupport::$_data[0]->id)){
                        $majesticsupport_js .= '
                        params.email = "'. esc_attr(majesticsupport::$_data[0]->email) .'";
                        params.ticketrandomid = "'. esc_attr(majesticsupport::$_data[0]->ticketid) .'";';
                    }
                    $majesticsupport_js .= "
                    jQuery.post(ajaxurl, params, function(data) {
                        if (data) { // ajax executed
                            if (cred_id != 0) {
                                jQuery('#mjtc-support-usercredentails-single-id-' + cred_id).remove();
                            }
                        }
                    });
                    return false;
                }

                function closeCredentailsForm(ticketid, internalid) {
                    getCredentails(ticketid, internalid);
                }";
                wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
                ?>                
                <div id="usercredentailspopup" style="display: none;">
                    <div class="mjtc-support-usercredentails-header">
                        <?php echo esc_html(__('Private Credentials', 'majestic-support')); ?><span
                            class="close-credentails"></span>
                    </div>
                    <div class="mjtc-support-usercredentails-wrp" style="display: none;">
                        <div class="mjtc-support-usercredentails-credentails-wrp">
                        </div>
                        <?php
                    if(in_array('privatecredentials',majesticsupport::$_active_addons) && majesticsupport::$_data[0]->status != 4 && majesticsupport::$_data[0]->status != 5 ){
                        $credential_add_permission = false;
                        if(in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()){
                            $credential_add_permission = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Add Credentials');
                        }elseif(current_user_can('manage_options')){
                            $credential_add_permission = true;
                        }elseif (MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid() != 0) {
                            if(MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid() == majesticsupport::$_data[0]->uid){
                                $credential_add_permission = true;
                            }
                        }elseif (MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid() == 0) {
                            $credential_add_permission = true;
                        }
                        if($credential_add_permission){ ?>
                        <div class="mjtc-support-usercredentail-data-add-new-button-wrap">
                            <button class="mjtc-support-usercredentail-data-add-new-button"
                                onclick="addEditCredentail(<?php echo esc_js(majesticsupport::$_data[0]->id);?>,'<?php echo esc_js(majesticsupport::$_data[0]->internalid);?>',<?php echo esc_js(MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid());?>);">
                                <?php echo esc_html(__("Add New Credential",'majestic-support')); ?>
                            </button>
                        </div><?php
                        }
                    }
                    ?>
                    </div>
                    <div class="mjtc-support-usercredentails-form-wrap">
                    </div>
                </div>

                <div id="userpopup" style="display:none;">
                    <!-- Ticket History popup -->
                    <div class="mjtc-row mjtc-support-popup-row">
                        <form id="userpopupsearch">
                            <div class="search-center-history">
                                <?php echo esc_html(__('Ticket History', 'majestic-support')); ?><span
                                    class="close-history"></span></div>
                        </form>
                    </div>
                    <div id="records">
                        <?php // data[5] holds the tickect history
                $field_array = MJTC_includer::MJTC_getModel('fieldordering')->getFieldTitleByFieldfor(1);
                if ((!empty(majesticsupport::$_data[5]))) {
                    ?>
                        <div class="mjtc-support-history-table-wrp">
                            <table class="mjtc-table mjtc-table-striped">
                                <thead>
                                    <tr>
                                        <th class="mjtc-support-textalign-center">
                                            <?php echo esc_html(__('Date','majestic-support'));?></th>
                                        <th class="mjtc-support-textalign-center">
                                            <?php echo esc_html(__('Time','majestic-support'));?></th>
                                        <th class=""><?php echo esc_html(__('Message Logs','majestic-support'));?></th>
                                    </tr>
                                </thead>
                                <tbody class="mjtc-support-ticket-history-body">
                                    <?php foreach (majesticsupport::$_data[5] AS $history) { ?>
                                    <tr>
                                        <td class="mjtc-support-textalign-center">
                                            <?php echo esc_html(date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($history->datetime))); ?>
                                        </td>
                                        <td class="mjtc-support-textalign-center">
                                            <?php echo esc_html(date_i18n('H:i:s', MJTC_majesticsupportphplib::MJTC_strtotime($history->datetime))); ?>
                                        </td>
                                        <?php
                                        if (is_super_admin($history->uid)) {
                                            $message = 'admin';
                                        } elseif ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff($history->uid)) {
                                            $message = 'agent';
                                        } else {
                                            $message = 'member';
                                        }
                                        ?>
                                        <td class=""><?php echo wp_kses_post($history->message); ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="mjtc-support-priorty-btn-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_button('canceleee', esc_html(__('Close', 'majestic-support')), array('class' => 'mjtc-support-priorty-cancel','onclick'=>'closePopup();')), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <?php
                $majesticsupport_js = "
                function showPopupAndFillValues(id, pfor) {
                    jQuery('div.edit-time-popup').hide();
                    if (pfor == 1) {
                        jQuery.post(ajaxurl, {
                            action: 'mjsupport_ajax',
                            val: id,
                            mjsmod: 'reply',
                            task: 'getReplyDataByID',
                            '_wpnonce':'". esc_attr(wp_create_nonce('get-reply-data-by-id')) ."'
                        }, function(data) {
                            if (data) {
                                jQuery('div.popup-header-text').html(
                                    '". esc_html(__('Edit Reply','majestic-support')) ."');
                                d = jQuery.parseJSON(data);
                                tinyMCE.get('mjsupport_replytext').execCommand('mceSetContent', false, d.message);
                                jQuery('div.edit-time-popup').hide();
                                jQuery('form#ms-time-edit-form').hide();
                                jQuery('form#ms-note-edit-form').hide();
                                jQuery('form#ms-reply-form').show();
                                jQuery('input#reply-replyid').val(id);
                                jQuery('div.ms-popup-background').show();
                                jQuery('div#ms-popup-wrapper').slideDown('slow');
                            }
                        });
                    } else if (pfor == 2) {
                        jQuery.post(ajaxurl, {
                            action: 'mjsupport_ajax',
                            val: id,
                            mjsmod: 'timetracking',
                            task: 'getTimeByReplyID',
                            '_wpnonce':'". esc_attr(wp_create_nonce('get-time-by-reply-id')) ."'
                        }, function(data) {
                            if (data) {
                                jQuery('div.popup-header-text').html(
                                    '". esc_html(__('Edit Time','majestic-support')) ."');
                                d = jQuery.parseJSON(data);
                                jQuery('div.edit-time-popup').hide();
                                jQuery('form#ms-reply-form').hide();
                                jQuery('form#ms-note-edit-form').hide();
                                jQuery('div.system-time-div').hide();
                                jQuery('form#ms-time-edit-form').show();
                                jQuery('input#reply-replyid').val(id);
                                jQuery('div.ms-popup-background').show();
                                jQuery('div#ms-popup-wrapper').slideDown('slow');
                                jQuery('input#edited_time').val(d.time);
                                jQuery('textarea#edit_reason').text(d.desc);
                                if (d.conflict == 1) {
                                    jQuery('div.system-time-div').show();
                                    jQuery('input#time-confilct').val(d.conflict);
                                    jQuery('input#systemtime').val(d.systemtime);
                                    jQuery('select#time-confilct-combo').val(0);
                                }
                            }
                        });
                    } else if (pfor == 3) {
                        jQuery.post(ajaxurl, {
                            action: 'mjsupport_ajax',
                            val: id,
                            mjsmod: 'note',
                            task: 'getTimeByNoteID',
                            '_wpnonce':'". esc_attr(wp_create_nonce('get-time-by-note-id')) ."'
                        }, function(data) {
                            if (data) {
                                jQuery('div.popup-header-text').html(
                                    '". esc_html(__('Edit Time','majestic-support')) ."');
                                d = jQuery.parseJSON(data);
                                jQuery('div.edit-time-popup').hide();
                                jQuery('form#ms-reply-form').hide();
                                jQuery('form#ms-note-edit-form').show();
                                jQuery('form#ms-time-edit-form').hide();
                                jQuery('div.system-time-div').hide();
                                jQuery('input#note-noteid').val(id);
                                jQuery('div.ms-popup-background').show();
                                jQuery('div#ms-popup-wrapper').slideDown('slow');
                                jQuery('input#edited_time').val(d.time);
                                jQuery('textarea#edit_reason').text(d.desc);
                                if (d.conflict == 1) {
                                    jQuery('div.system-time-div').show();
                                    jQuery('input#time-confilct').val(d.conflict);
                                    jQuery('input#systemtime').val(d.systemtime);
                                    jQuery('select#time-confilct-combo').val(0);
                                }
                            }
                        });
                    } else if (pfor == 4) {
                        jQuery.post(ajaxurl, {
                            action: 'mjsupport_ajax',
                            ticketid: id,
                            mjsmod: 'mergeticket',
                            task: 'getTicketsForMerging',
                            '_wpnonce':'". esc_attr(wp_create_nonce('get-tickets-for-merging')) ."'
                        }, function(data) {
                            if (data) {
                                jQuery('div.popup-header-text').html(
                                    '". esc_html(__('Merge Ticket','majestic-support')) ."');
                                data = jQuery.parseJSON(data);
                                jQuery('div#popup-record-data').html('');
                                jQuery('div#popup-record-data').slideDown('slow');
                                jQuery('div#popup-record-data').html(MJTC_msDecodeHTML(data['data']));
                            }
                        });
                    }
                    return false;
                }

                function updateticketlist(pagenum, ticketid) {
                    jQuery.post(ajaxurl, {
                        action: 'mjsupport_ajax',
                        mjsmod: 'mergeticket',
                        task: 'getTicketsForMerging',
                        ticketid: ticketid,
                        ticketlimit: pagenum,
                        '_wpnonce':'". esc_attr(wp_create_nonce('get-tickets-for-merging')) ."'
                    }, function(data) {
                        if (data) {
                            data = jQuery.parseJSON(data);
                            jQuery('div#popup-record-data').html('');
                            jQuery('div#popup-record-data').html(MJTC_msDecodeHTML(data['data']));
                        }
                    });
                }";
                wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
                ?>
                <span style="display:none"
                    id="filesize"><?php echo esc_html(__('Error file size too large', 'majestic-support')); ?></span>
                <span style="display:none"
                    id="fileext"><?php echo esc_html(__('The uploaded file extension not valid', 'majestic-support')); ?></span>
                <div class="ms-popup-background" style="display:none"></div>
                <div id="popup-record-data" style="display:inline-block;width:100%;"></div>
                <div id="ms-popup-wrapper" class="ms-popup-wrapper" style="display:none">
                    <!-- Js Ticket Edit Time Popups -->
                    <div class="ms-popup-header">
                        <div class="popup-header-text">
                            <?php echo esc_html(__('Edit Timer','majestic-support'))?>
                        </div>
                        <div class="popup-header-close-img">
                        </div>
                    </div>
                    <div class="edit-time-popup" style="display:none;">
                        <div class="mjtc-support-edit-form-wrp">
                            <div class="mjtc-support-edit-field-title">
                                <?php echo esc_html(__('Time', 'majestic-support')); ?>&nbsp;<span style="color: red">*</span>
                            </div>
                            <div class="mjtc-support-edit-field-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_text('edited_time', '', array('class' => 'inputbox mjtc-support-edit-field-input')), MJTC_ALLOWED_TAGS) ?>
                            </div>
                            <div class="mjtc-support-edit-field-title">
                                <?php echo esc_html(__('Reason For Editing the timer', 'majestic-support')); ?>
                            </div>
                            <div class="mjtc-support-edit-field-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_textarea('t_desc', '', array('class' => 'inputbox')), MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <div class="mjtc-support-priorty-btn-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('pok', esc_html(__('Save', 'majestic-support')), array('class' => 'mjtc-support-priorty-save','onclick' => 'updateTimerFromPopup();')), MJTC_ALLOWED_TAGS); ?>
                                <?php echo wp_kses(MJTC_formfield::MJTC_button('canceleeee', esc_html(__('Cancel', 'majestic-support')), array('class' => 'mjtc-support-priorty-cancel','onclick'=>'closePopup();')), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                    </div>

                    <form id="ms-reply-form" style="display:none" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_reply&task=saveeditedreply&action=mstask"),"save-edited-reply")); ?>">
                        <div class="mjtc-support-edit-form-wrp">
                            <div class="mjtc-support-form-field-wrp">
                                <?php wp_editor('', 'mjsupport_replytext', array('media_buttons' => false,'editor_height' => 200, 'textarea_rows' => 20,)); ?>
                            </div>
                        </div>
                        <div class="mjtc-support-priorty-btn-wrp">
                            <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('ppok', esc_html(__('Save', 'majestic-support')), array('class' => 'mjtc-support-priorty-save')), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_button('canceleeeee', esc_html(__('Cancel', 'majestic-support')), array('class' => 'mjtc-support-priorty-cancel','onclick'=>'closePopup();')), MJTC_ALLOWED_TAGS); ?>
                        </div>
                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('reply-replyid', ''), MJTC_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('reply-tikcetid',majesticsupport::$_data[0]->id), MJTC_ALLOWED_TAGS); ?>
                    </form>
                    <?php if(in_array('timetracking', majesticsupport::$_active_addons)){ ?>

                    <form id="ms-time-edit-form" style="display:none" method="post"
                        action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_reply&task=saveeditedtime&action=mstask"),"save-edited-time")); ?>">
                        <div class="mjtc-support-edit-form-wrp">
                            <div class="mjtc-support-edit-field-title">
                                <?php echo esc_html(__('Time', 'majestic-support')); ?>&nbsp;<span style="color: red">*</span>
                            </div>
                            <div class="mjtc-support-edit-field-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_text('edited_time', '', array('class' => 'inputbox mjtc-support-edit-field-input')), MJTC_ALLOWED_TAGS) ?>
                            </div>
                            <div class="mjtc-support-edit-field-title">
                                <?php echo esc_html(__('System Time', 'majestic-support')); ?>
                            </div>
                            <div class="mjtc-support-edit-field-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_text('systemtime', '', array('class' => 'inputbox mjtc-support-edit-field-input','disabled'=>'disabled')), MJTC_ALLOWED_TAGS) ?>
                            </div>
                            <div class="mjtc-support-edit-field-title">
                                <?php echo esc_html(__('Reason For Editing', 'majestic-support')); ?>
                            </div>
                            <div class="mjtc-support-edit-field-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_textarea('edit_reason', '', array('class' => 'inputbox mjtc-support-edit-field-input')), MJTC_ALLOWED_TAGS) ?>
                            </div>
                            <div class="mjtc-form-wrapper system-time-div" style="display:none;">
                                <div class="mjtc-form-title"><?php echo esc_html(__('Resolve conflict', 'majestic-support')); ?>
                                </div>
                                <div class="mjtc-form-value">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_select('time-confilct-combo', $yesno, ''), MJTC_ALLOWED_TAGS); ?>
                                </div>
                            </div>
                            <div class="mjtc-support-priorty-btn-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('pppok', esc_html(__('Save', 'majestic-support')), array('class' => 'mjtc-support-priorty-save','onclick' => 'updateTimerFromPopup();')), MJTC_ALLOWED_TAGS); ?>
                                <?php echo wp_kses(MJTC_formfield::MJTC_button('canceleeeeee', esc_html(__('Cancel', 'majestic-support')), array('class' => 'mjtc-support-priorty-cancel','onclick'=>'closePopup();')), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('reply-replyid', ''), MJTC_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('reply-tikcetid',majesticsupport::$_data[0]->id), MJTC_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('time-confilct',''), MJTC_ALLOWED_TAGS); ?>
                    </form>

                    <form id="ms-note-edit-form" style="display:none" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_note&task=saveeditedtime&action=mstask"),"save-edited-time")); ?>">
                        <div class="mjtc-col-md-12 mjtc-form-wrapper">
                            <div class="mjtc-col-md-12 mjtc-form-title"><?php echo esc_html(__('Time', 'majestic-support')); ?>
                            </div>
                            <div class="mjtc-col-md-12 mjtc-form-value">
                                <?php echo wp_kses(MJTC_formfield::MJTC_text('edited_time', '', array('class' => 'inputbox')), MJTC_ALLOWED_TAGS) ?>
                            </div>
                        </div>
                        <div class="mjtc-col-md-12 mjtc-form-wrapper system-time-div" style="display:none;">
                            <div class="mjtc-col-md-12 mjtc-form-title">
                                <?php echo esc_html(__('System Time', 'majestic-support')); ?></div>
                            <div class="mjtc-col-md-12 mjtc-form-value">
                                <?php echo wp_kses(MJTC_formfield::MJTC_text('systemtime', '', array('class' => 'inputbox','disabled'=>'disabled')), MJTC_ALLOWED_TAGS) ?>
                            </div>
                        </div>
                        <div class="mjtc-col-md-12 mjtc-form-wrapper">
                            <div class="mjtc-col-md-12 mjtc-form-title">
                                <?php echo esc_html(__('Reason For Editing', 'majestic-support')); ?>
                            </div>
                            <div class="mjtc-col-md-12 mjtc-form-value">
                                <?php echo wp_kses(MJTC_formfield::MJTC_textarea('edit_reason', '', array('class' => 'inputbox')), MJTC_ALLOWED_TAGS) ?>
                            </div>
                        </div>
                        <div class="mjtc-col-md-12 mjtc-form-wrapper system-time-div" style="display:none;">
                            <div class="mjtc-col-md-12 mjtc-form-title">
                                <?php echo esc_html(__('Resolve conflict', 'majestic-support')); ?>
                            </div>
                            <div class="mjtc-col-md-12 mjtc-form-value">
                                <?php echo wp_kses(MJTC_formfield::MJTC_select('time-confilct-combo', $yesno, ''), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                        <div class="mjtc-col-md-12 mjtc-form-button-wrapper">
                            <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('ppppok', esc_html(__('Save', 'majestic-support')), array('class' => 'button')),MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_button('cancele', esc_html(__('Cancel', 'majestic-support')), array('class' => 'button', 'onclick'=>'closePopup();')), MJTC_ALLOWED_TAGS); ?>
                        </div>
                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('note-noteid', ''), MJTC_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('note-tikcetid',majesticsupport::$_data[0]->id), MJTC_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('time-confilct',''), MJTC_ALLOWED_TAGS); ?>
                    </form>
                    <?php } ?>
                </div>
                <div class="ms-popup-wrapper ms-merge-popup-wrapper" style="display:none">
                    <div class="ms-popup-header">
                        <div class="popup-header-text">
                            <?php echo esc_html(__('Edit Timer','majestic-support'))?>
                        </div>
                        <div class="popup-header-close-img">
                        </div>
                    </div>
                </div>

                <?php
        if($printflag == false && majesticsupport::$_data['user_staff'] && majesticsupport::$_data[0]->status != 4 && majesticsupport::$_data[0]->status != 5){
            ?>

                <?php if(in_array('actions',majesticsupport::$_active_addons)){ 
				if(MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Ticket Department Transfer')){
				?>
                <div id="popupfordepartmenttransfer" style="display:none">
                    <div class="ms-popup-header">
                        <div class="popup-header-text">
                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['department']) . " " . __('Transfer', 'majestic-support')); ?>
                        </div>
                        <div class="popup-header-close-img">
                        </div>
                    </div>
                    <div>
                        <form method="post" action="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket','task'=>'transferdepartment')),"transfer-department")); ?>"
                            enctype="multipart/form-data">
                            <div class="mjtc-support-premade-msg-wrp">
                                <!-- Select Department Wrapper -->
                                <div class="mjtc-support-premade-field-title">
                                    <?php echo esc_html(__('Select', 'majestic-support') . " " . majesticsupport::MJTC_getVarValue($field_array['department'])); ?></div>
                                <div class="mjtc-support-premade-field-wrp">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_select('departmentid', MJTC_includer::MJTC_getModel('department')->getDepartmentForCombobox(), isset(majesticsupport::$_data[0]->departmentid) ? majesticsupport::$_data[0]->departmentid : '', esc_html(__('Select', 'majestic-support') . " " . majesticsupport::MJTC_getVarValue($field_array['department'])), array('class' => 'mjtc-support-premade-select')), MJTC_ALLOWED_TAGS); ?>

                                </div>
                            </div>
                            <?php if(in_array('note', majesticsupport::$_active_addons)){ ?>
                            <div class="mjtc-support-text-editor-wrp">
                                <div class="mjtc-support-text-editor-field-title">
                                    <?php echo esc_html(__('Type Note for', 'majestic-support') . " " . majesticsupport::MJTC_getVarValue($field_array['department'])); ?></div>
                                <div class="mjtc-support-text-editor-field">
                                    <?php wp_editor('', 'departmenttranfernote', array('media_buttons' => false)); ?>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="mjtc-support-reply-form-button-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('departmenttransferbutton', esc_html(__('Transfer', 'majestic-support')), array('class' => 'button mjtc-support-save-button', 'onclick' => "return checktinymcebyid('departmenttranfernote');")), MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ticketid', majesticsupport::$_data[0]->id), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('uid', MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid()), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'ticket_transferdepartment'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mspageid', get_the_ID()), MJTC_ALLOWED_TAGS); ?>
                        </form>
                    </div> <!-- end of departmenttransfer div -->
                </div>
                <?php } ?>
                <?php } ?>

                <?php if(in_array('agent',majesticsupport::$_active_addons)){ 
				if(MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Assign Ticket To Agent')){
				?>
                <div id="popupforagenttransfer" style="display:none">
                    <div class="ms-popup-header">
                        <div class="popup-header-text">
                            <?php echo esc_html(__('Assign Ticket To Agent', 'majestic-support')); ?>
                        </div>
                        <div class="popup-header-close-img">
                        </div>
                    </div>
                    <div>
                        <form method="post" action="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket','task'=>'assigntickettostaff')),"assign-ticket-to-staff")); ?>"
                            enctype="multipart/form-data">
                            <div class="mjtc-support-premade-msg-wrp">
                                <!-- Select Department Wrapper -->
                                <div class="mjtc-support-premade-field-title">
                                    <?php echo esc_html(__('Agent', 'majestic-support')); ?></div>
                                <div class="mjtc-support-premade-field-wrp">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_select('staffid', MJTC_includer::MJTC_getModel('agent')->getStaffForCombobox(), majesticsupport::$_data[0]->staffid, esc_html(__('Select Agent', 'majestic-support')), array('class' => 'inputbox mjtc-support-premade-select')), MJTC_ALLOWED_TAGS); ?>
                                </div>
                            </div>
                            <?php if(in_array('note', majesticsupport::$_active_addons)){ ?>
                            <div class="mjtc-support-text-editor-wrp">
                                <div class="mjtc-support-text-editor-field-title">
                                    <?php echo esc_html(__('Assigning Note', 'majestic-support')); ?></div>
                                <div class="mjtc-support-text-editor-field">
                                    <?php wp_editor('', 'assignnote', array('media_buttons' => false)); ?>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="mjtc-support-reply-form-button-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('assigntostaff', esc_html(__('Assign', 'majestic-support')), array('class' => 'button mjtc-support-save-button', 'onclick' => "return checktinymcebyid('assignnote');")), MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ticketid', majesticsupport::$_data[0]->id), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('uid', MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid()), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'ticket_assigntickettostaff'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mspageid', get_the_ID()), MJTC_ALLOWED_TAGS); ?>
                        </form>
                    </div> <!-- end of assigntostaff div -->
                </div>
                <?php } ?>
                <?php } ?>

                <?php if(in_array('note',majesticsupport::$_active_addons)){ ?>
                <div id="popupforinternalnote" style="display:none">
                    <div class="ms-popup-header">
                        <div class="popup-header-text">
                            <?php echo esc_html(__('Internal Note', 'majestic-support')); ?>
                        </div>
                        <div class="popup-header-close-img">
                        </div>
                    </div>
                    <div>
                        <!--  postinternalnote Area   -->
                        <form method="post" action="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'note','task'=>'savenote')),"save-note")); ?>"
                            enctype="multipart/form-data">
                            <?php if(in_array('timetracking', majesticsupport::$_active_addons)){ ?>
                            <div class="ms-ticket-detail-timer-wrapper">
                                <!-- Top Timer Section -->
                                <div class="timer-left">
                                    <?php echo esc_html(__('Time Track','majestic-support')); ?>
                                </div>
                                <div class="timer-right">
                                    <div class="timer-total-time">
                                        <?php
                                            $hours = floor(majesticsupport::$_data['time_taken'] / 3600);
                                            $mins = floor(majesticsupport::$_data['time_taken'] / 60);
                                            $mins = floor($mins % 60);
                                            $secs = floor(majesticsupport::$_data['time_taken'] % 60);
                                            echo esc_html(esc_html(__('Time Taken','majestic-support')).':&nbsp;'.sprintf('%02d:%02d:%02d', $hours, $mins, $secs));
                                        ?>
                                    </div>
                                    <div class="timer">
                                        00:00:00
                                    </div>
                                    <div class="timer-buttons">
                                        <?php if(MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Edit Time')){ ?>
                                        <span class="timer-button" onclick="showEditTimerPopup()">
                                            <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                                src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/timer-edit.png" />
                                        </span>
                                        <?php } ?>
                                        <span class="timer-button cls_1" onclick="changeTimerStatus(1)">
                                            <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                                src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/play.png" />
                                        </span>
                                        <span class="timer-button cls_2" onclick="changeTimerStatus(2)">
                                            <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                                src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/pause.png" />
                                        </span>
                                        <span class="timer-button cls_3" onclick="changeTimerStatus(3)">
                                            <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                                src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/stop.png" />
                                        </span>
                                    </div>
                                </div>
                                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('timer_time_in_seconds',''), MJTC_ALLOWED_TAGS); ?>

                                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('timer_edit_desc',''), MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <?php } ?>
                            <div class="mjtc-support-internalnote-wrp">
                                <!-- Ticket Tittle -->
                                <div class="mjtc-support-internalnote-field-title">
                                    <?php echo esc_html(__('Title', 'majestic-support')); ?>
                                </div>
                                <div class="mjtc-support-internalnote-field-wrp">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_text('internalnotetitle', '', array('class' => 'inputbox mjtc-support-internalnote-input')), MJTC_ALLOWED_TAGS) ?>
                                </div>
                            </div>
                            <div class="mjtc-support-text-editor-wrp">
                                <div class="mjtc-support-text-editor-field-title">
                                    <?php echo esc_html(__('Type Internal Note', 'majestic-support')); ?></div>
                                <div class="mjtc-support-text-editor-field">
                                    <?php wp_editor('', 'internalnote', array('media_buttons' => false)); ?>
                                </div>
                            </div>
                            <?php
                            if(isset($field_array['attachments'])){?>
                                <div class="mjtc-support-reply-attachments">
                                    <!-- Attachments -->
                                    <div class="mjtc-attachment-field-title">
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['attachments'])); ?></div>
                                    <div class="mjtc-attachment-field">
                                        <div class="tk_attachment_value_wrapperform tk_attachment_staff_reply_wrapper">
                                            <span class="tk_attachment_value_text">
                                                <input type="file" class="inputbox mjtc-attachment-inputbox"
                                                    name="note_attachment"
                                                    onchange="MJTC_uploadfile(this, '<?php echo esc_js(majesticsupport::$_config['file_maximum_size']); ?>', '<?php echo esc_js(majesticsupport::$_config['file_extension']); ?>');"
                                                    size="20" />
                                                <span class='tk_attachment_remove'></span>
                                            </span>
                                        </div>
                                        <span class="tk_attachments_configform">
                                            <?php
                                                $data = esc_html(__('Maximum File Size', 'majestic-support')).' (' . esc_html(majesticsupport::$_config['file_maximum_size']).' KB)<br>'.esc_html(__('File Extension Type', 'majestic-support')).' (' . esc_html(majesticsupport::$_config['file_extension']) . ')';
                                                echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <?php
                            } ?>
                            <div class="mjtc-support-closeonreply-wrp">
                                <div class="mjtc-support-closeonreply-title">
                                    <?php echo esc_html(__('Ticket Status', 'majestic-support')); ?>
                                </div>
                                <div class="replyFormStatus mjtc-form-title-position-reletive-left">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_checkbox('closeonreply', array('1' => esc_html(__('Close on reply', 'majestic-support'))), '', array('class' => 'radiobutton mjtc-support-closeonreply-checkbox')), MJTC_ALLOWED_TAGS); ?>
                                </div>
                            </div>
                            <div class="mjtc-support-reply-form-button-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('postinternalnote', esc_html(__('Post Internal Note', 'majestic-support')), array('class' => 'button mjtc-support-save-button', 'onclick' => "return checktinymcebyid('internalnote');")), MJTC_ALLOWED_TAGS); ?>
                            </div>

                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ticketid', majesticsupport::$_data[0]->id), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('uid', MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid()), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'note_savenote'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mspageid', get_the_ID()), MJTC_ALLOWED_TAGS); ?>
                        </form>
                    </div> <!-- end of postinternalnote div -->
                </div>
                <?php } ?>

                <?php
        }
        ?>



                <?php
            majesticsupport::$_data['custom']['ticketid'] = majesticsupport::$_data[0]->id;
                if (majesticsupport::$_data[0]->lock == 1) {
                    $style = "darkred";
                    $status = esc_html(__('Lock', 'majestic-support'));
                } elseif (majesticsupport::$_data[0]->status == 0) {
                    $style = "#5bb12f";
                    $status = esc_html(__('New', 'majestic-support'));
                } elseif (majesticsupport::$_data[0]->status == 1) {
                    $style = "#28abe3";
                    $status = esc_html(__('Waiting Reply', 'majestic-support'));
                } elseif (majesticsupport::$_data[0]->status == 2) {
                    $style = "#69d2e7";
                    $status = esc_html(__('In Progress', 'majestic-support'));
                } elseif (majesticsupport::$_data[0]->status == 3) {
                    $style = "#FFB613";
                    $status = esc_html(__('Replied', 'majestic-support'));
                } elseif (majesticsupport::$_data[0]->status == 4) {
                    $style = "#ed1c24";
                    $status = esc_html(__('Closed', 'majestic-support'));
                }
                $cur_uid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
                if (in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_data['user_staff']) {
                    $link = wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket','task'=>'actionticket')),"action-ticket");
                } else {
                    $link = wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'reply','task'=>'savereply')),"save-reply");
                }
                ?>
                <?php if($printflag != true){?>
                <form method="post" action="<?php echo esc_url($link); ?>" id="adminTicketform"
                    enctype="multipart/form-data">
                    <?php } ?>
                    <!-- Ticket Detail Left -->
                    <div class="mjtc-sprt-det-left">
                        <div class="mjtc-sprt-det-cnt mjtc-sprt-det-info-wrp">
                            <!-- Ticket Detail Info Wrp -->
                            <div class="mjtc-sprt-det-user">
                                <!-- Ticket Detail Box -->
                                <div class="mjtc-sprt-det-user-image">
                                    <!-- Left Side Image -->
                                    <?php if (in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_data[0]->staffphotophoto) { ?>
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" class="mjtc-support-staff-img"
                                        src="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent','task'=>'getStaffPhoto','action'=>'mstask','majesticsupportid'=> majesticsupport::$_data[0]->staffphotoid ,'mspageid'=>get_the_ID()))); ?>">
                                    <?php } else {
                                        echo wp_kses(ms_get_avatar(MJTC_includer::MJTC_getModel('majesticsupport')->getWPUidById(majesticsupport::$_data[0]->uid), 'mjtc-support-staff-img'), MJTC_ALLOWED_TAGS);
                                    } ?>
                                </div>
                                <div class="mjtc-sprt-det-user-cnt">
                                    <!-- Right Side -->
                                    <?php
                                    if(isset($field_array['fullname'])){ ?>
                                        <div class="mjtc-sprt-det-user-data name">
                                            <?php echo esc_html(esc_html(majesticsupport::MJTC_getVarValue(majesticsupport::$_data[0]->name))); ?>
                                        </div>
                                        <?php
                                    }
                                    if (isset($field_array['subject'])) { ?>
                                        <div class="mjtc-sprt-det-user-data subject">
                                            <?php echo esc_html(esc_html(majesticsupport::MJTC_getVarValue(majesticsupport::$_data[0]->subject))); ?>
                                        </div>
                                        <?php
                                    }
                                    if (isset($field_array['email'])) { ?>
                                        <div class="mjtc-sprt-det-user-data email">
                                            <?php echo esc_html(majesticsupport::$_data[0]->email); ?>
                                        </div>
                                        <?php
                                    }
                                    if (isset($field_array['phone'])) { ?>
                                        <div class="mjtc-sprt-det-user-data number">
                                            <?php echo esc_html(majesticsupport::$_data[0]->phone); ?>
                                        </div>
                                        <?php
                                    }?>
                                </div>
                            </div>
                            <div class="mjtc-sprt-det-other-tkt">
                                <!-- Ticket Detail View Btn -->
                                <?php
                                if(isset(majesticsupport::$_data['nticket'])){
                                    if(in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()){
                                        $url = majesticsupport::makeUrl(array('mjsmod'=>'agent','mjslay'=>'staffmyticket','uid'=>majesticsupport::$_data[0]->uid));
                                    } else {
                                        $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'myticket'));
                                    }
                                    ?>
                                    <a class="mjtc-sprt-det-other-tkt-btn" href="<?php echo esc_url($url); ?>">
                                        <?php
                                            if(in_array('agent', majesticsupport::$_active_addons) && majesticsupport::$_data['user_staff']){
    											echo esc_html(__('View all','majestic-support')).' '.esc_html(majesticsupport::$_data['nticket']).' '.esc_html(__('tickets by','majestic-support')).' '.esc_html(majesticsupport::$_data[0]->name);
                                            }else{
    											echo esc_html(__('View all','majestic-support')).' '.esc_html(majesticsupport::$_data['nticket']).' '.esc_html(__('tickets','majestic-support'));
                                            }
                                            ?>
                                    </a>
                                    <?php
                                } ?>
                            </div>
                            <div class="mjtc-sprt-det-tkt-msg">
                                <!-- Ticket Detail Message -->
                                <?php
                                if (isset($field_array['issuesummary'])) {
                                    echo wp_kses(majesticsupport::$_data[0]->message, MJTC_ALLOWED_TAGS);
                                }
                                majesticsupport::$_data['custom']['ticketid'] = majesticsupport::$_data[0]->id;
                                $customfields = MJTC_includer::MJTC_getObjectClass('customfields')->MJTC_userFieldsData(1, null, majesticsupport::$_data[0]->multiformid);
                                if (!empty($customfields)){
                                    ?>
                                    <div class="mjtc-sprt-det-tkt-custm-flds">
                                        <?php
                                        foreach ($customfields as $field) {
                                            if ($field->userfieldtype != 'termsandconditions') {
                                                $ret = MJTC_includer::MJTC_getObjectClass('customfields')->MJTC_showCustomFields($field,2, majesticsupport::$_data[0]->params);
                                                ?>
                                                <div class="mjtc-sprt-det-info-data">
                                                    <div class="mjtc-sprt-det-info-tit">
                                                        <?php echo wp_kses($ret['title'], MJTC_ALLOWED_TAGS).': '; ?>
                                                    </div>
                                                    <div class="mjtc-sprt-det-info-val">
                                                        <?php echo wp_kses($ret['value'], MJTC_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                        }
                                    ?>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="mjtc-sprt-det-actn-btn-wrp">
                                <!-- Ticket Action Button -->
                                <?php if ($printflag == false){
                                        $printpermission = false;
                                        $mergepermission = false;
                                    if (in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_data['user_staff'] && majesticsupport::$_data[0]->status != 5 ) {
                                        $printpermission = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Print Ticket');
                                        $mergepermission = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Ticket Merge');

                                        ?>
                                <a class="mjtc-sprt-det-actn-btn"
                                    href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent','mjslay'=>'staffaddticket','majesticsupportid'=>majesticsupport::$_data[0]->id))); ?>"
                                    title="<?php echo esc_attr(__('Edit Ticket', 'majestic-support')); ?>">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/edit.png"
                                        title="<?php echo esc_attr(__('Edit', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Edit', 'majestic-support')); ?></span>
                                </a>
                                <?php if (majesticsupport::$_data[0]->status != 4) {
                                    if (in_array('ticketclosereason',majesticsupport::$_active_addons)) {
                                        $js = 'showTicketCloseReasons('.esc_js(majesticsupport::$_data[0]->id).')';
                                    } else {
                                        $js = 'actionticket(2);';
                                    }
                                ?>
                                <a class="mjtc-sprt-det-actn-btn" href="#" onclick="<?php echo esc_js($js);?>"
                                    title="<?php echo esc_attr(__('Close Ticket', 'majestic-support')); ?>">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/close.png"
                                        title="<?php echo esc_attr(__('Close', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Close', 'majestic-support')); ?></span>
                                </a>
                                <?php } else { ?>
                                <a class="mjtc-sprt-det-actn-btn" href="#" onclick="actionticket(3);"
                                    title="<?php echo esc_attr(__('Reopen Ticket', 'majestic-support')); ?>">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/reopen.png"
                                        title="<?php echo esc_attr(__('Reopen', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Reopen', 'majestic-support')); ?></span>
                                </a>
                                <?php } ?>
                                <?php if(in_array('tickethistory', majesticsupport::$_active_addons)){ ?>
                                <a class="mjtc-sprt-det-actn-btn" href="#" id="showhistory" title="<?php echo esc_attr(__('Ticket History', 'majestic-support')); ?>" />
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/history.png"
                                        title="<?php echo esc_attr(__('History', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Ticket History', 'majestic-support')); ?></span>
                                </a>
                                <?php }?>
                                <?php if(in_array('mergeticket',majesticsupport::$_active_addons) && $mergepermission) {
                                            if (majesticsupport::$_data[0]->status != 4 && majesticsupport::$_data[0]->status != 5) { ?>
                                <a class="mjtc-sprt-det-actn-btn" href="#" id="mergeticket" title="<?php echo esc_attr(__('Merge Ticket', 'majestic-support')); ?>" 
                                    onclick="return showPopupAndFillValues(<?php echo esc_js(majesticsupport::$_data[0]->id);?>,4)">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/merge-ticket.png"
                                        title="<?php echo esc_attr(__('Merge', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Merge', 'majestic-support')); ?></span>
                                </a>
                                <?php }/*Merge Ticket*/
                                        } ?>
                                <?php if(in_array('actions',majesticsupport::$_active_addons)){ ?>
                                <?php if($printpermission && majesticsupport::$_data[0]->status != 5) { ?>
                                <a class="mjtc-sprt-det-actn-btn" href="#" id="print-link" title="<?php echo esc_attr(__('Print Ticket', 'majestic-support')); ?>" 
                                    data-ticketid="<?php echo esc_attr(majesticsupport::$_data[0]->id); ?>">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/print.png"
                                        title="<?php echo esc_attr(__('Print', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Print', 'majestic-support')); ?></span>
                                </a>
                                <!-- Print Ticket -->
                                <?php } ?>
                                <?php } ?>
                                <?php $deletepermission = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Delete Ticket');
                                    if($deletepermission) { ?>
                                        <a class="mjtc-sprt-det-actn-btn" title="<?php echo esc_attr(__('Delete Ticket', 'majestic-support')); ?>" onclick="return confirm('<?php echo esc_html(__('Are you sure you want to delete this ticket', 'majestic-support')); ?>');" href="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket','task'=>'deleteticket','action'=>'mstask','internalid'=> majesticsupport::$_data[0]->internalid,'ticketid'=> majesticsupport::$_data[0]->id ,'mspageid'=>get_the_ID())),'delete-ticket')); ?>"
                                            data-ticketid="<?php echo esc_attr(majesticsupport::$_data[0]->id); ?>">
                                            <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/delete.png" title="<?php echo esc_attr(__('Delete', 'majestic-support')); ?>" />
                                            <span><?php echo esc_html(__('Delete', 'majestic-support')); ?></span>
                                        </a>
                                <?php
                                    }
                                    $credentialpermission = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('View Credentials');
                                    if(in_array('privatecredentials',majesticsupport::$_active_addons) && $credentialpermission){ ?>
                                <a class="mjtc-sprt-det-actn-btn" href="javascript:return false;"
                                    id="private-credentials-button" title="<?php echo esc_attr(__('Private Credentials', 'majestic-support')); ?>" 
                                    onclick="getCredentails(<?php echo esc_js(majesticsupport::$_data[0]->id); ?>, '<?php echo esc_js(majesticsupport::$_data[0]->internalid); ?>')">
                                    <?php $query = "SELECT count(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_privatecredentials` WHERE status = 1 AND ticketid = ".majesticsupport::$_data[0]->id;
                                        $cred_count = majesticsupport::$_db->get_var($query);
                                        if ($cred_count>0) {
                                            $img_name = 'private-credentials-exist.png';
                                        } else {
                                            $img_name = 'private-credentials.png';
                                        }
                                    ?>
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/<?php echo esc_attr($img_name);?>"
                                        title="<?php echo esc_attr(__('Print', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Private Credentials', 'majestic-support')); ?></span>
                                </a>
                                <?php
                                        }
                                    } else { ?>
                                <?php if (majesticsupport::$_data[0]->status != 5) { ?>
                                <?php if (majesticsupport::$_data[0]->status != 4) { ?>
                                    <?php if (in_array('ticketclosereason',majesticsupport::$_active_addons)) { ?>
                                            <a onclick="showTicketCloseReasons(<?php echo esc_html(majesticsupport::$_data[0]->id)?>, '<?php echo esc_html(majesticsupport::$_data[0]->internalid)?>')" title="<?php echo esc_attr(__('Close Ticket', 'majestic-support')); ?>" class="mjtc-sprt-det-actn-btn">
                                                <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/close.png"
                                                    title="<?php echo esc_attr(__('Close', 'majestic-support')); ?>" />
                                                <span><?php echo esc_html(__('Close', 'majestic-support')); ?></span>
                                            </a>
                                    <?php } else { ?>
                                            <a onclick="return confirm('<?php echo esc_html(__('Are you sure to close this ticket', 'majestic-support')); ?>');" title="<?php echo esc_attr(__('Close Ticket', 'majestic-support')); ?>" class="mjtc-sprt-det-actn-btn" href="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket','task'=>'closeticket','action'=>'mstask','ticketid'=> majesticsupport::$_data[0]->id,'internalid'=> majesticsupport::$_data[0]->internalid ,'mspageid'=>get_the_ID())),"close-ticket")); ?>">
                                                <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/close.png"
                                                    title="<?php echo esc_attr(__('Close', 'majestic-support')); ?>" />
                                                <span><?php echo esc_html(__('Close', 'majestic-support')); ?></span>
                                            </a>
                                    <?php } ?>
                                <?php if(in_array('tickethistory', majesticsupport::$_active_addons)){ ?>
                                <a class="mjtc-sprt-det-actn-btn mjtc-margin-right" href="#" id="showhistory" title="<?php echo esc_attr(__('Ticket History', 'majestic-support')); ?>">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/history.png"
                                        title="<?php echo esc_attr(__('Ticket History', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Ticket History', 'majestic-support')); ?></span>
                                </a>
                                <?php }
                                } else {
                                    if (MJTC_includer::MJTC_getModel('ticket')->checkCanReopenTicket(majesticsupport::$_data[0]->id)) {
                                        $link = wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket','task'=>'reopenticket','action'=>'mstask','ticketid'=> majesticsupport::$_data[0]->id,'internalid'=> majesticsupport::$_data[0]->internalid,'mspageid'=>get_the_ID())),"reopen-ticket"); ?>
                                <a class="mjtc-sprt-det-actn-btn" href="<?php echo esc_url($link); ?>"
                                    title="<?php echo esc_attr(__('Reopen Ticket', 'majestic-support')); ?>">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/reopen.png"
                                        title="<?php echo esc_attr(__('Reopen', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Reopen', 'majestic-support')); ?></span>
                                </a>
                                <?php } ?>
                                <?php } ?>
                                <?php } ?>
                                <?php if (majesticsupport::$_config['show_ticket_delete_button'] == 1) { ?>
                                <a class="mjtc-sprt-det-actn-btn" title="<?php echo esc_attr(__('Delete Ticket', 'majestic-support')); ?>" onclick="return confirm('<?php echo esc_html(__('Are you sure you want to delete this ticket', 'majestic-support')); ?>');" href="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket','task'=>'deleteticket','action'=>'mstask','internalid'=> majesticsupport::$_data[0]->internalid,'ticketid'=> majesticsupport::$_data[0]->id ,'mspageid'=>get_the_ID())),'delete-ticket')); ?>" data-ticketid="<?php echo esc_attr(majesticsupport::$_data[0]->id); ?>">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/delete.png" title="<?php echo esc_attr(__('Delete', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Delete', 'majestic-support')); ?></span>
                                </a>
                                <?php } ?>
                                <?php
                                if(majesticsupport::$_config['print_ticket_user'] == 1 ){
                                    if(in_array('actions',majesticsupport::$_active_addons)){ ?>
                                        <a class="mjtc-sprt-det-actn-btn" title="<?php echo esc_attr(__('Print Ticket', 'majestic-support')); ?>" href="#" id="print-link" data-ticketid="<?php echo esc_attr(majesticsupport::$_data[0]->id); ?>">
                                            <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/print.png" title="<?php echo esc_attr(__('Print', 'majestic-support')); ?>" />
                                            <span><?php echo esc_html(__('Print', 'majestic-support')); ?></span>
                                        </a>
                                        <?php
                                    }
                                }
                                if(in_array('privatecredentials',majesticsupport::$_active_addons) && majesticsupport::$_data[0]->status != 4 && majesticsupport::$_data[0]->status != 5){ ?>
                                    <a class="mjtc-sprt-det-actn-btn" title="<?php echo esc_attr(__('Private Credentials', 'majestic-support')); ?>" href="javascript:return false;" id="private-credentials-button" onclick="getCredentails(<?php echo esc_js(majesticsupport::$_data[0]->id); ?>, '<?php echo esc_js(majesticsupport::$_data[0]->internalid); ?>')">
                                        <?php $query = "SELECT count(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_privatecredentials` WHERE status = 1 AND ticketid = ".majesticsupport::$_data[0]->id;
                                            $cred_count = majesticsupport::$_db->get_var($query);
                                            if ($cred_count>0) {
                                                $img_name = 'private-credentials-exist.png';
                                            } else {
                                                $img_name = 'private-credentials.png';
                                            } ?>
                                        <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/<?php echo esc_attr($img_name);?>" title="<?php echo esc_attr(__('Print', 'majestic-support')); ?>" />
                                        <span><?php echo esc_html(__('Private Credentials', 'majestic-support')); ?></span>
                                    </a>
                                <?php
                                }
                                } ?>
                                <?php if (in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_data['user_staff'] && majesticsupport::$_data[0]->status != 5) { ?>
                                <?php if (in_array('actions',majesticsupport::$_active_addons)) { ?>
                                <?php if (majesticsupport::$_data[0]->lock == 1) { ?>
                                <a class="mjtc-sprt-det-actn-btn" href="#" onclick="actionticket(5);"
                                    title="<?php echo esc_attr(__('Unlock Ticket', 'majestic-support')); ?>">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/unlock.png"
                                        title="<?php echo esc_attr(__('Unlock', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Unlock', 'majestic-support')); ?></span>
                                </a>
                                <?php } else { ?>
                                <a class="mjtc-sprt-det-actn-btn" href="#" onclick="actionticket(4);"
                                    title="<?php echo esc_attr(__('Lock Ticket', 'majestic-support')); ?>">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/lock.png"
                                        title="<?php echo esc_attr(__('Lock', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Lock', 'majestic-support')); ?></span>
                                </a>
                                <?php } ?>
                                <?php } ?>
                                <?php if(in_array('banemail', majesticsupport::$_active_addons)){ ?>
                                <?php
                                                if (MJTC_includer::MJTC_getModel('banemail')->isEmailBan(majesticsupport::$_data[0]->email)) { ?>
                                <a title="<?php echo esc_attr(__('Unban Email', 'majestic-support')); ?>" class="mjtc-sprt-det-actn-btn" href="#" onclick="actionticket(7);">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/un-ban.png"
                                        title="<?php echo esc_attr(__('Unban Email', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Unban Email', 'majestic-support')); ?></span>
                                </a>
                                <?php } else { ?>
                                <a class="mjtc-sprt-det-actn-btn" href="#" title="<?php echo esc_attr(__('Ban Email', 'majestic-support')); ?>" onclick="actionticket(6);">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/ban.png">
                                    <span><?php echo esc_html(__('Ban Email', 'majestic-support')); ?></span>
                                </a>
                                <?php } ?>
                                <?php } ?>
                                <?php if(in_array('overdue', majesticsupport::$_active_addons)){ ?>
                                <?php if (majesticsupport::$_data[0]->isoverdue == 1) { ?>
                                <a class="mjtc-sprt-det-actn-btn" href="#" onclick="actionticket(11);" title="<?php echo esc_attr(__('Unmark Overdue', 'majestic-support')); ?>" />
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/un-over-due.png"
                                        title="<?php echo esc_attr(__('Unmark Overdue', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Unmark Overdue', 'majestic-support')); ?></span>
                                </a>
                                <?php } else { ?>
                                <a class="mjtc-sprt-det-actn-btn" title="<?php echo esc_attr(__('Mark Overdue', 'majestic-support')); ?>"  href="#" onclick="actionticket(8);">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/over-due.png"
                                        title="<?php echo esc_attr(__('Mark Overdue', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Mark Overdue', 'majestic-support')); ?></span>
                                </a>
                                <?php } ?>
                                <?php } ?>
                                <?php if (in_array('actions',majesticsupport::$_active_addons)) { ?>
                                <a class="mjtc-sprt-det-actn-btn" href="#" onclick="actionticket(9);" title="<?php echo esc_attr(__('Mark In Progress', 'majestic-support')); ?>">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/ticket-detail/in-progress.png'; ?>"
                                        title="<?php echo esc_attr(__('Mark in Progress', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Mark in Progress', 'majestic-support'));?></span>
                                </a>
                                <?php } ?>
                                <?php if(in_array('banemail', majesticsupport::$_active_addons)){ ?>
                                <a class="mjtc-sprt-det-actn-btn" href="#" onclick="actionticket(10);" title="<?php echo esc_attr(__('Ban Email And Close Ticket', 'majestic-support')); ?>">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/ticket-detail/ban-email-close-ticket.png'; ?>"
                                        title="<?php echo esc_attr(__('Ban Email and Close Ticket', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Ban Email and Close Ticket', 'majestic-support')); ?></span>
                                </a>
                                <?php } ?>
                                <?php } ?>
                                <?php } else { ?>
                                <a class="mjtc-sprt-det-actn-btn" title="<?php echo esc_attr(__('Print Ticket', 'majestic-support')); ?>" href="javascript:window.print();">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/print.png" title="<?php echo esc_attr(__('Print', 'majestic-support')); ?>" />
                                    <span><?php echo esc_html(__('Print', 'majestic-support')); ?></span>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                        if (in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_data['user_staff']) {
                            if (in_array('note', majesticsupport::$_active_addons)) {
                                ?>
                        <!-- Ticket Detail Internal Note -->
                        <div class="mjtc-sprt-det-title">
                            <?php echo esc_html(__('Internal Note', 'majestic-support')); ?>
                        </div> <!-- Heading -->
                        <?php
                                foreach (majesticsupport::$_data[6] AS $note) {
                                    ?>
                        <div class="mjtc-support-detail-box mjtc-support-post-reply-box">
                            <!-- Ticket Detail Box -->
                            <div class="mjtc-support-detail-left mjtc-support-white-background">
                                <!-- Left Side Image -->
                                <div class="mjtc-support-user-img-wrp">
                                    <?php if (in_array('agent',majesticsupport::$_active_addons) && $note->staffphoto) { ?>
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" class="mjtc-support-staff-img"
                                        src="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent','task'=>'getStaffPhoto','action'=>'mstask','majesticsupportid'=> $note->staff_id ,'mspageid'=>get_the_ID()))); ?>">
                                    <?php } else {
                                                    if (isset(majesticsupport::$_data[0]->uid) && !empty(majesticsupport::$_data[0]->uid)) {
                                                        echo wp_kses(get_avatar($note->uid), MJTC_ALLOWED_TAGS);
                                                    } else { ?>
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" class="mjtc-support-staff-img"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL) . '/includes/images/ticketmanbig.png'; ?>" />
                                    <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="mjtc-support-detail-right mjtc-support-background">
                                <!-- Right Side Ticket Data -->
                                <div class="mjtc-support-rows-wrapper">
                                    <div class="mjtc-support-rows-wrp">
                                        <div class="mjtc-support-field-value name">
                                            <?php echo !empty($note->staffname) ? esc_html($note->staffname) : esc_html($note->display_name); ?>
                                        </div>
                                    </div>
                                    <?php if (isset($note->title) && $note->title != '') { ?>
                                    <div class="mjtc-support-rows-wrp">
                                        <div class="mjtc-support-field-value">
                                            <span class="mjtc-support-field-value-t"></span><?php echo esc_html($note->title); ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="mjtc-support-rows-wrp">
                                        <div class="mjtc-support-row">
                                            <div class="mjtc-support-field-value">
                                                <?php echo wp_kses_post($note->note); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                                if(in_array('timetracking', majesticsupport::$_active_addons)){ ?>
                                    <div class="mjtc-support-edit-options-wrp">
                                        <?php if(MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Edit Time') && majesticsupport::$_data[0]->status != 5){ ?>
                                        <a class="mjtc-button" href="#" title="<?php echo esc_attr(__('Edit Time', 'majestic-support')); ?>"
                                            onclick="return showPopupAndFillValues(<?php echo esc_js($note->id);?>,3)">
                                            <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                                src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/edit-reply.png" />
                                            <?php echo esc_html(__('Edit Time','majestic-support'));?>
                                        </a>
                                        <?php
                                                    }
                                                    $hours = floor($note->usertime / 3600);
                                                    $mins = floor($note->usertime / 60);
                                                    $mins = floor($mins % 60);
                                                    $secs = floor($note->usertime % 60);
                                                    $time = esc_html(__('Time Taken','majestic-support')).':&nbsp;'.sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                                                    ?>
                                        <span class="mjtc-support-thread-time"><?php echo esc_html($time); ?></span>
                                    </div>
                                    <?php } ?>

                                    <?php
                                                if($note->filesize > 0 && !empty($note->filename)){ ?>
                                    <div class="mjtc-support-attachments-wrp">
                                        <div class="mjtc_supportattachment">
                                            <span class="mjtc-support-download-file-title">
                                                <?php
                                                    $data =  esc_html(majesticsupport::MJTC_getVarValue($note->filename)).'(' . esc_html(majesticsupport::MJTC_getVarValue($note->filesize / 1024)) . ')';
                                                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                                ?>
                                            </span>
                                            <a class="mjtc-download-button" target="_blank"
                                                href="<?php echo esc_url(admin_url('?page=majesticsupport_note&action=mstask&task=downloadbyid&id='.esc_attr($note->id))); ?>">
                                                <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" class="mjtc-support-download-img"
                                                    src="<?php echo esc_url(MJTC_PLUGIN_URL);?>/includes/images/ticket-detail/download.png">
                                            </a>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="mjtc-support-time-stamp-wrp">
                                    <span class="mjtc-support-ticket-created-date">
                                        <?php echo esc_html(date_i18n("l F d, Y, h:i:s", MJTC_majesticsupportphplib::MJTC_strtotime($note->created))); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php
                                }
                                ?>
                        <div class="mjtc-support-thread-add-btn">
                            <a href="#" id="internalnotebtn" class="mjtc-support-thread-add-btn-link" title="<?php echo esc_attr(__('Post Internal Note', 'majestic-support')); ?>">
                                <img alt="<?php echo esc_html(__('Post New Internal Note','majestic-support')); ?>"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/edit-time.png" />
                                <?php echo esc_html(__("Post Internal Note",'majestic-support')); ?>
                            </a>
                        </div>
                        <?php
                            }
                        }
                        ?>

                        <!-- Ticket Detail Thread -->
                        <div class="mjtc-sprt-det-title thread">
                            <?php echo esc_html(__('Ticket Thread', 'majestic-support')); ?>
                        </div> <!-- Heading -->
                        <div class="mjtc-support-thread internal-note">
                            <!-- Ticket Detail Box -->
                            <div class="mjtc-support-thread-image">
                                <!-- Left Side Image -->
                                <?php if (in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_data[0]->staffphotophoto) { ?>
                                <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" class="mjtc-support-staff-img"
                                    src="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent','task'=>'getStaffPhoto','action'=>'mstask','majesticsupportid'=> majesticsupport::$_data[0]->staffphotoid ,'mspageid'=>get_the_ID()))); ?>">
                                <?php } else {
                                    echo wp_kses(ms_get_avatar(MJTC_includer::MJTC_getModel('majesticsupport')->getWPUidById(majesticsupport::$_data[0]->uid), 'mjtc-support-staff-img'), MJTC_ALLOWED_TAGS);
                                } ?>
                            </div>
                            <div class="mjtc-support-thread-cnt">
                                <!-- Right Side Ticket Data -->
                                <div class="mjtc-support-thread-data">
                                    <span class="mjtc-support-thread-person">
                                        <?php echo esc_html(majesticsupport::$_data[0]->name); ?>
                                    </span>
                                </div>
                                <div class="mjtc-support-thread-data">
                                    <span class="mjtc-support-thread-email">
                                        <?php echo esc_html(majesticsupport::$_data[0]->email); ?>
                                    </span>
                                </div>
                                <div class="mjtc-support-thread-data note-msg">
                                    <?php echo wp_kses_post(majesticsupport::$_data[0]->message); ?>
                                    <?php
                                    if (isset($field_array['attachments']) && !empty(majesticsupport::$_data['ticket_attachment'])) { ?>
                                        <div class="mjtc-support-attachments-wrp">
                                            <?php
                                            foreach (majesticsupport::$_data['ticket_attachment'] AS $attachment) {
                                                $path = majesticsupport::makeUrl(array('mjsmod'=>'ticket','task'=>'downloadbyid','action'=>'mstask','id'=> $attachment->id ,'mspageid'=>get_the_ID()));
                                                $data = wp_check_filetype($attachment->filename);
                                                $type = $data['type'];
                                                $ticketdata = '
                                                <div class="mjtc_supportattachment">
                                                    <span class="mjtc_supportattachment_fname">
                                                        ' . esc_html($attachment->filename) . '
                                                    </span>
                                                    <a class="mjtc-download-button" target="_blank" href="' . esc_url($path) . '">'
                                                        .esc_html(__('Download', 'majestic-support')).'
                                                    </a>';
                                                    echo wp_kses($ticketdata, MJTC_ALLOWED_TAGS);
                                                    if(MJTC_majesticsupportphplib::MJTC_strpos($type, "image") !== false) {
                                                        $ticketdata = '
                                                        <a data-gall="gallery-ticket-thread" class="mjtc-download-button venobox" data-vbtype="image" title="'.esc_html(__('View','majestic-support')).'" href="'. esc_url(MJTC_includer::MJTC_getModel('attachment')->getAttachmentImage($attachment->id)) .'"  target="_blank">
                                                            <img alt="'.esc_html(__('View Image','majestic-support')).'" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/ticket-detail/view.png" />
                                                        </a>';
                                                        echo wp_kses($ticketdata, MJTC_ALLOWED_TAGS);
                                                    }
                                                    echo wp_kses('</div>', MJTC_ALLOWED_TAGS);
                                                }
                                                $ticketdata = '<a class="mjtc-all-download-button" target="_blank" href="' . esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'task'=>'downloadall', 'action'=>'mstask', 'downloadid'=>majesticsupport::$_data[0]->id , 'mspageid'=>get_the_ID()))) . '" >'. esc_html(__('Download All', 'majestic-support')) . '</a>';
                                                echo wp_kses($ticketdata, MJTC_ALLOWED_TAGS); ?>
                                            </div>
                                            <?php
                                        } ?>
                                    </div>
                                    <div class="mjtc-support-thread-cnt-btm">
                                        <span class="mjtc-support-thread-date">
                                            <?php echo esc_html(date_i18n("l F d, Y, h:i:s", MJTC_majesticsupportphplib::MJTC_strtotime(majesticsupport::$_data[0]->created))); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- User post Reply Section -->
                            <?php if (!empty(majesticsupport::$_data[4]))
                                foreach (majesticsupport::$_data[4] AS $reply):
                                    if ($cur_uid == $reply->uid) ?>
                                        <div class="mjtc-support-thread">
                                            <!-- Ticket Detail Box -->
                                            <div class="mjtc-support-thread-image">
                                                <!-- Left Side Image -->
                                                <?php if (in_array('agent',majesticsupport::$_active_addons) &&  $reply->staffphoto) { ?>
                                <img class="mjtc-support-staff-img"
                                    src="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent','task'=>'getStaffPhoto','action'=>'mstask','majesticsupportid'=> $reply->staffid ,'mspageid'=>get_the_ID()))); ?>">
                                <?php } else {
                                    echo wp_kses(ms_get_avatar(MJTC_includer::MJTC_getModel('majesticsupport')->getWPUidById($reply->uid), 'mjtc-support-staff-img'), MJTC_ALLOWED_TAGS);
                                } ?>
                            </div>
                            <div class="mjtc-support-thread-cnt">
                                <!-- Right Side Ticket Data -->
                                <div class="mjtc-support-thread-data">
                                    <span class="mjtc-support-thread-person">
                                        <?php
                                            if (majesticsupport::$_config['anonymous_name_on_ticket_reply'] == 1) {
                                                if(majesticsupport::$_data[0]->uid  != $reply->uid){ //reply by staff, need anonymous
                                                    echo esc_html(majesticsupport::$_config['title']);
                                                }else{ // reply by user   
                                                    echo esc_html($reply->name); 
                                                }
                                            }elseif(majesticsupport::$_config['anonymous_name_on_ticket_reply'] == 2){
                                                echo esc_html($reply->name); 
                                            }
                                        ?>
                                    </span>
                                    <?php 
                                    if(in_array('timetracking', majesticsupport::$_active_addons)){
                                        if($reply->staffid != 0){
                                            $hours = floor($reply->time / 3600);
                                            $mins = floor($reply->time / 60);
                                            $mins = floor($mins % 60);
                                            $secs = floor($reply->time % 60);
                                            $time = esc_html(__('Time Taken','majestic-support')).':&nbsp;'.sprintf('%02d:%02d:%02d', $hours, $mins, $secs); ?>
                                            <span class="mjtc-support-thread-time">
                                                <?php echo esc_html($time); ?>
                                            </span>
                                            <?php
                                        }
                                    } ?>
                                </div>
                                <?php
								if (majesticsupport::$_config['show_email_on_ticket_reply'] == 1) {
                                    if(isset($reply->staffemail)){ ?>
                                        <div class="mjtc-support-thread-data">
                                            <span class="mjtc-support-thread-email">
                                                <?php echo esc_html($reply->staffemail); ?>
                                            </span>
                                        </div>
                                        <?php
                                    } elseif(isset($reply->useremail)) { ?>
                                        <div class="mjtc-support-thread-data">
                                            <span class="mjtc-support-thread-email">
                                                <?php echo esc_html($reply->useremail); ?>
                                            </span>
                                        </div>
                                        <?php
                                    }
							    }?>
                                <div class="mjtc-support-thread-data">
                                    <?php echo ($reply->ticketviaemail == 1) ? esc_html(__('Created via Email', 'majestic-support')) : ''; ?>
                                </div>
                                <div class="mjtc-support-thread-data note-msg">
                                    <?php echo wp_kses_post(html_entity_decode($reply->message)); ?>
                                    <?php if (!empty($reply->attachments)) { ?>
                                    <div class="mjtc-support-attachments-wrp">
                                        <?php
                                        $attachmentdata = '';
                                        foreach ($reply->attachments AS $attachment) {
                                            $path = majesticsupport::makeUrl(array('mjsmod'=>'ticket','task'=>'downloadbyid','action'=>'mstask','id'=> $attachment->id ,'mspageid'=>get_the_ID()));
                                            $data = wp_check_filetype($attachment->filename);
                                            $type = $data['type'];
                                            $attachmentdata .= '
                                                <div class="mjtc_supportattachment">
                                                    <span class="mjtc-support-download-file-title">
                                                        ' . esc_html($attachment->filename) . ' ( ' . esc_html(round($attachment->filesize,2)) . ' kb) ' . '
                                                    </span>
                                                    <a class="mjtc-download-button" target="_blank" href="' . esc_url($path) . '">'
                                                        . esc_html(__('Download', 'majestic-support')) .'
                                                    </a>';
                                                    if(MJTC_majesticsupportphplib::MJTC_strpos($type, "image") !== false) {
                                                        $path = MJTC_includer::MJTC_getModel('attachment')->getAttachmentImage($attachment->id);
                                                        $attachmentdata .= '<a data-gall="gallery-'. esc_attr($reply->replyid) .'" class="mjtc-download-button venobox" data-vbtype="image" title="'. esc_attr(esc_html(__('View','majestic-support'))) .'" href="'. esc_url($path) .'"  target="_blank">
                                                        <img alt="'.esc_html(__('View Image','majestic-support')).'" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/ticket-detail/view.png" />
                                                            </a>';
                                                    }
                                                    $attachmentdata .= '
                                                </div>';
                                        }
                                        $attachmentdata .= '<a class="mjtc-all-download-button" target="_blank" href="' . esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'task'=>'downloadallforreply', 'action'=>'mstask', 'downloadid'=>$reply->replyid , 'mspageid'=>get_the_ID(), '_wpnonce'=> wp_create_nonce('download-all-for-reply')))) . '" onclick="" target="_blank">'. esc_html(__('Download All', 'majestic-support')) . '</a>';
                                        echo wp_kses($attachmentdata, MJTC_ALLOWED_TAGS);
                                        ?>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php
                                if (in_array('agent',majesticsupport::$_active_addons) &&  majesticsupport::$_data['user_staff']) { ?>
                                    <div class="mjtc-support-thread-cnt-btm">
                                        <div class="mjtc-support-thread-date">
                                            <?php echo esc_html(date_i18n("l F d, Y, h:i:s", MJTC_majesticsupportphplib::MJTC_strtotime($reply->created))); ?>
                                        </div>
                                        <div class="mjtc-support-thread-actions">
                                            <?php
                                            if(MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Edit Reply') && majesticsupport::$_data[0]->status != 5){ ?>
                                                <a class="mjtc-support-thread-actn-btn ticket-edit-reply-button" href="#"
                                                    onclick="return showPopupAndFillValues(<?php echo esc_js($reply->replyid);?>,1)">
                                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/edit-reply.png" />
                                                    <?php echo esc_html(__('Edit Reply','majestic-support'));?>
                                                </a>
                                                <?php
                                            }
                                            if(in_array('timetracking', majesticsupport::$_active_addons)){
                                                if(MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Edit Time') && majesticsupport::$_data[0]->status != 5){ ?>
                                                    <a class="mjtc-support-thread-actn-btn ticket-edit-time-button" href="#" onclick="return showPopupAndFillValues(<?php echo esc_js($reply->replyid);?>,2)">
                                                        <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-detail/edit-reply.png" />
                                                        <?php echo esc_html(__('Edit Time','majestic-support'));?>
                                                    </a>
                                                    <?php
                                            	}
                                			} ?>
                                        </div>
                                    </div>
                                    <?php
                                } ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <!-- User post Reply Form Section -->
                        <div class="mjtc-support-reply-forms-wrapper">
                            <!-- Ticket Reply Forms Wrapper -->
                            <?php
                            if($printflag == false){
                                if (!majesticsupport::$_data['user_staff']) {
                                    if (majesticsupport::$_data[0]->status != 4 && majesticsupport::$_data[0]->lock != 1 && majesticsupport::$_data[0]->status != 5): ?>
                                        <div class="mjtc-support-reply-forms-heading">
                                            <?php echo esc_html(__('Reply A Message', 'majestic-support')); ?>
                                        </div>
                                        <div id="postreply" class="mjtc-support-post-reply">
                                            <div class="mjtc-support-reply-field-wrp">
                                                <div class="mjtc-support-reply-field">
                                                    <?php wp_editor('', 'mjsupport_message', array('media_buttons' => false)); ?>
                                                </div>
                                            </div>
                                            <?php
                                            if (isset($field_array['attachments'])) { ?>
                                                <div class="mjtc-support-reply-attachments">
                                                    <!-- Attachments -->
                                                    <div class="mjtc-attachment-field-title 03">
                                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['attachments'])); ?>
                                                    </div>
                                                    <div class="mjtc-attachment-field">
                                                        <div class="tk_attachment_value_wrapperform tk_attachment_user_reply_wrapper">
                                                            <span class="tk_attachment_value_text">
                                                                <input type="file" class="inputbox mjtc-attachment-inputbox" name="filename[]" onchange="MJTC_uploadfile(this, '<?php echo esc_js(majesticsupport::$_config['file_maximum_size']); ?>', '<?php echo esc_js(majesticsupport::$_config['file_extension']); ?>');" size="20" />
                                                                <span class='tk_attachment_remove'></span>
                                                            </span>
                                                        </div>
                                                        <span class="tk_attachments_configform">
                                                            <?php
                                                            $data = esc_html(__('Maximum File Size', 'majestic-support')).' (' . esc_html(majesticsupport::$_config['file_maximum_size']).' KB)<br>'.esc_html(__('File Extension Type', 'majestic-support')).' (' . esc_html(majesticsupport::$_config['file_extension']) . ')';
                                                            echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                                            ?>
                                                        </span>
                                                        <span id="tk_attachment_add" data-ident="tk_attachment_user_reply_wrapper" class="tk_attachments_addform"><?php echo esc_html(__('Add more', 'majestic-support')); ?></span>
                                                    </div>
                                                </div>
                                                <?php
                                            }?>
                                        </div>
                                        <div class="mjtc-support-closeonreply-wrp">
                                            <div class="mjtc-support-closeonreply-title">
                                                <?php echo esc_html(__('Ticket Status', 'majestic-support')); ?></div>
                                            <div class="replyFormStatus mjtc-form-title-position-reletive-left">
                                                <?php echo wp_kses(MJTC_formfield::MJTC_checkbox('closeonreply', array('1' => esc_html(__('Close on reply', 'majestic-support'))), '', array('class' => 'radiobutton mjtc-support-closeonreply-checkbox')), MJTC_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="mjtc-support-reply-form-button-wrp">
                                            <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('postreplybutton', esc_html(__('Post Reply', 'majestic-support')), array('class' => 'button mjtc-support-save-button', 'onclick' => "return checktinymcebyid('message');")), MJTC_ALLOWED_TAGS); ?>
                                        </div>
                                        <?php endif; ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('actionid', ''), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('priority', ''), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ticketid', majesticsupport::$_data[0]->id), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('internalid', majesticsupport::$_data[0]->internalid), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('created', majesticsupport::$_data[0]->created), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('uid', MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid()), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ticketrandomid', majesticsupport::$_data[0]->ticketid), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('hash', majesticsupport::$_data[0]->hash), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('updated', majesticsupport::$_data[0]->updated), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mspageid', get_the_ID()), MJTC_ALLOWED_TAGS); ?>
                                    </div>
                                </form>
                                <?php
                            }else { ?>
                                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('actionid', ''), MJTC_ALLOWED_TAGS); ?>
                                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('priority', ''), MJTC_ALLOWED_TAGS); ?>
                                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ticketid', majesticsupport::$_data[0]->id), MJTC_ALLOWED_TAGS); ?>
                                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('internalid', majesticsupport::$_data[0]->internalid), MJTC_ALLOWED_TAGS); ?>
                                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('created', majesticsupport::$_data[0]->created), MJTC_ALLOWED_TAGS); ?>
                                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('uid', MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid()), MJTC_ALLOWED_TAGS); ?>
                                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('updated', majesticsupport::$_data[0]->updated), MJTC_ALLOWED_TAGS); ?>
                                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mspageid', get_the_ID()), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </form>
                        <?php
                        if (majesticsupport::$_data[0]->status != 4 && majesticsupport::$_data[0]->status != 5) { ?>
                            <div id="postreply" class="mjtc-det-tkt-rply-frm">
                                <!-- Post Reply Area -->
                                <form class="mjtc-det-tkt-form" method="post" action="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'reply','task'=>'savereply')),"save-reply")); ?>"
                                    enctype="multipart/form-data">
                                    <div class="mjtc-sprt-det-title">
                                        <?php echo esc_html(__('Post Reply','majestic-support')); ?>
                                    </div>
                                    <?php if(in_array('timetracking', majesticsupport::$_active_addons)){ ?>
                                    <div class="ms-ticket-detail-timer-wrapper">
                                        <!-- Timer Wrapper -->
                                        <div class="timer-left">
                                            <div class="timer-total-time">
                                                <?php
                                                    $hours = floor(majesticsupport::$_data['time_taken'] / 3600);
                                                    $mins = floor(majesticsupport::$_data['time_taken'] / 60);
                                                    $mins = floor($mins % 60);
                                                    $secs = floor(majesticsupport::$_data['time_taken'] % 60);
                                                    echo esc_html(esc_html(__('Time Taken','majestic-support')).':&nbsp;'.sprintf('%02d:%02d:%02d', $hours, $mins, $secs));
                                                ?>
                                            </div>
                                        </div>
                                        <div class="timer-right">
                                            <div class="timer">
                                                00:00:00
                                            </div>
                                            <div class="timer-buttons">
                                                <?php if(MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Edit Own Time')){ ?>
                                                <span class="timer-button" onclick="showEditTimerPopup()">
                                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/timer-edit.png" />
                                                </span>
                                                <?php } ?>
                                                <span class="timer-button cls_1" onclick="changeTimerStatus(1)">
                                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/play.png" />
                                                </span>
                                                <span class="timer-button cls_2" onclick="changeTimerStatus(2)">
                                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/pause.png" />
                                                </span>
                                                <span class="timer-button cls_3" onclick="changeTimerStatus(3)">
                                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/stop.png" />
                                                </span>
                                            </div>
                                        </div>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('timer_time_in_seconds',''), MJTC_ALLOWED_TAGS); ?>

                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('timer_edit_desc',''), MJTC_ALLOWED_TAGS); ?>
                                    </div>
                                    <!-- Smart Reply Area -->
                                    <div id="smartreply" class="ms-ticket-detail-smartreply-wrp ms-ticket-detail-smartreply-wrp-ticket-admin-details add-margin">
                                        <div class="ms-smartreply-img">
                                            <img alt="<?php echo esc_html(__('smart-reply','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/smart-reply/icon.png"/>
                                        </div>
                                        <div class="ms-smartreply-text">
                                            <div class="ms-smartreply-text-head">
                                                <?php echo esc_html(__('Check Smart Reply', 'majestic-support')); ?>
                                            </div>
                                            <div class="ms-smartreply-text-description">
                                                <?php echo esc_html(__('Search and get a saved smart reply on Majestic Support.', 'majestic-support')); ?>
                                            </div>
                                        </div>
                                        <span class="ms-smartreply-btn-wrp" onclick="checkSmartReply('<?php echo esc_js(majesticsupport::$_data[0]->subject); ?>');">
                                            <img alt="<?php echo esc_html(__('search','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/smart-reply/search.png" class="ms-smartreply-btn-img">
                                            <span class="ms-smartreply-btn-text">
                                                <?php echo esc_html(__('Check Reply', 'majestic-support')); ?>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="ms-ticket-detail-smartreply-footer-wrp">
                                        <span class="ms-ticket-detail-smartreply-append-wrp smartReplyFound">
                                            <?php echo wp_kses(MJTC_formfield::MJTC_checkbox('append_smartreply', array('1' => esc_html(__('Append', 'majestic-support'))), '', array('class' => 'radiobutton')), MJTC_ALLOWED_TAGS); ?>
                                        </span>
                                        <span class="ms-ticket-detail-smartreply-add-wrp"></span>
                                        <span class="ms-ticket-detail-smartreply-append-wrp smartReplyNotFound">
                                            <?php echo wp_kses(MJTC_formfield::MJTC_checkbox('add_smartreply', array('1' => esc_html(__('Add this reply to smart reply?', 'majestic-support'))), '', array('class' => 'radiobutton')), MJTC_ALLOWED_TAGS); ?>
                                        </span>
                                    </div>
                                    <?php } ?>
                                    <?php if(in_array('cannedresponses', majesticsupport::$_active_addons) && isset($field_array['premade'])){ ?>
                                    <div class="mjtc-support-premade-msg-wrp">
                                        <!-- Premade Message Wrapper -->
                                        <div class="mjtc-support-premade-field-title">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['premade'])); ?>&nbsp;<?php echo esc_html(__('Message', 'majestic-support')); ?>
                                        </div>
                                        <div class="mjtc-support-premade-field-wrp">
                                            <?php echo wp_kses(MJTC_formfield::MJTC_select('premadeid', MJTC_includer::MJTC_getModel('cannedresponses')->getPreMadeMessageForCombobox(), isset(majesticsupport::$_data[0]->premadeid) ? majesticsupport::$_data[0]->premadeid : '', esc_html(__('Select Premade', 'majestic-support')), array('class' => 'mjtc-support-premade-select', 'onchange' => 'getpremade(this.value);')), MJTC_ALLOWED_TAGS); ?>
                                            <span class="mjtc-support-apend-radio-btn">
                                                <?php echo wp_kses(MJTC_formfield::MJTC_checkbox('append_premade', array('1' => esc_html(__('Append', 'majestic-support'))), '', array('class' => 'radiobutton mjtc-support-premade-radiobtn')), MJTC_ALLOWED_TAGS); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="mjtc-support-text-editor-wrp">
                                        <div class="mjtc-support-text-editor-field-title">
                                            <?php echo esc_html(__('Type Message', 'majestic-support')); ?>
                                        </div>
                                        <div class="mjtc-support-text-editor-field">
                                            <?php wp_editor('', 'mjsupport_message', array('media_buttons' => false)); ?>
                                        </div>
                                    </div>
                                    <?php
                                    if(isset($field_array['attachments'])){ ?>
                                        <div class="mjtc-support-reply-attachments">
                                            <!-- Attachments -->
                                            <div class="mjtc-attachment-field-title 01">
                                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['attachments'])); ?></div>
                                            <div class="mjtc-attachment-field">
                                                <div class="tk_attachment_value_wrapperform tk_attachment_staff_reply_wrapper">
                                                    <span class="tk_attachment_value_text">
                                                        <input type="file" class="inputbox mjtc-attachment-inputbox" name="filename[]"
                                                            onchange="MJTC_uploadfile(this, '<?php echo esc_js(majesticsupport::$_config['file_maximum_size']); ?>', '<?php echo esc_js(majesticsupport::$_config['file_extension']); ?>');"
                                                            size="20" />
                                                        <span class='tk_attachment_remove'></span>
                                                    </span>
                                                </div>
                                                <span class="tk_attachments_configform">
                                                    <?php
                                                        $data = esc_html(__('Maximum File Size', 'majestic-support')).' (' . esc_html(majesticsupport::$_config['file_maximum_size']).' KB)<br>'.esc_html(__('File Extension Type', 'majestic-support')).' (' . esc_html(majesticsupport::$_config['file_extension']) . ')';
                                                        echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                                    ?>
                                                </span>
                                                <span id="tk_attachment_add" data-ident="tk_attachment_staff_reply_wrapper"
                                                    class="tk_attachments_addform" title="<?php echo esc_attr(__('Add More', 'majestic-support')); ?>"><?php echo esc_html(__('Add more', 'majestic-support')); ?></span>
                                            </div>
                                        </div>
                                        <?php
                                    } ?>
                                    <div class="mjtc-support-append-signature-wrp">
                                        <!-- Append Signature -->
                                        <div class="mjtc-support-append-field-title">
                                            <?php echo esc_html(__('Append Signature', 'majestic-support')); ?>
                                        </div>
                                        <div class="mjtc-support-append-field-wrp">
                                            <div class="mjtc-support-signature-radio-box">
                                                <?php echo wp_kses(MJTC_formfield::MJTC_checkbox('ownsignature', array('1' => esc_html(__('Own Signature', 'majestic-support'))), '', array('class' => 'radiobutton mjtc-support-append-radio-btn')), MJTC_ALLOWED_TAGS); ?>
                                            </div>
                                            <div class="mjtc-support-signature-radio-box">
                                                <?php echo wp_kses(MJTC_formfield::MJTC_checkbox('departmentsignature', array('1' => esc_html(__('Department Signature', 'majestic-support'))), '', array('class' => 'radiobutton mjtc-support-append-radio-btn')), MJTC_ALLOWED_TAGS); ?>
                                            </div>
                                            <div class="mjtc-support-signature-radio-box">
                                                <?php echo wp_kses(MJTC_formfield::MJTC_checkbox('nonesignature', array('1' => esc_html(__('JNone', 'majestic-support'))), '', array('class' => 'radiobutton mjtc-support-append-radio-btn')), MJTC_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if(in_array('agent',majesticsupport::$_active_addons) && isset($field_array['assignto'])){
                                        $staffid = MJTC_includer::MJTC_getModel('agent')->getStaffId(MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid());
                                        if (majesticsupport::$_data[0]->staffid != $staffid) {
                                        ?>
                                    <div class="mjtc-support-assigntome-wrp">
                                        <div class="mjtc-support-assigntome-field-title">
                                            <?php echo esc_html(__('Assign Ticket', 'majestic-support')); ?>
                                        </div>
                                        <div class="mjtc-support-assigntome-field-wrp">
                                            <?php
                                                if(majesticsupport::$_data[0]->staffid){
                                                    $checked = '';
                                                }else{
                                                    $checked = 1;
                                                }
                                                echo wp_kses(MJTC_formfield::MJTC_checkbox('assigntome', array('1' => esc_html(__('Assign to me', 'majestic-support'))), $checked, array('class' => 'radiobutton mjtc-support-assigntome-checkbox')), MJTC_ALLOWED_TAGS);
                                            ?>
                                        </div>
                                    </div><!-- Assign to me -->
                                    <?php }
                                                        } ?>
                                    <div class="mjtc-support-closeonreply-wrp">
                                        <div class="mjtc-support-closeonreply-title">
                                            <?php echo esc_html(__('Ticket Status', 'majestic-support')); ?>
                                        </div>
                                        <div class="replyFormStatus mjtc-form-title-position-reletive-left">
                                            <?php echo wp_kses(MJTC_formfield::MJTC_checkbox('closeonreply', array('1' => esc_html(__('Close on reply', 'majestic-support'))), '', array('class' => 'radiobutton mjtc-support-closeonreply-checkbox')), MJTC_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                    <div class="mjtc-support-reply-form-button-wrp">
                                        <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('postreply', esc_html(__('Post Reply', 'majestic-support')), array('class' => 'button mjtc-support-save-button', 'onclick' => "return checktinymcebyid('message');")), MJTC_ALLOWED_TAGS); ?>
                                    </div>
                                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('departmentid', majesticsupport::$_data[0]->departmentid), MJTC_ALLOWED_TAGS); ?>
                                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ticketid', majesticsupport::$_data[0]->id), MJTC_ALLOWED_TAGS); ?>
                                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('internalid', majesticsupport::$_data[0]->internalid), MJTC_ALLOWED_TAGS); ?>
                                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('uid', MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid()), MJTC_ALLOWED_TAGS); ?>
                                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ticketrandomid',majesticsupport::$_data[0]->ticketid), MJTC_ALLOWED_TAGS); ?>
                                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('hash', majesticsupport::$_data[0]->hash), MJTC_ALLOWED_TAGS); ?>
                                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'reply_savereply'), MJTC_ALLOWED_TAGS); ?>
                                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mspageid', get_the_ID()), MJTC_ALLOWED_TAGS); ?>
                                </form>
                            </div>
                            <?php
                        }
                    }
                } ?>
            </div>
            <?php
            if($printflag == true){?>
                </div> <!-- extra div for print -->
                <?php
            } ?>
            <!-- Ticket Detail Right -->
            <div class="mjtc-sprt-det-right">
                <div class="mjtc-sprt-det-cnt mjtc-sprt-det-tkt-info">
                    <!-- Ticket Info -->
                    <?php
                    if (majesticsupport::$_data[0]->status == 0) {
                        $color = "#5bb12f;";
                        $ticketmessage = esc_html(__('Open', 'majestic-support'));
                    } elseif (majesticsupport::$_data[0]->status == 1) {
                        $color = "#28abe3;";
                        $ticketmessage = esc_html(__('On Waiting', 'majestic-support'));
                    } elseif (majesticsupport::$_data[0]->status == 2) {
                        $color = "#69d2e7;";
                        $ticketmessage = esc_html(__('In Progress', 'majestic-support'));
                    } elseif (majesticsupport::$_data[0]->status == 3) {
                        $color = "#FFB613;";
                        $ticketmessage = esc_html(__('Replied', 'majestic-support'));
                    } elseif (majesticsupport::$_data[0]->status == 4) {
                        $color = "#ed1c24;";
                        $ticketmessage = esc_html(__('Closed', 'majestic-support'));
                    } elseif (majesticsupport::$_data[0]->status == 5) {
                        $color = "#dc2742;";
                        $ticketmessage = esc_html(__('Close and merge', 'majestic-support'));
                    }
                    ?>
                    <div class="mjtc-sprt-det-status" style="background-color:<?php echo esc_attr($color);?>;">
                        <?php echo esc_attr($ticketmessage); ?>
                    </div>
                    <?php
                    if (isset(majesticsupport::$_data[0]->closedreason) && majesticsupport::$_data[0]->status == 4) { ?>
                        <?php
                        $closedreasons = json_decode(majesticsupport::$_data[0]->closedreason);
                        if (is_array($closedreasons)) { ?>
                            <div class="mjtc-sprt-det-close-reason-wrp">
                                <div class="mjtc-sprt-det-info-data">
                                    <span class="mjtc-sprt-det-info-tit">
                                        <?php echo esc_html(__('Ticket Closing Reason', 'majestic-support')). ': '; ?>
                                    </span>
                                </div>
                                <?php
                                foreach ($closedreasons as $closedreason) { ?>
                                    <div class="mjtc-sprt-det-info-data">
                                        <span class="mjtc-sprt-det-info-val">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($closedreason)); ?>
                                        </span>
                                    </div>
                                    <?php
                                } ?>
                            </div>
                            <?php
                        } ?>
                        <?php
                    } ?>
                    <div class="mjtc-sprt-det-info-cnt">
                        <div class="mjtc-sprt-det-info-data">
                            <div class="mjtc-sprt-det-info-tit">
                                <?php echo esc_html(__('Created', 'majestic-support')) . ': '; ?>
                            </div>
                            <div class="mjtc-sprt-det-info-val"
                                title="<?php echo esc_attr(date_i18n("d F, Y, h:i:s A", MJTC_majesticsupportphplib::MJTC_strtotime(majesticsupport::$_data[0]->created))); ?>">
                                <?php echo esc_html(human_time_diff(MJTC_majesticsupportphplib::MJTC_strtotime(majesticsupport::$_data[0]->created),MJTC_majesticsupportphplib::MJTC_strtotime(date_i18n("Y-m-d H:i:s")))).' '.esc_html(__('ago', 'majestic-support')); ?>
                            </div>
                        </div>
                        <div class="mjtc-sprt-det-info-data">
                            <div class="mjtc-sprt-det-info-tit">
                                <?php echo esc_html(__('Last Reply', 'majestic-support')); ?><?php echo esc_html(__(': ', 'majestic-support'));?>
                            </div>
                            <div class="mjtc-sprt-det-info-val">
                                <?php if (empty(majesticsupport::$_data[0]->lastreply) || majesticsupport::$_data[0]->lastreply == '0000-00-00 00:00:00') echo esc_html(__('No Last Reply', 'majestic-support'));
                                        else echo esc_html(date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime(majesticsupport::$_data[0]->lastreply))); ?>
                            </div>
                        </div>
                        <?php
                        if (isset($field_array['department'])) { ?>
                            <div class="mjtc-sprt-det-info-data">
                                <div class="mjtc-sprt-det-info-tit">
                                    <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['department'])); ?><?php echo esc_html(__(': ', 'majestic-support'));?>
                                </div>
                                <div class="mjtc-sprt-det-info-val">
                                    <?php echo esc_html(esc_html(majesticsupport::MJTC_getVarValue(majesticsupport::$_data[0]->departmentname))); ?>
                                </div>
                            </div>
                            <?php
                        }
                        if (in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                            $configname = 'agent';
                        } else {
                            $configname = 'user';
                        }
                        if (majesticsupport::$_config['show_closedby_on_' . $configname . '_tickets'] == 1 && majesticsupport::$_data[0]->status == 4) { ?>
                            <div class="mjtc-sprt-det-info-data">
                                <div class="mjtc-sprt-det-info-tit">
                                    <?php echo esc_html(__('Closed By', 'majestic-support')). ' : '; ?>
                                </div>
                                <div class="mjtc-sprt-det-info-val">
                                    <?php echo esc_html(MJTC_includer::MJTC_getModel('ticket')->getClosedBy(majesticsupport::$_data[0]->closedby)); ?>
                                </div>
                            </div>
                            <div class="mjtc-sprt-det-info-data">
                                <div class="mjtc-sprt-det-info-tit">
                                    <?php echo esc_html(__('Closed On', 'majestic-support')). ' : '; ?>
                                </div>
                                <div class="mjtc-sprt-det-info-val">
                                    <?php echo esc_html(date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime(majesticsupport::$_data[0]->closed))); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="mjtc-sprt-det-info-data">
                            <div class="mjtc-sprt-det-info-tit">
                                <?php echo esc_html(__('Ticket ID', 'majestic-support')); ?><?php echo esc_html(__(': ', 'majestic-support'));?>
                            </div>
                            <div class="mjtc-sprt-det-info-val">
                                <?php echo esc_html(majesticsupport::$_data[0]->ticketid); ?>
                                <a title="<?php echo esc_attr(__('Copy','majestic-support')); ?>" class="mjtc-sprt-det-copy-id"
                                    id="ticketidcopybtn"
                                    success="<?php echo esc_html(__('Copied','majestic-support')); ?>"><?php echo esc_html(__('Copy','majestic-support')); ?></a>
                            </div>
                        </div>
                        <?php  if(in_array('helptopic', majesticsupport::$_active_addons) && isset($field_array['helptopic'])){ ?>
                        <div class="mjtc-sprt-det-info-data">
                            <div class="mjtc-sprt-det-info-tit">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['helptopic'])); ?><?php echo esc_html(__(': ', 'majestic-support'));?>
                            </div>
                            <div class="mjtc-sprt-det-info-val">
                                <?php echo esc_html(majesticsupport::$_data[0]->helptopic); ?>
                            </div>
                        </div>
                        <?php }
                        if (isset($field_array['status'])) {
                        ?>
                            <div class="mjtc-sprt-det-info-data">
                                <div class="mjtc-sprt-det-info-tit">
                                    <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['status'])); ?><?php echo esc_html(__(': ', 'majestic-support'));?>
                                </div>
                                <div class="mjtc-sprt-det-info-val">
                                    <?php
                                        if (majesticsupport::$_data[0]->status == 4 || majesticsupport::$_data[0]->status == 5 )
                                            $ticketmessage = esc_html(__('Closed', 'majestic-support'));
                                        elseif (majesticsupport::$_data[0]->status == 2)
                                            $ticketmessage = esc_html(__('In Progress', 'majestic-support'));
                                        else
                                            $ticketmessage = esc_html(__('Open', 'majestic-support'));
                                        $printstatus = 1;
                                        if (majesticsupport::$_data[0]->lock == 1) {
                                            $ticketStatus = '<div class="mjtc-support-status-note">' . esc_html(__('Lock', 'majestic-support')).' '. esc_html(__(',', 'majestic-support')) . '</div>';
                                            echo wp_kses($ticketStatus, MJTC_ALLOWED_TAGS);
                                            $printstatus = 0;
                                        }
                                        if (majesticsupport::$_data[0]->isoverdue == 1) {
                                            $ticketStatus = '<div class="mjtc-support-status-note">' . esc_html(__('Overdue', 'majestic-support')) . '</div>';
                                            echo wp_kses($ticketStatus, MJTC_ALLOWED_TAGS);
                                            $printstatus = 0;
                                        }
                                        if ($printstatus == 1) {
                                            echo esc_html($ticketmessage);
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                        } ?>
                    </div>
                </div>
                <?php
                if(isset($field_array['priority'])){ ?>
                    <div class="mjtc-sprt-det-cnt mjtc-sprt-det-tkt-prty">
                        <!-- Ticket Priority -->
                        <div class="mjtc-sprt-det-hdg">
                            <div class="mjtc-sprt-det-hdg-txt">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['priority'])); ?>
                            </div>
                            <?php
                            if (in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_data['user_staff'] && majesticsupport::$_data[0]->status != 5) {
                                if(MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Change Ticket Priority')){ ?>
                                    <a class="mjtc-sprt-det-hdg-btn" href="#" id="changepriority">
                                        <?php echo esc_html(__('Change', 'majestic-support')); ?>
                                    </a>
                                    <div id="userpopupforchangepriority" style="display:none;">
                                        <div class="mjtc-support-priorty-header">
                                            <?php echo esc_html(__('Change', 'majestic-support')) . " " . majesticsupport::MJTC_getVarValue($field_array['priority']); ?>
                                            <span class="close-history"></span>
                                        </div>
                                        <div class="mjtc-support-priorty-fields-wrp">
                                            <div class="mjtc-support-select-priorty">
                                                <?php echo wp_kses(MJTC_formfield::MJTC_select('prioritytemp', MJTC_includer::MJTC_getModel('priority')->getPriorityForCombobox(), majesticsupport::$_data[0]->priorityid, esc_html(__('Change', 'majestic-support') . " " . majesticsupport::MJTC_getVarValue($field_array['priority'])), array()), MJTC_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="mjtc-support-priorty-btn-wrp">
                                            <?php echo wp_kses(MJTC_formfield::MJTC_button('changepriority', esc_html(__('Change', 'majestic-support') . " " . majesticsupport::MJTC_getVarValue($field_array['priority'])), array('class' => 'mjtc-support-priorty-save', 'onclick' => 'actionticket(1);')), MJTC_ALLOWED_TAGS); ?>
                                            <?php echo wp_kses(MJTC_formfield::MJTC_button('cancelee', esc_html(__('Cancel', 'majestic-support')), array('class' => 'mjtc-support-priorty-cancel','onclick'=>'closePopup();')), MJTC_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                    <?php
								}
                            } ?>
                        </div>
                        <?php
                        if(isset($field_array['priority'])){ ?>
                            <div class="mjtc-sprt-det-tkt-prty-txt"
                            style="background:<?php echo esc_attr(majesticsupport::$_data[0]->prioritycolour);?>; color:#ffffff;">
                                <?php echo esc_html(esc_html(majesticsupport::MJTC_getVarValue(majesticsupport::$_data[0]->priority))); ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                }
                $agentflag = in_array('agent', majesticsupport::$_active_addons) && $printflag == false && majesticsupport::$_data[0]->status != 4 && majesticsupport::$_data[0]->status != 5;
                $departmentflag = in_array('actions', majesticsupport::$_active_addons) && $printflag == false && majesticsupport::$_data[0]->status != 4 && majesticsupport::$_data[0]->status != 5 && isset($field_array['department']);
                if($agentflag || $departmentflag){ ?>
                    <div class="mjtc-sprt-det-cnt mjtc-sprt-det-tkt-assign">
                        <!-- Ticket Assign -->
                        <?php
                        if($agentflag){ ?>
                            <div class="mjtc-sprt-det-hdg">
                                <div class="mjtc-sprt-det-hdg-txt">
                                    <?php echo esc_html(__('Assigned To Agent','majestic-support')); ?>
                                </div>
                            </div>
                            <?php
                        } ?>
                        <div class="mjtc-sprt-det-tkt-asgn-cnt">
                            <?php
                            if($agentflag){ ?>
                                <div class="mjtc-sprt-det-hdg">
                                    <div class="mjtc-sprt-det-hdg-txt">
                                        <?php
                                        if(majesticsupport::$_data[0]->staffid > 0){
                                            echo esc_html(__('Ticket assigned to','majestic-support'));
                                        }else{
                                            echo esc_html(__('Not assigned to agent','majestic-support'));
                                        } ?>
                                    </div>
                                    <?php
                                    if(majesticsupport::$_data['user_staff']){ 
										if(MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Assign Ticket To Agent')){ ?>
                                            <a class="mjtc-sprt-det-hdg-btn" href="#" id="agenttransfer">
                                                <?php echo esc_html(__('Change', 'majestic-support')); ?>
                                            </a>
                                            <?php
                                        }
                                    } ?>
                                </div>
                                <?php
                            } ?>
                            <div class="mjtc-sprt-det-info-wrp">
                                <?php if($agentflag && majesticsupport::$_data[0]->staffid > 0){ ?>
                                <div class="mjtc-sprt-det-user">
                                    <div class="mjtc-sprt-det-user-image">
                                        <?php
                                        if(majesticsupport::$_data[0]->staffphoto && majesticsupport::$_config['anonymous_name_on_ticket_reply'] == 2){
                                            ?>
                                            <img alt="<?php echo esc_html(__('staff photo','majestic-support')); ?>"
                                            src="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent','task'=>'getStaffPhoto','action'=>'mstask','majesticsupportid'=>majesticsupport::$_data[0]->staffid, 'mspageid'=>majesticsupport::getPageid()))); ?>">
                                            <?php
                                        } else { ?>
                                            <img alt="<?php echo esc_html(__('staff photo','majestic-support')); ?>"
                                            src="<?php echo esc_url(MJTC_PLUGIN_URL) . '/includes/images/user.png'; ?>" />
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="mjtc-sprt-det-user-cnt">
                                        <div class="mjtc-sprt-det-user-data"><?php 
            								if (majesticsupport::$_config['anonymous_name_on_ticket_reply'] == 1) {
            									echo esc_html(majesticsupport::$_config['title']);
            								}else{
            									echo esc_html(majesticsupport::$_data[0]->staffname); 
            								} ?>
                                        </div>
                                        <div class="mjtc-sprt-det-user-data agent-email">
                                            <?php 
            								if (majesticsupport::$_config['show_email_on_ticket_reply'] == 1) {
            									echo esc_html(majesticsupport::$_data[0]->staffemail); 
            								} ?>
                                        </div>
                                        <div class="mjtc-sprt-det-user-data">
                                            <?php 
            								if (majesticsupport::$_config['show_email_on_ticket_reply'] == 2) {
            									echo esc_html(majesticsupport::$_data[0]->staffphone);
            								} ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php
                                if($departmentflag){ ?>
                                    <div class="mjtc-sprt-det-trsfer-dep">
                                        <div class="mjtc-sprt-det-trsfer-dep-txt">
                                            <span
                                                class="mjtc-sprt-det-trsfer-dep-txt-tit"><?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['department'])).': '; ?>
                                            </span>
                                            <?php echo esc_html(esc_html(majesticsupport::MJTC_getVarValue(majesticsupport::$_data[0]->departmentname))); ?>
                                        </div>
                                        <?php
                                        if(majesticsupport::$_data['user_staff']){ 
    										if(MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Ticket Department Transfer')){ ?>
                                                <a title="<?php echo esc_attr(__('Change','majestic-support')); ?>" href="#" class="mjtc-sprt-det-hdg-btn" id="departmenttransfer">
                                                    <?php echo esc_html(__('Change','majestic-support')); ?>
                                                </a>
                                                <?php
                                            }
                                        } ?>
                                    </div>
                                    <?php
                                } ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                if(in_array('timetracking', majesticsupport::$_active_addons) && isset(majesticsupport::$_data['time_taken'])){ ?>
                    <div class="mjtc-sprt-det-cnt mjtc-sprt-det-time-tracker">
                        <!-- Time Tracker -->
                        <div class="mjtc-sprt-det-hdg">
                            <div class="mjtc-sprt-det-hdg-txt">
                                <?php echo esc_html(__('Total Time Taken','majestic-support')); ?>
                            </div>
                        </div>
                        <div class="mjtc-sprt-det-timer-wrp">
                            <!-- Timer Wrapper -->
                            <div class="timer-total-time">
                                <?php
                                $hours = floor(majesticsupport::$_data['time_taken'] / 3600);
                                $mins = floor(majesticsupport::$_data['time_taken'] / 60);
                                $mins = floor($mins % 60);
                                $secs = floor(majesticsupport::$_data['time_taken'] % 60);
                                $time =  sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                                ?>
                                <div class="timer-total-time-value">
                                    <span class="timer-box">
                                        <?php echo esc_html(sprintf('%02d', $hours)); ?>
                                    </span>
                                    <span class="timer-box">
                                        <?php echo esc_html(sprintf('%02d', $mins)); ?>
                                    </span>
                                    <span class="timer-box">
                                        <?php echo esc_html(sprintf('%02d', $secs)); ?>
                                    </span>
                                </div>
                            </div>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('timer_time_in_seconds',''), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('timer_edit_desc',''), MJTC_ALLOWED_TAGS); ?>
                        </div>
                    </div>
                    <?php
                } ?>
                <!-- User Tickets -->
                <?php
                if(isset(majesticsupport::$_data['usertickets']) && !empty(majesticsupport::$_data['usertickets'])){ ?>
                    <div class="mjtc-sprt-det-cnt mjtc-sprt-det-user-tkts">
                        <div class="mjtc-sprt-det-hdg">
                            <div class="mjtc-sprt-det-hdg-txt">
                                <?php echo esc_html(majesticsupport::$_data[0]->name).' '.esc_html(__('Tickets','majestic-support')); ?>
                            </div>
                        </div>
                        <div class="mjtc-sprt-det-usr-tkt-list">
                            <?php
                            foreach(majesticsupport::$_data['usertickets'] as $userticket){ ?>
                                <div class="mjtc-sprt-det-user">
                                    <div class="mjtc-sprt-det-user-image">
                                        <?php echo wp_kses(ms_get_avatar(MJTC_includer::MJTC_getModel('majesticsupport')->getWPUidById(majesticsupport::$_data[0]->uid)), MJTC_ALLOWED_TAGS); ?>
                                    </div>
                                    <div class="mjtc-sprt-det-user-cnt">
                                        <div class="mjtc-sprt-det-user-data name">
                                            <span class="mjtc-sprt-det-user-val">
                                                <a class="mjtc-sprt-det-ticket-title" title="<?php echo esc_attr(__('view ticket','majestic-support')); ?>" href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'ticketdetail','majesticsupportid'=> $userticket->id))); ?>">
                                                    <?php echo esc_html($userticket->subject); ?>
                                                </a>
                                            </span>
                                        </div>
                                        <?php
                                        if(isset($field_array['department'])){ ?>
                                            <div class="mjtc-sprt-det-user-data">
                                                <span
                                                    class="mjtc-sprt-det-user-tit"><?php echo esc_html(__("Department",'majestic-support')). ': '; ?></span>
                                                <span
                                                    class="mjtc-sprt-det-user-val"><?php echo esc_html(majesticsupport::MJTC_getVarValue($userticket->departmentname)); ?></span>
                                            </div>
                                            <?php
                                        } ?>
                                        <div class="mjtc-sprt-det-user-data">
                                            <?php
                                            if(isset($field_array['priority'])){ ?>
                                                <span class="mjtc-sprt-det-prty"
                                                    style="background:<?php echo esc_html($userticket->prioritycolour);?>;">
                                                    <?php echo esc_html(majesticsupport::MJTC_getVarValue($userticket->priority)); ?>
                                                </span>
                                                <?php
                                            }?>
                                        <span class="mjtc-sprt-det-status">
                                            <?php
                                                if ($userticket->status == 4 || $userticket->status == 5 )
                                                    $userticketmessage = esc_html(__('Closed', 'majestic-support'));
                                                elseif ($userticket->status == 2)
                                                    $userticketmessage = esc_html(__('In Progress', 'majestic-support'));
                                                else
                                                    $userticketmessage = esc_html(__('Open', 'majestic-support'));
                                                $userticketprintstatus = 1;
                                                if ($userticket->lock == 1) {
                                                    echo wp_kses('<span class="mjtc-support-status-note">' . esc_html(__('Lock', 'majestic-support')).' '. esc_html(__(',', 'majestic-support')) . '</span>', MJTC_ALLOWED_TAGS);
                                                    $userticketprintstatus = 0;
                                                }
                                                if ($userticket->isoverdue == 1) {
                                                    echo wp_kses('<span class="mjtc-support-status-note">' . esc_html(__('Overdue', 'majestic-support')) . '</span>', MJTC_ALLOWED_TAGS);
                                                    $userticketprintstatus = 0;
                                                }
                                                if ($userticketprintstatus == 1) {
                                                    echo esc_html($userticketmessage);
                                                }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                } ?>
                <!-- Woocomerece -->
                <?php
                apply_filters( 'mjtc_support_ticket_admin_details_right_middle', majesticsupport::$_data[0]->id );
                if( class_exists('WooCommerce') && in_array('woocommerce', majesticsupport::$_active_addons)){
                    $order = wc_get_order(majesticsupport::$_data[0]->wcorderid);
                    $order_itemid = majesticsupport::$_data[0]->wcproductid;
                    if($order){ ?>
                        <div class="mjtc-sprt-det-cnt mjtc-sprt-det-woocom">
                            <div class="mjtc-sprt-det-hdg">
                                <div class="mjtc-sprt-det-hdg-txt">
                                    <?php echo esc_html(__("Woocommerce Order",'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="mjtc-sprt-wc-order-box">
                                <div class="mjtc-sprt-wc-order-item">
                                    <div class="mjtc-sprt-wc-order-item-title">
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['wcorderid'])); ?>:</div>
                                    <div class="mjtc-sprt-wc-order-item-value">#<?php echo esc_html($order->get_id()); ?></div>
                                </div>
                                <div class="mjtc-sprt-wc-order-item">
                                    <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("Status",'majestic-support')); ?>:</div>
                                    <div class="mjtc-sprt-wc-order-item-value">
                                        <?php echo wp_kses(wc_get_order_status_name($order->get_status()), MJTC_ALLOWED_TAGS); ?></div>
                                </div>
                                <?php
                                if($order_itemid){
                                    $item = new WC_Order_Item_Product($order_itemid);
                                    if($item){ ?>
                                        <div class="mjtc-sprt-wc-order-item">
                                            <div class="mjtc-sprt-wc-order-item-title">
                                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['wcproductid'])); ?>:</div>
                                            <div class="mjtc-sprt-wc-order-item-value"><?php echo esc_html($item->get_name()); ?></div>
                                        </div>
                                        <?php
                                    }
                                } ?>
                                <div class="mjtc-sprt-wc-order-item">
                                    <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("Created",'majestic-support')); ?>:</div>
                                    <div class="mjtc-sprt-wc-order-item-value">
                                        <?php echo esc_html($order->get_date_created()->date_i18n(wc_date_format())); ?></div>
                                </div>
                                <?php
                                if(in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()){
                                    do_action('ms_woocommerce_order_detail_agent', $order, $order_itemid);
                                }
                                if(majesticsupport::$_data[0]->uid == MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid()){ ?>
                                    <a href="<?php echo esc_url(wc_get_endpoint_url('orders','',wc_get_page_permalink('myaccount'))); ?>"
                                        class="mjtc-sprt-wc-order-item-link">
                                        <?php echo esc_html(__("View all orders",'majestic-support')); ?>
                                    </a>
                                    <?php
                                } ?>
                            </div>
                        </div>
                        <?php
                    }
                } ?>
                <!-- Easy Digital Downloads -->
                <?php
                if( class_exists('Easy_Digital_Downloads') && in_array('easydigitaldownloads', majesticsupport::$_active_addons)){
                    $orderid = majesticsupport::$_data[0]->eddorderid;
                    $order_product = majesticsupport::$_data[0]->eddproductid;
                    $order_license = majesticsupport::$_data[0]->eddlicensekey;
                    if($orderid != ''){ ?>
                        <div class="mjtc-sprt-det-cnt mjtc-sprt-det-edd">
                            <div class="mjtc-sprt-det-hdg">
                                <div class="mjtc-sprt-det-hdg-txt">
                                    <?php echo esc_html(__("Easy Digital Downloads",'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="mjtc-sprt-wc-order-box">
                                <div class="mjtc-sprt-wc-order-item">
                                    <div class="mjtc-sprt-wc-order-item-title">
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['eddorderid'])); ?>:</div>
                                    <div class="mjtc-sprt-wc-order-item-value">#<?php echo esc_html($orderid); ?></div>
                                </div>
                                <div class="mjtc-sprt-wc-order-item">
                                    <div class="mjtc-sprt-wc-order-item-title">
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['eddproductid'])); ?>:</div>
                                    <div class="mjtc-sprt-wc-order-item-value">
                                        <?php
                                            if(is_numeric($order_product)){
                                                $download = new EDD_Download($order_product);
                                                echo wp_kses($download->post_title, MJTC_ALLOWED_TAGS);
                                            }else{
                                                echo esc_html('-----------');
                                            }
                                        ?>
                                    </div>
                                </div>
                                <?php
                                if(class_exists('EDD_Software_Licensing')){ ?>
                                    <div class="mjtc-sprt-wc-order-item">
                                        <div class="mjtc-sprt-wc-order-item-title">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['eddlicensekey'])); ?>:</div>
                                        <div class="mjtc-sprt-wc-order-item-value"><?php
                                            if($order_license != ''){
                                                $license = EDD_Software_Licensing::instance();
                                                $licenseid = $license->get_license_by_key($order_license);
                                                $result = $license->get_license_status($licenseid);
                                                if($result == 'expired'){
                                                    $result_color = 'red';
                                                }elseif($result == 'inactive'){
                                                    $result_color = 'orange';
                                                }else{
                                                    $result_color = 'green';
                                                }
                                                echo wp_kses($order_license.'&nbsp;&nbsp;(<span style="color:'.esc_attr($result_color).';font-weight:bold;text-transform:uppercase;padding:0 3px;">'.wp_kses($result, MJTC_ALLOWED_TAGS).'</span>)', MJTC_ALLOWED_TAGS);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }?>
                            </div>
                        </div>
                        <?php
                    }
                } ?>
                <!-- Envato Validation -->
                <?php
                if(in_array('envatovalidation', majesticsupport::$_active_addons) && !empty(majesticsupport::$_data[0]->envatodata)){
                    $envlicense = majesticsupport::$_data[0]->envatodata;
                    if(!empty($envlicense)){ ?>
                        <div class="mjtc-sprt-det-cnt mjtc-sprt-det-env">
                            <div class="mjtc-sprt-det-hdg">
                                <div class="mjtc-sprt-det-hdg-txt">
                                    <?php echo esc_html(__("Envato License",'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="mjtc-sprt-wc-order-box">
                                <?php if(!empty($envlicense['itemname']) && !empty($envlicense['itemid'])){ ?>
                                <div class="mjtc-sprt-wc-order-item">
                                    <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("Item",'majestic-support')); ?>:</div>
                                    <div class="mjtc-sprt-wc-order-item-value">
                                        <?php echo esc_html($envlicense['itemname']).' (#'.esc_html($envlicense['itemid']).')'; ?></div>
                                </div>
                                <?php } ?>
                                <?php if(!empty($envlicense['buyer'])){ ?>
                                <div class="mjtc-sprt-wc-order-item">
                                    <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("Buyer",'majestic-support')); ?>:</div>
                                    <div class="mjtc-sprt-wc-order-item-value"><?php echo esc_html($envlicense['buyer']); ?></div>
                                </div>
                                <?php } ?>
                                <?php if(!empty($envlicense['licensetype'])){ ?>
                                <div class="mjtc-sprt-wc-order-item">
                                    <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("License Type",'majestic-support')); ?>:
                                    </div>
                                    <div class="mjtc-sprt-wc-order-item-value"><?php echo esc_html($envlicense['licensetype']); ?></div>
                                </div>
                                <?php } ?>
                                <?php if(!empty($envlicense['license'])){ ?>
                                <div class="mjtc-sprt-wc-order-item">
                                    <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("License",'majestic-support')); ?>:</div>
                                    <div class="mjtc-sprt-wc-order-item-value"><?php echo esc_html($envlicense['license']); ?></div>
                                </div>
                                <?php } ?>
                                <?php if(!empty($envlicense['purchasedate'])){ ?>
                                <div class="mjtc-sprt-wc-order-item">
                                    <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("Purchase Date",'majestic-support')); ?>:
                                    </div>
                                    <div class="mjtc-sprt-wc-order-item-value">
                                        <?php echo esc_html(date_i18n("F d, Y, h:i:s", MJTC_majesticsupportphplib::MJTC_strtotime($envlicense['purchasedate']))); ?>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if(!empty($envlicense['supporteduntil'])){ ?>
                                <div class="mjtc-sprt-wc-order-item">
                                    <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("Supported Until",'majestic-support')); ?>:
                                    </div>
                                    <div class="mjtc-sprt-wc-order-item-value">
                                        <?php echo esc_html(date_i18n("F d, Y", MJTC_majesticsupportphplib::MJTC_strtotime($envlicense['supporteduntil']))); ?></div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                } ?>
                <!-- Paid Support -->
                <?php
                if(in_array('paidsupport', majesticsupport::$_active_addons) && class_exists('WooCommerce')){
                    $linktickettoorder = true;
                    if(majesticsupport::$_data[0]->paidsupportitemid > 0){
                        $paidsupport = MJTC_includer::MJTC_getModel('paidsupport')->getPaidSupportDetails(majesticsupport::$_data[0]->paidsupportitemid);
                        if($paidsupport){
                            $linktickettoorder = false;
                            $nonpreminumsupport = in_array(majesticsupport::$_data[0]->id,$paidsupport['ignoreticketids']) ? 1 : 0;
                            $agentallowed = in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff() && MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Mark Non Premium');
                            ?>
                            <div class="mjtc-sprt-det-cnt mjtc-sprt-det-pdsprt">
                                <?php if(!$nonpreminumsupport || $agentallowed){ ?>
                                <div class="mjtc-sprt-det-hdg">
                                    <div class="mjtc-sprt-det-hdg-txt">
                                        <?php echo esc_html(__("Paid Support Details",'majestic-support')); ?>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if(!$nonpreminumsupport){ ?>
                                <div class="mjtc-sprt-wc-order-box">
                                    <div class="mjtc-sprt-wc-order-item">
                                        <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("Order",'majestic-support')); ?>:</div>
                                        <div class="mjtc-sprt-wc-order-item-value">#<?php echo esc_html($paidsupport['orderid']); ?></div>
                                    </div>
                                    <div class="mjtc-sprt-wc-order-item">
                                        <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("Product Name",'majestic-support')); ?>:
                                        </div>
                                        <div class="mjtc-sprt-wc-order-item-value"><?php echo esc_html($paidsupport['itemname']); ?></div>
                                    </div>
                                    <div class="mjtc-sprt-wc-order-item">
                                        <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("Total Tickets",'majestic-support')); ?>:
                                        </div>
                                        <div class="mjtc-sprt-wc-order-item-value">
                                            <?php
                                            if($paidsupport['totalticket']==-1){
                                                echo esc_html(__("Unlimited",'majestic-support'));
                                            } else {
                                                echo esc_html($paidsupport['totalticket']);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="mjtc-sprt-wc-order-item">
                                        <div class="mjtc-sprt-wc-order-item-title">
                                            <?php echo esc_html(__("Remaining Tickets",'majestic-support')); ?>:
                                        </div>
                                        <div class="mjtc-sprt-wc-order-item-value">
                                            <?php
                                            if($paidsupport['totalticket']==-1){
                                                echo esc_html(__("Unlimited",'majestic-support'));
                                            } else {
                                                echo esc_html($paidsupport['remainingticket']);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php if(isset($paidsupport['subscriptionid'])){ ?>
                                    <div class="mjtc-sprt-wc-order-item">
                                        <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("Subscription",'majestic-support')); ?>:
                                        </div>
                                        <div class="mjtc-sprt-wc-order-item-value">#<?php echo esc_html($paidsupport['subscriptionid']); ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if(isset($paidsupport['subscriptionstartdate'])){ ?>
                                    <div class="mjtc-sprt-wc-order-item">
                                        <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("Subscribed On",'majestic-support')); ?>:
                                        </div>
                                        <div class="mjtc-sprt-wc-order-item-value">
                                            <?php echo esc_html(date_i18n("F d, Y, h:i:s", MJTC_majesticsupportphplib::MJTC_strtotime($paidsupport['subscriptionstartdate']))); ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if(isset($paidsupport['expiry'])){ ?>
                                    <div class="mjtc-sprt-wc-order-item">
                                        <div class="mjtc-sprt-wc-order-item-title"><?php echo esc_html(__("Support Expiry",'majestic-support')); ?>:
                                        </div>
                                        <div class="mjtc-sprt-wc-order-item-value">
                                            <?php
                                            if($paidsupport['expiry']){
                                                 echo esc_html(date_i18n("F d, Y", MJTC_majesticsupportphplib::MJTC_strtotime($paidsupport['expiry'])));
                                            } else {
                                            echo  esc_html(__("No expiration",'majestic-support'));
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php } ?>

                                <?php
                                // non-premium section
                                // show only if agent and has permission to mark ticket as non-premium
                                if($agentallowed){
                                    ?>
                                <div class="mjtc-sprt-wc-order-box">
                                    <div class="mjtc-sprt-wc-order-item">
                                        <label>
                                            <input type="checkbox" id="nonpreminumsupport" <?php if($nonpreminumsupport){ echo esc_attr('checked');} ?>>
                                            <b><?php echo esc_html(__("Non-preminum support",'majestic-support')); ?></b>
                                        </label>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('paidsupportitemid',majesticsupport::$_data[0]->paidsupportitemid), MJTC_ALLOWED_TAGS) ?>
                                        <div>
                                            <small><i><?php echo esc_html(__("Check this box if this ticket should NOT apply against the paid support",'majestic-support')); ?></i></small>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                    }
                    if($linktickettoorder && in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff() && MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Link To Paid Support')){
                        $paidsupportitems = MJTC_includer::MJTC_getModel('paidsupport')->getPaidSupportList(majesticsupport::$_data[0]->uid);
                        $paidsupportlist = array();
                        foreach($paidsupportitems as $row){
                            $paidsupportlist[] = (object) array(
                                'id' => $row->itemid,
                                'text' => esc_html(__("Order",'majestic-support')).' #'.esc_html($row->orderid).', '.esc_html($row->itemname).', '.esc_html(__("Remaining",'majestic-support')).':'.esc_html($row->remaining).' '.esc_html(__("Out of",'majestic-support')).':'.esc_html($row->total),
                            );
                        }
                        ?>
                        <div class="mjtc-sprt-det-cnt mjtc-sprt-det-pdsprt">
                            <div class="mjtc-sprt-det-hdg">
                                <div class="mjtc-sprt-det-hdg-txt">
                                    <?php echo esc_html(__("Paid Support",'majestic-support')).': '.esc_html(__("Link ticket to paid support",'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="mjtc-sprt-wc-order-box">
                                <div class="mjtc-sprt-wc-order-item">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_select('paidsupportitemid',$paidsupportlist,null,esc_html(__("Select",'majestic-support'))), MJTC_ALLOWED_TAGS); ?>
                                    <button type="button" class="btn"
                                        id="paidsupportlinkticketbtn"><?php echo esc_html(__("Link",'majestic-support')); ?></button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } ?>
                <?php apply_filters('mjtc_support_ticket_admin_details_right_last', majesticsupport::$_data[0]->id); ?>
            </div>
        </div>
        <?php
    } else { // Record Not FOund
        MJTC_layout::MJTC_getNoRecordFound();
    }
} else {// User is permission
    MJTC_layout::MJTC_getPermissionNotGranted();
}
    } else {// User is guest
        $redirect_url = majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'ticketdetail'));
        $redirect_url = MJTC_majesticsupportphplib::MJTC_safe_encoding($redirect_url);
        MJTC_layout::MJTC_getUserGuest($redirect_url);
    }
} else { // System is offline
    MJTC_layout::MJTC_getSystemOffline();
}
?>
</div>
</div>
</div>
