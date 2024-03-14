<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Admin_CMB2_Support {

	/**
	 * Callback function for groups
	 *
	 * @param $field_args CMB2 Field args
	 * @param $field
	 */
	public static function cmb2_xlwcty_before_call( $field_args, $field ) {
		$attributes = '';
		if ( ( $field_args['id'] == '_xlwcty_events' ) ) {
			$class_single = '';
			foreach ( $field_args['attributes'] as $attr => $val ) {
				if ( $attr == 'class' ) {
					$class_single .= ' ' . $val;
				}
				// if data attribute, use single quote wraps, else double
				$quotes     = false !== stripos( $attr, 'data-' ) ? "'" : '"';
				$attributes .= sprintf( ' %1$s=%3$s%2$s%3$s', $attr, $val, $quotes );
			}
			echo '<div class="xlwcty_custom_wrapper_group' . $class_single . '" ' . $attributes . '>';
		}
	}

	/**
	 * Output a message if the current page has the id of "2" (the about page)
	 *
	 * @param object $field_args Current field args
	 * @param object $field Current field object
	 */
	public static function cmb_after_row_cb( $field_args, $field ) {
		echo '</div></div>';
	}

	/**
	 * Output a message if the current page has the id of "2" (the about page)
	 *
	 * @param object $field_args Current field args
	 * @param object $field Current field object
	 */
	public static function cmb_before_row_cb( $field_args, $field ) {
		$default         = array(
			'xlwcty_accordion_title'     => __( 'Untitled', 'woo-thank-you-page-nextmove-lite' ),
			'xlwcty_is_accordion_opened' => false,
		);
		$field_args      = wp_parse_args( $field_args, $default );
		$is_active       = ( $field_args['xlwcty_is_accordion_opened'] ) ? 'active' : '';
		$is_display_none = ( ! $field_args['xlwcty_is_accordion_opened'] ) ? "style='display:none'" : '';

		$xlwcty_notice = '';
		if ( isset( $field_args['xlwcty_error_notice'] ) && XLWCTY_Core()->data->get_option( $field_args['xlwcty_error_notice']['key'] ) == $field_args['xlwcty_error_notice']['value'] ) {
			$xlwcty_notice = '<div class="xlwcty_error_notice">' . $field_args['xlwcty_error_notice']['error'] . '</div>';
		}

		echo '<div class="cmb2_xlwcty_wrapper_ac" data-slug="' . $field_args['xlwcty_component'] . ( isset( $field_args['xlwcty_accordion_index'] ) ? '_' . $field_args['xlwcty_accordion_index'] : '' ) . '" data-title="' . trim( $field_args['xlwcty_accordion_title'] ) . '" data-component="' . $field_args['xlwcty_component'] . '" ><div class="cmb2_xlwcty_acc_head ' . $is_active . ' "><a href="javascript:void(0);">' . $field_args['xlwcty_accordion_title'] . '</a> <div class="toggleArrow"></div></div><div class="cmb2_xlwcty_wrapper_ac_data" ' . $is_display_none . '>' . $xlwcty_notice . '<div class="xlwcty_field_head">' . $field_args['xlwcty_accordion_title'] . ' <a href="javascript:void(0);" onclick="xlwcty_show_tb(\'' . $field_args['xlwcty_accordion_title'] . ' Settings\',\'xlwcty_component_settings' . $field_args['xlwcty_component'] . '_help\');"><i class="dashicons dashicons-warning"></i> See how it appears</a></div>';
	}

	/**
	 * Hooked over `xl_cmb2_add_conditional_script_page` so that we can load conditional logic scripts
	 *
	 * @param $options Pages
	 *
	 * @return mixed
	 */
	public static function xlwcty_push_support_form_cmb_conditionals( $pages ) {

		return $pages;
	}

	public static function row_classes_inline_desc( $field_args, $field ) {
		return array( 'xlwcty_field_inline_desc' );
	}

	public static function row_date_classes( $field_args, $field ) {
		return array( 'xlwcty_field_date_range' );
	}

	public static function render_trigger_nav() {
		$get_thank_you_page_statuses = apply_filters( 'xlwcty_admin_trigger_nav', XLWCTY_Common::get_thank_you_page_statuses() );
		$html                        = '<ul class="subsubsub subsubsub_xlwcty">';
		$html_inside                 = array();
		$html_inside[]               = sprintf( '<li><a href="%s" class="%s">%s</a></li>', admin_url( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() ), self::active_class( 'all' ), __( 'All', 'woo-thank-you-page-nextmove-lite' ) );
		foreach ( $get_thank_you_page_statuses as $status ) {
			$html_inside[] = sprintf( '<li><a href="%s" class="%s">%s</a></li>', admin_url( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() . '&section=' . $status['slug'] ), self::active_class( $status['slug'] ), $status['name'] );
		}

		if ( count( $html_inside ) > 0 ) {
			$html .= implode( '', $html_inside );
		}
		$html .= '</ul>';

		echo $html;
	}

	public static function active_class( $trigger_slug ) {

		if ( self::get_current_trigger() == $trigger_slug ) {
			return 'current';
		}

		return '';
	}

	public static function get_current_trigger() {
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'wc-settings' && isset( $_GET['section'] ) ) {
			return $_GET['section'];
		}

		return 'all';
	}

	public static function cmb2_product_title( $id ) {
		$product = wc_get_product( $id );

		if ( ! $product instanceof WC_Product ) {
			return false;
		}

		$product_name = XLWCTY_Compatibility::woocommerce_get_formatted_product_name( $product );

		return $product_name;
	}

	public static function before_wysiwyg( $field_args, $field ) {

		$attributes = '';

		$class_single = '';
		foreach ( $field_args['attributes'] as $attr => $val ) {
			if ( $attr == 'class' ) {
				$class_single .= ' ' . $val;
			}
			// if data attribute, use single quote wraps, else double
			$quotes     = false !== stripos( $attr, 'data-' ) ? "'" : '"';
			$attributes .= sprintf( ' %1$s=%3$s%2$s%3$s', $attr, $val, $quotes );
		}
		echo '<div class="xlwcty_custom_wrapper_wysiwyg' . $class_single . '" ' . $attributes . '>';
	}

	public static function after_wysiwyg( $field_args, $field ) {
		echo '</div>';
	}

	public static function get_wc_order_statuses() {

		return apply_filters( 'xlwcty_allowed_order_status', wc_get_order_statuses() );
	}

	public static function get_coupons_selected( $field ) {
		$value       = $field->escaped_value();
		$get_coupons = XLWCTY_Common::get_coupons();

		if ( $value && $value !== '' ) {
			$get_post = get_post( $value );

			if ( ! $get_post instanceof WP_Post ) {
				return $get_coupons;
			}
			$get_coupons[ $get_post->ID ] = get_the_title( $get_post );
		}

		return $get_coupons;
	}

	public static function get_orders_cmb2( $field = null, $is_pre_data = false ) {
		$data     = array();
		$pre_data = array();
		$args     = array(
			'status' => XLWCTY_Core()->data->get_option( 'allowed_order_statuses' ),
			'limit'  => 10,
		);

		$orders = wc_get_orders( $args );

		if ( is_array( $orders ) && count( $orders ) > 0 ) {
			foreach ( $orders as $order ) {

				$order_status = wc_get_order_status_name( $order->get_status() );

				$label                                                = '#' . XLWCTY_Compatibility::get_order_id( $order ) . ' (' . $order_status . ') ' . XLWCTY_Compatibility::get_order_data( $order, 'billing_email' ) . '';
				$data[ XLWCTY_Compatibility::get_order_id( $order ) ] = $label;

				if ( $is_pre_data ) {
					$pre_data[] = array(
						'text'  => $label,
						'value' => XLWCTY_Compatibility::get_order_id( $order ),
					);
				}
			}
		}

		if ( $is_pre_data ) {
			return $pre_data;
		}

		return $data;
	}


	/**
	 * Get max menu order from the existing thank you pages and provide to val()+1 to the cmb2 as default menu _order
	 *
	 * @param $meta
	 *
	 * @return null|string
	 */
	public static function escape_cb_to_consider_default( $meta ) {

		if ( '' === $meta ) {
			global $wpdb;

			$count = $wpdb->get_var( $wpdb->prepare( "SELECT max(`menu_order`) FROM $wpdb->posts WHERE post_type = %s", XLWCTY_Common::get_thank_you_page_post_type_slug() ) );

			return ( is_numeric( $count ) ) ? ( $count + 1 ) : 0;
		}

		return $meta;
	}

}
