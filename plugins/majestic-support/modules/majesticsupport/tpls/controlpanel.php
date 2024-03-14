<?php
if(!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('majesticsupport-notify-app', MJTC_PLUGIN_URL . 'includes/js/firebase-app.js');
wp_enqueue_script('majesticsupport-notify-message', MJTC_PLUGIN_URL . 'includes/js/firebase-messaging.js');
wp_enqueue_script('majesticsupport-google-charts', MJTC_PLUGIN_URL . 'includes/js/google-charts.js');
do_action('ticket-notify-generate-token');
wp_enqueue_style('majesticsupport-status-graph', MJTC_PLUGIN_URL . 'includes/css/status_graph.css');

if(isset(majesticsupport::$_data['stack_chart_horizontal'])){
    $majesticsupport_js ="
    google.load('visualization', '1', {
        packages: ['corechart']
    });
    google.setOnLoadCallback(drawStackChartHorizontal);

    function drawStackChartHorizontal() {
        var data = google.visualization.arrayToDataTable([
            ".
                wp_kses(majesticsupport::$_data['stack_chart_horizontal']['title'], MJTC_ALLOWED_TAGS).",".
                wp_kses(majesticsupport::$_data['stack_chart_horizontal']['data'], MJTC_ALLOWED_TAGS)."
        ]);

        var view = new google.visualization.DataView(data);

        var options = {
            height: 571,
            chartArea: {
                width: '80%'
            },
            legend: {
                position: 'top',
            },
            curveType: 'function',
            colors: ['#B82B2B', '#621166', '#2168A2', '#159667'],
        };
        var chart = new google.visualization.AreaChart(document.getElementById('stack_chart_horizontal'));
        chart.draw(view, options);
    }";
    //custom handle use because of this add script after chart library include
    wp_register_script( 'majesticsupport-inlinescript-handle', '' );
    wp_enqueue_script( 'majesticsupport-inlinescript-handle' );

    wp_add_inline_script('majesticsupport-inlinescript-handle',$majesticsupport_js);
}
$majesticsupport_js ="
    jQuery(document).ready(function($) {
        $('#msadmin-menu-toggle').click(function(){
        $('.mjtc-cp-left').slideToggle(500);
      });
        jQuery('div#mjtc-support-main-black-background,span#mjtc-support-popup-close-button').click(function() {
            jQuery('div#mjtc-support-main-popup').slideUp();
            setTimeout(function() {
                jQuery('div#mjtc-support-main-black-background').hide();
            }, 600);

        });

        jQuery('.mjtc-support-ticket-stats a.mjtc-support-link').click(function(e) {
            e.preventDefault();
            var list = jQuery(this).attr('data-tab-number');
            var oldUrl = jQuery(this).attr('href'); // Get current url
            var opt = '?';
            var found = oldUrl.search('&');
            if (found > 0) {
                opt = '&';
            }
            var found = oldUrl.search('[\?\]');
            if (found > 0) {
                opt = '&';
            }
            var newUrl = oldUrl + opt + 'list=' + list; // Create new url
            window.location.href = newUrl;
        });
    });

    function getDownloadById(value) {
        ajaxurl = '". esc_url(admin_url('admin-ajax.php')) ."';
        jQuery.post(ajaxurl, {action: 'mjsupport_ajax', downloadid: value, mjsmod: 'download', task: 'getDownloadById',mspageid: ". get_the_ID().", '_wpnonce':'". esc_attr(wp_create_nonce("get-download-by-id"))."'}, function (data) {
            if (data) {
                var obj = jQuery.parseJSON(data);
                jQuery('div#mjtc-support-main-content').html(MJTC_msDecodeHTML(obj.data));
                jQuery('span#mjtc-support-popup-title').html(obj.title);
                jQuery('div#mjtc-support-main-downloadallbtn').html(MJTC_msDecodeHTML(obj.downloadallbtn));
                jQuery('div#mjtc-support-main-black-background').show();
                jQuery('div#mjtc-support-main-popup').slideDown('slow');
            }
        });
    }
";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  

<div class="ms-main-up-wrapper">
    <?php

if (majesticsupport::$_config['offline'] == 2) {
    MJTC_message::MJTC_getMessage();
    include_once(MJTC_PLUGIN_PATH . 'includes/header.php');
    $agent_flag = 0;
    if(in_array('agent',majesticsupport::$_active_addons)){
        if (MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $agent_flag = 1;
        }
    }

    $data = isset(majesticsupport::$_data[0]) ? majesticsupport::$_data[0] : array();
    ?>
    <div class="mjtc-cp-wrapper">
        <img class="mjtc-transparent-header-img" alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
            src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tp-image.png" />
        <div class="mjtc-cp-main-wrp">
            <div class="mjtc-cp-left">
                <!-- cp links for user -->
                <?php
                if ($agent_flag == 0) { ?>
                    <div id="mjtc-dash-menu-link-wrp">
                        <!-- Dashboard Links -->
                        <div class="mjtc-menu-links-wrp">
                            <?php
                            $count = 0;
                            if (majesticsupport::$_config['cplink_openticket_user'] == 1):
                                $ajaxid = "";
                                $count++;
						        if(in_array('multiform',majesticsupport::$_active_addons) && majesticsupport::$_config['show_multiform_popup'] == 1){
									//show popup in case of multiform
									$ajaxid = "id=multiformpopup";
								}
								// controller add default form id, if single form
								$menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod' => 'ticket', 'mjslay' => 'addticket')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/add-ticket.png';
                                $menu_title =  esc_html(__('Submit Ticket', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path, $ajaxid);
                            endif;
                            if (majesticsupport::$_config['cplink_myticket_user'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'myticket')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/tickets.png';
                                $menu_title =  esc_html(__('My Tickets', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            if (majesticsupport::$_config['cplink_checkticketstatus_user'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketstatus')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/ticket-status.png';
                                $menu_title =  esc_html(__('Ticket Status', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            if (in_array('announcement', majesticsupport::$_active_addons) && majesticsupport::$_config['cplink_announcements_user'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'announcement', 'mjslay'=>'announcements')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/announcements.png';
                                $menu_title =  esc_html(__('Announcements', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            if (in_array('download', majesticsupport::$_active_addons) && majesticsupport::$_config['cplink_downloads_user'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'download', 'mjslay'=>'downloads')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/download.png';
                                $menu_title =  esc_html(__('Downloads', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            if (in_array('faq', majesticsupport::$_active_addons) &&  majesticsupport::$_config['cplink_faqs_user'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'faq', 'mjslay'=>'faqs')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/faq.png';
                                $menu_title =  esc_html(__("FAQs", 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            if (in_array('knowledgebase', majesticsupport::$_active_addons) &&  majesticsupport::$_config['cplink_knowledgebase_user'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'knowledgebase', 'mjslay'=>'userknowledgebase')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/kb.png';
                                $menu_title =  esc_html(__('Knowledge Base', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            if (majesticsupport::$_config['cplink_erasedata_user'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'gdpr', 'mjslay'=>'adderasedatarequest')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/user-data.png';
                                $menu_title =  esc_html(__('User Data', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            apply_filters( 'mjtc_support_ticket_frontend_controlpanel_left_menu_custom_links_middle',$count);
                            if (majesticsupport::$_config['cplink_login_logout_user'] == 1){
                                $count++;
                                $loginval = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('set_login_link');
                                $loginlink = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('login_link');
                                    if ($loginval == 3){
                                        $hreflink = wp_login_url();
                                    }
                                    else if ($loginval == 2 && $loginlink != ""){
                                        $hreflink = $loginlink;
                                    }else{
                                        $hreflink= majesticsupport::makeUrl(array('mjsmod'=>'majesticsupport', 'mjslay'=>'login'));
                                    }
                                    if (!is_user_logged_in()):
                                        $menu_url = $hreflink;
                                        $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/login.png';
                                        $menu_title =  esc_html(__('Log In', 'majestic-support'));
                                        mjtc_printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                if (is_user_logged_in()):
                                    $menu_url = wp_logout_url( home_url() );
                                    $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/logout.png';
                                    $menu_title =  esc_html(__('Log Out', 'majestic-support'));
                                    mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                                endif;
                            }
                            if (majesticsupport::$_config['cplink_register_user'] == 1){
                                $registerval = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('set_register_link');
                                $registerlink = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('register_link');
                                if ($registerval == 3){
                                    $hreflink = wp_registration_url();
                                }else if ($registerval == 2 && $registerlink != ""){
                                    $hreflink = $registerlink;
                                }else{
                                    $hreflink= majesticsupport::makeUrl(array('mjsmod'=>'majesticsupport', 'mjslay'=>'userregister'));
                                }
                                if (!is_user_logged_in()):
                                    $count++;
                                    $is_enable = get_option('users_can_register'); /*check to make sure user registration is enabled*/
                                    if ($is_enable) {// only show the registration form if allowed
                                        $menu_url = esc_url($hreflink);
                                        $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/register.png';
                                        $menu_title =  esc_html(__('Register', 'majestic-support'));
                                        mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                                    }
                                endif;
                            } ?>
                        </div>
                    </div>
                    <?php
                } ?>
                <!-- cp links for agent -->
                <?php
                if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {  ?>
                    <div id="mjtc-dash-menu-link-wrp">
                        <div class="mjtc-menu-links-wrp">
                            <!-- Dashboard Links -->
                            <?php
                            $count = 0;
                            if (majesticsupport::$_config['cplink_openticket_staff'] == 1):
                                $ajaxid = "";
                                $count++;
				                if(in_array('multiform',majesticsupport::$_active_addons) && majesticsupport::$_config['show_multiform_popup'] == 1){
							        //show popup in case of multiform
							        $ajaxid = "id=multiformpopup";
						        }
						        // controller add default form id, if single form
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent', 'mjslay'=>'staffaddticket')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/add-ticket.png';
                                $menu_title =  esc_html(__('Submit Ticket', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path, $ajaxid);
                            endif;
                            
                            if (majesticsupport::$_config['cplink_myticket_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent', 'mjslay'=>'staffmyticket')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/tickets.png';
                                $menu_title =  esc_html(__('My Tickets', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (majesticsupport::$_config['cplink_ticketclosereasons_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticketclosereason', 'mjslay'=>'ticketclosereasons')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/close-ticket-reson.png';
                                $menu_title =  esc_html(__('Ticket Close Reasons', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (majesticsupport::$_config['cplink_smartreply_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'smartreply', 'mjslay'=>'smartreplies')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/smart-reply.png';
                                $menu_title =  esc_html(__('Smart Reply', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (majesticsupport::$_config['cplink_staff_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent', 'mjslay'=>'staffs')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/staff.png';
                                $menu_title =  esc_html(__('Agents', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (majesticsupport::$_config['cplink_roles_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'role', 'mjslay'=>'roles')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/role.png';
                                $menu_title =  esc_html(__('Roles', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (majesticsupport::$_config['cplink_department_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'department', 'mjslay'=>'departments')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/department.png';
                                $menu_title =  esc_html(__('Departments', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (in_array('knowledgebase', majesticsupport::$_active_addons) && majesticsupport::$_config['cplink_category_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'knowledgebase', 'mjslay'=>'stafflistcategories')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/category.png';
                                $menu_title =  esc_html(__('Categories', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (in_array('knowledgebase', majesticsupport::$_active_addons) && majesticsupport::$_config['cplink_kbarticle_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'knowledgebase', 'mjslay'=>'stafflistarticles')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/kb.png';
                                $menu_title =  esc_html(__('Knowledge Base', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (in_array('download', majesticsupport::$_active_addons) && majesticsupport::$_config['cplink_download_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'download', 'mjslay'=>'staffdownloads')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/download.png';
                                $menu_title =  esc_html(__('Downloads', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (in_array('announcement', majesticsupport::$_active_addons) && majesticsupport::$_config['cplink_announcement_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'announcement', 'mjslay'=>'staffannouncements')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/announcements.png';
                                $menu_title =  esc_html(__('Announcements', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (in_array('faq', majesticsupport::$_active_addons) && majesticsupport::$_config['cplink_faq_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'faq', 'mjslay'=>'stafffaqs')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/faq.png';
                                $menu_title =  esc_html(__("FAQs", 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (in_array('helptopic', majesticsupport::$_active_addons) && majesticsupport::$_config['cplink_helptopic_agent'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'helptopic', 'mjslay'=>'agenthelptopics')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/help-topic.png';
                                $menu_title =  esc_html(__("Help Topics", 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;

                            if (in_array('cannedresponses', majesticsupport::$_active_addons) && majesticsupport::$_config['cplink_cannedresponses_agent'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'cannedresponses', 'mjslay'=>'agentcannedresponses')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/canned-response.png';
                                $menu_title =  esc_html(__("Premade Responses", 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;

                            if (in_array('mail', majesticsupport::$_active_addons) && majesticsupport::$_config['cplink_mail_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'mail', 'mjslay'=>'inbox')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/mails.png';
                                $menu_title =  esc_html(__('Mail', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;

                            if (in_array('banemail', majesticsupport::$_active_addons) && majesticsupport::$_config['cplink_banemail_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'banemail', 'mjslay'=>'banemails')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/ban.png';
                                $menu_title =  esc_html(__('Banned Emails', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;

                            if (majesticsupport::$_config['cplink_staff_report_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'reports', 'mjslay'=>'staffreports')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/staff-report.png';
                                $menu_title =  esc_html(__('Agent Reports', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;

                            if (majesticsupport::$_config['cplink_department_report_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'reports', 'mjslay'=>'departmentreports')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/department-report.png';
                                $menu_title =  esc_html(__('Department Reports', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (in_array('feedback', majesticsupport::$_active_addons) && majesticsupport::$_config['cplink_feedback_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'feedback', 'mjslay'=>'feedbacks')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/feedback.png';
                                $menu_title =  esc_html(__('Agent Feedback', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (majesticsupport::$_config['cplink_myprofile_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent', 'mjslay'=>'myprofile')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/profile.png';
                                $menu_title =  esc_html(__('My Profile', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (majesticsupport::$_config['cplink_erasedata_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'gdpr', 'mjslay'=>'adderasedatarequest')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/user-data.png';
                                $menu_title =  esc_html(__('User Data', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (in_array('export', majesticsupport::$_active_addons) && majesticsupport::$_config['cplink_export_ticket_staff'] == 1):
                                $count++;
                                $menu_url = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'export', 'mjslay'=>'export')));
                                $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/export.png';
                                $menu_title =  esc_html(__('Export Ticket', 'majestic-support'));
                                mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                            endif;
                            
                            if (majesticsupport::$_config['cplink_login_logout_staff'] == 1){
                                if (!is_user_logged_in()):
                                    $count++;
                                    $menu_url = $hreflink;
                                    $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/profile.png';
                                    $menu_title =  esc_html(__('Log In', 'majestic-support'));
                                    mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                                endif;
                                if (is_user_logged_in()):
                                    $count++;
                                    $menu_url = wp_logout_url( home_url() );
                                    $image_path = MJTC_PLUGIN_URL . 'includes/images/left-icons/menu/logout.png';
                                    $menu_title =  esc_html(__('Log Out', 'majestic-support'));
                                    mjtc_printMenuLink($menu_title, $menu_url, $image_path);
                                endif;
                            } ?>
                        </div>
                    </div>
                    <?php
                }
                if ($count == 0) {
                    $majesticsupport_js ="
                        jQuery('#mjtc-dash-menu-link-wrp').addClass('mjtc-dash-menu-link-hide');
                        jQuery('.mjtc-cp-right').addClass('mjtc-cp-right-fullwidth');

                    ";
                    wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
                }
            ?>
            </div>
            <div class="mjtc-cp-right">
                <?php if(!is_user_logged_in()){ ?>
                <div class="mjtc-support-wrapper">
                    <div class="ms-admin-collapse-logo-overall-wrapper">
                        <div id="msadmin-logo">
                        <img id="msadmin-menu-toggle" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/menu-icon_new-a.png">
                        <span class="majestic-support-overall-wrapper"><?php echo esc_html(__("Dashboard Menu",'majestic-support'));?></span>

                        </div>
                    </div>        
                    <div class="mjtc-support-top-sec">
                        <div class="mjtc-support-top-sec-left">
                            <div class="mjtc-support-main-heading"><?php echo esc_html(__("Dashboard",'majestic-support')); ?>
                            </div>
                        </div>
                        <div class="mjtc-support-top-sec-right">
                            <?php
                                $id = "";
                                if(in_array('multiform',majesticsupport::$_active_addons) && majesticsupport::$_config['show_multiform_popup'] == 1){
                                    //show popup in case of multiform
                                    $id = "id=multiformpopup";
                                }
                            ?>
                            <a <?php echo esc_attr($id); ?>
                                href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'addticket'))); ?>"
                                class="mjtc-support-button"><?php echo esc_html(__("Submit Ticket",'majestic-support')); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mjtc-support-ticket-cont" style="display: flex">
                    <?php
                        $id = "";
                        if(in_array('multiform',majesticsupport::$_active_addons) && majesticsupport::$_config['show_multiform_popup'] == 1){
                            //show popup in case of multiform
                            $id = "id=multiformpopup";
                        }
                    ?>
                    <a <?php echo esc_attr($id); ?> class="mjtc-support-link"
                        href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'addticket'))); ?>">
                        <div class="box-1">
                            <div class="top-sec">
                                <div class="top-sec-left">
                                        <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/submit-ticket.png"; ?>" />
                                </div>
                                <div class="top-sec-right">
                                    <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/Ticket-tp.png"; ?>" />
                                </div>
                            </div>
                            <div class="mid-sec-left">
                                <div class="top-sec-left-txt"><?php echo esc_html(__("Submit Ticket",'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="bottom-sec">
                                <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/ticket-line.png"; ?>" />
                            </div>
                        </div>
                    </a>
                    <a class="mjtc-support-link"
                        href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'myticket')));?>">
                        <div class="box-2">
                            <div class="top-sec">
                                <div class="top-sec-left">
                                        <img
                                            src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/my-ticket0icon.png"; ?>" />
                                </div>
                                <div class="top-sec-right">
                                    <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/my-ticket-tp.png"; ?>" />
                                </div>
                            </div>
                            <div class="mid-sec-left">
                                <div class="top-sec-left-txt"><?php echo esc_html(__("My Tickets",'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="bottom-sec">
                                <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/my-ticket-line.png"; ?>" />
                            </div>
                        </div>
                    </a>
                    <a class="mjtc-support-link"
                        href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketstatus')));?>">
                        <div class="box-3 box-6">
                            <div class="top-sec">
                                <div class="top-sec-left">
                                        <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/ticket-status.png"; ?>" />
                                </div>
                                <div class="top-sec-right">
                                    <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/status-tp.png"; ?>" />
                                </div>
                            </div>
                            <div class="mid-sec-left">
                                <div class="top-sec-left-txt"><?php echo esc_html(__("Ticket Status",'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="bottom-sec">
                                <img class="graph-no-padding"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/status-line.png"; ?>" />
                            </div>
                        </div>
                    </a>
                    <?php
                    if(majesticsupport::$_config['cplink_latesttickets_user'] == 1){ ?>
                        <div class="mjtc-support-latest-ticket-wrapper">
                            <div class="mjtc-support-haeder-tickets">
                                <div class="mjtc-support-header-txt">
                                    <div class="mjtc-ticket-data-image">
                                        <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" class="mjtc-ticket-data-img"
                                            src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tickets.png" />
                                    </div>
                                    <?php echo esc_html(__("My Tickets",'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="mjtc-support-mytickklket">
                                <?php 
                                $redirect_url = majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'myticket'));
                                $redirect_url = MJTC_majesticsupportphplib::MJTC_safe_encoding($redirect_url);
                                MJTC_layout::MJTC_getUserGuest($redirect_url);
                            ?>
                            </div>
                        </div>
                        <?php
                    }
                    } ?>
                    <?php if(is_user_logged_in()){ ?>
                    <div class="mjtc-support-wrapper">
                        <div class="ms-admin-collapse-logo-overall-wrapper">
                            <div id="msadmin-logo">
                                <img id="msadmin-menu-toggle" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/menu-icon_new-a.png">
                              <span class="majestic-support-overall-wrapper"> <?php echo esc_html(__("Dashboard Menu",'majestic-support')); ?></span>
                            </div>
                        </div>
                        <div class="mjtc-support-top-sec">
                            <div class="mjtc-support-top-sec-left">
                                <div class="mjtc-support-main-heading"><?php echo esc_html(__("Dashboard",'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="mjtc-support-top-sec-right">
                                <?php
                                    $id = "";
                                    if(in_array('multiform',majesticsupport::$_active_addons) && majesticsupport::$_config['show_multiform_popup'] == 1){
                                        //show popup in case of multiform
                                        $id = "id=multiformpopup";
                                    }
                                ?>
                                <a <?php echo esc_attr($id); ?>
                                    href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'addticket'))); ?>"
                                    class="mjtc-support-button"><?php echo esc_html(__("Submit Ticket",'majestic-support')); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- count boxes -->
                    <?php
                    if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()){
                        $linkname = 'staff';
                    } else {
                        $linkname = 'user';
                    }
                    if(isset($data['count']) && majesticsupport::$_config['cplink_totalcount_'. $linkname] == 1){
                        $open_percentage = 0;
                        $close_percentage = 0;
                        $answered_percentage = 0;
                        $overdue_percentage = 0;
                        $allticket_percentage = 0;
                        if($data['count']['allticket'] > 0){ //to avoid division by zero error
                            $open_percentage = round(($data['count']['openticket'] / $data['count']['allticket']) * 100);
                            $close_percentage = round(($data['count']['closedticket'] / $data['count']['allticket']) * 100);
                            $answered_percentage = round(($data['count']['answeredticket'] / $data['count']['allticket']) * 100);
                            if(isset($data['count']['overdue'])){
                                $overdue_percentage = round(($data['count']['overdue'] / $data['count']['allticket']) * 100);
                            }
                            $allticket_percentage = 100;
                        }
                        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()){
                            $tkt_url = majesticsupport::makeUrl(array('mjsmod'=>'agent', 'mjslay'=>'staffmyticket'));
                        }else{
                            $tkt_url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'myticket'));
                        } ?>
                        <div class="mjtc-support-ticket-cont mjtc-support-ticket-stats">
                            <a class="mjtc-support-link" href="<?php echo esc_url($tkt_url); ?>" data-tab-number="1">
                                <div class="box-1">
                                    <div class="top-sec">
                                        <div class="top-sec-left">
                                            <div class="top-sec-left-heading">
                                                <?php
                                            if(majesticsupport::$_config['count_on_myticket'] == 1){
                                                $openticket = 0;
                                                if(isset($data['count']['openticket'])){
                                                    $openticket = $data['count']['openticket'];
                                                }
                                            }
                                            echo esc_html($openticket);
                                            ?>
                                            </div>
                                        </div>
                                        <div class="top-sec-right">
                                            <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/Ticket-tp.png"; ?>" />
                                        </div>
                                    </div>
                                    <div class="mid-sec-left">
                                        <div class="top-sec-left-txt"><?php echo esc_html(__("Open Tickets",'majestic-support')); ?>
                                        </div>
                                    </div>
                                    <div class="bottom-sec">
                                        <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/ticket-line.png"; ?>" />
                                    </div>
                                </div>
                            </a>
                            <a class="mjtc-support-link" href="<?php echo esc_url($tkt_url); ?>" data-tab-number="3">
                                <div class="box-2">
                                    <div class="top-sec">
                                        <div class="top-sec-left">
                                            <div class="top-sec-left-heading">
                                                <?php
                                            if(majesticsupport::$_config['count_on_myticket'] == 1){
                                                $answeredticket = 0;
                                                if(isset($data['count']['answeredticket'])){
                                                    $answeredticket = $data['count']['answeredticket'];
                                                }
                                            }
                                            echo esc_html($answeredticket);
                                            ?>
                                            </div>
                                        </div>
                                        <div class="top-sec-right">
                                            <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/my-ticket-tp.png"; ?>" />
                                        </div>
                                    </div>
                                    <div class="mid-sec-left">
                                        <div class="top-sec-left-txt">
                                            <?php echo esc_html(__("Answered Tickets",'majestic-support')); ?>
                                        </div>
                                    </div>
                                    <div class="bottom-sec">
                                        <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/my-ticket-line.png"; ?>" />
                                    </div>
                                </div>
                            </a>
                            <a class="mjtc-support-link" href="<?php echo esc_url($tkt_url); ?>" data-tab-number="2">
                                <div class="box-3">
                                    <div class="top-sec">
                                        <div class="top-sec-left">
                                            <div class="top-sec-left-heading">
                                                <?php
                                            if(majesticsupport::$_config['count_on_myticket'] == 1){
                                                $closedticket = 0;
                                                if(isset($data['count']['closedticket'])){
                                                    $closedticket = $data['count']['closedticket'];
                                                }
                                            }
                                            echo esc_html($closedticket);
                                            ?>
                                            </div>
                                        </div>
                                        <div class="top-sec-right">
                                            <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/close-ticket-tp.png"; ?>" />
                                        </div>
                                    </div>
                                    <div class="mid-sec-left">
                                        <div class="top-sec-left-txt"><?php echo esc_html(__("Closed Tickets",'majestic-support')); ?>
                                        </div>
                                    </div>
                                    <div class="bottom-sec">
                                        <img class="graph-no-padding"
                                            src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/status-line.png"; ?>" />
                                    </div>
                                </div>
                            </a>
                            <?php if(isset($data['count']['overdue'])){ ?>
                            <a class="mjtc-support-link" href="<?php echo esc_url($tkt_url); ?>" data-tab-number="5">
                                <div class="box-4">
                                    <div class="top-sec">
                                        <div class="top-sec-left">
                                            <div class="top-sec-left-heading">
                                                <?php
                                                if(majesticsupport::$_config['count_on_myticket'] == 1){
                                                    $overdue = 0;
                                                    if(isset($data['count']['overdue'])){
                                                        $overdue = $data['count']['overdue'];
                                                    }
                                                }
                                                echo esc_html($overdue);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="top-sec-right">
                                            <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/over-due-tp.png"; ?>" />
                                        </div>
                                    </div>
                                    <div class="mid-sec-left">
                                        <div class="top-sec-left-txt">
                                            <?php echo esc_html(__("Overdue Tickets",'majestic-support')); ?>
                                        </div>
                                    </div>
                                    <div class="bottom-sec">
                                        <img class="graph-no-padding"
                                            src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/over-due-line.png"; ?>" />
                                    </div>
                                </div>
                            </a>
                            <?php } ?>
                            <a class="mjtc-support-link" href="<?php echo esc_url($tkt_url); ?>" data-tab-number="4">
                                <div class="box-5">
                                    <div class="top-sec">
                                        <div class="top-sec-left">
                                            <div class="top-sec-left-heading">
                                                <?php
                                            if(majesticsupport::$_config['count_on_myticket'] == 1){
                                                $allticket = 0;
                                                if(isset($data['count']['allticket'])){
                                                    $allticket = $data['count']['allticket'];
                                                }
                                            }
                                            echo esc_html($allticket);
                                            ?>
                                            </div>
                                        </div>
                                        <div class="top-sec-right">
                                            <img src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/status-tp.png"; ?>" />
                                        </div>
                                    </div>
                                    <div class="mid-sec-left">
                                        <div class="top-sec-left-txt"><?php echo esc_html(__("All Tickets",'majestic-support')); ?>
                                        </div>
                                    </div>
                                    <div class="bottom-sec">
                                        <img class="graph-no-padding"
                                            src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/all-tickets.png"; ?>" />
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }?>
                    <!-- latest agent tickets -->
                <div class="mjtc-support-ticket-cont mjtc-support-ticket-stats">
                    <?php
                    if(isset($data['agent-tickets']) && majesticsupport::$_config['cplink_latesttickets_staff'] == 1){
                        $field_array = MJTC_includer::MJTC_getModel('fieldordering')->getFieldTitleByFieldfor(1);
                        $show_field = MJTC_includer::MJTC_getModel('fieldordering')->getFieldsForListing(1);
                        ?>
                        <div class="mjtc-support-latest-ticket-wrapper">
                            <div class="mjtc-support-haeder-tickets">
                                <div class="mjtc-support-header-txt">
                                    <div class="mjtc-ticket-data-image">
                                        <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" class="mjtc-ticket-data-img"
                                            src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tickets.png" />
                                    </div>
                                    <?php echo esc_html(__("Latest Tickets",'majestic-support')); ?>
                                </div>
                                <a class="mjtc-support-header-link"
                                    href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent','mjslay'=>'staffmyticket'))); ?>"><?php echo esc_html(__("View All Tickets",'majestic-support')); ?></a>
                            </div>
                            <div class="mjtc-support-latest-tickets-wrp">
                                <?php
                                foreach($data['agent-tickets'] as $ticket){
                                if ($ticket->status == 0) {
                                    $style = "#159667;";
                                    $status = esc_html(__('New', 'majestic-support'));
                                } elseif ($ticket->status == 1) {
                                    $style = "#D78D39;";
                                    $status = esc_html(__('Waiting Reply', 'majestic-support'));
                                } elseif ($ticket->status == 2) {
                                    $style = "#EDA900;";
                                    $status = esc_html(__('In Progress', 'majestic-support'));
                                } elseif ($ticket->status == 3) {
                                    $style = "#2168A2;";
                                    $status = esc_html(__('Replied', 'majestic-support'));
                                } elseif ($ticket->status == 4) {
                                    $style = "#3D355A;";
                                    $status = esc_html(__('Closed', 'majestic-support'));
                                } elseif ($ticket->status == 5) {
                                    $style = "#E91E63;";
                                    $status = esc_html(__('Close and merge', 'majestic-support'));
                                }
                                $ticketviamail = '';
                                if ($ticket->ticketviaemail == 1)
                                    $ticketviamail = esc_html(__('Created via Email', 'majestic-support'));
                                ?>
                                    <div class="mjtc-support-row">
                                        <div class="mjtc-col-xs-12 mjtc-col-md-12 mjtc-support-toparea">
                                            <div class="mjtc-support-first-left">
                                                <div class="mjtc-support-user-img-wrp">
                                                    <?php if (in_array('agent',majesticsupport::$_active_addons) && $ticket->staffphoto) { ?>
                                                        <img class="mjtc-support-staff-img" src="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent','task'=>'getStaffPhoto','action'=>'mstask','majesticsupportid'=> $ticket->staffid ,'mspageid'=>get_the_ID())));?> ">
                                                    <?php } else {
                                                        echo wp_kses(ms_get_avatar(MJTC_includer::MJTC_getModel('majesticsupport')->getWPUidById($ticket->uid)), MJTC_ALLOWED_TAGS);
                                                    } ?>
                                                </div>
                                                <div class="mjtc-support-ticket-subject">
                                                    <?php
                                                    if (isset($field_array['fullname'])) { ?>
                                                        <div class="mjtc-support-data-row">
                                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($ticket->name)); ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    if (isset($field_array['subject'])) { ?>
                                                        <div class="mjtc-support-data-row name">
                                                            <a class="mjtc-support-data-link"
                                                                    href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'ticketdetail','majesticsupportid'=> $ticket->id))); ?>">
                                                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($ticket->subject)); ?>
                                                            </a>
                                                        </div>
                                                        <?php
                                                    }
                                                    if (isset($field_array['department'])) { ?>
                                                        <div class="mjtc-support-data-row">
                                                            <span class="mjtc-support-title"><?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['department'])). ' : '; ?></span>
                                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($ticket->departmentname)); ?>
                                                        </div>
                                                        <?php
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="mjtc-second-support-overall-left-wrapper">
                                                <div class="mjtc-support-second-left">
                                                    <?php
                                                    if ($ticket->ticketviaemail == 1){  ?>
                                                        <span class="mjtc-support-creade-via-email-spn">
                                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($ticketviamail)); ?>
                                                        </span>
                                                    <?php } ?>
                                                    <?php
                                                    $counter = 'one';
                                                    if ($ticket->lock == 1) { ?>
                                                        <img class="ticketstatusimage <?php echo esc_attr($counter); $counter = 'two'; ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/lock.png"; ?>" title="<?php echo esc_attr(__('The ticket is locked', 'majestic-support')); ?>" />
                                                    <?php } ?>
                                                    <?php if ($ticket->isoverdue == 1) { ?>
                                                        <img class="ticketstatusimage <?php echo esc_attr($counter); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/over-due.png"; ?>" title="<?php echo esc_attr(__('This ticket is marked as overdue', 'majestic-support')); ?>" />
                                                    <?php } ?>
                                                </div>
                                                <?php
                                                if (isset($field_array['priority'])) { ?>
                                                    <div class="mjtc-support-fourth-left">
                                                        <span class="mjtc-support-priorty" style="background:<?php echo esc_attr($ticket->prioritycolour); ?>;">
                                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($ticket->priority)); ?>
                                                        </span>
                                                    </div>
                                                    <?php
                                                } ?>
                                                <div class="mjtc-support-third-left">
                                                    <?php echo esc_html(date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))); ?>
                                                </div>
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
                </div>
                    <!-- latest user tickets -->
                <?php
                if(isset($data['user-tickets']) && majesticsupport::$_config['cplink_latesttickets_user'] == 1){
                    $field_array = MJTC_includer::MJTC_getModel('fieldordering')->getFieldTitleByFieldfor(1);
                    $show_field = MJTC_includer::MJTC_getModel('fieldordering')->getFieldsForListing(1);
                    ?>
                    <div class="mjtc-support-latest-ticket-wrapper">
                        <div class="mjtc-support-haeder">
                            <div class="mjtc-latest-ticket-data-image">
                                <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" class="mjtc-latest-ticket-data-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tickets.png" />
                            </div>
                            <div class="mjtc-support-header-txt">
                                <?php echo esc_html(__("Latest Tickets",'majestic-support')); ?>
                            </div>
                        </div>
                        <div class="mjtc-support-latest-tickets-wrp">
                            <?php
                            foreach($data['user-tickets'] as $ticket){
                                if ($ticket->status == 0) {
                                    $style = "#159667;";
                                    $status = esc_html(__('New', 'majestic-support'));
                                } elseif ($ticket->status == 1) {
                                    $style = "#D78D39;";
                                    $status = esc_html(__('Waiting Reply', 'majestic-support'));
                                } elseif ($ticket->status == 2) {
                                    $style = "#EDA900;";
                                    $status = esc_html(__('In Progress', 'majestic-support'));
                                } elseif ($ticket->status == 3) {
                                    $style = "#2168A2;";
                                    $status = esc_html(__('Replied', 'majestic-support'));
                                } elseif ($ticket->status == 4) {
                                    $style = "#3D355A;";
                                    $status = esc_html(__('Closed', 'majestic-support'));
                                } elseif ($ticket->status == 5) {
                                    $style = "#E91E63;";
                                    $status = esc_html(__('Close and merge', 'majestic-support'));
                                }
                                $ticketviamail = '';
                                if ($ticket->ticketviaemail == 1){
                                    $ticketviamail = esc_html(__('Created via Email', 'majestic-support'));
                                }
                                ?>
                                <div class="mjtc-support-row">
                                    <div class="mjtc-support-first-left">
                                        <div class="mjtc-support-user-img-wrp">
                                            <?php echo wp_kses(ms_get_avatar(MJTC_includer::MJTC_getModel('majesticsupport')->getWPUidById($ticket->uid)), MJTC_ALLOWED_TAGS); ?>
                                        </div>
                                        <div class="mjtc-support-ticket-subject">
                                            <?php
                                            if (isset($field_array['fullname'])) { ?>
                                                <div class="mjtc-support-data-row">
                                                    <?php echo esc_html($ticket->name); ?>
                                                </div>
                                                <?php
                                            }
                                            if (isset($field_array['subject'])) { ?>
                                                <div class="mjtc-support-data-row name">
                                                    <a class="mjtc-support-data-link"
                                                        href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'ticketdetail','majesticsupportid'=> $ticket->id))); ?>"><?php echo esc_html($ticket->subject); ?></a>
                                                </div>
                                                <?php
                                            }
                                            if (isset($field_array['department'])) { ?>
                                                <div class="mjtc-support-data-row">
                                                    <span class="mjtc-support-title"><?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['department'])). ' : '; ?></span>
                                                    <?php echo esc_html(majesticsupport::MJTC_getVarValue($ticket->departmentname)); ?>
                                                </div>
                                                <?php
                                            } ?>
                                        </div>
                                    </div>
                                    <div class="mjtc-second-support-overall-left-wrapper">
                                       <div class="mjtc-support-second-left">
                                            <?php if ($ticket->ticketviaemail == 1){  ?>
                                                <span class="mjtc-support-creade-via-email-spn">
                                                    <?php echo esc_html(majesticsupport::MJTC_getVarValue($ticketviamail)); ?>
                                                </span>
                                            <?php }
                                            $counter = 'one';
                                            if ($ticket->lock == 1) { ?>
                                                <img class="ticketstatusimage <?php echo esc_attr($counter); $counter = 'two'; ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/lock.png"; ?>" title="<?php echo esc_attr(__('The ticket is locked', 'majestic-support')); ?>" />
                                            <?php }
                                            if ($ticket->isoverdue == 1) { ?>
                                                <img class="ticketstatusimage <?php echo esc_attr($counter); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/over-due.png"; ?>" title="<?php echo esc_attr(__('This ticket is marked as overdue', 'majestic-support')); ?>" />
                                            <?php } ?>
                                        </div>
                                        <?php
                                        if (isset($field_array['priority'])) { ?>
                                            <div class="mjtc-support-fourth-left">
                                                <span class="mjtc-support-priorty" style="background:<?php echo esc_attr($ticket->prioritycolour); ?>;">
                                                    <?php echo esc_html(majesticsupport::MJTC_getVarValue($ticket->priority)); ?>
                                                </span>
                                            </div>
                                            <?php
                                        } ?>
                                        <div class="mjtc-support-third-left">
                                            <?php echo esc_html(date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))); ?>
                                       </div>
                                    </div>
                                </div>
                            <?php
                            }
                        ?>
                        </div>
                    </div>
                    <?php
                }
            ?>
            <!-- latest downloads -->
            <?php
            if(isset($data['latest-downloads'])  && majesticsupport::$_config['cplink_latestdownloads_'. $linkname] == 1){
                ?>
                    <div class="mjtc-support-data-list-wrp latst-dnlds">
                        <div class="mjtc-support-haeder">
                            <div class="mjtc-ticket-data-image">
                                <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" class="mjtc-ticket-data-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/download.png" />
                            </div>
                            <div class="mjtc-support-header-txt">
                                <?php echo esc_html(__("Downloads",'majestic-support')); ?>
                            </div>
                        </div>
                        <div class="mjtc-support-data-list">
                            <?php
                        $imgindex = 1;
                        foreach($data['latest-downloads'] as $download){
                            ?>
                            <div class="mjtc-support-data">
                                <div class="mjtc-support-data-tit">
                                    <a onclick="getDownloadById(<?php echo esc_js($download->downloadid) ?>)">
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($download->title)); ?>
                                    </a>
                                </div>

                            </div>
                            <?php
                            $imgindex = $imgindex==6 ? 1 : $imgindex+1;
                        }
                        ?>
                            <div class="mjtc-support-data-last">
                                <a class="mjtc-support-header-link" href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'download','mjslay'=>'downloads'))); ?>"><?php echo esc_html(__("View All Downloads",'majestic-support')); ?></a>
                            </div>
                        </div>
                    </div>
                    <?php
            }
            ?>
            <!-- latest announcements -->
            <?php
            if(isset($data['latest-announcements'])  && majesticsupport::$_config['cplink_latestannouncements_'. $linkname] == 1){
                ?>
                    <div class="mjtc-support-data-list-wrp latst-ancmts">
                        <div class="mjtc-support-haeder">
                            <div class="mjtc-ticket-data-image">
                                <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" class="mjtc-ticket-data-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/Announcements.png" />
                            </div>
                            <div class="mjtc-support-header-txt">
                                <?php echo esc_html(__("Announcements",'majestic-support')); ?>
                            </div>
                        </div>
                        <div class="mjtc-support-data-list">
                            <?php
                        $imgindex = 1;
                        foreach($data['latest-announcements'] as $announcement){
                            ?>
                            <div class="mjtc-support-data">
                                <a class="mjtc-support-data-tit"
                                    href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'announcement', 'mjslay'=>'announcementdetails', 'majesticsupportid'=>$announcement->id))); ?>">
                                    <?php echo esc_html($announcement->title); ?>
                                </a>
                            </div>
                            <?php
                            $imgindex = $imgindex==6 ? 1 : $imgindex+1;
                        }
                        ?>
                            <div class="mjtc-support-data-last">
                                <a class="mjtc-support-header-link"
                                    href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'announcement','mjslay'=>'announcements'))); ?>">
                                    <?php echo esc_html(__("View All Announcements",'majestic-support')); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
            }
            ?>
            <!-- latest articles -->
            <?php
            if(isset($data['latest-articles'])  && majesticsupport::$_config['cplink_latestkb_'. $linkname] == 1){
                ?>
                    <div class="mjtc-support-data-list-wrp latst-kb">
                        <div class="mjtc-support-haeder">
                            <div class="mjtc-ticket-data-image">
                                <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" class="mjtc-ticket-data-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/kb.png" />
                            </div>
                            <div class="mjtc-support-header-txt">
                                <?php echo esc_html(__("Knowledge Base",'majestic-support')); ?>
                            </div>
                        </div>
                        <div class="mjtc-support-data-list">
                            <?php
                        $imgindex = 1;
                        foreach($data['latest-articles'] as $article){
                            ?>
                            <div class="mjtc-support-data">
                                <a class="mjtc-support-data-tit"
                                    href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'knowledgebase', 'mjslay'=>'articledetails', 'majesticsupportid'=>$article->articleid))); ?>">
                                    <?php echo esc_html($article->subject); ?>
                                </a>
                            </div>
                            <?php
                            $imgindex = $imgindex==6 ? 1 : $imgindex+1;
                        }
                        ?>
                            <div class="mjtc-support-data-last">
                                <a class="mjtc-support-header-link"
                                    href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'knowledgebase','mjslay'=>'userknowledgebase'))); ?>">
                                    <?php echo esc_html(__("View All Knowledge Base",'majestic-support')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
            }
            ?>
            <!-- latest faqs -->
            <?php
            if(isset($data['latest-faqs'])  && majesticsupport::$_config['cplink_latestfaqs_'. $linkname] == 1){
                ?>
                    <div class="mjtc-support-data-list-wrp latst-faqs">
                        <div class="mjtc-support-haeder">
                            <div class="mjtc-ticket-data-image">
                                <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" class="mjtc-ticket-data-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/download.png" />
                            </div>
                            <div class="mjtc-support-header-txt">
                                <?php echo esc_html(__("FAQs",'majestic-support')); ?>
                            </div>
                        </div>
                        <div class="mjtc-support-data-list">
                            <?php
                        $imgindex = 1;
                        foreach($data['latest-faqs'] as $faq){
                            ?>
                            <div class="mjtc-support-data">
                                <a class="mjtc-support-data-tit" href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'faq', 'mjslay'=>'faqdetails', 'majesticsupportid'=>$faq->id))); ?>">
                                    <?php echo esc_html($faq->subject); ?>
                                </a>
                            </div>
                            <?php
                            $imgindex = $imgindex==6 ? 1 : $imgindex+1;
                        }
                        ?>
                            <div class="mjtc-support-data-last">
                                <a class="mjtc-support-header-link"
                                    href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'faq','mjslay'=>'faqs'))); ?>">
                                    <?php echo esc_html(__("View All FAQs",'majestic-support')); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
            }
            ?>
                </div>
                <!-- latest agent tickets -->
            </div>
        </div>


        <div id="mjtc-support-main-black-background" style="display:none;"></div>
          <div id="mjtc-support-main-popup" style="display:none;">
            <span id="mjtc-support-popup-title"></span>
            <span id="mjtc-support-popup-close-button"><img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/close-icon-white.png" /></span>
            <div id="mjtc-support-main-content"></div>
            <div id="mjtc-support-main-downloadallbtn"></div>
        </div>

        <?php
    // Permission setting for notification
    } else {
        MJTC_layout::MJTC_getSystemOffline();
    }

    function mjtc_printMenuLink($title, $url, $image_path, $ajaxid=""){
        $html = '
        <a class="mjtc-col-xs-12 mjtc-col-sm-6 mjtc-col-md-4 mjtc-support-dash-menu" href="'.esc_url($url).'" '.esc_attr($ajaxid).'>
            <span class="mjtc-support-dash-menu-icon">
                <img class="mjtc-support-dash-menu-img" alt="menu-link-image" src="'.esc_url($image_path).'" />
            </span>
            <span class="mjtc-support-dash-menu-text">'.esc_html($title).'</span>
        </a>';
        echo  wp_kses($html, MJTC_ALLOWED_TAGS);
        return;
    }
 ?>

    </div>
