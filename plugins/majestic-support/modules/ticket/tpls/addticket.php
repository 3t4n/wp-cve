<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest() && majesticsupport::$_config['show_captcha_on_visitor_from_ticket'] == 1 && majesticsupport::$_config['captcha_selection'] == 1) {
    wp_enqueue_script( 'majesticsupport-recaptcha', 'https://www.google.com/recaptcha/api.js' );
}
?>
<div class="ms-main-up-wrapper">
    <?php
if (majesticsupport::$_config['offline'] == 2) {
    if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid() != 0 || majesticsupport::$_config['visitor_can_create_ticket'] == 1) {
        MJTC_message::MJTC_getMessage();
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('majesticsupport-file_validate.js', MJTC_PLUGIN_URL . 'includes/js/file_validate.js');

		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		wp_enqueue_style('majesticsupport-jquery-ui-css', MJTC_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');
        ?>
        <?php
        $majesticsupport_js ="
            function onSubmit(token) {
                document.getElementById('adminTicketform').submit();
            }
            jQuery(document).ready(function($) {
                $('.custom_date').datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                jQuery('#tk_attachment_add').click(function() {
                    var obj = this;
                    var current_files = jQuery('input[name=\'filename[]\']').length;
                    var total_allow = ". majesticsupport::$_config['no_of_attachement'].";
                    var append_text =
                        '<span class=\"tk_attachment_value_text\"><input name=\"filename[]\" type=\"file\" onchange=\"MJTC_uploadfile(this,\"". esc_js(majesticsupport::$_config['file_maximum_size'])."\",". esc_js(majesticsupport::$_config['file_extension'])."\"); size=\"20\" maxlenght=\"30\"  /><span  class=\"tk_attachment_remove\"></span></span>';
                    if (current_files < total_allow) {
                        jQuery('.tk_attachment_value_wrapperform').append(append_text);
                    } else if ((current_files === total_allow) || (current_files > total_allow)) {
                        alert('". esc_html(__('File upload limit exceeds', 'majestic-support'))."');
                        jQuery(obj).hide();
                    }
                });
                jQuery(document).delegate('.tk_attachment_remove', 'click', function(e) {
                    jQuery(this).parent().remove();
                    var current_files = jQuery('input[name=\'filename[]\']').length;
                    var total_allow = ". majesticsupport::$_config['no_of_attachement'].";
                    if (current_files < total_allow) {
                        jQuery('#tk_attachment_add').show();
                    }
                });
                $.validate();
            });
            // to get premade and append to isssue summery
            function getHelpTopicByDepartment(val) {
                jQuery.post(ajaxurl, {
                    action: 'mjsupport_ajax',
                    val: val,
                    mjsmod: 'department',
                    task: 'getHelpTopicByDepartment',
                    '_wpnonce':'". esc_attr(wp_create_nonce('get-help-topic-by-department'))."'
                }, function(data) {
                    if (data != false) {
                        jQuery('div#helptopic').html(data);
                    } else {
                        jQuery('div#helptopic').html('<div class=\"helptopic-no-rec\">". esc_html(__('No help topic found','majestic-support'))."</div>');
                    }
                }); //jquery closed
            }

            // woocommerce
            function ms_wc_order_products() {
                var orderid = jQuery('#wcorderid').val();
                emptycombo =
                    '<select name=\"wcproductid\" id=\"wcproductid\"  class=\"inputbox mjtc-form-select-field mjtc-support-select-field\" ><option value=\"\">Select Product</option></select>';
                jQuery('#wcproductid-wrap').html(emptycombo);
                jQuery.post(
                    ajaxurl, {
                        action: 'mjsupport_ajax',
                        mjsmod: 'woocommerce',
                        task: 'getWcOrderProductsAjax',
                        orderid: orderid,
                        '_wpnonce':'". esc_attr(wp_create_nonce("get-wcorder-products-ajax"))."'
                    },
                    function(data) {
                        data1 = JSON.parse(data);
                        jQuery('#wcproductid-wrap').html(data1['html']);
                    }
                );
            }

            function ms_edd_order_products() {
                var orderid = jQuery('select#eddorderid').val();
                jQuery.post(ajaxurl, {
                    action: 'mjsupport_ajax',
                    mjsmod: 'easydigitaldownloads',
                    task: 'getEDDOrderProductsAjax',
                    eddorderid: orderid,
                    '_wpnonce':'". esc_attr(wp_create_nonce("get-eddorder-products-ajax"))."'
                }, function(data) {
                    jQuery('#eddproductid-wrap').html(data);
                });
            }

            function ms_eed_product_licenses() {
                var eddproductid = jQuery('select#eddproductid').val();
                var orderid = jQuery('select#eddorderid').val();
                jQuery.post(ajaxurl, {
                    action: 'mjsupport_ajax',
                    mjsmod: 'easydigitaldownloads',
                    task: 'getEDDProductlicensesAjax',
                    eddproductid: eddproductid,
                    eddorderid: orderid,
                    '_wpnonce':'". esc_attr(wp_create_nonce("get-edd-productlicenses-ajax"))."'
                }, function(data) {
                    jQuery('#eddlicensekey-wrap').html(data);
                });
            }

            jQuery(document).ready(function() {
                jQuery('select#eddorderid').change(function() {
                    ms_edd_order_products();
                });";
                if(!isset(majesticsupport::$_data[0]->id)){ 
                    $majesticsupport_js .="
                    if (jQuery('select#eddorderid').val()) {
                        ms_edd_order_products();
                    }";
                }
                $majesticsupport_js .="
                jQuery(document).on('change', 'select#eddproductid', function() {
                    ms_eed_product_licenses();
                });
                if (jQuery('select#eddproductid').val()) {
                    ms_eed_product_licenses();
                }

                jQuery('#wcorderid').change(function() {
                    ms_wc_order_products();
                });
                if (jQuery('#wcorderid').val()) {
                    ms_wc_order_products();
                }
            });
        ";
        wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
        ?>
    <span style="display:none" id="filesize"><?php echo esc_html(__('Error file size too large', 'majestic-support')); ?></span>
    <span style="display:none"
        id="fileext"><?php echo esc_html(__('The uploaded file extension not valid', 'majestic-support')); ?></span>
    <?php
        $loginuser_name = '';
        $loginuser_email = '';
        if (!MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
            $current_user = MJTC_includer::MJTC_getObjectClass('user')->MJTC_getMSCurrentUser();
            if(empty($current_user->display_name) == true){
                $loginuser_name = $current_user->user_nicename;
            }else{
                $loginuser_name = $current_user->display_name;
            }
            $loginuser_email = $current_user->user_email;
        }
    ?>
    <?php MJTC_message::MJTC_getMessage(); ?>
    <?php $formdata = MJTC_formfield::MJTC_getFormData(); ?>
    <?php include_once(MJTC_PLUGIN_PATH . 'includes/header.php'); ?>
    <div class="mjtc-support-top-sec-header">
        <img class="mjtc-transparent-header-img1" alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tp-image.png" />
        <div class="mjtc-support-top-sec-left-header">
            <div class="mjtc-support-main-heading">
                <?php echo esc_html(__("Submit Ticket",'majestic-support')); ?>
            </div>
            <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageBreadcrumps('addticketuser'); ?>
        </div>
    </div>
    <div class="mjtc-support-cont-main-wrapper">
        <div class="mjtc-support-cont-wrapper1">
            <?php
            if (majesticsupport::$_config['new_ticket_message']) { ?>
                <div class="mjtc-col-xs-12 mjtc-col-md-12 mjtc-support-form-instruction-message">
                    <?php echo wp_kses(majesticsupport::$_config['new_ticket_message'], MJTC_ALLOWED_TAGS); ?>
                </div>
                <?php
            } ?>
            <div class="mjtc-support-add-form-wrapper mjtc-support-add-form-main-wrapper">
                <?php
                $showform = true;
                if(in_array('paidsupport', majesticsupport::$_active_addons) && class_exists('WooCommerce')){
                    if(isset(majesticsupport::$_data['paidsupport'])){
                        $row = majesticsupport::$_data['paidsupport'];
                        $paidsupportid = $row->itemid; ?>
                        <div class="majestic-support-paid-support-info">
                            <h3>
                                <?php echo esc_html(__("Paid support info",'majestic-support')); ?>
                            </h3>
                            <table border="1" class="majestic-support-paid-support-info-table">
                                <tr>
                                    <th><?php echo esc_html(__("Order ID",'majestic-support')); ?></th>
                                    <th><?php echo esc_html(__("Product Name",'majestic-support')); ?></th>
                                    <th><?php echo esc_html(__("Total Tickets",'majestic-support')); ?></th>
                                    <th><?php echo esc_html(__("Remaining Tickets",'majestic-support')); ?></th>
                                </tr>
                                <tr>
                                    <td>#<?php echo esc_html($row->orderid); ?></td>
                                    <td><?php
                                        echo esc_html($row->itemname);
                                        if($row->qty > 1){
                                            $tkt = '<b> x '.esc_html($row->qty)."</b>";
                                            echo wp_kses($tkt, MJTC_ALLOWED_TAGS);
                                        }
                                        ?></td>
                                    <td>
                                        <?php
                                        if($row->total == -1) {
                                            echo esc_html(__("Unlimited",'majestic-support'));
                                        } else {
                                            echo esc_html($row->total);
                                        } ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($row->total == -1){
                                            echo esc_html(__("Unlimited",'majestic-support'));
                                        } else {
                                            echo esc_html($row->remaining);
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php
                    } elseif(isset(majesticsupport::$_data['paidsupportitems'])) {
                        $showform = false;
                        $paidsupportitems = majesticsupport::$_data['paidsupportitems'];
                        if(empty($paidsupportitems)){ ?>
                            <div class="mjtc-support-error-message-wrapper mjtc-support-cont-wrapper-color">
                                <div class="mjtc-support-message-image-wrapper">
                                    <img class="mjtc-support-message-image" alt="message image"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL).'/includes/images/error/not-permission-icon.png'; ?>">
                                </div>
                                <div class="mjtc-support-messages-data-wrapper">
                                    <span class="mjtc-support-messages-main-text">
                                        <?php echo esc_html(__("You have not purchased any supported item",'majestic-support')); ?>
                                    </span>
                                    <span class="mjtc-support-user-login-btn-wrp">
                                        <a class="mjtc-support-login-btn"
                                            href="<?php echo esc_url(get_permalink( wc_get_page_id( 'shop' ) )); ?>"><?php echo esc_html(__("Go to shop",'majestic-support')); ?></a>
                                    </span>
                                </div>
                            </div>
                            <?php
                        } else { ?>
                            <h3>
                                <?php echo esc_html(__("Please select paid support item",'majestic-support')); ?>
                                <span style="color:red">*</span>
                            </h3>
                            <table border="1">
                                <tr>
                                    <th><?php echo esc_html(__("Order ID",'majestic-support')); ?></th>
                                    <th><?php echo esc_html(__("Product Name",'majestic-support')); ?></th>
                                    <th><?php echo esc_html(__("Total Tickets",'majestic-support')); ?></th>
                                    <th><?php echo esc_html(__("Remaining Tickets",'majestic-support')); ?></th>
                                    <th></th>
                                </tr>
                                <?php
                                foreach($paidsupportitems as $row){ ?>
                                    <tr>
                                        <td>#<?php echo esc_html($row->orderid); ?></td>
                                        <td>
                                            <?php
                                            echo esc_html($row->itemname);
                                            if($row->qty > 1){
                                                $tkt = '<b> x '.esc_html($row->qty)."</b>";
                                                echo wp_kses($tkt, MJTC_ALLOWED_TAGS);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if($row->total == -1){
                                                echo esc_html(__("Unlimited",'majestic-support'));
                                            } else {
                                                echo esc_html($row->total);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if($row->total == -1){
                                                echo esc_html(__("Unlimited",'majestic-support'));
                                            } else {
                                                echo esc_html($row->remaining);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'addticket','paidsupportid'=>$row->itemid))); ?>">
                                                <?php echo esc_html(__("Select",'majestic-support')) ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                } ?>
                            </table>
                            <?php
                        }
                    }
                }
                if($showform): ?>
                    <form class="mjtc-support-form1" method="post" action="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'task'=>'saveticket')),"save-ticket")); ?>"id="adminTicketform" enctype="multipart/form-data">
                        <?php
                        $i = '';
                        $fieldcounter = 0;
                        $eddorderid = '';
                        $requiredTxt = '&nbsp;<span style="color:red">*</span>';
                        $openingTag = '<div class="mjtc-support-add-form-wrapper">';
                        $closingTag = '</div>';
                        apply_filters('mjtc_support_ticket_frontend_ticket_form_start',1);
                        foreach (majesticsupport::$_data['fieldordering'] AS $field):
                            switch ($field->field) {
                                case 'email':
                                    if($fieldcounter % 2 == 0){
                                        if($fieldcounter != 0){
                                            echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                        }
                                        echo wp_kses($openingTag, MJTC_ALLOWED_TAGS);
                                    }
                                    $fieldcounter++; ?>
                                    <div class="mjtc-support-from-field-wrp">
                                        <div class="mjtc-support-from-field-title">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;
                                            <?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                                        </div>
                                        <div class="mjtc-support-from-field">
                                            <?php
                                            if(isset($formdata['email'])) $email = $formdata['email'];
                                            elseif(isset(majesticsupport::$_data[0]->email)) $email = majesticsupport::$_data[0]->email;
                                            else $email = $loginuser_email;
                                            echo wp_kses(MJTC_formfield::MJTC_email('email', $email, array('class' => 'inputbox mjtc-support-form-field-input', 'data-validation' => ($field->required) ? 'required email' : '')), MJTC_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                    <?php
                                    break;
                                case 'fullname':
                                    if($fieldcounter % 2 == 0){
                                        if($fieldcounter != 0){
                                            echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                        }
                                        echo wp_kses($openingTag, MJTC_ALLOWED_TAGS);
                                    }
                                    $fieldcounter++; ?>
                                    <div class="mjtc-support-from-field-wrp">
                                        <div class="mjtc-support-from-field-title">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;
                                            <?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?></div>
                                        <div class="mjtc-support-from-field">
                                            <?php
                                            if(isset($formdata['name'])) $name = $formdata['name'];
                                            elseif(isset(majesticsupport::$_data[0]->name)) $name = majesticsupport::$_data[0]->name;
                                            else $name = $loginuser_name;
                                            echo wp_kses(MJTC_formfield::MJTC_text('name', $name, array('class' => 'inputbox mjtc-support-form-field-input', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS);
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                    break;
                                case 'phone':
                                    if($fieldcounter % 2 == 0){
                                        if($fieldcounter != 0){
                                            echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                        }
                                        echo wp_kses($openingTag, MJTC_ALLOWED_TAGS);
                                    }
                                    $fieldcounter++; ?>
                                    <div class="mjtc-support-from-field-wrp">
                                        <div class="mjtc-support-from-field-title">
                                            <?php
                                            echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                                        </div>
                                        <div class="mjtc-support-from-field">
                                            <?php
                                            if(isset($formdata['phone'])) $phone = $formdata['phone'];
                                            elseif(isset(majesticsupport::$_data[0]->phone)) $phone = majesticsupport::$_data[0]->phone;
                                            else $phone = '';
                                            echo wp_kses(MJTC_formfield::MJTC_text('phone', $phone, array('class' => 'inputbox mjtc-support-form-field-input', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS);
                                            ?>
                                        </div>
                                    </div>
                                        <?php
                                break;
                            case 'phoneext':
                                if($fieldcounter % 2 == 0){
                                    if($fieldcounter != 0){
                                        echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                    }
                                    echo wp_kses($openingTag, MJTC_ALLOWED_TAGS);
                                }
                                $fieldcounter++;
                                ?>
                        <div class="mjtc-support-from-field-wrp">
                            <div class="mjtc-support-from-field-title">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <div class="mjtc-support-from-field">
                                <?php
                                            if(isset($formdata['phoneext'])) $phoneext = $formdata['phoneext'];
                                            elseif(isset(majesticsupport::$_data[0]->phoneext)) $phoneext = majesticsupport::$_data[0]->phoneext;
                                            else $phoneext = '';
                                            echo wp_kses(MJTC_formfield::MJTC_text('phoneext', $phoneext, array('class' => 'inputbox mjtc-support-form-field-input', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS);
                                        ?>
                            </div>
                        </div>
                        <?php
                                break;
                            case 'department':
                                if($fieldcounter % 2 == 0){
                                    if($fieldcounter != 0){
                                        echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                    }
                                    echo wp_kses($openingTag, MJTC_ALLOWED_TAGS);
                                }
                                $fieldcounter++;
                                ?>
                        <div class="mjtc-support-from-field-wrp">
                            <div class="mjtc-support-from-field-title">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <div class="mjtc-support-from-field mjtc-support-form-field-select">
                                <?php 
    										$disabled ="";
                                            if(isset($formdata['departmentid'])) $departmentid = $formdata['departmentid'];
                                            elseif(isset(majesticsupport::$_data[0]->departmentid)) $departmentid = majesticsupport::$_data[0]->departmentid;
                                            elseif(MJTC_request::MJTC_getVar('departmentid','get',0) > 0) $departmentid = MJTC_request::MJTC_getVar('departmentid','get');
                                            else $departmentid = MJTC_includer::MJTC_getModel('department')->getDefaultDepartmentID();
    										if(isset(majesticsupport::$_data['formid'])){
    											if(in_array('multiform',majesticsupport::$_active_addons)){
    												$departmentid = MJTC_includer::MJTC_getModel('multiform')->getDepartmentIdByFormId(majesticsupport::$_data['formid']);
    												if($departmentid > 0){
    												}
    											}
    											
    										}
                                            $msVisibleFunction = '';
                                            $defaultFunc = '';
                                            // code for visible field
                                            if ($field->visible_field != null) {
                                                $visibleparams = MJTC_includer::MJTC_getModel('fieldordering')->MJTC_getDataForVisibleField($field->visible_field);
                                                foreach ($visibleparams as $visibleparam) {
                                                    $wpnonce = wp_create_nonce("is-field-required");
                                                    $msVisibleFunction .= " MJTC_getDataForVisibleField('".$wpnonce."', this.value, '" . $visibleparam->visibleParent . "','" . $visibleparam->visibleParentField . "','".$visibleparam->visibleValue."','".$visibleparam->visibleCondition."');";
                                                    //for default value
                                                    if (($visibleparam->visibleValue == $departmentid && $visibleparam->visibleCondition == 1) || ($visibleparam->visibleValue != $departmentid && $departmentid != 0 && $visibleparam->visibleCondition == 0)) {
                                                        $defaultFunc .= " MJTC_getDataForVisibleField('".$wpnonce."', '".$departmentid."', '" . $visibleparam->visibleParent . "','" . $visibleparam->visibleParentField . "','".$visibleparam->visibleValue."','".$visibleparam->visibleCondition."');";
                                                    }
                                                }
                                                $script = '';
                                                if (isset($defaultFunc) && !isset(majesticsupport::$_data[0]->id)) {
                                                    $majesticsupport_js ='
                                                        jQuery(document).ready(function(){
                                                            '.esc_js($defaultFunc).'
                                                        });
                                                    ';
                                                    wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
                                                }
                                            }
    										if($disabled == ""){
    											echo wp_kses(MJTC_formfield::MJTC_select('departmentid', MJTC_includer::MJTC_getModel('department')->getDepartmentForCombobox(), $departmentid, esc_html(__('Select Department', 'majestic-support')), array('class' => 'inputbox mjtc-support-select-field', 'onchange' => $msVisibleFunction.' getHelpTopicByDepartment(this.value);', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS);
    										}else{
    											echo wp_kses(MJTC_formfield::MJTC_select('departmentid', MJTC_includer::MJTC_getModel('department')->getDepartmentForCombobox(), $departmentid, esc_html(__('Select Department', 'majestic-support')), array('class' => 'inputbox mjtc-support-select-field', 'onchange' => $msVisibleFunction.' getHelpTopicByDepartment(this.value);', 'data-validation' => ($field->required) ? 'required' : '','disabled'=>'disabled')), MJTC_ALLOWED_TAGS);
    										}
                                        ?>
                            </div>
                        </div>
                        <?php
                                break;
                            case 'helptopic':
                                if(!in_array('helptopic', majesticsupport::$_active_addons)){
                                    break;
                                }
                                if($fieldcounter % 2 == 0){
                                    if($fieldcounter != 0){
                                        echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                    }
                                    echo wp_kses($openingTag, MJTC_ALLOWED_TAGS);
                                }
                                $fieldcounter++;
                                ?>
                        <div class="mjtc-support-from-field-wrp">
                            <div class="mjtc-support-from-field-title">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <div class="mjtc-support-from-field mjtc-support-form-field-select" id="helptopic">
                                <?php
                                            if(isset($formdata['helptopicid'])) $helptopicid = $formdata['helptopicid'];
                                            elseif(isset(majesticsupport::$_data[0]->helptopicid)) $helptopicid = majesticsupport::$_data[0]->helptopicid;
                                            elseif(MJTC_request::MJTC_getVar('helptopicid','get',0) > 0) $helptopicid = MJTC_request::MJTC_getVar('helptopicid','get');
                                            else $helptopicid = '';
                                            if (isset($departmentid)) {
                                                $dep_id = $departmentid;
                                            } else{
                                                $dep_id = 0;
                                            }
                                            echo wp_kses(MJTC_formfield::MJTC_select('helptopicid', MJTC_includer::MJTC_getModel('helptopic')->getHelpTopicsForCombobox($dep_id), $helptopicid, esc_html(__('Select Help Topic', 'majestic-support')), array('class ' => 'mjtc-support-select-field', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS);
                                        ?>
                            </div>
                        </div>
                        <?php
                                break;
                            case 'priority':
                                if($fieldcounter % 2 == 0){
                                    if($fieldcounter != 0){
                                        echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                    }
                                    echo wp_kses($openingTag, MJTC_ALLOWED_TAGS);
                                }
                                $fieldcounter++;
                                ?>
                                <div class="mjtc-support-from-field-wrp">
                                    <div class="mjtc-support-from-field-title">
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                                    </div>
                                    <div class="mjtc-support-from-field mjtc-support-form-field-select">
                                        <?php
                                            if(isset($formdata['priorityid'])) $priorityid = $formdata['priorityid'];
                                            elseif(isset(majesticsupport::$_data[0]->priorityid)) $priorityid = majesticsupport::$_data[0]->priorityid;
                                            else $priorityid = MJTC_includer::MJTC_getModel('priority')->getDefaultPriorityID();
                                            echo wp_kses(MJTC_formfield::MJTC_select('priorityid', MJTC_includer::MJTC_getModel('priority')->getPriorityForCombobox(), $priorityid, esc_html(__('Select Priority', 'majestic-support')), array('class' => 'inputbox mjtc-support-select-field', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS);
                                        ?>
                                    </div>
                                </div>
                                <?php
                                break;
                            case 'subject':
                                if($fieldcounter != 0){
                                    echo '</div>';
                                    $fieldcounter = 0;
                                } ?>
                                <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                                    <div class="mjtc-support-from-field-title">
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<span
                                            style="color:red">*</span></div>
                                    <div class="mjtc-support-from-field">
                                        <?php
                                            if(isset($formdata['subject'])) $subject = $formdata['subject'];
                                            elseif(isset(majesticsupport::$_data[0]->subject)) $subject = majesticsupport::$_data[0]->subject;
                                            else $subject = '';
                                            echo wp_kses(MJTC_formfield::MJTC_text('subject', $subject, array('class' => 'inputbox mjtc-support-form-field-input', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS);
                                        ?>
                                    </div>
                                </div>
                                <?php
                                break;
                            case 'issuesummary':
                                if($fieldcounter != 0){
                                    echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                    $fieldcounter = 0;
                                }
                                ?>
                                <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                                    <div class="mjtc-support-from-field-title">
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?></div>
                                    <div class="mjtc-support-from-field">
                                        <?php
                                            if(isset($formdata['message'])) $message = wpautop(wptexturize(MJTC_majesticsupportphplib::MJTC_stripslashes($formdata['message'])));
                                            elseif(isset(majesticsupport::$_data[0]->message)) $message = majesticsupport::$_data[0]->message;
                                            else $message = '';
                                            wp_editor($message, 'mjsupport_message', array('media_buttons' => false));
                                            /*
                                            * Use following settings for minimal editor as all are offering
                                            */
                                        ?>
                                    </div>
                                </div>
                                <?php
                                break;
                            case 'attachments':
                                if($fieldcounter != 0){
                                    echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                    $fieldcounter = 0;
                                }
                                ?>
                        <div class="mjtc-support-reply-attachments">
                            <!-- Attachments -->
                            <div class="mjtc-attachment-field-title">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?><?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <?php
                                    if(isset(majesticsupport::$_data[5]) && count(majesticsupport::$_data[5]) > 0){
                                        $attachmentreq = '';
                                    }else{
                                        $attachmentreq = $field->required == 1 ? 'required' : '';
                                    }
                                    ?>
                            <div class="mjtc-attachment-field">
                                <div class="tk_attachment_value_wrapperform tk_attachment_user_reply_wrapper">
                                    <span class="tk_attachment_value_text">
                                        <input type="file" class="inputbox mjtc-attachment-inputbox" name="filename[]"
                                            onchange="MJTC_uploadfile(this, '<?php echo esc_js(majesticsupport::$_config['file_maximum_size']); ?>', '<?php echo esc_js(majesticsupport::$_config['file_extension']); ?>');"
                                            size="20" data-validation="<?php echo esc_attr($attachmentreq); ?>" />
                                        <span class='tk_attachment_remove'></span>
                                    </span>
                                </div>
                                <span class="tk_attachments_configform">
                                    <?php 
                                    $tktdata = esc_html(__('Maximum File Size', 'majestic-support')).' (' . esc_html(majesticsupport::$_config['file_maximum_size']).'KB)<br>'.esc_html(__('File Extension Type', 'majestic-support')).' (' . esc_html(majesticsupport::$_config['file_extension']) . ')';
                                    echo wp_kses($tktdata, MJTC_ALLOWED_TAGS);
                                     ?>
                                </span>
                                <span id="tk_attachment_add" data-ident="tk_attachment_user_reply_wrapper"
                                    class="tk_attachments_addform"><?php echo esc_html(__('Add more', 'majestic-support')); ?></span>
                            </div>
                            <?php if (!empty(majesticsupport::$_data[5])) {
                                            foreach (majesticsupport::$_data[5] AS $attachment) {
                                                echo wp_kses('
                                                    <div class="mjtc-support-attached-files-wrp">
                                                         <div class="mjtc_supportattachment">
                                                                 ' . esc_html($attachment->filename) . ' ( ' . esc_html($attachment->filesize) . ' ) ' . '
                                                        </div>
                                                        <a class="mjtc-support-delete-attachment" href="'.wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'attachment', 'task'=>'deleteattachment', 'action'=>'mstask', 'id'=>$attachment->id, 'tikcetid'=>majesticsupport::$_data[0]->id, 'mspageid'=>majesticsupport::getPageid())),'delete-attachement') . '">' . esc_html(__('Remove','majestic-support')) . '</a>
                                                    </div>', MJTC_ALLOWED_TAGS);
                                             }
                                     } ?>
                        </div>
                        <?php
                                break;
                                case 'wcorderid':
                                    if(!in_array('woocommerce', majesticsupport::$_active_addons)){
                                        break;
                                    }
                                    if(!class_exists('WooCommerce')){
                                        break;
                                    }
                                    if($fieldcounter % 2 == 0){
                                        if($fieldcounter != 0){
                                            echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                        }
                                        echo wp_kses($openingTag, MJTC_ALLOWED_TAGS);
                                    }
                                    $fieldcounter++;

                                    $orderlist = array();
    								if(get_current_user_id() > 0){
    									foreach(wc_get_orders(array('customer_id'=>MJTC_includer::MJTC_getObjectClass('user')->MJTC_wpuid(),'post_status' => 'wc-completed')) as $order){ // wp uid because of woocommerce store wp uid
    										$orderlist[] = (object) array('id' => $order->get_id(),'text'=>'#'.esc_html($order->get_id()).' - '.esc_html($order->get_date_created()->date_i18n(wc_date_format())));
    									}
    								}
                                    if(isset($formdata['wcorderid'])) $wcorderid = $formdata['wcorderid'];
                                    elseif(isset(majesticsupport::$_data[0]->wcorderid)) $wcorderid = majesticsupport::$_data[0]->wcorderid;
                                    else $wcorderid = '';  ?>
                        <div class="mjtc-support-from-field-wrp">
                            <div class="mjtc-support-from-field-title">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <div class="mjtc-support-from-field mjtc-support-form-field-select">
                                <?php echo wp_kses(MJTC_formfield::MJTC_select('wcorderid', $orderlist, $wcorderid, esc_html(__('Select Order', 'majestic-support')), array('class' => 'inputbox mjtc-support-select-field', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                        <?php
                                    break;
                                case 'wcproductid':
                                    if(!in_array('woocommerce', majesticsupport::$_active_addons)){
                                        break;
                                    }
                                    if(!class_exists('WooCommerce')){
                                        break;
                                    }
                                    if($fieldcounter % 2 == 0){
                                        if($fieldcounter != 0){
                                            echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                        }
                                        echo wp_kses($openingTag, MJTC_ALLOWED_TAGS);
                                    }
                                    $fieldcounter++;

                                    $itemlist = array();
                                    if(isset($formdata['wcproductid'])) $wcproductid = $formdata['wcproductid'];
                                    elseif(isset(majesticsupport::$_data[0]->wcproductid)) $wcproductid = majesticsupport::$_data[0]->wcproductid;
                                    else $wcproductid = '';  ?>
                        <div class="mjtc-support-from-field-wrp">
                            <div class="mjtc-support-from-field-title">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <div class="mjtc-support-from-field mjtc-support-form-field-select" id="wcproductid-wrap">
                                <?php echo wp_kses(MJTC_formfield::MJTC_select('wcproductid', $itemlist, $wcproductid, esc_html(__('Select Product', 'majestic-support')), array('class' => 'inputbox mjtc-support-select-field', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                        <?php
                                    break;
                                case 'eddorderid':
                                    if(!in_array('easydigitaldownloads', majesticsupport::$_active_addons)){
                                        break;
                                    }
                                    if(!class_exists('Easy_Digital_Downloads')){
                                        break;
                                    }
                                    if($fieldcounter % 2 == 0){
                                        if($fieldcounter != 0){
                                            echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                        }
                                        echo wp_kses($openingTag, MJTC_ALLOWED_TAGS);
                                    }
                                    $fieldcounter++;

                                    $itemlist = array();

                                    if(isset($formdata['eddorderid'])) $eddorderid = $formdata['eddorderid'];
                                    elseif(isset(majesticsupport::$_data[0]->eddorderid)) $eddorderid = majesticsupport::$_data[0]->eddorderid;
                                    elseif(isset(majesticsupport::$_data['edd_order_id'])) $eddorderid = majesticsupport::$_data['edd_order_id'];
                                    $user_id = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
                                    if(is_numeric($user_id) && $user_id > 0){
                                        $user_purchases = edd_get_users_purchases($user_id);
                                        $user_purchase_array = array();
                                        if (is_array($user_purchases) || $user_purchases instanceof Countable) {
                                            foreach ($user_purchases AS $user_purchase) {
                                                $user_purchase_array[] = (object) array('id' => $user_purchase->ID, 'text' => '#'.esc_html($user_purchase->ID).'&nbsp;('. esc_html(__('Dated','majestic-support')).':&nbsp;' .date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($user_purchase->post_date)).')');
                                            }
                                        }
                                         ?>
                        <div class="mjtc-support-from-field-wrp">
                            <div class="mjtc-support-from-field-title">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <div class="mjtc-support-from-field mjtc-support-form-field-select" id="eddorderid-wrap">
                                <?php echo wp_kses(MJTC_formfield::MJTC_select('eddorderid', $user_purchase_array, $eddorderid, esc_html(__('Select Order ID', 'majestic-support')), array('class' => 'inputbox mjtc-support-select-field', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                        <?php
                                    }else{ ?>
                        <div class="mjtc-support-from-field-wrp">
                            <div class="mjtc-support-from-field-title">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <div class="mjtc-support-from-field mjtc-support-form-field-select" id="eddorderid-wrap">
                                <?php  echo wp_kses(MJTC_formfield::MJTC_text('eddorderid', $eddorderid, array('class' => 'inputbox mjtc-support-form-field-input', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                        <?php
                                    }
                                    break;
                                case 'eddproductid':
                                    if(!in_array('easydigitaldownloads', majesticsupport::$_active_addons)){
                                        break;
                                    }
                                    if(!class_exists('Easy_Digital_Downloads')){
                                        break;
                                    }
                                    if(is_numeric($user_id) && $user_id > 0){
                                        if($fieldcounter % 2 == 0){
                                            if($fieldcounter != 0){
                                                echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                            }
                                            echo wp_kses($openingTag, MJTC_ALLOWED_TAGS);
                                        }
                                        $fieldcounter++;

                                        $order_products_array = array();
                                        if($eddorderid != '' && is_numeric($eddorderid)){
                                            $order_products = edd_get_payment_meta_cart_details($eddorderid);
                                            foreach ($order_products as $order_product) {
                                                $order_products_array[] = (object) array('id'=>$order_product['id'], 'text'=>$order_product['name']);
                                            }
                                        }

                                        if(isset($formdata['eddproductid'])) $eddproductid = $formdata['eddproductid'];
                                        elseif(isset(majesticsupport::$_data[0]->eddproductid)) $eddproductid = majesticsupport::$_data[0]->eddproductid;
                                        else $eddproductid = '';  ?>
                        <div class="mjtc-support-from-field-wrp">
                            <div class="mjtc-support-from-field-title">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <div class="mjtc-support-from-field" id="eddproductid-wrap">
                                <?php echo wp_kses(MJTC_formfield::MJTC_select('eddproductid', $order_products_array, $eddproductid, esc_html(__('Select Product', 'majestic-support')), array('class' => 'inputbox mjtc-form-select-field', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                        <?php
                                }
                                    break;
                                case 'eddlicensekey':
                                    if(!in_array('easydigitaldownloads', majesticsupport::$_active_addons)){
                                        break;
                                    }
                                    if(!class_exists('Easy_Digital_Downloads')){
                                        break;
                                    }
                                    if(!class_exists('EDD_Software_Licensing')){
                                        break;
                                    }
                                    if($fieldcounter % 2 == 0){
                                        if($fieldcounter != 0){
                                            echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                        }
                                        echo wp_kses($openingTag, MJTC_ALLOWED_TAGS);
                                    }
                                    $fieldcounter++;
                                    $license_key_array = array();
                                    if($eddorderid != '' && is_numeric($eddorderid)){
                                        $license = EDD_Software_Licensing::instance();
                                        $result = $license->get_licenses_of_purchase($eddorderid);
                                        foreach ($result AS $license_record) {
                                            $license_record_licensekey = $license->get_license_key($license_record->ID);
                                            if($license_record_licensekey != ''){
                                                $license_key_array[] = (object) array('id' => $license_record_licensekey,'text' => $license_record_licensekey);
                                            }
                                        }
                                    }

                                    $itemlist = array();
                                    if(isset($formdata['eddlicensekey'])) $eddlicensekey = $formdata['eddlicensekey'];
                                    elseif(isset(majesticsupport::$_data[0]->eddlicensekey)) $eddlicensekey = majesticsupport::$_data[0]->eddlicensekey;
                                    else $eddlicensekey = '';
                                    $user_id = MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid();
                                    if(is_numeric($user_id) && $user_id > 0){
                                    ?>
                        <div class="mjtc-support-from-field-wrp">
                            <div class="mjtc-support-from-field-title">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <div class="mjtc-support-from-field mjtc-support-form-field-select" id="eddlicensekey-wrap">
                                <?php echo wp_kses(MJTC_formfield::MJTC_select('eddlicensekey', $license_key_array, $eddlicensekey, esc_html(__('Select license key', 'majestic-support')), array('class' => 'inputbox mjtc-support-select-field', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                        <?php
                                    }else{
                                        ?>
                        <div class="mjtc-support-from-field-wrp">
                            <div class="mjtc-support-from-field-title">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <div class="mjtc-support-from-field mjtc-support-form-field-select" id="eddlicensekey-wrap">
                                <?php  echo wp_kses(MJTC_formfield::MJTC_text('eddlicensekey', $eddlicensekey, array('class' => 'inputbox mjtc-support-form-field-input', 'data-validation' => ($field->required) ? 'required' : '')), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                        <?php
                                    }
                                    break;
                                case 'envatopurchasecode':
                                    if(!in_array('envatovalidation', majesticsupport::$_active_addons)){
                                        break;
                                    }
                                    if(!empty(majesticsupport::$_data[0]->envatodata)){
                                        $envlicense = json_decode(majesticsupport::$_data[0]->envatodata, true);
                                    }else{
                                        $envlicense = array();
                                    }
                                    if($fieldcounter % 2 == 0){
                                        if($fieldcounter != 0){
                                            echo wp_kses($closingTag, MJTC_ALLOWED_TAGS);
                                        }
                                        echo wp_kses($openingTag, MJTC_ALLOWED_TAGS);
                                    }
                                    $fieldcounter++;

                                    if(isset($formdata['envatopurchasecode'])) $envatopurchasecode = $formdata['envatopurchasecode'];
                                    elseif(isset($envlicense['license'])) $envatopurchasecode = $envlicense['license'];
                                    else $envatopurchasecode = '';  ?>
                        <div class="mjtc-support-from-field-wrp">
                            <div class="mjtc-support-from-field-title">
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>&nbsp;<?php if($field->required == 1) echo wp_kses($requiredTxt, MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <div class="mjtc-support-from-field mjtc-support-form-field-select"
                                id="envatopurchasecode-wrap">
                                <?php echo wp_kses(MJTC_formfield::MJTC_text('envatopurchasecode', $envatopurchasecode, array('class' => 'inputbox mjtc-support-form-field-input','data-validation'=>($field->required ? 'required' : ''))), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                        <?php
                                    break;
                            default:
                                $customfields = MJTC_includer::MJTC_getObjectClass('customfields')->MJTC_formCustomFields($field);
                                if (isset($customfields)) {
                                    echo wp_kses($customfields, MJTC_ALLOWED_TAGS);
                                }
                                break;
                        }

                    endforeach;
                    if($fieldcounter != 0){
                        echo wp_kses($closingTag, MJTC_ALLOWED_TAGS); // close extra div open in user field
                    }
                    $tktufield = '<input type="hidden" id="userfeilds_total" name="userfeilds_total"  value="' . esc_attr($i) . '"  />';
                    echo wp_kses($tktufield, MJTC_ALLOWED_TAGS);
                    ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('id', isset(majesticsupport::$_data[0]->id) ? majesticsupport::$_data[0]->id : ''), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('multiformid', isset(majesticsupport::$_data['formid']) ? majesticsupport::$_data['formid'] : '1'), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('attachmentdir', isset(majesticsupport::$_data[0]->attachmentdir) ? majesticsupport::$_data[0]->attachmentdir : ''), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ticketid', isset(majesticsupport::$_data[0]->ticketid) ? majesticsupport::$_data[0]->ticketid : ''), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('created', isset(majesticsupport::$_data[0]->created) ? majesticsupport::$_data[0]->created : ''), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('uid', MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid()), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('updated', isset(majesticsupport::$_data[0]->updated) ? majesticsupport::$_data[0]->updated : ''), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mspageid', get_the_ID()), MJTC_ALLOWED_TAGS); ?>
                    <?php
                    if(isset($paidsupportid)){
                        echo wp_kses(MJTC_formfield::MJTC_hidden('paidsupportid', $paidsupportid), MJTC_ALLOWED_TAGS);
                    }
                    // captcha
                    $google_recaptcha_3 = false;
                    if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
                        if (majesticsupport::$_config['show_captcha_on_visitor_from_ticket'] == 1) {  ?>
                        <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                            <div class="mjtc-support-from-field-title">
                                <?php echo esc_html(__('Captcha', 'majestic-support')); ?>
                            </div>
                            <div class="mjtc-support-from-field">
                                <?php
                                    if (majesticsupport::$_config['captcha_selection'] == 1) { // Google recaptcha
                                        $error = null;
                                        if (majesticsupport::$_config['recaptcha_version'] == 1) {
                                            $captchaTxt = '<div class="g-recaptcha" data-sitekey="'.esc_attr(majesticsupport::$_config['recaptcha_publickey']).'"></div>';
                                            echo wp_kses($captchaTxt, MJTC_ALLOWED_TAGS);
                                        } else {
                                            $google_recaptcha_3 = true;
                                        }
                                    } else { // own captcha
                                        $captcha = new MJTC_captcha;
                                        echo wp_kses($captcha->MJTC_getCaptchaForForm(), MJTC_ALLOWED_TAGS);
                                    }
                                    ?>
                            </div>
                        </div>
                        <?php
                        }
                    } ?>
                    <div class="mjtc-support-form-btn-wrp">
                        <?php
                        if($google_recaptcha_3 == true && MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()){ // to handle case of google recpatcha version 3
                            echo wp_kses(MJTC_formfield::MJTC_button('save', esc_html(__('Submit Ticket', 'majestic-support')), array('class' => 'mjtc-support-save-button g-recaptcha', 'data-callback' => 'onSubmit', 'data-action' => 'submit', 'data-sitekey' => esc_attr(majesticsupport::$_config['recaptcha_publickey']))), MJTC_ALLOWED_TAGS);
                        } else {
                            echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Submit Ticket', 'majestic-support')), array('class' => 'mjtc-support-save-button')), MJTC_ALLOWED_TAGS);
                        } ?>
                        <a href="<?php echo esc_url(esc_url(majesticsupport::makeUrl(array('mjsmod'=>'majesticsupport', 'mjslay'=>'controlpanel'))));?>" class="mjtc-support-cancel-button">
                            <?php echo esc_html(__('Cancel','majestic-support'));?>
                        </a>
                    </div>
                </form>
                <?php endif; ?>
            </div>
            <?php
    } else {// User is guest
        $redirect_url = majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'addticket'));
        $redirect_url = MJTC_majesticsupportphplib::MJTC_safe_encoding($redirect_url);
        MJTC_layout::MJTC_getUserGuest($redirect_url);
    }
} else { // System is offline
    MJTC_layout::MJTC_getSystemOffline();
}
?>
        </div>
    </div>
</div>
