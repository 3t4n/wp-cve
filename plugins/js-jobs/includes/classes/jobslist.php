<?php

if (!defined('ABSPATH')) die('Restricted Access');

class JSJOBSjobslist {

    function __construct() {}

    function checkLinks($name) {
        foreach (jsjobs::$_data['fields'] as $field) {
            $array =  array();
            $array[0] = 0;
            switch ($field->field) {
                case $name:
                    if($field->showonlisting == 1){
                        $array[0] = 1;
                        $array[1] =  $field->fieldtitle;
                    }
                return $array;
                break;
            }
        }
        return $array;
    }

    function printjobs(&$jobs){
        if(!isset(jsjobs::$_data['jsjobs_pageid'])){
            jsjobs::$_data['jsjobs_pageid'] = JSJOBSrequest::getVar('jsjobs_pageid');
        }
        $labelflag = true;
        $labelinlisting = jsjobs::$_configuration['labelinlisting'];
        if($labelinlisting != 1)
            $labelflag = false;
        $html = "";
        $noofjobs = 0;
        $customfields =JSJOBSincluder::getObjectClass('customfields')->userFieldsData(2,1);
        if(!empty($jobs)){
            $wpdir = wp_upload_dir();
            foreach ($jobs as $job) { 
                if($job->logofilename != ""){
                    $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                    $path = $wpdir['baseurl']. '/' . $data_directory .'/data/employer/comp_'.$job->companyid.'/logo/'.$job->logofilename;
                }else{ 
                    $path = JSJOBS_PLUGIN_URL.'/includes/images/default_logo.png'; 
                }
                $newdays = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newdays');
                $expiredate = date_i18n('Y-m-d H:i:s', jsjobslib::jsjobs_strtotime($job->created.'+ '. $newdays.' days'));
                $hourago = __('Posted','js-jobs').": ";
                $startTimeStamp = jsjobslib::jsjobs_strtotime($job->created);
                $endTimeStamp = jsjobslib::jsjobs_strtotime("now");
                $timeDiff = abs($endTimeStamp - $startTimeStamp);
                $numberDays = $timeDiff / 86400;  // 86400 seconds in one day
                // and you might want to convert to integer
                $numberDays = intval($numberDays);
                if ($numberDays != 0 && $numberDays == 1) {
                    $day_text = __('Day', 'js-jobs');
                } elseif ($numberDays > 1) {
                    $day_text = __('Days', 'js-jobs');
                } elseif ($numberDays == 0) {
                    $day_text = __('Today', 'js-jobs');
                }
                if ($numberDays == 0) {
                    $hourago .= $day_text;
                } else {
                    $hourago .= $numberDays.' '.$day_text.' '.__('Ago','js-jobs');
                }


                $html .='<div id="js-jobs-wrapper">';
                $html .=    '<div class="js-toprow">';
                $html .=        '<div class="js-image">';
                $html .=            '<a href="'.jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$job->companyaliasid,'jsjobspageid'=>jsjobs::$_data['jsjobs_pageid']  )).'"><img src="'.$path.'"></a>';
                $html .=        '</div>';
                $html .=        '<div class="js-data">';
                $html .=            '<div class="js-first-row">';
                $html .=                '<span class="js-col-xs-12 js-col-sm-8 js-col-md-8 js-title joblist-jobtitle"><a href="'.jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$job->jobaliasid,'jsjobspageid'=>jsjobs::$_data['jsjobs_pageid'])).'">'.$job->title;
                $html .= '</a>';

                $html .=                '</span>';
                            
                $html .=                '<span class="js-col-xs-12 js-col-sm-4 js-col-md-4 js-jobtype joblist-jobtype">'.$hourago.'<span class="js-type">'. __($job->jobtypetitle,'js-jobs') .'</span></span>';
                            
                $html .=            '</div>';
                if(JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('comp_name') == 1){
                $html .=            '<div class="js-col-xs-12 js-col-sm-12 js-col-md-12 js-midrow">
                                        <a class="js-companyname" href="'.jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$job->companyaliasid,'jsjobspageid'=>jsjobs::$_data['jsjobs_pageid'] )).'">'.$job->companyname.'</a>
                                    </div>';
                }
                $html .=            '<div class="js-second-row">';
                            $print = $this->checkLinks('jobcategory');
                                if ($print[0] == 1) {

                $html .=            '<div class="js-col-xs-12 js-col-sm-6 js-col-md-4 js-fields for-rtl joblist-datafields">';
                                        if($labelflag){
                    $html .=                '<span class="js-bold">'.__($print[1],'js-jobs').': </span>';
                                        }
                    $html .=                '<span class="get-text">'. __($job->cat_title,'js-jobs') .'</span>
                                    </div>';
                                }
                                $print = $this->checkLinks('jobsalaryrange');
                                
                                if ($print[0] == 1) {
                $html .=                '<div class="js-col-xs-12 js-col-sm-6 js-col-md-4 js-fields for-rtl joblist-datafields">';
                                      if($labelflag){
                $html .=                        '<span class="js-bold">'.__($print[1],'js-jobs').': </span>';
                                      }
                $html .=                        '<span class="get-text">'.$job->salary.'</span>
                                        </div>';
                                }
                // custom fields 
                foreach ($customfields as $field) {
                    $html .= JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field,1,$job->params);
                }
                                //end 
                $html .=    '</div>
                                </div>
                            </div>';
                $html .=    '<div class="js-bottomrow">';
                            $print = $this-> checkLinks('city');
                                if ($print[0] == 1) {
                $html .=        '<div class="js-col-xs-12 js-col-md-8 js-address"><img class="location" src="'.JSJOBS_PLUGIN_URL.'/includes/images/location.png">'.$job->location.'</div>';
                                            }
                $html .=        '<div class="js-col-xs-12 js-col-md-4 js-actions">';
                $config_array3 = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('jobapply');
                if($config_array3['showapplybutton'] == 1){
                    if($job->jobapplylink == 1 && !empty($job->joblink)){
                        if(!jsjobslib::jsjobs_strstr('http',$job->joblink)){
                            $job->joblink = 'http://'.$job->joblink;
                        }  
                        $html .= '<a class="js-btn-apply" href="'.$job->joblink.'" target=_blank" >'.__('Apply Now','js-jobs').'</a>';
                    }elseif(!empty($config_array3['applybuttonredirecturl'])){
                        if(!jsjobslib::jsjobs_strstr('http',$config_array3['applybuttonredirecturl'])){
                            $joblink = 'http://'.$config_array3['applybuttonredirecturl'];
                        }else{
                            $joblink = $config_array3['applybuttonredirecturl'];
                        }
                        $html .= '<a class="js-btn-apply" href="'.$joblink.'" target="_blank">'.__('Apply Now','js-jobs').'</a>';
                    }else{
                        $isguest = JSJOBSincluder::getObjectClass('user')->isguest();
                        if($isguest){
                            $visitorcanapply = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_can_apply_to_job');
                            if($visitorcanapply == 1){
                                $visitor_show_login_message = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_show_login_message');
                                if($visitor_show_login_message == 1){
                                    $html .='<a class="js-btn-apply" href="#" onclick="getApplyNowByJobid('.$job->jobid.','.jsjobs::$_data['jsjobs_pageid'].');">'.__('Apply Now','js-jobs').'</a>';
                                }else{
                                    $vis_link = jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'action'=>'jsjobtask', 'task'=>'jobapplyasvisitor', 'jsjobsid-jobid'=>$job->jobid, 'jsjobspageid'=>jsjobs::$_data['jsjobs_pageid'], '_wpnoncejobapply'=>wp_create_nonce('apply-jobapply')));
                                    $html .='<a class="js-btn-apply" href="'.$vis_link.'">'.__('Apply Now','js-jobs').'</a>';
                                }
                            }else{
                                $html .='<a class="js-btn-apply" href="#" onclick="getApplyNowByJobid('.$job->jobid.','.jsjobs::$_data['jsjobs_pageid'].');">'.__('Apply Now','js-jobs').'</a>';
                            }
                        }else{
                            $html .='<a class="js-btn-apply" href="#" onclick="getApplyNowByJobid('.$job->jobid.','.jsjobs::$_data['jsjobs_pageid'].');">'.__('Apply Now','js-jobs').'</a>';
                        }
                    }
                }                           
                $html .=        '</div>
                            </div>
                        </div>';
                $noofjobs++;
            }
        }
        if($html != ''){
            $nextpage = JSJOBSpagination::$_currentpage;
            $nextpage += 1;
            $showmore = 0;
            if($nextpage % 6 == 0){
                $showmore = 1;
            }
            $html .= '<a class="scrolltask" data-showmore="'.$showmore.'" data-scrolltask="getNextJobs" data-offset="'.$nextpage.'"></a>';
            if($showmore == 1 && count($jobs) > 0){
                $html .= '<a id="showmorejobs" href="javascript:void(0);" onclick="showmorejobs();">'.__('Show More','js-jobs').'</a>';
            }
        }
        return $html;
    }

    function printtemplatejobs(&$jobs){
	if(jsjobs::$theme_chk == 2){
            return $this->printtemplatejobsJobHub($jobs);
        }
        //global $job_manager_options;
        $jsjm_options=job_manager_GetOptions();
        $dateformat = jsjobs::$_configuration['date_format'];
        //$jobs=array();
        if(empty($jobs)){
            $html="";
            $no_record_error_flag=true;
            if($no_record_error_flag==true){
                $html  .= '<div class="no-more-jobs-message"><h1>';
                $html .=     __('No More Jobs','job-manager');
                $html .= '</h1></div>';
            }else{
                if($html != ''){
                    $html .=$this->getNextJobsHtml($jobs);
                }
            }
            return $html;
        }

        $labelflag = true;
        $labelinlisting = jsjobs::$_configuration['labelinlisting'];
        if($labelinlisting != 1)
        $labelflag = false;
        
        
        $html = "";
        $noofjobs = 0;
        $customfields =JSJOBSincluder::getObjectClass('customfields')->userFieldsData(2,1);

            if (!empty($jobs)) {
                foreach ($jobs AS $job) {
                    $wpdir = wp_upload_dir();
                    if ($job->logofilename != "") {
                        $wpdir = wp_upload_dir();
                        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                        $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $job->companyid . '/logo/' . $job->logofilename;
                    } else {
                        $path = JOB_MANAGER_IMAGE.'/default_company_logo.png';
                    }
                    $newdays = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newdays');
                    $expiredate = date_i18n('Y-m-d H:i:s', jsjobslib::jsjobs_strtotime($job->created.'+ '. $newdays.' days'));
                    if ($_SESSION['jsjb_jm_listing_style'] == 1 || $_SESSION['jsjb_jm_listing_style'] == 2 || $_SESSION['jsjb_jm_listing_style'] == 3 ) {

                    $html .='<div class="jsjb-jm-newestjob-jobs-list-wrap object_'.$job->jobid.'"  data-boxid="myjob_'.$job->jobid.'">';
                        $html .='<div class="jsjb-jm-newestjob-jobs-list-img-wrap">
                                <a title="'.esc_attr(__('company logo','job-manager')).'" href="'.esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$job->companyid,'jsjobspageid'=>jsjobs::$_data['jsjobs_pageid']))).'"><img alt="'.esc_attr(__('company logo','job-manager')).'" title="'.esc_attr(__('company logo','job-manager')).'" class="jsjb-jm-newestjob-jobs-list-img" src="'.esc_attr($path).'" /></a>
                            </div>';

                        $html .='<div class="jsjb-jm-newestjob-jobs-list-top-wrap">';
                            $html .='<div class="jsjb-jm-newestjob-jobs-list-data-left">';
                                        $curdate = date_i18n('Y-m-d');
                                        $html .='<h4 class="jsjb-jm-newestjob-jobs-list-title">
                                            <a title="'.esc_attr(__('job title','job-manager')).'" class="jsjb-jm-newestjob-jobs-list-title-achor" href="'.esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$job->jobaliasid,'jsjobspageid'=>jsjobs::$_data['jsjobs_pageid']))).'">'.$job->title.'</a>';
                                            if( $newdays != 0 && date_i18n('Y-m-d') < $expiredate){ 
                                                $html .='<span class="jsjb-jm-jobs-list-horizental-new-tag">'.esc_html__('New','job-manager').'</span>';
                                            }    
                                            $ago_title=job_manager_time_elapsed_string((int) jsjobslib::jsjobs_strtotime($job->created)); 
                                            $html .='<span class="jsjb-jm-newestjob-jobs-list-time">'.jsjobslib::jsjobs_ucwords($ago_title).'</span> 
                                        </h4>

                                    </div>'; // top list data left  close

                            $html .='<div class="jsjb-jm-newestjob-jobs-list-data-right">';   
                                    $print = $this->checkLinks('jobtype');
                                    if ($print[0] == 1) {
                                        $job_type_class=job_manager_job_type_class($job->jobtypetit);
                                        $html .='<span class="jsjb-jm-newestjob-jobs-list-timing '.$job_type_class.' ">
                                            '.sprintf(__('%s','job-manager'),$job->jobtypetitle).'
                                        </span>';
                                         } 
                                    $html .='</div>'; // list data right close                

                        $html .='</div>'; // top wrap close  
                        $html .='<div class="jsjb-jm-newestjob-jobs-list-bottom-wrap">';
                            $html .='<div class="jsjb-jm-newestjob-jobs-list-bottom-left">';
                                $html .='<div class="jsjb-jm-newestjob-jobs-list-info jsjb-jm-bigfont">';
                                            if(JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('comp_name') == 1){
                                                $print = $this->checkLinks('company');
                                                if ($print[0] == 1) {
                                                $html .='<div class="jsjb-jm-newestjob-jobs-list-txt-wrap">
                                                    <img alt="'. esc_attr(__('company logo','job-manager')).'" title="'. esc_attr(__('company logo','job-manager')).'" class="jsjb-jm-myjobapply-list-company-logo" src="'.esc_attr(JOB_MANAGER_IMAGE.'/company-icon.png').'">
                                                    <a title="'.esc_attr(__('View Company','job-manager')).'" class="jsjb-jm-newestjob-jobs-list-company-name" href="'.esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$job->companyid,'jsjobspageid'=>jsjobs::$_data['jsjobs_pageid']))).'">'.$job->companyname.'</a>
                                                </div>';
                                                 } 
                                             } 

                                            $print = $this->checkLinks('jobsalaryrange');
                                            if ($print[0] == 1) { 
                                            $html .='<div class="jsjb-jm-newestjob-jobs-list-txt-wrap">
                                                <img alt="'.esc_attr(__('Price ','job-manager')).'" title="'.esc_attr(__('Price ','job-manager')).'" src="'.esc_attr(JOB_MANAGER_IMAGE.'/price-icon.png').' " />
                                                <span class="jsjb-jm-newestjob-jobs-list-txt">'.$job->salary.' </span>
                                            </div>';

                                            }
                                            $print = $this->checkLinks('city');
                                            if ($print) {
                                            $html .='<div class="jsjb-jm-newestjob-jobs-list-txt-wrap">
                                                <span class="jsjb-jm-newestjob-jobs-list-txt">
                                                <img alt="'.esc_attr(__('location ','job-manager')).'" title="'.esc_attr(__('location ','job-manager')).'" src="'.esc_attr(JOB_MANAGER_IMAGE.'/location-icon.png').'"/>
                                                '.$job->location.' 
                                                </span>
                                            </div>';
                                            }
                                $html .='</div>';
                                $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(2, 1);
                                if(!empty($customfields) ){
                                    $html .='<div class="jsjb-jm-newestjob-jobs-list-custom-fields-wrp jsjb-jm-bigfont">';
                                    foreach ($customfields as $field) {
                                        $return_custom=JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field->field, 11,$job->params);
                                        if(!empty($return_custom)){
                                        $html .='<div class="jsjb-jm-newestjob-jobs-list-custom-fields-data">
                                            <span class="jsjb-jm-newestjob-jobs-list-custom-fields-title">'.$return_custom['title'].': </span>
                                            <span class="jsjb-jm-newestjob-jobs-list-custom-fields-value">'.$return_custom['value'].'</span>
                                        </div>';
                                        } 
                                    }
                                    $html .='</div>'; // custom field wrap close 
                                }
                            $html .='</div>'; // bottom left close
                            $html .='<div class="jsjb-jm-newestjob-jobs-list-bottom-right">'; 
                                    $config_array3 = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('jobapply');
                                    if($config_array3['showapplybutton'] == 1){
                                        if($job->jobapplylink == 1 && !empty($job->joblink)){
                                            if(!jsjobslib::jsjobs_strstr('http',$job->joblink)){
                                                $job->joblink = 'http://'.$job->joblink;
                                            }
                                            $html .='<a title="apply now" class="jsjb-jm-newestjob-jobs-list-achor-btn resume" href="'.$job->joblink.'" target="_blank">
                                                '.__('Apply Now','job-manager').'
                                            </a>';
                                        }elseif(!empty($config_array3['applybuttonredirecturl'])){
                                            if(!jsjobslib::jsjobs_strstr('http',$config_array3['applybuttonredirecturl'])){
                                                $joblink = 'http://'.$config_array3['applybuttonredirecturl'];
                                            }else{
                                                $joblink = $config_array3['applybuttonredirecturl'];
                                            }
                                            $html .='<a title="apply now" class="jsjb-jm-newestjob-jobs-list-achor-btn resume" href="'.$joblink.'" target="_blank">
                                                '.__('Apply Now','job-manager').'
                                            </a>';
                                        }else{
                                            $isguest = JSJOBSincluder::getObjectClass('user')->isguest();
                                            if($isguest){
                                                $visitorcanapply = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_can_apply_to_job');
                                                if($visitorcanapply == 1){
                                                    $visitor_show_login_message = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_show_login_message');
                                                    if($visitor_show_login_message == 1){
                                                        $html .='<a title="apply now" class="jsjb-jm-newestjob-jobs-list-achor-btn resume" href="#" onclick="getApplyNowByJobid('.$job->jobid.','.jsjobs::$_data['jsjobs_pageid'].',1);" >
                                                            '.__('Apply Now','job-manager').'
                                                        </a>';
                                                    }else{
                                                        $vis_link = jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'action'=>'jsjobtask', 'task'=>'jobapplyasvisitor', 'jsjobsid-jobid'=>$job->jobid, 'jsjobspageid'=>jsjobs::$_data['jsjobs_pageid']));
                                                        $html .='<a title="apply now" class="jsjb-jm-newestjob-jobs-list-achor-btn resume" href="'.$vis_link.'" >
                                                            '.__('Apply Now','job-manager').'
                                                        </a>';
                                                    }
                                                }else{
                                                    $html .='<a title="apply now" class="jsjb-jm-newestjob-jobs-list-achor-btn resume" href="#" onclick="getApplyNowByJobid('.$job->jobid.','.jsjobs::$_data['jsjobs_pageid'].',1);" >
                                                            '.__('Apply Now','job-manager').'
                                                        </a>';
                                                }
                                            }else{
                                                $html .='<a title="apply now" class="jsjb-jm-newestjob-jobs-list-achor-btn resume" href="#" onclick="getApplyNowByJobid('.$job->jobid.','.jsjobs::$_data['jsjobs_pageid'].',1);" >
                                                    '.__('Apply Now','job-manager').'
                                                </a>';
                                            }
                                        }                                           
                                    }
                            $html .='</div>'; // bottom right close

                        $html .='</div>'; // bottom wrap close  


                    $html .='</div>'; // main div close 

                    $count_increment = true;
                    
                    if($count_increment == true){
                        $noofjobs++;
                    }
                }elseif ($_SESSION['jsjb_jm_listing_style'] == 4 || $_SESSION['jsjb_jm_listing_style'] == 5 || $_SESSION['jsjb_jm_listing_style'] == 6 ) {
                    $html .='<div class="col-md-4 jsjb-jm-joblist-box-data-grid-wrap" >
                    <div class="jsjb-jm-joblist-box-grid-data">';
                            $curdate = date_i18n('Y-m-d');
                            if( $newdays != 0 && date_i18n('Y-m-d') < $expiredate){ 
                                $html .='<span class="jsjb-jm-jobs-list-vetical-new-tag">'.esc_html__('New','job-manager').'</span>';
                            } 
                        $html .='<div class="jsjb-jm-joblist-box-logo-image">
                            <img alt="company logo" title="company logo" src="'.esc_attr($path).'" class="img-responsive">
                            <div class="overlay-wrap">
                                <div class="overlay-data">';
                                    $config_array3 = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('jobapply');
                                    if($config_array3['showapplybutton'] == 1){
                                        if($job->jobapplylink == 1 && !empty($job->joblink)){
                                            if(!jsjobslib::jsjobs_strstr('http',$job->joblink)){
                                                $job->joblink = 'http://'.$job->joblink;
                                            }  
                                            $html .='<a title="apply now" class="overlay-data-link" href="'.$job->joblink.'" target="_blank">
                                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                            </a>';
                                        }elseif(!empty($config_array3['applybuttonredirecturl'])){
                                            if(!jsjobslib::jsjobs_strstr('http',$config_array3['applybuttonredirecturl'])){
                                                $joblink = 'http://'.$config_array3['applybuttonredirecturl'];
                                            }else{
                                                $joblink = $config_array3['applybuttonredirecturl'];
                                            }
                                            $html .='<a title="apply now" class="overlay-data-link" href="'.$job->joblink.'" target="_blank">
                                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                            </a>';
                                        }else{
                                            $isguest = JSJOBSincluder::getObjectClass('user')->isguest();
                                            if($isguest){
                                                $visitorcanapply = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_can_apply_to_job');
                                                if($visitorcanapply == 1){
                                                    $visitor_show_login_message = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_show_login_message');
                                                    if($visitor_show_login_message == 1){
                                                        $html .='<a title="apply now" class="overlay-data-link" href="javascript:void(0);" onclick="getApplyNowByJobid('.$job->jobid.','.jsjobs::$_data['jsjobs_pageid'].',1);" >
                                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                        </a>';
                                                    }else{
                                                        $vis_link = jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'action'=>'jsjobtask', 'task'=>'jobapplyasvisitor', 'jsjobsid-jobid'=>$job->jobid, 'jsjobspageid'=>jsjobs::$_data['jsjobs_pageid']));
                                                        $html .='<a title="apply now" class="overlay-data-link" href="'.$vis_link.'" >
                                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                        </a>';
                                                    }
                                                }else{
                                                    $html .='<a title="apply now" class="overlay-data-link" href="javascript:void(0);" onclick="getApplyNowByJobid('.$job->jobid.','.jsjobs::$_data['jsjobs_pageid'].',1);" >
                                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                        </a>';
                                                }
                                            }else{
                                                $html .='<a title="apply now" class="overlay-data-link" href="javascript:void(0);" onclick="getApplyNowByJobid('.$job->jobid.','.jsjobs::$_data['jsjobs_pageid'].',1);" >
                                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                </a>';
                                            }
                                        }                                           
                                    }

                                $html .='</div>
                            </div>
                        </div>
                        <div class="jsjb-jm-joblist-box-data-mid">
                            <div class="jsjb-jm-joblist-box-data-info">';
                                if(JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('comp_name') == 1){
                                    $print = $this->checkLinks('company');
                                    if ($print[0] == 1) { 
                                        $html .='<span class="jsjb-jm-joblist-box-comp-name">
                                           <a href="'.esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$job->companyid,'jsjobspageid'=>jsjobs::$_data['jsjobs_pageid']))).'" > '.$job->companyname.'</a>
                                        </span>';
                                    }
                                }

                                $html .='<h5 class="jsjb-jm-joblist-box-job-title">
                                    <a title="'.esc_attr(__('job title','job-manager')).'" class="" href="'.esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$job->jobaliasid,'jsjobspageid'=>jsjobs::$_data['jsjobs_pageid']))).'">'.$job->title.'</a>
                                </h5>';
                                $print = $this->checkLinks('jobtype');
                                if ($print[0] == 1) {
                                    $job_type_class=job_manager_job_type_class($job->jobtypetit);
                                    $html .='<span class="jsjb-jm-joblist-box-job-status ">
                                        <span class="'.$job_type_class.' "> 
                                            '.sprintf(__('%s','job-manager'),$job->jobtypetitle).'
                                        </span>
                                    </span>';
                                } 
                                $print = $this->checkLinks('jobsalaryrange');
                                if ($print[0] == 1) {
                                    $html .='<span class="jsjb-jm-joblist-box-desc">
                                        <img alt="'.esc_attr(__('Price ','job-manager')).'" title="'.esc_attr(__('Price ','job-manager')).'" src="'.esc_attr(JOB_MANAGER_IMAGE.'/price-icon.png').' " />
                                        <span class="jsjb-jm-joblist-box-desc-text">'.$job->salary.' </span>
                                    </span>';

                                }
                                $html .='<span class="jsjb-jm-joblist-box-desc">
                                    <img alt="date" title="date" src="'.esc_attr(JOB_MANAGER_IMAGE.'/calendar-icon.png').' " class="img-responsive">
                                    <span class="jsjb-jm-joblist-box-desc-text"> 
                                        '.date($dateformat,jsjobslib::jsjobs_strtotime($job->created)).'
                                    </span>
                                </span>
                            </div>';
                            $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(2, 1);
                            if(!empty($customfields) ) { 
                                $html .='<div class="jsjb-jm-joblist-box-data-custom-fields-wrp jsjb-jm-bigfont">';
                                    foreach ($customfields as $field) {
                                        $return_custom=JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field->field, 11,$job->params);
                                        if(!empty($return_custom)){
                                            $html .='<div class="jsjb-jm-joblist-box-data-cf-data">
                                                <span class="jsjb-jm-joblist-box-data-cf-title">'.$return_custom['title'].': </span>
                                                <span class="jsjb-jm-joblist-box-data-cf-value">'.$return_custom['value'].'</span>
                                            </div>';
                                        }
                                    }
                                $html .='</div>';
                            }
                        $html .='</div>';
                        $print = $this->checkLinks('city');
                        if ($print) {
                            $html .='<div class="jsjb-jm-joblist-box-btm">
                                <span class="jsjb-jm-joblist-box-btm-text">
                                <img alt="'.esc_attr(__('location ','job-manager')).'" title="'.esc_attr(__('location ','job-manager')).'" src="'.esc_attr(JOB_MANAGER_IMAGE.'/location-icon.png').'"/>
                                '.$job->location.'
                                </span>
                            </div>';        
                        }

                        $html .='</div>
                    </div>';
                        $count_increment = true;

                        if($count_increment == true){
                            $noofjobs++;
                        }
                    }     
                }
            }   

        if($html != ''){
            $html .=$this->getNextJobsHtml($jobs);
        }
        return $html;
    }

    function getNextJobsHtml(&$jobs){
        $nextpage = JSJOBSpagination::$_currentpage;
        $nextpage += 1;
        $showmore = 0;
        if($nextpage % 6 == 0){
            $showmore = 1;
        }
        $html="";
        $html .= '<a id="jsjb-jm-showmorejobs" class="scrolltask" data-showmore="'.$showmore.'" data-scrolltask="getNextTemplateJobs" data-offset="'.esc_attr($nextpage).'" style="display:none;"></a>';

        if($showmore == 1 && ( count($jobs) > 0  || (isset(jsjobs::$_data['indeedjobs']['results']) && count(jsjobs::$_data['indeedjobs']['results']) > 0) || (isset(jsjobs::$_data['careerbuilder']['results']) && count(jsjobs::$_data['careerbuilder']['results']) > 0)  )){
        //if($showmore == 1){
            $html .= '<a id="jsjb-jm-showmorejobbtn" href="javascript:void(0);" onclick="showmorejobs();">
                        <span>' . __('Show More', 'job-manager') . '</span>
                        <img src="' . JOB_MANAGER_IMAGE . '/arrow-icon.png">
                    </a>';
        }
        return $html;
    }


    function printtemplatejobsJobHub(&$jobs){
        //global $job_manager_options;
        $dateformat = jsjobs::$_configuration['date_format'];
        $jsjh_options = job_hub_GetOptions();
        $option = isset($_SESSION['jsjb_jh_listing_style']) ? sanitize_key($_SESSION['jsjb_jh_listing_style']) : $jsjh_options['jobs_layout'];
        $noofjobs = 0;
    
        $customfields =JSJOBSincluder::getObjectClass('customfields')->userFieldsData(2,1);
        $job_list_plugin_call = 1;
        $job_list_page_id = jsjobs::$_data['jsjobs_pageid'];
        ob_start();
        switch($option){
                    case 1: // Full width List
                    case 2: // List left search bar
                    case 3: // List left search bar
                        include_once( get_template_directory() . '/js-jobs/fullwidthlist.php');
                        //include_once( get_template_directory() . '/js-jobs/listrightsearchbar.php');
                    break;
                    case 4: // Full width 3 column
                    case 5: // 3 column left search bar
                    case 6: // 3 column right search bar
                        include_once( get_template_directory() . '/js-jobs/fullwidth3column.php');
                        //include_once( get_template_directory() . '/js-jobs/3columnrightsearchbar.php');
                    break;
                    default:
                        include_once( get_template_directory() . '/js-jobs/fullwidthlist.php');
                    break;
                }
        $html = ob_get_contents();
        ob_end_clean();
        if(!isset(jsjobs::$_error_flag_message_for)){
            if($html != '' && count($jobs) > 0){
                $html .=$this->getNextJobsHtmlJobHub($jobs);
            }
        }
        return $html;
    }

    function getNextJobsHtmlJobHub(&$jobs){
        $nextpage = JSJOBSpagination::$_currentpage;
        $nextpage += 1;
        $showmore = 0;
        if($nextpage % 6 == 0){
            $showmore = 1;
        }
        $html="";
        $html .= '<a id="jsjb-jh-showmorejobs" class="scrolltask" data-showmore="'.$showmore.'" data-scrolltask="getNextTemplateJobs" data-offset="'.esc_attr($nextpage).'" style="display:none;"></a>';

        if($showmore == 1 && ( count($jobs) > 0  || (isset(jsjobs::$_data['indeedjobs']['results']) && count(jsjobs::$_data['indeedjobs']['results']) > 0) || (isset(jsjobs::$_data['careerbuilder']['results']) && count(jsjobs::$_data['careerbuilder']['results']) > 0)  )){
        //if($showmore == 1){
            $html .= '<a id="jsjb-jh-showmorejobbtn" href="javascript:void(0);" onclick="showmorejobs();">
                        <span>' . __('Show More', 'job-hub') . '</span>
                        <img src="' . JOB_HUB_IMAGE . '/arrow-icon.png">
                    </a>';
        }
        return $html;
    }

}

?>
