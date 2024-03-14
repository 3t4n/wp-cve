<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script >
    function resetFrom() {
        document.getElementById('searchdepartment').value = '';
        document.getElementById('searchcompany').value = '';
        document.getElementById('jsjobsform').submit();
    }
</script>
<?php wp_enqueue_script('jsjob-res-tables', JSJOBS_PLUGIN_URL . 'includes/js/responsivetable.js'); ?>
<?php 

$msgkey = JSJOBSincluder::getJSModel('departments')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey); 
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Departments Approval Queue', 'js-jobs') ?>
    </span>
    <div class="page-actions js-row no-margin">
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for="approve" data-for-wpnonce="<?php echo esc_attr(wp_create_nonce("approve-department")); ?>" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/approve-s.png" /><?php echo __('Approve', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for="reject" data-for-wpnonce="<?php echo esc_attr(wp_create_nonce("reject-department")); ?>" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/reject-s.png" /><?php echo __('Reject', 'js-jobs') ?></a>
    </div>
    <script >
        function resetFrom() {
            jQuery("input#departmentname").val('');
            jQuery("input#companyname").val('');
            jQuery("form#jsjobsform").submit();
        }
    </script>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_departments&jsjobslt=departmentqueue"),"department")); ?>">
        <?php echo wp_kses(JSJOBSformfield::text('departmentname', jsjobs::$_data['filter']['departmentname'], array('class' => 'inputbox', 'placeholder' => __('Department Name', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::text('companyname', jsjobs::$_data['filter']['companyname'], array('class' => 'inputbox', 'placeholder' => __('Company Name', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::button('reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSJOBS_ALLOWED_TAGS); ?>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>  		
        <form id="jsjobs-list-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_departments")); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="grid"><input type="checkbox" name="selectall" id="selectall" value=""></th>
                        <th class="left-row"><?php echo __('Department', 'js-jobs'); ?></th>
                        <th><?php echo __('Company', 'js-jobs'); ?></th>
                        <th><?php echo __('Created', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Status', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach (jsjobs::$_data[0] AS $department) {
                        ?>			
                        <tr valign="top">
                            <td class="grid-rows">
                                <input type="checkbox" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo esc_attr($department->id); ?>" />
                            </td>
                            <td class="left-row"><a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_departments&jsjobslt=formdepartment&jsjobsid='.$department->id.'&isqueue=1'),"save-department")); ?>"><?php echo esc_html($department->name); ?></a></td>
                            <td><?php echo esc_html($department->companyname); ?></td>
                            <td><?php
                                $dateformat = jsjobs::$_configuration['date_format'];
                                echo esc_html(date_i18n($dateformat, jsjobslib::jsjobs_strtotime($department->created))); ?></td>
                            <td class="action">
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_departments&task=approve&action=jsjobtask&jsjobs-cb[]='.$department->id),'approve-department')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/approve.png"alt="<?php echo __('Approve', 'js-jobs'); ?>" title="<?php echo __('Approve', 'js-jobs'); ?>" /></a>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_departments&task=reject&action=jsjobtask&jsjobs-cb[]='.$department->id),'reject-department')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/reject.png"alt="<?php echo __('Reject', 'js-jobs'); ?>" title="<?php echo __('Reject', 'js-jobs'); ?>" /></a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php echo wp_kses(JSJOBSformfield::hidden('task', ''), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', 'department'), JSJOBS_ALLOWED_TAGS); ?>
        </form>
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
