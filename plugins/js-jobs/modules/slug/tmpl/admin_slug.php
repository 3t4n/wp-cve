<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jsjob-res-tables', JSJOBS_PLUGIN_URL . 'includes/js/responsivetable.js');
?>
<div id="jsjobsadmin-wrapper">
    <div id="full_background" style="display:none;"></div>
    <div id="popup_main" style="display:none;"></div>
    <div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('slug')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey); 
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
    <?php 
        echo __('Slug', 'js-jobs');
    ?>
    <a class="js-button-link button reset-all-slug" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_slug&task=resetallslugs&action=jsjobtask'),'saveprefix-slug')); ?>"><?php echo __('Resest All', 'js-jobs') ?></a>
    </span>
    <script >/*Function to Show popUp,Reset*/
        var slug_for_edit = 0;
        jQuery(document).ready(function () {
        jQuery("div#full_background").click(function () {
          closePopup();
           });
       });
             
    function resetFrom() {// Resest Form
        jQuery("input#slug").val('');
        jQuery("form#jsjobsform").submit();
    }

    function showPopupAndSetValues(id,slug) {//Showing PopUp
        slug = jQuery('td#td_'+id).html();
        slug_for_edit = id;
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'slug', task: 'getOptionsForEditSlug',id:id ,slug:slug, wpnoncecheck:common.wp_jm_nonce }, function (data) {
            if (data) {
                var d = jQuery.parseJSON(data);
                jQuery("div#full_background").css("display", "block");
                jQuery("div#popup_main").html(JSJOBSDecodeHTML(d));
                jQuery("div#popup_main").slideDown('slow');
            }
        });
    }
    function closePopup() {// Close PopUp
        jQuery("div#popup_main").slideUp('slow');
        setTimeout(function () {
        jQuery("div#full_background").hide();
        jQuery("div#popup_main").html('');
        }, 700);
    }
    function getFieldValue() {
        var slugvalue = jQuery("#slugedit").val();
        jQuery('input#'+slug_for_edit).val(slugvalue);
        jQuery('td#td_'+slug_for_edit).html(slugvalue);
        closePopup();
    }
</script>
    <form class="js-filter-form slug-configform" name="jsjobsform" id="conjsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_slug&task=savehomeprefix"),"savehomeprefix-slug")); ?>">
     <?php echo wp_kses(JSJOBSformfield::text('prefix', jsjobs::$_configuration['home_slug_prefix'], array('class' => 'inputbox', 'placeholder' => __('Home Slug','js-jobs').' '. __('Prefix', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?> 
        <?php echo wp_kses(JSJOBSformfield::submitbutton('btnsubmit', __('Save', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <span class="slug-prefix-msg" ><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/view-job-information.png" /><?php echo __('This prefix will be added to slug incase of homepage links','js-jobs')?></span>
        <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('savehomeprefix-slug')), JSJOBS_ALLOWED_TAGS); ?>
     </form>

    <form class="js-filter-form slug-configform" name="jsjobsform" id="conjsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_slug&task=saveprefix"),"saveprefix-slug")); ?>">
     <?php echo wp_kses(JSJOBSformfield::text('prefix', jsjobs::$_configuration['slug_prefix'], array('class' => 'inputbox', 'placeholder' => __('Slug','js-jobs').' '. __('Prefix', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?> 
        <?php echo wp_kses(JSJOBSformfield::submitbutton('btnsubmit', __('Save', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <span class="slug-prefix-msg" ><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/view-job-information.png" /><?php echo __('This prefix will be added to slug incase of conflict','js-jobs')?></span>
        <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('saveprefix-slug')), JSJOBS_ALLOWED_TAGS); ?>
     </form>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_slug"),"slug")); ?>">
        <?php echo wp_kses(JSJOBSformfield::text('slug', jsjobs::$_data['slug'], array('class' => 'inputbox', 'placeholder' => __('Search By Slug', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::button('reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'), JSJOBS_ALLOWED_TAGS); ?>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>
        <form id="jsjobs-list-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_slug&task=saveSlug")); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="left-row"><?php echo __('Slug List', 'js-jobs'); ?></th>
                        <th class="left-row"><?php echo __('Description', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Action', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pagenum = JSJOBSrequest::getVar('pagenum', 'get', 1);
                    $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                    foreach (jsjobs::$_data[0] as $row){
                        ?>
                        <tr valign="top">
                            <td class="left-row" id="<?php echo 'td_'.esc_attr($row->id);?>"><?php echo esc_html($row->slug); ?></td>
                            <td class="left-row"><?php echo __($row->description,'jsjobs');?></td>
                            <td class="action">
                            <a href="#" onclick="showPopupAndSetValues(<?php echo esc_js($row->id); ?>)">
                                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/edit.png" title="<?php echo __('Edit','js-jobs'); ?>"> </a></td>
                        </tr>
                            <?php echo wp_kses(JSJOBSformfield::hidden($row->id, $row->slug), JSJOBS_ALLOWED_TAGS);?>
                        <?php
                         }
                        ?>
                 </tbody>
            </table>
                <!-- Hidden Fields -->
                <div class="submit-button-slug-form-wrap" >
                    <?php echo wp_kses(JSJOBSformfield::submitbutton('btnsubmit', __('Save', 'js-jobs'), array('class' => 'button savebutton')), JSJOBS_ALLOWED_TAGS); ?>
                    <span class="slug-save-msg" > <?php echo  __('This button will only save slugs on current page','js-jobs'); ?> !</span>
                </div>
                
                <?php echo wp_kses(JSJOBSformfield::hidden('task', ''), JSJOBS_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('pagenum', ($pagenum > 1) ? $pagenum : ''), JSJOBS_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('save-slug')), JSJOBS_ALLOWED_TAGS); ?>
        </form>
     <?php
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(jsjobs::$_data[1]) . '</div></div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        $link[] = array(
                    'link' => 'admin.php?page=jsjobs_slug&jsjobslt=formcareerlevels',
                );
        JSJOBSlayout::getNoRecordFound($msg, $link);
    }
    ?>
</div>
</div>
