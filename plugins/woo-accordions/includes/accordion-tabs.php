<?php
/**
 * Single Product accordion
 *
 * @author  MagniGenie
 * @package WooAtm
 * @version 1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$options = get_option( 'wooatm' );
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>
  <div id="accordion-container" class="woocommerce-tabs wc-tabs-wrapper">
    <?php foreach ( $tabs as $key => $tab ) : ?>
      <h1 class="<?php echo esc_attr( $key ); ?>_tab">
        <?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?>
      </h1>
      <div id="tab-<?php echo esc_attr( $key ); ?>">
        <?php call_user_func( $tab['callback'], $key, $tab ); ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>