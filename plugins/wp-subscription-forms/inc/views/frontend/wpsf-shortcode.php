<?php
$form_details = maybe_unserialize($form_row->form_details);
$form_template = (!empty($atts['template'])) ? $atts['template'] : $form_details['layout']['template'];

$alias_class = 'wpsf-' . $form_row->form_alias;
$popup_alias_class = 'wpsf-popup-' . $form_row->form_alias;
$heading_show = (!empty($form_details['form']['heading']['show'])) ? true : false;
$heading_text = $form_details['form']['heading']['text'];
$sub_heading_show = (!empty($form_details['form']['sub_heading']['show'])) ? true : false;
$sub_heading_text = $form_details['form']['sub_heading']['text'];
$name_show = (!empty($form_details['form']['name']['show'])) ? true : false;
$name_label = $form_details['form']['name']['label'];
$email_label = $form_details['form']['email']['label'];
$terms_agreement_show = (!empty($form_details['form']['terms_agreement']['show'])) ? true : false;
$terms_agreement_text = $form_details['form']['terms_agreement']['agreement_text'];
$subscribe_button_text = $form_details['form']['subscribe_button']['button_text'];
$footer_show = (!empty($form_details['form']['footer']['show'])) ? true : false;
$footer_text = $form_details['form']['footer']['footer_text'];
$display_type = $form_details['layout']['display_type'];
$popup_trigger_text = $form_details['layout']['popup_trigger_text'];
if ($display_type == 'direct') {
    include(WPSF_PATH . 'inc/views/frontend/form-template.php');
} else {
    ?>
    <div class="wpsf-popup-outerwrap <?php echo esc_attr($popup_alias_class); ?>">
        <input type="button" class="wpsf-popup-trigger wpsf-popup-<?php echo esc_attr($form_template); ?>" value="<?php echo esc_attr($popup_trigger_text); ?>">
        <div class="wpsf-popup-innerwrap" style="display:none;">
            <div class="wpsf-overlay wpsf-popup-wrapper">

                <div class="wpsf-popup-contetn-wrap">
                    <a href="javascript:void(0)" class="wpsf-popup-close"><i class="fas fa-times"></i></a>
                        <?php include(WPSF_PATH . 'inc/views/frontend/form-template.php');
                        ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
include(WPSF_PATH . 'inc/cores/customize.php');

