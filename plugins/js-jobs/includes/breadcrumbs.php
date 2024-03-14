<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSbreadcrumbs {

    static function getBreadcrumbs() {
        $cur_location = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('cur_location');
        if ($cur_location != 1)
            return false;
        if (!is_admin()) {
            $editid = JSJOBSrequest::getVar('jsjobsid');
            $isnew = ($editid == null) ? true : false;
            $module = JSJOBSrequest::getVar('jsjobsme');
            $layout = JSJOBSrequest::getVar('jsjobslt');
            $array[] = array('link' => get_the_permalink(), 'text' => __('Control Panel', 'js-jobs'));
            if ($layout == 'printresume' || $layout == 'pdf')
                return false; // b/c we have print and pdf layouts
            if ($module != null) {
                switch ($module) {
                    case 'company':
                        // Add default module link
                        switch ($layout) {
                            case 'addcompany':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'mycompanies')),"company"), 'text' => __('My Companies', 'js-jobs'));
                                $text = ($isnew) ? __('Add','js-jobs') .' '. __('Company', 'js-jobs') : __('Edit','js-jobs') .' '. __('Company', 'js-jobs');
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'addcompany')),"formcompany"), 'text' => $text);
                                break;
                            case 'mycompanies':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'mycompanies')),"company"), 'text' => __('My Companies', 'js-jobs'));
                                break;
                            case 'companies':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'companies')),"company"), 'text' => __('Companies', 'js-jobs'));
                                break;
                            case 'viewcompany':
                                if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                                    $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'mycompanies')),"company"), 'text' => __('My Companies', 'js-jobs'));
                                }
                                $array[] = array('link' => jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany')), 'text' => __('Company Information', 'js-jobs'));
                                break;
                        }
                        break;
                    case 'departments':
                        // Add default module link
                        switch ($layout) {
                            case 'adddepartment':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'mydepartments')),"department"), 'text' => __('My Departments', 'js-jobs'));
                                $text = ($isnew) ? __('Add','js-jobs') .' '. __('Department', 'js-jobs') : __('Edit','js-jobs') .' '. __('Department', 'js-jobs');
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'adddepartment')),"save-department"), 'text' => $text);
                                break;
                            case 'mydepartments':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'mydepartments')),"department"), 'text' => __('My Departments', 'js-jobs'));
                                break;
                            case 'viewdepartment':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'mydepartments')),"department"), 'text' => __('My Departments', 'js-jobs'));
                                $array[] = array('link' => jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'viewdepartment')), 'text' => __('View Department', 'js-jobs'));
					 break;
                        }
                        break;
                    case 'job':
                        // Add default module link
                        switch ($layout) {
                            case 'addjob':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs')),"job"), 'text' => __('My Jobs', 'js-jobs'));
                                $text = ($isnew) ? __('Add','js-jobs') .' '. __('Job', 'js-jobs') : __('Edit','js-jobs') .' '. __('Job', 'js-jobs');
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'addjob')),"formjob"), 'text' => $text);
                                break;
                            case 'myjobs':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs')),"job"), 'text' => __('My Jobs', 'js-jobs'));
                                break;
                            case 'viewjob':
                                if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                                    $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs')),"job"), 'text' => __('My Jobs', 'js-jobs'));
                                }
                                $array[] = array('link' => jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob')), 'text' => __('View Job', 'js-jobs'));
                                break;
                            case 'jobsbycategories':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobsbycategories')),"jobsbycategories"), 'text' => __('Jobs By Categories', 'js-jobs'));
                                break;
                            case 'jobsbytypes':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobsbycategories')),"jobsbycategories"), 'text' => __('Jobs By Types', 'js-jobs'));
                                break;
                            case 'jobs':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobsbycategories')),"jobsbycategories"), 'text' => __('Jobs', 'js-jobs'));
                                break;
                            case 'newestjobs':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobsbycategories')),"jobsbycategories"), 'text' => __('Newest Jobs', 'js-jobs'));
                                break;
                        }
                        break;
                    case 'resumesearch':
                        // Add default module link
                        switch ($layout) {
                            case 'resumesearch':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resumesearch', 'jsjobslt'=>'resumesearch')),"resumesearch"), 'text' => __('Resume Search', 'js-jobs'));
                                break;
                            case 'resumesavesearch':
                                $array[] = array('link' => get_the_permalink(), 'text' => __('Saved Searches', 'js-jobs'));
                                break;
                        }
                        break;
                    case 'resume':
                        // Add default module link
                        switch ($layout) {
                            case 'addresume':
                                $text = ($isnew) ? __('Add','js-jobs') .' '. __('Resume', 'js-jobs') : __('Edit','js-jobs') .' '. __('Resume', 'js-jobs');
                                if (!JSJOBSincluder::getObjectClass('user')->isguest()) {
                                    $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes')),"resume"), 'text' => __('My Resumes', 'js-jobs'));
                                    $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'addresume')),"formresume"), 'text' => $text );
                                } else {
                                    $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'addresume')),"formresume"), 'text' => $text );
                                }
                                break;
                            case 'myresumes':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes')),"resume"), 'text' => __('My Resume', 'js-jobs'));
                                break;
                            case 'resumes':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumebycategory')),"resumebycategory"), 'text' => __('Resume', 'js-jobs'));
                                break;
                            case 'resumebycategory':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumebycategory')),"resumebycategory"), 'text' => __('Resume By Categories', 'js-jobs'));
                                break;
                            case 'viewresume':
                                if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                                    $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes')),"resume"), 'text' => __('My Resume', 'js-jobs'));
                                }
                                $array[] = array('link' => jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume')), 'text' => __('View Resume', 'js-jobs'));
                                break;
                        }
                        break;
                    case 'jobapply':
                        // Add default module link
                        switch ($layout) {
                            case 'myappliedjobs':
                                $array[] = array('link' => jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'myappliedjobs')), 'text' => __('My Applied Jobs', 'js-jobs'));
                                break;
                            case 'jobappliedresume':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs')),"job"), 'text' => __('My Jobs', 'js-jobs'));
                                $array[] = array('link' => jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'jobappliedresume')), 'text' => __('Job Applied Resume', 'js-jobs'));
                                break;
                        }
                        break;
                    case 'jobsearch':
                        // Add default module link
                        switch ($layout) {
                            case 'jobsearch':
                                $array[] = array('link' => jsjobs::makeUrl(array('jsjobsme'=>'jobsearch', 'jsjobslt'=>'jobsearch')), 'text' => __('Job Search', 'js-jobs'));
                                break;
                            case 'jobsavesearch':
                                $array[] = array('link' => jsjobs::makeUrl(array('jsjobsme'=>'jobsearch', 'jsjobslt'=>'jobsavesearch')), 'text' => __('Saved Searches', 'js-jobs'));
                                break;
                        }
                        break;
                    case 'coverletter':
                        // Add default module link
                        switch ($layout) {
                            case 'addcoverletter':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'mycoverletters')),"coverletter"), 'text' => __('My Cover Letters', 'js-jobs'));
                                $text = ($isnew) ? __('Add','js-jobs') .' '. __('Cover Letter', 'js-jobs') : __('Edit','js-jobs') .' '. __('Cover Letter', 'js-jobs');
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'addcoverletter')),"formcoverletter"), 'text' => $text);
                                break;
                            case 'mycoverletters':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'mycoverletters')),"coverletter"), 'text' => __('My Cover Letters', 'js-jobs'));
                                break;
                            case 'viewcoverletter':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'mycoverletters')),"coverletter"), 'text' => __('My Cover Letters', 'js-jobs'));
                                $array[] = array('link' => jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'viewcoverletter')), 'text' => __('View Cover Letter', 'js-jobs'));
                                break;
                        }
                        break;
                    case 'jobseeker':
                        // Add default module link
                        switch ($layout) {
                            case 'controlpanel':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'jobseeker', 'jsjobslt'=>'controlpanel')),"dashboard"), 'text' => __('Control Panel', 'js-jobs'));
                                break;
                            case 'mystats':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'jobseeker', 'jsjobslt'=>'mystats')),"stats"), 'text' => __('My Stats', 'js-jobs'));
                                break;
                        }
                        break;
                    case 'employer':
                        // Add default module link
                        switch ($layout) {
                            case 'controlpanel':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'employer', 'jsjobslt'=>'controlpanel')),"dashboard"), 'text' => __('Control Panel', 'js-jobs'));
                                break;
                            case 'mystats':
                                $array[] = array('link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'employer', 'jsjobslt'=>'mystats')),"stats"), 'text' => __('My Stats', 'js-jobs'));
                                break;
                        }
                        break;
                    case 'jsjobs':
                        // Add default module link
                        switch ($layout) {
                            case 'login':
                                $array[] = array('link' => jsjobs::makeUrl(array('jsjobsme'=>'jsjobs', 'jsjobslt'=>'login')), 'text' => __('Log In', 'js-jobs'));
                                break;
                        }
                        break;
                    case 'user':
                        // Add default module link
                        switch ($layout) {
                            case 'regemployer':
                                $array[] = array('link' => jsjobs::makeUrl(array('jsjobsme'=>'user', 'jsjobslt'=>'userregister')), 'text' => __('Register', 'js-jobs'));
                                break;
                            case 'regjobseeker':
                                $array[] = array('link' => jsjobs::makeUrl(array('jsjobsme'=>'user', 'jsjobslt'=>'userregister')), 'text' => __('Register', 'js-jobs'));
                                break;
                        }
                        break;
                }
            }
        }
        $breadcrhtml = "";
        if (isset($array)) {
            $count = count($array);
            $i = 0;
            $breadcrhtml = '<div id="jsst_breadcrumbs_parent">';
            foreach ($array AS $obj) {
                if ($i == 0) {
                    $breadcrhtml .= '<div class="home"><a href="' . $obj['link'] . '"><img class="homeicon" src="' . JSJOBS_PLUGIN_URL . 'includes/images/homeicon.png"/></a></div>';
                } else {
                    if ($i == ($count - 1)) {
                        $breadcrhtml .= '<div class="lastlink">' . $obj['text'] . '</div>';
                    } else {
                        $breadcrhtml .= '<div class="links"><a class="links" href="' . $obj['link'] . '">' . $obj['text'] . '</a></div>';
                    }
                }
                $i++;
            }
            $breadcrhtml .= '</div>';
            echo wp_kses($breadcrhtml, JSJOBS_ALLOWED_TAGS);
        }
    }

}

$JSJOBSbreadcrumbs = new JSJOBSbreadcrumbs;
?>
