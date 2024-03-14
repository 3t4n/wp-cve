<?php defined('ABSPATH') || exit;

\ShopEngine\Widgets\Widget_Helper::instance()->wc_template_filter();
?>
<div class="shopengine shopengine-widget">
   <div class="shopengine-account-downloads">
      <?php

      if(get_post_type() == \ShopEngine\Core\Template_Cpt::TYPE) {

         WC()->customer = new WC_Customer(get_current_user_id(), true);
      }
      

      //  woocommerce_account_downloads();
      
      $downloads     = WC()->customer->get_downloadable_products();
      $has_downloads = (bool) $downloads;
      woocommerce_order_downloads_table($downloads);

      do_action( 'woocommerce_before_account_downloads', $has_downloads ); ?>

      <?php if ( $has_downloads ) : ?>

         <?php do_action( 'woocommerce_before_available_downloads' ); ?>

         <?php do_action( 'woocommerce_available_downloads', $downloads ); ?>

         <?php do_action( 'woocommerce_after_available_downloads' ); ?>

      <?php else : ?>
         <div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
            <a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
               <?php esc_html_e( 'Browse products', 'shopengine-gutenberg-addon' ); ?>
            </a>
            <?php esc_html_e( 'No downloads available yet.', 'shopengine-gutenberg-addon' ); ?>
         </div>
      <?php endif; ?>

      <?php do_action( 'woocommerce_after_account_downloads', $has_downloads ); ?>
      
   </div>
</div>
<?php 

