<?php

namespace QuadLayers\WOOCCM\View\Frontend;

/**
 * Fields_Disable Class
 */
class Fields_Disable {

	protected static $_instance;

	public function __construct() {
		// Fix country
		add_filter( 'wooccm_checkout_field_filter', array( $this, 'fix_country' ) );
		// Fix email
		// make sure guest users include their email in order to download products
		add_filter( 'wooccm_checkout_field_filter', array( $this, 'fix_email' ) );
		// Remove by product
		add_filter( 'wooccm_checkout_field_filter', array( $this, 'disable_by_product' ), 20 );
		// Remove by category
		add_filter( 'wooccm_checkout_field_filter', array( $this, 'disable_by_category' ), 30 );
		// Remove by role
		add_filter( 'wooccm_checkout_field_filter', array( $this, 'disable_by_role' ), 40 );
		// Remove by product type
		add_filter( 'wooccm_checkout_field_filter', array( $this, 'disable_by_product_type' ), 50 );
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function fix_country( $field ) {
		if ( 'country' == $field['type'] && true == $field['disabled'] ) {
			$field['disabled'] = false;
			$field['required'] = false;
			/**
			 * Fix:Preserve the select field to allow state selection based on default store country
			 * $field['type']     = 'hidden';
			 */
			$field['class'] = array( 'wooccm-type-hidden' );
		}

		return $field;
	}

	public function fix_email( $field ) {
		if ( 'email' == $field['type'] && ! is_user_logged_in() ) {
			$field['disabled'] = false;
			$field['required'] = true;
		}

		return $field;
	}

	public function disable_by_role( $field ) {
		global $current_user;

		$user_roles = ! empty( (array) $current_user->roles ) ? (array) $current_user->roles : array( 'customer' );

		if ( ! empty( $field['hide_role'] ) ) {

			if ( array_intersect( $user_roles, $field['hide_role'] ) ) {
				$field['disabled'] = true;
			} else {
				$field['disabled'] = false;
			}
		}

		if ( ! empty( $field['show_role'] ) ) {

			if ( ! array_intersect( $user_roles, $field['show_role'] ) ) {
				$field['disabled'] = true;
			} else {
				$field['disabled'] = false;
			}
		}

		return $field;
	}

	public function disable_by_product_type( $field ) {
		if ( empty( $field['disabled'] ) && ( ! empty( $field['hide_product_type'] ) || ! empty( $field['show_product_type'] ) ) ) {
			if ( is_object( WC()->cart ) ) {
				$cart_contents = WC()->cart->get_cart_contents();
				if ( count( $cart_contents ) ) {

					$hide_product_type_array = (array) $field['hide_product_type'];

					$show_product_type_array = (array) $field['show_product_type'];

					$apply_conditions_if_more_than_one_product = empty( $field['apply_conditions_if_more_than_one_product'] );

					$products_types = array();
					foreach ( $cart_contents as $key => $values ) {
						$product      = wc_get_product( $values['product_id'] );
						$product_type = $product->get_type();

						if ( $product_type && ! in_array( $product_type, $products_types ) ) {
							array_push( $products_types, $product_type );
						}
					}

					// field without more
					// -------------------------------------------------------------------
					if ( $apply_conditions_if_more_than_one_product && count( $cart_contents ) < 2 ) {
						// hide field
						// -----------------------------------------------------------------
						if ( count( $hide_product_type_array ) ) {
							if ( array_intersect( $products_types, $hide_product_type_array ) ) {
								$field['disabled'] = true;
							}
						}

						// show field
						// -----------------------------------------------------------------
						if ( count( $show_product_type_array ) ) {
							if ( ! array_intersect( $products_types, $show_product_type_array ) ) {
								$field['disabled'] = true;
							} else {
								$field['disabled'] = false;
							}
						}
					}

					// field with more
					// -------------------------------------------------------------------
					if ( ! $apply_conditions_if_more_than_one_product ) {

						// hide field
						// -------------------------------------------------------------
						if ( count( $hide_product_type_array ) ) {
							if ( array_intersect( $products_types, $hide_product_type_array ) ) {
								$field['disabled'] = true;
							}
						}

						// show field
						// ---------------------------------------------------------------
						if ( count( $show_product_type_array ) ) {

							if ( ! array_intersect( $products_types, $show_product_type_array ) ) {
								$field['disabled'] = true;
							} else {
								$field['disabled'] = false;
							}
						}
					}
				}
			}
		}

		return $field;
	}

	public function disable_by_category( $field ) {
		if ( empty( $field['disabled'] ) && ( ! empty( $field['hide_product_cat'] ) || ! empty( $field['show_product_cat'] ) ) ) {

			if ( is_object( WC()->cart ) ) {
				$cart_contents = WC()->cart->get_cart_contents();
				if ( count( $cart_contents ) ) {

					$hide_cats_array = (array) $field['hide_product_cat'];

					$show_cats_array = (array) $field['show_product_cat'];

					$apply_conditions_if_more_than_one_product = empty( $field['apply_conditions_if_more_than_one_product'] );

					$product_cats = array();

					foreach ( $cart_contents as $key => $values ) {
						$cats = wp_get_post_terms( $values['product_id'], 'product_cat', array( 'fields' => 'ids' ) );
						if ( $cats ) {
							$product_cats = array_merge( $product_cats, $cats );
						}
					}

					// field without more
					// -------------------------------------------------------------------
					if ( $apply_conditions_if_more_than_one_product && count( $cart_contents ) < 2 ) {
						// hide field
						// -----------------------------------------------------------------
						if ( count( $hide_cats_array ) ) {
							if ( array_intersect( $product_cats, $hide_cats_array ) ) {
								$field['disabled'] = true;
							}
						}

						// show field
						// -----------------------------------------------------------------
						if ( count( $show_cats_array ) ) {
							if ( ! array_intersect( $product_cats, $show_cats_array ) ) {
								$field['disabled'] = true;
							} else {
								$field['disabled'] = false;
							}
						}
					}

					// field with more
					// -------------------------------------------------------------------
					if ( ! $apply_conditions_if_more_than_one_product ) {

						// hide field
						// -------------------------------------------------------------
						if ( count( $hide_cats_array ) ) {
							if ( array_intersect( $product_cats, $hide_cats_array ) ) {
								$field['disabled'] = true;
							}
						}

						// show field
						// ---------------------------------------------------------------
						if ( count( $show_cats_array ) ) {

							if ( ! array_intersect( $product_cats, $show_cats_array ) ) {
								$field['disabled'] = true;
							} else {
								$field['disabled'] = false;
							}
						}
					}
				}
			}
		}

		return $field;
	}

	public function disable_by_product( $field ) {
		if ( empty( $field['disabled'] ) && ( ! empty( $field['hide_product'] ) || ! empty( $field['show_product'] ) ) ) {
			if ( is_object( WC()->cart ) ) {
				$cart_contents = WC()->cart->get_cart_contents();
				if ( count( $cart_contents ) ) {

					$hide_ids_array = (array) $field['hide_product'];

					$show_ids_array = (array) $field['show_product'];

					$apply_conditions_if_more_than_one_product = empty( $field['apply_conditions_if_more_than_one_product'] );

					$product_ids = array_column( $cart_contents, 'product_id' );

					// field without more
					// -------------------------------------------------------------------
					if ( $apply_conditions_if_more_than_one_product && count( $cart_contents ) < 2 ) {
						// hide field
						// -----------------------------------------------------------------
						if ( count( $hide_ids_array ) ) {
							if ( array_intersect( $product_ids, $hide_ids_array ) ) {
								$field['disabled'] = true;
							}
						}

						// show field
						// -----------------------------------------------------------------
						if ( count( $show_ids_array ) ) {
							if ( ! array_intersect( $product_ids, $show_ids_array ) ) {
								$field['disabled'] = true;
							} else {
								$field['disabled'] = false;
							}
						}
					}

					// field with more
					// -------------------------------------------------------------------
					if ( ! $apply_conditions_if_more_than_one_product ) {

						// hide field
						// -------------------------------------------------------------
						if ( count( $hide_ids_array ) ) {

							if ( array_intersect( $product_ids, $hide_ids_array ) ) {
								$field['disabled'] = true;
							}
						}

						// show field
						// ---------------------------------------------------------------
						if ( count( $show_ids_array ) ) {
							if ( ! array_intersect( $product_ids, $show_ids_array ) ) {
								$field['disabled'] = true;
							} else {
								$field['disabled'] = false;
							}
						}
					}
				}
			}
		}

		return $field;
	}
}
