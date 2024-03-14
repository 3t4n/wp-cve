<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
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
        <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_user')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('User Details', 'js-jobs') ?>
    </span>
    
    <?php
    if (!empty(jsjobs::$_data[0])) { 
        $user = jsjobs::$_data[0]; ?>
    <div class="user-detail">
        <div class="user-info">
            <div class="user-detail-left">
                <div class="img-wrapper">
                    <a class="jsanchor" href="">
                        <span class="jsborder">
                            <img class="user-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/users.png">
                        </span>                        
                    </a>
                </div>
            </div>
            <div class="user-detail-right">
            <div class="detail-top">
                <div class="title">
                    <?php echo esc_html($user->first_name).' '.esc_html($user->last_name); ?>
                    <span class="role-<?php echo ($user->roleid == 1) ? 'empl' : 'jobs'; ?>">
                        <?php if($user->roleid == 1){
                            echo __('Employer','js-jobs');
                            }elseif($user->roleid == 2){
                                echo __('Job seeker','js-jobs');
                            } ?>
                    </span>
                </div>
                <div class="flag-and-type">
                    <?php
                    if ($user->socialmedia == 'facebook') {
                        echo '<span class="flag facebook">' . __('Facebook', 'js-jobs') . '</span>';
                    } elseif ($user->socialmedia == 'xing') {
                        echo '<span class="flag xing">' . __('Xing', 'js-jobs') . '</span>';
                    } elseif ($user->socialmedia == 'twitter') {
                        echo '<span class="flag twitter">' . __('Twitter', 'js-jobs') . '</span>';
                    }

                    if ($user->status == 0) {
                        echo '<span class="flag pending">' . __('Pending', 'js-jobs') . '</span>';
                    } elseif ($user->status == 1) {
                        echo '<span class="flag approved">' . __('Approved', 'js-jobs') . '</span>';
                    } elseif ($user->status == -1) {
                        echo '<span class="flag rejected">' . __('Rejected', 'js-jobs') . '</span>';
                    }
                    ?> 

                </div>
            </div>
                <div class="detail-data">
                    <span class="text-label"><?php echo __('Email','js-jobs'); ?> : </span>
                    <span class="text-data"><?php echo esc_html($user->emailaddress); ?></span>
                </div>
                <div class="detail-data">
                    <span class="text-label"><?php echo __('Group','js-jobs'); ?> : </span>
                    <span class="text-data"><?php echo wp_kses(JSJOBSincluder::getJSModel('user')->getWPRoleNameById($user->uid), JSJOBS_ALLOWED_TAGS);  ?></span>
                </div>
                <div class="detail-data">
                    <span class="text-label"><?php echo __('Created','js-jobs'); ?> : </span>
                    <span class="text-data"><?php echo esc_html(date_i18n(jsjobs::$_configuration['date_format'], jsjobslib::jsjobs_strtotime($user->created) )); ?></span>
                </div>
            </div>
        </div>
        <div class="user-stats">
            <div class ="stat-header">
                <?php echo __('User Stats','js-jobs'); ?>
            </div>
            <?php if($user->roleid == 1){ ?>
                <div class="stat-parts">
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/lower-icons/jobs.png">
                    <span class="number"><?php echo esc_html(jsjobs::$_data['companies'])?></span>
                    <span class="text"><?php echo __('Companies','js-jobs')?></span>
                </div>
                <div class="stat-parts">
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/lower-icons/companies.png">
                    <span class="number"><?php echo esc_html(jsjobs::$_data['jobs'])?></span>
                    <span class="text"><?php echo __('Jobs','js-jobs')?></span>
                </div>
                <div class="stat-parts">
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/lower-icons/department.png">
                    <span class="number"><?php echo esc_html(jsjobs::$_data['department'])?></span>
                    <span class="text"><?php echo __('Department','js-jobs')?></span>
                </div>
                <div class="stat-parts">
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/lower-icons/job-applied.png">
                    <span class="number"><?php echo esc_html(jsjobs::$_data['jobapply'])?></span>
                    <span class="text"><?php echo __('Job Applies','js-jobs')?></span>
                </div>
            <?php }elseif($user->roleid == 2){?>
                <div class="stat-parts">
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/lower-icons/reume.png">
                    <span class="number"><?php echo esc_html(jsjobs::$_data['resume'])?></span>
                    <span class="text"><?php echo __('Resume','js-jobs')?></span>
                </div>
                <div class="stat-parts">
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/lower-icons/cover-letter.png">
                    <span class="number"><?php echo esc_html(jsjobs::$_data['coverletter'])?></span>
                    <span class="text"><?php echo __('Cover Letter','js-jobs')?></span>
                </div>
                <div class="stat-parts">
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/lower-icons/job-applied.png">
                    <span class="number"><?php echo esc_html(jsjobs::$_data['jobapply'])?></span>
                    <span class="text"><?php echo __('Job Applies','js-jobs')?></span>
                </div>
            <?php }?>
        </div>
        <div class="bottom-and-actions">
            <div class="user-id">
                <span class="bold">
                    <?php echo __('ID').': '; ?>
                </span>
                <?php echo esc_html($user->id); ?>
            </div>
            <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_user&action=jsjobtask&task=enforcedeleteuser&jsjobsid='.$user->id),'delete-userrole')); ?>" onclick="return confirm('<?php echo __('This will delete every thing about this record','js-jobs').'. '.__('Are you sure to delete','js-jobs').'?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/force-delete.png" /><?php echo __('Enforce Delete', 'js-jobs') ?></a>
            <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_user&action=jsjobtask&task=deleteuser&jsjobsid='.$user->id),'delete-userrole')); ?>" onclick="return confirm('<?php echo __('Are you sure to delete', 'js-jobs').' ?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-jobs') ?></a>
            <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_user&action=jsjobtask&task=changeuserstatus&jsjobsid='.$user->id),'change-userrole')); ?>&detail=1"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/disable-icon.png" /><?php echo ($user->status == 1) ? __('Disable', 'js-jobs') : __('Enable', 'js-jobs'); ?></a>
            <a class="js-action-link button" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_user&jsjobslt=changerole&jsjobsid='.$user->id)); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/change-role-icon-2.png" /><?php echo __('Change Role', 'js-jobs') ?></a>    
        </div>
    </div>

    <?php
    } else {
        $msg = __('No record found','js-jobs');
        JSJOBSlayout::getNoRecordFound($msg); 
    }
    ?>
</div>
</div>
