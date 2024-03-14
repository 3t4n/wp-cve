<?php

class Webtoffee_Product_Feed_Basic_Shipping {
	
	protected $shipping_options;
	private $zoneId;
	private $zoneName;
	private $methods;
	private $country;
	private $state;
	private $postcode;
	protected $counter = 0;
	protected $product;
	protected $form_data;

	public function __construct( $product, $type, $form_data ) {
		$this->product = $product;
		$this->type  = $type;
		$this->form_data = $form_data;
	}
	

	public function get_shipping_zones() {
		$shipping_options = wp_cache_get( 'wt_feed_shipping_options' );

		if ( false === $shipping_options ) {
			$zones = WC_Shipping_Zones::get_zones();
			if ( ! empty( $zones ) ) {
				foreach ( $zones as $zone ) {
					$this->zoneId   = $zone['zone_id'];
					$this->zoneName = $zone['zone_name'];
					$this->methods  = $zone['shipping_methods'];
					$this->get_locations( $zone['zone_locations'] );
				}
			}
			wp_cache_add( 'wt_feed_shipping_options', $this->shipping_options, '', WEEK_IN_SECONDS );
			$shipping_options = $this->shipping_options;
		}
		
		
		return $shipping_options;
	}
	

	private function get_locations( $locations ) {
		if ( ! empty( $locations ) ) {
			foreach ( $locations as $location ) {
				
				if ( 'country' === $location->type ) {
					$this->country = $location->code;
					$this->get_methods();
				} elseif ( 'state' === $location->type ) {
					
					$countryState  = explode( ':', $location->code );
					$this->country = $countryState[0];
					$this->state   = $countryState[1];
					
					$this->get_methods();
					
				} elseif ( 'postcode' === $location->type ) {
					$this->postcode = str_replace( "...", "-", $location->code );
				}
			}
			$this->zoneId   = "";
			$this->zoneName = "";			
			$this->country  = "";
			$this->state    = "";
			$this->postcode = "";
		}
	}
	

	private function get_methods() {
		if ( ! empty( $this->methods ) ) {
			foreach ( $this->methods as $method ) {
				if ( 'yes' === $method->enabled && 'local_pickup' !== $method->id ) {
					
					if ( empty( $this->country ) ) {
						$service = $this->zoneName . " " . $method->title;
					} else {
						$service = $this->zoneName . " " . $method->title . " " . $this->country;
					}
					
					$this->shipping_options[ $this->counter ]['zone_id']            = $this->zoneId;
					$this->shipping_options[ $this->counter ]['zone_name']          = $this->zoneName;
					$this->shipping_options[ $this->counter ]['country']            = $this->country;
					$this->shipping_options[ $this->counter ]['state']              = $this->state;
					$this->shipping_options[ $this->counter ]['service']            = $service;
					$this->shipping_options[ $this->counter ]['postcode']           = $this->postcode;
					$this->shipping_options[ $this->counter ]['method_id']          = $method->id;
					$this->shipping_options[ $this->counter ]['method_instance_id'] = $method->instance_id;
					$this->shipping_options[ $this->counter ]['method_title']      = $method->get_method_title();
					$this->shipping_options[ $this->counter ]['method_min_amount'] = isset( $method->min_amount ) ? $method->min_amount : "";
					$this->shipping_options[ $this->counter ]['price']             = $this->get_shipping_price( $this->shipping_options[ $this->counter ] );
					$this->counter ++;
				}
			}
		}
	}
	

	private function get_shipping_price( $shipping ) {
		
		if ( ! is_object( $this->product ) ) {
			return "";
		}
		
		if ( ( 'free_shipping' === $shipping['method_id'] ) && $this->product->get_price() >= $shipping['method_min_amount'] ) {
			return apply_filters( 'wt_feed_shipping_attribute_price', 0, $shipping);
		}
		

		$shipping_cost = 0;
		$tax           = 0;
		defined( 'WC_ABSPATH' ) || exit;
		
		include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
		include_once WC_ABSPATH . 'includes/class-wc-cart.php';
		
		wc_load_cart(); // >= 3.6.4
		global $woocommerce;
		
		$woocommerce->cart->empty_cart(); 
		
		if ( isset( $shipping['country'] ) && ! empty( $shipping['country'] ) ) {
			$woocommerce->customer->set_shipping_country( $shipping['country'] );
		}

		if ( isset( $shipping['state'] ) && ! empty( $shipping['state'] ) ) {
			$woocommerce->customer->set_shipping_state( $shipping['state'] );
		} else {
			$woocommerce->customer->set_shipping_state( "" );
		}
		
		$chosen_ship_method_id = $shipping['method_id'] . ':' . $shipping['method_instance_id'];

		WC()->session->set( 'chosen_shipping_methods', array( $chosen_ship_method_id ) );
		
		if ( 'variation' === $this->product->get_type() ) {
			$id = $this->product->get_parent_id();
		} elseif ( 'grouped' === $this->product->get_type() ) {
			$id = $this->product->get_children();
			$id = reset( $id );
		} else {
			$id = $this->product->get_id();
		}
		
		$woocommerce->cart->add_to_cart( $id, 1 );
		
		$shipping_cost = $woocommerce->cart->get_shipping_total();
		$tax           = $woocommerce->cart->get_shipping_tax();
		
		if ( isset( $method['id'] ) && $method['instance_id'] ) {
			WC()->session->set( 'chosen_shipping_methods', array( '' ) );
		}
		
		$shipping_cost += $tax;
		
		$woocommerce->cart->empty_cart();
		
		return $shipping_cost;
	}
	
	
	
	
	
