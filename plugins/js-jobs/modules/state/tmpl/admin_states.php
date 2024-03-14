<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script >
    function confirmdelete() {
        if (confirm("<?php echo __('Are you sure to delete','js-jobs') . ' ?'; ?>") == true) {
            return false;
        } else {
            event.preventDefualt();
            return false;
        }
        return false;
    }

    function resetFrom() {
        jQuery("input#searchname").val('');
        jQuery("select#status").val('');
        jQuery("#city1").prop('checked', false);
        jQuery("form#jsjobsform").submit();
    }

</script>
<?php wp_enqueue_script('jsjob-res-tables', JSJOBS_PLUGIN_URL . 'includes/js/responsivetable.js'); ?>
<div id="jsjobsadmin-wrapper">
    <div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 

    $msgkey = JSJOBSincluder::getJSModel('state')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_country')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('States', 'js-jobs') ?>
        <a class="js-button-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_state&jsjobslt=formstate'),"formstate")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/add_icon.png" /><?php echo __('Add','js-jobs') .'&nbsp;'. __('New State', 'js-jobs') ?></a>
    </span>
    <div class="page-actions js-row no-margin">
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for="publish" data-for-wpnonce="<?php echo esc_attr(wp_create_nonce("publish-state")); ?>" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/publish-icon.png" /><?php echo __('Publish', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for="unpublish" data-for-wpnonce="<?php echo esc_attr(wp_create_nonce("unpublish-state")); ?>" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/unbuplish.png" /><?php echo __('Unpublish', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" confirmmessage="<?php echo __('Are you sure to delete','js-jobs') . ' ?'; ?>" data-for="remove" data-for-wpnonce="<?php echo esc_attr(wp_create_nonce("delete-state")); ?>" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-jobs') ?></a>
    </div>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_state&jsjobslt=states"),"state")); ?>">
        <?php echo wp_kses(JSJOBSformfield::text('searchname', jsjobs::$_data['filter']['searchname'], array('class' => 'inputbox', 'placeholder' => __('Name', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::select('status', JSJOBSincluder::getJSModel('common')->getstatus(), is_numeric(jsjobs::$_data['filter']['status']) ? jsjobs::$_data['filter']['status'] : '', __('Select Status', 'js-jobs'), array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
        <div class="checkbox">
            <?php echo wp_kses(JSJOBSformfield::checkbox('city', array('1' => __('Has cities', 'js-jobs')), isset(jsjobs::$_data['filter']['city']) ? jsjobs::$_data['filter']['city'] : 0, array('class' => 'checkbox')), JSJOBS_ALLOWED_TAGS); ?>
        </div>
        <?php echo wp_kses(JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::button('reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSJOBS_ALLOWED_TAGS); ?>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>  
        <form id="jsjobs-list-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_state")); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="grid"><input type="checkbox" name="selectall" id="selectall" value=""></th>
                        <th class="left-row"><?php echo __('Name', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('Published', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('Cities', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Action', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pagenum = JSJOBSrequest::getVar('pagenum', 'get', 1);
                    $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                    for ($i = 0, $n = count(jsjobs::$_data[0]); $i < $n; $i++) {
                        $row = jsjobs::$_data[0][$i];
                        $link = wp_nonce_url(admin_url('admin.php?page=jsjobs_state&jsjobslt=formstate&jsjobsid=' . $row->id),"formstate");
                        ?>
                        <tr>
                            <td class="grid-rows">
                                <input type="checkbox" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo esc_attr($row->id); ?>" />
                            </td>
                            <td class="left-row">
                                <a href="<?php echo esc_url($link); ?>">
                                    <?php echo esc_html(__($row->name,'js-jobs')); ?></a>
                            </td>
                            <td>
                                <?php if ($row->enabled == '1') { ?>
                                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_state&task=unpublish&action=jsjobtask&jsjobs-cb[]='.$row->id.$pageid),'unpublish-state')); ?>">
                                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" alt="Default" border="0" />
                                    </a>
                                   <?php } else { ?>
                                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_state&task=publish&action=jsjobtask&jsjobs-cb[]='.$row->id.$pageid),'publish-state')); ?>">
                                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" />
                                    </a>
        <?php } ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_city&stateid='.$row->id.'&countryid='.$row->countryid)); ?>"><?php echo __('Cities', 'js-jobs') ?></a>
                            </td>
                            <td class="action">
                                <a href="<?php echo esc_url($link); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/edit.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_state&task=remove&action=jsjobtask&jsjobs-cb[]='.$row->id),'delete-state')); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'js-jobs').' ?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/remove.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php echo wp_kses(JSJOBSformfield::hidden('action', 'state_remove'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('pagenum', ($pagenum > 1) ? $pagenum : ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('task', ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('delete-state')), JSJOBS_ALLOWED_TAGS); ?>
        </form>
        <?php
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(jsjobs::$_data[1]) . '</div></div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        $link[] = array(
                    'link' => wp_nonce_url(admin_url('admin.php?page=jsjobs_state&jsjobslt=formstate'),"formstate"),
                    'text' => __('Add New','js-jobs') .' '. __('State','js-jobs')
                );
        JSJOBSlayout::getNoRecordFound();
    }
    ?>
</div>
</div>
