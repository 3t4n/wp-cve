<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSjobapplyModel {
    public $class_prefix = '';

    function __construct(){
        if(jsjobs::$theme_chk == 1){
            $this->class_prefix = 'jsjb-jm';
        }elseif(jsjobs::$theme_chk == 2){
            $this->class_prefix = 'jsjb-jh';
        }
    }

    function jsGetPrefix(){
        global $wpdb;
        if(is_multisite()) {
            $prefix = $wpdb->base_prefix;
        }else{
            $prefix = jsjobs::$_db->prefix;
        }
        return $prefix;
    }


    function getAppliedResume() {

        //Filters
        $searchtitle = JSJOBSrequest::getVar('searchtitle');
        $searchcompany = JSJOBSrequest::getVar('searchcompany');
        $searchjobcategory = JSJOBSrequest::getVar('searchjobcategory');
        $searchjobtype = JSJOBSrequest::getVar('searchjobtype');
        $searchjobstatus = JSJOBSrequest::getVar('searchjobstatus');

        jsjobs::$_data['filter']['searchtitle'] = $searchtitle;
        jsjobs::$_data['filter']['searchcompany'] = $searchcompany;
        jsjobs::$_data['filter']['searchjobcategory'] = $searchjobcategory;
        jsjobs::$_data['filter']['searchjobtype'] = $searchjobtype;
        jsjobs::$_data['filter']['searchjobstatus'] = $searchjobstatus;

        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        if ($searchjobstatus)
            if (is_numeric($searchjobstatus) == false)
                return false;

        $inquery = "";
        if ($searchtitle)
            $inquery .= " AND LOWER(job.title) LIKE '%" . $searchtitle . "%'";
        if ($searchcompany)
            $inquery .= " AND LOWER(company.name) LIKE '%" . $searchcompany . "%'";
        if ($searchjobcategory)
            $inquery .= " AND job.jobcategory = " . $searchjobcategory;
        if ($searchjobtype)
            $inquery .= " AND job.jobtype = " . $searchjobtype;
        if ($searchjobstatus)
            $inquery .= " AND job.jobstatus = " . $searchjobstatus;

        //Pagination
        $query = "SELECT COUNT(job.id) FROM " . jsjobs::$_db->prefix . "js_job_jobs AS job
        JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON job.companyid = company.id
        WHERE job.status <> 0";
        $query.=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle, company.name AS companyname
                , ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` WHERE jobid = job.id) AS totalresume
                FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON job.jobcategory = cat.id
                JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON job.companyid = company.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                WHERE job.status <> 0";
        $query.=$inquery;
        $query .= " ORDER BY job.created DESC";
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        return;
    }


    function getJobAppliedResume($tab_action, $jobid, $uid) {
        if (!is_numeric($jobid))
            return false;
        if($uid)
        if (!is_numeric($uid))
            return false;
        $this->getOrdering();
        $query = "SELECT title FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE id = " . $jobid;
        jsjobs::$_data['jobtitle'] = jsjobs::$_db->get_var($query);

        $inquery = "";

        //Pagination
        $query = "SELECT COUNT(job.id)
        FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
           , `" . jsjobs::$_db->prefix . "js_job_jobapply` AS jobapply
           , `" . jsjobs::$_db->prefix . "js_job_resume` AS app
        WHERE jobapply.jobid = job.id AND jobapply.cvid = app.id AND jobapply.jobid = " . $jobid;
        $query.=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT app.uid AS jobseekerid,company.uid AS employerid,jobapply.comments,jobapply.id AS jobapplyid ,job.id,job.uid as userid,job.agefrom,job.ageto
                , cat.cat_title ,jobapply.apply_date, jobapply.resumeview, jobtype.title AS jobtypetitle,app.iamavailable
                ,app.id AS appid, app.first_name, app.middle_name, app.last_name, app.email_address, app.jobtype,app.gender
                ,exp.title AS total_experience, jobapply.rating
                ,app.id as resumeid ,job.hits AS jobview,app.last_modified
                ,(SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` WHERE jobid = job.id) AS totalapply
                ,(SELECT address_city FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` WHERE resumeid = app.id ORDER BY created DESC LIMIT 1) AS resumecity
                ,salaryrangefrom.rangestart, salaryend.rangeend,education.title AS educationtitle
                ,currency.symbol AS symbol,saltype.title AS saltypetitle,dsaltype.title AS dsaltypetitle
                ,dcurrency.symbol AS dsymbol ,dsalarystart.rangestart AS drangestart, dsalaryend.rangeend AS drangeend
                ,app.photo AS photo,app.application_title AS applicationtitle
                ,CONCAT(app.alias,'-',app.id) resumealiasid, CONCAT(job.alias,'-',job.id) AS jobaliasid
                ,cletter.id AS cletterid, cletter.title AS clettertitle
                ,( Select rinsitute.institute From`" . jsjobs::$_db->prefix . "js_job_resumeinstitutes` AS rinsitute Where rinsitute.resumeid = app.id LIMIT 1 ) AS institute
                ,( Select rinsitute.institute_study_area From`" . jsjobs::$_db->prefix . "js_job_resumeinstitutes` AS rinsitute Where rinsitute.resumeid = app.id LIMIT 1 ) AS institute_study_area
                ,cletter.description AS cletterdescription,job.companyid,jobapply.socialapplied,jobapply.socialprofileid
                FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                JOIN `" . jsjobs::$_db->prefix . "js_job_jobapply` AS jobapply  ON jobapply.jobid = job.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resume` AS app ON app.id = jobapply.cvid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON cat.id = job.jobcategory
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_experiences` AS exp ON app.experienceid=exp.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_heighesteducation` AS  education  ON app.heighestfinisheducation=education.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangefrom  ON  app.jobsalaryrangestart=salaryrangefrom.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryend  ON  app.jobsalaryrangeend=salaryend.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS dsalarystart ON app.desiredsalarystart=dsalarystart.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS dsalaryend ON app.desiredsalaryend=dsalaryend.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = app.currencyid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS dcurrency ON dcurrency.id = app.dcurrencyid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_coverletters` AS cletter ON jobapply.coverletterid = cletter.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS dsaltype ON app.djobsalaryrangetype = dsaltype.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS saltype ON app.jobsalaryrangetype = saltype.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = job.companyid
            WHERE jobapply.jobid = " . $jobid;
        $query.=$inquery;
        $query .= " ORDER BY " . jsjobs::$_ordering;
        $query .= " LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        $result = jsjobsdb::get_results($query);
        jsjobs::$_data[0]['ta'] = $jobid;
        jsjobs::$_data[0]['tabaction'] = $tab_action;
        jsjobs::$_data[0]['jobid'] = $jobid;
        $data = array();
        foreach ($result AS $d) {
            $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->resumecity);
            $d->dsalary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView($d->dsymbol, $d->drangestart, $d->drangeend, $d->dsaltypetitle);
            $d->salary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView($d->symbol, $d->rangestart, $d->rangeend, $d->saltypetitle);
            $data[] = $d;
        }
        jsjobs::$_data[0]['data'] = $data;

        $query = "Select Count(id) from`" . jsjobs::$_db->prefix . "js_job_jobapply` WHERE jobid=$jobid";
        jsjobs::$_data[0]['applied'] = jsjobsdb::get_var($query);

        $query = "Select hits from`" . jsjobs::$_db->prefix . "js_job_jobs` WHERE id=$jobid";
        jsjobs::$_data[0]['hits'] = jsjobsdb::get_var($query);

        $query = "Select title from`" . jsjobs::$_db->prefix . "js_job_jobs` WHERE id = $jobid";
        jsjobs::$_data[0]['jobtitle'] = jsjobsdb::get_var($query);
        jsjobs::$_data['listingfields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsForListing(3);
        return;
    }

    function getResumeDetail($themecall=null) {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $salary = JSJOBSrequest::getVar('sal');
        $exprince = JSJOBSrequest::getVar('expe');
        $insitute = JSJOBSrequest::getVar('institue');
        $study = JSJOBSrequest::getVar('stud');
        $available = JSJOBSrequest::getVar('ava');

        if ($available == 1) {
            $res = "Yes";
        } else {
            $res = "No";
        }
        if(null != $themecall){
            $return['salary']=$salary;
            $return['exprince']=$exprince;
            $return['insitute']=$insitute;
            $return['study']=$study;
            $return['available']=$available;
            $return['res']=$res;
            return $return;
        }
        $html = '';
        $html.='<img id="close-section" onclick="closeSection()" src="' . JSJOBS_PLUGIN_URL . 'includes/images/no.png"/>';
        $html.='<span class="detail">';
        $html.='<span class="heading">' . __('Current Salary', 'js-jobs') . ': </span>' . $salary;
        $html.='</span>';
        $html.='<span class="detail">';
        $html.='<span class="heading">' . __('Experience', 'js-jobs') . ': </span>' . __($exprince,'js-jobs');
        $html.='</span>';
        $html.='<span class="detail">';
        $html.='        <span class="heading">' . __('Institute', 'js-jobs') . ': </span>' . $insitute;
        $html.='</span>';
        $html.='<span class="detail">';
        $html.='        <span class="heading">' . __('Study Area', 'js-jobs') . ': </span>' . $study;
        $html.='</span>';
        $html.='<span class="detail">';
        $html.='        <span class="heading">' . __('Available', 'js-jobs') . ': </span>' . $res;
        $html.='</span>';
        return $html;
    }
    function getJobApplyHtmlForJobManager($jobid){

        $content="";
        $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('jobapply');
        if ($jobid && is_numeric($jobid)) {
                $query = "SELECT job.title FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job WHERE job.id = " . $jobid;
                $jobtitle = jsjobs::$_db->get_var($query);

                $title = __('Apply Now', 'js-jobs');
                $content = '<div class="modal-content '.$this->class_prefix.'-modal-wrp">';
                $content .= '<div class="'.$this->class_prefix.'-modal-left-image-wrp">
                            <i class="fa fa-paper-plane '.$this->class_prefix.'-modal-left-image" aria-hidden="true"></i>
                        </div>
                        <div class="'.$this->class_prefix.'-modal-header">
                            <a title="close" class="close '.$this->class_prefix.'-modal-close-icon-wrap" href="#" onclick="jsjobsClosePopup(1);" >
                                <img id="popup_cross" alt="popup cross" src="'.JSJOBS_PLUGIN_URL.'includes/images/popup-close.png">
                            </a>
                            <h3 class="'.$this->class_prefix.'-modal-title">'.__("Apply Now", "js-jobs").'</h3>
                        </div>';
                $content .= '<div class="col-md-12 '.$this->class_prefix.'-modal-job-title-wrp">
                            <div class="col-md-11 col-md-offset-1 '.$this->class_prefix.'-modal-job-title">
                                <h5 class="'.$this->class_prefix.'-modal-job-title-txt">'.$jobtitle.'</h5>
                            </div>
                        </div>';
                $content .= '<div class="col-md-11 col-md-offset-1 '.$this->class_prefix.'-modal-data-wrp">';
                $content .= '<div class="modal-body '.$this->class_prefix.'-modal-body">';
                $content .= '<div class="form '.$this->class_prefix.'-modal-form-wrp">';


                $showlink = true;
                $isjobseeker = JSJOBSincluder::getObjectClass('user')->isjobseeker();
                $isemployer = JSJOBSincluder::getObjectClass('user')->isemployer();
                if (!JSJOBSincluder::getObjectClass('user')->isguest()) {
                    $uid = JSJOBSincluder::getObjectClass('user')->uid();
                    $resumelist = null;
                    $coverletterlist = null;
                    if (is_numeric($uid) && $uid != 0 && $isjobseeker == true) {
                        $query = "SELECT id,CONCAT(first_name, ' ', last_name) AS text FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE status = 1 AND uid = " . $uid;
                        $resumelist = jsjobs::$_db->get_results($query);
                        $query = "SELECT id,title AS text FROM `" . jsjobs::$_db->prefix . "js_job_coverletters` WHERE status=1 AND uid = " . $uid;
                        $coverletterlist = jsjobs::$_db->get_results($query);
                    }
                    if ($resumelist != null && $isjobseeker == true) {
                        $content .= '<div class="col-md-12 '.$this->class_prefix.'-modal-form-row">';
                        $content .= '<div class="col-md-6 '.$this->class_prefix.'-modal-form-inpf-l">';
                        $content .= '<div class="form-group">';
                        $content .= '<label for="cvid" class="label-control" >' . __('Resume', 'js-jobs') . '</label>';
                        $content .= JSJOBSformfield::select('cvid', $resumelist, '','',array("class"=>"form-control"));
                        $content .= '</div>';
                        $content .= '</div>';
                        $content .= '<div class="col-md-6 '.$this->class_prefix.'-modal-form-inpf-r">';
                        $content .= '<div class="form-group">';
                        $content .= '<label for="coverletterid">' . __('Cover letter', 'js-jobs') . '</label>';
                        if ($coverletterlist == null) {
                            $cl_link = jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'addcoverletter', 'jsjobspageid'=>JSJOBSrequest::getVar('jsjobs_pageid')));
                            $content .= '<span class="no-resume-span">' . __('you do not have any cover letter', 'js-jobs') . '</span><a href ="' .$cl_link.'" class="no-resume-link">' . __('Add','js-jobs') .' '. __('Cover Letter', 'js-jobs') . '</a>';

                        } else {
                            $content .= JSJOBSformfield::select('coverletterid', $coverletterlist, '',__("Select cover letter","js-jobs"),array("class"=>"form-control"));
                        }
                        $content .= '</div>';
                        $content .= '</div>';
                        $content .= '</div>';// form row  close
                        $link1 = 'href="#" onclick="jobApply(' . $jobid . ',1);"';
                        $text1 = __('Apply Now', 'js-jobs');
                        $class1 = '';
                        $class2 = ''.$this->class_prefix.'-btn-primary';
                    } else {
                        $showlink = false;
                        if ($isjobseeker == true) {
                            $content .= '<div class="'.$this->class_prefix.'-popup-row-msg">';
                            $content .= '<span class="visitor-message"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/not-loggedin.png" />' . __('You do not have any resume', 'js-jobs') . '</span>';
                            $content .= '</div>';
                            $content .= '   <div class="quickviewbutton">
                            <a class="'.$this->class_prefix.'-btn-primary" href="'.jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'addresume', 'jsjobspageid'=>JSJOBSrequest::getVar('jsjobs_pageid'))).'" > ' . __('Add Resume', 'js-jobs') . '</a>
                        </div>';
                        } elseif($isemployer == true) {
                            $content .= '<div class="'.$this->class_prefix.'-popup-row-msg">';
                            $content .= '<span class="visitor-message"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/not-loggedin.png" />' . __('You are employer, you can not apply to job', 'js-jobs') . '!</span>';
                            $content .= '</div>';
                        } else {
                            $content .= '<div class="'.$this->class_prefix.'-popup-row-msg">';
                            $content .= '<span class="visitor-message"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/not-loggedin.png" />' . __('You do not have any role', 'js-jobs') . '!</span>';
                            $content .= '</div>';
                            $showlink = true;
                            $link1 = 'href="' . jsjobs::makeUrl(array('jsjobsme'=>'common','jsjobslt'=>'newinjsjobs', 'jsjobsid-jobid'=>$jobid, 'jsjobspageid'=>jsjobs::getPageid())) . '" target="_blank" ';
                            $text1 = __('Select Role', 'js-jobs');
                            $class2 = ''.$this->class_prefix.'-btn-primary';
                        }
                    }
                } else {
                    $content .= '<div class="'.$this->class_prefix.'-popup-row-msg">';
                    $content .= '<span class="'.$this->class_prefix.'-popup-row-txt"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/not-loggedin.png" />' . __('You are not a logged in member, please select below option to apply on job.', 'js-jobs') . '</span>';
                    $content .= '</div>';
                    $link1 = 'href="' . jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'action'=>'jsjobtask', 'task'=>'jobapplyasvisitor', 'jsjobsid-jobid'=>$jobid, 'jsjobspageid'=>JSJOBSrequest::getVar('jsjobs_pageid'))) . '"';
                    $thiscpurl = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$jobid));
                    $thiscpurl = jsjobslib::jsjobs_safe_encoding($thiscpurl);
                    $link2 = 'href="'.jsjobs::makeUrl(array('jsjobsme'=>'jsjobs', 'jsjobslt'=>'login', 'jsjobsredirecturl'=>$thiscpurl, 'jsjobspageid'=>JSJOBSrequest::getVar('jsjobs_pageid'))).'"';
                    $text1 = __('Apply as visitor', 'js-jobs');
                    $text2 = __('Login', 'js-jobs');
                    $class1 = 'login '.$this->class_prefix.'-btn-secondary';
                    $class2 = 'applyvisitor '.$this->class_prefix.'-btn-primary';
                }
                $visitor_can_apply_to_job = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_can_apply_to_job');
                $content .= '<div class="col-md-12 '.$this->class_prefix.'-modal-form-btn">';
                if ($showlink == true) {
                    $full=true;
                    $content .= '   <div class="form-group">';
                    if(JSJOBSincluder::getObjectClass('user')->isguest()){
                        if(isset($link2) && $link2!=""){
                            $full=false;
                            $content .= '<div class="col-md-6 '.$this->class_prefix.'-modal-form-inpf-l">';
                                $content .='<a ' . $link2 . ' class="'.$this->class_prefix.'-modal-form-btn-inpf ' . $class1 . '" >' . $text2 . '</a>';
                            $content .= '</div>';
                        }
                        if($visitor_can_apply_to_job == 1){
                            if($full===false) $content .= '<div class="col-md-6 '.$this->class_prefix.'-modal-form-inpf-l">';
                                $content .= '<a ' . $link1 . ' class="'.$this->class_prefix.'-modal-form-btn-inpf ' . $class2 . '" id="apply-now-btn" >' . $text1 . '</a>';
                            if($full===false) $content .= '</div">';
                        }
                    }else{
                        if($full===false) $content .= '<div class="col-md-6 '.$this->class_prefix.'-modal-form-inpf-l">';
                                $content .= '<a ' . $link1 . ' class="'.$this->class_prefix.'-modal-form-btn-inpf ' . $class2 . '" id="apply-now-btn" >' . $text1 . '</a>';
                            if($full===false) $content .= '</div">';
                    }
                    $content .= ' </div>';
                }
                $content .= '</div>'; // form close
                if ($config_array['applywithfacebook'] == 1 || $config_array['applywithlinkedin'] == 1 || $config_array['applywithxing'] == 1) {
                    if ($isemployer != true) {
                        $content .= '   <div class="'.$this->class_prefix.'-social-btn-wrp">';
                    $jsnext = jsjobs::makeUrl(array('jsjobsme'=>'job','jsjobslt'=>'viewjob','jsjobsid'=>$jobid,'jsjobspageid'=>jsjobs::getPageid()));
                    if ($config_array['applywithfacebook'] == 1) {
                                    //$content .= '<a href="'.jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'action'=>'jsjobtask', 'task'=>'jobapplysocial', 'media'=>'facebook', 'jobid'=>$jobid. 'jsnext'=>$jsnext, '_wpnonce'=>wp_create_nonce('socialjob-jobapply'))) . '" class="sc fb"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/scico/fb.png" />' . __('Apply with facebook','js-jobs') . '</a>';
                        $content .= '<a class="'.$this->class_prefix.'-social-btn" href="'.jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'action'=>'jsjobtask', 'task'=>'jobapplysocial', 'media'=>'facebook', 'jobid'=>$jobid, 'jsnext'=>$jsnext, '_wpnonce'=>wp_create_nonce('socialjob-jobapply'))) . '" class="sc fb" ><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/scico/fb.png" />' . __('Apply with facebook','js-jobs') . '</a>';
                    }
                    if ($config_array['applywithlinkedin'] == 1) {
                        $content .= '<a class="'.$this->class_prefix.'-social-btn" href="'.jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'action'=>'jsjobtask', 'task'=>'jobapplysocial', 'media'=>'linkedin', 'jobid'=>$jobid, 'jsnext'=>$jsnext, '_wpnonce'=>wp_create_nonce('socialjob-jobapply'))) . '" class="sc linkedin" ><img class="js-linkd" src="' . JSJOBS_PLUGIN_URL . 'includes/images/scico/in.png" />' . __('Apply with linkedin','js-jobs') . '</a>';
                    }
                    if ($config_array['applywithxing'] == 1) {
                        $content .= '<a class="'.$this->class_prefix.'-social-btn" href="'.jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'action'=>'jsjobtask', 'task'=>'jobapplysocial', 'media'=>'xing', 'jobid'=>$jobid, 'jsnext'=>$jsnext, '_wpnonce'=>wp_create_nonce('socialjob-jobapply'))). '" class="sc xing" ><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/scico/xing.png" />' . __('Apply with xing','js-jobs') . '</a>';
                    }
                    $content .= '   </div>';
                }
            }

            $content .= '</div>'; // model body close
            $content .= '</div>';// model data wrap close
            $content .= '</div>'; // model wrap close
        } else {
            $title = __('No record found', 'js-jobs');
            $content = '<div class="modal-content '.$this->class_prefix.'-modal-wrp">';
            $content .= '<div class="'.$this->class_prefix.'-modal-left-image-wrp">
                        <i class="fa fa-paper-plane '.$this->class_prefix.'-modal-left-image" aria-hidden="true"></i>
                    </div>
                    <div class="'.$this->class_prefix.'-modal-header">
                        <a title="close" class="close '.$this->class_prefix.'-modal-close-icon-wrap" href="#" onclick="jsjobsClosePopup(1);" >
                            <img id="popup_cross" alt="popup cross" src="'.JSJOBS_PLUGIN_URL.'includes/images/popup-close.png">
                        </a>
                        <h3 class="'.$this->class_prefix.'-modal-title">'.__("Apply Now", "js-jobs").'</h3>
                    </div>';
            $content .= '<div class="col-md-12 '.$this->class_prefix.'-modal-job-title-wrp">
                        <div class="col-md-11 col-md-offset-1 '.$this->class_prefix.'-modal-job-title">
                            <h1 class="'.$this->class_prefix.'-modal-job-title-txt">'.$title.'</h1>
                        </div>
                    </div>
                    </div>';
        }
        return $content;
    }

    function getJobApplyDetailByid(){
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $id = JSJOBSrequest::getVar('id');
        $pageid = JSJOBSrequest::getVar('pageid');
        $content="";
        if ($id && is_numeric($id)) {
            $query = "SELECT resume.id AS resumeid
                    ,CONCAT(resume.first_name, ' ', resume.last_name) AS Name,coverletter.title AS coverlettertitle,coverletter.id AS coverletterid
                    ,jobapply.id AS id
                     FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` AS jobapply
                     JOIN `" . jsjobs::$_db->prefix . "js_job_resume` AS resume ON resume.id = jobapply.cvid
                     LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_coverletters` AS coverletter ON coverletter.id = jobapply.coverletterid
                     WHERE jobapply.id = " . $id;
            $result = jsjobsdb::get_row($query);
            if($result){
                $content .='<div class="modal-content '.$this->class_prefix.'-modal-wrp">
                                <div class="'.$this->class_prefix.'-modal-header">
                                    <a title="close" class="close '.$this->class_prefix.'-modal-close-icon-wrap" href="#" onclick="jsjobsClosePopup(1);" >
                                        <img id="popup_cross" alt="popup cross" src="'.JSJOBS_PLUGIN_URL.'includes/images/popup-close.png">
                                    </a>
                                    <h2 class="'.$this->class_prefix.'-modal-title">'.__("Applied Info","job-manager").'</h2>
                                </div>
                                <div class="col-md-12 '.$this->class_prefix.'-appliedinformation-modal-data-wrp">
                                    <div class="modal-body '.$this->class_prefix.'-modal-body">
                                       <div class="'.$this->class_prefix.'-appliedinformation-title">

                                       <h5 class="'.$this->class_prefix.'-appliedinformation-title-txt">
                                            <a href="'.jsjobs::makeUrl(array("jsjobspageid"=>$pageid,"jsjobsme"=>"resume","jsjobslt"=>"viewresume","jsjobsid"=>$result->resumeid)).'">
                                                '.$result->Name.'
                                            </a>';
                                            if($result->application_title != ''){
                                                $content .= '('.$result->application_title.')';
                                            }
                                        $content .='
                                        </h5>
                                       </div>
                                       <div class="'.$this->class_prefix.'-appliedinformation-value">
                                            <a href="'.jsjobs::makeUrl(array("jsjobspageid"=>$pageid,"jsjobsme"=>"coverletter","jsjobslt"=>"viewcoverletter","jsjobsid"=>$result->coverletterid)).'">
                                                '.$result->coverlettertitle.'
                                            </a>
                                       </div>
                                    </div>
                                </div>
                            </div>';
            }else{
                $content .='<div class="modal-content '.$this->class_prefix.'-modal-wrp">
                    <div class="'.$this->class_prefix.'-modal-header">
                        <a title="close" class="close '.$this->class_prefix.'-modal-close-icon-wrap" href="#" onclick="jsjobsClosePopup(1);" >
                            <img id="popup_cross" alt="popup cross" src="'.JSJOBS_PLUGIN_URL.'includes/images/popup-close.png">
                        </a><h2 class="'.$this->class_prefix.'-modal-title">'.__("Applied Info","job-manager").'</h2></div>
                        <div class="col-md-12 '.$this->class_prefix.'-appliedinformation-modal-data-wrp">
                            <h3 class="'.$this->class_prefix.'-modal-title">'.__("No Record Found","job-manager").'</h3>
                        </div>
                        </div>';
            }
        }else{
            $content .='<div class="modal-content '.$this->class_prefix.'-modal-wrp">
            <div class="'.$this->class_prefix.'-modal-header">
                <a title="close" class="close '.$this->class_prefix.'-modal-close-icon-wrap" href="#" onclick="jsjobsClosePopup(1);" >
                    <img id="popup_cross" alt="popup cross" src="'.JSJOBS_PLUGIN_URL.'includes/images/popup-close.png">
                </a><h2 class="'.$this->class_prefix.'-modal-title">'.__("Applied Info","job-manager").'</h2></div>
                <div class="col-md-12 '.$this->class_prefix.'-appliedinformation-modal-data-wrp">
                    <h3 class="'.$this->class_prefix.'-modal-title">'.__("Something wrong pleas try later","job-manager").'</h3>
                </div>
                </div>';
        }
        $array = array('title' => "", 'content' => $content);
        return json_encode($array);
    }

    function getApplyNowByJobid() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $jobid = JSJOBSrequest::getVar('jobid');
        $themecall = JSJOBSrequest::getVar('themecall');
        $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('jobapply');
        //if (locate_template('js-jobs/popup-jobs-jobapply.php')) {
        if (null!=$themecall) {
                $return_value=$this->getJobApplyHtmlForJobManager($jobid);
                $title="";
                $content=$return_value;
                //include_once(locate_template('js-jobs/popup-jobs-jobapply.php'));
        } else {
            if ($jobid && is_numeric($jobid)) {
                $query = "SELECT job.title FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job WHERE job.id = " . $jobid;
                $jobtitle = jsjobs::$_db->get_var($query);

                $title = __('Apply Now', 'js-jobs');
                $content = '<div class="quickviewrow">';
                $content .= '<span class="jobtitle">' . $jobtitle . '</span>';
                $showlink = true;
                if (!JSJOBSincluder::getObjectClass('user')->isguest()) {
                    $uid = JSJOBSincluder::getObjectClass('user')->uid();
                    $resumelist = null;
                    $coverletterlist = null;
                    $isjobseeker = JSJOBSincluder::getObjectClass('user')->isjobseeker();
                    $isemployer = JSJOBSincluder::getObjectClass('user')->isemployer();
                    if (is_numeric($uid) && $uid != 0 && $isjobseeker == true) {
                        $query = "SELECT id,CONCAT(first_name, ' ', last_name) AS text FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE status = 1 AND uid = " . $uid;
                        $resumelist = jsjobs::$_db->get_results($query);
                        $query = "SELECT id,title AS text FROM `" . jsjobs::$_db->prefix . "js_job_coverletters` WHERE status=1 AND uid = " . $uid;
                        $coverletterlist = jsjobs::$_db->get_results($query);
                    }
                    if ($resumelist != null && $isjobseeker == true) {
                        $content .= '<div class="quickviewhalfwidth">';
                        $content .= '<label for="cvid">' . __('Resume', 'js-jobs') . '</label>';
                        $content .= JSJOBSformfield::select('cvid', $resumelist, '');
                        $content .= '</div>';
                        $content .= '<div class="quickviewhalfwidth">';
                        $content .= '<label for="coverletterid">' . __('Cover letter', 'js-jobs') . '</label>';
                        if ($coverletterlist == null) {
							$cl_link = jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'addcoverletter', 'jsjobspageid'=>JSJOBSrequest::getVar('jsjobs_pageid')));
                            $content .= '<span class="no-resume-span">' . __('you do not have any cover letter', 'js-jobs') . '</span><a href ="' .$cl_link.'" class="no-resume-link">' . __('Add','js-jobs') .' '. __('Cover Letter', 'js-jobs') . '</a>';

                        } else {
                            $content .= JSJOBSformfield::select('coverletterid', $coverletterlist, '',__("Select cover letter","js-jobs"));
                        }
                        $content .= '</div>';
                        $link1 = 'href="#" onclick="jobApply(' . $jobid . ');"';
                        $link2 = 'href="#" onclick="closePopup();"';
                        $text1 = __('Apply Now', 'js-jobs');
                        $text2 = __('Close', 'js-jobs');
                        $class1 = '';
                        $class2 = '';
                    } else {
                        $showlink = false;
                        if ($isjobseeker == true) {
                            $content .= '<div class="quickviewrow">';
                            $content .= '<span class="visitor-message"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/not-loggedin.png" />' . __('You do not have any resume!', 'js-jobs') . '</span>';
                            $content .= '</div>';
                            $content .= '   <div class="quickviewbutton">
                                                <a href="'.jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'addresume', 'jsjobspageid'=>JSJOBSrequest::getVar('jsjobs_pageid'))).'" class="resumeaddlink"> <img src="' . JSJOBS_PLUGIN_URL .'includes/images/add-circle.png" />' . __('Add Resume', 'js-jobs') . '</a>
                                            </div>';
                        } elseif($isemployer == true) {
                            $content .= '<div class="quickviewrow">';
                            $content .= '<span class="visitor-message"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/not-loggedin.png" />' . __('You are employer, you can not apply to job', 'js-jobs') . '!</span>';
                            $content .= '</div>';
                        } else {
                            $showlink = true;
                            $content .= '<div class="quickviewrow">';
                            $content .= '<span class="visitor-message"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/not-loggedin.png" />' . __('You do not have any role', 'js-jobs') . '!</span>';
                            $content .= '</div>';
                            $link1 = 'href="' . jsjobs::makeUrl(array('jsjobsme'=>'common','jsjobslt'=>'newinjsjobs', 'jsjobsid-jobid'=>$jobid, 'jsjobspageid'=>jsjobs::getPageid())) . '" target="_blank" ';
                            $text1 = __('Select Role', 'js-jobs');
                            $link2 = 'href="#" onclick="closePopup();"';
                            $text2 = __('Close', 'js-jobs');
                        }
                    }
                } else {
                    $content .= '<div class="quickviewrow">';
                    $content .= '<span class="visitor-message"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/not-loggedin.png" />' . __('You are not a logged in member, please select below option to apply on job.', 'js-jobs') . '</span>';
                    $content .= '</div>';
                    $link1 = 'href="' . jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'action'=>'jsjobtask', 'task'=>'jobapplyasvisitor', 'jsjobsid-jobid'=>$jobid, 'jsjobspageid'=>jsjobs::getPageid())) . '"';
		    $thiscpurl = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$jobid, 'jsjobspageid'=>jsjobs::getPageid()));
                    $thiscpurl = jsjobslib::jsjobs_safe_encoding($thiscpurl);
					$link2 = 'href="'.jsjobs::makeUrl(array('jsjobsme'=>'jsjobs', 'jsjobslt'=>'login', 'jsjobsredirecturl'=>$thiscpurl, 'jsjobspageid'=>jsjobs::getPageid())).'"';
                    $text1 = __('Apply as visitor', 'js-jobs');
                    $text2 = __('Login', 'js-jobs');
                    $class1 = 'login';
                    $class2 = 'applyvisitor';
                }
                $visitor_can_apply_to_job = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_can_apply_to_job');
                $content .= '<div class="quickviewlower">';
                if ($showlink == true) {
                    $content .= '   <div class="quickviewbutton">
                                        <a ' . $link2 . ' class="quickviewbutton ' . $class1 . '" >' . $text2 . '</a>';
                        if(JSJOBSincluder::getObjectClass('user')->isguest()){
                            if($visitor_can_apply_to_job == 1){
                                $content .= '<a ' . $link1 . ' class="quickviewbutton ' . $class2 . '" id="apply-now-btn" >' . $text1 . '</a>';
                            }
                        }else{
                            $content .= '<a ' . $link1 . ' class="quickviewbutton ' . $class2 . '" id="apply-now-btn" >' . $text1 . '</a>';
                        }                    $content .= ' </div>';
                }
                $content .= '</div>';
                $content .= '</div>';
            } else {
                $title = __('No record found', 'js-jobs');
                $content = '<h1>' . __('No record found', 'js-jobs') . '</h1>';
            }
        }
        return $content;
        $array = array('title' => $title, 'content' => $content);
        $array = array_map('utf8_encode', $array);
        return json_encode($array);
    }

    function jobapplyjobmanager(){
        $return_val=$this->jobapply(1);
        if($return_val===1){
            $msg = '<div id="'.$this->class_prefix.'-notification-not-ok"><div id="popup_message">
            <img src="' . JSJOBS_PLUGIN_URL . 'includes/images/unpublish.png"/><span class="popup_msg_txt">' . __("please select a resume first", "js-jobs") . '</span><button class="applynow-closebutton" onclick="jsjobsClosePopup(1);" ><img src="'.JSJOBS_PLUGIN_URL.'/includes/images/popupcloseicon.png"/>'.__('Close','js-jobs').'</button></div></div>';
        }elseif($return_val === 2) {
            $msg = '<div id="'.$this->class_prefix.'-notification-ok"><div id="popup_message">
            <img src="' . JSJOBS_PLUGIN_URL . 'includes/images/approve.png"/><span class="popup_msg_txt">' . __("You have already applied this job", "js-jobs") . '</span><button class="applynow-closebutton" onclick="jsjobsClosePopup(1);" ><img src="'.JSJOBS_PLUGIN_URL.'/includes/images/popupcloseicon.png"/>'.__('Close','js-jobs').'</button></div></div>';
        }elseif($return_val == JSJOBS_SAVE_ERROR) {
            $msg = '<div id="'.$this->class_prefix.'-notification-not-ok"><div id="popup_message">
            <img src="' . JSJOBS_PLUGIN_URL . 'includes/images/unpublish.png"/><span class="popup_msg_txt">' . __("Failed while performing this action", "js-jobs") . '</span><button class="applynow-closebutton" onclick="jsjobsClosePopup(1);" ><img src="'.JSJOBS_PLUGIN_URL.'/includes/images/popupcloseicon.png"/>'.__('Close','js-jobs').'</button></div></div>';
        }elseif($return_val == JSJOBS_SAVED) {
            $msg = '<div id="'.$this->class_prefix.'-notification-ok"><div id="popup_message">
            <img src="' . JSJOBS_PLUGIN_URL . 'includes/images/approve.png"/><span class="popup_msg_txt">' . __("Job has been applied", "js-jobs") . '</span><button class="applynow-closebutton" onclick="jsjobsClosePopup(1);" ><img src="'.JSJOBS_PLUGIN_URL.'/includes/images/popupcloseicon.png"/>'.__('Close','js-jobs').'</button></div></div>';
        }
        return $msg;
    }

    function jobapply($themecall=null) {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $jobid = JSJOBSrequest::getVar('jobid');
        $cvid = JSJOBSrequest::getVar('cvid');
        $coverletterid = JSJOBSrequest::getVar('coverletterid');
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $action_status = 1;

        if (!is_numeric($cvid)) {
            if(null !=$themecall) return 1;
            $msg = '<div id="notification-not-ok"><label id="popup_message"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/unpublish.png"/>' . __("please select a resume first", "js-jobs") . '</label><button class="applynow-closebutton" onclick="closePopup();" ><img src="'.JSJOBS_PLUGIN_URL.'/includes/images/popupcloseicon.png"/>'.__('Close','js-jobs').'</button></div>';
            return $msg;
        }

        $action_status = 2;

        $data = array();
        $data['jobid'] = $jobid;
        $data['cvid'] = $cvid;
        $data['coverletterid'] = $coverletterid;
        $data['uid'] = $uid;
        $data['action_status'] = $action_status;
        $data['apply_date'] = date('Y-m-d H:i:s');
        $row = JSJOBSincluder::getJSTable('jobapply');
        $result = array();
        $alreadycheck = $this->checkAlreadyAppliedJob($data['jobid'], $data['uid']);
        if ($alreadycheck == false) {
            if(null !=$themecall) return 2;
            $msg = '<div id="notification-ok"><label id="popup_message"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/approve.png"/>' . __("You have already applied this job", "js-jobs") . '</label><button class="applynow-closebutton" onclick="closePopup();" ><img src="'.JSJOBS_PLUGIN_URL.'/includes/images/popupcloseicon.png"/>'.__('Close','js-jobs').'</button></div>';
            return $msg;
        }
        $return = JSJOBS_SAVED;
        if (!$row->bind($data)) {
            $return = JSJOBS_SAVE_ERROR;
        }
        if (!$row->store()) {
            $return = JSJOBS_SAVE_ERROR;
        }
        if ($return != JSJOBS_SAVE_ERROR) {

            $this->sendMail($jobid,$cvid);

            $msg = '<div id="notification-ok"><label id="popup_message"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/approve.png"/>' . __("Job has been applied", "js-jobs") . '</label><button class="applynow-closebutton" onclick="closePopup();" ><img src="'.JSJOBS_PLUGIN_URL.'/includes/images/popupcloseicon.png"/>'.__('Close','js-jobs').'</button></div>';
            $uid = JSJOBSincluder::getJSModel('common')->getUidByObjectId('job', $row->jobid);
            //notification for employer
        } else {
            $msg = '<div id="notification-not-ok"><label id="popup_message"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/unpublish.png"/>' . __("Failed while performing this action", "js-jobs") . '</label><button class="applynow-closebutton" onclick="closePopup();" ><img src="'.JSJOBS_PLUGIN_URL.'/includes/images/popupcloseicon.png"/>'.__('Close','js-jobs').'</button></div>';
        }
        if(null !=$themecall) return $return;
        return $msg;
    }

    private function sendMail($jobid, $resumeid) {
        //this code is not moved into email template model bcz of its high complextiy and low usage

        if ($jobid)
            if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
                return false;
        if ($resumeid)
            if ((is_numeric($resumeid) == false) || ($resumeid == 0) || ($resumeid == ''))
                return false;

        $jobquery = "SELECT company.name AS companyname,company.contactname AS name, company.contactemail AS email, job.title, job.sendemail
            FROM `".jsjobs::$_db->prefix."js_job_companies` AS company
            JOIN `".jsjobs::$_db->prefix."js_job_jobs` AS job ON job.companyid = company.id
            WHERE job.id = " . $jobid;

        $jobuser = jsjobsdb::get_row($jobquery);

        $userquery = "SELECT CONCAT(first_name,' ',last_name) AS name, email_address AS email,application_title FROM `".jsjobs::$_db->prefix."js_job_resume`
            WHERE id = " . $resumeid;
        $user = jsjobsdb::get_row($userquery);

        $userquery = "SELECT cvl.title, cvl.description,ja.action_status
            FROM `".jsjobs::$_db->prefix."js_job_jobapply` AS ja
            LEFT JOIN `".jsjobs::$_db->prefix."js_job_coverletters` AS cvl ON cvl.id = ja.coverletterid
            WHERE ja.jobid = ".$jobid;
        $coverletter = jsjobsdb::get_row($userquery);

        $emailconfig = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('email');

//MAIL TO ADMIN ON JOBAPPLY
        $templatefor = 'jobapply-jobapply';
        $query = "SELECT template.* FROM `".jsjobs::$_db->prefix."js_job_emailtemplates` AS template WHERE template.templatefor = '" . $templatefor . "'";

        $template = jsjobsdb::get_row($query);
        $msgSubject = $template->subject;
        $msgBody = $template->body;

        $ApplicantName = $user->name;
        $EmployerEmail = $emailconfig['adminemailaddress'];
        $EmployerName = $jobuser->name;
        $JobTitle = $jobuser->title;
        $msgSubject = jsjobslib::jsjobs_str_replace('{JOBSEEKER_NAME}', $ApplicantName, $msgSubject);
        $msgSubject = jsjobslib::jsjobs_str_replace('{EMPLOYER_NAME}', $EmployerName, $msgSubject);
        $msgSubject = jsjobslib::jsjobs_str_replace('{JOB_TITLE}', $JobTitle, $msgSubject);
        $msgBody = jsjobslib::jsjobs_str_replace('{JOBSEEKER_NAME}', $ApplicantName, $msgBody);
        $msgBody = jsjobslib::jsjobs_str_replace('{EMPLOYER_NAME}', $EmployerName, $msgBody);
        $msgBody = jsjobslib::jsjobs_str_replace('{JOB_TITLE}', $JobTitle, $msgBody);
        $emailstatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('jobapply_jobapply');
        $coverletterdescription = '';
        $coverlettertitle = '';
        if(!empty($coverletter)){
            $coverletterdescription = $coverletter->description;
            $coverlettertitle = $coverletter->title;
        }
        $msgBody = jsjobslib::jsjobs_str_replace('{COVER_LETTER_TITLE}',$coverlettertitle, $msgBody);
        $msgBody = jsjobslib::jsjobs_str_replace('{COVER_LETTER_DESCRIPTION}', $coverletterdescription, $msgBody);
        $senderName = $emailconfig['mailfromname'];
        $senderEmail = $emailconfig['mailfromaddress'];
        $resume_data = $this->prepareResumeDataForEmployer($resumeid);
        if (jsjobslib::jsjobs_strstr($msgBody, '{RESUME_DATA}')) {
            $msgBody = jsjobslib::jsjobs_str_replace('{RESUME_DATA}', $resume_data, $msgBody);
        }
            $parsed_url_admin = admin_url('admin.php?page=jsjobs_resume&jsjobslt=viewresume&jsjobsid='.$resumeid);
            $applied_resume_link_admin = '<br><a href="' . $parsed_url_admin . '" target="_blank" >' . __('Resume','js-jobs') . '</a>';
            $msgBody = jsjobslib::jsjobs_str_replace('{RESUME_LINK}', $applied_resume_link_admin , $msgBody);
            $recevierEmail = $EmployerEmail;
            $subject = $msgSubject;
            $body = $msgBody;
        if ($emailstatus->admin == 1) {
            $datadirectory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
            $resumeFiles = JSJOBSincluder::getJSModel('resume')->getResumeFilesByResumeId($resumeid);
            $attachments = '';
            if (!empty($resumeFiles)) {
                $attachments = array();
                foreach ($resumeFiles as $resumeFile) {
                    $iddir = 'resume_' . $resumeid;
                    $wpdir = wp_upload_dir();
                    $path = $wpdir['baseurl'] . '/' . $datadirectory;
                    $path = $path . '/data/jobseeker/' . $iddir . '/resume/' . $resumeFile->filename;
                    $attachments[] = $path;
                }
            }
            JSJOBSincluder::getJSModel('common')->sendEmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments);
        }
    //MAIL TO EMPLOYER
        $templatefor = 'jobapply-employer';
        $query = "SELECT template.* FROM `".jsjobs::$_db->prefix."js_job_emailtemplates` AS template WHERE template.templatefor = '" . $templatefor . "'";

        $template = jsjobsdb::get_row($query);
        $msgSubject = $template->subject;
        $msgBody = $template->body;

        $ApplicantName = $user->name;
        $EmployerEmail = $jobuser->email;
        $EmployerName = $jobuser->name;
        $JobTitle = $jobuser->title;
        $msgSubject = jsjobslib::jsjobs_str_replace('{JOBSEEKER_NAME}', $ApplicantName, $msgSubject);
        $msgSubject = jsjobslib::jsjobs_str_replace('{JOB_TITLE}', $JobTitle, $msgSubject);
        $msgBody = jsjobslib::jsjobs_str_replace('{JOBSEEKER_NAME}', $ApplicantName, $msgBody);
        $msgBody = jsjobslib::jsjobs_str_replace('{JOB_TITLE}', $JobTitle, $msgBody);
        $msgBody = jsjobslib::jsjobs_str_replace('{EMPLOYER_NAME}', $EmployerName, $msgBody);
        $coverletterdescription = '';
        $coverlettertitle = '';
        if(!empty($coverletter)){
            $coverletterdescription = $coverletter->description;
            $coverlettertitle = $coverletter->title;
        }
        $applied_resume_status = '';
        if(isset($coverletter->action_status)){
            switch ($coverletter->action_status) {
                case 1:
                    $applied_resume_status = __('Inbox','js-jobs');
                break;
                case 2:
                    $applied_resume_status = __('Spam','js-jobs');
                break;
                case 3:
                    $applied_resume_status = __('Hired','js-jobs');
                break;
                case 4:
                    $applied_resume_status = __('Rejected','js-jobs');
                break;
                case 5:
                    $applied_resume_status = __('Short listed','js-jobs');
                break;
            }
        }

        $msgBody = jsjobslib::jsjobs_str_replace('{COVER_LETTER_TITLE}',$coverlettertitle, $msgBody);
        $msgBody = jsjobslib::jsjobs_str_replace('{COVER_LETTER_DESCRIPTION}', $coverletterdescription, $msgBody);
        $emailconfig = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('email');
        $senderName = $emailconfig['mailfromname'];
        $senderEmail = $emailconfig['mailfromaddress'];
            $resume_data = $this->prepareResumeDataForEmployer($resumeid);
            if (jsjobslib::jsjobs_strstr($msgBody, '{RESUME_DATA}')) {
                $msgBody = jsjobslib::jsjobs_str_replace('{RESUME_DATA}', $resume_data, $msgBody);
            }
            $parsed_url = jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume','jsjobsid'=>$resumeid,'jsjobspageid'=>jsjobs::getPageid()));

            $applied_resume_link = '<br><a href="' . $parsed_url . '" target="_blank" >' . __('Resume','js-jobs') . '</a>';
            $msgBody = jsjobslib::jsjobs_str_replace('{RESUME_LINK}', $applied_resume_link, $msgBody);
            $msgBody = jsjobslib::jsjobs_str_replace('{RESUME_APPLIED_STATUS}', $applied_resume_status, $msgBody);
            $recevierEmail = $EmployerEmail;
            $subject = $msgSubject;
            $body = $msgBody;
        if ($jobuser->sendemail == 1 && $emailstatus->employer == 1) {
            $attachments = '';
            JSJOBSincluder::getJSModel('common')->sendEmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments);
        }elseif ($jobuser->sendemail == 2 && $emailstatus->employer == 1) {
            $datadirectory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
            $resumeFiles = JSJOBSincluder::getJSModel('resume')->getResumeFilesByResumeId($resumeid);
            if (!empty($resumeFiles)) {
                $attachments = array();
                foreach ($resumeFiles as $resumeFile) {
                    $iddir = 'resume_' . $resumeid;
                    $wpdir = wp_upload_dir();
                    $path = $wpdir['baseurl'] . '/' . $datadirectory;
                    $path = $path . '/data/jobseeker/' . $iddir . '/resume/' . $resumeFile->filename;
                    $attachments[] = $path;
                }
            }
            JSJOBSincluder::getJSModel('common')->sendEmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments);
        }

    // MAIL TO JOB SEEKER
        $templatefor = 'jobapply-jobseeker';
        $query = "SELECT template.* FROM `".jsjobs::$_db->prefix."js_job_emailtemplates` AS template WHERE template.templatefor = '" . $templatefor . "'";
        $template = jsjobsdb::get_row($query);
        $msgSubject = $template->subject;
        $msgBody = $template->body;
        $msgSubject = jsjobslib::jsjobs_str_replace('{JOB_TITLE}', $JobTitle, $msgSubject);
        $msgBody = jsjobslib::jsjobs_str_replace('{JOBSEEKER_NAME}', $ApplicantName, $msgBody);
        $msgBody = jsjobslib::jsjobs_str_replace('{JOB_TITLE}', $JobTitle, $msgBody);
        $msgBody = jsjobslib::jsjobs_str_replace('{RESUME_APPLIED_STATUS}', $applied_resume_status, $msgBody);
        $msgBody = jsjobslib::jsjobs_str_replace('{RESUME_TITLE}', $user->application_title, $msgBody);
        $msgBody = jsjobslib::jsjobs_str_replace('{COMPANY_NAME}', $jobuser->companyname, $msgBody);
        $subject = $msgSubject;
        $body = $msgBody;
        $recevierEmail = $user->email;
        $attachments ='';
        if($emailstatus->jobseeker == 1){
            JSJOBSincluder::getJSModel('common')->sendEmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments);
        }
        return true;
    }

    function checkAlreadyAppliedJob($jobid, $uid) {
        if (!is_numeric($jobid))
            return false;
        if (!is_numeric($uid))
            return false;
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` WHERE jobid = " . $jobid . " AND uid = " . $uid;
        $result = jsjobs::$_db->get_var($query);
        if ($result == 0) {
            return true;
        } else {
            return false;
        }
    }
    function getEmailFieldsJobManager(){
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $email = JSJOBSrequest::getVar('em');
        $resumeid = JSJOBSrequest::getVar('resumeid');
        $html = '<div class="'.$this->class_prefix.'-sendemail-form">
                    <form class="">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">'. __('Job seeker', 'job-manager'). ':</label>
                                <input type="text" id="jobseeker" class="form-control" value="' . $email . '" disabled >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputName2">'. __('Subject', 'job-manager'). ':</label>
                                <input type="text" id="subject" class="form-control" placeholder="' . __('Subject', 'job-manager') . '">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputName2">'. __('Sender Email', 'job-manager'). ':</label>
                                <input type="email" id="sender"  class="form-control " placeholder="'. __('Sender Email', 'job-manager'). '">
                            </div>
                        </div>
                        <div class="col-md-4 '.$this->class_prefix.'-ar-se">
                            <div class="form-group">
                                <textarea id="email-body" placeholder="' . __('Type here', 'job-manager') . '" class="form-control note-txt" rows="8"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4 '.$this->class_prefix.'-sendemail-btn-wrp">
                            <div class="form-group '.$this->class_prefix.'-sendemail-btn-data">
                                <input type="button" class="form-control '.$this->class_prefix.'-sendemail-btn" value="' . __('Send', 'job-manager') . '" onclick="sendEmail('.$resumeid.')">
                                <input type="button" class="form-control '.$this->class_prefix.'-sendemail-btn" onclick="closeSection()" value="' . __('Cancel', 'job-manager') . '">
                            </div>
                        </div>
                    </form>
                </div>';
        return $html;
    }

    function getEmailFields() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $email = JSJOBSrequest::getVar('em');
        $resumeid = JSJOBSrequest::getVar('resumeid');
        $html = '';
        $html.='<img id="close-section" onclick="closeSection()" src="' . JSJOBS_PLUGIN_URL . 'includes/images/no.png"/>';
        $html.='<div class="email-feilds"><div>';
        $html.='<label for="jobseeker">'
                . __('Job seeker', 'js-jobs')
                . ' : </label>';
        $html.='<input type="text" id="jobseeker" value="' . $email . '" disabled /></div><div><label for="subject">'
                . __('Subject', 'js-jobs') .
                ' : </label>';
        $html.='<input type="text" id="e-subject" />';
        $html.='</div><div>';
        $html.='<label for="sender">' . __('Sender Email', 'js-jobs') . '  : </label>';
        $html.='<input type="text" id="sender"  /></div>';
        $html.='</div><textarea id="email-body" placeholder=' . __('Type here', 'js-jobs') . '>';
        $html.='</textarea> <input type="button" id="send" value=' . __('Send', 'js-jobs') . ' onclick="sendEmail('.$resumeid.')" />';
        return $html;
    }

    function getOrdering() {
        $sort = JSJOBSrequest::getVar('sortby', '', 'postedasc');
        $this->getListOrdering($sort);
        $this->getListSorting($sort);
    }

    function getMyAppliedJobs($uid) {
        if (!is_numeric($uid)) return false;

        $this->getOrdering();
        $query = "SELECT COUNT(jobapply.id)
                 FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` AS jobapply
                 JOIN `" . jsjobs::$_db->prefix . "js_job_jobs` AS job ON job.id = jobapply.jobid
                 JOIN `" . jsjobs::$_db->prefix . "js_job_resume` AS resume ON resume.id = jobapply.cvid
                 JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = job.companyid
                 JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS category ON category.id = job.jobcategory
                 JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                 WHERE jobapply.uid = " . $uid;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total, 'myappliedjobs');

        $query = "SELECT job.id AS jobid,job.city,job.title,job.noofjobs,CONCAT(job.alias,'-',job.id) AS jobaliasid ,CONCAT(company.alias,'-',companyid) AS companyaliasid, job.serverid,
                 jobapply.action_status AS resumestatus,jobapply.apply_date,cur.symbol,
                 company.id AS companyid, company.name AS companyname,company.logofilename,category.cat_title,salaryrangefrom.rangestart,salaryrangeto.rangeend,
                 jobtype.title AS jobtypetitle, srtype.title AS srttitle, jobstatus.title AS jobstatustitle,resume.id AS resumeid
                ,resume.application_title,coverletter.title AS coverlettertitle,coverletter.id AS coverletterid,job.params,job.created,LOWER(jobtype.title) AS jobtype
                ,jobapply.id AS id,resume.first_name,resume.middle_name,resume.last_name
                 FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` AS jobapply
                 JOIN `" . jsjobs::$_db->prefix . "js_job_jobs` AS job ON job.id = jobapply.jobid
                 JOIN `" . jsjobs::$_db->prefix . "js_job_resume` AS resume ON resume.id = jobapply.cvid
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_coverletters` AS coverletter ON coverletter.id = jobapply.coverletterid
                 JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = job.companyid
                 JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS category ON category.id = job.jobcategory
                 JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangefrom ON salaryrangefrom.id = job.salaryrangefrom
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangeto ON salaryrangeto.id = job.salaryrangeto
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS srtype ON srtype.id = job.salaryrangetype
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobstatus` AS jobstatus ON jobstatus.id = job.jobstatus
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS cur ON cur.id = job.currencyid
                 WHERE jobapply.uid = " . $uid;
        $query.= " ORDER BY " . jsjobs::$_ordering;
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        $results = jsjobsdb::get_results($query);
        $data = array();
        foreach ($results AS $d) {
            $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->city);
            $data[] = $d;
        }
        $results = $data;
        $data = array();
        foreach ($results AS $d) {
            $d->salary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView($d->symbol, $d->rangestart, $d->rangeend, $d->srttitle);
            $data[] = $d;
        }
        jsjobs::$_data[0] = $data;
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(2);
        return;
    }

    function getListOrdering($sort) {
        switch ($sort) {
            case "titledesc":
                jsjobs::$_ordering = "job.title DESC";
                jsjobs::$_sorton = "title";
                jsjobs::$_sortorder = "DESC";
                break;
            case "titleasc":
                jsjobs::$_ordering = "job.title ASC";
                jsjobs::$_sorton = "title";
                jsjobs::$_sortorder = "ASC";
                break;
            case "jobtypedesc":
                jsjobs::$_ordering = "jobtype.title DESC";
                jsjobs::$_sorton = "jobtype";
                jsjobs::$_sortorder = "DESC";
                break;
            case "jobtypeasc":
                jsjobs::$_ordering = "jobtype.title ASC";
                jsjobs::$_sorton = "jobtype";
                jsjobs::$_sortorder = "ASC";
                break;
            case "jobstatusdesc":
                jsjobs::$_ordering = "jobstatus.title DESC";
                jsjobs::$_sorton = "jobstatus";
                jsjobs::$_sortorder = "DESC";
                break;
            case "jobstatusasc":
                jsjobs::$_ordering = "jobstatus.title ASC";
                jsjobs::$_sorton = "jobstatus";
                jsjobs::$_sortorder = "ASC";
                break;
            case "companydesc":
                jsjobs::$_ordering = "company.name DESC";
                jsjobs::$_sorton = "company";
                jsjobs::$_sortorder = "DESC";
                break;
            case "companyasc":
                jsjobs::$_ordering = "company.name ASC";
                jsjobs::$_sorton = "company";
                jsjobs::$_sortorder = "ASC";
                break;
            case "salarydesc":
                jsjobs::$_ordering = "salaryrangefrom.rangestart DESC";
                jsjobs::$_sorton = "salary";
                jsjobs::$_sortorder = "DESC";
                break;
            case "salaryasc":
                jsjobs::$_ordering = "salaryrangefrom.rangestart ASC";
                jsjobs::$_sorton = "salary";
                jsjobs::$_sortorder = "ASC";
                break;
            case "posteddesc":
                jsjobs::$_ordering = "jobapply.apply_date DESC";
                jsjobs::$_sorton = "posted";
                jsjobs::$_sortorder = "DESC";
                break;
            case "postedasc":
                jsjobs::$_ordering = "jobapply.apply_date ASC";
                jsjobs::$_sorton = "posted";
                jsjobs::$_sortorder = "ASC";
                break;
            default: jsjobs::$_ordering = "job.title DESC";
        }
        return;
    }

    function getSortArg($type, $sort) {
        $mat = array();
        if (jsjobslib::jsjobs_preg_match("/(\w+)(asc|desc)/i", $sort, $mat)) {
            if ($type == $mat[1]) {
                return ( $mat[2] == "asc" ) ? "{$type}desc" : "{$type}asc";
            } else {
                return $type . $mat[2];
            }
        }
        return "iddesc";
    }

    function getListSorting($sort) {
        jsjobs::$_sortlinks['title'] = $this->getSortArg("title", $sort);
        jsjobs::$_sortlinks['category'] = $this->getSortArg("category", $sort);
        jsjobs::$_sortlinks['jobtype'] = $this->getSortArg("jobtype", $sort);
        jsjobs::$_sortlinks['jobstatus'] = $this->getSortArg("jobstatus", $sort);
        jsjobs::$_sortlinks['company'] = $this->getSortArg("company", $sort);
        jsjobs::$_sortlinks['salary'] = $this->getSortArg("salary", $sort);
        jsjobs::$_sortlinks['posted'] = $this->getSortArg("posted", $sort);
        return;
    }

    function jobapplyVisitor($resumeid, $jobid) {
        if (!is_numeric($resumeid))
            return false;
        if (!is_numeric($jobid))
            return false;
        $data['jobid'] = $jobid;
        $data['cvid'] = $resumeid;
        $data['uid'] = 0;
        $data['apply_date'] = date('Y-m-d H:i:s');
        $data['action_status'] = 1;
        $row = JSJOBSincluder::getJSTable('jobapply');
        if (!$row->bind($data)) {
            return false;
        }
        if (!$row->store()) {
            return false;
        }

        if (isset($_SESSION['jsjobs_apply_visitor'])) {
            unset($_SESSION['jsjobs_apply_visitor']);
        }
        if (isset($_SESSION['wp-jsjobs']['resumeid'])) {
            unset($_SESSION['wp-jsjobs']['resumeid']);
            unset($_SESSION['wp-jsjobs']);
        }
        $this->sendMail($jobid, $resumeid);
        return true;
    }

    function setJobApplyRating() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $jobapplyid = JSJOBSrequest::getVar('jobapplyid');
        if (!is_numeric($jobapplyid))
            return false;
        $newrating = JSJOBSrequest::getVar('newrating');

        $row = JSJOBSincluder::getJSTable('jobapply');
        if ($row->update(array('id' => $jobapplyid , 'rating' => $newrating))){
            return true;
        } else {
            return false;
        }
    }

    function prepareResumeDataForEmployer($resumeid) {

        $send_only_filled_fields = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('employer_resume_alert_fields');
        $show_only_section_that_have_value = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('show_only_section_that_have_value');

        JSJOBSincluder::getJSModel('resume')->getResumebyId($resumeid);
        $personalInfo = jsjobs::$_data[0]['personal_section'];
        $addresses = jsjobs::$_data[0]['address_section'];
        $institutes = jsjobs::$_data[0]['institute_section'];
        $employers = jsjobs::$_data[0]['employer_section'];
        $references = jsjobs::$_data[0]['reference_section'];
        $languages = jsjobs::$_data[0]['language_section'];

        $show_contact_detail =  jsjobs::$_data['resumecontactdetail'];

        $userfields = ''; // Ask form Shees
        $fieldsordering = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3); // resume fields
        jsjobs::$_data[2] = array();
        foreach ($fieldsordering AS $field) {
            jsjobs::$_data['fieldtitles'][$field->field] = $field->fieldtitle;
            jsjobs::$_data[2][$field->section][$field->field] = $field->published;
        }
        $fieldsordering = jsjobs::$_data[2];
        $msgBody = "<table cellpadding='5' style='border-color: #666;' cellspacing='0' border='0' width='100%'>";

        $temp_body = '';
        $flag = 0;
        if(isset($fieldsordering[1]))
        foreach ($fieldsordering[1] as $field => $required) {
            switch ($field) {
                case "section_personal":
                    $temp_body .= "<tr style='background: #eee;'>";
                    $temp_body .= "<td colspan='2' align='center'><strong>" . __('Personal Information','js-jobs') . "</strong></td></tr>";
                    break;
                case "application_title":
                    $this->getRowForResume(__('Application title','js-jobs'), $personalInfo->application_title, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                case "first_name":
                    $this->getRowForResume(__('First name','js-jobs'), $personalInfo->first_name, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                case "middle_name":
                    $this->getRowForResume(__('Middle name','js-jobs'), $personalInfo->middle_name, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                case "last_name":
                    $this->getRowForResume(__('Last name','js-jobs'), $personalInfo->last_name, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                case "email_address":
                    if($show_contact_detail){
                        $this->getRowForResume(__('Email address','js-jobs'), $personalInfo->email_address, $temp_body, $required,$send_only_filled_fields , $flag);
                    }
                    break;
                case "home_phone":
                    if($show_contact_detail){
                        $this->getRowForResume(__('Home phone','js-jobs'), $personalInfo->home_phone, $temp_body, $required,$send_only_filled_fields , $flag);
                    }
                    break;
                case "work_phone":
                    if($show_contact_detail){
                        $this->getRowForResume(__('Work phone','js-jobs'), $personalInfo->work_phone, $temp_body, $required,$send_only_filled_fields , $flag);
                    }
                    break;
                case "cell":
                    if($show_contact_detail){
                        $this->getRowForResume(__('Cell','js-jobs'), $personalInfo->cell, $temp_body, $required,$send_only_filled_fields , $flag);
                    }
                    break;
                case "gender":
                    $genderText = ($personalInfo->gender == 1) ? __('Male','js-jobs') : __('Female','js-jobs');
                    $this->getRowForResume(__('Gender','js-jobs'), $genderText, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                case "iamavailable":
                    $availableText = ($personalInfo->iamavailable == 1) ? __('Yes','js-jobs') : __('No');
                    $this->getRowForResume(__('I am available','js-jobs'), $availableText, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                case "nationality":
                    $this->getRowForResume(__('Country','js-jobs'), $personalInfo->nationality, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                case "section_moreoptions":
                    if ($required == 1) {
                        $temp_body .= "<tr style='background: #eee;'>";
                        $temp_body .= "<td colspan='2' align='center'><strong>" . __('Basic Information','js-jobs') . "</strong></td></tr>";
                    }
                    break;
                case "category":
                    $this->getRowForResume(__('Category','js-jobs'), $personalInfo->categorytitle, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                case "salary":
                    $currencyalign = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('currency_align');
                    $salary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView($personalInfo->symbol,$personalInfo->rangestart,$personalInfo->rangeend,$personalInfo->rangetype,$currencyalign);
                    $this->getRowForResume(__('Salary','js-jobs'), $salary, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                case "jobtype":
                    $this->getRowForResume(__('Work preference','js-jobs'), $personalInfo->jobtypetitle, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                case "heighestfinisheducation":
                    $this->getRowForResume(__('Highest education','js-jobs'), $personalInfo->highestfinisheducation, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                case "total_experience":
                    $this->getRowForResume(__('Total experience','js-jobs'), $personalInfo->total_experience, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                case "start_date":
                    $this->getRowForResume(__('Date you can start','js-jobs'), $personalInfo->date_start, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                case "date_of_birth":
                    $this->getRowForResume(__('Date of birth','js-jobs'), $personalInfo->date_of_birth, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                default:
                    $data = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11,$personalInfo->params);
                    if($send_only_filled_fields == 1){
                        if(! empty($data['value'])){
                            $temp_body .= "<tr style='background: #eee;'>";
                            $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                            $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                        }
                    }else{
                        if(is_array($data)){
                            $temp_body .= "<tr style='background: #eee;'>";
                            $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                            $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                        }
                    }
                break;
            }
        }
        if($show_only_section_that_have_value == 1){
            if($flag > 0){
                $msgBody .= $temp_body;
            }
        }else{
            $msgBody .= $temp_body;
        }

        $flag = 0;
        $temp_body = '';

        $i = 0;
        $temp_body .= "<tr style='background: #eee;'>";
        $temp_body .= "<td colspan='2' align='center'><strong>" . __('Address','js-jobs') . "</strong></td></tr>";
        if(isset($addresses) && is_array($addresses))
        foreach ($addresses as $address) {
            $i++;
            foreach ($fieldsordering[2] as $field => $required) {
                switch ($field) {
                    case "section_address":
                        if ($required == 1) {
                            $temp_body .= "<tr style='background: #eee;'>";
                            $temp_body .= "<td colspan='2' align='center'><strong>" . __('Address','js-jobs') . "-" . $i . "</strong></td></tr>";
                        }
                        break;
                    case "address_city":
                        $this->getRowForResume(__('City'), $address->cityname, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "address_zipcode":
                        $this->getRowForResume(__('Zip Code','js-jobs'), $address->address_zipcode, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "address":
                        $this->getRowForResume(__('Address','js-jobs'), $address->address, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    default:
                        $data = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11,$address->params);
                        if($send_only_filled_fields == 1){
                            if(! empty($data['value'])){
                                $temp_body .= "<tr style='background: #eee;'>";
                                $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                                $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                            }
                        }else{
                            if(is_array($data)){
                                $temp_body .= "<tr style='background: #eee;'>";
                                $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                                $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                            }
                        }
                        break;
                }
            }
        }

        if($show_contact_detail){
            if($show_only_section_that_have_value == 1){
                if($flag > 0){
                    $msgBody .= $temp_body;
                }
            }else{
                $msgBody .= $temp_body;
            }
        }

        $flag = 0;
        $temp_body = '';

        $i = 0;
        $temp_body .= "<tr style='background: #eee;'>";
        $temp_body .= "<td colspan='2' align='center'><strong>" . __('Institutes','js-jobs') . "</strong></td></tr>";
        if(isset($institutes) && is_array($institutes))
        foreach ($institutes as $institute) {
            $i++;
            foreach ($fieldsordering[3] as $field => $required) {
                switch ($field) {
                    case "section_education":
                        if ($required == 1) {
                            $temp_body .= "<tr style='background: #eee;'>";
                            $temp_body .= "<td colspan='2' align='center'><strong>" . __('Institute','js-jobs') . "-" . $i . "</strong></td></tr>";
                        }
                        break;
                    case "institute":
                        $this->getRowForResume(__('Institution Name','js-jobs'), $institute->institute, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "institute_city":
                        $this->getRowForResume(__('City'), $institute->cityname, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "institute_address":
                        $this->getRowForResume(__('Address','js-jobs'), $institute->institute_address, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "institute_certificate":
                        $this->getRowForResume(__('Cert/deg/oth'), $institute->institute_certificate, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "institute_study_area":
                        $this->getRowForResume(__('Area Of Study','js-jobs'), $institute->institute_study_area, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    default:
                        $data = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11,$institute->params);
                        if($send_only_filled_fields == 1){
                            if(! empty($data['value'])){
                                $temp_body .= "<tr style='background: #eee;'>";
                                $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                                $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                            }
                        }else{
                            if(is_array($data)){
                                $temp_body .= "<tr style='background: #eee;'>";
                                $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                                $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                            }
                        }
                        break;
                }
            }
        }

        if($show_only_section_that_have_value == 1){
            if($flag > 0){
                $msgBody .= $temp_body;
            }
        }else{
            $msgBody .= $temp_body;
        }

        $flag = 0;
        $temp_body = '';

        $i = 0;
        $temp_body .= "<tr style='background: #eee;'>";
        $temp_body .= "<td colspan='2' align='center'><strong>" . __('Employers','js-jobs') . "</strong></td></tr>";
        if(isset($employers) && is_array($employers))
        foreach ($employers as $employer) {
            $i++;
            foreach ($fieldsordering[4] as $field => $required) {
                switch ($field) {
                    case "section_employer":
                        if ($required == 1) {
                            $temp_body .= "<tr style='background: #eee;'>";
                            $temp_body .= "<td colspan='2' align='center'><strong>" . __('Employer','js-jobs') . "-" . $i . "</strong></td></tr>";
                        }
                        break;
                    case "employer":
                        $this->getRowForResume(__('Employer','js-jobs'), $employer->employer, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "employer_position":
                        $this->getRowForResume(__('Position','js-jobs'), $employer->employer_position, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "employer_resp":
                        $this->getRowForResume(__('Responsibilities','js-jobs'), $employer->employer_resp, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "employer_pay_upon_leaving":
                        $this->getRowForResume(__('Pay Upon Leaving','js-jobs'), $employer->employer_pay_upon_leaving, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "employer_supervisor":
                        $this->getRowForResume(__('Supervisor','js-jobs'), $employer->employer_supervisor, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "employer_from_date":
                        $this->getRowForResume(__('From Date','js-jobs'), $employer->employer_from_date, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "employer_to_date":
                        $this->getRowForResume(__('To Date','js-jobs'), $employer->employer_to_date, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "employer_leave_reason":
                        $this->getRowForResume(__('Reason For Leaving','js-jobs'), $employer->employer_leave_reason, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "employer_city":
                        $this->getRowForResume(__('City'), $employer->cityname, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "employer_zip":
                        $this->getRowForResume(__('Zip Code','js-jobs'), $employer->employer_zip, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "employer_address":
                        $this->getRowForResume(__('Address','js-jobs'), $employer->employer_address, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "employer_phone":
                        $this->getRowForResume(__('Phone','js-jobs'), $employer->employer_phone, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    default:
                        $data = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11,$employer->params);
                        if($send_only_filled_fields == 1){
                            if(! empty($data['value'])){
                                $temp_body .= "<tr style='background: #eee;'>";
                                $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                                $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                            }
                        }else{
                            if(is_array($data)){
                                $temp_body .= "<tr style='background: #eee;'>";
                                $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                                $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                            }
                        }
                        break;
                }
            }
        }

        if($show_only_section_that_have_value == 1){
            if($flag > 0){
                $msgBody .= $temp_body;
            }
        }else{
            $msgBody .= $temp_body;
        }

        $flag = 0;
        $temp_body = '';

        if(isset($fieldsordering['skills']))
        foreach ($fieldsordering[5] as $field => $required) {
            switch ($field) {
                case "section_skills":
                    if ($required == 1) {
                        $temp_body .= "<tr style='background: #eee;'>";
                        $temp_body .= "<td colspan='2' align='center'><strong>" . __('Skills','js-jobs') . "</strong></td></tr>";
                    }
                    break;
                case "skills":
                    $this->getRowForResume(__('Skills','js-jobs'), $personalInfo->skills, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                default:
                    $data = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11,$personalInfo->params);
                    if($send_only_filled_fields == 1){
                        if(! empty($data['value'])){
                            $temp_body .= "<tr style='background: #eee;'>";
                            $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                            $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                        }
                    }else{
                        if(is_array($data)){
                            $temp_body .= "<tr style='background: #eee;'>";
                            $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                            $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                        }
                    }
                    break;
            }
        }

        if($show_only_section_that_have_value == 1){
            if($flag > 0){
                $msgBody .= $temp_body;
            }
        }else{
            $msgBody .= $temp_body;
        }

        $flag = 0;
        $temp_body = '';


        if(isset($fieldsordering['resume']))
        foreach ($fieldsordering['resume'] as $field) {
            switch ($field) {
                case "section_resume":
                    if ($required == 1) {
                        $temp_body .= "<tr style='background: #eee;'>";
                        $temp_body .= "<td colspan='2' align='center'><strong>" . __('Resume','js-jobs') . "</strong></td></tr>";
                    }
                    break;
                case "resume":
                    $this->getRowForResume(__('Resume','js-jobs'), $personalInfo->resume, $temp_body, $required,$send_only_filled_fields , $flag);
                    break;
                default:
                    $data = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11,$personalInfo->params);
                    if($send_only_filled_fields == 1){
                        if(! empty($data['value'])){
                            $temp_body .= "<tr style='background: #eee;'>";
                            $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                            $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                        }
                    }else{
                        if(is_array($data)){
                            $temp_body .= "<tr style='background: #eee;'>";
                            $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                            $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                        }
                    }
                    break;
            }
        }

        if($show_only_section_that_have_value == 1){
            if($flag > 0){
                $msgBody .= $temp_body;
            }
        }else{
            $msgBody .= $temp_body;
        }

        $flag = 0;
        $temp_body = '';


        $i = 0;
        $temp_body .= "<tr style='background: #eee;'>";
        $temp_body .= "<td colspan='2' align='center'><strong>" . __('References','js-jobs') . "</strong></td></tr>";
        if(isset($references) && is_array($references))
        foreach ($references as $reference) {
            $i++;
            foreach ($fieldsordering[7] as $field => $required) {
                switch ($field) {
                    case "section_reference":
                        if ($required == 1) {
                            $temp_body .= "<tr style='background: #eee;'>";
                            $temp_body .= "<td colspan='2' align='center'><strong>" . __('Reference','js-jobs') . "-" . $i . "</strong></td></tr>";
                        }
                        break;
                    case "reference":
                        $this->getRowForResume(__('Reference','js-jobs'), $reference->reference, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "reference_name":
                        $this->getRowForResume(__('Name','js-jobs'), $reference->reference_name, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "reference_city":
                        $this->getRowForResume(__('City','js-jobs'), $reference->cityname, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "reference_zipcode":
                        $this->getRowForResume(__('Zip Code','js-jobs'), $reference->reference_zipcode, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "reference_address":
                        $this->getRowForResume(__('Address','js-jobs'), $reference->reference_address, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "reference_phone":
                        $this->getRowForResume(__('Phone','js-jobs'), $reference->reference_phone, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "reference_relation":
                        $this->getRowForResume(__('Relation','js-jobs'), $reference->reference_relation, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "reference_years":
                        $this->getRowForResume(__('Years','js-jobs'), $reference->reference_years, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    default:
                        $data = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11,$reference->params);
                        if($send_only_filled_fields == 1){
                            if(! empty($data['value'])){
                                $temp_body .= "<tr style='background: #eee;'>";
                                $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                                $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                            }
                        }else{
                            if(is_array($data)){
                                $temp_body .= "<tr style='background: #eee;'>";
                                $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                                $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                            }
                        }
                    break;
                }
            }
        }

        if($show_only_section_that_have_value == 1){
            if($flag > 0){
                $msgBody .= $temp_body;
            }
        }else{
            $msgBody .= $temp_body;
        }

        $flag = 0;
        $temp_body = '';


        $i = 0;
        $temp_body .= "<tr style='background: #eee;'>";
        $temp_body .= "<td colspan='2' align='center'><strong>" . __('Language','js-jobs') . "</strong></td></tr>";
        if(isset($languages) && is_array($languages))
        foreach ($languages as $language) {
            $i++;
            foreach ($fieldsordering[8] as $field => $required) {
                switch ($field) {
                    case "section_language":
                        if ($required == 1) {
                            $temp_body .= "<tr style='background: #eee;'>";
                            $temp_body .= "<td colspan='2' align='center'><strong>" . __('Language','js-jobs') . "-" . $i . "</strong></td></tr>";
                        }
                        break;
                    case "language_name":
                        $this->getRowForResume(__('Language Name','js-jobs'), $language->language, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "language_reading":
                        $this->getRowForResume(__('Language Read','js-jobs'), $language->language_reading, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "language_writing":
                        $this->getRowForResume(__('Language Write','js-jobs'), $language->language_writing, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "language_understading":
                        $this->getRowForResume(__('Language Understand','js-jobs'), $language->language_understanding, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    case "language_where_learned":
                        $this->getRowForResume(__('Language Learn Institute','js-jobs'), $language->language_where_learned, $temp_body, $required,$send_only_filled_fields , $flag);
                        break;
                    default:
                        $data = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 11,$language->params);
                        if($send_only_filled_fields == 1){
                            if(! empty($data['value'])){
                                $temp_body .= "<tr style='background: #eee;'>";
                                $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                                $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                            }
                        }else{
                            if(is_array($data)){
                                $temp_body .= "<tr style='background: #eee;'>";
                                $temp_body .= "<td><strong>" . $data['title'] . "</strong></td>";
                                $temp_body .= "<td>" . $data['value'] . "</td></tr>";
                            }
                        }
                        break;
                }
            }
        }

        if($show_only_section_that_have_value == 1){
            if($flag > 0){
                $msgBody .= $temp_body;
            }
        }else{
            $msgBody .= $temp_body;
        }
        $msgBody .= "</table>";

        return $msgBody;
    }

    protected function getRowForResume($title, $value, &$msgBody, $published , $send_ifnotempty , &$flag) {

        if ($published == 1) {
            if($send_ifnotempty == 1){
                if(! empty($value)){
                    $msgBody .= "<tr style='background: #eee;'>";
                    $msgBody .= "<td><strong>" . $title . "</strong></td>";
                    $msgBody .= "<td>" . $value . "</td></tr>";
                    $flag++;
                }
            }else{
                    $msgBody .= "<tr style='background: #eee;'>";
                    $msgBody .= "<td><strong>" . $title . "</strong></td>";
                    $msgBody .= "<td>" . $value . "</td></tr>";
                    $flag++;
            }

        }
    }

    protected function getUserFieldRowForResume( &$msgBody , $section) {
        $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(3);
        foreach ($customfields as $field) {
            $data = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 6,$section->params);
            $msgBody .= "<tr style='background: #eee;'>";
            $msgBody .= "<td><strong>" . $data['title'] . "</strong></td>";
            $msgBody .= "<td>" . $data['value'] . "</td></tr>";

        }
    }
    function canceljobapplyasvisitor(){
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        jsjobslib::jsjobs_setcookie('wp-jsjobs' , '' , time() - 3600 , COOKIEPATH);
        if ( SITECOOKIEPATH != COOKIEPATH ){
            jsjobslib::jsjobs_setcookie('wp-jsjobs' , '' , time() - 3600 , SITECOOKIEPATH);
        }

        jsjobslib::jsjobs_setcookie('jsjobs_apply_visitor' , '' , time() - 3600 , COOKIEPATH);
        if ( SITECOOKIEPATH != COOKIEPATH ){
            jsjobslib::jsjobs_setcookie('jsjobs_apply_visitor' , '' , time() - 3600 , SITECOOKIEPATH);
        }
        $link = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'newestjobs'));
        echo esc_attr($link);
        die();
    }

    function visitorapplyjob($callfrom=0,$resumeid = null){ // 0 for if not calling from resume model
        if($callfrom == 0){
            check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        }
        if($resumeid == null){
            $resumeid = json_decode(jsjobslib::jsjobs_safe_decoding($_COOKIE['wp-jsjobs']),true);
            $resumeid = $resumeid['resumeid'];
        }
        $jobid = sanitize_key($_COOKIE['jsjobs_apply_visitor']);
        $result = $this->jobapplyVisitor($resumeid, $jobid);
        if($result){
            $msg = JSJOBSMessages::getMessage(JSJOBS_APPLY,'job');
        }else{
            $msg = JSJOBSMessages::getMessage(JSJOBS_APPLY_ERROR,'job');
        }
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],'job');
        $link = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'newestjobs','jsjobspageid'=>jsjobs::getPageid()));
        return $link;

    }
    function getMessagekey(){
        $key = 'jobapply';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }

}
?>
