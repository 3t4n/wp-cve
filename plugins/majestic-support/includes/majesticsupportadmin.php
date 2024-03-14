<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_majesticsupportadmin {

    function __construct() {
        add_action('admin_menu', array($this, 'MJTC_mainmenu'));
    }

    function MJTC_mainmenu() {
        if (current_user_can('ms_support_ticket')) {
            add_menu_page(esc_html(__('Majestic Support Control Panel', 'majestic-support')), // Page title
                    esc_html(__('Majestic Support', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport', //menu slug
                    array($this, 'MJTC_showAdminPage'), // function name
    			  plugins_url('majestic-support/includes/images/admin_ticket.png'),26
            );
            add_submenu_page('majesticsupport_hide', // parent slug
                    esc_html(__('Slug', 'majestic-support')), // Page title
                    esc_html(__('Slug', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_slug', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );
            add_submenu_page('majesticsupport', // parent slug
                    esc_html(__('Tickets', 'majestic-support')), // Page title
                    esc_html(__('Tickets', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_ticket', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );
            add_submenu_page('majesticsupport', // parent slug
                    esc_html(__('Smart Replies', 'majestic-support')), // Page title
                    esc_html(__('Smart Replies', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_smartreply', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );
            if(!in_array('multiform', majesticsupport::$_active_addons)){ 
				add_submenu_page('majesticsupport', // parent slug
						esc_html(__('Fields', 'majestic-support')), // Page title
						esc_html(__('Fields', 'majestic-support')), // menu title
						'ms_support_ticket', // capability
						'majesticsupport_fieldordering', //menu slug
						array($this, 'MJTC_showAdminPage') // function name
				);
            } 
            if(in_array('agent', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport', // parent slug
                        esc_html(__('Agents', 'majestic-support')), // Page title
                        esc_html(__('Agents', 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_agent', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('agent');
            }
              add_submenu_page('majesticsupport', // parent slug
                    esc_html(__('Settings', 'majestic-support')), // Page title
                    esc_html(__('Settings', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_configuration', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );
             add_submenu_page('majesticsupport', // parent slug
                    esc_html(__('Priorities', 'majestic-support')), // Page title
                    esc_html(__('Priority', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_priority', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );
             add_submenu_page('majesticsupport', // parent slug
                    esc_html(__('Department', 'majestic-support')), // Page title
                    esc_html(__('Departments', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_department', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );
             add_submenu_page('majesticsupport', // parent slug
                    esc_html(__('Colors', 'majestic-support')), // Page title
                    esc_html(__('Colors', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_themes', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );
             add_submenu_page('majesticsupport', // parent slug
                    esc_html(__('Majestic Support', 'majestic-support')), // Page title
                    esc_html(__('Reports', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_reports', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );

            if(in_array('announcement', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport', // parent slug
                        esc_html(__('Announcements', 'majestic-support')), // Page title
                        esc_html(__('Announcements', 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_announcement', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('announcement');
            }
            if(in_array('knowledgebase', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport', // parent slug
                        esc_html(__('Knowledge Base', 'majestic-support')), // Page title
                        esc_html(__('Knowledge Base', 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_knowledgebase', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('knowledgebase');
            }
            add_submenu_page('majesticsupport_hide', // parent slug
                    esc_html(__('Emails', 'majestic-support')), // Page title
                    esc_html(__('System Emails', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_email', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );
            add_submenu_page('majesticsupport_hide', // parent slug
                    esc_html(__('System Error', 'majestic-support')), // Page title
                    esc_html(__('System Errors', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_systemerror', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );
            add_submenu_page('majesticsupport', // parent slug
                    esc_html(__('Email Templates', 'majestic-support')), // Page title
                    esc_html(__('Email Templates', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_emailtemplate', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );
            add_submenu_page('majesticsupport_hide', // parent slug
                esc_html(__('Translations')), // Page title
                esc_html(__('Translations')), // menu title
                'ms_support_ticket', // capability
                'majesticsupport&mjslay=translations', //menu slug
                array($this, 'MJTC_showAdminPage') // function name
            );
            add_submenu_page('majesticsupport_hide', // parent slug
                    esc_html(__('User Fields', 'majestic-support')), // Page title
                    esc_html(__('User Fields', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_userfeild', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );

            if(in_array('cannedresponses', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport_hide', // parent slug
                        esc_html(__('Premade Responses', 'majestic-support')), // Page title
                        esc_html(__('Premade Responses', 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_cannedresponses', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('cannedresponses');
            }

            add_submenu_page('majesticsupport_hide', // parent slug
                    esc_html(__('Roles', 'majestic-support')), // Page title
                    esc_html(__('Roles', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_role', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );

            if(in_array('mail', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport_hide', // parent slug
                        esc_html(__('Mail', 'majestic-support')), // Page title
                        esc_html(__('Mail', 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_mail', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('mail');
            }

            if(in_array('banemail', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport_hide', // parent slug
                        esc_html(__('Ban Email', 'majestic-support')), // Page title
                        esc_html(__('Ban Emails', 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_banemail', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
                add_submenu_page('majesticsupport_hide', // parent slug
                        esc_html(__('Ban list log', 'majestic-support')), // Page title
                        esc_html(__('Ban list log', 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_banemaillog', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('banemail');
                $this->MJTC_addMissingAddonPage('banemaillog');
            }
            add_submenu_page('majesticsupport_hide', // parent slug
                    esc_html(__('Field Ordering', 'majestic-support')), // Page title
                    esc_html(__('Field Ordering', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_fieldordering', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );

            if(in_array('emailpiping', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport_hide', // parent slug
                        esc_html(__('Majestic Support', 'majestic-support')), // Page title
                        esc_html(__('Email Piping', 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_emailpiping', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('emailpiping');
            }


            if(in_array('export', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport_hide', // parent slug
                        esc_html(__('Export', 'majestic-support')), // Page title
                        esc_html(__('Export', 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_export', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('export');
            }

            if(in_array('feedback', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport_hide', // parent slug
                        esc_html(__('Feedbacks', 'majestic-support')), // Page title
                        esc_html(__('Feedbacks', 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_feedback', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('feedback');
            }
            add_submenu_page('majesticsupport_hide', // parent slug
                    esc_html(__('Post Installation', 'majestic-support')), // Page title
                    esc_html(__('Post Installation', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_postinstallation', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );

           if(in_array('faq', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport_hide', // parent slug
                        esc_html(__("FAQs", 'majestic-support')), // Page title
                        esc_html(__("FAQs", 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_faq', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('faq');
            }

            if(in_array('emailcc', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport_hide', // parent slug
                        esc_html(__("Emailcc", 'majestic-support')), // Page title
                        esc_html(__("Emailcc", 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_emailcc', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('emailcc');
            }

            if(in_array('agentautoassign', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport', // parent slug
                        esc_html(__("Agent Auto Assign", 'majestic-support')), // Page title
                        esc_html(__("Agent Auto Assign", 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_agentautoassign', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('agentautoassign');
            }

            if(in_array('ticketclosereason', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport', // parent slug
                        esc_html(__("Ticket Close Reason", 'majestic-support')), // Page title
                        esc_html(__("Ticket Close Reason", 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_ticketclosereason', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('ticketclosereason');
            }


            if(in_array('multiform', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport', // parent slug
                        esc_html(__("Multiform", 'majestic-support')), // Page title
                        esc_html(__("Multiform", 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_multiform', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('multiform');
            }

            if(in_array('download', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport_hide', // parent slug
                    esc_html(__('Downloads', 'majestic-support')), // Page title
                    esc_html(__('Downloads', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_download', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('download');
            }


            add_submenu_page('majesticsupport', // parent slug
                    esc_html(__('Premium Addons', 'majestic-support')), // Page title
                    esc_html(__('Premium Addons', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_premiumplugin', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );

            add_submenu_page('majesticsupport', // parent slug
                    esc_html(__('Help', 'majestic-support')), // Page title
                    esc_html(__('Help', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_majesticsupport&mjslay=help', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );

            // adddons mpage code.



            // if(in_array('knowledgebase', majesticsupport::$_active_addons)){
            //     add_submenu_page('majesticsupport', // parent slug
            //         esc_html(__('Knowledge Base', 'majestic-support')), // Page title
            //         esc_html(__('Knowledge Base', 'majestic-support')), // menu title
            //         'ms_support_ticket', // capability
            //         'majesticsupport_knowledgebase', //menu slug
            //         array($this, 'MJTC_showAdminPage') // function name
            //     );
            // }

            if(in_array('helptopic', majesticsupport::$_active_addons)){
                add_submenu_page('majesticsupport_hide', // parent slug
                        esc_html(__('Help Topics', 'majestic-support')), // Page title
                        esc_html(__('Help Topics', 'majestic-support')), // menu title
                        'ms_support_ticket', // capability
                        'majesticsupport_helptopic', //menu slug
                        array($this, 'MJTC_showAdminPage') // function name
                );
            }else{
                $this->MJTC_addMissingAddonPage('helptopic');
            }

            add_submenu_page('majesticsupport_hide', // parent slug
                    esc_html(__('Colors', 'majestic-support')), // Page title
                    esc_html(__('Colors', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_themes', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );

            add_submenu_page('majesticsupport_hide', // parent slug
                    esc_html(__('GDPR', 'majestic-support')), // Page title
                    esc_html(__('GDPR', 'majestic-support')), // menu title
                    'ms_support_ticket', // capability
                    'majesticsupport_gdpr', //menu slug
                    array($this, 'MJTC_showAdminPage') // function name
            );

        }else{
            add_menu_page(esc_html(__('Majestic Support Control Panel', 'majestic-support')), // Page title
                    esc_html(__('Majestic Support', 'majestic-support')), // menu title
                    'ms_support_ticket_tickets', // capability
                    'majesticsupport_ticket', //menu slug
                    array($this, 'MJTC_showAdminPage'), // function name
                  plugins_url('majestic-support/includes/images/admin_ticket.png'), 26
            );
        }
    }

    function MJTC_addMissingAddonPage($module_name){
        add_submenu_page('majesticsupport_hide', // parent slug
                esc_html(__('Premium Addon', 'majestic-support')), // Page title
                esc_html(__('Premium Addon', 'majestic-support')), // menu title
                'ms_support_ticket', // capability
                'majesticsupport_'.$module_name, //menu slug
                array($this, 'MJTC_showMissingAddonPage') // function name
        );
    }

    function MJTC_showAdminPage() {
        $page = MJTC_request::MJTC_getVar('page');
        $page = MJTC_majesticsupportphplib::MJTC_str_replace('majesticsupport_', '', $page);
        MJTC_includer::MJTC_include_file($page);
    }

    function MJTC_showMissingAddonPage() {
        MJTC_includer::MJTC_include_file('admin_missingaddon','premiumplugin');
    }

}

$majesticsupportAdmin = new MJTC_majesticsupportadmin();
?>
