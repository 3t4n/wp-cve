<?php
/**
 * The Main Scheduled Order Edit Form
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-items-form.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_edit_form', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_order_items_form_skin', array(
'form'                  => '',
'table'                 => 'shop_table shop_table_responsive cart woocommerce-cart-form__contents',
'table_head'            => '',
'table_head_row'        => '',
'table_head_cell'       => '',
'table_body'            => '',
'table_row'             => 'woocommerce-cart-form__cart-item',
'table_cell'            => '',
'table_row_placeholder' => 'scheduled-order-add-ons-placeholder',
'table_row_addon'       => '',
'table_cell_addon'      => '',
'table_row_coupons'     => '',
'table_cell_coupons'    => '',
'table_row_addon_msg'   => 'scheduled-order-add-ons-msg-container',
'table_cell_addon_msg'  => ''
));

?>

<form class="<?php echo $skin['form'];?> <?php echo apply_filters( 'autoship_edit_scheduled_order_form_classes', 'autoship-scheduled-order-edit-form' . ( empty( $autoship_order['scheduledOrderItems'] ) ? ' empty-order' : '' ), $autoship_order ); ?>" method="post" <?php do_action( 'autoship_edit_scheduled_order_form_tag' ); ?> >

  <?php do_action( 'autoship_before_scheduled_order_edit_table', $autoship_order  ); ?>

  <table class="<?php echo $skin['table'];?> <?php echo apply_filters( 'autoship_edit_scheduled_order_items_table_classes', 'autoship-scheduled-order-items-table' ); ?>" cellspacing="0">
    <thead class="<?php echo $skin['table_head'];?>">
      <tr class="<?php echo $skin['table_head_row'];?>">
        <th class="product-remove <?php echo $skin['table_head_cell'];?>">&nbsp;</th>
        <th class="product-thumbnail <?php echo $skin['table_head_cell'];?>">&nbsp;</th>
        <th class="product-name <?php echo $skin['table_head_cell'];?>"><?php esc_html_e( 'Product', 'autoship' ); ?></th>
        <th class="product-quantity <?php echo $skin['table_head_cell'];?>"><?php esc_html_e( 'Quantity', 'autoship' ); ?></th>
        <th class="product-price <?php echo $skin['table_head_cell'];?>"><?php esc_html_e( 'Price', 'autoship' ); ?></th>
      </tr>
    </thead>
    <tbody class="<?php echo $skin['table_body'];?>">

      <?php do_action( 'autoship_before_scheduled_order_form_items', $autoship_order, $customer_id, $autoship_customer_id ); ?>

      <?php

      // Iterate through all Scheduled Order Items
      $current_products = array();
      $autoship_order['future_products'] = array();
      $order_items = autoship_get_scheduled_order_form_items_data( $autoship_order['id'], $autoship_order['scheduledOrderItems'] );

      foreach ( $order_items as $key => $item) {

        // Grab the original item for future filters.
        $scheduled_item = $autoship_order['scheduledOrderItems'][$item['item_key']];

        if ( $item['qty'] > 0 && $item['visible'] ) {

          $current_products[] = $item['product_id'];

          ?>

          <tr id="<?php echo $item['key']; ?>" class="<?php echo $skin['table_row'];?> <?php echo esc_attr( apply_filters( 'autoship_edit_scheduled_order_item_class', 'scheduled_order_item', $item, $scheduled_item ) ); ?> <?php echo $item['stock_status']; ?>" data-product-id="<?php echo $item['product_id']; ?>">

            <td class="product-remove <?php echo $skin['table_cell'];?>">

              <?php

                echo apply_filters( 'autoship_scheduled_order_form_item_remove_link', sprintf(
                  '<a href="%s" class="remove remove-item-action" aria-label="%s" data-scheduled-order-item-id="%s" data-scheduled-order-id="%s">&times;</a>',
                  esc_url( $item['remove_url'] ),
                  __( 'Remove this item', 'autoship' ),
                  esc_attr( $item['id'] ),
                  esc_attr( $item['order_id'] )
                ), $item['key'], $item );

              ?>

            </td><!-- .product-remove -->

            <td class="product-thumbnail <?php echo $skin['table_cell'];?>" data-title="<?php esc_attr_e( 'Thumbnail', 'autoship' ); ?>">

              <?php

                if ( empty( $item['wc_permallink'] ) ) {
                  echo $item['thumbnail'];
                } else {
                  printf( '<a href="%s">%s</a>', esc_url( $item['wc_permallink'] ), $item['thumbnail'] ); // PHPCS: XSS ok.
                }

              ?>

            </td><!-- .product-thumbnail -->

            <td class="product-name <?php echo $skin['table_cell'];?>" data-title="<?php esc_attr_e( 'Product', 'autoship' ); ?>">

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
                  echo wp_kses_post( apply_filters( 'autoship_scheduled_order_form_item_name_display', $item['name'], $item, $scheduled_item ) . '&nbsp;' );
                } else {
                  echo wp_kses_post( apply_filters( 'autoship_scheduled_order_form_item_name_display', sprintf( '<a href="%s">%s</a>', esc_url( $item['wc_permallink'] ), $item['name'] ), $item, $scheduled_item ) );
                }

                /**
                 * @hooked autoship_scheduled_order_item_meta_template_display - 10
                 * @hooked autoship_scheduled_order_item_cycle_notice - 11
                 */
                do_action( 'autoship_after_scheduled_order_form_item_name', $item,
                $scheduled_item,
                $autoship_order,
                $customer_id,
                $autoship_customer_id );

              ?>

            </td><!-- .product-name -->

            <td class="product-quantity <?php echo $skin['table_cell'];?>" data-title="<?php esc_attr_e( 'Quantity', 'autoship' ); ?>">

              <?php

                if ( $item['is_sold_individually'] ) {
                  $product_quantity = sprintf( '1 <input type="hidden" name="autoship_scheduled_order_items[%s][qty]" value="1" />', $item['id'] );
                } else if ( 'outofstock' == $item['stock_status'] ) {
                  $product_quantity = $item['qty'];
                } else {
                  $product_quantity = autoship_scheduled_order_quantity_input( array(
                    'input_name'   => "autoship_scheduled_order_items[{$item['id']}][qty]",
                    'input_value'  => $item['qty'],
                    'max_value'    => $item['max_input'],
                    'min_value'    => $item['min_input'],
                    'product_name' => $item['name'],
                  ), $item['product'], $item['wc_product'], false );
                }

                echo apply_filters( 'autoship_scheduled_order_form_item_quantity_field', $product_quantity, $item, $scheduled_item);

              ?>

            </td><!-- .product-quantity -->

            <td class="product-price <?php echo $skin['table_cell'];?>" data-title="<?php esc_attr_e( 'Price', 'autoship' ); ?>">

              <?php

                echo apply_filters( 'autoship_scheduled_order_form_item_price', autoship_get_formatted_price( autoship_get_calculated_scheduled_order_item_sale_price( $scheduled_item, $autoship_order ) , array(
                  'original'  => $item['price'],
                  'suffix'    => $item['wc_product']->get_price_suffix(),
                ) ), $item, $scheduled_item, $autoship_order );

              ?>

            </td><!-- .product-price -->

          </tr><!-- .scheduled_order_item -->

          <?php do_action( 'autoship_after_scheduled_order_form_item', $autoship_order, $skin ); ?>

          <?php
        }
      }

      if ( autoship_allow_added_products( $autoship_order, $customer_id, $autoship_customer_id ) ):

        $products = autoship_get_schedulable_products_script_data();
        $available_products = apply_filters( 'autoship_filter_schedulable_products_display_labels', array(), $products, $autoship_order, $customer_id );

        // Don't include if no products available
        if ( !empty( $available_products ) ):

        ?>

        <tr class="<?php echo $skin['table_row_placeholder'];?>"><td colspan="1"></td><td colspan="4" class="product-thumbnail auto_loader"><?php echo autoship_get_product_thumbnail_html ( Autoship_Plugin_Url . 'images/placeholder.png' );?><span></span></td></tr>

        <tr class="scheduled-order-add-ons <?php echo $skin['table_row_addon'];?>">

          <td colspan="1" class="add-on-action <?php echo $skin['table_cell_addon'];?>">

            <?php

              echo apply_filters( 'autoship_scheduled_order_form_item_add_link', sprintf('<a href="%s" class="add-item-action">%s</a>','#', __( 'Add', 'autoship' )));

            ?>

          </td><!-- .add-on-action -->

          <td colspan="4" class="add-on-action-options <?php echo $skin['table_cell_addon'];?>">

            <div class="add-on-select">

              <label class="screen-reader-text" for="autoship-product-select"><?php echo __( 'Add a Product', 'autoship' ); ?></label>

              <select name="autoship_add_scheduled_order_item" class="autoship-item-select" id="autoship_add_scheduled_order_item">

                <option value=""><?php

                echo apply_filters( 'autoship_scheduled_order_form_item_add_select_default', esc_html( 'Add a Product' ) );

                ?></option>

                <?php
                $current_products = array_flip( $current_products );
                foreach ( $available_products as $product_id => $values ): ?>

                  <option value="<?php echo $product_id;?>" <?php disabled( array_key_exists( $product_id, $current_products ) );?> >
                    <?php echo $values['label'];?>
                  </option>

                <?php endforeach; ?>

              </select>

            </div>
          </td><!-- .add-on-action-options -->

        </tr>

        <?php

        endif;

      endif;

      ?>

      <tr class="<?php echo $skin['table_row_addon_msg']; ?>">
        <td colspan="5" class="scheduled-order-add-ons-msg <?php echo $skin['table_cell_addon_msg']; ?>"></td>
      </tr>

      <?php

      do_action( 'autoship_after_scheduled_order_form_items', $autoship_order, $customer_id, $autoship_customer_id );

      // Display the currently assigned coupons with the ability to remove them.
      if ( autoship_allow_coupons() && !empty( $autoship_order['coupons'] ) && !empty( $autoship_order['scheduledOrderItems'] ) ):?>

      <tr class="scheduled-order-coupons <?php echo $skin['table_row_coupons'];?>">

        <td colspan="5" class="<?php echo $skin['table_cell_coupons'];?>">

          <ul class="coupon-codes">

          <?php

          foreach (  $autoship_order['coupons'] as $value ){
            $remove_url = autoship_get_scheduled_order_coupon_remove_url( $value , $autoship_order['id'] );
            echo apply_filters( 'autoship_scheduled_order_form_coupon_remove_link', sprintf(
              '<li class="coupon"><a href="%s" class="remove-item-action" aria-label="%s" data-scheduled-order-coupon-code="%s" data-scheduled-order-id="%s">&times;</a><span>%s</span></li>',
              esc_url( $remove_url ),
              __( 'Remove this item', 'autoship' ),
              esc_attr( $value ),
              esc_attr( $autoship_order['id'] ),
              $value
            ), $value );

          }

          ?>

          </ul>

        </td>

      </tr><!-- .scheduled-order-coupons -->

      <?php endif; ?>

      <?php do_action( 'autoship_after_scheduled_order_form_add_ons', $autoship_order, $customer_id, $autoship_customer_id, $order_items ); ?>

      <?php
      /**
      * @hooked autoship_scheduled_order_no_items_template_display - 10
      */
      if ( empty( $autoship_order['scheduledOrderItems'] ) )
      do_action( 'autoship_update_scheduled_order_form_no_items', $autoship_order, $customer_id, $autoship_customer_id ); ?>

    </tbody>

    <?php do_action( 'autoship_scheduled_order_form_table_footer', $autoship_order, $customer_id, $autoship_customer_id, $order_items ); ?>

  </table>

  <?php
  /**
   * @hooked autoship_scheduled_order_totals_summary_template_display - 10
   */
   do_action( 'autoship_after_scheduled_order_form_table', $autoship_order, $customer_id, $autoship_customer_id, $order_items ); ?>

</form>
