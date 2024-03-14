<?php

namespace InspireLabs\WoocommerceInpost\admin;

use InspireLabs\WoocommerceInpost\EasyPack;
use WC_Shipping;
use WC_Shipping_Method;

class EasyPack_Product_Shipping_Method_Selector {

	const NO_METHODS_SELECTED = 0;

	const META_ID = EasyPack::ATTRIBUTE_PREFIX . '_shipping_methods_allowed';
    const META_ID_SIZE = EasyPack::ATTRIBUTE_PREFIX . '_parcel_dimensions';

	public static $inpost_methods;

	public function handle_product_edit_hooks() {
		add_filter( 'woocommerce_product_data_tabs', [ $this, 'filter_woocommerce_product_data_tabs' ], 10, 1 );
		add_action( 'woocommerce_product_data_panels', [ $this, 'action_woocommerce_product_data_panels' ], 10, 0 );
		add_action( 'woocommerce_admin_process_product_object', [ $this, 'action_woocommerce_admin_process_product_object' ], 10, 1 );
        add_action( 'woocommerce_product_options_shipping', array( $this, 'easypack_parcel_size_select' ), 10, 0 );
	}


	/**
	 * @param int $product_id
	 *
	 * @return array|null
	 */
	private function get_config_by_product_id( int $product_id ): ?array {
		$meta = get_post_meta( $product_id, self::META_ID, true );
        // if saved zero methods (all methods unchecked before save) - we have empty array
        // if product is new - return null to show all checked in is_checked function
		return is_array( $meta ) ? $meta : null;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
    private function get_inpost_methods_by_product_id( int $product_id  ): array {
        $return = [];
        $new_config_settings = false;

        $config_raw = $this->get_config_by_product_id( $product_id );

        // reset settings for old plugin version
        if( ! $this->saved_product_settings_is_actual( $config_raw ) ) {
            $config_raw = null;
        }

        $all_inpost_methods = EasyPack_Helper()->get_inpost_methods();

        if ( null === $config_raw ) { // by default for all products
            if ( ! empty( $all_inpost_methods ) ) {
                foreach ( $all_inpost_methods as $method ) {
                    $return[] = $method['method_title_with_id'];
                }
            }
        }

        if ( null !== $config_raw && is_array( $config_raw ) ) {
            return $config_raw;
        }

        return $return;
    }


	/**
	 * Add custom product setting tab.
	 */
	public function filter_woocommerce_product_data_tabs( $default_tabs ) {
		$default_tabs['custom_tab'] = [
			'label'    => __( 'InPost', 'woocommerce-inpost' ),
			'target'   => 'wk_custom_tab_data',
			'priority' => 60,
			'class'    => [],
		];

		return $default_tabs;
	}

	/**
	 * @param array|null $config_by_product
	 * @param $method_id
	 *
	 * @return bool
	 */
	private function is_checked( ?array $config_by_product, $method_id ) {

        // reset settings for old plugin version
        if( ! $this->saved_product_settings_is_actual( $config_by_product ) ) {
            $config_by_product = null;
        }

        // first time product open for edit
	    if( null === $config_by_product ) {
            return true;
        }

		if ( is_array( $config_by_product ) && in_array( $method_id, $config_by_product ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Contents custom product setting tab.
	 */
    public function action_woocommerce_product_data_panels() {
        global $post;
        echo '<div id="wk_custom_tab_data" class="panel woocommerce_options_panel">';
        $config_by_product = $this->get_config_by_product_id( $post->ID );

        foreach ( EasyPack_Helper()->get_inpost_methods() as $key => $method ) {
            woocommerce_wp_checkbox( [
                'id'    => $this->get_post_key_from_method_id( $method['method_title_with_id'] ),
                'label' => $method['user_title'],
                'value' => $this->is_checked( $config_by_product, $method['method_title_with_id'] ) ? 'yes' : null,
            ] );
        }

        echo '</div>';
    }

	/**
	 * @param string $method_id
	 *
	 * @return string
	 */
	private function get_post_key_from_method_id( string $method_id ): string {
		return '_' . EasyPack::ATTRIBUTE_PREFIX . '_shipping_method_id_' . $method_id;
	}


	/**
	 * Save the checkbox.
	 */
    public function action_woocommerce_admin_process_product_object( $product ) {
        $allowed_methods = [];
        foreach ( EasyPack_Helper()->get_inpost_methods() as $method ) {
            $post_key = $this->get_post_key_from_method_id( $method['method_title_with_id'] );
            if ( isset( $_POST[ $post_key ] ) && $_POST[ $post_key ] === 'yes' ) {
                $allowed_methods[] = $method['method_title_with_id'];
            }
        }

        $product->update_meta_data( self::META_ID, $allowed_methods );

        if ( isset( $_POST[ 'easypack_parcel_dimensions' ] ) && ! empty( $_POST[ 'easypack_parcel_dimensions' ] ) ) {
            $product->update_meta_data( self::META_ID_SIZE, sanitize_text_field( $_POST[ 'easypack_parcel_dimensions' ] ) );
        }
        // clear shipping methods cache
        \WC_Cache_Helper::get_transient_version( 'shipping', true );

    }

    /**
     * Return allowed InPost shipping methods defined for products in cart
     *
     * @param array $contents_of_the_cart
     *
     * @return array
     */
    public function get_methods_allowed_by_cart( array $contents_of_the_cart ): array {

        $config_by_product = [];


        if( ! empty( $contents_of_the_cart ) ) {

            $physical_goods_ids = [];
            $total_weight = 0;
            foreach ( $contents_of_the_cart as $cart_item_key => $cart_item ) {

                // if variation in cart
                if( $cart_item['variation_id'] ) {

                    $variant = wc_get_product( $cart_item['variation_id'] );
                    if( ! $variant->is_virtual() /* && ! $variant->is_downloadable() */ ) {
                        $physical_goods_ids[] = $cart_item['product_id'];
                        $total_weight += floatval( $variant->get_weight() ) * $cart_item['quantity'];
                    }

                } else {

                    $_product = wc_get_product( $cart_item['product_id'] );
                    if ( ! $_product->is_virtual() /* && ! $_product->is_downloadable() */ ) {
                        $physical_goods_ids[] = $cart_item['product_id'];
                        $total_weight += floatval( $_product->get_weight() ) * $cart_item['quantity'];
                    }
                }
            }

            $physical_goods_ids = array_unique( $physical_goods_ids );

            if( ! empty( $physical_goods_ids ) ) {

                if( get_option( 'easypack_enable_for_all_products' ) === 'yes') {


                    foreach ( EasyPack_Helper()->get_inpost_methods() as $method ) {
                        $config_by_product[] = $method['method_title_with_id'];
                    }

                    return $this->block_overweight( $total_weight, $config_by_product );

                } else {

                    foreach ( $physical_goods_ids as $id ) {
                        $config_by_product[$id] = $this->get_inpost_methods_by_product_id( $id );
                    }

                    if( count( $physical_goods_ids ) === 1 ) {
                        $config_by_product = $this->block_overweight( $total_weight, $config_by_product[$id] );
                        return  $config_by_product;
                    }

                    if( is_array( $config_by_product ) && count( $config_by_product ) > 1 ) {
                        $config_by_product = call_user_func_array( 'array_intersect', $config_by_product );
                        $config_by_product = $this->block_overweight( $total_weight, $config_by_product );
                    }
                }

            }
        }

        return $config_by_product;
    }


    /**
     * Add option with parcel dimensions on product edit page
     *
     */
    public function easypack_parcel_size_select() {
        global $post;

        $options = EasyPack()->get_package_sizes_gabaryt();

        woocommerce_wp_select(
            array(
                'id'      => 'easypack_parcel_dimensions',
                'label'   => __( 'InPost parcel dimensions', 'woocommerce-inpost' ),
                'options' => $options,
                'value'   => get_post_meta( $post->ID, self::META_ID_SIZE, true)
            )
        );

    }


    /**
     * Block weight over 25 kg for two methods: paczkomaty and courier_c2c
     *
     */
    private function block_overweight( $total_weight, $config_by_product ) {

        if( get_option( 'easypack_over_weight' ) === 'yes') {
            if( $total_weight >= 25 ) {

                if( ! empty( $config_by_product) && is_array( $config_by_product ) ) {

                    foreach( $config_by_product as $key => $allowed_method ) {
                        if ( 0 === strpos( $allowed_method, 'easypack_') ) {
                            $method_name = EasyPack_Helper()->validate_method_name( $allowed_method );
                            if( 'easypack_parcel_machines' === $method_name
                                || 'easypack_parcel_machines_cod' === $method_name
                                || 'easypack_shipping_courier_c2c' === $method_name
                                || 'easypack_shipping_courier_c2c_cod' === $method_name
                                || 'easypack_parcel_machines_economy' === $method_name
                                || 'easypack_parcel_machines_economy_cod' === $method_name ) {
                                unset( $config_by_product[$key] );
                            }
                            // integration with Flexible Shipping
                        } elseif ( 0 === strpos( $allowed_method, 'flexible_shipping' ) ) {
                            $instance_id = '';
                            if( stripos( $allowed_method, ':' ) ) {
                                $instance_id =  trim( explode(':', $allowed_method )[1] );
                            }
                            if( ! empty( $instance_id ) ) {
                                $linked_method = EasyPack_Helper()->get_method_linked_to_fs_by_instance_id( $instance_id );
                                if( 'easypack_parcel_machines' === $linked_method
                                    || 'easypack_parcel_machines_cod' === $linked_method
                                    || 'easypack_shipping_courier_c2c' === $linked_method
                                    || 'easypack_shipping_courier_c2c_cod' === $linked_method
                                    || 'easypack_parcel_machines_economy' === $linked_method
                                    || 'easypack_parcel_machines_economy_cod' === $linked_method ) {
                                    unset( $config_by_product[$key] );
                                }
                            }

                        }
                    }
                }
            }
        }

        return $config_by_product;
    }


    /**
     * We need to reset allowed shipping methods stored in product meta
     * in some cases: on Flexible Shipping activating/ deactivating
     * and when customer upgraded plugin as we changed this functionality
     * after ver. 1.0.5
     *
     */
    private function saved_product_settings_is_actual( $config ) {
        // empty array in config
        // settings are correct but all InPost methods are prohibited in product settings
        if( is_array( $config ) && empty( $config ) ) {
            return true;
        }

        if( is_array( $config ) && ! empty( $config ) ) {

            foreach ( $config as $method_name ) {
                // check if settings were saved in previous version of plugin (<= 1.0.5)
                // or product has settings configured in those time when was active Flexible Shipping
                $is_easypack_method_with_instance_id = stripos( $method_name, ':' ) && 0 === strpos( $method_name, 'easypack_' );
                $is_flexible_method_with_instance_id = stripos( $method_name, ':' ) && 0 === strpos( $method_name, 'flexible_shipping' );

                if ( $is_easypack_method_with_instance_id || $is_flexible_method_with_instance_id )
                {
                    return true;
                }
            }
        }

        return false;
    }


}