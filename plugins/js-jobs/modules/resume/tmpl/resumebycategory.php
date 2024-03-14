<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
$msgkey = JSJOBSincluder::getJSModel('resume')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    ?>
    <div id="jsjob-popup-background"></div>
    <div id="jsjobs-listpopup">
        <span class="popup-title"><span class="title"></span><img id="popup_cross" alt="popup cross" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/popup-close.png"></span>
        <div class="jsjob-contentarea"></div>
    </div>
    <div id="jsjobs-wrapper" class="jsjobs-by-categories-layout-wrap">
        <div class="page_heading"><?php echo __('Resumes By Categories', 'js-jobs'); ?></div>
        <?php
        $number =  jsjobs::$_data['config']['categories_colsperrow'];
        if ($number < 1 || $number > 100) {
            $number = 3; // by default set to 3
        }
        $width = 100 / $number;
        $count = 0;
        if (isset(jsjobs::$_data[0]) && !empty(jsjobs::$_data[0])) {
            foreach (jsjobs::$_data[0] AS $jobsByCategories) {
                if (($count % $number) == 0) {
                    if ($count == 0)
                        echo '<div class="category-row-wrapper">';
                    else
                        echo '</div><div class="category-row-wrapper">';
                }
                ?>
                <div class="category-wrapper" style="width:<?php echo esc_attr($width); ?>%;" data-id="<?php echo esc_attr($jobsByCategories->aliasid); ?>">
                    <a href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumes', 'category'=>$jobsByCategories->aliasid, 'jsjobspageid'=>jsjobs::getPageid())),'resume')); ?>">
                        <div class="jobs-by-categories-wrapper">
                            <span class="title"><?php echo esc_html(__($jobsByCategories->cat_title,'js-jobs')); ?></span>
                            <?php if(jsjobs::$_data['config']['categories_numberofresumes'] == 1){ ?>
                                <span class="totat-jobs"><?php echo '(' . esc_html($jobsByCategories->totaljobs + $jobsByCategories->total_sub_jobs) . ')'; ?></span>
                            <?php } ?>
                        </div> 
                    </a>
                    <?php 
                        $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('category');
                        $subcategory_limit = $config_array['subcategory_limit'];
                        if (!empty($jobsByCategories->subcat)) {
                            $html = '<div class="jsjobs-subcategory-wrapper" style="display:none;">';
                            $subcount = 0;
                            foreach ($jobsByCategories->subcat AS $cat) {
                                $link = wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumes', 'category'=>$cat->aliasid, 'jsjobspageid'=>jsjobs::getPageid())),'resume');
                                $html .= '  <div class="category-wrapper" style="width:100%;">
                                                <a href="' . $link . '">
                                                <div class="jobs-by-categories-wrapper">
                                                    <span class="title">' . __($cat->cat_title,'js-jobs') . '</span>';
                                if($config_array['categories_numberofresumes'] == 1){
                                    $html .= '<span class="totat-jobs">(' . $cat->totaljobs . ')</span>';
                                }   
                                $html .=    '</div> 
                                            </a>
                                        </div>';
                                $subcount++;
                            }
                            if ($subcount >= $subcategory_limit) {
                                $html .= '  <div class="showmore-wrapper">
                                                <a href="#" class="showmorebutton" onclick="getPopupAjax(\'' . $jobsByCategories->aliasid . '\', \'' . $jobsByCategories->cat_title . '\');">' . __('Show More', 'js-jobs') . '</a>
                                            </div>';
                            }
                            $html .= '</div>';
                            echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
                        }
                    ?>
                </div>
                <?php
                $count++;
            }
            echo '</div>';
        }else {
            JSJOBSlayout::getNoRecordFound();
        }
        ?>
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
} ?>
</div>
