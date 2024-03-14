<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_replyModel {

    function getReplies($id) {
        if (!is_numeric($id))
            return false;
        // Data

        do_action('reset_ms_aadon_query');
        do_action('ms_aadon_getreplies');// to prepare any addon based query (action is defined in two addons)
        $query = "SELECT replies.*,replies.id AS replyid,user.user_email AS useremail,tickets.id ".majesticsupport::$_addon_query['select']."
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_replies` AS replies
                    JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS tickets ON  replies.ticketid = tickets.id
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_users` AS user ON  replies.uid = user.id
                    ".majesticsupport::$_addon_query['join']."
                    WHERE tickets.id = " . esc_sql($id) . " ORDER By replies.id ASC";
        majesticsupport::$_data[4] = majesticsupport::$_db->get_results($query);
        do_action('reset_ms_aadon_query');
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        $attachmentmodel = MJTC_includer::MJTC_getModel('attachment');
        foreach (majesticsupport::$_data[4] AS $reply) {
            $reply->attachments = $attachmentmodel->getAttachmentForReply($reply->id, $reply->replyid);
        }
        return;
    }

    function getTicketNameForReplies() {
        $query = "SELECT id, ticketid AS text FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets`";
        $list = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $list;
    }

    function getRepliesForForm($id) {
        if ($id) {
            if (!is_numeric($id))
                return false;
            $query = "SELECT replies.*,tickets.id
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_replies` AS replies
                        JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS tickets ON  replies.ticketid = tickets.id
                        WHERE replies.id = " . esc_sql($id);
            majesticsupport::$_data[0] = majesticsupport::$_db->get_row($query);
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            }
        }
        return;
    }

    function storeReplies($data) {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-reply') ) {
            die( 'Security check Failed' );
        }
        $checkduplicatereplies = $this->checkIsReplyDuplicate($data);
        if(!$checkduplicatereplies){
            return false;
        }
        //validate reply for break down
        $ticketid   = $data['ticketrandomid'];
        $internalid   = $data['internalid'];
        $hash       = $data['hash'];
        $query = "SELECT id FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE ticketid='".esc_sql($ticketid)."'
        AND IF(`hash` is NULL,true,`hash`='".esc_sql($hash)."') ";
        $id = majesticsupport::$_db->get_var($query);
        if($id != $data['ticketid']){
            return;
        }//end

        $ticketviaemailstaffid = 0;
        // set in Email Piping
        if(isset($data['staffid'])){
            $ticketviaemailstaffid = $data['staffid'];
            unset($data['staffid']);
        }
        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $allowed = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Reply Ticket');
            if ($allowed != true) {
                MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed', 'majestic-support')), 'error');
                return;
            }
        } else if (!MJTC_includer::MJTC_getModel('ticket')->validateTicketAction($id, $internalid)) {
            MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed','majestic-support')), 'error');
            return false;
        }
        // check whether ticket is closed or not incase of ticket viw email
        if(isset($data['ticketviaemail']) && $data['ticketviaemail'] == 1){
            if(majesticsupport::$_config['reply_to_closed_ticket'] != 1){
                $closed = MJTC_includer::MJTC_getModel('ticket')->checkActionStatusSame($data['ticketid'],array('action' => 'closeticket'));
                if($closed == false){
                    MJTC_includer::MJTC_getModel('email')->sendMail(1, 14, $data['ticketid']); // Mailfor, Reply Ticket
                    return;
                }
                // check this ticket is not assign to any one
                if( MJTC_includer::MJTC_getModel('ticket')->isTicketAssigned($data['ticketid']) == false){
                    // if not assigned then assign to me
                    $data['assigntome'] = 1;
                }
            }
        }
        $sendEmail = true;
        $staffid = 0;
        if (!MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
            //$current_user = get_userdata(MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid());
            $currentUserName = MJTC_includer::MJTC_getObjectClass('user')->MJTC_fullname();
            if( in_array('agent',majesticsupport::$_active_addons) ){
                //$staffid = MJTC_includer::MJTC_getModel('agent')->getStaffId($current_user->ID);
				$staffid = MJTC_includer::MJTC_getModel('agent')->getStaffId(MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid());
            }
        } else {
            $currentUserName = '';
        }

        if($staffid == 0 && $ticketviaemailstaffid != 0){
            $staffid = $ticketviaemailstaffid;
        }

        //check the assign to me on reply
        if (isset($data['assigntome']) && $data['assigntome'] == 1) {
            MJTC_includer::MJTC_getModel('ticket')->ticketAssignToMe($data['ticketid'], $staffid);
        }
        if(isset($data['ticketviaemail'])){
            if($data['ticketviaemail'] == 1)
                $currentUserName = $data['name'];
        }
        $data['id'] = isset($data['id']) ? $data['id'] : '';
        $data['status'] = isset($data['status']) ? $data['status'] : '';
        $data['closeonreply'] = isset($data['closeonreply']) ? $data['closeonreply'] : '';
        $data['ticketviaemail'] = isset($data['ticketviaemail']) ? $data['ticketviaemail'] : 0;
        $tempmessage = $data['mjsupport_message'];
        $data = majesticsupport::MJTC_sanitizeData($data);// MJTC_sanitizeData() function uses wordpress santize functions
        if(isset($data['ticketviaemail']) && $data['ticketviaemail'] == 1){
            $data['message'] = $tempmessage;
        }else{
            $data['message'] = MJTC_includer::MJTC_getModel('majesticsupport')->getSanitizedEditorData($_POST['mjsupport_message']);
        }
        if(empty($data['message'])){
            MJTC_message::MJTC_setMessage(esc_html(__('Message field cannot be empty', 'majestic-support')), 'error');
            return false;
        }
        //check signature
        if (!isset($data['nonesignature'])) {
            if (isset($data['ownsignature']) && $data['ownsignature'] == 1) {
                if (is_admin()) {
                    $data['message'] .= '<br/>' . get_user_meta(MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid(), 'ms_signature', true);
                } elseif(in_array('agent',majesticsupport::$_active_addons)) {
                    $data['message'] .= '<br/>' . MJTC_includer::MJTC_getModel('agent')->getMySignature();
                }
            }
            if (isset($data['departmentsignature']) && $data['departmentsignature'] == 1) {
                $data['message'] .= '<br/>' . MJTC_includer::MJTC_getModel('department')->getSignatureByID($data['departmentid']);
            }
        }

        $data['created'] = date_i18n('Y-m-d H:i:s');
        $data['name'] = $currentUserName;
        $data['staffid'] = $staffid;

        $row = MJTC_includer::MJTC_getTable('replies');

        $data = MJTC_includer::MJTC_getModel('majesticsupport')->stripslashesFull($data);// remove slashes with quotes.
        $error = 0;
        if (!$row->bind($data)) {
            $error = 1;
        }
        if (!$row->store()) {
            $error = 1;
        }

        if ($error == 0) {
            $replyid = $row->id;
            // smart reply store
            if (isset($data['add_smartreply']) && $data['add_smartreply'] == 1) {
                $samrtreplyTitle = MJTC_includer::MJTC_getModel('ticket')->getTicketSubjectById($data['ticketid']);
                $samrtreply['id'] = '';
                $samrtreply['title'] = $samrtreplyTitle;
                $samrtreply['ticketsubjects'][0] = $samrtreplyTitle;
                $samrtreply['reply'] = $data['message'];
                MJTC_includer::MJTC_getModel('smartreply')->storeSmartReply($samrtreply);
            }
            //tickets attachments store
            $data['replyattachmentid'] = $replyid;
            MJTC_includer::MJTC_getModel('attachment')->storeAttachments($data);
            //reply stored change action
            if (is_admin()){
                MJTC_includer::MJTC_getModel('ticket')->setStatus(3, $data['ticketid']); // 3 -> waiting for customer reply
                if(in_array('timetracking', majesticsupport::$_active_addons)){
                    MJTC_includer::MJTC_getModel('timetracking')->storeTimeTaken($data,$replyid,1);// to store time for reply 1 is to identfy that current record is reply
                }
            }else {
                if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()){
                    MJTC_includer::MJTC_getModel('ticket')->setStatus(3, $data['ticketid']); // 3 -> waiting for customer reply
                    $data['staffid'] = $staffid;
                    if(in_array('timetracking', majesticsupport::$_active_addons)){
                        MJTC_includer::MJTC_getModel('timetracking')->storeTimeTaken($data,$replyid,1);// to store time for reply 1 is to identfy that current record is reply
                    }

                }else{
                    MJTC_includer::MJTC_getModel('ticket')->setStatus(1, $data['ticketid']); // 1 -> waiting for admin/staff reply
                }
            }
            MJTC_includer::MJTC_getModel('ticket')->updateLastReply($data['ticketid']);
            MJTC_message::MJTC_setMessage(esc_html(__('Reply posted', 'majestic-support')), 'updated');
            $messagetype = esc_html(__('Successfully', 'majestic-support'));

            // Reply notification
            if(in_array('notification', majesticsupport::$_active_addons)){
                // Get Ticket Staffid
                $ticketstaffid = MJTC_includer::MJTC_getModel('ticket')->getStaffIdById($data['ticketid']);
                $ticketuid = MJTC_includer::MJTC_getModel('ticket')->getUIdById($data['ticketid']);

                // to admin
                $dataarray = array();
                $dataarray['title'] = esc_html(__("Reply posted on ticket",'majestic-support'));
                $dataarray['body'] =  MJTC_includer::MJTC_getModel('ticket')->getTicketSubjectById($data['ticketid']);

                // To admin
                $devicetoken = MJTC_includer::MJTC_getModel('notification')->checkSubscriptionForAdmin();
                if($devicetoken){
                    $dataarray['link'] = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=".esc_sql($data['ticketid']));
                    $dataarray['devicetoken'] = $devicetoken;
                    $value = majesticsupport::$_config[MJTC_majesticsupportphplib::MJTC_md5(MSTN)];
                    if($value != ''){
                      do_action('send_push_notification',$dataarray);
                    }else{
                      do_action('resetnotificationvalues');
                    }
                }

                $dataarray['link'] = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail', "majesticsupportid"=>$data['ticketid'],'mspageid'=>majesticsupport::getPageid()));
                if($ticketuid != 0 && ($ticketuid != MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid())){
                    $devicetoken = MJTC_includer::MJTC_getModel('notification')->getUserDeviceToken($ticketuid);
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

                if($ticketstaffid != 0 && ($ticketuid != $staffid)){
                    $devicetoken = MJTC_includer::MJTC_getModel('notification')->getUserDeviceToken($ticketstaffid);
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
                if($ticketuid == 0){ // for visitor
                    $tokenarray['emailaddress'] = MJTC_includer::MJTC_getModel('ticket')->getTicketEmailById($data['ticketid']);
                    $tokenarray['trackingid'] = MJTC_includer::MJTC_getModel('ticket')->getTrackingIdById($data['ticketid']);
                    $tokenarray['sitelink']=MJTC_includer::MJTC_getModel('majesticsupport')->getEncriptedSiteLink();
                    $token = json_encode($tokenarray);
                    include_once MJTC_PLUGIN_PATH . 'includes/encoder.php';
                    $encoder = new MJTC_encoder();
                    $encryptedtext = $encoder->MJTC_encrypt($token);
                    $dataarray['link'] = majesticsupport::makeUrl(array('mjsmod'=>'ticket' ,'task'=>'showticketstatus','action'=>'mstask','token'=>$encryptedtext,'mspageid'=>majesticsupport::getPageid()));
                    $notificationid = MJTC_includer::MJTC_getModel('ticket')->getNotificationIdById($data['ticketid']);
                    $devicetoken = MJTC_includer::MJTC_getModel('notification')->getUserDeviceToken($notificationid,0);
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
            // End notification
        }else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Reply posted', 'majestic-support')), 'error');
            $messagetype = esc_html(__('Error', 'majestic-support'));
            $sendEmail = false;
        }

        /* for activity log */
        $ticketid = $data['ticketid']; // get the ticket id
        $current_user = MJTC_includer::MJTC_getObjectClass('user')->MJTC_getMSCurrentUser(); // to get current user name
        $currentUserName = isset($current_user->display_name) ? $current_user->display_name : esc_html(__('Guest', 'majestic-support'));
        $eventtype = 'REPLIED_TICKET';
        $message = esc_html(__('Ticket is replied by', 'majestic-support')) . " ( " . $currentUserName . " ) ";
        if(in_array('tickethistory', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('tickethistory')->addActivityLog($ticketid, 1, $eventtype, $message, $messagetype);
        }

        // Send Emails
        if ($sendEmail == true) {
            if (is_admin()) {
                MJTC_includer::MJTC_getModel('email')->sendMail(1, 4, $ticketid); // Mailfor, Reply Ticket
            } else {
                MJTC_includer::MJTC_getModel('email')->sendMail(1, 5, $ticketid); // Mailfor, Reply Ticket
            }
            $ticketreplyobject = majesticsupport::$_db->get_row("SELECT * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_replies` WHERE id = " . esc_sql($replyid));
            do_action('ms-ticketreply', $ticketreplyobject);
        }
        // if Close on reply is cheked
        if ($data['closeonreply'] == 1) {
            MJTC_includer::MJTC_getModel('ticket')->closeTicket($ticketid, $internalid);
        }

        return;
    }

    function checkIsReplyDuplicate($data){
        if(empty($data)) return false;
        
        $curdate = date_i18n('Y-m-d H:i:s');
        $inquery = '';
        if (isset($data['ticketviaemail']) && $data['ticketviaemail'] == 1) {
            $inquery .= " AND ticketviaemail = 1";
        }
        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_replies` WHERE ticketid = '" . esc_sql($data['ticketid']) . "' AND uid = '" . esc_sql($data['uid']) . "' ORDER BY created DESC LIMIT 1";
        $query .= $inquery;
        $datetime = majesticsupport::$_db->get_var($query);
        if($datetime){
            $diff = MJTC_majesticsupportphplib::MJTC_strtotime($curdate) - MJTC_majesticsupportphplib::MJTC_strtotime($datetime);
            if($diff <= 7){
                return false;
            }
        }
        return true;
    }

    function getLastReply($ticketid) {
        if (!is_numeric($ticketid))
            return false;
        $query = "SELECT created FROM `" . majesticsupport::$_db->prefix . "mjtc_support_replies` WHERE ticketid =  " . esc_sql($ticketid) . " ORDER BY created desc";
        $lastreply = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
        }
        return $lastreply;
    }

    function removeTicketReplies($ticketid) {
        if(!is_numeric($ticketid)) return false;
        majesticsupport::$_db->delete(majesticsupport::$_db->prefix . 'mjtc_support_replies', array('ticketid' => $ticketid));
        return;
    }

    function getReplyDataByID() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-reply-data-by-id') ) {
            die( 'Security check Failed' );
        }
        $replyid = MJTC_request::MJTC_getVar('val');
        if(!is_numeric($replyid)) return false;
        $query = "SELECT reply.id AS replyid, reply.message AS message
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_replies` AS reply
                    WHERE reply.id =  " . esc_sql($replyid) ;
        $lastreply = majesticsupport::$_db->get_row($query);
        // $lastreply = MJTC_majesticsupportphplib::MJTC_htmlentities(($lastreply));

        return json_encode($lastreply);
    }

    function getAttachmentByReplyId($id){
        if(!is_numeric($id)) return false;
        $query = "SELECT attachment.filename , ticket.attachmentdir
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_attachments` AS attachment
                    JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket ON ticket.id = attachment.ticketid AND attachment.replyattachmentid = ".esc_sql($id) ;
        $replyattachments = majesticsupport::$_db->get_results($query);
        return $replyattachments;
    }

    function editReply($data) {
        if (empty($data))
            return false;
        $desc = wpautop(wptexturize(MJTC_majesticsupportphplib::MJTC_stripslashes($data['mjsupport_replytext']))); // use mjsupport_message to avoid conflict

        $row = MJTC_includer::MJTC_getTable('replies');
        if (!$row->update(array('id' => $data['reply-replyid'], 'message' => $desc))) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return;
    }

    function storeMergeTicketReplies($reply,$ticketid){
        if(!is_string($reply))
            return false;
        $id          = $ticketid;
        $user_id        = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
        $username       = MJTC_includer::MJTC_getModel('majesticsupport')->getUserNameById($user_id);
        $query_array    = array(
            'uid'       => $user_id,
            'ticketid'  => $id,
            'name'      => $username,
            'message'   => $reply,
            'status'    => 1,
            'created'   => date_i18n('Y-m-d H:i:s'),
        );
        majesticsupport::$_db->replace(majesticsupport::$_db->prefix . 'mjtc_support_replies', $query_array);
        if (majesticsupport::$_db->last_error == null) {
            MJTC_message::MJTC_setMessage(esc_html(__('Reply Has been Posted', 'majestic-support')), 'updated');
            $messagetype = esc_html(__('Successfully', 'majestic-support'));
        }else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Reply Has Not been Posted', 'majestic-support')), 'error');
            $messagetype = esc_html(__('Error', 'majestic-support'));
        }
    }

    function getTicketLastReplyById($ticketid) {
        if (!is_numeric($ticketid))
            return false;
        $query = "SELECT message FROM `" . majesticsupport::$_db->prefix . "mjtc_support_replies` WHERE ticketid =  " . esc_sql($ticketid) . " ORDER BY created desc LIMIT 1";
        $lastreply = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
        }
        return $lastreply;
    }
}

?>
