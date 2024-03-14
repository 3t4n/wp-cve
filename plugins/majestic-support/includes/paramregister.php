<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

function MJTC_generate_rewrite_rules(&$rules, $rule){
    $_new_rules = array();
    foreach($rules AS $key => $value){
        if(MJTC_majesticsupportphplib::MJTC_strstr($key, $rule)){
            $newkey = MJTC_majesticsupportphplib::MJTC_substr($key,0,MJTC_majesticsupportphplib::MJTC_strlen($key) - 3);
            $matcharray = MJTC_majesticsupportphplib::MJTC_explode('$matches', $value);
            $countmatch = COUNT($matcharray);
            //on all pages
            $_key = $newkey.'/(';
            $_key .= MJTC_includer::MJTC_getModel('slug')->getSlugString();
            $_key .= ')(/[^/]*)?(/[^/]*)?(/[^/]*)?/?$';
			$newvalue = $value . '&mslayout=$matches['.$countmatch.']&majesticsupport1=$matches['.($countmatch + 1).']&majesticsupport2=$matches['.($countmatch + 2).']&majesticsupport3=$matches['.($countmatch + 3).']';
            $_new_rules[$_key] = $newvalue;
        }
    }
    return $_new_rules;
}

function MJTC_post_rewrite_rules_array($rules){
    $newrules = array();
    $newrules = MJTC_generate_rewrite_rules($rules, '([^/]+)(?:/([0-9]+))?/?$');
    $newrules += MJTC_generate_rewrite_rules($rules, '([^/]+)(/[0-9]+)?/?$');
    $newrules += MJTC_generate_rewrite_rules($rules, '([0-9]+)(?:/([0-9]+))?/?$');
    $newrules += MJTC_generate_rewrite_rules($rules, '([0-9]+)(/[0-9]+)?/?$');
    return $newrules + $rules;
}
add_filter('post_rewrite_rules', 'MJTC_post_rewrite_rules_array');

function MJTC_page_rewrite_rules_array($rules){
    $newrules = array();
    $newrules = MJTC_generate_rewrite_rules($rules, '(.?.+?)(?:/([0-9]+))?/?$');
    $newrules += MJTC_generate_rewrite_rules($rules, '(.?.+?)(/[0-9]+)?/?$');
    return $newrules + $rules;
}
add_filter('page_rewrite_rules', 'MJTC_page_rewrite_rules_array');

function MJTC_rewrite_rules( $wp_rewrite ) {
      // Hooks params
      $rules = array();
      // Homepage params
      $pageid = get_option('page_on_front');
      if($pageid == 0 || $pageid == ''){
          $pageid = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('default_pageid');
      }
      $key = MJTC_includer::MJTC_getModel('slug')->getSlugString(1);
      $rules['('.$key.')(/[^/]*)?(/[^/]*)?(/[^/]*)?/?$'] = 'index.php?page_id='.$pageid.'&mslayout=$matches[1]&majesticsupport1=$matches[2]&majesticsupport2=$matches[3]&majesticsupport3=$matches[4]';
      $wp_rewrite->rules = $rules + $wp_rewrite->rules;
      return $wp_rewrite->rules;
}
add_filter( 'generate_rewrite_rules', 'MJTC_rewrite_rules' );

function MJTC_query_var( $qvars ) {
    $qvars[] = 'mslayout';
    $qvars[] = 'majesticsupport1';
    $qvars[] = 'majesticsupport2';
    $qvars[] = 'majesticsupport3';
    return $qvars;
}
add_filter( 'query_vars', 'MJTC_query_var' , 10, 1 );

