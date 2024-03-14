<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSMessages {
    /*
     * setLayoutMessage
     * @params $message = Your message to display
     * @params $type = Messages types => 'updated','error','update-nag'
     */

    public static $counter;

    public static function setLayoutMessage($message, $type, $msgkey) {
        JSJOBSincluder::getObjectClass('wpjobnotification')->addSessionNotificationDataToTable($message,$type,'notification',$msgkey);
    }

    public static function getLayoutMessage($msgkey) {
        $frontend = (is_admin()) ? '' : 'frontend';
        $divHtml = '';
        $notificationdata = JSJOBSincluder::getObjectClass('wpjobnotification')->getNotificationDatabySessionId('notification',$msgkey,true);
        if(!isset($notificationdata['msg'][0]) && !isset($notificationdata['type'][0])) return;
        for ($i = 0; $i < COUNT($notificationdata['msg']); $i++){
            if (isset($notificationdata['msg'][$i]) && isset($notificationdata['type'][$i])) {
                if(is_admin()){
                    $divHtml .= '<div class="frontend ' . $notificationdata['type'][$i] . '"><p>' . $notificationdata['msg'][$i] . '</p></div>';
                }else{
                    if(jsjobs::$theme_chk != 0){
                        if($notificationdata['type'][$i] == 'updated'){
                            $alert_class = 'success';
                            $img_name = 'job-alert-successful.png';
                        }elseif($notificationdata['type'][$i] == 'saved'){
                            $alert_class = 'success';
                            $img_name = 'job-alert-successful.png';
                        }elseif($notificationdata['type'][$i] == 'saved'){
                                    //$alert_class = 'info';
                                    //$alert_class = 'warning';
                        }elseif($notificationdata['type'][$i] == 'error'){
                            $alert_class = 'danger';
                            $img_name = 'job-alert-unsuccessful.png';
                        }
                        $divHtml .= '<div class="alert alert-' . $alert_class . '" role="alert" id="autohidealert">
                                        <img class="leftimg" src="'.JSJOBS_PLUGIN_URL.'includes/images/'.$img_name.'" />
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        '. $notificationdata['msg'][$i] . '
                                    </div>';
                    }else{
                        $divHtml .= '<div class=" ' . $frontend . ' ' . $notificationdata['type'][$i] . '"><p>' . $notificationdata['msg'][$i] . '</p></div>';
                    }
                }
            }
        }

        echo wp_kses($divHtml, JSJOBS_ALLOWED_TAGS);
    }

    public static function getMSelectionEMessage() { // multi selection error message
        return __('Please first make a selection from the list', 'js-jobs');
    }

    public static function getMessage($result, $entity) {

        $msg['message'] = __('Unknown');
        $msg['status'] = "updated";
        $msg1 = JSJOBSMessages::getEntityName($entity);

        switch ($result) {
            case JSJOBS_INVALID_REQUEST:
                $msg['message'] = __('Invalid request', 'js-jobs');
                $msg['status'] = 'error';
                break;
            case JSJOBS_SAVED:
                $msg2 = __('has been successfully saved', 'js-jobs');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSJOBS_SAVE_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been saved', 'js-jobs');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSJOBS_DELETED:
                $msg2 = __('has been successfully deleted', 'js-jobs');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSJOBS_NOT_EXIST:
                $msg['status'] = "error";
                $msg['message'] = __('Record not exist', 'js-jobs');
                break;
            case JSJOBS_DELETE_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been deleted', 'js-jobs');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (JSJOBSMessages::$counter) {
                        if(JSJOBSMessages::$counter > 1){
                            $msg['message'] = JSJOBSMessages::$counter . ' ' . $msg['message'];
                        }
                    }
                }
                break;
            case JSJOBS_PUBLISHED:
                $msg2 = __('has been successfully published', 'js-jobs');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (JSJOBSMessages::$counter) {
                        if(JSJOBSMessages::$counter > 1){
                            $msg['message'] = JSJOBSMessages::$counter . ' ' . $msg['message'];
                        }
                    }
                }
                break;
            case JSJOBS_VERIFIED:
                $msg['message'] = __('transaction has been successfully verified', 'js-jobs');
                break;
            case JSJOBS_UN_VERIFIED:
                $msg['message'] = __('transaction has been successfully un-verified', 'js-jobs');
                break;
            case JSJOBS_VERIFIED_ERROR:
                $msg['message'] = __('transaction has not been successfully verified', 'js-jobs');
                break;
            case JSJOBS_UN_VERIFIED_ERROR:
                $msg['message'] = __('transaction has not been successfully un-verified', 'js-jobs');
                break;
            case JSJOBS_PUBLISH_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been published', 'js-jobs');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (JSJOBSMessages::$counter) {
                            $msg['message'] = JSJOBSMessages::$counter . ' ' . $msg['message'];
                    }
                }
                break;
            case JSJOBS_UN_PUBLISHED:
                $msg2 = __('has been successfully unpublished', 'js-jobs');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (JSJOBSMessages::$counter) {
                        if(JSJOBSMessages::$counter > 1){
                            $msg['message'] = JSJOBSMessages::$counter . ' ' . $msg['message'];
                        }
                    }
                }
                break;
            case JSJOBS_UN_PUBLISH_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been unpublished', 'js-jobs');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (JSJOBSMessages::$counter) {
                            $msg['message'] = JSJOBSMessages::$counter . ' ' . $msg['message'];
                    }
                }
                break;
            case JSJOBS_REQUIRED:
                $msg['message'] = __('Fields has been successfully required', 'js-jobs');
                break;
            case JSJOBS_REQUIRED_ERROR:
                $msg['status'] = "error";
                if (JSJOBSMessages::$counter) {
                    if (JSJOBSMessages::$counter == 1)
                        $msg['message'] = JSJOBSMessages::$counter . ' ' . __('Field has not been required', 'js-jobs');
                    else
                        $msg['message'] = JSJOBSMessages::$counter . ' ' . __('Fields has not been required', 'js-jobs');
                }else {
                    $msg['message'] = __('Field has not been required', 'js-jobs');
                }
                break;
            case JSJOBS_NOT_REQUIRED:
                $msg['message'] = __('Fields has been successfully not required', 'js-jobs');
                break;
            case JSJOBS_NOT_REQUIRED_ERROR:
                $msg['status'] = "error";
                if (JSJOBSMessages::$counter) {
                    if (JSJOBSMessages::$counter == 1)
                        $msg['message'] = JSJOBSMessages::$counter . ' ' . __('Field has not been not required', 'js-jobs');
                    else
                        $msg['message'] = JSJOBSMessages::$counter . ' ' . __('Fields has not been not required', 'js-jobs');
                }else {
                    $msg['message'] = __('Field has not been not required', 'js-jobs');
                }
                break;
            case JSJOBS_ORDER_UP:
                $msg['message'] = __('Field order up successfully', 'js-jobs');
                break;
            case JSJOBS_ORDER_UP_ERROR:
                $msg['status'] = "error";
                $msg['message'] = __('Field order up error', 'js-jobs');
                break;
            case JSJOBS_ORDER_DOWN:
                $msg['message'] = __('Field order down successfully', 'js-jobs');
                break;
            case JSJOBS_ORDER_DOWN_ERROR:
                $msg['status'] = "error";
                $msg['message'] = __('Field order up error', 'js-jobs');
                break;
            case JSJOBS_REJECTED:
                $msg2 = __('has been rejected', 'js-jobs');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSJOBS_APPLY:
                $msg['status'] = "updated";
                $msg2 = __('Job applied successfully', 'js-jobs');
                $msg['message'] = $msg2;
                break;
            case JSJOBS_APPLY_ERROR:
                $msg2 = __('Error in applying job', 'js-jobs');
                $msg['message'] = $msg2;
                $msg['status'] = "error";
                break;
            case JSJOBS_REJECT_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been rejected', 'js-jobs');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSJOBS_APPROVED:
                $msg2 = __('has been approved', 'js-jobs');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSJOBS_APPROVE_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been approved', 'js-jobs');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (JSJOBSMessages::$counter) {
                        $msg['message'] = JSJOBSMessages::$counter . ' ' . $msg['message'];
                    }
                }
                break;
            case JSJOBS_SET_DEFAULT:
                $msg2 = __('has been set as default', 'js-jobs');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSJOBS_UNPUBLISH_DEFAULT_ERROR:
                $msg['status'] = "error";
                $msg['message'] = __('Unpublished field cannot set default', 'js-jobs');
                break;
            case JSJOBS_SET_DEFAULT_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been set as default', 'js-jobs');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSJOBS_STATUS_CHANGED:
                $msg2 = __('status has been updated', 'js-jobs');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSJOBS_STATUS_CHANGED_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been updated', 'js-jobs');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSJOBS_IN_USE:
                $msg['status'] = "error";
                $msg2 = __('in use cannot deleted', 'js-jobs');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSJOBS_ALREADY_EXIST:
                $msg['status'] = "error";
                $msg2 = __('already exist', 'js-jobs');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSJOBS_FILE_TYPE_ERROR:
                $msg['status'] = "error";
                $msg['message'] = __('File type error', 'js-jobs');
                break;
            case JSJOBS_FILE_SIZE_ERROR:
                $msg['status'] = "error";
                $msg['message'] = __('File size error', 'js-jobs');
                break;
            case JSJOBS_ENABLED:
                $msg['status'] = "updated";
                $msg2 = __('has been enabled', 'js-jobs');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                }
                break;
            case JSJOBS_DISABLED:
                $msg['status'] = "updated";
                $msg2 = __('has been disabled', 'js-jobs');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                }
                break;
        }
        return $msg;
    }

    static function getEntityName($entity) {
        $name = "";
        $entity = jsjobslib::jsjobs_strtolower($entity);
        switch ($entity) {
            case 'salaryrange':$name = __('Salary Range', 'js-jobs');
                break;
            case 'addressdata':$name = __('Address Data', 'js-jobs');
                break;
            case 'age':$name = __('Age', 'js-jobs');
                break;
            case 'careerlevel':case 'careerlevels':$name = __('Career level', 'js-jobs');
                break;
            case 'coverletter':$name = __('Cover Letter', 'js-jobs');
                break;
            case 'coverletters':$name = __('Cover Letter', 'js-jobs');
                break;
            case 'category':$name = __('Category', 'js-jobs');
                break;
            case 'city':$name = __('City', 'js-jobs');
                break;
            case 'company':
                    $name = __('Company', 'js-jobs');
                    if(JSJOBSMessages::$counter){
                        if(JSJOBSMessages::$counter >1){
                            $name = __('Companies', 'js-jobs');
                        }
                    }
                break;
            case 'country':$name = __('Country', 'js-jobs');
                break;
            case 'currency':$name = __('Currency', 'js-jobs');
                break;
            case 'customfield':
            case 'fieldordering':$name = __('Field', 'js-jobs');
                break;
            case 'department':case 'departments':$name = __('Department', 'js-jobs');
                break;
            case 'employerpackages':$name = __('Employer package', 'js-jobs');
                break;
            case 'experience':$name = __('Experience', 'js-jobs');
                break;
            case 'highesteducation':$name = __('Highest education', 'js-jobs');
                break;
            case 'job':
                $name = __('Job', 'js-jobs');
                if(JSJOBSMessages::$counter){
                    if(JSJOBSMessages::$counter >1){
                        $name = __('Jobs', 'js-jobs');
                    }
                }
                break;
            case 'jobstatus':$name = __('Job Status', 'js-jobs');
                break;
            case 'jobtype':$name = __('Job type', 'js-jobs');
                break;
            case 'resume':
                $name = __('Resume', 'js-jobs');
                if(JSJOBSMessages::$counter){
                    if(JSJOBSMessages::$counter >1){
                        $name = __('Resume', 'js-jobs');
                    }
                }
                break;
            case 'salaryrange':$name = __('Salary Range', 'js-jobs');
                break;
            case 'salaryrangetype':$name = __('Salary Range Type', 'js-jobs');
                break;
            case 'shift':$name = __('Shift', 'js-jobs');
                break;
            case 'state':$name = __('State', 'js-jobs');
                break;
            case 'user':$name = __('User', 'js-jobs');
                break;
            case 'userrole':$name = __('User role', 'js-jobs');
                break;
            case 'configuration':$name = __('Configuration', 'js-jobs');
                break;
            case 'emailtemplate':$name = __('Email Template', 'js-jobs');
                break;
            case 'jobsavesearch':$name = __('Job Search', 'js-jobs');
                break;
            case 'resumesearch':$name = __('Resume Search', 'js-jobs');
                break;
            case 'record':
                    $name = __('record', 'js-jobs').'('. __('s','js-jobs') .')';
                break;
            case 'slug':
                    $name = __('Slug', 'js-jobs').'('. __('s','js-jobs') .')';
                break;
            case 'prefix':
                    $name = __('Prefix', 'js-jobs').'('. __('s','js-jobs') .')';
                break;
        }
        return $name;
    }

}

?>
