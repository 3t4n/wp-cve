<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

function jsjb_generate_rewrite_rules(&$rules, $rule){
    
        $_new_rules = array();
        foreach($rules AS $key => $value){
            if(jsjobslib::jsjobs_strstr($key, $rule)){
                $newkey = jsjobslib::jsjobs_substr($key,0,jsjobslib::jsjobs_strlen($key) - 3);
                $matcharray = jsjobslib::jsjobs_explode('$matches', $value);
                $countmatch = COUNT($matcharray);
                //on all pages
                // $_key = $newkey . '/(jobseeker-purchase-history|jobseeker-control-panel|jobseeker-credits-log|jobseeker-credits|jobseeker-messages|jobseeker-my-stats|jobseeker-rate-list|jobseeker-register|jsjobs-login|employer-purchase-history|employer-control-panel|employer-credits-log|employer-credits|employer-messages|employer-my-stats|employer-rate-list|employer-register|folder-resumes|resume-save-searches|resume-categories|resume-search|resume-rss|add-resume|my-resumes|resumes|resume|my-companies|add-company|companies|company|add-cover-letter|my-cover-letters|cover-letter|add-department|my-departments|department|add-folder|my-folders|add-job|my-jobs|job-applied-resume|job-save-searches|job-categories|job-messages|job-by-types|job-search|job-types|job-alert|job-rss|my-applied-jobs|newest-jobs|shortlisted-jobs|messages|message|new-in-jsjobs|folder|jobs|job|user-register|jm-user-register|resume-pdf|resume-print)(/[^/]*)?(/[^/]*)?(/[^/]*)?/?$';
                $_key = $newkey.'/(';
                $_key .= JSJOBSincluder::getJSModel('slug')->getSlugString();
                $_key .= ')(/[^/]*)?(/[^/]*)?(/[^/]*)?/?$';
                $newvalue = $value . '&jsjblayout=$matches['.$countmatch.']&jsjb1=$matches['.($countmatch + 1).']&jsjb2=$matches['.($countmatch + 2).']&jsjb3=$matches['.($countmatch + 3).']';
                $_new_rules[$_key] = $newvalue;
            }
        }
        return $_new_rules;
    }

    function jsjb_post_rewrite_rules_array($rules){
        $newrules = jsjb_generate_rewrite_rules($rules, '([^/]+)(?:/([0-9]+))?/?$');
        $newrules += jsjb_generate_rewrite_rules($rules, '([^/]+)(/[0-9]+)?/?$');
        $newrules += jsjb_generate_rewrite_rules($rules, '([0-9]+)(?:/([0-9]+))?/?$');
        $newrules += jsjb_generate_rewrite_rules($rules, '([0-9]+)(/[0-9]+)?/?$');
        
        return $newrules + $rules;
    }
    add_filter('post_rewrite_rules', 'jsjb_post_rewrite_rules_array',999);

    function jsjb_page_rewrite_rules_array($rules){
        $newrules = array();
        $newrules = jsjb_generate_rewrite_rules($rules, '(.?.+?)(?:/([0-9]+))?/?$');
        $newrules += jsjb_generate_rewrite_rules($rules, '(.?.+?)(/[0-9]+)?/?$');
        return $newrules + $rules;
    }
    add_filter('page_rewrite_rules', 'jsjb_page_rewrite_rules_array');

    function jsjb_root_rewrite_rules( $rules ) {
    // Homepage params
        $pageid = get_option('page_on_front');
        if($pageid == 0 || $pageid == ''){
            $pageid = JSJOBSincluder::getJSModel('configuration')->getConfigValue('default_pageid');
        }
        $key = JSJOBSincluder::getJSModel('slug')->getSlugString(1);
        $rules['('.$key.')(/[^/]*)?(/[^/]*)?(/[^/]*)?/?$'] = 'index.php?page_id='.$pageid.'&jsjblayout=$matches[1]&jsjb1=$matches[2]&jsjb2=$matches[3]&jsjb3=$matches[4]';
        return $rules;
    }
   add_filter( 'root_rewrite_rules', 'jsjb_root_rewrite_rules' );

    function jsjb_query_var( $qvars ) {

        $value=json_encode($qvars);
        $qvars[] = 'jsjblayout';
        $qvars[] = 'jsjb1';
        $qvars[] = 'jsjb2';
        $qvars[] = 'jsjb3';
    return $qvars;

}
add_filter( 'query_vars', 'jsjb_query_var' , 10, 1 );

