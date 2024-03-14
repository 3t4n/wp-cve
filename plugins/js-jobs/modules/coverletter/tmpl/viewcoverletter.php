<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
$msgkey = JSJOBSincluder::getJSModel('coverletter')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) { ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading">
            <?php echo __('Cover Letter', 'js-jobs'); ?>
        </div>
        <div id="cover-letter-wrapper-title">
            <span class="cover-letter-title" ><?php echo __('Name', 'js-jobs').': '; ?></span><span class="wrapper-text" ><?php echo esc_html(jsjobs::$_data[0]->title); ?></span>
        </div>
        <div id="cover-letter-wrapper-disc">
            <span class="cover-letter-title" ><?php echo __('Description', 'js-jobs').': '; ?></span><span class="wrapper-text1" ><?php echo wp_kses(jsjobs::$_data[0]->description, JSJOBS_ALLOWED_TAGS); ?></span>
        </div>    
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>
</div>
