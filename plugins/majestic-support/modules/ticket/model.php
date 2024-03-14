<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_ticketModel {

    private $ticketid;

    function getTicketsForAdmin($lst=null) {
        $this->getOrdering();
        // Filter
        $search_userfields = MJTC_includer::MJTC_getObjectClass('customfields')->userFieldsForSearch(1);
        $subject = majesticsupport::$_search['ticket']['subject'];
        $name = majesticsupport::$_search['ticket']['name'];
        $email = majesticsupport::$_search['ticket']['email'];
        $ticketid = majesticsupport::$_search['ticket']['ticketid'];
        $datestart = majesticsupport::$_search['ticket']['datestart'];
        $dateend = majesticsupport::$_search['ticket']['dateend'];
        $orderid = majesticsupport::$_search['ticket']['orderid'];
        $eddorderid = majesticsupport::$_search['ticket']['eddorderid'];
        $priority = majesticsupport::$_search['ticket']['priority'];
        $departmentid = majesticsupport::$_search['ticket']['departmentid'];
        $staffid = majesticsupport::$_search['ticket']['staffid'];
        $sortby = majesticsupport::$_search['ticket']['sortby'];
        if (!empty($search_userfields)) {
            foreach ($search_userfields as $uf) {
                $value_array[$uf->field] = majesticsupport::$_search['ms_ticket_custom_field'][$uf->field];
            }
        }
        $inquery = '';
        if($lst != null){
            majesticsupport::$_search['ticket']['list'] = $lst;
        }
        $list = majesticsupport::$_search['ticket']['list'];
        switch ($list) {
            // Ticket Default Status
            // 0 -> New Ticket
            // 1 -> Waiting admin/staff reply
            // 2 -> in progress
            // 3 -> waiting for customer reply
            // 4 -> close ticket
            case 1:$inquery .= " AND ticket.status != 4 AND ticket.status != 5";
                break;
            case 2:$inquery .= " AND ticket.isanswered = 1 AND ticket.status != 4 AND ticket.status != 5 AND ticket.status != 0";
                break;
            case 3:$inquery .= " AND ticket.isoverdue = 1 AND ticket.status != 4 AND ticket.status != 5 ";
                break;
            case 4:$inquery .= " AND (ticket.status = 4 OR ticket.status = 5) ";
                break;
            case 5://$inquery .= " AND ticket.uid =" . MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
                break;
        }

        if ($datestart != null)
            $inquery .= " AND '".esc_sql($datestart)."' <= DATE(ticket.created)";
        if ($dateend != null)
            $inquery .= " AND '".esc_sql($dateend)."' >= DATE(ticket.created)";
        if ($ticketid != null)
            $inquery .= " AND ticket.ticketid LIKE '%".esc_sql($ticketid)."%'";
        if ($subject != null)
            $inquery .= " AND ticket.subject LIKE '%".esc_sql($subject)."%'";
        if ($name != null)
            $inquery .= " AND ticket.name LIKE '%".esc_sql($name)."%'";
        if ($email != null)
            $inquery .= " AND ticket.email LIKE '%".esc_sql($email)."%'";
        if ($priority != null)
            $inquery .= " AND ticket.priorityid = ".esc_sql($priority);
        if ($departmentid != null)
            $inquery .= " AND ticket.departmentid = ".esc_sql($departmentid);
        if ($staffid != null)
            $inquery .= " AND ticket.staffid = ".esc_sql($staffid);

        if ($orderid != null && is_numeric($orderid))
            $inquery .= " AND ticket.wcorderid = ".esc_sql($orderid);

        if ($eddorderid != null && is_numeric($eddorderid))
            $inquery .= " AND ticket.eddorderid = ".esc_sql($eddorderid);

        $valarray = array();
        if (!empty($search_userfields)) {
            foreach ($search_userfields as $uf) {
                if (MJTC_request::MJTC_getVar('pagenum', 'get', null) != null) {
                    $valarray[$uf->field] = $value_array[$uf->field];
                }else{
                    $valarray[$uf->field] = MJTC_request::MJTC_getVar($uf->field, 'post');
                }
                if (isset($valarray[$uf->field]) && $valarray[$uf->field] != null) {
                    switch ($uf->userfieldtype) {
                        case 'admin_only':
                            $inquery .= ' AND ticket.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '.*"\' ';
                            break;
                        case 'text':
                            $inquery .= ' AND ticket.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '.*"\' ';
                            break;
                        case 'email':
                            $inquery .= ' AND ticket.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '.*"\' ';
                            break;
                        case 'file':
                            $inquery .= ' AND ticket.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '.*"\' ';
                            break;
                        case 'combo':
                            $inquery .= ' AND ticket.params LIKE \'%"' . esc_sql($uf->field) . '":"' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '"%\' ';
                            break;
                        case 'depandant_field':
                            $inquery .= ' AND ticket.params LIKE \'%"' . esc_sql($uf->field) . '":"' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '"%\' ';
                            break;
                        case 'radio':
                            $inquery .= ' AND ticket.params LIKE \'%"' . esc_sql($uf->field) . '":"' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '"%\' ';
                            break;
                        case 'checkbox':
                            $finalvalue = '';
                            foreach($valarray[$uf->field] AS $value){
                                $finalvalue .= esc_sql($value).'.*';
                            }
                            $inquery .= ' AND ticket.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars($finalvalue) . '.*"\' ';
                            break;
                        case 'date':
                            $inquery .= ' AND ticket.params LIKE \'%"' . esc_sql($uf->field) . '":"' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '"%\' ';
                            break;
                        case 'textarea':
                            $inquery .= ' AND ticket.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '.*"\' ';
                            break;
                        case 'multiple':
                            $finalvalue = '';
                            foreach($valarray[$uf->field] AS $value){
                                if($value != null){
                                    $finalvalue .= esc_sql($value).'.*';
                                }
                            }
                            if($finalvalue !=''){
                                $inquery .= ' AND ticket.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*'.MJTC_majesticsupportphplib::MJTC_htmlspecialchars($finalvalue).'.*"\'';
                            }
                            break;
                    }
                    majesticsupport::$_data['filter']['params'] = $valarray;
                }
            }
        }
        //end

        majesticsupport::$_data['filter']['subject'] = $subject;
        majesticsupport::$_data['filter']['ticketid'] = $ticketid;
        majesticsupport::$_data['filter']['name'] = $name;
        majesticsupport::$_data['filter']['email'] = $email;
        majesticsupport::$_data['filter']['datestart'] = $datestart;
        majesticsupport::$_data['filter']['dateend'] = $dateend;
        majesticsupport::$_data['filter']['priority'] = $priority;
        majesticsupport::$_data['filter']['departmentid'] = $departmentid;
        majesticsupport::$_data['filter']['staffid'] = $staffid;
        majesticsupport::$_data['filter']['sortby'] = $sortby;
        majesticsupport::$_data['filter']['orderid'] = $orderid;
        majesticsupport::$_data['filter']['eddorderid'] = $eddorderid;

        $userquery = '';
        $uid = MJTC_request::MJTC_getVar('uid');
        if ($uid != '') {
            $uid = MJTC_majesticsupportphplib::MJTC_trim($uid);
        }
        if($uid != null && is_numeric($uid)){
            $userquery = ' AND ticket.uid = '.esc_sql($uid);
        }

        // Pagination
        $query = "SELECT COUNT(ticket.id) "
                . "FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket "
                . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id "
                . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id "
                . "WHERE 1 = 1";
        $query .= $inquery.$userquery;
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total);

        /*
          list variable detail
          1=>For open ticket
          2=>For answered  ticket
          3=>For overdue ticket
          4=>For Closed tickets
          5=>For mytickets tickets
         */
        majesticsupport::$_data['list'] = $list; // assign for reference
        // Data
        do_action('ms_addon_staff_admin_tickets');
        $query = "SELECT ticket.*,department.departmentname AS departmentname ,priority.priority AS priority,priority.prioritycolour AS prioritycolour ".majesticsupport::$_addon_query['select']."
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                    ".majesticsupport::$_addon_query['join']."
                    WHERE 1 = 1";

        $query .= $inquery.$userquery;
        $query .= " ORDER BY " . majesticsupport::$_ordering . " LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        majesticsupport::$_data[0] = majesticsupport::$_db->get_results($query);
        do_action('reset_ms_aadon_query');
        // check email is bane
        if(in_array('banemail', majesticsupport::$_active_addons)){
            if (isset(majesticsupport::$_data[0]->email))
                $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email_banlist` WHERE email = ' " . majesticsupport::$_data[0]->email . "'";
            majesticsupport::$_data[7] = majesticsupport::$_db->get_var($query);
        }else{
            majesticsupport::$_data[7] = 0;
        }
        //Hook action
        do_action('ms-ticketbeforelisting', majesticsupport::$_data[0]);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        if(majesticsupport::$_config['count_on_myticket'] == 1){
            $query = "SELECT COUNT(ticket.id) "
                    . "FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket "
                    . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id "
                    . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id "
                    . "WHERE (ticket.status != 4 AND ticket.status != 5)".$userquery;
            majesticsupport::$_data['count']['openticket'] = majesticsupport::$_db->get_var($query);;

            $query = "SELECT COUNT(ticket.id) "
                    . "FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket "
                    . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id "
                    . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id "
                    . "WHERE ticket.isanswered = 1 AND ticket.status != 4 AND ticket.status != 5 AND ticket.status != 0".$userquery;
            majesticsupport::$_data['count']['answeredticket'] = majesticsupport::$_db->get_var($query);;

            $query = "SELECT COUNT(ticket.id) "
                    . "FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket "
                    . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id "
                    . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id "
                    . "WHERE ticket.isoverdue = 1".$userquery;
            majesticsupport::$_data['count']['overdueticket'] = majesticsupport::$_db->get_var($query);;

            $query = "SELECT COUNT(ticket.id) "
                    . "FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket "
                    . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id "
                    . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id "
                    . "WHERE (ticket.status = 4 OR ticket.status = 5)".$userquery;
            majesticsupport::$_data['count']['closedticket'] = majesticsupport::$_db->get_var($query);;

            $query = "SELECT COUNT(ticket.id) "
                    . "FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket "
                    . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id "
                    . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id "
                    . "WHERE 1 = 1".$userquery;
            majesticsupport::$_data['count']['allticket'] = majesticsupport::$_db->get_var($query);
        }
        return;
    }

    function getOrdering() {
        $sort = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['sortby'] : '';
        if ($sort == '') {
            $list = majesticsupport::$_config['tickets_ordering'];
            // default sort by
            $sortbyconfig = majesticsupport::$_config['tickets_sorting'];
            if($sortbyconfig == 1){
                $sortbyconfig = "asc";
            }else{
                $sortbyconfig = "desc";
            }
            $sort = 'status';
            if($list == 2)
                $sort = 'created';
            $sort = $sort.$sortbyconfig;
        }
        $this->getTicketListOrdering($sort);
        $this->getTicketListSorting($sort);
    }

    function combineOrSingleSearch() {
        $ticketkeys = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['ticketkeys'] : false;
        $inquery = '';
        if ($ticketkeys) {
            if (MJTC_majesticsupportphplib::MJTC_strpos($ticketkeys, '@') && MJTC_majesticsupportphplib::MJTC_strpos($ticketkeys, '.')){
                $inquery = " AND ticket.email LIKE '%".esc_sql($ticketkeys)."%'";
            }else{
                $inquery = " AND (ticket.ticketid = '".esc_sql($ticketkeys)."' OR ticket.subject LIKE '%".esc_sql($ticketkeys)."%')";
            }
            majesticsupport::$_data['filter']['ticketsearchkeys'] = $ticketkeys;
        }else {
            $search_userfields = MJTC_includer::MJTC_getObjectClass('customfields')->userFieldsForSearch(1);
            $ticketid = MJTC_request::MJTC_getVar('ms-ticket', 'post');

            $from = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['name'] : '';
            $email = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['email'] : '';
            $departmentid = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['departmentid'] : '';
            $priorityid = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['priority'] : '';
            $subject = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['subject'] : '';
            $datestart = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['datestart'] : '';
            $dateend = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['dateend'] : '';
            $orderid = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['orderid'] : '';
            $eddorderid = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['eddorderid'] : '';
            $staffid = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['staffid'] : '';
            $sortby = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['sortby'] : '';
            $assignedtome = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['assignedtome'] : '';

            if (!empty($search_userfields)) {
                foreach ($search_userfields as $uf) {
                    $value_array[$uf->field] = isset(majesticsupport::$_search['ms_ticket_custom_field']) ? majesticsupport::$_search['ms_ticket_custom_field'][$uf->field] : '';
                }
            }

            if ($ticketid != null) {
                $inquery .= " AND ticket.ticketid LIKE '".esc_sql($ticketid)."'";
                majesticsupport::$_data['filter']['ticketid'] = $ticketid;
            }
            if ($from != null) {
                $inquery .= " AND ticket.name LIKE '%".esc_sql($from)."%'";
                majesticsupport::$_data['filter']['from'] = $from;
            }
            if ($email != null) {
                $inquery .= " AND ticket.email LIKE '".esc_sql($email)."'";
                majesticsupport::$_data['filter']['email'] = $email;
            }
            if ($departmentid != null) {
                $inquery .= " AND ticket.departmentid = '".esc_sql($departmentid)."'";
                majesticsupport::$_data['filter']['departmentid'] = $departmentid;
            }
            if ($priorityid != null) {
                $inquery .= " AND ticket.priorityid = '".esc_sql($priorityid)."'";
                majesticsupport::$_data['filter']['priorityid'] = $priorityid;
            }
            if(in_array('agent', majesticsupport::$_active_addons)){
                if ($staffid != null) {
                    $inquery .= " AND ticket.staffid = '".esc_sql($staffid)."'";
                    majesticsupport::$_data['filter']['staffid'] = $staffid;
                }
            }

            if ($subject != null) {
                $inquery .= " AND ticket.subject LIKE '%".esc_sql($subject)."%'";
                majesticsupport::$_data['filter']['subject'] = $subject;
            }
            if ($datestart != null) {
                $inquery .= " AND '".esc_sql($datestart)."' <= DATE(ticket.created)";
                majesticsupport::$_data['filter']['datestart'] = $datestart;
            }
            if ($dateend != null) {
                $inquery .= " AND '".esc_sql($dateend)."' >= DATE(ticket.created)";
                majesticsupport::$_data['filter']['dateend'] = $dateend;
            }

            if ($orderid != null && is_numeric($orderid)) {
                $inquery .= " AND ticket.wcorderid = ".esc_sql($orderid);
                majesticsupport::$_data['filter']['orderid'] = $orderid;
            }

            if ($eddorderid != null && is_numeric($eddorderid)) {
                $inquery .= " AND ticket.eddorderid = ".esc_sql($eddorderid);
                majesticsupport::$_data['filter']['eddorderid'] = $eddorderid;
            }

            if ($assignedtome != null) {
                if(in_array('agent',majesticsupport::$_active_addons)){
                    $uid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
                    $stfid = MJTC_includer::MJTC_getModel('agent')->getStaffId($uid);
                    $inquery .= " AND ticket.staffid = '".esc_sql($stfid)."'";
                    majesticsupport::$_data['filter']['assignedtome'] = $assignedtome;
                }
            }
            //Custom field search


            //start
            $data = MJTC_includer::MJTC_getObjectClass('customfields')->userFieldsForSearch(1);
            $valarray = array();
            if (!empty($data)) {
                foreach ($data as $uf) {
                    if (MJTC_request::MJTC_getVar('pagenum', 'get', null) != null) {
                        $valarray[$uf->field] = $value_array[$uf->field];
                    }else{
                        $valarray[$uf->field] = MJTC_request::MJTC_getVar($uf->field, 'post');
                    }
                    if (isset($valarray[$uf->field]) && $valarray[$uf->field] != null) {
                        switch ($uf->userfieldtype) {
                            case 'text':
                                $inquery .= ' AND ticket.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '.*"\' ';
                                break;
                            case 'email':
                                $inquery .= ' AND ticket.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '.*"\' ';
                                break;
                            case 'file':
                                $inquery .= ' AND ticket.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '.*"\' ';
                                break;
                            case 'combo':
                                $inquery .= ' AND ticket.params LIKE \'%"' . esc_sql($uf->field) . '":"' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '"%\' ';
                                break;
                            case 'depandant_field':
                                $inquery .= ' AND ticket.params LIKE \'%"' . esc_sql($uf->field) . '":"' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '"%\' ';
                                break;
                            case 'radio':
                                $inquery .= ' AND ticket.params LIKE \'%"' . esc_sql($uf->field) . '":"' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '"%\' ';
                                break;
                            case 'checkbox':
                                $finalvalue = '';
                                foreach($valarray[$uf->field] AS $value){
                                    $finalvalue .= esc_sql($value).'.*';
                                }
                                $inquery .= ' AND ticket.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars($finalvalue) . '.*"\' ';
                                break;
                            case 'date':
                                $inquery .= ' AND ticket.params LIKE \'%"' . esc_sql($uf->field) . '":"' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '"%\' ';
                                break;
                            case 'textarea':
                                $inquery .= ' AND ticket.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . MJTC_majesticsupportphplib::MJTC_htmlspecialchars(esc_sql($valarray[$uf->field])) . '.*"\' ';
                                break;
                            case 'multiple':
                                $finalvalue = '';
                                foreach($valarray[$uf->field] AS $value){
                                    if($value != null){
                                        $finalvalue .= esc_sql($value).'.*';
                                    }
                                }
                                if($finalvalue !=''){
                                    $inquery .= ' AND ticket.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*'.MJTC_majesticsupportphplib::MJTC_htmlspecialchars($finalvalue).'.*"\'';
                                }
                                break;
                        }
                        majesticsupport::$_data['filter']['params'] = $valarray;
                    }
                }
            }
            //end

            if ($inquery == '')
                majesticsupport::$_data['filter']['combinesearch'] = false;
            else
                majesticsupport::$_data['filter']['combinesearch'] = true;
        }
        return $inquery;
    }

    function getMyTickets($lst=null) {
        $this->getOrdering();
        // Filter
        /*
          list variable detail
          1=>For open ticket
          2=>For closed ticket
          3=>For open answered ticket
          4=>For all my tickets
         */
        $inquery = $this->combineOrSingleSearch();
        if($lst != null){
            majesticsupport::$_search['ticket']['list'] = $lst;
        }
        $list = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['list'] : 1;
        majesticsupport::$_data['list'] = $list; // assign for reference
        switch ($list) {
            // Ticket Default Status
            // 0 -> New Ticket
            // 1 -> Waiting admin/staff reply
            // 2 -> in progress
            // 3 -> waiting for customer reply
            // 4 -> close ticket
           case 1:$inquery .= " AND (ticket.status != 4 AND ticket.status != 5)";
                break;
            case 2:$inquery .= " AND (ticket.status = 4 OR ticket.status = 5) ";
                break;
            case 3:$inquery .= " AND ticket.status = 3 ";
                break;
            case 4:$inquery .= " ";
                break;
            case 5:$inquery .= " AND ticket.isoverdue = 1 ";
                break;
        }

        $uid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
        if ($uid) {
            // Pagination
            $query = "SELECT COUNT(ticket.id)
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                        WHERE ticket.uid = ".esc_sql($uid);
            $query .= $inquery;
            $total = majesticsupport::$_db->get_var($query);
            majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total,'myticket');

            // Data
            do_action('ms_addon_user_my_tickets');

            $query = "SELECT ticket.*,department.departmentname AS departmentname ,priority.priority AS priority,priority.prioritycolour AS prioritycolour ".majesticsupport::$_addon_query['select']."
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                        ".majesticsupport::$_addon_query['join']."
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id";
            $query .= " WHERE ticket.uid = ".esc_sql($uid) . $inquery;
            $query .= " ORDER BY " . majesticsupport::$_ordering . " LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
            majesticsupport::$_data[0] = majesticsupport::$_db->get_results($query);
            do_action('reset_ms_aadon_query');
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
            }
            if(majesticsupport::$_config['count_on_myticket'] == 1){
                $query = "SELECT COUNT(ticket.id) "
                        . "FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket "
                        . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id "
                        . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id "
                        . "WHERE ticket.uid = ".esc_sql($uid)." AND (ticket.status != 4 AND ticket.status != 5)";
                majesticsupport::$_data['count']['openticket'] = majesticsupport::$_db->get_var($query);;

                $query = "SELECT COUNT(ticket.id) "
                        . "FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket "
                        . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id "
                        . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id "
                        . "WHERE ticket.uid = ".esc_sql($uid)." AND ticket.status = 3";
                majesticsupport::$_data['count']['answeredticket'] = majesticsupport::$_db->get_var($query);;

                $query = "SELECT COUNT(ticket.id) "
                        . "FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket "
                        . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id "
                        . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id "
                        . "WHERE ticket.uid = ".esc_sql($uid)." AND (ticket.status = 4 OR ticket.status = 5)";
                majesticsupport::$_data['count']['closedticket'] = majesticsupport::$_db->get_var($query);;

                $query = "SELECT COUNT(ticket.id) "
                        . "FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket "
                        . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id "
                        . "LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id "
                        . "WHERE ticket.uid = ".esc_sql($uid);
                majesticsupport::$_data['count']['allticket'] = majesticsupport::$_db->get_var($query);
            }
        }
        return;
    }

    function getStaffTickets($lst=null) {
        if (! in_array('agent',majesticsupport::$_active_addons)) {
            return;
        }

        $this->getOrdering();
        // Filter
        /*
          list variable detail
          1=>For open ticket
          2=>For closed ticket
          3=>For open answered ticket
          4=>For all my tickets
         */

        $inquery = $this->combineOrSingleSearch();
        if($lst != null){
            majesticsupport::$_search['ticket']['list'] = $lst;
        }
        $list = isset(majesticsupport::$_search['ticket']) ? majesticsupport::$_search['ticket']['list'] : 1; // assign for reference
        majesticsupport::$_data['list'] = $list;
        switch ($list) {
            // Ticket Default Status
            // 0 -> Open Ticket
            // 1 -> Waiting admin/staff reply
            // 2 -> in progress
            // 3 -> waiting for customer reply
            // 4 -> close ticket
            case 1:$inquery .= " AND (ticket.status != 4 AND ticket.status != 5)";
                break;
            case 2:$inquery .= " AND (ticket.status = 4 OR ticket.status = 5) ";
                break;
            case 3:$inquery .= " AND ticket.status = 3 ";
                break;
            case 4:$inquery .= " ";
                break;
            case 5:$inquery .= " AND ticket.isoverdue = 1 ";
                break;
        }

        $uid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
        if ($uid == 0)
            return false;
        $staffid = MJTC_includer::MJTC_getModel('agent')->getStaffId($uid);

        //to handle all tickets permissoin
        $allowed = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('All Tickets');
        if($allowed == true){
            $agent_conditions = "1 = 1";
        }else{
            $agent_conditions = "ticket.staffid = ".esc_sql($staffid)." OR ticket.departmentid IN (SELECT dept.departmentid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` AS dept WHERE dept.staffid = ".esc_sql($staffid).")";
        }
        //show specific user's tickets
        $userquery = "";
        $uid = MJTC_request::MJTC_getVar('uid');
        if(is_numeric($uid) && $uid > 0){
            $userquery .= " AND ticket.uid = ".esc_sql($uid);
        }
        // Pagination
        $query = "SELECT COUNT(ticket.id)
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                    WHERE (".$agent_conditions.") ";
        $query .= $inquery;
        $query .= $userquery;
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total,'myticket');

        // Data
        $query = "SELECT DISTINCT ticket.*,department.departmentname AS departmentname ,priority.priority AS priority,priority.prioritycolour AS prioritycolour,assignstaff.photo AS staffphoto,assignstaff.id AS staffid, assignstaff.firstname AS staffname
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS assignstaff ON ticket.staffid = assignstaff.id
                    WHERE (".$agent_conditions.") " . $inquery . $userquery;;
        $query .= " ORDER BY " . majesticsupport::$_ordering . " LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        majesticsupport::$_data[0] = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        if(majesticsupport::$_config['count_on_myticket'] == 1){
            $query = "SELECT COUNT(ticket.id)
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                        WHERE (".$agent_conditions.") AND (ticket.status != 4 AND ticket.status !=5) ".$userquery;
            majesticsupport::$_data['count']['openticket'] = majesticsupport::$_db->get_var($query);;

            $query = "SELECT COUNT(ticket.id)
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                        WHERE (".$agent_conditions.") AND ticket.status = 3 ".$userquery;
            majesticsupport::$_data['count']['answeredticket'] = majesticsupport::$_db->get_var($query);;

            $query = "SELECT COUNT(ticket.id)
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                        WHERE (".$agent_conditions.") AND (ticket.status = 4 OR ticket.status = 5) ".$userquery;
            majesticsupport::$_data['count']['closedticket'] = majesticsupport::$_db->get_var($query);;


            $query = "SELECT COUNT(ticket.id)
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                        WHERE (".$agent_conditions.") AND ticket.isoverdue = 1 ".$userquery;
            majesticsupport::$_data['count']['overdue'] = majesticsupport::$_db->get_var($query);;

            $query = "SELECT COUNT(ticket.id)
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                        WHERE (".$agent_conditions.")  ".$userquery;
            majesticsupport::$_data['count']['allticket'] = majesticsupport::$_db->get_var($query);;
        }
        return;
    }

    function getTicketsForForm($id,$formid='') {
        if (!isset($formid) || $formid=='') {
           $formid = MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId();
        }
        if ($id) {
            if (!is_numeric($id))
                return false;
            $query = "SELECT ticket.*,department.departmentname AS departmentname ,priority.priority AS priority,priority.prioritycolour AS prioritycolour,user.name AS user_login
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                        LEFT JOIN `".majesticsupport::$_wpprefixforuser."mjtc_support_users` AS user ON user.id = ticket.uid
                        WHERE ticket.id = " . esc_sql($id);
            majesticsupport::$_data[0] = majesticsupport::$_db->get_row($query);
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            }else{
                if(!empty(majesticsupport::$_data[0])){
                    //to store hash value of id against old tickets
                    if( majesticsupport::$_data[0]->hash == null ){
                        $hash = $this->generateHash($id);
                        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` SET `hash`='".esc_sql($hash)."' WHERE id=".esc_sql($id);
                        majesticsupport::$_db->query($query);
                    } //end
                }
            }
            $formid = majesticsupport::$_data[0]->multiformid;
        }
        majesticsupport::$_data['formid'] = $formid;
        MJTC_includer::MJTC_getModel('attachment')->getAttachmentForForm($id);
        MJTC_includer::MJTC_getModel('fieldordering')->getFieldsOrderingforForm(1,$formid);
        return;
    }

    function getTicketForDetail($id) {
        if (!is_numeric($id)){
            return $id;
        }
        if (in_array('agent', majesticsupport::$_active_addons) && majesticsupport::$_data['user_staff']) { //staff
            if(current_user_can('ms_support_ticket')){
                majesticsupport::$_data['permission_granted'] = true;
                MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable(date("Y-m-d h:i:s"),'','ticket_time_start_',$id);
                if(in_array('timetracking', majesticsupport::$_active_addons)){
                    majesticsupport::$_data['time_taken'] = MJTC_includer::MJTC_getModel('timetracking')->getTimeTakenByTicketId($id);
                }
            }else{
                majesticsupport::$_data['permission_granted'] = $this->validateTicketDetailForStaff($id);
                if (majesticsupport::$_data['permission_granted']) { // validation passed
                    if(in_array('timetracking', majesticsupport::$_active_addons)){
                        MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable(date("Y-m-d h:i:s"),'','ticket_time_start_',$id);
                        majesticsupport::$_data['time_taken'] = MJTC_includer::MJTC_getModel('timetracking')->getTimeTakenByTicketId($id);
                    }
                }
            }

        } else { // user
            if(current_user_can('ms_support_ticket') || current_user_can('ms_support_ticket_tickets')){
                majesticsupport::$_data['permission_granted'] = true;
                if(in_array('timetracking', majesticsupport::$_active_addons)){
                    MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable(date("Y-m-d h:i:s"),'','ticket_time_start_',$id);
                    majesticsupport::$_data['time_taken'] = MJTC_includer::MJTC_getModel('timetracking')->getTimeTakenByTicketId($id);
                }
            }
            elseif (!MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest())
                majesticsupport::$_data['permission_granted'] = $this->validateTicketDetailForUser($id);
            else
                majesticsupport::$_data['permission_granted'] = $this->validateTicketDetailForVisitor($id);
        }
        if (!majesticsupport::$_data['permission_granted']) { // validation failed
            return;
        }

        do_action('ticket_detail_query');// TO HANDLE ALL THE QUERIES OF ADDONS

        $query = "SELECT ticket.*,priority.priority AS priority,priority.prioritycolour AS prioritycolour,department.departmentname AS departmentname
                     ".majesticsupport::$_addon_query['select']."
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                    ".majesticsupport::$_addon_query['join']."
                    WHERE ticket.id = " . esc_sql($id);
        majesticsupport::$_data[0] = majesticsupport::$_db->get_row($query);
        do_action('reset_ms_aadon_query');
        // check email is ban
        if(in_array('banemail', majesticsupport::$_active_addons)){
            $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email_banlist` WHERE email = '" . majesticsupport::$_data[0]->email . "'";
            majesticsupport::$_data[7] = majesticsupport::$_db->get_var($query);
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
            }
        }else{
            majesticsupport::$_data[7] = 0;
        }
        if(in_array('note', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('note')->getNotes($id);
        }
        MJTC_includer::MJTC_getModel('reply')->getReplies($id);
        majesticsupport::$_data['ticket_attachment'] = MJTC_includer::MJTC_getModel('attachment')->getAttachmentForReply($id, 0);
        $this->getTicketHistory($id);

        if(majesticsupport::$_data[0]->uid > 0){

            //count all ticket of user
            $query = "SELECT COUNT(id) FROM `" .majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE `uid` = ".majesticsupport::$_data[0]->uid;
            majesticsupport::$_data['nticket'] = majesticsupport::$_db->get_var($query);

            //get user tickets for right widget
            $inquery = " WHERE ticket.id != " . esc_sql($id) . " AND ticket.uid = " . majesticsupport::$_data[0]->uid;
            if(!is_admin() && in_array('agent', majesticsupport::$_active_addons) && majesticsupport::$_data['user_staff']){
                $allowed = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('All Tickets');
                if($allowed != true){
                    $staffid = MJTC_includer::MJTC_getModel('agent')->getStaffId(MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid());
                    $inquery .= " AND (ticket.staffid = ".esc_sql($staffid)." OR ticket.departmentid IN (SELECT dept.departmentid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` AS dept WHERE dept.staffid = ".esc_sql($staffid)."))";
                }
            }
            $query = "SELECT ticket.id,ticket.subject,ticket.status,ticket.lock,ticket.isoverdue,priority.priority AS priority,priority.prioritycolour AS prioritycolour,department.departmentname AS departmentname
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id";
            $query .= $inquery . " LIMIT 3 ";
            majesticsupport::$_data['usertickets'] = majesticsupport::$_db->get_results($query);
        }
        //Hooks
        do_action('ms-ticketbeforeview', majesticsupport::$_data);

        return;
    }



    function validateUserForTicket($id) {
        if (!MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {

        } else {
            majesticsupport::$_data['permission_granted'] = $this->checkTokenForTicketDetail($id);
        }
        return;
    }

    function getRandomTicketId() {
        $match = '';
        $customticketno = '';
        $count = 0;
        //$match = 'Y';
		do {
            $count++;
            $ticketid = "";
            $length = 9;
            $sequence = majesticsupport::$_config['ticketid_sequence'];
            if($sequence == 1){
                $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
                // we refer to the length of $possible a few times, so let's grab it now
                $maxlength = MJTC_majesticsupportphplib::MJTC_strlen($possible);
                if ($length > $maxlength) { // check for length overflow and truncate if necessary
                    $length = $maxlength;
                }
                // set up a counter for how many characters are in the ticketid so far
                $i = 0;
                // add random characters to $password until $length is reached
                while ($i < $length) {
                    // pick a random character from the possible ones
                    $char = MJTC_majesticsupportphplib::MJTC_substr($possible, mt_rand(0, $maxlength - 1), 1);
                    if (!MJTC_majesticsupportphplib::MJTC_strstr($ticketid, $char)) {
                        if ($i == 0) {
                            if (ctype_alpha($char)) {
                                $ticketid .= $char;
                                $i++;
                            }
                        } else {
                            $ticketid .= $char;
                            $i++;
                        }
                    }
                }
            }else{ // Sequential ticketid
                if($ticketid == ""){
                    $ticketid = 0; // by default its set to zero
                }
                //$maxquery = "SELECT max(convert(ticketid, SIGNED INTEGER)) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets`";
                $maxquery = "SELECT max(customticketno) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets`";
                $maxticketid = majesticsupport::$_db->get_var($maxquery);
                if(is_numeric($maxticketid)){
                    $ticketid = $maxticketid + $count;
                }else{
                    $ticketid = $ticketid + $count;
                }
                $customticketno = $ticketid;
                $padding_zeros = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('padding_zeros_ticketid');

                $idlen = MJTC_majesticsupportphplib::MJTC_strlen($ticketid);
                while ($idlen < $padding_zeros) {
                    $ticketid = "0".esc_sql($ticketid);
                    $idlen = MJTC_majesticsupportphplib::MJTC_strlen($ticketid);
                }
            }
			$prefix = "";
			$suffix = "";			
			$prefix = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('prefix_ticketid');
			$suffix = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('suffix_ticketid');
            if ($prefix != '') {
			    $prefix = MJTC_majesticsupportphplib::MJTC_trim($prefix);
            }
            if ($suffix != '') {
			    $suffix = MJTC_majesticsupportphplib::MJTC_trim($suffix);
            }
			if($prefix) $ticketid = $prefix . $ticketid;
			if($suffix) $ticketid = $ticketid . $suffix;
			
            $query = "SELECT count(ticketid) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE ticketid = '".esc_sql($ticketid) ."'";
            $row = majesticsupport::$_db->get_var($query);
            if($row > 0)
                $match = 'Y';
            else
                $match = 'N';
            /*
            $rows = majesticsupport::$_db->get_results($query);
                foreach ($rows as $row) {
                    if ($ticketid == $row->ticketid)
                        $match = 'Y';
                    else
                        $match = 'N';
                }
             */   
        }while ($match == 'Y');
        $result = array();
        $result['ticketid'] = $ticketid;
        $result['customticketno'] = $customticketno;
        return $result;
    }

    function getInternalTicketId() {
        $match = '';
        //$match = 'Y';
        do {
            $internalid = "";
            $length = 9;
            $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
            // we refer to the length of $possible a few times, so let's grab it now
            $maxlength = MJTC_majesticsupportphplib::MJTC_strlen($possible);
            if ($length > $maxlength) { // check for length overflow and truncate if necessary
                $length = $maxlength;
            }
            // set up a counter for how many characters are in the internalid so far
            $i = 0;
            // add random characters to $password until $length is reached
            while ($i < $length) {
                // pick a random character from the possible ones
                $char = MJTC_majesticsupportphplib::MJTC_substr($possible, mt_rand(0, $maxlength - 1), 1);
                if (!MJTC_majesticsupportphplib::MJTC_strstr($internalid, $char)) {
                    if ($i == 0) {
                        if (ctype_alpha($char)) {
                            $internalid .= $char;
                            $i++;
                        }
                    } else {
                        $internalid .= $char;
                        $i++;
                    }
                }
            }
            $query = "SELECT count(internalid) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE internalid = '".esc_sql($internalid) ."'";
            $row = majesticsupport::$_db->get_var($query);
            if($row > 0)
                $match = 'Y';
            else
                $match = 'N';
        }while ($match == 'Y');
        return  $internalid;
    }

    function countTicket($emailorid) {
        if (is_numeric($emailorid)) { // its UserID
            $counts = majesticsupport::$_db->get_var("SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE uid = " . esc_sql($emailorid));
        } else { // its EmailAddress
            $counts = majesticsupport::$_db->get_var("SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE email = '" . esc_sql($emailorid) . "'");
        }
        return $counts;
    }

    function countOpenTicket($emailorid) {
        if (is_numeric($emailorid)) { // its UserID
            $counts = majesticsupport::$_db->get_var("SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE uid = " . esc_sql($emailorid) . " AND status != 4");
        } else { // its EmailAddress
            $counts = majesticsupport::$_db->get_var("SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE email = '" . esc_sql($emailorid) . "' AND status != 4");
        }
        return $counts;
    }

    function checkBannedEmail($emailaddress) {
        if(!in_array('banemail', majesticsupport::$_active_addons)){
            return true;
        }
        $counts = majesticsupport::$_db->get_var("SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email_banlist` WHERE email = '" . esc_sql($emailaddress) . "'");
        if ($counts > 0) {
            $data['loggeremail'] = $emailaddress;
            $data['title'] = esc_html(__('Ban Email', 'majestic-support'));
            $data['log'] = esc_html(__('Ban email try to create ticket', 'majestic-support'));
            $current_user = MJTC_includer::MJTC_getObjectClass('user')->MJTC_getMSCurrentUser(); // to get current user name
            $currentUserName = $current_user->display_name;
            $data['logger'] = $currentUserName;
            $data['ipaddress'] = $this->getIpAddress();
            MJTC_includer::MJTC_getModel('banemaillog')->storebanemaillog($data);
            MJTC_message::MJTC_setMessage(esc_html(__('Banned email cannot create ticket', 'majestic-support')), 'error');
            return false;
        }
        return true;
    }

    function getIpAddress() {
        //if client use the direct ip
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = MJTC_majesticsupportphplib::MJTC_htmlspecialchars($_SERVER['HTTP_CLIENT_IP']);
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = MJTC_majesticsupportphplib::MJTC_htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR']);
        } else {
            $ip = MJTC_majesticsupportphplib::MJTC_htmlspecialchars($_SERVER['REMOTE_ADDR']);
        }
        return $ip;
    }



    function ticketValidate($emailaddress = '') {
        //check the banned user / email
        if($emailaddress != '' && in_array('banemail', majesticsupport::$_active_addons)){
            if (!$this->checkBannedEmail($emailaddress)) {
                return false;
            }
        }
        if(in_array('maxticket', majesticsupport::$_active_addons)){
            //check the Maximum Tickets
            if (!MJTC_includer::MJTC_getModel('maxticket')->checkMaxTickets($emailaddress)) {
                return false;
            }

            //check the Maximum Open Tickets

            if (!MJTC_includer::MJTC_getModel('maxticket')->checkMaxOpenTickets($emailaddress)) {
                return false;
            }
        }

        return true;
    }

    function captchaValidate() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-ticket') ) {
            die( 'Security check Failed' );
        }
        if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
            if (majesticsupport::$_config['show_captcha_on_visitor_from_ticket'] == 1) {
                if (majesticsupport::$_config['captcha_selection'] == 1) { // Google recaptcha
                    $gresponse = MJTC_majesticsupportphplib::MJTC_htmlspecialchars(majesticsupport::MJTC_sanitizeData($_POST['g-recaptcha-response']));// MJTC_sanitizeData() function uses wordpress santize functions
                    $resp = MJTC_googleRecaptchaHTTPPost(majesticsupport::$_config['recaptcha_privatekey'],$gresponse);

                    if ($resp == true) {
                        return true;
                    } else {
                        # set the error code so that we can display it
                        MJTC_message::MJTC_setMessage(esc_html(__('Incorrect Captcha Code', 'majestic-support')), 'error');
                        return false;
                    }
                } else { // own captcha
                    $captcha = new MJTC_captcha;
                    $result = $captcha->MJTC_checkCaptchaUserForm();
                    if ($result == 1) {
                        return true;
                    } else {
                        MJTC_message::MJTC_setMessage(esc_html(__('Incorrect Captcha Code', 'majestic-support')), 'error');
                        return false;
                    }
                }
            }
        }
	return true;
    }

    function storeTickets($data) {

		$nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-ticket') ) {
            die( 'Security check Failed' );
        }
        if (isset($data['email'])) {
            $checkduplicatetk = $this->checkIsTicketDuplicate($data['subject'],$data['email']);
            if(!$checkduplicatetk){
                return false;
            }
        }
        if($data['departmentid'] == ''){
            // auto assign
            $data['departmentid'] = MJTC_includer::MJTC_getModel('department')->getDepartmentIDForAutoAssign();
        }

        if (!is_admin() && ( !isset($data['ticketviaemail']) || $data['ticketviaemail'] != 1) ) { //if not admin or Email Piping
            if (!$this->captchaValidate()) {
                return false;
            }
            $email = isset($data['email']) ? $data['email'] : '';
            if (!$this->ticketValidate($email)) {
                return 3;
            }
        }

        //paid support validation
        if(in_array('paidsupport', majesticsupport::$_active_addons) && class_exists('WooCommerce')){
            //ignore if admin or agent or visitor
            if(!MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest() && !is_admin() && !(in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff())){
                $paidsupport = MJTC_includer::MJTC_getModel('paidsupport')->getPaidSupportList(MJTC_includer::MJTC_getObjectClass('user')->MJTC_wpuid(),$data['paidsupportid']);
                if(empty($paidsupport)){
                    MJTC_message::MJTC_setMessage(esc_html(__('Please select paid support item', 'majestic-support')), 'error');
                    return false;
                }
            }
        }

        $data['ticketviaemail'] = isset($data['ticketviaemail']) ? $data['ticketviaemail'] : 0;
        if($data['ticketviaemail'] != 1){ // do not check in ticket via email case
            //envato purchase code validation
            if(in_array('envatovalidation', majesticsupport::$_active_addons)){
                $code = $data['envatopurchasecode'];
                $pcode = isset($data['prev_envatopurchasecode']) ? $data['prev_envatopurchasecode'] : '';
                $required = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('envato_license_required');
                if($required!=1 && empty($code) && !empty($pcode)){
                    $envatoData = '';
                }
                if( (!empty($code) && (empty($pcode) || $pcode!=$code)) || ($required==1 && (empty($pcode) || $pcode!=$code)) ){
                    $res = MJTC_includer::MJTC_getModel('envatovalidation')->validatePurchaseCode($data['envatopurchasecode']);
                    if(!$res){
                        MJTC_message::MJTC_setMessage(esc_html(__('No purchase found with that code', 'majestic-support')), 'error');
                        return false;
                    }else{
                        $envatoData = json_encode($res);
                    }
                }
            }
        }

        // edd license
        if($data['ticketviaemail'] != 1){ // do not check in ticket via email case
            if(in_array('easydigitaldownloads', majesticsupport::$_active_addons)){
                if(majesticsupport::$_config['verify_license_on_ticket_creation'] == 1){
                    if(isset($data['eddlicensekey'])){
                        if($data['eddlicensekey'] == ''){
                            MJTC_message::MJTC_setMessage(esc_html(__('Provide a valid license key to create a ticket.', 'majestic-support')), 'error');
                            return false;
                        }else{
                            $l_result = MJTC_includer::MJTC_getModel('easydigitaldownloads')->getEDDLicenseVerification($data['eddlicensekey']);
                            if($l_result == 'expired'){
                                MJTC_message::MJTC_setMessage(esc_html(__('Your license has expired.', 'majestic-support')), 'error');
                                return false;
                            }elseif($l_result == 'inactive'){
                                MJTC_message::MJTC_setMessage(esc_html(__('Your license is not active, activate your license.', 'majestic-support')), 'error');
                                return false;
                            }
                        }
                    }
                }
            }
        }

        $sendEmail = true;
        if ($data['id']) {
            $sendEmail = false;
            $updated = date_i18n('Y-m-d H:i:s');
            $created = $data['created'];
            if (isset($data['isoverdue']) &&  $data['isoverdue'] == 1) {// for edit case to change the overdue if criteria is passed
                $curdate = date_i18n('Y-m-d H:i:s');
                if (date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($data['duedate'])) > date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($curdate))){
                    $data['isoverdue'] = 0;
                }else{
                    $query = "SELECT ticket.duedate FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` AS ticket WHERE ticket.id = ".esc_sql($data['id']);
                    $duedate = majesticsupport::$_db->get_var($query);
                    if(date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($data['duedate'])) != date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($duedate))){
                        MJTC_ticketModel::MJTC_setMessage(esc_html(__('Due date error is not valid','majestic-support')),'error');
                        return; //Due Date must be greater then current date
                    }
                }
            }
            //to check hash
            $query = "SELECT hash,uid FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE ticketid='".esc_sql($data['ticketid'])."'";
            $row = majesticsupport::$_db->get_row($query);
            $edituid = $row->uid;
            if( $row->hash != $this->generateHash($data['id']) ){
                return false;
            }//end
        } else {
            $idresult = $this->getRandomTicketId();
            $data['ticketid'] = $idresult['ticketid'];
            $data['customticketno'] = $idresult['customticketno'];
            $data['internalid'] = $this->getInternalTicketId();

            $data['attachmentdir'] = $this->getRandomFolderName();
            $created = date_i18n('Y-m-d H:i:s');
            $updated = '';
        }
        if(isset($data['assigntome']) && $data['assigntome'] == 1){
            if (in_array('agent',majesticsupport::$_active_addons)) {
                $uid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
                $staffid = MJTC_includer::MJTC_getModel('agent')->getStaffId($uid);
                $data['staffid'] = $staffid;
            }
        }else{
            $data['staffid'] = isset($data['staffid']) ? $data['staffid'] : '';
        }
        $data['status'] = isset($data['status']) ? $data['status'] : '';
        if (isset($data['duedate']) && $data['duedate'] != '') {
            $data['duedate'] = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($data['duedate']));
        } else {
            $data['duedate'] = '';
        }
        $data['lastreply'] = isset($data['lastreply']) ? $data['lastreply'] : '';
        $data['message'] = MJTC_includer::MJTC_getModel('majesticsupport')->getSanitizedEditorData($_POST['mjsupport_message']); // use mjsupport_message to avoid conflict
		$mjsupport_message = MJTC_includer::MJTC_getModel('majesticsupport')->msremovetags($data['message']);
        //check if message field is set as required or not
        $isRequired = MJTC_includer::MJTC_getModel('fieldordering')->checkIsFieldRequired('issuesummary',$data['multiformid']);
        if(empty($data['message']) && $isRequired == 1){
            MJTC_message::MJTC_setMessage(esc_html(__('Message field cannot be empty', 'majestic-support')), 'error');
            return false;
        }
        $data = majesticsupport::MJTC_sanitizeData($data);// MJTC_sanitizeData() function uses wordpress santize functions
        if(isset($envatoData)){
            $data['envatodata'] = $envatoData;
        }
        //custom field code start
        $customflagforadd = false;
        $customflagfordelete = false;
        $custom_field_namesforadd = array();
        $custom_field_namesfordelete = array();
        $userfield = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1,$data['multiformid']);
        $params = array();
        $maxfilesizeallowed = majesticsupport::$_config['file_maximum_size'];
        foreach ($userfield AS $ufobj) {
            $vardata = '';
            if($ufobj->userfieldtype == 'file'){
                if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1']== 0){
                    $vardata = $data[$ufobj->field.'_2'];
                }
                $customflagforadd=true;
                $custom_field_namesforadd[]=$ufobj->field;
            }else if($ufobj->userfieldtype == 'date'){
                $vardata = isset($data[$ufobj->field]) ? date("Y-m-d", MJTC_majesticsupportphplib::MJTC_strtotime($data[$ufobj->field])) : '';
            }else{
                $vardata = isset($data[$ufobj->field]) ? $data[$ufobj->field] : '';
            }
            if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1'] == 1){
                $customflagfordelete = true;
                $custom_field_namesfordelete[]= $data[$ufobj->field.'_2'];
            }
            if($vardata != ''){

                if(is_array($vardata)){
                    $vardata = implode(', ', array_filter($vardata));
                }
                $params[$ufobj->field] = MJTC_majesticsupportphplib::MJTC_htmlentities($vardata);
            }
        }
        if($data['id'] != ''){
            if(is_numeric($data['id'])){
                $query = "SELECT params FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($data['id']);
                $oParams = majesticsupport::$_db->get_var($query);

                if(!empty($oParams)){
                    $oParams = json_decode($oParams,true);
                    $unpublihsedFields = MJTC_includer::MJTC_getModel('fieldordering')->getUserUnpublishFieldsfor(1);
                    foreach($unpublihsedFields AS $field){
                        if(isset($oParams[$field->field])){
                            $params[$field->field] = $oParams[$field->field];
                        }
                    }
                }
            }
        }
        $params = html_entity_decode(json_encode($params, JSON_UNESCAPED_UNICODE));
        $data['params'] = $params;
        //custom field code end

		$data['message'] = $mjsupport_message;
        $data['created'] = $created;
        $data['updated'] = $updated;


        if($data['uid'] == 0 && isset($_SESSION['majestic-support']['notificationid'])){
            $data['notificationid'] = MJTC_majesticsupportphplib::MJTC_htmlspecialchars($_SESSION['majestic-support']['notificationid']);
        }

        if($data['id']){
           $data['uid'] = $edituid;
        }
        $sendnotification = false;
        $row = MJTC_includer::MJTC_getTable('tickets');
		// this line make problem with custom field data (latin words)
        $error = 0;
        if (!$row->bind($data)) {
            $error = 1;
        }
        if (!$row->store()) {
            $error = 1;
        }

        if ($error == 1) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
            $messagetype = esc_html(__('Error', 'majestic-support'));
            $sendEmail = false;
            MJTC_message::MJTC_setMessage(esc_html(__('Ticket has not been created', 'majestic-support')), 'error');
        } else {
            $ticketid = $row->id;
            $sendnotification = true;
            $messagetype = esc_html(__('Successfully', 'majestic-support'));

            //update hash value against ticket
            $hash = $this->generateHash($ticketid);
            $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` SET `hash`='".esc_sql($hash)."' WHERE id=".esc_sql($ticketid);
            majesticsupport::$_db->query($query);

            // Storing Attachments
			$data['ticketid'] = $ticketid;
			if($data['ticketviaemail'] != 1){ // since ticket via emial attacments are handled saprately
			   MJTC_includer::MJTC_getModel('attachment')->storeAttachments($data);
			   MJTC_message::MJTC_setMessage(esc_html(__('Your ticket has been submitted successfully', 'majestic-support')), 'updated');

			   //removing custom field attachments
                if($customflagfordelete == true){
				    foreach ($custom_field_namesfordelete as $key) {
					   $res = $this->removeFileCustom($ticketid,$key);
				    }
	            }
                //storing custom field attachments
                if($customflagforadd == true){
			        foreach ($custom_field_namesforadd as $key) {
                        if ($_FILES[$key]['size'] > 0) { // logo
	                       $res = $this->uploadFileCustom($ticketid,$key);
				        }
				    }
                }

                //update paid support item tickets
                if(isset($paidsupport)){
                    $paidsupport = $paidsupport[0];
                    $res = MJTC_includer::MJTC_getModel('paidsupport')->recordTicket($paidsupport->itemid, $ticketid);
                    if($res){
                        $t = MJTC_includer::MJTC_getTable('tickets');
                        if($t->bind(array('id'=>$ticketid,'paidsupportitemid'=>$paidsupport->itemid))){
                            $t->store();
                        }
                    }
                }

			}
        }
        do_action('ms_after_ticket_create',$data,$ticketid);
        

        /* Push Notification */
        if($data['id'] == '' && $sendnotification == true && in_array('notification', majesticsupport::$_active_addons)){
            $dataarray = array();
            $dataarray['title'] = $data['subject'];
            $dataarray['body'] = esc_html(__("created",'majestic-support'));

            //send notification to admin
            $devicetoken = MJTC_includer::MJTC_getModel('notification')->checkSubscriptionForAdmin();
            if($devicetoken){
                $dataarray['link'] = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=".$ticketid);
                $dataarray['devicetoken'] = $devicetoken;
                $value = majesticsupport::$_config[MJTC_majesticsupportphplib::MJTC_md5(MSTN)];
                if($value != ''){
                  do_action('send_push_notification',$dataarray);
                }else{
                  do_action('resetnotificationvalues');
                }
            }

            $dataarray['link'] = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail', "majesticsupportid"=>$ticketid,'mspageid'=>majesticsupport::getPageid()));
            // for department staff
            MJTC_includer::MJTC_getModel('notification')->sendNotificationToDepartment($data['departmentid'],$dataarray);
            // for all
            if($data['departmentid'] == ''){
                MJTC_includer::MJTC_getModel('notification')->sendNotificationToAllStaff($dataarray);
            }

            // send notification to MJTC_uidticket create for)
            if($data['uid'] > 0 && is_numeric($data['uid']) && ($data['uid'] != MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid())){
                $devicetoken = MJTC_includer::MJTC_getModel('notification')->getUserDeviceToken($data['uid']);
                $dataarray['devicetoken'] = $devicetoken;
                if($devicetoken != '' && !empty($devicetoken)){
                    $value = majesticsupport::$_config[MJTC_majesticsupportphplib::MJTC_md5(MSTN)];
                    if($value != ''){
                      do_action('send_push_notification',$dataarray);
                    }else{
                      do_action('resetnotificationvalues');
                    }
                }
            }else if($data['uid'] == 0 && isset($data['notificationid']) && $data['notificationid'] != ""){ //visitor
                $tokenarray['emailaddress'] = $data['email'];
                $tokenarray['trackingid'] = $data['ticketid'];
                $tokenarray['sitelink']=MJTC_includer::MJTC_getModel('majesticsupport')->getEncriptedSiteLink();
                $token = json_encode($tokenarray);
                include_once MJTC_PLUGIN_PATH . 'includes/encoder.php';
                $encoder = new MJTC_encoder();
                $encryptedtext = $encoder->MJTC_encrypt($token);
                $dataarray['link'] = majesticsupport::makeUrl(array('mjsmod'=>'ticket' ,'task'=>'showticketstatus','action'=>'mstask','token'=>$encryptedtext,'mspageid'=>majesticsupport::getPageid()));
                $devicetoken = MJTC_includer::MJTC_getModel('notification')->getUserDeviceToken($data['notificationid'],0);
                $dataarray['devicetoken'] = $devicetoken;
                if($devicetoken != '' && !empty($devicetoken)){
                    $value = majesticsupport::$_config[MJTC_majesticsupportphplib::MJTC_md5(MSTN)];
                    if($value != ''){
                      do_action('send_push_notification',$dataarray);
                    }else{
                      do_action('resetnotificationvalues');
                    }
                }
            }

        }


        /* for activity log */
        if (!MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
            $current_user = MJTC_includer::MJTC_getObjectClass('user')->MJTC_getMSCurrentUser(); // to get current user name
            $currentUserName = $current_user->display_name;
        }else{
            $currentUserName = esc_html(__('Guest','majestic-support'));
        }
        $eventtype = esc_html(__('New ticket', 'majestic-support'));
        if ($data['id']) {
            $message = esc_html(__('Ticket is updated by', 'majestic-support')) . " ( " . $currentUserName . " ) ";
        } else {
            $message = esc_html(__('Ticket is created by', 'majestic-support')) . " ( " . $currentUserName . " ) ";
        }
        if(in_array('tickethistory', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('tickethistory')->addActivityLog($ticketid, 1, $eventtype, $message, $messagetype);
        }

        // Send Emails
        if ($sendEmail == true) {
            MJTC_includer::MJTC_getModel('email')->sendMail(1, 1, $ticketid); // Mailfor, Create Ticket, Ticketid
            //For Hook
            $ticketobject = majesticsupport::$_db->get_row("SELECT * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($ticketid));
            do_action('ms-ticketcreate', $ticketobject);
        }
        /* to store internal notes */
        if(in_array('note', majesticsupport::$_active_addons)){
            if (isset($data['internalnote']) && $data['internalnote'] != '') {
                MJTC_includer::MJTC_getModel('note')->storeTicketInternalNote($data, $data['internalnote']);
            }
        }
        /* agent auto assign */
        do_action('ms-agentautoassign', $ticketid);
        return $ticketid;
    }

    function uploadFileCustom($id,$field){
        MJTC_includer::MJTC_getObjectClass('uploads')->MJTC_storeTicketCustomUploadFile($id,$field);
    }

    function storeUploadFieldValueInParams($ticketid,$filename,$field){
        $query = "SELECT params FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE id = ".esc_sql($ticketid);
        $params = majesticsupport::$_db->get_var($query);
        $decoded_params = json_decode($params,true);
        $decoded_params[$field] = $filename;
        $encoded_params = json_encode($decoded_params);
        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` SET params = '" . esc_sql($encoded_params) . "' WHERE id = " . esc_sql($ticketid);
        majesticsupport::$_db->query($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return;
    }

    function removeTicket($id, $internalid) {
        $sendEmail = true;
        if (!is_numeric($id))
            return false;
        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $allowed = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Delete Ticket');
            if ($allowed != true) {
                MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed', 'majestic-support')), 'error');
                return;
            }
        } else if (!$this->validateTicketAction($id, $internalid)) {
            MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed','majestic-support')), 'error');
            return false;
        }

        if ($this->canRemoveTicket($id)) {
            majesticsupport::$_data['ticketid'] = $this->getTrackingIdById($id);
            majesticsupport::$_data['ticketemail'] = $this->getTicketEmailById($id);
            majesticsupport::$_data['staffid'] = $this->getStaffIdById($id);
            majesticsupport::$_data['ticketsubject'] = $this->getTicketSubjectById($id);
            // delete attachments
            $this->removeTicketAttachmentsByTicketid($id);

            $row = MJTC_includer::MJTC_getTable('tickets');
            if ($row->delete($id)) {
                $messagetype = esc_html(__('Successfully', 'majestic-support'));
                MJTC_message::MJTC_setMessage(esc_html(__('Ticket has been deleted', 'majestic-support')), 'updated');
            } else {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
                MJTC_message::MJTC_setMessage(esc_html(__('Ticket has not been deleted', 'majestic-support')), 'error');
                $messagetype = esc_html(__('Error', 'majestic-support'));
                $sendEmail = false;
            }

            // Send Emails
            if ($sendEmail == true) {
                MJTC_includer::MJTC_getModel('email')->sendMail(1, 3); // Mailfor, Delete Ticket
                $ticketobject = (object) array('ticketid' => majesticsupport::$_data['ticketid'], 'ticketemail' => majesticsupport::$_data['ticketemail']);
                do_action('ms-ticketdelete', $ticketobject);
            }
            if(in_array('note', majesticsupport::$_active_addons)){
                // delete internal notes
                MJTC_includer::MJTC_getModel('note')->removeTicketInternalNote($id);
            }
            // delete replies
            MJTC_includer::MJTC_getModel('reply')->removeTicketReplies($id);
        } else {
            MJTC_message::MJTC_setMessage(esc_html(__('Ticket','majestic-support')).' '.esc_html(__('in use cannot be deleted', 'majestic-support')), 'error');
        }

        return;
    }

    function removeEnforceTicket($id) {
        $sendEmail = true;
        if (!is_numeric($id))
            return false;
        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $allowed = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Delete Ticket');
            if ($allowed != true) {
                MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed', 'majestic-support')), 'error');
                return;
            }
        }

        majesticsupport::$_data['ticketid'] = $this->getTrackingIdById($id);
        majesticsupport::$_data['ticketemail'] = $this->getTicketEmailById($id);
        majesticsupport::$_data['staffid'] = $this->getStaffIdById($id);
        majesticsupport::$_data['ticketsubject'] = $this->getTicketSubjectById($id);
		// delete attachments
		$this->removeTicketAttachmentsByTicketid($id);

        $row = MJTC_includer::MJTC_getTable('tickets');
        if ($row->delete($id)) {
		    // delete attachments
            $messagetype = esc_html(__('Successfully', 'majestic-support'));
            MJTC_message::MJTC_setMessage(esc_html(__('Ticket has been deleted', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Ticket has not been deleted', 'majestic-support')), 'error');
            $messagetype = esc_html(__('Error', 'majestic-support'));
            $sendEmail = false;
        }

        // Send Emails
        if ($sendEmail == true) {
            MJTC_includer::MJTC_getModel('email')->sendMail(1, 3); // Mailfor, Delete Ticket
            $ticketobject = (object) array('ticketid' => majesticsupport::$_data['ticketid'], 'ticketemail' => majesticsupport::$_data['ticketemail']);
            do_action('ms-ticketdelete', $ticketobject);
        }
        if(in_array('note', majesticsupport::$_active_addons)){
            // delete internal notes
            MJTC_includer::MJTC_getModel('note')->removeTicketInternalNote($id);
        }
        // delete replies
        MJTC_includer::MJTC_getModel('reply')->removeTicketReplies($id);

        return;
    }

    private function removeTicketAttachmentsByTicketid($id){
		if(!is_numeric($id)) return false;
		$datadirectory = majesticsupport::$_config['data_directory'];
		$maindir = wp_upload_dir();
		$mainpath = $maindir['basedir'];
		$mainpath = $mainpath .'/'.$datadirectory;
		$mainpath = $mainpath . '/attachmentdata';
		$query = "SELECT ticket.attachmentdir
					FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` AS ticket
					WHERE ticket.id = ".esc_sql($id);
		$foldername = majesticsupport::$_db->get_var($query);
		if(!empty($foldername)){
			$folder = $mainpath . '/ticket/'.$foldername;
            if(file_exists($folder)){
    			$path = $mainpath . '/ticket/'.$foldername.'/*.*';
    			$files = glob($path);
    			array_map('unlink', $files);//deleting files
    			rmdir($folder);
    			$query = "DELETE FROM `".majesticsupport::$_db->prefix."mjtc_support_attachments` WHERE ticketid = ".esc_sql($id);
    			majesticsupport::$_db->query($query);
            }
		}
	}

    private function canRemoveTicket($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT (
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_replies` WHERE ticketid = " . esc_sql($id) . ") ";
                    if(in_array('note', majesticsupport::$_active_addons)){
                        $query .= " +(SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_notes` WHERE ticketid = " . esc_sql($id) . ") ";
                    }
                    $query .= "
                    ) AS total";
        $result = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        if ($result == 0)
            return true;
        else
            return false;
    }

    function getTicketSubjectById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT subject FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($id);
        $subject = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $subject;
    }

    function getTrackingIdById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT ticketid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($id);
        $ticketid = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $ticketid;
    }

    function getTicketEmailById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT email FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($id);
        $ticketemail = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $ticketemail;
    }

    function getStaffIdById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT staffid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($id);
        $staffid = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $staffid;
    }

    function setStatus($status, $ticketid) {
        // 0 -> New Ticket
        // 1 -> Waiting admin/staff reply
        // 2 -> in progress
        // 3 -> waiting for customer reply
        // 4 -> close ticket
        if (!is_numeric($status))
            return false;
        if (!is_numeric($ticketid))
            return false;
        $row = MJTC_includer::MJTC_getTable('tickets');
        if (!$row->update(array('id' => $ticketid, 'status' => $status))) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return;
    }
    function getLastReply($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT reply.message FROM `" . majesticsupport::$_db->prefix . "mjtc_support_replies` AS reply WHERE reply.ticketid = " . esc_sql($id) . " ORDER BY reply.created DESC LIMIT 1";
        $message =majesticsupport::$_db->query($query);
        return $message;
    }
    function updateLastReply($id) {
        if (!is_numeric($id))
            return false;
        $date = date_i18n('Y-m-d H:i:s');
        $isanswered = " , isanswered = 0 ";
        if ( is_admin() || ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ) {
            $isanswered = " , isanswered = 1 ";
        }
        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` SET lastreply = '" . esc_sql($date) . "' " . $isanswered . " WHERE id = " . esc_sql($id);
        majesticsupport::$_db->query($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return;
    }

    function closeTicket($id, $internalid = '' ,$cron_flag = 0) { // second parameter is for crown call(when crown job is executed to hanled close ticket configuration)
        if (!is_numeric($id))
            return false;
        if($cron_flag == 0){
            //Check if its allowed to close ticket
            if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                $allowed = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Close Ticket');
                if ($allowed != true) {
                    MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed', 'majestic-support')), 'error');
                    return;
                }
            } else if (!$this->validateTicketAction($id, $internalid)) {
                MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed','majestic-support')), 'error');
                return;
            }
        }
        if (!$this->checkActionStatusSame($id, array('action' => 'closeticket'))) {
            MJTC_message::MJTC_setMessage(esc_html(__('Ticket already closed', 'majestic-support')), 'error');
            return;
        }
        $sendEmail = true;
        $date = date_i18n('Y-m-d H:i:s');
        if($cron_flag == 0){
            $current_user = MJTC_includer::MJTC_getObjectClass('user')->MJTC_getMSCurrentUser(); // to get current user id
            $closedby = isset($current_user->display_name) ? $current_user->id : -1;
        }else{
            $closedby = 0;
        }


        $row = MJTC_includer::MJTC_getTable('tickets');
        if ($row->update(array('id' => $id, 'status' => 4, 'closed' => $date, 'closedby' => $closedby, 'isoverdue' => 0))) {

            MJTC_message::MJTC_setMessage(esc_html(__('Ticket has been closed', 'majestic-support')), 'updated');
            $messagetype = esc_html(__('Successfully', 'majestic-support'));
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Ticket has not been closed', 'majestic-support')), 'error');
            $messagetype = esc_html(__('Error', 'majestic-support'));
            $sendEmail = false;
        }

        /* for activity log */
        $ticketid = $id; // get the ticket id
        if($cron_flag == 0){
            $current_user = MJTC_includer::MJTC_getObjectClass('user')->MJTC_getMSCurrentUser(); // to get current user name
            $currentUserName = isset($current_user->display_name) ? $current_user->display_name : esc_html(__('Guest', 'majestic-support'));
        }else{
            $currentUserName = esc_html(__('System', 'majestic-support'));
        }
        $eventtype = esc_html(__('Close Ticket', 'majestic-support'));
        $message = esc_html(__('Ticket is closed by', 'majestic-support')) . " ( " . esc_html($currentUserName) . " ) ";
        $query = " SELECT closedreason FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($ticketid);
        $closedreason = majesticsupport::$_db->get_var($query);
        if (isset($closedreason)) {
            $message .= esc_html(__('due to the following reasons', 'majestic-support'));
            $reasons = json_decode($closedreason);
            foreach ($reasons as $reason) {
                $message .= '<br>';
                $message .= $reason;
            }
        }
        if(in_array('tickethistory', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('tickethistory')->addActivityLog($ticketid, 1, $eventtype, $message, $messagetype);
        }

        // Send Emails
        if ($sendEmail == true) {
            MJTC_includer::MJTC_getModel('email')->sendMail(1, 2, $ticketid); // Mailfor, Close Ticket, Ticketid
            $ticketobject = majesticsupport::$_db->get_row("SELECT * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($ticketid));
            do_action('ms-ticketclose', $ticketobject);
        }
        // on ticket close make remove credentails data and show messsage on retrive.
        if(in_array('privatecredentials',majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('privatecredentials')->deleteCredentialsOnCloseTicket($ticketid);
        }
        return;
    }

    function getTicketListOrdering($sort) {
        switch ($sort) {
            case "subjectdesc":
                majesticsupport::$_ordering = "ticket.subject DESC";
                majesticsupport::$_sorton = "subject";
                majesticsupport::$_sortorder = "DESC";
                break;
            case "subjectasc":
                majesticsupport::$_ordering = "ticket.subject ASC";
                majesticsupport::$_sorton = "subject";
                majesticsupport::$_sortorder = "ASC";
                break;
            case "prioritydesc":
                majesticsupport::$_ordering = "priority.ordering DESC";
                majesticsupport::$_sorton = "priority";
                majesticsupport::$_sortorder = "DESC";
                break;
            case "priorityasc":
                majesticsupport::$_ordering = "priority.ordering ASC";
                majesticsupport::$_sorton = "priority";
                majesticsupport::$_sortorder = "ASC";
                break;
            case "ticketiddesc":
                majesticsupport::$_ordering = "ticket.ticketid DESC";
                majesticsupport::$_sorton = "ticketid";
                majesticsupport::$_sortorder = "DESC";
                break;
            case "ticketidasc":
                majesticsupport::$_ordering = "ticket.ticketid ASC";
                majesticsupport::$_sorton = "ticketid";
                majesticsupport::$_sortorder = "ASC";
                break;
            case "isanswereddesc":
                majesticsupport::$_ordering = "ticket.isanswered DESC";
                majesticsupport::$_sorton = "isanswered";
                majesticsupport::$_sortorder = "DESC";
                break;
            case "isansweredasc":
                majesticsupport::$_ordering = "ticket.isanswered ASC";
                majesticsupport::$_sorton = "isanswered";
                majesticsupport::$_sortorder = "ASC";
                break;
            case "statusdesc":
                majesticsupport::$_ordering = "ticket.status DESC";
                majesticsupport::$_sorton = "status";
                majesticsupport::$_sortorder = "DESC";
                break;
            case "statusasc":
                majesticsupport::$_ordering = "ticket.status ASC";
                majesticsupport::$_sorton = "status";
                majesticsupport::$_sortorder = "ASC";
                break;
            case "createddesc":
                majesticsupport::$_ordering = "ticket.created DESC";
                majesticsupport::$_sorton = "created";
                majesticsupport::$_sortorder = "DESC";
                break;
            case "createdasc":
                majesticsupport::$_ordering = "ticket.created ASC";
                majesticsupport::$_sorton = "created";
                majesticsupport::$_sortorder = "ASC";
                break;
            default:
                $sortbyconfig = majesticsupport::$_config['tickets_sorting'];
                if($sortbyconfig == 1){
                    $sortbyconfig = "ASC";
                }else{
                    $sortbyconfig = "DESC";
                }
                majesticsupport::$_ordering = "ticket.id $sortbyconfig";
            break;
        }
        return;
    }

    function getSortArg($type, $sort) {
        $mat = array();
        if (MJTC_majesticsupportphplib::MJTC_preg_match("/(\w+)(asc|desc)/i", $sort, $mat)) {
            if ($type == $mat[1]) {
                return ( $mat[2] == "asc" ) ? "{$type}desc" : "{$type}asc";
            } else {
                return $type . $mat[2];
            }
        }
        $sortlink = "id";
        // default sorting
        $sortbyconfig = majesticsupport::$_config['tickets_sorting'];
        if($sortbyconfig == 1){
            $sortbyconfig = "asc";
        }else{
            $sortbyconfig = "desc";
        }
        $sortlink = $sortlink.$sortbyconfig;

        return $sortlink;
    }

    function getTicketListSorting($sort) {
        majesticsupport::$_sortlinks['subject'] = $this->getSortArg("subject", $sort);
        majesticsupport::$_sortlinks['priority'] = $this->getSortArg("priority", $sort);
        majesticsupport::$_sortlinks['ticketid'] = $this->getSortArg("ticketid", $sort);
        majesticsupport::$_sortlinks['isanswered'] = $this->getSortArg("isanswered", $sort);
        majesticsupport::$_sortlinks['status'] = $this->getSortArg("status", $sort);
        majesticsupport::$_sortlinks['created'] = $this->getSortArg("created", $sort);
        return;
    }

    private function getTicketHistory($id) {
        if(in_array('tickethistory', majesticsupport::$_active_addons)){
            if(!is_numeric($id)) return false;
            $query = "SELECT al.id,al.message,al.datetime,al.uid
            from `" . majesticsupport::$_db->prefix . "mjtc_support_activity_log`  AS al
            join `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS tic on al.referenceid=tic.id
            where al.referenceid=" . esc_sql($id) . " AND al.eventfor=1 ORDER BY al.datetime DESC ";
            majesticsupport::$_data[5] = majesticsupport::$_db->get_results($query);
        }else{
            majesticsupport::$_data[5] = array();
        }
    }

    function tickDepartmentTransfer($data) {
        $ticketid = $data['ticketid'];
        if (!is_numeric($ticketid))
            return false;
        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $allow = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Ticket Department Transfer');
            if ($allow != true) {
                MJTC_message::MJTC_setMessage(esc_html(__('Your are not allowed', 'majestic-support')), 'updated');
                return;
            }
        }
        $sendEmail = true;
        $date = date_i18n('Y-m-d H:i:s');

        $row = MJTC_includer::MJTC_getTable('tickets');
        if ($row->update(array('id' => $ticketid, 'departmentid' => $data['departmentid'], 'updated' => $date))) {
            MJTC_message::MJTC_setMessage(esc_html(__('The department has been transferred', 'majestic-support')), 'updated');
            $messagetype = esc_html(__('Successfully', 'majestic-support'));
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('The department has not been transferred', 'majestic-support')), 'error');
            $messagetype = esc_html(__('Error', 'majestic-support'));
            $sendEmail = false;
        }

        /* for activity log */
        $current_user = MJTC_includer::MJTC_getObjectClass('user')->MJTC_getMSCurrentUser(); // to get current user name
        $currentUserName = $current_user->display_name;
        $eventtype = esc_html(__('Ticket department transfer', 'majestic-support'));
        $message = esc_html(__('The department is transferred by', 'majestic-support')) . " ( " . esc_html($currentUserName) . " ) ";
        if(in_array('tickethistory', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('tickethistory')->addActivityLog($ticketid, 1, $eventtype, $message, $messagetype);
        }

        // Send Emails
        if ($sendEmail == true) {
            MJTC_includer::MJTC_getModel('email')->sendMail(1, 12, $ticketid); // Mailfor, Department Ticket, Ticketid
            $ticketobject = majesticsupport::$_db->get_row("SELECT * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($ticketid));
            do_action('ms-ticketclose', $ticketobject);
        }

        /* to store internal notes FOR department transfer  */
        if (isset($data['departmenttranfernote']) && $data['departmenttranfernote'] != '') {
            MJTC_includer::MJTC_getModel('note')->storeTicketInternalNote($data, $data['departmenttranfernote']);
        }
        return;
    }

    function assignTicketToStaff($data) {
        $ticketid = $data['ticketid'];
        if (!is_numeric($ticketid))
            return false;
        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $allow = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Assign Ticket To Agent');
            if ($allow != true) {
                MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed', 'majestic-support')), 'error');
                return;
            }
        }
        $sendEmail = true;
        $date = date_i18n('Y-m-d H:i:s');

        $row = MJTC_includer::MJTC_getTable('tickets');
        if ($row->update(array('id' => $ticketid, 'staffid' => $data['staffid'], 'updated' => $date))) {
            MJTC_message::MJTC_setMessage(esc_html(__('Assigned to agent', 'majestic-support')), 'updated');
            $messagetype = esc_html(__('Successfully', 'majestic-support'));
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Not assigned to agent', 'majestic-support')), 'error');
            $messagetype = esc_html(__('Error', 'majestic-support'));
            $sendEmail = false;
        }

        /* for activity log */
        $current_user = MJTC_includer::MJTC_getObjectClass('user')->MJTC_getMSCurrentUser(); // to get current user name
        $currentUserName = isset($current_user->display_name) ? $current_user->display_name : esc_html(__('Guest', 'majestic-support'));
        $eventtype = esc_html(__('Assign ticket to agent', 'majestic-support'));
        $message = esc_html(__('Ticket is assigned to agent by', 'majestic-support')) . " ( " . esc_html($currentUserName) . " ) ";
        if(in_array('tickethistory', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('tickethistory')->addActivityLog($ticketid, 1, $eventtype, $message, $messagetype);
        }

        // Send Emails
        if ($sendEmail == true) {
            MJTC_includer::MJTC_getModel('email')->sendMail(1, 13, $ticketid); // Mailfor, Assign Ticket, Ticketid
            $ticketobject = majesticsupport::$_db->get_row("SELECT * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($ticketid));
            do_action('ms-ticketclose', $ticketobject);
        }

        /* to store internal notes FOR department transfer  */
        if(in_array('note', majesticsupport::$_active_addons)){
            if (isset($data['assignnote']) && $data['assignnote'] != '') {
                MJTC_includer::MJTC_getModel('note')->storeTicketInternalNote($data, $data['assignnote']);
            }
        }
        return;
    }

    function changeTicketPriority($id, $priorityid) {
        if (!is_numeric($id))
            return false;
        if (!is_numeric($priorityid))
            return false;
        if (!$this->checkActionStatusSame($id, array('action' => 'priority', 'id' => $priorityid))) {
            MJTC_message::MJTC_setMessage(esc_html(__('The ticket already has the same priority', 'majestic-support')), 'error');
            return;
        }
        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $allow = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Change Ticket Priority');
            if ($allow == 0) {
                MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed', 'majestic-support')), 'error');
                return;
            }
        }
        $sendEmail = true;
        $date = date_i18n('Y-m-d H:i:s');

        $row = MJTC_includer::MJTC_getTable('tickets');
        if ($row->update(array('id' => $id, 'priorityid' => $priorityid, 'updated' => $date))) {
            MJTC_message::MJTC_setMessage(esc_html(__('Priority has been changed', 'majestic-support')), 'updated');
            $messagetype = esc_html(__('Successfully', 'majestic-support'));
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Priority has not been changed', 'majestic-support')), 'error');
            $messagetype = esc_html(__('Error', 'majestic-support'));
            $sendEmail = false;
        }

        /* for activity log */
        $current_user = MJTC_includer::MJTC_getObjectClass('user')->MJTC_getMSCurrentUser(); // to get current user name
        $currentUserName = $current_user->display_name;
        $eventtype = esc_html(__('Change Priority', 'majestic-support'));
        $message = esc_html(__('Ticket priority is changed by', 'majestic-support')) . " ( " . esc_html($currentUserName) . " ) ";
        if(in_array('tickethistory', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('tickethistory')->addActivityLog($id, 1, $eventtype, $message, $messagetype);
        }
        // Send Emails
        if ($sendEmail == true) {
            MJTC_includer::MJTC_getModel('email')->sendMail(1, 11, $id, 'mjtc_support_tickets'); // Mailfor, Ban email, Ticketid
        }
        return;
    }

    function banEmail($data) {
        if(!in_array('banemail', majesticsupport::$_active_addons)){
            return false;
        }
        $ticketid = $data['ticketid'];
        $uid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
        if(in_array('agent',majesticsupport::$_active_addons)){
            $staffid = MJTC_includer::MJTC_getModel('agent')->getstaffid($uid);
        }else{
            $staffid = '';
        }
        if (!is_numeric($ticketid))
            return false;
        if(!is_admin()){
            if (!is_numeric($staffid))
                return false;
        }

        $email = self::getTicketEmailById($ticketid);
        if (!$this->checkActionStatusSame($ticketid, array('action' => 'banemail', 'email' => $email))) {
            MJTC_message::MJTC_setMessage(esc_html(__('Email already banned', 'majestic-support')), 'error');
            return;
        }

        $sendEmail = true;
        $data = array(
            'email' => $email,
            'submitter' => $staffid,
            'uid' => $uid,
            'created' => date_i18n('Y-m-d H:i:s')
        );

        $row = MJTC_includer::MJTC_getTable('banemail');

        $data = MJTC_includer::MJTC_getModel('majesticsupport')->stripslashesFull($data);// remove slashes with quotes.
        $error = 0;
        if (!$row->bind($data)) {
            $error = 1;
        }
        if (!$row->store()) {
            $error = 1;
        }
        if ($error == 0) {

            MJTC_message::MJTC_setMessage(esc_html(__('The email has been banned', 'majestic-support')), 'updated');
            $messagetype = esc_html(__('Successfully', 'majestic-support'));
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('The email has not been banned', 'majestic-support')), 'error');
            $messagetype = esc_html(__('Error', 'majestic-support'));
            $sendEmail = false;
        }

        /* for activity log */
        $current_user = MJTC_includer::MJTC_getObjectClass('user')->MJTC_getMSCurrentUser(); // to get current user name
        $currentUserName = $current_user->display_name;
        $eventtype = esc_html(__('Ban Email', 'majestic-support'));
        $message = esc_html(__('Email is banned by', 'majestic-support')) . " ( " . $currentUserName . " ) ";
        if(in_array('tickethistory', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('tickethistory')->addActivityLog($ticketid, 1, $eventtype, $message, $messagetype);
        }

        // Send Emails
        if ($sendEmail == true) {
            MJTC_includer::MJTC_getModel('email')->sendMail(2, 1, $ticketid, 'mjtc_support_tickets'); // Mailfor, Ban email, Ticketid
            $ticketobject = majesticsupport::$_db->get_row("SELECT * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($ticketid));
            do_action('ms-ticketclose', $ticketobject);
        }
        return;
    }



    function sendFeedbackMailByTicketid($ticketid) {

        if (!is_numeric($ticketid))
            return false;

        $date = date_i18n('Y-m-d H:i:s');

        $row = MJTC_includer::MJTC_getTable('tickets');
        if ($row->update(array('id' => $ticketid, 'feedbackemail' => 1))) {
            MJTC_includer::MJTC_getModel('email')->sendMail(1, 15, $ticketid); // Mailfor, feedback for Ticket, Ticketid
        }
        return;
    }

    function banEmailAndCloseTicket($data) {
        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $allow = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Ban Email And Close Ticket');
            if ($allow != true) {
                MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed', 'majestic-support')), 'error');
                return;
            }
        }
        self::banEmail($data);
        self::closeTicket($data['ticketid']);
        return;
    }

    /* check can a ticket be opened with in the given days */

    function checkCanReopenTicket($ticketid) {
        if (!is_numeric($ticketid))
            return false;
        $lastreply = MJTC_includer::MJTC_getModel('reply')->getLastReply($ticketid);
        if (!$lastreply)
            $lastreply = date_i18n('Y-m-d H:i:s');
        $days = majesticsupport::$_config['reopen_ticket_within_days'];
        $date = date("Y-m-d H:i:s", MJTC_majesticsupportphplib::MJTC_strtotime(date("Y-m-d H:i:s", MJTC_majesticsupportphplib::MJTC_strtotime($lastreply)) . " +" . esc_html($days) . " day"));
        if ($date < date_i18n('Y-m-d H:i:s'))
            return false;
        else
            return true;
    }

    function reopenTicket($data) {
        $ticketid = $data['ticketid'];
        $internalid = $data['internalid'];
        $lastreply = isset($data['lastreplydate']) ? $data['lastreplydate'] : '';
        if (!is_numeric($ticketid))
            return false;
        //check the permission to reopen ticket
        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $allowed = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Reopen Ticket');
            if ($allowed != true) {
                MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed', 'majestic-support')), 'error');
                return;
            }
        } else if (!$this->validateTicketAction($ticketid, $internalid)) {
            MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed','majestic-support')), 'error');
            return;
        }
        /* check can a ticket be opened with in the given days */
        if ($this->checkCanReopenTicket($ticketid)) {
            $sendEmail = true;
            $date = date_i18n('Y-m-d H:i:s');

            $row = MJTC_includer::MJTC_getTable('tickets');
            if ($row->update(array('id' => $ticketid, 'status' =>0, 'closedreason' =>'', 'updated' => $date))) {
                MJTC_message::MJTC_setMessage(esc_html(__('The ticket has been reopened', 'majestic-support')), 'updated');
                $messagetype = esc_html(__('Successfully', 'majestic-support'));
            } else {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
                MJTC_message::MJTC_setMessage(esc_html(__('The ticket has not been reopened', 'majestic-support')), 'error');
                $messagetype = esc_html(__('Error', 'majestic-support'));
                $sendEmail = false;
            }

            /* for activity log */
            $current_user = MJTC_includer::MJTC_getObjectClass('user')->MJTC_getMSCurrentUser(); // to get current user name
            $currentUserName = isset($current_user->display_name) ? $current_user->display_name : esc_html(__('Guest', 'majestic-support'));
            $eventtype = esc_html(__('Reopen Ticket', 'majestic-support'));
            $message = esc_html(__('The ticket is reopened by', 'majestic-support')) . " ( " . esc_html($currentUserName) . " ) ";
            if(in_array('tickethistory', majesticsupport::$_active_addons)){
                MJTC_includer::MJTC_getModel('tickethistory')->addActivityLog($ticketid, 1, $eventtype, $message, $messagetype);
            }
        } else {
            MJTC_message::MJTC_setMessage(esc_html(__('The ticket reopens time limit end', 'majestic-support')), 'error');
        }


        return;
    }

    private function canUnbanEmail($email) {
        $query = " SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email_banlist` WHERE email = '" . esc_sql($email) . "' ";
        $result = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        if ($result > 0)
            return true;
        else
            return false;
    }

    function unbanEmail($data) {
        $ticketid = $data['ticketid'];
        if (!is_numeric($ticketid))
            return false;
        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $allow = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Unban Email');
            if ($allow != true) {
                MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed', 'majestic-support')), 'error');
                return;
            }
        }
        $email = self::getTicketEmailById($ticketid);
        if ($this->canUnbanEmail($email)) {
            $sendEmail = true;
            $date = date_i18n('Y-m-d H:i:s');
            $query = "DELETE FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email_banlist` WHERE email = '" . esc_sql($email) . " ' ";
            majesticsupport::$_db->query($query);
            if (majesticsupport::$_db->last_error == null) {
                MJTC_message::MJTC_setMessage(esc_html(__('Email has been unbanned', 'majestic-support')), 'updated');
                $messagetype = esc_html(__('Successfully', 'majestic-support'));
            } else {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
                MJTC_message::MJTC_setMessage(esc_html(__('Email has not been unbanned', 'majestic-support')), 'error');
                $messagetype = esc_html(__('Error', 'majestic-support'));
                $sendEmail = false;
            }

            /* for activity log */
            $current_user = MJTC_includer::MJTC_getObjectClass('user')->MJTC_getMSCurrentUser(); // to get current user name
            $currentUserName = $current_user->display_name;
            $eventtype = esc_html(__('Unbanned Email', 'majestic-support'));
            $message = esc_html(__('Email is unbanned by', 'majestic-support')) . " ( " . esc_html($currentUserName) . " ) ";
            if(in_array('tickethistory', majesticsupport::$_active_addons)){
                MJTC_includer::MJTC_getModel('tickethistory')->addActivityLog($ticketid, 1, $eventtype, $message, $messagetype);
            }

            // Send Emails
            if ($sendEmail == true) {
                MJTC_includer::MJTC_getModel('email')->sendMail(2, 2, $ticketid, 'mjtc_support_tickets'); // Mailfor, Unban Ticket, Ticketid
                $ticketobject = majesticsupport::$_db->get_row("SELECT * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($ticketid));
                do_action('ms-ticketclose', $ticketobject);
            }
        } else {
            MJTC_message::MJTC_setMessage(esc_html(__('Email cannot be unbanned', 'majestic-support')), 'error');
        }

        return;
    }

    function markTicketInProgress($data) {
        $ticketid = $data['ticketid'];
        if (!is_numeric($ticketid))
            return false;
        if (!$this->checkActionStatusSame($ticketid, array('action' => 'markinprogress'))) {
            MJTC_message::MJTC_setMessage(esc_html(__('Ticket already marked in progress', 'majestic-support')), 'error');
            return;
        }
        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $allow = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Mark In Progress');
            if ($allow != true) {
                MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed', 'majestic-support')), 'error');
                return;
            }
        }
        $date = date_i18n('Y-m-d H:i:s');
        $sendEmail = true;

        $row = MJTC_includer::MJTC_getTable('tickets');
        if ($row->update(array('id' => $ticketid, 'status' => 2, 'updated' => $date))) {
            MJTC_message::MJTC_setMessage(esc_html(__('The ticket has been marked as in progress', 'majestic-support')), 'updated');
            $messagetype = esc_html(__('Successfully', 'majestic-support'));
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('The ticket has not been marked as in progress', 'majestic-support')), 'error');
            $messagetype = esc_html(__('Error', 'majestic-support'));
            $sendEmail = false;
        }

        /* for activity log */
        $current_user = MJTC_includer::MJTC_getObjectClass('user')->MJTC_getMSCurrentUser(); // to get current user name
        $currentUserName = $current_user->display_name;
        $eventtype = esc_html(__('In progress ticket', 'majestic-support'));
        $message = esc_html(__('The ticket is marked as in progress by', 'majestic-support')) . " ( " . esc_html($currentUserName) . " ) ";
        if(in_array('tickethistory', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('tickethistory')->addActivityLog($ticketid, 1, $eventtype, $message, $messagetype);
        }

        // Send Emails
        if ($sendEmail == true) {
            MJTC_includer::MJTC_getModel('email')->sendMail(1, 9, $ticketid, 'mjtc_support_tickets'); // Mailfor, Unban Ticket, Ticketid
            $ticketobject = majesticsupport::$_db->get_row("SELECT * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($ticketid));
            do_action('ms-ticketclose', $ticketobject);
        }
        return;
    }

    function updateTicketStatusCron() {
        // close ticket
        if(in_array('autoclose', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('autoclose')->autoCloseTicketsCron();
        }

        if(in_array('overdue', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('overdue')->markTicketOverdueCron();
        }
    }

    function sendFeedbackMail() {
        if(!in_array('feedback', majesticsupport::$_active_addons)){
            return;
        }
        if(majesticsupport::$_config['feedback_email_delay_type'] == 1){
            $intrval_string = " date(DATE_ADD(closed,INTERVAL " . (int)majesticsupport::$_config['feedback_email_delay']." DAY)) < '".date("Y-m-d")."'";
        }else{
            $intrval_string = " DATE_ADD(closed,INTERVAL " .(int) majesticsupport::$_config['feedback_email_delay'] . " HOUR) < '".date_i18n("Y-m-d H:i:s")."'";
        }
        // select closed ticket
        $query = "SELECT id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE ".$intrval_string." AND status = 4 AND (feedbackemail != 1  OR feedbackemail IS NULL) AND closed IS NOT NULL";
        $ticketids = majesticsupport::$_db->get_results($query);
        if(!empty($ticketids)){
            foreach ($ticketids as $key) {
                if(is_numeric($key->id)){
                    MJTC_includer::MJTC_getModel('ticket')->sendFeedbackMailByTicketid($key->id);
                }
            }
        }
        return;
    }

    function removeFileCustom($id,$key){
        $filename = MJTC_majesticsupportphplib::MJTC_str_replace(' ', '_', $key);
        $maindir = wp_upload_dir();
        $basedir = $maindir['basedir'];
        $datadirectory = majesticsupport::$_config['data_directory'];
        $path = $basedir . '/' . $datadirectory. '/attachmentdata/ticket';

        $query = "SELECT attachmentdir FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE id = ".esc_sql($id);
        $foldername = majesticsupport::$_db->get_var($query);
        $userpath = $path . '/' . $foldername.'/'.$filename;
        unlink($userpath);
        return ;
    }

    function getTicketidForVisitor($token) {
        include_once MJTC_PLUGIN_PATH . 'includes/encoder.php';
        $encoder = new MJTC_encoder();
        $decryptedtext = $encoder->MJTC_decrypt($token);
        $array = json_decode($decryptedtext, true);
        $emailaddress = $array['emailaddress'];
        $trackingid = $array['trackingid'];
        if (isset($array['sitelink']) && $array['sitelink'] != '') {
            $siteLink = $array['sitelink'];
            include_once MJTC_PLUGIN_PATH . 'includes/encoder.php';
            $encoder = new MJTC_encoder();
            $savedSiteLink = get_option('ms_encripted_site_link');
            $decryptedSiteLink = $encoder->MJTC_decrypt($siteLink);
            $decryptedSavedSiteLink = $encoder->MJTC_decrypt($savedSiteLink);
            if ($decryptedSiteLink != $decryptedSavedSiteLink) {
                return false;
            }
        }
        if($emailaddress == '' && $trackingid == ''){
            return false;
        }
        $query = "SELECT id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE email = '" . esc_sql($emailaddress) . "' AND ticketid = '" . esc_sql($trackingid) . "'";
        $ticketid = majesticsupport::$_db->get_var($query);
        return $ticketid;
    }

    function createTokenByEmailAndTrackingId($emailaddress, $trackingid) {
        include_once MJTC_PLUGIN_PATH . 'includes/encoder.php';
        $encoder = new MJTC_encoder();
        $token = $encoder->MJTC_encrypt(json_encode(array('emailaddress' => $emailaddress, 'trackingid' => $trackingid)));
        return $token;
    }

    function validateTicketDetailForStaff($ticketid) {
        if(!in_array('agent', majesticsupport::$_active_addons)){
            return false;
        }
        if (!is_numeric($ticketid))
            return false;
        $allowed = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('All Tickets');
        if($allowed == true){
            return true;
        }
        // check in assign department
        $c_uid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
        $query = "SELECT ticket.id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
            JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` AS dept ON ticket.departmentid = dept.departmentid
            JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS staff ON dept.staffid = staff.id AND staff.uid = " . esc_sql($c_uid) . "
            WHERE ticket.id = " . esc_sql($ticketid);
        $id = majesticsupport::$_db->get_var($query);

        if ($id) {
            return true;
        } else {
            // check in assign ticket
            $query = "SELECT ticket.id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS staff ON ticket.staffid = staff.id AND staff.uid = " . esc_sql($c_uid);
            $query .= " WHERE ticket.id = ". esc_sql($ticketid);
            $id = majesticsupport::$_db->get_var($query);
            if ($id)
                return true;
            else
                return false;
        }
    }

    function totalTicket() {
        $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets`";
        $total = majesticsupport::$_db->get_var($query);
        return $total;
    }

    function validateTicketDetailForUser($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT uid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($id);
        $uid = majesticsupport::$_db->get_var($query);

        if ($uid == MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid()) {
            return true;
        }elseif($uid != '') {
            majesticsupport::$_data['error_message'] = 2;// to prompt user that he can not view this ticket.
            return;
        }else {
            return false;
        }
    }

    function validateTicketDetailForVisitor($id) {
        if (!isset($_COOKIE['majestic-support-token-tkstatus'])) {
            return false;
        }
        $token = MJTC_majesticsupportphplib::MJTC_htmlspecialchars($_COOKIE['majestic-support-token-tkstatus']);
        include_once MJTC_PLUGIN_PATH . 'includes/encoder.php';
        $encoder = new MJTC_encoder();
        $decryptedtext = $encoder->MJTC_decrypt($token);
        $array = json_decode($decryptedtext, true);
        $emailaddress = $array['emailaddress'];
        $trackingid = $array['trackingid'];
        $query = "SELECT id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE email = '" . esc_sql($emailaddress) . "' AND ticketid = '" . esc_sql($trackingid) . "'";
        $ticketid = majesticsupport::$_db->get_var($query);

        if ($ticketid == $id) {
            return true;
        } else {
            $query = "SELECT id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = ".esc_sql($id);
            $ticketid = majesticsupport::$_db->get_var($query);
            if($ticketid > 0){
                majesticsupport::$_data['error_message'] = 1;// to prompt user to login
            }
            majesticsupport::$_data['error_message'] = 1;
            return false;
        }
    }

    function checkActionStatusSame($id, $array) {
        switch ($array['action']) {
            case 'priority':
                if(!is_numeric($id)) return false;
                $result = majesticsupport::$_db->get_var('SELECT COUNT(id) FROM `' . majesticsupport::$_db->prefix . 'mjtc_support_tickets` WHERE id = ' . esc_sql($id) . ' AND priorityid = ' . esc_sql($array['id']));
                break;
            case 'markoverdue':
                if(!is_numeric($id)) return false;
                $result = majesticsupport::$_db->get_var('SELECT COUNT(id) FROM `' . majesticsupport::$_db->prefix . 'mjtc_support_tickets` WHERE id = ' . esc_sql($id) . ' AND isoverdue = 1');
                break;
            case 'markinprogress':
                if(!is_numeric($id)) return false;
                $result = majesticsupport::$_db->get_var('SELECT COUNT(id) FROM `' . majesticsupport::$_db->prefix . 'mjtc_support_tickets` WHERE id = ' . esc_sql($id) . ' AND status = 2');
                break;
            case 'closeticket':
                if(!is_numeric($id)) return false;
                $result = majesticsupport::$_db->get_var('SELECT COUNT(id) FROM `' . majesticsupport::$_db->prefix . 'mjtc_support_tickets` WHERE id = ' . esc_sql($id) . ' AND status = 4');
                break;
            case 'banemail':
                $result = majesticsupport::$_db->get_var('SELECT COUNT(id) FROM `' . majesticsupport::$_db->prefix . 'mjtc_support_email_banlist` WHERE email = "' . esc_sql($array['email']) . '"');
                break;
        }
        if ($result > 0) {
            return false;
        } else {
            return true;
        }
    }

    function ticketAssignToMe($ticketid, $staffid) {
        if (!is_numeric($ticketid))
            return false;
        if (!is_numeric($staffid))
            return false;
        $row = MJTC_includer::MJTC_getTable('tickets');
        $row->update(array('id' => $ticketid, 'staffid' => $staffid));

        return true;
    }

    function isTicketAssigned($ticketid){
        if (! in_array('agent',majesticsupport::$_active_addons)) {
            return false;
        }
        if (!is_numeric($ticketid))
            return false;
        $query = "SELECT staffid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id=".esc_sql($ticketid);
        $staffid = majesticsupport::$_db->get_var($query);
        if($staffid > 0)
            return true;
        return false;
    }


    function getMyTicketInfo_Widget($maxrecord){
        if(!is_numeric($maxrecord)) return false;
        if(!MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()){
            $uid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
                // Data
            $query = "SELECT DISTINCT ticket.id,ticket.subject,ticket.status,ticket.name,priority.priority AS priority,priority.prioritycolour AS prioritycolour
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                        WHERE ticket.uid = ".esc_sql($uid)." AND (ticket.status = 0 OR ticket.status = 1) ORDER BY ticket.status DESC LIMIT $maxrecord";

            if(in_array('agent',majesticsupport::$_active_addons)){
                $staffid = MJTC_includer::MJTC_getModel('agent')->getStaffId($uid);
                if($staffid){
                    // Data
                    $query = "SELECT DISTINCT ticket.id,ticket.subject,ticket.status,ticket.name,priority.priority AS priority,priority.prioritycolour AS prioritycolour
                                FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                                LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                                LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
                                LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS staff ON staff.uid = ticket.uid
                                WHERE (ticket.staffid = ".esc_sql($staffid)." OR ticket.departmentid IN (SELECT dept.departmentid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` AS dept WHERE dept.staffid = ".esc_sql($staffid).")) AND (ticket.status = 0 OR ticket.status = 1) ORDER BY ticket.status DESC LIMIT $maxrecord";
                }
            }
            if(isset($query)){
                majesticsupport::$_data['widget_myticket'] = majesticsupport::$_db->get_results($query);
                if (majesticsupport::$_db->last_error != null) {
                    MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
                }
            }else{
                majesticsupport::$_data['widget_myticket'] = false;
            }
        }else{
            majesticsupport::$_data['widget_myticket'] = false;
        }
        return;
    }

    function getLatestTicketForDashboard(){
        $query = "SELECT ticket.id,ticket.subject,ticket.name,priority.priority,priority.prioritycolour
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON priority.id = ticket.priorityid
                    ORDER BY ticket.status ASC, ticket.created DESC LIMIT 0, 5";
        $tickets = majesticsupport::$_db->get_results($query);
        return $tickets;
    }
    function getAttachmentByTicketId($id){
        if(!is_numeric($id)) return false;
        $query = "SELECT attachment.filename , ticket.attachmentdir
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_attachments` AS attachment
                    JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket ON ticket.id = attachment.ticketid AND ticket.id =".esc_sql($id). " AND attachment.replyattachmentid = 0 ";
        $attachments = majesticsupport::$_db->get_results($query);
        return $attachments;
    }

    function getTotalStatsForDashboard(){
        $curdate = date_i18n('Y-m-d');
        $fromdate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));

        $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00' OR lastreply = '') AND date(created) >= '".esc_sql($fromdate)."'AND date(created) <= '".esc_sql($curdate)."'";
        $result['open'] = majesticsupport::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '".esc_sql($fromdate)."' AND date(created) <= '".esc_sql($curdate)."'";
        $result['answered'] = majesticsupport::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND date(created) >= '".esc_sql($fromdate)."' AND date(created) <= '".esc_sql($curdate)."'";
        $result['overdue'] = majesticsupport::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00' AND lastreply != '') AND date(created) >= '".esc_sql($fromdate)."' AND date(created) <= '".esc_sql($curdate)."'";
        $result['pending'] = majesticsupport::$_db->get_var($query);

        return $result;
    }

    function getRandomFolderName() {
        $foldername = "";
        $length = 7;
        $possible = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
        // we refer to the length of $possible a few times, so let's grab it now
        $maxlength = MJTC_majesticsupportphplib::MJTC_strlen($possible);
        if ($length > $maxlength) { // check for length overflow and truncate if necessary
            $length = $maxlength;
        }
        // set up a counter for how many characters are in the ticketid so far
        $i = 0;
        // add random characters to $password until $length is reached
        while ($i < $length) {
            // pick a random character from the possible ones
            $char = MJTC_majesticsupportphplib::MJTC_substr($possible, mt_rand(0, $maxlength - 1), 1);
            if (!MJTC_majesticsupportphplib::MJTC_strstr($foldername, $char)) {
                if ($i == 0) {
                    if (ctype_alpha($char)) {
                        $foldername .= $char;
                        $i++;
                    }
                } else {
                    $foldername .= $char;
                    $i++;
                }
            }
        }
        return $foldername;
    }

    static function generateHash($id){
        if(!is_numeric($id))
            return null;
        return MJTC_majesticsupportphplib::MJTC_safe_encoding(json_encode(MJTC_majesticsupportphplib::MJTC_safe_encoding($id)));
    }

    function getUIdById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT uid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($id);
        $ticketuid = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $ticketuid;
    }

    function getNotificationIdById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT notificationid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($id);
        $notificationid = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $notificationid;
    }

    function getAdminTicketSearchFormData($search_userfields){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'my-ticket') ) {
            die( 'Security check Failed' );
        }
        $ms_search_array = array();
        $search_userfields = MJTC_includer::MJTC_getObjectClass('customfields')->userFieldsForSearch(1);
        $ms_search_array['subject'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('subject' , ''));
        $ms_search_array['name'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('name' , ''));
        $ms_search_array['email'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('email' , ''));
        $ms_search_array['ticketid'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('ticketid' , ''));
        $ms_search_array['datestart'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('datestart' , ''));
        $ms_search_array['dateend'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('dateend' , ''));
        $ms_search_array['orderid'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('orderid' , ''));
        $ms_search_array['eddorderid'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('eddorderid', ''));
        $ms_search_array['priority'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('priority' , ''));
        $ms_search_array['departmentid'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('departmentid' , ''));
        $ms_search_array['list'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('list', null ,1));
        $ms_search_array['staffid'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('staffid' , ''));
        $ms_search_array['sortby'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('sortby' , ''));
        $ms_search_array['search_from_ticket'] = 1;
        if (!empty($search_userfields)) {
            foreach ($search_userfields as $uf) {
                $ms_search_array['ms_ticket_custom_field'][$uf->field] = MJTC_request::MJTC_getVar($uf->field, 'post');
            }
        }
        return $ms_search_array;
    }

    function getFrontSideTicketSearchFormData($search_userfields){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'my-ticket') ) {
            die( 'Security check Failed' );
        }$ms_search_array = array();
        $search_userfields = MJTC_includer::MJTC_getObjectClass('customfields')->userFieldsForSearch(1);
        if(MJTC_request::MJTC_getVar('ms-subject' , '') != ''){
            $ms_search_array['subject'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('ms-subject' , ''));
        } else {
            $ms_search_array['subject'] = '';
        }
        if (MJTC_request::MJTC_getVar('ms-from' , '') != '') {
            $ms_search_array['name'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('ms-from' , ''));
        } else {
            $ms_search_array['name'] = '';
        }
        if (MJTC_request::MJTC_getVar('ms-email' , '') != '') {
            $ms_search_array['email'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('ms-email' , ''));
        } else {
            $ms_search_array['email'] = '';
        }
        if (MJTC_request::MJTC_getVar('ms-ticket') != '') {
            $ms_search_array['ticketid'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('ms-ticket'));
        } else {
            $ms_search_array['ticketid'] = '';
        }
        if (MJTC_request::MJTC_getVar('ms-datestart' , '') != '') {
            $ms_search_array['datestart'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('ms-datestart' , ''));
        } else {
            $ms_search_array['datestart'] = '';
        }
        if (MJTC_request::MJTC_getVar('ms-dateend' , '') != '') {
            $ms_search_array['dateend'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('ms-dateend' , ''));
        } else {
            $ms_search_array['dateend'] = '';
        }
        if (MJTC_request::MJTC_getVar('ms-orderid' , '') != '') {
            $ms_search_array['orderid'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('ms-orderid' , ''));
        } else {
            $ms_search_array['orderid'] = '';
        }
        if (MJTC_request::MJTC_getVar('ms-eddorderid' , '') != '') {
            $ms_search_array['eddorderid'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('ms-eddorderid', ''));
        } else {
            $ms_search_array['eddorderid'] = '';
        }
        if (MJTC_request::MJTC_getVar('ms-priorityid' , '') != '') {
            $ms_search_array['priority'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('ms-priorityid' , ''));
        } else {
            $ms_search_array['priority'] = '';
        }
        if (MJTC_request::MJTC_getVar('ms-departmentid' , '') != '') {
            $ms_search_array['departmentid'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('ms-departmentid' , ''));
        } else {
            $ms_search_array['departmentid'] = '';
        }
        if (MJTC_request::MJTC_getVar('list', null ,1) != '') {
            $ms_search_array['list'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('list', null ,1));
        } else {
            $ms_search_array['list'] = '';
        }
        $ms_search_array['assignedtome'] = MJTC_request::MJTC_getVar('assignedtome', 'post');
        if (MJTC_request::MJTC_getVar('staffid' , '') != '') {
            $ms_search_array['staffid'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('staffid' , ''));
        } else {
            $ms_search_array['staffid'] = '';
        }
        if (MJTC_request::MJTC_getVar('sortby' , '') != '') {
            $ms_search_array['sortby'] = MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('sortby' , ''));
        } else {
            $ms_search_array['sortby'] = '';
        }
        if (MJTC_request::MJTC_getVar('ms-ticketsearchkeys', 'post') != '') {
            $ms_search_array['ticketkeys'] = MJTC_majesticsupportphplib::MJTC_addslashes(MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar('ms-ticketsearchkeys', 'post')));
        } else {
            $ms_search_array['ticketkeys'] = '';
        }
        $ms_search_array['search_from_ticket'] = 1;
        if (!empty($search_userfields)) {
            foreach ($search_userfields as $uf) {
                $ms_search_array['ms_ticket_custom_field'][$uf->field] = MJTC_request::MJTC_getVar($uf->field, 'post');
            }
        }
        return $ms_search_array;
    }

    function getCookiesSavedSearchDataTicket($search_userfields){
        $ms_search_array = array();
        $ticket_search_cookie_data = '';
        if(isset($_COOKIE['ms_ticket_search_data'])){
            $ticket_search_cookie_data = MJTC_majesticsupportphplib::MJTC_htmlspecialchars($_COOKIE['ms_ticket_search_data']);
            $ticket_search_cookie_data = json_decode( MJTC_majesticsupportphplib::MJTC_safe_decoding($ticket_search_cookie_data) , true );
        }
        if($ticket_search_cookie_data != '' && isset($ticket_search_cookie_data['search_from_ticket']) && $ticket_search_cookie_data['search_from_ticket'] == 1){
            $ms_search_array['subject'] = $ticket_search_cookie_data['subject'];
            $ms_search_array['name'] = $ticket_search_cookie_data['name'];
            $ms_search_array['email'] = $ticket_search_cookie_data['email'];
            $ms_search_array['ticketid'] = $ticket_search_cookie_data['ticketid'];
            $ms_search_array['datestart'] = $ticket_search_cookie_data['datestart'];
            $ms_search_array['dateend'] = $ticket_search_cookie_data['dateend'];
            $ms_search_array['orderid'] = $ticket_search_cookie_data['orderid'];
            $ms_search_array['eddorderid'] = $ticket_search_cookie_data['eddorderid'];
            $ms_search_array['priority'] = $ticket_search_cookie_data['priority'];
            $ms_search_array['departmentid'] = $ticket_search_cookie_data['departmentid'];
            $ms_search_array['staffid'] = $ticket_search_cookie_data['staffid'];
            $ms_search_array['sortby'] = $ticket_search_cookie_data['sortby'];
            $ms_search_array['list'] = $ticket_search_cookie_data['list'];
            $ms_search_array['assignedtome'] = isset($ticket_search_cookie_data['assignedtome']) ? $ticket_search_cookie_data['assignedtome'] : null;
            $ms_search_array['ticketkeys'] = isset($ticket_search_cookie_data['ticketkeys']) ? $ticket_search_cookie_data['ticketkeys'] : false;
            if (!empty($search_userfields)) {
                foreach ($search_userfields as $uf) {
                    $ms_search_array['ms_ticket_custom_field'][$uf->field] = (isset($ticket_search_cookie_data['ms_ticket_custom_field'][$uf->field]) && $ticket_search_cookie_data['ms_ticket_custom_field'][$uf->field] != '') ? $ticket_search_cookie_data['ms_ticket_custom_field'][$uf->field] : null;
                }
            }
        }

        return $ms_search_array;
    }

    function setSearchVariableForTicket($ms_search_array,$search_userfields){

        majesticsupport::$_search['ticket']['subject'] = isset($ms_search_array['subject']) ? $ms_search_array['subject'] : null;
        majesticsupport::$_search['ticket']['name'] = isset($ms_search_array['name']) ? $ms_search_array['name'] : null;
        majesticsupport::$_search['ticket']['email'] = isset($ms_search_array['email']) ? $ms_search_array['email'] : null;
        majesticsupport::$_search['ticket']['ticketid'] = isset($ms_search_array['ticketid']) ? $ms_search_array['ticketid'] : null;
        majesticsupport::$_search['ticket']['datestart'] = isset($ms_search_array['datestart']) ? $ms_search_array['datestart'] : null;
        majesticsupport::$_search['ticket']['dateend'] = isset($ms_search_array['dateend']) ? $ms_search_array['dateend'] : null;
        majesticsupport::$_search['ticket']['orderid'] = isset($ms_search_array['orderid']) ? $ms_search_array['orderid'] : null;
        majesticsupport::$_search['ticket']['eddorderid'] = isset($ms_search_array['eddorderid']) ? $ms_search_array['eddorderid'] : null;
        majesticsupport::$_search['ticket']['priority'] = isset($ms_search_array['priority']) ? $ms_search_array['priority'] : null;
        majesticsupport::$_search['ticket']['departmentid'] = isset($ms_search_array['departmentid']) ? $ms_search_array['departmentid'] : null;
        majesticsupport::$_search['ticket']['staffid'] = isset($ms_search_array['staffid']) ? $ms_search_array['staffid'] : null;
        majesticsupport::$_search['ticket']['sortby'] = isset($ms_search_array['sortby']) ? $ms_search_array['sortby'] : null;
        majesticsupport::$_search['ticket']['list'] = isset($ms_search_array['list']) ? $ms_search_array['list'] : 1;
        // frontend
        majesticsupport::$_search['ticket']['assignedtome'] = isset($ms_search_array['assignedtome']) ? $ms_search_array['assignedtome'] : null;
        majesticsupport::$_search['ticket']['ticketkeys'] = isset($ms_search_array['ticketkeys']) ? $ms_search_array['ticketkeys'] : false;
        if (!empty($search_userfields)) {
            foreach ($search_userfields as $uf) {
                majesticsupport::$_search['ms_ticket_custom_field'][$uf->field] = isset($ms_search_array['ms_ticket_custom_field'][$uf->field]) ? $ms_search_array['ms_ticket_custom_field'][$uf->field] : null;
            }
        }
    }
    function checkIsTicketDuplicate($subject,$email){
        if(empty($subject)) return false;
        if(empty($email)) return true;

        $curdate = date_i18n('Y-m-d H:i:s');
        $query = 'SELECT created FROM `' . majesticsupport::$_db->prefix . 'mjtc_support_tickets` WHERE email = "' . esc_sql($email) . '" AND subject = "' . esc_sql($subject) . '" ORDER BY created DESC LIMIT 1';
        $datetime = majesticsupport::$_db->get_var($query);
        if($datetime){
            $diff = MJTC_majesticsupportphplib::MJTC_strtotime($curdate) - MJTC_majesticsupportphplib::MJTC_strtotime($datetime);
            if($diff <= 15){
				return false;
            }
        }
        return true;
    }
    function getDefaultMultiFormId(){
        $query = "SHOW TABLES LIKE '%mjtc_support_multiform%'";
        $count = majesticsupport::$_db->query($query);
        if ($count == 1) {
            $query = "SELECT * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_multiform` WHERE is_default = 1 ";
            $id = majesticsupport::$_db->get_row($query);
            if(isset($id)) {
                
                return $id->id;
            }
        }
        return 1;
    }

    function MJTC_isFieldRequired(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'is-field-required') ) {
            die( 'Security check Failed' );
        }
        $field = MJTC_request::MJTC_getVar('field');
        $query = "SELECT required  FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE  field ='".esc_sql($field)."'";
        return majesticsupport::$_db->get_var($query);
    }

    function getClosedBy($id){
        if ($id == 0) {
            $closedBy = esc_html(__('System', 'majestic-support'));
        } else if($id == -1){
            $closedBy = esc_html(__('Guest', 'majestic-support'));
        } else {
            $query = "SELECT display_name AS name FROM `" . majesticsupport::$_wpprefixforuser . "mjtc_support_users` WHERE id = " . esc_sql($id);
            $closedBy = majesticsupport::$_db->get_var($query);
        }
        return $closedBy;
    }

    function checkForTicketOwner($id) {
        if (!is_numeric($id))
            return false;
        //ignore if admin or agent or visitor
        if(!MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest() && !current_user_can('manage_options') && !(in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff())){
            $ticketUid = MJTC_includer::MJTC_getModel('ticket')->getUIdById($id);
            $currentuserid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
            if ($currentuserid != $ticketUid){
                return false;
            }
        }
        return true;
    }	

    function validateTicketAction($ticketid, $internalid) {
        if (!is_numeric($ticketid)){
            return false;
        }
        //ignore if admin or agent
        if(!current_user_can('manage_options') && !(in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff())){
            $query = "SELECT id,internalid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE id = " . esc_sql($ticketid);
            $ticketData = majesticsupport::$_db->get_row($query);
            if (!isset($internalid) || $internalid == '' || $internalid == 0){
                if ($ticketData->internalid != '') {
                    // if ticket have internalid but miss in the data
                    return false;
                }
            }
            if ($ticketData->id != $ticketid || $ticketData->internalid != $internalid) {
                // if ticket stored data is not match with the sent data
                return false;
            }
        }
        if (!$this->checkForTicketOwner($ticketid)) {
            // if the current user is not ticket owner
            return false;
        }
        return true;
    }
}
?>
