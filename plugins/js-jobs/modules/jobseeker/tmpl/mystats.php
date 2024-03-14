<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
wp_enqueue_script('jsjob-res-tables', JSJOBS_PLUGIN_URL . 'includes/js/responsivetable.js');

$msgkey = JSJOBSincluder::getJSModel('jobseeker')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __('Stats', 'js-jobs'); ?></div>
        <?php if(isset(jsjobs::$_data[0]) AND !empty(jsjobs::$_data[0])){ ?>        
        
        <div class="jsjobs-bottom-wrapper">
            <div class="js-topstats">
                <div class="js-mainwrp js-col-xs-12 js-col-md-4">
                    <div class="resume tprow">
                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/stats/total-resume.png">
                        <div class="js-headtext"><?php echo __('Total resume','js-jobs'); ?></div>
                        <div class="js-count">(<?php echo esc_html(jsjobs::$_data[0]['totalresume']); ?>)</div>
                    </div>
                </div>
                <div class="js-mainwrp js-col-xs-12 js-col-md-4">
                    <div class="coverletter tprow">
                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/stats/total-coverletter.png">
                        <div class="js-headtext"><?php echo __('Cover letter','js-jobs'); ?></div>
                        <div class="js-count">(<?php echo esc_html(jsjobs::$_data[0]['totalcoverletter']); ?>)</div>
                    </div>
                </div>
                <div class="js-mainwrp js-col-xs-12 js-col-md-4">
                    <div class="appliedjobs tprow">
                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/stats/applied-jobs.png">
                        <div class="js-headtext"><?php echo __('Applied jobs','js-jobs'); ?></div>
                        <div class="js-count">(<?php echo esc_html(jsjobs::$_data[0]['totalapplied']); ?>)</div>
                    </div>
                </div>
            </div>
            <table id="js-table" class="jsjobs-first-table">
                <thead class="stats">
                    <tr>
                        <th class="title"><img class="table-image" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/stats/resume-stats.png"><?php echo __('Resume','js-jobs');?></th>
                        <th class="publish center"> <?php echo __('Publish','js-jobs');?> </th>
                        <th class="expired center"> <?php echo __('Expired','js-jobs');?> </th>
                    </tr>
                </thead>
                <tbody class="stats">
                </tbody>
            </table>
        </div>
        <?php 
    } else{
        $msg = __('No record found','js-jobs');
        JSJOBSlayout::getNoRecordFound($msg);
    }
        ?>
        
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
} ?>
</div>
