<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script >
    jQuery(document).ready(function () {
        jQuery("span#showhidefilter").click(function (e) {
            e.preventDefault();
            var img2 = "<?php echo JSJOBS_PLUGIN_URL . "includes/images/filter-up.png"; ?>";
            var img1 = "<?php echo JSJOBS_PLUGIN_URL . "includes/images/filter-down.png"; ?>";
            if (jQuery('.default-hidden').is(':visible')) {
                jQuery(this).find('img').attr('src', img1);
            } else {
                jQuery(this).find('img').attr('src', img2);
            }
            jQuery(".default-hidden").toggle();
            var height = jQuery(this).height();
            var imgheight = jQuery(this).find('img').height();
            var currenttop = (height - imgheight) / 2;
            jQuery(this).find('img').css('top', currenttop);
        });
    });
    function resetFrom() {
        document.getElementById('searchname').value = '';
        document.getElementById('searchusername').value = '';
        document.getElementById('searchcompany').value = '';
        document.getElementById('searchresume').value = '';
        document.getElementById('searchrole').value = '';
        document.getElementById('jsjobsform').submit();
    }
</script>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('user')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Users', 'js-jobs') ?>
        <a class="js-button-link button" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_user&jsjobslt=assignrole')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/add_icon.png" /><?php echo __('Assign role', 'js-jobs') ?></a>
    </span>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_user&jsjobslt=users"),"user")); ?>">
        <?php echo wp_kses(JSJOBSformfield::text('searchname', jsjobs::$_data['filter']['searchname'], array('class' => 'inputbox', 'placeholder' => __('Name', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::text('searchusername', jsjobs::$_data['filter']['searchusername'], array('class' => 'inputbox', 'placeholder' => __('Word Press user login', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::text('searchcompany', jsjobs::$_data['filter']['searchcompany'], array('class' => 'inputbox default-hidden', 'placeholder' => __('Company', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::text('searchresume', jsjobs::$_data['filter']['searchresume'], array('class' => 'inputbox default-hidden', 'placeholder' => __('Resume', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::select('searchrole', JSJOBSincluder::getJSModel('common')->getRolesForCombo(), jsjobs::$_data['filter']['searchrole'], __('Select Role', 'js-jobs'), array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'), JSJOBS_ALLOWED_TAGS); ?>
        <div class="filterbutton">
            <?php echo wp_kses(JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::button('reset', __('Reset', 'js-jobs', 'js-jobs', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSJOBS_ALLOWED_TAGS); ?>
        </div>
        <span id="showhidefilter"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/filter-down.png"/></span>
    </form>
    <hr class="listing-hr" />
    <?php
    if (!empty(jsjobs::$_data[0])) {
        $wpdir = wp_upload_dir();
        foreach (jsjobs::$_data[0] AS $user) {
            $photo = '';
            if (isset($user->photo) && $user->photo != '' && file_exists(JSJOBS_PLUGIN_PATH . '/' . $user->photo)) {
                $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                $photo = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $user->resumeid . '/photo/' . $user->photo;
                $padding = "";
            } else {
                $photo = JSJOBS_PLUGIN_URL . '/includes/images/users.png';
                $padding = '';
            }
            $approved = ($user->status == 1) ? '<span class="text-green">' . __('Approved', 'js-jobs') . '</span>' : '<span class="text-red">' . __('Rejected', 'js-jobs') . '</span>';
            ?>
            <div id="user_<?php echo esc_attr($user->id); ?>" class="user-container user-container-margin-bottom js-col-lg-12 js-col-md-12 no-padding">
                <div id="item-data" class="item-data item-data-resume js-row no-margin">
                    <span id="selector_<?php echo esc_attr($user->id); ?>" class="selector">
                        <input type="checkbox" onclick="javascript:highlight(<?php echo esc_js($user->id); ?>);" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo esc_attr($user->id); ?>" />
                    </span>
                    <div class="item-icon js_circle">
                        <div class="profile">
                            <a class="js-anchor" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_user&jsjobslt=userdetail&id='.$user->id)); ?>">
                            <span class="js-border">
                                <img src="<?php echo esc_url($photo); ?>" <?php echo esc_attr($padding); ?> />
                            </span>
                            </a>
                        </div>
                    </div>
                    <div class="item-details js-col-lg-10 js-col-md-10 js-col-xs-12 no-padding">
                        <div class="item-title js-col-lg-11 js-col-md-11 js-col-xs-8 no-padding">
                            <span class="value title-text-user">
                                <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_user&jsjobslt=userdetail&id='.$user->id)); ?>" ><?php echo esc_html($user->first_name) . ' ' . esc_html($user->last_name); ?></a>
                                <span class="role-<?php echo ($user->roleid == 1) ? 'empl' : 'jobs'; ?>">
                                    <?php 
                                    if($user->roleid == 1){
                                        echo __('Employer','js-jobs');
                                    }elseif($user->roleid == 2){
                                        echo __('Job seeker','js-jobs');   
                                    } ?>
                                </span>
                            </span>

                            <div class="flag-and-type">
                                <?php
                                if ($user->socialmedia == 'facebook') {
                                    echo '<span class="flag facebook">' . __('Facebook', 'js-jobs') . '</span>';
                                } elseif ($user->socialmedia == 'xing') {
                                    echo '<span class="flag xing">' . __('Xing', 'js-jobs') . '</span>';
                                } elseif ($user->socialmedia == 'linkedin') {
                                    echo '<span class="flag twitter">' . __('Linkedin', 'js-jobs') . '</span>';
                                }

                                if ($user->status == 0) {
                                    echo '<span class="flag pending">' . __('Disabled', 'js-jobs') . '</span>';
                                } elseif ($user->status == 1) {
                                    echo '<span class="flag approved">' . __('Enabled', 'js-jobs') . '</span>';
                                } elseif ($user->status == -1) {
                                    echo '<span class="flag rejected">' . __('Rejected', 'js-jobs') . '</span>';
                                }
                                ?> 

                            </div>
                        </div>
                        <?php if($user->roleid == 2){ ?>
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                            <span class="heading"><?php echo __('Resume', 'js-jobs') . ': '; ?></span><span class="value"><?php echo esc_html($user->resume_first_name) . ' ' . esc_html($user->resume_last_name); ?></span>
                        </div>
                        <?php }elseif($user->roleid == 1){ ?>
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                            <span class="heading"><?php echo __('Company', 'js-jobs') . ': '; ?></span><span class="value"><?php echo esc_html($user->companyname); ?></span>
                        </div>
                        <?php }?>
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                            <span class="heading"><?php echo __('Group', 'js-jobs') . ': '; ?></span><span class="value"><?php echo wp_kses(JSJOBSincluder::getJSModel('user')->getWPRoleNameById($user->wpuid), JSJOBS_ALLOWED_TAGS); ?></span>
                        </div>
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                            <span class="heading"><?php echo __('User Name', 'js-jobs') . ': '; ?></span><span class="value"><?php echo esc_html($user->user_login); ?></span>
                        </div>
                    </div>
                </div>
                <div id="item-actions" class="item-actions js-row no-margin">
                    <div class="item-text-block js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding user-layout-id">
                        <span class="heading"><?php echo __('ID', 'js-jobs') . ': '; ?></span><span class="item-action-text"><?php echo esc_html($user->id); ?></span>
                    </div>
                    <div class="item-values js-col-lg-7 js-col-md-7 js-col-xs-12 no-padding">
                        <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_user&action=jsjobtask&task=enforcedeleteuser&jsjobsid='.$user->id),'delete-userrole')); ?>" onclick="return confirm('<?php echo __('This will delete every thing about this record','js-jobs').'. '.__('Are you sure to delete','js-jobs').'?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/force-delete.png" /><?php echo __('Enforce Delete', 'js-jobs') ?></a>
                        <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_user&action=jsjobtask&task=deleteuser&jsjobsid='.$user->id),'delete-userrole')); ?>" onclick="return confirm('<?php echo __('Are you sure to delete', 'js-jobs').' ?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-jobs') ?></a>
                        <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_user&action=jsjobtask&task=changeuserstatus&jsjobsid='.$user->id),'change-userrole')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/disable-icon.png" /><?php echo ($user->status == 1) ? __('Disable', 'js-jobs') : __('Enable', 'js-jobs'); ?></a>
                        <a class="js-action-link button" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_user&jsjobslt=changerole&jsjobsid='.$user->id)); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/change-role-icon-2.png" /><?php echo __('Change Role', 'js-jobs') ?></a>
                    </div>
                </div>
            </div>
            <?php
        }
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(jsjobs::$_data[1]) . '</div></div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        JSJOBSlayout::getNoRecordFound($msg); 
    }
    ?>
</div>
</div>
