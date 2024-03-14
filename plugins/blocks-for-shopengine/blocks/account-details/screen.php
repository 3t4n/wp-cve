

<?php 

defined('ABSPATH') || exit;

	if(!is_user_logged_in()) {

      return esc_html__('You need first to be logged in', 'shopengine-gutenberg-addon');
   }

;?>

<div class="shopengine shopengine-widget">

   <div class="shopengine-account-details">
      <?php woocommerce_account_edit_account(); ?>
   </div>
</div>