function jsjb_parse_request($q) {


    if(isset($q->query_vars['page_id']) && !empty($q->query_vars['page_id'])){
        jsjobs::$_data['sanitized_args']['pageid'] = $q->query_vars['page_id'];
    }
    if(isset($q->query_vars['jsjblayout']) && !empty($q->query_vars['jsjblayout'])){
        $layout = $q->query_vars['jsjblayout'];
        $slug_prefix = JSJOBSincluder::getJSModel('configuration')->getConfigValue('slug_prefix');
        $home_slug_prefix = JSJOBSincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
        $length = jsjobslib::jsjobs_strlen($home_slug_prefix);
        if(jsjobslib::jsjobs_substr($layout, 0, $length) === $home_slug_prefix){
            $layout = jsjobslib::jsjobs_substr($layout,$length);
        }
        $length = jsjobslib::jsjobs_strlen($slug_prefix);
        if(jsjobslib::jsjobs_substr($layout, 0, $length) === $slug_prefix){
            $slug_flag = JSJOBSincluder::getJSModel('slug')->checkIfSlugExist($layout);
            if($slug_flag != true){
                $layout = jsjobslib::jsjobs_substr($layout,$length);
            }
        }

        //if(jsjobslib::jsjobs_substr($layout, 0, 3) == 'jm-') {
        //    $layout = jsjobslib::jsjobs_substr($layout,3);
        //}

        $layout = JSJOBSincluder::getJSModel('slug')->getDefaultSlugFromSlug($layout);
        switch ($layout) {
            case 'new-in-jsjobs':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'common';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'newinjsjobs';
            break;
            case 'jsjobs-login':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jsjobs';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'login';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsredirecturl'] = $jsjb1;
            }
            break;
            case 'jobseeker-control-panel':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobseeker';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'controlpanel';
            break;
            case 'jobseeker-my-stats':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobseeker';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'mystats';
            break;
            case 'employer-my-stats':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'employer';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'mystats';
            break;
            case 'employer-control-panel':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'employer';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'controlpanel';
            break;
            case 'resumes':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'resumes';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                if(jsjobslib::jsjobs_strstr($jsjb1, 'sortby')){
                    jsjobs::$_data['sanitized_args']['sortby'] = $jsjb1;
                }else{
                    if(jsjobslib::jsjobs_strstr($jsjb1, 'vt')){
                        jsjobs::$_data['sanitized_args']['viewtype'] = $jsjb1;
                    }else{
                        jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
                        
                    }    
                }
            }
            $jsjb2 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb2']);
            if(!empty($jsjb2)){
                jsjobs::$_data['sanitized_args']['sortby'] = $jsjb2;
            }
            break;
            case 'jobs':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobs';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'my-companies':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'company';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'mycompanies';
            break;
            case 'add-company':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'company';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'addcompany';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'my-jobs':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'myjobs';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['sortby'] = $jsjb1;
            }
            break;
            case 'add-job':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'addjob';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'my-departments':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'departments';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'mydepartments';
            break;
            case 'add-department':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'departments';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'adddepartment';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'department':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'departments';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'viewdepartment';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'cover-letter':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'coverletter';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'viewcoverletter';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'company':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'company';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'viewcompany';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'resume':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'viewresume';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '', $q->query_vars['jsjb1']);
            $jsjb2 = jsjobslib::jsjobs_str_replace('/', '', $q->query_vars['jsjb2']);
            if(!empty($jsjb2)){
                jsjobs::$_data['sanitized_args']['jobid'] = $jsjb1;
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb2;
            }else{
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'job':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'viewjob';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'my-folders':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'folder';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'myfolders';
            break;
            case 'add-folder':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'folder';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'addfolder';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'folder':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'folder';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'viewfolder';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'folder-resumes':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'folderresume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'folderresume';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'jobseeker-messages':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'message';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobseekermessages';
            break;
            case 'employer-messages':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'message';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'employermessages';
            break;
            case 'message':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'message';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'sendmessage';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'job-messages':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'message';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobmessages';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'job-types':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobsbytypes';
            break;
            case 'messages':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'message';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'messages';
            break;
            case 'resume-search':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resumesearch';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'resumesearch';
            break;
            case 'resume-save-searches':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resumesearch';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'resumesavesearch';
            break;
            case 'resume-categories':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'resumebycategory';
            break;
            case 'resume-rss':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'rss';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'resumerss';
            break;
            case 'employer-credits':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'credits';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'employercredits';
            break;
            case 'jobseeker-credits':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'credits';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobseekercredits';
            break;
            case 'employer-purchase-history':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'purchasehistory';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'employerpurchasehistory';
            break;
            case 'employer-my-stats':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'stats';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'employermystats';
            break;
            case 'jobseker-my-stats':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'stats';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobseekerstats';
            break;
            case 'employer-register':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'user';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'regemployer';
            break;
            case 'jobseeker-register':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'user';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'regjobseeker';
            break;
            case 'user-register':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'user';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'userregister';
            break;
            case 'add-resume':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'addresume';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'my-resumes':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'myresumes';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['sortby'] = $jsjb1;
            }
            break;
            case 'add-cover-letter':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'coverletter';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'addcoverletter';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'companies':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'company';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'companies';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                if(jsjobslib::jsjobs_strstr($jsjb1, 'company-')){
                    jsjobs::$_data['sanitized_args']['jsjobs-company'] = $jsjb1;                        
                }elseif(jsjobslib::jsjobs_strstr($jsjb1, 'city-')){
                    jsjobs::$_data['sanitized_args']['jsjobs-city'] = $jsjb1;
                }else{
                    jsjobs::$_data['sanitized_args']['sortby'] = $jsjb1;
                }
            }
            $jsjb2 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb2']);
            if(!empty($jsjb2)){
                jsjobs::$_data['sanitized_args']['jsjobs-city'] = $jsjb2;
            }
            break;
            case 'my-applied-jobs':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobapply';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'myappliedjobs';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['sortby'] = $jsjb1;
            }
            break;
            case 'job-applied-resume':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobapply';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobappliedresume';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jobid'] = $jsjb1;
            }
            $jsjb2 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb2']);
                //var_dump($jsjb2);
            if(!empty($jsjb2)){
                if(jsjobslib::jsjobs_strstr($jsjb2, 'sortby')){
                    jsjobs::$_data['sanitized_args']['sortby'] = $jsjb2;
                }else{
                    jsjobs::$_data['sanitized_args']['ta'] = $jsjb2;
                }
            }
            $jsjb3 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb3']);
            if(!empty($jsjb3)){
                jsjobs::$_data['sanitized_args']['sortby'] = $jsjb3;
            }
            break;
            case 'my-cover-letters':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'coverletter';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'mycoverletters';
            break;
            case 'job-search':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobsearch';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobsearch';
            break;
            case 'job-save-searches':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobsearch';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobsavesearch';
            break;
            case 'job-alert':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobalert';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobalert';
            break;
            case 'job-rss':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'rss';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobrss';
            break;
            case 'shortlisted-jobs':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'shortlistedjobs';
            break;
            case 'jobseeker-purchase-history':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'purchasehistory';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobseekerpurchasehistory';
            break;
            case 'jobseeker-rate-list':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'credits';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'ratelistjobseeker';
            break;
            case 'employer-rate-list':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'credits';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'ratelistemployer';
            break;
            case 'jobseeker-credits-log':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'creditslog';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobseekercreditslog';
            break;
            case 'employer-credits-log':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'creditslog';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'employercreditslog';
            break;
            case 'job-categories':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobsbycategories';
            break;
            case 'newest-jobs':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'newestjobs';
            break;
            case 'job-by-types':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobsbytypes';
            break;
            case 'jobs-by-cities':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'city';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobsbycities';
            break;
            case 'resume-pdf':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'pdf';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'resume-print':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'printresume';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            default:
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobseeker';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'controlpanel';
            break;
        }
        /*
        switch ($layout) {
            case 'new-in-jsjobs':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'common';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'newinjsjobs';
            break;
            case 'jsjobs-login':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jsjobs';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'login';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsredirecturl'] = $jsjb1;
            }
            break;
            case 'jobseeker-control-panel':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobseeker';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'controlpanel';
            break;
            case 'jobseeker-my-stats':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobseeker';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'mystats';
            break;
            case 'employer-my-stats':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'employer';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'mystats';
            break;
            case 'employer-control-panel':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'employer';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'controlpanel';
            break;
            case 'resumes':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'resumes';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                if(jsjobslib::jsjobs_strstr($jsjb1, 'sortby')){
                    jsjobs::$_data['sanitized_args']['sortby'] = $jsjb1;
                }else{
                    if(jsjobslib::jsjobs_strstr($jsjb1, 'vt')){
                        jsjobs::$_data['sanitized_args']['viewtype'] = $jsjb1;
                    }else{
                        jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
                        
                    }    
                }
            }
            $jsjb2 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb2']);
            if(!empty($jsjb2)){
                jsjobs::$_data['sanitized_args']['sortby'] = $jsjb2;
            }
            break;
            case 'jobs':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobs';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'my-companies':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'company';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'mycompanies';
            break;
            case 'add-company':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'company';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'addcompany';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'my-jobs':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'myjobs';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['sortby'] = $jsjb1;
            }
            break;
            case 'add-job':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'addjob';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'my-departments':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'departments';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'mydepartments';
            break;
            case 'add-department':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'departments';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'adddepartment';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'department':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'departments';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'viewdepartment';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'cover-letter':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'coverletter';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'viewcoverletter';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'company':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'company';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'viewcompany';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'resume':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'viewresume';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '', $q->query_vars['jsjb1']);
            $jsjb2 = jsjobslib::jsjobs_str_replace('/', '', $q->query_vars['jsjb2']);
            if(!empty($jsjb2)){
                jsjobs::$_data['sanitized_args']['jobid'] = $jsjb1;
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb2;
            }else{
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'job':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'viewjob';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'my-folders':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'folder';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'myfolders';
            break;
            case 'add-folder':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'folder';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'addfolder';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'folder':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'folder';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'viewfolder';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'folder-resumes':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'folderresume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'folderresume';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'jobseeker-messages':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'message';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobseekermessages';
            break;
            case 'employer-messages':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'message';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'employermessages';
            break;
            case 'message':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'message';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'sendmessage';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'job-messages':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'message';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobmessages';
            jsjobs::$_data['sanitized_args']['jsjobsid'] = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            break;
            case 'job-types':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobsbytypes';
            break;
            case 'messages':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'message';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'messages';
            break;
            case 'resume-search':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resumesearch';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'resumesearch';
            break;
            case 'resume-save-searches':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resumesearch';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'resumesavesearch';
            break;
            case 'resume-categories':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'resumebycategory';
            break;
            case 'resume-rss':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'rss';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'resumerss';
            break;
            case 'employer-credits':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'credits';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'employercredits';
            break;
            case 'jobseeker-credits':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'credits';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobseekercredits';
            break;
            case 'employer-purchase-history':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'purchasehistory';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'employerpurchasehistory';
            break;
            case 'employer-my-stats':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'stats';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'employermystats';
            break;
            case 'jobseker-my-stats':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'stats';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobseekerstats';
            break;
            case 'employer-register':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'user';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'regemployer';
            break;
            case 'jobseeker-register':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'user';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'regjobseeker';
            break;
            case 'user-register':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'user';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'userregister';
            break;
            case 'add-resume':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'addresume';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'my-resumes':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'myresumes';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['sortby'] = $jsjb1;
            }
            break;
            case 'add-cover-letter':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'coverletter';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'addcoverletter';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'companies':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'company';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'companies';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                if(jsjobslib::jsjobs_strstr($jsjb1, 'company-')){
                    jsjobs::$_data['sanitized_args']['jsjobs-company'] = $jsjb1;                        
                }elseif(jsjobslib::jsjobs_strstr($jsjb1, 'city-')){
                    jsjobs::$_data['sanitized_args']['jsjobs-city'] = $jsjb1;
                }
            }
            $jsjb2 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb2']);
            if(!empty($jsjb2)){
                jsjobs::$_data['sanitized_args']['jsjobs-city'] = $jsjb2;
            }
            break;
            case 'my-applied-jobs':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobapply';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'myappliedjobs';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['sortby'] = $jsjb1;
            }
            break;
            case 'job-applied-resume':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobapply';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobappliedresume';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jobid'] = $jsjb1;
            }
            $jsjb2 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb2']);
                //var_dump($jsjb2);
            if(!empty($jsjb2)){
                if(jsjobslib::jsjobs_strstr($jsjb2, 'sortby')){
                    jsjobs::$_data['sanitized_args']['sortby'] = $jsjb2;
                }else{
                    jsjobs::$_data['sanitized_args']['ta'] = $jsjb2;
                }
            }
            $jsjb3 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb3']);
            if(!empty($jsjb3)){
                jsjobs::$_data['sanitized_args']['sortby'] = $jsjb3;
            }
            break;
            case 'my-cover-letters':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'coverletter';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'mycoverletters';
            break;
            case 'job-search':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobsearch';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobsearch';
            break;
            case 'job-save-searches':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobsearch';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobsavesearch';
            break;
            case 'job-alert':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobalert';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobalert';
            break;
            case 'job-rss':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'rss';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobrss';
            break;
            case 'shortlisted-jobs':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'shortlistedjobs';
            break;
            case 'jobseeker-purchase-history':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'purchasehistory';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobseekerpurchasehistory';
            break;
            case 'jobseeker-rate-list':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'credits';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'ratelistjobseeker';
            break;
            case 'employer-rate-list':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'credits';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'ratelistemployer';
            break;
            case 'jobseeker-credits-log':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'creditslog';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobseekercreditslog';
            break;
            case 'employer-credits-log':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'creditslog';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'employercreditslog';
            break;
            case 'job-categories':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobsbycategories';
            break;
            case 'newest-jobs':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'newestjobs';
            break;
            case 'job-by-types':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'job';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'jobsbytypes';
            break;
            case 'resume-pdf':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'pdf';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            case 'resume-print':
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'resume';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'printresume';
            $jsjb1 = jsjobslib::jsjobs_str_replace('/', '',$q->query_vars['jsjb1']);
            if(!empty($jsjb1)){
                jsjobs::$_data['sanitized_args']['jsjobsid'] = $jsjb1;
            }
            break;
            default:
            jsjobs::$_data['sanitized_args']['jsjobsme'] = 'jobseeker';
            jsjobs::$_data['sanitized_args']['jsjobslt'] = 'controlpanel';
            break;
        }
        */
    }    
}

