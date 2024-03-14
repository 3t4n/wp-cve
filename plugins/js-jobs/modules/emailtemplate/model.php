<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSEmailtemplateModel {

    function sendMail($mailfor, $action, $id,$mailextradata = array()) {
        if (!is_numeric($mailfor))
            return false;
        if (!is_numeric($action))
            return false;
        if ($id != null)
            if (!is_numeric($id))
                return false;
        $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('email');
        $pageid = JSJOBS::getPageid();
        switch ($mailfor) {
            case 1: // Mail For Company
                switch ($action) {
                    case 1: // Add New Company
                        $record = $this->getRecordByTablenameAndId('js_job_companies', $id,15);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = $record->companyownername;
                        $link = null;
                        $checkstatus = null;
                        if ($Username == '') {
                            $Username = $record->username;
                        }
                        $Email = $record->companyuseremail;
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $status = $record->status;
                        if ($status == 0) {
                            $checkstatus = __('Pending', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'mycompanies', 'jsjobspageid'=>jsjobs::getPageid())) ."?jsjobscf=email" . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == -1) {
                            $checkstatus = __('Rejected', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'mycompanies', 'jsjobspageid'=>jsjobs::getPageid())) ."?jsjobscf=email" . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == 1) {
                            $checkstatus = __('Approved', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        }
                        $Companyname = $record->companyname;
                        $credits = 0;
                        $matcharray = array(
                            '{COMPANY_NAME}' => $Companyname,
                            '{EMPLOYER_NAME}' => $Username,
                            '{COMPANY_LINK}' => $link,
                            '{COMPANY_CREDITS}' => $credits,
                            '{COMPANY_STATUS}' => $checkstatus
                        );
                        $template = $this->getTemplateForEmail('company-new');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_company');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Add New Company mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 2); //2 action for add company hock
                        }
                        $link = "<a href=" . admin_url("admin.php?page=jsjobs_company&jsjobscf=email") . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        $matcharray['{COMPANY_LINK}'] = $link;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // Add New Company mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', 1); //1 action for add company hock
                        }
                        break;
                    case 2: // Delete Company
                        $matcharray = array(
                            '{COMPANY_NAME}' => $mailextradata['companyname'],
                            '{COMPANY_OWNER_NAME}' => $mailextradata['contactname']
                        );
                        $Email = $mailextradata['contactemail'];
                        $template = $this->getTemplateForEmail('company-delete');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('delete_company');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Delete Company mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 3); // 3 action for company delete hock
                        }
                        break;
                    case 3: // Company approve OR compnay Reject
                        $record = $this->getRecordByTablenameAndId('js_job_companies', $id,15);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = $record->companyownername;
                        if ($Username == '') {
                            $Username = $record->username;
                        }
                        $Email = $record->companyuseremail;
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $Companyname = $record->companyname;
                        $status = $record->status;
                        $credits = 0;
                        $checkstatus = null;
                        $link = null;
                        if ($status == -1) {
                            $checkstatus = __('Rejected', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'mycompanies', 'jsjobspageid'=>jsjobs::getPageid())) ."?jsjobscf=email" . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == 1) {
                            $checkstatus = __('Approved', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        }
                        $matcharray = array(
                            '{COMPANY_NAME}' => $Companyname,
                            '{EMPLOYER_NAME}' => $Username,
                            '{COMPANY_LINK}' => $link,
                            '{COMPANY_STATUS}' => $checkstatus,
                            '{COMPANY_CREDITS}' => $credits
                        );
                        $template = $this->getTemplateForEmail('company-status');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('company_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Company approve or reject mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for compnay status hock
                        }
                        break;
                    case 4: // Company approve OR reject for gold
                        $record = $this->getRecordByTablenameAndId('js_job_companies', $id,16);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = $record->companyownername;
                        if ($Username == '') {
                            $Username = $record->username;
                        }
                        $Email = $record->companyuseremail;
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $Companyname = $record->companyname;
                        $goldcompany = $record->goldcompany;
                        $credits = 0;
                        $link = null;
                        $checkgoldcompany = null;
                        if ($goldcompany == -1) {
                            $checkgoldcompany = __('rejected for gold', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'mycompanies', 'jsjobspageid'=>jsjobs::getPageid())) ."?jsjobscf=email" . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        }
                        if ($goldcompany == 1) {
                            $checkgoldcompany = __('approved for gold', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        }
                        if ($goldcompany == 2) {
                            $checkgoldcompany = __('removed for gold', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        }
                        if ($goldcompany == 0) {
                            $checkgoldcompany = __('pending for gold', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        }
                        $matcharray = array(
                            '{COMPANY_NAME}' => $Companyname,
                            '{EMPLOYER_NAME}' => $Username,
                            '{COMPANY_LINK}' => $link,
                            '{COMPANY_STATUS}' => $checkgoldcompany,
                            '{COMPANY_CREDITS}' => $credits
                        );
                        $template = $this->getTemplateForEmail('company-status');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('company_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Gold Company mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 5); // 5 action for compnay gold hock
                        }
                        break;
                    case 5: // Company approve OR reject for featured
                        $record = $this->getRecordByTablenameAndId('js_job_companies', $id,17);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = $record->companyownername;
                        if ($Username == '') {
                            $Username = $record->username;
                        }
                        $Email = $record->companyuseremail;
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $Companyname = $record->companyname;
                        $credits = 0;
                        $featuredcompany = $record->featuredcompany;
                        $link = null;
                        $checkfeaturedcompany = null;
                        if ($featuredcompany == -1) {
                            $checkfeaturedcompany = __('rejected for featured', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'mycompanies', 'jsjobspageid'=>jsjobs::getPageid())) ."?jsjobscf=email" . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        }
                        if ($featuredcompany == 1) {
                            $checkfeaturedcompany = __('approved for featured', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        }
                        if ($featuredcompany == 2) {
                            $checkfeaturedcompany = __('removed for featured', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        }
                        if ($featuredcompany == 0) {
                            $checkfeaturedcompany = __('pending for featured', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Company Detail', 'js-jobs') . "</a>";
                        }
                        $matcharray = array(
                            '{COMPANY_NAME}' => $Companyname,
                            '{EMPLOYER_NAME}' => $Username,
                            '{COMPANY_LINK}' => $link,
                            '{COMPANY_STATUS}' => $checkfeaturedcompany,
                            '{COMPANY_CREDITS}' => $credits
                        );
                        $template = $this->getTemplateForEmail('company-status');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('company_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        //  Featured Company mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 6); // 6 action for company featured hock
                        }
                        break;
                }
                break;
            case 2: // Mail For Job
                switch ($action) {
                    case 1: // Add New Job
                        $record = $this->getRecordByTablenameAndId('js_job_jobs', $id,19);
			            if($record == '' || empty($record)){
                            break;
                        }
                        $userid = isset($record->id) ? $record->id : '';
                        $Username = isset($record->username) ? $record->username : $record->visname;
                        $jobname = $record->jobtitle;
                        $Email = $record->useremail;
                        $status = $record->status;
                        $companyname = $record->companyname;
                        $credits = 0;
                        $checkstatus = null;
                        $link = null;
                        if ($status == 1) {
                            $checkstatus = __('Approved', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == -1) {
                            $checkstatus = __('Rejected', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == 0) {
                            $checkstatus = __('Pending', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        $matcharray = array(
                            '{JOB_TITLE}' => $jobname,
                            '{EMPLOYER_NAME}' => $Username,
                            '{JOB_LINK}' => $link,
                            '{JOB_STATUS}' => $checkstatus,
                            '{JOB_CREDITS}' => $credits,
                            '{COMPANY_NAME}' => $companyname
                        );
                        $template = $this->getTemplateForEmail('job-new');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_job');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Add New Job mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 7); // 7 action for add job hock
                        }
                        $link = "<a href=" . admin_url("admin.php?page=jsjobs_job&jsjobscf=email") . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        $matcharray['{JOB_LINK}'] = $link;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // Add New Job mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', 8); // 8 action for add job hock
                        }
                        break;
                    case 2: // Job Delete
                        $matcharray = array(
                            '{JOB_TITLE}' => $mailextradata['jobtitle'],
                            '{EMPLOYER_NAME}' => $mailextradata['user'],
                            '{COMPANY_NAME}' => $mailextradata['companyname']
                        );
                        $Email = $mailextradata['useremail'];
                        $template = $this->getTemplateForEmail('job-delete');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('delete_job');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // job Delete mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 10); // 10 for job delete
                        }
                        break;
                    case 3: // job approve OR reject
                        $record = $this->getRecordByTablenameAndId('js_job_jobs', $id ,19);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = isset($record->username) ? $record->username : $record->visname;
                        $jobname = $record->jobtitle;
                        $Email = $record->useremail;
                        $status = $record->status;
                        $goldjob = isset($record->goldjob) ? $record->goldjob : '';
                        $featuredjob = isset($record->featuredjob) ? $record->featuredjob : '';
                        $credits = 0;
                        $link = null;
                        $checkstatus = null;
                        if ($status == 1) {
                            $checkstatus = __('Approved', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == -1) {
                            $checkstatus = __('Rejected', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == 2) {
                            $checkstatus = __('Removed', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        $matcharray = array(
                            '{JOB_TITLE}' => $jobname,
                            '{EMPLOYER_NAME}' => $Username,
                            '{JOB_LINK}' => $link,
                            '{JOB_STATUS}' => $checkstatus,
                            '{JOB_CREDITS}' => $credits
                        );
                        $template = $this->getTemplateForEmail('job-status');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('job_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // job Approve mail to User
                        if ($getEmailStatus->employer == 1 && $record->uid !=0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 11); // 11 action for job gold hock
                        }
                        if ($status == 1) {
                            $checkstatus = __('Approved', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == -1) {
                            $checkstatus = __('Rejected', 'js-jobs');
                            $link = null;
                        }
                        if ($status == 2) {
                            $checkstatus = __('Removed', 'js-jobs');
                            $link = null;
                        }
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // job Approve mail to visitor
                        if ($getEmailStatus->employer_visitor == 1 && $record->uid == 0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 12); // 12 action for job gold hock
                        }
                        break;
                    case 4: // Job approve OR reject for gold
                        $record = $this->getRecordByTablenameAndId('js_job_jobs', $id ,20);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = isset($record->username) ? $record->username : $record->visname;
                        $jobname = $record->jobtitle;
                        $Email = $record->useremail;
                        $goldjob = isset($record->goldjob) ? $record->goldjob : '';
                        $featuredjob = isset($record->featuredjob) ? $record->featuredjob : '';
                        $credits = 0;
                        $link = null;
                        $checkstatus = null;
                        $checkgoldjob = null;
                        if ($goldjob == -1) {
                            $checkgoldjob = __('rejected for gold', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        if ($goldjob == 1) {
                            $checkgoldjob = __('approved for gold', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        if ($goldjob == 2) {
                            $checkgoldjob = __('removed for gold', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        if ($goldjob == 0) {
                            $checkgoldjob = __('pending for gold', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        $matcharray = array(
                            '{JOB_TITLE}' => $jobname,
                            '{EMPLOYER_NAME}' => $Username,
                            '{JOB_LINK}' => $link,
                            '{JOB_STATUS}' => $checkgoldjob,
                            '{JOB_CREDITS}' => $credits
                        );
                        $template = $this->getTemplateForEmail('job-status');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('job_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // job Approve mail to User
                        if ($getEmailStatus->employer == 1 && $record->uid !=0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 13); // 13 action for job gold hock
                        }
                        $matcharray['{JOB_LINK}'] = $link;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // job Approve mail to visitor
                        if ($getEmailStatus->employer_visitor == 1 && $record->uid == 0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 14); // 14 action for job gold hock
                        }
                        break;
                    case 5: // Job approve OR reject for featured
                        $record = $this->getRecordByTablenameAndId('js_job_jobs', $id ,21);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = isset($record->username) ? $record->username : $record->visname;
                        $jobname = $record->jobtitle;
                        $Email = $record->useremail;
                        $featuredjob = isset($record->featuredjob) ? $record->featuredjob : '';
                        $link = null;
                        $checkstatus = null;
                        $checkfeaturedjob = null;
                        $credits = 0;
                        if ($featuredjob == -1) {
                            $checkfeaturedjob = __('rejected for featured', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        if ($featuredjob == 1) {
                            $checkfeaturedjob = __('approved for featured', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        if ($featuredjob == 2) {
                            $checkfeaturedjob = __('removed for featured', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        if ($featuredjob == 0) {
                            $checkfeaturedjob = __('pending for featured', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        $matcharray = array(
                            '{JOB_TITLE}' => $jobname,
                            '{EMPLOYER_NAME}' => $Username,
                            '{JOB_LINK}' => $link,
                            '{JOB_STATUS}' => $checkfeaturedjob,
                            '{JOB_CREDITS}' => $credits
                        );
                        $template = $this->getTemplateForEmail('job-status');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('job_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // job featured mail to User
                        if ($getEmailStatus->employer == 1 && $record->uid !=0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 15); // 15 action for job gold hock
                        }
                        $matcharray['{JOB_LINK}'] = $link;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // job featured mail to visitor
                        if ($getEmailStatus->employer_visitor == 1 && $record->uid == 0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 16); // 16 action for job gold hock
                        }
                        break;
                    case 6: // Add New visitor Job
                        $record = $this->getRecordByTablenameAndId('js_job_jobs', $id);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $visusername = $record->visname ? $record->visname : '';
                        $jobname = $record->jobtitle;
                        $Email = $record->useremail;
                        $companyname = $record->companyname;
                        $status = $record->status;
                        $checkstatus = null;
                        $link = null;
                        if ($status == 1) {
                            $checkstatus = __('Approved', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == -1) {
                            $checkstatus = __('Rejected', 'js-jobs');
                            $link = "<strong>" . __('Due to rejection of job you do not have permission to see job detail', 'js-jobs') . "</strong>";
                        }
                        if ($status == 0) {
                            $checkstatus = __('Pending', 'js-jobs');
                            $link = "<strong>" . __('Due to pending status of job you do not have permission to see job detail', 'js-jobs') . "</strong>";
                        }
                        $matcharray = array(
                            '{JOB_TITLE}' => $jobname,
                            '{EMPLOYER_NAME}' => $visusername,
                            '{JOB_LINK}' => $link,
                            '{JOB_STATUS}' => $status,
                            '{COMPANY_NAME}' => $companyname
                        );
                        $template = $this->getTemplateForEmail('job-new-vis');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_job');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Add New visitor Job mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', 8); // 8 action for add job hock
                        }
                        if ($status == 1) {
                            $checkstatus = __('Approved', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Job Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == -1) {
                            $checkstatus = __('Rejected', 'js-jobs');
                            $link = "<strong>" . __('Due to rejection of job you do not have permission to see job detail', 'js-jobs') . "</strong>";
                        }
                        if ($status == 0) {
                            $checkstatus = __('Pending', 'js-jobs');
                            $link = "<strong>" . __('Due to pending status of job you do not have permission to see job detail', 'js-jobs') . "</strong>";
                        }
                        $matcharray['{JOB_LINK}'] = $link;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // Add New visitor Job mail to visitor
                        if ($getEmailStatus->employer_visitor == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 9); // 9 action for add job hock
                        }
                        break;
                }
                break;

            case 3: // Mail For Resume
                switch ($action) {
                    case 1: // Add New Resume
                        $record = $this->getRecordByTablenameAndId('js_job_resume', $id,1);
                        if($record == '' || empty($record)){
                            return;
                        }
                        $Username = $record->firstname . '' . $record->middlename . '' . $record->lastname;
                        if ($Username == '') {
                            $Username = $record->username;
                        }
                        $Email = isset($record->useremailfromresume) ? $record->useremailfromresume : '';
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $resumename = $record->resumetitle;
                        $status = $record->resumestatus;
                        $link = null;
                        $checkstatus = null;
                        if ($status == 1) {
                            $checkstatus = __('Approved', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == -1) {
                            $checkstatus = __('Rejected', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == 0) {
                            $checkstatus = __('Pending', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        $matcharray = array(
                            '{RESUME_TITLE}' => $resumename,
                            '{JOBSEEKER_NAME}' => $Username,
                            '{RESUME_STATUS}' => $checkstatus,
                            '{RESUME_LINK}' => $link
                        );
                        $template = $this->getTemplateForEmail('resume-new');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_resume');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Add New resume mail to User
                        if ($getEmailStatus->jobseeker == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        $link = "<a href=" . admin_url("admin.php?page=jsjobs_resume&jsjobscf=email") . ">" . __('Resumes', 'js-jobs') . "</a>";
                        $matcharray['{RESUME_LINK}'] = $link;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // Add New resume mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 2: // Resume Approve or Reject
                        $record = $this->getRecordByTablenameAndId('js_job_resume', $id,1);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = $record->firstname . '' . $record->middlename . '' . $record->lastname;
                        if ($Username == '') {
                            $Username = $record->username;
                        }
                        $Email = $record->useremailfromresume;
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $resumename = $record->resumetitle;
                        $status = $record->resumestatus;
                        $credits = $record->credits;
                        $link = null;
                        $checkstatus = null;
                        if ($status == 1) {
                            $checkstatus = __('Approved', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == -1) {
                            $checkstatus = __('Rejected', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        $matcharray = array(
                            '{RESUME_TITLE}' => $resumename,
                            '{JOBSEEKER_NAME}' => $Username,
                            '{RESUME_LINK}' => $link,
                            '{RESUME_STATUS}' => $checkstatus,
                            '{RESUME_CREDITS}' => $credits
                        );
                        $template = $this->getTemplateForEmail('resume-status');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('resume_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // resume Approve mail to jobseeker
                        if ($getEmailStatus->jobseeker == 1 && $record->uid != 0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        if ($status == 1) {
                            $checkstatus = __('Approved', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == -1) {
                            $checkstatus = __('Rejected', 'js-jobs');
                            $link = null;
                        }
                        if ($status == 2) {
                            $checkstatus = __('Removed', 'js-jobs');
                            $link = null;
                        }
                        if ($status == 0) {
                            $checkstatus = __('Pending', 'js-jobs');
                            $link = null;
                        }
                        $matcharray['{RESUME_LINK}'] = $link;
                        $matcharray['{RESUME_STATUS}'] = $checkstatus;
                        $matcharray['{RESUME_CREDITS}'] = 0;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // job Approve mail to visitor
                        if ($getEmailStatus->jobseeker_visitor == 1 && $record->uid == 0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 12); // 12 action for job gold hock
                        }

                        break;
                    case 3: // Resume approve OR reject for gold
                        $record = $this->getRecordByTablenameAndId('js_job_resume', $id,2);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = $record->firstname . '' . $record->middlename . '' . $record->lastname;
                        if ($Username == '') {
                            $Username = $record->username;
                        }
                        $Email = $record->useremailfromresume;
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $resumename = $record->resumetitle;
                        $status = $record->resumestatus;
                        $goldresume = $record->goldresume;
                        $featuredresume = $record->featuredresume;
                        $credits = $record->credits;
                        $link = null;
                        $checkgoldresume = null;
                        if ($goldresume == -1) {
                            $checkgoldresume = __('rejected for gold', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        if ($goldresume == 1) {
                            $checkgoldresume = __('approved for gold', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        if ($goldresume == 2) {
                            $checkgoldresume = __('Removed for gold', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        if ($goldresume == 0) {
                            $checkgoldresume = __('pending for gold', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        $matcharray = array(
                            '{RESUME_TITLE}' => $resumename,
                            '{JOBSEEKER_NAME}' => $Username,
                            '{RESUME_LINK}' => $link,
                            '{RESUME_STATUS}' => $checkgoldresume,
                            '{RESUME_CREDITS}' => $credits
                        );
                        $template = $this->getTemplateForEmail('resume-status');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('resume_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // resume Approve mail to Jobseeker
                        if ($getEmailStatus->jobseeker == 1 && $record->uid != 0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        if ($status == 1) {
                            $checkgoldresume = __('approved for gold', 'js-jobs');
                        }
                        if ($status == -1) {
                            $checkgoldresume = __('rejected for gold', 'js-jobs');
                        }
                        if ($status == 2) {
                            $checkgoldresume = __('removed for gold', 'js-jobs');
                        }
                        if ($status == 0) {
                            $checkgoldresume = __('pending for gold', 'js-jobs');
                        }
                        $matcharray['{RESUME_LINK}'] = $link;
                        $matcharray['{RESUME_STATUS}'] = $checkgoldresume;
                        $matcharray['{RESUME_CREDITS}'] = 0;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // job Approve mail to visitor
                        if ($getEmailStatus->jobseeker_visitor == 1 && $record->uid == 0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 12); // 12 action for job gold hock
                        }
                        break;
                    case 4: // resume approve OR reject for featured
                        $record = $this->getRecordByTablenameAndId('js_job_resume', $id,3);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = $record->firstname . '' . $record->middlename . '' . $record->lastname;
                        if ($Username == '') {
                            $Username = $record->username;
                        }
                        $Email = $record->useremailfromresume;
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $resumename = $record->resumetitle;
                        $status = $record->resumestatus;
                        $goldresume = $record->goldresume;
                        $featuredresume = $record->featuredresume;
                        $credits = $record->credits;
                        $link = null;
                        $checkfeaturedresume = null;
                        if ($featuredresume == -1) {
                            $checkfeaturedresume = __('rejected for featured', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        if ($featuredresume == 1) {
                            $checkfeaturedresume = __('approved for featured', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        if ($featuredresume == 0) {
                            $checkfeaturedresume = __('pending for featured', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        if ($featuredresume == 2) {
                            $checkfeaturedresume = __('removed for featured', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        $matcharray = array(
                            '{RESUME_TITLE}' => $resumename,
                            '{JOBSEEKER_NAME}' => $Username,
                            '{RESUME_LINK}' => $link,
                            '{RESUME_STATUS}' => $checkfeaturedresume,
                            '{RESUME_CREDITS}' => $credits
                        );
                        $template = $this->getTemplateForEmail('resume-status');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('resume_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // resume Approve mail to Jobseeker
                        if ($getEmailStatus->jobseeker == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        if ($featuredresume == 1) {
                            $checkfeaturedresume = __('approved for featured', 'js-jobs');
                        }
                        if ($featuredresume == -1) {
                            $checkfeaturedresume = __('rejected for featured', 'js-jobs');
                        }
                        if ($featuredresume == 2) {
                            $checkfeaturedresume = __('removed for featured', 'js-jobs');
                        }
                        if ($featuredresume == 0) {
                            $checkfeaturedresume = __('pending for featured', 'js-jobs');
                            $link = null;
                        }
                        $matcharray['{RESUME_LINK}'] = $link;
                        $matcharray['{RESUME_STATUS}'] = $checkfeaturedresume;
                        $matcharray['{RESUME_CREDITS}'] = 0;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // job Approve mail to visitor
                        if ($getEmailStatus->jobseeker_visitor == 1 && $record->uid == 0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 12); // 12 action for job gold hock
                        }
                        break;
                    case 5: //Add new visitor resume
                        $record = $this->getRecordByTablenameAndId('js_job_resume', $id);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $visusername = $record->firstname . '' . $record->middlename . '' . $record->lastname;
                        $Email = $record->useremailfromresume;
                        $resumename = $record->resumetitle;
                        $status = $record->status;
                        if ($status == 1) {
                            $checkstatus = __('Approved', 'js-jobs');
                            $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$id, 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        }
                        if ($status == -1) {
                            $checkstatus = __('Rejected', 'js-jobs');
                            $link = "<strong>" . __('Due to rejection of resume you do not have permission to see resume detail', 'js-jobs') . "</strong>";
                        }
                        if ($status == 0) {
                            $checkstatus = __('Pending', 'js-jobs');
                            $link = "<strong>" . __('Due to pending status of resume you do not have permission to see resume detail', 'js-jobs') . "</strong>";
                        }
                        $matcharray = array(
                            '{RESUME_TITLE}' => $resumename,
                            '{JOBSEEKER_NAME}' => $visusername,
                            '{RESUME_STATUS}' => $checkstatus,
                            '{RESUME_LINK}' => $link
                        );
                        $template = $this->getTemplateForEmail('resume-new-vis');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_resume_visitor');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Add New visitor resume mail to User
                        if ($getEmailStatus->jobseeker_visitor == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 7); // 7 action for add job hock
                        }
                        $link = "<a href=" . admin_url("admin.php?page=jsjobs_resume&jsjobscf=email") . ">" . __('Resume Detail', 'js-jobs') . "</a>";
                        $matcharray['{RESUME_LINK}'] = $link;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // Add New visitor resume mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', 8); // 8 action for add job hock
                        }

                    break;
                case 6://delete resume
                    $matcharray = array(
                        '{RESUME_TITLE}' => $mailextradata['resumetitle'],
                        '{JOBSEEKER_NAME}' => $mailextradata['jobseekername']
                    );
                    $Email = $mailextradata['useremail'];
                    $template = $this->getTemplateForEmail('resume-delete');
                    $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('resume-delete');
                    $msgSubject = $template->subject;
                    $msgBody = $template->body;
                    $this->replaceMatches($msgSubject, $matcharray);
                    $this->replaceMatches($msgBody, $matcharray);
                    $senderEmail = $config_array['mailfromaddress'];
                    $senderName = $config_array['mailfromname'];
                    // Delete resume mail to User
                    if ($getEmailStatus->jobseeker == 1) {
                        $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 3); // 3 action for company delete hock
                    }
                break;
                }
            break;
            case 4: // mail for purchase credits pack
                switch ($action) {
                    case 1: // Employer purchase crdits pack
                        $record = $this->getRecordByTablenameAndId('js_job_purchasehistory', $id);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = $record->userfirstname . $record->userlastname;
                        $packagename = $record->packagename;
                        $Email = $record->useremailaddress;
                        $purchasedate = $record->purcahsedate;
                        $packageprice = $record->price;
                        if ($Email == '') {
                            $finalEmail = $record->userotheremailaddress;
                        }
                        if ($Username == '') {
                            $username = $record->userothername;
                        }
                        $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'credits', 'jsjobslt'=>'employercredits', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Package Detail', 'js-jobs') . "</a>";
                        $matcharray = array(
                            '{PACKAGE_NAME}' => $packagename,
                            '{EMPLOYER_NAME}' => $Username,
                            '{PACKAGE_PRICE}' => $packageprice,
                            '{PACKAGE_LINK}' => $link,
                            '{PACKAGE_PURCHASE_DATE}' => $purchasedate
                        );
                        $template = $this->getTemplateForEmail('employer-purchase-credit-pack');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('employer_purchase_credits_pack');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Employer purchase credits pack  mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 1); // 1 action for employer purchase credits pack
                        }
                        $link = "<a href=" . admin_url("admin.php?page=jsjobs_creditspack") . ">" . __('Package Detail', 'js-jobs') . "</a>";
                        $matcharray['{PACKAGE_LINK}'] = $link;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // Employer purchase credits pack  mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        break;
                    case 2: // Jobseeker purchase crdits pack
                        $record = $this->getRecordByTablenameAndId('js_job_purchasehistory', $id);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = $record->userfirstname . $record->userlastname;
                        $packagename = $record->packagename;
                        $Email = $record->useremailaddress;
                        $purchasedate = $record->purcahsedate;
                        $packageprice = $record->price;
                        if ($Email == '') {
                            $finalEmail = $record->userotheremailaddress;
                        }
                        if ($Username == '') {
                            $username = $record->userothername;
                        }
                        $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'credits', 'jsjobslt'=>'jobseekercredits', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Package Detail', 'js-jobs') . "</a>";
                        $matcharray = array(
                            '{PACKAGE_NAME}' => $packagename,
                            '{JOBSEEKER_NAME}' => $Username,
                            '{PACKAGE_PRICE}' => $packageprice,
                            '{PACKAGE_LINK}' => $link,
                            '{PACKAGE_PURCHASE_DATE}' => $purchasedate
                        );
                        $template = $this->getTemplateForEmail('jobseeker-purchase-credit-pack');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('jobseeker_purchase_credits_pack');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Jobseeker purchase credits pack  mail to User
                        if ($getEmailStatus->jobseeker == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        $link = "<a href=" . admin_url("admin.php?page=jsjobs_creditspack") . ">" . __('Credit Detail', 'js-jobs') . "</a>";
                        $matcharray['{PACKAGE_LINK}'] = $link;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // Jobseeker purchase credits pack  mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        break;
                    case 3: // package Expiry for jobseeker
                        $record = $this->getRecordByTablenameAndId('js_job_credits_pack', $id);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = $record->userfirstname . $record->userlastname;
                        $packagename = $record->packagename;
                        $Email = $record->useremailaddress;
                        $purchasedate = $record->purcahsedate;
                        $packageprice = $record->price;
                        $expirydays = $record->expire;
                        $expirydate = date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($purchasedate . ' + ' . $expirydays . ' day'));
                        $curdate = date_i18n('Y-m-d');
                        $purchasedate = date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($purchasedate));
                        if ($Email == '') {
                            $finalEmail = $record->userotheremailaddress;
                        }
                        if ($Username == '') {
                            $username = $record->userothername;
                        }
                        $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'credits', 'jsjobslt'=>'jobseekercredits', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Package Detail', 'js-jobs') . "</a>";
                        $matcharray = array(
                            '{PACKAGE_NAME}' => $packagename,
                            '{JOBSEEKER_NAME}' => $Username,
                            '{PACKAGE_LINK}' => $link,
                            '{PACKAGE_PURCHASE_DATE}' => $purchasedate
                        );
                        $template = $this->getTemplateForEmail('jobseeker-package-expire');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('jobseeker_package_expire');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Jobseeker purchase credits pack  mail to User
                        if ($getEmailStatus->jobseeker == 1 && ($expirydate > $curdate)) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        break;
                    case 4: // package Expiry for employer
                        $record = $this->getRecordByTablenameAndId('js_job_credits_pack', $id);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = $record->userfirstname . $record->userlastname;
                        $packagename = $record->packagename;
                        $Email = $record->useremailaddress;
                        $purchasedate = $record->purcahsedate;
                        $packageprice = $record->price;
                        $expirydays = $record->expire;
                        $expirydate = date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($purchasedate . ' + ' . $expirydays . ' day'));
                        $curdate = date_i18n('Y-m-d');
                        $purchasedate = date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($purchasedate));
                        if ($Email == '') {
                            $finalEmail = $record->userotheremailaddress;
                        }
                        if ($Username == '') {
                            $username = $record->userothername;
                        }
                        $link = "<a href=" . jsjobs::makeUrl(array('jsjobsme'=>'credits', 'jsjobslt'=>'employercredits', 'jsjobspageid'=>jsjobs::getPageid())) . ">" . __('Package Detail', 'js-jobs') . "</a>";
                        $matcharray = array(
                            '{PACKAGE_NAME}' => $packagename,
                            '{EMPLOYER_NAME}' => $Username,
                            '{PACKAGE_LINK}' => $link,
                            '{PACKAGE_PURCHASE_DATE}' => $purchasedate
                        );
                        $template = $this->getTemplateForEmail('employer-package-expire');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('employer_package_expire');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // employer purchase credits pack  mail to User
                        if ($getEmailStatus->employer == 1 && ($expirydate > $curdate)) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        break;
                }
                break;
            case 5: //Email for Apply job
                switch ($action) {
                    case 1:// jobapply email to employer and jobseeker
                        $record = $this->getRecordByTablenameAndId('js_job_jobapply', $id);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $employername = $record->companycontactname;
                        if ($employername == '') {
                            $employername = $record->username;
                        }
                        $Emailtoemployer = $record->companycontactemail;
                        if ($Emailtoemployer == '') {
                            $Emailtoemployer = $record->useremailforemployer;
                        }
                        $Emailtojobseekr = $record->resumeemail;
                        if ($Emailtojobseekr == '') {
                            $Emailtojobseekr == $record->useremailforjobseeker;
                        }
                        $companyname = $record->companyname;
                        $resumename = $record->resumetitle;
                        $jobtitle = $record->jobtitle;
                        $resumeappliedstatus = $record->resumestatus;
                        $resumetitle = $record->resumetitle;
                        $jobseekername = $record->firstname . '' . $record->middlename . '' . $record->lastname;
                        if ($resumeappliedstatus == 1) {
                            $checkstatus = __('Inbox', 'js-jobs');
                        }
                        if ($resumeappliedstatus == 1) {
                            $checkstatus = __('Spam', 'js-jobs');
                        }
                        if ($resumeappliedstatus == 1) {
                            $checkstatus = __('Hired', 'js-jobs');
                        }
                        if ($resumeappliedstatus == 1) {
                            $checkstatus = __('Rejected', 'js-jobs');
                        }
                        if ($resumeappliedstatus == 1) {
                            $checkstatus = __('Short listed', 'js-jobs');
                        }
                        $resumedata = null;
                        $matcharray = array(
                            '{JOBSEEKER_NAME}' => $jobseekername,
                            '{EMPLOYER_NAME}' => $employername,
                            '{RESUME_APPLIED_STATUS}' => $checkstatus,
                            '{RESUME_TITLE}' => $resumename,
                            '{JOB_TITLE}' => $jobtitle,
                            '{RESUME_DATA}' => $resumedata
                        );
                        $template = $this->getTemplateForEmail('jobapply-employer');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus($template->id);
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Add New Job mail to employer
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Emailtoemployer, $msgSubject, $msgBody, $senderEmail, $senderName, '', 7); // 7 action for add job hock
                        }
                        $template = $this->getTemplateForEmail('jobapply-jobseeker');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus($template->id);
                        $matcharray = array(
                            '{JOBSEEKER_NAME}' => $jobseekername,
                            '{RESUME_APPLIED_STATUS}' => $checkstatus,
                            '{RESUME_TITLE}' => $resumename,
                            '{COMPANY_NAME}' => $companyname,
                            '{JOB_TITLE}' => $jobtitle
                        );
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // jobapply mail to jobseeker
                        if ($getEmailStatus->jobseeker == 1) {
                            $this->sendEmail($Emailtojobseekr, $msgSubject, $msgBody, $senderEmail, $senderName, '', 8); // 8 action for add job hock
                        }
                        break;
                }

                break;
            case 6: //employer OR jobseeker resgistration
                switch ($action) {
                    case 1: //for employer registration
                        $record = $this->getRecordByTablenameAndId('users', $id);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $link = null;
                        $checkuserrole = null;
                        $Username = $record->username;
                        $Email = $record->useremail;
                        $userrole = $record->userrole;
                        $link = "<a href=" . jsjobs::makeUrl(array('jsjobspageid'=>JSJOBSRequest::getVar('jsjobspageid'))) . ">" . __('Control Panel', 'js-jobs') . "</a>";
                        if ($userrole == 1) {
                            $checkuserrole = __('Employer', 'js-jobs');
                        }
                        $matcharray = array(
                            '{USER_ROLE}' => $checkuserrole,
                            '{USER_NAME}' => $Username,
                            '{CONTROL_PANEL_LINK}' => $link
                        );
                        $template = $this->getTemplateForEmail('employer-new');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_employer');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // New employer registration mail to user
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        $link = "<a href=" . admin_url("admin.php?page=jsjobs") . ">" . __('Control Panel', 'js-jobs') . "</a>";
                        $matcharray['{CONTROL_PANEL_LINK}'] = $link;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // New employer registration mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        break;
                    case 2: //for jobseeker registration
                        $record = $this->getRecordByTablenameAndId('users', $id);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $link = null;
                        $checkuserrole = null;
                        $Username = $record->username;
                        $Email = $record->useremail;
                        $userrole = $record->userrole;
                        $link = "<a href=" . jsjobs::makeUrl(array('jsjobspageid'=>jsjobs::getPageid() )) . ">" . __('Control Panel', 'js-jobs') . "</a>";
                        if ($userrole == 2) {
                            $checkuserrole = __('Job seeker', 'js-jobs');
                        }
                        $matcharray = array(
                            '{USER_ROLE}' => $checkuserrole,
                            '{USER_NAME}' => $Username,
                            '{CONTROL_PANEL_LINK}' => $link
                        );
                        $template = $this->getTemplateForEmail('jobseeker-new');
                        $getEmailStatus = JSJOBSincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_jobseeker');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // New jobseeker registration mail to user
                        if ($getEmailStatus->jobseeker == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        $link = "<a href=" . admin_url("admin.php?page=jsjobs") . ">" . __('Control Panel', 'js-jobs') . "</a>";
                        $matcharray['{CONTROL_PANEL_LINK}'] = $link;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // New jobseeker registration mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                        }
                        break;
                }

                break;
        }
    }

    function getTemplate($tempfor) {

        switch ($tempfor) {
            case 'd-cm' : $tempatefor = 'company-delete';
                break;
            case 'ew-obv' : $tempatefor = 'job-new-vis';
                break;
            case 'em-n' : $tempatefor = 'employer-new';
                break;
            case 'obs-n' : $tempatefor = 'jobseeker-new';
                break;
            case 'ob-d' : $tempatefor = 'job-delete';
                break;
            case 'obse-ps' : $tempatefor = 'jobseeker-purcahse-package-status';
                break;
            case 'js-jap' : $tempatefor = 'jobapply-jobseeker';
                break;
            case 'em-jap' : $tempatefor = 'jobapply-employer';
                break;
            case 'ew-cm' : $tempatefor = 'company-new';
                break;
            case 'cm-sts' : $tempatefor = 'company-status';
                break;
            case 'cm-rj' : $tempatefor = 'company-rejecting';
                break;
            case 'ew-ob' : $tempatefor = 'job-new';
                break;
            case 'ob-sts' : $tempatefor = 'job-Status';
                break;
            case 'ob-rj' : $tempatefor = 'job-rejecting';
                break;
            case 'ap-rs' : $tempatefor = 'applied-resume_status';
                break;
            case 'ew-rm' : $tempatefor = 'resume-new';
                break;
            case 'ew-rmv' : $tempatefor = 'resume-new-vis';
                break;
            case 'rm-sts' : $tempatefor = 'resume-status';
                break;
            case 'ew-ms' : $tempatefor = 'message-email';
                break;
            case 'rm-rj' : $tempatefor = 'resume-rejecting';
                break;
            case 'ob-pe' : $tempatefor = 'jobseeker-package-expire';
                break;
            case 'em-pe' : $tempatefor = 'employer-package-expire';
                break;
            case 'em-pc' : $tempatefor = 'employer-purchase-credit-pack';
                break;
            case 'obs-pc' : $tempatefor = 'jobseeker-purchase-credit-pack';
                break;
            case 'ms-sy' : $tempatefor = 'message-email';
                break;
            case 'jb-at' : $tempatefor = 'job-alert';
                break;
            case 'jb-at-vis' : $tempatefor = 'job-alert-visitor';
                break;
            case 'jb-to-fri' : $tempatefor = 'job-to-friend';
                break;
            case 'd-rs' : $tempatefor = 'resume-delete';
                break;
            case 'ad-jap' : $tempatefor = 'jobapply-jobapply';
                break;
            case 'ap-jap' : $tempatefor = 'applied-resume_status';
                break;
        }

        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_emailtemplates` WHERE templatefor = '" . $tempatefor . "'";
        jsjobs::$_data[0] = jsjobsdb::get_row($query);

        return;
    }

    function storeEmailTemplate($data) {
        if (empty($data))
            return false;

        $data['body'] = wpautop(wptexturize(jsjobslib::jsjobs_stripslashes($data['body'])));
        $row = JSJOBSincluder::getJSTable('emailtemplate');
        if (!$row->bind($data)) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSJOBS_SAVE_ERROR;
        }

        return JSJOBS_SAVED;
    }

    function sendMailtoVisitor($jobid) {
        if ($jobid)
            if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
                return false;

        $templatefor = 'job-new-vis';

        $query = "SELECT template.* FROM `" . jsjobs::$_db->prefix . "js_job_emailtemplates` AS template	WHERE template.templatefor = '" . $templatefor."'";

        $template = jsjobsdb::get_row($query);
        $msgSubject = $template->subject;
        $msgBody = $template->body;
        $jobquery = "SELECT job.id AS id,job.title, job.jobstatus,job.jobid AS jobid, company.name AS companyname, cat.cat_title AS cattitle,job.sendemail,company.contactemail,company.contactname
                              FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                              JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = job.companyid
                              JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON cat.id = job.jobcategory
                              WHERE job.id = " . $jobid;
        $jobuser = jsjobsdb::get_row($jobquery);
        if ($jobuser->jobstatus == 1) {

            $CompanyName = $jobuser->companyname;
            $JobCategory = $jobuser->cattitle;
            $ContactName = $jobuser->contactname;
            $JobTitle = $jobuser->title;
            if ($jobuser->jobstatus == 1)
                $JobStatus = __('Approved', 'js-jobs');
            else
                $JobStatus = __('Waiting for approval', 'js-jobs');
            $EmployerEmail = $jobuser->contactemail;
            $ContactName = $jobuser->contactname;
			$joblink = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$jobid, 'jsjobspageid'=>jsjobs::getPageid()));
            $msgSubject = jsjobslib::jsjobs_str_replace('{COMPANY_NAME}', $CompanyName, $msgSubject);
            $msgSubject = jsjobslib::jsjobs_str_replace('{CONTACT_NAME}', $ContactName, $msgSubject);
            $msgSubject = jsjobslib::jsjobs_str_replace('{JOB_CATEGORY}', $JobCategory, $msgSubject);
            $msgSubject = jsjobslib::jsjobs_str_replace('{JOB_TITLE}', $JobTitle, $msgSubject);
            $msgSubject = jsjobslib::jsjobs_str_replace('{JOB_STATUS}', $JobStatus, $msgSubject);
            $msgSubject = jsjobslib::jsjobs_str_replace('{EMPLOYER_NAME}', $ContactName, $msgSubject);
            $msgSubject = jsjobslib::jsjobs_str_replace('{JOB_LINK}', $joblink, $msgSubject);
            $msgBody = jsjobslib::jsjobs_str_replace('{COMPANY_NAME}', $CompanyName, $msgBody);
            $msgBody = jsjobslib::jsjobs_str_replace('{CONTACT_NAME}', $ContactName, $msgBody);
            $msgBody = jsjobslib::jsjobs_str_replace('{JOB_CATEGORY}', $JobCategory, $msgBody);
            $msgBody = jsjobslib::jsjobs_str_replace('{JOB_TITLE}', $JobTitle, $msgBody);
            $msgBody = jsjobslib::jsjobs_str_replace('{JOB_STATUS}', $JobStatus, $msgBody);
            $msgBody = jsjobslib::jsjobs_str_replace('{EMPLOYER_NAME}', $ContactName, $msgBody);
            $msgBody = jsjobslib::jsjobs_str_replace('{JOB_LINK}', $joblink, $msgBody);

            $config = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('visitor');
            if ($config['visitor_can_edit_job'] == 1) {
                $path = jsjobs::makeUrl(array('jsjobsme'=>'employer', 'jsjobslt'=>'addjob', 'email'=>$jobuser->contactemail, 'jobid'=>$jobuser->jobid, 'jsjobspageid'=>jsjobs::getPageid()))."?jsjobscf=email";
                $path = jsjobs::makeUrl(array('jsjobsme'=>'employer', 'jsjobslt'=>'addjob', 'jsjobsid'=>$jobuser->id, 'jsjobspageid'=>jsjobs::getPageid()))."?jsjobscf=email";
                $text = '<br><a href="' . $path . '" target="_blank" >' . __('click here to edit job', 'js-jobs') . '</a>';
                $msgBody .= $text;
            }

            $emailconfig = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('email');
            $senderName = $emailconfig['mailfromname'];
            $senderEmail = $emailconfig['mailfromaddress'];

            $recevierEmail = $EmployerEmail;

            JSJOBSincluder::getJSModel('common')->sendEmail($recevierEmail, $msgSubject, $msgBody, $senderEmail, $senderName );
        }
    }

    function getTemplateForEmail($templatefor) {
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_emailtemplates` WHERE templatefor = '" . $templatefor . "'";
        $template = jsjobs::$_db->get_row($query);
        if (jsjobs::$_db->last_error != null) {
            JSJOBSincluder::getJSModel('systemerror')->addSystemError();
        }
        return $template;
    }

    function replaceMatches(&$string, $matcharray) {
        foreach ($matcharray AS $find => $replace) {
            $string = jsjobslib::jsjobs_str_replace($find, $replace, $string);
        }
    }


    function jsjb_set_html_content_type() {
        return 'text/html';
    }

    function sendEmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments = '') {
        if (!$senderName)
            $senderName = jsjobs::$_configuration['title'];
        $headers = 'From: ' . $senderName . ' <' . $senderEmail . '>' . "\r\n";
        add_filter('wp_mail_content_type', array($this,'jsjb_set_html_content_type'));
        $body = jsjobslib::jsjobs_preg_replace('/\r?\n|\r/', '<br/>', $body);
        $body = jsjobslib::jsjobs_str_replace(array("\r\n", "\r", "\n"), "<br/>", $body);
        $body = nl2br($body);
        wp_mail($recevierEmail, $subject, $body, $headers, $attachments);
    }

    function getRecordByTablenameAndId($tablename, $id,$actionid = null) {
        if (!is_numeric($id))
            return false;
        $query = null;
        switch ($tablename) {
            case 'js_job_companies':

                $query = 'SELECT company.name AS companyname,CONCAT(user.first_name," ",user.last_name) AS username,user.emailaddress AS useremail,company.contactname AS companyownername
                            , company.status AS status,company.contactemail AS companyuseremail
                            FROM `' . jsjobs::$_db->prefix . 'js_job_companies` AS company
                            LEFT JOIN `' . jsjobs::$_db->prefix . 'js_job_users` AS user ON user.id = company.uid
                            WHERE company.id = ' . $id;
                break;
            case 'js_job_jobs':
                $decisionalquery = 'SELECT uid FROM `' . jsjobs::$_db->prefix . 'js_job_jobs` AS job WHERE id=' . $id;
                $decisionalrecord = jsjobs::$_db->get_row($decisionalquery);
                //query for get visitor jobs
                if ($decisionalrecord->uid == 0) {
                    $query = 'SELECT job.title AS jobtitle,company.contactname AS visname,company.name AS companyname,job.status AS status
                                ,company.contactemail AS useremail,company.uid
                            FROM `' . jsjobs::$_db->prefix . 'js_job_jobs` AS job
                            JOIN `' . jsjobs::$_db->prefix . 'js_job_companies` AS company ON job.companyid = company.id
                            WHERE job.id = ' . $id;
                }
                //query for get jobs
                else {
                    $query = 'SELECT user.id AS id,job.title AS jobtitle,company.contactname AS visname,company.name AS companyname, CONCAT(user.first_name," ",user.last_name) AS username,job.status AS status
                    ,company.contactemail AS useremail ,company.uid
                            FROM `' . jsjobs::$_db->prefix . 'js_job_jobs` AS job
                            JOIN `' . jsjobs::$_db->prefix . 'js_job_companies` AS company ON job.companyid = company.id
                            JOIN `' . jsjobs::$_db->prefix . 'js_job_users` AS user ON user.id = job.uid
                            WHERE job.id = ' . $id;
                }
                break;
            case 'js_job_resume':
                $decisionalquery = 'SELECT uid FROM `' . jsjobs::$_db->prefix . 'js_job_resume` AS rs WHERE id=' . $id;
                $decisionalrecord = jsjobs::$_db->get_row($decisionalquery);
                if ($decisionalrecord->uid == 0) {
                    //query for visitor resume
                    $query = 'SELECT rs.application_title AS resumetitle,rs.email_address AS useremail,rs.status AS resumestatus,  rs.first_name AS firstname, rs.last_name AS lastname, rs.middle_name AS middlename,rs.uid
                            FROM `' . jsjobs::$_db->prefix . 'js_job_resume` AS rs
                            WHERE rs.id = ' . $id;
                }
                //query for resume
                $query = 'SELECT rs.application_title AS resumetitle, CONCAT(user.first_name," ",user.last_name) AS username,rs.email_address AS useremailfromresume
                        ,rs.first_name AS firstname, rs.last_name AS lastname, rs.middle_name AS middlename, rs.email_address AS useremail,rs.status AS resumestatus,rs.uid
                            FROM `' . jsjobs::$_db->prefix . 'js_job_resume` AS rs
                            JOIN `' . jsjobs::$_db->prefix . 'js_job_users` AS user ON user.id = rs.uid
                            WHERE rs.id = ' . $id;
                break;
            case 'users':
                $query = 'SELECT CONCAT(u.first_name," ",u.last_name) AS username, u.emailaddress AS useremail, u.roleid AS userrole
                            FROM `' . jsjobs::$_db->prefix . 'js_job_users` AS u
                            WHERE u.id = ' . $id;
                break;
            case 'js_job_jobapply':
                $query = 'SELECT rs.first_name AS firstname, rs.middle_name AS middlename, rs.last_name AS lastname, jobap.action_status AS resumestatus , rs.email_address AS resumeemail,job.title AS jobtitle, com.contactname AS companycontactname,com.contactemail AS companycontactemail,com.name AS companyname, rs.application_title AS resumetitle, CONCAT(uforemployer.first_name," ",uforemployer.last_name) AS username, uforemployer.emailaddress AS useremailforemployer,uforjobseeker.emailaddress AS useremailforjobseeker,job.params
                            FROM ' . jsjobs::$_db->prefix . 'js_job_jobapply AS jobap
                            JOIN ' . jsjobs::$_db->prefix . 'js_job_jobs AS job ON jobap.jobid = job.id
                            JOIN ' . jsjobs::$_db->prefix . 'js_job_companies AS com ON job.companyid = com.id
                            JOIN ' . jsjobs::$_db->prefix . 'js_job_resume AS rs ON rs.id = jobap.cvid
                            JOIN ' . jsjobs::$_db->prefix . 'js_job_users AS uforemployer ON uforemployer.id = com.uid
                            JOIN ' . jsjobs::$_db->prefix . 'js_job_users AS uforjobseeker ON uforjobseeker.id = jobap.uid
                            WHERE jobap.id =' . $id;
                break;
        }
        if ($query != null) {
            $record = jsjobs::$_db->get_row($query);
            return $record;
        }
        return false;
    }
    function getMessagekey(){
        $key = 'emailtemplate';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
