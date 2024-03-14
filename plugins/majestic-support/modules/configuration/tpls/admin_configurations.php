<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(in_array('notification', majesticsupport::$_active_addons)){
    wp_enqueue_script('majesticsupport-notify-app', MJTC_PLUGIN_URL . 'includes/js/firebase-app.js');
    wp_enqueue_script('majesticsupport-notify-message', MJTC_PLUGIN_URL . 'includes/js/firebase-messaging.js');
}
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
MJTC_message::MJTC_getMessage();
if(in_array('notification', majesticsupport::$_active_addons)){
    if(majesticsupport::$_data[0]['apiKey_firebase'] != "" && majesticsupport::$_data[0]['databaseURL_firebase'] != "" && majesticsupport::$_data[0]['authDomain_firebase'] != "" && majesticsupport::$_data[0]['projectId_firebase'] != "" && majesticsupport::$_data[0]['storageBucket_firebase'] != "" && majesticsupport::$_data[0]['messagingSenderId_firebase'] != "" && majesticsupport::$_data[0]['server_key_firebase'] != ""){
        do_action('ticket-notify-generate-token');
    }
}
?>
<?php
$majesticsupport_js ="
    jQuery(document).ready(function () {
        jQuery('.majestic-support-configurations-toggle').click(function(){
            jQuery('.majestic-support-configurations .majestic-support-configurations-left').toggle();
        });
        var msconfigid = '". esc_js(majesticsupport::$_data["msconfigid"])."';
        if (msconfigid == 'general') {
            jQuery('#general').css('display','inline-block');
            jQuery('#cn_gen').addClass('active');
        }else if (msconfigid == 'ticketsettig') {
            jQuery('#ticketsettig').css('display','inline-block');
            jQuery('#cn_ts').addClass('active');
        }else if (msconfigid == 'defaultemail') {
            jQuery('#defaultemail').css('display','inline-block');
            jQuery('#cn_dm').addClass('active');
        }else if (msconfigid == 'mailsetting') {
            jQuery('#mailsetting').css('display','inline-block');
            jQuery('#cn_ms').addClass('active');
        }else if (msconfigid == 'staffmenusetting') {
            jQuery('#staffmenusetting').css('display','inline-block');
            jQuery('#cn_sms').addClass('active');
        }else if (msconfigid == 'usermenusetting') {
            jQuery('#usermenusetting').css('display','inline-block');
            jQuery('#cn_ums').addClass('active');
        }else if (msconfigid == 'feedback') {
            jQuery('#feedback').css('display','inline-block');
            jQuery('#cn_fb').addClass('active');
        }else if (msconfigid == 'sociallogin') {
            jQuery('#sociallogin').css('display','inline-block');
            jQuery('#cn_sl').addClass('active');
        }else if (msconfigid == 'ticketviaemail') {
            jQuery('#ticketviaemail').css('display','inline-block');
            jQuery('#cn_tve').addClass('active');
        }else if (msconfigid == 'pushnotification') {
            jQuery('#pushnotification').css('display','inline-block');
            jQuery('#cn_pn').addClass('active');
        }else if (msconfigid == 'privatecredentials') {
            jQuery('#privatecredentials').css('display','inline-block');
            jQuery('#cn_pc').addClass('active');
        }else if (msconfigid == 'envatovalidation') {
            jQuery('#envatovalidation').css('display','inline-block');
            jQuery('#cn_ev').addClass('active');
        }else if (msconfigid == 'mailchimp') {
            jQuery('#mailchimp').css('display','inline-block');
            jQuery('#cn_mc').addClass('active');
        }else if (msconfigid == 'easydigitaldownloads') {
            jQuery('#easydigitaldownloads').css('display','inline-block');
            jQuery('#cn_edd').addClass('active');
        }else if (msconfigid == 'captcha') {
            jQuery('#captcha').css('display','inline-block');
            jQuery('#cn_cap').addClass('active');
        }else{
            jQuery('#general').css('display','inline-block');
            jQuery('#cn_gen').addClass('active');
        }
        // new code

        jQuery('ul.ms_tabs li').click(function(){
            var tab_id = jQuery(this).attr('data-ms-tab');
            jQuery('ul.ms_tabs li').removeClass('ms_current_tab');
            jQuery('.ms_tab_content').removeClass('ms_current_tab');
            jQuery(this).addClass('ms_current_tab');
            jQuery('#'+tab_id).addClass('ms_current_tab');
        });

        jQuery('select#ticket_overdue_type').change(function(){
            var isselect = jQuery('select#ticket_overdue_type').val();
            if(isselect == 1){
                jQuery('span.ticket_overdue_type_text').html(\"". esc_html(__('Days', 'majestic-support'))."\");
            }else{
                jQuery('span.ticket_overdue_type_text').html(\"". esc_html(__('Hours', 'majestic-support'))."\");
            }
        });
    });
    function showhidehostname(value){
        if(value == 4){
            jQuery('div#tve_hostname').show();
        }else{
            jQuery('div#tve_hostname').hide();
        }
    }
    function deleteSupportCustomImage(){
        jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'configuration', task: 'deleteSupportCustomImage', '_wpnonce':'". esc_attr(wp_create_nonce("delete-support-customimage"))."'}, function (data) {
            if(data){
                jQuery('.mjtc-support-configuration-img').addClass('visible');
            }
        });
    }

    jQuery(document).ready(function () {
        jQuery('select#set_login_link').change(function(){
            var value = jQuery(this).val();
            if (value == 2) {
               jQuery('.loginlink_field').attr('style','display: block');
            } else {
                jQuery('.loginlink_field').attr('style','display: none');
            }
        })

        var value = jQuery('select#set_login_link').val();
        if (value == 2) {
            jQuery('.loginlink_field').attr('style','display: block');
        } else {
            jQuery('.loginlink_field').attr('style','display: none');
        }

        jQuery('select#set_register_link').change(function(){
            var value = jQuery(this).val();
            if (value == 2) {
                jQuery('.registerlink_field').attr('style','display: block');
            } else {
                jQuery('.registerlink_field').attr('style','display: none');
            }
        });

        var value = jQuery('select#set_register_link').val();
        if (value == 2) {
           jQuery('.registerlink_field').attr('style','display: block');
        } else {
            jQuery('.registerlink_field').attr('style','display: none');
        }

    });

    // for hide and show baseb on custom fields
    jQuery(document).ready(function () {
        jQuery('select#ticketid_sequence').change(function(){
            var value = jQuery(this).val();
            if (value == 2){
                jQuery('.Ticketid-sequence-custom').slideDown('slow');
            } else {
                jQuery('.Ticketid-sequence-custom').slideUp('slow');
            }
            setpadZerosText();
        });
        var value = jQuery('select#ticketid_sequence').val();
        if (value == 2){
            jQuery('.Ticketid-sequence-custom').css('display','inline-block');
        } else {
            jQuery('.Ticketid-sequence-custom').css('display','none');
        }

        // for prefix and suffix
        jQuery('#padZeros-prefix').text(jQuery('#prefix_ticketid').val());
        jQuery('#padZeros-suffix').text(jQuery('#suffix_ticketid').val());
        jQuery('#prefix_ticketid').on('input', function(){
            jQuery('#padZeros-prefix').text(jQuery(this).val());
        });
        jQuery('#suffix_ticketid').on('input', function(){
            jQuery('#padZeros-suffix').text(jQuery(this).val());
        });

        // for pad zeroes
        jQuery('select#padding_zeros_ticketid').change(function(){
           setpadZerosText();
        });
        setpadZerosText();
        
    });

    function setpadZerosText() {
        var value = jQuery('select#ticketid_sequence').val();
        if (value == 1){
            jQuery('#padZeros').text('xxxxxxx');
        } else {
            var value = jQuery('select#padding_zeros_ticketid').val();
            if (value == 1){
                jQuery('#padZeros').text('1');
            } else if (value == 2) {
                jQuery('#padZeros').text('01');
            } else if (value == 3) {
                jQuery('#padZeros').text('001');
            } else if (value == 4) {
                jQuery('#padZeros').text('0001');
            } else if (value == 5) {
                jQuery('#padZeros').text('00001');
            } else if (value == 6) {
                jQuery('#padZeros').text('000001');
            }
        }
    }

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  

