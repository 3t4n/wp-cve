<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_ticketController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        if (is_admin()) {
            $defaultlayout = "tickets";
        } else
            $defaultlayout = "myticket";
        $layout = MJTC_request::MJTC_getLayout('mjslay', null, $defaultlayout);
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_tickets':
                    $list = MJTC_request::MJTC_getVar('list');
                    MJTC_includer::MJTC_getModel('ticket')->getTicketsForAdmin($list);
                    break;
                case 'admin_addticket':
                case 'addticket':

                    $id = MJTC_request::MJTC_getVar('majesticsupportid','',null);
                    $formid = MJTC_request::MJTC_getVar('formid');
					
                    if($formid == null){
                        $formid = MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId();
                    }
                    // below code to is hanlde parameters for easy digital downloads and woocommerce
                    if($id != null && MJTC_majesticsupportphplib::MJTC_strstr($id, '_')){
                        $id_array = MJTC_majesticsupportphplib::MJTC_explode('_', $id);
                        if($id_array[1] == 10){// tikcet id
                            $id = $id_array[0];
                        }elseif($id_array[1] == 11){ // edd order id
                            $id = NULL;
                            majesticsupport::$_data['edd_order_id'] = $id_array[0];
                        }else{
                            $id = NULL;
                        }
                    }
                    majesticsupport::$_data['permission_granted'] = true;

                    if (majesticsupport::$_data['permission_granted']) {
                        MJTC_includer::MJTC_getModel('ticket')->getTicketsForForm($id,$formid);

                        if(in_array('paidsupport', majesticsupport::$_active_addons) && class_exists('WooCommerce') && !is_admin() && !MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()){
                            $selected = false;
                            $paidsupportid = MJTC_request::MJTC_getVar('paidsupportid',null,0);
                            if($paidsupportid){
								$paidsupport = MJTC_includer::MJTC_getModel('paidsupport')->getPaidSupportList(MJTC_includer::MJTC_getObjectClass('user')->MJTC_wpuid(), $paidsupportid);
                                if($paidsupport){
                                    majesticsupport::$_data['paidsupport'] = $paidsupport[0];
                                    $selected = true;
                                }
                            }
                            if(!$selected){
								$paidsupportitems = MJTC_includer::MJTC_getModel('paidsupport')->getPaidSupportList(MJTC_includer::MJTC_getObjectClass('user')->MJTC_wpuid());
                                if(count($paidsupportitems) == 1){
                                    majesticsupport::$_data['paidsupport'] = $paidsupportitems[0];
                                }else{
                                    majesticsupport::$_data['paidsupportitems'] = $paidsupportitems;
                                }
                            }
                        }

                    }
                    MJTC_includer::MJTC_getModel('majesticsupport')->updateColorFile();
                    break;
                case 'admin_ticketdetail':
                case 'ticketdetail':
                    $id = MJTC_request::MJTC_getVar('majesticsupportid');
                    majesticsupport::$_data['permission_granted'] = true;
                    majesticsupport::$_data['user_staff'] = false;
                    if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                        majesticsupport::$_data['user_staff'] = true;
                        majesticsupport::$_data['permission_granted'] = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('View Ticket');
                    }
                    if (majesticsupport::$_data['permission_granted']) {
                        MJTC_includer::MJTC_getModel('ticket')->getTicketForDetail($id);
                        //check if envato license support has expired
                        if(in_array('envatovalidation', majesticsupport::$_active_addons) && !empty(majesticsupport::$_data[0]->envatodata)){
                            $envlicense = json_decode(majesticsupport::$_data[0]->envatodata, true);
                            if(!empty($envlicense['supporteduntil']) && date_i18n('Y-m-d') > date_i18n('Y-m-d',MJTC_majesticsupportphplib::MJTC_strtotime($envlicense['supporteduntil']))){
                                MJTC_message::MJTC_setMessage(esc_html(__('Support for this Envato license has expired', 'majestic-support')), 'error');
                            }
                            majesticsupport::$_data[0]->envatodata = $envlicense;
                        }
                    }
                    break;
                case 'myticket':
                    $list = MJTC_request::MJTC_getVar('list');
                    MJTC_includer::MJTC_getModel('ticket')->getMyTickets($list);
                    break;

            }
            $module = (is_admin()) ? 'page' : 'mjsmod';
            $module = MJTC_request::MJTC_getVar($module, null, 'ticket');
            $module = MJTC_majesticsupportphplib::MJTC_str_replace('majesticsupport_', '', $module);
            MJTC_includer::MJTC_include_file($layout, $module);
        }
    }

    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'majesticsupport')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'mstask')
            return false;
        else
            return true;
    }

    function closeticket() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'close-ticket') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('ticketid');
        $internalid = MJTC_request::MJTC_getVar('internalid');
        MJTC_includer::MJTC_getModel('ticket')->closeTicket($id, $internalid);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=tickets");
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail','majesticsupportid'=>$id));
        }
        wp_redirect($url);
        exit;
    }

    function lockticket() {
        $id = MJTC_request::MJTC_getVar('ticketid');
        MJTC_includer::MJTC_getModel('ticket')->lockTicket($id);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($id));
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail', 'majesticsupportid'=>$id));
        }
        wp_redirect($url);
        exit;
    }

    function unlockticket() {
        $id = MJTC_request::MJTC_getVar('ticketid');
        MJTC_includer::MJTC_getModel('ticket')->unLockTicket($id);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($id));
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail', 'majesticsupportid'=>$id));
        }
        wp_redirect($url);
        exit;
    }

    static function saveticket() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-ticket') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        $result = MJTC_includer::MJTC_getModel('ticket')->storeTickets($data);
        if (is_admin()) {
            if($result == false){
                $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=addticket");
				if(in_array('multiform', majesticsupport::$_active_addons)){
					$formid = $data['multiformid'];
					$url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=addticket&formid=".esc_attr($formid));
				}	
            }else{
                $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=tickets");
            }
        } else {
            if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid() == 0) { // visitor
                if ($result == false) { // error on captcha or ticket validation
                    $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'addticket'));
					if(in_array('multiform', majesticsupport::$_active_addons)){
						$formid = $data['multiformid'];
						$url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'addticket', 'formid'=> $formid));
					}	
                } else { // all things perfect
                    $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'visitormessagepage'));
                }
            } else {
                if ($result == false) { // error on captcha or ticket validation
                    $addticket = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'staffaddticket' : 'addticket';
                    $module1 = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'agent' : 'ticket';
                    $url = majesticsupport::makeUrl(array('mjsmod'=>$module1, 'mjslay'=>$addticket));
					if(in_array('multiform', majesticsupport::$_active_addons)){
						$formid = $data['multiformid'];
						$url = majesticsupport::makeUrl(array('mjsmod'=>$module1, 'mjslay'=>$addticket, 'formid'=> $formid));
					}	
                } else {
                    $myticket = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'staffmyticket' : 'myticket';
                    $module1 = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'agent' : 'ticket';
                    $url = majesticsupport::makeUrl(array('mjsmod'=>$module1, 'mjslay'=>$myticket));
                }
            }
        }
        if($result == false){
            MJTC_formfield::MJTC_setFormData($data);
        }
        wp_redirect($url);
        exit;
    }

    static function transferdepartment() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'transfer-department') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        MJTC_includer::MJTC_getModel('ticket')->tickDepartmentTransfer($data);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']));
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail', 'majesticsupportid'=>$data['ticketid']));
        }
        wp_redirect($url);
        exit;
    }

    static function assigntickettostaff() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'assign-ticket-to-staff') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        MJTC_includer::MJTC_getModel('ticket')->assignTicketToStaff($data);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']));
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail', 'majesticsupportid'=>$data['ticketid']));
        }
        wp_redirect($url);
        exit;
    }

    static function deleteticket() {
        $id = MJTC_request::MJTC_getVar('ticketid');
        $internalid = MJTC_request::MJTC_getVar('internalid');
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-ticket') ) {
            die( 'Security check Failed' );
        }
        MJTC_includer::MJTC_getModel('ticket')->removeTicket($id, $internalid);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=tickets");
        } elseif ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'agent', 'mjslay'=>'staffmyticket'));
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'myticket'));
        }
        wp_redirect($url);
        exit;
    }

    static function enforcedeleteticket() {
        $id = MJTC_request::MJTC_getVar('ticketid');
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'enforce-delete-ticket') ) {
            die( 'Security check Failed' );
        }
        MJTC_includer::MJTC_getModel('ticket')->removeEnforceTicket($id);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=tickets");
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'myticket'));
        }
        wp_redirect($url);
        exit;
    }

    static function changepriority() {
        $id = MJTC_request::MJTC_getVar('ticketid');
        $priorityid = MJTC_request::MJTC_getVar('priority');
        MJTC_includer::MJTC_getModel('ticket')->changeTicketPriority($id, $priorityid);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . esc_attr($id));
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail', 'majesticsupportid'=>$id));
        }
        wp_redirect($url);
        exit;
    }

    static function reopenticket() { // for user
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'reopen-ticket') ) {
            die( 'Security check Failed' );
        }
        $ticketid = MJTC_request::MJTC_getVar('ticketid');
        $internalid = MJTC_request::MJTC_getVar('internalid');
        $data['ticketid'] = $ticketid;
        $data['internalid'] = $internalid;
        MJTC_includer::MJTC_getModel('ticket')->reopenTicket($data);
        $url = "&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_ticket" . esc_attr($url));
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail', 'majesticsupportid'=>$data['ticketid']));
        }
        wp_redirect($url);
        exit;
    }

    static function actionticket() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'action-ticket') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        /* to handle actions */
        switch ($data['actionid']) {
            case 1: /* Change Priority Ticket */
                MJTC_includer::MJTC_getModel('ticket')->changeTicketPriority($data['ticketid'], $data['priority']);
                $url = "&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']);
                break;
            case 2: /* close ticket */
                MJTC_includer::MJTC_getModel('ticket')->closeTicket($data['ticketid']);
                $url = "&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']);
                break;
            case 3: /* Reopen Ticket */
                MJTC_includer::MJTC_getModel('ticket')->reopenTicket($data);
                $url = "&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']);
                break;
            case 4: /* Lock Ticket */
                if(in_array('actions', majesticsupport::$_active_addons)){
                    MJTC_includer::MJTC_getModel('actions')->lockTicket($data['ticketid']);
                    $url = "&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']);
                }
                break;
            case 5: /* Unlock ticket */
                if(in_array('actions', majesticsupport::$_active_addons)){
                    MJTC_includer::MJTC_getModel('actions')->unLockTicket($data['ticketid']);
                    $url = "&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']);
                }
                break;
            case 6: /* Banned Email */
                if(in_array('banemail', majesticsupport::$_active_addons)){
                    MJTC_includer::MJTC_getModel('ticket')->banEmail($data);
                    $url = "&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']);
                }
                break;
            case 7: /* Unban Email */
                if(in_array('banemail', majesticsupport::$_active_addons)){
                    MJTC_includer::MJTC_getModel('ticket')->unbanEmail($data);
                    $url = "&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']);
                }
                break;
            case 8: /* Mark over due */
                if(in_array('overdue', majesticsupport::$_active_addons)){
                    MJTC_includer::MJTC_getModel('overdue')->markOverDueTicket($data);
                    $url = "&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']);
                }
                break;
            case 9: /* In Progress */
                if(in_array('actions', majesticsupport::$_active_addons)){
                    MJTC_includer::MJTC_getModel('ticket')->markTicketInProgress($data);
                    $url = "&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']);
                }
                break;
            case 10: /* ban Email & close ticket */
                MJTC_includer::MJTC_getModel('ticket')->banEmailAndCloseTicket($data);
                $url = "&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']);
                break;
            case 11: /* unMark over due */
                if(in_array('overdue', majesticsupport::$_active_addons)){
                    MJTC_includer::MJTC_getModel('overdue')->unMarkOverDueTicket($data);;
                    $url = "&mjslay=ticketdetail&majesticsupportid=" . esc_attr($data['ticketid']);
                }
                break;
        }

        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_ticket" . $url);
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail', 'majesticsupportid'=>$data['ticketid']));
        }
        wp_redirect($url);
        exit;
    }

    static function showticketstatus() {
        $token = MJTC_request::MJTC_getVar('token');
        if ($token == null) { // in case it come from ticket status form
            $nonce = MJTC_request::MJTC_getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'show-ticket-status') ) {
                die( 'Security check Failed' );
            }
            $emailaddress = MJTC_request::MJTC_getVar('email');
            $trackingid = MJTC_request::MJTC_getVar('ticketid');
            $token = MJTC_includer::MJTC_getModel('ticket')->createTokenByEmailAndTrackingId($emailaddress, $trackingid);
        }
        MJTC_majesticsupportphplib::MJTC_setcookie('majestic-support-token-tkstatus',$token ,0, COOKIEPATH);
        if ( SITECOOKIEPATH != COOKIEPATH ){
            MJTC_majesticsupportphplib::MJTC_setcookie('majestic-support-token-tkstatus',$token ,0, SITECOOKIEPATH);
        }
        $ticketid = MJTC_includer::MJTC_getModel('ticket')->getTicketidForVisitor($token);
        if ($ticketid) {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail', 'majesticsupportid'=>$ticketid));
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketstatus'));
            MJTC_message::MJTC_setMessage(esc_html(__('Record not found', 'majestic-support')), 'error');
        }
        wp_redirect($url);
        exit;
    }

    static function downloadall() {
        $id = MJTC_request::MJTC_getVar('id');
        MJTC_includer::MJTC_getModel('attachment')->getAllDownloads();
        if (is_admin()) {
          $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail");
          } else {
          $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'ticketdetail','majesticsupportid'=>'$id','mspageid'=>majesticsupport::getPageid()));
          }
          wp_redirect($url);
          exit;
    }
    static function downloadallforreply() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'download-all-for-reply') ) {
            die( 'Security check Failed' );
        }
        MJTC_includer::MJTC_getModel('attachment')->getAllReplyDownloads();
        if (is_admin()) {
          $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail");
          } else {
          $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'ticketdetail','majesticsupportid'=>'$id','mspageid'=>majesticsupport::getPageid()));
          }
          wp_redirect($url);
          exit;
    }

    function downloadbyid(){
        $id = MJTC_request::MJTC_getVar('id');
        MJTC_includer::MJTC_getModel('attachment')->getDownloadAttachmentById($id);
    }


    function downloadbyname(){
        $name = MJTC_request::MJTC_getVar('name');
        $id = MJTC_request::MJTC_getVar('id');
        MJTC_includer::MJTC_getModel('attachment')->getDownloadAttachmentByName($name,$id);
    }

    function mergeticket() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'merge-ticket') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        MJTC_includer::MJTC_getModel('mergeticket')->storeMergeTicket($data);
        if(is_admin()){
             $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" .esc_attr($data['secondaryticket']));
        }else if( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()){
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'ticketdetail','majesticsupportid'=>$data['secondaryticket']));
        }
        wp_redirect($url);
        exit;
    }
}
$ticketController = new MJTC_ticketController();
?>
