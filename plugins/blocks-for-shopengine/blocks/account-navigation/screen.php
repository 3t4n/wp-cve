<?php
defined('ABSPATH') || exit;

if (!is_user_logged_in()) {
?>
    <div class="shopengine shopengine-editor-alert shopengine-editor-alert-warning">
        <?php esc_html_e('You need first to be logged in', 'shopengine-gutenberg-addon'); ?>
    </div>
<?php
    return;
}

?>
<div class="shopengine shopengine-widget">
    <div class="shopengine-account-navigation">
        <?php woocommerce_account_navigation(); ?>
    </div>
</div>