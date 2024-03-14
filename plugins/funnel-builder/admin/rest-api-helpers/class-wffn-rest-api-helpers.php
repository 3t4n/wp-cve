<?php
if ( ! class_exists( 'WFFN_REST_API_Helpers' ) ) {
	class WFFN_REST_API_Helpers extends WFFN_REST_Controller {

		private static $ins = null;

		/**
		 * @return WFFN_REST_API_Helpers|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function get_step_post( $step_id, $is_updated = false ) {

			$data = array(
				'step_data' => [],
				'step_list' => []
			);

			$step_data = [];
			$step_list = [];

			if ( absint( $step_id ) > 0 && $this->check_step_exists( $step_id ) ) {
				$post_data = get_post( $step_id );
				$step_post = get_post_status( $step_id );

				if ( $post_data instanceof WP_Post ) {
					if ( 'wfocu_offer' === $post_data->post_type ) {
						$step_data['status']    = 'publish' === $step_post ? true : false;
						$step_data['upsell_id'] = get_post_meta( $step_id, '_funnel_id', true );
					} else {
						$step_data['status'] = 'publish' === $step_post ? true : false;
					}
					$step_data['title'] = WFFN_Core()->admin->maybe_empty_title( $post_data->post_title );
					if ( 'wfob_bump' === $post_data->post_type ) {

						$funnel_id = get_post_meta( $step_id, '_bwf_in_funnel', true );

						if ( empty( $funnel_id ) ) {
							$step_data['view_link'] = '';

						} else {
							$funnel = new WFFN_Funnel( $funnel_id );
							$steps  = $funnel->get_steps();

							foreach ( $steps as $stp ) {
								if ( $stp['type'] === 'wc_checkout' && isset( $stp['substeps'] ) && isset( $stp['substeps']['wc_order_bump'] ) && ! empty( $stp['substeps']['wc_order_bump'] ) ) {
									$stp['substeps']['wc_order_bump'] = array_map( 'absint', $stp['substeps']['wc_order_bump'] );
									if ( in_array( absint( $step_id ), $stp['substeps']['wc_order_bump'], true ) ) {
										$step_data['view_link']      = get_the_permalink( $stp['id'] );
										$step_data['edit_link_past'] = admin_url( 'admin.php?page=wfob&section=products&wfob_id=' . $step_id );
										$step_data['checkout_id']    = $stp['id'];
										break;
									}
								}
							}
						}

					} else {
						$get_step = WFFN_Core()->steps->get_integration_object( WFFN_Common::get_step_type( $post_data->post_type ) );
						if ( $get_step instanceof WFFN_Step ) {
							$step_data['view_link'] = $get_step->get_entity_view_link( $step_id );
						} else {
							$step_data['view_link'] = 'javascript:void(0);';
						}

					}

					if ( 'wfocu_offer' === $post_data->post_type && class_exists( 'WFOCU_Core' ) ) {
						$offer_data = WFOCU_Core()->offers->get_offer( $step_id );
						if ( ! empty( $offer_data ) && ! empty( $offer_data->template_group ) && 'customizer' === $offer_data->template_group ) {
							$step_data['view_link'] = add_query_arg( [
								'wfocu_customize' => 'loaded',
								'offer_id'        => $step_id,
							], get_the_permalink( $step_id ) );
						} elseif ( 'custom_page' === $offer_data->template_group ) {
							$custom_page = get_post_meta( $step_id, '_wfocu_custom_page', true );
							if ( ! empty( $custom_page ) ) {
								$step_data['view_link'] = get_the_permalink( $custom_page );
							}
						}
						$step_data['edit_link_past'] = admin_url( 'admin.php?page=upstroke&section=offers&edit=' . $step_data['upsell_id'] );

					}

					if ( 'wfacp_checkout' === $post_data->post_type && class_exists( 'WFACP_Common' ) ) {
						/**
						 * clear wfacp cache data for get updated data
						 */
						add_filter( 'wfacp_get_post_meta_data', '__return_true' );
						$step_data['edit_link_past'] = admin_url( 'admin.php?page=wfacp&section=design&wfacp_id=' . $step_id );

					}

					/**
					 * Get step updated data if perform any update in step
					 * and send this updated data to react for update component
					 */
					if ( true === $is_updated ) {
						$list_step_id = $step_id;
						if ( 'wfob_bump' === $post_data->post_type ) {
							$list_step_id = is_array( $step_data ) && isset( $step_data['checkout_id'] ) ? $step_data['checkout_id'] : $list_step_id;
						}

						if ( 'wfocu_offer' === $post_data->post_type ) {
							$list_step_id = is_array( $step_data ) && isset( $step_data['upsell_id'] ) ? $step_data['upsell_id'] : $list_step_id;
						}

						$get_funnel_id = get_post_meta( $list_step_id, '_bwf_in_funnel', true );
						if ( 0 === $list_step_id || empty( $get_funnel_id ) ) {
							$step_list = '';

						} else {
							$funnel    = new WFFN_Funnel( $get_funnel_id );
							$get_steps = $funnel->get_steps();

							/*
							 * get bump data for store checkout
							 */
							if ( 'wfocu_offer' === $post_data->post_type && ( absint( $get_funnel_id ) === WFFN_Common::get_store_checkout_id() ) && false === in_array( 'wc_checkout', wp_list_pluck( $get_steps, 'type' ), true ) ) {
								$sub_steps     = WFFN_Common::get_store_checkout_global_substeps( $get_funnel_id );
								$sub_step_data = [];
								if ( is_array( $sub_steps ) && count( $sub_steps ) > 0 ) {
									$get_substep = WFFN_Core()->substeps->get_integration_object( 'wc_order_bump' );
									if ( $get_substep instanceof WFFN_Substep ) {
										$sub_step_data = $get_substep->populate_substep_data_properties( $sub_steps );
									}
								}
								$step_list = array(
									'id'       => 0,
									'type'     => WFFN_Common::store_native_checkout_slug(),
									'substeps' => $sub_step_data,
								);

							} else {
								$get_key = array_search( absint( $list_step_id ), wp_list_pluck( $get_steps, 'id' ), true );
								if ( absint( $list_step_id ) > 0 && false !== $get_key && isset( $get_steps[ $get_key ] ) ) {
									$get_object = WFFN_Core()->steps->get_integration_object( $get_steps[ $get_key ]['type'] );
									$step_list  = $get_object->populate_data_properties( $get_steps[ $get_key ], $get_funnel_id );
									if ( is_array( $step_list ) && count( $step_list ) > 0 ) {
										$step_list = apply_filters( 'wffn_rest_get_funnel_steps', array( $step_list ), $funnel );
										$step_list = $this->add_step_edit_details( $step_list );
										if ( is_array( $step_list ) && isset( $step_list[0] ) ) {
											$step_list = $step_list[0];
										}

									}
								}
							}

						}

					}

					$control_id = get_post_meta( $step_id, '_bwf_ab_variation_of', true );
					if ( ! empty( $control_id ) && absint( $control_id ) > 0 ) {
						$step_data               = apply_filters( 'wffn_rest_get_step_post', $step_data, $control_id );
						$step_data['control_id'] = $control_id;
						$step_data['is_variant'] = true;
					} else {
						$step_data = apply_filters( 'wffn_rest_get_step_post', $step_data, $step_id );
					}

					$data['step_list'] = $step_list;
					$data['step_data'] = $step_data;

					return ( false === $is_updated ) ? $step_data : $data;

				}

			}

			return false;
		}

		/**
		 * @param $steps
		 * TODO: this can be further optimized by properly analyzing in-scope
		 * and get steps data without create multiple get_post instance
		 *
		 * @return mixed
		 */
		public function add_step_edit_details( $steps ) {
			if ( is_array( $steps ) && count( $steps ) > 0 ) {

				foreach ( $steps as &$step ) {
					if ( isset( $step['id'] ) ) {
						if ( 'wc_upsells' === $step['type'] && isset( $step['substeps'] ) && count( $step['substeps'] ) > 0 ) {
							foreach ( $step['substeps']['offer'] as $offer ) {
								$offer_post = get_post( $offer['id'] );
								if ( $offer_post instanceof WP_Post ) {
									$offer['_data']->post_name = $offer_post->post_name;
									$offer['_data']->view_link = $this->get_base_url( $offer_post );
								} else {
									$offer['_data']->post_name = '';
									$offer['_data']->view_link = '';
								}
							}

						} else if ( 'wc_native' === $step['type'] ) {
							$view_link = '';
							if ( function_exists( 'wc_get_checkout_url' ) ) {
								$view_link = wc_get_checkout_url();
							}
							$step['_data']['post_name'] = '';
							$step['_data']['view_link'] = $view_link;
							$step['_data']['view']      = $view_link;
						} else {

							$step_post = get_post( $step['id'] );
							if ( $step_post instanceof WP_Post ) {
								$step['_data']['post_name'] = $step_post->post_name;
								$step['_data']['view_link'] = $this->get_base_url( $step_post );
							} else {
								$step['_data']['post_name'] = '';
								$step['_data']['view_link'] = '';
							}
						}
					}
				}
			}

			return $steps;
		}


		public static function check_step_exists( $step_id ) {
			if ( ! empty( $step_id ) && absint( $step_id ) > 0 ) {
				if ( false === get_post_status( $step_id ) ) {
					return false;
				}
			}

			return true;
		}

		public function array_change_key( $arr, $oldkey, $newkey ) {
			$old_key = '"' . $oldkey . '"';
			$new_key = '"' . $newkey . '"';
			$json    = str_replace( $old_key, $new_key, wp_json_encode( $arr ) );

			return json_decode( $json, 1 );
		}

		public function format_fields_options( $options, $seperator = ",", $set_format = false ) {
			$option_data = '';
			$values      = [];
			if ( ! empty( $options ) && is_array( $options ) ) {
				$options = array_values( $options );

				if ( ! empty( $options[0] ) && false === strpos( $seperator, $options[0] ) ) {
					foreach ( $options as $value ) {
						if ( ! empty( $value ) ) {
							$values[] = $value;
						}
					}
				} else {
					$values        = $options;
					$option_values = explode( $seperator, $options[0] );
					if ( count( $option_values ) ) {
						$values = [];
						foreach ( $option_values as $value ) {
							if ( ! empty( $value ) ) {
								$values[] = $value;
							}
						}
					}
				}
				$option_data = ! empty( $values ) ? implode( $seperator, $values ) : '';

				if ( true === $set_format ) {
					$option_data = $this->set_input_options( $option_data, "," );
				}

			}

			return $option_data;
		}

		public function get_template_design( $builder, $slug, $type ) {
			$templates = array();

			if ( ! empty( $builder ) && ! empty( $slug ) ) {
				// GET all WooFunnels templates.
				$all_templates = wffn_rest_funnels()->get_templates();
				$templates     = $all_templates['templates'];
				// FIX Builder name issues for WC Checkout
				if ( 'wc_checkout' === $type ) {
					$builder = ( 'embed_forms' === $builder ) ? 'wp_editor' : $builder;
					$builder = ( 'pre_built' === $builder ) ? 'customizer' : $builder;
				}

				if ( 'upsell' === $type ) {
					if ( 'wfocu-custom-empty' === $slug ) {
						$builder = 'wp_editor';
					}
				}
				return isset( $templates[ $type ][ $builder ][ $slug ] ) ? $templates[ $type ][ $builder ][ $slug ] : array();
			}

			return $templates;
		}

		// Convert array to Name Value Pair.
		public function array_to_nvp( $array, $key = 'label', $value = 'value', $replicate_from = "", $replicate_to = "" ) {
			$nvp = [];
			if ( ! empty( $array ) ) {
				foreach ( $array as $arr_key => $arr_val ) {
					$field           = [];
					$field[ $key ]   = trim( $arr_key );
					$field[ $value ] = trim( $arr_val );
					if ( ! empty( $replicate_from ) && ! empty( $replicate_to ) ) {
						if ( 'key' === $replicate_from ) {
							$field[ $replicate_to ] = $field[ $key ];
						} else {
							$field[ $replicate_to ] = $field[ $value ];
						}
					}
					$nvp[] = $field;
				}
			}

			return $nvp;
		}

		public function set_input_options( $op_options, $seperator = "," ) {
			$options = array();
			$option  = [];
			if ( ! empty( $op_options ) ) {
				$op_options = explode( $seperator, $op_options );
				foreach ( $op_options as $_option ) {
					$option['label'] = $option['value'] = $option['key'] = trim( $_option );
					$options[]       = $option;
				}
			}

			return $options;
		}

		public static function get_name_part( $name, $part = 0 ) {
			if ( ! empty( $name ) && ! empty( $part ) ) {
				$name = explode( "-", $name );
				if ( ! empty( $name[ $part ] ) ) {
					$name = trim( $name[ $part ] );
				}
			}

			return $name;
		}

		public function get_availability_price_text( $product_id ) {
			$availability = [
				'text'  => '',
				'price' => ''
			];


			if ( absint( $product_id ) > 0 ) {
				$product = wc_get_product( $product_id );
				if ( $product instanceof WC_Product ) {
					$availability_text = "";
					$available         = $product->get_availability();

					if ( ! empty( $available['class'] ) ) {
						switch ( $available['class'] ) {
							case 'available-on-backorder' :
								$availability_text = __( 'On backorder', 'funnel-builder' );
								break;
							case 'in-stock' :
								$availability_text = __( 'In stock', 'funnel-builder' );
								break;
							case 'out-of-stock' :
								$availability_text = __( 'Out of stock', 'funnel-builder' );
								break;
						}
					}

					$availability['text']  = $availability_text;
					$availability['price'] = $this->get_product_price( $product_id );

				}
			}

			return $availability;
		}

		public function get_product_price( $product_id ) {
			$price = 0;
			if ( absint( $product_id ) > 0 ) {
				$product = wc_get_product( $product_id );
				if ( $product instanceof WC_Product ) {
					if ( 'variable' === $product->get_type() ) {
						$price = html_entity_decode( wp_strip_all_tags( $product->get_price_html() ) );
					} else {
						$price = $product->get_price();
						if ( '' === $price ) {
							$price = $product->get_regular_price();
						}
						$price = html_entity_decode( wp_strip_all_tags( wc_price( $price ) ) );
					}
				}
			}

			return $price;
		}

		public function unstrip_product_data( $product ) {
			if ( is_object( $product ) ) {
				$product        = (array) $product;
				$product['key'] = ! empty( $product['key'] ) ? $product['key'] : $product['id'];
			}
			if ( is_array( $product ) ) {

				$chk_product   = wc_get_product( $product['key'] );
				$product_image = ! empty( wp_get_attachment_thumb_url( $chk_product->get_image_id() ) ) ? wp_get_attachment_thumb_url( $chk_product->get_image_id() ) : WFFN_PLUGIN_URL . '/admin/assets/img/product_default_icon.jpg';

				$product_availability = wffn_rest_api_helpers()->get_availability_price_text( $chk_product->get_id() );
				$product_stock        = $product_availability['text'];
				$stock_status         = ( $chk_product->is_in_stock() ) ? true : false;

				if ( is_a( $chk_product, 'WC_Product_Variation' ) ) {
					$variation_name = wffn_rest_api_helpers()->get_name_part( $chk_product->get_name(), 1 );
				}

				$product['product_image']        = $product_image;
				$product['product_type']         = $product['type'];
				$product['product_attribute']    = ! empty( $variation_name ) ? $variation_name : '-';
				$product['regular_price']        = ! empty( $chk_product->get_regular_price() ) ? $chk_product->get_regular_price() : 0;
				$product['sale_price']           = ! empty( $chk_product->get_sale_price() ) ? $chk_product->get_sale_price() : 0;
				$product['is_on_sale']           = $chk_product->is_on_sale();
				$product['currency_symbol']      = get_woocommerce_currency_symbol();
				$product['product_stock_status'] = $stock_status;
				$product['product_stock']        = $product_stock;
				$product['price_range']          = ( 'variable' === $chk_product->get_type() ) ? $product_availability['price'] : '';

			}

			return $product;
		}


		public function format_notification_msg( $messages, $type ) {

			foreach ( $messages as $index => $msg ) {
				$msg['message']     = str_replace( '<a', '<a class="bwf-link" ', $msg['message'] );
				$msg['show']        = ! empty( $msg['show'] ) ? bwf_string_to_bool( $msg['show'] ) : false;
				$msg['type']        = str_replace( $type, '', $msg['type'] );
				$msg['message_id']  = $index;
				$messages[ $index ] = $msg;

			}

			return array_values( $messages );

		}

		public function get_entity_url( $type, $entity, $step_id ) {
			if ( absint( $step_id ) > 0 && ! empty( $type ) ) {
				$funnel_id = $this->get_funnel_id_from_step_id( $step_id );
				if ( absint( $funnel_id ) === WFFN_Common::get_store_checkout_id() ) {
					$suffix = "/store-checkout/" . $type . "/" . $step_id . "/" . $entity;
				} else {
					$suffix = "/funnel-$type/$step_id/$entity&funnel_id=$funnel_id";
				}

				return $suffix;
			} else {
				return '';
			}

		}

		public function get_funnel_id_from_step_id( $step_id ) {
			$funnel_id = 0;
			$post_data = get_post( $step_id );

			if ( $post_data instanceof WP_Post ) {

				$funnel_id = get_post_meta( $step_id, '_bwf_in_funnel', true );

				if ( 'wfocu_offer' === $post_data->post_type ) {
					$upsell_id = get_post_meta( $step_id, '_funnel_id', true );
					$funnel_id = get_post_meta( $upsell_id, '_bwf_in_funnel', true );
				}
			}

			return $funnel_id;

		}


		public static function maybe_step_not_exits( $step_id ) {
			if ( 0 === absint( $step_id ) || ! self::check_step_exists( $step_id ) ) {
				http_response_code( 404 );
				$error_message = array(
					'code'    => 'woofunnels_rest_step_not_exists',
					'message' => __( 'Invalid step ID.', 'funnel-builder' ),
					'data'    => array(
						'status' => 404
					)
				);
				wp_send_json( $error_message );

			}

			return true;
		}

		/**
		 * @param $cap
		 * @param $access
		 *
		 * @return bool
		 */
		public function get_api_permission_check( $cap, $access ) {
			if ( WFFN_Core()->role->user_access( $cap, $access ) ) {
				return true;
			}

			return false;
		}

	}

	if ( ! function_exists( 'wffn_rest_api_helpers' ) ) {

		/**
		 * @return WFFN_REST_API_Helpers|null
		 */
		function wffn_rest_api_helpers() {
			return WFFN_REST_API_Helpers::get_instance();
		}
	}

}