<?php
    wp_enqueue_script('jsjobs-tokeninput', JSJOBS_PLUGIN_URL . 'includes/js/jquery.tokeninput.js');
    wp_enqueue_script('jsjobs-tokeninput', JSJOBS_PLUGIN_URL . 'includes/js/chosen/chosen.jquery.min.js');
    echo  wp_enqueue_style('jsjobs-choose', JSJOBS_PLUGIN_URL . 'includes/js/chosen/chosen.min.css');
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery('.jsjobs-userselect').chosen({
        placeholder_text_single: "Select User",
        no_results_text: "Oops, nothing found!"
      });
      jQuery("#jobseeker_list").val(0).trigger('chosen:updated')  
      jQuery("#employer_list").val(0).trigger('chosen:updated')  
    });
    function refreshList() {
        location.reload();
    }
    function showHideUserForm() {
        var sampledata = jQuery('#sampledata').val();
        if (sampledata == 0) {
            jQuery('.jsjobs-show-sample-data-form').addClass('jsjobs-hide-sample-data-form');
        } else {
            jQuery('.jsjobs-show-sample-data-form').removeClass('jsjobs-hide-sample-data-form');
        }
    }
    function checkForEmpAndJSId() {
        var jobseeker_id = jQuery('#jobseeker_id').val();
        var employer_id = jQuery('#employer_id').val();
        if (employer_id != 0 && employer_id == jobseeker_id) {
            alert('Jobseeker And Employer Cannot Be Same');
        } else {
            document.getElementById('jsjobs-form-ins').submit();
        }
    }
    function setValueForJobSeeker() {
        var option = jQuery('#jobseeker_list').val();
        var myOption = option.split("-");
        var id =  Number(myOption[myOption.length - 1]);
        jQuery("#jobseeker_id").val(id);
    }
    function setValueForEmployer() {
        var option = jQuery('#employer_list').val();
        var myOption = option.split("-");
        var id =  Number(myOption[myOption.length - 1]);
        jQuery("#employer_id").val(id);
    }
</script>
<?php $searchjobtag = array((object) array('id' => 1, 'text' => __('Top left', 'js-jobs'))
                    , (object) array('id' => 2, 'text' => __('Top right', 'js-jobs'))
                    , (object) array('id' => 3, 'text' => __('Middle left', 'js-jobs'))
                    , (object) array('id' => 4, 'text' => __('Middle right', 'js-jobs'))
                    , (object) array('id' => 5, 'text' => __('Bottom left', 'js-jobs'))
                    , (object) array('id' => 6, 'text' => __('Bottom right', 'js-jobs')));
$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'js-jobs'))
                    , (object) array('id' => 0, 'text' => __('No', 'js-jobs')));
