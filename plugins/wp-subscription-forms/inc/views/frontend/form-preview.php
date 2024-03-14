<?php
defined('ABSPATH') or die('No script kiddies please!!');
$form_alias = sanitize_text_field($_GET['form_alias']);
$form_row = $this->get_form_row_by_alias($form_alias);
if (empty($form_row)) {
    return;
}
$form_alias = $form_row->form_alias;
$form_id = $form_row->form_id;
?>
<html>
    <head>
        <title><?php esc_html_e('Preview - WP Subscription Forms', 'wp-subscription-forms'); ?></title>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <div class="wpsf-preview-head-wrap">
            <div class="wpsf-preview-title-wrap">
                <div class="wpsf-preview-title"><?php esc_html_e('Preview Mode', 'wp-subscription-forms'); ?></div>
            </div>
            <div class="wpsf-preview-note"><?php esc_html_e('This is just the basic preview and it may look different when used in frontend as per your theme\'s styles.', 'wp-subscription-forms'); ?></div>
        </div>
        <div class="wpsf-form-preview-wrap">

            <?php
            echo do_shortcode('[wp_subscription_forms alias="' . $form_alias . '"]');
            ?>
        </div>

        <span class="wpsf-preview-subtitle"><a href="<?php echo admin_url('admin.php?page=wp-subscription-forms&action=edit_form&form_id=' . esc_attr($form_id)); ?>"><?php esc_html_e('Edit this Form', 'wp-subscription-forms'); ?></a></span>

        <?php wp_footer(); ?>
    </body>
</html>