add_action('parse_request', 'jsjb_parse_request');

function jsjb_redirect_canonical($redirect_url, $requested_url) {

    global $wp_rewrite;
    if(is_home() || is_front_page()){
        $array = JSJOBSincluder::getJSModel('slug')->getRedirectCanonicalArray();
        

        /*
        $array = array('/jm-employer-credits-log','/jm-jobseeker-credits-log','/jm-employer-rate-list','/jm-jobseeker-rate-list','/jm-jobseeker-purchase-history','/jm-shortlisted-jobs','/jm-job-rss','/jm-job-alert','/jm-job-save-searches','/jm-job-search','/jm-job-applied-resume','/jm-my-applied-jobs'
            ,'/jm-companies','/jm-job-by-types','/jm-newest-jobs','/jm-job-categories','/jm-my-cover-letters','/jm-add-cover-letter','/jm-my-resumes','/jm-jobseeker-register','/jm-employer-register','/jm-add-resume','/jm-jobseker-my-stats','/jm-employer-my-stats','/jm-employer-purchase-history','/jm-jobseeker-credits','/jm-employer-credits','/jm-resume-rss','/jm-resume-categories'
            ,'/jm-resume-save-searches','/jm-resume-search','/jm-messages','/jm-job-types','/jm-job-messages','/jm-message','/jm-employer-messages','/jm-jobseeker-messages','/jm-folder-resumes','/jm-folder','/jm-add-folder','/jm-my-folders','/jm-job','/jm-resume','/jm-company','/jm-cover-letter','/jm-department'
            ,'/jm-add-department','/jm-my-departments','/jm-add-job','/jm-my-jobs','/jm-add-company','/jm-my-companies','/jm-jobs','/jm-resumes','/jm-employer-control-panel','/jm-employer-my-stats','/jm-jobseeker-my-stats','/jm-jobseeker-control-panel','/jm-jsjobs-login','/jm-new-in-jsjobs','/jm-user-register','/jm-resume-pdf','/jm-resume-print');
        */
        $ret = false;
        foreach($array AS $layout){
            if(jsjobslib::jsjobs_strstr($requested_url, $layout)){
                $ret = true;
                break;
            }
        }
        if($ret == true){
            return $requested_url;
        }
    }
    return $redirect_url;
}
add_filter('redirect_canonical', 'jsjb_redirect_canonical', 11, 2);

?>
