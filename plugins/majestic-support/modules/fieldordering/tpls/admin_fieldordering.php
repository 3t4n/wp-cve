<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$majesticsupport_js ="
    function resetFrom() {
        document.getElementById('title').value = '';
        document.getElementById('categoryid').value = '';
        document.getElementById('type').value = '';
        document.getElementById('majesticsupportform').submit();
    }
    jQuery(document).ready(function () {
        jQuery('a#userpopup').click(function (e) {
            e.preventDefault();
            jQuery('div#userpopupblack').show();
            var f = jQuery(this).attr('data-id');
            jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'fieldordering', task: 'getOptionsForFieldEdit',field:f, '_wpnonce':'". esc_attr(wp_create_nonce("get-options-for-field-edit"))."'}, function (data) {
                if(data){
                    var abc = jQuery.parseJSON(data)
                    jQuery('div#userpopup').html('');
                    jQuery('div#userpopup').html(MJTC_msDecodeHTML(abc));
                }
            });
            jQuery('div#userpopup').slideDown('slow');
        });
        jQuery('span.close, div#userpopupblack').click(function (e) {
            jQuery('div#userpopup').slideUp('slow', function () {
                jQuery('div#userpopupblack').hide();
            });

        });
        jQuery('table#majestic-support-table tbody').sortable({
            handle : '.ms-order-grab-column',
            update  : function () {
                jQuery('.mjtc-form-button').slideDown('slow');
                var abc =  jQuery('table#majestic-support-table tbody').sortable('serialize');
                jQuery('input#fields_ordering_new').val(abc);
            }
        });
    });
    function close_popup(){
        jQuery('div#userpopup').slideUp('slow', function () {
            jQuery('div#userpopupblack').hide();
        });
    }

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
<?php
wp_enqueue_script('jquery-ui-sortable');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('majesticsupport-jquery-ui-css', MJTC_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');

MJTC_message::MJTC_getMessage(); ?>
<?php
$type = array(
    (object) array('id' => '1', 'text' => esc_html(__('Public', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Private', 'majestic-support')))
);
?>
<?php if(isset(majesticsupport::$_data['formid']) && majesticsupport::$_data['formid'] != null){ $mformid = majesticsupport::$_data['formid'];}else{ $mformid = MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId();} ?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('userfields'); ?>
        <div id="userpopupblack" style="display:none;"></div>
        <div id="userpopup" style="display:none;">
        </div>
        <div id="msadmin-data-wrp">
            <?php if (!empty(majesticsupport::$_data[0])) { ?>
                <form class="msadmin-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_majesticsupport&task=saveordering&formid=".esc_attr($mformid)),"save-ordering")); ?>">
                <table id="majestic-support-table">
                    <thead>
                    <tr class="majestic-support-table-heading">
                        <th><?php echo esc_html(__('Ordering', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('S.No', 'majestic-support')); ?></th>
                        <th class="left"><?php echo esc_html(__('Field Title', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('User Publish', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Visitor Publish', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Required', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Action', 'majestic-support')); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    $count = count(majesticsupport::$_data[0]) - 1;
                    foreach (majesticsupport::$_data[0] AS $field) {
                        if($field->field == 'wcorderid' || $field->field == 'wcproductid' || $field->field == 'wcitemid'){
                            if(!in_array('woocommerce', majesticsupport::$_active_addons)){
                                continue;
                            }
                            if(!class_exists('WooCommerce')){
                                continue;
                            }
                        }

                        if($field->field == 'eddorderid' || $field->field == 'eddproductid'){
                            if(!in_array('easydigitaldownloads', majesticsupport::$_active_addons)){
                                continue;
                            }
                            if(!class_exists('Easy_Digital_Downloads')){
                                continue;
                            }
                        }

                        if($field->field == 'eddlicensekey'){
                            if(!in_array('easydigitaldownloads', majesticsupport::$_active_addons)){
                                continue;
                            }
                            if(!class_exists('Easy_Digital_Downloads')){
                                continue;
                            }
                            if(!class_exists('EDD_Software_Licensing')){
                                continue;
                            }
                        }
                        if($field->field == 'wcitemid'){
                            continue;
                        }

                        if($field->field == 'envatopurchasecode'){
                            if(!in_array('envatovalidation', majesticsupport::$_active_addons)){
                                continue;
                            }
                        }

                        $alt = $field->published ? esc_html(__('Published','majestic-support')) : esc_html(__('Unpublished','majestic-support'));
                        $reqalt = $field->required ? esc_html(__('Required','majestic-support')) : esc_html(__('Not required','majestic-support'));
                        ?>
                        <tr id="id_<?php echo esc_attr($field->id); ?>">
                            <td class="mjtc-textaligncenter ms-order-grab-column">
                                <span class="majestic-support-table-responsive-heading">
                                    <?php echo esc_html(__('Ordering', 'majestic-support')); echo esc_html(" : "); ?>
                                </span>
                                <img alt="<?php echo esc_html(__('grab','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/list-full.png'?>"/>
                            </td>

                            <td>
                            <span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('S.No','majestic-support')); ?>:</span>
                            <?php echo esc_html($field->id); ?></td>
                            <td class="left">
                            <span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Field Title','majestic-support')); ?>:</span>
                                <?php
                                    if ($field->fieldtitle){
                                        $head = '<a title="'.esc_html(__('users popup','majestic-support')).'" href="#" id="userpopup" data-id='.esc_attr($field->id).'>'.esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)).'</a>';
                                        echo wp_kses($head, MJTC_ALLOWED_TAGS);
                                    } else {
                                        echo esc_html(majesticsupport::MJTC_getVarValue($field->userfieldtitle));
                                    }
                                    if($field->cannotunpublish == 1){
                                        echo wp_kses('<font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font>', MJTC_ALLOWED_TAGS);
                                    }
                                ?>
                            </td>
                            <td>
                            <span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('User Publish','majestic-support')); ?>:</span>
                                <?php if ($field->cannotunpublish == 1) { ?>
                                    <img height="15" width="15" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/good.png'; ?>" title="<?php echo esc_attr(__('Can Not Unpublished','majestic-support')); ?>" alt="<?php echo esc_html(__('good','majestic-support')); ?>" />
                                <?php }elseif ($field->published == 1) {
                                    $url  = "?page=majesticsupport_fieldordering&task=changepublishstatus&action=mstask&status=unpublish&fieldorderingid=".esc_attr($field->id).'&fieldfor='.esc_attr(majesticsupport::$_data['fieldfor']).'&formid='.esc_attr($field->multiformid);
                                         ?>
                                        <a title="<?php echo esc_attr(__('good','majestic-support')); ?>" href="<?php echo esc_url(wp_nonce_url($url, 'change-publish-status')); ?>" ><img height="15" width="15" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/good.png'; ?>" alt="<?php echo esc_html(__('good','majestic-support')); ?>" /></a>
                                <?php }else{
                                    $url  = "?page=majesticsupport_fieldordering&task=changepublishstatus&action=mstask&status=publish&fieldorderingid=".esc_attr($field->id).'&fieldfor='.esc_attr(majesticsupport::$_data['fieldfor']).'&formid='.esc_attr($field->multiformid);
                                         ?>
                                        <a title="<?php echo esc_attr(__('cross','majestic-support')); ?>" href="<?php echo esc_url(wp_nonce_url($url, 'change-publish-status')); ?>" ><img height="15" width="15" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/close.png'; ?>" alt="<?php echo esc_attr(__('cross','majestic-support')); ?>" /></a>
                                <?php } ?>
                            </td>
                            <td>
                            <span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Visitor Publish','majestic-support')); ?>:</span>
                                <?php if ($field->cannotunpublish == 1) { ?>
                                    <img height="15" width="15" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/good.png'; ?>" title="<?php echo esc_attr(__('Can Not Unpublished','majestic-support')); ?>" />
                                <?php }elseif ($field->isvisitorpublished == 1) {
                                    $url  = "?page=majesticsupport_fieldordering&task=changevisitorpublishstatus&action=mstask&status=unpublish&fieldorderingid=".esc_attr($field->id).'&fieldfor='.esc_attr(majesticsupport::$_data['fieldfor']).'&formid='.esc_attr($field->multiformid);
                                         ?>
                                        <a title="<?php echo esc_attr(__('good','majestic-support')); ?>" href="<?php echo esc_url(wp_nonce_url($url, 'change-visitor-publish-status')); ?>" ><img height="15" width="15" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/good.png'; ?>" alt="<?php echo esc_html(__('good','majestic-support')); ?>" /></a>
                                <?php }else{
                                    $url  = "?page=majesticsupport_fieldordering&task=changevisitorpublishstatus&action=mstask&status=publish&fieldorderingid=".esc_attr($field->id).'&fieldfor='.esc_attr(majesticsupport::$_data['fieldfor']).'&formid='.esc_attr($field->multiformid);
                                         ?>
                                        <a title="<?php echo esc_attr(__('cross','majestic-support')); ?>" href="<?php echo esc_url(wp_nonce_url($url, 'change-visitor-publish-status')); ?>" ><img height="15" width="15" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/close.png'; ?>" alt="<?php echo esc_html(__('cross','majestic-support')); ?>" /></a>
                                <?php } ?>
                            </td>
                            <td>
                            <span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Required','majestic-support')); ?>:</span>
                                <?php if ($field->cannotunpublish == 1 || ($field->userfieldtype == 'termsandconditions' && $field->required == 1) ) { ?>
                                    <img height="15" width="15" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/good.png'; ?>" alt="<?php echo esc_html(__('good','majestic-support')); ?>" title="<?php echo esc_attr(__('can not mark as not required','majestic-support')); ?>" />
                                <?php }elseif ($field->required == 1) {
                                    $url  = "?page=majesticsupport_fieldordering&task=changerequiredstatus&action=mstask&status=unrequired&fieldorderingid=".esc_attr($field->id).'&fieldfor='.esc_attr(majesticsupport::$_data['fieldfor']).'&formid='.esc_attr($field->multiformid);
                                         ?>
                                        <a title="<?php echo esc_attr(__('good','majestic-support')); ?>" href="<?php echo esc_url(wp_nonce_url($url, 'change-required-status')); ?>" ><img height="15" width="15" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/good.png'; ?>" alt="<?php echo esc_html(__('good','majestic-support')); ?>" /></a>
                                <?php }else{
                                    $url  = "?page=majesticsupport_fieldordering&task=changerequiredstatus&action=mstask&status=required&fieldorderingid=".esc_attr($field->id).'&fieldfor='.esc_attr(majesticsupport::$_data['fieldfor']).'&formid='.esc_attr($field->multiformid);
                                         ?>
                                        <a title="<?php echo esc_attr(__('Close','majestic-support')); ?>" href="<?php echo esc_url(wp_nonce_url($url, 'change-required-status')); ?>" ><img height="15" width="15" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/close.png'; ?>" title="<?php echo esc_attr(__('Close','majestic-support')); ?>" /></a>
                                <?php } ?>
                            </td>
                            <td>
                            <span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Action','majestic-support')); ?>:</span>
                                <?php
                                    if($field->isuserfield==1){
                                        $fieldData = '<a title="'.esc_html(__('Edit','majestic-support')).'" class="action-btn" href="?page=majesticsupport_fieldordering&mjslay=adduserfeild&majesticsupportid='.esc_attr($field->id).'&fieldfor='.esc_attr(majesticsupport::$_data['fieldfor']).'&formid='.esc_attr($field->multiformid).'"><img alt="'.esc_html(__('Edit','majestic-support')).'" src="'.esc_url(MJTC_PLUGIN_URL).'includes/images/edit.png" /></a>&nbsp;';
                                        $fieldData = '<a title="'.esc_html(__('Delete','majestic-support')).'" class="action-btn" onclick="return confirm(\''.esc_html(__('Are you sure you want to delete it?','majestic-support')).'\');" href="'.esc_url(wp_nonce_url('?page=majesticsupport_fieldordering&task=removeuserfeild&action=mstask&majesticsupportid='.esc_attr($field->id).'&fieldfor='.esc_attr(majesticsupport::$_data['fieldfor']).'&formid='.esc_attr($field->multiformid),'remove-userfeild')).'"><img alt="'.esc_html(__('Delete','majestic-support')).'" src="'.esc_url(MJTC_PLUGIN_URL).'includes/images/delete.png" /></a>';
                                        echo wp_kses($fieldData, MJTC_ALLOWED_TAGS); 
                                    }else{
                                        echo esc_html('---');
                                    }
                                ?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                 </tbody>
                 </table>
                 <?php echo wp_kses(MJTC_formfield::MJTC_hidden('fields_ordering_new', '123'), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ordering_for', 'fieldordering'), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('fieldfor', majesticsupport::$_data['fieldfor']), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('pagenum_for_ordering', MJTC_request::MJTC_getVar('pagenum', 'get', 1)), MJTC_ALLOWED_TAGS); ?>
                    <div class="mjtc-form-button" style="display: none;">
                        <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save Ordering', 'majestic-support')), array('class' => 'button mjtc-form-save')), MJTC_ALLOWED_TAGS); ?>
                    </div>
                </form>
                <div class="msadmin-help-msg">
                    <?php echo wp_kses('<font style="color:#1C6288;font-size:20px;margin:0px 5px;vertical-align: middle;">*</font>'.esc_html(__('Cannot unpublished field','majestic-support')), MJTC_ALLOWED_TAGS); ?>
                </div>
                <?php
            } else {
                MJTC_layout::MJTC_getNoRecordFound();
            }
            ?>
        </div>
    </div>
</div>
