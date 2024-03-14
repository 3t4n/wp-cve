<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jsjob-res-tables', JSJOBS_PLUGIN_URL . 'includes/js/responsivetable.js');
wp_enqueue_script('jquery-ui-sortable');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', JSJOBS_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');
?>
<div id="jsjobsadmin-wrapper">
    <div id="full_background" style="display:none;"></div>
    <div id="popup_main" style="display:none;"></div>
    <div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
        $msgkey = JSJOBSincluder::getJSModel('fieldordering')->getMessagekey();
        JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <a href="?page=jsjobs"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php 
            if(jsjobs::$_data['fieldfor'] == 1){
                echo __('Company','js-jobs');
            }elseif(jsjobs::$_data['fieldfor'] == 2){
                echo __('Job','js-jobs');
            }elseif(jsjobs::$_data['fieldfor'] == 3){
                echo __('Resume','js-jobs');
            }
            echo ' '.__('Fields', 'js-jobs');
        ?>
        <a class="js-button-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&jsjobslt=formuserfield&ff='.jsjobs::$_data["fieldfor"]),'formuserfield')) ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/add_icon.png" /><?php echo __('Add New','js-jobs') .'&nbsp;'. __('User Field', 'js-jobs') ?></a>
    </span>
    <div class="page-actions js-row no-margin ">
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for="fieldpublished" data-for-wpnonce="<?php echo esc_attr(wp_create_nonce("publish-fieldordering")); ?>" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/user-publish.png" /><?php echo __('User Publish', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for="fieldunpublished" data-for-wpnonce="<?php echo esc_attr(wp_create_nonce("unpublish-fieldordering")); ?>" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/user-unpublish.png" /><?php echo __('User Unpublished', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for="visitorfieldpublished" data-for-wpnonce="<?php echo esc_attr(wp_create_nonce("vpublish-fieldordering")); ?>" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/publish-icon.png" /><?php echo __('Visitor Publish', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for="visitorfieldunpublished" data-for-wpnonce="<?php echo esc_attr(wp_create_nonce("vunpublish-fieldordering")); ?>" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/unbuplish.png" /><?php echo __('Visitor Unpublished', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for="fieldrequired" data-for-wpnonce="<?php echo esc_attr(wp_create_nonce("required-fieldordering")); ?>" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" /><?php echo __('Required', 'js-jobs') ?></a>
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" data-for="fieldnotrequired" data-for-wpnonce="<?php echo esc_attr(wp_create_nonce("notrequired-fieldordering")); ?>" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" /><?php echo __('Not Required', 'js-jobs') ?></a>
    </div>

    <script >
        jQuery(document).ready(function () {
            jQuery("div#full_background").click(function () {
                closePopup();
            });
        });

        function resetFrom() {
            jQuery("input#title").val('');
            jQuery("select#ustatus").val('');
            jQuery("select#vstatus").val('');
            jQuery("select#required").val('');
            jQuery("form#jsjobsform").submit();
        }

        function showPopupAndSetValues(id) {
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'fieldordering', task: 'getOptionsForFieldEdit', field: id, wpnoncecheck:common.wp_jm_nonce}, function (data) {
                if (data) {
                    var d = (data);
                    jQuery("div#full_background").css("display", "block");
                    jQuery("div#popup_main").html(d);
                    jQuery("div#popup_main").slideDown('slow');
                }
            });
        }

        function closePopup() {
            jQuery("div#popup_main").slideUp('slow');
            setTimeout(function () {
                jQuery("div#full_background").hide();
                jQuery("div#popup_main").html('');
            }, 700);
        }
    </script>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_fieldordering&ff=" . jsjobs::$_data['fieldfor']),"fieldordering")); ?>">
        <?php echo wp_kses(JSJOBSformfield::text('title', jsjobs::$_data['filter']['title'], array('class' => 'inputbox', 'placeholder' => __('Title', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::select('ustatus', JSJOBSincluder::getJSModel('common')->getStatus(), is_numeric(jsjobs::$_data['filter']['ustatus']) ? jsjobs::$_data['filter']['ustatus'] : '', __('Select user status', 'js-jobs'), array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::select('vstatus', JSJOBSincluder::getJSModel('common')->getStatus(), is_numeric(jsjobs::$_data['filter']['vstatus']) ? jsjobs::$_data['filter']['vstatus'] : '', __('Select visitor status', 'js-jobs'), array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
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
        <form id="jsjobs-list-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_fieldordering")); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="grid"><input type="checkbox" name="selectall" id="selectall" value=""></th>
                        <th class="left-row"><?php echo __('Field Title', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('User Published', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('Visitor Published', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('Required', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('Ordering', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Action', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pagenum = JSJOBSrequest::getVar('pagenum', 'get', 1);
                    $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                    $islastordershow = JSJOBSpagination::isLastOrdering(jsjobs::$_data['total'], $pagenum);
                    for ($i = 0, $n = count(jsjobs::$_data[0]); $i < $n; $i++) {
                        $row = jsjobs::$_data[0][$i];

                        if (isset(jsjobs::$_data[0][$i + 1]))
                            $row1 = jsjobs::$_data[0][$i + 1];
                        else
                            $row1 = jsjobs::$_data[0][$i];

                        $uptask = 'fieldorderingup';
                        $downtask = 'fieldorderingdown';
                        $upimg = 'uparrow.png';
                        $downimg = 'downarrow.png';

                        $pubtask = $row->published ? 'fieldunpublished' : 'fieldpublished';
                        $noncepubtask = $row->published ? 'unpublish-fieldordering' : 'publish-fieldordering';
                        $pubimg = $row->published ? 'tick.png' : 'publish_x.png';
                        $alt = $row->published ? __('Published', 'js-jobs') : __('Unpublished', 'js-jobs');

                        $visitorpubtask = $row->isvisitorpublished ? 'visitorfieldunpublished' : 'visitorfieldpublished';
                        $visitorpubimg = $row->isvisitorpublished ? 'tick.png' : 'publish_x.png';
                        $visitoralt = $row->isvisitorpublished ? __('Published', 'js-jobs') : __('Unpublished', 'js-jobs');

                        $requiredtask = $row->required ? 'fieldnotrequired' : 'fieldrequired';
                        $requiredpubimg = $row->required ? 'tick.png' : 'publish_x.png';
                        $requiredalt = $row->required ? __('Required', 'js-jobs') : __('Not Required', 'js-jobs');
                        ?>
                        <tr valign="top">
                            <td class="grid-rows">
                                <input type="checkbox" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo esc_attr($row->id); ?>" />
                            </td>
                            <?php
                            $sec = jsjobslib::jsjobs_substr($row->field, 0, 8); //get section_
                            $newsection = 0;
                            if ($sec == 'section_') {
                                $newsection = 1;
                                $subsec = jsjobslib::jsjobs_substr($row->field, 0, 12);
                                if ($subsec == 'section_sub_') {
                                    ?>
                                    <td class="left-row" ><strong><?php echo esc_html(__($row->fieldtitle,'js-jobs')); ?></strong></td>
                                <?php } else { ?>
                                    <td class="left-row" ><strong><font size="2"><?php echo esc_html(__($row->fieldtitle,'js-jobs')); ?></font></strong></td>
                                <?php } ?>
                                <td>
                                    <?php if ($row->cannotunpublish == 1) { ?>
                                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" title="<?php echo __('Cannot unpublished', 'js-jobs'); ?>" />
                                    <?php
                                    } else {
                                        $icon_name = "no.png";
                                        $task = "fieldpublished";
                                        $noncepubtask = "publish-fieldordering";
                                        if ($row->published == 1) {
                                            $task = "fieldunpublished";
                                            $icon_name = "yes.png";
                                            $noncepubtask = "unpublish-fieldordering";
                                        }
                                        ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&task='.$task.'&action=jsjobtask&jsjobs-cb[]='.$row->id.$pageid.'&ff='.jsjobs::$_data['fieldfor']),$noncepubtask)); ?>">
                                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/<?php echo esc_attr($icon_name); ?>" alt="<?php echo esc_attr($alt); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($row->cannotunpublish == 1) { ?>
                                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" title="<?php echo __('Cannot unpublished', 'js-jobs'); ?>" />
                                    <?php
                                    } else {
                                        $icon_name = "no.png";
                                        $task = "visitorfieldpublished";
                                        $vnoncepubtask = "vpublish-fieldordering";
                                        if ($row->isvisitorpublished == 1) {
                                            $task = "visitorfieldunpublished";
                                            $icon_name = "yes.png";
                                            $vnoncepubtask = "vunpublish-fieldordering";
                                        }
                                        ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&task='.$task.'&action=jsjobtask&jsjobs-cb[]'.$row->id.$pageid.'&ff='.jsjobs::$_data['fieldfor']),$vnoncepubtask)); ?>">
                                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/<?php echo esc_attr($icon_name); ?>" alt="<?php echo esc_attr($visitoralt); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                    <?php } else { ?>
                                <td class="left-row">
                                    <?php echo esc_html(__($row->fieldtitle,'js-jobs')); ?>
                                </td>
                                <td>
                                    <?php if ($row->cannotunpublish == 1) { ?>
                                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" title="<?php echo __('Cannot unpublished', 'js-jobs'); ?>" />
                                    <?php
                                    } else {
                                        $icon_name = "no.png";
                                        $task = "fieldpublished";
                                        $noncepubtask = "publish-fieldordering";
                                        if ($row->published == 1) {
                                            $task = "fieldunpublished";
                                            $icon_name = "yes.png";
                                            $noncepubtask = "unpublish-fieldordering";
                                        }
                                        ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&task='.$task.'&action=jsjobtask&jsjobs-cb[]='.$row->id.$pageid.'&ff='.jsjobs::$_data['fieldfor']),$noncepubtask)); ?>">
                                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/<?php echo esc_attr($icon_name); ?>" alt="<?php echo esc_attr($alt); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($row->cannotunpublish == 1) { ?>
                                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" title="<?php echo __('Cannot unpublished', 'js-jobs'); ?>" />
                                    <?php
                                    } else {
                                        $icon_name = "no.png";
                                        $task = "visitorfieldpublished";
                                        $vnoncepubtask = "vpublish-fieldordering";
                                        if ($row->isvisitorpublished == 1) {
                                            $task = "visitorfieldunpublished";
                                            $icon_name = "yes.png";
                                            $vnoncepubtask = "vunpublish-fieldordering";
                                        }
                                        ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&task='.$task.'&action=jsjobtask&jsjobs-cb[]='.$row->id.$pageid.'&ff='.jsjobs::$_data['fieldfor']),$vnoncepubtask)); ?>">
                                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/<?php echo esc_attr($icon_name); ?>" alt="<?php echo esc_attr($visitoralt); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($row->sys == 1) { ?>
                                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" title="<?php echo __('Cannot un-required', 'js-jobs'); ?>" />
                                    <?php
                                    } else {
                                        $icon_name = "no.png";
                                        $task = "fieldrequired";
                                        $noncepubtask = "required-fieldordering";
                                        if ($row->required == 1) {
                                            $task = "fieldnotrequired";
                                            $icon_name = "yes.png";
                                            $noncepubtask = "notrequired-fieldordering";
                                        }
                                        ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&task='.$task.'&action=jsjobtask&jsjobs-cb[]='.$row->id.$pageid.'&ff='.jsjobs::$_data['fieldfor']),$noncepubtask)); ?>">
                                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/<?php echo esc_attr($icon_name); ?>" alt="<?php echo esc_attr($requiredalt); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                       <?php
                                       if ($row->ordering != 1) {
                                           if ($newsection != 1) {
					        $noncepubtask = "fieldup-fieldordering";
                                               ?>
						<a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&task=fieldorderingup&action=jsjobtask&fieldid='.$row->id.$pageid.'&ff='.jsjobs::$_data['fieldfor']),$noncepubtask)); ?>">
                                                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/uparrow.png" alt="Order Up" />
                                            </a>
                                        <?php
                                        } else
                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                                    } else
                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                                    ?>  
                                    &nbsp;&nbsp;<?php echo $row->ordering; ?>&nbsp;&nbsp;
                                    <?php
                                    //if ($i < $n-1) { 
                                    if ($row->section == $row1->section) {
                                        $noncepubtask = "fielddown-fieldordering";
                                        ?>
										<a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&task=fieldorderingdown&action=jsjobtask&fieldid='.$row->id.$pageid.'&ff='.jsjobs::$_data['fieldfor']),$noncepubtask)); ?>">
                                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/downarrow.png" alt="Order Down" />
                                        </a>
                                <?php }
                                ?>
                                </td>
                                <td class="action">
                                    <a href="#" onclick="showPopupAndSetValues(<?php echo esc_attr($row->id); ?>)" ><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/edit.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                                    <?php if ($row->isuserfield == 1) { ?>  
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&task=remove&action=jsjobtask&fieldid='.$row->id.'&ff='.jsjobs::$_data['fieldfor']),'delete-fieldordering')); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'js-jobs').' ?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/remove.png" title="<?php echo __('Delete', 'js-jobs'); ?>"></a>
                                    <?php } ?>
                                </td>
                    <?php
                    $newsection = 0;
                }
                ?>

                        </tr>
            <?php
        }
        ?>
                </tbody>
            </table>
        <?php echo wp_kses(JSJOBSformfield::hidden('task', ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('pagenum', ($pagenum > 1) ? $pagenum : ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('fieldfor',jsjobs::$_data['fieldfor']), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('ff',jsjobs::$_data['fieldfor']), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('delete-fieldordering')), JSJOBS_ALLOWED_TAGS); ?>
        </form>
    <?php
    if (jsjobs::$_data[1]) {
        echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(jsjobs::$_data[1]) . '</div></div>';
    }
} else {
    $msg = __('No record found','js-jobs');
    $link[] = array(
                'link' => wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&jsjobslt=formuserfield&ff='.jsjobs::$_data['fieldfor']),"formuserfield"),
                'text' => __('Add New','js-jobs') .' '. __('User Field','js-jobs')
            );
    JSJOBSlayout::getNoRecordFound($msg,$link);
}
?>
</div>
</div>