function MJTC_parse_request($q) {
    if(isset($q->query_vars['page_id']) && !empty($q->query_vars['page_id'])){
        majesticsupport::$_data['sanitized_args']['pageid'] = $q->query_vars['page_id'];
    }
	$new_addon_layoutname = "";
	$new_addon_layoutname = apply_filters('ms_ticket_paramregister_thirdparty_addon_layoutname',false);
	$new_addon_modulename = "";
	$new_addon_modulename = apply_filters('ms_ticket_paramregister_thirdparty_addon_modulename',false);

    if(isset($q->query_vars['mslayout']) && !empty($q->query_vars['mslayout'])){
        $layout = $q->query_vars['mslayout'];
        $slug_prefix = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('slug_prefix');
        $home_slug_prefix = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('home_slug_prefix');
        $length = MJTC_majesticsupportphplib::MJTC_strlen($home_slug_prefix);
        if(MJTC_majesticsupportphplib::MJTC_substr($layout, 0, $length) === $home_slug_prefix){
            $layout = MJTC_majesticsupportphplib::MJTC_substr($layout,$length);
        }
        $length = MJTC_majesticsupportphplib::MJTC_strlen($slug_prefix);
        if(MJTC_majesticsupportphplib::MJTC_substr($layout, 0, $length) === $slug_prefix){
            $layout = MJTC_majesticsupportphplib::MJTC_substr($layout,$length);
        }

        $layout = MJTC_includer::MJTC_getModel('slug')->getDefaultSlugFromSlug($layout);
        switch ($layout) {
            case 'ticket':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'ticket';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'ticketdetail';
                majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
            break;
            case 'agent-add-ticket':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'agent';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'staffaddticket';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case $new_addon_layoutname:
                majesticsupport::$_data['sanitized_args']['mjsmod'] = $new_addon_modulename;
                majesticsupport::$_data['sanitized_args']['mjslay'] = $new_addon_layoutname;
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'announcements':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'announcement';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'announcements';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'agent-feedbacks':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'feedback';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'feedbacks';
            break;
            case 'visitor-message':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'ticket';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'visitormessagepage';
            break;
            case 'role-permission':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'role';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'rolepermission';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'roles':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'role';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'roles';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'ticket-my-profile':
            case 'my-profile':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'agent';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'myprofile';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'agents':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'agent';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'staffs';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'agent-reports':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'reports';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'staffreports';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'department-reports':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'reports';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'departmentreports';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'feed-back':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'feedback';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'formfeedback';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['token'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'print-ticket':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'ticket';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'printticket';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'agent-report':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'reports';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'staffdetailreport';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['ms-id'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
                if(!empty($q->query_vars['majesticsupport2'])){
                    $date = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport2']);
                    if(MJTC_majesticsupportphplib::MJTC_strstr($date, 'date-start')){
                        $date = MJTC_majesticsupportphplib::MJTC_str_replace('date-start:', '', $date);
                        majesticsupport::$_data['sanitized_args']['ms-date-start'] = $date;
                    }
                    if(MJTC_majesticsupportphplib::MJTC_strstr($date, 'date-end')){
                        $date = MJTC_majesticsupportphplib::MJTC_str_replace('date-end:', '', $date);
                        majesticsupport::$_data['sanitized_args']['ms-date-end'] = $date;
                    }
                }
                if(!empty($q->query_vars['majesticsupport3'])){
                    $date = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport3']);
                    if(MJTC_majesticsupportphplib::MJTC_strstr($date, 'date-start')){
                        $date = MJTC_majesticsupportphplib::MJTC_str_replace('date-start:', '', $date);
                        majesticsupport::$_data['sanitized_args']['ms-date-start'] = $date;
                    }
                    if(MJTC_majesticsupportphplib::MJTC_strstr($date, 'date-end')){
                        $date = MJTC_majesticsupportphplib::MJTC_str_replace('date-end:', '', $date);
                        majesticsupport::$_data['sanitized_args']['ms-date-end'] = $date;
                    }
                }
            break;
            case 'downloads':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'download';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'downloads';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'faqs':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'faq';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'faqs';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'faq':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'faq';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'faqdetails';
                majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
            break;
            case 'add-department':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'department';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'adddepartment';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'add-announcement':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'announcement';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'addannouncement';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'add-download':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'download';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'adddownload';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'add-faq':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'faq';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'addfaq';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'add-article':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'knowledgebase';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'addarticle';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'add-category':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'knowledgebase';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'addcategory';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'kb-articles':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'knowledgebase';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'userknowledgebasearticles';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'kb-article':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'knowledgebase';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'articledetails';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'add-message':
            case 'ticket-add-message':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'mail';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'formmessage';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'ticket-message':
            case 'message':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'mail';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'message';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'ticket-message-inbox':
            case 'message-inbox':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'mail';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'inbox';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'ticket-message-outbox':
            case 'message-outbox':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'mail';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'outbox';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'ticket-login':
            case 'login':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'majesticsupport';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'login';
            break;
            case 'ticket-user-register':
            case 'userregister':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'majesticsupport';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'userregister';
            break;
            case 'add-role':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'role';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'addrole';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'add-agent':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'agent';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'addstaff';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'agent-permissions':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'agent';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'staffpermissions';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'agent-announcements':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'announcement';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'staffannouncements';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'departments':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'department';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'departments';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'smartreplies':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'smartreply';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'smartreplies';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'add-smartreply':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'smartreply';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'addsmartreply';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'banemails':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'banemail';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'banemails';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'add-banemail':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'banemail';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'addbanemail';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'agent-downloads':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'download';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'staffdownloads';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'agent-faqs':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'faq';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'stafffaqs';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'control-panel':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'majesticsupport';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'controlpanel';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'agent-kb-articles':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'knowledgebase';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'stafflistarticles';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'agent-categories':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'knowledgebase';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'stafflistcategories';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'knowledgebase':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'knowledgebase';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'userknowledgebase';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'ticket-status':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'ticket';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'ticketstatus';
            break;
            case 'add-ticket':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'ticket';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'addticket';
                if(!empty($q->query_vars['majesticsupport1'])){
                    if(MJTC_majesticsupportphplib::MJTC_strstr($q->query_vars['majesticsupport1'], '_13')){
                        majesticsupport::$_data['sanitized_args']['paidsupportid'] = MJTC_majesticsupportphplib::MJTC_preg_replace('/\/|_13/', '',$q->query_vars['majesticsupport1']);
                    }elseif(MJTC_majesticsupportphplib::MJTC_strstr($q->query_vars['majesticsupport1'], '_15')){
                        majesticsupport::$_data['sanitized_args']['formid'] = MJTC_majesticsupportphplib::MJTC_preg_replace('/\/|_15/', '',$q->query_vars['majesticsupport1']);
                    }else{
                        majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                    }
                }
            break;
            case 'my-tickets':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'ticket';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'myticket';
            break;
            case 'agent-my-tickets':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'agent';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'staffmyticket';
                if(!empty($q->query_vars['majesticsupport1'])){
                    if(MJTC_majesticsupportphplib::MJTC_strstr($q->query_vars['majesticsupport1'], '_12')){
                        majesticsupport::$_data['sanitized_args']['uid'] = MJTC_majesticsupportphplib::MJTC_preg_replace('/\/|_12/', '',$q->query_vars['majesticsupport1']);
                    }else{
                        majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                    }
                }
            break;
            case 'ticket-close-reasons':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'ticketclosereason';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'ticketclosereasons';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'add-ticket-close-reason':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'ticketclosereason';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'addticketclosereason';
                if(!empty($q->query_vars['majesticsupport1'])){
                    majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
                }
            break;
            case 'announcement':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'announcement';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'announcementdetails';
                majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
            break;
            case 'gdpr-data-compliance-actions':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'gdpr';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'adderasedatarequest';
            break;
            case 'agent-help-topics':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'helptopic';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'agenthelptopics';
                majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
            break;
            case 'add-help-topic':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'helptopic';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'addhelptopic';
                majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
            break;
            case 'agent-canned-responses':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'cannedresponses';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'agentcannedresponses';
                majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
            break;
            case 'add-canned-response':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'cannedresponses';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'addcannedresponse';
                majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
            break;
            case 'export':
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'export';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'export';
                majesticsupport::$_data['sanitized_args']['majesticsupportid'] = MJTC_majesticsupportphplib::MJTC_str_replace('/', '',$q->query_vars['majesticsupport1']);
            break;
            default:
                majesticsupport::$_data['sanitized_args']['mjsmod'] = 'majesticsupport';
                majesticsupport::$_data['sanitized_args']['mjslay'] = 'controlpanel';
            break;
        }
    }
}
add_action('parse_request', 'MJTC_parse_request');

function MJTC_redirect_canonical($redirect_url, $requested_url) {
    global $wp_rewrite;
    if(is_home() || is_front_page()){
        $array = MJTC_includer::MJTC_getModel('slug')->getRedirectCanonicalArray();
        $ret = false;
        foreach($array AS $layout){
            if(MJTC_majesticsupportphplib::MJTC_strstr($requested_url, $layout)){
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
add_filter('redirect_canonical', 'MJTC_redirect_canonical', 11, 2);

?>
