<?php
/**
 * The Main Cart Schedule Options Template
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/cart/schedule-options.php
*/

/*
* The Skins Filter Allows Devs to Completely customize the template classes.
*/
$skin = apply_filters( 'autoship_product_schedule_options_select_skin', array(
'container'             => '',
'type'                  => '',
'label'                 => '',
'input'                 => '',
'span'                  => '',
'frequency'             => '',
'select'                => '',
));

$autoship_selected  = !empty( $cart_item['autoship_frequency_type'] );

$autoship_no        = !$autoship_selected ? 'checked="checked"' : '';
$autoship_yes       = $autoship_selected ? 'checked="checked"' : '';
$autoship_active    = $autoship_selected ? 'active"' : '';

$cart_key = esc_attr( $cart_item_key );

$autoship_data = autoship_product_discount_data( $product, array(), true );
$autoship_json = wp_json_encode( $autoship_data );
$autoship_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $autoship_json ) : _wp_specialchars( $autoship_json, ENT_QUOTES, 'UTF-8', true );

?>

<div class="autoship-cart-schedule-options autoship-schedule-options <?php echo $skin['container']?>" data-autoship-id="<?php echo $product->get_id(); ?>" data-autoship="<?php echo $autoship_attr;?>">

  <?php do_action('autoship_before_cart_schedule_options', $product, $frequency_type, $frequency, $next_occurrence, $cart_item, $autoship_selected ); ?>

  <div class="autoship-type autoship-no <?php echo $skin['type']?>">
    <label class="autoship-label <?php echo $skin['label']?>">
      <input type="radio" class="autoship-no-radio <?php echo $skin['input']?>" name="autoship_<?php echo $cart_key; ?>" value="no" <?php echo $autoship_no; ?>/>
      <?php echo apply_filters('autoship_radio_label', __( 'One-time Purchase', 'autoship' ), 'no', false, $product ); ?>
    </label>
  </div>

  <div class="autoship-type autoship-yes <?php echo $skin['type']?>">
    <label class="autoship-label <?php echo $skin['label']?>">
      <input type="radio" class="autoship-yes-radio <?php echo $skin['input']?>" name="autoship_<?php echo $cart_key; ?>" value="yes" <?=$autoship_yes?> />

      <?php

      // Output the Extended Discount String
      echo $autoship_data['custom_percent_discount_str'];

      // Output the Autoship Notice Dialog link
      echo autoship_info_dialog_link( $product->get_id() );

      // Output the Autoship Product Message
      echo autoship_product_message_string( $product->get_id() );

      ?>

    </label>
  </div>

  <?php do_action('autoship_after_cart_schedule_radio_options', $product, $frequency_type, $frequency, $next_occurrence, $cart_item, $autoship_selected ); ?>

  <div class="autoship-frequency <?php echo $skin['frequency']?>" <?php echo $autoship_active; ?>>

    <label class="<?php echo $skin['label']?>"><?php echo apply_filters('autoship_frequency_label', __( 'Schedule', 'autoship' ), $product ); ?></label>

    <select class="autoship-frequency-select <?php echo $skin['select']?>">
      <?php foreach ( autoship_product_frequency_options( $product->get_id() ) as $option ):

      // Check if this option is currently assigned.
      $selected =  $autoship_selected && ( $cart_item['autoship_frequency_type'] == $option['frequency_type'] && $cart_item['autoship_frequency'] == $option['frequency'] ) ? 'selected="selected" class="current-frequency"' : '';?>

        <option value="<?php echo esc_attr( json_encode( $option ) ); ?>" <?php echo $selected; ?>>
          <?php echo esc_html( $option['display_name'] ); ?>
        </option>

      <?php endforeach; ?>
    </select>

  </div>

  <input type="hidden" class="autoship-frequency-type-value" name="cart[<?php echo $cart_key; ?>][autoship_frequency_type]" value="<?php echo esc_attr( $frequency_type ); ?>" />
  <input type="hidden" class="autoship-frequency-value" name="cart[<?php echo $cart_key; ?>][autoship_frequency]" value="<?php echo esc_attr( $frequency ); ?>" />
  <input type="hidden" class="autoship-next-occurrence-value"name="cart[<?php echo $cart_key; ?>][autoship_next_occurrence]" value="<?php echo esc_attr( $next_occurrence ); ?>" />

  <?php do_action('autoship_after_cart_schedule_options', $product, $frequency_type, $frequency, $next_occurrence, $cart_item, $autoship_selected ); ?>

</div>
