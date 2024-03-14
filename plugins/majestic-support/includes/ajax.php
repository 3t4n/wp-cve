<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_ajax {

    function __construct() {
        add_action("wp_ajax_mjsupport_ajax", array($this, "MJTC_ajaxhandler")); // when user is login
        add_action("wp_ajax_nopriv_mjsupport_ajax", array($this, "MJTC_ajaxhandler")); // when user is not login
    }

    function MJTC_ajaxhandler() {
        $functions_allowed = array('DataForDepandantField','subscribeForNotifications','unsubscribeFromNotifications','getDownloadById','getAllDownloads','sendTestEmail','getuserlistajax','getmultiformlistajax','getFieldsForComboByFieldFor','getSectionToFillValues','getListTranslations','validateandshowdownloadfilename','getlanguagetranslation','updateUserDevice','checkParentType','checkChildType','makeParentOfType','getTypeForByParentId','getusersearchstaffreportajax','getusersearchuserreportajax','getusersearchajax','saveuserprofileajax','getHelpTopicByDepartment','getpremadeajax','getPremadeByDepartment','getTicketsForMerging','getLatestReplyForMerging','getReplyDataByID','getTimeByReplyID','getTimeByNoteID','readEmailsAjax','getOptionsForFieldEdit','storePrivateCredentials','getFormForPrivteCredentials', 'getPrivateCredentials', 'removePrivateCredential','getWcOrderProductsAjax','markUnmarkTicketNonPremiumAjax','linkTicketPaidSupportAjax','getEDDOrderProductsAjax','getEDDProductlicensesAjax','uploadStaffImage','installPluginFromAjax','activatePluginFromAjax','listEmailTemplate','deleteEmailTemplate','getDefaultEmailTemplate','getHtmlForMoreEmail','getHtmlForMoreConditions','getOperatorsByTitleForCombobox','getValuesByTitleForCombobox','getChildForVisibleCombobox','deleteSupportCustomImage','reviewBoxAction','MJTC_isFieldRequired','getOptionsForEditSlug','checkSmartReply','getSmartReply','getTicketCloseReasonsForPopup','downloadandinstalladdonfromAjax','deleteCategoryLogo','deleteAgentLogo');
        $task = MJTC_request::MJTC_getVar('task');
        if($task != '' && in_array($task, $functions_allowed)){
            $module = MJTC_request::MJTC_getVar('mjsmod');
            $result = MJTC_includer::MJTC_getModel($module)->$task();
            echo wp_kses($result, MJTC_ALLOWED_TAGS);
            die();
        }else{
            die('Not Allowed!');
        }
    }

}

$MJTC_ajax = new MJTC_ajax();
?>
