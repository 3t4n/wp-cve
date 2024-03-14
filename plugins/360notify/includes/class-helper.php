<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WooNotify_360Messenger_Helper {

	private static $_instance = false;
	private static $all_options = [];

	public static function init() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function Options( $option, $section = '', $default = '' ) {

		if ( ! empty( $section ) && ( ! is_string( $section ) || stripos( $section, '_settings' ) === false ) ) {

			if ( $section == '__' ) {
				$skip = true;
			} else {
				$default = $section;
			}

			unset( $section );
		}

		if ( ! empty( $section ) ) {
			$options = get_option( $section );
		} else {

			if ( empty( self::$all_options ) ) {

				$sections = WooNotify_360Messenger_Settings::settingSections();
				$sections = wp_list_pluck( $sections, 'id' );

				$options = [];
				foreach ( $sections as $section ) {
					$section = get_option( $section );
					if ( ! empty( $section ) ) {
						$options = array_merge( $options, $section );
					}
				}

				self::$all_options = $options;
			}

			$options = self::$all_options;
		}

		$option = isset( $options[ $option ] ) ? $options[ $option ] : $default;

		if ( empty( $skip ) && ! empty( $option ) && is_string( $option ) ) {
			$option = $this->maybeBool( $option );
		}


		return $option;
	}

	public function modifyStatus( $status ) {
		return str_ireplace( [ 'wc-', 'wc_' ], '', $status );
	}

	public function get_variation( $order_id ) {
		// Get an instance of the WC_Order object from an Order ID
		$order = wc_get_order( $order_id );

		// Loop though order "line items"
		foreach ( $order->get_items() as $item_id => $item ) {
			$product_id   = $item->get_product_id(); //Get the product ID
			$quantity     = $item->get_quantity(); //Get the product QTY
			$product_name = $item->get_name(); //Get the product NAME

			// Get an instance of the WC_Product object (can be a product variation  too)
			$product = $item->get_product();

			// Get the product description (works for product variation too)
			$description = $product->get_description();

			// Only for product variation
			if ( $product->is_type( 'variation' ) ) {
				// Get the variation attributes
				$variation_attributes = $product->get_variation_attributes();
				// Loop through each selected attributes
				foreach ( $variation_attributes as $attribute_taxonomy => $term_slug ) {
					// Get product attribute name or taxonomy
					$taxonomy = str_replace( 'attribute_', '', $attribute_taxonomy );
					// The label name from the product attribute
					$attribute_name = wc_attribute_label( $taxonomy, $product );
					// The term name (or value) from this attribute
					if ( taxonomy_exists( $taxonomy ) ) {
						$attribute_value = get_term_by( 'slug', $term_slug, $taxonomy )->name;
					} else {
						$attribute_value = $term_slug; // For custom product attributes
					}
				}
			}
		}

		return $attribute_name . '-' . $attribute_value;

	}

	public function statusName( $status, $pending = false ) {

		$status = wc_get_order_status_name( $status );

		$pending_label = _x( 'Pending payment', 'Order status', 'woocommerce' );
		if ( get_locale() == 'fa_IR' ) {
			$status = $pending ? ( $pending_label ) : ( $pending_label ) . ( ' (بلافاصله بعد از ثبت سفارش)' );
		} else {
			$status = $pending ? ( $pending_label ) : ( $pending_label ) . ( ' (immediately after placing the order)' );
		}

		return $status;
	}

	public function GetAllStatuses( $pending = false ) {

		if ( ! function_exists( 'wc_get_order_statuses' ) ) {
			return [];
		}

		$statuses = wc_get_order_statuses();

		$pending_label = _x( 'Pending payment', 'Order status', 'woocommerce' );
		if ( ! empty( $statuses['wc-pending'] ) ) {
			if ( get_locale() == 'fa_IR' ) {
				$statuses['wc-pending'] = $pending ? ( $pending_label ) : ( $pending_label ) . ( ' (بعد از تغییر وضعیت سفارش)' );
			} else {
				$status['wc-pending'] = $pending ? ( $pending_label ) : ( $pending_label ) . ( ' (after changing the status of the order)' );
			}
		}
		if ( empty( $statuses['wc-created'] ) ) {
			if ( get_locale() == 'fa_IR' ) {
				$statuses = array_merge( [ 'wc-created' => $pending ? ( 'بعد از ثبت سفارش' ) : ( $pending_label ) . ( ' (بلافاصله بعد از ثبت سفارش)' ) ], $statuses );
			} else {
				$statuses = array_merge( [ 'wc-created' => $pending ? ( 'After placing the order' ) : ( $pending_label ) . ( ' (Immediately after placing the order)' ) ], $statuses );
			}
		}

		$opt_statuses = [];
		foreach ( (array) $statuses as $status_val => $status_name ) {
			$opt_statuses[ $this->modifyStatus( $status_val ) ] = $status_name;
		}

		return $opt_statuses;
	}

	public function GetAllSuperAdminStatuses( $pending = false ) {
		$opt_statuses = $this->GetAllStatuses( $pending );
		if ( get_locale() == 'fa_IR' ) {
			$opt_statuses['low'] = ( 'کم بودن موجودی انبار' );
			$opt_statuses['out'] = ( 'تمام شدن موجودی انبار' );
		} else {
			$opt_statuses['low'] = ( 'low inventory' );
			$opt_statuses['out'] = ( 'Out of stock' );
		}

		return $opt_statuses;
	}

	public function GetAllProductAdminStatuses( $pending = false ) {

		$opt_statuses = $this->GetAllSuperAdminStatuses( $pending );

		return $opt_statuses;
	}

	public function prepareAdminProductStatus( $statuses, $array = true ) {

		$delimator = '-sv-';

		if ( ! is_array( $statuses ) ) {
			$statuses = explode( $delimator, $statuses );
		}

		$statuses = array_map( 'trim', $statuses );
		$statuses = array_map( [ $this, 'sanitize_text_field' ], $statuses );
		$statuses = array_unique( array_filter( $statuses ) );

		sort( $statuses );

		if ( $array ) {
			return $statuses;
		}

		return implode( $delimator, $statuses );
	}

	public function GetBuyerAllowedStatuses( $pending = false ) {

		$statuses              = $this->GetAllStatuses( $pending );
		$order_status_settings = (array) $this->Options( 'order_status', [] );

		$allowed_statuses = [];
		foreach ( (array) $statuses as $status_val => $status_name ) {
			if ( in_array( $status_val, array_keys( $order_status_settings ) ) ) {
				$allowed_statuses[ $status_val ] = $status_name;
			}
		}

		return $allowed_statuses;
	}

	public function MaybeVariableProductTitle( $product ) {

		$product_id = $this->ProductId( $product );

		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		$attributes = $this->ProductProp( $product, 'variation_attributes' );
		$parent_id  = $this->ProductProp( $product, 'parent_id' );

		if ( ! empty( $attributes ) && ! empty( $parent_id ) ) {

			$parent = wc_get_product( $parent_id );

			$variation_attributes = $this->ProductProp( $parent, 'variation_attributes' );

			$variable_title = [];
			foreach ( (array) $attributes as $attribute_name => $options ) {

				$attribute_name = str_ireplace( 'attribute_', '', $attribute_name );

				foreach ( (array) $variation_attributes as $key => $value ) {
					$key = str_ireplace( 'attribute_', '', $key );

					if ( sanitize_title( $key ) == sanitize_title( $attribute_name ) ) {
						$attribute_name = $key;
						break;
					}
				}

				//if ( ! empty( $options ) && substr( strtolower( $attribute_name ), 0, 3 ) !== 'pa_' ) {
				if ( ! empty( $options ) ) {
					if ( substr( strtolower( $attribute_name ), 0, 3 ) == 'pa_' ) {
						$str            = [ 'pa_', '-' ];
						$rplc           = [ '', ' ' ];
						$attribute_name = str_replace( $str, $rplc, $attribute_name );
						$options        = str_replace( "-", "", $options );
					}
					$variable_title[] = $attribute_name . ':' . $options;
				}
			}

			$product_title = get_the_title( $parent_id );

			if ( ! empty( $variable_title ) ) {
				$product_title .= ' (' . implode( ' - ', $variable_title ) . ')';
			}
		} else {
			$product_title = get_the_title( $product_id );
		}

		return html_entity_decode( urldecode( $product_title ) );
	}

	public function MayBeVariable( $product ) {

		$product_id = $this->ProductId( $product );
		$product    = wc_get_product( $product_id );
		if ( $product->is_type( 'variable' ) ) {

			unset( $product_id );

			$product_ids = [];
			foreach ( (array) $this->ProductProp( $product, 'children' ) as $product_id ) {
				//$product_ids[] = wc_get_product( $product_id );
				$product_ids[] = $this->sanitize_text_field( $product_id );
			}

			return $product_ids;//array
		} else {
			return $product_id;//int
		}
	}

	public function ProductHasProp( $product, $prop ) {

		$check = true;

		$product_ids = (array) $this->MayBeVariable( $product );
		foreach ( $product_ids as $product_id ) {

			$product = wc_get_product( $product_id );

			if ( $prop == 'is_not_low_stock' ) {

				if ( $check = ( WooNotify()->IsStockManaging( $product ) && $product->is_in_stock() && $this->ProductStockQty( $product_id ) > get_option( 'woocommerce_notify_low_stock_amount' ) ) ) {
					break;
				}

			} elseif ( method_exists( $product, $prop ) ) {
				$check = $check && $product->$prop();
			} else {
				$check = false;
			}
		}

		return $check;
	}


	public function ProductSalePriceTime( $product, $type = '' ) {

		if ( is_numeric( $product ) ) {
			$product_id = $product;
			$product    = wc_get_product( $product_id );
		} else {
			$product_id = $this->ProductId( $product );
		}

		$timestamp = '';
		$method    = 'get_date_on_sale_' . $type;
		if ( method_exists( $product, $method ) ) {
			$timestamp = $product->$method();
			if ( is_object( $timestamp ) && method_exists( $timestamp, 'getOffsetTimestamp' ) ) {
				$timestamp = $timestamp->getOffsetTimestamp();
			}
		}
		if ( empty( $timestamp ) ) {
			$timestamp = get_post_meta( $product_id, '_sale_price_dates_' . $type, true );
		}

		return $timestamp;
	}


	public static function multiSelectAndCheckbox( $field, $key, $args, $value ) {

		$after = ! empty( $args['clear'] ) ? '<div class="clear"></div>' : '';

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required        = ' <abbr class="required" title="' . esc_attr(__( 'required', 'woocommerce' )) . '">*</abbr>';
		} else {
			$required = '';
		}

		$custom_attributes = [];
		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
			foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = ( $attribute ) . '="' . ( $attribute_value ) . '"';
			}
		}

		if ( $args['type'] == "WooNotify_multiselect" ) {
			$value = is_array( $value ) ? $value : [ $value ];
			if ( ! empty( $args['options'] ) ) {
				$options = '';
				foreach ( $args['options'] as $option_key => $option_text ) {
					$options .= '<option value="' . ( ( $option_key ) ) . '" ' . selected( in_array( $option_key, $value ), 1, false ) . '>' . ( ( $option_text ) ) . '</option>';
				}
				$field = '<p class="form-row ' . ( implode( ' ', $args['class'] ) ) . '" id="' . ( ( $key ) ) . '_field">';
				if ( $args['label'] ) {
					$field .= '<label for="' . ( ( $key ) ) . '" class="' . implode( ' ', $args['label_class'] ) . '">' . $args['label'] . ( $required ) . '</label>';
				}
				$field .= '<select name="' . ( ( $key ) ) . '[]" id="' . ( ( $key ) ) . '" class="select" multiple="multiple" ' . implode( ' ', $custom_attributes ) . '>'
				          . ( $options )
				          . ' </select>';

				if ( $args['description'] ) {
					$field .= '<span class="description">' . ( $args['description'] ) . '</span>';
				}

				$field .= '</p>' . $after;
			}
		}

		if ( $args['type'] == "WooNotify_multicheckbox" ) {
			$value = is_array( $value ) ? $value : [ $value ];
			if ( ! empty( $args['options'] ) ) {
				$field .= '<p class="form-row ' . ( implode( ' ', $args['class'] ) ) . '" id="' . ( $key ) . '_field">';
				if ( $args['label'] ) {
					$field .= '<label for="' . ( current( array_keys( $args['options'] ) ) ) . '" class="' . implode( ' ', $args['label_class'] ) . '">' . $args['label'] . ( $required ) . '</label>';
				}
				foreach ( $args['options'] as $option_key => $option_text ) {
					$field .= '<input type="checkbox" class="input-checkbox" value="' . ( ( $option_key ) ) . '" name="' . ( ( $key ) ) . '[]" id="' . ( ( $key ) ) . '_' . ( ( $option_key ) ) . '"' . checked( in_array( $option_key, $value ), 1, false ) . ' />';
					$field .= '<label for="' . ( ( $key ) ) . '_' . ( ( $option_key ) ) . '" class="checkbox ' . implode( ' ', $args['label_class'] ) . '">' . ( $option_text ) . '</label><br>';
				}
				if ( $args['description'] ) {
					$field .= '<span class="description">' . ( $args['description'] ) . '</span>';
				}
				$field .= '</p>' . $after;
			}
		}

		return $field;
	}


	public function multiSelectAdminField( $field ) {

		if ( ! isset( $field['placeholder'] ) ) {
			$field['placeholder'] = '';
		}
		if ( ! isset( $field['class'] ) ) {
			$field['class'] = 'short';
		}
		if ( ! isset( $field['options'] ) ) {
			$field['options'] = [];
		}

		if ( ! empty( $field['value'] ) ) {
			$field['value'] = array_filter( (array) $field['value'] );
		}
		//dont use else
		if ( empty( $field['value'] ) ) {
			$field['value'] = isset( $field['default'] ) ? $field['default'] : [];
		}

		$field['value']   = (array) $field['value'];
		$field['options'] = (array) $field['options'];

		echo '<p class="form-field ' . ( esc_attr($field['id']) ) . '_field"><label style="display:block;" for="' . ( esc_attr($field['id'])  ) . '">' . ( esc_attr($field['label']) ) . '</label>';
		echo '<select multiple="multiple" class="' . ( esc_attr($field['class']) ) . '" name="' . ( esc_attr($field['id'])  ) . '[]" id="' . ( esc_attr($field['id'])  ) . '" ' . '>';

		foreach ( $field['options'] as $status_value => $status_name ) {
			echo '<option value="' . ( esc_attr($status_value) ) . '"' . selected( esc_attr(in_array( $status_value, ( $field['value'] ) ), true, false )) . '>' . ( ($status_name) ) . '</option>';
		}

		echo '</select>';
		echo '</p>';
	}

	public function Meta_Saved_Mobile( $meta, $post_id = 0, $empty_array = [] ) {

		if ( empty( $post_id ) ) {
			global $post;
			$post_id = is_object( $post ) && ! empty( $post->ID ) ? $post->ID : 0;
		}
		if ( empty( $post_id ) ) {
			return $empty_array;
		}

		$data = get_post_meta( $post_id, '_WooNotify_product_admin_meta_' . $meta, true );
		if ( ! empty( $data ) ) {
			return (array) $data;//mobile and statuses that set via admin
		}

		return $post_id;
	}

	public function User_Meta_Mobile( $post_id = 0 ) {

		$meta        = 'user';
		$empty_array = [ 'meta' => $meta, 'mobile' => '', 'statuses' => '' ];
		$data        = $this->Meta_Saved_Mobile( $meta, $post_id, $empty_array );
		if ( is_array( $data ) ) {
			return $data;
		}

		$meta_key = $this->Options( "product_admin_{$meta}_meta" );
		if ( empty( $meta_key ) ) {
			unset( $empty_array['meta'] );

			return $empty_array;
		}

		$post_id = absint( $data );
		$post    = get_post( $post_id );

		if ( empty( $post->post_author ) ) {
			return $empty_array;
		}

		return [
			'meta'     => $meta,
			'mobile'   => get_user_meta( $post->post_author, $meta_key, true ),
			'statuses' => $this->Options( 'product_admin_meta_order_status' ),
		];
	}

	public function Post_Meta_Mobile( $post_id = 0 ) {

		$meta        = 'post';
		$empty_array = [ 'meta' => $meta, 'mobile' => '', 'statuses' => '' ];
		$data        = $this->Meta_Saved_Mobile( $meta, $post_id, $empty_array );
		if ( is_array( $data ) ) {
			return $data;
		}

		$meta_key = $this->Options( "product_admin_{$meta}_meta" );
		if ( empty( $meta_key ) ) {
			unset( $empty_array['meta'] );

			return $empty_array;
		}

		$post_id = absint( $data );

		return [
			'meta'     => $meta,
			'mobile'   => get_post_meta( $post_id, $meta_key, true ),
			'statuses' => $this->Options( 'product_admin_meta_order_status' ),
		];
	}

	public function mayBeJalaliDate( $date_time ) {

		if ( empty( $date_time ) ) {
			return '';
		}
		$date_time = $this->EnglishNumberMobile( $date_time );
		$_date_time = explode( ' ', $date_time );
		$date       = ! empty( $_date_time[0] ) ? explode( '-', $_date_time[0], 3 ) : '';
		$time       = ! empty( $_date_time[1] ) ? $_date_time[1] : '';

		if ( count( $date ) != 3 || $date[0] < 2000 ) {
			return $date_time;
		}

		[ $year, $month, $day ] = $date;

		$date = $this->JalaliDate( $year, $month, $day, '/' ) . ' - ' . $time;

		return trim( trim( $date ), '- ' );
	}

	//از سایت jdf
	public function JalaliDate( int $g_y, int $g_m, int $g_d, $mod = '' ) {
		$d_4   = $g_y % 4;
		$g_a   = [ 0, 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334 ];
		$doy_g = $g_a[ (int) $g_m ] + $g_d;
		if ( $d_4 == 0 and $g_m > 2 ) {
			$doy_g ++;
		}
		$d_33 = (int) ( ( ( $g_y - 16 ) % 132 ) * .0305 );
		$a    = ( $d_33 == 3 or $d_33 < ( $d_4 - 1 ) or $d_4 == 0 ) ? 286 : 287;
		$b    = ( ( $d_33 == 1 or $d_33 == 2 ) and ( $d_33 == $d_4 or $d_4 == 1 ) ) ? 78 : ( ( $d_33 == 3 and $d_4 == 0 ) ? 80 : 79 );
		if ( (int) ( ( $g_y - 10 ) / 63 ) == 30 ) {
			$a --;
			$b ++;
		}
		if ( $doy_g > $b ) {
			$jy    = $g_y - 621;
			$doy_j = $doy_g - $b;
		} else {
			$jy    = $g_y - 622;
			$doy_j = $doy_g + $a;
		}
		if ( $doy_j < 187 ) {
			$jm = (int) ( ( $doy_j - 1 ) / 31 );
			$jd = $doy_j - ( 31 * $jm ++ );
		} else {
			$jm = (int) ( ( $doy_j - 187 ) / 30 );
			$jd = $doy_j - 186 - ( $jm * 30 );
			$jm += 7;
		}

		$jd = $jd > 9 ? $jd : '0' . $jd;
		$jm = $jm > 9 ? $jm : '0' . $jm;

		return ( $mod == '' ) ? [ $jy, $jm, $jd ] : $jy . $mod . $jm . $mod . $jd;
	}

 public function ReplaceShortCodes( $content, $order_status, WC_Order $order, $vendor_items_array = [] ) {
	$price = strip_tags( $this->OrderProp( $order, 'formatted_order_total', [ '', false ] ) );
	$price = html_entity_decode( $price );

		$all_product_list = $this->AllItems( $order );
		$all_product_ids  = ! empty( $all_product_list['product_ids'] ) ? $this->sanitize_text_field( $all_product_list['product_ids'] ) : [];
		$all_items        = ! empty( $all_product_list['items'] ) ? $this->sanitize_text_field( $all_product_list['items'] ) : [];
		$all_items_qty    = ! empty( $all_product_list['items_qty'] ) ? $this->sanitize_text_field( $all_product_list['items_qty'] ) : [];
		$vendor_product_ids = ! empty( $vendor_items_array['product_ids'] ) ? $this->sanitize_text_field( $vendor_items_array['product_ids'] ) : [];
		$vendor_items       = ! empty( $vendor_items_array['items'] ) ? $this->sanitize_text_field( $vendor_items_array['items'] ) : [];
		$vendor_items_qty   = ! empty( $vendor_items_array['items_qty'] ) ? $this->sanitize_text_field( $vendor_items_array['items_qty'] ) : [];
		$vendor_price       = ! empty( $vendor_items_array['price'] ) ? array_sum( (array) $this->sanitize_text_field( $vendor_items_array['price'] ) ) : 0;
		$vendor_price       = strip_tags( wc_price( $vendor_price ) );

		$payment_gateways = [];
		if ( WC()->payment_gateways() ) {
			$payment_gateways = WC()->payment_gateways->payment_gateways();
		}

		$payment_method  = $this->OrderProp( $order, 'payment_method' );
		$payment_method  = ( isset( $payment_gateways[ $payment_method ] ) ? ( $payment_gateways[ $payment_method ]->get_title() ) : ( $payment_method ) );
		$shipping_method = ( $this->OrderProp( $order, 'shipping_method' ) );

		$country = WC()->countries;

		$bill_country = ( isset( $country->countries[ $this->OrderProp( $order, 'billing_country' ) ] ) ) ? $country->countries[ $this->OrderProp( $order, 'billing_country' ) ] : $this->OrderProp( $order, 'billing_country' );
		$bill_state   = ( $this->OrderProp( $order, 'billing_country' ) && $this->OrderProp( $order, 'billing_state' ) && isset( $country->states[ $this->OrderProp( $order, 'billing_country' ) ][ $this->OrderProp( $order, 'billing_state' ) ] ) ) ? $country->states[ $this->OrderProp( $order, 'billing_country' ) ][ $this->OrderProp( $order, 'billing_state' ) ] : $this->OrderProp( $order, 'billing_state' );

$ship_country = ( isset( $country->countries[ $this->OrderProp( $order, 'shipping_country' ) ] ) ) ? $country->countries[ $this->OrderProp( $order, 'shipping_country' ) ] : $this->OrderProp( $order, 'shipping_country' );
	$ship_state   = ( $this->OrderProp( $order, 'shipping_country' ) && $this->OrderProp( $order, 'shipping_state' ) && isset( $country->states[ $this->OrderProp( $order, 'shipping_country' ) ][ $this->OrderProp( $order, 'shipping_state' ) ] ) ) ? $country->states[ $this->OrderProp( $order, 'shipping_country' ) ][ $this->OrderProp( $order, 'shipping_state' ) ] : $this->OrderProp( $order, 'shipping_state' );

		$tags = [
			'{b_first_name}'  => $this->OrderProp( $order, 'billing_first_name' ),
			'{b_last_name}'   => $this->OrderProp( $order, 'billing_last_name' ),
			'{b_company}'     => $this->OrderProp( $order, 'billing_company' ),
			'{b_address_1}'   => $this->OrderProp( $order, 'billing_address_1' ),
			'{b_address_2}'   => $this->OrderProp( $order, 'billing_address_2' ),
			'{b_state}'       => $bill_state,
			'{b_city}'        => $this->OrderProp( $order, 'billing_city' ),
			'{b_postcode}'    => $this->OrderProp( $order, 'billing_postcode' ),
			'{b_country}'     => $bill_country,
			'{sh_first_name}' => $this->OrderProp( $order, 'shipping_first_name' ),
			'{sh_last_name}'  => $this->OrderProp( $order, 'shipping_last_name' ),
			'{sh_company}'    => $this->OrderProp( $order, 'shipping_company' ),
			'{sh_address_1}'  => $this->OrderProp( $order, 'shipping_address_1' ),
			'{sh_address_2}'  => $this->OrderProp( $order, 'shipping_address_2' ),
			'{sh_state}'      => $ship_state,
			'{sh_city}'       => $this->OrderProp( $order, 'shipping_city' ),
			'{sh_postcode}'   => $this->OrderProp( $order, 'shipping_postcode' ),
			'{sh_country}'    => $ship_country,
			'{phone}'         => $this->buyerMobile( $order->get_id() ),
			'{mobile}'        => $this->buyerMobile( $order->get_id() ),
			'{email}'         => $this->OrderProp( $order, 'billing_email' ),
			'{order_id}'      => $this->OrderProp( $order, 'order_number' ),
			'{date}'          => $this->OrderDate( $order ),
			'{post_id}'       => $order->get_id(),
			'{status}'        => $this->statusName( $order_status, true ),
			'{price}'         => $price,

			'{total_discount}' => strip_tags( wc_price( $this->OrderProp( $order, 'total_discount' ) ) ),
			'{total_tax}'      => strip_tags( wc_price( $this->OrderProp( $order, 'total_tax' ) ) ),
			'{subtotal}'       => strip_tags( wc_price( $this->OrderProp( $order, 'subtotal' ) ) ),

			'{all_items}'     => implode( ' - ', $all_items ),
			'{all_items_qty}' => implode( ' - ', $all_items_qty ),
			'{count_items}'   => count( $all_items ),

			'{vendor_items}'       => implode( ' - ', $vendor_items ),
			'{vendor_items_qty}'   => implode( ' - ', $vendor_items_qty ),
			'{count_vendor_items}' => count( $vendor_items ),
			'{vendor_price}'       => $vendor_price,

			'{transaction_id}'  => $order->get_meta( '_transaction_id' ),
			'{payment_method}'  => $payment_method,
			'{shipping_method}' => $shipping_method,
			'{description}'     => nl2br( esc_html( $order->get_customer_note() ) ),
		];


		$content = apply_filters( 'WooNotify_order_360Messenger_body_before_replace', $content, array_keys( $tags ), array_values( $tags ), $order->get_id(), $order, $all_product_ids, $vendor_product_ids );

		$content = str_ireplace( array_keys( $tags ), array_values( $tags ), $content );
		$content = str_ireplace( [ '<br>', '<br/>', '<br />', '&nbsp;' ], [ '', '', '', ' ' ], $content );

		$content = apply_filters( 'WooNotify_order_360Messenger_body_after_replace', $content, $order->get_id(), $order, $all_product_ids, $vendor_product_ids );

		return $content;
	}

	public function buyerMobileMeta() {
		return apply_filters( 'WooNotify_mobile_meta', 'billing_phone' );
	}

	public function buyerMobile( $order_id ) {
		        $order = wc_get_order( $order_id );
 		
 		        $meta = $this->buyerMobileMeta();
 		
 		        if ( is_callable( [ $order, 'get_' . $meta ] ) ) {
 		            $buyer_mobile = $order->{'get_' . $meta}();
 		        } else {
 		            $buyer_mobile = $order->get_meta( '_' . $meta );
 		        }
 		
 		        return apply_filters( 'WooNotify_order_buyer_mobile', $buyer_mobile, $order_id, $order );
	}


	public function buyerCountry( $order_id ) {
		        $order = wc_get_order( $order_id );
				if($order){
					$buyer_country = $order->get_shipping_country();
				}
 		
 		        return apply_filters( 'WooNotify_order_buyer_country', $buyer_country, $order_id, $order );
	}
	public function validateMobile( $mobile ) {

		$mobile = $this->modifyMobile( $mobile );

		return $mobile;
		//return preg_match( '/9\d{9,}?$/', trim( $mobile ) );
		//return is_numeric( $mobile );
	}

	public function modifyMobile( $mobile ) {

		if ( is_array( $mobile ) ) {
			return array_map( [ $this, __FUNCTION__ ], $mobile );
		}

		$mobile = $this->EnglishNumberMobile( $mobile );

		$modified = preg_replace( '/\D/is', '', (string) $mobile );

		if ( substr( $mobile, 0, 1 ) == '+' ) {
			return $modified;
		} elseif ( substr( $modified, 0, 2 ) == '00' ) {
			return substr( $modified, 2 );
		} elseif ( substr( $modified, 0, 1 ) == '0' ) {
			return $modified;
		} elseif ( ! empty( $modified ) ) {
			//$modified = '0' . $modified;
		}

		return $modified;
	}

	public function EnglishNumberMobile( $mobile ) {
		if ( is_array( $mobile ) ) {
			return array_map( [ $this, __FUNCTION__ ], $mobile );
		} else {

			$mobile = esc_attr( sanitize_text_field( $mobile ) );

			$mobile = str_ireplace( [ '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ],
				['0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ], $mobile ); //farsi
			$mobile = str_ireplace( [ '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' ],
				[ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ], $mobile ); //arabi

			return $mobile;
		}
	}

	public function sanitize_text_field( $post ) {
		if ( is_array( $post ) ) {
			return array_map( [ $this, __FUNCTION__ ], $post );
		}

		return sanitize_text_field( $post );
	}

	public function hasNotifCond( $key, $product_id ) {
		return $this->Options( 'enable_notif_360Messenger_main' ) && $this->maybeBool( $this->getValue( $key, $product_id ) );
	}

	public function getValue( $key, $product_id ) {

		//مقدار واقعی

		$key = ltrim( $key, '_' );

		$woonotify_360messenger_set = get_post_meta( $product_id, '_is_360Messenger_set', true );

		if ( ( is_string( $woonotify_360messenger_set ) && $this->maybeBool( $woonotify_360messenger_set ) ) || ( is_array( $woonotify_360messenger_set ) && in_array( $key, $woonotify_360messenger_set ) ) ) {
			return get_post_meta( $product_id, '_' . $key, true );
		}

		return $this->Options( $key, '__' );
	}

	public function maybeBool( $value ) {

		if ( empty( $value ) ) {
			return false;
		}

		if ( is_string( $value ) ) {

			if ( in_array( $value, [ 'on', 'true', 'yes' ] ) ) {
				return true;
			}

			if ( in_array( $value, [ 'off', 'false', 'no' ] ) ) {
				return false;
			}
		}

		return $value;
	}

	public function ReplaceTags( $key, $product_id, $parent_product_id ) {

		$sale_price_dates_from = ( $date = $this->ProductSalePriceTime( $product_id, 'from' ) ) ? date_i18n( 'Y-m-d', $date ) : '';
		$sale_price_dates_to   = ( $date = $this->ProductSalePriceTime( $product_id, 'to' ) ) ? date_i18n( 'Y-m-d', $date ) : '';

		$product = wc_get_product( $product_id );

		$sku = $this->ProductProp( $product, 'sku' );
		if ( empty( $sku ) ) {
			$sku = $this->ProductProp( $parent_product_id, 'sku' );
		}
		if ( get_locale() == 'fa_IR' ) {
			$tags = [
				'{product_id}'    => $parent_product_id,
				'{sku}'           => $sku,
				'{product_title}' => $this->MaybeVariableProductTitle( $product ),
				'{regular_price}' => strip_tags( wc_price( $this->ProductProp( $product, 'regular_price' ) ) ),
				'{onsale_price}'  => strip_tags( wc_price( $this->ProductProp( $product, 'sale_price' ) ) ),
				'{onsale_from}'   => $this->mayBeJalaliDate( $sale_price_dates_from ),
				'{onsale_to}'     => $this->mayBeJalaliDate( $sale_price_dates_to ),
				'{stock}'         => $this->ProductStockQty( $product ),
			];
		}else{
			$tags = [
				'{product_id}'    => $parent_product_id,
				'{sku}'           => $sku,
				'{product_title}' => $this->MaybeVariableProductTitle( $product ),
				'{regular_price}' => strip_tags( wc_price( $this->ProductProp( $product, 'regular_price' ) ) ),
				'{onsale_price}'  => strip_tags( wc_price( $this->ProductProp( $product, 'sale_price' ) ) ),
				'{onsale_from}'   => $sale_price_dates_from ,
				'{onsale_to}'     => $sale_price_dates_to,
				'{stock}'         => $this->ProductStockQty( $product ),
			];			
		}

		$content = $this->getValue( $key, $parent_product_id );

		return str_replace( [ '<br>', '<br>', '<br />', '&nbsp;' ],
			[ '', '', '', ' ' ],
			str_replace( array_keys( $tags ), array_values( $tags ), $content ) );
	}


	public function ProductId( $product = '' ) {

		if ( empty( $product ) ) {
			$product_id = get_the_ID();
		} else if ( is_numeric( $product ) ) {
			$product_id = $product;
		} else if ( is_object( $product ) ) {
			$product_id = $this->ProductProp( $product, 'id' );
		} else {
			$product_id = false;
		}

		return $product_id;
	}

	public function ProductProp( $product, $prop ) {
		$method = 'get_' . $prop;

		return method_exists( $product, $method ) ? $product->$method() : ( ! empty( $product->{$prop} ) ? $product->{$prop} : '' );
	}

	public function IsStockManaging( $product ) {

		if ( 'yes' !== get_option( 'woocommerce_manage_stock' ) ) {
			return false;
		}

		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		if ( method_exists( $product, 'get_manage_stock' ) ) {
			$manage = $product->get_manage_stock();
		} elseif ( method_exists( $product, 'managing_stock' ) ) {
			$manage = $product->managing_stock();
		} else {
			$manage = true;
		}

		if ( strtolower( $manage ) == 'parent' ) {
			$manage = false;
		}

		return $manage;
	}

	public function ProductStockQty( $product ) {

		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		if ( method_exists( $product, 'get_stock_quantity' ) ) {
			$quantity = $product->get_stock_quantity();
		} else {
			$quantity = $this->ProductProp( $product, 'total_stock' );
		}

		if ( empty( $quantity ) ) {
			$quantity = ( (int) get_post_meta( $this->ProductId( $product ), '_stock', true ) );
		}

		return ! empty( $quantity ) ? $quantity : 0;
	}

	public function ProductAdminMobiles( $product_ids, $status = '' ) {

		$product_ids = array_unique( (array) $product_ids );

		$mobiles = [];
		foreach ( $product_ids as $product_id ) {

			$product_admin   = (array) get_post_meta( $product_id, '_WooNotify_product_admin_data', true );
			$product_admin[] = $this->User_Meta_Mobile( $product_id );
			$product_admin[] = $this->Post_Meta_Mobile( $product_id );
			$product_admin   = array_filter( $product_admin );

			foreach ( (array) $product_admin as $data ) {

				if ( ! empty( $data['mobile'] ) && ! empty( $data['statuses'] ) && $this->validateMobile( $data['mobile'] ) ) {

					$statuses = $this->prepareAdminProductStatus( $data['statuses'] );

					if ( empty( $status ) || in_array( $status, $statuses ) ) {
						$_mobiles = array_map( 'trim', explode( ',', $data['mobile'] ) );
						foreach ( $_mobiles as $_mobile ) {
							$mobiles[ $_mobile ][] = $product_id;
						}
					}
				}
			}
		}

		return $mobiles;
	}

	public function GetProdcutLists( $order, $field = '' ) {

		$products = [];
		$fields   = [];

		foreach ( (array) $this->OrderProp( $order, 'items' ) as $product ) {

			$parent_product_id = ! empty( $product['product_id'] ) ? $product['product_id'] : $this->ProductId( $product );
			$product_id        = $this->ProductProp( $product, 'variation_id' );
			$product_id        = ! empty( $product_id ) ? $product_id : $parent_product_id;

			$item = [
				'id'         => absint(sanitize_text_field($product_id)),
				'product_id' => absint(sanitize_text_field($parent_product_id)),
				'qty'        => ! empty( $product['qty'] ) ? absint(sanitize_text_field($product['qty'] )): 0,
				'total'      => ! empty( $product['total'] ) ? absint(sanitize_text_field($product['total'])) : 0,
			];

			if ( ! empty( $field ) && isset( $item[ $field ] ) ) {
				$fields[] = $item[ $field ];
			}

			$products[ $parent_product_id ][] = $item;
		}

		if ( ! empty( $field ) ) {
			$products[ $field ] = $fields;
		}

		return $products;
	}

	public function prepareItems( &$items, $item_data ) {

		if ( ! empty( $item_data['id'] ) ) {
			$title                = $this->MaybeVariableProductTitle( $item_data['id'] );
			$items['items'][]     = $title;
			$items['items_qty'][] = $title . ' (' . $item_data['qty'] . ')';
			$items['price'][]     = $item_data['total'];
		}
	}

	public function AllItems( $order ) {

		$order_products = $this->GetProdcutLists( $order );

		$items = [];
		foreach ( (array) $order_products as $item_datas ) {
			foreach ( (array) $item_datas as $item_data ) {
				$this->prepareItems( $items, $item_data );
			}
		}

		$items['product_ids'] = array_keys( $order_products );

		return $items;
	}

	public function ProductAdminItems( $order_products, $product_ids ) {

		$product_ids = array_unique( $product_ids );

		$items = [];
		foreach ( $product_ids as $product_id ) {
			$item_datas = $order_products[ $product_id ];
			foreach ( (array) $item_datas as $item_data ) {
				$this->prepareItems( $items, $item_data );
			}
		}

		$items['product_ids'] = $product_ids;

		return $items;
	}

	public function OrderProp( $order, $prop, $args = [] ) {
		$method = 'get_' . $prop;

		if ( method_exists( $order, $method ) ) {
			if ( empty( $args ) || ! is_array( $args ) ) {
				return $order->$method();
			} else {
				return call_user_func_array( [ $order, $method ], $args );
			}
		}

		return ! empty( $order->{$prop} ) ? $order->{$prop} : '';
	}

	public function OrderId( $order ) {
		return $this->OrderProp( $order, 'id' );
	}

	public function OrderDate( $order ) {

		$order_date = $this->OrderProp( $order, 'date_paid' );
		if ( empty( $order_date ) ) {
			$order_date = $this->OrderProp( $order, 'date_created' );
		}
		if ( empty( $order_date ) ) {
			$order_date = $this->OrderProp( $order, 'date_modified' );
		}
		if ( ! empty( $order_date ) ) {
			if ( method_exists( $order_date, 'getOffsetTimestamp' ) ) {
				$order_date = gmdate( 'Y-m-d H:i:s', $order_date->getOffsetTimestamp() );
			}
		} else {
			$order_date = date_i18n( 'Y-m-d H:i:s' );
		}
		if ( get_locale() == 'fa_IR' ) {
			return $this->mayBeJalaliDate( $order_date );
		}else{
			return $order_date;
		}
	}

	public function orderNoteMetaBox( WC_Order $order ) {

		if ( ! class_exists( 'WC_Meta_Box_Order_Notes' ) ) {
			return '';
		}

		if ( ! method_exists( 'WC_Meta_Box_Order_Notes', 'output' ) ) {
			return '';
		}

		ob_start();
		WC_Meta_Box_Order_Notes::output( $order );

		return ob_get_clean();
	}

	public function Send360Messenger( $data ) {

		$message = ! empty( $data['message'] ) ? esc_textarea( $data['message'] ) : '';

		$mobile = ! empty( $data['mobile'] ) ? $data['mobile'] : '';
		if ( ! is_array( $mobile ) ) {
			$mobile = explode( ',', $mobile );
		}

		$mobile = $this->modifyMobile( $mobile );

		$mobile = explode( ',', implode( ',', (array) $mobile ) );//حتما یه خیریتی داشته
		$mobile = array_map( 'trim', $mobile );
		$mobile = array_unique( array_filter( $mobile ) );

		$gateway_method = $this->Options( '360Messenger_gateway' );
		$gateway_method = $gateway_method == 'none' ? '' : $gateway_method;

		$gateway_object = WooNotify_360Messenger_Gateways::init();

		if ( empty( $mobile ) ) {
			if ( get_locale() == 'fa_IR' ) {
				$result = ('شماره واتساپ خالی است.');
			} else {
				$result = ('The whatsapp number is empty.');
			}
		} elseif ( empty( $message ) ) {
			if ( get_locale() == 'fa_IR' ) {
				$result = ('متن پیام خالی است.');
			} else {
				$result = ('The text of the message is empty.');
			}
		} elseif ( empty( $gateway_method ) ) {
			if ( get_locale() == 'fa_IR' ) {
				$result = ('تنظیمات وبسرویس پیام انجام نشده است.');
			} else {
				$result = ('Message web service settings are not done.');
			}

		} elseif ( ! method_exists( $gateway_object, $gateway_method ) ) {
			if ( get_locale() == 'fa_IR' ) {
				$result = ('تابع وبسرویس پیام واتساپ شما داخل کلاس وبسرویس های پیام وجود ندارد.');
			} else {
				$result = ('Your whatsapp message web service function does not exist in the message web services class.');
			}
		} else {

			try {

				$gateway_object->mobile  = $mobile;
				$gateway_object->message = $message;

				$result = $gateway_object->$gateway_method( $data );
			} catch ( Exception $e ) {
				$result = $e->getMessage();
			}
		}

		if ( $result !== true && ! is_string( $result ) ) {
			ob_start();
			var_dump( $result );
			$result = ob_get_clean();
		}

		if ( ! empty( $mobile ) && ! empty( $message ) ) {

			$gateways = WooNotify_360Messenger_Gateways::get_360Messenger_gateway();
			$sender   = ! empty( $gateway_method ) ? '(' . $gateway_object->senderNumber . ') ' . $gateways[ $gateway_method ] : '';

			WooNotify_360Messenger_Archive::insertRecord( [
				'post_id'  => ! empty( $data['post_id'] ) ? absint(sanitize_text_field($data['post_id'])) : '',
				'type'     => ! empty( $data['type'] ) ? absint(sanitize_text_field($data['type'])) : 0,
				'reciever' => implode( ',', (array) $mobile ),
				'message'  => strip_tags($message),
				'sender'   => strip_tags($sender),
				'result'   => $result === true ? '_ok_' : $result
			] );
		}

		return $result;
	}


}

function WooNotify() {
	global $WooNotify;

	return ( $WooNotify = WooNotify_360Messenger_Helper::init() );
}

function WooNotify_Shortcode( $get = false, $strip_brac = false ) {

	$shortcode = 'woo_ps_360Messenger';

	if ( $get ) {
		if ( $strip_brac ) {
			return $shortcode;
		}

		return "[$shortcode]";
	}
	//old coe
	//echo do_shortcode( "[$shortcode]" );
	//new

	echo wp_kses_post(do_shortcode( "[$shortcode]" ));


}