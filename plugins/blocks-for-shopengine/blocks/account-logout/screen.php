<?php

/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

defined('ABSPATH') || exit;
require_once 'helper.php';

if (!is_user_logged_in() && get_post_type() != \ShopEngine\Core\Template_Cpt::TYPE) {
    return '<div class="shopengine-editor-alert shopengine-editor-alert-warning">' . esc_html__('You need to logged in first', 'shopengine-gutenberg-addon') . '</div>';
}

?>
<div class="shopengine shopengine-widget">
    <div class="shopengine-account-logout">
        <a href="<?php echo esc_url(wc_logout_url(wc_get_page_permalink('myaccount'))); ?>">
            <?php render_icon($settings['shopengine_acc_logout_content_icon']['desktop'], ['aria-hidden' => 'true']); ?>
            <span> <?php echo esc_html($settings['shopengine_acc_logout_content_title']['desktop']); ?> </span>
        </a>
    </div>
</div>