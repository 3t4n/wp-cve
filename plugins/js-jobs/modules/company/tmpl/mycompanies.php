<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
$msgkey = JSJOBSincluder::getJSModel('company')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $config_array = jsjobs::$_data['config'];
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading">
            <?php echo __('My Companies', 'js-jobs'); ?>
            <a class="additem" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'addcompany')),"formcompany")); ?>"><?php echo __('Add New','js-jobs') .'&nbsp;'. __('Company', 'js-jobs'); ?></a>
        </div>
        <?php
        if (isset(jsjobs::$_data[0]) && !empty(jsjobs::$_data[0])) {
            foreach (jsjobs::$_data[0] AS $company) {
                if ($company->logofilename != "") {
                    $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                    $wpdir = wp_upload_dir();
                    $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $company->id . '/logo/' . $company->logofilename;
                } else {
                    $path = JSJOBS_PLUGIN_URL . '/includes/images/default_logo.png';
                }
                ?>
                <div class="company-wrapper">
                    <div class="company-upper-wrapper object_<?php echo esc_attr($company->id); ?>" data-boxid="company_<?php echo esc_attr($company->id); ?>">
                        <div class="company-img">
                            <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$company->aliasid))); ?>">
                                <img src="<?php echo esc_url($path); ?>">
                            </a>
                        </div>
                        <div class="company-detail">
                            <div class="company-detail-upper">
                                <div class="company-detail-upper-left  item-title"> 
                                <?php if ($config_array['comp_name']) { ?>
                                            <span class="company-title">
                                                <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$company->aliasid))); ?>">
                                                    <?php echo esc_html($company->name); ?>
                                                 </a>
                                            </span><?php 
                                        } 
                                    $dateformat = jsjobs::$_configuration['date_format'];
                                    $curdate = date_i18n($dateformat);
                                    ?>
                                </div>
                                <div class="company-detail-upper-right">
                                    <span class="company-date"><?php echo __('Created', 'js-jobs') . ':&nbsp;' . esc_html(date_i18n($dateformat, jsjobslib::jsjobs_strtotime($company->created))); ?></span>
                                </div>
                            </div>
                            <div class="company-detail-lower">
                                <div class="js-col-xs-12 js-col-sm-6 js-col-md-4 company-detail-lower-left">
                                    <span class="js-text">
                                        <?php 
                                        if(isset(jsjobs::$_data['fields']['category'])){
                                            echo esc_html(__(jsjobs::$_data['fields']['category'], 'js-jobs')) . ':&nbsp;'; 
                                        }else{
                                            echo __('category', 'js-jobs') . ':&nbsp;'; 
                                        } ?>
                                    </span>
                                    <span class="js-value">
                                        <?php echo esc_html(__($company->cat_title,'js-jobs')); ?>
                                    </span>
                                </div>
                                <div class="js-col-xs-12 js-col-sm-6 js-col-md-4 company-detail-lower-left">
                                    <span class="js-text"><?php echo __('Status', 'js-jobs') . ':&nbsp;'; ?></span>                                    
                                    <?php
                                    $color = ($company->status == 1) ? "green" : "red";
                                    if ($company->status == 1) {
                                        $statusCheck = __('Approved', 'js-jobs');
                                    } elseif ($company->status == 0) {
                                        $statusCheck = __('Waiting for approval', 'js-jobs');
                                    } else {
                                        $statusCheck = __('Rejected', 'js-jobs');
                                    }
                                    ?>
                                    <span class="js-value get-status<?php echo esc_attr($color); ?>"><?php echo esc_html($statusCheck); ?></span>
                                </div>
                                <?php
                                $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(1, 1);
                                foreach ($customfields as $field) {
                                    echo wp_kses(JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 8, $company->params), JSJOBS_ALLOWED_TAGS);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="company-lower-wrapper">
                        <div class="company-lower-wrapper-left"><?php 
                            if($config_array['comp_city']) { ?>
                                <span class="company-address"><img id=location-img  src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/location.png"><?php echo esc_html($company->location); ?></span>  <?php
                            } ?>
                        </div>
                        <div class="company-lower-wrapper-right">
                            <?php
                                if($company->status == 1){ ?>
                                    <div class="button edit-button">
                                        <a href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'addcompany', 'jsjobsid'=>$company->id)),"formcompany")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-edit.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                                    </div>                                
                                    <div class="button search-button">
                                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$company->aliasid))); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-view.png" title="<?php echo __('View', 'js-jobs'); ?>"></a>
                                    </div>
                                    <div class="button delete-button">
                                        <a href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'task'=>'remove', 'jsjobs-cb[]'=>$company->id, 'action'=>'jsjobtask','jsjobspageid'=>jsjobs::getPageid())),'delete-company')); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete','js-jobs').' ?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-force-delete.png" title="<?php echo __('Delete', 'js-jobs'); ?>"></a>
                                    </div>
                                <?php
                                }elseif($company->status == 0) { ?>
                                    <div class="big-lower-data-text pending"><img id="pending-img"  src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pending-corner.png"/><span><?php echo __('Waiting for approval', 'js-jobs'); ?></span></div>
                                <?php
                                }elseif($company->status == -1){ ?>
                                    <div class="big-lower-data-text reject"><img id="pending-img"  src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/reject-cornor.png"/><span><?php echo __('Rejected', 'js-jobs'); ?></span></div>
                                <?php
                                }
                                ?>
                        </div>
                    </div>
                </div> 
                <?php
            }
            if (jsjobs::$_data[1]) {
                echo '<div id="jsjobs-pagination">' . wp_kses_post(jsjobs::$_data[1]) . '</div>';
            }
        } else {
            $msg = __('No record found','js-jobs');
            $linkcompany[] = array(
                        'link' => wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'addcompany')),"formcompany"),
                        'text' => __('Add New','js-jobs') .' '. __('Company', 'js-jobs')
                    );
            JSJOBSlayout::getNoRecordFound($msg,$linkcompany);
        }
        ?>
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
} 
?>
</div>
