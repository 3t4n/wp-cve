<?php

namespace InspireLabs\WoocommerceInpost;

use ConcatPdf;
use InspireLabs\WoocommerceInpost\EasyPack;
use Exception;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shippng_Parcel_Machines;
use InspireLabs\WoocommerceInpost\shipx\models\courier_pickup\ShipX_Dispatch_Order_Model;
use Requests_Utility_CaseInsensitiveDictionary;
use WC_Shipping_Method;

/**
 * EasyPack Helper
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EasyPack_Helper' ) ) :

	class EasyPack_Helper {

		protected static $instance;
        protected static $css_embeded;

		public function __construct() {
			add_filter( 'query_vars', [ $this, 'query_vars' ] );
			add_action( 'parse_request', [ $this, 'parse_request' ] );

			add_action( 'woocommerce_before_my_account', [ $this, 'woocommerce_before_my_account' ] );
			add_filter( 'woocommerce_screen_ids', [ $this, 'woocommerce_screen_ids' ] );
		}

		public static function EasyPack_Helper() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function write_stickers_to_file( $stickers = [] ) {
			$temp_dir = trailingslashit( get_temp_dir() );
			if ( sizeof( $stickers ) == 1 ) {
				$temp_file = tempnam( $temp_dir, 'ep' );
				$fp        = fopen( $temp_file, 'w' );
				fwrite( $fp, $stickers[0] );
				fclose( $fp );
			} else {
				$files = [];
				foreach ( $stickers as $sticker ) {
					$temp_file = tempnam( $temp_dir, 'ep' );
					$fp        = fopen( $temp_file, 'w' );
					fwrite( $fp, $sticker );
					fclose( $fp );
					$files[] = $temp_file;
				}

				$temp_file = tempnam( $temp_dir, 'ep' );
				$pdf       = new ConcatPdf();
				$pdf->setFiles( $files );
				$pdf->concat();
				$pdf->Output( $temp_file, 'F' );
				foreach ( $files as $file ) {
					unlink( $file );
				}
			}

			return $temp_file;
		}

		public function get_file( $file, $file_name, $content_type = '' ) {

			header( 'Content-type: ' . $content_type );
			header( 'Content-Disposition: attachment; filename="' . $file_name
			        . '"' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Content-Length: ' . filesize( $file ) );
			header( 'Accept-Ranges: bytes' );

			@readfile( $file );

			unlink( $file );

		}

		public function print_stickers(
			$return_stickers = false,
			$orders = null
		) {
			$ret = [ 'status' => 'ok' ];

			if ( null === $orders ) {
                $orders = isset( $_POST['easypack_parcel'] ) ? (array) $_POST['easypack_parcel'] : array();
                $orders = array_map( 'sanitize_text_field', $orders );
			}

			$selected_shipments_ids = [];
			$shipment_service       = EasyPack()->get_shipment_service();

            if( ! empty ( $orders ) ) {

                if( is_array( $orders ) ) {
                    foreach ($orders as $order) {
                        $inpost_internal_data = $shipment_service->get_shipment_by_order_id( (int)$order );

                        if( $inpost_internal_data && is_object( $inpost_internal_data ) ) {
                            $selected_shipments_ids[] = $inpost_internal_data->getInternalData()->getInpostId();
                        }
                    }
                } else {

                    $inpost_internal_data = $shipment_service->get_shipment_by_order_id( (int)$orders );

                    if( $inpost_internal_data && is_object( $inpost_internal_data ) ) {
                        $selected_shipments_ids[] = $inpost_internal_data->getInternalData()->getInpostId();
                    }
                }
            }

			try {
				if ( true === $return_stickers ) {
					$results
						= EasyPack_API()->customer_shipments_return_labels( $selected_shipments_ids );
				} else {
					$results
						= EasyPack_API()->customer_shipments_labels( $selected_shipments_ids );
				}

				if ( ! isset( $results['headers'] ) ) {
                    if ( isset( $_POST['easypack_action'] ) && $_POST['easypack_action'] === 'easypack_create_bulk_labels' ) {
                        echo json_encode(array(
                                'status' => isset($results['status']) ? $results['status']: 'Błąd',
                                'details' => isset($results['details']) ? $results['details'] : 'Wystąpił błąd',
                                'message' => isset($results['message']) ? $results['message'] : 'Helper:138'
                            )
                        );
                    }
                    return;

				} else {
					$headers = $results['headers'];
				}

				/**
				 * @var Requests_Utility_CaseInsensitiveDictionary $headers
				 */

				header( sprintf( "Content-type:%s",
					$headers->getAll()['content-type'] ) );

				echo $results['body'];
				die();

			} catch ( Exception $e ) {
				$ret['status']  = 'error';
				$ret['message'] = $e->getMessage();
				wp_die( __( 'Error while creating manifest:  ', 'woocommerce-inpost' ) . $e->getMessage() );

			}

		}


		public function print_dispatch_order( $order_id ) {
			$ret = [ 'status' => 'ok' ];

			try {
				$results = EasyPack_API()->dispatch_order_pdf( $order_id );

				header( sprintf( "Content-type:%s",
					$results['headers']['data']['content-type'] ) );

				echo $results['body'];
				wp_die();

			} catch ( Exception $e ) {
				$ret['status']  = 'error';
				$ret['message'] = $e->getMessage();
				wp_die( __( 'Error while creating dispatch_order:  ', 'woocommerce-inpost' ) . $e->getMessage() );

			}
		}

		/**
		 * Allow for custom query variables
		 */
		public function query_vars( $query_vars ) {
			$query_vars[] = 'easypack_download';

			return $query_vars;
		}

		/**
		 * Parse the request
		 */
		public function parse_request( &$wp ) {
			if ( array_key_exists( 'easypack_download', $wp->query_vars ) ) {
				if ( isset( $_GET['easypack_parcel_machines_stickers'] )
				     && $_GET['easypack_parcel_machines_stickers'] == '1'
				) {
					EasyPack_Shippng_Parcel_Machines::get_stickers();
				}
				if ( isset( $_GET['easypack_file'] ) ) {
					$temp_dir = trailingslashit( get_temp_dir() );
					$file     = $temp_dir . sanitize_text_field( $_GET['easypack_file'] );
					$this->get_file( $file,
						__( 'stickers', 'woocommerce-inpost' ) . '_' . time()
						. '.pdf', 'application/pdf' );
				}

				exit;
			}
		}


		/**
		 * @param string|null $country
		 *
		 * @return string
		 */
		public function get_tracking_url( $country = null ) {
			if ( null === $country ) {
				if ( EasyPack_API()->is_pl() ) {
					return 'https://inpost.pl/sledzenie-przesylek?number=';
				}

				return 'https://tracking.inpost.co.uk/';
			}

			if ( EasyPack_API()->normalize_country_code_for_inpost( $country )
			     === EasyPack_API::COUNTRY_PL
			) {
				return 'https://inpost.pl/sledzenie-przesylek?number=';
			}

			return 'https://tracking.inpost.co.uk/';
		}

		public function get_weight_option( $weight, $options ) {
			$ret     = - 1;
			$options = array_reverse( $options, true );
			foreach ( $options as $val => $option ) {
				if ( floatval( $weight ) <= floatval( $val ) ) {
					$ret = $val;
				}
			}

			return $ret;
		}


		function woocommerce_before_my_account() {
			if ( get_option( 'easypack_returns_page' )
			     && trim( get_option( 'easypack_returns_page' ) ) != ''
			) {
				$page = get_page( get_option( 'easypack_returns_page' ) );
				if ( $page ) {
					$img_src = EasyPack()->getPluginImages()
					           . 'logo/small/white.png';
					$args = [
						'returns_page'       => get_page_link( get_option( 'easypack_returns_page' ) ),
						'returns_page_title' => $page->post_title,
						'img_src'            => $img_src,
					];
					wc_get_template( 'myaccount/before-my-account.php', $args,
						'', plugin_dir_path( EasyPack()->getPluginFilePath() )
						    . 'templates/' );
				}
			}
		}

		function woocommerce_screen_ids( $screen_ids ) {
			$screen_ids[] = 'inpost_page_easypack_shipment';

			return $screen_ids;
		}

        /**
         * Check if at least one physical product exists in cart
         *
         * @param array $cart_contents
         *
         * @return bool
         */
        public function physical_goods_in_cart( $cart_contents ) {
            $res = false;

            if( ! empty( $cart_contents ) ) {
                foreach ( $cart_contents as $cart_item_key => $cart_item ) {
                    // if variation in cart
                    if( $cart_item['variation_id'] ) {

                        $variant = wc_get_product( $cart_item['variation_id'] );
                        if( ! $variant->is_virtual() /* && ! $variant->is_downloadable() */ ) {
                            $res = true;
                            break;
                        }
                    } else {
                        // simple product
                        $_product = wc_get_product( $cart_item['product_id'] );
                        if ( ! $_product->is_virtual() /* && ! $_product->is_downloadable() */ ) {
                            $res = true;
                            break;
                        }
                    }
                }
            }

            return $res;
        }

		public function validate_method_name( $method_name ) {
		    if( stripos( $method_name, ':' ) ) {
		        return trim( explode(':', $method_name)[0] );
            }
		    return $method_name;
        }

        public function validate_method_instance_id( $method_name ) {
            if( stripos( $method_name, ':' ) ) {
                return trim( explode(':', $method_name)[1] );
            }
            return null;
        }


        /**
         * Convert size from model data to letter symbol (A,B,C)
         *
         * @param string $size
         *
         * @return string
         */
        public function convert_size_to_symbol($size) {
            if($size === 'small') {
                return 'A';
            }
            if($size === 'medium') {
                return 'B';
            }
            if($size === 'large') {
                return 'C';
            }
			if($size === 'xlarge') {
                return 'D';
            }

            return $size; // for Kurier shipments with dimensions

        }


        public function get_saved_method_rates($id, $instance_id) {

            $rates = get_option( 'woocommerce_' . $id . '_' . $instance_id . '_rates' );
            // backward compatibility
            if ( ! $rates ) {
                $rates = get_option( 'woocommerce_' . $id . '_rates', [] );
            }

            return is_array($rates) ? $rates : [];
        }


        /**
         * Integration with plugin "Flexible shipping"
         *
         * @return boolean
         */
        public function is_flexible_shipping_activated() {
            $plugin_file = 'flexible-shipping/flexible-shipping.php';
            $active_plugins = (array) get_option( 'active_plugins', array() );

            if ( is_multisite() ) {
                $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
            }

            return in_array( $plugin_file, $active_plugins ) || array_key_exists( $plugin_file, $active_plugins );
        }

        /**
         * Integration with plugin "Flexible shipping" (get shipping method linked in FS settings)
         *
         * @param array $chosen_shipping_methods
         *
         * @return string
         */
        public function get_method_linked_to_fs( $chosen_shipping_methods ) {
            $method_linked_to_fs = '';
            foreach ( $chosen_shipping_methods as $shipping_method ) {
                if ( 0 === strpos( $shipping_method, 'flexible_shipping_single' ) ) {

                    $shipping_method_instance_id = $this->validate_method_instance_id( $shipping_method );
                    if( isset( $shipping_method_instance_id ) ) {
                        $method_linked_to_fs = $this->get_method_linked_to_fs_by_instance_id( $shipping_method_instance_id );
                    }
                }
            }

            return $method_linked_to_fs;
        }


        /**
         * Integration with plugin "Flexible shipping" (get shipping method linked in FS settings)
         *
         * @param string $instance_id
         *
         * @return string
         */
        public function get_method_linked_to_fs_by_instance_id( $instance_id ) {
            $method_linked_to_fs = '';
            $shipping_method_settings = get_option( 'woocommerce_flexible_shipping_single_' . $instance_id . '_settings' );

            if( isset( $shipping_method_settings['fs_inpost_pl_method'] ) && ! empty( $shipping_method_settings['fs_inpost_pl_method'] ) ) {
                $method_linked_to_fs = $shipping_method_settings['fs_inpost_pl_method'];
            }

            return $method_linked_to_fs;
        }


        public function get_class_name_by_shipping_id( $shipping_id ) {

            switch ( $shipping_id ) {
                case 'easypack_parcel_machines_economy':
                    return 'EasyPack_Shipping_Parcel_Machines_Economy';
                case 'easypack_parcel_machines_economy_cod':
                    return 'EasyPack_Shipping_Parcel_Machines_Economy_COD';
                case 'easypack_shipping_courier_c2c_cod':
                    return 'EasyPack_Shipping_Method_Courier_C2C_COD';
                case 'easypack_shipping_courier_c2c':
                    return 'EasyPack_Shipping_Method_Courier_C2C';
                case 'easypack_parcel_machines':
                    return 'EasyPack_Shippng_Parcel_Machines';
                case 'easypack_parcel_machines_cod':
                    return 'EasyPack_Shippng_Parcel_Machines_COD';
                case 'easypack_parcel_machines_weekend':
                    return 'EasyPack_Shipping_Parcel_Machines_Weekend';
                case 'easypack_shipping_courier':
                    return 'EasyPack_Shipping_Method_Courier';
                case 'easypack_cod_shipping_courier':
                    return 'EasyPack_Shipping_Method_Courier_COD';
                case 'easypack_shipping_courier_local_express':
                    return 'EasyPack_Shipping_Method_Courier_Local_Express';
                case 'easypack_shipping_courier_le_cod':
                    return 'EasyPack_Shipping_Method_Courier_Local_Express_COD';
                case 'easypack_shipping_courier_local_standard':
                    return 'EasyPack_Shipping_Method_Courier_Local_Standard';
                case 'easypack_shipping_courier_local_standard_cod':
                    return 'EasyPack_Shipping_Method_Courier_Local_Standard_COD';
                case 'easypack_shipping_courier_lse':
                    return 'EasyPack_Shipping_Method_Courier_LSE';
                case 'easypack_shipping_courier_lse_cod':
                    return 'EasyPack_Shipping_Method_Courier_LSE_COD';
                case 'easypack_shipping_courier_palette':
                    return 'EasyPack_Shipping_Method_Courier_Palette';
                case 'courier_palette_cod_create_package':
                    return 'EasyPack_Shipping_Method_Courier_Palette_COD';
            }
        }


        /**
         * Inline CSS for button
         *
         * @return void
         */
        public function include_inline_css() {
            add_action(
                'wp_head',
                function () {
                    $custom_button_color = get_option('easypack_custom_button_css' );

                    if ( ! empty( $custom_button_color ) && ! self::$css_embeded ) {

                        $easypack_button_settings_css = '';

                        if( isset( $custom_button_color ) ) {
                            $custom_button_color = sanitize_text_field( $custom_button_color );
                            $easypack_button_settings_css .= '.easypack_show_geowidget {
                                  background:  ' . $custom_button_color . ' !important;
                                }';
                        }
                        self::$css_embeded = true;
                        echo wp_kses( "<style>$easypack_button_settings_css</style>", [ 'style' => [] ] );
                    }
                },
                100
            );
        }

        /**
         * Save data to order meta from action on Edit order page
         *
         * @return void
         */
        public function set_data_to_order_meta( $_post, $id ) {

            $parcel_machine_id   = isset( $_post['parcel_machine_id'] ) ? sanitize_text_field( $_post['parcel_machine_id'] ) : '';
            $parcel_machine_desc = isset( $_post['parcel_machine_desc'] ) ? sanitize_text_field( $_post['parcel_machine_desc'] ) : '';

            if( ! empty( $parcel_machine_id ) ) {
                update_post_meta( $id, '_parcel_machine_id', $parcel_machine_id );
            }
            if( ! empty( $parcel_machine_desc ) ) {
                update_post_meta( $id, '_parcel_machine_desc', $parcel_machine_desc );
            }

            $parcel_length = isset( $_post['parcel_length'] ) ? sanitize_text_field( $_post['parcel_length'] ) : '';
            $parcel_width = isset( $_post['parcel_width'] ) ? sanitize_text_field( $_post['parcel_width'] ) : '';
            $parcel_height = isset( $_post['parcel_height'] ) ? sanitize_text_field( $_post['parcel_height'] ) : '';
            $parcel_weight = isset( $_post['parcel_weight'] ) ? sanitize_text_field( $_post['parcel_weight'] ) : '';
            $parcel_non_standard = isset( $_post['parcel_non_standard'] ) ? sanitize_text_field( $_post['parcel_non_standard'] ) : '';
            $insurance = isset( $_post['insurance_amounts'][0] ) ? sanitize_text_field( $_post['insurance_amounts'][0] ) : '';

            if( ! empty( $parcel_length ) ) {
                update_post_meta( $id, '_easypack_parcel_length', $parcel_length );
            }

            if( ! empty( $parcel_width ) ) {
                update_post_meta( $id, '_easypack_parcel_width', $parcel_width );
            }

            if( ! empty( $parcel_height ) ) {
                update_post_meta( $id, '_easypack_parcel_height', $parcel_height );
            }

            if( ! empty( $parcel_weight ) ) {
                update_post_meta( $id, '_easypack_parcel_weight', $parcel_weight );
            }

            if( ! empty( $insurance ) ) {
                update_post_meta( $id, '_easypack_parcel_insurance', $insurance );
            } else {
                update_post_meta( $id, '_easypack_parcel_insurance',
                    get_option('easypack_insurance_amount_default', '0.00' ) );
            }

            if( ! empty( $parcel_non_standard ) ) {
                update_post_meta( $id, '_easypack_parcel_non_standard', $parcel_non_standard );
            }

            $parcels = isset( $_post['parcel'] ) ? (array) $_post['parcel'] : array();
            $parcels = array_map( 'sanitize_text_field', $parcels );

            $cod_amounts = isset( $_post['cod_amount'] ) ? (array) $_post['cod_amount'] : array();
            $cod_amounts = array_map( 'sanitize_text_field', $cod_amounts );

            $easypack_pacels = [];
            if( ! empty ( $parcels ) ) {

                foreach ( $parcels as $key => $parcel ) {
                    if( isset( $cod_amounts[$key] ) ) {
                        $easypack_pacels[] = [
                            'package_size' => $parcel,
                            'cod_amount' => $cod_amounts[$key]
                        ];
                    } else {
                        $easypack_pacels[] = [
                            'package_size' => $parcel
                        ];
                    }

                }
            }
            update_post_meta( $id, '_easypack_parcels', $easypack_pacels );

            $easypack_ref_number = isset( $_post['reference_number'] ) ? sanitize_text_field( $_POST['reference_number'] ) : $id;
            if( ! empty( $easypack_ref_number ) ) {
                update_post_meta( $id, '_reference_number', $easypack_ref_number );
            }

            $easypack_send_method = isset( $_post['easypack_send_method'] ) ? sanitize_text_field( $_POST['easypack_send_method'] ) : '';
            if( ! empty( $easypack_send_method ) ) {
                update_post_meta( $id, '_easypack_send_method', $easypack_send_method );
            }

            $commercial_product_identifier = isset( $_post['commercial_product_identifier'] ) ? sanitize_text_field( $_POST['commercial_product_identifier'] ) : '';
            if( ! empty( $commercial_product_identifier ) ) {
                update_post_meta( $id, '_commercial_product_identifier', $commercial_product_identifier );
            }
        }


        /**
         * Get save data from order meta if user used "Update order" button
         *
         * @return array
         */
        public function get_courier_parcel_data_from_order_meta( $id ) {
            $data['length'] = '';
            $data['width'] = '';
            $data['height'] = '';
            $data['weight'] = '';
            $data['non_standard'] = '';

            $data['length'] = get_post_meta( $id, '_easypack_parcel_length', true )
                ? get_post_meta( $id, '_easypack_parcel_length', true )
                : '';

            $data['width'] = get_post_meta( $id, '_easypack_parcel_width', true )
                ? get_post_meta( $id, '_easypack_parcel_width', true )
                : '';

            $data['height'] = get_post_meta( $id, '_easypack_parcel_height', true )
                ? get_post_meta( $id, '_easypack_parcel_height', true )
                : '';

            $data['weight'] = get_post_meta( $id, '_easypack_parcel_weight', true )
                ? get_post_meta( $id, '_easypack_parcel_weight', true )
                : '';

            $data['non_standard'] = get_post_meta( $id, '_easypack_parcel_non_standard', true )
                ? get_post_meta( $id, '_easypack_parcel_non_standard', true )
                : 'no';

            return $data;
        }


        public function get_dimensions_for_courier_shipments( $post_id ) {

            if( 'yes' === get_option('easypack_set_default_courier_dimensions') ) {
                $dimensions = get_option('easypack_default_courier_dimensions');
            } else {
                $dimensions = $this->get_courier_parcel_data_from_order_meta( $post_id );
            }

            if( ! empty( $dimensions['length'] )
                && ! empty( $dimensions['width'] )
                && ! empty( $dimensions['height'] )
                && ! empty( $dimensions['weight'] )
            ){
                // if all data was saved in meta
                return $dimensions;
            }

            // otherwise try to get dimension for single product from product settings
            $order = wc_get_order($post_id);
            $items = $order->get_items();

            if ( count($items) > 1 ) {
                return;
            }

            foreach ($order->get_items() as $item_id => $item) {
                $product_id = $item->get_product_id();

                if ($item->get_quantity() > 1) {
                    return;
                }

                $product = wc_get_product( $product_id );
                if( is_object( $product ) && ! is_wp_error( $product ) ) {

                    $dimensions['height'] = !empty($dimensions['height']) ? $dimensions['height'] : (float)$product->get_height() * 10;
                    $dimensions['width'] = !empty($dimensions['width']) ? $dimensions['width'] : (float)$product->get_width() * 10;
                    $dimensions['length'] = !empty($dimensions['length']) ? $dimensions['length'] : (float)$product->get_length() * 10;

                    if (!empty($dimensions['height'])
                        && !empty($dimensions['width'])
                        && !empty($dimensions['length'])) {
                        $dimensions['weight'] = !empty($dimensions['weight'])
                            ? $dimensions['weight']
                            : EasyPack_Helper()->get_order_weight($order);
                    }
                }
            }

            return $dimensions;
        }



        public function is_courier_service_by_id( $shipment_id )
        {

            if (in_array( $shipment_id
                , [
                    'easypack_shipping_courier',
                    'easypack_cod_shipping_courier',
                    'easypack_shipping_courier_local_express',
                    'easypack_shipping_courier_le_cod',
                    'easypack_shipping_courier_local_standard',
                    'easypack_shipping_courier_local_standard_cod',
                    'easypack_shipping_courier_lse',
                    'easypack_shipping_courier_lse_cod',
                    'easypack_shipping_courier_palette',
                    'easypack_shipping_courier_palette_cod'
                ])
            ) {
                return true;
            }

            return false;
        }


        public function get_order_weight( $order ) {
            $weight = 0;
            if ( sizeof( $order->get_items() ) > 0 ) {
                foreach ( $order->get_items() as $item ) {
                    if ( isset( $item['product_id'] ) && $item['product_id'] > 0 ) {
                        if( is_object( $item ) ) {
                            $_product = $item->get_product();
                            if ( ! $_product->is_virtual() ) {
                                $weight += (float) $_product->get_weight() * (int) $item['qty'];
                            }
                        }
                    }
                }
            }

            return $weight;
        }


        public function is_required_pages_for_modal() {
            global $pagenow, $post_type;
            $current_screen = get_current_screen();

            if ('shop_order' === $post_type) {
                if ('post.php' === $pagenow || 'post-new.php' === $pagenow) {
                    return true;
                }
            }

            if ('shop_order_placehold' === $post_type) {
                return true;
            }

            if ( is_a( $current_screen, 'WP_Screen' ) && 'woocommerce_page_wc-orders' === $current_screen->id ) {
                return true;
            }

            if ( is_checkout() ) {
                return true;
            }

            if ( is_a( $current_screen, 'WP_Screen' ) && 'inpost_page_easypack_shipment' === $current_screen->id ) {
                return true;
            }

            if ( is_a( $current_screen, 'WP_Screen' ) && 'woocommerce_page_wc-settings' === $current_screen->id ) {
                if( isset( $_GET['tab'] ) && $_GET['tab'] == 'easypack_general') {
                    return true;
                }

            }

            return false;
        }


        /**
         * @return WC_Shipping_Method[]
         */
        public function get_inpost_methods(): array {

            $configured_shipping_methods = array();

            $delivery_zones = \WC_Shipping_Zones::get_zones();

            foreach ((array) $delivery_zones as $key => $the_zone) {
                if( isset( $the_zone['shipping_methods'] ) ) {
                    foreach ($the_zone['shipping_methods'] as $configured_method) {
                        if ( 0 === strpos( $configured_method->id, 'easypack_') ) {
                            $configured_shipping_methods[$configured_method->instance_id]['user_title'] = $configured_method->title;
                            $configured_shipping_methods[$configured_method->instance_id]['method_title'] = $configured_method->id;
                            $configured_shipping_methods[$configured_method->instance_id]['method_title_with_id'] = $configured_method->id . ':' . $configured_method->instance_id;

                        } elseif( 0 === strpos( $configured_method->id, 'flexible_shipping') ) {
                            // Integration with Flexible Shipping
                            $linked_method = EasyPack_Helper()->get_method_linked_to_fs_by_instance_id( $configured_method->instance_id );
                            if ( 0 === strpos( $linked_method, 'easypack_') ) {
                                $configured_shipping_methods[$configured_method->instance_id]['user_title'] = $configured_method->title;
                                $configured_shipping_methods[$configured_method->instance_id]['method_title'] = $configured_method->id;
                                $configured_shipping_methods[$configured_method->instance_id]['method_title_with_id'] = $configured_method->id . ':' . $configured_method->instance_id;
                            }
                        }
                    }
                }
            }

            return $configured_shipping_methods;

        }


        public function set_order_status_completed( $order_id ) {

            if( get_option( 'easypack_set_order_completed' ) === 'yes') {
                $order = wc_get_order( $order_id );
                if ( $order ) {
                    $current_order_status = $order->get_status();
                    if( 'completed' !== $current_order_status ) {
                        $order->update_status('wc-completed');
                    }
                }
            }
        }


        public function get_parcel_size_from_settings ( $order_id ) {

            $size = 'small';

            $tem_array = [];

            $order = wc_get_order( $order_id );

            $shipping_method = '';

            if( $order && ! is_wp_error($order) ) {

                $shipping_methods = $order->get_shipping_methods();

                if( ! empty($shipping_methods) && is_array( $shipping_methods ) ) {
                    foreach ($shipping_methods as $method ) {
                        $shipping_method = $method->get_method_id();
                    }
                }

                $items = $order->get_items();

                if( ! empty( $items ) ) {

                    foreach ($items as $item) {

                        // Compatibility for woocommerce 3+
                        $product_id = version_compare(WC_VERSION, '3.0', '<') ? $item['product_id'] : $item->get_product_id();

                        $size_from_product = get_post_meta( $product_id, 'woo_inpost_parcel_dimensions', true);

                        if( ! empty($size_from_product) ) {
                            $tem_array[] = $size_from_product;
                        }
                    }
                }
            }

            // define size as biggest value among the products in the order
            if( !empty( $tem_array ) ) {

                $tem_array = array_unique($tem_array);

                if( in_array('large', $tem_array) ) {
                    return 'large';
                } else if( in_array('medium', $tem_array) ) {
                    return 'medium';
                } else if( in_array('small', $tem_array) ) {
                    return 'small';
                }

                // or get size from global settings
            } else {

                if( ! empty($shipping_method) ) {
                    if( 'easypack_shipping_courier_c2c' === $shipping_method
                        || 'easypack_shipping_courier_c2c_cod' === $shipping_method
                    ) {
                        $size = get_option( 'easypack_default_package_size_c2c' );
                    } else {
                        $size = get_option( 'easypack_default_package_size' );
                    }
                } else {
                    $size = get_option( 'easypack_default_package_size' );
                }

            }

            return $size;
        }

	}


endif;