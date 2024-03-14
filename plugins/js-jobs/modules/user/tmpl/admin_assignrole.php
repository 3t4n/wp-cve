<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobsadmin-wrapper">
    <div id="full_background" style="display:none;"></div>
    <div id="popup_main" style="display:none;">
        <img class="icon" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/popup-coin-icon.png"/>
        <span class="popup-top"><span id="popup_title" ></span><img id="popup_cross" alt="popup cross" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/popup-close.png">
        </span>
        <div style="display:inline-block;width:100%;float:left;">
            <form id="userpopupsearch">
                <div class="search-center">
                    <div class="js-col-md-12">
                        <div class="js-col-xs-12 js-col-md-3 search-value">
                            <input type="text" name="uname" id="uname" placeholder="<?php echo __('Username', 'js-jobs');?>" />
                        </div>
                        <div class="js-col-xs-12 js-col-md-3 search-value">
                            <input type="text" name="name" id="name" placeholder="<?php echo __('Name', 'js-jobs');?>" />
                        </div>
                        <div class="js-col-xs-12 js-col-md-3 search-value">
                            <input type="text" name="email" id="email" placeholder="<?php echo __('Email Address', 'js-jobs');?>"/>
                        </div>
                        <div class="js-col-xs-12 js-col-md-3 search-value-button">
                            <div class="js-button ">
                                <input type="submit" class="submit-button" value="<?php echo __('Search', 'js-jobs');?>" />
                            </div>
                            <div class="js-button">
                                <input type="submit" onclick="document.getElementById('name').value = '';document.getElementById('uname').value = ''; document.getElementById('email').value = '';" value="<?php echo __('Reset', 'js-jobs');?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div id="popup-record-data" style="display:inline-block;width:100%;"></div>
    </div>
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
        <span class="js-admin-title">
            <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_user')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
            <?php echo __('Assign role', 'js-jobs'); ?>
        </span>
        <form id="jsjobs-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_user&task=assignuserrole")); ?>">
            <div class="js-field-wrapper js-row no-margin">
                <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __('Select User', 'js-jobs') . '<font class="required-notifier">*</font>'; ?></div>
                <div class="js-field-obj js-col-lg-9 js-col-md-9 js-col-xs-12 no-padding"><label id="uname"></label></div>

                <?php if (!isset(jsjobs::$_data[0]->uid)) { ?>
                    <a href="#" id="userpopup"><?php echo __('User Name', 'js-jobs'); ?></a><div id="username-div"></div>
                <?php } ?>
                <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"></div>
                <div class="js-field-obj js-col-lg-9 js-col-md-9 js-col-xs-12 no-padding">
                    <?php echo wp_kses(JSJOBSformfield::hidden('uid', '', array('data-validation' => 'required')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
            </div>

            <div class="js-field-wrapper js-row no-margin">
                <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Role', 'js-jobs'); ?></div>
                <div class="js-field-obj js-col-lg-5 js-col-md-5 no-padding"><?php echo wp_kses(JSJOBSformfield::select('roleid', JSJOBSincluder::getJSModel('common')->getRolesForCombo(), '', '', array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?></div>
            </div>
            
            <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('payer_firstname', ''), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('payer_emailadress', ''), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('user-popup-title-text', __('Select User','js-jobs')), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('assign-userrole')), JSJOBS_ALLOWED_TAGS); ?>

            <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
                <?php echo wp_kses(JSJOBSformfield::submitbutton('save', __('Assign role', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
            </div>
        </form>
    </div>
</div>

<script >
    jQuery(document).ready(function () {
        jQuery.validate();
        jQuery("a#userpopup").click(function (e) {
            e.preventDefault();
            jQuery("div#popup-new-company").css("display", "none");
            jQuery("img.icon").css("display", "none");
            jQuery("div#popup-record-data").css("display", "block");
            jQuery("div#full_background").show();
            var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'user', task: 'getAllRoleLessUsersAjax', listfor: 1,wpnoncecheck:common.wp_jm_nonce}, function (data) {
                if (data) {
                    jQuery("div#popup-record-data").html("");
                    jQuery("span#popup_title").html(jQuery("input#user-popup-title-text").val());
                    jQuery("div#popup-record-data").html(data);
                    setUserLink();
                }
            });
            jQuery("div#popup_main").slideDown('slow');
        });
    });

    function setUserLink() {
        jQuery("a.js-userpopup-link").each(function () {
            var anchor = jQuery(this);
            jQuery(anchor).click(function (e) {
                var name = jQuery(this).attr('data-name');
                jQuery("label#uname").html(name);
                var id = jQuery(this).attr('data-id');
                var email = jQuery(this).attr('data-email');
                var name = jQuery(this).attr('data-name');
                jQuery("input#uid").val(id);
                jQuery("input#payer_firstname").val(name);
                jQuery("input#payer_emailadress").val(email);
                jQuery("div#popup_main").slideUp('slow', function () {
                    jQuery("div#full_background").hide();
                });
            });
        });
    }
    
    jQuery(document).delegate('form#userpopupsearch', 'submit', function (e) {
        e.preventDefault();
        e.preventDefault();
        var username = jQuery("input#uname").val();
        var name = jQuery("input#name").val();
        var emailaddress = jQuery("input#email").val();
        var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'user', task: 'getAllRoleLessUsersAjax', name: name, uname: username, email: emailaddress, listfor: 1,wpnoncecheck:common.wp_jm_nonce}, function (data) {
            if (data) {
                console.log(data);
                jQuery("span#popup_title").html(jQuery("input#user-popup-title-text").val());
                jQuery("div#popup-record-data").html(data);
                setUserLink();
            }
        });//jquery closed
    });
    jQuery("span.close, div#full_background,img#popup_cross").click(function (e) {
        jQuery("div#popup_main").slideUp('slow', function () {
            jQuery("div#full_background").hide();
        });

    });

</script>
