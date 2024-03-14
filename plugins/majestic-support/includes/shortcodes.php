<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_shortcodes {

    function __construct() {
        add_shortcode('majesticsupport', array($this, 'MJTC_show_main_ticket'));
        add_shortcode('majesticsupport_addticket', array($this, 'MJTC_show_form_ticket'));
        if( in_array('multiform', majesticsupport::$_active_addons) ){
            add_shortcode('majesticsupport_addticket_multiform', array($this, 'MJTC_show_form_ticket_for_multiform'));
        }
        add_shortcode('majesticsupport_mytickets', array($this, 'MJTC_show_my_ticket'));
    }

    function MJTC_show_main_ticket($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'mjsmod' => '',
            'mjslay' => '',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(majesticsupport::$_data['sanitized_args']) && !empty(majesticsupport::$_data['sanitized_args'])){
            majesticsupport::$_data['sanitized_args'] += $sanitized_args;
        }else{
            majesticsupport::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        majesticsupport::setPageID($pageid);
        MJTC_includer::MJTC_include_slug('');
        $content .= ob_get_clean();
        return $content;
    }

    function MJTC_show_form_ticket($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $pageid = get_the_ID();
        majesticsupport::setPageID($pageid);
        $module = MJTC_Request::MJTC_getVar('mjsmod', '', 'ticket');
        $layout = MJTC_Request::MJTC_getVar('mjslay', '', 'addticket');
        if ($layout != 'addticket' && $layout != 'staffaddticket') {
            $module = MJTC_majesticsupportphplib::MJTC_str_replace('majesticsupport_', '', $module);
            MJTC_includer::MJTC_include_file($module);
        } else {
            $defaults = array(
                'job_type' => '',
                'city' => '',
                'company' => '',
            );
            $sanitized_args = shortcode_atts($defaults, $raw_args);
            if(isset(majesticsupport::$_data['sanitized_args']) && !empty(majesticsupport::$_data['sanitized_args'])){
                majesticsupport::$_data['sanitized_args'] += $sanitized_args;
            }else{
                majesticsupport::$_data['sanitized_args'] = $sanitized_args;
            }
            majesticsupport::$_data['short_code_header'] = 'addticket';
            if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                $id = MJTC_request::MJTC_getVar('majesticsupportid');
                $per_task = ($id == null) ? 'Add Ticket' : 'Edit Ticket';
                majesticsupport::$_data['permission_granted'] = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask($per_task);
                if (majesticsupport::$_data['permission_granted']) {
                    MJTC_includer::MJTC_getModel('ticket')->getTicketsForForm($id);
                }
                MJTC_includer::MJTC_include_file('staffaddticket', 'agent');
            } else {
                MJTC_includer::MJTC_getModel('ticket')->getTicketsForForm(null);
                MJTC_includer::MJTC_include_file('addticket', 'ticket');
            }
        }
        $content .= ob_get_clean();
        return $content;
    }

    function MJTC_show_form_ticket_for_multiform($raw_args, $content = null) {
        $formid = $raw_args['formid'];
        //default set of parameters for the front end shortcodes
        ob_start();
        $pageid = get_the_ID();
        majesticsupport::setPageID($pageid);
        $module = MJTC_Request::MJTC_getVar('mjsmod', '', 'ticket');
        $layout = MJTC_Request::MJTC_getVar('mjslay', '', 'addticket');
        if ($layout != 'addticket' && $layout != 'staffaddticket') {
            $module = MJTC_majesticsupportphplib::MJTC_str_replace('majesticsupport_', '', $module);
            MJTC_includer::MJTC_include_file($module);
        } else {
            $defaults = array(
                'job_type' => '',
                'city' => '',
                'company' => '',
            );
            $sanitized_args = shortcode_atts($defaults, $raw_args);
            if(isset(majesticsupport::$_data['sanitized_args']) && !empty(majesticsupport::$_data['sanitized_args'])){
                majesticsupport::$_data['sanitized_args'] += $sanitized_args;
            }else{
                majesticsupport::$_data['sanitized_args'] = $sanitized_args;
            }
            majesticsupport::$_data['short_code_header'] = 'addticket';
            if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                $id = MJTC_request::MJTC_getVar('majesticsupportid');
                $per_task = ($id == null) ? 'Add Ticket' : 'Edit Ticket';
                majesticsupport::$_data['permission_granted'] = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask($per_task);
                if (majesticsupport::$_data['permission_granted']) {
                    MJTC_includer::MJTC_getModel('ticket')->getTicketsForForm($id, $formid);
                }
                MJTC_includer::MJTC_include_file('staffaddticket', 'agent');
            } else {
                MJTC_includer::MJTC_getModel('ticket')->getTicketsForForm(null, $formid);
                MJTC_includer::MJTC_include_file('addticket', 'ticket');
            }
        }
        $content .= ob_get_clean();
        return $content;
    }

    function MJTC_show_my_ticket($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $pageid = get_the_ID();
        majesticsupport::setPageID($pageid);
        $module = MJTC_Request::MJTC_getVar('mjsmod', '', 'ticket');
        $layout = MJTC_Request::MJTC_getVar('mjslay', '', 'myticket');
        if ($layout != 'myticket' && $layout != 'staffmyticket') {
            $module = MJTC_majesticsupportphplib::MJTC_str_replace('majesticsupport_', '', $module);
            MJTC_includer::MJTC_include_file($module);
        } else {
            $defaults = array(
                'list' => '',
                'ticketid' => '',
            );
            $list = MJTC_request::MJTC_getVar('list', 'get', null);
            $ticketid = MJTC_request::MJTC_getVar('ticketid', null, null);
            $args = shortcode_atts($defaults, $raw_args);
            if(isset(majesticsupport::$_data['sanitized_args']) && !empty(majesticsupport::$_data['sanitized_args'])){
                majesticsupport::$_data['sanitized_args'] += $args;
            }else{
                majesticsupport::$_data['sanitized_args'] = $args;
            }
            if ($list == null)
                $list = $args['list'];
            if ($ticketid == null)
                $ticketid = $args['ticketid'];
            majesticsupport::$_data['short_code_header'] = 'myticket';
            if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                MJTC_includer::MJTC_getModel('ticket')->getStaffTickets();
                MJTC_includer::MJTC_include_file('staffmyticket', 'agent');
            } else {
                MJTC_includer::MJTC_getModel('ticket')->getMyTickets($list, $ticketid);
                MJTC_includer::MJTC_include_file('myticket', 'ticket');
            }
        }
        $content .= ob_get_clean();
        return $content;
    }

}

$shortcodes = new MJTC_shortcodes();
?>
