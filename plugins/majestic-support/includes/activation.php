<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_activation {

    static function MJTC_activate() {
        // Install Database
        MJTC_activation::MJTC_insertMenu();
        MJTC_activation::MJTC_runSQL();
	    MJTC_activation::MJTC_checkUpdates();
        MJTC_activation::MJTC_addCapabilites();
    }

    static private function MJTC_addCapabilites() {
		if($GLOBALS['wp_roles']->is_role( 'administrator' )){ // if role exists
			$role = get_role( 'administrator' );
			$role->add_cap( 'ms_support_ticket' );
			$role->add_cap( 'ms_support_ticket_tickets' );
		}
		if($GLOBALS['wp_roles']->is_role( 'contributor' )){ // if role exists
			$role2 = get_role( 'contributor' );
			$role2->add_cap( 'ms_support_ticket_tickets' );
		}
    }

    static private function MJTC_checkUpdates() {
        include_once MJTC_PLUGIN_PATH . 'includes/updates/updates.php';
        MJTC_updates::MJTC_checkUpdates();
    }

    static private function MJTC_insertMenu() {
        $pageexist = majesticsupport::$_db->get_var("Select COUNT(id) FROM `" . majesticsupport::$_db->prefix . "posts` WHERE post_name = 'majestic-support-controlpanel'");
        if ($pageexist == 0) {
            $post = array(
                'post_name' => 'majestic-support-controlpanel',
                'post_title' => 'Majestic Support',
                'post_status' => 'publish',
                'post_content' => '[majesticsupport]',
                'post_type' => 'page'
            );
            wp_insert_post($post);
        } else {
            majesticsupport::$_db->get_var("UPDATE `" . majesticsupport::$_db->prefix . "posts` SET post_status = 'publish' WHERE post_name = 'majestic-support-controlpanel'");
        }
        update_option('rewrite_rules', '');
    }

    static private function MJTC_runSQL() {
        $pageid = majesticsupport::$_db->get_var("SELECT id FROM `" . majesticsupport::$_db->prefix . "posts` WHERE post_name = 'majestic-support-controlpanel'");
         $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_config` (
                      `configname` varchar(100) NOT NULL DEFAULT '',
                      `configvalue` text NOT NULL DEFAULT '',
                      `configfor` varchar(50) DEFAULT NULL,
                      `addon` varchar(100) DEFAULT NULL,
                      PRIMARY KEY (`configname`),
                      FULLTEXT KEY `config_name` (`configname`),
                      FULLTEXT KEY `config_for` (`configfor`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        majesticsupport::$_db->query($query);
        $runConfig = majesticsupport::$_db->get_var("SELECT COUNT(configname) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_config`");
        if ($runConfig == 0) {
            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_attachments` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `ticketid` int(11) DEFAULT NULL,
                                `replyattachmentid` int(11) DEFAULT NULL,
                                `filesize` varchar(32) DEFAULT NULL,
                                `filename` varchar(128) DEFAULT NULL,
                                `filekey` varchar(128) DEFAULT NULL,
                                `deleted` tinyint(1) DEFAULT NULL,
                                `status` tinyint(1) DEFAULT NULL,
                                `created` datetime DEFAULT NULL,
                                PRIMARY KEY (`id`)
                                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            majesticsupport::$_db->query($query);

            $query = "INSERT INTO `" . majesticsupport::$_db->prefix . "mjtc_support_config` (`configname`, `configvalue`, `configfor`, `addon`) VALUES
                    ('title', 'Majestic Support System', 'default', NULL),
                    ('offline', '2', 'default', NULL),
                    ('offline_message', 'We are offline now please come back soon.\r\n\r\nThank you', 'default', NULL),
                    ('data_directory', 'majesticsupportdata', 'default', NULL),
                    ('date_format', 'd-m-Y', 'default', NULL),
                    ('ticket_overdue', '5', 'default', 'overdue'),
                    ('ticket_auto_close', '5', 'default', 'autoclose'),
                    ('no_of_attachement', '5', 'default', NULL),
                    ('file_maximum_size', '10240', 'default', NULL),
                    ('file_extension', 'jpg,jpeg,png,gif,pdf,doc,docx,ppt,pptx,pps,ppsx,odt,xls,xlsx,mp3,m4a,ogg,wav,mp4,m4v,mov,wmv,avi,mpg,ogv,3gp,3g2,zip', 'default', NULL),
                    ('show_current_location', '2', 'default', NULL),
                    ('maximum_tickets', '1000', 'default', 'maxticket'),
                    ('reopen_ticket_within_days', '50', 'default', NULL),
                    ('visitor_can_create_ticket', '1', 'default', NULL),
                    ('show_captcha_on_visitor_from_ticket', '1', 'default', NULL),
                    ('default_alert_email', '1', 'default', NULL),
                    ('default_admin_email', '1', 'default', NULL),
                    ('new_ticket_mail_to_admin', '1', 'default', ''),
                    ('new_ticket_mail_to_staff_members', '0', 'default', 'agent'),
                    ('banemail_mail_to_admin', '0', 'default', 'banemail'),
                    ('ticket_reassign_admin', '1', 'default', NULL),
                    ('ticket_reassign_staff', '0', 'default', 'agent'),
                    ('ticket_reassign_user', '1', 'default', NULL),
                    ('ticket_close_admin', '1', 'default', NULL),
                    ('ticket_close_staff', '0', 'default', 'agent'),
                    ('ticket_close_user', '1', 'default', NULL),
                    ('ticket_delete_admin', '1', 'default', NULL),
                    ('ticket_delete_staff', '0', 'default', 'agent'),
                    ('ticket_delete_user', '1', 'default', NULL),
                    ('ticket_mark_overdue_admin', '0', 'default', 'overdue'),
                    ('ticket_mark_overdue_staff', '0', 'default', 'agent'),
                    ('ticket_mark_overdue_user', '0', 'default', 'overdue'),
                    ('ticket_ban_email_admin', '0', 'default', 'banemail'),
                    ('ticket_ban_email_staff', '0', 'default', 'banemail'),
                    ('ticket_ban_email_user', '0', 'default', 'banemail'),
                    ('ticket_department_transfer_admin', '1', 'default', NULL),
                    ('ticket_department_transfer_staff', '0', 'default', 'agent'),
                    ('ticket_department_transfer_user', '1', 'default', NULL),
                    ('ticket_reply_ticket_user_admin', '1', 'default', NULL),
                    ('ticket_reply_ticket_user_staff', '0', 'default', 'agent'),
                    ('ticket_reply_ticket_user_user', '1', 'default', NULL),
                    ('ticket_response_to_staff_admin', '0', 'default', NULL),
                    ('ticket_response_to_staff_staff', '0', 'default', 'agent'),
                    ('ticket_response_to_staff_user', '0', 'default', NULL),
                    ('ticker_ban_eamil_and_close_ticktet_admin', '0', 'default', 'banemail'),
                    ('ticker_ban_eamil_and_close_ticktet_staff', '0', 'default', 'banemail'),
                    ('ticker_ban_eamil_and_close_ticktet_user', '0', 'default', 'banemail'),
                    ('unban_email_admin', '0', 'default', 'banemail'),
                    ('unban_email_staff', '0', 'default', 'banemail'),
                    ('unban_email_user', '0', 'default', 'banemail'),
                    ('maximum_open_tickets', '25', 'deafult', 'maxticket'),
                    ('pagination_default_page_size', '10', 'deafult', NULL),
                    ('recaptcha_version', '1', 'default', NULL),
                    ('recaptcha_publickey', '', 'default', NULL),
                    ('recaptcha_privatekey', '', 'default', NULL),
                    ('captcha_selection', '2', 'default', NULL),
                    ('owncaptcha_calculationtype', '1', 'default', NULL),
                    ('owncaptcha_totaloperand', '2', 'default', NULL),
                    ('owncaptcha_subtractionans', '1', 'default', NULL),
                    ('ticket_lock_staff', '0', 'email', 'agent'),
                    ('ticket_lock_admin', '0', 'email', 'actions'),
                    ('ticket_lock_user', '0', 'email', 'actions'),
                    ('ticket_unlock_staff', '0', 'email', 'agent'),
                    ('ticket_unlock_admin', '0', 'email', 'actions'),
                    ('ticket_unlock_user', '0', 'email', 'actions'),
                    ('ticket_mark_progress_staff', '0', 'email', 'agent'),
                    ('ticket_mark_progress_admin', '0', 'email', 'actions'),
                    ('ticket_mark_progress_user', '0', 'email', 'actions'),
                    ('ticket_priority_staff', '1', 'email', 'agent'),
                    ('ticket_priority_admin', '1', 'email', NULL),
                    ('ticket_priority_user', '1', 'email', NULL),
                    ('new_ticket_message', '', 'default', NULL),
                    ('cplink_openticket_staff', '2', 'cplink', 'agent'),
                    ('cplink_myticket_staff', '2', 'cplink', 'agent'),
                    ('cplink_ticketclosereasons_staff', '2', 'cplink', 'agent'),
                    ('cplink_smartreply_staff', '2', 'cplink', 'agent'),
                    ('cplink_addrole_staff', '2', 'cplink', 'agent'),
                    ('cplink_roles_staff', '2', 'cplink', 'agent'),
                    ('cplink_addstaff_staff', '2', 'cplink', 'agent'),
                    ('cplink_staff_staff', '2', 'cplink', 'agent'),
                    ('cplink_adddepartment_staff', '2', 'cplink', 'agent'),
                    ('cplink_department_staff', '2', 'cplink', 'agent'),
                    ('cplink_addcategory_staff', '2', 'cplink', 'knowledgebase'),
                    ('cplink_category_staff', '2', 'cplink', 'knowledgebase'),
                    ('cplink_addkbarticle_staff', '2', 'cplink', 'knowledgebase'),
                    ('cplink_kbarticle_staff', '2', 'cplink', 'knowledgebase'),
                    ('cplink_adddownload_staff', '2', 'cplink', 'download'),
                    ('cplink_download_staff', '2', 'cplink', 'download'),
                    ('cplink_addannouncement_staff', '2', 'cplink', 'announcement'),
                    ('cplink_announcement_staff', '2', 'cplink', 'announcement'),
                    ('cplink_addfaq_staff', '2', 'cplink', 'faq'),
                    ('cplink_faq_staff', '2', 'cplink', 'faq'),
                    ('cplink_mail_staff', '2', 'cplink', 'mail'),
                    ('cplink_banemail_staff', '2', 'cplink', 'banemail'),
                    ('cplink_myprofile_staff', '2', 'cplink', 'agent'),
                    ('cplink_openticket_user', '1', 'cplink', NULL),
                    ('cplink_myticket_user', '1', 'cplink', NULL),
                    ('cplink_checkticketstatus_user', '1', 'cplink', NULL),
                    ('cplink_downloads_user', '2', 'cplink', 'download'),
                    ('cplink_announcements_user', '2', 'cplink', 'announcement'),
                    ('cplink_faqs_user', '2', 'cplink', 'faq'),
                    ('cplink_knowledgebase_user', '2', 'cplink', 'knowledgebase'),
                    ('cplink_latestdownloads_user', '2', 'cplink', 'download'),
                    ('cplink_latestannouncements_user', '2', 'cplink', 'announcement'),
                    ('cplink_latestkb_user', '2', 'cplink', 'knowledgebase'),
                    ('cplink_latestfaqs_user', '2', 'cplink', 'faq'),
                    ('tplink_home_staff', '2', 'tplink', 'agent'),
                    ('tplink_tickets_staff', '2', 'tplink', 'agent'),
                    ('tplink_knowledgebase_staff', '2', 'tplink', 'knowledgebase'),
                    ('tplink_announcements_staff', '2', 'tplink', 'announcement'),
                    ('tplink_downloads_staff', '2', 'tplink', 'download'),
                    ('tplink_faqs_staff', '0', 'tplink', 'faq'),
                    ('tplink_home_user', '1', 'tplink', NULL),
                    ('tplink_tickets_user', '1', 'tplink', NULL),
                    ('tplink_knowledgebase_user', '2', 'tplink', 'knowledgebase'),
                    ('tplink_announcements_user', '2', 'tplink', 'announcement'),
                    ('tplink_downloads_user', '1', 'tplink', NULL),
                    ('tplink_faqs_user', '0', 'tplink', 'faq'),
                    ('show_breadcrumbs', '1', 'default', NULL),
                    ('productcode', 'mjsupport', 'default', NULL),
                    ('versioncode', '1.0.1', 'default', NULL),
                    ('productversion', '101', 'default', NULL),
                    ('producttype', 'free', 'default', NULL),
                    ('tve_enabled', '2', 'default', NULL),
                    ('tve_mailreadtype', '3', 'default', NULL),
                    ('tve_attachment', '1', 'default', NULL),
                    ('tve_emailreadingdelay', '300', 'default', NULL),
                    ('tve_hosttype', '4', 'default', NULL),
                    ('tve_hostname', '', 'default', NULL),
                    ('tve_emailaddress', '', 'default', NULL),
                    ('tve_emailpassword', '', 'default', NULL),
                    ('lastEmailReadingTime', '1562051615', 'default', NULL),
                    ('tve_ssl', '2', 'ticketviaemail', NULL),
                    ('tve_hostportnumber', '', 'ticketviaemail', NULL),
                    ('ck', 'abc29ff5d6ec8d9e108ea1a4515e26a3', 'default', NULL),
                    ('login_redirect', '2', 'default', NULL),
                    ('count_on_myticket', '1', 'default', NULL),
                    ('system_slug', 'majesticsupport', 'default', NULL),
                    ('default_pageid', '".$pageid."', 'default', NULL),
                    ('support_screentag', '1', 'default', NULL),
                    ('support_custom_img', '0', 'default', NULL),
                    ('support_custom_txt', 'Support', 'default', NULL),
                    ('woocommerce_default_categoryid', '0', 'default', NULL),
                    ('screentag_position', '1', 'default', NULL),
                    ('last_step_updater', '', 'default', NULL),
                    ('cplink_login_logout_user', '1', 'cplink', NULL),
                    ('cplink_login_logout_staff', '2', 'cplink', 'agent'),
                    ('ticketid_sequence', '1', 'default', NULL),
                    ('prefix_ticketid', '', 'customticketid', NULL), 
                    ('suffix_ticketid', '', 'customticketid', NULL),
                    ('padding_zeros_ticketid', '', 'customticketid', NULL),
                    ('print_ticket_user', '1', 'ticket', NULL),
                    ('last_version', '211', 'default', NULL),
                    ('cplink_staff_report_staff', '2', 'cplink', 'agent'),
                    ('cplink_department_report_staff', '2', 'cplink', 'agent'),
                    ('wp_default_role', 'subscriber', 'default', 'useroptions'),
                    ('captcha_on_registration', '1', 'default', 'useroptions'),
                    ('cplink_register_user', '1', 'default', NULL),
                    ('cplink_feedback_staff', '0', 'default', 'feedback'),
                    ('feedback_email_delay_type', '1', 'default', 'feedback'),
                    ('feedback_email_delay', '30', 'default', 'feedback'),
                    ('ticket_feedback_user', '1', 'default', 'feedback'),
                    ('ticket_overdue_type', '1', 'default', 'overdue'),
                    ('reply_to_closed_ticket', '1', 'default', NULL),
                    ('anonymous_name_on_ticket_reply', '2', 'ticket', NULL),
                    ('maximum_record_for_smart_reply', '1', 'ticket', NULL),
                    ('show_email_on_ticket_reply', '1', 'ticket', NULL),
                    ('show_ticket_delete_button', '1', 'ticket', NULL),
                    ('visitor_message', 'Thank you for contacting us. A support ticket request has been submitted, and a representative will be contacting you shortly.\r\nSupport Team', 'default', NULL),
                    ('ticket_reply_closed_ticket_user', '1', 'default', NULL),
                    ('feedback_thanks_message', 'Thank you for providing your feedback. We appreciate the time you have taken and will actively use it to improve our services to you.', 'default', 'feedback'),
                    ('serialnumber', '67259', 'hostdata', NULL),
                    ('hostdata', '88fd93f82e5ca231ff4e85e769be370f', 'hostdata', NULL),
                    ('zvdk', '8ffe8941fa06d68', 'hostdata', NULL),
                    ('read_utf_ticket_via_email', '1', 'ticketviaemail', 'emailpiping'),
                    ('set_login_link', '1', 'default', NULL),
                    ('login_link', '', 'default', NULL),
                    ('set_register_link', '1', 'default', NULL),
                    ('register_link', '', 'default', NULL),
                    ('show_header', '1', 'default', NULL),
                    ('tplink_openticket_user', '1', 'tplink', NULL),
                    ('tplink_openticket_staff', '1', 'tplink', 'agent'),
                    ('cplink_latesttickets_staff', '2', 'cplink', 'agent'),
                    ('cplink_latestdownloads_staff', '1', 'cplink', 'download'),
                    ('cplink_latestannouncements_staff', '1', 'cplink', 'announcement'),
                    ('cplink_latestkb_staff', '1', 'cplink', 'knowledgebase'),
                    ('cplink_latestfaqs_staff', '1', 'cplink', 'faq'),
                    ('cplink_latesttickets_user', '1', 'cplink', NULL),
                    ('cplink_totalcount_staff', '2', 'cplink', 'agent'),
                    ('cplink_totalcount_user', '1', 'cplink', NULL),
                    ('cplink_ticketstats_staff', '2', 'cplink', 'agent'),
                    ('tplink_login_logout_user', '1', 'tplink', NULL),
                    ('tplink_login_logout_staff', '1', 'tplink', NULL),
                    ('0d607e93d5af0655351743b41ed67944', '', 'firebase', 'notification'),
                    ('apiKey_firebase', '', 'firebase', 'notification'),
                    ('authDomain_firebase', '', 'firebase', 'notification'),
                    ('databaseURL_firebase', '', 'firebase', 'notification'),
                    ('projectId_firebase', '', 'firebase', 'notification'),
                    ('storageBucket_firebase', '', 'firebase', 'notification'),
                    ('messagingSenderId_firebase', '', 'firebase', 'notification'),
                    ('server_key_firebase', '', 'firebase', 'notification'),
                    ('logo_for_desktop_notfication_url', '', 'firebase', 'notification'),
                    ('private_credentials_secretkey', '', 'privatecredentials', 'privatecredentials'),
                    ('tickets_ordering', '1', 'default', NULL),
                    ('mailchimp_api_key', '', 'mailchimp', 'mailchimp'),
                    ('mailchimp_list_id', '', 'mailchimp', 'mailchimp'),
                    ('mailchimp_double_optin', '1', 'mailchimp', 'mailchimp'),
                    ('envato_api_key', '', 'envatovalidation', 'envatovalidation'),
                    ('envato_license_required', '1', 'envatovalidation', 'envatovalidation'),
                    ('envato_product_ids', '', 'envatovalidation', 'envatovalidation'),
                    ('cplink_helptopic_agent', '1', 'cplink', 'helptopic'),
                    ('cplink_cannedresponses_agent', '1', 'cplink', 'cannedresponses'),
                    ('verify_license_on_ticket_creation', '1', 'default', 'easydigitaldownloads'),
                    ('cplink_erasedata_staff', '0', 'cplink', 'agent'),
                    ('cplink_erasedata_user', '1', 'cplink', NULL),
                    ('redirect_after_checkout', '', 'default', 'paidsupport'),
                    ('create_user_via_email', '0', 'ticketviaemail', 'emailpiping'),
                    ('tickets_sorting', '2', 'default', NULL),
					('apikeylinkedin' ,'' , 'linkedin', 'sociallogin'),
                    ('loginwithfacebook' ,'0' , 'login', 'sociallogin'),
                    ('loginwithlinkedin' ,'0' , 'login', 'sociallogin'),
                    ('apikeyfacebook' ,'' , 'facebook', 'sociallogin'),
                    ('clientsecretfacebook' ,'' , 'facebook', 'sociallogin'),
                    ('clientsecretlinkedin' ,'' , 'linkedin', 'sociallogin'),
                    ('slug_prefix', 'st-', 'default', NULL),
                    ('home_slug_prefix', 'ms-', 'default', NULL),
                    ('show_multiform_popup', '1', 'ticket', 'multiform'),
                    ('show_closedby_on_admin_tickets', '1', 'ticket', NULL),
                    ('show_closedby_on_agent_tickets', '1', 'ticket', NULL),
                    ('show_closedby_on_user_tickets', '1', 'ticket', NULL),
                    ('show_assignto_on_admin_tickets', '1', 'ticket', 'agent'),
                    ('show_assignto_on_agent_tickets', '1', 'ticket', 'agent'),
                    ('show_assignto_on_user_tickets', '1', 'ticket', 'agent'),
                    ('cplink_export_ticket_staff', '1', 'cplink', 'export');";
            majesticsupport::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_departments` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `emailtemplateid` int(11) DEFAULT NULL,
                                `emailid` int(11) DEFAULT NULL,
                                `autoresponceemailid` int(11) DEFAULT NULL,
                                `managerid` int(11) DEFAULT NULL,
                                `departmentname` varchar(255) DEFAULT NULL,
                                `departmentsignature` text,
                                `ispublic` tinyint(1) DEFAULT NULL,
                                `ticketautoresponce` tinyint(1) DEFAULT NULL,
                                `messageautoresponce` tinyint(1) DEFAULT NULL,
                                `canappendsignature` tinyint(1) DEFAULT NULL,
                                `ordering` int(11) NOT NULL,
                                `updated` datetime DEFAULT NULL,
                                `created` datetime DEFAULT NULL,
                                `status` tinyint(1) DEFAULT NULL,
                                `isdefault` tinyint(1) DEFAULT NULL,
                                `sendmail` tinyint NOT NULL DEFAULT '0',
                                PRIMARY KEY (`id`)
                                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;";
            majesticsupport::$_db->query($query);
            $query = "INSERT INTO `" . majesticsupport::$_db->prefix . "mjtc_support_departments` (`id`, `emailtemplateid`, `emailid`, `autoresponceemailid`, `managerid`, `departmentname`, `departmentsignature`, `ispublic`, `ticketautoresponce`, `messageautoresponce`, `canappendsignature`, `ordering`, `updated`, `created`, `status`) VALUES (1, NULL, 1, NULL, NULL, 'Support', '-- \n\n Support Department.', 1, NULL, NULL, 1, 1, '" . date_i18n('Y-m-d H:i:s') . "', '" . date_i18n('Y-m-d H:i:s') . "', 1);";
            majesticsupport::$_db->query($query);
            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_email` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `autoresponse` tinyint(1) DEFAULT NULL,
                                `priorityid` int(11) DEFAULT NULL,
                                `email` varchar(125) DEFAULT NULL,
                                `name` varchar(32) DEFAULT NULL,
                                `uid` int(11) DEFAULT NULL,
                                `password` varchar(125) DEFAULT NULL COMMENT '    ',
                                `status` tinyint(1) DEFAULT NULL,
                                `smtpemailauth` TINYINT DEFAULT NULL,
                                `mailhost` varchar(125) DEFAULT NULL,
                                `mailprotocol` enum('pop','map') DEFAULT NULL,
                                `mailencryption` enum('NONE','SSL') DEFAULT NULL,
                                `mailport` smallint(6) DEFAULT NULL,
                                `mailfetchfrequency` tinyint(3) DEFAULT NULL,
                                `mailfetchmaximum` tinyint(4) DEFAULT NULL,
                                `maildeleted` tinyint(1) DEFAULT NULL,
                                `mailerrors` tinyint(3) DEFAULT NULL,
                                `maillasterror` datetime DEFAULT NULL,
                                `maillastfetch` datetime DEFAULT NULL,
                                `smtpactive` tinyint(1) DEFAULT NULL,
                                `smtphosttype` INT DEFAULT NULL,
                                `smtphost` varchar(125) DEFAULT NULL,
                                `smtpport` smallint(6) DEFAULT NULL,
                                `smtpsecure` tinyint(1) DEFAULT NULL,
                                `smtpauthencation` tinyint(1) DEFAULT NULL,
                                `created` datetime DEFAULT NULL,
                                `updated` datetime DEFAULT NULL,
                                PRIMARY KEY (`id`)
                                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;";
            majesticsupport::$_db->query($query);
            $systememail = get_option('admin_email');
            $query = "INSERT INTO `" . majesticsupport::$_db->prefix . "mjtc_support_email` (`id`,`autoresponse`, `priorityid`, `email`, `name`, `uid`, `password`, `status`, `mailhost`, `mailprotocol`, `mailencryption`, `mailport`, `mailfetchfrequency`, `mailfetchmaximum`, `maildeleted`, `mailerrors`, `maillasterror`, `maillastfetch`, `smtpactive`, `smtphost`, `smtpport`, `smtpsecure`, `smtpauthencation`, `created`, `updated`) VALUES
                                (1,1, 1, '" . esc_sql($systememail) . "', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2014-10-02 10:38:48', '0000-00-00 00:00:00');";
            majesticsupport::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_emailtemplates` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `templatefor` varchar(50) DEFAULT NULL,
                                `title` varchar(50) DEFAULT NULL,
                                `subject` varchar(255) DEFAULT NULL,
                                `body` text,
                                `created` datetime DEFAULT NULL,
                                `status` tinyint(1) DEFAULT NULL,
                                PRIMARY KEY (`id`)
                                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26;";
            majesticsupport::$_db->query($query);
            $query = "INSERT INTO `" . majesticsupport::$_db->prefix . "mjtc_support_emailtemplates` (`id`, `templatefor`, `title`, `subject`, `body`, `created`, `status`) VALUES
                                (1, 'ticket-new', '', '{SITETITLE}: New Ticket Received', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: New Ticket Received</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #cccdd0;\">\n<div style=\"font-weight: bold; font-size: 18px; padding-bottom: 15px; color: #242223;\">Dear {USERNAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Your support ticket (<strong style=\"color: #242223;\">{SUBJECT}</strong>) with ticket id (<strong style=\"color: #242223;\">{TRACKINGID}</strong>) has been submitted. We try to reply all tickets as soon as possible, usually within 24 hours.</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-size: 16px; font-weight: bold; padding-bottom: 10px; color: #242223; line-height: 2;\">You will receive email notification when our agent replies to your ticket. You can view the status of your ticket here:</div>\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px 70px; background: #392c7d; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; font-weight: bold; border-bottom: 4px solid #1a1a1a;\" href=\"{TICKETURL}\">View Ticket</a></div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (2, 'department-new', '', '{SITETITLE}:  New Department {DEPARTMENT_TITLE} has been received', '<div style=\"border: 3px dotted #e0e1e0;\">\n<div style=\"padding: 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 40px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 30px;\">{SITETITLE}: New Department</div>\n<div style=\"padding: 40px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear Admin,</div>\n<div style=\"color: #727376; line-height: 2;\">We receive new department (<strong style=\"color: #4b4b4d;\">{DEPARTMENT_TITLE}</strong>).</div>\n</div>\n<div style=\"background: #fef2ef; padding: 25px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 16px; margin-bottom: 15px; color: #983133; text-transform: uppercase;\">Do not reply on this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© 2014-{CURRENT_YEAR} All rights reserved - <a href=\"https://www.majesticsupport.com\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>', NULL, 0),
                                (3, 'group-new', '', '{SITETITLE}:  New Group {GROUP_TITLE} has beed received ', 'Hello Admin ,\r\n\r\nWe receive new group.\r\n\r\n<span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span>\r\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wonot receive your reply!', NULL, 0),
                                (4, 'staff-new', '', '{SITETITLE}: New Agent {AGENT_NAME} has been received', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: New Agent</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px;\">\n<div style=\"color: #727376; line-height: 2;\">We receive new agent (<strong style=\"color: #242223;\">{AGENT_NAME}</strong>).</div>\n</div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (5, 'helptopic-new', '', '{SITETITLE}: New Help Topic {HELPTOPIC_TITLE} has beed received', '<div style=\"border: 3px dotted #e0e1e0;\">\n<div style=\"padding: 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 40px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 30px;\">{SITETITLE}: New Help Topic</div>\n<div style=\"padding: 40px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"color: #727376; line-height: 2;\">We receive new help topic (<strong style=\"color: #4b4b4d;\">{HELPTOPIC_TITLE}</strong>) of department (<strong style=\"color: #4b4b4d;\">{DEPARTMENT_TITLE}</strong>). We try to reply all tickets as soon as possible, usually within 24 hours.</div>\n</div>\n<div style=\"background: #fef2ef; padding: 25px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 16px; margin-bottom: 15px; color: #983133; text-transform: uppercase;\">Do not reply on this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© 2014-{CURRENT_YEAR} All rights reserved - <a href=\"https://www.majesticsupport.com\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>', NULL, 0),
                                (6, 'reassign-tk', '', '{SITETITLE}: Reassign Ticket', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Ticket Reassigned</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"color: #4b4b4d; line-height: 2;\">Your support ticket (<strong style=\"color: #242223;\">{SUBJECT}</strong>) with ticket id (<strong style=\"color: #242223;\">{TRACKINGID}</strong>) has been successfully reassigned to Agent:{AGENT_NAME}.</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-weight: bold; font-size: 16px; margin-bottom: 10px; color: #242223; line-height: 2;\">You will receive email notification when our agent replies to your ticket. You can view the status of your ticket here:</div>\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px 70px; background: #392c7d; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; font-weight: bold; border-bottom: 4px solid #1a1a1a;\" href=\"{TICKETURL}\">View Ticket</a></div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (7, 'close-tk', '', '{SITETITLE}: Close Ticket', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Ticket Closed</div>\n<div style=\"padding: 40px 20px 20px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"color: #727376; line-height: 2;\">Your support ticket (<strong style=\"color: #242223;\">{SUBJECT}</strong>) with ticket id (<strong style=\"color: #242223;\">{TRACKINGID}</strong>) is closed.</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-weight: bold; font-size: 16px; margin-bottom: 10px; line-height: 2; color: #242223;\">You can view the status of your ticket here:</div>\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px 70px; background: #392c7d; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; font-weight: bold; border-bottom: 4px solid #1a1a1a;\" href=\"{TICKETURL}\">View Ticket</a></div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (8, 'delete-tk', '', '{SITETITLE}: Delete Ticket', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Ticket Deleted</div>\n<div style=\"padding: 40px 20px 20px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px;\">\n<div style=\"color: #727376; line-height: 2;\">Your support ticket (<strong style=\"color: #242223;\">{SUBJECT}</strong>) with ticket id (<strong style=\"color: #242223;\">{TRACKINGID}</strong>) is deleted.</div>\n</div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (9, 'moverdue-tk', '', '{SITETITLE}: Overdue Ticket', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Ticket Marked Overdue</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"color: #727376; line-height: 2;\">Your support ticket (<strong style=\"color: #242223;\">{SUBJECT}</strong>) with ticket id (<strong style=\"color: #242223;\">{TRACKINGID}</strong>) is marked overdue.</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-weight: bold; font-size: 16px; margin-bottom: 10px; color: #242223; line-height: 2;\">You will receive email notification when our agent replies to your ticket. You can view the status of your ticket here:</div>\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px 70px; background: #392c7d; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; font-weight: bold; border-bottom: 4px solid #1a1a1a;\" href=\"{TICKETURL}\">View Ticket</a></div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (10, 'banemail-tk', '', '{SITETITLE}: Email Baned', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Email Banned</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px;\">\n<div style=\"color: #727376; line-height: 2;\">This email (<strong style=\"color: #242223;\">{EMAIL_ADDRESS}</strong>) is Banned.</div>\n</div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (11, 'deptrans-tk', '', '{SITETITLE}: Ticket Transfered to Department {DEPARTMENT_TITLE}', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Ticket Transfered To Department</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px;\">\n<div style=\"color: #727376; line-height: 2;\">Your support ticket (<strong style=\"color: #242223;\">{SUBJECT}</strong>) is transferred to department (<strong style=\"color: #242223;\">{DEPARTMENT_TITLE}</strong>).</div>\n</div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (12, 'banemailcloseticket-tk', '', '{SITETITLE}: Email baned and ticket closed', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Email Baned and ticket closed</div>\n<div style=\"padding: 40px 20px 20px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px;\">\n<div style=\"color: #727376; line-height: 2;\">Your support ticket (<strong style=\"color: #242223;\">{SUBJECT}</strong>) with this email (<strong style=\"color: #242223;\">{EMAIL_ADDRESS}</strong>) is Baned and ticket ID:(<strong style=\"color: #242223;\">{TICKETID}</strong>) is closed.</div>\n</div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (13, 'unbanemail-tk', '', '{SITETITLE}: Email Unbaned', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}:Email Unbaned</div>\n<div style=\"padding: 40px 20px 20px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px;\">\n<div style=\"color: #727376; line-height: 2;\">This email (<strong style=\"color: #242223;\">{EMAIL_ADDRESS}</strong>) is unbaned.</div>\n</div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (14, 'reply-tk', '', '{SITETITLE}: Ticket Reply', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Ticket Reply</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"color: #727376; line-height: 2;\">A new reply of ticket (<strong style=\"color: #242223;\">{SUBJECT}</strong>) has been submitted with the following details.</div>\n</div>\n<div style=\"border: 1px solid #e0e1e0;\">\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Tracking ID: {TRACKINGID}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Email: {EMAIL}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Message: {MESSAGE}</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-weight: bold; font-size: 16px; margin-bottom: 10px; color: #242223; line-height: 2;\">You can view the status of your ticket here:</div>\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px 70px; background: #392c7d; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; font-weight: bold; border-bottom: 4px solid #1a1a1a;\" href=\"{TICKETURL}\">View Ticket</a></div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (15, 'responce-tk', '', '{SITETITLE}: Ticket Response', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Ticket Response</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #242223;\">Dear {USERNAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Agent just replied to your ticket (<strong style=\"color: #242223;\">{SUBJECT}</strong>) with the following details.</div>\n</div>\n<div style=\"border: 1px solid #e0e1e0;\">\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Tracking ID: {TRACKINGID}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Email ID: {EMAIL}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Message: {MESSAGE}</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-weight: bold; line-height: 2; font-size: 16px; margin-bottom: 10px; color: #242223;\">You can view the status of your ticket here:</div>\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px 70px; background: #392c7d; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; font-weight: bold; border-bottom: 4px solid #1a1a1a;\" href=\"{TICKETURL}\">View Ticket</a></div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (16, 'ticket-staff', '', '{SITETITLE}: Ticket Received', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Ticket Received</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px;\">\n<div style=\"color: #727376; line-height: 2;\">New ticket (<strong style=\"color: #242223;\">{SUBJECT}</strong>) has been submitted with the following details.</div>\n</div>\n<div style=\"border: 1px solid #ebecec;\">\n<div style=\"border-bottom: 1px solid #ebecec; color: #727376; padding: 15px;\">Tracking ID: {TRACKINGID}</div>\n<div style=\"border-bottom: 1px solid #ebecec; color: #727376; padding: 15px;\">Email ID: {EMAIL}</div>\n<div style=\"border-bottom: 1px solid #ebecec; color: #727376; padding: 15px;\">Ticket Message: {MESSAGE}</div>\n<div style=\"color: #727376; padding: 15px;\">Help Topic: {HELP_TOPIC}</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-weight: bold; font-size: 16px; margin-bottom: 10px; line-height: 2; color: #242223;\">You can view the status of your ticket here:</div>\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px 70px; background: #392c7d; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; font-weight: bold; border-bottom: 4px solid #1a1a1a;\" href=\"{TICKETURL}\">View Ticket</a></div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (17, 'banemail-trtk', '', '{SITETITLE}: Banemail try new ticket', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Email Banned</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #242223;\">Dear Admin,</div>\n<div style=\"color: #727376; line-height: 2;\">This email (<strong style=\"color: #242223;\">{EMAIL_ADDRESS}</strong>) is banned and try open new ticket.</div>\n</div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (18, 'ticket-new-admin', '', '{SITETITLE}: Ticket Received', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Ticket Received</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #242223;\">Dear Admin,</div>\n<div style=\"color: #727376; line-height: 2;\">A new support ticket (<strong style=\"color: #242223;\">{SUBJECT}</strong>) has been submitted with the following details.</div>\n</div>\n<div style=\"border: 1px solid #e0e1e0;\">\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Tracking ID: {TRACKINGID}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Email: {EMAIL}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Message: {MESSAGE}</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-weight: bold; font-size: 16px; margin-bottom: 10px; color: #242223; line-height: 2;\">You can manage the ticket here:</div>\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px 70px; background: #392c7d; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; font-weight: bold; border-bottom: 4px solid #1a1a1a;\" href=\"{TICKETURL}\">View Ticket</a></div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (19, 'lock-tk', '', '{SITETITLE}: Ticket {TRACKINGID} has been locked', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Ticket Locked</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #242223;\">Dear {USERNAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Your support ticket (<strong style=\"color: #242223;\">{TRACKINGID}</strong>) has been locked.</div>\n</div>\n<div style=\"border: 1px solid #e0e1e0;\">\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">User Name: {USERNAME}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Subject : {SUBJECT}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Ticket ID : {TRACKINGID}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Email : {EMAIL}</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-weight: bold; font-size: 16px; margin-bottom: 10px; line-height: 2; color: #242223;\">You will receive email notification when our agent replies to your ticket. You can view the status of your ticket here:</div>\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px 70px; background: #392c7d; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; font-weight: bold; border-bottom: 4px solid #1a1a1a;\" href=\"{TICKETURL}\">View Ticket</a></div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (21, 'unlock-tk', '', '{SITETITLE}: Ticket {TRACKINGID} has been unlocked', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Ticket Unlocked</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #242223;\">Dear {USERNAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Your support ticket (<strong style=\"color: #242223;\">{TRACKINGID}</strong>) has been unlocked.</div>\n</div>\n<div style=\"border: 1px solid #e0e1e0;\">\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">User Name: {USERNAME}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Subject : {SUBJECT}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Ticket ID : {TRACKINGID}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Email : {EMAIL}</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-weight: bold; font-size: 16px; margin-bottom: 10px; color: #242223; line-height: 2;\">You will receive email notification when our agent replies to your ticket. You can view the status of your ticket here:</div>\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px 70px; background: #392c7d; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; font-weight: bold; border-bottom: 4px solid #1a1a1a;\" href=\"{TICKETURL}\">View Ticket</a></div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (22, 'minprogress-tk', '', '{SITETITLE}: In progress Ticket', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Ticket Marked in progress</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"color: #727376; line-height: 2;\">Your support ticket (<strong style=\"color: #242223;\">{SUBJECT}</strong>) with ticket id (<strong style=\"color: #4b4b4d;\">{TRACKINGID}</strong>) is marked in progress.</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-weight: bold; font-size: 16px; line-height: 2; margin-bottom: 10px; color: #242223;\">You will receive email notification when our agent replies to your ticket. You can view the status of your ticket here:</div>\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px 70px; background: #392c7d; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; font-weight: bold; border-bottom: 4px solid #1a1a1a;\" href=\"{TICKETURL}\">View Ticket</a></div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (23, 'prtrans-tk', '', '{SITETITLE}: Ticket priority changed', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Ticket priority is changed</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"color: #727376; line-height: 2;\">Your support ticket (<strong style=\"color: #242223;\">{SUBJECT}</strong>) with ticket id (<strong style=\"color: #242223;\">{TRACKINGID}</strong>) priority changed and new priority is (<strong style=\"color: #242223;\">{PRIORITY_TITLE}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-weight: bold; line-height: 2; font-size: 16px; margin-bottom: 10px; color: #242223;\">You will receive email notification when our agent replies to your ticket. You can view the status of your ticket here:</div>\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px 70px; background: #392c7d; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; font-weight: bold; border-bottom: 4px solid #1a1a1a;\" href=\"{TICKETURL}\">View Ticket</a></div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (24, 'mail-new', '', '{SITETITLE}: New Mail has been sent by {AGENT_NAME}', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: New Mail</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"color: #727376; line-height: 2;\">New mail has been sent by the (<strong style=\"color: #242223;\">{AGENT_NAME}</strong>) with the following details.</div>\n</div>\n<div style=\"border: 1px solid #e0e1e0; margin-bottom: 25px;\">\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Mail Subject : {SUBJECT}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Message : {MESSAGE}</div>\n</div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (25, 'mail-rpy', '', '{SITETITLE}: New reply has been sent by {AGENT_NAME}', '<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: New Reply</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"color: #727376; line-height: 2;\">New reply has been sent by the (<strong style=\"color: #242223;\">{AGENT_NAME}</strong>) with the following details.</div>\n</div>\n<div style=\"border: 1px solid #e0e1e0; margin-bottom: 25px;\">\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Mail Subject : {SUBJECT}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Message : {MESSAGE}</div>\n</div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n', NULL, 0),
                                (26,'mail-rpy-closed','', '{SITETITLE}: Ticket has been closed', '<div style=\"background-color: #f7f7f7;padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px;display: block;margin: 0 auto;padding-bottom: 20px;\">\n<div style=\"border-top:7px solid #1a1a1a; padding:25px; background:#392c7d; color: #fff; font-size: 50px;text-align: center; font-weight: bold; text-transform: capitalize;text-shadow: 0px 4px 0px #1a1a1a;border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding:30px 45px;text-align: center;font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Closed Ticket</div>\n<div style=\"padding:30px;background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"color: #727376; line-height: 2;\">Your support ticket (<strong style=\"color: #242223;\">{TICKET_SUBJECT}</strong>) has been closed.</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-weight: bold; font-size: 16px;line-height: 2; margin-bottom: 10px; color: #242223;\">You can not reply to a closed ticket.</div>\n</div>\n<div style=\"color: #727376; line-height: 2;padding-bottom:20px; \">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background:#392c7d; padding: 20px; color: #fff; text-align: center;border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a target=\"_blank\" href=\"https://www.majesticsupport.com\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n',NULL,0),
                                (27,'mail-feedback','','{SITETITLE}: Give Us Your Feedback','<div style=\"background-color: #f7f7f7; padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px; display: block; margin: 0 auto; padding-bottom: 20px;\">\n<div style=\"border-top: 7px solid #1a1a1a; padding: 25px; background: #392c7d; color: #fff; font-size: 50px; text-align: center; font-weight: bold; text-transform: capitalize; text-shadow: 0px 4px 0px #1a1a1a; border-bottom: 1px solid #242223;\">Majestic Support</div>\n<div style=\"padding: 30px 45px; text-align: center; font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Give Us Your Feedback</div>\n<div style=\"padding: 30px; background-color: #fff;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #e0e1e0;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #242223;\">Dear {USERNAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Your support ticket (<strong style=\"color: #242223;\">{TICKET_SUBJECT}</strong>) having tracking id (<strong style=\"color: #242223;\">{TRACKING_ID}</strong>) has been closed on (<strong style=\"color: #242223;\">{CLOSE_DATE}</strong>).</div>\n</div>\n<div style=\"border: 1px solid #e0e1e0;\">\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Mail Subject : {SUBJECT}</div>\n<div style=\"border-bottom: 1px solid #e0e1e0; color: #727376; padding: 15px;\">Message : {MESSAGE}</div>\n</div>\n<div style=\"padding: 20px 0;\">\n<div style=\"font-weight: bold; line-height: 2; font-size: 16px; margin-bottom: 10px; color: #242223;\">We would really appreciate if you took the time to tell us how well our agent helped you in your problem.</div>\n</div>\n<div style=\"text-align: center; margin-bottom: 40px;\">{LINK}link text{/LINK}</div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 20px;\">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background: #392c7d; padding: 20px; color: #fff; text-align: center; border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved – <a href=\"https://www.majesticsupport.com\" target=\"_blank\" rel=\"noopener\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n',NULL,0),
                                (28,'delete-user-data','','{SITETITLE}: Delete User Data','<div style=\"background-color: #f7f7f7;padding: 70px 0; width: 100%;\">\n<div style=\"width: 650px;display: block;margin: 0 auto;padding-bottom: 20px;\">\n<div style=\"border-top:7px solid #1a1a1a; padding:25px; background:#392c7d; color: #fff; font-size: 50px;text-align: center; font-weight: bold; text-transform: capitalize;text-shadow: 0px 4px 0px #1a1a1a;border-bottom: 1px solid #4b4b4d;\">Majestic Support</div>\n<div style=\"padding:30px 45px;text-align: center;font-weight: bold; background: #e4e7e8; color: #1a1a1a; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Data Delete request</div>\n<div style=\"padding:30px;background-color: #fff;\">\n<div style=\"padding-bottom: 20px;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #242223;\">Dear {USERNAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Your data delete request has been received.</div>\n</div>\n<div style=\"color: #727376; line-height: 2;padding-bottom:20px; \">This email was sent from <a href=\"https://www.majesticsupport.com\"><span style=\"color: #3e4095; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n<div style=\"padding: 15px 0;\">\n<div style=\"font-weight: bold; font-size: 14px; padding-bottom: 5px; color: #751f20; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n</div>\n<div style=\"background:#392c7d; padding: 20px; color: #fff; text-align: center;border-bottom: 4px solid #1a1a1a;\">© 2014-{CURRENT_YEAR} All rights reserved &#8211; <a target=\"_blank\" href=\"https://www.majesticsupport.com\"><span style=\"color: white; display: inline-block; text-decoration: underline; cursor: pointer;\">Majestic Support System</span></a></div>\n</div>\n</div>\n',NULL,0);"
                                ;
            majesticsupport::$_db->query($query);
            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `priority` varchar(60) DEFAULT NULL,
                                `prioritycolour` varchar(7) DEFAULT NULL,
                                `priorityurgency` int(1) DEFAULT NULL,
                                `overduetypeid` int(5) DEFAULT NULL,
                                `overdueinterval` int(5) DEFAULT NULL,
                                `ispublic` varchar(45) DEFAULT NULL,
                                `ordering` int(11) NOT NULL,
                                `isdefault` tinyint(1) DEFAULT NULL,
                                `status` tinyint(4) NOT NULL DEFAULT '0',
                                PRIMARY KEY (`id`)
                                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;";
            majesticsupport::$_db->query($query);
            $query = "INSERT INTO `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` (`id`, `priority`, `prioritycolour`, `priorityurgency`, `ispublic`, `overdueinterval`, `overduetypeid`, `ordering`, `isdefault`, `status`) VALUES (1, 'Low', '#049fc1', 0, 1, 3, '1', 1, 1, 0),(2, 'High', '#bd6403', 0, 1, 1, '1', 3, 0, 1),(3, 'Normal', '#188f28', 0, 1, 2, '1', 2, 0, 1),(4, 'Urgent', '#c90000', 0, 1, 1, '1', 4, 0, 0);";
            majesticsupport::$_db->query($query);
            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_replies` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `uid` int(11) NOT NULL,
                                `ticketid` int(11) DEFAULT NULL,
                                `name` varchar(50) DEFAULT NULL,
                                `message` text,
                                `staffid` int(11) DEFAULT NULL,
                                `rating` enum('1','5') DEFAULT NULL,
                                `status` tinyint(1) DEFAULT NULL,
                                `created` datetime DEFAULT NULL,
                                `ticketviaemail` tinyint(1) NOT NULL,
                                `mergemessage` TINYINT(1) NOT NULL DEFAULT '0',
                                PRIMARY KEY (`id`)
                                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            majesticsupport::$_db->query($query);
            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_system_errors` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `uid` int(11) DEFAULT NULL,
                                `error` text,
                                `isview` tinyint(1) DEFAULT '0',
                                `created` datetime DEFAULT NULL,
                                PRIMARY KEY (`id`)
                                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            majesticsupport::$_db->query($query);
            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_smartreplies` (
                                `id` INT(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
                                `title` VARCHAR(255) DEFAULT NULL,
                                `ticketsubjects` TEXT,
                                `reply` text,
                                `usedby` tinyint(1) DEFAULT '0',
                                `created` datetime DEFAULT NULL,
                                FULLTEXT (ticketsubjects)
                                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            majesticsupport::$_db->query($query);
            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `uid` int(11) DEFAULT NULL,
                                `ticketid` varchar(35) DEFAULT NULL,
                                `internalid` varchar(35) DEFAULT NULL,
                                `departmentid` int(11) DEFAULT NULL,
                                `priorityid` int(11) DEFAULT NULL,
                                `staffid` int(11) DEFAULT NULL,
                                `email` varchar(255) DEFAULT NULL,
                                `name` varchar(100) DEFAULT NULL,
                                `subject` varchar(255) DEFAULT NULL,
                                `message` text,
                                `helptopicid` int(11) DEFAULT NULL,
								`multiformid` INT DEFAULT 1,
                                `phone` varchar(100) DEFAULT NULL,
                                `phoneext` varchar(25) DEFAULT NULL,
                                `status` tinyint(1) DEFAULT NULL,
                                `isoverdue` tinyint(1) DEFAULT NULL,
                                `isanswered` tinyint(1) NOT NULL DEFAULT '0',
                                `duedate` datetime DEFAULT NULL,
                                `reopened` datetime DEFAULT NULL,
                                `closed` datetime DEFAULT NULL,
                                `closedby` int(11) DEFAULT NULL,
                                `closedreason` text DEFAULT NULL,
                                `lastreply` datetime DEFAULT NULL,
                                `created` datetime DEFAULT NULL,
                                `updated` datetime DEFAULT NULL,
                                `lock` tinyint(4) NOT NULL DEFAULT '0',
                                `ticketviaemail` tinyint(1) NOT NULL,
                                `ticketviaemail_id` INT(11) DEFAULT NULL,
                                `attachmentdir` VARCHAR(50) NOT NULL,
                                `feedbackemail` TINYINT NOT NULL,
                                `mergestatus` TINYINT(4) NOT NULL DEFAULT '0',
                                `mergewith` INT(11) DEFAULT NULL,
                                `mergenote` TEXT DEFAULT NULL,
                                `mergedate` datetime DEFAULT NULL,
                                `multimergeparams` TEXT DEFAULT NULL,
                                `mergeuid` INT(11) DEFAULT NULL,
                                `params` longtext NULL,
                                `hash` varchar(200) COLLATE 'utf8_general_ci' NULL,
                                `notificationid` INT NOT NULL,
								`wcorderid` bigint NULL,
								`wcitemid` bigint NULL,
								`wcproductid` bigint NULL,
                                `eddorderid` INT NULL,
                                `eddproductid` INT NULL,
                                `eddlicensekey` VARCHAR(250) NULL,
                                `envatodata` text NULL,
                                `paidsupportitemid` bigint(20) NULL,
                                `customticketno` INT NOT NULL DEFAULT '1',
                                PRIMARY KEY (`id`)
                                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            majesticsupport::$_db->query($query);
            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `field` varchar(50) NOT NULL,
                        `fieldtitle` varchar(50) DEFAULT NULL,
                        `ordering` int(11) DEFAULT NULL,
                        `section` varchar(20) DEFAULT NULL,
                        `fieldfor` tinyint(2) DEFAULT NULL,
                        `published` tinyint(1) DEFAULT NULL,
                        `sys` tinyint(1) NOT NULL,
                        `cannotunpublish` tinyint(1) NOT NULL,
                        `required` tinyint(1) NOT NULL DEFAULT '0',
                        `size` varchar(200) DEFAULT NULL,
                        `maxlength` varchar(200) DEFAULT NULL,
                        `cols` varchar(200) DEFAULT NULL,
                        `rows` varchar(200) DEFAULT NULL,
                        `isuserfield` tinyint(4) DEFAULT NULL,
                        `userfieldtype` varchar(250) DEFAULT NULL,
                        `depandant_field` varchar(250) DEFAULT NULL,
                        `visible_field` varchar(250) DEFAULT NULL,
                        `showonlisting` tinyint(4) DEFAULT NULL,
                        `cannotshowonlisting` tinyint(4) DEFAULT NULL,
                        `search_user` tinyint(4) DEFAULT NULL,
                        `cannotsearch` tinyint(4) DEFAULT NULL,
                        `isvisitorpublished` tinyint(4) DEFAULT NULL,
						`multiformid` INT DEFAULT 1,
                        `userfieldparams` longtext,
                        `visibleparams` longtext,
                        PRIMARY KEY (`id`),KEY `fieldordering_filedfor` (`fieldfor`))
                        ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14;";
            majesticsupport::$_db->query($query);
            $query = "INSERT INTO `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` (`id`, `field`, `fieldtitle`, `ordering`, `section`, `fieldfor`, `published`, `sys`, `cannotunpublish`, `required`,`cannotsearch`,`cannotshowonlisting`,`isvisitorpublished`) VALUES (1, 'email', 'Email Address', 2, '10', 1, 1, 0, 0, 1, 1, 1, 1),  (15, 'users', 'Users', 1, '10', 1, 1, 0, 0, 0, 1, 1, 1),  (2, 'fullname', 'Full Name', 3, '10', 1, 1, 0, 0, 1, 1, 1, 1),  (3, 'phone', 'Phone', 4, '10', 1, 1, 0, 0, 0, 1, 1, 1),  (4, 'department', 'Department', 5, '10', 1, 1, 0, 0, 0, 1, 1, 1),  (5, 'helptopic', 'Help Topic', 6, '10', 1, 1, 0, 0, 0, 1, 1, 1),  (6, 'priority', 'Priority', 7, '10', 1, 1, 0, 0, 1, 1, 1, 1),  (7, 'subject', 'Subject', 8, '10', 1, 1, 0, 1, 1, 1, 1, 1),  (8, 'premade', 'Premade Response', 9, '10', 1, 1, 0, 0, 0, 1, 1, 1),  (9, 'issuesummary', 'Issue Summary', 10, '10', 1, 1, 0, 0, 1, 1, 1, 1),  (10, 'attachments', 'Attachments', 11, '10', 1, 1, 0, 0, 0, 1, 1, 1),  (11, 'internalnotetitle', 'Internal Note Title', 12, '10', 1, 1, 0, 0, 0, 1, 1, 1),  (12, 'assignto', 'Assign To', 13, '10', 1, 1, 0, 0, 0, 1, 1, 1),  (13, 'duedate', 'Due Date', 14, '10', 1, 1, 0, 0, 0, 1, 1, 1),  (14, 'status', 'Status', 15, '10', 1, 1, 0, 0, 0, 1, 1, 1),  (16, 'rating', 'Rating', 1, '10', 2, 1, 0, 0, 0, 0, 0, 1),  (17, 'remarks', 'Remarks', 2, '10', 2, 1, 0, 0, 0, 0, 0, 1)
			, (18, 'wcorderid', 'Order ID', 16, '10', 1, 1, 0, 0, 0, 0, 0, 1), (19, 'wcproductid', 'Product', 17, '10', 1, 1, 0, 0, 0, 1, 0, 1), (20, 'eddorderid', 'EDD Order ID', 18, '10', 1, 1, 0, 0, 0, 0, 0, 1), (21, 'eddproductid', 'Product', 19, '10', 1, 1, 0, 0, 0, 0, 0, 1), (22, 'eddlicensekey', 'License Key', 20, '10', 1, 1, 0, 0, 0, 1, 0, 1), (23, 'envatopurchasecode', 'Envato Purchase Code', 18, '10', 1, 1, 0, 0, 0, 1, 1, 1)
			;";
            majesticsupport::$_db->query($query);
            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_erasedatarequests` (
                      `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                      `uid` int(11) NOT NULL,
                      `subject` varchar(250) NOT NULL,
                      `message` text NOT NULL,
                      `status` int(11) NOT NULL,
                      `created` datetime NOT NULL
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            majesticsupport::$_db->query($query);
            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_mjtcsessiondata` (
              `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `usersessionid` char(64) NOT NULL,
              `sessionmsg` text CHARACTER SET utf8 NOT NULL,
              `sessionexpire` bigint(32) NOT NULL,
              `sessionfor` varchar(125) NOT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            majesticsupport::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_users` (
              `id` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `wpuid` bigint(20),
              `name` varchar(250),
              `display_name` varchar(250),
              `user_nicename` varchar(250),
              `user_email` varchar(250) NOT NULL,
              `status` int(11) NOT NULL,
              `issocial` int(11),
              `socialid` varchar(250) NOT NULL,
              `created` datetime NOT NULL,
              `autogenerated` int(2)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            majesticsupport::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . majesticsupport::$_db->prefix . "mjtc_support_slug` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `slug` varchar(100) CHARACTER SET utf8 NOT NULL,
              `defaultslug` varchar(100) CHARACTER SET utf8 NOT NULL,
              `filename` varchar(100) CHARACTER SET utf8 NOT NULL,
              `description` varchar(200) CHARACTER SET utf8 NOT NULL,
              `status` tinyint(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=64;";
            majesticsupport::$_db->query($query);

            $query = "INSERT INTO `" . majesticsupport::$_db->prefix . "mjtc_support_slug` (`id`, `slug`, `defaultslug`, `filename`, `description`, `status`) VALUES
              (1, 'ticket', 'ticket', 'ticketdetail', 'slug for ticket page', 1),
              (2, 'agent-add-ticket', 'agent-add-ticket', 'staffaddticket', 'slug for agent add ticket page', 1),
              (3, 'role-permission', 'role-permission', 'rolepermission', 'slug for rolepermission page', 1),
              (4, 'add-announcement', 'add-announcement', 'addannouncement', 'slug for add announcement', 1),
              (5, 'add-department', 'add-department', 'adddepartment', 'slug for add department page', 1),
              (6, 'add-download', 'add-download', 'adddownload', 'slug for add download page', 1),
              (7, 'add-faq', 'add-faq', 'addfaq', 'slug for add faq page', 1),
              (8, 'faq', 'faq', 'faqdetails', 'slug for faq page', 1),
              (9, 'add-article', 'add-article', 'addarticle', 'slug for add article page', 1),
              (10, 'add-category', 'add-category', 'addcategory', 'slug for add category page', 1),
              (11, 'kb-articles', 'kb-articles', 'userknowledgebasearticles', 'slug for user knowledgebase articles page', 1),
              (12, 'kb-article', 'kb-article', 'articledetails', 'slug for article detail page', 1),
              (13, 'add-role', 'add-role', 'addrole', 'slug for add role page', 1),
              (14, 'add-agent', 'add-agent', 'addstaff', 'slug for add agent page', 1),
              (15, 'agent-permissions', 'agent-permissions', 'staffpermissions', 'slug for agent permissions page', 1),
              (17, 'my-tickets', 'my-tickets', 'myticket', 'slug for my tickets page', 1),
              (18, 'agent-my-tickets', 'agent-my-tickets', 'staffmyticket', 'slug for agent my tickets page', 1),
              (19, 'knowledgebase', 'knowledgebase', 'userknowledgebase', 'slug for knowledgebase page', 1),
              (20, 'agent-categories', 'agent-categories', 'stafflistcategories', 'slug for agent categories page', 1),
              (21, 'agent-kb-articles', 'agent-kb-articles', 'stafflistarticles', 'slug for agent kb articles page', 1),
              (22, 'agent-announcements', 'agent-announcements', 'staffannouncements', 'slug for agent announcements page', 1),
              (23, 'agent-downloads', 'agent-downloads', 'staffdownloads', 'slug for agent downloads page', 1),
              (24, 'agent-faqs', 'agent-faqs', 'stafffaqs', 'slug for agent faqs page', 1),
              (25, 'add-ticket', 'add-ticket', 'addticket', 'slug for add ticket page', 1),
              (26, 'ticket-status', 'ticket-status', 'ticketstatus', 'slug for ticket status page', 1),
              (27, 'control-panel', 'control-panel', 'controlpanel', 'slug for controlpanel', 1),
              (28, 'agent-report', 'agent-report', 'staffdetailreport', 'slug for agent report detail page', 1),
              (29, 'agent-reports', 'agent-reports', 'staffreports', 'slug for agent reports page', 1),
              (30, 'department-reports', 'department-reports', 'departmentreports', 'slug for department reports page', 1),
              (31, 'announcement', 'announcement', 'announcementdetails', 'slug for announcement detail page', 1),
              (32, 'feed-back', 'feed-back', 'formfeedback', 'slug for feedback page', 1),
              (33, 'agent-feedbacks', 'agent-feedbacks', 'feedbacks', 'slug for agent feedbacks page', 1),
              (34, 'visitor-message', 'visitor-message', 'visitormessagepage', 'slug for visitor message page', 1),
              (35, 'add-help-topic', 'add-help-topic', 'addhelptopic', 'slug for add help topic page', 1),
              (36, 'agent-help-topics', 'agent-help-topics', 'agenthelptopics', 'slug for agent help topics page', 1),
              (37, 'add-canned-response', 'add-canned-response', 'addcannedresponse', 'slug for add premade response page', 1),
              (38, 'agent-canned-responses', 'agent-canned-responses', 'agentcannedresponses', 'slug for agent Premade responses page', 1),
              (39, 'gdpr-data-compliance-actions', 'gdpr-data-compliance-actions', 'adderasedatarequest', 'slug for gdpr data compliance actions page', 1),
              (40, 'print-ticket', 'print-ticket', 'printticket', 'slug for print ticket page', 1),
              (41, 'my-profile', 'my-profile', 'myprofile', 'slug for my profile page', 1),
              (42, 'login', 'login', 'login', 'slug for login page', 1),
              (43, 'userregister', 'userregister', 'userregister', 'slug for user register page', 1),
              (45, 'add-message', 'add-message', 'formmessage', 'slug for add message page', 1),
              (46, 'message', 'message', 'message', 'slug for message detail page', 1),
              (47, 'message-inbox', 'message-inbox', 'inbox', 'slug for message inbox page', 1),
              (49, 'message-outbox', 'message-outbox', 'outbox', 'slug for message outbox page', 1),
              (50, 'roles', 'roles', 'roles', 'slug for roles page', 1),
              (51, 'agents', 'agents', 'staffs', 'slug for agent page', 1),
              (52, 'departments', 'departments', 'departments', 'slug for departments page', 1),
              (53, 'announcements', 'announcements', 'announcements', 'slug for announcements page', 1),
              (54, 'downloads', 'downloads', 'downloads', 'slug for downloads page', 1),
              (55, 'faqs', 'faqs', 'faqs', 'slug for faqs page', 1),
              (56, 'smartreplies', 'smartreplies', 'smartreplies', 'slug for smart replies page', 1),
              (57, 'add-smartreply', 'add-smartreply', 'addsmartreply', 'slug for add smart reply page', 1),
              (58, 'ticket-close-reasons', 'ticket-close-reasons', 'ticketclosereasons', 'slug for ticket close reasons page', 1),
              (59, 'add-ticket-close-reason', 'add-ticket-close-reason', 'addticketclosereason', 'slug for add ticket close reason page', 1),
              (60, 'agent-export', 'export', 'export', 'slug for export page', 1),
              (61, 'banemails', 'banemails', 'banemails', 'slug for ban emails page', 1),
              (62, 'add-banemail', 'add-banemail', 'addbanemail', 'slug for add banemail page', 1);";

            majesticsupport::$_db->query($query);
        }
    }

}

?>
