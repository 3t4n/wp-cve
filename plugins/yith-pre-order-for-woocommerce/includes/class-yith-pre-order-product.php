<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH\PreOrder\Includes
 * @author YITH <plugins@yithemes.com>
 */

if ( ! defined( 'YITH_WCPO_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

if ( ! class_exists( 'YITH_Pre_Order_Product' ) ) {
	/**
	 * Class YITH_Pre_Order_Product
	 */
	class YITH_Pre_Order_Product {

		/**
		 * The WC_Product object.
		 *
		 * @var false|WC_Product|null
		 */
		public $product;

		/**
		 * The product ID.
		 *
		 * @var int|string
		 */
		public $id;

		/**
		 * Constructor. Should be called from ywpo_get_pre_order().
		 *
		 * @param WC_Product $product The WC_Product object.
		 *
		 * @since  1.0.0
		 */
		public function __construct( $product ) {
			$this->product = '';
			$this->id      = '';

			if ( $product instanceof WC_Product ) {
				$this->product = $product;
				if ( apply_filters( 'ywpo_wpml_enable_default_lang_sync', true, $product ) ) {
					$this->id = $product->get_id();
				} else {
					$this->id = apply_filters( 'wpml_object_id', $product->get_id(), 'product', true );
				}
			}
		}

		// Getters.

		/**
		 * Get the pre-order status value. This value defines if a product is a pre-order or not, but not if the
		 * pre-order is currently active. For checking if a pre-order product is active,
		 * see YITH_Pre_Order()::is_pre_order_active().
		 *
		 * @return string
		 */
		public function get_pre_order_status() {
			$pre_order_status = get_post_meta( $this->id, '_ywpo_preorder', true );
			return apply_filters( 'yith_ywpo_pre_order_get_status', $pre_order_status, $this->id, $this->product );
		}

		/**
		 * Get the pre-order mode value.
		 *
		 * @return string
		 */
		public function get_pre_order_mode() {
			$pre_order_mode = get_post_meta( $this->id, '_ywpo_pre_order_mode', true );
			return apply_filters( 'yith_ywpo_pre_order_get_mode', $pre_order_mode, $this->id, $this->product );
		}

		/**
		 * Get the override pre-order mode value.
		 *
		 * @return string
		 */
		public function get_override_pre_order_mode() {
			$pre_order_mode = get_post_meta( $this->id, '_ywpo_override_pre_order_mode', true );
			return apply_filters( 'yith_ywpo_pre_order_get_override_mode', $pre_order_mode, $this->id, $this->product );
		}

		/**
		 * Get the pre-order start mode.
		 *
		 * @return string
		 */
		public function get_start_mode() {
			$start_mode = get_post_meta( $this->id, '_ywpo_start_mode', true );
			return apply_filters( 'yith_ywpo_pre_order_get_start_mode', $start_mode, $this->id, $this->product );
		}

		/**
		 * Get the start date in human-readable format.
		 *
		 * @return string
		 */
		public function get_start_date() {
			$timestamp  = get_post_meta( $this->id, '_ywpo_start_date', true );
			$start_date = ! empty( $timestamp ) ? get_date_from_gmt( gmdate( 'Y-m-d H:i:s', (int) $timestamp ) ) : '';

			return apply_filters( 'yith_ywpo_pre_order_get_start_date', $start_date, $this->id, $this->product );
		}

		/**
		 * Get the start date in timestamp format.
		 *
		 * @return string
		 */
		public function get_start_date_timestamp() {
			$start_date = get_post_meta( $this->id, '_ywpo_start_date', true );
			return apply_filters( 'yith_ywpo_pre_order_get_start_date', $start_date, $this->id, $this->product );
		}

		/**
		 * Get the custom text for the start date.
		 *
		 * @return string
		 */
		public function get_start_date_label() {
			$start_date_label = get_post_meta( $this->id, '_ywpo_start_date_label', true );
			return apply_filters( 'yith_ywpo_pre_order_get_start_date_label', $start_date_label, $this->id, $this->product );
		}

		/**
		 * Get the value for the availability date mode. Possible values are 'no_date', 'date' and 'dynamic'.
		 *
		 * @return string
		 */
		public function get_availability_date_mode() {
			$availability_date_mode = get_post_meta( $this->id, '_ywpo_availability_date_mode', true );
			return apply_filters( 'yith_ywpo_pre_order_get_availability_date_mode', $availability_date_mode, $this->id, $this->product );
		}

		/**
		 * Get the availability date in human-readable format.
		 *
		 * @return string
		 */
		public function get_for_sale_date() {
			$timestamp     = get_post_meta( $this->id, '_ywpo_for_sale_date', true );
			$for_sale_date = ! empty( $timestamp ) ? get_date_from_gmt( gmdate( 'Y-m-d H:i:s', (int) $timestamp ) ) : '';

			return apply_filters( 'yith_ywpo_pre_order_get_for_sale_date', $for_sale_date, $this->id, $this->product );
		}

		/**
		 * Get the availability date in timestamp format.
		 *
		 * @return string
		 */
		public function get_for_sale_date_timestamp() {
			$timestamp = get_post_meta( $this->id, '_ywpo_for_sale_date', true );
			return apply_filters( 'yith_ywpo_pre_order_get_for_sale_date_timestamp', $timestamp, $this->id, $this->product );
		}

		/**
		 * Calculates the availability date for a product, having a fixed date or a dynamic date.
		 *
		 * @return string
		 */
		public function calculate_availability_date_timestamp() {
			$timestamp               = '';
			$availability_date_mode  = $this->get_availability_date_mode();
			$days                    = $this->get_dynamic_availability_date();
			$for_sale_date_timestamp = $this->get_for_sale_date_timestamp();

			// For backward compatibility.
			if ( $for_sale_date_timestamp && ! $availability_date_mode ) {
				$timestamp = $for_sale_date_timestamp;
			}

			if ( 'dynamic' === $availability_date_mode ) {
				$timestamp = time() + ( (int) $days * DAY_IN_SECONDS );
			} elseif ( 'date' === $availability_date_mode ) {
				$timestamp = $for_sale_date_timestamp;
			}

			return apply_filters( 'yith_ywpo_pre_order_calculate_availability_date_timestamp', $timestamp, $availability_date_mode, $days, $for_sale_date_timestamp );
		}

		/**
		 * Get the number of days for the dynamic availability date.
		 *
		 * @return string
		 */
		public function get_dynamic_availability_date() {
			$dynamic_availability_date = get_post_meta( $this->id, '_ywpo_dynamic_availability_date', true );
			return apply_filters( 'yith_ywpo_pre_order_get_dynamic_availability_date', $dynamic_availability_date, $this->id, $this->product );
		}

		/**
		 * Get the value for the price mode. Possible values are 'default', 'fixed', 'discount_percentage',
		 * 'discount_fixed', 'increase_percentage' and 'increase_fixed'.
		 *
		 * @return string
		 */
		public function get_price_mode() {
			$price_mode = get_post_meta( $this->id, '_ywpo_price_mode', true );
			return apply_filters( 'yith_ywpo_pre_order_get_price_mode', $price_mode, $this->id, $this->product );
		}

		/**
		 * Get the value to check if the Maximum Quantity option is enabled for this pre-order product.
		 *
		 * @return string
		 */
		public function get_max_qty_enabled() {
			$max_qty_enabled = get_post_meta( $this->id, '_ywpo_max_qty_enabled', true );
			return apply_filters( 'yith_ywpo_pre_order_get_max_qty_enabled', $max_qty_enabled, $this->id, $this->product );
		}

		/**
		 * Get the maximum number of items that can be pre-ordered.
		 *
		 * @return string
		 */
		public function get_max_qty() {
			$max_qty = get_post_meta( $this->id, '_ywpo_max_qty', true );
			return apply_filters( 'yith_ywpo_pre_order_get_max_qty', $max_qty, $this->id, $this->product );
		}

		/**
		 * Get the value to check if the pre-order labels are overridden for this pre-order product.
		 *
		 * @return string
		 */
		public function get_override_labels() {
			$override_labels = get_post_meta( $this->id, '_ywpo_override_labels', true );
			return apply_filters( 'yith_ywpo_pre_order_get_override_labels', $override_labels, $this->id, $this->product );
		}

		/**
		 * Get the text that substitutes the Add to cart button text.
		 *
		 * @return string
		 */
		public function get_pre_order_label() {
			$pre_order_label = get_post_meta( $this->id, '_ywpo_preorder_label', true );
			return apply_filters( 'yith_ywpo_pre_order_get_label', $pre_order_label, $this->id, $this->product );
		}

		/**
		 * Get the text that announces the product's release date.
		 *
		 * @return string
		 */
		public function get_pre_order_availability_date_label() {
			$pre_order_availability_date_label = get_post_meta( $this->id, '_ywpo_preorder_availability_date_label', true );
			return apply_filters( 'yith_ywpo_pre_order_get_availability_date_label', $pre_order_availability_date_label, $this->id, $this->product );
		}

		/**
		 * Get the text to display when no release date is set for the pre-order product.
		 *
		 * @return string
		 */
		public function get_pre_order_no_date_label() {
			$pre_order_no_date_label = get_post_meta( $this->id, '_ywpo_preorder_no_date_label', true );
			return apply_filters( 'yith_ywpo_pre_order_get_no_date_label', $pre_order_no_date_label, $this->id, $this->product );
		}

		/**
		 * Get the value to check if the pre-order fee is overridden for this pre-order product.
		 *
		 * @return string
		 */
		public function get_override_fee() {
			$override_fee = get_post_meta( $this->id, '_ywpo_override_fee', true );
			return apply_filters( 'yith_ywpo_pre_order_get_override_fee', $override_fee, $this->id, $this->product );
		}

		/**
		 * Get the fee amount value.
		 *
		 * @return string
		 */
		public function get_fee() {
			$fee = get_post_meta( $this->id, '_ywpo_fee', true );
			return apply_filters( 'yith_ywpo_pre_order_get_fee', $fee, $this->id, $this->product );
		}

		/**
		 * Get the value to check if the charge type is overridden for this pre-order product.
		 *
		 * @return string
		 */
		public function get_override_charge_type() {
			$override_charge_type = get_post_meta( $this->id, '_ywpo_override_charge_type', true );
			return apply_filters( 'yith_ywpo_pre_order_get_override_charge_type', $override_charge_type, $this->id, $this->product );
		}

		/**
		 * Get the charge type.
		 *
		 * @return string
		 */
		public function get_charge_type() {
			$charge_type = get_post_meta( $this->id, '_ywpo_charge_type', true );
			return apply_filters( 'yith_ywpo_pre_order_get_charge_type', $charge_type, $this->id, $this->product );
		}

		/**
		 * Get the pre-order price.
		 *
		 * @return string
		 */
		public function get_pre_order_price() {
			$pre_order_price = get_post_meta( $this->id, '_ywpo_preorder_price', true );
			return apply_filters( 'yith_ywpo_pre_order_get_price', $pre_order_price, $this->id, $this->product );
		}

		/**
		 * Get the value for the percentage amount for discount.
		 *
		 * @return string
		 */
		public function get_discount_percentage() {
			$discount_percentage = get_post_meta( $this->id, '_ywpo_preorder_discount_percentage', true );
			return apply_filters( 'yith_ywpo_pre_order_get_discount_percentage', $discount_percentage, $this->id, $this->product );
		}

		/**
		 * Get the value for the fixed amount for discount.
		 *
		 * @return string
		 */
		public function get_discount_fixed() {
			$discount_fixed = get_post_meta( $this->id, '_ywpo_preorder_discount_fixed', true );
			return apply_filters( 'yith_ywpo_pre_order_get_discount_fixed', $discount_fixed, $this->id, $this->product );
		}

		/**
		 * Get the value for the percentage amount for increase.
		 *
		 * @return string
		 */
		public function get_increase_percentage() {
			$increase_percentage = get_post_meta( $this->id, '_ywpo_preorder_increase_percentage', true );
			return apply_filters( 'yith_ywpo_pre_order_get_increase_percentage', $increase_percentage, $this->id, $this->product );
		}

		/**
		 * Get the value for the fixed amount for increase.
		 *
		 * @return string
		 */
		public function get_increase_fixed() {
			$increase_fixed = get_post_meta( $this->id, '_ywpo_preorder_increase_fixed', true );
			return apply_filters( 'yith_ywpo_pre_order_get_increase_fixed', $increase_fixed, $this->id, $this->product );
		}

		/**
		 * Kept for backward compatibility. Get the adjustment amount.
		 *
		 * @return string
		 */
		public function get_pre_order_adjustment_amount() {
			$price_adjustment_amount = get_post_meta( $this->id, '_ywpo_price_adjustment_amount', true );
			return apply_filters( 'yith_ywpo_pre_order_get_adjustment_amount', $price_adjustment_amount, $this->id, $this );
		}

		/**
		 * Kept for backward compatibility.
		 *
		 * @return string
		 */
		public function get_pre_order_price_adjustment() {
			$price_adjustment = get_post_meta( $this->id, '_ywpo_price_adjustment', true );
			$price_adjustment = empty( $price_adjustment ) ? 'manual' : $price_adjustment;

			return apply_filters( 'yith_ywpo_pre_order_get_price_adjustment', $price_adjustment, $this->id, $this->product );
		}

		/**
		 * Kept for backward compatibility.
		 *
		 * @return string
		 */
		public function get_pre_order_adjustment_type() {
			$adjustment_type = get_post_meta( $this->id, '_ywpo_adjustment_type', true );
			return apply_filters( 'yith_ywpo_pre_order_get_adjustment_type', $adjustment_type, $this->id, $this->product );
		}

		// Setters.

		/**
		 * Set the pre-order status. Possible values are 'yes' or 'no'.
		 *
		 * @param string $status Pre-order status.
		 */
		public function set_pre_order_status( $status ) {
			$old_status = $this->get_pre_order_status();
			update_post_meta( $this->id, '_ywpo_preorder', $status );
			do_action( 'yith_ywpo_pre_order_status_changed', $this->product, $status, $old_status );
		}

		/**
		 * Set the pre-order mode. This is the option used when the product isn't selected for the automatic
		 * pre-order mode. Possible values are 'manual' or 'auto'.
		 * When mode is 'manual', the pre-order will work as usual. When it's 'auto', the pre-order will be available
		 * only when the product is out-of-stock.
		 *
		 * @param string $mode Pre-order mode.
		 */
		public function set_pre_order_mode( $mode ) {
			update_post_meta( $this->id, '_ywpo_pre_order_mode', $mode );
			do_action( 'yith_ywpo_pre_order_mode_changed', $this->product, $mode );
		}

		/**
		 * Set the override pre-order mode. Possible values are 'yes' or 'no'.
		 *
		 * @param string $mode Option value.
		 */
		public function set_override_pre_order_mode( $mode ) {
			update_post_meta( $this->id, '_ywpo_override_pre_order_mode', $mode );
			do_action( 'yith_ywpo_override_pre_order_mode_changed', $this->product, $mode );
		}

		/**
		 * Set the start mode for the pre-order product. Possible values are:
		 * - 'now':  There is no start date, the product is available for pre-order immediately.
		 * - 'date': There is a start date. The product cannot be pre-ordered until this date arrives.
		 *
		 * @param string $start_mode Start mode.
		 */
		public function set_start_mode( $start_mode ) {
			update_post_meta( $this->id, '_ywpo_start_mode', $start_mode );
			do_action( 'yith_ywpo_start_mode_changed', $this->product, $start_mode );
		}

		/**
		 * Set the start date used when the start date mode is 'date'. The value entered must be in timestamp format.
		 *
		 * @param string $start_date The start date in timestamp format.
		 */
		public function set_start_date( $start_date ) {
			if ( ! empty( $start_date ) ) {
				$formatted_date = get_gmt_from_date( $start_date );
				update_post_meta( $this->id, '_ywpo_start_date', $formatted_date ? strtotime( $formatted_date ) : '' );
				do_action( 'yith_ywpo_start_date_changed', $this->product, $start_date );
			} else {
				update_post_meta( $this->id, '_ywpo_start_date', '' );
			}
		}

		/**
		 * Set the value for the start date label.
		 *
		 * @param string $start_date_label Start date label.
		 */
		public function set_start_date_label( $start_date_label ) {
			update_post_meta( $this->id, '_ywpo_start_date_label', $start_date_label );
			do_action( 'yith_ywpo_start_date_label_changed', $this->product, $start_date_label );
		}

		/**
		 * Set the availability date mode. Possible values are:
		 * - 'no_date': The pre-order doesn't have a release date. It must be manually completed by the admin.
		 * - 'date':    There is a release date for the pre-order. The pre-order will be automatically completed on the
		 *              release date.
		 * - 'dynamic': The release date is calculated X days after the customer places the pre-order.
		 *
		 * @param string $availability_date_mode The availability date mode.
		 */
		public function set_availability_date_mode( $availability_date_mode ) {
			update_post_meta( $this->id, '_ywpo_availability_date_mode', $availability_date_mode );
			do_action( 'yith_ywpo_availability_date_mode_changed', $this->product, $availability_date_mode );
		}

		/**
		 * Set the release date for the pre-order in timestamp format.
		 *
		 * @param string $date The release date in timestamp format.
		 */
		public function set_for_sale_date( $date ) {
			if ( ! empty( $date ) ) {
				$format_date = get_gmt_from_date( $date );
				update_post_meta( $this->id, '_ywpo_for_sale_date', $format_date ? strtotime( $format_date ) : '' );
				do_action( 'yith_ywpo_pre_order_date_changed', $this->id, $date );
			} else {
				update_post_meta( $this->id, '_ywpo_for_sale_date', '' );
			}
		}

		/**
		 * Set the dynamic availability date. This value is not a date, it's a number of days in order to calculate the
		 * release date dynamically based on the day the product is pre-ordered.
		 * Valid only if the availability date mode is 'dynamic'. See get_availability_date_mode().
		 *
		 * @param string $dynamic_availability_date Number of days to calculate the release date dynamically.
		 */
		public function set_dynamic_availability_date( $dynamic_availability_date ) {
			update_post_meta( $this->id, '_ywpo_dynamic_availability_date', $dynamic_availability_date );
			do_action( 'yith_ywpo_dynamic_availability_date_changed', $this->product, $dynamic_availability_date );
		}

		/**
		 * Set the price mode. Possible values are:
		 * - 'default':             Use the product's regular/sale price.
		 * - 'fixed:                Use a fixed pre-order price for this product.
		 * - 'discount_percentage': Apply a percentage discount over the product's price.
		 * - 'discount_fixed':      Apply a fixed amount discount over the product's price.
		 * - 'increase_percentage': Apply a percentage increase over the product's price.
		 * - 'increase_fixed':      Apply a fixed amount increase over the product's price.
		 *
		 * @param string $price_mode The price mode.
		 */
		public function set_price_mode( $price_mode ) {
			update_post_meta( $this->id, '_ywpo_price_mode', $price_mode );
			do_action( 'yith_ywpo_price_mode_changed', $this->product, $price_mode );
		}

		/**
		 * Set if the max qty feature is enabled for this pre-order product.
		 *
		 * @param string $max_qty_enabled Whether is the feature is enabled or not.
		 */
		public function set_max_qty_enabled( $max_qty_enabled ) {
			update_post_meta( $this->id, '_ywpo_max_qty_enabled', $max_qty_enabled );
			do_action( 'yith_ywpo_max_qty_enabled_changed', $this->product, $max_qty_enabled );
		}

		/**
		 * The number of maximum quantity of items that can be pre-ordered for this product.
		 *
		 * @param string $max_qty The option value.
		 */
		public function set_max_qty( $max_qty ) {
			update_post_meta( $this->id, '_ywpo_max_qty', $max_qty );
			do_action( 'yith_ywpo_max_qty_changed', $this->product, $max_qty );
		}

		/**
		 * Set if the pre-order labels ('_ywpo_preorder_label', '_ywpo_preorder_availability_date_label' and
		 * '_ywpo_preorder_no_date_label') are overridden or not.
		 *
		 * @param string $override_labels The option value.
		 */
		public function set_override_labels( $override_labels ) {
			update_post_meta( $this->id, '_ywpo_override_labels', $override_labels );
			do_action( 'yith_ywpo_override_labels_changed', $this->product, $override_labels );
		}

		/**
		 * Set the text that substitutes the Add to cart button text. If '_ywpo_override_labels' is 'yes', this option
		 * will be used rather than the global option.
		 *
		 * @param string $pre_order_label The option value.
		 */
		public function set_pre_order_label( $pre_order_label ) {
			if ( isset( $pre_order_label ) ) {
				update_post_meta( $this->id, '_ywpo_preorder_label', $pre_order_label );
				do_action( 'yith_ywpo_pre_order_label_changed', $this->id, $pre_order_label );
			}
		}

		/**
		 * Set the text that announces the product's release date. If '_ywpo_override_labels' is 'yes', this option
		 * will be used rather than the global option.
		 *
		 * @param string $pre_order_availability_date_label The option value.
		 */
		public function set_pre_order_availability_date_label( $pre_order_availability_date_label ) {
			update_post_meta( $this->id, '_ywpo_preorder_availability_date_label', $pre_order_availability_date_label );
			do_action( 'yith_ywpo_pre_order_availability_date_label_changed', $this->id, $pre_order_availability_date_label );
		}

		/**
		 * Set the text to display when no release date is set for the pre-order product.
		 *
		 * @param string $pre_order_no_date_label The option value.
		 */
		public function set_pre_order_no_date_label( $pre_order_no_date_label ) {
			update_post_meta( $this->id, '_ywpo_preorder_no_date_label', $pre_order_no_date_label );
			do_action( 'yith_ywpo_pre_order_no_date_label_changed', $this->id, $pre_order_no_date_label );
		}

		/**
		 * Set the value to check if the pre-order fee is overridden for this pre-order product.
		 *
		 * @param string $override_fee The option value.
		 */
		public function set_override_fee( $override_fee ) {
			update_post_meta( $this->id, '_ywpo_override_fee', $override_fee );
			do_action( 'yith_ywpo_override_fee_changed', $this->product, $override_fee );
		}

		/**
		 * Set the fee amount value.
		 *
		 * @param string $fee The option value.
		 */
		public function set_fee( $fee ) {
			update_post_meta( $this->id, '_ywpo_fee', $fee );
			do_action( 'yith_ywpo_fee_changed', $this->product, $fee );
		}

		/**
		 * Set the value to check if the charge type is overridden for this pre-order product.
		 *
		 * @param string $override_charge_type The option value.
		 */
		public function set_override_charge_type( $override_charge_type ) {
			update_post_meta( $this->id, '_ywpo_override_charge_type', $override_charge_type );
			do_action( 'yith_ywpo_override_charge_type_changed', $this->product, $override_charge_type );
		}

		/**
		 * Set the charge type. Possible values are:
		 * - 'upfront':      The product will be charged upfront when placing the pre-order.
		 * - 'upon_release': The product will be charged on the release date through an integrated payment gateway.
		 * - 'pay_later':    The product will be pre-ordered through the Pay Later gateway and the payment method can be
		 *                   selected by the customer on the release date.
		 *
		 * @param string $charge_type The option value.
		 */
		public function set_charge_type( $charge_type ) {
			update_post_meta( $this->id, '_ywpo_charge_type', $charge_type );
			do_action( 'yith_ywpo_charge_type_changed', $this->product, $charge_type );
		}

		/**
		 * Set the pre-order price.
		 *
		 * @param string $pre_order_price The option value.
		 */
		public function set_pre_order_price( $pre_order_price ) {
			update_post_meta( $this->id, '_ywpo_preorder_price', $pre_order_price );
			do_action( 'yith_ywpo_pre_order_price_changed', $this->id, $pre_order_price );
		}

		/**
		 * Set the discount percentage amount.
		 *
		 * @param string $discount_percentage The option value.
		 */
		public function set_discount_percentage( $discount_percentage ) {
			update_post_meta( $this->id, '_ywpo_preorder_discount_percentage', $discount_percentage );
			do_action( 'yith_ywpo_discount_percentage_changed', $this->product, $discount_percentage );
		}

		/**
		 * Set the fixed amount for discount.
		 *
		 * @param string $discount_fixed The option value.
		 */
		public function set_discount_fixed( $discount_fixed ) {
			update_post_meta( $this->id, '_ywpo_preorder_discount_fixed', $discount_fixed );
			do_action( 'yith_ywpo_discount_fixed_changed', $this->product, $discount_fixed );
		}

		/**
		 * Set the increase percentage amount.
		 *
		 * @param string $increase_percentage The option value.
		 */
		public function set_increase_percentage( $increase_percentage ) {
			update_post_meta( $this->id, '_ywpo_preorder_increase_percentage', $increase_percentage );
			do_action( 'yith_ywpo_increase_percentage_changed', $this->product, $increase_percentage );
		}

		/**
		 * Set the fixed amount for increase.
		 *
		 * @param string $increase_fixed The option value.
		 */
		public function set_increase_fixed( $increase_fixed ) {
			update_post_meta( $this->id, '_ywpo_preorder_increase_fixed', $increase_fixed );
			do_action( 'yith_ywpo_increase_fixed_changed', $this->product, $increase_fixed );
		}
	}
}
