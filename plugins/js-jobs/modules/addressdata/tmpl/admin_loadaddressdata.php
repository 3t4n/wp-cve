<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jsjob-res-tables', JSJOBS_PLUGIN_URL . 'includes/js/responsivetable.js');
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('addressdata')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey); 
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Load Address Data', 'js-jobs'); ?>
    </span>
    <form id="jsjobs-list-form" enctype="multipart/form-data" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_addressdata'),"load-address-data")); ?>">
        <div id="loadaddressdata_wrapper">
            <div id="loadaddressdata_upper">
                <img id="loadaddressdata_companylogo" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/add_logo.png" />
                <span id="loadaddressdata_logo"><?php echo __('Joomsky', 'js-jobs'); ?></span>
                <span id="loadaddressdata_slogon"><?php echo __('Download From Joomsky website', 'js-jobs'); ?></span>
                <a href="http://www.joomsky.com/index.php/download-buy/product/product/8/43" target="_blank" ><img id="loadaddressdata_downloadbutton" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/loadaddressdownloadbutton.png"/></a>
            </div>
            <div id="loadaddressdata_options">
                <div id="loadaddressdata_options_left"><?php echo __('Action', 'js-jobs'); ?>&nbsp;:</div>
                <div id="loadaddressdata_options_right">
                    <div class="row">
                        <input type="radio" name="datakept" id="option1" value="1" /><label for="option1"><?php echo __('Kept Data', 'js-jobs'); ?></label>                        
                    </div>
                    <div class="row">
                        <input type="radio" name="datakept" id="option2" checked="checked" value="2" /><label for="option2"><?php echo __('Discard Old Data', 'js-jobs'); ?></label>                        
                    </div>
                </div>
                <div id="loadaddressdata_options_left"><?php echo __('File', 'js-jobs'); ?>&nbsp;:</div>
                <div id="loadaddressdata_options_right">
                    <div class="row">
                        <input type="radio" name="fileowner" id="fileowner1" value="1" /><label for="fileowner1"><?php echo __('My File', 'js-jobs'); ?></label>                        
                    </div>
                    <div class="row">
                        <input type="radio" name="fileowner" id="fileowner2" checked="checked" value="2" /><label for="fileowner2"><?php echo __('Joomsky File', 'js-jobs'); ?></label>                        
                    </div>
                </div>
                <div id="loadaddressdata_options_left"><?php echo __('Data Contain', 'js-jobs'); ?>&nbsp;:</div>
                <div id="loadaddressdata_options_right">
                    <div class="row">
                        <input type="radio" name="datacontain" id="datacontain1" value="1" /><label for="datacontain1"><?php echo __('States', 'js-jobs'); ?></label>
                    </div>
                    <div class="row">
                        <input type="radio" name="datacontain" id="datacontain2" value="2" /><label for="datacontain2"><?php echo __('Cities', 'js-jobs'); ?></label>                        
                    </div>
                    <div class="row">
                        <input type="radio" name="datacontain" id="datacontain3" checked="checked" value="3" /><label for="datacontain3"><?php echo __('States and cities', 'js-jobs'); ?></label>                        
                    </div>
                </div>
                <div id="loadaddressdata_file">
                    <div id="loadaddressdata_msg" >
                        <span id="loadaddressdata_msg"><?php echo __('Make sure you did not changed country id or state id in database', 'js-jobs'); ?></span>
                    </div>
                    <label id="file"><?php echo __('File', 'js-jobs'); ?> :&nbsp;<font color="red">*</font></label>
                    <input type="file" class="inputbox  required" name="loadaddressdata" id="loadaddressdata" size="20" maxlenght='30'/>
                </div>
                <div class="add_button">
                    <input class="button" type="submit" name="submit_app" id="submitbutton" value="<?php echo __('Load Address Data', 'js-jobs'); ?>" onclick="return validate_form(document.adminForm)" />                    
                </div>
            </div>
        </div>

        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('task', 'loadaddressdata'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('action', 'addressdata_loadaddressdata'), JSJOBS_ALLOWED_TAGS); ?>

    </form>
</div>
</div>
<script>
    jQuery("input[type=radio]").change(function () {
        var keptdata = jQuery("#option1").is(':checked');
        var discarddata = jQuery("#option2").is(':checked');
        var myfile = jQuery("#fileowner1").is(':checked');
        var joomskyfile = jQuery("#fileowner2").is(':checked');
        var states = jQuery("#datacontain1").is(':checked');
        var cities = jQuery("#datacontain2").is(':checked');
        var statesandcities = jQuery("#datacontain3").is(':checked');
        var msg = '';
        if (keptdata == true) {
            if (myfile == true) {
                if (states == true) {
                    msg = "<?php echo __('State id greater country id is your responsibility', 'js-jobs'); ?>";
                } else if (cities == true) {
                    msg = "<?php echo __('Cities have no ids also state id and country id is your responsibility', 'js-jobs'); ?>";
                } else if (statesandcities == true) {
                    msg = "<?php echo __('State id greater and cities have no ids also state id and country id is your responsibility', 'js-jobs'); ?>";
                }
            } else if (joomskyfile == true) {
                if (states == true) {
                    msg = "";
                } else if (cities == true) {
                    msg = "<?php echo __('Consider old countries and states were not edit otherwise problem may occur', 'js-jobs'); ?>";
                } else if (statesandcities == true) {
                    msg = "<?php echo __('Consider old countries were not edit otherwise problem may occur', 'js-jobs'); ?>";
                }
            }
        } else if (discarddata == true) {
            if (myfile == true) {
                if (states == true) {
                    msg = "<?php echo __('State id greater country id is your responsibility', 'js-jobs'); ?>";
                } else if (cities == true) {
                    msg = "<?php echo __('Cities have no ids also state id and country id is your responsibility', 'js-jobs'); ?>";
                } else if (statesandcities == true) {
                    msg = "<?php echo __('State id greater and cities have no ids also state id and country id is your responsibility', 'js-jobs'); ?>";
                }
            } else if (joomskyfile == true) {
                if (states == true) {
                    msg = "";
                } else if (cities == true) {
                    msg = "<?php echo __('Consider old countries and states were not edit otherwise problem may occur', 'js-jobs'); ?>";
                } else if (statesandcities == true) {
                    msg = "<?php echo __('Consider old countries were not edit otherwise problem may occur', 'js-jobs'); ?>";
                }
            }
        }

        if (msg != "") {
            jQuery("span#loadaddressdata_msg").html(msg);
            jQuery("div#loadaddressdata_msg").slideDown("slow");
        } else
            jQuery("div#loadaddressdata_msg").slideUp("slow");
    });
</script>
