<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$majesticsupport_js ="
    jQuery(document).ready(function ($) {
        $.validate();
        jQuery('#termsandconditions_linktype').on('change', function() {
            if(this.value == 1){
                jQuery('.for-terms-condtions-linktype1').slideDown();
                jQuery('.for-terms-condtions-linktype2').hide();
            }else{
                jQuery('.for-terms-condtions-linktype1').hide();
                jQuery('.for-terms-condtions-linktype2').slideDown();
            }
        });";
        if(isset(majesticsupport::$_data[0]['userfield']->id)){
            $majesticsupport_js .="
            var intial_val = jQuery('#termsandconditions_linktype').val();
            if(intial_val == 1){
                jQuery('.for-terms-condtions-linktype1').slideDown();
                jQuery('.for-terms-condtions-linktype2').hide();
            }else{
                jQuery('.for-terms-condtions-linktype1').hide();
                jQuery('.for-terms-condtions-linktype2').slideDown();
            }";
        }
    $majesticsupport_js .="
    });

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('addgdpr'); ?>
        <div id="msadmin-data-wrp">
            <form class="msadmin-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_gdpr&task=savegdprfield"),"save-gdprfield")); ?>">
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Field Title', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('fieldtitle', isset(majesticsupport::$_data[0]['userfield']->fieldtitle) ? majesticsupport::$_data[0]['userfield']->fieldtitle : '', array('class' => 'inputbox mjtc-form-input-field', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?></div>
                </div>
                <?php
                $termsandconditions_text = '';
                $termsandconditions_linktype = '';
                $termsandconditions_link = '';
                $termsandconditions_page = '';
                if( isset(majesticsupport::$_data[0]['userfieldparams']) && majesticsupport::$_data[0]['userfieldparams'] != '' && is_array(majesticsupport::$_data[0]['userfieldparams']) && !empty(majesticsupport::$_data[0]['userfieldparams'])){
                    $termsandconditions_text = isset(majesticsupport::$_data[0]['userfieldparams']['termsandconditions_text']) ? majesticsupport::$_data[0]['userfieldparams']['termsandconditions_text'] :'' ;
                    $termsandconditions_linktype = isset(majesticsupport::$_data[0]['userfieldparams']['termsandconditions_linktype']) ? majesticsupport::$_data[0]['userfieldparams']['termsandconditions_linktype'] :'' ;
                    $termsandconditions_link = isset(majesticsupport::$_data[0]['userfieldparams']['termsandconditions_link']) ? majesticsupport::$_data[0]['userfieldparams']['termsandconditions_link'] :'' ;
                    $termsandconditions_page = isset(majesticsupport::$_data[0]['userfieldparams']['termsandconditions_page']) ? majesticsupport::$_data[0]['userfieldparams']['termsandconditions_page'] :'' ;
                } ?>
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Field Text', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('termsandconditions_text', $termsandconditions_text, array('class' => 'inputbox mjtc-form-input-field', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?></div>
                    <div class="mjtc-form-desc">
                        <?php echo esc_html(__('e.g., "I have read and agree to the [link] Terms and Conditions [/link]." The text between [link] and [/link] will be linked to provided URL or WordPress page.', 'majestic-support')); ?>
                    </div>
                </div>
                <?php
                $yesno = array(
                    (object) array('id' => 1, 'text' => esc_html(__('Yes', 'majestic-support'))),
                    (object) array('id' => 0, 'text' => esc_html(__('No', 'majestic-support'))));
                $linktype = array(
                    (object) array('id' => 1, 'text' => esc_html(__('Direct Link', 'majestic-support'))),
                    (object) array('id' => 2, 'text' => esc_html(__('Wordpress Page', 'majestic-support'))));
                ?>
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Link Type', 'majestic-support')); ?> </div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_select('termsandconditions_linktype', $linktype, $termsandconditions_linktype, esc_html(__('Select Link Type', 'majestic-support')), array('class' => 'inputbox mjtc-form-select-field')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper for-terms-condtions-linktype2" style="display: none;">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Link Page', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_select('termsandconditions_page', MJTC_includer::MJTC_getModel('configuration')->getPageList(), $termsandconditions_page, esc_html(__('Select Page', 'majestic-support')), array('class' => 'inputbox mjtc-form-select-field')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper for-terms-condtions-linktype1" style="display: none;">
                    <div class="mjtc-form-title"><?php echo esc_html(__('URL', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('termsandconditions_link', $termsandconditions_link, array('class' => 'inputbox mjtc-form-input-field')), MJTC_ALLOWED_TAGS) ?></div>
                </div>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('id', isset(majesticsupport::$_data[0]['userfield']->id) ? majesticsupport::$_data[0]['userfield']->id : ''), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('created', isset(majesticsupport::$_data[0]['userfield']->created) ? majesticsupport::$_data[0]['userfield']->created : ''), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ordering', isset(majesticsupport::$_data[0]['userfield']->ordering) ? majesticsupport::$_data[0]['userfield']->ordering : ''), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('userfieldtype', 'termsandconditions'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('isuserfield', 1), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('fieldfor', 3), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('published', 1), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('required', 1), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('isvisitorpublished', 1), MJTC_ALLOWED_TAGS); ?>
                <div class="mjtc-form-button">
                    <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save', 'majestic-support')), array('class' => 'button mjtc-form-save')), MJTC_ALLOWED_TAGS); ?>
                </div>
            </form>
        </div>
    </div>
</div>
