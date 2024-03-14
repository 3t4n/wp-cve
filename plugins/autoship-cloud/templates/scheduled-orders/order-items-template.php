<?php
/**
 * The Main Scheduled Order View Items
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-items-template.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_view_template', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_order_items_view_skin', array(
'container'             => '',
'table'                 => 'shop_table shop_table_responsive cart woocommerce-cart-form__contents',
'table_head'            => '',
'table_head_row'        => '',
'table_head_cell'       => '',
'table_body'            => '',
'table_row'             => 'woocommerce-cart-form__cart-item',
'table_cell'            => '',
'table_row_coupons'     => '',
'table_cell_coupons'    => '',
));

?>

<div class="<?php echo $skin['container'];?> <?php echo apply_filters( 'autoship_view_scheduled_order_template_classes', 'autoship-scheduled-order-view-template' . ( empty( $autoship_order['scheduledOrderItems'] ) ? ' empty-order' : '' ), $autoship_order ); ?>">

  <?php do_action( 'autoship_before_scheduled_order_view_table', $autoship_order  ); ?>

  <table class="<?php echo $skin['table'];?> <?php echo apply_filters( 'autoship_view_scheduled_order_items_table_classes', 'autoship-scheduled-order-items-table' ); ?>" cellspacing="0">
    <thead class="<?php echo $skin['table_head'];?>">
      <tr class="<?php echo $skin['table_head_row'];?>">
        <th class="product-thumbnail <?php echo $skin['table_head_cell'];?>">&nbsp;</th>
        <th class="product-name <?php echo $skin['table_head_cell'];?>"><?php esc_html_e( 'Product', 'autoship' ); ?></th>
        <th class="product-quantity <?php echo $skin['table_head_cell'];?>"><?php esc_html_e( 'Quantity', 'autoship' ); ?></th>
        <th class="product-price <?php echo $skin['table_head_cell'];?>"><?php esc_html_e( 'Price', 'autoship' ); ?></th>
      </tr>
    </thead>
    <tbody class="<?php echo $skin['table_body'];?>">

      <?php do_action( 'autoship_before_scheduled_order_view_items', $skin, $autoship_order ); ?>

      <?php

      // Iterate through all Scheduled Order Items
      $order_items = autoship_get_scheduled_order_form_items_data( $autoship_order['id'], $autoship_order['scheduledOrderItems'], 'view_item' );

      foreach ( $order_items as $item ) {

        // Grab the original item for future filters.
        $scheduled_item = $autoship_order['scheduledOrderItems'][$item['item_key']];

        if ( $item['qty'] > 0 && $item['visible'] ) {

          ?>

          <tr id="<?php echo $item['key']; ?>" class="<?php echo $skin['table_row'];?> <?php echo esc_attr( apply_filters( 'autoship_view_scheduled_order_item_class', 'scheduled_order_item', $item, $scheduled_item ) ); ?> <?php echo $item['stock_status']; ?>">

            <?php do_action( 'autoship_before_scheduled_order_view_item_columns', $item, $scheduled_item, $autoship_order ); ?>

            <td class="product-thumbnail <?php echo $skin['table_cell'];?>" data-title="<?php esc_attr_e( 'Thumbnail', 'autoship' ); ?>">

              <?php

                if ( empty( $item['wc_permallink'] ) ) {
                  echo $item['thumbnail'];
                } else {
                  printf( '<a href="%s">%s</a>', esc_url( $item['wc_permallink'] ), $item['thumbnail'] ); // PHPCS: XSS ok.
                }

              ?>

            </td><!-- .product-thumbnail -->

            <td class="product-name <?php echo $skin['table_cell'];?>" data-title="<?php esc_attr_e( 'product', 'autoship' ); ?>">

              <?php

                /**
                * @hooked autoship_scheduled_order_item_stock_notice_display - 10
                */
                do_action ( 'autoship_before_scheduled_order_form_item_name', $item,
                $scheduled_item,
                $autoship_order,
                $customer_id,
                $autoship_customer_id );

                if ( empty( $item['wc_permallink'] ) ) {
                  echo wp_kses_post( apply_filters( 'autoship_scheduled_order_view_item_name_display', $item['name'], $item, $scheduled_item ) . '&nbsp;' );
                } else {
                  echo wp_kses_post( apply_filters( 'autoship_scheduled_order_view_item_name_display', sprintf( '<a href="%s">%s</a>', esc_url( $item['wc_permallink'] ), $item['name'] ), $item, $scheduled_item ) );
                }

                /**
                 * @hooked autoship_scheduled_order_item_meta_template_display - 10
                 * @hooked autoship_scheduled_order_item_cycle_notice - 11
                 */
                do_action( 'autoship_after_scheduled_order_view_item_name', $item,
                $scheduled_item,
                $autoship_order,
                $customer_id,
                $autoship_customer_id );

              ?>

            </td><!-- .product-name -->

            <td class="product-quantity <?php echo $skin['table_cell'];?>" data-title="<?php esc_attr_e( 'Quantity', 'autoship' ); ?>">

              <?php

                echo apply_filters( 'autoship_scheduled_order_view_item_quantity', $item['qty'], $item, $scheduled_item);

              ?>

            </td><!-- .product-quantity -->

            <td class="product-price <?php echo $skin['table_cell'];?>" data-title="<?php esc_attr_e( 'Price', 'autoship' ); ?>">

              <?php

                echo apply_filters( 'autoship_scheduled_order_view_item_price', autoship_get_formatted_price( autoship_get_calculated_scheduled_order_item_sale_price( $scheduled_item, $autoship_order ) , array(
                  'original'  => $item['price'],
                  'suffix'    => $item['wc_product']->get_price_suffix(),
                ) ), $item, $scheduled_item, $autoship_order );

              ?>

            </td><!-- .product-price -->

            <?php do_action( 'autoship_after_scheduled_order_view_item_columns', $item, $scheduled_item, $autoship_order ); ?>

          </tr><!-- .scheduled_order_item -->

          <?php
        }
      }

      // Display the currently assigned coupons with the ability to remove them.
      if ( !empty( $autoship_order['coupons'] ) && !empty( $autoship_order['scheduledOrderItems'] ) ):?>

      <tr class="scheduled-order-coupons <?php echo $skin['table_row_coupons'];?>">

        <td colspan="5" class="<?php echo $skin['table_cell_coupons'];?>">
          <h6><?php echo apply_filters('autoship_scheduled_order_view_coupon_code_title', __('Applied Coupon Codes:', 'autoship' ) ); ?></h6>
          <ul class="coupon-codes">

          <?php

          foreach ( $autoship_order['coupons'] as $value ){
            echo apply_filters( 'autoship_scheduled_order_form_coupon_code', sprintf(
              '<li class="coupon"><span>%s</span></li>',
              $value
            ), $value );
          }

          ?>

          </ul>

        </td>

      </tr><!-- .scheduled-order-coupons -->

      <?php endif; ?>

      <?php do_action( 'autoship_after_scheduled_order_view_items', $autoship_order, $customer_id, $autoship_customer_id, $order_items ); ?>

      <?php
      /**
      * @hooked autoship_scheduled_order_no_items_template_display - 10
      */
      if ( empty( $autoship_order['ScheduledOrderItems'] ) )
      do_action( 'autoship_scheduled_order_view_no_items', $autoship_order, $customer_id, $autoship_customer_id ); ?>

    </tbody>

    <?php do_action( 'autoship_scheduled_order_view_table_footer', $autoship_order, $customer_id, $autoship_customer_id, $order_items ); ?>

  </table>

  <?php
  /**
   * @hooked autoship_scheduled_order_totals_summary_template_display - 10
   */
   do_action( 'autoship_after_scheduled_order_view_table', $autoship_order, $customer_id, $autoship_customer_id, $order_items ); ?>

</div>
