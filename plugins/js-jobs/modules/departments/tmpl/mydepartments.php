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
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading">
            <?php echo __('My Departments', 'js-jobs'); ?>
            <a class="additem" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'adddepartment')),"save-department")); ?>"><?php echo __('Add New','js-jobs') .'&nbsp;'. __('Department', 'js-jobs'); ?></a>
        </div>
        <?php
        if (isset(jsjobs::$_data[0]) && !empty(jsjobs::$_data[0])) {
            $dateformat = jsjobs::$_configuration['date_format'];
            foreach (jsjobs::$_data[0] AS $dept) {
                ?>
                <div class="department-content-data">
                    <div class="data-left">
                        <div class="data-upper">
                            <span class="upper-app-title"> <?php echo esc_html($dept->name); ?> </span><?php echo __('Created', 'js-jobs') . ':&nbsp;' . esc_html(date_i18n($dateformat, jsjobslib::jsjobs_strtotime($dept->created))); ?>
                        </div>
                        <div class="data-lower">
                            <span class="lower-text1">
                                <span class="title"><?php echo __('Company', 'js-jobs'); ?></span>:&nbsp;<a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$dept->companyid))); ?>"><?php echo esc_html($dept->companyname); ?></a>
                            </span>
                            <span class="lower-text1">
                                <span class="title">
                                    <?php
                                    if($dept->status == 1){
                                        $color = "green";                        
                                        $statusCheck = __('Approved','js-jobs');
                                    }elseif($dept->status == 0){
                                        $color = "orange";                        
                                        $statusCheck = __('Pending','js-jobs');
                                    }else{
                                        $color = "red";                        
                                        $statusCheck = __('Rejected','js-jobs');
                                    }
                                    echo __('Status', 'js-jobs').': ';
                                    ?>
                                </span>
                                    <span class="get-status <?php echo esc_attr($color); ?>"><?php echo esc_html($statusCheck); ?></span>
                            </span>
                        </div>
                    </div>
                    <div class="data-icons">
                        <a href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'adddepartment', 'jsjobsid'=>$dept->id)),"save-department")); ?>"><img class="icon-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-edit.png" alt="<?php echo __('Edit', 'js-jobs'); ?>" title="<?php echo __('Edit', 'js-jobs'); ?>"/></a>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'viewdepartment', 'jsjobsid'=>$dept->id))); ?>"><img class="icon-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-view.png" alt="<?php echo __('View', 'js-jobs'); ?>" title="<?php echo __('View', 'js-jobs'); ?>"/></a>
                        <a href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobspageid'=>jsjobs::getPageid(),'jsjobsme'=>'departments', 'task'=>'remove', 'action'=>'jsjobtask', 'jsjobs-cb[]'=>$dept->id)),'delete-department')); ?>"onclick="return confirmdelete('<?php echo __('Are you sure to delete','js-jobs').' ?'; ?>');"><img class="icon-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-force-delete.png" alt="<?php echo __('Delete', 'js-jobs'); ?>" title="<?php echo __('Delete', 'js-jobs'); ?>"/></a>
                    </div>
                </div>
                <?php
            }
            if (jsjobs::$_data[1]) {
                echo '<div id="jsjobs-pagination">' . wp_kses_post(jsjobs::$_data[1]) . '</div>';
            }
        } else {
            $msg = __('No record found','js-jobs');
            $link[] = array(
                        'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'adddepartment')),"save-department"),
                        'text' => __('Add New','js-jobs') .' '. __('Department', 'js-jobs')
                    );
            JSJOBSlayout::getNoRecordFound();
        }
        ?>
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>
</div>
