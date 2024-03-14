<?php
defined('ABSPATH') or die('No script kiddies please!!');
?>
<html>
    <head>
        <title><?php esc_html_e('Preview - Subscribe to Unlock Lite', 'subscribe-to-unlock-lite'); ?></title>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <div class="stul-preview-head-wrap">
            <div class="stul-preview-title-wrap">
                <div class="stul-preview-title"><?php esc_html_e('Preview Mode', 'subscribe-to-unlock-lite'); ?></div>
            </div>
            <div class="stul-preview-note"><?php esc_html_e('This is just the basic preview and it may look different when used in frontend as per your theme\'s styles.', 'subscribe-to-unlock-lite'); ?></div>
        </div>
        <div class="stul-form-preview-wrap">

            <?php
            echo do_shortcode('[subscribe_to_unlock_form]');
            ?>
        </div>

        <span class="stul-preview-subtitle"><a href="<?php echo admin_url('admin.php?page=stul-settings'); ?>"><?php esc_html_e('Update Settings', 'subscribe-to-unlock-lite'); ?></a></span>

        <?php wp_footer(); ?>
    </body>
</html>
