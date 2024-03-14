<?php

/*
Class Name: WOO_PRE_ORDER_Admin_Product
Author: villatheme
Author URI: http://villatheme.com
Copyright 2020-2021 villatheme.com. All rights reserved.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WPRO_WOO_PRE_ORDER_Admin_Product {
	public function __construct() {
		$get_option = get_option( 'pre_order_setting_default' );
		if ( $get_option['enabled'] == 'yes' ) {
			add_action( '_wpro_schedule_date_cron', array( $this, 'pre_order_event_date' ) );
			add_action( '_wpro_schedule_date_cron_variation', array( $this, 'pre_order_event_date_variable' ) );
			add_filter( 'product_type_options', array( $this, 'pre_order_checkbox' ), 5 );
			add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_my_custom_product_data_tab' ), 10, 1 );
			add_action( 'woocommerce_product_data_panels', array($this,	'woo_pre_order_data_custom_field_simple') );
			add_action( 'woocommerce_process_product_meta', array( $this, 'pre_order_save_simple_data' ) );
			add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'woo_pre_order_data_custom_field_variation' ), 10, 3 );
			add_action( 'woocommerce_save_product_variation', array( $this, 'pre_order_save_variation_data' ), 10, 2 );
			add_action( 'woocommerce_variation_options', array( $this, 'add_pre_order_variable_checkbox' ), 10, 3 );
		}
	}

	/** Cron date variable
	 *
	 * @param $variation_id
	 */
	public function pre_order_event_date_variable( $variation_id ) {
		update_post_meta( $variation_id, '_wpro_date_variable', '' );
		update_post_meta( $variation_id, '_wpro_time_variable', '' );
		update_post_meta( $variation_id, '_wpro_variable_is_preorder', 'no' );
	}

	/** Cron date simple
	 *
	 * @param $post_id
	 */
	public function pre_order_event_date( $post_id ) {
		update_post_meta( $post_id, '_wpro_date', '' );
		update_post_meta( $post_id, '_wpro_time', '' );
		update_post_meta( $post_id, '_simple_preorder', 'no' );
	}


	/**  Pre Order Checkbox
	 *
	 * @param $product_type_options
	 *
	 * @return mixed
	 */
	public function pre_order_checkbox( $product_type_options ) {
		?>
        <style>
            #woocommerce-product-data ul.wc-tabs li a::before {
                content: "\f174";
            }
        </style>
		<?php
		$product_type_options['simple_preorder'] = array(
			'id'            => 'simple_preorder',
			'wrapper_class' => 'show_if_simple hide_if_bundle',
			'label'         => esc_html__( 'Pre-Order', 'product-pre-orders-for-woo' ),
			'description'   => esc_html__( 'Set the Pre-Order status for this product.', 'product-pre-orders-for-woo' ),
			'default'       => 'no',
		);

		return $product_type_options;
	}

	/** Create new tab pre order
	 *
	 * @param $product_data_tabs
	 *
	 * @return mixed
	 */
	public function add_my_custom_product_data_tab( $product_data_tabs ) {
		$product_data_tabs['pre-order-tab'] = array(
			'label'  => esc_html__( 'Pre-Order', 'product-pre-orders-for-woo' ),
			'class'  => array( 'show_if_pre_order', 'hidden' ),
			'target' => 'wpro_pre_order',
		);

		return $product_data_tabs;
	}

	/**
	 * Create new fields for simpple
	 */
	public function woo_pre_order_data_custom_field_simple() {
		global $post;
		echo '<div class="panel woocommerce_options_panel" id = "wpro_pre_order" >';
		$product_object  = wc_get_product( $post->ID );
		$offset          = get_option( 'gmt_offset' );
		$date            = $product_object->get_meta( '_wpro_date' ) ? ( $product_object->get_meta( '_wpro_date' ) + $offset * 3600 ) : 0;
		$sale_time       = $product_object->get_meta( '_wpro_time' );
		$pre_order_date  = $date ? date_i18n( 'Y-m-d', $date ) : '';
		$label           = $product_object->get_meta( '_wpro_label' ) ? $product_object->get_meta( '_wpro_label' ) : '';
		$date_label      = $product_object->get_meta( '_wpro_date_label' );
		$no_date_label   = $product_object->get_meta( '_wpro_no_date_label' );
		$manage_price    = $product_object->get_meta( '_wpro_manage_price' );
		$_price_manually = $product_object->get_meta( '_wpro_price_type' );
		if ( ! $_price_manually ) {
			$_price_manually = 'manual';
		}
		$_amount_price = $product_object->get_meta( '_wpro_amount_price' );
		if ( ! $_amount_price ) {
			$_amount_price = 'fixed';
		}
		$price = get_post_meta( $post->ID, '_wpro_price', true ) ? ( get_post_meta( $post->ID, '_wpro_price', true ) ) : '';

		echo '<p class="form-field _wpro_dates_field">
                    <label>' . esc_html__( 'Pre-Order date', 'product-pre-orders-for-woo' ) . '</label>
					<span class="wrap">
						<input type="text" class="_wpro_date" id="_wpro_date" name="_wpro_date" value="' . esc_attr( $pre_order_date ) . '" placeholder="' . esc_html_x( 'Date&hellip;', 'placeholder', 'product-pre-orders-for-woo' ) . ' YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
						<input type="time" id="_wpro_time" name="_wpro_time" class="_wpro_time" value="' . esc_attr($sale_time) . '" >
					</span>'
		     . wc_help_tip( esc_html__( 'Set the date when the product will be for sale. The Timezone used is the WorldPress local Timezone. Settings -> General -> Timezone.', 'product-pre-orders-for-woo' ) ) . '</p>';

		woocommerce_wp_text_input(
			array(
				'id'          => '_wpro_label',
				'label'       => esc_html__( 'Pre-Order label', 'product-pre-orders-for-woo' ),
				'placeholder' => esc_html__( 'Pre-Order Now', 'product-pre-orders-for-woo' ),
				'desc_tip'    => true,
				'value'       => $label,
				'description' => esc_html__( "Set a custom label to announce the pre-order product status. For example: Pre-Order Now!. Leaving it blankl, it will show the default label (Pre-Order).", 'product-pre-orders-for-woo' ),
				'type'        => 'text',
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'          => '_wpro_date_label',
				'label'       => esc_html__( 'Availability date label', 'product-pre-orders-for-woo' ),
				'placeholder' => esc_html__( 'Available on: {availability_date} at {availability_time}', 'product-pre-orders-for-woo' ),
				'desc_tip'    => true,
				'value'       => esc_attr($date_label),
				'description' => esc_html__( "Use {availability_date} and {availability_time} to show when to remove the Pre-Order status.", 'product-pre-orders-for-woo' ),
				'type'        => 'text',
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'          => '_wpro_no_date_label',
				'label'       => esc_html__( 'No date label', 'product-pre-orders-for-woo' ),
				'placeholder' => esc_html__( 'Coming soon...', 'product-pre-orders-for-woo' ),
				'desc_tip'    => true,
				'value'       => esc_attr($no_date_label),
				'description' => esc_html__( "Message to be shown when no date is set. Leave it empty to use the global option.", 'product-pre-orders-for-woo' ),
				'type'        => 'text',
			)
		);

		?>

        <p class="form-field wpro-manage-price">
            <label for="_wpro_manage_price"><?php echo esc_html__( 'Manage Price', 'product-pre-orders-for-woo' ); ?></label>
            <span class="wrap">
 		        	<input class="_wpro_manage_price" id="_wpro_manage_price" type="checkbox"
                           name="_wpro_manage_price"
                           value="yes"<?php checked( $manage_price, esc_attr( 'yes' ) ); ?> />
                    <?php echo esc_html__( 'Allow to change prices.', 'product-pre-orders-for-woo' ); ?>
 	        	</span>
        </p>

        <div class="wpro-form-manage-price hidden">
            <p class="form-field _wpro_price_adjustment_field">
                <label for="_wpro_dates_from"><?php echo esc_html__( 'Price adjustment', 'product-pre-orders-for-woo' ); ?></label>
                <span class="wrap">
 		        	<input class="_wpro_price_manually" id="_wpro_price_manually" type="radio" name="_wpro_price_type"
                           value="manual" <?php checked( $_price_manually, esc_attr( 'manual' ) ); ?>/>
                    <?php echo esc_html__( ' Set pre-order price to a specific value', 'product-pre-orders-for-woo' ); ?><br><br>
                    <input class="_wpro_discount_price" id="_wpro_discount_price" type="radio" name="_wpro_price_type"
                           value="discount" <?php checked( $_price_manually, esc_attr( 'discount' ) ); ?> />
                    <?php echo esc_html__( 'Adjust to decrease pre-order price', 'product-pre-orders-for-woo' ); ?><br><br>
                    <input class="_wpro_mark_up" id="_wpro_mark_up" type="radio" name="_wpro_price_type"
                           value="markup" <?php checked( $_price_manually, esc_attr( 'markup' ) ); ?> />
                    <?php echo esc_html__( 'Adjust to increase pre-order price', 'product-pre-orders-for-woo' ); ?>
 	        	</span>
            </p>

            <p class="form-field _wpro_price_adjustment_type_simple hidden">
                <label for="_wpro_type_from"><?php echo esc_html__( 'Adjustment type', 'product-pre-orders-for-woo' ); ?></label>
                <span class="wrap">
 		        	<input class="_wpro_amount_price" id="_wpro_amount_price" type="radio" name="_wpro_amount_price"
                           value="fixed"<?php checked( $_amount_price, esc_attr( 'fixed' ) ); ?> />
                    <?php echo esc_html__( 'Set a fixed amount to apply to the pre-order price', 'product-pre-orders-for-woo' ); ?><br><br>
                    <input class="_wpro_percentage_price" id="_wpro_percentage_price" type="radio"
                           name="_wpro_amount_price"
                           value="percentage" <?php checked( $_amount_price, esc_attr( 'percentage' ) ); ?> />
                    <?php echo esc_html__( ' Set a percentage to apply to the pre-order price', 'product-pre-orders-for-woo' ); ?><br><br>
 	        	</span>
            </p>
			<?php
			echo '<p class="form-field _wpro_price">
                    <label>' . esc_html__( 'Adjustment amount', 'product-pre-orders-for-woo' ) . '</label>
					<span class="wrap">
						<input type="number" class="_wpro_price" id="_wpro_price" name="_wpro_price" value="' . esc_attr( $price ) . '" min ="0" />
					</span>'
			     . wc_help_tip( esc_html__( 'Set the quantity to apply (Fixed or percentage). Type numbers only.', 'product-pre-orders-for-woo' ) ) . '</p>';
			?>
        </div>
		<?php
		echo '</div>';
	}

	/** Pre Order checkbox variations
	 *
	 * @param $loop
	 * @param $variation_data
	 * @param $variation
	 */
	public function add_pre_order_variable_checkbox( $loop, $variation_data, $variation ) {
		$product_project = wc_get_product( $variation->ID );
		$is_preorder     = $product_project->get_meta( '_wpro_variable_is_preorder' );
		?>
        <label>
            <input type="checkbox" id="_wpro_variable_is_preorder[<?php echo esc_attr($loop); ?>]"
                   class="_wpro_variable_is_preorder"
                   name="_wpro_variable_is_preorder[<?php echo esc_attr($loop); ?>]"<?php checked( $is_preorder, esc_attr( 'yes' ) ); ?> />
			<?php _ex( 'Pre-Order', 'product-pre-orders-for-woo' ); ?>
			<?php echo wc_help_tip( esc_html__( 'Enable this option to set this variation to the Pre-Order status.', 'product-pre-orders-for-woo' ) ); ?>
        </label>
		<?php
	}

	/** Create new fields for variations
	 *
	 * @param $loop
	 * @param $variation_data
	 * @param $variation
	 */
	public function woo_pre_order_data_custom_field_variation( $loop, $variation_data, $variation ) {
		echo '<div class="wpro-pre-order-variable hidden">';
		$variation_object = wc_get_product( $variation->ID );
		$offset           = get_option( 'gmt_offset' );
		$date             = $variation_object->get_meta( '_wpro_date_variable' ) ? ( $variation_object->get_meta( '_wpro_date_variable' ) + $offset * 3600 ) : 0;
		$sale_time        = $variation_object->get_meta( '_wpro_time_variable' );
		$pre_order_date   = $date ? date_i18n( 'Y-m-d', $date ) : '';
		$manage_price     = $variation_object->get_meta( '_wpro_manage_price_variable' );
		$date_label       = $variation_object->get_meta( '_wpro_date_label_variable' );
		$no_date_label    = $variation_object->get_meta( '_wpro_no_date_label_variable' );
		$label            = ( get_post_meta( $variation->ID, '_wpro_label_variable', true ) ? ( get_post_meta( $variation->ID, '_wpro_label_variable', true ) ) : '' );
		$price_type       = $variation_object->get_meta( '_wpro_price_type_variable' );
		if ( ! $price_type ) {
			$price_type = 'manual';
		}
		$adjustment_type = $variation_object->get_meta( '_wpro_amount_price_variable' );
		if ( ! $adjustment_type ) {
			$adjustment_type = 'fixed';
		}
		$pre_order_price = ( get_post_meta( $variation->ID, '_wpro_price_variable', true ) ? ( get_post_meta( $variation->ID, '_wpro_price_variable', true ) ) : '' );

		echo '<div class="form-field _wpro_dates_field_variable">
					<p class="form-row form-row-first">
						<label>' . esc_html__( 'Pre-Order date', 'product-pre-orders-for-woo' ) . '</label><br>
						<input style="width: 364px;" type="text" class="_wpro_date_variable" id="_wpro_date_variable[' . esc_attr($loop) . ']" name="_wpro_date_variable[' . esc_attr($loop) . ']" value="' . esc_attr( $pre_order_date ) . '" placeholder="' . esc_html_x( 'DATE&hellip;', 'placeholder', 'woocommerce' ) . ' YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
						<input style="width: 145px;" type="time" id="_wpro_time_variable[' . esc_attr($loop) . ']" name="_wpro_time_variable[' . esc_attr($loop) . ']" class="_wpro_time_variable" value="' . esc_attr($sale_time) . '">         
					</p>';

		echo '<p class="form-field form-row form-row-last">
                        <label for="_wpro_label_variable' . esc_attr($loop) . '">' . esc_html__( 'Pre-Order label', 'product-pre-orders-for-woo' ) . '</label>' .
		     wc_help_tip( esc_html__( 'Set a custom label to announce the pre-order product status. For example: Pre-Order Now!. Leaving it blankl, it will show the default label (Pre-Order)', 'product-pre-orders-for-woo' ) ) .
		     '<input type="text"  value="' . esc_attr($label) . '" name="_wpro_label_variable[' . esc_attr($loop) . ']" id="_wpro_label_variable' . esc_attr($loop) . '">
                    </p>
				   </div>';
		?>
        <p class="form-field form-row form-row-first wpro-date-label-variable">
            <label for="_wpro_date_label_variable"><?php echo esc_html__( 'Availability date label', 'product-pre-orders-for-woo' ); ?></label>
			<?php echo wc_help_tip( esc_html__( 'Use {availability_date} and {availability_time} to show when to remove the Pre-Order status.', 'product-pre-orders-for-woo' ) ); ?>
            <span class="wrap">
 		        	<input class="_wpro_date_label_variable"
                           id="_wpro_date_label_variable[<?php echo esc_attr($loop); ?>]" type="text"
                           name="_wpro_date_label_variable[<?php echo esc_attr($loop); ?>]"
                           value="<?php echo esc_attr__( $date_label ) ?>"
                           placeholder="<?php echo esc_attr__( 'Available on: {availability_date} at {availability_time}', 'product-pre-orders-for-woo' ); ?>"/>
 	        	</span>
        </p>

        <p class="form-field form-row form-row-last wpro-no-date-label-variable">
            <label for="_wpro_no_date_label_variable"><?php echo esc_html__( 'No date label', 'product-pre-orders-for-woo' ); ?></label>
			<?php echo wc_help_tip( esc_html__( 'Message to be shown when no date is set. Leave it empty to use the global option.', 'product-pre-orders-for-woo' ) ); ?>
            <span class="wrap">
 		        	<input class="_wpro_no_date_label_variable"
                           id="_wpro_no_date_label_variable[<?php echo esc_attr($loop); ?>]" type="text"
                           name="_wpro_no_date_label_variable[<?php echo esc_attr($loop); ?>]"
                           value="<?php echo esc_attr__( $no_date_label ) ?>"
                           placeholder="<?php echo esc_attr__( 'Coming soon...', 'product-pre-orders-for-woo' ); ?>"/>
 	        	</span>
        </p>

        <div class="form-field form-row form-row-first wpro-pre-order-variation">
            <p class="wpro-manage-price-variable">
                <label for="_wpro_manage_price_variable"><?php echo esc_html__( 'Manage Price', 'product-pre-orders-for-woo' ); ?></label></br></br>
                <span class="wrap">
 		        	<input class="_wpro_manage_price_variable"
                           id="_wpro_manage_price_variable[<?php echo esc_attr($loop); ?>]" type="checkbox"
                           name="_wpro_manage_price_variable[<?php echo esc_attr($loop); ?>]"
                           value="yes" <?php checked( $manage_price, esc_attr( 'yes' ) ); ?>/>
                    <?php echo esc_html__( 'Allow to change prices.', 'product-pre-orders-for-woo' ); ?>
 	        	</span>
            </p>
            <div class=" wpro-manage-price hidden">
                <p class="_wpro_price_adjustment_field">
                    <label for="_wpro_type_variable_from"><?php echo esc_html__( 'Price adjustment', 'product-pre-orders-for-woo' ); ?></label></br></br>
                    <span class="wrap">

 		        	<input class="_wpro_price_manually_variable"
                           id="_wpro_price_manually_variable[<?php echo esc_attr($loop); ?>]" type="radio"
                           name="_wpro_price_type_variable[<?php echo esc_attr($loop); ?>]"
                           value="manual" <?php checked( $price_type, esc_attr( 'manual' ) ); ?>/>
                    <?php echo esc_html__( 'Set pre-order price to a specific value', 'product-pre-orders-for-woo' ); ?><br><br>

                    <input class="_wpro_discount_price_variable"
                           id="_wpro_discount_price_variable[<?php echo esc_attr($loop); ?>]" type="radio"
                           name="_wpro_price_type_variable[<?php echo esc_attr($loop); ?>]"
                           value="discount" <?php checked( $price_type, esc_attr( 'discount' ) ); ?> />
                    <?php echo esc_html__( 'Adjust to decrease pre-order price', 'product-pre-orders-for-woo' ); ?><br><br>

                    <input class="_wpro_markup_variable" id="_wpro_markup_variable[<?php echo esc_attr($loop); ?>]" type="radio"
                           name="_wpro_price_type_variable[<?php echo esc_attr($loop); ?>]"
                           value="markup" <?php checked( $price_type, esc_attr( 'markup' ) ); ?> />
                    <?php echo esc_html__( 'Adjust to increase pre-order price', 'product-pre-orders-for-woo' ); ?>
 	        	</span>
                </p>
                <p class=" _wpro_price_adjustment_type_variable hidden">
                    <label for="_wpro_type_variable_from"><?php echo esc_html__( 'Adjustment type', 'product-pre-orders-for-woo' ); ?></label></br></br>
                    <span class="wrap">
 		        	<input class="_wpro_amount_price_variable" id="_wpro_amount_price_variable[<?php echo esc_attr($loop); ?>]"
                           type="radio"
                           name="_wpro_amount_price_variable[<?php echo esc_attr($loop); ?>]"
                           value="fixed"<?php checked( $adjustment_type, esc_attr( 'fixed' ) ); ?> />
                     <?php echo esc_html__( 'Set a fixed amount to apply to the pre-order price', 'product-pre-orders-for-woo' ); ?><br><br>

                    <input class="_wpro_percentage_price_variable"
                           id="_wpro_percentage_price_variable[<?php echo esc_attr($loop); ?>]" type="radio"
                           name="_wpro_amount_price_variable[<?php echo esc_attr($loop); ?>]"
                           value="percentage" <?php checked( $adjustment_type, esc_attr( 'percentage' ) ); ?> />
                     <?php echo esc_html__( 'Set a percentage to apply to the pre-order price', 'product-pre-orders-for-woo' ); ?>
 	        	</span>
                </p>
				<?php
				echo '<p class="form-field _wpro_price_variable[' . esc_attr($loop) . ']"> ' . wc_help_tip( esc_html__( 'Set the quantity to apply (Fixed or percentage). Type numbers only.', 'product-pre-orders-for-woo' ) ) .
				     '<label>' . esc_html__( 'Adjustment amount', 'product-pre-orders-for-woo' ) . '</label> 
					<span class="wrap">
						<input type="number" class="_wpro_price_variable" id="_wpro_price_variable[' . esc_attr($loop) . ']" min ="0" name="_wpro_price_variable[' . esc_attr($loop) . ']" value="' . esc_attr( $pre_order_price ) . '" />
					</span></p>';
				?>
            </div>
        </div>
		<?php
		echo '</div>';
	}

	public static function pre_order_time( $time ) {
		if ( ! $time ) {
			return 0;
		}
		$temp = explode( ":", $time );
		if ( count( $temp ) == 2 ) {
			return ( absint( $temp[0] ) * 3600 + absint( $temp[1] ) * 60 );
		} else {
			return 0;
		}
	}

	/** Save our simple product fields
	 *
	 * @param $post_id
	 */
	public function pre_order_save_simple_data( $post_id ) {
		$_price_manually = isset( $_POST['_wpro_price_type'] ) ? sanitize_text_field( $_POST['_wpro_price_type'] ) : '';
		$_amount_price   = isset( $_POST['_wpro_amount_price'] ) ? sanitize_text_field( $_POST['_wpro_amount_price'] ) : '';
		$check_box       = isset( $_POST['simple_preorder'] ) && sanitize_text_field( $_POST['simple_preorder'] ) ? 'yes' : 'no';
		$manage_price    = isset( $_POST['_wpro_manage_price'] ) ? sanitize_text_field( $_POST['_wpro_manage_price'] ) : '';
		$save_price      = isset( $_POST['_wpro_price'] ) ? sanitize_text_field( $_POST['_wpro_price'] ) : '';
		$save_label      = isset( $_POST['_wpro_label'] ) ? sanitize_text_field( $_POST['_wpro_label'] ) : '';
		$date_label      = isset( $_POST['_wpro_date_label'] ) ? sanitize_text_field( $_POST['_wpro_date_label'] ) : '';
		$no_date_label   = isset( $_POST['_wpro_no_date_label'] ) ? sanitize_text_field( $_POST['_wpro_no_date_label'] ) : '';
		$_field_date     = isset( $_POST['_wpro_date'] ) ? strtotime( sanitize_text_field( $_POST['_wpro_date'] ) ) : '';
		$_field_time     = isset( $_POST['_wpro_time'] ) ? self::pre_order_time( sanitize_text_field( $_POST['_wpro_time'] ) ) : '';
		$gmt_offset      = get_option( 'gmt_offset' );
		if ( $_field_date ) {
			$_field_date += $_field_time - $gmt_offset * HOUR_IN_SECONDS;
		}
		$next_date = wp_next_scheduled( '_wpro_schedule_date_cron', array( $post_id ) );
		if ( $next_date ) {
			if ( $next_date !== $_field_date ) {
				wp_unschedule_event( $next_date, '_wpro_schedule_date_cron', array( $post_id ) );
				if ( $_field_date ) {
					wp_schedule_single_event( $_field_date, '_wpro_schedule_date_cron', array( $post_id ) );
				} else {
					wp_schedule_single_event( $next_date, '_wpro_schedule_date_cron', array( $post_id ) );
				}
			} elseif ( $_field_date == '' ) {
				wp_unschedule_event( $next_date, '_wpro_schedule_date_cron', array( $post_id ) );
			}
		} else {
			if ( $_field_date ) {
				wp_schedule_single_event( $_field_date, '_wpro_schedule_date_cron', array( $post_id ) );
			}
		}

		update_post_meta( $post_id, '_wpro_no_date_label', $no_date_label );
		update_post_meta( $post_id, '_wpro_date_label', $date_label );
		update_post_meta( $post_id, '_wpro_manage_price', $manage_price );
		update_post_meta( $post_id, '_wpro_price_type', $_price_manually );
		update_post_meta( $post_id, '_wpro_amount_price', $_amount_price );
		update_post_meta( $post_id, '_simple_preorder', $check_box );
		update_post_meta( $post_id, '_wpro_price', $save_price );
		update_post_meta( $post_id, '_wpro_label', $save_label );
		update_post_meta( $post_id, '_wpro_date', $_field_date );
		update_post_meta( $post_id, '_wpro_time', isset( $_POST['_wpro_time'] ) ? sanitize_text_field( $_POST['_wpro_time'] ) : '00:00' );
	}

	/** Save our variable product fields
	 *
	 * @param $variation_id
	 * @param $a
	 */
	public function pre_order_save_variation_data( $variation_id, $a ) {
		$_price_manually = isset( $_POST['_wpro_price_type_variable'][ $a ] ) ? sanitize_text_field( $_POST['_wpro_price_type_variable'][ $a ] ) : '';
		$_amount_price   = isset( $_POST['_wpro_amount_price_variable'][ $a ] ) ? sanitize_text_field( $_POST['_wpro_amount_price_variable'][ $a ] ) : '';
		$save_price      = isset( $_POST['_wpro_price_variable'][ $a ] ) ? sanitize_text_field( $_POST['_wpro_price_variable'][ $a ] ) : '';
		$save_label      = isset( $_POST['_wpro_label_variable'][ $a ] ) ? sanitize_text_field( $_POST['_wpro_label_variable'][ $a ] ) : '';
		$date_label      = isset( $_POST['_wpro_date_label_variable'][ $a ] ) ? sanitize_text_field( $_POST['_wpro_date_label_variable'][ $a ] ) : '';
		$no_date_label   = isset( $_POST['_wpro_no_date_label_variable'][ $a ] ) ? sanitize_text_field( $_POST['_wpro_no_date_label_variable'][ $a ] ) : '';
		$manage_price    = isset( $_POST['_wpro_manage_price_variable'][ $a ] ) ? sanitize_text_field( $_POST['_wpro_manage_price_variable'][ $a ] ) : '';
		$_field_date     = isset( $_POST['_wpro_date_variable'][ $a ] ) ? strtotime( sanitize_text_field( $_POST['_wpro_date_variable'][ $a ] ) ) : '';
		$_field_time     = isset( $_POST['_wpro_time_variable'][ $a ] ) ? self::pre_order_time( sanitize_text_field( $_POST['_wpro_time_variable'][ $a ] ) ) : '';
		$check_box       = isset( $_POST['_wpro_variable_is_preorder'][ $a ] ) && sanitize_text_field( $_POST['_wpro_variable_is_preorder'][ $a ] ) ? 'yes' : 'no';
		$gmt_offset      = get_option( 'gmt_offset' );
		if ( $_field_date ) {
			$_field_date += $_field_time - $gmt_offset * HOUR_IN_SECONDS;
		}

		$next_date = wp_next_scheduled( '_wpro_schedule_date_cron_variation', array( $variation_id ) );
		if ( $next_date ) {
			if ( $next_date !== $_field_date ) {
				wp_unschedule_event( $next_date, '_wpro_schedule_date_cron_variation', array( $variation_id ) );
				if ( $_field_date ) {
					wp_schedule_single_event( $_field_date, '_wpro_schedule_date_cron_variation', array( $variation_id ) );
				}
			} elseif ( $_field_date == '' ) {
				wp_unschedule_event( $next_date, '_wpro_schedule_date_cron_variation', array( $variation_id ) );
			}
		} else {
			if ( $_field_date ) {
				wp_schedule_single_event( $_field_date, '_wpro_schedule_date_cron_variation', array( $variation_id ) );
			}
		}

		update_post_meta( $variation_id, '_wpro_date_label_variable', $date_label );
		update_post_meta( $variation_id, '_wpro_no_date_label_variable', $no_date_label );
		update_post_meta( $variation_id, '_wpro_manage_price_variable', $manage_price );
		update_post_meta( $variation_id, '_wpro_price_variable', $save_price );
		update_post_meta( $variation_id, '_wpro_label_variable', $save_label );
		update_post_meta( $variation_id, '_wpro_price_type_variable', $_price_manually );
		update_post_meta( $variation_id, '_wpro_amount_price_variable', $_amount_price );
		update_post_meta( $variation_id, '_wpro_date_variable', $_field_date );
		update_post_meta( $variation_id, '_wpro_time_variable', isset( $_POST['_wpro_time_variable'][ $a ] ) ? sanitize_text_field( $_POST['_wpro_time_variable'][ $a ] ) : '00:00' );
		update_post_meta( $variation_id, '_wpro_variable_is_preorder', $check_box );
	}
}
