<?php
/**
 * The New Scheduled Order Item via Ajax Template
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-ajax-add-item-form.php
*/
defined( 'ABSPATH' ) || exit;

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

$scheduled_order_item = array(
  'id'                => $product['id'],
  'cycles'            => NULL,
  'minCycles'         => NULL,
  'maxCycles'         => NULL,
  'price'             => $product['price'],
  'salePrice'         => $product['salePrice'],
  'quantity'          => 1,
  'productId'         => $product['id'],
  'product'           => $product
);

$item = autoship_get_scheduled_order_form_item_data( NULL, $scheduled_order_item, 'form_add_item' );

?>

<tr id="<?php echo $item['key']; ?>" class="<?php echo $skin['table_row']; ?> <?php echo esc_attr( apply_filters( 'autoship_edit_scheduled_order_add_item_class', 'scheduled_order_add_item scheduled_order_item', $item, $product  ) ); ?>">

  <td class="product-remove <?php echo $skin['table_cell'];?>">

    <?php

    echo apply_filters( 'autoship_scheduled_order_form_add_item_remove_link', sprintf(
      '<a href="%s" class="remove remove-new-item-action" aria-label="%s" data-scheduled-order-item-id="%s">&times;</a>',
      '#', __( 'Remove this item', 'autoship' ), $item['key'] ), $product );

    ?>

  </td><!-- .product-remove -->

  <td class="product-thumbnail <?php echo $skin['table_cell'];?>" data-title="<?php esc_attr_e( 'Thumbnail', 'autoship' ); ?>">

    <?php

      if ( empty( $item['wc_permallink'] ) ) {
        echo $item['thumbnail'];
      } else {
        printf( '<a href="%s">%s</a>', esc_url( $item['wc_permallink'] ), $item['thumbnail'] );
      }

    ?>

  </td><!-- .product-thumbnail -->

  <td class="product-name <?php echo $skin['table_cell'];?>" data-title="<?php esc_attr_e( 'Product', 'autoship' ); ?>">

    <?php

      if ( empty( $item['wc_permallink'] ) ) {
        echo wp_kses_post( apply_filters( 'autoship_scheduled_order_form_add_item_name_display', $item['name'], $item, $product  ) . '&nbsp;' );
      } else {
        echo wp_kses_post( apply_filters( 'autoship_scheduled_order_form_add_item_name_display', sprintf( '<a href="%s">%s</a>', esc_url( $item['wc_permallink'] ), $item['name'] ), $item, $product  ) );
      }

      /**
       * @hooked autoship_scheduled_order_add_item_meta_template_display - 10
       */
      do_action( 'autoship_after_scheduled_order_form_add_item_name', array( $item['sku'], $item['meta'] ),$product );

    ?>

  </td><!-- .product-name -->

  <td class="product-quantity <?php echo $skin['table_cell'];?>" data-title="<?php esc_attr_e( 'Quantity', 'autoship' ); ?>">

    <?php

      if ( $item['is_sold_individually'] ) {
        $product_quantity = sprintf( '1 <input type="hidden" name="autoship_scheduled_order_add_items[%s][qty]" value="1" />', $item['id'] . '-' . $item['wc_product_id'] );
      } else {
        $product_quantity = autoship_scheduled_order_quantity_input( array(
          'input_name'   => "autoship_scheduled_order_add_items[{$item['id']}-{$item['wc_product_id']}][qty]",
          'input_value'  => $item['qty'],
          'max_value'    => $item['max_input'],
          'min_value'    => $item['min_input'],
          'product_name' => $item['name'],
        ), $product, $item['wc_product'], false );
      }

      echo apply_filters( 'autoship_scheduled_order_add_item_quantity_field', $product_quantity, $item, $product );

    ?>

  </td><!-- .product-quantity -->

  <td class="product-price <?php echo $skin['table_cell'];?>" data-title="<?php esc_attr_e( 'Price', 'autoship' ); ?>">

    <?php

      echo apply_filters( 'autoship_scheduled_order_form_add_item_price', autoship_get_formatted_price( $item['sale_price'], array(
        'original'  => $item['price'],
        'suffix'    => $item['wc_product']->get_price_suffix(),
      ) ), $item, $product  );

    ?>

  </td><!-- .product-price -->

</tr><!-- .scheduled_order_add_item -->
