<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
$msgkey = JSJOBSincluder::getJSModel('job')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __('Jobs By Types', 'js-jobs'); ?></div>
        <?php
        $number = jsjobs::$_data['config']['jobtype_per_row'];
        if ($number < 1 || $number > 100) {
            $number = 3; // by default set to 3
        }
        $width = 100 / $number;
        $count = 0;
        if (isset(jsjobs::$_data[0]) && !empty(jsjobs::$_data[0])) {
            foreach (jsjobs::$_data[0] AS $jobsBytype) {
                if (($count % $number) == 0) {
                    if ($count == 0)
                        echo '<div class="type-row-wrapper">';
                    else
                        echo '</div><div class="type-row-wrapper">';
                }
                ?>
                <div class="type-wrapper" style="width:<?php echo esc_attr($width); ?>%;">
                    <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobs', 'jobtype'=>$jobsBytype->alias))); ?>">
                        <div class="jobs-by-type-wrapper">
                            <span class="title"><?php echo esc_html(__($jobsBytype->title,'js-jobs')); ?></span>
                        <?php if(jsjobs::$_data['config']['jobtype_numberofjobs']){ ?>
                            <span class="totat-jobs"><?php echo esc_html($jobsBytype->totaljobs); ?></span>
                        <?php } ?>
                        </div> 
                    </a>
                </div>
                <?php
                $count++;
            }
            echo '</div>';
        }
        else {
            JSJOBSlayout::getNoRecordFound();
            ?><?php }
        ?>
    </div>	
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>
</div>
