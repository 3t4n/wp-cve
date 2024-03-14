<?php
/**
 * Plugin Name:       Custom Backorders for Woocommerce
 * Plugin URI:        https://gregbastianelli.com
 * Description:       Allows the author to provide a product or variation a custom backorder message. 
 * Version:           1.12
 * Author:            gregbast1994
 * Author URI:        http://gregbastianelli.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       cbfwc
 * Domain Path:       /languages
 */


class Custom_Backorders_For_Woocommerce
{
  /**
   * Adds a meta box in the inventory tab (for simple) and in the variation tab (for variations)
   * to include a custom backorder message like: "Ships out in january".
   */
  private $key = "_backorder_message";

  function __construct()
  {
    // add meta box to inventory tab for simple producs
    add_action(
      "woocommerce_product_options_stock_status",
      [$this, "simple_backorder_message_input_box"],
      1,
      1
    );

    // Add meta box to variations on admin side
    add_action(
      "woocommerce_variation_options_pricing",
      [$this, "variation_backorder_message_input_box"],
      1,
      3
    );

    // save (non-variation) product meta
    add_action(
      "woocommerce_process_product_meta",
      [$this, "save_simple_backorder_message"],
      15,
      2
    );

    // save backorder variation meta
    add_action(
      "woocommerce_save_product_variation",
      [$this, "save_variation_backorder_message"],
      10,
      2
    );

    // set availability text
    add_filter(
      "woocommerce_get_availability_text",
      [$this, "change_backorder_message"],
      10,
      2
    );
  }

  public function variation_backorder_message_input_box(
    /**
     * HTML which displays the input box for updating variations backorder message
     */
    $loop,
    $variation_data,
    $variation
  ) {
    echo '<div class="show_if_variation_manage_stock">';

    woocommerce_form_field(
      $this->key . "[" . $loop . "]",

      [
        "type" => "text",
        "wrapper_class" => "form-field",
        "label" => "Backorder Message",
        "description" =>
          "Custom message that will display if item on backorder - defaults to 'available on backorder'",
      ],

      get_post_meta($variation->ID, $this->key, true)
    );

    echo "</div>";
  }

  public function simple_backorder_message_input_box()
  {
    /**
     * HTML which displays the input box for updating variations backorder message
     */

    global $post;

    $product = wc_get_product($post->ID);
    $value = get_post_meta($post->ID, $this->key, true);

    echo '<div class="stock_fields show_if_simple">';

    woocommerce_form_field(
      $this->key,

      [
        "type" => "text",
        "wrapper_class" => "form-field",
        "label" => "Backorder Message",
        "description" =>
          "Custom message that will display if item on backorder - defaults to 'available on backorder'",
      ],

      is_array($value) ? $value[0] : $value
    );

    echo "</div>";
  }

  public function save_simple_backorder_message($post_id)
  {
    /**
     * Saves the backorder message for simple (non-variation) products
     * @param int $post_id
     */
    if (isset($_POST[$this->key])) {
      update_post_meta($post_id, $this->key, sanitize_text_field($_POST[$this->key]));
    }
  }

  public function save_variation_backorder_message($variation_id, $i)
  {
    /**
     * Saves the backorder message for variation products
     * @param int $variation_id - the id of the variation
     * @param int $i - this variations position in the loop
     */
    if (isset($_POST[$this->key])) {
      update_post_meta($variation_id, $this->key, sanitize_text_field($_POST[$this->key][$i]));
    }
  }

  public function change_backorder_message($text, $product)
  {
    /**
     * Edits the backorder message to include a custom message or a default
     * @param string $text
     * @param WC_Product $product
     */

    if (!$product->managing_stock() || !$product->is_on_backorder(1)) {
      return $text;
    }

    $backorder_message = get_post_meta(
      $product->get_id(),
      "_backorder_message",
      true
    );

    return !empty($backorder_message)
      ? $backorder_message
      : $text;
  }
}


/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    new Custom_Backorders_For_Woocommerce();
}