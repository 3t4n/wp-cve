<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
    $msgkey = JSJOBSincluder::getJSModel('common')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    JSJOBSbreadcrumbs::getBreadcrumbs();
    include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $module = JSJOBSrequest::getVar('jsjobsme');
    $layout = JSJOBSrequest::getVar('jsjobslt');
    $nonce = JSJOBSrequest::getVar('_wpnonce');
    $currentuser = get_userdata(get_current_user_id());
    $uid = $currentuser->ID;

    $title = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('title');
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo esc_html(__( $title , 'js-jobs')); ?></div>
        <form class="js-ticket-form" id="coverletter_form" method="post" action="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'common', 'task'=>'savenewinjsjobs')),"new-in-jsjobs")); ?>">
            <div class="js-form-wrapper-newlogin">
                <div class="js-imagearea">
                    <div class="js-img">
                        <img id="jsjobslogin" src="<?php echo JSJOBS_PLUGIN_URL;?>/includes/images/man-icon.png">
                    </div>
                </div>
                <div class="js-dataarea">
                    <div class="js-col-md-12 js-form-heading"><?php echo __('Are you new in', 'js-jobs').' '.__( $title,'js-jobs'); ?></div>
                    <div class="js-col-md-12 js-form-title"><?php echo __('Please select your role', 'js-jobs'); ?>&nbsp;<font color="red">*</font></div>
                    <div class="js-col-md-12 js-form-value">
                        <?php echo wp_kses(JSJOBSformfield::select('roleid', JSJOBSincluder::getJSModel('common')->getRolesForCombo(''), '', __('Select Role'), array('class' => 'inputbox', 'data-validation' => 'required')), JSJOBS_ALLOWED_TAGS); ?>
                    </div>
                    <?php echo wp_kses(JSJOBSformfield::hidden('desired_module', $module), JSJOBS_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSJOBSformfield::hidden('desired_layout', $layout), JSJOBS_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSJOBSformfield::hidden('desired_nonce', $nonce), JSJOBS_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSJOBSformfield::hidden('id', ''), JSJOBS_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSJOBSformfield::hidden('uid', $uid), JSJOBS_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSJOBSformfield::hidden('action', 'common_savenewinjsjobs'), JSJOBS_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSJOBSformfield::hidden('jsjobspageid', get_the_ID()), JSJOBS_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSJOBSformfield::submitbutton('save', __('Submit', 'js-jobs'), array('class' => 'button jsjobs-newsubmit')), JSJOBS_ALLOWED_TAGS); ?>
                </div>

            </div>
        </form>
    </div>
<?php
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>
</div>
