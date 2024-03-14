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

    $msgkey = JSJOBSincluder::getJSModel('customfield')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey); 

    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('User Fields', 'js-jobs') ?>
        <a class="js-button-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&jsjobslt=formuserfield'),"formuserfield")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/add_icon.png" /><?php echo __('Add User Field', 'js-jobs') ?></a>
    </span>
    <div class="page-actions js-row no-margin">
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for="remove" confirmmessage="<?php echo __('Are you sure to delete', 'js-jobs') .' ?'; ?>"  href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-jobs') ?></a>
    </div>
    <script >
        function resetFrom() {
            jQuery("input#title").val('');
            jQuery("select#type").val('');
            jQuery("select#required").val('');
            jQuery("form#jsjobsform").submit();
        }
    </script>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_customfield&ff=" . jsjobs::$_data['fieldfor']), "customfield")); ?>">
        <?php echo wp_kses(JSJOBSformfield::text('title', jsjobs::$_data['filter']['title'], array('class' => 'inputbox', 'placeholder' => __('Title', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::select('type', JSJOBSincluder::getJSModel('common')->getFeilds(), is_numeric(jsjobs::$_data['filter']['type']) ? jsjobs::$_data['filter']['type'] : '', __('Select','js-jobs') .' '. __('Field Type', 'js-jobs'), array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::select('required', JSJOBSincluder::getJSModel('common')->getYesNo(), is_numeric(jsjobs::$_data['filter']['required']) ? jsjobs::$_data['filter']['required'] : '', __('Required', 'js-jobs'), array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'), JSJOBS_ALLOWED_TAGS); ?>
        <div class="filter-bottom-button">
            <?php echo wp_kses(JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::button('reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSJOBS_ALLOWED_TAGS); ?>
        </div>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>
        <form id="jsjobs-list-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_customfield")); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="grid"><input type="checkbox" name="selectall" id="selectall" value=""></th>
                        <th class="left-row"><?php echo __('Field Name', 'js-jobs'); ?></th>
                        <th><?php echo __('Field Title', 'js-jobs'); ?></th>
                        <th><?php echo __('Field Type', 'js-jobs'); ?></th>
                        <th><?php echo __('Required', 'js-jobs'); ?></th>
                        <th><?php echo __('Read Only', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Action', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $k = 0;
                    for ($i = 0, $n = count(jsjobs::$_data[0]); $i < $n; $i++) {
                        $row = jsjobs::$_data[0][$i];
                        $required = ($row->required == 1) ? 'yes' : 'no';
                        $requiredalt = ($row->required == 1) ? __('Required', 'js-jobs') : __('Not Required', 'js-jobs');
                        $readonly = ($row->readonly == 1) ? 'yes' : 'no';
                        $readonlyalt = ($row->readonly == 1) ? __('Required', 'js-jobs') : __('Not Required', 'js-jobs');
                        ?>
                        <tr valign="top">
                            <td class="grid-rows">
                                <input type="checkbox" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo esc_attr($row->id); ?>" />
                            </td>
                            <td class="left-row"><a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_customfield&jsjobslt=formuserfield&jsjobsid='.$row->id),"formuserfield")); ?>"><?php echo esc_html($row->name); ?></a></td>
                            <td><?php echo esc_html($row->title); ?></td>
                            <td><?php echo esc_html($row->type); ?></td>
                            <td><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/<?php echo esc_attr($required); ?>.png" alt="<?php echo esc_attr($requiredalt); ?>" /></td>
                            <td><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/<?php echo esc_attr($readonly); ?>.png" alt="<?php echo esc_attr($readonlyalt); ?>" /></td>
                            <td class="action">
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_customfield&jsjobslt=formuserfield&jsjobsid='.$row->id),"formuserfield")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/edit.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_customfield&task=remove&action=jsjobtask&jsjobs-cb[]='.$row->id),'delete-customfield')); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'js-jobs').' ?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/remove.png" title="<?php echo __('Delete', 'js-jobs'); ?>"></a>
                            </td>
                        </tr>
                        <?php
                        $k = 1 - $k;
                    }
                    ?>
                </tbody>
            </table>
            <?php echo wp_kses(JSJOBSformfield::hidden('action', 'customfield_remove'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('task', ''), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('delete-customfield')), JSJOBS_ALLOWED_TAGS); ?>
        </form>
        <?php
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(jsjobs::$_data[1]) . '</div></div>';
        }
    } else {
        JSJOBSlayout::getNoRecordFound();
    }
    ?>
</div>
</div>
