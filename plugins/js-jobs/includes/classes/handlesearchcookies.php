<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBShandlesearchcookies {
    public $_jsjob_search_array;
    public $_callfrom;
    public $_setcookies;

    function __construct( ) {
        $this->_jsjob_search_array = array();
        $this->_callfrom = 3;
        $this->_setcookies = false;
        $this->init();
    }

    function init(){
        $isadmin = is_admin();
        $jstlay = '';
        if(isset($_REQUEST['page'])){
            $jstlay = jsjobs::sanitizeData($_REQUEST['page']);
        }elseif(isset($_REQUEST['jsjobslt'])){
            $jstlay = jsjobs::sanitizeData($_REQUEST['jsjobslt']);
        }elseif(isset($_REQUEST['jsjobslay'])){
            $jstlay = jsjobs::sanitizeData($_REQUEST['jsjobslay']);
        }
        $layoutname = jsjobslib::jsjobs_explode("jsjobs_", $jstlay);
        if(isset($layoutname[1])){
            $jstlay = $layoutname[1];
        }

        if(isset($_REQUEST['JSJOBS_form_search']) && $_REQUEST['JSJOBS_form_search'] == 'JSJOBS_SEARCH'){
            $this->_callfrom = 1;
        }elseif(JSJOBSrequest::getVar('pagenum', 'get', null) != null){
            $this->_callfrom = 2;
        }
        switch($jstlay){
            case 'activitylog':
                $nonce = JSJOBSrequest::getVar('_wpnonce');
                if (! wp_verify_nonce( $nonce, 'activity-logs') ) {
                    die( 'Security check Failed' ); 
                }
                $this->searchFormDataForActivitylogData($jstlay);
            break;
            case 'jobs':
            case 'job':
                $this->searchdataforjobs();
            break;
            case 'myresume':
            case 'resumes':
            case 'resume':
            case 'resumesearch':
                $this->searchFormDataForResume($jstlay);
            break;
            case 'appliedjobs': // for jobseeker case
            case 'myjobs': // For employer case
            case 'activitylog': // For activity log
                $this->searchFormDataForCommonData($jstlay);
            break;
            case 'mycompany': // For employer case
            case 'company': // For admin case
            case 'companies': // For jobseeker front end case
            case 'mycompanies': // For Employer front end case
            case 'jsjobs_company': // For Admin 
                $this->searchFormDataForCompanies();
            break;
            case 'careerlevel':
                if(is_admin())
                    $this->searchFormDataForCareerLevel();
            break;
            case 'category':
                if(is_admin())
                    $this->searchFormDataForCategory();
            break;
            case 'city':
                if(is_admin())
                    $this->searchFormDataForCity();
            break;
            case 'country':
                if(is_admin())
                    $this->searchFormDataForCountry();
            break;
            case 'currency':
            case 'fieldordering':
            case 'highesteducation':
            case 'user':
            case 'state':
            case 'slug':
            case 'jobstatus':
            case 'jobtype':
            case 'shift':
            case 'salaryrange':
            case 'salaryrangetype':
            case 'age':
            case 'coverletter':
            case 'departments':
            case 'experience':
            case 'jobalert':
                if(is_admin()){
                    $this->setSearchFormData($jstlay);
                }
            break;
            default:
                jsjobs::removeusersearchcookies();
            break;
        }

        if($this->_setcookies){
            jsjobs::setusersearchcookies($this->_setcookies,$this->_jsjob_search_array);
        }
    }

    private function searchdataforjobs(){
        $search_userfields = array();
        // $search_userfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsForSearch(1);
        if($this->_callfrom == 1){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'job') ) {
                JSJOBSincluder::getJSModel('common')->js_verify_nonce();
            }
            if(is_admin()){
                $this->_jsjob_search_array = JSJOBSincluder::getJSModel('job')->getAdminJobSearchFormData($search_userfields);
            }else{
                $this->_jsjob_search_array = JSJOBSincluder::getJSModel('job')->getFrontSideJobSearchFormData($search_userfields);
            }
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'job') ) {
                JSJOBSincluder::getJSModel('common')->js_verify_nonce();
            }
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('job')->getCookiesSavedSearchDataJob($search_userfields);
        }else{
            jsjobs::removeusersearchcookies();
        }
        JSJOBSincluder::getJSModel('job')->setSearchVariableForJob($this->_jsjob_search_array,$search_userfields);
    }

    private function searchFormDataForResume($layout){
        if($this->_callfrom == 1){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'resume') ) {
                JSJOBSincluder::getJSModel('common')->js_verify_nonce();
            }
            if(is_admin()){
                $this->_jsjob_search_array = JSJOBSincluder::getJSModel('resume')->getAdminResumeSearchFormData();
            }else{
                $this->_jsjob_search_array = JSJOBSincluder::getJSModel('resume')->getMyResumeSearchFormData($layout);
            }
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'resume') ) {
                JSJOBSincluder::getJSModel('common')->js_verify_nonce();
            }
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('resume')->getResumeSavedCookiesData($layout);
        }else{
            jsjobs::removeusersearchcookies();
        }
        if(is_admin()){
            JSJOBSincluder::getJSModel('resume')->setSearchVariableForAdminResume($this->_jsjob_search_array,$layout);
        }else{
            JSJOBSincluder::getJSModel('resume')->setSearchVariableForMyResume($this->_jsjob_search_array,$layout);
        }
    }

    private function searchFormDataForCommonData($jstlay){
        if($this->_callfrom == 1){
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('common')->getSearchFormDataOnlySort($jstlay);
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('common')->getCookiesSavedOnlySortandOrder();
        }else{
            jsjobs::removeusersearchcookies();
        }
        JSJOBSincluder::getJSModel('common')->setSearchVariableOnlySortandOrder($this->_jsjob_search_array,$jstlay);
    }

    private function searchFormDataForActivitylogData($jstlay){
        if($this->_callfrom == 1){
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('activitylog')->getSearchFormActivitylogData();
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('activitylog')->getActivitylogSavedCookies();
        }else{
            jsjobs::removeusersearchcookies();
        }
        JSJOBSincluder::getJSModel('activitylog')->setSearchVariableOnlySortandOrder($this->_jsjob_search_array,$jstlay);
    }

    private function searchFormDataForCompanies(){
        if($this->_callfrom == 1){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'company') ) {
                JSJOBSincluder::getJSModel('common')->js_verify_nonce();
            }
            if(is_admin()){
                $this->_jsjob_search_array = JSJOBSincluder::getJSModel('company')->getSearchFormAdminCompanyData();
            }else{
                $this->_jsjob_search_array = JSJOBSincluder::getJSModel('company')->getSearchFormDataMyCompany();
            }
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'company') ) {
                JSJOBSincluder::getJSModel('common')->js_verify_nonce();
            }
            if(is_admin()){
                $this->_jsjob_search_array = JSJOBSincluder::getJSModel('company')->getAdminCompanySavedCookies();
            }else{
                $this->_jsjob_search_array = JSJOBSincluder::getJSModel('company')->getCookiesSavedMyCompany();
            }
        }else{
            jsjobs::removeusersearchcookies();
        }
        if(is_admin()){
            JSJOBSincluder::getJSModel('company')->setAdminCompanySearchVariable($this->_jsjob_search_array);
        }else{
            JSJOBSincluder::getJSModel('company')->setSearchVariableMyCompany($this->_jsjob_search_array);
        }
    }

    private function searchFormDataForCareerLevel(){
        if($this->_callfrom == 1){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'careerlevel') ) {
                die( 'Security check Failed' );
            }
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('careerlevel')->getSearchFormDataCareerLevel();
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'careerlevel') ) {
                // die( 'Security check Failed' );
            }
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('careerlevel')->getCookiesSavedCareerLevel();
        }else{
            jsjobs::removeusersearchcookies();
        }
        JSJOBSincluder::getJSModel('careerlevel')->setSearchVariableCareerLevel($this->_jsjob_search_array);
    }

    private function searchFormDataForCategory(){
        if($this->_callfrom == 1){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'category') ) {
                die( 'Security check Failed' );
            }
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('category')->getSearchFormDataCategory();
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'category') ) {
                die( 'Security check Failed' );
            }
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('category')->getCookiesSavedCategory();
        }else{
            jsjobs::removeusersearchcookies();
        }
        JSJOBSincluder::getJSModel('category')->setSearchVariableCategory($this->_jsjob_search_array);
    }

    private function searchFormDataForCity(){
        if($this->_callfrom == 1){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'city') ) {
                die( 'Security check Failed' );
            }
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('city')->getSearchFormDataCity();
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'city') ) {
                die( 'Security check Failed' );
            }
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('city')->getCookiesSavedCity();
        }else{
            jsjobs::removeusersearchcookies();
        }
        JSJOBSincluder::getJSModel('city')->setSearchVariableCity($this->_jsjob_search_array);
    }

    private function searchFormDataForCountry(){
        if($this->_callfrom == 1){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'country') ) {
                die( 'Security check Failed' );
            }
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('country')->getCountrySearchFormData();
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $nonce = JSJOBSrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'country') ) {
                die( 'Security check Failed' );
            }
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel('country')->getCountrySavedCookiesData();
        }else{
            jsjobs::removeusersearchcookies();
        }
        JSJOBSincluder::getJSModel('country')->setCountrySearchVariable($this->_jsjob_search_array);
    }

    private function setSearchFormData($module){
        if($this->_callfrom == 1){
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel($module)->getSearchFormData();
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $this->_jsjob_search_array = JSJOBSincluder::getJSModel($module)->getSavedCookiesDataForSearch();
        }else{
            jsjobs::removeusersearchcookies();
        }
        JSJOBSincluder::getJSModel($module)->setSearchVariableForSearch($this->_jsjob_search_array);
    }
}

?>
