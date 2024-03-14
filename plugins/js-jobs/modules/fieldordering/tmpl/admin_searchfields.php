<?php
   if(!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jsjob-res-tables', JSJOBS_PLUGIN_URL . 'includes/js/responsivetable.js');
wp_enqueue_script('jquery-ui-sortable');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', JSJOBS_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');
  $searchable_combo = array(
        (object) array('id' => 1, 'text' => __('Enabled', 'js-jobs')),
        (object) array('id' => 0, 'text' => __('Disabled', 'js-jobs')));
?>
<div id="jsjobsadmin-wrapper">
    <div id="full_background" style="display:none;"></div>
    <div id="popup_main" style="display:none;">
        <span class="popup-top">
            <span id="popup_title" >
            </span> 
            <img id="popup_cross" alt="popup cross" onClick="closePopup();" src="<?php echo  JSJOBS_PLUGIN_URL;?>includes/images/popup-close.png">
        </span>
        <form id="jsjobs-form" class="popup-field-from" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_fieldordering&task=savesearchfieldordering&action=jsjobtask"),"savesearch-fieldordering"));?>">
            <div class="popup-field-wrapper">
                <div class="popup-field-title"><?php echo  __('User Search', 'js-jobs');?></div>
                <div class="popup-field-obj"><?php echo  wp_kses(JSJOBSformfield::select('search_user', $searchable_combo, 0, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS);?></div>
            </div>
            <div class="popup-field-wrapper">
                <div class="popup-field-title"><?php echo  __('Visitor Search', 'js-jobs');?></div>
                <div class="popup-field-obj"><?php echo  wp_kses(JSJOBSformfield::select('search_visitor', $searchable_combo, 0, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS);?></div>
            </div>
            <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('id',''), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('fieldfor',jsjobs::$_data['fieldfor']), JSJOBS_ALLOWED_TAGS); ?>
            <div class="js-submit-container js-col-lg-10 js-col-md-10 js-col-md-offset-1 js-col-md-offset-1">
                <?php echo  wp_kses(JSJOBSformfield::submitbutton('save', __('Save', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
            </div>
            <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('savesearch-fieldordering')), JSJOBS_ALLOWED_TAGS); ?>
        </form>
    </div>
    <div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <script >
        jQuery(document).ready(function () {
            jQuery("div#full_background").click(function () {
                closePopup();
            });
            jQuery('table#js-table tbody').sortable({
                handle : ".grid-rows , .left-row",
                update  : function () {
                    var abc =  jQuery('table#js-table tbody').sortable('serialize');
                    jQuery('input#fields_ordering_new').val(abc);
                }
                
            });
        });

        function showPopupAndSetValues(id,title_string, search_user, search_visitor) {
            jQuery("select#search_user").val(search_user);
            jQuery("select#search_visitor").val(search_visitor);
            jQuery("input#id").val(id);
            jQuery("span#popup_title").html(title_string);
            jQuery("div#full_background").css("display", "block");
            jQuery("div#popup_main").slideDown('slow');
        }

        function closePopup() {
            jQuery("div#popup_main").slideUp('slow');
            setTimeout(function () {
                jQuery("div#full_background").hide();
            }, 700);
        }
    </script>
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('fieldordering')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey); 
    $search_combo = array(
        (object) array('id' => 0, 'text' => __('Search Fields', 'js-jobs')),
        (object) array('id' => 1, 'text' => __('All Fields', 'js-jobs')));
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php 
            if(jsjobs::$_data['fieldfor'] == 1){
                echo __('Company','js-jobs');
            }elseif(jsjobs::$_data['fieldfor'] == 2){
                echo __('Job','js-jobs');
            }elseif(jsjobs::$_data['fieldfor'] == 3){
                echo __('Resume','js-jobs');
            }
            echo ' '.__('Search Fields', 'js-jobs');
        ?>
    </span>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_fieldordering&jsjobslt=searchfields"),"searchfields")); ?>">
        <?php echo wp_kses(JSJOBSformfield::select('search', $search_combo, jsjobs::$_data['filter']['search'], '', array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::submitbutton('btnsubmit', __('Go', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('fieldfor', jsjobs::$_data['fieldfor']), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('ff', jsjobs::$_data['fieldfor']), JSJOBS_ALLOWED_TAGS); ?>
    </form>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>
        <form  id="jsjobs-form" class="search-fields-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_fieldordering&action=jsjobtask&task=savesearchfieldorderingFromForm")); ?>">
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="left-row"><?php echo __('Title', 'js-jobs'); ?></th>
                        <th class="search_combo"><?php echo __('User Search', 'js-jobs'); ?></th>
                        <th class="search_combo"><?php echo __('Visitor Search', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Edit', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0, $n = count(jsjobs::$_data[0]); $i < $n; $i++) {
                        $row = jsjobs::$_data[0][$i];
                        ?>
                        <tr valign="top" id="id_<?php echo esc_attr($row->id); ?>" >
                            <td class="left-row" style="cursor:grab;">
                                <?php echo esc_html(__($row->fieldtitle,'js-jobs')); ?>
                            </td>
                            <td  >
                                 <?php if($row->search_user == 1){ ?>
                                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" title="<?php echo __('Cannot unpublished', 'js-jobs'); ?>" />
                                 <?php }else{ ?>
                                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" title="<?php echo __('Cannot unpublished', 'js-jobs'); ?>" />
                                 <?php } ?>
                            </td>
                            <td  >
                                <?php if($row->search_visitor == 1){ ?>
                                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" title="<?php echo __('Cannot unpublished', 'js-jobs'); ?>" />
                                <?php }else{ ?>
                                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" title="<?php echo __('Cannot unpublished', 'js-jobs'); ?>" />
                                <?php } ?>
                            </td>
                            <td class="action" >
                                <a href="#" onclick="showPopupAndSetValues(<?php echo esc_attr($row->id); ?>,'<?php echo esc_attr($row->fieldtitle);?>', <?php echo esc_attr($row->search_user);?>, <?php echo esc_attr($row->search_visitor);?>)" ><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/edit.png" title="<?php echo __('Edit', 'js-jobs'); ?>"></a>
                            </td>
                        </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
            <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('fieldfor', jsjobs::$_data['fieldfor']), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('fields_ordering_new',''), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('savesearch-fieldordering')), JSJOBS_ALLOWED_TAGS); ?>
            <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
                <a id="form-cancel-button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&ff='.jsjobs::$_data['fieldfor']),"fieldordering")); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
                <?php echo wp_kses(JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Ordering', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
            </div>
        </form>
        <?php
    }
    ?>
</div>
</div>
