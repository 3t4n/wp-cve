<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_emailModel {
    /*
      $mailfor
      For which purpose you want to send mail
      1 => Ticket

      $action
      For which action of $mailfor you want to send the mail
      1 => New Ticket Create
      2 => Close Ticket
      3 => Delete Ticket
      4 => Reply Ticket (Admin/Staff Member)
      5 => Reply Ticket (Ticket member)
      6 => Lock Ticket

      $id
      id required when recever emailaddress is stored in record
     */

    function sendMail($mailfor, $action, $id = null, $tablename = null) {
        if (!is_numeric($mailfor))
            return false;
        if (!is_numeric($action))
            return false;
        if ($id != null)
            if (!is_numeric($id))
                return false;
        $pageid = majesticsupport::getPageid();
        switch ($mailfor) {
            case 1: // Mail For Tickets
                switch ($action) {
                    case 1: // New Ticket Created
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        if (isset($ticketRecord->name) && isset($ticketRecord->subject) && isset($ticketRecord->ticketid) && isset($ticketRecord->email)) {
                        $Username = $ticketRecord->name;
                        $Subject = $ticketRecord->subject;
                        $TrackingId = $ticketRecord->ticketid;
                        $Email = $ticketRecord->email;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', majesticsupport::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Message = $ticketRecord->message;
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{USERNAME}' => $Username,
                            '{SUBJECT}' => $Subject,
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{EMAIL}' => $Email,
                            '{MESSAGE}' => $Message,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{CURRENT_YEAR}' => date('Y')
                        );

                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;

                        // New ticket mail to admin
                        if(majesticsupport::$_config['new_ticket_mail_to_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','ticket-new-admin' , $adminEmail ,'');
                            if($template == '' && empty($template)){
                                $template = $this->getTemplateForEmail('ticket-new-admin');
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $link = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($id));
                            $matcharray['{TICKETURL}'] = $link;
                            $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###admin####" />';
                            $msgBody .= '<span style="display:none;" ticketid:' . esc_attr($TrackingId) . '###admin#### ></span>';
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'ticket-new-admin');
                        }
                        //Check to send email to department
                        $query = "SELECT dept.sendmail, email.email AS emailaddress
                                    FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` AS ticket
                                    LEFT JOIN `".majesticsupport::$_db->prefix."mjtc_support_departments` AS dept ON dept.id = ticket.departmentid
                                    LEFT JOIN `".majesticsupport::$_db->prefix."mjtc_support_email` AS email ON email.id = dept.emailid
                                    WHERE ticket.id = ".esc_sql($id);
                        $dept_result = majesticsupport::$_db->get_row($query);
                        if($dept_result){
                            if(isset($dept_result->sendmail) && $dept_result->sendmail == 1){
                                $deptemail = $dept_result->emailaddress;
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','ticket-new-admin' , $deptemail ,'');
                                if($template == '' && empty($template)){
                                    $template = $this->getTemplateForEmail('ticket-new-admin');
                                }

                                $msgSubject = $template->subject;
                                $msgBody = $template->body;

                                $link = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($id));
                                $matcharray['{TICKETURL}'] = $link;
                                $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###admin####" />';
                                $msgBody .= '<span style="display:none;" ticketid:' . esc_attr($TrackingId) . '###admin#### ></span>';
                                $attachments = '';
                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($deptemail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'ticket-new-admin');
                            }
                        }
                        // New ticket mail to User
                        $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','ticket-new' , $ticketRecord->email , $ticketRecord->uid);
                        if($template == '' && empty($template)){
                            $template = $this->getTemplateForEmail('ticket-new');
                        }
                        //Parsing template
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        //token encrption
                        $tokenarray['emailaddress']=$Email;
                        $tokenarray['trackingid']=$TrackingId;
                        $tokenarray['sitelink']=MJTC_includer::MJTC_getModel('majesticsupport')->getEncriptedSiteLink();
                        $token = json_encode($tokenarray);
                        include_once MJTC_PLUGIN_PATH . 'includes/encoder.php';
                        $encoder = new MJTC_encoder();
                        $encryptedtext = $encoder->MJTC_encrypt($token);
                        // end token encryotion
                        $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'task'=>'showticketstatus','action'=>'mstask','token'=>$encryptedtext,'mspageid'=>majesticsupport::getPageid())));
                        $matcharray['{TICKETURL}'] = $link;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###user####" />';
                        $msgBody .= '<span style="display:none;" ticketid:' . esc_attr($TrackingId) . '###user#### ></span>';
                        $attachments = '';
                        $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);

                        //New ticket mail to staff member
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['new_ticket_mail_to_staff_members'] == 1) {
                            // Get All Staff member of the department of Current Ticket
                            if ( in_array('agentautoassign',majesticsupport::$_active_addons) && isset(majesticsupport::$_config['department_email_on_ticket_create']) && majesticsupport::$_config['department_email_on_ticket_create'] == 2) {
                                $agentmembers = MJTC_includer::MJTC_getModel('agentautoassign')->getAllStaffMemberByDepId($ticketRecord->departmentid);
                            }
                            else{
                                $agentmembers = MJTC_includer::MJTC_getModel('agent')->getAllStaffMemberByDepId($ticketRecord->departmentid);
                            }
                            if(is_array($agentmembers) && !empty($agentmembers)){
                                foreach ($agentmembers AS $agent) {
                                    if($agent->canemail == 1){
                                        $staffuid = $agent->staffuid;
                                        if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('New Ticket Notification', $staffuid) == 1) {
                                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','ticket-staff' , $agent->email , $staffuid);
                                            if($template == '' && empty($template)){
                                                $template = $this->getTemplateForEmail('ticket-staff');
                                            }

                                            $msgSubject = $template->subject;
                                            $msgBody = $template->body;
                                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));
                                            $matcharray['{TICKETURL}'] = $link;
                                            $this->replaceMatches($msgSubject, $matcharray);
                                            $this->replaceMatches($msgBody, $matcharray);
                                            $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###" />';
                                            $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###staff####" />';
                                            $msgBody .= '<span style="display:none;" ticketid:' . esc_attr($TrackingId) . '###staff#### ></span>';
                                            $attachments = '';
                                            $this->sendEmail($agent->email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'ticket-staff');
                                        }
                                    }
                                }
                            }
                        }
                        }
                        break;
                    case 2: // Close Ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $Username = $ticketRecord->name;
                        $Subject = $ticketRecord->subject;
                        $TrackingId = $ticketRecord->ticketid;
                        $Email = $ticketRecord->email;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', majesticsupport::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Message = $ticketRecord->message;
                        $ticketHistory = $this->getTicketReplyHistory($id);
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{USERNAME}' => $Username,
                            '{SUBJECT}' => $Subject,
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{EMAIL}' => $Email,
                            '{MESSAGE}' => $Message,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{TICKET_HISTORY}' => $ticketHistory,
                            '{CURRENT_YEAR}' => date('Y')

                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('close-tk');
                        // Close ticket mail to admin
                        if (majesticsupport::$_config['ticket_close_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);

                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','close-tk' , $adminEmail ,'');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }

                            $link = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($id));
                            $matcharray['{TICKETURL}'] = $link;
                            $matcharray['{FEEDBACKURL}'] = ' ';
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'close-tk-admin');
                        }
                        // Close ticket mail to staff member
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['ticket_close_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $staffuid = $this->getStaffUidByStaffId($ticketRecord->staffid);
                            if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','close-tk' , $agentEmail ,$staffuid);
                                if($template == '' && empty($template)){
                                    $template = $defaulttemplate;
                                }

                                $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));
                                $matcharray['{TICKETURL}'] = $link;
                                $matcharray['{FEEDBACKURL}'] = ' ';
                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $attachments = '';
                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'close-tk-staff');
                            }
                        }
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticket_close_user'] == 1) {
                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));
                            $tokenarray['emailaddress']=$Email;
                            $tokenarray['trackingid']=$TrackingId;
                            $token = json_encode($tokenarray);
                            include_once MJTC_PLUGIN_PATH . 'includes/encoder.php';
                            $encoder = new MJTC_encoder();
                            $encryptedtext = $encoder->MJTC_encrypt($token);
                            if(in_array('feedback', majesticsupport::$_active_addons)){
                                $flink = "<a href=" . esc_url(majesticsupport::makeUrl(array('mjsmod'=>'feedback', 'task'=>'showfeedbackform','action'=>'mstask','token'=>$encryptedtext,'mspageid'=>majesticsupport::getPageid()))) . ">".esc_html(__('Click here to give us feedback','majestic-support'))." </a>";
                            }else{
                                $flink = " ";
                            }

                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','close-tk' , $Email ,$ticketRecord->uid);
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }

                            $matcharray['{TICKETURL}'] = $link;
                            $matcharray['{FEEDBACKURL}'] = $flink;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 3: // Delete Ticket
                        $TrackingId = majesticsupport::$_data['ticketid'];
                        $Email = majesticsupport::$_data['ticketemail'];
                        $Subject = majesticsupport::$_data['ticketsubject'];
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{TRACKINGID}' => $TrackingId,
                            '{SUBJECT}' => $Subject,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        $object = $this->getSenderEmailAndName(null);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('delete-tk');
                        // Delete ticket mail to admin
                        if (majesticsupport::$_config['ticket_delete_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $matcharray['{EMAIL}'] = $adminEmail;

                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','delete-tk' , $adminEmail ,'');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'delete-tk-admin');
                        }
                        // Delete ticket mail to staff
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['ticket_delete_staff'] == 1) {
                            $agent_id = majesticsupport::$_data['staffid'];
                            $agentEmail = $this->getStaffEmailAddressByStaffId($agent_id);
                            $matcharray['{EMAIL}'] = $agentEmail;
                            if( ! empty($agentEmail)){
                                $staffuid = $this->getStaffUidByStaffId(majesticsupport::$_data['staffid']);
                                if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                    $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','delete-tk' , $agentEmail ,$staffuid);
                                    if($template == '' && empty($template)){
                                        $template = $defaulttemplate;
                                    }
                                    $msgSubject = $template->subject;
                                    $msgBody = $template->body;
                                    $attachments = '';
                                    $this->replaceMatches($msgSubject, $matcharray);
                                    $this->replaceMatches($msgBody, $matcharray);
                                    $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'delete-tk-staff');
                                }
                            }
                        }
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticket_delete_user'] == 1) {
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','delete-tk' , $Email , '');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $matcharray['{EMAIL}'] = $Email;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 4: // Reply Ticket (Admin/Staff Member)
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $Username = $ticketRecord->name;
                        $Subject = $ticketRecord->subject;
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', majesticsupport::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Message = $this->getLatestReplyByTicketId($id);
                        $ticketHistory = $this->getTicketReplyHistory($id);
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{USERNAME}' => $Username,
                            '{SUBJECT}' => $Subject,
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{EMAIL}' => $Email,
                            '{MESSAGE}' => $Message,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{TICKET_HISTORY}' => $ticketHistory,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('reply-tk');
                        // Reply ticket mail to admin
                        if (majesticsupport::$_config['ticket_response_to_staff_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . $id);
                            $matcharray['{TICKETURL}'] = $link;

                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','reply-tk' , $adminEmail , '');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }

                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###admin####" />';
                            $attachments = '';
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'reply-tk-admin');
                        }
                        // Reply ticket mail to staff
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['ticket_response_to_staff_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));
                            $staffuid = $this->getStaffUidByStaffId($ticketRecord->staffid);
                            if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','reply-tk' , $agentEmail , $staffuid);
                                if($template == '' && empty($template)){
                                    $template = $defaulttemplate;
                                }

                                $matcharray['{TICKETURL}'] = $link;
                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###staff####" />';
                                $attachments = '';
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'reply-tk-staff');
                            }
                        }
                        // New ticket mail to User
                        $template = $this->getTemplateForEmail('responce-tk');
                        if (majesticsupport::$_config['ticket_response_to_staff_user'] == 1) {
                            //token encrption
                            $tokenarray['emailaddress']=$Email;
                            $tokenarray['trackingid']=$TrackingId;
                            $tokenarray['sitelink']=MJTC_includer::MJTC_getModel('majesticsupport')->getEncriptedSiteLink();
                            $token = json_encode($tokenarray);
                            include_once MJTC_PLUGIN_PATH . 'includes/encoder.php';
                            $encoder = new MJTC_encoder();
                            $encryptedtext = $encoder->MJTC_encrypt($token);
                            // end token encryotion
                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'task'=>'showticketstatus','action'=>'mstask','token'=>$encryptedtext,'mspageid'=>majesticsupport::getPageid())));
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','reply-tk' , $Email , $ticketRecord->uid);
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $matcharray['{TICKETURL}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###user####" />';
                            $attachments = '';
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 5: // Reply Ticket (Ticket Member)
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $Username = $ticketRecord->name;
                        $Subject = $ticketRecord->subject;
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', majesticsupport::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Message = $this->getLatestReplyByTicketId($id);
                        $ticketHistory = $this->getTicketReplyHistory($id);
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{USERNAME}' => $Username,
                            '{SUBJECT}' => $Subject,
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{EMAIL}' => $Email,
                            '{MESSAGE}' => $Message,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{TICKET_HISTORY}' => $ticketHistory,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('reply-tk');
                        // New ticket mail to admin
                        if (majesticsupport::$_config['ticket_reply_ticket_user_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($id));
                            $matcharray['{TICKETURL}'] = $link;

                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','reply-tk' ,$adminEmail ,'');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###admin####" />';
                            $attachments = '';
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'reply-tk-admin');
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['ticket_reply_ticket_user_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));
                            $matcharray['{TICKETURL}'] = $link;
                            $staffuid = $this->getStaffUidByStaffId($ticketRecord->staffid);
                            if (isset($staffuid) && MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','reply-tk' ,$adminEmail ,$staffuid);
                                if($template == '' && empty($template)){
                                    $template = $defaulttemplate;
                                }

                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###staff####" />';
                                $attachments = '';
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'reply-tk-staff');
                            }
                        }
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticket_reply_ticket_user_user'] == 1) {
                            //token encrption
                            $tokenarray['emailaddress']=$Email;
                            $tokenarray['trackingid']=$TrackingId;
                            $tokenarray['sitelink']=MJTC_includer::MJTC_getModel('majesticsupport')->getEncriptedSiteLink();
                            $token = json_encode($tokenarray);
                            include_once MJTC_PLUGIN_PATH . 'includes/encoder.php';
                            $encoder = new MJTC_encoder();
                            $encryptedtext = $encoder->MJTC_encrypt($token);
                            // end token encryotion
                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket' ,'task'=>'showticketstatus','action'=>'mstask','token'=>$encryptedtext,'mspageid'=>majesticsupport::getPageid())));
                            $matcharray['{TICKETURL}'] = $link;
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','reply-tk' ,$Email ,$ticketRecord->uid);
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }

                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###user####" />';
                            $attachments = '';
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 6: // Lock Ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $Username = $ticketRecord->name;
                        $Subject = $ticketRecord->subject;
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', majesticsupport::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $ticketHistory = $this->getTicketReplyHistory($id);
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{USERNAME}' => $Username,
                            '{SUBJECT}' => $Subject,
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{EMAIL}' => $Email,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{TICKET_HISTORY}' => $ticketHistory,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('lock-tk');
                        // New ticket mail to admin
                        if (majesticsupport::$_config['ticket_lock_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($id));
                            $matcharray['{TICKETURL}'] = $link;
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','lock-tk' ,$adminEmail ,'');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';

                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'lock-tk-admin');
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['ticket_lock_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));

                            $matcharray['{TICKETURL}'] = $link;
                            $staffuid = $this->getStaffUidByStaffId($ticketRecord->staffid);
                            if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','lock-tk' ,$agentEmail ,$staffuid);
                                if($template == '' && empty($template)){
                                    $template = $defaulttemplate;
                                }
                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $attachments = '';

                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'lock-tk-staff');
                            }
                        }
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticket_lock_user'] == 1) {
                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));

                            $matcharray['{TICKETURL}'] = $link;
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','lock-tk' ,$Email ,$ticketRecord->uid);
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 7: // Unlock Ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $Username = $ticketRecord->name;
                        $Subject = $ticketRecord->subject;
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', majesticsupport::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $ticketHistory = $this->getTicketReplyHistory($id);
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{USERNAME}' => $Username,
                            '{SUBJECT}' => $Subject,
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{EMAIL}' => $Email,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{TICKET_HISTORY}' => $ticketHistory,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('unlock-tk');
                        // New ticket mail to admin
                        if (majesticsupport::$_config['ticket_unlock_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($id));

                            $matcharray['{TICKETURL}'] = $link;
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','unlock-tk' ,$adminEmail ,'');
                            if($template == '' && empty($template)){
                            $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';

                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'unlock-tk-admin');
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['ticket_unlock_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));

                            $matcharray['{TICKETURL}'] = $link;
                            $staffuid = $this->getStaffUidByStaffId($ticketRecord->staffid);
                            if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','unlock-tk' ,$agentEmail ,$staffuid);
                                if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                                }
                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $attachments = '';

                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'unlock-tk-staff');
                            }
                        }
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticket_unlock_user'] == 1) {
                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));

                            $matcharray['{TICKETURL}'] = $link;
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','unlock-tk' ,$Email ,$ticketRecord->uid);
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';

                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 8: // Markoverdue Ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', majesticsupport::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Subject = $ticketRecord->subject;
                        $ticketHistory = $this->getTicketReplyHistory($id);
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{SUBJECT}' => $Subject,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{TICKET_HISTORY}' => $ticketHistory,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('moverdue-tk');
                        // New ticket mail to admin
                        if (majesticsupport::$_config['ticket_mark_overdue_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $matcharray['{EMAIL}'] = $adminEmail;
                            $link = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($id));

                            $matcharray['{TICKETURL}'] = $link;
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','moverdue-tk' ,$adminEmail ,'');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'moverdue-tk-admin');
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['ticket_mark_overdue_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));

                            $matcharray['{TICKETURL}'] = $link;
                            $staffuid = $this->getStaffUidByStaffId($ticketRecord->staffid);
                            if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','moverdue-tk' ,$adminEmail ,$staffuid);
                                $matcharray['{EMAIL}'] = $agentEmail;
                                if($template == '' && empty($template)){
                                    $template = $defaulttemplate;
                                }
                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $attachments = '';
                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'moverdue-tk-staff');
                            }
                            // Get All Staff member of the department of Current Ticket
                            $agentmembers = MJTC_includer::MJTC_getModel('agent')->getAllStaffMemberByDepId($ticketRecord->departmentid);
                            if(is_array($agentmembers) && !empty($agentmembers)){
                                foreach ($agentmembers AS $agent) {
                                    if($agent->canemail == 1){
                                        $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','moverdue-tk' ,$agent->email ,$agent->staffuid);
                                        if($template == '' && empty($template)){
                                            $template = $defaulttemplate;
                                        }
                                        $matcharray['{EMAIL}'] = $agent->email;
                                        $msgSubject = $template->subject;
                                        $msgBody = $template->body;
                                        $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###staff####" />';
                                        $msgBody .= '<span style="display:none;" ticketid:' . esc_attr($TrackingId) . '###staff#### ></span>';
                                        $attachments = '';
                                        $this->sendEmail($agent->email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                                    }
                                }
                            }
                            // send email to staff memebers with all ticket permissions
                            if( !is_numeric($ticketRecord->staffid) && !is_numeric($ticketRecord->departmentid)){
                                if( in_array('agent',majesticsupport::$_active_addons)){
                                    $agentmembers = MJTC_includer::MJTC_getModel('agent')->getAllStaffMemberByAllTicketPermission();
                                    if(is_array($agentmembers) && !empty($agentmembers)){
                                        foreach ($agentmembers AS $agent) {
                                            if($agent->canemail == 1){
                                                if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $agent->uid) == 1) {
                                                    $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','moverdue-tk' ,$agent->email,$agent->uid);
                                                    $matcharray['{EMAIL}'] = $agent->email;
                                                    if($template == '' && empty($template)){
                                                        $template = $defaulttemplate;
                                                    }
                                                    $msgSubject = $template->subject;
                                                    $msgBody = $template->body;
                                                    $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###staff####" />';
                                                    $msgBody .= '<span style="display:none;" ticketid:' . esc_attr($TrackingId) . '###staff#### ></span>';
                                                    $attachments = '';

                                                    $this->sendEmail($agent->email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticket_mark_overdue_user'] == 1) {
                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));
                            $matcharray['{TICKETURL}'] = $link;
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','moverdue-tk' ,$Email ,$ticketRecord->uid);
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $matcharray['{EMAIL}'] = $Email;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 9: // Mark in progress Ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', majesticsupport::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Subject = $ticketRecord->subject;
                        $ticketHistory = $this->getTicketReplyHistory($id);
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{SUBJECT}' => $Subject,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{TICKET_HISTORY}' => $ticketHistory,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('minprogress-tk');
                        // New ticket mail to admin
                        if (majesticsupport::$_config['ticket_mark_progress_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $matcharray['{EMAIL}'] = $adminEmail;
                            $link = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($id));

                            $matcharray['{TICKETURL}'] = $link;

                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','minprogress-tk' ,$adminEmail ,'');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'minprogress-tk-admin');
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['ticket_mark_progress_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $matcharray['{EMAIL}'] = $agentEmail;
                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));

                            $matcharray['{TICKETURL}'] = $link;
                            $staffuid = $this->getStaffUidByStaffId($ticketRecord->staffid);
                            if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','minprogress-tk'
                                 ,$agentEmail ,$staffuid);
                                if($template == '' && empty($template)){
                                    $template = $defaulttemplate;
                                }
                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $attachments = '';
                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'minprogress-tk-staff');
                            }
                        }
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticket_mark_progress_user'] == 1) {
                            $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));

                            $matcharray['{TICKETURL}'] = $link;
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','minprogress-tk' ,$Email ,$ticketRecord->uid);
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $matcharray['{EMAIL}'] = $Email;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 10: // Ban email and close Ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', majesticsupport::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Subject = $ticketRecord->subject;
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{EMAIL_ADDRESS}' => $Email,
                            '{SUBJECT}' => $Subject,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{TRACKINGID}' => $TrackingId,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('banemailcloseticket-tk');

                        // New ticket mail to admin
                        if (majesticsupport::$_config['ticker_ban_eamil_and_close_ticktet_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','banemailcloseticket-tk' ,$adminEmail ,'');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'banemailcloseticket-tk-admin');
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['ticker_ban_eamil_and_close_ticktet_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $staffuid = $this->getStaffUidByStaffId($ticketRecord->staffid);
                            if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','banemailcloseticket-tk' ,$adminEmail ,$staffuid);
                                if($template == '' && empty($template)){
                                    $template = $defaulttemplate;
                                }
                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $attachments = '';

                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'banemailcloseticket-tk-staff');
                            }
                        }
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticker_ban_eamil_and_close_ticktet_user'] == 1) {
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','banemailcloseticket-tk' ,$Email ,$ticketRecord->uid);
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 11: // Priority change ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $TrackingId = $ticketRecord->ticketid;
                        $Subject = $ticketRecord->subject;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', majesticsupport::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Priority = MJTC_includer::MJTC_getModel('priority')->getPriorityById($ticketRecord->priorityid);
                        $ticketHistory = $this->getTicketReplyHistory($id);
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{PRIORITY_TITLE}' => majesticsupport::MJTC_getVarValue($Priority),
                            '{SUBJECT}' => $Subject,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{TRACKINGID}' => $TrackingId,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{TICKET_HISTORY}' => $ticketHistory,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('prtrans-tk');

                        // New ticket mail to admin
                        if (majesticsupport::$_config['ticket_priority_admin'] == 1) {
                            $link = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($id));
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $matcharray['{TICKETURL}'] = $link;
                            $matcharray['{EMAIL}'] = $adminEmail;
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','prtrans-tk' ,$adminEmail ,'');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'prtrans-tk-admin');
                        }
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));
                        $matcharray['{TICKETURL}'] = $link;
                        // New ticket mail to staff
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['ticket_priority_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $staffuid = $this->getStaffUidByStaffId($ticketRecord->staffid);
                            $matcharray['{EMAIL}'] = $agentEmail;
                            if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','prtrans-tk' ,$agentEmail ,$staffuid);
                                if($template == '' && empty($template)){
                                    $template = $defaulttemplate;
                                }
                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $attachments = '';

                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'prtrans-tk-staff');
                            }
                        }
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticket_priority_user'] == 1) {
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','prtrans-tk' ,$Email ,$ticketRecord->uid);
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $matcharray['{EMAIL}'] = $Email;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 12: // DEPARTMENT TRANSFER
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $TrackingId = $ticketRecord->ticketid;
                        $Subject = $ticketRecord->subject;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', majesticsupport::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Department = MJTC_includer::MJTC_getModel('department')->getDepartmentById($ticketRecord->departmentid);
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{SUBJECT}' => $Subject,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{TRACKINGID}' => $TrackingId,
                            '{DEPARTMENT_TITLE}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('deptrans-tk');
                        // New ticket mail to admin
                        if (majesticsupport::$_config['ticket_department_transfer_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $matcharray['{EMAIL}'] = $adminEmail;

                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','deptrans-tk' ,$adminEmail ,'');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'deptrans-tk-admin');
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['ticket_department_transfer_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $staffuid = $this->getStaffUidByStaffId($ticketRecord->staffid);
                            $matcharray['{EMAIL}'] = $agentEmail;
                            if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','deptrans-tk' ,$agentEmail ,$staffuid);
                                if($template == '' && empty($template)){
                                    $template = $defaulttemplate;
                                }
                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $attachments = '';
                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'deptrans-tk-staff');
                            }
                            // send email to all staff memebers of current ticket department
                            // Get All Staff member of the department of Current Ticket
                            $agentmembers = MJTC_includer::MJTC_getModel('agent')->getAllStaffMemberByDepId($ticketRecord->departmentid);
                            if(is_array($agentmembers) && !empty($agentmembers)){
                                foreach ($agentmembers AS $agent) {
                                    if($agent->canemail == 1){
                                        if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $agent->staffuid) == 1) {
                                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','deptrans-tk' ,$agent->email ,$agent->staffuid);
                                            $matcharray['{EMAIL}'] = $agent->email;
                                            if($template == '' && empty($template)){
                                                $template = $defaulttemplate;
                                            }
                                            $msgSubject = $template->subject;
                                            $msgBody = $template->body;
                                            $this->replaceMatches($msgSubject, $matcharray);
                                            $this->replaceMatches($msgBody, $matcharray);
                                            $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###staff####" />';
                                            $msgBody .= '<span style="display:none;" ticketid:' . esc_attr($TrackingId) . '###staff#### ></span>';
                                            $attachments = '';
                                            $this->sendEmail($agent->email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                                        }
                                    }
                                }
                            }
                            // send email to staff memebers with all ticket permissions
                            if( !is_numeric($ticketRecord->staffid) && !is_numeric($ticketRecord->departmentid)){
                                if( in_array('agent',majesticsupport::$_active_addons) ){
                                    $agentmembers = MJTC_includer::MJTC_getModel('agent')->getAllStaffMemberByAllTicketPermission();
                                    if(is_array($agentmembers) && !empty($agentmembers)){
                                        foreach ($agentmembers AS $agent) {
                                            if($agent->canemail == 1){
                                                if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $agent->uid) == 1) {
                                                    $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','deptrans-tk' ,$agent->email ,$agent->uid);
                                                    $matcharray['{EMAIL}'] = $agent->email;
                                                    if($template == '' && empty($template)){
                                                        $template = $defaulttemplate;
                                                    }
                                                    $msgSubject = $template->subject;
                                                    $msgBody = $template->body;
                                                    $this->replaceMatches($msgSubject, $matcharray);
                                                    $this->replaceMatches($msgBody, $matcharray);
                                                    $msgBody .= '<input type="hidden" name="ticketid:' . esc_attr($TrackingId) . '###staff####" />';
                                                    $msgBody .= '<span style="display:none;" ticketid:' . esc_attr($TrackingId) . '###staff#### ></span>';
                                                    $attachments = '';
                                                    $this->sendEmail($agent->email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticket_department_transfer_user'] == 1) {
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','deptrans-tk' ,$Email,$ticketRecord->uid);
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $matcharray['{EMAIL}'] = $Email;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 13: // REASSIGN TICKET TO STAFF
                        if(! in_array('agent',majesticsupport::$_active_addons) ){
                            return;
                        }
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', majesticsupport::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Subject = $ticketRecord->subject;
                        $Staff = MJTC_includer::MJTC_getModel('agent')->getMyName($ticketRecord->staffid);
                        $ticketHistory = $this->getTicketReplyHistory($id);
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{AGENT_NAME}' => $Staff,
                            '{SUBJECT}' => $Subject,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{TRACKINGID}' => $TrackingId,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{TICKET_HISTORY}' => $ticketHistory,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('reassign-tk');
                        // New ticket mail to admin
                        $link = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . $id);
                        $matcharray['{TICKETURL}'] = $link;
                        if (majesticsupport::$_config['ticket_reassign_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $matcharray['{EMAIL}'] = $adminEmail;

                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','reassign-tk' ,$adminEmail ,'');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';

                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'reassign-tk-admin');
                        }

                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{AGENT_NAME}' => $Staff,
                            '{SUBJECT}' => $Subject,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{TRACKINGID}' => $TrackingId,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{TICKET_HISTORY}' => $ticketHistory,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $link = esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id,'mspageid'=>majesticsupport::getPageid())));
                        $matcharray['{TICKETURL}'] = $link;
                        // New ticket mail to staff
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['ticket_reassign_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $matcharray['{EMAIL}'] = $agentEmail;
                            $staffuid = $this->getStaffUidByStaffId($ticketRecord->staffid);
                            if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','reassign-tk' ,$adminEmail ,$staffuid);
                                if($template == '' && empty($template)){
                                    $template = $defaulttemplate;
                                }

                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $attachments = '';
                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'reassign-tk-staff');
                            }
                        }
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticket_reassign_user'] == 1) {
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','reassign-tk' ,$Email ,$ticketRecord->uid);
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $matcharray['{EMAIL}'] = $Email;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 14: // Reply to closed ticket for Email Piping
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $Subject = $ticketRecord->subject;
                        $Email = $ticketRecord->email;
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{SUBJECT}' => $Subject,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('mail-rpy-closed');
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticket_reply_closed_ticket_user'] == 1) {
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','mail-rpy-closed' ,$Email ,$ticketRecord->uid);
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 15: // Send feedback email to user
                        if(!in_array('feedback', majesticsupport::$_active_addons)){
                            break;
                        }
                        $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $Subject = $ticketRecord->subject;
                        $Email = $ticketRecord->email;
                        $TrackingId = $ticketRecord->ticketid;
                        $close_date = date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($ticketRecord->closed));
                        $username = $ticketRecord->name;
                        $tokenarray['emailaddress']=$Email;
                        $tokenarray['trackingid']=$TrackingId;
                        $tokenarray['sitelink']=MJTC_includer::MJTC_getModel('majesticsupport')->getEncriptedSiteLink();
                        $token = json_encode($tokenarray);
                        include_once MJTC_PLUGIN_PATH . 'includes/encoder.php';
                        $encoder = new MJTC_encoder();
                        $encryptedtext = $encoder->MJTC_encrypt($token);
                        $link = "<a href=" . esc_url(majesticsupport::makeUrl(array('mjsmod'=>'feedback', 'task'=>'showfeedbackform','action'=>'mstask','token'=>$encryptedtext,'mspageid'=>majesticsupport::getPageid()))) . ">";
                        $linkclosing = "</a>";
                        $tracking_url = "<a href=" . esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'task'=>'showticketstatus','action'=>'mstask','token'=>$encryptedtext,'mspageid'=>majesticsupport::getPageid()))) . ">" . $TrackingId . "</a>";
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{USER_NAME}' => $username,
                            '{TICKET_SUBJECT}' => $Subject,
                            '{TRACKING_ID}' => $tracking_url,
                            '{CLOSE_DATE}' => $close_date,
                            '{LINK}' => $link,
                            '{/LINK}' => $linkclosing,
                            '{DEPARTMENT}' => majesticsupport::MJTC_getVarValue($ticketRecord->departmentname),
                            '{PRIORITY}' => majesticsupport::MJTC_getVarValue($ticketRecord->priority),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.esc_attr($field->field).'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('mail-feedback');
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticket_feedback_user'] == 1) {
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','mail-feedback' ,$Email ,$ticketRecord->uid);
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                }
                break;
            case 2: // Ban Email
                switch ($action) {
                    case 1: // Ban Email
                        if ($tablename != null)
                            $banemailRecord = $this->getRecordByTablenameAndId($tablename, $id);
                        else
                            $banemailRecord = $this->getRecordByTablenameAndId('mjtc_support_email_banlist', $id);
                        $Email = $banemailRecord->email;
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{EMAIL_ADDRESS}' => $Email,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        $object = $this->getDefaultSenderEmailAndName();
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('banemail-tk');

                        // New ticket mail to admin
                        if (majesticsupport::$_config['ticket_ban_email_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $matcharray['{EMAIL}'] = $adminEmail;
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','banemail-tk' ,$adminEmail ,'');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';

                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'banemail-tk-admin');
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['ticket_ban_email_staff'] == 1) {
                            if ($tablename != null){
                                $agentEmail = $this->getStaffEmailAddressByStaffId($banemailRecord->staffid);
                                $staffuid = $this->getStaffUidByStaffId($banemailRecord->staffid);
                            }else{
                                $agentEmail = $this->getStaffEmailAddressByStaffId($banemailRecord->submitter);
                                $staffuid = $this->getStaffUidByStaffId($banemailRecord->submitter);
                            }
                            if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','banemail-tk' ,$agentEmail ,$staffuid);
                                $matcharray['{EMAIL}'] = $agentEmail;
                                if($template == '' && empty($template)){
                                    $template = $defaulttemplate;
                                }
                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $attachments = '';

                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'banemail-tk-staff');
                            }
                        }
                        // New ticket mail to User
                        if (majesticsupport::$_config['ticket_ban_email_user'] == 1) {
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','banemail-tk' ,$Email ,'');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $matcharray['{EMAIL}'] = $Email;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';

                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                    case 2: // Unban Email
                        if ($tablename != null)
                            $ticketRecord = $this->getRecordByTablenameAndId($tablename, $id);
                        else
                            $ticketRecord = $this->getRecordByTablenameAndId('mjtc_support_tickets', $id);
                        $Email = $ticketRecord->email;
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{EMAIL_ADDRESS}' => $Email,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('unbanemail-tk');

                        // New ticket mail to admin
                        if (majesticsupport::$_config['unban_email_admin'] == 1) {
                            $adminEmailid = majesticsupport::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $matcharray['{EMAIL}'] = $adminEmail;
                            $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','unbanemail-tk' ,$adminEmail ,'');
                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'unbanemail-tk-admin');
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['unban_email_staff'] == 1) {
                            if ($tablename != null){
                                $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                                $staffuid = $this->getStaffUidByStaffId($ticketRecord->staffid);
                            }else{
                                $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->submitter);
                                $staffuid = $this->getStaffUidByStaffId($ticketRecord->submitter);
                            }
                            if (MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForAgent('Mail To Agent', $staffuid) == 1) {
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','unbanemail-tk' ,$agentEmail ,$staffuid);
                                if($template == '' && empty($template)){
                                    $template = $defaulttemplate;
                                }
                                $matcharray['{EMAIL}'] = $agentEmail;
                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $attachments = '';
                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'unbanemail-tk-staff');
                            }
                        }
                        // New ticket mail to User
                        if (majesticsupport::$_config['unban_email_user'] == 1) {
                            if ($tablename != null){
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','unbanemail-tk' , $Email, '');
                            }else{
                                $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','unbanemail-tk' ,$ticketRecord->email , $ticketRecord->uid);
                            }

                            if($template == '' && empty($template)){
                                $template = $defaulttemplate;
                            }
                            $matcharray['{EMAIL}'] = $Email;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $attachments = '';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        }
                        break;
                }
                break;
            case 3: // Sending email alerts on mail system
                if(!in_array('mail', majesticsupport::$_active_addons)){ // if mail addon is not installed
                    break;
                }
                switch ($action) {
                    case 1: // Store message
                        $mailRecord = $this->getMailRecordById($id);
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{AGENT_NAME}' => $mailRecord->sendername,
                            '{SUBJECT}' => $mailRecord->subject,
                            '{MESSAGE}' => $mailRecord->message,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        $object = $this->getSenderEmailAndName(null);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('mail-new');
                        $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','mail-new' ,'' ,$mailRecord->staffuid);
                        if($template == '' && empty($template)){
                            $template = $defaulttemplate;
                        }
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;

                        $Email = isset($mailRecord->receveremail) ? $mailRecord->receveremail : '';
                        $attachments = '';
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'mail-new');
                        break;
                    case 2: // Store reply
                        $mailRecord = $this->getMailRecordById($id, 1);
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{AGENT_NAME}' => $mailRecord->sendername,
                            '{SUBJECT}' => $mailRecord->subject,
                            '{MESSAGE}' => $mailRecord->message,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        $object = $this->getSenderEmailAndName(null);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('mail-rpy');
                        $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','mail-rpy' ,'' ,$mailRecord->staffuid);
                        if($template == '' && empty($template)){
                            $template = $defaulttemplate;
                        }
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $Email = isset($mailRecord->receveremail) ? $mailRecord->receveremail : '';
                        $attachments = '';
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'mail-rpy');
                        break;
                }
                break;
            case 4: // gdpr data erase or delte.
                switch ($action) {
                    case 1: // erase data email
                        $Email = majesticsupport::$_data['mail_data']['email'];
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{USERNAME}' => majesticsupport::$_data['mail_data']['name'],
                            '{EMAIL}' => $Email,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        $object = $this->getSenderEmailAndName(null);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $defaulttemplate = $this->getTemplateForEmail('delete-user-data');
                        $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','delete-user-data' ,majesticsupport::$_data['mail_data']['email'] , '');
                        if($template == '' && empty($template)){
                            $template = $defaulttemplate;
                        }

                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $attachments = '';
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action);
                        break;
                }
                break;
            case 5: // agent emails
                switch ($action) {
                    case 1: // new agent
                        $staffname = MJTC_includer::MJTC_getModel('agent')->getMyName($id);
                        $object = $this->getSenderEmailAndName(null);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $matcharray = array(
                            '{SITETITLE}' => majesticsupport::$_config['title'],
                            '{AGENT_NAME}' => $staffname,
                            '{EMAIL}' => $object->email,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        
                        $defaulttemplate = $this->getTemplateForEmail('staff-new');

                        $adminEmailid = majesticsupport::$_config['default_admin_email'];
                        $adminEmail = $this->getEmailById($adminEmailid);
                        $template = apply_filters( 'ms_get_email_template_by_user_defined_language','','staff-new' , $adminEmail , '');
                        if($template == '' && empty($template)){
                            $template = $defaulttemplate;
                        }
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $attachments = '';
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, $attachments, $action, 'staff-new');
                        break;
                }
                break;
        }
    }


    function getMailRecordById($id, $replyto = null) { // this function will not be called if the mail addon is not installed
        if (!is_numeric($id))
            return false;
        if ($replyto == null) {
            $query = "SELECT mail.subject,mail.message,CONCAT(staff.firstname,' ',staff.lastname) AS sendername, staff.uid as staffuid
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_staff_mail` AS mail
                        JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS staff ON staff.id = mail.fromid
                        WHERE mail.id = " . esc_sql($id);
        } else {
            $query = "SELECT mail.subject,reply.message,CONCAT(staff.firstname,' ',staff.lastname) AS sendername, staff.uid as staffuid
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_staff_mail` AS reply
                        JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_staff_mail` AS mail ON mail.id = reply.replytoid
                        JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS staff ON staff.id = reply.fromid
                        WHERE reply.id = " . esc_sql($id);
        }
        $result = majesticsupport::$_db->get_row($query);
            $query = "SELECT staff.email
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_staff_mail` AS mail
                        JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS staff ON staff.id = mail.toid
                        WHERE mail.id = " . esc_sql($id);
        $email = majesticsupport::$_db->get_var($query);
        if (isset($email)) {
            $result->receveremail = $email;
        }
        return $result;
    }

    private function getStaffEmailAddressByStaffId($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT staff.email
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS staff
                    WHERE staff.id = ".esc_sql($id);
        $emailaddress = majesticsupport::$_db->get_var($query);
        return $emailaddress;
    }

    private function getStaffUidByStaffId($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT staff.uid
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_staff` AS staff
                    WHERE staff.id = ".esc_sql($id);
        $emailaddress = majesticsupport::$_db->get_var($query);
        return $emailaddress;
    }

    private function getLatestReplyByTicketId($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT reply.message FROM `" . majesticsupport::$_db->prefix . "mjtc_support_replies` AS reply WHERE reply.ticketid = " . esc_sql($id) . " ORDER BY reply.created DESC LIMIT 1";
        $message = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $message;
    }

    private function replaceMatches(&$string, $matcharray) {
        foreach ($matcharray AS $find => $replace) {
            if($string != '' && $replace != ''){
                $string = MJTC_majesticsupportphplib::MJTC_str_replace($find, $replace, $string);
            }
        }
    }

    // remove this after testing

    function emailTest($sendername,$senderemail,$recemail,$message,$sub,$mailfor){
        $myfile = fopen("emailtesting.html", "a") or die("Unable to open file!");
        $txt  = "<hr>\n</br>";
        $txt .= $mailfor."\n</br></br>";
        $txt .= "Sender name => ". $sendername."\n</br>";
        $txt .= "Sender Email => ". $senderemail."\n</br>";
        $txt .= "Recepient Email => ". $recemail."\n\n</br></br>";
        $txt .= "Message => \n</br>". $message."\n\n</br></br>";
        $txt .= "Subject => ". $sub."\n</br>";
        $txt .= "<hr>\n</br></br></br></br>";
        fwrite($myfile, $txt);
        fclose($myfile);
    }

    function sendEmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments, $action, $actionfor='') {
        if( (is_array($recevierEmail) && empty($recevierEmail)) || (!is_array($recevierEmail) && MJTC_majesticsupportphplib::MJTC_trim($recevierEmail) == '') ){ // avoid the case of trying to send email to empty email.
            return;
        }

        $enablesmtp = $this->checkSMTPEnableOrDisable($senderEmail);
        if ($enablesmtp) {
            $this->sendSMTPmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments, $action, $actionfor);
        }else{
            $this->sendEmailDefault($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments, $action, $actionfor);
        }

    }

    private function sendEmailDefault($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments, $action, $actionfor) {
	$senderName = majesticsupport::$_config['title']; // site name
        /*
          $attachments = array( WP_CONTENT_DIR . '/uploads/file_to_attach.zip' );
          $headers = 'From: My Name <myname@example.com>' . "\r\n";
          wp_mail('test@example.org', 'subject', 'message', $headers, $attachments );

          $action
          For which action of $mailfor you want to send the mail
          1 => New Ticket Create
          2 => Close Ticket
          3 => Delete Ticket
          4 => Reply Ticket (Admin/Staff Member)
          5 => Reply Ticket (Ticket member)
         */
        switch ($action) {
            case 1:
                do_action('ms-beforeemailticketcreate', $recevierEmail, $subject, $body, $senderEmail);
                break;
            case 2:
                do_action('ms-beforeemailticketreply', $recevierEmail, $subject, $body, $senderEmail);
                break;
            case 3:
                do_action('ms-beforeemailticketclose', $recevierEmail, $subject, $body, $senderEmail);
                break;
            case 4:
                do_action('ms-beforeemailticketdelete', $recevierEmail, $subject, $body, $senderEmail);
                break;
        }
        if (!$senderName)
            $senderName = majesticsupport::$_config['title'];
        $headers[] = 'From: ' . $senderName . ' <' . $senderEmail . '>' . "\r\n";
        $headers = apply_filters('ms_emailcc_send_email_to_cc' , $headers , $actionfor); // eg $actionfor = ticket-new
        add_filter('wp_mail_content_type', array($this,'ms_set_html_content_type'));
		if($recevierEmail){
			if(!wp_mail($recevierEmail, $subject, $body, $headers, $attachments)){
				if($GLOBALS['phpmailer']->ErrorInfo)
					MJTC_includer::MJTC_getModel('systemerror')->addSystemError($GLOBALS['phpmailer']->ErrorInfo);
			}
		}else{
			MJTC_includer::MJTC_getModel('systemerror')->addSystemError("No recipient email for ".$subject);
		}
    }

    function ms_set_html_content_type() {
        return 'text/html';
    }

    private function sendSMTPmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments, $action, $actionfor){
        do_action('ms_aadon_send_smtp_mail',$recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments, $action, $actionfor);
    }

    private function getSenderEmailAndName($id) {
        if ($id) {
            if (!is_numeric($id))
                return false;
            $query = "SELECT email.email,email.name
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS ticket
                        JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON department.id = ticket.departmentid
                        JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_email` AS email ON email.id = department.emailid
                        WHERE ticket.id = " . esc_sql($id);
            $email = majesticsupport::$_db->get_row($query);
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
            }
        } else {
            $email = '';
        }
        if (empty($email)) {
            $email = $this->getDefaultSenderEmailAndName();
        }
        return $email;
    }

    private function getDefaultSenderEmailAndName() {
        $emailid = majesticsupport::$_config['default_alert_email'];
        $query = "SELECT email,name FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email` WHERE id = " . esc_sql($emailid);
        $email = majesticsupport::$_db->get_row($query);
        return $email;
    }

    private function getTemplateForEmail($templatefor) {
        $query = "SELECT * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_emailtemplates` WHERE templatefor = '" . esc_sql($templatefor) . "'";
        $template = majesticsupport::$_db->get_row($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $template;
    }

    private function getRecordByTablenameAndId($tablename, $id) {
        if (!is_numeric($id))
            return false;
        switch($tablename){
            case 'mjtc_support_tickets':
                do_action('get_mail_table_record_query');// to prepare any addon based query
                $query = "SELECT ticket.*,department.departmentname,priority.priority ".majesticsupport::$_addon_query['select']
                    . " FROM `" . majesticsupport::$_db->prefix . $tablename . "` AS ticket "
                    . " LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department ON department.id = ticket.departmentid "
                    . majesticsupport::$_addon_query['join']
                    . " LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ON priority.id = ticket.priorityid "
                    . " WHERE ticket.id = " . esc_sql($id);
                do_action('reset_ms_aadon_query');
            break;
            default:
                $query = "SELECT * FROM `" . majesticsupport::$_db->prefix . $tablename . "` WHERE id = " . esc_sql($id);
            break;
        }
        $record = majesticsupport::$_db->get_row($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $record;
    }

    function getEmails() {
        // Filter
        $email = majesticsupport::$_search['email']['email'];
        $inquery = '';
        if ($email != null)
            $inquery .= " WHERE email.email LIKE '%".esc_sql($email)."%'";

        majesticsupport::$_data['filter']['email'] = $email;

        // Pagination
        $query = "SELECT COUNT(email.id)
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email` AS email ";
        $query .= $inquery;
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total);

        // Data
        $query = " SELECT email.id, email.email, email.autoresponse, email.created, email.updated,email.status
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email` AS email ";
        $query .= $inquery;
        $query .= " ORDER BY email.email DESC LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        majesticsupport::$_data[0] = majesticsupport::$_db->get_results($query);
        majesticsupport::$_data['email'] = $email;
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return;
    }

    function getAllEmailsForCombobox() {
        $query = "SELECT id AS id, email AS text FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email` WHERE status = 1 AND autoresponse = 1";
        $emails = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $emails;
    }

    function getEmailForForm($id) {
        if ($id) {
            if (!is_numeric($id))
                return false;
            $query = "SELECT email.id, email.email, email.autoresponse, email.created, email.updated,email.status,email.smtpemailauth,email.smtphosttype,email.smtphost,email.smtpauthencation,email.name,email.password,email.smtpsecure,email.mailport
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email` AS email
                        WHERE email.id = " . esc_sql($id);
            majesticsupport::$_data[0] = majesticsupport::$_db->get_row($query);
            if(isset(majesticsupport::$_data[0]->password) && majesticsupport::$_data[0]->password != ''){
                majesticsupport::$_data[0]->password = MJTC_majesticsupportphplib::MJTC_safe_decoding(majesticsupport::$_data[0]->password);
            }
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            }
        }
        return;
    }

    function storeEmail($data) {
        if(!$data['id'])
        if($this->checkAlreadyExist($data['email'])){
            MJTC_message::MJTC_setMessage(esc_html(__('Email Already Exist', 'majestic-support')), 'error');
            return;
        }
        if ($data['id'])
            $data['updated'] = date_i18n('Y-m-d H:i:s');
        else{
            $data['updated'] = date_i18n('Y-m-d H:i:s');
            $data['created'] = date_i18n('Y-m-d H:i:s');
        }
        if(isset($data['password']) && $data['password'] != ''){
            $data['password'] = MJTC_majesticsupportphplib::MJTC_safe_encoding($data['password']);
        }

        $data = majesticsupport::MJTC_sanitizeData($data);// MJTC_sanitizeData() function uses wordpress santize functions

        $row = MJTC_includer::MJTC_getTable('email');

        $data = MJTC_includer::MJTC_getModel('majesticsupport')->stripslashesFull($data);// remove slashes with quotes.
        $error = 0;
        if (!$row->bind($data)) {
            $error = 1;
        }
        if (!$row->store()) {
            $error = 1;
        }

        if ($error == 0) {
            MJTC_message::MJTC_setMessage(esc_html(__('The email has been stored', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('The email has not been stored', 'majestic-support')), 'error');
        }
        return;
    }

    function checkAlreadyExist($email){
        $query = "SELECT COUNT(id) FROM`" . majesticsupport::$_db->prefix . "mjtc_support_email`  WHERE email = '".esc_sql($email)."'";
        $result = majesticsupport::$_db->get_var($query);
        if($result > 0)
            return true;
        else
            return false;
    }

    function removeEmail($id) {
        if (!is_numeric($id))
            return false;
        if ($this->canRemoveEmail($id)) {
            $row = MJTC_includer::MJTC_getTable('email');
            if ($row->delete($id)) {
                MJTC_message::MJTC_setMessage(esc_html(__('The email has been deleted', 'majestic-support')), 'updated');
            } else {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
                MJTC_message::MJTC_setMessage(esc_html(__('The email has not been deleted', 'majestic-support')), 'error');
            }
        } else {
            MJTC_message::MJTC_setMessage(esc_html(__('Email','majestic-support')).' '.esc_html(__('in use cannot deleted', 'majestic-support')), 'error');
        }
        return;
    }

    private function canRemoveEmail($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT (
                        (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments` WHERE emailid = " . esc_sql($id) . ")
                        + (SELECT COUNT(*) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_config` WHERE configname = 'default_alert_email' AND configvalue = " . esc_sql($id) . ")
                        + (SELECT COUNT(*) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_config` WHERE configname = 'default_admin_email' AND configvalue = " . esc_sql($id) . ")
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

    function getEmailForDepartment() {
        $query = "SELECT id, email AS text FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email`";
        $emails = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $emails;
    }

    function getEmailById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT email  FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email` WHERE id = " . esc_sql($id);
        $email = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $email;
    }

    function checkSMTPEnableOrDisable($senderemail){
        if(!in_array('smtp', majesticsupport::$_active_addons)){
            return false;
        }
        if(!is_string($senderemail))
            return false;
        $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email` WHERE email = '".esc_sql($senderemail). "' AND smtpemailauth = 1"; // 1 For smtp 0 for default
        $total = majesticsupport::$_db->get_var($query);
        if($total > 0){
            return true;
        }else{
            return false;
        }
    }

    function getSMTPEmailConfig($senderemail){
        $query = "SELECT * FROM  `" . majesticsupport::$_db->prefix . "mjtc_support_email` WHERE email = '".esc_sql($senderemail)."'";
        $emailconfig = majesticsupport::$_db->get_row($query);
        return $emailconfig;
    }

    function sendTestEmail(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'send-test-email') ) {
            die( 'Security check Failed' );
        }
        $hosttype = MJTC_request::MJTC_getVar('hosttype');
        $hostname = MJTC_request::MJTC_getVar('hostname');
        $ssl = MJTC_request::MJTC_getVar('ssl');
        $hostportnumber = MJTC_request::MJTC_getVar('hostportnumber');
        $emailaddress = MJTC_request::MJTC_getVar('emailaddress');
        $password = MJTC_request::MJTC_getVar('password');
        $smtpauthencation = MJTC_request::MJTC_getVar('smtpauthencation');

        if(get_bloginfo('version') >= 5){
            require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
            require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
            require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer( true );
        } else {
            require_once ABSPATH . WPINC . '/class-phpmailer.php';
            require_once ABSPATH . WPINC . '/class-smtp.php';
            $mail = new PHPMailer(true);
        }
        try {

            $mail->isSMTP();
            $mail->Host = $hostname;
            $mail->SMTPAuth = $smtpauthencation;
            $mail->Username = $emailaddress;
            $mail->Password = $password;
            if($ssl == 0){
                $mail->SMTPSecure = 'ssl';
            }else{
                $mail->SMTPSecure = 'tls';
            }
            $mail->Port = $hostportnumber;
            //Recipients
            $mail->setFrom($emailaddress, majesticsupport::$_config['title']);
            $adminEmailid = majesticsupport::$_config['default_admin_email'];
            $adminEmail = $this->getEmailById($adminEmailid);

            $mail->addAddress($adminEmail,'Administrator');

            $mail->isHTML(true);
            $mail->Subject = 'SMTP Test email From :'.site_url();
            $mail->Body    = 'This is body text for SMTP test email from :'.site_url();
            $mail->send();
            $error['text'] = __('Test email has been sent on : ', 'majestic-support'). $adminEmail;
            $error['type'] = 0;
        } catch (Exception $e) {
            $error['text'] = __('Message could not be sent. Mailer Error: ', 'majestic-support'). $mail->ErrorInfo;
            $error['type'] = 1;
        }
        return json_encode($error);;

    }

    function getAdminSearchFormDataEmails(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'emails') ) {
            die( 'Security check Failed' );
        }
        $ms_search_array = array();
        $ms_search_array['email'] = MJTC_request::MJTC_getVar('email');
        $ms_search_array['search_from_email'] = 1;
        return $ms_search_array;
    }

    private function getTicketReplyHistory($id) {
        $html = '';
        if ($id) {
            if (!is_numeric($id))
                return false;
            $query = "SELECT replies.*,replies.id AS replyid,tickets.id 
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_replies` AS replies
                    JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` AS tickets ON  replies.ticketid = tickets.id
                    WHERE tickets.id = " . esc_sql($id) . " ORDER By replies.id DESC";
            $replies = majesticsupport::$_db->get_results($query);
            foreach ($replies as $key => $reply) {
                if ($key == 0) {
                    $html .= '<div style="float:left;width:100%;padding:15px 0;border-bottom:1px solid #e0e1e0;margin-bottom:20px;">
                                <div style="font-weight:bold;font-size:18px;margin-bottom:5px;color:#4b4b4d;">'.esc_html(__('Ticket History','majestic-support')).'</div>';
                }
                $html .= '<div style="float:left;width:100%;padding:10px 15px;border:1px solid #e0e1e0;background:#f8fafc;box-sizing:border-box;margin:10px 0;">
                            <div style="float:left;width:100%;margin:10px 0;">
                                <span style="float:left;width:auto;display:inline-block;color:#4b4b4d;font-size:14px;font-weight: 600;">'.esc_html(__('Reply By','majestic-support')).':&nbsp;</span>
                                <span style="float:left;width:auto;display:inline-block;color:#727376;">'.esc_html($reply->name).'</span>
                            </div>
                            <div style="float:left;width:100%;margin:10px 0 0;">
                                <span style="float:left;width:auto;display:inline-block;color:#4b4b4d;font-size:14px;font-weight: 600;">'.esc_html(__('Date','majestic-support')).':&nbsp;</span>
                                <span style="float:left;width:auto;display:inline-block;color:#727376;">'.esc_html($reply->created).'</span>
                            </div>
                            <div style="float:left;width:100%;">
                                <span style="float:left;width:auto;display:inline-block;color:#727376;">'.esc_html($reply->message).'</span>
                            </div>
                        </div>';
            }
            if (isset($html)) {
                $html .= '</div>';
            }
            
        }
        return $html;
    }
}

?>
