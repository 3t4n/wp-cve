<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSajax {

    function __construct() {
        add_action("wp_ajax_jsjobs_ajax", array($this, "ajaxhandler")); // when user is login
        add_action("wp_ajax_nopriv_jsjobs_ajax", array($this, "ajaxhandler")); // when user is not login
    }

    function ajaxhandler() {
        $fucntin_allowed = array('DataForDepandantFieldResume', 'DataForDepandantField', 'getQuickViewByJobId', 'getApplyNowByJobid', 'jobapply', 'jobapplyjobmanager', 'deletecompanylogo', 'deleteresumelogo', 'getuserlistajax',  'getFieldsForComboByFieldFor', 'getSectionToFillValues', 'getUserIdByCompanyid', 'getOptionsForFieldEdit', 'listdepartments', 'saveTokenInputTag',  'getsubcategorypopup', 'updateJobApplyResumeStatus', 'getResumeCommentSection', 'storeResumeComments', 'setResumeRatting', 'getResumeDetail', 'getEmailFields', 'jobapplyid', 'sendEmailToJobSeeker', 'setJobApplyRating', 'getResumeDetailJobManager', 'getEmailFieldsJobManager', 'hideTemplateBanner', 'getListTranslations', 'validateandshowdownloadfilename', 'getlanguagetranslation', 'getPacakageListByUid', 'canceljobapplyasvisitor', 'visitorapplyjob', 'removeResumeFileById', 'getResumeSectionAjax', 'deleteResumeSectionAjax', 'getOptionsForEditSlug', 'getAllRoleLessUsersAjax', 'getNextJobs', 'getNextTemplateJobs','savetokeninputcity', 'sendmailtofriend', 'getJobApplyDetailByid', 'setListStyleSession','sendmailtofriendJobManager', 'getResumeCommentSectionJobManager' ,'installPluginFromAjax','activatePluginFromAjax' );
        $task = JSJOBSrequest::getVar('task');
        if($task != '' && in_array($task, $fucntin_allowed)){
            $module = JSJOBSrequest::getVar('jsjobsme');
            $result = JSJOBSincluder::getJSModel($module)->$task();
            echo wp_kses($result, JSJOBS_ALLOWED_TAGS);
            die();
        }else{
            die('Not Allowed!');
        }

    }



}

$jsajax = new JSJOBSajax();
?>
