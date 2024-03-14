<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jsjobsadmin-wrapper">
    <div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs&jsjobslt=stepone')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Update', 'js-jobs'); ?>
    </span>
    <div id="jsjobs-content">
            <div id="jsjobs-lower-wrapper">
                <div style="display:none;" id="jsjob_installer_waiting_div"></div>
                <span style="display:none;" id="jsjob_installer_waiting_span"><?php echo __("Please wait installation in progress",'js-jobs'); ?></span>
                <div class="jsjobs_installer_wrapper" id="jsjobs-installer_id">    
                    <div class="jsjobs_top">
                        <div class="jsjobs_logo_wrp">
                            <img src="<?php echo JSJOBS_PLUGIN_URL.'includes/images/installer/logo.png';?>">
                        </div>
                        <div class="jsjobs_heading_text"><?php echo __("JS Jobs",'js-jobs'); ?></div>
                        <div class="jsjobs_subheading_text"><?php echo __("Most Powerful Job Board Plugin",'js-jobs'); ?></div>
                    </div>
                        <?php 
                        if(jsjobs::$_data['response'] != " "){
                            $response = jsjobslib::jsjobs_safe_decoding(jsjobs::$_data['response']);
                            $response = json_decode($response);
                            if(isset($response)){ ?>
                                <div class="jsjobs_error_messages">
                                    <?php
                                    if($response[0] != 1){ ?>
                                        <span class="jsjobs_msg" id="jsjobs_error_message"><?php echo wp_kses($response[1], JSJOBS_ALLOWED_TAGS)?></span><?php  
                                    }else{ ?>
                                        <div id="jsjobs_next_form">
                                            <?php echo wp_kses($response[1], JSJOBS_ALLOWED_TAGS);?>
                                        </div>
                                        <?php 
                                    } ?>
                                </div>
                                <?php
                            }
                        } ?>

                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('span#jsjob_installer_helptext').hide();
        $('div#jsjob_installer_formlabel').hide();
    });    
</script>
