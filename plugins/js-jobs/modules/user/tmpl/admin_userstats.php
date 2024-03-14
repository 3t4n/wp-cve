<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script >
    function resetFrom() {
        document.getElementById('searchname').value = '';
        document.getElementById('searchusername').value = '';
        document.getElementById('jsjobsform').submit();
    }
</script>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php jsjobs::$_data['filter']['categoryid'] = 0; ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('User Stats', 'js-jobs') ?>
    </span>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_user&jsjobslt=userstats"),"userstats")); ?>">
        <?php echo wp_kses(JSJOBSformfield::text('searchname', jsjobs::$_data['filter']['searchname'], array('class' => 'inputbox', 'placeholder' => __('Name', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::text('searchusername', jsjobs::$_data['filter']['searchusername'], array('class' => 'inputbox', 'placeholder' => __('Word Press user login', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::button('reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSJOBS_ALLOWED_TAGS); ?>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>  		
        <table id="js-table">
            <thead>
                <tr>
                    <th class="left-row"><?php echo __('Name', 'js-jobs'); ?></th>
                    <th><?php echo __('Username', 'js-jobs'); ?></th>
                    <th><?php echo __('Company', 'js-jobs'); ?></th>
                    <th><?php echo __('Resume', 'js-jobs'); ?></th>
                    <th><?php echo __('Companies', 'js-jobs'); ?></th>
                    <th><?php echo __('Jobs', 'js-jobs'); ?></th>
                    <th><?php echo __('Resume', 'js-jobs'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $k = 0;
                for ($i = 0, $n = count(jsjobs::$_data[0]); $i < $n; $i++) {
                    $row = jsjobs::$_data[0][$i];
                    ?>			
                    <tr>
                        <td><?php echo esc_html($row->name); ?></td>
                        <td><?php echo esc_html($row->username); ?>	</td>
                        <td><?php echo esc_html($row->companyname); ?>	</td>
                        <td><?php echo esc_html($row->resumename); ?>	</td>

                        <?php if ($row->rolefor == 1) { // employer ?>
                            <td><a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_user&jsjobslt=userstate_companies&md='.$row->id)); ?>"><strong><?php echo esc_html($row->companies); ?></strong></a></td>
                            <td><a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_user&jsjobslt=userstate_jobs&bd='.$row->id)); ?>"><strong><?php echo esc_html($row->jobs); ?></a></strong></td>
                            <td><strong>-</strong></td>
                        <?php } elseif ($row->rolefor == 2) { //jobseeker ?>
                            <td><strong>-</strong></td>
                            <td><strong>-</strong></td>
                            <td><a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_user&jsjobslt=userstate_resumes&ruid='.$row->id)); ?>"><strong><?php echo esc_html($row->resumes); ?></a></strong></td>
                        <?php } else { ?>
                            <td><strong>-</strong></td>
                            <td><strong>-</strong></td>
                            <td><strong>-</strong></td>
                        <?php } ?>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
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
