<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCommonController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('common')->getMessagekey();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'newinjsjobs');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'newinjsjobs':
                    if(JSJOBSincluder::getObjectClass('user')->isguest()){
                        $link = get_permalink();
                        $linktext = __('Login','js-jobs');
                        jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                        jsjobs::$_error_flag = true;
                    }
                break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'common');
            $module = jsjobslib::jsjobs_str_replace('jsjobs_', '', $module);
            JSJOBSincluder::include_file($layout, $module);
        }
    }

    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jsjobs')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jsjobtask')
            return false;
        else
            return true;
    }

    function makedefault() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'make-default') ) {
            die( 'Security check Failed' );
        }
        $id = JSJOBSrequest::getVar('id');
        $for = JSJOBSrequest::getVar('for'); // table name
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $result = JSJOBSincluder::getJSModel('common')->setDefaultForDefaultTable($id, $for);
        $object = $this->getpageandlayoutname($for);
        $msg = JSJOBSMessages::getMessage($result, $object['page']);
        $url = admin_url("admin.php?page=jsjobs_" . $object['page'] . "&jsjobslt=" . $object['jsjobslt']);
        $this->setMessageKey($for);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        $url = wp_nonce_url($url,$object['nonce']);
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function defaultorderingup() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'field-up') ) {
             die( 'Security check Failed' );
        }
        $id = JSJOBSrequest::getVar('id');
        $for = JSJOBSrequest::getVar('for'); //table name
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $result = JSJOBSincluder::getJSModel('common')->setOrderingUpForDefaultTable($id, $for);
        $object = $this->getpageandlayoutname($for);
        $msg = JSJOBSMessages::getMessage($result, $object['page']);
        $url = admin_url("admin.php?page=jsjobs_" . $object['page'] . "&jsjobslt=" . $object['jsjobslt']);
        $this->setMessageKey($for);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        $url = wp_nonce_url($url,$object['nonce']);
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function defaultorderingdown() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'field-down') ) {
             die( 'Security check Failed' );
        }
        $id = JSJOBSrequest::getVar('id');
        $for = JSJOBSrequest::getVar('for'); // table name
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $result = JSJOBSincluder::getJSModel('common')->setOrderingDownForDefaultTable($id, $for);
        $object = $this->getpageandlayoutname($for);
        $msg = JSJOBSMessages::getMessage($result, $object['page']);
        $url = admin_url("admin.php?page=jsjobs_" . $object['page'] . "&jsjobslt=" . $object['jsjobslt']);
        $this->setMessageKey($for);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        $url = wp_nonce_url($url,$object['nonce']);
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function getpageandlayoutname($for) { // for tablename
        switch ($for) {
            case 'jobtypes' : $object['page'] = "jobtype";
                $object['jsjobslt'] = "jobtypes";
                $object['nonce'] = "jobtype";
                break;
            case 'shifts' : $object['page'] = "shift";
                $object['jsjobslt'] = "shifts";
                $object['nonce'] = "shift";
                break;
            case 'ages' : $object['page'] = "age";
                $object['jsjobslt'] = "ages";
                $object['nonce'] = "age";
                break;
            case 'careerlevels' : $object['page'] = "careerlevel";
                $object['jsjobslt'] = "careerlevels";
                $object['nonce'] = "careerlevel";
                break;
            case 'salaryrange' : $object['page'] = "salaryrange";
                $object['jsjobslt'] = "salaryrange";
                $object['nonce'] = "salaryrange";
                break;
            case 'salaryrangetypes' : $object['page'] = "salaryrangetype";
                $object['jsjobslt'] = "salaryrangetype";
                $object['nonce'] = "salaryrangetype";
                break;
            case 'currencies' : $object['page'] = "currency";
                $object['jsjobslt'] = "currency";
                $object['nonce'] = "currency";
                break;
            case 'experiences' : $object['page'] = "experience";
                $object['jsjobslt'] = "experience";
                $object['nonce'] = "experience";
                break;
            case 'heighesteducation' : $object['page'] = "highesteducation";
                $object['jsjobslt'] = "highesteducations";
                $object['nonce'] = "highesteducation";
                break;
            case 'categories' : $object['page'] = "category";
                $object['jsjobslt'] = "categories";
                $object['nonce'] = "category";
                break;
            case 'subcategories' :
                $object['page'] = "subcategory";
                $object['nonce'] = "subcategory";
                $categoryid = get_option( 'jsjobs_sub_categoryid' );
                $object['jsjobslt'] = "subcategories&categoryid=" . $categoryid;
                break;
            default : $object['page'] = $object['jsjobslt'] = $object['nonce'] = $for;
                break;
        }
        return $object;
    }

    function savenewinjsjobs() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'new-in-jsjobs') ) {
            die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('common')->saveNewInJSJobs($data);
        if ($data['desired_module'] == 'common' && $data['desired_layout'] == 'newinjsjobs') {
            if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                $data['desired_module'] = 'job seeker';
            } else {
                $data['desired_module'] = 'employer';
            }
            $data['desired_layout'] = 'controlpanel';
        }
        $url = jsjobs::makeUrl(array('jsjobsme'=>$data['desired_module'], 'jsjobslt'=>$data['desired_layout']));
        if (isset($data['desired_nonce']) && $data['desired_nonce'] != '') {
            $url = wp_nonce_url($url, $data['desired_nonce']);
        }
        $msg = JSJOBSMessages::getMessage($result, 'userrole');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    private function setMessageKey($table_name){
        if ($table_name == "jobtypes") {
            $this->_msgkey = JSJOBSincluder::getJSModel('jobtype')->getMessagekey();
        } else if ($table_name == "jobstatus") {
            $this->_msgkey = JSJOBSincluder::getJSModel('jobstatus')->getMessagekey();
        } else if ($table_name == "ages") {
            $this->_msgkey = JSJOBSincluder::getJSModel('age')->getMessagekey();
        } else if ($table_name == "currencies") {
            $this->_msgkey = JSJOBSincluder::getJSModel('currency')->getMessagekey();
        } else if ($table_name == "heighesteducation") {
            $this->_msgkey = JSJOBSincluder::getJSModel('highesteducation')->getMessagekey();
        } else if ($table_name == "shifts") {
            $this->_msgkey = JSJOBSincluder::getJSModel('shift')->getMessagekey();
        } else if ($table_name == "careerlevels") {
            $this->_msgkey = JSJOBSincluder::getJSModel('careerlevel')->getMessagekey();
        } else if ($table_name == "experiences") {
            $this->_msgkey = JSJOBSincluder::getJSModel('experience')->getMessagekey();
        } else if ($table_name == "salaryrange") {
            $this->_msgkey = JSJOBSincluder::getJSModel('salaryrange')->getMessagekey();
        } else if ($table_name == "salaryrangetypes") {
            $this->_msgkey = JSJOBSincluder::getJSModel('salaryrangetype')->getMessagekey();
        } else if ($table_name == "categories") {
            $this->_msgkey = JSJOBSincluder::getJSModel('category')->getMessagekey();
        }else {
            $this->_msgkey = JSJOBSincluder::getJSModel('common')->getMessagekey();
        }
    }

}

$JSJOBSCommonController = new JSJOBSCommonController;
?>