<?php
$captchaselection = array(
    (object) array('id' => '1', 'text' => esc_html(__('Google Recaptcha', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Own Captcha', 'majestic-support')))
);
$owncaptchaoparend = array(
    (object) array('id' => '2', 'text' => '2'),
    (object) array('id' => '3', 'text' => '3')
);
$owncaptchatype = array(
    (object) array('id' => '0', 'text' => esc_html(__('Any', 'majestic-support'))),
    (object) array('id' => '1', 'text' => esc_html(__('Addition', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Subtraction', 'majestic-support')))
);
$recaptcha_version = array(
    (object) array('id' => '1', 'text' => esc_html(__('Recaptcha Version 2', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Recaptcha Version 3', 'majestic-support')))
);
$yesno = array(
    (object) array('id' => '1', 'text' => esc_html(__('Yes', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('No', 'majestic-support')))
);
$showhide = array(
    (object) array('id' => '1', 'text' => esc_html(__('Show', 'majestic-support'))),
    (object) array('id' => '0', 'text' => esc_html(__('Hide', 'majestic-support')))
);
$defaultcustom = array(
    (object) array('id' => '1', 'text' => esc_html(__('Majestic Support Login Page', 'majestic-support'))),
    (object) array('id' => '3', 'text' => esc_html(__('WordPress Default Login Page', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Custom', 'majestic-support')))
);
$defaultregisterpage = array(
    (object) array('id' => '1', 'text' => esc_html(__('Majestic Support Register Page', 'majestic-support'))),
    (object) array('id' => '3', 'text' => esc_html(__('WordPress Default Login Page', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Custom', 'majestic-support')))
);
$screentagposition = array(
    (object) array('id' => '1', 'text' => esc_html(__('Top left', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Top right', 'majestic-support'))),
    (object) array('id' => '3', 'text' => esc_html(__('Middle left', 'majestic-support'))),
    (object) array('id' => '4', 'text' => esc_html(__('Middle right', 'majestic-support'))),
    (object) array('id' => '5', 'text' => esc_html(__('Bottom left', 'majestic-support'))),
    (object) array('id' => '6', 'text' => esc_html(__('Bottom right', 'majestic-support')))
);
$enableddisabled = array(
    (object) array('id' => '1', 'text' => esc_html(__('Enabled', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Disabled', 'majestic-support')))
);
$mailreadtype = array(
    (object) array('id' => '1', 'text' => esc_html(__('Only New Tickets', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Only Replies', 'majestic-support'))),
    (object) array('id' => '3', 'text' => esc_html(__('Both', 'majestic-support')))
);

$sequence = array(
    (object) array('id' => '1', 'text' => esc_html(__('Random', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Sequence', 'majestic-support')))
);

$padZeros = array(
    (object) array('id' => '1', 'text' => esc_html(__('1', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('2', 'majestic-support'))),
    (object) array('id' => '3', 'text' => esc_html(__('3', 'majestic-support'))),
    (object) array('id' => '4', 'text' => esc_html(__('4', 'majestic-support'))),
    (object) array('id' => '5', 'text' => esc_html(__('5', 'majestic-support'))),
    (object) array('id' => '6', 'text' => esc_html(__('6', 'majestic-support')))
);

$hosttype = array(
    (object) array('id' => '1', 'text' => esc_html(__('Gmail', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Yahoo', 'majestic-support'))),
    (object) array('id' => '3', 'text' => esc_html(__('Aol', 'majestic-support'))),
    (object) array('id' => '4', 'text' => esc_html(__('Other', 'majestic-support')))
);

$ticketordering = array(
    (object) array('id' => '1', 'text' => esc_html(__('Default', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Created', 'majestic-support')))
);
$ticketsorting = array(
    (object) array('id' => '1', 'text' => esc_html(__('Ascending', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Descending', 'majestic-support')))
);
$smartreply = array(
    (object) array('id' => '1', 'text' => esc_html(__('1', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('2', 'majestic-support'))),
    (object) array('id' => '3', 'text' => esc_html(__('3', 'majestic-support')))
);
$reasontype = array(
    (object) array('id' => '1', 'text' => esc_html(__('Single', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Multiple', 'majestic-support')))
);
// wp roles combo for new user
global $wp_roles;
$roles = $wp_roles->get_names();
$userroles = array();
foreach ($roles as $key => $value) {
    $userroles[] = (object) array('id' => $key, 'text' => $value);
}
$plugin_array = get_option('active_plugins');
?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('configurations'); ?>
        <div id="msadmin-data-wrp" class="msadmin-data-wrp-ban-email">
            <form method="post" class="majestic-support-configurations" action="<?php echo esc_url(wp_nonce_url(admin_url("?page=majesticsupport_configuration&task=saveconfiguration"),"save-configuration")); ?>" enctype="multipart/form-data">
                <div class="majestic-support-configurations-toggle">
                    <img class="ms_menu-icon" alt="<?php echo esc_html(__('menu' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/menu.png'; ?>"/>
                    <span class="ms_text">
                        <?php echo esc_html(__('Select Configuration' , 'majestic-support')); ?>
                    </span>
                </div>
                <div class="majestic-support-configurations-left">
                    <ul class="msadmin-sidebar-menu tree accordion" data-widget="tree">
                        <li class="treeview" id="cn_gen">
                            <a href="?page=majesticsupport_configuration&msconfigid=general" title="<?php echo esc_attr(__('General' , 'majestic-support')); ?>">
                                <img class="ms_menu-icon" alt="<?php echo esc_html(__('General' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/config.png'; ?>"/>
                                <span class="ms_text">
                                    <?php echo esc_html(__('General' , 'majestic-support')); ?>
                                </span>
                            </a>
                            <ul class="msadmin-sidebar-submenu treeview-menu">
                                <li>
                                    <a href="?page=majesticsupport_configuration&msconfigid=general"><?php echo esc_html(__('General Settings', 'majestic-support')) ?></a>
                                </li>
                                <li>
                                    <a href="?page=majesticsupport_configuration&msconfigid=general#TicketDefault">
                                        <?php echo esc_html(__('Attachments', 'majestic-support')) ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="?page=majesticsupport_configuration&msconfigid=general#login">
                                        <?php echo esc_html(__('Login', 'majestic-support')) ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="?page=majesticsupport_configuration&msconfigid=general#register">
                                        <?php echo esc_html(__('Register', 'majestic-support')) ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="?page=majesticsupport_configuration&msconfigid=general#SupportIcons">
                                        <?php echo esc_html(__('Support Icon', 'majestic-support')) ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="?page=majesticsupport_configuration&msconfigid=general#Offline">
                                        <?php echo esc_html(__('Offline', 'majestic-support')) ?>
                                    </a>
                                </li>
                                <?php if(in_array('paidsupport', majesticsupport::$_active_addons) && in_array('woocommerce/woocommerce.php', $plugin_array)){ ?>
                                    <li>
                                        <a href="?page=majesticsupport_configuration&msconfigid=general#PaidSupport">
                                            <?php echo esc_html(__('Paid Support', 'majestic-support')) ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li class="treeview" id="cn_ts">
                            <a href="?page=majesticsupport_configuration&msconfigid=ticketsettig" title="<?php echo esc_attr(__('Ticket Settings' , 'majestic-support')); ?>">
                                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Ticket Settings' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/tickets.png'; ?>"/>
                                <span class="ms_text">
                                    <?php echo esc_html(__('Ticket Settings' , 'majestic-support')); ?>
                                </span>
                            </a>
                            <ul class="msadmin-sidebar-submenu treeview-menu">
                                <li>
                                    <a href="?page=majesticsupport_configuration&msconfigid=ticketsettig">
                                        <?php echo esc_html(__('Ticket Settings', 'majestic-support')) ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="?page=majesticsupport_configuration&msconfigid=ticketsettig#TicketListing">
                                        <?php echo esc_html(__('Ticket Listing', 'majestic-support')); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="?page=majesticsupport_configuration&msconfigid=ticketsettig#TS_visitorTs">
                                        <?php echo esc_html(__('Visitor Ticket Setting', 'majestic-support')) ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview" id="cn_dm">
                            <a href="?page=majesticsupport_configuration&msconfigid=defaultemail" title="<?php echo esc_attr(__('System Emails' , 'majestic-support')); ?>">
                                <img class="ms_menu-icon" alt="<?php echo esc_html(__('System Emails' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/system-email.png'; ?>"/>
                                <span class="ms_text"><?php echo esc_html(__('System Emails' , 'majestic-support')); ?> </span>
                            </a>
                            <ul class="msadmin-sidebar-submenu treeview-menu">
                              <li><a href="?page=majesticsupport_configuration&msconfigid=defaultemail"><?php echo esc_html(__('System Emails', 'majestic-support')) ?></a></li>
                            </ul>
                        </li>
                        <li class="treeview" id="cn_cap">
                            <a href="?page=majesticsupport_configuration&msconfigid=captcha" title="<?php echo esc_attr(__('Captcha' , 'majestic-support')); ?>">
                                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Captcha' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/captcha.png'; ?>"/>
                                <span class="ms_text"><?php echo esc_html(__('Captcha' , 'majestic-support')); ?> </span>
                            </a>
                            <ul class="msadmin-sidebar-submenu treeview-menu">
                              <li><a href="?page=majesticsupport_configuration&msconfigid=captcha"><?php echo esc_html(__('Captcha', 'majestic-support')) ?></a></li>
                            </ul>
                        </li>
                        <li class="treeview" id="cn_ms">
                            <a href="?page=majesticsupport_configuration&msconfigid=mailsetting" title="<?php echo esc_attr(__('Email Settings' , 'majestic-support')); ?>">
                                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Email Settings' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/email-settings.png'; ?>"/>
                                <span class="ms_text"><?php echo esc_html(__('Email Settings' , 'majestic-support')); ?> </span>
                            </a>
                            <ul class="msadmin-sidebar-submenu treeview-menu">
                              <?php if(isset(majesticsupport::$_data[0]['banemail_mail_to_admin'])){ ?>
                                <li><a href="?page=majesticsupport_configuration&msconfigid=mailsetting#BanEmailNewTicket"><?php echo esc_html(__('Email Regarding Banned Users', 'majestic-support')) ?></a></li>
                                <?php } ?>
                              <li><a href="?page=majesticsupport_configuration&msconfigid=mailsetting#TicketOperationsEmailSetting"><?php echo esc_html(__('Ticket Operations Email Setting', 'majestic-support')) ?></a></li>
                            </ul>
                        </li>
                        <?php if(in_array('agent', majesticsupport::$_active_addons)){ ?>
                          <li class="treeview" id="cn_sms">
                              <a href="?page=majesticsupport_configuration&msconfigid=staffmenusetting" title="<?php echo esc_attr(__('Agent Menu' , 'majestic-support')); ?>">
                                  <img class="ms_menu-icon" alt="<?php echo esc_html(__('Agent Menu' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/agent-menu.png'; ?>"/>
                                  <span class="ms_text"><?php echo esc_html(__('Agent Menu' , 'majestic-support')); ?> </span>
                              </a>
                              <ul class="msadmin-sidebar-submenu treeview-menu">
                                <li><a href="?page=majesticsupport_configuration&msconfigid=staffmenusetting"><?php echo esc_html(__('Dashboard Links', 'majestic-support')) ?></a></li>
                                <li><a href="?page=majesticsupport_configuration&msconfigid=staffmenusetting#TopMenuLinks"><?php echo esc_html(__('Top Menu Links', 'majestic-support')) ?></a></li>
                              </ul>
                          </li>
                        <?php } ?>
                        <li class="treeview" id="cn_ums">
                            <a href="?page=majesticsupport_configuration&msconfigid=usermenusetting" title="<?php echo esc_attr(__('User Menu' , 'majestic-support')); ?>">
                                <img class="ms_menu-icon" alt="<?php echo esc_html(__('User Menu' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/user-menu.png'; ?>"/>
                                <span class="ms_text"><?php echo esc_html(__('User Menu' , 'majestic-support')); ?> </span>
                            </a>
                            <ul class="msadmin-sidebar-submenu treeview-menu">
                              <li><a href="?page=majesticsupport_configuration&msconfigid=usermenusetting"><?php echo esc_html(__('Dashboard Links', 'majestic-support')) ?></a></li>
                              <li><a href="?page=majesticsupport_configuration&msconfigid=usermenusetting#TopMenuLinksUser"><?php echo esc_html(__('Top Menu Links', 'majestic-support')) ?></a></li>
                            </ul>
                        </li>
                        <?php if(in_array('feedback', majesticsupport::$_active_addons)){ ?>
                          <li class="treeview" id="cn_fb">
                              <a href="?page=majesticsupport_configuration&msconfigid=feedback" title="<?php echo esc_attr(__('Feedback' , 'majestic-support')); ?>">
                                  <img class="ms_menu-icon" alt="<?php echo esc_html(__('Feedback' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/feedback.png'; ?>"/>
                                  <span class="ms_text"><?php echo esc_html(__('Feedback' , 'majestic-support')); ?> </span>
                              </a>
                              <ul class="msadmin-sidebar-submenu treeview-menu">
                                <li><a href="?page=majesticsupport_configuration&msconfigid=feedback"><?php echo esc_html(__('Feedback Settings', 'majestic-support')) ?></a></li>
                              </ul>
                          </li>
                            <?php
                        }
                        if(in_array('emailpiping', majesticsupport::$_active_addons)){ ?>
                          <li class="treeview" id="cn_tve">
                              <a href="?page=majesticsupport_configuration&msconfigid=ticketviaemail" title="<?php echo esc_attr(__('Email Piping' , 'majestic-support')); ?>">
                                  <img class="ms_menu-icon" alt="<?php echo esc_html(__('Email Piping' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/email-piping.png'; ?>"/>
                                  <span class="ms_text"><?php echo esc_html(__('Email Piping' , 'majestic-support')); ?> </span>
                              </a>
                              <ul class="msadmin-sidebar-submenu treeview-menu">
                                <li><a href="?page=majesticsupport_configuration&msconfigid=ticketviaemail"><?php echo esc_html(__('Email Piping', 'majestic-support')) ?></a></li>
                              </ul>
                          </li>
                            <?php
                        }
                        if(in_array('notification', majesticsupport::$_active_addons)){ ?>
                          <li class="treeview" id="cn_pn">
                              <a href="?page=majesticsupport_configuration&msconfigid=pushnotification" title="<?php echo esc_attr(__('Push Notifications' , 'majestic-support')); ?>">
                                  <img class="ms_menu-icon" alt="<?php echo esc_html(__('Push Notifications' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/push-notifications.png'; ?>"/>
                                  <span class="ms_text"><?php echo esc_html(__('Push Notifications' , 'majestic-support')); ?> </span>
                              </a>
                              <ul class="msadmin-sidebar-submenu treeview-menu">
                                <li><a href="?page=majesticsupport_configuration&msconfigid=pushnotification"><?php echo esc_html(__('Firebase Notifications', 'majestic-support')) ?></a></li>
                              </ul>
                          </li>
                            <?php
                        }
                        if(in_array('privatecredentials', majesticsupport::$_active_addons)){ ?>
                            <li class="treeview" id="cn_pc">
                                <a href="?page=majesticsupport_configuration&msconfigid=privatecredentials" title="<?php echo esc_attr(__('Private Credentials' , 'majestic-support')); ?>">
                                    <img class="ms_menu-icon" alt="<?php echo esc_html(__('Private Credentials' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/private-credentials.png'; ?>"/>
                                    <span class="ms_text"><?php echo esc_html(__('Private Credentials' , 'majestic-support')); ?> </span>
                                </a>
                                <ul class="msadmin-sidebar-submenu treeview-menu">
                                    <li>
                                        <a href="?page=majesticsupport_configuration&msconfigid=privatecredentials">
                                            <?php echo esc_html(__('Private Credentials', 'majestic-support')) ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <?php
                        }
                        if(in_array('envatovalidation', majesticsupport::$_active_addons)){ ?>
                            <li class="treeview" id="cn_ev">
                                <a href="?page=majesticsupport_configuration&msconfigid=envatovalidation" title="<?php echo esc_attr(__('Envato Validation' , 'majestic-support')); ?>">
                                    <img class="ms_menu-icon" alt="<?php echo esc_html(__('Envato Validation' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/envato-validation.png'; ?>"/>
                                    <span class="ms_text"><?php echo esc_html(__('Envato Validation' , 'majestic-support')); ?> </span>
                                </a>
                                <ul class="msadmin-sidebar-submenu treeview-menu">
                                    <li>
                                        <a href="?page=majesticsupport_configuration&msconfigid=envatovalidation">
                                            <?php echo esc_html(__('Envato Validation', 'majestic-support')) ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <?php
                        }
                        if(in_array('mailchimp', majesticsupport::$_active_addons)){ ?>
                            <li class="treeview" id="cn_mc">
                                <a href="?page=majesticsupport_configuration&msconfigid=mailchimp" title="<?php echo esc_attr(__('MailChimp' , 'majestic-support')); ?>">
                                    <img class="ms_menu-icon" alt="<?php echo esc_html(__('MailChimp' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/mail-chimp.png'; ?>"/>
                                    <span class="ms_text">
                                        <?php echo esc_html(__('MailChimp' , 'majestic-support')); ?>
                                    </span>
                                </a>
                                <ul class="msadmin-sidebar-submenu treeview-menu">
                                    <li>
                                        <a href="?page=majesticsupport_configuration&msconfigid=mailchimp">
                                            <?php echo esc_html(__('MailChimp', 'majestic-support')) ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if(in_array('easydigitaldownloads', majesticsupport::$_active_addons)){ ?>
                          <li class="treeview" id="cn_edd">
                              <a href="?page=majesticsupport_configuration&msconfigid=easydigitaldownloads" title="<?php echo esc_attr(__('Easy Digital Downloads' , 'majestic-support')); ?>">
                                  <img class="ms_menu-icon" alt="<?php echo esc_html(__('Easy Digital Downloads' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/easy-digital-downloads.png'; ?>"/>
                                  <span class="ms_text"><?php echo esc_html(__('Easy Digital Downloads' , 'majestic-support')); ?> </span>
                              </a>
                              <ul class="msadmin-sidebar-submenu treeview-menu">
                                <li><a href="?page=majesticsupport_configuration&msconfigid=easydigitaldownloads"><?php echo esc_html(__('Easy Digital Downloads', 'majestic-support')) ?></a></li>
                              </ul>
                          </li>
                        <?php } ?>
                        <?php if(in_array('sociallogin', majesticsupport::$_active_addons)){ ?>
                          <li class="treeview" id="cn_sl">
                              <a href="?page=majesticsupport_configuration&msconfigid=sociallogin" title="<?php echo esc_attr(__('Social Login' , 'majestic-support')); ?>">
                                  <img class="ms_menu-icon" alt="<?php echo esc_html(__('Social Login' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/config-icons/social-login.png'; ?>"/>
                                  <span class="ms_text"><?php echo esc_html(__('Social Login' , 'majestic-support')); ?> </span>
                              </a>
                              <ul class="msadmin-sidebar-submenu treeview-menu">
                                <li><a href="?page=majesticsupport_configuration&msconfigid=sociallogin"><?php echo esc_html(__('Facebook', 'majestic-support')) ?></a></li>
                                <li><a href="?page=majesticsupport_configuration&msconfigid=sociallogin#Linkedin"><?php echo esc_html(__('Linkedin', 'majestic-support')) ?></a></li>
                              </ul>
                          </li>
                        <?php } ?>
                      </ul>
                    </div>
                    <div class="majestic-support-configurations-right">
                    <div id="general" class="msadmin-hide-config">
                      <div class="tabs config-tabs" id="tabs">
                          <ul class="ms_tabs">
                              <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#GeneralSetting"><?php echo esc_html(__('General Settings', 'majestic-support')) ?></a></li>
                              <li class="tab-link" data-ms-tab="ticketsettig"><a href="#TicketDefault"><?php echo esc_html(__('Attachments', 'majestic-support')) ?></a></li>
                              <li class="tab-link" data-ms-tab="ticketsettig"><a href="#login"><?php echo esc_html(__('Login', 'majestic-support')) ?></a></li>
                              <li class="tab-link" data-ms-tab="ticketsettig"><a href="#register"><?php echo esc_html(__('Register', 'majestic-support')) ?></a></li>
                              <li class="tab-link" data-ms-tab="defaultemail"><a href="#SupportIcons"><?php echo esc_html(__('Support Icon', 'majestic-support')) ?></a></li>
                              <li class="tab-link" data-ms-tab="mailsetting"><a href="#Offline"><?php echo esc_html(__('Offline', 'majestic-support')) ?></a></li>
                              <?php if(in_array('paidsupport', majesticsupport::$_active_addons) && in_array('woocommerce/woocommerce.php', $plugin_array)){ ?>
                                <li class="tab-link" data-ms-tab="paidsupport"><a href="#PaidSupport"><?php echo esc_html(__('Paid Support', 'majestic-support')) ?></a></li>
                              <?php } ?>
                          </ul>
                      </div>
                      <div class="ms_gen_body" id="GeneralSetting">
                          <h2><?php echo esc_html(__('General Settings', 'majestic-support')) ?></h2>
                          <?php
                            if(isset(majesticsupport::$_data[0]['title'])){
                              $title = esc_html(__('Title', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_text('title', majesticsupport::$_data[0]['title'], array('class' => 'inputbox'));
                              $description =  esc_html(__('Set the heading of your plugin', 'majestic-support'));
                              mjtc_printConfigFieldSingle($title, $field, $description);
                            }

                            if(isset(majesticsupport::$_data[0]['default_pageid'])){
                              $title = esc_html(__('Ticket Default Page', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_select('default_pageid', MJTC_includer::MJTC_getModel('configuration')->getPageList(), majesticsupport::$_data[0]['default_pageid'], esc_html(__('Select Page', 'majestic-support')), array('class' => 'inputbox', 'data-validation' => 'required'));
                              $description =  esc_html(__('Select the Majestic Support default page, and on the action system, it will redirect to the selected page. If not selected the default page, email links, and support icon might not work.', 'majestic-support'));
                              mjtc_printConfigFieldSingle($title, $field, $description);
                            }

                            if(isset(majesticsupport::$_data[0]['data_directory'])){
                              $title = esc_html(__('Data Directory', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_text('data_directory', majesticsupport::$_data[0]['data_directory'], array('class' => 'inputbox'));
                              $description =  esc_html(__('Set the name for your data directory', 'majestic-support')) .'<br>' . esc_html(__('You need to rename the existing data directory in the file system before changing the data directory name', 'majestic-support')) ; ?><?php
                              mjtc_printConfigFieldSingle($title, $field, $description);
                            }

                            if(isset(majesticsupport::$_data[0]['date_format'])){
                              $title = esc_html(__('Date Format', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_select('date_format', array((object) array('id' => 'd-m-Y', 'text' => esc_html(__("DD-MM-YYYY", 'majestic-support'))), (object) array('id' => 'm-d-Y', 'text' => esc_html(__("MM-DD-YYYY", 'majestic-support'))), (object) array('id' => 'Y-m-d', 'text' => esc_html(__("YYYY-MM-DD", 'majestic-support')))), majesticsupport::$_data[0]['date_format']);
                              $description =  esc_html(__('Set the default date format', 'majestic-support'));
                              mjtc_printConfigFieldSingle($title, $field, $description);
                            }

                            if(isset(majesticsupport::$_data[0]['pagination_default_page_size'])){
                              $title = esc_html(__('Pagination default page size', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_text('pagination_default_page_size', majesticsupport::$_data[0]['pagination_default_page_size'], array('class' => 'inputbox'));
                              $description =  esc_html(__('Set the number of records per page', 'majestic-support'));
                              mjtc_printConfigFieldSingle($title, $field, $description);
                            }

                            if(isset(majesticsupport::$_data[0]['show_breadcrumbs'])){
                              $title = esc_html(__('Breadcrumbs', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_select('show_breadcrumbs', $showhide, majesticsupport::$_data[0]['show_breadcrumbs']);
                              $description =  esc_html(__('Show or hide breadcrumbs', 'majestic-support'));
                              mjtc_printConfigFieldSingle($title, $field, $description);
                            }

                            if(isset(majesticsupport::$_data[0]['show_header'])){
                              $title = esc_html(__('Top Header', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_select('show_header', $showhide, majesticsupport::$_data[0]['show_header']);
                              $description =  esc_html(__('Show or hide top header', 'majestic-support'));
                              mjtc_printConfigFieldSingle($title, $field, $description);
                            }

                            if(isset(majesticsupport::$_data[0]['count_on_myticket'])){
                              $title = esc_html(__('Show count on tickets', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_select('count_on_myticket', $yesno, majesticsupport::$_data[0]['count_on_myticket']);
                              $description =  esc_html(__('Show the number of open, closed, and answered tickets in my tickets and dashboard', 'majestic-support'));
                              $video = 'gCB-wGVZph8';
                              $videotext = 'Show count on tickets';
                              mjtc_printConfigFieldSingle($title, $field, $description, $video, '', $videotext);
                            }

                            if(isset(majesticsupport::$_data[0]['wp_default_role'])){
                                $title = esc_html(__('Default wp role for new users', 'majestic-support'));
                                $field = MJTC_formfield::MJTC_select('wp_default_role', $userroles, majesticsupport::$_data[0]['wp_default_role']);
                                $description =  esc_html(__('Select the role you want to assign to new users', 'majestic-support'));
                                $video = '';
                                $videotext = 'Default wp role for new users';
                                if(in_array('useroptions', majesticsupport::$_active_addons)){
                                    $video = '6AE4ZHB9bJk';
                                }
                                mjtc_printConfigFieldSingle($title, $field, $description, $video, '', $videotext);
                            }
                          ?>
                      </div>
                      <div class="ms_gen_body" id="TicketDefault">
                          <h2><?php echo esc_html(__('Attachments', 'majestic-support')) ?></h2>
                          <?php
                            if(isset(majesticsupport::$_data[0]['no_of_attachement'])){
                              $title = esc_html(__('No. of attachments', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_text('no_of_attachement', majesticsupport::$_data[0]['no_of_attachement'], array('class' => 'inputbox'));
                              $description =  esc_html(__('Number of attachments allowed at a time', 'majestic-support'));
                              mjtc_printConfigFieldSingle($title, $field, $description);
                            }

                           if(isset(majesticsupport::$_data[0]['file_maximum_size'])){
                              $title = esc_html(__('File maximum size', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_text('file_maximum_size', majesticsupport::$_data[0]['file_maximum_size'], array('class' => 'inputbox')) ?><?php 
                              $description =  esc_html(__('Kb', 'majestic-support'));
                              mjtc_printConfigFieldSingle($title, $field,$description);
                            }

                            if(isset(majesticsupport::$_data[0]['file_extension'])){
                              $title = esc_html(__('File extensions', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_textarea('file_extension', majesticsupport::$_data[0]['file_extension'], array('class' => 'inputbox'));
                              $description =  esc_html(__('File extensions allowed to attach', 'majestic-support'));
                              mjtc_printConfigFieldSingle($title, $field, $description);
                            }
                          ?>
                      </div>
                      <div class="ms_gen_body" id="login">
                          <h2><?php echo esc_html(__('Login', 'majestic-support')) ?></h2>
                          <?php

                            if(isset(majesticsupport::$_data[0]['set_login_link'])){
                              $title = esc_html(__('Set Login Link', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_select('set_login_link', $defaultcustom, majesticsupport::$_data[0]['set_login_link']);
                              $description =  esc_html(__('Set login link default or custom', 'majestic-support'));
                              $childfield = '';
                              $video = 'bzK2IxQ0QaU';
                              $videotext = 'Login redirect';
                              if(isset(majesticsupport::$_data[0]['login_link'])){
                                  $childfield = MJTC_formfield::MJTC_text('login_link', majesticsupport::$_data[0]['login_link'], array('class' => 'inputbox loginlink_field'));
                              }
                              mjtc_printConfigFieldSingle($title, $field, $description, $video, $childfield, $videotext);
                            }
                          ?>
                        </div>
                        <div class="ms_gen_body" id="register">
                            <h2>
                                <?php echo esc_html(__('Register', 'majestic-support')) ?>
                            </h2>
                            <?php
                            if(isset(majesticsupport::$_data[0]['set_register_link'])){
                                $title = esc_html(__('Set register Link', 'majestic-support'));
                                $field = MJTC_formfield::MJTC_select('set_register_link', $defaultregisterpage, majesticsupport::$_data[0]['set_register_link']);
                                $description =  esc_html(__('Set register link default or custom'.'.<br />'.' To enable registrations, WordPress admin > General > Settings > Membership: Anyone can register', 'majestic-support'));
                                $childfield = '';
                                if(isset(majesticsupport::$_data[0]['register_link'])){
                                    $childfield = MJTC_formfield::MJTC_text('register_link', majesticsupport::$_data[0]['register_link'], array('class' => 'inputbox registerlink_field'));
                                }
                                mjtc_printConfigFieldSingle($title, $field, $description, '', $childfield);
                            }
                          ?>
                      </div>
                      <div class="ms_gen_body" id="SupportIcons">
                          <h2><?php echo esc_html(__('Support Icon', 'majestic-support')) ?></h2>
                          <?php
                            if(isset(majesticsupport::$_data[0]['support_screentag'])){
                              $title = esc_html(__('Support Icon', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_select('support_screentag', $showhide, majesticsupport::$_data[0]['support_screentag'], esc_html(__('Screen Tag', 'majestic-support')), array('class' => 'inputbox', 'data-validation' => 'required'));
                              $description =  esc_html(__('Enable or disable your support icon', 'majestic-support'));
                              mjtc_printConfigFieldSingle($title, $field, $description);
                            }

                            if(isset(majesticsupport::$_data[0]['support_custom_img'])){ ?>
                              <div class="mjtc-support-configuration-row">
                                <div class="mjtc-support-configuration-title"><?php echo esc_html(__('Custom Image', 'majestic-support'))?></div>
                                <div class="mjtc-support-configuration-value">
                                    <input type="file" name="support_custom_img" id="support_custom_img"  />
                                    <div class="mjtc-support-configuration-description">
                                      <?php echo esc_html(__('Set custom support image', 'majestic-support')) ?>
                                    </div>
                                    <span class="mjtc-support-configuration-img">
                                      <?php if(majesticsupport::$_data[0]['support_custom_img'] != '0'){
                                        $maindir = wp_upload_dir();
                                        $basedir = $maindir['baseurl'];
                                        $datadirectory = majesticsupport::$_config['data_directory'];
                                        $path = $basedir . '/' . $datadirectory;
                                        $path .= "/supportImg/" . majesticsupport::$_data[0]['support_custom_img'];
                                        ?>
                                        <img alt="<?php echo esc_html(__('image','majestic-support')); ?>" width="50px" height="50px" src="<?php echo esc_url($path); ?>">
                                          <?php echo esc_html(majesticsupport::$_data[0]['support_custom_img']) ?>
                                          <a title="<?php echo esc_attr(__('Delete','majestic-support')) ?>" onclick="deleteSupportCustomImage()">( <?php echo esc_html(__('Delete','majestic-support')) ?> )</a>
                                      <?php } ?>
                                    </span>
                                </div>
                              </div>
                            <?php }

                            if(isset(majesticsupport::$_data[0]['support_custom_txt'])){
                                $title = esc_html(__('custom text', 'majestic-support'));
                                $field = MJTC_formfield::MJTC_text('support_custom_txt', majesticsupport::$_data[0]['support_custom_txt'], array('class' => 'inputbox'));
                                $description =  esc_html(__('Set custom support text', 'majestic-support'));
                                mjtc_printConfigFieldSingle($title, $field, $description);
                            }

                            if(isset(majesticsupport::$_data[0]['screentag_position'])){
                              $title = esc_html(__('Support Icon Position', 'majestic-support'));
                              $field = MJTC_formfield::MJTC_select('screentag_position', $screentagposition, majesticsupport::$_data[0]['screentag_position'], esc_html(__('Screen Tag Position', 'majestic-support')), array('class' => 'inputbox', 'data-validation' => 'required'));
                              $description =  esc_html(__('Select a position for your support icon', 'majestic-support'));
                              mjtc_printConfigFieldSingle($title, $field, $description);
                            }
                          ?>
                      </div>
                      <div class="ms_gen_body" id="Offline">
                          <h2><?php echo esc_html(__('Offline', 'majestic-support')) ?></h2>
                          <?php
                            if(isset(majesticsupport::$_data[0]['offline'])){
                             $title = esc_html(__('Offline', 'majestic-support'));
                             $field = MJTC_formfield::MJTC_select('offline', array((object) array('id' => '1', 'text' => esc_html(__('Offline', 'majestic-support'))), (object) array('id' => '2', 'text' => esc_html(__('Online', 'majestic-support')))), majesticsupport::$_data[0]['offline']);
                             $description =  esc_html(__('Set your plugin offline for front end', 'majestic-support'));
                             mjtc_printConfigFieldSingle($title, $field, $description);
                            }

                          if(isset(majesticsupport::$_data[0]['offline_message'])){?>
                          <div class="mjtc-support-configuration-row">
                            <div class="mjtc-support-configuration-title"><?php echo esc_html(__('Offline Message', 'majestic-support'))?></div>
                            <div class="mjtc-support-configuration-value full-width">
                                <?php wp_editor(majesticsupport::$_data[0]['offline_message'], 'offline_message', array('media_buttons' => false)); ?>
                                <div class="mjtc-support-configuration-description">
                                  <?php echo esc_html(__('Set the offline message for your user', 'majestic-support')) ?>
                                </div>
                            </div>
                          </div>
                          <?php } ?>
                      </div>
                        <?php if(in_array('paidsupport', majesticsupport::$_active_addons) && in_array('woocommerce/woocommerce.php', $plugin_array)){ ?>
                            <div class="ms_gen_body" id="PaidSupport">
                                <h2><?php echo esc_html(__('Paid Support', 'majestic-support')) ?></h2>
                                <?php
                                if(isset(majesticsupport::$_data[0]['woocommerce_default_categoryid'])){
                                    $title = esc_html(__('Woocommerce Category', 'majestic-support'));
                                    $field = MJTC_formfield::MJTC_select('woocommerce_default_categoryid', MJTC_includer::MJTC_getModel('configuration')->getWooCommerceCategoryList(), majesticsupport::$_data[0]['woocommerce_default_categoryid'], esc_html(__('Select Category', 'majestic-support')), array('class' => 'inputbox', 'data-validation' => 'required'));
                                    $description =  esc_html(__('Select category to display only products of this category on the WooCommerce shop page.', 'majestic-support'));
                                    mjtc_printConfigFieldSingle($title, $field, $description);
                                }
                                ?>
                            </div>
                        <?php } ?>
                    </div>
           
            <!-- .....TICKET SETTINGS.... -->
            <!-- .....TICKET SETTINGS.... -->
            <div id="ticketsettig" class="msadmin-hide-config">
               <div class="tabs config-tabs" id="tabs">
                  <ul class="ms_tabs">
                      <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#TicketSetting"><?php echo esc_html(__('Ticket Settings', 'majestic-support')) ?></a></li>
                      <li class="tab-link" data-ms-tab="general"><a href="#TicketListing"><?php echo esc_html(__('Ticket Listing', 'majestic-support')); ?></a></li>
                      <li class="tab-link" data-ms-tab="defaultemail"><a href="#TS_visitorTs"><?php echo esc_html(__('Visitor Ticket Setting', 'majestic-support')) ?></a></li>
                  </ul>
              </div>
              <div class="ms_gen_body" id="TicketSetting">
                  <h2><?php echo esc_html(__('Ticket Settings', 'majestic-support')) ?></h2>
                   <?php
                    if(isset(majesticsupport::$_data[0]['prefix_ticketid'])){
                      $title = esc_html(__('Ticket ID Prefix', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_text('prefix_ticketid', majesticsupport::$_data[0]['prefix_ticketid'], array('class' => 'inputbox','maxlength' => '10'));
                      $description =  esc_html(__('Set a prefix for the custom ticket ID', 'majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field,$description);
                    }
                    if(isset(majesticsupport::$_data[0]['ticketid_sequence'])){ ?>
                        <div class="mjtc-support-configuration-row">
                          <div class="mjtc-support-configuration-title"><?php echo esc_html(__('Ticket ID sequence', 'majestic-support'))?></div>
                          <div class="mjtc-support-configuration-value">
                            <?php echo wp_kses(MJTC_formfield::MJTC_select('ticketid_sequence', $sequence, majesticsupport::$_data[0]['ticketid_sequence']), MJTC_ALLOWED_TAGS); ?>
                            <div class="mjtc-support-configuration-description">
                              <?php echo esc_html(__('Set the ticket ID sequentially or randomly, e.g., ', 'majestic-support')) ?><span id="padZeros-prefix" class="mjtc-support-font-bold"></span><span id="padZeros" class="mjtc-support-font-bold"></span><span id="padZeros-suffix" class="mjtc-support-font-bold"></span>
                            </div>
                          </div>
                        </div>
                        <?php
                    }

                    if(isset(majesticsupport::$_data[0]['padding_zeros_ticketid'])){ ?>
                        <div class="mjtc-support-configuration-row Ticketid-sequence-custom">
                          <div class="mjtc-support-configuration-title"><?php echo esc_html(__('Pad Zeros', 'majestic-support'))?></div>
                          <div class="mjtc-support-configuration-value">
                            <?php echo wp_kses(MJTC_formfield::MJTC_select('padding_zeros_ticketid', $padZeros, majesticsupport::$_data[0]['padding_zeros_ticketid']), MJTC_ALLOWED_TAGS); ?>
                            <div class="mjtc-support-configuration-description">
                              <?php echo esc_html(__('To pad an integer with leading zeros to a specific length', 'majestic-support')) ?>
                            </div>
                          </div>
                        </div>
                       <?php
                    }

                    if(isset(majesticsupport::$_data[0]['suffix_ticketid'])){
                        $title = esc_html(__('Ticket ID Suffix', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_text('suffix_ticketid', majesticsupport::$_data[0]['suffix_ticketid'], array('class' => 'inputbox','maxlength' => '7'));
                        $description =  esc_html(__('Set the suffix for your custom ticket ID', 'majestic-support'));
                        mjtc_printConfigFieldSingle($title, $field,$description);
                    }


                    if(isset(majesticsupport::$_data[0]['maximum_tickets'])){
                      $title = esc_html(__('Maximum tickets', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_text('maximum_tickets', majesticsupport::$_data[0]['maximum_tickets'], array('class' => 'inputbox'));
                      $description =  esc_html(__('Maximum ticket per user', 'majestic-support'));
                      $video = '';
                      $videotext = 'Maximum tickets';
                      mjtc_printConfigFieldSingle($title, $field, $description, $video, '', $videotext);
                    }

                    if(isset(majesticsupport::$_data[0]['maximum_open_tickets'])){
                      $title = esc_html(__('Maximum open tickets', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_text('maximum_open_tickets', majesticsupport::$_data[0]['maximum_open_tickets'], array('class' => 'inputbox'));
                      $description =  esc_html(__('Maximum number of tickets opened per user', 'majestic-support'));
                      $video = '';
                      $videotext = 'Maximum open tickets';
                      mjtc_printConfigFieldSingle($title, $field, $description, $video, '', $videotext);
                    }

                    if(isset(majesticsupport::$_data[0]['reopen_ticket_within_days'])){
                      $title = esc_html(__('Reopen ticket within days', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_text('reopen_ticket_within_days', majesticsupport::$_data[0]['reopen_ticket_within_days'], array('class' => 'inputbox'));
                      $description =  esc_html(__('The ticket will reopen within the given number of days', 'majestic-support'));
                      $video = '';
                      $videotext = 'Reopen ticket within days';
                      mjtc_printConfigFieldSingle($title, $field, $description, $video, '', $videotext);
                    }

                    if(in_array('multiform', majesticsupport::$_active_addons)){
                        if(isset(majesticsupport::$_data[0]['show_multiform_popup'])){
                          $title = esc_html(__('Multiforms Popup For New Tickets', 'majestic-support'));
                          $field = MJTC_formfield::MJTC_select('show_multiform_popup', $showhide, majesticsupport::$_data[0]['show_multiform_popup']);
                          $description =  esc_html(__('Show or hide the multiform popup when creating a new ticket. if you hide them, the system will open the default form.','majestic-support'));
                          mjtc_printConfigFieldSingle($title, $field, $description);
                        }
                    }

                    if(in_array('actions',majesticsupport::$_active_addons)){
                        if(isset(majesticsupport::$_data[0]['print_ticket_user'])){
                          $title = esc_html(__('User can print ticket', 'majestic-support'));
                          $field = MJTC_formfield::MJTC_select('print_ticket_user', $yesno, majesticsupport::$_data[0]['print_ticket_user']);
                          $description =  esc_html(__('Can users print a ticket from the ticket detail or not?', 'majestic-support'));
                          mjtc_printConfigFieldSingle($title, $field, $description);
                        }
                    }

                    if(in_array('emailpiping', majesticsupport::$_active_addons)){
                        if(isset(majesticsupport::$_data[0]['reply_to_closed_ticket'])){
                          $title = esc_html(__('Allow Users To Reply via Email On Closed Ticket', 'majestic-support'));
                          $field = MJTC_formfield::MJTC_select('reply_to_closed_ticket', $yesno, majesticsupport::$_data[0]['reply_to_closed_ticket']);
                          $description =  esc_html(__('Select whether users can reply to closed email piping tickets or not','majestic-support'));
                          mjtc_printConfigFieldSingle($title, $field, $description);
                        }
                    }

                    if(isset(majesticsupport::$_data[0]['show_email_on_ticket_reply'])){
                      $title = esc_html(__('Show Admin OR Agent Email On Ticket Reply', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('show_email_on_ticket_reply', $yesno, majesticsupport::$_data[0]['show_email_on_ticket_reply']);
                      $description =  esc_html(__('Select whether users can see the email of administrator or agent on the ticket reply','majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['anonymous_name_on_ticket_reply'])){
                      $title = esc_html(__('Show Anonymous Name On Ticket Reply', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('anonymous_name_on_ticket_reply', $yesno, majesticsupport::$_data[0]['anonymous_name_on_ticket_reply']);
                      $description =  esc_html(__('Select whether users can see the name of administrator or agent on ticket reply','majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['ticket_auto_close'])){
                      $title = esc_html(__('Ticket auto close', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_text('ticket_auto_close', majesticsupport::$_data[0]['ticket_auto_close'], array('class' => 'inputbox'));
                      $description = '<span class="mjtc-support-configuration-sml-txt">'. esc_html(__('Days','majestic-support')).'</span>' . esc_html(__('Ticket auto-close if user does not respond within given days', 'majestic-support'));
                      $video = '2iA8SuNLmMI';
                      $videotext = 'Ticket auto close';
                      mjtc_printConfigFieldSingle($title, $field, $description, $video, '', $videotext);
                    }

                    if(isset(majesticsupport::$_data[0]['show_ticket_delete_button'])){
                      $title = esc_html(__('Show ticket delete button', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('show_ticket_delete_button', $yesno, majesticsupport::$_data[0]['show_ticket_delete_button']);
                      $description =  esc_html(__('Select whether users can see the ticket delete button','majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['ticket_close_reason_type'])){
                      $title = esc_html(__('Ticket close reason Type', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('ticket_close_reason_type', $reasontype, majesticsupport::$_data[0]['ticket_close_reason_type']);
                      $description = esc_html(__('Select whether users can save single or multiple reasons', 'majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['maximum_record_for_smart_reply'])){
                      $title = esc_html(__('Maximum Record For Smart Reply', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('maximum_record_for_smart_reply', $smartreply, majesticsupport::$_data[0]['maximum_record_for_smart_reply']);
                      $description =  esc_html(__('Set the number of replies to show','majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['new_ticket_message'])){?>
                      <div class="mjtc-support-configuration-row">
                        <div class="mjtc-support-configuration-title"><?php echo esc_html(__('New ticket message', 'majestic-support'))?></div>
                        <div class="mjtc-support-configuration-value full-width">
                          <?php wp_editor(majesticsupport::$_data[0]['new_ticket_message'], 'new_ticket_message'); ?>
                          <div class="mjtc-support-configuration-description">
                            <?php echo esc_html(__('This message will show on the new ticket', 'majestic-support')) ?>
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                  ?>
                </div>
                <div class="ms_gen_body" id="TicketListing">
                    <h2><?php echo esc_html(__('Ticket Listing', 'majestic-support')) ?></h2>
                    <?php
                   if(isset(majesticsupport::$_data[0]['tickets_ordering'])){
                      $title = esc_html(__('Ticket listing ordering', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('tickets_ordering', $ticketordering, majesticsupport::$_data[0]['tickets_ordering']);
                      $description =  esc_html(__('Set default ordering for ticket listing', 'majestic-support'));
                      $video = '';
                      $videotext = 'Ticket listing ordering';
                      mjtc_printConfigFieldSingle($title, $field, $description, $video, '', $videotext);
                    }

                    if(isset(majesticsupport::$_data[0]['tickets_sorting'])){
                      $title = esc_html(__('Ticket listing sorting', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('tickets_sorting', $ticketsorting, majesticsupport::$_data[0]['tickets_sorting']);
                      $description =  esc_html(__('Set default sorting for ticket listing', 'majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['show_closedby_on_admin_tickets'])){
                      $title = esc_html(__('Closed info. on admin closed tickets', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('show_closedby_on_admin_tickets', $showhide, majesticsupport::$_data[0]['show_closedby_on_admin_tickets']);
                      $description =  esc_html(__('By enabling this option, an admin can know who closed the ticket and when that ticket closed.','majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(in_array('agent', majesticsupport::$_active_addons)){
                        if(isset(majesticsupport::$_data[0]['show_closedby_on_agent_tickets'])){
                          $title = esc_html(__('Closed info. on agent closed tickets', 'majestic-support'));
                          $field = MJTC_formfield::MJTC_select('show_closedby_on_agent_tickets', $showhide, majesticsupport::$_data[0]['show_closedby_on_agent_tickets']);
                          $description =  esc_html(__('By enabling this option, an agent can know who closed the ticket and when that ticket closed.','majestic-support'));
                          mjtc_printConfigFieldSingle($title, $field, $description);
                        }
                    }

                    if(isset(majesticsupport::$_data[0]['show_closedby_on_user_tickets'])){
                      $title = esc_html(__('Closed info. on user closed tickets', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('show_closedby_on_user_tickets', $showhide, majesticsupport::$_data[0]['show_closedby_on_user_tickets']);
                      $description =  esc_html(__('By enabling this option, a user can know who closed the ticket and when that ticket closed.','majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['show_assignto_on_admin_tickets'])){
                      $title = esc_html(__('Assigned info. on admin tickets', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('show_assignto_on_admin_tickets', $showhide, majesticsupport::$_data[0]['show_assignto_on_admin_tickets']);
                      $description =  esc_html(__('By enabling this option, an admin can know to whom the ticket has been assigned.','majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['show_assignto_on_agent_tickets'])){
                      $title = esc_html(__('Assigned info. on agent tickets', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('show_assignto_on_agent_tickets', $showhide, majesticsupport::$_data[0]['show_assignto_on_agent_tickets']);
                      $description =  esc_html(__('By enabling this option, an agent can know to whom the ticket has been assigned.','majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['show_assignto_on_user_tickets'])){
                      $title = esc_html(__('Assigned info. on user tickets', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('show_assignto_on_user_tickets', $showhide, majesticsupport::$_data[0]['show_assignto_on_user_tickets']);
                      $description =  esc_html(__('By enabling this option, a user can know to whom the ticket has been assigned.','majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }
                  ?>
              </div>
              <div class="ms_gen_body" id="TS_visitorTs">
                    <h2><?php echo esc_html(__('Visitor Ticket Setting', 'majestic-support')); ?></h2>
                  <?php
                    if(isset(majesticsupport::$_data[0]['visitor_can_create_ticket'])){
                      $title = esc_html(__('Visitors can create tickets', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('visitor_can_create_ticket', $yesno, majesticsupport::$_data[0]['visitor_can_create_ticket']);
                      $description =  esc_html(__('Allow visitors to create tickets or not', 'majestic-support'));
                      $video = '9NvBOu_ojMo';
                      $videotext = 'Visitor can create ticket';
                      mjtc_printConfigFieldSingle($title, $field, $description, $video, '',$videotext);
                    }

                    if(isset(majesticsupport::$_data[0]['visitor_message'])){?>
                      <div class="mjtc-support-configuration-row">
                        <div class="mjtc-support-configuration-title"><?php echo esc_html(__('Visitor ticket creation message', 'majestic-support'))?></div>
                        <div class="mjtc-support-configuration-value full-width">
                          <?php wp_editor(majesticsupport::$_data[0]['visitor_message'], 'visitor_message') ?>
                          <div class="mjtc-support-configuration-description">
                            <?php echo esc_html(__('This text will appear whenever a visitor creates a ticket', 'majestic-support')) ?>
                          </div>
                        </div>
                      </div>
                  <?php } ?>
              </div>
            </div>

            <!-- .....SYSTEM EMAILS..... -->
            <!-- .....SYSTEM EMAILS..... -->
            <div id="defaultemail" class="msadmin-hide-config">
               <div class="tabs config-tabs" id="tabs">
                  <ul class="ms_tabs">
                      <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#SystemEmail"><?php echo esc_html(__('System Emails', 'majestic-support')) ?></a></li>
                  </ul>
              </div>
              <div class="ms_gen_body" id="SystemEmail">
                  <h2><?php echo esc_html(__('System Emails', 'majestic-support')) ?></h2>
                  <?php

                   if(isset(majesticsupport::$_data[0]['default_alert_email'])){
                      $title = esc_html(__('Default alert email', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('default_alert_email', majesticsupport::$_data[1], majesticsupport::$_data[0]['default_alert_email']);
                      $description = esc_html(__('If the ticket department email is not selected, then this email is used to send emails', 'majestic-support'));
                      $video = '';
                      $videotext = 'Default alert email';
                      $actionbtn = 'Add New Email';
                      mjtc_printConfigFieldSingle($title, $field, $description, $video, '', $videotext, $actionbtn);
                    }

                    if(isset(majesticsupport::$_data[0]['default_admin_email'])){
                        $title = esc_html(__('Default admin email', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('default_admin_email', majesticsupport::$_data[1], majesticsupport::$_data[0]['default_admin_email']);
                        $description = esc_html(__('Admin email address to receive emails', 'majestic-support'));
                        $video = '';
                        $videotext = 'Default admin email';
                        $actionbtn = 'Add New Email';
                        mjtc_printConfigFieldSingle($title, $field, $description, $video, '', $videotext, $actionbtn);
                    }

                    if(isset(majesticsupport::$_data[0]['department_email_on_ticket_create'])){
                        $title = esc_html(__('Department Email', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('department_email_on_ticket_create', $yesno, majesticsupport::$_data[0]['department_email_on_ticket_create']);
                        $description =  esc_html(__('Send email to all departments on ticket create', 'majestic-support'));
                        mjtc_printConfigFieldSingle($title, $field, $description);
                    }
                  ?>
              </div>
            </div>
            <!-- .....EMAIL Settings..... -->
            <div id="mailsetting" class="msadmin-hide-config">
              <div class="tabs config-tabs" id="tabs">
                  <ul class="ms_tabs">
                      <?php if(isset(majesticsupport::$_data[0]['banemail_mail_to_admin'])){ ?>
                      <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#BanEmailNewTicket"><?php echo esc_html(__('Email Regarding Banned Users', 'majestic-support')) ?></a></li>
                    <?php } ?>
                      <li class="tab-link" data-ms-tab="ticketsettig"><a href="#TicketOperationsEmailSetting"><?php echo esc_html(__('Ticket Operations Email Setting', 'majestic-support')) ?></a></li>
                  </ul>
              </div>
              <?php if(isset(majesticsupport::$_data[0]['banemail_mail_to_admin'])){ ?>
                <div class="ms_gen_body" id="BanEmailNewTicket">
                    <h2><?php echo esc_html(__('Email Regarding Banned Users', 'majestic-support')) ?></h2>
                    <?php
                      $title = esc_html(__('Mail to admin', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('banemail_mail_to_admin', $enableddisabled, majesticsupport::$_data[0]['banemail_mail_to_admin']);;
                      $description = esc_html(__('Send an email to the admin when a banned email tries to create a ticket', 'majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    ?>
                </div>
              <?php } ?>
              <div class="ms_gen_body" id="TicketOperationsEmailSetting">
                  <h2><?php echo esc_html(__('Ticket Operations Email Setting', 'majestic-support')) ?></h2>
                  <div class="mjtc-support-configuration-row-mail">
                    <div class="mjtc-support-conf-text-sub"><?php echo esc_html(__('Admin', 'majestic-support')) ?></div>
                    <?php if(in_array('agent', majesticsupport::$_active_addons)){ ?>
                      <div class="mjtc-support-conf-text-sub"><?php echo esc_html(__('Agent', 'majestic-support')) ?></div>
                    <?php }else{ ?>
                      <div class="mjtc-support-conf-text-sub">------</div>
                    <?php } ?>
                    <div class="mjtc-support-conf-text-sub"><?php echo esc_html(__('User', 'majestic-support')) ?></div>
                  </div>
                  <?php

                  if(isset(majesticsupport::$_data[0]['new_ticket_mail_to_admin'])){
                    $title = esc_html(__('New ticket', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('new_ticket_mail_to_admin', $enableddisabled, majesticsupport::$_data[0]['new_ticket_mail_to_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('new_ticket_mail_to_staff_members', $enableddisabled, majesticsupport::$_data[0]['new_ticket_mail_to_staff_members']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_reassign_admin'])){
                    $title = esc_html(__('Ticket reassign', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('ticket_reassign_admin', $enableddisabled, majesticsupport::$_data[0]['ticket_reassign_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('ticket_reassign_staff', $enableddisabled, majesticsupport::$_data[0]['ticket_reassign_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('ticket_reassign_user', $enableddisabled, majesticsupport::$_data[0]['ticket_reassign_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_close_admin'])){
                    $title = esc_html(__('Ticket close', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('ticket_close_admin', $enableddisabled, majesticsupport::$_data[0]['ticket_close_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('ticket_close_staff', $enableddisabled, majesticsupport::$_data[0]['ticket_close_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('ticket_close_user', $enableddisabled, majesticsupport::$_data[0]['ticket_close_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_delete_admin'])){
                    $title = esc_html(__('Ticket delete', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('ticket_delete_admin', $enableddisabled, majesticsupport::$_data[0]['ticket_delete_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('ticket_delete_staff', $enableddisabled, majesticsupport::$_data[0]['ticket_delete_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('ticket_delete_user', $enableddisabled, majesticsupport::$_data[0]['ticket_delete_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_mark_overdue_admin'])){
                    $title = esc_html(__('Ticket marked as overdue', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('ticket_mark_overdue_admin', $enableddisabled, majesticsupport::$_data[0]['ticket_mark_overdue_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('ticket_mark_overdue_staff', $enableddisabled, majesticsupport::$_data[0]['ticket_mark_overdue_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('ticket_mark_overdue_user', $enableddisabled, majesticsupport::$_data[0]['ticket_mark_overdue_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_ban_email_admin'])){
                    $title = esc_html(__('Ticket ban email', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('ticket_ban_email_admin', $enableddisabled, majesticsupport::$_data[0]['ticket_ban_email_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('ticket_ban_email_staff', $enableddisabled, majesticsupport::$_data[0]['ticket_ban_email_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('ticket_ban_email_user', $enableddisabled, majesticsupport::$_data[0]['ticket_ban_email_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_department_transfer_admin'])){
                    $title = esc_html(__('Ticket department transfer', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('ticket_department_transfer_admin', $enableddisabled, majesticsupport::$_data[0]['ticket_department_transfer_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('ticket_department_transfer_staff', $enableddisabled, majesticsupport::$_data[0]['ticket_department_transfer_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('ticket_department_transfer_user', $enableddisabled, majesticsupport::$_data[0]['ticket_department_transfer_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_reply_ticket_user_admin'])){
                    $title = esc_html(__('Ticket reply User', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('ticket_reply_ticket_user_admin', $enableddisabled, majesticsupport::$_data[0]['ticket_reply_ticket_user_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('ticket_reply_ticket_user_staff', $enableddisabled, majesticsupport::$_data[0]['ticket_reply_ticket_user_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('ticket_reply_ticket_user_user', $enableddisabled, majesticsupport::$_data[0]['ticket_reply_ticket_user_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_response_to_staff_admin'])){
                    $title = esc_html(__('Ticket Response Agent', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('ticket_response_to_staff_admin', $enableddisabled, majesticsupport::$_data[0]['ticket_response_to_staff_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('ticket_response_to_staff_staff', $enableddisabled, majesticsupport::$_data[0]['ticket_response_to_staff_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('ticket_response_to_staff_user', $enableddisabled, majesticsupport::$_data[0]['ticket_response_to_staff_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticker_ban_eamil_and_close_ticktet_admin'])){
                    $title = esc_html(__('Ticket ban email and close ticket', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('ticker_ban_eamil_and_close_ticktet_admin', $enableddisabled, majesticsupport::$_data[0]['ticker_ban_eamil_and_close_ticktet_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('ticker_ban_eamil_and_close_ticktet_staff', $enableddisabled, majesticsupport::$_data[0]['ticker_ban_eamil_and_close_ticktet_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('ticker_ban_eamil_and_close_ticktet_user', $enableddisabled, majesticsupport::$_data[0]['ticker_ban_eamil_and_close_ticktet_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['unban_email_admin'])){
                    $title = esc_html(__('Ticket unban email', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('unban_email_admin', $enableddisabled, majesticsupport::$_data[0]['unban_email_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('unban_email_staff', $enableddisabled, majesticsupport::$_data[0]['unban_email_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('unban_email_user', $enableddisabled, majesticsupport::$_data[0]['unban_email_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_lock_admin'])){
                    $title = esc_html(__('Ticket lock', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('ticket_lock_admin', $enableddisabled, majesticsupport::$_data[0]['ticket_lock_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('ticket_lock_staff', $enableddisabled, majesticsupport::$_data[0]['ticket_lock_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('ticket_lock_user', $enableddisabled, majesticsupport::$_data[0]['ticket_lock_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_unlock_admin'])){
                    $title = esc_html(__('Ticket unlock', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('ticket_unlock_admin', $enableddisabled, majesticsupport::$_data[0]['ticket_unlock_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('ticket_unlock_staff', $enableddisabled, majesticsupport::$_data[0]['ticket_unlock_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('ticket_unlock_user', $enableddisabled, majesticsupport::$_data[0]['ticket_unlock_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_priority_admin'])){
                    $title = esc_html(__('Ticket Change Priority', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('ticket_priority_admin', $enableddisabled, majesticsupport::$_data[0]['ticket_priority_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('ticket_priority_staff', $enableddisabled, majesticsupport::$_data[0]['ticket_priority_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('ticket_priority_user', $enableddisabled, majesticsupport::$_data[0]['ticket_priority_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_mark_progress_admin'])){
                    $title = esc_html(__('Mark Ticket In Progress', 'majestic-support'));
                    $field1 = MJTC_formfield::MJTC_select('ticket_mark_progress_admin', $enableddisabled, majesticsupport::$_data[0]['ticket_mark_progress_admin']);
                    if(in_array('agent', majesticsupport::$_active_addons)){
                      $field2 = MJTC_formfield::MJTC_select('ticket_mark_progress_staff', $enableddisabled, majesticsupport::$_data[0]['ticket_mark_progress_staff']);
                    }else{
                      $field2 = '<span class="mjtc-support-configuration-no-rec">'.'------'.'</span>';
                    }
                    $field3 = MJTC_formfield::MJTC_select('ticket_mark_progress_user', $enableddisabled, majesticsupport::$_data[0]['ticket_mark_progress_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_reply_closed_ticket_user'])){
                    $title = esc_html(__('Reply To A Closed Ticket By Email', 'majestic-support'));
                    $field1 = '<span class="mjtc-support-configuration-no-rec">'.'----'.'</span>';
                    $field2 = '<span class="mjtc-support-configuration-no-rec">'.'----'.'</span>';
                    $field3 =  MJTC_formfield::MJTC_select('ticket_reply_closed_ticket_user', $enableddisabled, majesticsupport::$_data[0]['ticket_reply_closed_ticket_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                  if(isset(majesticsupport::$_data[0]['ticket_feedback_user'])){
                    $title = esc_html(__('Send Feedback Email To User', 'majestic-support'));
                    $field1 = '<span class="mjtc-support-configuration-no-rec">'.'----'.'</span>';
                    $field2 = '<span class="mjtc-support-configuration-no-rec">'.'----'.'</span>';
                    $field3 = MJTC_formfield::MJTC_select('ticket_feedback_user', $enableddisabled, majesticsupport::$_data[0]['ticket_feedback_user']);
                    mjtc_printConfigFieldMulti($title, $field1, $field2, $field3);
                  }

                ?>
              </div>
            </div>
            <!-- .....AGENT MENUS..... -->
            <!-- .....AGENT MENUS..... -->
            <div id="staffmenusetting" class="msadmin-hide-config">
              <?php if(in_array('agent', majesticsupport::$_active_addons)){ ?>
                <div class="tabs config-tabs" id="tabs">
                  <ul class="ms_tabs">
                      <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#DashboardLinks"><?php echo esc_html(__('Dashboard Links', 'majestic-support')) ?></a></li>
                      <li class="tab-link" data-ms-tab="ticketsettig"><a href="#TopMenuLinks"><?php echo esc_html(__('Top Menu Links', 'majestic-support')) ?></a></li>
                  </ul>
                </div>
                <div class="ms_gen_body" id="DashboardLinks">
                  <h2><?php echo esc_html(__('Dashboard Links', 'majestic-support')) ?></h2>
                  <?php
                    if(isset(majesticsupport::$_data[0]['cplink_openticket_staff'])){
                        $title = esc_html(__('Open Ticket', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_openticket_staff', $showhide, majesticsupport::$_data[0]['cplink_openticket_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_myticket_staff'])){
                        $title =  esc_html(__('My Tickets', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_myticket_staff', $showhide, majesticsupport::$_data[0]['cplink_myticket_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_ticketclosereasons_staff'])){
                        $title =  esc_html(__('Ticket Close Reasons', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_ticketclosereasons_staff', $showhide, majesticsupport::$_data[0]['cplink_ticketclosereasons_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_smartreply_staff'])){
                        $title =  esc_html(__('Smart Reply', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_smartreply_staff', $showhide, majesticsupport::$_data[0]['cplink_smartreply_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_roles_staff'])){
                        $title =  esc_html(__('Roles', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_roles_staff', $showhide, majesticsupport::$_data[0]['cplink_roles_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_staff_staff'])){
                        $title =  esc_html(__('Agent', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_staff_staff', $showhide, majesticsupport::$_data[0]['cplink_staff_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_department_staff'])){
                        $title =  esc_html(__('Department', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_department_staff', $showhide, majesticsupport::$_data[0]['cplink_department_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_category_staff'])){
                        $title =  esc_html(__('Category', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_category_staff', $showhide, majesticsupport::$_data[0]['cplink_category_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_kbarticle_staff'])){
                        $title =  esc_html(__('Knowledge Base', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_kbarticle_staff', $showhide, majesticsupport::$_data[0]['cplink_kbarticle_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_download_staff'])){
                        $title =  esc_html(__('Download', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_download_staff', $showhide, majesticsupport::$_data[0]['cplink_download_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_announcement_staff'])){
                        $title =  esc_html(__('Announcement', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_announcement_staff', $showhide, majesticsupport::$_data[0]['cplink_announcement_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_faq_staff'])){
                        $title =  esc_html(__("FAQs", 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_faq_staff', $showhide, majesticsupport::$_data[0]['cplink_faq_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_mail_staff'])){
                        $title = esc_html(__('Mail', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_mail_staff', $showhide, majesticsupport::$_data[0]['cplink_mail_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_banemail_staff'])){
                        $title = esc_html(__('Banned Emails', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_banemail_staff', $showhide, majesticsupport::$_data[0]['cplink_banemail_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_myprofile_staff'])){
                        $title =  esc_html(__('My Profile', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_myprofile_staff', $showhide, majesticsupport::$_data[0]['cplink_myprofile_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_staff_report_staff'])){
                        $title = esc_html(__('Agent Reports', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_staff_report_staff', $showhide, majesticsupport::$_data[0]['cplink_staff_report_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_department_report_staff'])){
                        $title =  esc_html(__('Department reports', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_department_report_staff', $showhide, majesticsupport::$_data[0]['cplink_department_report_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_feedback_staff'])){
                        $title = esc_html(__('Feedbacks', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_feedback_staff', $showhide, majesticsupport::$_data[0]['cplink_feedback_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_login_logout_staff'])){
                        $title =  esc_html(__('Login/Logout Button', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_login_logout_staff', $showhide, majesticsupport::$_data[0]['cplink_login_logout_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_totalcount_staff'])){
                        $title = esc_html(__('Ticket Total Count', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_totalcount_staff', $showhide, majesticsupport::$_data[0]['cplink_totalcount_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    /*if(isset(majesticsupport::$_data[0]['cplink_ticketstats_staff'])){
                        $title =  esc_html(__('Ticket Statistics', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_ticketstats_staff', $showhide, majesticsupport::$_data[0]['cplink_ticketstats_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }*/
                    if(isset(majesticsupport::$_data[0]['cplink_latesttickets_staff'])){
                        $title = esc_html(__('Latest Tickets', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_latesttickets_staff', $showhide, majesticsupport::$_data[0]['cplink_latesttickets_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_helptopic_agent'])){
                        $title = esc_html(__('Help Topic', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_helptopic_agent', $showhide, majesticsupport::$_data[0]['cplink_helptopic_agent']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_cannedresponses_agent'])){
                        $title = esc_html(__('Premade Response', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_cannedresponses_agent', $showhide, majesticsupport::$_data[0]['cplink_cannedresponses_agent']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_erasedata_staff'])){
                        $title = esc_html(__('Erase Agent Data', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_erasedata_staff', $showhide, majesticsupport::$_data[0]['cplink_erasedata_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_export_ticket_staff'])){
                        $title = esc_html(__('Export Ticket', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_export_ticket_staff', $showhide, majesticsupport::$_data[0]['cplink_export_ticket_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_latestdownloads_staff'])){
                        $title = esc_html(__('Latest Downloads', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_latestdownloads_staff', $showhide, majesticsupport::$_data[0]['cplink_latestdownloads_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_latestannouncements_staff'])){
                        $title = esc_html(__('Latest Announcements', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_latestannouncements_staff', $showhide, majesticsupport::$_data[0]['cplink_latestannouncements_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_latestkb_staff'])){
                        $title = esc_html(__('Latest Knowledge Base', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_latestkb_staff', $showhide, majesticsupport::$_data[0]['cplink_latestkb_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_latestfaqs_staff'])){
                        $title = esc_html(__('Latest FAQs', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_latestfaqs_staff', $showhide, majesticsupport::$_data[0]['cplink_latestfaqs_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    } ?>
                </div>

                <div class="ms_gen_body" id="TopMenuLinks">
                    <h2>
                        <?php echo esc_html(__('Top Menu Links', 'majestic-support')) ?>
                    </h2>
                    <?php
                    if(isset(majesticsupport::$_data[0]['tplink_home_staff'])){
                        $title = esc_html(__('Home', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('tplink_home_staff', $showhide, majesticsupport::$_data[0]['tplink_home_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }

                    if(isset(majesticsupport::$_data[0]['tplink_tickets_staff'])){
                        $title = esc_html(__('Tickets', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('tplink_tickets_staff', $showhide, majesticsupport::$_data[0]['tplink_tickets_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }

                    if(isset(majesticsupport::$_data[0]['tplink_openticket_staff'])){
                        $title = esc_html(__('Open Ticket', 'majestic-support'));
                        $field =  MJTC_formfield::MJTC_select('tplink_openticket_staff', $showhide, majesticsupport::$_data[0]['tplink_openticket_staff']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }

                    if(isset(majesticsupport::$_data[0]['tplink_login_logout_staff'])){
                      $title = esc_html(__('Login/Logout Button', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('tplink_login_logout_staff', $showhide, majesticsupport::$_data[0]['tplink_login_logout_staff']);
                      mjtc_printConfigFieldSingle($title, $field);
                    }
                  ?>
                </div>
              <?php } ?>
            </div>
            <!-- .....USER MENUS..... -->
            <!-- .....USER MENUS..... -->
            <div id="usermenusetting" class="msadmin-hide-config">
               <div class="tabs config-tabs" id="tabs">
                  <ul class="ms_tabs">
                      <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#DashboardLinksUser"><?php echo esc_html(__('Dashboard Links', 'majestic-support')) ?></a></li>
                      <li class="tab-link" data-ms-tab="ticketsettig"><a href="#TopMenuLinksUser"><?php echo esc_html(__('Top Menu Links', 'majestic-support')) ?></a></li>
                  </ul>
              </div>
              <div class="ms_gen_body" id="DashboardLinksUser">
                    <h2><?php echo esc_html(__('Dashboard Links', 'majestic-support')) ?></h2>
                    <?php
                    if(isset(majesticsupport::$_data[0]['cplink_openticket_user'])){
                        $title = esc_html(__('Open Ticket', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_openticket_user', $showhide, majesticsupport::$_data[0]['cplink_openticket_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_myticket_user'])){
                        $title = esc_html(__('My Tickets', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_myticket_user', $showhide, majesticsupport::$_data[0]['cplink_myticket_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_checkticketstatus_user'])){
                        $title = esc_html(__('Check Ticket Status', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_checkticketstatus_user', $showhide, majesticsupport::$_data[0]['cplink_checkticketstatus_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_downloads_user'])){
                        $title = esc_html(__('Downloads', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_downloads_user', $showhide, majesticsupport::$_data[0]['cplink_downloads_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_announcements_user'])){
                        $title = esc_html(__('Announcements', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_announcements_user', $showhide, majesticsupport::$_data[0]['cplink_announcements_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_faqs_user'])){
                        $title = esc_html(__("FAQs", 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_faqs_user', $showhide, majesticsupport::$_data[0]['cplink_faqs_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_knowledgebase_user'])){
                        $title = esc_html(__('Knowledge Base', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_knowledgebase_user', $showhide, majesticsupport::$_data[0]['cplink_knowledgebase_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_login_logout_user'])){
                        $title = esc_html(__('Login/Logout Button', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_login_logout_user', $showhide, majesticsupport::$_data[0]['cplink_login_logout_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }

                    if(isset(majesticsupport::$_data[0]['cplink_register_user'])){
                        $title = esc_html(__('Registration', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_register_user', $showhide, majesticsupport::$_data[0]['cplink_register_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_erasedata_user'])){
                        $title = esc_html(__('Erase User Data', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_erasedata_user', $showhide, majesticsupport::$_data[0]['cplink_erasedata_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_latesttickets_user'])){
                        $title = esc_html(__('Latest Tickets', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_latesttickets_user', $showhide, majesticsupport::$_data[0]['cplink_latesttickets_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_totalcount_user'])){
                        $title = esc_html(__('Ticket Total Count', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_totalcount_user', $showhide, majesticsupport::$_data[0]['cplink_totalcount_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_latestdownloads_user'])){
                        $title = esc_html(__('Latest Downloads', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_latestdownloads_user', $showhide, majesticsupport::$_data[0]['cplink_latestdownloads_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_latestannouncements_user'])){
                        $title = esc_html(__('Latest Announcements', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_latestannouncements_user', $showhide, majesticsupport::$_data[0]['cplink_latestannouncements_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_latestkb_user'])){
                        $title = esc_html(__('Latest Knowledge Base', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_latestkb_user', $showhide, majesticsupport::$_data[0]['cplink_latestkb_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                    if(isset(majesticsupport::$_data[0]['cplink_latestfaqs_user'])){
                        $title = esc_html(__('Latest FAQs', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('cplink_latestfaqs_user', $showhide, majesticsupport::$_data[0]['cplink_latestfaqs_user']);
                        mjtc_printConfigFieldSingle($title, $field);
                    }
                  ?>
              </div>
              <div class="ms_gen_body" id="TopMenuLinksUser">
                  <h2><?php echo esc_html(__('Top Menu Links', 'majestic-support')) ?></h2>
                  <?php
                    if(isset(majesticsupport::$_data[0]['tplink_home_user'])){
                      $title = esc_html(__('Home', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('tplink_home_user', $showhide, majesticsupport::$_data[0]['tplink_home_user']);
                      mjtc_printConfigFieldSingle($title, $field);
                    }

                    if(isset(majesticsupport::$_data[0]['tplink_tickets_user'])){
                      $title = esc_html(__('Tickets', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('tplink_tickets_user', $showhide, majesticsupport::$_data[0]['tplink_tickets_user']);
                      mjtc_printConfigFieldSingle($title, $field);
                    }

                    if(isset(majesticsupport::$_data[0]['tplink_openticket_user'])){
                      $title = esc_html(__('Open Ticket', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('tplink_openticket_user', $showhide, majesticsupport::$_data[0]['tplink_openticket_user']);
                      mjtc_printConfigFieldSingle($title, $field);
                    }

                    if(isset(majesticsupport::$_data[0]['tplink_login_logout_user'])){
                      $title = esc_html(__('Login/Logout Button', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('tplink_login_logout_user', $showhide, majesticsupport::$_data[0]['tplink_login_logout_user']);
                      mjtc_printConfigFieldSingle($title, $field);
                    }
                  ?>
              </div>
            </div>
            <!-- .....feedback..... -->
            <div id="feedback" class="msadmin-hide-config">
              <?php if(in_array('feedback', majesticsupport::$_active_addons)){ ?>
                 <div class="tabs config-tabs" id="tabs">
                  <ul class="ms_tabs">
                      <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#FeedbackSettings"><?php echo esc_html(__('Feedback Settings', 'majestic-support')) ?></a></li>
                  </ul>
                </div>
                <div class="ms_gen_body" id="FeedbackSettings">
                  <h2><?php echo esc_html(__('Feedback Settings', 'majestic-support')) ?></h2>
                  <?php
                    if(isset(majesticsupport::$_data[0]['feedback_email_delay_type'])){
                      $title = esc_html(__('Feedback Email Delay Type', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('feedback_email_delay_type',  array((object) array('id' => '1', 'text' => esc_html(__('Days', 'majestic-support'))), (object) array('id' => '2', 'text' => esc_html(__('Hours', 'majestic-support')))), majesticsupport::$_data[0]['feedback_email_delay_type']);
                      $description = esc_html(__('Select delay type for feedback email', 'majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['feedback_email_delay'])){
                      $title = esc_html(__('Feedback Email Delay', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_text('feedback_email_delay', majesticsupport::$_data[0]['feedback_email_delay'], array('class' => 'inputbox'));
                      $description = esc_html(__('Set the number of days or hours to send a feedback email after a ticket is closed', 'majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['feedback_thanks_message'])){ ?>
                      <div class="mjtc-support-configuration-row">
                        <div class="mjtc-support-configuration-title"><?php echo esc_html(__('Success message after submitting feedback', 'majestic-support'))?></div>
                        <div class="mjtc-support-configuration-value full-width">
                          <?php wp_editor(majesticsupport::$_data[0]['feedback_thanks_message'], 'feedback_thanks_message') ?>
                          <div class="mjtc-support-configuration-description">
                            <?php echo esc_html(__('This text will appear whenever anyone submits feedback', 'majestic-support')) ?>
                          </div>
                        </div>
                      </div>
                    <?php } ?>

                </div>
              <?php } ?>
            </div>
            <!-- .....Social Login..... -->
            <div id="sociallogin" class="msadmin-hide-config">
              <?php if (in_array('sociallogin', majesticsupport::$_active_addons)) { ?>
                 <div class="tabs config-tabs" id="tabs">
                  <ul class="ms_tabs">
                      <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#Facebook"><?php echo esc_html(__('Facebook', 'majestic-support')) ?></a></li>
                      <li class="tab-link" data-ms-tab="general"><a href="#Linkedin"><?php echo esc_html(__('Linkedin', 'majestic-support')) ?></a></li>
                  </ul>
                </div>
                <div class="ms_gen_body" id="Facebook">
                    <h2><?php echo esc_html(__('Facebook', 'majestic-support')); ?></h2>
                    <?php
                      $loginwithfacebook = "";
                      $apikeyfacebook = "";
                      $clientsecretfacebook = "";
                      if (isset(majesticsupport::$_data[0]['loginwithfacebook'])) {
                          $loginwithfacebook = majesticsupport::$_data[0]['loginwithfacebook'];
                      }
                      $title = esc_html(__('Login with facebook', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('loginwithfacebook', array((object)array('id' => '1', 'text' => esc_html(__('Yes', 'majestic-support'))), (object)array('id' => '2', 'text' => esc_html(__('No', 'majestic-support')))), $loginwithfacebook);
                      $description = esc_html(__('Facebook user can log in to Majestic Support', 'majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                      $title = esc_html(__('Secret', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_text('clientsecretfacebook', majesticsupport::$_data[0]['clientsecretfacebook'], array('class' => 'inputbox'));
                      $description = esc_html(__('secret key', 'majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                      $title = esc_html(__('API Key', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_text('apikeyfacebook', majesticsupport::$_data[0]['apikeyfacebook'], array('class' => 'inputbox'));
                      $description = esc_html(__('The Facebook app requires an API key', 'majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    ?>
                </div>
                <div class="ms_gen_body" id="Linkedin">
                    <h2><?php echo esc_html(__('Linkedin', 'majestic-support')); ?></h2>
                    <?php
                      $loginwithlinkedin = "";
                      $apikeylinkedin = "";
                      $clientsecretlinkedin = "";
                      if (isset(majesticsupport::$_data[0]['loginwithlinkedin'])) {
                          $loginwithlinkedin = majesticsupport::$_data[0]['loginwithlinkedin'];
                      }
                      $title = esc_html(__('Login with linkedin', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('loginwithlinkedin', array((object)array('id' => '1', 'text' => esc_html(__('Yes', 'majestic-support'))), (object)array('id' => '2', 'text' => esc_html(__('No', 'majestic-support')))), $loginwithlinkedin);
                      $description = esc_html(__('Linkedin users can log in to Majestic Support', 'majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);

                      if (isset(majesticsupport::$_data[0]['apikeylinkedin'])) {
                          $loginwithlinkedin = majesticsupport::$_data[0]['apikeylinkedin'];
                      }
                      $title = esc_html(__('Secret', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_text('clientsecretlinkedin',  majesticsupport::$_data[0]['clientsecretlinkedin'], array('class' => 'inputbox'));
                      $description = esc_html(__('secret key', 'majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);

                      if (isset(majesticsupport::$_data[0]['clientsecretlinkedin'])) {
                          $clientsecretlinkedin = majesticsupport::$_data[0]['clientsecretlinkedin'];
                      }
                      $title = esc_html(__('API Key', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_text('apikeylinkedin',majesticsupport::$_data[0]['apikeylinkedin'], array('class' => 'inputbox'));
                      $description = esc_html(__('The LinkedIn app requires an API key', 'majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                  ?>
                </div>
              <?php } ?>
            </div>
            <!-- .....Email Piping..... -->
            <div id="ticketviaemail" class="msadmin-hide-config">
              <?php if (in_array('emailpiping', majesticsupport::$_active_addons)) { ?>
                <div class="tabs config-tabs" id="tabs">
                  <ul class="ms_tabs">
                      <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#EmailPiping"><?php echo esc_html(__('Email Piping', 'majestic-support')) ?></a></li>
                  </ul>
                </div>
                <div class="ms_gen_body" id="EmailPiping">
                    <h2><?php echo esc_html(__('Email Piping', 'majestic-support')) ?></h2>
                    <?php
                      if (isset(majesticsupport::$_data[0]['read_utf_ticket_via_email'])) {
                        $title = esc_html(__('UTF Auto Switch', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('read_utf_ticket_via_email',$yesno, majesticsupport::$_data[0]['read_utf_ticket_via_email']);
                        mjtc_printConfigFieldSingle($title, $field);
                      }
                      if(isset(majesticsupport::$_data[0]['create_user_via_email'])){
                        $title = esc_html(__('Create User via email', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('create_user_via_email',$yesno, majesticsupport::$_data[0]['create_user_via_email']);
                        mjtc_printConfigFieldSingle($title, $field);
                      }
                    ?>
                </div>
              <?php } ?>
            </div>
            <!-- .....Firebase Notifications..... -->
            <div id="pushnotification" class="msadmin-hide-config">
              <?php if(in_array('notification', majesticsupport::$_active_addons)){ ?>
                <div class="tabs config-tabs" id="tabs">
                  <ul class="ms_tabs">
                      <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#FirebaseNotifications"><?php echo esc_html(__('Firebase Notifications', 'majestic-support')) ?></a></li>
                  </ul>
                </div>
              <div class="ms_gen_body" id="FirebaseNotifications">
                  <h2><?php echo esc_html(__('Firebase Notifications', 'majestic-support')) ?></h2>
                  <?php
                    if(!file_exists(WP_PLUGIN_DIR.'/majestic-support-notification/majestic-support-notification.php')){ ?>
                      <div class="ms_error_messages" style="color: #000; margin-bottom: 15px;">
                        <span style="color: #000;" class="ms_msg" id="ms_error_message"><?php echo esc_html(__("Majestic Support Desktop Notifications plugin is not installed. Please install the plugin to enable desktop notifications",'majestic-support'));?><a title="<?php echo esc_attr(__("Click here to insert Install.",'majestic-support')); ?>" href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_premiumplugin")); ?>"><?php echo esc_html(__("Click here to insert Install.",'majestic-support')); ?></a></span>
                      </div>
                    <?php
                    }elseif(!class_exists('MJTC_Notification')){ ?>
                      <div class="ms_error_messages" style="color: #000; margin-bottom: 15px;">
                          <span style="color: #000;" class="ms_msg" id="ms_success_message"><?php echo esc_html(__("Majestic Support Desktop Notifications plugin is not active.",'majestic-support'));?></span>
                      </div>
                    <?php
                    } ?>
                    <div class="ms_error_messages" style="color: #000; margin-bottom: 15px;">
                      <span style="color: #000;" class="ms_warning_msg" id="ms_error_message"><?php echo esc_html(__("Find and add the Firebase API keys.",'majestic-support'));?><a title="<?php echo esc_attr(__("Click here to get Firebase API keys.",'majestic-support')); ?>" href="https://console.firebase.google.com" target="_blank"><?php echo esc_html(__("Click here to get Firebase API keys.",'majestic-support')); ?></a></span>
                    </div>
                    <?php
                      if(isset(majesticsupport::$_data[0]['apiKey_firebase'])){
                        $title = esc_html(__("User's API Key", 'majestic-support'));
                        $field = MJTC_formfield::MJTC_text('apiKey_firebase', majesticsupport::$_data[0]['apiKey_firebase'], array('class' => 'inputbox'));
                        $description =  esc_html(__('Firebase API key for the front user', 'majestic-support'));
                        mjtc_printConfigFieldSingle($title, $field, $description);
                      }

                      if(isset(majesticsupport::$_data[0]['authDomain_firebase'])){
                        $title = esc_html(__('Auth Domain', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_text('authDomain_firebase', majesticsupport::$_data[0]['authDomain_firebase'], array('class' => 'inputbox'));
                        $description =  esc_html(__('Firebase Auth Domain', 'majestic-support'));
                        mjtc_printConfigFieldSingle($title, $field, $description);
                      }

                      if(isset(majesticsupport::$_data[0]['databaseURL_firebase'])){
                        $title = esc_html(__('Database Url', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_text('databaseURL_firebase', majesticsupport::$_data[0]['databaseURL_firebase'], array('class' => 'inputbox'));
                        $description =  esc_html(__('Firebase Database URL', 'majestic-support'));
                        mjtc_printConfigFieldSingle($title, $field, $description);
                      }

                      if(isset(majesticsupport::$_data[0]['projectId_firebase'])){
                        $title = esc_html(__('Project ID', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_text('projectId_firebase', majesticsupport::$_data[0]['projectId_firebase'], array('class' => 'inputbox'));
                        $description =  esc_html(__('Firebase Project ID', 'majestic-support'));
                        mjtc_printConfigFieldSingle($title, $field, $description);
                      }

                      if(isset(majesticsupport::$_data[0]['storageBucket_firebase'])){
                        $title = esc_html(__('Bucket Storage', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_text('storageBucket_firebase', majesticsupport::$_data[0]['storageBucket_firebase'], array('class' => 'inputbox'));
                        $description =  esc_html(__('Firebase Bucket Storage', 'majestic-support'));
                        mjtc_printConfigFieldSingle($title, $field, $description);
                      }

                      if(isset(majesticsupport::$_data[0]['messagingSenderId_firebase'])){
                        $title = esc_html(__('Message Sender ID', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_text('messagingSenderId_firebase', majesticsupport::$_data[0]['messagingSenderId_firebase'], array('class' => 'inputbox'));
                        $description =  esc_html(__('Firebase Message Sender ID', 'majestic-support'));
                        mjtc_printConfigFieldSingle($title, $field, $description);
                      }

                      if(isset(majesticsupport::$_data[0]['server_key_firebase'])){
                        $title = esc_html(__('Private Server Key', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_text('server_key_firebase', majesticsupport::$_data[0]['server_key_firebase'], array('class' => 'inputbox'));
                        $description =  esc_html(__('Firebase Server Key', 'majestic-support'));
                        mjtc_printConfigFieldSingle($title, $field, $description);
                      }

                      if(isset(majesticsupport::$_data[0]['logo_for_desktop_notfication_url'])){
                        $title = esc_html(__('Logo Image for Desktop Notifications', 'majestic-support'));
                        $value = '<input type="file" name="logo_for_desktop_notfication" id="logo_for_desktop_notfication">';
                        $description = '';
                        if(majesticsupport::$_config['logo_for_desktop_notfication_url'] != ''){
                          $maindir = wp_upload_dir();
                          $path = $maindir['baseurl'].'/'.esc_attr(majesticsupport::$_config['data_directory']).'/attachmentdata';
                          $description = '<img alt="'. esc_html(__('Remove Image','majestic-support')).'" height="60px" width="60px;" src="'.esc_url($path).'/'.esc_attr(majesticsupport::$_config['logo_for_desktop_notfication_url']).'"/> <label><input type="checkbox" name="del_logo_for_desktop_notfication" value="1">'. esc_html(__('Remove Logo','majestic-support')).'</label>';
                        }else{
                          $description = esc_html(__('No Firebase Notification Logo', 'majestic-support'));
                        }
                        mjtc_printConfigFieldSingle($title, $value, $description);
                      }
                    ?>
              </div>
              <?php } ?>
            </div>
            <!-- .....Private Credentials..... -->
            <div id="privatecredentials" class="msadmin-hide-config">
              <?php if(in_array('privatecredentials', majesticsupport::$_active_addons)){ ?>
                 <div class="tabs config-tabs" id="tabs">
                  <ul class="ms_tabs">
                      <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#PrivateCredentials"><?php echo esc_html(__('Private Credentials', 'majestic-support')) ?></a></li>
                  </ul>
                </div>
                <div class="ms_gen_body" id="PrivateCredentials">
                    <h2><?php echo esc_html(__('Private Credentials', 'majestic-support')) ?></h2>
                    <?php
                      if(isset(majesticsupport::$_data[0]['private_credentials_secretkey'])){
                          $title = esc_html(__('Secret Key', 'majestic-support'));
                          $field = MJTC_formfield::MJTC_text('private_credentials_secretkey', majesticsupport::$_data[0]['private_credentials_secretkey'], array('class' => 'inputbox'));
                          $description =  esc_html(__('Changing the encryption key of private credentials will cause all existing credentials to be discarded ', 'majestic-support'));
                          mjtc_printConfigFieldSingle($title, $field, $description);
                      }
                      $privatecredentialsurl = WP_PLUGIN_DIR.'/majestic-support-privatecredentials/classes/privatecredentials.php';
                      $title = esc_html(__('Second Level Security', 'majestic-support'));
                      $field = '';
                      $description =  sprintf(esc_html(__('For enhanced security, change the encryption method in %s on line %s', 'majestic-support')),$privatecredentialsurl,10);
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    ?>
                </div>
              <?php } ?>
            </div>
            <!-- .....Envato Validation..... -->
            <div id="envatovalidation" class="msadmin-hide-config">
              <?php if(in_array('envatovalidation', majesticsupport::$_active_addons)){ ?>
                <div class="tabs config-tabs" id="tabs">
                  <ul class="ms_tabs">
                      <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#EnvatoValidation"><?php echo esc_html(__('Envato Validation', 'majestic-support')) ?></a></li>
                  </ul>
                </div>
                <div class="ms_gen_body" id="EnvatoValidation">
                    <h2><?php echo esc_html(__('Envato Validation', 'majestic-support')) ?></h2>
                    <?php
                      if(isset(majesticsupport::$_data[0]['envato_api_key'])){
                          $title = esc_html(__('Api Key', 'majestic-support'));
                          $field = MJTC_formfield::MJTC_text('envato_api_key', majesticsupport::$_data[0]['envato_api_key'], array('class' => 'inputbox'));
                          $description =  esc_html(__('Enter Envato api key ', 'majestic-support'));
                          $description.= '<a title="'.esc_html(__("Click here to generate an api key",'majestic-support')).'" target="_blank" href="https://build.envato.com/create-token/">'.esc_html(__("Click here to generate an api key",'majestic-support')).'</a>';
                          mjtc_printConfigFieldSingle($title, $field, $description);
                      }
                      if(isset(majesticsupport::$_data[0]['envato_license_required'])){
                          $title = esc_html(__('License Mandatory', 'majestic-support'));
                          $field = MJTC_formfield::MJTC_select('envato_license_required', $yesno, majesticsupport::$_data[0]['envato_license_required']);
                          $description =  esc_html(__('Prevent users from submitting a ticket without a valid license for one of your product', 'majestic-support'));
                          mjtc_printConfigFieldSingle($title, $field, $description);
                      }
                      if(isset(majesticsupport::$_data[0]['envato_product_ids'])){
                          $title = esc_html(__('Product ID', 'majestic-support'));
                          $field = MJTC_formfield::MJTC_text('envato_product_ids', majesticsupport::$_data[0]['envato_product_ids'], array('class' => 'inputbox'));
                          $description =  esc_html(__('A comma-separated list of Envato product ids', 'majestic-support'));
                          mjtc_printConfigFieldSingle($title, $field, $description);
                      }
                    ?>
                </div>
              <?php } ?>
            </div>
            <!-- .....MailChimp..... -->
            <div id="mailchimp" class="msadmin-hide-config">
              <?php if(in_array('mailchimp', majesticsupport::$_active_addons)){ ?>
                <div class="tabs config-tabs" id="tabs">
                  <ul class="ms_tabs">
                      <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#MailChimp"><?php echo esc_html(__('MailChimp', 'majestic-support')) ?></a></li>
                  </ul>
                </div>
                <div class="ms_gen_body" id="MailChimp">
                    <h2><?php echo esc_html(__('MailChimp', 'majestic-support')) ?></h2>
                    <?php
                      if(isset(majesticsupport::$_data[0]['mailchimp_api_key'])){
                          $title = esc_html(__('Api Key', 'majestic-support'));
                          $field = MJTC_formfield::MJTC_text('mailchimp_api_key', majesticsupport::$_data[0]['mailchimp_api_key'], array('class' => 'inputbox'));
                          $description =  esc_html(__('Enter MailChimp API key ', 'majestic-support'));
                          mjtc_printConfigFieldSingle($title, $field, $description);
                      }
                      if(isset(majesticsupport::$_data[0]['mailchimp_list_id'])){
                          $title = esc_html(__('Audience ID', 'majestic-support'));
                          $field = MJTC_formfield::MJTC_text('mailchimp_list_id', majesticsupport::$_data[0]['mailchimp_list_id'], array('class' => 'inputbox'));
                          $description =  esc_html(__('Find Audience ID in your MailChimp account', 'majestic-support'));
                          mjtc_printConfigFieldSingle($title, $field, $description);
                      }
                      if(isset(majesticsupport::$_data[0]['mailchimp_double_optin'])){
                          $title = esc_html(__('Enable double opt-in', 'majestic-support'));
                          $field = MJTC_formfield::MJTC_select('mailchimp_double_optin', $yesno, majesticsupport::$_data[0]['mailchimp_double_optin']);
                          $description =  esc_html(__('You must also enable double opt-in in your MailChimp account', 'majestic-support'));
                          mjtc_printConfigFieldSingle($title, $field, $description);
                      }
                      $title = esc_html(__('Welcome email', 'majestic-support'));
                      $field = esc_html(__('You can enable Final Welcome Email in your MailChimp account', 'majestic-support'));
                      $description = '';
                      mjtc_printConfigFieldSingle($title, $field, $description);
                      ?>
                </div>
              <?php } ?>
            </div>
            <!-- .....Easy Digital Downloads..... -->
            <div id="easydigitaldownloads" class="msadmin-hide-config">
              <?php if(in_array('easydigitaldownloads', majesticsupport::$_active_addons)){ ?>
                <div class="tabs config-tabs" id="tabs">
                  <ul class="ms_tabs">
                      <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#EasyDigitalDownloads"><?php echo esc_html(__('Easy Digital Downloads', 'majestic-support')) ?></a></li>
                  </ul>
                </div>
                <div class="ms_gen_body" id="EasyDigitalDownloads">
                    <h2><?php echo esc_html(__('Easy Digital Downloads', 'majestic-support')) ?></h2>
                    <?php
                      if(isset(majesticsupport::$_data[0]['verify_license_on_ticket_creation'])){
                          $title = esc_html(__('Verify License On Ticket Creation', 'majestic-support'));
                          $field = MJTC_formfield::MJTC_select('verify_license_on_ticket_creation', $yesno, majesticsupport::$_data[0]['verify_license_on_ticket_creation']);
                          mjtc_printConfigFieldSingle($title, $field);
                      }
                    ?>
                </div>
              <?php } ?>
            </div>
            <!-- .....Captcha..... -->
            <div id="captcha" class="msadmin-hide-config">
              <div class="tabs config-tabs" id="tabs">
                <ul class="ms_tabs">
                    <li class="tab-link ms_current_tab" data-ms-tab="general"><a href="#captcha"><?php echo esc_html(__('Captcha', 'majestic-support')) ?></a></li>
                </ul>
              </div>
              <div class="ms_gen_body" id="captcha">
                    <h2><?php echo esc_html(__('Captcha Setting', 'majestic-support')) ?></h2>
                    <?php
        
                    if(isset(majesticsupport::$_data[0]['captcha_on_registration'])){
                        $title = esc_html(__('Show captcha on registration form', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('captcha_on_registration', $yesno, majesticsupport::$_data[0]['captcha_on_registration']);
                        $description =  esc_html(__('Select whether you want to show captcha on the registration form or not', 'majestic-support'));
                        mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['show_captcha_on_visitor_from_ticket'])){
                        $title = esc_html(__('Show captcha on the visitor ticket form', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('show_captcha_on_visitor_from_ticket', $yesno, majesticsupport::$_data[0]['show_captcha_on_visitor_from_ticket']);
                        $description =  esc_html(__('Show captcha when a visitor wants to create a ticket', 'majestic-support'));
                        $video = '';
                        $videotext = 'Show captcha on the visitor ticket form';
                        mjtc_printConfigFieldSingle($title, $field, $description, $video, '', $videotext);
                    }

                    if(isset(majesticsupport::$_data[0]['captcha_selection'])){
                        $title = esc_html(__('Captcha selection', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('captcha_selection', $captchaselection, majesticsupport::$_data[0]['captcha_selection']);
                        $description =  esc_html(__('Which captcha do you want to add?', 'majestic-support'));
                        $video = '';
                        $videotext = 'Captcha selection';
                        mjtc_printConfigFieldSingle($title, $field, $description, $video, '', $videotext);
                    } ?>

                    <h2><?php echo esc_html(__('Google reCaptcha', 'majestic-support')) ?></h2>

                    <?php
                    if(isset(majesticsupport::$_data[0]['recaptcha_version'])){
                        $title = esc_html(__('Google ReCaptcha version', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('recaptcha_version', $recaptcha_version, majesticsupport::$_data[0]['recaptcha_version']);
                        $description =  esc_html(__('Select the Google ReCaptcha version','majestic-support'));
                        mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['recaptcha_publickey'])){
                      $title = esc_html(__('Google ReCaptcha site key', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_text('recaptcha_publickey', majesticsupport::$_data[0]['recaptcha_publickey'], array('class' => 'inputbox'));
                      $description =  esc_html(__('Please enter the site key for Google ReCaptcha from','majestic-support')).' https://www.google.com/recaptcha/admin ';
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['recaptcha_privatekey'])){
                      $title = esc_html(__('Google ReCaptcha secret key', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_text('recaptcha_privatekey', majesticsupport::$_data[0]['recaptcha_privatekey'], array('class' => 'inputbox'));
                      $description =  esc_html(__('Please enter the secret key for Google ReCaptcha from','majestic-support')).' https://www.google.com/recaptcha/admin ';
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    } ?>

                    <h2><?php echo esc_html(__('Own Captcha', 'majestic-support')) ?></h2>

                    <?php
                    if(isset(majesticsupport::$_data[0]['owncaptcha_calculationtype'])){
                        $title = esc_html(__('Own captcha calculation type', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('owncaptcha_calculationtype', $owncaptchatype, majesticsupport::$_data[0]['owncaptcha_calculationtype']);
                        $description =  esc_html(__('Select the calculation type (addition or subtraction)', 'majestic-support'));
                        mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['owncaptcha_totaloperand'])){
                        $title = esc_html(__('Own captcha operands', 'majestic-support'));
                        $field = MJTC_formfield::MJTC_select('owncaptcha_totaloperand', $owncaptchaoparend, majesticsupport::$_data[0]['owncaptcha_totaloperand']);
                        $description =  esc_html(__('Select the total number of operands to be given', 'majestic-support'));
                        mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                    if(isset(majesticsupport::$_data[0]['owncaptcha_subtractionans'])){
                      $title = esc_html(__('Positive Answer Upon Own Captcha Subtraction', 'majestic-support'));
                      $field = MJTC_formfield::MJTC_select('owncaptcha_subtractionans', $yesno, majesticsupport::$_data[0]['owncaptcha_subtractionans']);
                      $description =  esc_html(__('Selecting "Yes" ensures that the result of the subtraction will always be positive', 'majestic-support'));
                      mjtc_printConfigFieldSingle($title, $field, $description);
                    }

                  ?>
              </div>
            </div>
            </div>
            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'configuration_saveconfiguration'), MJTC_ALLOWED_TAGS); ?>
            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
            <div class="mjtc-form-button">
              <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save Settings', 'majestic-support')), array('class' => 'button mjtc-form-save')), MJTC_ALLOWED_TAGS); ?>
            </div>
        </form>
        </div>
    </div>
</div>
<?php

    function mjtc_printConfigFieldSingle($title, $field, $description = '', $video = '', $childfield = '', $videotext = '', $actionbtn = ''){
        $html = '';
        $html .= '
            <div class="mjtc-support-configuration-row">
                <div class="mjtc-support-configuration-title">';
                $html .= esc_html($title).'</div>
                <div class="mjtc-support-configuration-value">'.wp_kses($field, MJTC_ALLOWED_TAGS);
                  if($childfield !=''){
                      $html .= '<div class="mjtc-support-configuration-value childfield">'.wp_kses($childfield, MJTC_ALLOWED_TAGS).'</div>';
                  }
                  if($description !=''){
                      $html .= '<div class="mjtc-support-configuration-description">'.wp_kses($description, MJTC_ALLOWED_TAGS).'</div>';
                  }
                $html .= '</div>';
                if(isset($video) && $video != ''){
                    $html .= '<div class="mjtc-support-configuration-video">
                      <a target="blank" href="https://www.youtube.com/watch?v='.esc_attr($video).'" class="mjtc-sprt-det-hdg-img mjtc-cp-video-'.esc_attr($video).'">
                        <img title="'. esc_html(__('watch video','majestic-support')) .'" alt="'. esc_html(__('watch video','majestic-support')).'" src="'.esc_url(MJTC_PLUGIN_URL).'/includes/images/watch-video-icon-config.png" />
                        <span></span>
                      </a>';
                      if(isset($actionbtn) && $actionbtn != ''){
                        $html .= '<a href="?page=majesticsupport_email&mjslay=addemail" class="mjtc-support-configuration-btn">
                                    <img title="'. esc_html(__('Add','majestic-support')) .'" alt="'. esc_html(__('Add','majestic-support')).'" src="'.esc_url(MJTC_PLUGIN_URL).'/includes/images/plus-icon.png" />
                                    '. esc_html(majesticsupport::MJTC_getVarValue($actionbtn)).'
                                  </a>';
                      }
                    $html .= '</div>';
                }
                  

        $html .= '
            </div>';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }

    function mjtc_printConfigFieldMulti($title, $field1, $field2, $field3){
        $html = '';

        $html = '
        <div class="mjtc-support-configuration-row-mail">
            <div class="mjtc-support-configuration-title">'.esc_html($title).'</div>
            <div class="mjtc-support-configuration-value"><span class="mjtc-support-config-xs-show-hide">'. esc_html(__('Agent','majestic-support')) .'</span>'.wp_kses($field1, MJTC_ALLOWED_TAGS).'</div>
            <div class="mjtc-support-configuration-value"><span class="mjtc-support-config-xs-show-hide">'. esc_html(__('User','majestic-support')) .'</span>'.wp_kses($field2, MJTC_ALLOWED_TAGS).'</div>
            <div class="mjtc-support-configuration-value"><span class="mjtc-support-config-xs-show-hide">'. esc_html(__('Admin','majestic-support')) .'</span>'.wp_kses($field3, MJTC_ALLOWED_TAGS).'</div>
        </div>
        ';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }

 ?>
