<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

$layout = JSJOBSrequest::getVar('jsjobslt');
if ($layout == 'printresume' || $layout == 'pdf')
    return false; // b/c we have print and pdf layouts
$module = JSJOBSrequest::getVar('jsjobsme');
if(
    ($module == 'company' && $layout == 'addcompany') ||
    ($module == 'company' && $layout == 'mycompanies') ||
    ($module == 'credits' && $layout == 'employercredits') ||
    ($module == 'creditslog' && $layout == 'employercreditslog') ||
    ($module == 'credits' && $layout == 'ratelistemployer') ||
    ($module == 'departments' && $layout == 'adddepartment') ||
    ($module == 'departments' && $layout == 'mydepartments') ||
    ($module == 'departments' && $layout == 'viewdepartment') ||
    ($module == 'folder' && $layout == 'addfolder') ||
    ($module == 'folder' && $layout == 'myfolders') ||
    ($module == 'folder' && $layout == 'viewfolder') ||
    ($module == 'folderresume' && $layout == 'folderresume') ||
    ($module == 'job' && $layout == 'addjob') ||
    ($module == 'job' && $layout == 'myjobs') ||
    ($module == 'jobapply' && $layout == 'jobappliedresume') ||
    ($module == 'employer' && $layout == 'controlpanel') ||
    ($module == 'employer' && $layout == 'mystats') ||
    ($module == 'message' && $layout == 'employermessages') ||
    ($module == 'message' && $layout == 'jobmessages') ||
    ($module == 'purchasehistory' && $layout == 'employerpurchasehistory') ||
    ($module == 'resumesearch' && $layout == 'resumesearch') ||
    ($module == 'resumesearch' && $layout == 'resumesavesearch') || 
    ($module == 'resume' && $layout == 'resumebycategory') || 
    ($module == 'resume' && $layout == 'resumes')
){
    $menu = 'employer';
}elseif(
    ($module == 'company' && $layout == 'companies') ||
    ($module == 'company' && $layout == 'viewcompany') ||
    ($module == 'job' && $layout == 'newestjobs') ||
    ($module == 'job' && $layout == 'jobs') ||
    ($module == 'job' && $layout == 'shortlistedjobs') ||
    ($module == 'job' && $layout == 'viewjob') ||
    ($module == 'jsjobs' && $layout == 'login') ||
    ($module == 'resume' && $layout == 'viewresume') ||
    ($module == 'message' && $layout == 'sendmessage')
){
    if(JSJOBSincluder::getObjectClass('user')->isEmployer()){
        $menu = 'employer';
    }else{
        $menu = 'jobseeker';
    }
}elseif(
    ($module == 'coverletter' && $layout == 'addcoverletter') ||
    ($module == 'coverletter' && $layout == 'mycoverletters') ||
    ($module == 'coverletter' && $layout == 'viewcoverletter') ||
    ($module == 'credits' && $layout == 'jobseekercredits') ||
    ($module == 'credits' && $layout == 'ratelistjobseeker') ||
    ($module == 'creditslog' && $layout == 'jobseekercreditslog') ||
    ($module == 'job' && $layout == 'jobsbycategories') ||
    ($module == 'job' && $layout == 'jobsbytypes') ||
    ($module == 'job' && $layout == 'visitoraddjob') ||
    ($module == 'jobalert' && $layout == 'jobalert') ||
    ($module == 'jobapply' && $layout == 'myappliedjobs') ||
    ($module == 'jobsearch' && $layout == 'jobsearch') ||
    ($module == 'jobsearch' && $layout == 'jobsavesearch') ||
    ($module == 'jobseeker' && $layout == 'controlpanel') ||
    ($module == 'jobseeker' && $layout == 'mystats') ||
    ($module == 'message' && $layout == 'jobseekermessages') ||
    ($module == 'purchasehistory' && $layout == 'jobseekerpurchasehistory') ||
    ($module == 'resume' && $layout == 'addresume') ||
    ($module == 'resume' && $layout == 'myresumes') ||
    ($module == 'user' && $layout == 'userregister')
){
    $menu = 'jobseeker';
    
}else{
    $menu = 'jobseeker';
}

$div = '';
$config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('topmenu');
if ($menu == 'employer') {
    if (is_user_logged_in()) { // Login user
        if ($config_array['tmenu_emcontrolpanel'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'employer', 'jsjobslt'=>'controlpanel')),"dashboard"),
                'title' => __('Control Panel', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_emnewjob'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'addjob')),"formjob"),
                'title' => __('Add','js-jobs') .' '. __('Job', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_emmyjobs'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs')),"job"),
                'title' => __('My Jobs', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_emmycompanies'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'mycompanies')),"company"),
                'title' => __('My Companies', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_emsearchresume'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resumesearch', 'jsjobslt'=>'resumesearch')),"resumesearch"),
                'title' => __('Resume Search', 'js-jobs'),
            );
        }
    } else { // user is visitor
        if ($config_array['tmenu_vis_emcontrolpanel'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'employer', 'jsjobslt'=>'controlpanel')),"dashboard"),
                'title' => __('Control Panel', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_vis_emnewjob'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'addjob')),"formjob"),
                'title' => __('Add','js-jobs') .' '. __('Job', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_vis_emmyjobs'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs')),"job"),
                'title' => __('My Jobs', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_vis_emmycompanies'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'mycompanies')),"company"),
                'title' => __('My Companies', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_vis_emsearchresume'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resumesearch', 'jsjobslt'=>'resumesearch')),"resumesearch"),
                'title' => __('Search Resume', 'js-jobs'),
            );
        }
    }
} else {
    if (is_user_logged_in()) {
        if ($config_array['tmenu_jscontrolpanel'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'jobseeker', 'jsjobslt'=>'controlpanel')),"dashboard"),
                'title' => __('Control Panel', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_jsjobcategory'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobsbycategories')),"jobsbycategories"),
                'title' => __('Jobs By Categories', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_jssearchjob'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'jobsearch', 'jsjobslt'=>'jobsearch')),"jobsearch"),
                'title' => __('Job Search', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_jsnewestjob'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'newestjobs')),"job"),
                'title' => __('Newest Jobs', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_jsmyresume'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes')),"resume"),
                'title' => __('My Resumes', 'js-jobs'),
            );
        }
    } else { // user is visitor
        if ($config_array['tmenu_vis_jscontrolpanel'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'jobseeker', 'jsjobslt'=>'controlpanel')),"dashboard"),
                'title' => __('Control Panel', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_vis_jsjobcategory'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobsbycategories')),"jobsbycategories"),
                'title' => __('Jobs By Categories', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_vis_jssearchjob'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'jobsearch', 'jsjobslt'=>'jobsearch')),"jobbsearch"),
                'title' => __('Job Search', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_vis_jsnewestjob'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'newestjobs')),"job"),
                'title' => __('Newest Jobs', 'js-jobs'),
            );
        }
        if ($config_array['tmenu_vis_jsmyresume'] == 1) {
            $linkarray[] = array(
                'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes')),"resume"),
                'title' => __('My Resume', 'js-jobs'),
            );
        }
    }
}

if (isset($linkarray)) {
    $div .= '<div id="jsjobs-header-main-wrapper">';
    foreach ($linkarray AS $link) {
        $div .= '<a class="headerlinks" href="' . $link['link'] . '">' . $link['title'] . '</a>';
    }
    $div .= '</div>';
}
echo wp_kses($div, JSJOBS_ALLOWED_TAGS);
?>
