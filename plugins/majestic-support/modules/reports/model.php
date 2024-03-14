<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_reportsModel {

    function getOverallReportData(){

        //Overall Data by status
        $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` ";
        $allticket = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status != 4 AND status != 5";
        $openticket = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE (status = 4 OR status = 5)";
        $closeticket = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 5 AND status != 0";
        $answeredticket = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4";
        $overdueticket = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00')";
        $pendingticket = majesticsupport::$_db->get_var($query);

        majesticsupport::$_data['ticket_total']['allticket'] = $allticket;
        majesticsupport::$_data['ticket_total']['openticket'] = $openticket;
        majesticsupport::$_data['ticket_total']['closeticket'] = $closeticket;
        majesticsupport::$_data['ticket_total']['answeredticket'] = $answeredticket;
        majesticsupport::$_data['ticket_total']['overdueticket'] = $overdueticket;
        majesticsupport::$_data['ticket_total']['pendingticket'] = $pendingticket;

        majesticsupport::$_data['status_chart'] = "['".esc_html(__('New','majestic-support'))."',$openticket],['".esc_html(__('Answered','majestic-support'))."',$answeredticket],['".esc_html(__('Overdue','majestic-support'))."',$overdueticket],['".esc_html(__('Pending','majestic-support'))."',$pendingticket]";
        $total = $openticket + $closeticket + $answeredticket + $overdueticket + $pendingticket;
        majesticsupport::$_data['bar_chart'] = "
        ['".esc_html(__('New','majestic-support'))."',$openticket,'#FF9900'],
        ['".esc_html(__('Answered','majestic-support'))."',$answeredticket,'#2168A2'],
        ['".esc_html(__('Closed','majestic-support'))."',$closeticket,'#3D355A'],
        ['".esc_html(__('Pending','majestic-support'))."',$pendingticket,'#f39f10'],
        ['".esc_html(__('Overdue','majestic-support'))."',$overdueticket,'#B82B2B']
        ";

        $query = "SELECT dept.departmentname,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE departmentid = dept.id) AS totalticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_departments` AS dept";
        $department = majesticsupport::$_db->get_results($query);
        majesticsupport::$_data['pie3d_chart1'] = "";
        foreach($department AS $dept){
            majesticsupport::$_data['pie3d_chart1'] .= "['".majesticsupport::MJTC_getVarValue($dept->departmentname)."',$dept->totalticket],";
        }

        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id) AS totalticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ORDER BY priority.priority";
        $department = majesticsupport::$_db->get_results($query);
        majesticsupport::$_data['pie3d_chart2'] = "";
        foreach($department AS $dept){
            majesticsupport::$_data['pie3d_chart2'] .= "['".majesticsupport::MJTC_getVarValue($dept->priority)."',$dept->totalticket],";
        }
        if(in_array('emailpiping', majesticsupport::$_active_addons)){
            $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE ticketviaemail = 1";
            $ticketviaemail = majesticsupport::$_db->get_var($query);
            $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_replies` WHERE ticketviaemail = 1";
            $replyviaemail = majesticsupport::$_db->get_var($query);
        }else{
            $ticketviaemail = '';
            $replyviaemail = '';
        }
        $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE ticketviaemail = 0";
        $directticket = majesticsupport::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_replies` WHERE ticketviaemail = 0";
        $directreply = majesticsupport::$_db->get_var($query);

        majesticsupport::$_data['stack_data'] = "['".esc_html(__('Tickets','majestic-support'))."',$directticket,$ticketviaemail,''],['".esc_html(__('Replies','majestic-support'))."',$directreply,$replyviaemail,'']";

        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id AND status = 0 AND (lastreply = '0000-00-00 00:00:00') ) AS totalticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ORDER BY priority.priority";
        $openticket_pr = majesticsupport::$_db->get_results($query);
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id AND isanswered = 1 AND status != 4 AND status != 0 ) AS totalticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ORDER BY priority.priority";
        $answeredticket_pr = majesticsupport::$_db->get_results($query);
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id AND isoverdue = 1 AND status != 4 ) AS totalticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ORDER BY priority.priority";
        $overdueticket_pr = majesticsupport::$_db->get_results($query);
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id AND isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') ) AS totalticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ORDER BY priority.priority";
        $pendingticket_pr = majesticsupport::$_db->get_results($query);
        majesticsupport::$_data['stack_chart_horizontal']['title'] = "['".esc_html(__('Priority','majestic-support'))."','".esc_html(__('Overdue','majestic-support'))."','".esc_html(__('Pending','majestic-support'))."','".esc_html(__('Answered','majestic-support'))."','".esc_html(__('New','majestic-support'))."']";
        majesticsupport::$_data['stack_chart_horizontal']['data'] = "";

        foreach($overdueticket_pr AS $index => $pr){
            majesticsupport::$_data['stack_chart_horizontal']['data'] .= "[";
            majesticsupport::$_data['stack_chart_horizontal']['data'] .= "'".majesticsupport::MJTC_getVarValue($pr->priority)."',";
            majesticsupport::$_data['stack_chart_horizontal']['data'] .= $overdueticket_pr[$index]->totalticket.",";
            majesticsupport::$_data['stack_chart_horizontal']['data'] .= $pendingticket_pr[$index]->totalticket.",";
            majesticsupport::$_data['stack_chart_horizontal']['data'] .= $answeredticket_pr[$index]->totalticket.",";
            majesticsupport::$_data['stack_chart_horizontal']['data'] .= $openticket_pr[$index]->totalticket.",";
            majesticsupport::$_data['stack_chart_horizontal']['data'] .= "],";
        }

        if(in_array('agent',majesticsupport::$_active_addons)){
            $query = "SELECT staff.firstname,staff.lastname,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE staffid = staff.id) AS totalticket
                        FROM `".majesticsupport::$_db->prefix."mjtc_support_staff` AS staff";
            $agenttickets = majesticsupport::$_db->get_results($query);
            majesticsupport::$_data['slice_chart'] = '';
            if(!empty($agenttickets))
            foreach($agenttickets AS $ticket){
                $agentname = $ticket->firstname;
                if(!empty($ticket->lastname)){
                    $agentname .= ' '.$ticket->lastname;
                }
                majesticsupport::$_data['slice_chart'] .= "['".$agentname."',$ticket->totalticket],";
            }
        }

        //To show priority colors on chart
        $query = "SELECT prioritycolour FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` ORDER BY priority ";
        $jsonColorList = "[";
        foreach(majesticsupport::$_db->get_results($query) as $priority){
            $jsonColorList.= "'".$priority->prioritycolour."',";
        }
        $jsonColorList .= "]";
        majesticsupport::$_data['priorityColorList'] = $jsonColorList;
        //end priority colors
    }

    function getDepartmentReportsFE(){
        if( !in_array('agent',majesticsupport::$_active_addons) ){
            return;
        }
        $uid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
        $staffid = MJTC_includer::MJTC_getModel('agent')->getStaffId($uid);
        $query = "SELECT dept.departmentname,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE departmentid = dept.id ) AS totalticket
            FROM `".majesticsupport::$_db->prefix."mjtc_support_departments` AS dept
            JOIN `".majesticsupport::$_db->prefix."mjtc_support_acl_user_access_departments` AS acl ON acl.departmentid = dept.id
            WHERE acl.staffid = ".esc_sql($staffid)." AND dept.status=1";

        $department = majesticsupport::$_db->get_results($query);
        majesticsupport::$_data['pie3d_chart1'] = "";
        $i = 0;
        foreach($department AS $dept){
            if($dept->totalticket == 0)
                $i += 1;
            majesticsupport::$_data['pie3d_chart1'] .= "['".majesticsupport::MJTC_getVarValue($dept->departmentname)."',$dept->totalticket],";
        }

        if(count($department) == $i)
            majesticsupport::$_data['pie3d_chart1'] = '';

        // pagination
        $query = "SELECT count(dept.id)
            FROM `".majesticsupport::$_db->prefix."mjtc_support_departments` AS dept
            JOIN `".majesticsupport::$_db->prefix."mjtc_support_acl_user_access_departments` AS acl ON acl.departmentid = dept.id
            WHERE acl.staffid = ".esc_sql($staffid)." AND dept.status=1";
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data['total'] = $total;
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total);

        $query = "SELECT dept.departmentname,
            (SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00') AND departmentid = dept.id) AS openticket,
            (SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE status = 4 AND departmentid = dept.id) AS closeticket,
            (SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND departmentid = dept.id) AS answeredticket,
            (SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND departmentid = dept.id) AS overdueticket,
            (SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND departmentid = dept.id) AS pendingticket
            FROM `".majesticsupport::$_db->prefix."mjtc_support_departments` AS dept
            JOIN `".majesticsupport::$_db->prefix."mjtc_support_acl_user_access_departments` AS acl ON acl.departmentid = dept.id
            WHERE acl.staffid = ".esc_sql($staffid)." AND dept.status=1";
        $query .= " LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        $departments = majesticsupport::$_db->get_results($query);
        majesticsupport::$_data['departments_report'] = $departments;

        return;
    }

    function getStaffReports(){
        if( !in_array('agent',majesticsupport::$_active_addons) ){
            return;
        }
        $date_start = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['date_start'] : '';
        $date_end = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['date_end'] : '';

        if(isset($date_start) && $date_start != ""){
            $date_start = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start));
        }
        if(isset($date_end) && $date_end != ""){
            $date_end = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end));
        }
        if($date_start > $date_end){
            $tmp = $date_start;
            $date_start = $date_end;
            $date_end = $tmp;
        }

        $uid = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['uid'] : '';

        //Line Chart Data
        $curdate = ($date_start != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start)) : date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));
        $dates = '';
        $fromdate = ($date_end != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end)) : date_i18n('Y-m-d');
        majesticsupport::$_data['filter']['date_start'] = $curdate;
        majesticsupport::$_data['filter']['date_end'] = $fromdate;
        majesticsupport::$_data['filter']['uid'] = $uid;
        // forexport
        $_SESSION['forexport']['curdate'] = $curdate;
        $_SESSION['forexport']['fromdate'] = $fromdate;
        $_SESSION['forexport']['uid'] = $uid;

        $staffid = MJTC_includer::MJTC_getModel('agent')->getStaffId($uid);
        majesticsupport::$_data['filter']['staffname'] = MJTC_includer::MJTC_getModel('agent')->getMyName($staffid);
        $nextdate = $fromdate;
        //Query to get Data
        $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` ";
        if($uid) $query .= " WHERE staffid = ".esc_sql($staffid);
        $allticket = majesticsupport::$_db->get_var($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($uid) $query .= " AND staffid = ".esc_sql($staffid);
        $openticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($uid) $query .= " AND staffid = ".esc_sql($staffid);
        $closeticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($uid) $query .= " AND staffid = ".esc_sql($staffid);
        $answeredticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($uid) $query .= " AND staffid = ".esc_sql($staffid);
        $overdueticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($uid) $query .= " AND staffid = ".esc_sql($staffid);
        $pendingticket = majesticsupport::$_db->get_results($query);

        $date_openticket = array();
        $date_closeticket = array();
        $date_answeredticket = array();
        $date_overdueticket = array();
        $date_pendingticket = array();
        foreach ($openticket AS $ticket) {
            if (!isset($date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($closeticket AS $ticket) {
            if (!isset($date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($answeredticket AS $ticket) {
            if (!isset($date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($overdueticket AS $ticket) {
            if (!isset($date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($pendingticket AS $ticket) {
            if (!isset($date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        $open_ticket = 0;
        $close_ticket = 0;
        $answered_ticket = 0;
        $json_array = "";
        $open_ticket = 0;
        $close_ticket = 0;
        $answered_ticket = 0;
        $overdue_ticket = 0;
        $pending_ticket = 0;

        do{
            $year = date_i18n('Y',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = date_i18n('m',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = $month - 1; //js month are 0 based
            $day = date_i18n('d',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $openticket_tmp = isset($date_openticket[$nextdate]) ? $date_openticket[$nextdate]  : 0;
            $closeticket_tmp = isset($date_closeticket[$nextdate]) ? $date_closeticket[$nextdate] : 0;
            $answeredticket_tmp = isset($date_answeredticket[$nextdate]) ? $date_answeredticket[$nextdate] : 0;
            $overdueticket_tmp = isset($date_overdueticket[$nextdate]) ? $date_overdueticket[$nextdate] : 0;
            $pendingticket_tmp = isset($date_pendingticket[$nextdate]) ? $date_pendingticket[$nextdate] : 0;
            $json_array .= "[new Date($year,$month,$day),$openticket_tmp,$answeredticket_tmp,$pendingticket_tmp,$overdueticket_tmp,$closeticket_tmp],";
            $open_ticket += $openticket_tmp;
            $close_ticket += $closeticket_tmp;
            $answered_ticket += $answeredticket_tmp;
            $overdue_ticket += $overdueticket_tmp;
            $pending_ticket += $pendingticket_tmp;
            if($nextdate == $curdate){
                break;
            }
                $nextdate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($nextdate . " -1 days"));
        }while($nextdate != $curdate);

        majesticsupport::$_data['ticket_total']['allticket'] = $allticket;
        majesticsupport::$_data['ticket_total']['openticket'] = $open_ticket;
        majesticsupport::$_data['ticket_total']['closeticket'] = $close_ticket;
        majesticsupport::$_data['ticket_total']['answeredticket'] = $answered_ticket;
        majesticsupport::$_data['ticket_total']['overdueticket'] = $overdue_ticket;
        majesticsupport::$_data['ticket_total']['pendingticket'] = $pending_ticket;

        majesticsupport::$_data['line_chart_json_array'] = $json_array;

        // Pagination
        $query = "SELECT count(staff.id)
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_staff` AS staff
                    JOIN `".majesticsupport::$_wpprefixforuser."mjtc_support_users` AS user ON user.id = staff.uid";
        if($uid) $query .= ' WHERE staff.uid = '.esc_sql($uid);
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data['total'] = $total;
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total , 'staffreports');

        $query = "SELECT staff.photo,staff.id,staff.firstname,staff.lastname,staff.username,staff.email,user.display_name,user.user_email,user.user_nicename,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND staffid = staff.id) AS openticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND staffid = staff.id) AS closeticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND staffid = staff.id) AS answeredticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND staffid = staff.id) AS overdueticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND staffid = staff.id) AS pendingticket  ";
                    if(in_array('feedback', majesticsupport::$_active_addons)){
                        $query .=    ",(SELECT AVG(feed.rating) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_feedbacks` AS feed JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket ON ticket.id= feed.ticketid WHERE date(feed.created) >= '" . esc_sql($curdate) . "' AND date(feed.created) <= '" . esc_sql($fromdate) . "' AND ticket.staffid = staff.id) AS avragerating ";
                    }
                    $query .=  "FROM `".majesticsupport::$_db->prefix."mjtc_support_staff` AS staff
                    JOIN `".majesticsupport::$_wpprefixforuser."mjtc_support_users` AS user ON user.id = staff.uid";
        if($uid) $query .= ' WHERE staff.uid = '.esc_sql($uid);
        $query .= " LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        $agents = majesticsupport::$_db->get_results($query);
        if(in_array('timetracking', majesticsupport::$_active_addons)){
            foreach ($agents as $agent) {
                $agent->time = MJTC_includer::MJTC_getModel('timetracking')->getAverageTimeByStaffId($agent->id);// time 0 contains avergage time in seconds and 1 contains wheter it is conflicted or not
            }
        }
        majesticsupport::$_data['staffs_report'] = $agents;
        return;
    }

    function getDepartmentReports(){
        $date_start = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['date_start'] : '';
        $date_end = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['date_end'] : '';
        if(isset($date_start) && $date_start != ""){
            $date_start = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start));
        }
        if(isset($date_end) && $date_end != ""){
            $date_end = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end));
        }
        if($date_start > $date_end){
            $tmp = $date_start;
            $date_start = $date_end;
            $date_end = $tmp;
        }

        //Line Chart Data
        $curdate = ($date_start != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start)) : date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));
        $dates = '';
        $fromdate = ($date_end != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end)) : date_i18n('Y-m-d');
        majesticsupport::$_data['filter']['date_start'] = $curdate;
        majesticsupport::$_data['filter']['date_end'] = $fromdate;
        // forexport
        $_SESSION['forexport']['curdate'] = $curdate;
        $_SESSION['forexport']['fromdate'] = $fromdate;

        $nextdate = $fromdate;
        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        $openticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        $closeticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        $answeredticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        $overdueticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        $pendingticket = majesticsupport::$_db->get_results($query);

        $date_openticket = array();
        $date_closeticket = array();
        $date_answeredticket = array();
        $date_overdueticket = array();
        $date_pendingticket = array();
        foreach ($openticket AS $ticket) {
            if (!isset($date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($closeticket AS $ticket) {
            if (!isset($date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($answeredticket AS $ticket) {
            if (!isset($date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($overdueticket AS $ticket) {
            if (!isset($date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($pendingticket AS $ticket) {
            if (!isset($date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        $open_ticket = 0;
        $close_ticket = 0;
        $answered_ticket = 0;
        $json_array = "";
        $open_ticket = 0;
        $close_ticket = 0;
        $answered_ticket = 0;
        $overdue_ticket = 0;
        $pending_ticket = 0;

        do{
            $year = date_i18n('Y',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = date_i18n('m',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = $month - 1; //js month are 0 based
            $day = date_i18n('d',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $openticket_tmp = isset($date_openticket[$nextdate]) ? $date_openticket[$nextdate]  : 0;
            $closeticket_tmp = isset($date_closeticket[$nextdate]) ? $date_closeticket[$nextdate] : 0;
            $answeredticket_tmp = isset($date_answeredticket[$nextdate]) ? $date_answeredticket[$nextdate] : 0;
            $overdueticket_tmp = isset($date_overdueticket[$nextdate]) ? $date_overdueticket[$nextdate] : 0;
            $pendingticket_tmp = isset($date_pendingticket[$nextdate]) ? $date_pendingticket[$nextdate] : 0;
            $json_array .= "[new Date($year,$month,$day),$openticket_tmp,$answeredticket_tmp,$pendingticket_tmp,$overdueticket_tmp,$closeticket_tmp],";
            $open_ticket += $openticket_tmp;
            $close_ticket += $closeticket_tmp;
            $answered_ticket += $answeredticket_tmp;
            $overdue_ticket += $overdueticket_tmp;
            $pending_ticket += $pendingticket_tmp;
             if($nextdate == $curdate){
                break;
            }
            $nextdate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($nextdate . " -1 days"));
        }while($nextdate != $curdate);

        majesticsupport::$_data['ticket_total']['openticket'] = $open_ticket;
        majesticsupport::$_data['ticket_total']['closeticket'] = $close_ticket;
        majesticsupport::$_data['ticket_total']['answeredticket'] = $answered_ticket;
        majesticsupport::$_data['ticket_total']['overdueticket'] = $overdue_ticket;
        majesticsupport::$_data['ticket_total']['pendingticket'] = $pending_ticket;

        majesticsupport::$_data['line_chart_json_array'] = $json_array;

        // Pagination
        $query = "SELECT count(department.id)
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_departments` AS department
                    JOIN `".majesticsupport::$_db->prefix."mjtc_support_email` AS email ON department.emailid = email.id";
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data['total'] = $total;
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total);

        $query = "SELECT department.id,department.departmentname,email.email,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE departmentid = department.id) AS allticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND departmentid = department.id) AS openticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND departmentid = department.id) AS closeticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND departmentid = department.id) AS answeredticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND departmentid = department.id) AS overdueticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND departmentid = department.id) AS pendingticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_departments` AS department
                    JOIN `".majesticsupport::$_db->prefix."mjtc_support_email` AS email ON department.emailid = email.id";
        $query .= " LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        $depatments = majesticsupport::$_db->get_results($query);
        majesticsupport::$_data['depatments_report'] =$depatments;
        return;
    }

    function getStaffReportsFE(){
        if( !in_array('agent',majesticsupport::$_active_addons) ){
            return;
        }
        $date_start = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['ms-date-start'] : '';
        $date_end = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['ms-date-end'] : '';
        if(isset($date_start) && $date_start != ""){
            $date_start = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start));
        }
        if(isset($date_end) && $date_end != ""){
            $date_end = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end));
        }

        if($date_start > $date_end){
            $tmp = $date_start;
            $date_start = $date_end;
            $date_end = $tmp;
        }

        $uid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();

        //Line Chart Data
        $curdate = ($date_start != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start)) : date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));
        $dates = '';
        $fromdate = ($date_end != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end)) : date_i18n('Y-m-d');
        majesticsupport::$_data['filter']['ms-date-start'] = $curdate;
        majesticsupport::$_data['filter']['ms-date-end'] = $fromdate;

        $staffid = MJTC_includer::MJTC_getModel('agent')->getStaffId($uid);

        majesticsupport::$_data['filter']['staffname'] = MJTC_includer::MJTC_getModel('agent')->getMyName($staffid);
        $nextdate = $fromdate;
        // find my depats
        $query = "SELECT dept.departmentid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` AS dept WHERE dept.staffid = " . esc_sql($staffid);
        $data = majesticsupport::$_db->get_results($query);
        $my_depts = '';
        foreach ($data as $key => $value) {
            if($my_depts)
                $my_depts .= ',';
            $my_depts .= $value->departmentid;
        }
        // get mytickets, or all tickets with my depatments
        if($my_depts)
            $dep_query = " AND (ticket.staffid = ".esc_sql($staffid)." OR ticket.departmentid IN (".esc_sql($my_depts).")) ";
        else
            $dep_query = " AND ( ticket.staffid = ".esc_sql($staffid)." ) ";
        //Query to get Data
        $query = "SELECT ticket.created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket WHERE ticket.status = 0 AND (ticket.lastreply = '0000-00-00 00:00:00') AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "'";
        $query .= $dep_query;
        $openticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT ticket.created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket WHERE ticket.status = 4 AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "'";
        $query .= $dep_query;
        $closeticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT ticket.created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket WHERE ticket.isanswered = 1 AND ticket.status != 4 AND ticket.status != 0 AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "'";
        $query .= $dep_query;
        $answeredticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT ticket.created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket WHERE ticket.isoverdue = 1 AND ticket.status != 4 AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "'";
        $query .= $dep_query;
        $overdueticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT ticket.created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket WHERE ticket.isanswered != 1 AND ticket.status != 4 AND (ticket.lastreply != '0000-00-00 00:00:00') AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "'";
        $query .= $dep_query;
        $pendingticket = majesticsupport::$_db->get_results($query);

        $date_openticket = array();
        $date_closeticket = array();
        $date_answeredticket = array();
        $date_overdueticket = array();
        $date_pendingticket = array();
        foreach ($openticket AS $ticket) {
            if (!isset($date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($closeticket AS $ticket) {
            if (!isset($date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($answeredticket AS $ticket) {
            if (!isset($date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($overdueticket AS $ticket) {
            if (!isset($date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($pendingticket AS $ticket) {
            if (!isset($date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        $open_ticket = 0;
        $close_ticket = 0;
        $answered_ticket = 0;
        $json_array = "";
        $open_ticket = 0;
        $close_ticket = 0;
        $answered_ticket = 0;
        $overdue_ticket = 0;
        $pending_ticket = 0;

        do{
            $year = date_i18n('Y',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = date_i18n('m',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = $month - 1; //js month are 0 based
            $day = date_i18n('d',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $openticket_tmp = isset($date_openticket[$nextdate]) ? $date_openticket[$nextdate]  : 0;
            $closeticket_tmp = isset($date_closeticket[$nextdate]) ? $date_closeticket[$nextdate] : 0;
            $answeredticket_tmp = isset($date_answeredticket[$nextdate]) ? $date_answeredticket[$nextdate] : 0;
            $overdueticket_tmp = isset($date_overdueticket[$nextdate]) ? $date_overdueticket[$nextdate] : 0;
            $pendingticket_tmp = isset($date_pendingticket[$nextdate]) ? $date_pendingticket[$nextdate] : 0;
            $json_array .= "[new Date($year,$month,$day),$openticket_tmp,$answeredticket_tmp,$pendingticket_tmp,$overdueticket_tmp,$closeticket_tmp],";
            $open_ticket += $openticket_tmp;
            $close_ticket += $closeticket_tmp;
            $answered_ticket += $answeredticket_tmp;
            $overdue_ticket += $overdueticket_tmp;
            $pending_ticket += $pendingticket_tmp;
             if($nextdate == $curdate){
                break;
            }
            $nextdate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($nextdate . " -1 days"));
        }while($nextdate != $curdate);

        majesticsupport::$_data['ticket_total']['openticket'] = $open_ticket;
        majesticsupport::$_data['ticket_total']['closeticket'] = $close_ticket;
        majesticsupport::$_data['ticket_total']['answeredticket'] = $answered_ticket;
        majesticsupport::$_data['ticket_total']['overdueticket'] = $overdue_ticket;
        majesticsupport::$_data['ticket_total']['pendingticket'] = $pending_ticket;

        majesticsupport::$_data['line_chart_json_array'] = $json_array;

        // Pagination staffs listing
        $query = "SELECT COUNT(DISTINCT staff.id)
            FROM `".majesticsupport::$_db->prefix."mjtc_support_staff` AS staff
            JOIN `".majesticsupport::$_wpprefixforuser."mjtc_support_users` AS user ON user.id = staff.uid
            LEFT JOIN `".majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` AS dep ON dep.staffid = staff.id ";
        $query .= " WHERE (staff.id = ".esc_sql($staffid)." OR dep.departmentid IN (SELECT dept.departmentid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` AS dept WHERE dept.staffid = ".esc_sql($staffid)."))";
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data['total'] = $total;
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total);
        // data
        $query = "SELECT DISTINCT staff.photo,staff.id,staff.firstname,staff.lastname,staff.username,staff.email,user.display_name,user.user_email,user.user_nicename,
            (SELECT COUNT(ticket.id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket WHERE ticket.status = 0 AND (ticket.lastreply = '0000-00-00 00:00:00') AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "' AND ticket.staffid = staff.id) AS openticket,
            (SELECT COUNT(ticket.id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket WHERE ticket.status = 4 AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "' AND ticket.staffid = staff.id) AS closeticket,
            (SELECT COUNT(ticket.id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket WHERE ticket.isanswered = 1 AND ticket.status != 4 AND ticket.status != 0 AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "' AND ticket.staffid = staff.id) AS answeredticket,
            (SELECT COUNT(ticket.id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket WHERE ticket.isoverdue = 1 AND ticket.status != 4 AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "' AND ticket.staffid = staff.id) AS overdueticket,
            (SELECT COUNT(ticket.id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket WHERE ticket.isanswered != 1 AND ticket.status != 4 AND (ticket.lastreply != '0000-00-00 00:00:00') AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "' AND ticket.staffid = staff.id) AS pendingticket
            FROM `".majesticsupport::$_db->prefix."mjtc_support_staff` AS staff
            JOIN `".majesticsupport::$_wpprefixforuser."mjtc_support_users` AS user ON user.id = staff.uid
            LEFT JOIN `".majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` AS dep ON dep.staffid = staff.id";
        $query .= " WHERE (staff.id = ".esc_sql($staffid)." OR dep.departmentid IN (SELECT dept.departmentid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` AS dept WHERE dept.staffid = ".esc_sql($staffid)."))";
        $query .= " LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        $agents = majesticsupport::$_db->get_results($query);
        majesticsupport::$_data['staffs_report'] = $agents;
        return;
    }

    function isValidStaffid($staffid){
        if( !in_array('agent',majesticsupport::$_active_addons) ){
            return false;
        }

        if( ! is_numeric($staffid))
            return false;
        $uid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
        $id = MJTC_includer::MJTC_getModel('agent')->getStaffId($uid);
        if( $id == $staffid )
            return true;
        $query = "SELECT staff.id AS staffid
            FROM `".majesticsupport::$_db->prefix."mjtc_support_staff` AS staff
            JOIN `".majesticsupport::$_wpprefixforuser."mjtc_support_users` AS user ON user.id = staff.uid
            JOIN `".majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` AS dep ON dep.staffid = staff.id ";
        $query .= " WHERE (dep.departmentid IN (SELECT dept.departmentid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` AS dept WHERE dept.staffid = ".esc_sql($id)."))";
        $result = majesticsupport::$_db->get_results($query);
        foreach ($result as $agent) {
            if($agent->staffid == $staffid)
                return true;
        }
        return false;
    }

    function getUserReports(){
        $date_start = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['date_start'] : '';
        $date_end = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['date_end'] : '';
        if(isset($date_start) && $date_start != ""){
            $date_start = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start));
        }
        if(isset($date_end) && $date_end != ""){
            $date_end = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end));
        }
        if($date_start > $date_end){
            $tmp = $date_start;
            $date_start = $date_end;
            $date_end = $tmp;
        }
        $uid = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['uid'] : '';

        //Line Chart Data
        $curdate = ($date_start != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start)) : date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));
        $dates = '';
        $fromdate = ($date_end != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end)) : date_i18n('Y-m-d');
        majesticsupport::$_data['filter']['date_start'] = $curdate;
        majesticsupport::$_data['filter']['date_end'] = $fromdate;
        majesticsupport::$_data['filter']['uid'] = $uid;

        // forexport
        $_SESSION['forexport']['curdate'] = $curdate;
        $_SESSION['forexport']['fromdate'] = $fromdate;
        $_SESSION['forexport']['uid'] = $uid;

        majesticsupport::$_data['filter']['username'] = MJTC_includer::MJTC_getModel('majesticsupport')->getUserNameById($uid);
        $nextdate = $fromdate;
        //Query to get Data
        $query = "SELECT count(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` ";
        if($uid) $query .= " WHERE  uid = ".esc_sql($uid);
        $allticket = majesticsupport::$_db->get_var($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 0  AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($uid) $query .= " AND uid = ".esc_sql($uid);
        $openticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($uid) $query .= " AND uid = ".esc_sql($uid);
        $closeticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($uid) $query .= " AND uid = ".esc_sql($uid);
        $answeredticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($uid) $query .= " AND uid = ".esc_sql($uid);
        $overdueticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($uid) $query .= " AND uid = ".esc_sql($uid);
        $pendingticket = majesticsupport::$_db->get_results($query);

        $date_openticket = array();
        $date_closeticket = array();
        $date_answeredticket = array();
        $date_overdueticket = array();
        $date_pendingticket = array();
        foreach ($openticket AS $ticket) {
            if (!isset($date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($closeticket AS $ticket) {
            if (!isset($date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($answeredticket AS $ticket) {
            if (!isset($date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($overdueticket AS $ticket) {
            if (!isset($date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($pendingticket AS $ticket) {
            if (!isset($date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        $open_ticket = 0;
        $close_ticket = 0;
        $answered_ticket = 0;
        $overdue_ticket = 0;
        $pending_ticket = 0;
        $json_array = "";
        do{
            $year = date_i18n('Y',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = date_i18n('m',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = $month - 1; //js month are 0 based
            $day = date_i18n('d',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $openticket_tmp = isset($date_openticket[$nextdate]) ? $date_openticket[$nextdate]  : 0;
            $closeticket_tmp = isset($date_closeticket[$nextdate]) ? $date_closeticket[$nextdate] : 0;
            $answeredticket_tmp = isset($date_answeredticket[$nextdate]) ? $date_answeredticket[$nextdate] : 0;
            $overdueticket_tmp = isset($date_overdueticket[$nextdate]) ? $date_overdueticket[$nextdate] : 0;
            $pendingticket_tmp = isset($date_pendingticket[$nextdate]) ? $date_pendingticket[$nextdate] : 0;
            $json_array .= "[new Date($year,$month,$day),$openticket_tmp,$answeredticket_tmp,$pendingticket_tmp,$overdueticket_tmp,$closeticket_tmp],";
            $open_ticket += $openticket_tmp;
            $close_ticket += $closeticket_tmp;
            $answered_ticket += $answeredticket_tmp;
            $overdue_ticket += $overdueticket_tmp;
            $pending_ticket += $pendingticket_tmp;
             if($nextdate == $curdate){
                break;
            }
            $nextdate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($nextdate . " -1 days"));
        }while($nextdate != $curdate);

        majesticsupport::$_data['ticket_total']['allticket'] = $allticket;
        majesticsupport::$_data['ticket_total']['openticket'] = $open_ticket;
        majesticsupport::$_data['ticket_total']['closeticket'] = $close_ticket;
        majesticsupport::$_data['ticket_total']['answeredticket'] = $answered_ticket;
        majesticsupport::$_data['ticket_total']['overdueticket'] = $overdue_ticket;
        majesticsupport::$_data['ticket_total']['pendingticket'] = $pending_ticket;

        majesticsupport::$_data['line_chart_json_array'] = $json_array;

        // Pagination
        $query = "SELECT COUNT(user.id)
                    FROM `".majesticsupport::$_wpprefixforuser."mjtc_support_users` AS user
                    WHERE  ";
                    if(in_array('agent', majesticsupport::$_active_addons)){
                        $query .=" NOT EXISTS (SELECT id FROM `".majesticsupport::$_db->prefix."mjtc_support_staff` WHERE uid = user.id) AND  ";
                    }
                    $query .=" NOT EXISTS (SELECT umeta_id FROM `".majesticsupport::$_wpprefixforuser."usermeta` WHERE user_id = user.id AND meta_value LIKE '%administrator%')";
        if($uid) $query .= " AND user.id = ".esc_sql($uid);
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data['total'] = $total;
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total);

        $query = "SELECT user.display_name,user.user_email,user.user_nicename,user.id,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE uid = user.id) AS allticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND uid = user.id) AS openticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND uid = user.id) AS closeticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND uid = user.id) AS answeredticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND uid = user.id) AS overdueticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND uid = user.id) AS pendingticket
                    FROM `".majesticsupport::$_wpprefixforuser."mjtc_support_users` AS user
                    WHERE  ";
                    if(in_array('agent', majesticsupport::$_active_addons)){
                        $query .=" NOT EXISTS (SELECT id FROM `".majesticsupport::$_db->prefix."mjtc_support_staff` WHERE uid = user.id) AND  ";
                    }
                    $query .=" NOT EXISTS (SELECT umeta_id FROM `".majesticsupport::$_wpprefixforuser."usermeta` WHERE user_id = user.id AND meta_value LIKE '%administrator%')";
        if($uid) $query .= " AND user.id = ".esc_sql($uid);
        $query .= " LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        $users = majesticsupport::$_db->get_results($query);
        majesticsupport::$_data['users_report'] =$users;
        return;
    }

    function getStaffDetailReportByStaffId($id){
        if( !in_array('agent',majesticsupport::$_active_addons) ){
            return;
        }
        if(!is_numeric($id)) return false;

        if( ! is_admin()){
            $result = $this->isValidStaffid( $id );
            if( $result == false)
                return false;
        }

        $start_date = is_admin() ? 'date_start' : 'ms-date-start';
        $end_date = is_admin() ? 'date_end' : 'ms-date-end';
        if(isset($date_start) && $date_start != ""){
            $date_start = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start));
        }
        if(isset($date_end) && $date_end != ""){
            $date_end = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end));
        }
        $date_start = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report'][$start_date] : '';
        $date_end = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report'][$end_date] : '';
        if(isset($date_start) && $date_start != ""){
            $date_start = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start));
        }
        if(isset($date_end) && $date_end != ""){
            $date_end = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end));
        }
        if($date_start > $date_end){
            $tmp = $date_start;
            $date_start = $date_end;
            $date_end = $tmp;
        }

        //Line Chart Data
        $curdate = ($date_start != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start)) : date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));
        $fromdate = ($date_end != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end)) : date_i18n('Y-m-d');
        majesticsupport::$_data['filter'][$start_date] = $curdate;
        majesticsupport::$_data['filter'][$end_date] = $fromdate;
        majesticsupport::$_data['filter']['uid'] = $id;

        $nextdate = $fromdate;

        //Query to get Data
        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND staffid = ".esc_sql($id);
        $openticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND staffid = ".esc_sql($id);
        $closeticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND staffid = ".esc_sql($id);
        $answeredticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND staffid = ".esc_sql($id);
        $overdueticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND staffid = ".esc_sql($id);
        $pendingticket = majesticsupport::$_db->get_results($query);

        $date_openticket = array();
        $date_closeticket = array();
        $date_answeredticket = array();
        $date_overdueticket = array();
        $date_pendingticket = array();
        foreach ($openticket AS $ticket) {
            if (!isset($date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($closeticket AS $ticket) {
            if (!isset($date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($answeredticket AS $ticket) {
            if (!isset($date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($overdueticket AS $ticket) {
            if (!isset($date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($pendingticket AS $ticket) {
            if (!isset($date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        $open_ticket = 0;
        $close_ticket = 0;
        $answered_ticket = 0;
        $overdue_ticket = 0;
        $pending_ticket = 0;
        $json_array = "";
        do{
            $year = date_i18n('Y',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = date_i18n('m',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = $month - 1; //js month are 0 based
            $day = date_i18n('d',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $openticket_tmp = isset($date_openticket[$nextdate]) ? $date_openticket[$nextdate]  : 0;
            $closeticket_tmp = isset($date_closeticket[$nextdate]) ? $date_closeticket[$nextdate] : 0;
            $answeredticket_tmp = isset($date_answeredticket[$nextdate]) ? $date_answeredticket[$nextdate] : 0;
            $overdueticket_tmp = isset($date_overdueticket[$nextdate]) ? $date_overdueticket[$nextdate] : 0;
            $pendingticket_tmp = isset($date_pendingticket[$nextdate]) ? $date_pendingticket[$nextdate] : 0;
            $json_array .= "[new Date($year,$month,$day),$openticket_tmp,$answeredticket_tmp,$pendingticket_tmp,$overdueticket_tmp,$closeticket_tmp],";
            $open_ticket += $openticket_tmp;
            $close_ticket += $closeticket_tmp;
            $answered_ticket += $answeredticket_tmp;
            $overdue_ticket += $overdueticket_tmp;
            $pending_ticket += $pendingticket_tmp;
             if($nextdate == $curdate){
                break;
            }
            $nextdate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($nextdate . " -1 days"));
        }while($nextdate != $curdate);

        majesticsupport::$_data['line_chart_json_array'] = $json_array;

        $query = "SELECT staff.photo,staff.id,staff.firstname,staff.lastname,staff.username,staff.email,user.display_name,user.user_email,user.user_nicename,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND staffid = staff.id) AS allticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND staffid = staff.id) AS openticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND staffid = staff.id) AS closeticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND staffid = staff.id) AS answeredticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND staffid = staff.id) AS overdueticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND staffid = staff.id) AS pendingticket   ";
                    if(in_array('feedback', majesticsupport::$_active_addons)){
                        $query .=    ",(SELECT AVG(feed.rating) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_feedbacks` AS feed JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket ON ticket.id= feed.ticketid WHERE date(feed.created) >= '" . esc_sql($curdate) . "' AND date(feed.created) <= '" . esc_sql($fromdate) . "' AND ticket.staffid = staff.id) AS avragerating ";
                    }
                    $query .=  "FROM `".majesticsupport::$_db->prefix."mjtc_support_staff` AS staff
                    JOIN `".majesticsupport::$_wpprefixforuser."mjtc_support_users` AS user ON user.id = staff.uid
                    WHERE staff.id = ".esc_sql($id);

        $agent = majesticsupport::$_db->get_row($query);
        if(!empty($agent)){
            if(in_array('timetracking', majesticsupport::$_active_addons)){
                $agent->time = MJTC_includer::MJTC_getModel('timetracking')->getAverageTimeByStaffId($agent->id);// time 0 contains avergage time in seconds and 1 contains wheter it is conflicted or not
            }
        }

        majesticsupport::$_data['staff_report'] =$agent;
        // ticket ids for staff member on which he replied but are not assigned to him
        $ticketid_string = '';
        if(in_array('timetracking', majesticsupport::$_active_addons)){
            $query = "SELECT DISTINCT(ticketid) AS ticketid
                        FROM `".majesticsupport::$_db->prefix."mjtc_support_staff_time`
                        WHERE staffid = ".esc_sql($id);
            $all_tickets = majesticsupport::$_db->get_results($query);
            $comma = '';
            foreach ($all_tickets as $ticket) {
                $ticketid_string .= $comma.$ticket->ticketid;
                $comma = ', ';
            }
        }

        if($ticketid_string == ''){
            $q_strig = "(staffid = ".esc_sql($id).")";
        }else{
            $q_strig = "(staffid = ".esc_sql($id)." OR ticket.id IN (".esc_sql($ticketid_string)."))";
        }

        // Pagination
        $query = "SELECT COUNT(ticket.id)
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` AS ticket
                    LEFT JOIN `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ON priority.id = ticket.priorityid
                    WHERE ".$q_strig." AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "' ";
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data['total'] = $total;
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total,'staffdetailreport');

        //Tickets
        do_action('msFeedbackQueryStaff');
        $query = "SELECT ticket.*,priority.priority, priority.prioritycolour ".majesticsupport::$_addon_query['select']."
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` AS ticket
                    LEFT JOIN `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ON priority.id = ticket.priorityid
                    ".majesticsupport::$_addon_query['join']."
                    WHERE ".$q_strig." AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "' ";
        $query .= " LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        do_action('reset_ms_aadon_query');
        majesticsupport::$_data['staff_tickets'] = majesticsupport::$_db->get_results($query);

        if(in_array('timetracking', majesticsupport::$_active_addons)){
            foreach (majesticsupport::$_data['staff_tickets'] as $ticket) {
                 $ticket->time = MJTC_includer::MJTC_getModel('timetracking')->getTimeTakenByTicketIdAndStaffid($ticket->id,$id);// second parameter is staff id
            }
        }
        return;
    }

    function getDepartmentDetailReportByDepartmentId($id){
        if(!is_numeric($id)) return false;

        $start_date ='date_start';
        $end_date ='date_end';

        $date_start = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['date_start'] : '';
        $date_end = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['date_end'] : '';
        if(isset($date_start) && $date_start != ""){
            $date_start = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start));
        }
        if(isset($date_end) && $date_end != ""){
            $date_end = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end));
        }
        if($date_start > $date_end){
            $tmp = $date_start;
            $date_start = $date_end;
            $date_end = $tmp;
        }

        //Line Chart Data
        $curdate = ($date_start != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start)) : date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));
        $fromdate = ($date_end != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end)) : date_i18n('Y-m-d');
        majesticsupport::$_data['filter'][$start_date] = $curdate;
        majesticsupport::$_data['filter'][$end_date] = $fromdate;
        majesticsupport::$_data['filter']['id'] = $id;

        $nextdate = $fromdate;

        //Query to get Data
        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND departmentid = ".esc_sql($id);
        $openticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND departmentid = ".esc_sql($id);
        $closeticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND departmentid = ".esc_sql($id);
        $answeredticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND departmentid = ".esc_sql($id);
        $overdueticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND departmentid = ".esc_sql($id);
        $pendingticket = majesticsupport::$_db->get_results($query);

        $date_openticket = array();
        $date_closeticket = array();
        $date_answeredticket = array();
        $date_overdueticket = array();
        $date_pendingticket = array();
        foreach ($openticket AS $ticket) {
            if (!isset($date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($closeticket AS $ticket) {
            if (!isset($date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($answeredticket AS $ticket) {
            if (!isset($date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($overdueticket AS $ticket) {
            if (!isset($date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($pendingticket AS $ticket) {
            if (!isset($date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        $open_ticket = 0;
        $close_ticket = 0;
        $answered_ticket = 0;
        $overdue_ticket = 0;
        $pending_ticket = 0;
        $json_array = "";
        do{
            $year = date_i18n('Y',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = date_i18n('m',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = $month - 1; //js month are 0 based
            $day = date_i18n('d',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $openticket_tmp = isset($date_openticket[$nextdate]) ? $date_openticket[$nextdate]  : 0;
            $closeticket_tmp = isset($date_closeticket[$nextdate]) ? $date_closeticket[$nextdate] : 0;
            $answeredticket_tmp = isset($date_answeredticket[$nextdate]) ? $date_answeredticket[$nextdate] : 0;
            $overdueticket_tmp = isset($date_overdueticket[$nextdate]) ? $date_overdueticket[$nextdate] : 0;
            $pendingticket_tmp = isset($date_pendingticket[$nextdate]) ? $date_pendingticket[$nextdate] : 0;
            $json_array .= "[new Date($year,$month,$day),$openticket_tmp,$answeredticket_tmp,$pendingticket_tmp,$overdueticket_tmp,$closeticket_tmp],";
            $open_ticket += $openticket_tmp;
            $close_ticket += $closeticket_tmp;
            $answered_ticket += $answeredticket_tmp;
            $overdue_ticket += $overdueticket_tmp;
            $pending_ticket += $pendingticket_tmp;
             if($nextdate == $curdate){
                break;
            }
            $nextdate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($nextdate . " -1 days"));
        }while($nextdate != $curdate);

        majesticsupport::$_data['line_chart_json_array'] = $json_array;


        // Pagination
        $query = "SELECT count(ticket.id)
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` AS ticket
                    JOIN `".majesticsupport::$_db->prefix."mjtc_support_departments` AS department ON department.id = ticket.departmentid WHERE department.id = ".esc_sql($id)." AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "' ";
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data['total'] = $total;
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total);

        $query = "SELECT department.id,department.departmentname,email.email,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE departmentid = department.id) AS allticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND departmentid = department.id) AS openticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND departmentid = department.id) AS closeticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND departmentid = department.id) AS answeredticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND departmentid = department.id) AS overdueticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND departmentid = department.id) AS pendingticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_departments` AS department
                    JOIN `".majesticsupport::$_db->prefix."mjtc_support_email` AS email ON department.emailid = email.id
                    WHERE department.id = ".esc_sql($id);
        $depatments = majesticsupport::$_db->get_row($query);
        majesticsupport::$_data['depatments_report'] =$depatments;

        //Tickets
        do_action('msFeedbackQueryStaff');
        $query = "SELECT ticket.*,priority.priority, priority.prioritycolour ".majesticsupport::$_addon_query['select']."
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` AS ticket
                    LEFT JOIN `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ON priority.id = ticket.priorityid
                    ".majesticsupport::$_addon_query['join']."
                    WHERE departmentid = ".esc_sql($id)." AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "' ";
        $query .= " LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        do_action('reset_ms_aadon_query');

        majesticsupport::$_data['department_tickets'] = majesticsupport::$_db->get_results($query);
        if(in_array('timetracking', majesticsupport::$_active_addons)){
            foreach (majesticsupport::$_data['department_tickets'] as $ticket) {
                 $ticket->time = MJTC_includer::MJTC_getModel('timetracking')->getTimeTakenByTicketId($ticket->id);
            }
        }
    }


    function getStaffDetailReportByUserId($id){
        if(!is_numeric($id)) return false;

        $date_start = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['date_start'] : '';
        $date_end = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report']['date_end'] : '';
        if(isset($date_start) && $date_start != ""){
            $date_start = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start));
        }
        if(isset($date_end) && $date_end != ""){
            $date_end = date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end));
        }
        if($date_start > $date_end){
            $tmp = $date_start;
            $date_start = $date_end;
            $date_end = $tmp;
        }
        //Line Chart Data
        $curdate = ($date_start != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start)) : date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));
        $fromdate = ($date_end != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end)) : date_i18n('Y-m-d');
        majesticsupport::$_data['filter']['date_start'] = $curdate;
        majesticsupport::$_data['filter']['date_end'] = $fromdate;
        majesticsupport::$_data['filter']['uid'] = $id;
        $nextdate = $fromdate;

        // forexport
        $_SESSION['forexport']['curdate'] = $curdate;
        $_SESSION['forexport']['fromdate'] = $fromdate;
        $_SESSION['forexport']['id'] = $id;


        //Query to get Data
        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND uid = ".esc_sql($id);
        $openticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND uid = ".esc_sql($id);
        $closeticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND uid = ".esc_sql($id);
        $answeredticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND uid = ".esc_sql($id);
        $overdueticket = majesticsupport::$_db->get_results($query);

        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "'";
        if($id) $query .= " AND uid = ".esc_sql($id);
        $pendingticket = majesticsupport::$_db->get_results($query);

        $date_openticket = array();
        $date_closeticket = array();
        $date_answeredticket = array();
        $date_overdueticket = array();
        $date_pendingticket = array();
        foreach ($openticket AS $ticket) {
            if (!isset($date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($closeticket AS $ticket) {
            if (!isset($date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_closeticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($answeredticket AS $ticket) {
            if (!isset($date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_answeredticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($overdueticket AS $ticket) {
            if (!isset($date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_overdueticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        foreach ($pendingticket AS $ticket) {
            if (!isset($date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_pendingticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + 1;
        }
        $open_ticket = 0;
        $close_ticket = 0;
        $answered_ticket = 0;
        $overdue_ticket = 0;
        $pending_ticket = 0;
        $json_array = "";
        do{
            $year = date_i18n('Y',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = date_i18n('m',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = $month - 1; //js month are 0 based
            $day = date_i18n('d',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $openticket_tmp = isset($date_openticket[$nextdate]) ? $date_openticket[$nextdate]  : 0;
            $closeticket_tmp = isset($date_closeticket[$nextdate]) ? $date_closeticket[$nextdate] : 0;
            $answeredticket_tmp = isset($date_answeredticket[$nextdate]) ? $date_answeredticket[$nextdate] : 0;
            $overdueticket_tmp = isset($date_overdueticket[$nextdate]) ? $date_overdueticket[$nextdate] : 0;
            $pendingticket_tmp = isset($date_pendingticket[$nextdate]) ? $date_pendingticket[$nextdate] : 0;
            $json_array .= "[new Date($year,$month,$day),$openticket_tmp,$answeredticket_tmp,$pendingticket_tmp,$overdueticket_tmp,$closeticket_tmp],";
            $open_ticket += $openticket_tmp;
            $close_ticket += $closeticket_tmp;
            $answered_ticket += $answeredticket_tmp;
            $overdue_ticket += $overdueticket_tmp;
            $pending_ticket += $pendingticket_tmp;
             if($nextdate == $curdate){
                break;
            }
            $nextdate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($nextdate . " -1 days"));
        }while($nextdate != $curdate);

        majesticsupport::$_data['line_chart_json_array'] = $json_array;

        $query = "SELECT user.display_name,user.user_email,user.user_nicename,user.id,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE uid = user.id) AS allticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 0  AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND uid = user.id) AS openticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND uid = user.id) AS closeticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND uid = user.id) AS answeredticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isoverdue = 1 AND status != 4 AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND uid = user.id) AS overdueticket,
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered != 1 AND status != 4 AND isoverdue = 1 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '" . esc_sql($curdate) . "' AND date(created) <= '" . esc_sql($fromdate) . "' AND uid = user.id) AS pendingticket
                    FROM `".majesticsupport::$_wpprefixforuser."mjtc_support_users` AS user
                    WHERE user.id = ".esc_sql($id);
        $agent = majesticsupport::$_db->get_row($query);
        majesticsupport::$_data['user_report'] =$agent;
        // Pagination
        $query = "SELECT COUNT(ticket.id)
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` AS ticket
                    LEFT JOIN `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ON priority.id = ticket.priorityid
                    WHERE uid = ".esc_sql($id)." AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "' ";
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data['total'] = $total;
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total);
        //Tickets
        do_action('msFeedbackQueryStaff');
        $query = "SELECT ticket.*,priority.priority, priority.prioritycolour ".majesticsupport::$_addon_query['select']."
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` AS ticket
                    LEFT JOIN `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ON priority.id = ticket.priorityid
                    ".majesticsupport::$_addon_query['join']."
                    WHERE uid = ".esc_sql($id)." AND date(ticket.created) >= '" . esc_sql($curdate) . "' AND date(ticket.created) <= '" . esc_sql($fromdate) . "' ";
        $query .= " LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        do_action('reset_ms_aadon_query');
        majesticsupport::$_data['user_tickets'] = majesticsupport::$_db->get_results($query);
        if(in_array('timetracking', majesticsupport::$_active_addons)){
            foreach (majesticsupport::$_data['user_tickets'] as $ticket) {
                 $ticket->time = MJTC_includer::MJTC_getModel('timetracking')->getTimeTakenByTicketId($ticket->id);
            }
        }
        return;
    }

    function getStaffTimingReportById($id){
        if( !in_array('agent',majesticsupport::$_active_addons) ){
            return;
        }
        if(!is_numeric($id)) return false;

        $start_date ='date_start';
        $end_date ='date_end';

        $date_start = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report'][$start_date] : '';
        $date_end = isset(majesticsupport::$_search['report']) ? majesticsupport::$_search['report'][$end_date] : '';
        if($date_start > $date_end){
            $tmp = $date_start;
            $date_start = $date_end;
            $date_end = $tmp;
        }

        //Line Chart Data
        $curdate = ($date_start != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_start)) : date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));
        $fromdate = ($date_end != null) ? date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($date_end)) : date_i18n('Y-m-d');
        majesticsupport::$_data['filter'][$start_date] = $curdate;
        majesticsupport::$_data['filter'][$end_date] = $fromdate;
        majesticsupport::$_data['filter']['id'] = $id;

        $nextdate = $fromdate;

        //Query to get Data
        if(in_array('timetracking', majesticsupport::$_active_addons)){
            $query = "SELECT created,usertime FROM `" . majesticsupport::$_db->prefix . "mjtc_support_staff_time` ";
            $query .= " WHERE staffid = ".esc_sql($id);
            $openticket = majesticsupport::$_db->get_results($query);
        }else{
            $openticket = array();
        }

        $date_openticket = array();
        foreach ($openticket AS $ticket) {
            if (!isset($date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))]))
                $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = 0;
            $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] = $date_openticket[date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))] + $ticket->usertime;
        }
        $open_ticket = 0;
        $json_array = "";
        do{
            $year = date_i18n('Y',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = date_i18n('m',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            $month = $month - 1; //js month are 0 based
            $day = date_i18n('d',MJTC_majesticsupportphplib::MJTC_strtotime($nextdate));
            if(isset($date_openticket[$nextdate])){

                $mins = floor($date_openticket[$nextdate] / 60);
                $openticket_tmp =  $mins;
            }else{
                $openticket_tmp =  0;
            }
            $json_array .= '[new Date('.$year.','.$month.','.$day.'),'.$openticket_tmp.'],';
            $nextdate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime($nextdate . " -1 days"));
        }while($nextdate != $curdate);
        majesticsupport::$_data['line_chart_json_array'] = $json_array;
        majesticsupport::$_data[0]['staffname'] = MJTC_includer::MJTC_getModel('agent')->getMyName($id);

    }


}
?>
