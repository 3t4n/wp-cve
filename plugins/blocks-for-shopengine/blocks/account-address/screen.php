<?php

defined('ABSPATH') || exit;

global $wp;

if (!is_user_logged_in()) {

   return esc_html__('You need first to be logged in', 'shopengine-gutenberg-addon');
}

$address_type = $settings['shopengine_account_address_type']['desktop'];

$edit_screen = $settings['shopengine_account_address_type']['desktop'] ? $settings['shopengine_account_address_type']['desktop']:'';

\ShopEngine\Widgets\Widget_Helper::instance()->wc_template_filter();

$screen = isset($wp->query_vars['edit-address']) ? $wp->query_vars['edit-address'] : '';

if (get_post_type() == \ShopEngine\Core\Template_Cpt::TYPE) {

   $screen = $edit_screen;
}

$has_form = ($screen === '') ? '' : ' shopengine-account-address-form';


?>
<div class="shopengine shopengine-widget">
   <div class="shopengine-account-address <?php echo esc_attr($screen . $has_form); ?>">
      <?php woocommerce_account_edit_address($screen); ?>
   </div>
</div>
<?php
