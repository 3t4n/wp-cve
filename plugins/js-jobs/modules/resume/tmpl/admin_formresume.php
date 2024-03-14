<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
    include_once JSJOBS_PLUGIN_PATH.'includes/css/style_color.php';
wp_enqueue_style('jsjob-style', JSJOBS_PLUGIN_URL . 'includes/css/style.css');
wp_enqueue_style('jsjob-style-mobile', JSJOBS_PLUGIN_URL . 'includes/css/style_mobile.css',array(),'','(max-width: 480px)');;
wp_enqueue_style('jsjob-jobseeker-style', JSJOBS_PLUGIN_URL . 'includes/css/jobseekercp.css');
wp_enqueue_style('jsjob-employer-style', JSJOBS_PLUGIN_URL . 'includes/css/employercp.css');
if (is_rtl()) {
    wp_register_style('jsjob-style-rtl', JSJOBS_PLUGIN_URL . 'includes/css/stylertl.css');
    wp_enqueue_style('jsjob-style-rtl');
}
update_option( 'jsjobsresumeeditadmin', 1 );
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <span class="heading">
            <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_resume')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
            <span class="text-heading"><?php echo __('Resume', 'js-jobs') ?></span>
        </span>
    </span>
    <?php
    require_once(JSJOBS_PLUGIN_PATH . 'modules/resume/tmpl/addresume.inc.php');
    require_once(JSJOBS_PLUGIN_PATH . 'modules/resume/tmpl/addresume.php');
    ?>
    </div>
</div>
