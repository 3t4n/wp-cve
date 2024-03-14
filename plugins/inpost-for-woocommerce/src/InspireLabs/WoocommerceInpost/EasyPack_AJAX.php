<?php

namespace InspireLabs\WoocommerceInpost;

use Exception;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_C2C;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_C2C_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Local_Express;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Local_Express_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Local_Standard;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Local_Standard_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_LSE;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_LSE_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Palette;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Palette_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Parcel_Machines_Economy_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shippng_Parcel_Machines;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shippng_Parcel_Machines_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Parcel_Machines_Weekend;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Parcel_Machines_Economy;

/**
 * EasyPack AJAX
 *
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EasyPack_AJAX' ) ) :

	class EasyPack_AJAX {

		/**
		 * Ajax handler
		 */
		public static function init() {
			add_action( 'wp_ajax_easypack', array( __CLASS__, 'ajax_easypack' ) );
			add_action( 'admin_head', array( __CLASS__, 'wp_footer_easypack_nonce' ) );
		}

		public static function wp_footer_easypack_nonce() {
			?>
            <script type="text/javascript">
                var easypack_nonce = '<?php echo wp_create_nonce( 'easypack_nonce' ); ?>';
            </script>
			<?php
		}

		public static function ajax_easypack() {
			check_ajax_referer( 'easypack_nonce', 'security' );

			if ( isset( $_POST['easypack_action'] ) ) {
				$action = sanitize_text_field( $_POST['easypack_action'] );

				if ( $action == 'dispatch_point' ) {
					self::dispatch_point();
				}
				if ( $action == 'parcel_machines_create_package' ) {
					self::parcel_machines_create_package();
				}
                if ( $action == 'parcel_machines_weekend_create_package' ) {
                    self::parcel_machines_weekend_create_package();
                }
				if ( $action == 'parcel_machines_cancel_package' ) {
					self::parcel_machines_cancel_package();
				}
                if ( $action == 'courier_c2c_create_package_cod' ) {
                    self::courier_c2c_create_package_cod();
                }
                if ( $action == 'parcel_machines_economy' ) {
                    self::parcel_machines_economy_create_package();
                }
                if ( $action == 'parcel_machines_economy_cod' ) {
                    self::parcel_machines_economy_cod_create_package();
                }


				if ( $action == 'parcel_machines_cod_create_package' ) {
					self::parcel_machines_cod_create_package();
				}
				if ( $action == 'courier_create_package' ) {
					self::courier_create_package();
				}
				if ( $action == 'courier_c2c_create_package' ) {
					self::courier_c2c_create_package();
				}
				if ( $action == 'courier_lse_create_package' ) {
					self::courier_lse_create_package();
				}
				if ( $action == 'courier_lse_create_package_cod' ) {
					self::courier_lse_create_package_cod();
				}
				if ( $action == 'courier_local_standard_create_package' ) {
					self::courier_local_standard_create_package();
				}
				if ( $action == 'courier_local_standard_cod_create_package' ) {
					self::courier_local_standard_cod_create_package();
				}
				if ( $action == 'courier_local_express_create_package' ) {
					self::courier_local_express_create_package();
				}
				if ( $action == 'courier_local_express_cod_create_package' ) {
					self::courier_local_express_cod_create_package();
				}
				if ( $action == 'courier_palette_create_package' ) {
					self::courier_palette_create_package();
				}
				if ( $action == 'courier_palette_cod_create_package' ) {
					self::courier_palette_cod_create_package();
				}
				if ( $action == 'courier_cod_create_package' ) {
					self::courier_cod_create_package();
				}
				if ( $action == 'parcel_machines_cod_cancel_package' ) {
					self::parcel_machines_cod_cancel_package();
				}

                if ( $action == 'easypack_create_bulk_labels' ) {

                    if( isset( $_POST['order_ids'] ) ) {

                        $data_string = sanitize_text_field( $_POST['order_ids'] );
                        $order_ids_arr = json_decode( stripslashes( $data_string ), true );

                        // we need validate choosed orders if they already has status which is allowing to get labels
                        $validated_ids = [];
                        foreach( $order_ids_arr as $order_id ) {
                            $easypack_status = get_post_meta( $order_id, '_easypack_parcel_tracking', true );
                            if( ! empty( $easypack_status ) ) {
                                $validated_ids[] = $order_id;
                            }
                        }

                        if( ! empty( $validated_ids ) ) {
                            // this function echo pdf or zip string
                            EasyPack_Helper::EasyPack_Helper()->print_stickers(false, $validated_ids);
                            die;
                        } else {
                            echo json_encode( array( 'details' => __( 'Check your selection.', 'woocommerce-inpost' ) ) );
                            die;
                        }
                    }

                    echo json_encode( array( 'details' => __( 'There are some validation errors.', 'woocommerce-inpost' ) ) );
                    die;
                }

			}
		}

		public static function dispatch_point() {
			$dispatch_point_name = sanitize_text_field( $_POST['dispatch_point_name'] );
			try {
				$dispatch_point = EasyPack_API()->dispatch_point( $dispatch_point_name );
				echo json_encode( $dispatch_point );
			} catch ( Exception $e ) {
				echo 0;
			}
			wp_die();
		}

		public static function parcel_machines_create_package() {
			EasyPack_Shippng_Parcel_Machines::ajax_create_package();
		}

		public static function parcel_machines_cancel_package() {
			EasyPack_Shippng_Parcel_Machines::ajax_cancel_package();
		}

		public static function parcel_machines_cod_create_package() {
			EasyPack_Shippng_Parcel_Machines_COD::ajax_create_package();
		}

        public static function parcel_machines_weekend_create_package() {
            EasyPack_Shipping_Parcel_Machines_Weekend::ajax_create_package();
        }

        public static function parcel_machines_economy_create_package() {
            EasyPack_Shipping_Parcel_Machines_Economy::ajax_create_package();
        }

        public static function parcel_machines_economy_cod_create_package() {
            EasyPack_Shipping_Parcel_Machines_Economy_COD::ajax_create_package();
        }

		/**
		 * @throws \ReflectionException
		 */
		public static function courier_create_package() {
			EasyPack_Shipping_Method_Courier::ajax_create_package();
		}

        /**
         * @throws \ReflectionException
         */
        public static function courier_c2c_create_package_cod() {
            EasyPack_Shipping_Method_Courier_C2C_COD::ajax_create_package();
        }

		/**
		 * @throws \ReflectionException
		 */
		public static function courier_c2c_create_package() {
			EasyPack_Shipping_Method_Courier_C2C::ajax_create_package();
		}

		/**
		 * @throws \ReflectionException
		 */
		public static function courier_lse_create_package() {
			EasyPack_Shipping_Method_Courier_LSE::ajax_create_package();
		}

		/**
		 * @throws \ReflectionException
		 */
		public static function courier_lse_create_package_cod() {
			EasyPack_Shipping_Method_Courier_LSE_COD::ajax_create_package();
		}

		/**
		 * @throws \ReflectionException
		 */
		public static function courier_local_standard_create_package() {
			EasyPack_Shipping_Method_Courier_Local_Standard::ajax_create_package();
		}

		/**
		 * @throws \ReflectionException
		 */
		public static function courier_local_standard_cod_create_package() {
			EasyPack_Shipping_Method_Courier_Local_Standard_COD::ajax_create_package();
		}

		/**
		 * @throws \ReflectionException
		 */
		public static function courier_local_express_create_package() {
			EasyPack_Shipping_Method_Courier_Local_Express::ajax_create_package();
		}

		public static function courier_local_express_cod_create_package() {
			EasyPack_Shipping_Method_Courier_Local_Express_COD::ajax_create_package();
		}

		/**
		 * @throws \ReflectionException
		 */
		public static function courier_palette_create_package() {
			EasyPack_Shipping_Method_Courier_Palette::ajax_create_package();
		}

		/**
		 * @throws \ReflectionException
		 */
		public static function courier_palette_cod_create_package() {
			EasyPack_Shipping_Method_Courier_Palette_COD::ajax_create_package();
		}

		/**
		 * @throws \ReflectionException
		 */
		public static function courier_cod_create_package() {
			EasyPack_Shipping_Method_Courier_COD::ajax_create_package();
		}

		public static function parcel_machines_cod_cancel_package() {
			EasyPack_Shippng_Parcel_Machines_COD::ajax_cancel_package();
		}

//		public static function easypack_dispatch_order() {
//			EasyPack_Shippng_Parcel_Machines::ajax_dispatch_order();
//		}

	}

endif;

