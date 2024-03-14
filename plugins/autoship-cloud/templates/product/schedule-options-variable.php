<?php
/**
 * The Main Product Schedule Variation Options Template
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/product/schedule-options-variable.php
*/

/*
* The Skins Filter Allows Devs to Completely customize the template classes.
*/
$skin = apply_filters( 'autoship_product_schedule_options_variable_select_skin', array(
'container'             => '',
'type'                  => '',
'label'                 => '',
'input'                 => '',
'span'                  => '',
'frequency'             => '',
'select'                => '',
));

// Apply the default choice filter - to autoship by default or not - that is the question.
$default_check = apply_filters( 'autoship_default_product_schedule_options_choice_value' , 'no', $product );
$autoship_no  = 'no' == $default_check ? 'checked="checked"' : '';
$autoship_yes = 'no' != $default_check ? 'checked="checked"' : '';

$autoship_data = autoship_product_variations_discount_data( $product );
$autoship_json = wp_json_encode( $autoship_data );
$autoship_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $autoship_json ) : _wp_specialchars( $autoship_json, ENT_QUOTES, 'UTF-8', true );

?>

<div id="autoship-schedule-options-variable" class="autoship-schedule-options-variable autoship-schedule-options hidden <?php echo $skin['container']?>" data-autoship-variation-id="0" data-autoship-id="<?php echo $product->get_id(); ?>" data-autoship="<?php echo $autoship_attr;?>">

    <?php do_action('autoship_before_schedule_options_variable', $product, $skin, $default_check ); ?>

    <div class="autoship-type autoship-no <?php echo $skin['type']?>">
      <label class="autoship-label <?php echo $skin['label']?>">
        <input type="radio" class="autoship-no-radio <?php echo $skin['input']?>" name="autoship<?php echo $product->get_id(); ?>" value="no" <?=$autoship_no?> />
        <?php echo apply_filters('autoship_radio_label', __( 'One-time Purchase', 'autoship' ), 'no', false, $product ); ?>
      </label>
    </div>

    <div class="autoship-type autoship-yes <?php echo $skin['type']?>">
      <label class="autoship-label <?php echo $skin['label']?>">
        <input type="radio" class="autoship-yes-radio <?php echo $skin['input']?>" name="autoship<?php echo $product->get_id(); ?>" value="yes" <?=$autoship_yes?> />

        <?php

        // Output the Extended Discount String
        echo autoship_checkout_recurring_variable_discount_string( $product->get_id() );

        // Output the Autoship Notice Dialog link
        echo autoship_info_dialog_link( $product->get_id() );

        // Output the Autoship Product Message
        echo autoship_product_message_string( $product->get_id() );

        ?>

      </label>
    </div>

    <?php do_action('autoship_after_schedule_radio_options_variable', $product, $skin, $default_check ); ?>

    <div class="autoship-frequency <?php echo $skin['frequency']?>">
      <label class="<?php echo $skin['label']?>"><?php echo apply_filters('autoship_frequency_label', __( 'Schedule', 'autoship' ), $product ); ?></label>
      <select class="autoship-frequency-select <?php echo $skin['select']?>"></select>
    </div>

    <div class="loading"><?php echo apply_filters('autoship_schedule_options_variable_loading', __( 'Loading...', 'autoship' ) ); ?></div>

    <input type="hidden" class="autoship-frequency-type-value" name="autoship_frequency_type" value="" />
    <input type="hidden" class="autoship-frequency-value" name="autoship_frequency" value="" />

    <?php do_action('autoship_after_schedule_options_variable', $product, $skin, $default_check ); ?>

</div>

<?php do_action('autoship_after_schedule_options_variable_template', $product ); ?>
