<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script >
    function resetFrom() {
        document.getElementById('searchname').value = '';
        document.getElementById('status').value = '';
        document.getElementById('jsjobsform').submit();
    }
</script>
<?php wp_enqueue_script('jsjob-res-tables', JSJOBS_PLUGIN_URL . 'includes/js/responsivetable.js'); ?>
<?php
$msgkey = JSJOBSincluder::getJSModel('city')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php
    $countryid = get_option("jsjobs_countryid_for_city" );
    $stateid = get_option( "jsjobs_stateid_for_city" );
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_state&countryid='.$countryid),"city")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Cities', 'js-jobs') ?>
        <a class="js-button-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_city&jsjobslt=formcity'),"formcity")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/add_icon.png" /><?php echo __('Add New','js-jobs') .'&nbsp;'. __('City', 'js-jobs') ?></a>
    </span>
    <div class="page-actions js-row no-margin">
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for="publish" data-for-wpnonce="<?php echo esc_attr(wp_create_nonce("publish-city")); ?>" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/publish-icon.png" /><?php echo __('Publish', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for="unpublish" data-for-wpnonce="<?php echo esc_attr(wp_create_nonce("unpublish-city")); ?>" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/unbuplish.png" /><?php echo __('Unpublish', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for-wpnonce="<?php echo wp_create_nonce("delete-city"); ?>" confirmmessage="<?php echo __('Are you sure to delete','js-jobs').' ?'; ?>" data-for="removecity" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-jobs') ?></a>

    </div>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_city&jsjobslt=cities&countryid=$countryid&stateid=$stateid"),"city")); ?>">
        <?php echo wp_kses(JSJOBSformfield::text('searchname', jsjobs::$_data['filter']['searchname'], array('class' => 'inputbox', 'placeholder' => __('Name', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::select('status', JSJOBSincluder::getJSModel('common')->getstatus(), is_numeric(jsjobs::$_data['filter']['status']) ? jsjobs::$_data['filter']['status'] : '', __('Select Status', 'js-jobs'), array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::button('reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSJOBS_ALLOWED_TAGS); ?>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>
        <form id="jsjobs-list-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_city")); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="grid"><input type="checkbox" name="selectall" id="selectall" value=""></th>
                        <th class="left-row"><?php echo __('Name', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('Published', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Action', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pagenum = JSJOBSrequest::getVar('pagenum', 'get', 1);
                    $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                    for ($i = 0, $n = count(jsjobs::$_data[0]); $i < $n; $i++) {
                        $row = jsjobs::$_data[0][$i];
                        $link = wp_nonce_url(admin_url('admin.php?page=jsjobs_city&jsjobslt=formcity&jsjobsid=' . $row->id),"formcity");
                        ?>
                        <tr>
                            <td class="grid-rows">
                                <input type="checkbox" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo esc_attr($row->id); ?>" />
                            </td>
                            <td class="left-row">
                                <a href="<?php echo esc_url($link); ?>">
                                    <?php echo esc_html(__($row->name,'js-jobs')); ?></a>
                            </td>
                            <td width="20%">
                                <?php if ($row->enabled == '1') { ?>
                                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_city&task=unpublish&action=jsjobtask&jsjobs-cb[]='.$row->id.$pageid),'unpublish-city')); ?>">
                                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" alt="Default" border="0" />
                                    </a>
                                <?php } else { ?>
                                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_city&task=publish&action=jsjobtask&jsjobs-cb[]='.$row->id.$pageid),'publish-city')); ?>">
                                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" />
                                    </a>
                                <?php } ?>
                            </td>
                            <td class="action" width="20%">
                                <a href="<?php echo esc_url($link); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/edit.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_city&task=removecity&action=jsjobtask&jsjobs-cb[]='.$row->id),'delete-city')); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'js-jobs').' ?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/remove.png" title="<?php echo __('Delete', 'js-jobs'); ?>"></a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php echo wp_kses(JSJOBSformfield::hidden('action', 'city_remove'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('pagenum', ($pagenum > 1) ? $pagenum : ''), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('task', ''), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('delete-city')), JSJOBS_ALLOWED_TAGS); ?>
        </form>
        <?php
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(jsjobs::$_data[1]) . '</div></div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        $link[] = array(
                    'link' => wp_nonce_url(admin_url('admin.php?page=jsjobs_city&jsjobslt=formcity'),"formcity"),
                    'text' => __('Add New','js-jobs') .' '. __('City','js-jobs')
                );
        JSJOBSlayout::getNoRecordFound($msg,$link);
    }
    ?>
</div>
</div>
