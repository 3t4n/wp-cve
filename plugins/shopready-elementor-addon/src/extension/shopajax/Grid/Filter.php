<?php

namespace Shop_Ready\extension\shopajax\Grid;

use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;

/** 
 * @since 1.0 
 * WooCommerce Shop page Ajax grid filter
 * Sidebar widget
 * use in Shop Archive
 * @author quomodosoft.com 
 */
class Filter
{


  public $result = [];
  public function register()
  {

    //Ajax Action
    add_action('wp_ajax_shop_ready_shop_product_refresh_content', [$this, 'product_grid_content']);
    add_action('wp_ajax_nopriv_shop_ready_shop_product_refresh_content', [$this, 'product_grid_content']);
    add_filter('woocommerce_shortcode_products_query_results', [$this, 'woocommerce_shortcode_products_query_results'], 20, 2);

  }



  public function woocommerce_shortcode_products_query_results($result, $obj)
  {
    $this->result = $result;
    return $result;
  }

  public function product_grid_content()
  {

    $attr_array = [];

    if (isset($_REQUEST['orderby'])) {

      $attr_array['orderby'] = sanitize_text_field($_REQUEST['orderby']);
    }

    $shortcode = "[products " . shop_ready_attr_to_shortcide($attr_array) . "]";

    ob_start();
    echo wp_kses_post(do_shortcode(shortcode_unautop($shortcode)));
    $this->get_shop_to_header_content();
    $this->loadmore_product();
    $fragments['woo_ready_products'] = ob_get_clean();
    wp_send_json($fragments);

  }

  public function loadmore_product()
  {

    $loadmore_lavel = WReady_Helper::get_global_setting('shop_ready_product_grid_loadmore', 'Loadmore');
    $per_page = sanitize_text_field(isset($_GET['page_count']) ? (int) $_GET['page_count'] : 9);
    $page_number = sanitize_text_field(isset($_GET['page_number']) ? (int) $_GET['page_number'] : 1);
    $_page_args = array(
      'class' => 'shop-ready-auto-product-load margin-top:20 text-align:center'
    );

    $_button_args = array(

      'total' => isset($this->result->total) ? $this->result->total : 0,
      'per_page' => isset($this->result->per_pag) ? $this->result->per_pag : $per_page,
      'current' => $page_number,
      'class' => 'shop-ready-more-btn shop-ready-mod-lbtn-js button'

    );

    if ($this->result->total < $per_page) {
      return;
    }

    echo wp_kses_post(shop_ready_html_tag('div', $_page_args));
    echo wp_kses_post(shop_ready_html_tag('button', $_button_args));
    echo wp_kses_post(sprintf('%s </button>', $loadmore_lavel));
    echo wp_kses_post('</div>');
  }
  /**
   * Unused Because of ajax loading
   */
  public function get_shop_to_header_content()
  {

    $per_page = sanitize_text_field(isset($_GET['page_count']) ? (int) absint($_GET['page_count']) : 9);

  }

}