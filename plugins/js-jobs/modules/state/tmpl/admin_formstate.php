<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script >
    jQuery(document).ready(function ($) {
        $.validate();
    });
</script>
<div id="jsjobsadmin-wrapper">
    <div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_state&countryid='.get_option( 'jsjobs_countryid_for_state'))); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php
        $heading = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
        echo esc_html($heading) . ' ' . __('State', 'js-jobs');
        ?>
    </span>

    <form id="jsjobs-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_state&task=savestate")); ?>">
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Name', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('name', isset(jsjobs::$_data[0]->name) ? __(jsjobs::$_data[0]->name,'js-jobs') : '', array('class' => 'inputbox one', 'data-validation' => 'required')), JSJOBS_ALLOWED_TAGS) ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Published', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::radiobutton('enabled', array('1' => __('Yes', 'js-jobs'), '0' => __('No', 'js-jobs')), isset(jsjobs::$_data[0]->enabled) ? jsjobs::$_data[0]->enabled : 1, array('class' => 'radiobutton')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <?php echo wp_kses(JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('action', 'state_savestate'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('save-state')), JSJOBS_ALLOWED_TAGS); ?>
<?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
            <a id="form-cancel-button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_state&countryid='.get_option( 'jsjobs_countryid_for_state')),"State")); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
<?php echo wp_kses(JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('State', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        </div>
    </form>
</div>
</div>
