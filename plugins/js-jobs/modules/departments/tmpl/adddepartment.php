<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
$msgkey = JSJOBSincluder::getJSModel('departments')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
        $msg = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
        ?>
        <div id="jsjobs-wrapper">
            <div class="page_heading"><?php echo esc_html($msg) .'&nbsp;'. __("Department", 'js-jobs'); ?></div>
            <form class="js-ticket-form" id="department_form" method="post" action="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'task'=>'savedepartment'))); ?>">
            <div class="js-col-md-12 js-form-wrapper">
                <div class="js-col-md-12 js-form-title"><?php echo __('Company', 'js-jobs'); ?>&nbsp;<font color="red">*</font></div>
                <div class="js-col-md-12 js-form-value"><?php echo wp_kses(JSJOBSformfield::select('companyid', JSJOBSincluder::getJSModel('company')->getUserCompaniesForCombo(), isset(jsjobs::$_data[0]->companyid) ? jsjobs::$_data[0]->companyid : '', '', array('class' => 'inputbox', 'data-validation' => 'required')), JSJOBS_ALLOWED_TAGS); ?></div>
            </div>
            <div class="js-col-md-12 js-form-wrapper">
                <div class="js-col-md-12 js-form-title"><?php echo __('Department Name', 'js-jobs'); ?>&nbsp;<font color="red">*</font></div>
                <div class="js-col-md-12 js-form-value"><?php echo wp_kses(JSJOBSformfield::text('name', isset(jsjobs::$_data[0]->name) ? jsjobs::$_data[0]->name : '', array('maxlength' => '70', 'class' => 'inputbox', 'data-validation' => 'required')), JSJOBS_ALLOWED_TAGS) ?></div>
            </div>
            <div class="js-col-md-12 js-form-wrapper">
                <div class="js-col-md-12 js-form-title"><?php echo __('Description', 'js-jobs'); ?></div>
                <div class="js-col-md-12 js-form-value"><?php wp_editor(isset(jsjobs::$_data[0]->description) ? jsjobs::$_data[0]->description : '', 'description', array('media_buttons' => false)); ?></div>
            </div>
            <?php echo wp_kses(JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : '' ), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('creditid', 0), JSJOBS_ALLOWED_TAGS); ?>
            <?php
            if (!isset(jsjobs::$_data[0]->id)) { // edit case form
                echo wp_kses(JSJOBSformfield::hidden('uid', JSJOBSincluder::getObjectClass('user')->uid()), JSJOBS_ALLOWED_TAGS);
            } ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('created', isset(jsjobs::$_data[0]->created) ? jsjobs::$_data[0]->created : date('Y-m-d H:i:s')), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('action', 'coverletter_savecoverletter'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('jsjobspageid', get_the_ID()), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('save-department')), JSJOBS_ALLOWED_TAGS); ?>
            <div class="js-col-md-12 js-form-button" id="save-button">			    	
                <?php
                    echo wp_kses(JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Department', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS);
                ?>
            </div>
        </form>
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>
</div>
