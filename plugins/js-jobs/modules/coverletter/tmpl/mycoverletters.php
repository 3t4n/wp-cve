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
            <?php echo __('My Cover Letters', 'js-jobs'); ?>
            <a class="additem" href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'addcoverletter'))); ?>"><?php echo __('Add New','js-jobs') .' '. __('Cover Letter', 'js-jobs'); ?></a>
        </div>
        <?php
        if (isset(jsjobs::$_data[0]) && !empty(jsjobs::$_data[0])) {
            $dateformat = jsjobs::$_configuration['date_format'];
            foreach (jsjobs::$_data[0] AS $cover) {
                ?>
                <div class="cover-letter-content-data">
                    <div class="data-left">
                        <div class="data-upper">
                            <span class="upper-app-title"> <?php echo esc_html($cover->title); ?> </span><span class="datecreated"><?php echo __('Created', 'js-jobs') .': ' . esc_html(date_i18n($dateformat, jsjobslib::jsjobs_strtotime($cover->created))); ?></span>
                        </div>
                    </div>
                    <div class="data-icons">
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'addcoverletter', 'jsjobsid'=>$cover->id))); ?>"><img class="icon-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-edit.png" alt="<?php echo __('Edit', 'js-jobs'); ?>" title="<?php echo __('Edit', 'js-jobs'); ?>"/></a>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'viewcoverletter', 'jsjobsid'=>$cover->id))); ?>"><img class="icon-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-view.png" alt="<?php echo __('View', 'js-jobs'); ?>" title="<?php echo __('View', 'js-jobs'); ?>"/></a>
                        <a href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'task'=>'removecoverletter', 'action'=>'jsjobtask', 'jsjobsid'=>$cover->id,'jsjobspageid'=>jsjobs::getPageid())),'delete-coverletter')); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete','js-jobs').' ?'; ?>');"><img class="icon-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-force-delete.png" alt="<?php echo __('Delete', 'js-jobs'); ?>" title="<?php echo __('Delete', 'js-jobs'); ?>"/></a>
                    </div>
                </div>
                <?php
            }
            if (jsjobs::$_data[1]) {
                echo '<div id="jsjobs-pagination">' . wp_kses_post(jsjobs::$_data[1]) . '</div>';
            }
        } else {
            $msg = __('No record found','js-jobs');
            $linkcoverletter[] = array(
                        'link' => jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'addcoverletter')),
                        'text' => __('Add New','js-jobs') .' '. __('Cover Letter', 'js-jobs')
                    );
            JSJOBSlayout::getNoRecordFound($msg,$linkcoverletter);
        }
        ?>
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
} 
?>
</div>