	public function get_shipping_by_location($shpping_country) {
			$shipping_info = [];
			$shipping_zones = WC_Shipping_Zones::get_zones();

			foreach ($shipping_zones as $zone) {
				$shipping = [];
				$locations = $zone['zone_locations'];

				$zone_location = wp_list_pluck($locations, 'code');
				if ($zone_location) {
					$target_zone_key = array_search($shpping_country, $zone_location);

					if (isset($target_zone_key) && !empty($target_zone_key)) {
						$temp_location = $locations[0];
						$locations[0] = $locations[$target_zone_key];
						$locations[1] = $temp_location;
					}
				}


				foreach ($locations as $zone_type) {
					if ('country' == $zone_type->type) {
						$shipping['country'] = $zone_type->code;
						$shipping['region'] = '';
					} elseif ('code' == $zone_type->type) {
						$shipping['country'] = $zone_type->code;
						$shipping['region'] = '';
					} elseif ('state' == $zone_type->type) {
						$zone_explode = explode(":", $zone_type->code);
						$shipping['country'] = $zone_explode[0];
						$shipping['region'] = $zone_explode[1];
					} elseif ('postcode' == $zone_type->type) {
						$zone_type->code = str_replace("...", "-", $zone_type->code);
						if (empty($shipping['region'])) {
							$shipping['postal_code'] = $zone_type->code;
						}
					}

					$shipping_country_check = Webtoffee_Product_Feed_Sync_Common_Helper::get_advanced_settings('all_shipping_zone');
					$shipping_country_check = apply_filters( 'wt_feed_shipping_country_check', $shipping_country_check );
					if ( 0 === $shipping_country_check && $shipping['country'] !== $this->form_data['post_type_form_data']['item_country'] ) {
						unset($shipping);
						continue 2;
					}				
					
					$shipping_methods = $zone['shipping_methods'];

					if (empty($shipping_methods)) {
						unset($shipping);
						continue 2;
					}

					foreach ($shipping_methods as $key => $method) {
						if ('yes' === $method->enabled) {
							if (empty($shipping['country'])) {
								$shipping['service'] = $zone['zone_name'] . " " . $method->title;
								$shipping['zone_name'] = $zone['zone_name'];
							} else {
								$shipping['service'] = $zone['zone_name'] . " " . $method->title . " " . $shipping['country'];
								$shipping['zone_name'] = $zone['zone_name'];
							}

							if ('free_shipping' === $method->id) {
								$minimum_fee = $method->min_amount;
								settype($minimum_fee, "double");

								if ($this->product->get_price() >= $minimum_fee) {
									$shipping['price'] = apply_filters('wt_feed_filter_shipping_price', 0, $method);
								}
							} else {
								$shipping_method['id'] = $method->id;
								$shipping_method['instance_id'] = $method->instance_id;

								if (isset($shipping) && !empty($shipping)) {
									$shipping_cost = $this->get_shipping_cost($shipping, $shipping_method);
									$shipping['price'] = apply_filters('wt_feed_filter_shipping_attribute_price', $shipping_cost, $method);
								}
							}
						} else {
							unset($shipping);
							continue 3;
						}

						if (isset($shipping)) {
							$shipping_info[] = $shipping;
						}
					}
				}
			}


			return apply_filters("wt_feed_processed_shipping_info", $shipping_info, $shipping_zones, $this->product);
		}

	
	
	
	public function get_shipping_cost( $shipping, $method ) {

		// Shipping cost calculations using cart object
		$shipping_cost = 0;
		$tax = 0;
		defined( 'WC_ABSPATH' ) || exit;

		include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
		include_once WC_ABSPATH . 'includes/class-wc-cart.php';

		wc_load_cart(); // >= 3.6.4
		global $woocommerce;


		$woocommerce->cart->empty_cart();

		// Set shipping country.
		if ( isset($shipping['country']) && ! empty($shipping['country']) ) {
			$woocommerce->customer->set_shipping_country( $shipping['country'] );
		}
		// Set shipping region.
		if ( isset($shipping['region']) && ! empty($shipping['region']) ) {
			$woocommerce->customer->set_shipping_state( $shipping['region'] );
		}else {
			$woocommerce->customer->set_shipping_state("");
		}

        // Set shipping method in the cart
        if ( isset($method['id'] ) && $method['instance_id'] ) {
            $chosen_ship_method_id = $method['id'] . ':' . $method['instance_id'];
            $chosen_ship_method_id = apply_filters('wt_feed_filter_chosen_method_id', $chosen_ship_method_id, $shipping, $method);
            WC()->session->set('chosen_shipping_methods', array( $chosen_ship_method_id ) );
        }

        // Get product ID.
        if ( 'variation' === $this->product->get_type() ) {
            $id = $this->product->get_parent_id();
        } elseif ( 'grouped' === $this->product->get_type() ) {
            $id = $this->product->get_children();
            $id = reset($id);
        } else {
            $id = $this->product->get_id();
        }

        // Add product to cart x 1.
		$woocommerce->cart->add_to_cart( $id, 1 );

		// Get shipping costs.
		foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
			$shipping_cost = $woocommerce->cart->get_shipping_total();
			$tax = $woocommerce->cart->get_shipping_tax();
		}

        // Reset chosen shipping methods in the cart
        if ( isset($method['id'] ) && $method['instance_id'] ) {
            WC()->session->set('chosen_shipping_methods', array( '' ) );
        }

		$shipping_cost = $shipping_cost + $tax;

		// Empty the cart
		$woocommerce->cart->empty_cart();

		return $shipping_cost;
	}
	
}