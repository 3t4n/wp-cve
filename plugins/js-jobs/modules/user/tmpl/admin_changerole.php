<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$role = jsjobs::$_data[0];
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_user')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Change Role', 'js-jobs'); ?>
    </span>
    <form id="jsjobs-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_user&task=saveuserrole")); ?>">
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Name', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-5 js-col-md-5 no-padding"><?php echo esc_html($role->first_name) . ' ' . esc_html($role->last_name); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Username', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-5 js-col-md-5 no-padding"><?php echo esc_html($role->user_login); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Group', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-5 js-col-md-5 no-padding"><?php echo wp_kses(JSJOBSincluder::getJSModel('user')->getWPRoleNameById($role->wpuid), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('ID', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-5 js-col-md-5 no-padding"><?php echo esc_html($role->id); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Role', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-5 js-col-md-5 no-padding"><?php echo wp_kses(JSJOBSformfield::select('roleid', JSJOBSincluder::getJSModel('common')->getRolesForCombo(), isset(jsjobs::$_data[0]->roleid) ? jsjobs::$_data[0]->roleid : '', '', array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <?php
        if ($role) {
            if (($role->dated == '0000-00-00 00:00:00') || ($role->dated == ''))
                $curdate = date_i18n('Y-m-d H:i:s');
            else
                $curdate = $role->dated;
        }else {
            $curdate = date_i18n('Y-m-d H:i:s');
        }
        ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('id', $role->id), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('created', $curdate), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('action', 'user_saveuserrole'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('save-userrole')), JSJOBS_ALLOWED_TAGS); ?>
        <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
            <a id="form-cancel-button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_user'),"user")); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
            <?php echo wp_kses(JSJOBSformfield::submitbutton('save', __('Change Role', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        </div>
    </form>
</div>
</div>
