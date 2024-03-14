<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_majesticsupportModel {

    function getControlPanelData() {

        //determine user
        $user_is = 'unknown';
        if(MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()){
            $user_is = 'visitor';
        }else{
            if(in_array('agent', majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()){
                $user_is = 'agent';
            }else{
                $user_is = 'user';
            }
        }
        //check if any addon is installed
        $addon_are_installed = !empty(majesticsupport::$_active_addons) ? true : false;

        if( $user_is == 'agent' ){

            $uid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
            $staffid = MJTC_includer::MJTC_getModel('agent')->getStaffId($uid);

            $tickets = $this->getAgentLatestTicketsForCp($staffid);
            if($tickets){
                majesticsupport::$_data[0]['agent-tickets'] = $tickets;
            }

            $ticketStats = $this->getAgentTicketStats($staffid);
            if($ticketStats){
                majesticsupport::$_data[0]['count'] = $ticketStats;
            }

            //data for graph
            $this->getAgentCpChartData($staffid);

        }

        if( $user_is == 'user' ){
            $uid = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();

            $tickets = $this->getUserLatestTicketsForCp($uid);
            if($tickets){
                majesticsupport::$_data[0]['user-tickets'] = $tickets;
            }

            $ticketStats = $this->getUserTicketStats($uid);

            if($ticketStats){
                majesticsupport::$_data[0]['count'] = $ticketStats;
            }
        }

        if( $addon_are_installed ){

            $downloads = $this->getLatestDownloadsForCp();
            if($downloads){
                majesticsupport::$_data[0]['latest-downloads'] = $downloads;
            }

            $announcements = $this->getLatestAnnouncementsForCp();
            if($announcements){
                majesticsupport::$_data[0]['latest-announcements'] = $announcements;
            }

            $articles = $this->getLatestArticlesForCp();
            if($articles){
                majesticsupport::$_data[0]['latest-articles'] = $articles;
            }

            $faqs = $this->getLatestFaqsForCp();
            if($faqs){
                majesticsupport::$_data[0]['latest-faqs'] = $faqs;
            }
        }
    }

    function getControlPanelDataAdmin(){
        $curdate = date_i18n('Y-m-d');
        $fromdate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));

        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id AND status = 0 AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '".esc_sql($fromdate)."' AND date(created) <= '".esc_sql($curdate)."' ) AS totalticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ORDER BY priority.priority";
        $openticket_pr = majesticsupport::$_db->get_results($query);

        $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets`";
        $allticket_pr = majesticsupport::$_db->get_var($query);

        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id AND isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '".esc_sql($fromdate)."' AND date(created) <= '".esc_sql($curdate)."') AS totalticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ORDER BY priority.priority";
        $answeredticket_pr = majesticsupport::$_db->get_results($query);
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id AND isoverdue = 1 AND status != 4 AND date(created) >= '".esc_sql($fromdate)."' AND date(created) <= '".esc_sql($curdate)."') AS totalticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ORDER BY priority.priority";
        $overdueticket_pr = majesticsupport::$_db->get_results($query);
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id  AND isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '".esc_sql($fromdate)."' AND date(created) <= '".esc_sql($curdate)."') AS totalticket
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

        //To show priority colors on chart
        $query = "SELECT prioritycolour FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` ORDER BY priority ";
        $jsonColorList = "[";
        foreach(majesticsupport::$_db->get_results($query) as $priority){
            $jsonColorList.= "'".$priority->prioritycolour."',";
        }
        $jsonColorList .= "]";
        majesticsupport::$_data['stack_chart_horizontal']['colors'] = $jsonColorList;
        //end priority colors

        majesticsupport::$_data['ticket_total']['allticket'] = $allticket_pr;
        majesticsupport::$_data['ticket_total']['openticket'] = 0;
        majesticsupport::$_data['ticket_total']['overdueticket'] = 0;
        majesticsupport::$_data['ticket_total']['pendingticket'] = 0;
        majesticsupport::$_data['ticket_total']['answeredticket'] = 0;

        $count = count($openticket_pr);
        for($i = 0;$i < $count; $i++){
            majesticsupport::$_data['ticket_total']['openticket'] += $openticket_pr[$i]->totalticket;
            majesticsupport::$_data['ticket_total']['overdueticket'] += $overdueticket_pr[$i]->totalticket;
            majesticsupport::$_data['ticket_total']['pendingticket'] += $pendingticket_pr[$i]->totalticket;
            majesticsupport::$_data['ticket_total']['answeredticket'] += $answeredticket_pr[$i]->totalticket;
        }

        do_action('ms_staff_admin_cp_query');

        $query = "SELECT ticket.id,ticket.ticketid,ticket.subject,ticket.name,ticket.created,priority.priority,priority.prioritycolour,ticket.status,department.departmentname,ticket.uid".majesticsupport::$_addon_query['select']."
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON priority.id = ticket.priorityid
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
                    ".majesticsupport::$_addon_query['join']."
                    ORDER BY ticket.status ASC, ticket.created DESC LIMIT 0, 10";
        majesticsupport::$_data['tickets'] = majesticsupport::$_db->get_results($query);
        // smart reply
        $query = "SELECT smartreply.title,smartreply.usedby
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_smartreplies` AS smartreply
                    ORDER BY smartreply.usedby DESC LIMIT 0, 5";
        majesticsupport::$_data['smartreply'] = majesticsupport::$_db->get_results($query);
        // agents
        if(in_array('agent', majesticsupport::$_active_addons)){
            $query = "SELECT CONCAT(staff.firstname ,'  ' ,staff.lastname) AS staffname,staff.id AS staffid, staff.photo AS staffphoto, (SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` AS ticket WHERE ticket.staffid = staff.id and ticket.status != 4 and ticket.status != 5) AS totalticket
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS staff WHERE staff.status = 1 ORDER BY totalticket DESC LIMIT 0, 5";
            majesticsupport::$_data['agents'] = majesticsupport::$_db->get_results($query);
        }
        // tickets by priority
        $query = "SELECT priority.priority, priority.prioritycolour, (SELECT count(ticket.id) AS usedby
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                    WHERE ticket.priorityid = priority.id GROUP BY priorityid) AS usedby
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority
                    ORDER BY usedby DESC LIMIT 0, 5";
        majesticsupport::$_data['tickets_by_priority'] = majesticsupport::$_db->get_results($query);
        // tickets by departments
        $query = "SELECT dept.departmentname, (SELECT count(ticket.id) AS usedby
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                    WHERE ticket.departmentid = dept.id GROUP BY departmentid) AS usedby
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS dept
                    ORDER BY usedby DESC LIMIT 0, 5";
        majesticsupport::$_data['tickets_by_department'] = majesticsupport::$_db->get_results($query);

        majesticsupport::$_data['version'] = majesticsupport::$_config['versioncode'];
        //today tickets for chart
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id AND date(created) = '".esc_sql($curdate)."')  AS totalticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ORDER BY priority.priority";
        $priorities = majesticsupport::$_db->get_results($query);
        majesticsupport::$_data['today_ticket_chart']['title'] = "['".esc_html(__('Priority','majestic-support'))."',";
        majesticsupport::$_data['today_ticket_chart']['data'] = "['',";
        foreach($priorities AS $pr){
            majesticsupport::$_data['today_ticket_chart']['title'] .= "'".majesticsupport::MJTC_getVarValue($pr->priority)."',";
            majesticsupport::$_data['today_ticket_chart']['data'] .= $pr->totalticket.",";
        }
        majesticsupport::$_data['today_ticket_chart']['title'] .= "]";
        majesticsupport::$_data['today_ticket_chart']['data'] .= "]";

        //Ticket Hisotry
        if(in_array('tickethistory', majesticsupport::$_active_addons)){
            $query = "SELECT al.id,al.message,al.datetime,al.uid,al.eventtype,pr.priority,pr.prioritycolour,dp.departmentname
            FROM `" . majesticsupport::$_db->prefix . "mjtc_support_activity_log`  AS al
            JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS tic ON al.referenceid=tic.id
            LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS pr ON pr.id = tic.priorityid
            LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS dp ON dp.id = tic.departmentid
            WHERE al.eventfor=1 ORDER BY al.datetime DESC LIMIT 5 ";
            majesticsupport::$_data['tickethistory'] = majesticsupport::$_db->get_results($query);
        }

        // update available alert
        majesticsupport::$_data['update_avaliable_for_addons'] = $this->showUpdateAvaliableAlert();
    }

    function getAgentLatestTicketsForCp($staffid){
        if(!is_numeric($staffid)){
            return false;
        }

        $allowed = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('All Tickets');
        if($allowed == true){
            $agent_conditions = "1 = 1";
        }else{
            $agent_conditions = "ticket.staffid = ".esc_sql($staffid)." OR ticket.departmentid IN (SELECT dept.departmentid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` AS dept WHERE dept.staffid = ".esc_sql($staffid).")";
        }

        //latest tickets
        $query = "SELECT DISTINCT ticket.*,department.departmentname AS departmentname ,priority.priority AS priority,
        priority.prioritycolour AS prioritycolour,staff.photo AS staffphoto,staff.id AS staffid,
        assignstaff.firstname AS staffname
        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS staff ON staff.uid = ticket.uid
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS assignstaff ON ticket.staffid = assignstaff.id
        WHERE (".$agent_conditions.") ORDER BY ticket.created DESC LIMIT 3 ";
        $tickets = majesticsupport::$_db->get_results($query);
        return $tickets;
    }

    function getAgentTicketStats($staffid){
        if(!is_numeric($staffid) || majesticsupport::$_config['count_on_myticket'] != 1){
            return false;
        }

        $result = array();

        $allowed = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('All Tickets');
        if($allowed == true){
            $agent_conditions = "1 = 1";
        }else{
            $agent_conditions = "ticket.staffid = " . esc_sql($staffid) . " OR ticket.departmentid IN (SELECT dept.departmentid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` AS dept WHERE dept.staffid = " . esc_sql($staffid).")";
        }

        $query = "SELECT COUNT(ticket.id)
        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
        WHERE (".$agent_conditions.") AND (ticket.status != 4 AND ticket.status !=5) ";
        $result['openticket'] = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(ticket.id)
        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
        WHERE (".$agent_conditions.") AND ticket.status = 3 ";
        $result['answeredticket'] = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(ticket.id)
        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
        WHERE (".$agent_conditions.") AND (ticket.status = 4 OR ticket.status = 5) ";
        $result['closedticket'] = majesticsupport::$_db->get_var($query);


        $query = "SELECT COUNT(ticket.id)
        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
        WHERE (".$agent_conditions.") AND ticket.isoverdue = 1 ";
        $result['overdue'] = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(ticket.id)
        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
        WHERE (".$agent_conditions.")  ";
        $result['allticket'] = majesticsupport::$_db->get_var($query);

        return $result;
    }

    function getAgentCpChartData($staffid){
        if(!is_numeric($staffid) || majesticsupport::$_config['cplink_ticketstats_staff'] != 1){
            return false;
        }

        $curdate = date_i18n('Y-m-d');
        $fromdate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));

        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id AND status = 0 AND (lastreply = '0000-00-00 00:00:00') AND date(created) >= '".esc_sql($fromdate)."' AND date(created) <= '".esc_sql($curdate)."' ) AS totalticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ORDER BY priority.priority";
        $openticket_pr = majesticsupport::$_db->get_results($query);

        $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets`";
        $allticket_pr = majesticsupport::$_db->get_var($query);

        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id AND isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '".esc_sql($fromdate)."' AND date(created) <= '".esc_sql($curdate)."') AS totalticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ORDER BY priority.priority";
        $answeredticket_pr = majesticsupport::$_db->get_results($query);
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id AND isoverdue = 1 AND status != 4 AND date(created) >= '".esc_sql($fromdate)."' AND date(created) <= '".esc_sql($curdate)."') AS totalticket
                    FROM `".majesticsupport::$_db->prefix."mjtc_support_priorities` AS priority ORDER BY priority.priority";
        $overdueticket_pr = majesticsupport::$_db->get_results($query);
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE priorityid = priority.id  AND isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00') AND date(created) >= '".esc_sql($fromdate)."' AND date(created) <= '".esc_sql($curdate)."') AS totalticket
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
    }

    function getUserLatestTicketsForCp($uid){
        if(!is_numeric($uid)){
            return false;
        }
        do_action('ms_addon_user_cp_tickets');
        $query = "SELECT ticket.*,department.departmentname AS departmentname ,priority.priority AS priority,priority.prioritycolour AS prioritycolour ".majesticsupport::$_addon_query['select']."
        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON     ticket.departmentid = department.id
        ".majesticsupport::$_addon_query['join'];
        $query .= " WHERE ticket.uid = " . esc_sql($uid);
        $query .= " ORDER BY ticket.created DESC LIMIT 3";
        $tickets = majesticsupport::$_db->get_results($query);
        do_action('reset_ms_aadon_query');
        return $tickets;
    }

    function getUserTicketStats($uid){
        if(!is_numeric($uid) || majesticsupport::$_config['count_on_myticket'] != 1){
            return false;
        }

        $result = array();

        $query = "SELECT COUNT(ticket.id)
        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
        WHERE ticket.uid = ".esc_sql($uid)." AND (ticket.status != 4 AND ticket.status != 5)";
        $result['openticket'] = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(ticket.id)
        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
        WHERE ticket.uid = ".esc_sql($uid)." AND ticket.status = 3 ";
        $result['answeredticket'] = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(ticket.id)
        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
        WHERE ticket.uid = ".esc_sql($uid)." AND (ticket.status = 4 OR ticket.status = 5)";
        $result['closedticket'] = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(ticket.id)
        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON ticket.departmentid = department.id
        LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON ticket.priorityid = priority.id
        WHERE ticket.uid = ".esc_sql($uid);
        $result['allticket'] = majesticsupport::$_db->get_var($query);

        return $result;
    }

    function getLatestDownloadsForCp(){
        if( in_array('download', majesticsupport::$_active_addons) ){
            $query = "SELECT download.title, download.id AS downloadid
            FROM `" . majesticsupport::$_db->prefix . "mjtc_support_downloads` AS download
            WHERE download.status = 1 ORDER BY download.created DESC LIMIT 4";
            return majesticsupport::$_db->get_results($query);
        }
        return false;
    }

    function getLatestAnnouncementsForCp(){
        if( in_array('announcement', majesticsupport::$_active_addons) ){
            $query = "SELECT announcement.id, announcement.title
            FROM `" . majesticsupport::$_db->prefix . "mjtc_support_announcements` AS announcement
            WHERE announcement.status = 1 ORDER BY announcement.created DESC LIMIT 4";
            return majesticsupport::$_db->get_results($query);
        }
        return false;
    }


    function getLatestArticlesForCp(){
        if( in_array('knowledgebase', majesticsupport::$_active_addons) ){
            $query = "SELECT article.subject,article.content, article.id AS articleid
            FROM `" . majesticsupport::$_db->prefix . "mjtc_support_articles` AS article
            WHERE article.status = 1 ORDER BY article.created DESC LIMIT 4";
            return majesticsupport::$_db->get_results($query);
        }
        return false;
    }

    function getLatestFaqsForCp(){
        if( in_array('faq', majesticsupport::$_active_addons) ){
            $query = "SELECT faq.id, faq.subject, faq.content
            FROM `" . majesticsupport::$_db->prefix . "mjtc_support_faqs` AS faq
            WHERE faq.status = 1 ORDER BY faq.created DESC LIMIT 4";
            return majesticsupport::$_db->get_results($query);
        }
        return false;
    }


    function getStaffControlPanelData() {

        $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` ";
        $allticket = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00')";
        $openticket = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE status = 4";
        $closeticket = majesticsupport::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0";
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

        $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets`";
        majesticsupport::$_data['total_tickets']['total_ticket'] = majesticsupport::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_departments`";
        majesticsupport::$_data['total_tickets']['total_department'] = majesticsupport::$_db->get_var($query);

        if(in_array('agent', majesticsupport::$_active_addons)){
            $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_staff`";
            majesticsupport::$_data['total_tickets']['total_staff'] = majesticsupport::$_db->get_var($query);
        }else{
            majesticsupport::$_data['total_tickets']['total_staff'] = 0;
        }
        if(in_array('feedback', majesticsupport::$_active_addons)){
            $query = "SELECT COUNT(id) FROM `".majesticsupport::$_db->prefix."mjtc_support_feedbacks`";
            majesticsupport::$_data['total_tickets']['total_feedback'] = majesticsupport::$_db->get_var($query);
        }else{
            majesticsupport::$_data['total_tickets']['total_feedback'] = 0;
        }
    }

    function makeDir($path) {
        if (!file_exists($path)) { // create directory
            mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die(esc_html(__('Cannot open file', 'majestic-support')));
            fclose($ourFileHandle);
        }
    }

    function MJTC_checkExtension($filename) {
        $i = strrpos($filename, ".");
        if (!$i)
            return 'N';
        $l = MJTC_majesticsupportphplib::MJTC_strlen($filename) - $i;
        $ext = MJTC_majesticsupportphplib::MJTC_substr($filename, $i + 1, $l);
        $extensions = MJTC_majesticsupportphplib::MJTC_explode(",", majesticsupport::$_config['file_extension']);
        $match = 'N';
        foreach ($extensions as $extension) {
            if (MJTC_majesticsupportphplib::MJTC_strtolower($extension) == MJTC_majesticsupportphplib::MJTC_strtolower($ext)) {
                $match = 'Y';
                break;
            }
        }
        return $match;
    }

    function storeTheme($data) {
        $filepath = MJTC_PLUGIN_PATH . 'includes/css/style.php';
        $filestring = file_get_contents($filepath);
        $this->replaceString($filestring, 1, $data);
        $this->replaceString($filestring, 2, $data);
        $this->replaceString($filestring, 3, $data);
        $this->replaceString($filestring, 4, $data);
        $this->replaceString($filestring, 5, $data);
        $this->replaceString($filestring, 6, $data);
        $this->replaceString($filestring, 7, $data);
        if (file_put_contents($filepath, $filestring)) {
            MJTC_message::MJTC_setMessage(esc_html(__('The new theme has been applied', 'majestic-support')), 'updated');
        } else {
            MJTC_message::MJTC_setMessage(esc_html(__('Error applying the new theme', 'majestic-support')), 'error');
        }
        return;
    }

    function replaceString(&$filestring, $colorNo, $data) {
        if (MJTC_majesticsupportphplib::MJTC_strstr($filestring, '$color' . $colorNo)) {
            $path1 = MJTC_majesticsupportphplib::MJTC_strpos($filestring, '$color' . $colorNo);
            $path2 = MJTC_majesticsupportphplib::MJTC_strpos($filestring, ';', $path1);
            $filestring = substr_replace($filestring, '$color' . $colorNo . ' = "' . $data['color' . $colorNo] . '";', $path1, $path2 - $path1 + 1);
        }
    }

    function getColorCode($filestring, $colorNo) {
        if (MJTC_majesticsupportphplib::MJTC_strstr($filestring, '$color' . $colorNo)) {
            $path1 = MJTC_majesticsupportphplib::MJTC_strpos($filestring, '$color' . $colorNo);
            $path1 = MJTC_majesticsupportphplib::MJTC_strpos($filestring, '#', $path1);
            $path2 = MJTC_majesticsupportphplib::MJTC_strpos($filestring, ';', $path1);
            $colorcode = MJTC_majesticsupportphplib::MJTC_substr($filestring, $path1, $path2 - $path1 - 1);
            return $colorcode;
        }
    }

    function getCurrentTheme() {
        $filepath = MJTC_PLUGIN_PATH . 'includes/css/style.php';
        $filestring = file_get_contents($filepath);
        $theme['color1'] = $this->getColorCode($filestring, 1);
        $theme['color2'] = $this->getColorCode($filestring, 2);
        $theme['color3'] = $this->getColorCode($filestring, 3);
        $theme['color4'] = $this->getColorCode($filestring, 4);
        $theme['color5'] = $this->getColorCode($filestring, 5);
        $theme['color6'] = $this->getColorCode($filestring, 6);
        $theme['color7'] = $this->getColorCode($filestring, 7);
        $theme = apply_filters('cm_theme_colors', $theme, 'majestic-support');
        majesticsupport::$_data[0] = $theme;
        return;
    }
    //translation code
    function getListTranslations() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-list-translations') ) {
            die( 'Security check Failed' );
        }
        $result = array();
        $result['error'] = false;

        // $path = MJTC_PLUGIN_PATH.'languages';

        $path = WP_LANG_DIR;
        if(!is_dir($path)){
            $this->makeDir($path);
        }else{
            $path = WP_LANG_DIR . '/plugins/';
            if(!is_dir($path)){
                $this->makeDir($path);
            }
        }

        if( ! is_writeable($path)){
            $result['error'] = esc_html(__('Dir is not writable','majestic-support')).' '.esc_url($path);

        }else{

            if($this->isConnected()){

                $url = "https://majesticsupport.com/translations/api/1.0/index.php";
                $post_data['product'] ='majestic-support-wp';
                $post_data['domain'] = get_site_url();
                $post_data['producttype'] = majesticsupport::$_config['producttype'];
                $post_data['productcode'] = 'mjsupport';
                $post_data['productversion'] = majesticsupport::$_config['productversion'];
                $post_data['JVERSION'] = get_bloginfo('version');
                $post_data['method'] = 'getTranslations';

                $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>45,'sslverify'=>false));
                if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
                    $call_result = $response['body'];
                }else{
                    $call_result = false;
                    if(!is_wp_error($response)){
                       $error = $response['response']['message'];
                    }else{
                        $error = $response->get_error_message();
                    }
                }

                $result['data'] = MJTC_majesticsupportphplib::MJTC_htmlentities($call_result);
                if(!$call_result){
                    $result['error'] = $error;
                }

            }else{
                $result['error'] = esc_html(__('Unable to connect to the server','majestic-support'));
            }
        }

        $result = json_encode($result);

        return $result;
    }

    function makeLanguageCode($lang_name){
        $langarray = wp_get_installed_translations('core');
        $langarray = $langarray['default'];
        $match = false;
        if(array_key_exists($lang_name, $langarray)){
            $lang_name = $lang_name;
            $match = true;
        }else{
            $m_lang = '';
            foreach($langarray AS $k => $v){
                if($lang_name[0].$lang_name[1] == $k[0].$k[1]){
                    $m_lang .= $k.', ';
                }
            }

            if($m_lang != ''){
                $m_lang = MJTC_majesticsupportphplib::MJTC_substr($m_lang, 0,MJTC_majesticsupportphplib::MJTC_strlen($m_lang) - 2);
                $lang_name = $m_lang;
                $match = 2;
            }else{
                $lang_name = $lang_name;
                $match = false;
            }
        }

        return array('match' => $match , 'lang_name' => $lang_name);
    }

    function validateAndShowDownloadFileName( ){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'validate-and-show-download-filename') ) {
            die( 'Security check Failed' );
        }
        $lang_name = MJTC_request::MJTC_getVar('langname');
        if($lang_name == '') return '';
        $result = array();
        $f_result = $this->makeLanguageCode($lang_name);
        // $path = MJTC_PLUGIN_PATH.'languages';
        $path = WP_LANG_DIR . '/plugins/';
        $result['error'] = false;
        if($f_result['match'] === false){
            $result['error'] = $lang_name. ' ' . esc_html(__('Language is not installed','majestic-support'));
        }elseif( ! is_writeable($path)){
            $result['error'] = $lang_name. ' ' . esc_html(__('Language directory is not writable','majestic-support')).': '.esc_url($path);
        }else{
            $result['input'] = '<input id="languagecode" class="text_area" type="text" value="'.esc_attr($lang_name).'" name="languagecode">';
            if($f_result['match'] === 2){
                $result['input'] .= '<div id="mjtc-emessage-wrapper-other" style="display:block;margin:20px 0px 20px;">';
                $result['input'] .= esc_html(__('Required language is not installed but similar language like','majestic-support')).': "<b>'.esc_html($f_result['lang_name']).'</b>" '.esc_html(__('is found in your system','majestic-support'));
                $result['input'] .= '</div>';

            }
            $result['input'] = MJTC_majesticsupportphplib::MJTC_htmlentities($result['input']);
            $result['path'] = esc_html(__('Language code','majestic-support'));
        }
        $result = json_encode($result);
        return $result;
    }

    function getLanguageTranslation(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-language-translation') ) {
            die( 'Security check Failed' );
        }
        $lang_name = MJTC_request::MJTC_getVar('langname');
        $language_code = MJTC_request::MJTC_getVar('filename');

        $result = array();
        $result['error'] = false;
        // $path = MJTC_PLUGIN_PATH.'languages';
        $path = WP_LANG_DIR . '/plugins/';
        if(!is_dir($path)){
            mkdir($path);
        }

        if($lang_name == '' || $language_code == ''){
            $result['error'] = esc_html(__('Empty values','majestic-support'));
            return json_encode($result);
        }

        $final_path = $path.'/majestic-support-'.$language_code.'.po';


        $langarray = wp_get_installed_translations('core');
        $langarray = $langarray['default'];

        if(!array_key_exists($language_code, $langarray)){
            $result['error'] = $lang_name. ' ' . esc_html(__('Language is not installed','majestic-support'));
            return json_encode($result);
        }elseif( ! is_writeable($path)){
            $result['error'] = $lang_name. ' ' . esc_html(__('Language directory is not writable','majestic-support')).': '.esc_url($path);
            return json_encode($result);
        }

        if( ! file_exists($final_path)){
            touch($final_path);
        }

        if( ! is_writeable($final_path)){
            $result['error'] = esc_html(__('File is not writable','majestic-support')).': '.$final_path;
        }else{

            if($this->isConnected()){

                $url = "https://majesticsupport.com/translations/api/1.0/index.php";
                $post_data['product'] ='majestic-support-wp';
                $post_data['domain'] = get_site_url();
                $post_data['producttype'] = majesticsupport::$_config['producttype'];
                $post_data['productcode'] = 'mjsupport';
                $post_data['productversion'] = majesticsupport::$_config['productversion'];
                $post_data['JVERSION'] = get_bloginfo('version');
                $post_data['translationcode'] = $lang_name;
                $post_data['method'] = 'getTranslationFile';

                $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>7,'sslverify'=>false));
                if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
                    $result = $response['body'];
                }else{
                    $result = false;
                    if(!is_wp_error($response)){
                       $error = $response['response']['message'];
                   }else{
                        $error = $response->get_error_message();
                   }
                }
                if($result){
                    $result = json_decode($result, true);
                $ret = $this->writeLanguageFile( $final_path , $result['file']);
                }else{
                    $result = array();
                } 
                $result['data'] = esc_html(__('File successfully downloaded','majestic-support'));
            }else{
                $result['error'] = esc_html(__('Unable to connect to the server','majestic-support'));
            }
        }

        $result = json_encode($result);

        return $result;

    }

    function writeLanguageFile( $path , $url ){
        $result = true;
        do_action('majesticsupport_load_wp_admin_file');
        $tmpfile = download_url( $url);
        copy( $tmpfile, $path );
        @unlink( $tmpfile ); // must unlink afterwards
        //make mo for po file
        $this->phpmo_convert($path);
        return $result;
    }

    function isConnected(){

        $connected = @fsockopen("www.google.com", 80);
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }

    function phpmo_convert($input, $output = false) {
        if ( !$output )
            $output = MJTC_majesticsupportphplib::MJTC_str_replace( '.po', '.mo', $input );
        $hash = $this->phpmo_parse_po_file( $input );
        if ( $hash === false ) {
            return false;
        } else {
            $this->phpmo_write_mo_file( $hash, $output );
            return true;
        }
    }

    function phpmo_clean_helper($x) {
        if (is_array($x)) {
            foreach ($x as $k => $v) {
                $x[$k] = $this->phpmo_clean_helper($v);
            }
        } else {
            if ($x[0] == '"')
                $x = MJTC_majesticsupportphplib::MJTC_substr($x, 1, -1);
            $x = MJTC_majesticsupportphplib::MJTC_str_replace("\"\n\"", '', $x);
            $x = MJTC_majesticsupportphplib::MJTC_str_replace('$', '\\$', $x);
        }
        return $x;
    }
    /* Parse gettext .po files. */
    /* @link http://www.gnu.org/software/gettext/manual/gettext.html#PO-Files */
    function phpmo_parse_po_file($in) {
    if (!file_exists($in)){ return false; }
    $ids = array();
    $strings = array();
    $language = array();
    $lines = file($in);
    foreach ($lines as $line_num => $line) {
        if (MJTC_majesticsupportphplib::MJTC_strstr($line, 'msgid')){
			$endpos = strrpos($line, '"',7);
			if($endpos > 7){ // to avoid msgid ""
				$id = MJTC_majesticsupportphplib::MJTC_substr($line, 7, $endpos-7);
				$ids[] = $id;
			}
        }elseif(MJTC_majesticsupportphplib::MJTC_strstr($line, 'msgstr')){
			$endpos = strrpos($line, '"',8);
			if($endpos > 8){ // to avoid msgstr ""
				$string = MJTC_majesticsupportphplib::MJTC_substr($line, 8, $endpos-8);
				$strings[] = array($string);
			}
        }else{}
    }
    for ($i=0; $i<count($ids); $i++){
        //Shoaib
        if(isset($ids[$i]) && isset($strings[$i])){
            $language[$ids[$i]] = array('msgid' => $ids[$i], 'msgstr' =>$strings[$i]);
        }
    }
    return $language;
    }
    /* Write a GNU gettext style machine object. */
    /* @link http://www.gnu.org/software/gettext/manual/gettext.html#MO-Files */
    function phpmo_write_mo_file($hash, $out) {
        // sort by msgid
        ksort($hash, SORT_STRING);
        // our mo file data
        $mo = '';
        // header data
        $offsets = array ();
        $ids = '';
        $strings = '';
        foreach ($hash as $entry) {
            $id = $entry['msgid'];
            $str = implode("\x00", $entry['msgstr']);
            // keep track of offsets
            $offsets[] = array (
                            MJTC_majesticsupportphplib::MJTC_strlen($ids), MJTC_majesticsupportphplib::MJTC_strlen($id), MJTC_majesticsupportphplib::MJTC_strlen($strings), MJTC_majesticsupportphplib::MJTC_strlen($str)
                            );
            // plural msgids are not stored (?)
            $ids .= $id . "\x00";
            $strings .= $str . "\x00";
        }
        // keys start after the header (7 words) + index tables ($#hash * 4 words)
        $key_start = 7 * 4 + sizeof($hash) * 4 * 4;
        // values start right after the keys
        $value_start = $key_start +MJTC_majesticsupportphplib::MJTC_strlen($ids);
        // first all key offsets, then all value offsets
        $key_offsets = array ();
        $value_offsets = array ();
        // calculate
        foreach ($offsets as $v) {
            list ($o1, $l1, $o2, $l2) = $v;
            $key_offsets[] = $l1;
            $key_offsets[] = $o1 + $key_start;
            $value_offsets[] = $l2;
            $value_offsets[] = $o2 + $value_start;
        }
        $offsets = array_merge($key_offsets, $value_offsets);
        // write header
        $mo .= pack('Iiiiiii', 0x950412de, // magic number
        0, // version
        sizeof($hash), // number of entries in the catalog
        7 * 4, // key index offset
        7 * 4 + sizeof($hash) * 8, // value index offset,
        0, // hashtable size (unused, thus 0)
        $key_start // hashtable offset
        );
        // offsets
        foreach ($offsets as $offset)
            $mo .= pack('i', $offset);
        // ids
        $mo .= $ids;
        // strings
        $mo .= $strings;
        file_put_contents($out, $mo);
    }

    function stripslashesFull($input){// testing this function/.
        if($input == ''){
            return $input;
        }
        if (is_array($input)) {
            $input = array_map(array($this,'stripslashesFull'), $input);
        } elseif (is_object($input)) {
            $vars = get_object_vars($input);
            foreach ($vars as $k=>$v) {
                $input->{$k} = stripslashesFull($v);
            }
        } else {
            $input = MJTC_majesticsupportphplib::MJTC_stripslashes($input);
        }
        return $input;
    }

    function getUserNameById($id){
        if (!is_numeric($id))
            return false;
        $query = "SELECT user_nicename AS name FROM `" . majesticsupport::$_wpprefixforuser . "mjtc_support_users` WHERE id = " . esc_sql($id);
        $username = majesticsupport::$_db->get_var($query);
        return $username;
    }

    function getusersearchajax() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-usersearch-ajax') ) {
            die( 'Security check Failed' );
        }
        $username = MJTC_request::MJTC_getVar('username');
        $name = MJTC_request::MJTC_getVar('name');
        $emailaddress = MJTC_request::MJTC_getVar('emailaddress');
        $canloadresult = false;
        $query = "SELECT DISTINCT user.id AS userid, user.name AS username, user.user_email AS useremail, user.display_name AS userdisplayname
                    FROM `" . majesticsupport::$_wpprefixforuser . "mjtc_support_users` AS user ";
                    if(in_array('agent',majesticsupport::$_active_addons)){
                        $query .= " WHERE NOT EXISTS( SELECT staff.id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS staff WHERE user.id = staff.uid) ";
                    }else{
                        $query .= " WHERE 1 = 1 "; // to handle filter cases
                    }
        if (MJTC_majesticsupportphplib::MJTC_strlen($name) > 0) {
            $query .= " AND user.display_name LIKE '%".esc_sql($name)."%'";
            $canloadresult = true;
        }
        if (MJTC_majesticsupportphplib::MJTC_strlen($emailaddress) > 0) {
            $query .= " AND user.user_email LIKE '%".esc_sql($emailaddress)."%'";
            $canloadresult = true;
        }
        if (MJTC_majesticsupportphplib::MJTC_strlen($username) > 0) {
            $query .= " AND user.name LIKE '%".esc_sql($username)."%'";
            $canloadresult = true;
        }
        if($canloadresult){
            $users = majesticsupport::$_db->get_results($query);
            if(!empty($users)){
                $result ='
                <div class="mjtc-support-table-wrp">
                    <div class="mjtc-support-table-header">
                        <div class="mjtc-support-table-header-col mjtc-sprt-tbl-uid">'.esc_html(__('ID', 'majestic-support')).'</div>
                        <div class="mjtc-support-table-header-col mjtc-sprt-tbl-unm">'.esc_html(__('User Name', 'majestic-support')).'</div>
                        <div class="mjtc-support-table-header-col mjtc-sprt-tbl-eml">'.esc_html(__('Email Address', 'majestic-support')).'</div>
                        <div class="mjtc-support-table-header-col mjtc-sprt-tbl-nam">'.esc_html(__('Name', 'majestic-support')).'</div>
                    </div>
                    <div class="mjtc-support-table-body">';
                        foreach($users AS $user){
                            $result .='
                            <div class="mjtc-support-data-row">
                                <div class="mjtc-support-table-body-col mjtc-sprt-tbl-uid">
                                    <span class="mjtc-support-display-block">'.esc_html(__('User ID','majestic-support')).'</span>'.esc_html($user->userid).'
                                </div>
                                <div class="mjtc-support-table-body-col mjtc-sprt-tbl-unm">
                                    <span class="mjtc-support-display-block">'.esc_html(__('User Name','majestic-support')).':</span>
                                    <span class="mjtc-support-title"><a href="#" class="mjtc-userpopup-link" data-id="'.esc_attr($user->userid).'" data-email="'.esc_attr($user->useremail).'" data-name="'.esc_attr($user->userdisplayname).'">';
                                        if(isset($user->username) && $user->username != ''){
                                            $result .= esc_html($user->username);
                                        } else {
                                            $result .= esc_html($user->useremail);
                                        }
                                        $result .='</a></span>
                                </div>
                                <div class="mjtc-support-table-body-col mjtc-sprt-tbl-eml">
                                    <span class="mjtc-support-display-block">'.esc_html(__('Email','majestic-support')).':</span>
                                    '.esc_html($user->useremail).'
                                </div>
                                <div class="mjtc-support-table-body-col mjtc-sprt-tbl-nam">
                                    <span class="mjtc-support-display-block">'.esc_html(__('Name','majestic-support')).':</span>
                                    '.esc_html($user->userdisplayname).'
                                </div>
                            </div>';
                        }
                $result .='</div>';
            }else{
                $result= MJTC_layout::MJTC_getNoRecordFound();
            }
        }else{ // reset button
            $result = $this->getuserlistajax(0);
        }

        return $result;
    }



    function getuserlistajax($ajaxCall = 1){
        if ($ajaxCall == 1) {
            $nonce = MJTC_request::MJTC_getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'get-user-list-ajax') ) {
                die( 'Security check Failed' );
            }
        }
        $userlimit = MJTC_request::MJTC_getVar('userlimit',null,0);
        $maxrecorded = 4;
        $query = "SELECT DISTINCT COUNT(user.id)
                    FROM `" . majesticsupport::$_wpprefixforuser . "mjtc_support_users` AS user 
					WHERE user.status = 1 ";
                    if(in_array('agent',majesticsupport::$_active_addons)){
                        $query .= " AND NOT EXISTS( SELECT staff.id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS staff WHERE user.id = staff.uid) ";
                    }

        $total = majesticsupport::$_db->get_var($query);
        $limit = $userlimit * $maxrecorded;
        if($limit >= $total){
            $limit = 0;
        }
        $query = "SELECT DISTINCT user.id AS userid, user.name AS username, user.user_email AS useremail,
                    user.display_name AS userdisplayname
                    FROM `" . majesticsupport::$_wpprefixforuser . "mjtc_support_users` AS user 
					WHERE user.status = 1";
                    if(in_array('agent',majesticsupport::$_active_addons)){
                        $query .= " AND NOT EXISTS( SELECT staff.id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS staff WHERE user.id = staff.uid) ";
                    }
                    $query .= " LIMIT $limit, $maxrecorded";
        $users = majesticsupport::$_db->get_results($query);
        $html = $this->makeUserList($users,$total,$maxrecorded,$userlimit);
        return $html;

    }

    function makeUserList($users,$total,$maxrecorded,$userlimit){
        $html = '';
        if(!empty($users)){
            if(is_array($users)){
                $html ='
                <div class="mjtc-support-table-wrp">
                    <div class="mjtc-support-table-header">
                        <div class="mjtc-support-table-header-col mjtc-sprt-tbl-uid">'.esc_html(__('ID', 'majestic-support')).'</div>
                        <div class="mjtc-support-table-header-col mjtc-sprt-tbl-unm">'.esc_html(__('User Name', 'majestic-support')).'</div>
                        <div class="mjtc-support-table-header-col mjtc-sprt-tbl-eml">'.esc_html(__('Email Address', 'majestic-support')).'</div>
                        <div class="mjtc-support-table-header-col mjtc-sprt-tbl-nam">'.esc_html(__('Name', 'majestic-support')).'</div>
                    </div>
                    <div class="mjtc-support-table-body">';
                        foreach($users AS $user){
                            $html .='
                            <div class="mjtc-support-data-row">
                                <div class="mjtc-support-table-body-col mjtc-sprt-tbl-uid">
                                    <span class="mjtc-support-display-block">'.esc_html(__('User ID','majestic-support')).'</span>'.esc_html($user->userid).'
                                </div>
                                <div class="mjtc-support-table-body-col mjtc-sprt-tbl-unm">
                                    <span class="mjtc-support-display-block">'.esc_html(__('User Name','majestic-support')).':</span>
                                    <span class="mjtc-support-title"><a href="#" class="mjtc-userpopup-link" data-id="'.esc_attr($user->userid).'" data-email="'.esc_attr($user->useremail).'" data-name="'.esc_attr($user->userdisplayname).'">';
                                        if(isset($user->username) && $user->username != ''){
                                            $html .= esc_html($user->username);
                                        } else {
                                            $html .= esc_html($user->useremail);
                                        }
                                        $html .='</a></span>
                                </div>
                                <div class="mjtc-support-table-body-col mjtc-sprt-tbl-eml">
                                    <span class="mjtc-support-display-block">'.esc_html(__('Email','majestic-support')).':</span>
                                    '.esc_html($user->useremail).'
                                </div>
                                <div class="mjtc-support-table-body-col mjtc-sprt-tbl-nam">
                                    <span class="mjtc-support-display-block">'.esc_html(__('Name','majestic-support')).':</span>
                                    '.esc_html($user->userdisplayname).'
                                </div>
                            </div>';
                        }
                $html .='</div>';
            }
            $num_of_pages = ceil($total / $maxrecorded);
            $num_of_pages = ($num_of_pages > 0) ? ceil($num_of_pages) : floor($num_of_pages);
            if($num_of_pages > 0){
                $page_html = '';
                $prev = $userlimit;
                if($prev > 0){
                    $page_html .= '<a class="ms_userlink" href="#" onclick="updateuserlist('.esc_js(($prev - 1)).');">'.esc_html(__('Previous','majestic-support')).'</a>';
                }
                for($i = 0; $i < $num_of_pages; $i++){
                    if($i == $userlimit)
                        $page_html .= '<span class="ms_userlink selected" >'.($i + 1).'</span>';
                    else
                        $page_html .= '<a class="ms_userlink" href="#" onclick="updateuserlist('.esc_js($i).');">'.esc_js(($i + 1)).'</a>';

                }
                $next = $userlimit + 1;
                if($next < $num_of_pages){
                    $page_html .= '<a class="ms_userlink" href="#" onclick="updateuserlist('.esc_js($next).');">'.esc_html(__('Next','majestic-support')).'</a>';
                }
                if($page_html != ''){
                    $html .= '<div class="ms_userpages">'.wp_kses($page_html, MJTC_ALLOWED_TAGS).'</div>';
                }
            }

        }else{
            $html = MJTC_layout::MJTC_getNoRecordFound();
        }
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
        die();
        return $html;
    }

    function storeOrderingFromPage($data) {//
        if (empty($data)) {
            return false;
        }
        $sorted_array = array();
        MJTC_majesticsupportphplib::MJTC_parse_str($data['fields_ordering_new'],$sorted_array);
        $sorted_array = reset($sorted_array);
        if(!empty($sorted_array)){

            if($data['ordering_for'] == 'department'){
                $row = MJTC_includer::MJTC_getTable('departments');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'priority'){
                $row = MJTC_includer::MJTC_getTable('priorities');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'fieldsordering'){
                $row = MJTC_includer::MJTC_getTable('fieldsordering');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'announcement'){
                $row = MJTC_includer::MJTC_getTable('announcement');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'faq'){
                $row = MJTC_includer::MJTC_getTable('faq');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'helptopic'){
                $row = MJTC_includer::MJTC_getTable('helptopic');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'article'){
                $row = MJTC_includer::MJTC_getTable('articles');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'download'){
                $row = MJTC_includer::MJTC_getTable('download');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'fieldordering'){
                $row = MJTC_includer::MJTC_getTable('fieldsordering');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'multiform'){
                $row = MJTC_includer::MJTC_getTable('multiform');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'ticketclosereason'){
                $row = MJTC_includer::MJTC_getTable('ticketclosereason');
                $ordering_coloumn = 'ordering';
            }

            $page_multiplier = 1;
            if($data['pagenum_for_ordering'] > 1){
                $page_multiplier = ($data['pagenum_for_ordering'] - 1) * majesticsupport::$_config['pagination_default_page_size'] + 1;
            }
            for ($i=0; $i < count($sorted_array) ; $i++) {
                $row->update(array('id' => $sorted_array[$i], $ordering_coloumn => $page_multiplier + $i));
            }
        }
        MJTC_message::MJTC_setMessage(esc_html(__('Ordering updated', 'majestic-support')), 'updated');
        return ;
    }

    function updateDate($addon_name,$plugin_version){
        return MJTC_includer::MJTC_getModel('premiumplugin')->verfifyAddonActivation($addon_name);
    }

    function getAddonSqlForActivation($addon_name,$addon_version){
        return MJTC_includer::MJTC_getModel('premiumplugin')->verifyAddonSqlFile($addon_name,$addon_version);
    }

    function installPluginFromAjax(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'install-plugin-ajax') ) {
             die( 'Security check Failed' ); 
        }
        if(current_user_can( 'install_plugins' )){
            $pluginslug = MJTC_request::MJTC_getVar('pluginslug');
            if(file_exists(plugins_url($pluginslug . '/' . $pluginslug . '.php'))){
                return false;
            }
            if($pluginslug != ""){
                do_action('majesticsupport_load_wp_plugin_install');
                do_action('majesticsupport_load_wp_upgrader');
                do_action('majesticsupport_load_wp_ajax_upgrader_skin');
                do_action('majesticsupport_load_wp_plugin_upgrader');

                // Get Plugin Info
                $api = plugins_api( 'plugin_information',
                    array(
                        'slug' => $pluginslug,
                        'fields' => array(
                            'short_description' => false,
                            'sections' => false,
                            'requires' => false,
                            'rating' => false,
                            'ratings' => false,
                            'downloaded' => false,
                            'last_updated' => false,
                            'added' => false,
                            'tags' => false,
                            'compatibility' => false,
                            'homepage' => false,
                            'donate_link' => false,
                        ),
                    )
                );
                $skin     = new WP_Ajax_Upgrader_Skin();
                $upgrader = new Plugin_Upgrader( $skin );
                $upgrader->install( $api->download_link );
                if(file_exists(plugins_url($pluginslug . '/' . $pluginslug . '.php'))){
                    return true;
                }
            }
        }
        return false;
    }

    function activatePluginFromAjax(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'activate-plugin-ajax') ) {
             die( 'Security check Failed' ); 
        }
        if(current_user_can( 'activate_plugins')){
            $pluginslug = MJTC_request::MJTC_getVar('pluginslug');
            do_action('majesticsupport_load_wp_plugin_file');
            if(file_exists(plugins_url($pluginslug . '/' . $pluginslug . '.php'))){
                $isactivate = is_plugin_active($pluginslug.'/'.$pluginslug.'.php');
                if($isactivate){
                    return false;
                }
                if($pluginslug != ""){
                    if(!defined( 'WP_ADMIN')){
                        define( 'WP_ADMIN', TRUE );
                    }
                    // define( 'WP_NETWORK_ADMIN', TRUE ); // Need for Multisite
                    if(!defined( 'WP_USER_ADMIN')){
                        define( 'WP_USER_ADMIN', TRUE );
                    }

                    ob_get_clean();
                    do_action('majesticsupport_load_wp_admin_file');
                    do_action('majesticsupport_load_wp_plugin_file');
                    activate_plugin( $pluginslug.'/'.$pluginslug.'.php' );
                    $isactivate = is_plugin_active($pluginslug.'/'.$pluginslug.'.php');
                    if($isactivate){
                        return true;
                    }
                }
            }
        }
        return false;
    }

    function MJTC_getDateFormat(){
        $dateformat = majesticsupport::$_config['date_format'];
        if ($dateformat == 'm/d/Y' || $dateformat == 'd/m/y' || $dateformat == 'm/d/y' || $dateformat == 'd/m/Y') {
            $dash = '/';
        } else {
            $dash = '-';
        }
        $firstdash = MJTC_majesticsupportphplib::MJTC_strpos($dateformat, $dash, 0);
        $firstvalue = MJTC_majesticsupportphplib::MJTC_substr($dateformat, 0, $firstdash);
        $firstdash = $firstdash + 1;
        $seconddash = MJTC_majesticsupportphplib::MJTC_strpos($dateformat, $dash, $firstdash);
        $secondvalue = MJTC_majesticsupportphplib::MJTC_substr($dateformat, $firstdash, $seconddash - $firstdash);
        $seconddash = $seconddash + 1;
        $thirdvalue = MJTC_majesticsupportphplib::MJTC_substr($dateformat, $seconddash, MJTC_majesticsupportphplib::MJTC_strlen($dateformat) - $seconddash);
        $mjtc_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
        $mjtc_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
        if($mjtc_scriptdateformat != ''){
            $mjtc_scriptdateformat = MJTC_majesticsupportphplib::MJTC_str_replace('Y', 'yy', $mjtc_scriptdateformat);
            $mjtc_scriptdateformat = MJTC_majesticsupportphplib::MJTC_str_replace('m', 'mm', $mjtc_scriptdateformat);
            $mjtc_scriptdateformat = MJTC_majesticsupportphplib::MJTC_str_replace('d', 'dd', $mjtc_scriptdateformat);
        }
        return $mjtc_scriptdateformat;
    }

    function getAddonTransationKey($option_name){
        $query = "SELECT `option_value` FROM " . majesticsupport::$_wpprefixforuser . "options WHERE option_name = '".esc_sql($option_name)."'";
        $transactionKey = majesticsupport::$_db->get_var($query);
		if($transactionKey == ""){
			$transactionKey = get_option($option_name);
		}
        return $transactionKey;
    }

    function getInstalledTranslationKey(){
        do_action('majesticsupport_load_wp_translation_install');
        $activated_lang = get_option('WPLANG','en_US');
        $install_lang_name = wp_get_available_translations();
        if(isset($install_lang_name[$activated_lang])){
            $lang_name = $this->makeLanguageCode($activated_lang);
            $install_lang_name = $install_lang_name[$activated_lang]['english_name'];
            if($activated_lang == "" || $activated_lang == 'en_US'){
                update_option( 'mjtc_tran_lang_exists', false);
                return false;
            }else{
                $path = WP_LANG_DIR . '/plugins/';
                $final_path = $path.'/majestic-support-'.$activated_lang.'.po';
                if(file_exists($final_path)){
                    update_option( 'mjtc_tran_lang_exists', false);
                    return false;
                }
                if(get_option( 'mjtc_tran_lang_exists', '') != ''){
                    $session = json_decode(get_option( 'mjtc_tran_lang_exists', ''));
                    if($session->code == $activated_lang){
                        return get_option( 'mjtc_tran_lang_exists');
                    }
                }
                $url = "https://majesticsupport.com/translations/api/1.0/index.php";
                $post_data['product'] ='majestic-support-wp';
                $post_data['domain'] = get_site_url();
                $post_data['producttype'] = majesticsupport::$_config['producttype'];
                $post_data['productcode'] = 'mjsupport';
                $post_data['productversion'] = majesticsupport::$_config['productversion'];
                $post_data['JVERSION'] = get_bloginfo('version');
                $post_data['translationcode'] = $activated_lang;
                $post_data['method'] = 'getTranslationFile';

                $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>7,'sslverify'=>false));
                if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
                    $result = $response['body'];
                }else{
                    $result = false;
                    if(!is_wp_error($response)){
                       $error = $response['response']['message'];
                    }else{
                        $error = $response->get_error_message();
                    }
                }
                if($result){
                    $array = json_decode($result, true);
                }else{
                    $array = array();
                }
                if(is_array($array) && isset($array['file'])){
                    $mjtc_tran_lang_exists = array("code" => $activated_lang, "lang_fullname" => $install_lang_name , "name" => $lang_name);
                    $mjtc_tran_lang_exists = json_encode($mjtc_tran_lang_exists);
                    update_option( 'mjtc_tran_lang_exists', $mjtc_tran_lang_exists);
                    return $mjtc_tran_lang_exists;
                }else{
                    update_option( 'mjtc_tran_lang_exists', false);
                    return false;
                }
            }
        }
        return false;
    }
    function getWPUidById($id){
        if(!is_numeric($id)){
            return false;
        }

        $query = "SELECT user.wpuid
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_users` AS user 
                    WHERE id = ".esc_sql($id);
        $wpuid = majesticsupport::$_db->get_var($query);
        return $wpuid;
    }

    function reviewBoxAction(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'review-box-action') ) {
            die( 'Security check Failed' );
        }
        $days = MJTC_request::MJTC_getVar('days');
        if($days == -1) {
            add_option("majesticsupport_hide_review_box", "1");
        } else {
            $date = date("Y-m-d", MJTC_majesticsupportphplib::MJTC_strtotime("+".$days." days"));
            update_option("majesticsupport_show_review_box_after", $date);
        }
        return true;
    }

    function getShortCodeData(){
        if( in_array('multiform', majesticsupport::$_active_addons) ){
            $query = "SELECT multiform.id, multiform.title, department.departmentname FROM `" . majesticsupport::$_db->prefix . "mjtc_support_multiform` AS multiform
                LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON multiform.departmentid = department.id WHERE multiform.status = 1 ORDER BY multiform.id ASC";
            majesticsupport::$_data[0]['multiforms'] = majesticsupport::$_db->get_results($query);
        }
        return true;
    }

    function checkIfMainCssFileIsEnqued(){
        global $wp_styles;
        if (!in_array('majesticsupport-main-css',$wp_styles->queue)) {
            wp_enqueue_style('majesticsupport-main-css', MJTC_PLUGIN_URL . 'includes/css/style.css');
            // responsive style sheets
            wp_enqueue_style('majesticsupport-desktop-css', MJTC_PLUGIN_URL . 'includes/css/style_desktop.css',array(),'','(min-width: 783px) and (max-width: 1280px)');
            wp_enqueue_style('majesticsupport-tablet-css', MJTC_PLUGIN_URL . 'includes/css/style_tablet.css',array(),'','(min-width: 668px) and (max-width: 782px)');
            wp_enqueue_style('majesticsupport-mobile-css', MJTC_PLUGIN_URL . 'includes/css/style_mobile.css',array(),'','(min-width: 481px) and (max-width: 667px)');
            wp_enqueue_style('majesticsupport-oldmobile-css', MJTC_PLUGIN_URL . 'includes/css/style_oldmobile.css',array(),'','(max-width: 480px)');
            if(is_rtl()){
                wp_enqueue_style('majesticsupport-main-css-rtl', MJTC_PLUGIN_URL . 'includes/css/stylertl.css');
            }
            $color = require_once(MJTC_PLUGIN_PATH . 'includes/css/style.php');
            wp_enqueue_style('majesticsupport-color-css', MJTC_PLUGIN_URL . 'includes/css/color.css');
        }
        return true;
    }

    function updateColorFile(){
        $color = require(MJTC_PLUGIN_PATH . 'includes/css/style.php');
        $file = fopen(MJTC_PLUGIN_PATH . 'includes/css/color.css','w');
        fwrite($file,$color);  
        fclose($file);
    }

    function getSiteUrl(){
        $site_url = site_url();
        if($site_url != ''){
            $site_url = MJTC_majesticsupportphplib::MJTC_str_replace("https://","",$site_url);
            $site_url = MJTC_majesticsupportphplib::MJTC_str_replace("http://","",$site_url);
        }
        return $site_url;
    }

    function getNetworkSiteUrl(){
        $network_site_url = network_site_url();
        if($network_site_url != ''){
            $network_site_url = MJTC_majesticsupportphplib::MJTC_str_replace("https://","",$network_site_url);
            $network_site_url = MJTC_majesticsupportphplib::MJTC_str_replace("http://","",$network_site_url);
        }
        return $network_site_url;
    }

    function addMissingUsers(){
        $missingUser = 0;
        $query = "SELECT id FROM `" . majesticsupport::$_db->prefix . "users`";
        $users = majesticsupport::$_db->get_results($query);
        $wpUsers = array();
        $msUsers = array();
        foreach ($users as $key => $user) {
            $wpUsers[] = $user->id;
        }
        $query = " SELECT wpuid FROM `" . majesticsupport::$_db->prefix . "mjtc_support_users`";
        $users = majesticsupport::$_db->get_results($query);
        foreach ($users as $key => $user) {
            $msUsers[] = $user->wpuid;
        }

        $missingUsers = array_diff($wpUsers,$msUsers);
        foreach ($missingUsers as $missingUser) {
            $query = "SELECT count(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_users` WHERE wpuid = " . esc_sql($missingUser);
            $total = majesticsupport::$_db->get_var($query);
            if ($total == 0) {
                $query = "SELECT * FROM `" . majesticsupport::$_db->prefix . "users` WHERE id = " . esc_sql($missingUser);
                $user = majesticsupport::$_db->get_row($query);                
                if (isset($user)) {
                    $row = MJTC_includer::MJTC_getTable('users');
                    $data['wpuid'] = $user->ID;
                    $data['name'] = $user->display_name;
                    $data['user_email'] = $user->user_email;
                    $data['issocial'] = 0;
                    $data['socialid'] = null;
                    $data['status'] = 1;
                    $data['created'] = date_i18n('Y-m-d H:i:s');
                    $row->bind($data);
                    $row->store();
                    $missingUser = 1;
                }
            }
        }
        if ($missingUser == 1) {
            MJTC_message::MJTC_setMessage(esc_html(__('Missing user(s) added successfully!', 'majestic-support')), 'updated');
        } else {
            MJTC_message::MJTC_setMessage(esc_html(__('No missing user found!', 'majestic-support')), 'error');
        }
        return;
    }

    function getPageTitle($layouts){
        $breadCrumbs = "";
        $actionButton = "";
        $title = __("About Us", 'majestic-support');
        switch ($layouts) {
            case 'dashboard':
                if(in_array('agent', majesticsupport::$_active_addons)){
                    $actionButton = "<a href=\"?page=majesticsupport_agent\" class=\"msadmin-add-link button\" title=\"". esc_html(__('Agents', 'majestic-support')) ."\">
                        <img alt=\"". esc_html(__('Staff', 'majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/admincp/staff-1.png\"/>
                        ". esc_html(__('Agents', 'majestic-support')) ."
                    </a>";
                }
                $actionButton .= "<a href=\"?page=majesticsupport_ticket\" class=\"msadmin-add-link button\" title=\"". esc_html(__('All Tickets', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('All Tickets', 'majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/admincp/all-tickets.png\"/>
                    ". esc_html(__('All Tickets', 'majestic-support'))."
                </a>";
                $hideBredcurms = true;
                $title = __("Dashboard", 'majestic-support');
                break;
            case 'admin_aboutus':
                $title = __("About Us", 'majestic-support');
                break;
            case 'translations':
                $actionButton = "<a target=\"blank\" href=\"#\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to translate Majestic Support', 'majestic-support'))."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                $title = __("Translations", 'majestic-support');
                break;
            case 'systemerror':
                $actionButton = "<a class=\"msadmin-add-link button\" onclick=\"return confirm('". esc_html(__('Are you sure you want to delete it?', 'majestic-support')) ."');\" href=\"". esc_url(wp_nonce_url('?page=majesticsupport_systemerror&task=deletesystemerror&action=mstask&systemerrorid=all','delete-systemerror')) ."\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/delete.png\" />". esc_html(__('Remove All', 'majestic-support')) ."</a>";
                $title = __("System Errors", 'majestic-support');
            break;
            case 'admin_addticket':
                $actionButton = "<a target=\"blank\" href=\"https://www.youtube.com/watch?v=dYniAnKyv-Q\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to create ticket', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                $title = __("Create Ticket", 'majestic-support');
                break;
            case 'ticketdetail':
                $title = __("Ticket Detail", 'majestic-support');
                if (isset(majesticsupport::$_data[0]->subject)) {
                    $customTitle = esc_html(majesticsupport::MJTC_getVarValue(majesticsupport::$_data[0]->subject));

                }
                break;
            case 'userfields':
                if(isset(majesticsupport::$_data['formid']) && majesticsupport::$_data['formid'] != null){
                    $mformid = majesticsupport::$_data['formid'];
                } else {
                    $mformid = MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId();
                }
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_fieldordering&mjslay=adduserfeild&&fieldfor=". esc_attr(majesticsupport::$_data['fieldfor']) ."&formid=". esc_attr($mformid) ."\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Field', 'majestic-support')) ."</a>
                <a target=\"blank\" href=\"#\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to setup user fields', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                if(in_array('multiform', majesticsupport::$_active_addons)){
                    $breadCrumbs = "<li><a href=\"?page=majesticsupport_multiform\" title=\"".  esc_html(__('Multiform','majestic-support')) ."\">". esc_html(__('Multiform','majestic-support')) ."</a></li>";
                }
                $customTitle = esc_html(__('Fields', 'majestic-support'));
                if(isset(majesticsupport::$_data['multiFormTitle'])){
                    $customTitle .= "<span class=\"msadmin-head-sub-text\">
                        ". ' ('.esc_html(majesticsupport::MJTC_getVarValue(majesticsupport::$_data["multiFormTitle"])).')' ."
                    </span>";
                }
                $title = __("Fields", 'majestic-support');
                break;
            case 'adduserfield':
                if(in_array('multiform', majesticsupport::$_active_addons)){
                    $breadCrumbs = "<li><a href=\"?page=majesticsupport_multiform\" title=\"".  esc_html(__('Multiform','majestic-support')) ."\">". esc_html(__('Multiform','majestic-support')) ."</a></li>";
                }
                $customTitle = isset(majesticsupport::$_data[0]['fieldvalues']) ? esc_html(__('Edit Field', 'majestic-support')) : esc_html(__('Add Field', 'majestic-support'));
                if(isset(majesticsupport::$_data['multiFormTitle'])){
                    $customTitle .= "<span class=\"msadmin-head-sub-text\">
                        ". ' ('.esc_html(majesticsupport::MJTC_getVarValue(majesticsupport::$_data["multiFormTitle"])).')' ."
                    </span>";
                }
                $title = __("Add Field", 'majestic-support');
                break;
            case 'admin_slug':
                $actionButton = "<a class=\"msadmin-add-link button\" title=\"". esc_html(__('reset','majestic-support')) ."\" href=\"". esc_url(admin_url("admin.php?page=majesticsupport_slug&task=resetallslugs&action=mstask")) ."\">
                    ". esc_html(__('Reset All','majestic-support')) ."
                </a>";
                $title = __("Slugs", 'majestic-support');
                break;
            case 'admin_tickets':
                $id='';
                if(in_array('multiform', majesticsupport::$_active_addons) && majesticsupport::$_config['show_multiform_popup'] == 1){
                    $id="id=multiformpopup";
                }
                $actionButton = "<a ".esc_attr($id)." title=\"". esc_html(__('Add', 'majestic-support'))."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_ticket&mjslay=addticket&formid=". esc_attr(MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId()) ."\"><img alt=\"". esc_html(__('Add', 'majestic-support'))."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Create Ticket', 'majestic-support'))."</a>";
                $title = __("Tickets", 'majestic-support');
                break;
            case 'ticketclosereason':
                $actionButton = "<a  title=\"". esc_html(__('Add', 'majestic-support'))."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_ticketclosereason&mjslay=addticketclosereason\"><img alt=\"". esc_html(__('Add', 'majestic-support'))."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Reason', 'majestic-support'))."</a>";
                $title = __("Ticket Close Reasons", 'majestic-support');
                break;
            case 'addticketclosereason':
                $title = __("Add Ticket Close Reason", 'majestic-support');
                break;
            case 'admin_export':
                $title = __("Export", 'majestic-support');
                break;
            case 'smartreply':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_smartreply&mjslay=addsmartreply\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Smart Reply', 'majestic-support')) ."</a>
                <a target=\"blank\" href=\"https://www.youtube.com/watch?v=YDYnagRWyEU\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to set smartreply', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                $title = __("Smart Replies", 'majestic-support');
                break;
            case 'addsmartreply':
                $title = __("Add Smart Reply", 'majestic-support');
                break;
            case 'admin_multiform':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_multiform&mjslay=addmultiform\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Multiform', 'majestic-support')) ."</a>
                <a target=\"blank\" href=\"https://www.youtube.com/watch?v=Z5-dKDt8DJ8\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to set multiforms', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                $title = __("Multiforms", 'majestic-support');
                break;
            case 'admin_addmultiform':
                $title = __("Add Multiform", 'majestic-support');
                break;
            case 'admin_staffs':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_agent&mjslay=addstaff\"><img alt=\"". esc_html(__('Add','majestic-support'))."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Agent', 'majestic-support')) ."</a>";
                $title = __("Agents", 'majestic-support');
                break;
            case 'admin_addstaffs':
                $title = __("Add Agent", 'majestic-support');
                break;
            case 'agentautoassign':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_agentautoassign&mjslay=addagentautoassign\"><img alt=\"". esc_html(__('Add','majestic-support'))."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Conditions', 'majestic-support')) ."</a>";
                $title = __("Conditions", 'majestic-support');
                break;
            case 'addagentautoassign':
                $breadCrumbs = "<li><a href=\"?page=majesticsupport_agentautoassign\" title=\"". esc_html(__('Conditions','majestic-support')) ."\">". esc_html(__('Conditions','majestic-support')) ."</a></li>";
                $title = __("Add Rule", 'majestic-support');
                break;
            case 'rolepermission':
                $customTitle = esc_html(majesticsupport::$_data[0]['role']->name) . " " . esc_html(__('Role Permission', 'majestic-support'));
                $title = __("Role Permission", 'majestic-support');
                break;
            case 'admin_staffpermissions':
                if (isset(majesticsupport::$_data[0])) {
                    $customTitle = esc_html(majesticsupport::$_data[0]['agent']->firstname) . " " .
                        esc_html(majesticsupport::$_data[0]['agent']->lastname) . " " . esc_html(__('Permissions', 'majestic-support'));
                }
                $title = __("Add Permissions", 'majestic-support');
                break;
            case 'configurations':
                $title = __("Settings", 'majestic-support');
                break;
            case 'cronjob':
                $title = __("Cron Job URLs", 'majestic-support');
                break;
            case 'shortcoses':
                $actionButton = "<a target=\"blank\" href=\"https://www.youtube.com/watch?v=PV-shw5Nr8Q\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to add Shortcode', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                $title = __("Short Codes", 'majestic-support');
                break;
            case 'themes':
                $actionButton = "<a target=\"blank\" href=\"https://www.youtube.com/watch?v=OZTabfsnVIQ\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to set colors', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                $title = __("Colors", 'majestic-support');
                break;
            case 'reports':
                $title = __("Reports", 'majestic-support');
                break;
            case 'overal_statistics':
                if(in_array('export', majesticsupport::$_active_addons)){
                    $actionButton = "<a title=\"". esc_html(__('Export Data', 'majestic-support')) ."\" id=\"jsexport-link\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_export&task=getoverallexport&action=mstask\"><img alt=\"". esc_html(__('Export', 'majestic-support'))."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/export-icon.png\" />". esc_html(__('Export Data', 'majestic-support')) ."</a>";
                }
                $title = __("Overall Statistics", 'majestic-support');
                break;
            case 'agent_reports':
                if(in_array('export', majesticsupport::$_active_addons)){
                    $t_name = 'getstaffmemberexport';
                    $link_export = admin_url('admin.php?page=majesticsupport_export&task='.esc_attr($t_name).'&action=mstask&uid='.esc_attr(majesticsupport::$_data['filter']['uid']).'&date_start='.esc_attr(majesticsupport::$_data['filter']['date_start']).'&date_end='.esc_attr(majesticsupport::$_data['filter']['date_end']));
                    $actionButton = "<a title=\"". esc_html(__('Export Data', 'majestic-support')) ."\" id=\"jsexport-link\" class=\"msadmin-add-link button\" href=\"".esc_url($link_export)."\"><img alt=\"". esc_html(__('Export', 'majestic-support'))."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/export-icon.png\" />". esc_html(__('Export Data', 'majestic-support')) ."</a>";
                }
                $title = __("Agent Reports", 'majestic-support');
                break;
            case 'agentdetail_reports':
                if(in_array('export', majesticsupport::$_active_addons)){
                    $t_name = 'getstaffmemberexportbystaffid';
                    $link_export = admin_url('admin.php?page=majesticsupport_export&task='.esc_attr($t_name).'&action=mstask&uid='.esc_attr(majesticsupport::$_data['filter']['uid']).'&date_start='.esc_attr(majesticsupport::$_data['filter']['date_start']).'&date_end='.esc_attr(majesticsupport::$_data['filter']['date_end']));
                    $actionButton = "<a title=\"". esc_html(__('Export Data', 'majestic-support')) ."\" id=\"jsexport-link\" class=\"msadmin-add-link button\" href=\"".esc_url($link_export)."\"><img alt=\"". esc_html(__('Export', 'majestic-support'))."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/export-icon.png\" />". esc_html(__('Export Data', 'majestic-support')) ."</a>";
                }
                $title = __("Agent Detail Report", 'majestic-support');
                break;
            case 'department_reports':
                if(in_array('export', majesticsupport::$_active_addons)){
                    $t_name = 'getdepartmentexport';
                    $link_export = admin_url('admin.php?page=majesticsupport_export&task='.esc_attr($t_name).'&action=mstask&date_start='.esc_attr(majesticsupport::$_data['filter']['date_start']).'&date_end='.esc_attr(majesticsupport::$_data['filter']['date_end']));
                    $actionButton = "<a title=\"". esc_html(__('Export Data', 'majestic-support')) ."\" id=\"jsexport-link\" class=\"msadmin-add-link button\" href=\"".esc_url($link_export)."\"><img alt=\"". esc_html(__('Export', 'majestic-support'))."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/export-icon.png\" />". esc_html(__('Export Data', 'majestic-support')) ."</a>";
                }
                $title = __("Department Reports", 'majestic-support');
                break;
            case 'departmentdetail_reports':
                if(in_array('export', majesticsupport::$_active_addons)){
                    $t_name = 'getdepartmentmemberexportbydepartmentid';
                    $link_export = admin_url('admin.php?page=majesticsupport_export&task='.esc_attr($t_name).'&action=mstask&id='.esc_attr(majesticsupport::$_data['filter']['id']).'&date_start='.esc_attr(majesticsupport::$_data['filter']['date_start']).'&date_end='.esc_attr(majesticsupport::$_data['filter']['date_end']));
                    $actionButton = "<a title=\"". esc_html(__('Export Data', 'majestic-support')) ."\" id=\"jsexport-link\" class=\"msadmin-add-link button\" href=\"".esc_url($link_export)."\"><img alt=\"". esc_html(__('Export', 'majestic-support'))."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/export-icon.png\" />". esc_html(__('Export Data', 'majestic-support')) ."</a>";
                }
                $title = __("Department Detail Report", 'majestic-support');
                break;
            case 'user_reports':
                if(in_array('export', majesticsupport::$_active_addons)){
                    $t_name = 'getusersexport';
                    $link_export = admin_url('admin.php?page=majesticsupport_export&task='.esc_attr($t_name).'&action=mstask&uid='.esc_attr(majesticsupport::$_data['filter']['uid']).'&date_start='.esc_attr(majesticsupport::$_data['filter']['date_start']).'&date_end='.esc_attr(majesticsupport::$_data['filter']['date_end']));
                    $actionButton = "<a title=\"". esc_html(__('Export Data', 'majestic-support')) ."\" id=\"jsexport-link\" class=\"msadmin-add-link button\" href=\"".esc_url($link_export)."\"><img alt=\"". esc_html(__('Export', 'majestic-support'))."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/export-icon.png\" />". esc_html(__('Export Data', 'majestic-support')) ."</a>";
                }
                $title = __("User Reports", 'majestic-support');
                break;
            case 'userdetail_reports':
                if(in_array('export', majesticsupport::$_active_addons)){
                    $t_name = 'getuserexportbyuid';
                    $link_export = admin_url('admin.php?page=majesticsupport_export&task='.esc_attr($t_name).'&action=mstask&uid='.esc_attr(majesticsupport::$_data['filter']['uid']).'&date_start='.esc_attr(majesticsupport::$_data['filter']['date_start']).'&date_end='.esc_attr(majesticsupport::$_data['filter']['date_end']));
                    $actionButton = "<a title=\"". esc_html(__('Export Data', 'majestic-support')) ."\" id=\"jsexport-link\" class=\"msadmin-add-link button\" href=\"".esc_url($link_export)."\"><img alt=\"". esc_html(__('Export', 'majestic-support'))."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/export-icon.png\" />". esc_html(__('Export Data', 'majestic-support')) ."</a>";
                }
                $title = __("User Detail Report", 'majestic-support');
                break;
            case 'satisfaction_reports':
                $title = __("Satisfaction Reports", 'majestic-support');
                break;
            case 'addemialpiping':
                $title = __("Add Email Piping", 'majestic-support');
                break;
            case 'emialpiping':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_emailpiping&mjslay=addticketviaemail\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Email Piping', 'majestic-support')) ."</a>";
                $title = __("Email Piping", 'majestic-support');
                break;
            case 'addgdpr':
                $title = __("Add GDPR Field", 'majestic-support');
                break;
            case 'gdpr':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_gdpr&mjslay=addgdprfield\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add GDPR Field', 'majestic-support')) ."</a>";
                $title = __("GDPR Fields", 'majestic-support');
                break;
            case 'erasedatarequests':
                $title = __("Erase Data Requests", 'majestic-support');
                break;
            case 'addonslist':
                $title = __("Add-ons List", 'majestic-support');
                break;
            case 'missingaddon':
                $title = __("Premium Addons", 'majestic-support');
                break;
            case 'step1':
                $title = __("Install Add-ons", 'majestic-support');
                break;
            case 'feedbacks':
                $title = __("Feedback", 'majestic-support');
                break;
            case 'adddepertment':
                $title = __("Add Department", 'majestic-support');
                break;
            case 'depertments':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_department&mjslay=adddepartment\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Depertment', 'majestic-support')) ."</a>";
                $title = __("Depertments", 'majestic-support');
                break;
            case 'addpriority':
                $title = __("Add Priority", 'majestic-support');
                break;
            case 'priorities':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_priority&mjslay=addpriority\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Priority', 'majestic-support')) ."</a>";
                $title = __("Priorities", 'majestic-support');
                break;
            case 'addcategory':
                $title = __("Add Category", 'majestic-support');
                break;
            case 'categories':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_knowledgebase&mjslay=addcategory\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Category', 'majestic-support')) ."</a>";
                $title = __("Categories", 'majestic-support');
                break;
            case 'addknowledgebase':
                $title = __("Add Knowledge Base", 'majestic-support');
                break;
            case 'knowledgebase':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_knowledgebase&mjslay=addarticle\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Knowledge base', 'majestic-support')) ."</a>
                <a target=\"blank\" href=\"https://www.youtube.com/watch?v=g6l5M8hR1hE\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to use Knowledge Base', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                $title = __("Knowledge Base", 'majestic-support');
                break;
            case 'adddownload':
                $title = __("Add Download", 'majestic-support');
                break;
            case 'downloads':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_download&mjslay=adddownload\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Download', 'majestic-support')) ."</a>
                <a target=\"blank\" href=\"#\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to use downloads', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                $title = __("Downloads", 'majestic-support');
                break;
            case 'addfaq':
                $title = __("Add FAQ", 'majestic-support');
                break;
            case 'faqs':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_faq&mjslay=addfaq\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add FAQ', 'majestic-support')) ."</a>
                <a target=\"blank\" href=\"#\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to create FAQ', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                $title = __("FAQs", 'majestic-support');
                break;
            case 'addannouncement':
                $title = __("Add Announcement", 'majestic-support');
                break;
            case 'announcements':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_announcement&mjslay=addannouncement\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Announcement', 'majestic-support')) ."</a>
                <a target=\"blank\" href=\"https://www.youtube.com/watch?v=UJv3-FdD0Fs\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to add announcement', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                $title = __("Announcements", 'majestic-support');
                break;
            case 'helptopics':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_helptopic&mjslay=addhelptopic\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Help Topics', 'majestic-support')) ."</a>
                <a target=\"blank\" href=\"https://www.youtube.com/watch?v=8mS5EWOUl7c\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to use help topic', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                $title = __("Help Topics", 'majestic-support');
                break;
            case 'addhelptopic':
                $title = __("Add Help Topic", 'majestic-support');
                break;
            case 'systememails':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_email&mjslay=addemail\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Email', 'majestic-support')) ."</a>
                <a target=\"blank\" href=\"#\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to set SMTP', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                $title = __("System Emails", 'majestic-support');
                break;
            case 'addsystememail':
                $title = __("Add Email", 'majestic-support');
                break;
            case 'permademessages':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_cannedresponses&mjslay=addpremademessage\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Premade Response', 'majestic-support')) ."</a>";
                $title = __("Premade Responses", 'majestic-support');
                break;
            case 'addpermademessage':
                $title = __("Add Premade Response", 'majestic-support');
                break;
            case 'roles':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_role&mjslay=addrole\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Role', 'majestic-support')) ."</a>";
                $title = __("Roles", 'majestic-support');
                break;
            case 'addrole':
                $title = __("Add Role", 'majestic-support');
                break;
            case 'inbox':
                $title = __("Mail", 'majestic-support');
                break;
            case 'outbox':
                $title = __("Mail", 'majestic-support');
                break;
            case 'form_message':
                $title = __("Compose", 'majestic-support');
                break;
            case 'message':
                $customTitle = esc_html(majesticsupport::$_data[0]['message']->subject);
                $title = __("Message", 'majestic-support');
                break;
            case 'baneemiallogs':
                $title = __("Banned Email Log List", 'majestic-support');
                break;
            case 'banned_emails':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_banemail&mjslay=addbanemail\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Banned Email', 'majestic-support')) ."</a>";
                $title = __("Banned Emails", 'majestic-support');
                break;
            case 'addbanned_email':
                $title = __("Add Banned Email", 'majestic-support');
                break;
            case 'emailcc':
                $actionButton = "<a title=\"". esc_html(__('Add','majestic-support')) ."\" class=\"msadmin-add-link button\" href=\"?page=majesticsupport_emailcc&mjslay=addemailcc\"><img alt=\"". esc_html(__('Add','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/add-icon.png\" />". esc_html(__('Add Email CC', 'majestic-support')) ."</a>
                <a target=\"blank\" href=\"#\" class=\"msadmin-video-link mjtc-cp-video-popup\" title=\"". esc_html(__('How to use email cc', 'majestic-support')) ."\">
                    <img alt=\"". esc_html(__('arrow','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/watch-video.png\"/>
                </a>";
                $title = __("Email CC", 'majestic-support');
                break;
            case 'addemailcc':
                $title = __("Add Email CC", 'majestic-support');
                break;
            case 'emailtemplates':
                $title = __("Email Templates", 'majestic-support');
                break;
            case 'admin_help':
                $title = __("Help", 'majestic-support');
                break;
            case 'admin_addons_status':
                $title = __("Addons Status", 'majestic-support');
                break;
            
        }
        $html = "<div class=\"msadmin-head-wrapper\">
            <div id=\"msadmin-wrapper-top\">
                <div id=\"msadmin-wrapper-top-left\">
                    <div id=\"msadmin-breadcrunbs\">
                        <ul>
                            <li><a href=\"".esc_url(admin_url('admin.php?page=majesticsupport'))."\" title=\"". esc_html(__('Dashboard','majestic-support')) ."\"><img alt=\"". esc_html(__('Configuration','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/homeicon-blue.png\" /></a></li>
                            ".$breadCrumbs;
                            if (!isset($hideBredcurms)) {
                                $html .= "<li>". esc_html($title)."</li>";
                            }
                        $html .= "</ul>
                    </div>
                </div>
                <div id=\"msadmin-wrapper-top-right\">
                    <div id=\"msadmin-config-btn\" class=\"msupportadmin-help-btn\">
                        <a href=\"". esc_url(admin_url("admin.php?page=majesticsupport&mjslay=help")) ."\" title=\"". esc_html(__('Help','majestic-support')) ."\">
                            <img alt=\"". esc_html(__('Help','majestic-support')) ."\" src=\"". esc_url(MJTC_PLUGIN_URL) ."includes/images/help.png\" />
                        </a>
                    </div>
                    <div id=\"msadmin-vers-txt\">
                        <span class=\"msadmin-ver\">". esc_html(MJTC_includer::MJTC_getModel('configuration')->getConfigValue('versioncode')) ."</span>
                    </div>
                </div>
            </div>";
            if (isset($customTitle)) {
                $title = $customTitle;
            }
            $html .= "<div id=\"msadmin-head\">
                <h1 class=\"msadmin-head-text\">". wp_kses($title, MJTC_ALLOWED_TAGS) ."</h1>
                ".wp_kses($actionButton, MJTC_ALLOWED_TAGS)."
            </div>
        </div>";
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }

    function getPageBreadcrumps($layouts){
        if (majesticsupport::$_config['show_breadcrumbs'] != 1)
            return false;
        $title = "";
        switch ($layouts) {
            case 'addrole':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'role', 'mjslay'=>'roles')))."\">
                        ". __("Roles",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add Role";
                break;
            case 'roles':
                $title = "Roles";
                break;
            case 'rolepermissions':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'role', 'mjslay'=>'roles')))."\">
                        ". __("Roles",'majestic-support') ."
                    </a>
                </span>";
                $title = "Role Permissions";
                break;
            case 'addagent':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent', 'mjslay'=>'staffs')))."\">
                        ". __("Agents",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add Agent";
                break;
            case 'agents':
                $title = "Agents";
                break;
            case 'mytickets':
                $title = "My Tickets";
                break;
            case 'agentpermissions':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent', 'mjslay'=>'staffs')))."\">
                        ". __("Agents",'majestic-support') ."
                    </a>
                </span>";
                $title = "Agent Permissions";
                break;
            case 'addticket':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent', 'mjslay'=>'staffmyticket')))."\">
                        ". __("My Tickets",'majestic-support') ."
                    </a>
                </span>";
                $title = "Submit Ticket";
                break;
            case 'ticketdetail':
                $breadcrumps = "<span>";
                    if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                        $breadcrumps .= "<a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'agent', 'mjslay'=>'staffmyticket')))."\">
                            ". __("My Tickets",'majestic-support')."
                        </a>";
                    } else {
                        $breadcrumps .= "<a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'myticket')))."\">
                            ". __("My Tickets",'majestic-support')."
                        </a>";
                    }
                    $breadcrumps .= "
                </span>";
                $title = "Ticket Detail";
                break;
            case 'addticketuser':
                $title = "Submit Ticket";
                break;
            case 'addknowledgebase':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'knowledgebase', 'mjslay'=>'stafflistarticles')))."\">
                        ". __("Knowledge Base",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add Knowledge Base";
                break;
            case 'knowledgebasearticles':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'knowledgebase', 'mjslay'=>'userknowledgebase')))."\">
                        ". __("Knowledge Base",'majestic-support') ."
                    </a>
                </span>";
                $title = "Knowledge Base Articles";
                break;
            case 'knowledgebasedetail':
                $breadcrumps = "<span>";
                    if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                        $breadcrumps .= "<a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'knowledgebase', 'mjslay'=>'stafflistarticles')))."\">
                            ". __("Knowledge Base",'majestic-support')."
                        </a>";
                    } else {
                        $breadcrumps .= "<a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'knowledgebase', 'mjslay'=>'userknowledgebase')))."\">
                            ". __("Knowledge Base",'majestic-support')."
                        </a>";
                    }
                    $breadcrumps .= "
                </span>";
                $title = "Knowledge Base Detail";
                break;
            case 'knowledgebase':
                $title = "Knowledge Base";
                break;
            case 'categories':
                $title = "Categories";
                break;
            case 'addcategory':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'knowledgebase', 'mjslay'=>'stafflistcategories')))."\">
                        ". __("Categories",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add Category";
                break;
            case 'addannouncement':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'announcement', 'mjslay'=>'staffannouncements')))."\">
                        ". __("Announcements",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add Announcement";
                break;
            case 'announcements':
                $title = "Announcements";
                break;
            case 'announcementdetail':
                $breadcrumps = "<span>";
                    if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                        $breadcrumps .= "<a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'announcement', 'mjslay'=>'staffannouncements')))."\">
                            ". __("Announcements",'majestic-support')."
                        </a>";
                    } else {
                        $breadcrumps .= "<a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'announcement', 'mjslay'=>'announcements')))."\">
                            ". __("Announcements",'majestic-support')."
                        </a>";
                    }
                    $breadcrumps .= "
                </span>";
                $title = "Announcement Detail";
                break;
            case 'faqdetail':
                $breadcrumps = "<span>";
                    if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                        $breadcrumps .= "<a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'faq', 'mjslay'=>'stafffaqs')))."\">
                            ". __("FAQs",'majestic-support')."
                        </a>";
                    } else {
                        $breadcrumps .= "<a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'faq', 'mjslay'=>'faqs')))."\">
                            ". __("FAQs",'majestic-support')."
                        </a>";
                    }
                    $breadcrumps .= "
                </span>";
                $title = "FAQ Detail";
                break;
            case 'faqs':
                $title = "FAQs";
                break;
            case 'addfaqs':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'faq', 'mjslay'=>'stafffaqs')))."\">
                        ". __("FAQs",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add FAQs";
                break;
            case 'addhelptopic':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'helptopic', 'mjslay'=>'agenthelptopics')))."\">
                        ". __("Help Topics",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add Help Topic";
                break;
            case 'helptopics':
                $title = "Help Topics";
                break;
            case 'addannouncement':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'announcement', 'mjslay'=>'staffannouncements')))."\">
                        ". __("Announcements",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add Department";
                break;
            case 'announcements':
                $title = "Announcements";
                break;
            case 'adddepartment':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'department', 'mjslay'=>'departments')))."\">
                        ". __("Departments",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add Department";
                break;
            case 'departments':
                $title = "Departments";
                break;
            case 'userdata':
                $title = "User Data";
                break;
            case 'login':
                $title = "Login";
                break;
            case 'register':
                $title = "Register";
                break;
            case 'departmentreports':
                $title = "Department Reports";
                break;
            case 'agentdetailreport':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'reports', 'mjslay'=>'staffreports')))."\">
                        ". __("Agent Reports",'majestic-support') ."
                    </a>
                </span>";
                $title = "Agent detail Report";
                break;
            case 'agentreports':
                $title = "Agent Reports";
                break;
            case 'addsmartreply':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'smartreply', 'mjslay'=>'smartreplies')))."\">
                        ". __("Smart Replies",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add Smart Reply";
                break;
            case 'smartreplies':
                $title = "Smart Replies";
                break;
            case 'addemail':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'banemail', 'mjslay'=>'banemails')))."\">
                        ". __("Banned Emails",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add Email";
                break;
            case 'bannedemails':
                $title = "Banned Emails";
                break;
            case 'addpremaderesponse':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'cannedresponses', 'mjslay'=>'agentcannedresponses')))."\">
                        ". __("Premade Responses",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add Premade Response";
                break;
            case 'premaderesponse':
                $title = "Premade Response";
                break;
            case 'adddownload':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'download', 'mjslay'=>'staffdownloads')))."\">
                        ". __("Downloads",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add Download";
                break;
            case 'downloads':
                $title = "Downloads";
                break;
            case 'addreasons':
                $breadcrumps = "<span>
                    <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticketclosereason', 'mjslay'=>'ticketclosereasons')))."\">
                        ". __("Reasons",'majestic-support') ."
                    </a>
                </span>";
                $title = "Add Reasons";
                break;
            case 'formfeedback':
                $title = "Form Feedback";
                break;
            case 'feedbacks':
                $title = "Feedbacks";
                break;
            case 'export':
                $title = "Export";
                break;
            case 'downloads':
                $title = "Downloads";
                break;
            case 'ticketstatus':
                $title = "Ticket Status";
                break;
            case 'mail':
                $title = "Mail";
                break;
            case 'message':
                $title = "Message";
                break;
            case 'ticketclosereasons':
                $title = "Ticket Close Reasons";
                break;
            case 'myprofile':
                $title = "My Profile";
                break;
            case 'admin_addons_status':
                $title = "Addons Status";
                break;
            
        }
        $html = "
        <div class=\"mjtc-support-breadcrumps\">
            <a href=\"". esc_url(majesticsupport::makeUrl(array('mjsmod'=>'majesticsupport', 'mjslay'=>'controlpanel')))."\">
                <img alt=\"". esc_html(__('image', 'majestic-support'))."\" src=\"". esc_url(MJTC_PLUGIN_URL)."includes/images/home-icon.png\" />
            </a>";
            if (isset($breadcrumps)) {
                $html .= $breadcrumps;
            }
            $html .= "<span>". esc_html(majesticsupport::MJTC_getVarValue($title)) ."</span>
        </div>";
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }

    function getSanitizedEditorData($data){
        $data = wp_filter_post_kses($data);
        if ($data != null){
            $data = MJTC_majesticsupportphplib::MJTC_stripslashes($data);
        }
        return $data;
    }

    function getEncriptedSiteLink(){
        $siteLink = get_option('ms_encripted_site_link');
        if ($siteLink == '') {
            include_once MJTC_PLUGIN_PATH . 'includes/encoder.php';
            $encoder = new MJTC_encoder();
            $siteLink = $encoder->MJTC_encrypt(get_site_url());
            update_option('ms_encripted_site_link', $siteLink);
        }
       return $siteLink;
    }

    function msremovetags($message){
        if(MJTC_majesticsupportphplib::MJTC_strpos($message, '<script>') !== false || MJTC_majesticsupportphplib::MJTC_strpos($message, '</script>') !== false){ // check and remove script tag from the message
            if($message != ''){
                $message = MJTC_majesticsupportphplib::MJTC_str_replace('<script>','&lt;script&gt;', $message);
                $message = MJTC_majesticsupportphplib::MJTC_str_replace('</script>','&lt;/script&gt;', $message);
            }
        }
        return $message;
    }
    
    function showUpdateAvaliableAlert(){    
        require_once MJTC_PLUGIN_PATH.'includes/addon-updater/msupdater.php';
        $MJTC_SUPPORTTICKETUpdater  = new MJTC_SUPPORTTICKETUpdater();
        $cdnversiondata = $MJTC_SUPPORTTICKETUpdater->MJTC_getPluginVersionDataFromCDN();
        $not_installed = array();
        $majesticsupport_addons = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_getAddonsArray();
        $installed_plugins = get_plugins();
        $count = 0;
        foreach ($majesticsupport_addons as $key1 => $value1) {
            $matched = 0;
            $version = "";
            foreach ($installed_plugins as $name => $value) {
                $install_plugin_name = MJTC_majesticsupportphplib::MJTC_str_replace(".php","",MJTC_majesticsupportphplib::MJTC_basename($name));
                if($key1 == $install_plugin_name){
                    $matched = 1;
                    $version = $value["Version"];
                    $install_plugin_matched_name = $install_plugin_name;
                }
            }
            if($matched == 1){ //installed
                $name = $key1;
                $title = $value1['title'];
                $img = MJTC_majesticsupportphplib::MJTC_str_replace("majestic-support-", "", $key1).'.png';
                $cdnavailableversion = "";
                foreach ($cdnversiondata as $cdnname => $cdnversion) {
                    $install_plugin_name_simple = MJTC_majesticsupportphplib::MJTC_str_replace("-", "", $install_plugin_matched_name);
                    if($cdnname == MJTC_majesticsupportphplib::MJTC_str_replace("-", "", $install_plugin_matched_name)){
                        if($cdnversion > $version){ // new version available
                            $count++;
                        }
                    }    
                }
            }
        }
        return $count;
    }
}

?>