wp_enqueue_script('jsjob-commonjs', JSJOBS_PLUGIN_URL . 'includes/js/radio.js');
if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jsjobsadmin-wrapper" class="post-installation">
    <div class="js-admin-title-installtion">
        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/setting-icon.png" />
        <?php echo __('JS Jobs Settings','js-jobs'); ?>
    </div>
    <div class="post-installtion-content-header">
        <div class="update-header-img step-4">
            <div class="header-parts first-part">
                <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/green.png" />
                <span class="text"><?php echo __('General', 'js-jobs'); ?></span>
                <span class="text-no">1</span>
                <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/green-2.png" />
            </div>
            <div class="header-parts second-part">
                <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/green.png" />
                <span class="text"><?php echo __('Employer', 'js-jobs'); ?></span>
                <span class="text-no">2</span>
                <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/green-2.png" />
            </div>
            <div class="header-parts third-part">
                <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/green.png" />
                <span class="text"><?php echo __('Job seeker', 'js-jobs'); ?></span>
                <span class="text-no">3</span>
                <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/green-2.png" />
            </div>
            <div class="header-parts fourth-part">
                <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/orange.png" />
                <span class="text"><?php echo __('Sample data', 'js-jobs'); ?></span>
                <span class="text-no">4</span>
                <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/orange-2.png" />
            </div>
        </div>
    </div>
    
    <span class="heading-post-ins"><?php echo __('Sample Data','js-jobs');?></span>
    <div class="post-installtion-content">
        <form id="jsjobs-form-ins" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_postinstallation&task=savesampledata"),"save-sampledata")); ?>">
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Insert Sample Data','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::select('sampledata', $yesno,1,'',array('class' => 'inputbox','onchange' => 'showHideUserForm()')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
                <div class="jsjobs-show-sample-data-form">
                    <div class="sample-data-heading">
                       <?php echo  __('Job Seeker','js-jobs'); ?>
                    </div>
                    <div class="sample-data-text">
                        <div class="name-part">
                            <?php echo wp_kses(JSJOBSformfield::select('jobseeker_list', JSJOBSincluder::getJSModel('postinstallation')->getWpUsersList(),1,'',array('class' => 'inputbox jsjobs-userselect' , 'onchange' => 'setValueForJobSeeker()')),JSJOBS_ALLOWED_TAGS); ?>
                        </div>
                        <span class="name-part-refresh-btn" onclick="refreshList()" title="<?php echo __('refresh','js-jobs');?>"><?php echo __('Refresh','js-jobs'); ?></span>
                        <a target="_blank" class="name-part-create-user-btn" href="<?php echo esc_url( admin_url( 'user-new.php' ) ); ?>" title="<?php echo __('create user','js-jobs');?>"><?php echo __('Create user','js-jobs'); ?></a>
                    </div>
                    <div class="sample-data-heading">
                       <?php echo  __('Employer','js-jobs'); ?>
                    </div>
                    <div class="sample-data-text bottom-border">
                        <div class="name-part">
                            <?php echo wp_kses(JSJOBSformfield::select('employer_list', JSJOBSincluder::getJSModel('postinstallation')->getWpUsersList(),1,'',array('class' => 'inputbox jsjobs-userselect' , 'onchange' => 'setValueForEmployer()')),JSJOBS_ALLOWED_TAGS); ?>
                        </div>
                        <span class="name-part-refresh-btn" onclick="refreshList()" title="<?php echo __('refresh','js-jobs');?>"><?php echo __('Refresh','js-jobs'); ?></span>
                        <a target="_blank" class="name-part-create-user-btn" href="<?php echo esc_url( admin_url( 'user-new.php' ) ); ?>" title="<?php echo __('create user','js-jobs');?>"><?php echo __('Create user','js-jobs'); ?></a>
                    </div>
                </div>
            </div>
        <?php
            
            if(jsjobs::$theme_chk == 0){ 
                ?>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Job Seeker Menu','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::select('jsmenu', $yesno,1,'',array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Employer Menu','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::select('empmenu', $yesno,1,'',array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
            </div>
            <?php }elseif(jsjobs::$theme_chk != 1){ ?>
                <div class="pic-config temp-demo-data">
                    <div class="title"> 
                        <?php echo __('Job Hub Sample Data','js-jobs');?>: &nbsp;
                    </div>
                    <div class="field"> 
                        <?php echo wp_kses(JSJOBSformfield::select('temp_data', $yesno,1,'',array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                    </div>
                    <div class="desc"><?php echo __('if yes is selected then pages and menus of job manager template will be cretaed and published.','js-jobs');?>. </div>
                </div>
            <?php }
             ?>
			

            <div class="pic-button-part">
                <a class="next-step finish" href="#" onclick="checkForEmpAndJSId()">
                    <?php echo __('Finish','js-jobs'); ?>
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/finsh-icon.png" />
                </a>
                <a class="back" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_postinstallation&jsjobslt=stepthree')); ?>"> 
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/back-arrow.png" />
                    <?php echo __('Back','js-jobs'); ?>
                </a>

            </div>
            <?php echo wp_kses(JSJOBSformfield::hidden('jobseeker_id', '0'),JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('employer_id', '0'),JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('step', 3), JSJOBS_ALLOWED_TAGS); ?>
        </form>
    </div>
    <div class="close-button-bottom">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>" class="close-button">
            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/close-icon.png" />
            <?php echo __('Close','js-jobs'); ?>
        </a>
    </div>
</div>
