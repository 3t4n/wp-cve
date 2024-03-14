<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSlayout {

    static function getNoRecordFound($message = null, $linkarray = array()) {        
        if($message == null){
            $message = __('Could not find any matching results', 'js-jobs');
        }
        $html = '
                <div class="js_job_error_messages_wrapper">
                    <div class="message1">
                        <span>
                            ' . __("Oops...", "js-jobs") . '
                        </span>
                    </div>    
                    <div class="message2">
                         <span class="img">
                        <img class="js_job_messages_image" src="' . JSJOBS_PLUGIN_URL . 'includes/images/norecordfound.png"/>
                         </span> 
                         <span class="message-text">
                            ' . $message . '
                         </span>
                    </div>
                    <div class="footer">';
                        if(!empty($linkarray)){
                            foreach($linkarray AS $link){
                                if( isset($link['text']) && $link['text'] != ''){
                                    $html .= '<a href="' . $link['link'] . '">' . $link['text'] . '</a>';
                                }
                            }
                        }
        $html .=    '</div>
                </div>
        ';
        echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
    }

    static function getAdminPopupNoRecordFound() {
        $html = '
                <div class="jsjobs-popup-norecordfound">
                    <img class="jsjobmessages_image" src="' . JSJOBS_PLUGIN_URL . 'includes/images/no-record-admin.png"/>
                    '.__("No record found","js-jobs").'
                </div>
		';
        echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
    }

    static function getNoRecordFoundInSpecialCase() {
        if (is_admin()) {
            $link = 'admin.php?page=jsjobs_jsjobs';
        } else {
            $link = get_the_permalink();
        }
        $html = '
                <div class="js_job_error_in_speacial_case_messages_wrapper">
                    <img src="' . JSJOBS_PLUGIN_URL . 'includes/images/no record icon.png"/>
                    <span class="error-text">' . __('Record Not Found', 'js-jobs') . '</span>
                </div>
        ';
        echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
    }

    static function getSystemOffline() {
        $offline_text = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline_text');
        $html = '
            <div id="jsjobs-main-up-wrapper">
                <div class="js_job_error_messages_wrapper">
                    <div class="message1">
                        <span>
                            ' . __("Oops...", "js-jobs") . '
                        </span>
                    </div>    
                    <div class="message2">
                         <span class="img">
                        <img class="js_job_messages_image" src="' . JSJOBS_PLUGIN_URL . 'includes/images/offline.png"/>
                         </span> 
                         <span class="message-text">
                            ' . $offline_text . '
                         </span>
                    </div>
                    <div class="footer">
                    </div>
                </div>
            </div>
        ';
        echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
    }

    static function getUserDisabledMsg() {
        $html = '
            <div id="jsjobs-main-up-wrapper">
                <div class="js_job_error_messages_wrapper disableduser">
                    <div class="message1">
                    	<span>
                            ' . __("Oops...", "js-jobs") . '
                    	</span>
                    </div>    
                    <div class="message2">
                     	 <span class="img">
                            <img class="js_job_messages_image" src="' . JSJOBS_PLUGIN_URL . 'includes/images/disable.png"/>
                     	 </span> 
                     	 <span class="message-text">
                            ' . __('Your account is disabled, please contact system administrator!', 'js-jobs') . '
                     	 </span>
                    </div>
                    <div class="footer">
                    </div>
                </div>
            </div>
		';
        echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
    }

    static function getUserGuest() {
        $html = '<div class="js_job_error_messages_wrapper">
                    <div class="message1">
                        <span>
                            ' . __("Oops...", "js-jobs") . '
                        </span>
                    </div>    
                    <div class="message2">
                         <span class="img">
                        <img class="js_job_messages_image" src="' . JSJOBS_PLUGIN_URL . 'includes/images/notloginicon.png"/>
                         </span> 
                         <span class="message-text">
                            ' . __('To Access This Page Please Login', 'js-jobs') . '
                         </span>
                    </div>
                    <div class="footer">
                        <a href="' . get_the_permalink() . '">' . __('Back to control panel', 'js-jobs') . '</a>
                    </div>
                </div>
        ';
        echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
    }

    static function getRegistrationDisabled() {
        $html = '<div class="js_job_error_messages_wrapper">
                    <div class="message1">
                    	<span>
                    		' . __("Oops...", "js-jobs") . '
                    	</span>
                    </div>    
                    <div class="message3">
                     	 <span class="img">
                     	<img class="js_job_messages_image" src="' . JSJOBS_PLUGIN_URL . 'includes/images/disable.png"/>
                     	 </span> 
                     	 <span class="message-text">
                     	 	' . __('Registration is disabled by admin, please contact to system administrator', 'js-jobs') . '
                     	 </span>
                    </div>
                    <div class="footer">
                    </div>
                </div>
		';
        echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
    }

    static function setMessageFor($for, $link = null, $linktext = null, $return = 0) {
        $image = null;
        $description = '';
        switch ($for) {
            case '1': // User is guest
                $description = __('You are not logged in', 'js-jobs');
                break;
            case '2': // User is job seeker
                $description = __('Job seeker not allowed to perform this action', 'js-jobs');
                break;
            case '3': // User is employer
                $description = __('Employer not allowed to perform this action', 'js-jobs');
                break;
            case '4': // User is not allowed to do that b/c of credits
                $description = __('You do not have enough credits', 'js-jobs');
                break;
            case '5': // When employer is disabled from configuration 
                $description = __('Employer Is Disabled By Admin', 'js-jobs');
                break;
            case '6': // When job/company/resume is not approved or expired 
                $description = __('The page you are looking for no longer exists', 'js-jobs');
                break;
            case '7': // Employer not allowed in jobseeker area
                $description = __('Employer not allowed in job seeker area', 'js-jobs');
                break;
            case '8': // Already loged in 
                $description = __('You are already logged in', 'js-jobs');
                break;
            case '9': // User have no role
                $description = __('Please select your role', 'js-jobs');
                break;
            case '10': // User have no role
                $description = __('You are not allowed', 'js-jobs');
                break;
        }
        $html = JSJOBSlayout::getUserNotAllowed($description, $link, $linktext, $image, $return);
        if ($return == 1) {
            return $html;
        }
    }

    static function getUserNotAllowed($description, $link, $linktext, $image, $return = 0) {
        $html = '
            <div id="jsjobs-main-up-wrapper">
                <div class="js_job_error_messages_wrapper">
                    <div class="message1">
                        <span>
                            ' . __("Oops...", "js-jobs") . '
                        </span>
                    </div>    
                    <div class="message2">
                         <span class="img">
                        <img class="js_job_messages_image" src="' . JSJOBS_PLUGIN_URL . 'includes/images/notallow.png"/>
                         </span> 
                         <span class="message-text">
                            ' . $description . '
                         </span>
                    </div>
                    <div class="footer">
                    ';
        if($linktext == null){
            $linktext = "Login";
        }
        if ($link != null) {
            $html .= '<a href="' . $link . '">' . __($linktext,'js-jobs') . '</a>';
            if($linktext == "Login"){
                $rlink = jsjobs::makeUrl(array('jsjobsme'=>'user', 'jsjobslt'=>'userregister','jsjobspageid'=>jsjobs::getPageid()));
                $html .= '<a href="' . $rlink . '">' . __("Register",'js-jobs') . '</a>';
            }
        }
        $html .= '
                    </div>
                </div>
            </div>
        ';
        if ($return == 0) {
            echo wp_kses($html, JSJOBS_ALLOWED_TAGS);

        } else {
            return $html;
        }
    }

    static function getUserAlreadyLoggedin( $link ) {
        $html = '
            <div id="jsjobs-main-up-wrapper">
                <div class="js_job_error_messages_wrapper">
                    <div class="message1">
                    	<span>
                    		' . __("Oops...", "js-jobs") . '
                    	</span>
                    </div>    
                    <div class="message2">
                     	 <span class="img">
                     	<img class="js_job_messages_image" src="' . JSJOBS_PLUGIN_URL . 'includes/images/notallow.png"/>
                     	 </span> 
                     	 <span class="message-text">
                     	 	' . __('You are already logged in', 'js-jobs') . '
                     	 </span>
                    </div>
                    <div class="footer">
                    ';
        $html .= '<a href="' . $link. '">' . __('Logout','js-jobs') . '</a>';
        $html .= '
                    </div>
                </div>
            </div>
		';
        echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
    }

}

?>
