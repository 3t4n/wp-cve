<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script >
    function resetFrom() {
        document.getElementById('departmentname').value = '';
        document.getElementById('companyname').value = '';
        document.getElementById('status').value = '';
        document.getElementById('jsjobsform').submit();
    }
</script>
<?php wp_enqueue_script('jsjob-res-tables', JSJOBS_PLUGIN_URL . 'includes/js/responsivetable.js'); ?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php
    $msgkey = JSJOBSincluder::getJSModel('departments')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Departments', 'js-jobs') ?>
        <a class="js-button-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_departments&jsjobslt=formdepartment'),"save-department")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/add_icon.png" /><?php echo __('Add New','js-jobs') .' '. __('Department', 'js-jobs') ?></a>
    </span>
    <div class="page-actions js-row no-margin">
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" confirmmessage="<?php echo __('Are you sure to delete', 'js-jobs') .' ?'; ?>"  data-for="remove" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-jobs') ?></a>
    </div>
    <?php
    $statuscombo = array();
    $statuscombo[] = (object) array('id' => '1', 'text' => __('Approved', 'js-jobs'));
    $statuscombo[] = (object) array('id' => '-1', 'text' => __('Rejected', 'js-jobs'));
    $cid = '';
    $check = '';
    if (isset($_COOKIE['cid_departments'])) {
        $cid = sanitize_key($_COOKIE['cid_departments']);
        $name = jsjobs::$_data[0]['companyname'];
        $check = "readonly value='$name'";
    }
    ?>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_departments&jsjobslt=departments&companyid=" . $cid),"department")); ?>">
        <input name="departmentname" id="departmentname" type="text" placeholder="<?php echo __('Department', 'js-jobs'); ?>" value="<?php echo esc_attr(jsjobs::$_data['filter']['departmentname']); ?>" />
        <input name="companyname" id="companyname" type="text" <?php echo esc_attr($check); ?> placeholder="<?php echo __('Company', 'js-jobs'); ?>" value="<?php echo esc_attr(jsjobs::$_data['filter']['companyname']); ?>" />
        <?php echo wp_kses(JSJOBSformfield::select('status', $statuscombo, is_numeric(jsjobs::$_data['filter']['status']) ? jsjobs::$_data['filter']['status'] : '', __('Select Status', 'js-jobs'), array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'), JSJOBS_ALLOWED_TAGS); ?>
        <div class="filter-bottom-button">
            <input type="submit" class="button" value="<?php echo __('Search', 'js-jobs') ?>" name="btnsubmit"/>
            <input type="button" class="button" value="<?php echo __('Reset', 'js-jobs') ?>" onclick="resetFrom();" />
        </div>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0]['department'])) {
        ?>
        <form id="jsjobs-list-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_departments")); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="grid"><input type="checkbox" name="selectall" id="selectall" value=""></th>
                        <th class="left-row"><?php echo __('Department', 'js-jobs'); ?></th>
                        <th ><?php echo __('Company', 'js-jobs'); ?></th>
                        <th><?php echo __('Created', 'js-jobs'); ?></th>
                        <th><?php echo __('Status', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Action', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach (jsjobs::$_data[0]['department'] AS $department) {
                        ?>
                        <tr>
                            <td class="grid-rows">
                                <input type="checkbox" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo esc_attr($department->id); ?>" />
                            </td>
                            <td class="left-row">
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_departments&jsjobslt=formdepartment&jsjobsid='.$department->id),"save-department")); ?>"><?php echo esc_html($department->name); ?></a>
                            </td>
                            <td><?php echo esc_html($department->companyname); ?></td>
                            <td><?php
                                $dateformat = jsjobs::$_configuration['date_format'];
                                echo esc_html(date_i18n($dateformat, jsjobslib::jsjobs_strtotime($department->created))); ?></td>
                            <td><span class="status-text-bold">
                                    <?php
                                    if ($department->status == 1) {
                                        echo "<font color='green'>" . __('Approved', 'js-jobs') . "</font>";
                                    } elseif ($department->status == -1) {
                                        echo "<font color='red'>" . __('Rejected', 'js-jobs') . "</font>";
                                    } else {
                                        echo "<font color='orange'>" . __('Pending', 'js-jobs') . "</font>";
                                    }
                                    ?></span>
                            </td>
                            <td class="action">
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_departments&jsjobslt=formdepartment&jsjobsid='.$department->id),"save-department")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/edit.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_departments&task=remove&action=jsjobtask&jsjobs-cb[]='.$department->id),'delete-department')); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'js-jobs').' ?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/remove.png" title="<?php echo __('Delete', 'js-jobs'); ?>"></a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php echo wp_kses(JSJOBSformfield::hidden('action', 'departments_remove'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('task', ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('delete-department')), JSJOBS_ALLOWED_TAGS); ?>
        </form>
        <?php
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(jsjobs::$_data[1]) . '</div></div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        $link[] = array(
                    'link' => wp_nonce_url(admin_url('admin.php?page=jsjobs_departments&jsjobslt=formdepartment'),"save-department"),
                    'text' => __('Add New','js-jobs') .' '. __('Department','js-jobs')
                );
        JSJOBSlayout::getNoRecordFound($msg,$link);
    }
    ?>
</div>
</div>
