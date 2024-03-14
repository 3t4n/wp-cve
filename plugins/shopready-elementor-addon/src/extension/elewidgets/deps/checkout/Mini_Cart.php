<?php

namespace Shop_Ready\extension\elewidgets\deps\checkout;

use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;

/** 
 * @since 1.0 
 * WooCommerce mini Cart Ajax
 * 
 * @author quomodosoft.com 
 */

class Mini_Cart
{

  public function register()
  {

    add_action('wp_ajax_shop_ready_update_mini_cart_item', [$this, 'mini_cart_fragment_update']);
    add_action('wp_ajax_nopriv_shop_ready_update_mini_cart_item', [$this, 'mini_cart_fragment_update']);

  }

  public function mini_cart_fragment_update()
  {

    $cart_data = wc_clean(sanitize_text_field($_POST['sr_cart_item_keys']));
    foreach ($cart_data as $item) {
      global $woocommerce;

      $woocommerce->cart->set_quantity(sanitize_text_field($item['key']), sanitize_text_field($item['value']));
    }

    ob_start();

    woocommerce_mini_cart();

    $mini_cart = ob_get_clean();

    $data = array(
      'fragments' => apply_filters(
        'woocommerce_add_to_cart_fragments',
        array(
          'div.woo-ready-mini-cart-container' => '<div class="woo-ready-mini-cart-container sready-minicart-order-review ajax-fragemnt display:flex flex-direction:column">' . $mini_cart . '</div>',
          'span.wr-mini-cart-subtotal-bill' => '<span class="wr-mini-cart-subtotal-bill">' . WC()->cart->get_cart_subtotal() . '</span>',
          '.wr-checkout-cart-total-bill' => '<div class="wr-checkout-cart-total-bill">' . WC()->cart->get_cart_subtotal() . '</div>',
          'div.wr-tax-amount' => '<div class="wr-tax-amount">' . wc_cart_totals_taxes_total_html() . '</div>',

        )
      ),
      'cart_hash' => WC()->cart->get_cart_hash(),
    );

    wp_send_json($data);

  }


}