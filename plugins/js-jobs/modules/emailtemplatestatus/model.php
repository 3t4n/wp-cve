<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSemailtemplatestatusModel {

    function sendEmailModel($id, $actionfor) {
        if (empty($id))
            return false;
        if (!is_numeric($actionfor))
            return false;

        $row = JSJOBSincluder::getJSTable('emailtemplateconfig');
        $value = 1;

        switch ($actionfor) {
            case 1: //updation for employer send email
                $row->update(array('id' => $id, 'employer' => $value));
                break;
            case 2: //updation for jobseeker send email
                $row->update(array('id' => $id, 'jobseeker' => $value));

                break;
            case 3: //updation for admin send email
                $row->update(array('id' => $id, 'admin' => $value));
                break;
            case 4: //updation for jobseeker visitor send email
                $row->update(array('id' => $id, 'jobseeker_visitor' => $value));
                break;
            case 5: //updation for employer visitor send email
                $row->update(array('id' => $id, 'employer_visitor' => $value));
        }
    }

    function noSendEmailModel($id, $actionfor) {
        if (empty($id))
            return false;
        if (!is_numeric($actionfor))
            return false;

        $row = JSJOBSincluder::getJSTable('emailtemplateconfig');
        $value = 0;

        switch ($actionfor) {
            case 1: //updation for employer not send email
                $row->update(array('id' => $id, 'employer' => $value));
                break;
            case 2: //updation for jobseeker not send email
                $row->update(array('id' => $id, 'jobseeker' => $value));
                break;
            case 3: //updation for admin not send email
                $row->update(array('id' => $id, 'admin' => $value));
                break;
            case 4: //updation for jobseeker visitor not send email
                $row->update(array('id' => $id, 'jobseeker_visitor' => $value));
                break;
            case 5: //updation for employer visitor not send email
                $row->update(array('id' => $id, 'employer_visitor' => $value));
        }
    }

    function getLanguageForEmail($keyword) {
        switch ($keyword) {
            case 'add_new_company':
                $lanng = __('Add','js-jobs'). __('new','js-jobs').__('company', 'js-jobs');
                return $lanng;
                break;
            case 'delete_company':
                $lanng = __('Delete','js-jobs') .' '. __('company', 'js-jobs');
                return $lanng;
                break;
            case 'company_status':
                $lanng = __('Company','js-jobs') .' '. __('status', 'js-jobs');
                return $lanng;
                break;
            case 'job_status':
                $lanng = __('Job','js-jobs') .' '. __('Status', 'js-jobs');
                return $lanng;
                break;
            case 'add_new_job':
                $lanng = __('Add','js-jobs') .' '. __('new','js-jobs') .' '. __('job', 'js-jobs');
                return $lanng;
                break;
            case 'add_new_resume':
                $lanng = __('Add','js-jobs') .' '. __('new','js-jobs') .' '. __('resume', 'js-jobs');
                return $lanng;
                break;
            case 'resume_status':
                $lanng = __('Resume','js-jobs') .' '. __('status', 'js-jobs');
                return $lanng;
                break;
            case 'employer_purchase_credits_pack':
                $lanng = __('Employer','js-jobs') .' '. __('buy credits pack', 'js-jobs');
                return $lanng;
                break;
            case 'jobseeker_package_expire':
                $lanng = __('Job seeker','js-jobs') .' '. __('expire package', 'js-jobs');
                return $lanng;
                break;
            case 'jobseeker_purchase_credits_pack':
                $lanng = __('Job seeker','js-jobs') .' '. __('buy credits pack', 'js-jobs');
                return $lanng;
                break;
            case 'employer_package_expire':
                $lanng = __('Employer','js-jobs') .' '. __('expire package', 'js-jobs');
                return $lanng;
                break;
            case 'jobapply_employer':
                $lanng = __('Employer','js-jobs') .' '. __('job apply', 'js-jobs');
                return $lanng;
                break;
            case 'jobapply_jobseeker':
                $lanng = __('Job seeker','js-jobs') .' '. __('job apply', 'js-jobs');
                return $lanng;
                break;
            case 'delete_job':
                $lanng = __('Delete','js-jobs') .' '. __('job', 'js-jobs');
                return $lanng;
                break;
            case 'add_new_employer':
                $lanng = __('Add','js-jobs') .' '. __('New','js-jobs') .' '. __('Employer', 'js-jobs');
                return $lanng;
                break;
            case 'add_new_jobseeker':
                $lanng = __('Add','js-jobs') .' '. __('New','js-jobs') .' '. __('Job Seeker', 'js-jobs');
                return $lanng;
                break;
            case 'add_new_resume_visitor':
                $lanng = __('Add','js-jobs') .' '. __('new','js-jobs') .' '. __('resume ','js-jobs') .' '. __('by visitor', 'js-jobs');
                return $lanng;
                break;
            case 'add_new_job_visitor':
                $lanng = __('Add','js-jobs') .' '. __('new','js-jobs') .' '. __('job','js-jobs') .' '. __('by visitor', 'js-jobs');
                return $lanng;
                break;
            case 'resume-delete':
                $lanng = __('Delete','js-jobs') .' '. __('resume', 'js-jobs');
                return $lanng;
                break;
            case 'jobapply_jobapply':
                $lanng = __('job apply', 'js-jobs');
                return $lanng;
                break;
            case 'applied-resume_status':
                $lanng = __('Applied resume status change', 'js-jobs');
                return $lanng;
                break;
        }
    }

    function getEmailTemplateStatusData() {
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_emailtemplates_config";
        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        $newdata = array();
        foreach (jsjobs::$_data[0] as $data) {
            $newdata[$data->emailfor] = array(
                'tempid' => $data->id,
                'tempname' => $data->emailfor,
                'admin' => $data->admin,
                'employer' => $data->employer,
                'jobseeker' => $data->jobseeker,
                'jobseeker_vis' => $data->jobseeker_visitor,
                'employer_vis' => $data->employer_visitor
            );
        }
        jsjobs::$_data[0] = $newdata;
    }

    function getEmailTemplateStatus($template_name) {
        $query = "SELECT emc.admin,emc.employer,emc.jobseeker,emc.employer_visitor,emc.jobseeker_visitor
                FROM " . jsjobs::$_db->prefix . "js_job_emailtemplates_config AS emc
                where  emc.emailfor = '" . $template_name . "'";
        $templatestatus = jsjobsdb::get_row($query);
        return $templatestatus;
    }
    function getMessagekey(){
        $key = 'emailtemplatestatus';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
