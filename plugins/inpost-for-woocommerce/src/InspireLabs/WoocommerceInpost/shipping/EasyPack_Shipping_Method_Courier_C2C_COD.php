<?php

namespace InspireLabs\WoocommerceInpost\shipping;

use InspireLabs\WoocommerceInpost\EasyPack;
use InspireLabs\WoocommerceInpost\EasyPack_Helper;
use Exception;
use InspireLabs\WoocommerceInpost\EasyPack_API;
use InspireLabs\WoocommerceInpost\Geowidget_v5;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Parcel_Dimensions_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Parcel_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Parcel_Weight_Model;
use ReflectionException;
use InspireLabs\WoocommerceInpost\EmailFilters\TrackingInfoEmail;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'EasyPack_Shipping_Method_Courier_C2C_COD' ) ) {
    class EasyPack_Shipping_Method_Courier_C2C_COD
        extends EasyPack_Shippng_Parcel_Machines {

        const WP_AJAX_ACTION_CREATE = 'courier_c2c_create_package_cod';

        const SERVICE_ID = ShipX_Shipment_Model::SERVICE_INPOST_COURIER_C2C;

        const NONCE_ACTION = self::SERVICE_ID;

        static $review_order_after_shipping_once = false;

        /**
         * Constructor for shipping class
         *
         * @access public
         * @return void
         */
        public function __construct( $instance_id = 0 ) {
            $this->init_form_fields();
            $this->instance_id  = absint( $instance_id );
            $this->supports     = [
                'shipping-zones',
                'instance-settings',
            ];
            $this->id           = 'easypack_shipping_courier_c2c_cod';
            $this->method_title = __( 'InPost Courier C2C COD', 'woocommerce-inpost' );
            $this->init();
        }

        public function generate_rates_html( $key, $data ) {
            $rates = EasyPack_Helper()->get_saved_method_rates($this->id, $this->instance_id);
            ob_start();
            include( 'views/html-rates-courier.php' );

            return ob_get_clean();
        }

        public function init_form_fields() {

            $settings = [
                [
                    'title'       => __( 'General settings', 'woocommerce-inpost' ),
                    'type'        => 'title',
                    'description' => '',
                    'id'          => 'section_general_settings',
                ],
                'logo_upload'        => [
                    'name'  => __( 'Change logo', '' ),
                    'title' => __( 'Upload custom logo', 'woocommerce-inpost' ),
                    'type'  => 'logo_upload',
                    'id'    => 'logo_upload',
                ],
                'title'              => [
                    'title'             => __( 'Method title', 'woocommerce-inpost' ),
                    'type'              => 'text',
                    'default'           => __( 'InPost Courier C2C COD', 'woocommerce-inpost' ),
                    'custom_attributes' => [ 'required' => 'required' ],
                    'desc_tip'          => false,
                ],
                /*'delivery_terms'              => [
                    'title'    => __( 'Terms of delivery', 'woocommerce-inpost' ),
                    'type'     => 'text',
                    'default'  => __( '', 'woocommerce-inpost' ),
                    'desc_tip' => false,
                    'placeholder'       => '(2-3 dni)',
                ],*/
                'free_shipping_cost' => [
                    'title'             => __( 'Free shipping', 'woocommerce-inpost' ),
                    'type'              => 'number',
                    'custom_attributes' => [ 'step' => 'any', 'min' => '0' ],
                    'default'           => '',
                    'desc_tip'          => __( 'Enter the amount of the contract, from which shipping will be free (does not include virtual products).',
                        'woocommerce-inpost' ),
                    'placeholder'       => '0.00',
                ],
                'show_free_shipping_label' => array(
                    'title'       => __( '', 'woocommerce-inpost' ),
                    'label'       => __( 'Add label (free) to the end of title of shipping method', 'woocommerce-inpost' ),
                    'type'        => 'checkbox',
                    'description' => __( '', 'woocommerce-inpost' ),
                    'default'     => 'yes',
                    'desc_tip'    => true,
                ),
                'apply_minimum_order_rule_before_coupon' => array(
                    'title'       => __( 'Coupons discounts', 'woocommerce' ),
                    'label'       => __( 'Apply minimum order rule before coupon discount', 'woocommerce' ),
                    'type'        => 'checkbox',
                    'description' => __( 'If checked, free shipping would be available based on pre-discount order amount.', 'woocommerce' ),
                    'default'     => 'no',
                    'desc_tip'    => true,
                ),
                'flat_rate'          => [
                    'title'   => __( 'Flat rate', 'woocommerce-inpost' ),
                    'type'    => 'checkbox',
                    'label'   => __( 'Set a flat-rate shipping fee for the entire order.', 'woocommerce-inpost' ),
                    'class'   => 'easypack_flat_rate',
                    'default' => 'yes',
                ],
                'cost_per_order'     => [
                    'title'             => __( 'Cost per order', 'woocommerce-inpost' ),
                    'type'              => 'number',
                    'custom_attributes' => [ 'step' => 'any', 'min' => '0' ],
                    'class'             => 'easypack_cost_per_order',
                    'default'           => '',
                    'desc_tip'          => __( 'Set a flat-rate shipping for all orders'
                        , 'woocommerce-inpost' ),
                    'placeholder'       => '0.00',
                ],
                'tax_status' => [
                    'title'   => __( 'Tax status', 'woocommerce' ),
                    'type'    => 'select',
                    'class'   => 'wc-enhanced-select',
                    'default' => 'none',
                    'options' => [
                        'none'    => _x( 'None', 'Tax status', 'woocommerce-inpost' ),
                        'taxable' => __( 'Taxable', 'woocommerce-inpost' ),
                    ],
                ],
                'based_on'           => [
                    'title'    => __( 'Based on', 'woocommerce-inpost' ),
                    'type'     => 'select',
                    'desc_tip' => __( 'Select the method of calculating shipping cost. If the cost of shipping is to be calculated based on the weight of the cart and the products do not have a defined weight, the cost will be calculated incorrectly.',
                        'woocommerce-inpost' ),
                    'description' => sprintf( '<b id="easypack_dimensions_warning" style="color:red;display:none">%1s</b> %1s',
                        __('Attention!', 'woocommerce-inpost'),
                        __('Set the dimension in the settings of each product. The default value is size \'A\'', 'woocommerce-inpost' )

                    ),
                    'class'    => 'wc-enhanced-select easypack_based_on',
                    'options'  => [
                        'price'  => __( 'Price', 'woocommerce-inpost' ),
                        'weight' => __( 'Weight', 'woocommerce-inpost' ),
                        'size'   => __( 'Size (A, B, C)', 'woocommerce-inpost' ),
                    ],
                ],
                'rates'    => [
                    'title'    => '',
                    'type'     => 'rates',
                    'class'    => 'easypack_rates',
                    'default'  => '',
                    'desc_tip' => '',
                ],
                'gabaryt_a'     => [
                    'title'             => __( 'Size A', 'woocommerce-inpost' ),
                    'type'              => 'number',
                    'custom_attributes' => [ 'step' => 'any', 'min' => '0' ],
                    'class'             => 'easypack_gabaryt_a',
                    'default'           => '',
                    'desc_tip'          => __( 'Set a flat-rate shipping for size A', 'woocommerce-inpost' ),
                    'placeholder'       => '0.00',
                ],

                'gabaryt_b'     => [
                    'title'             => __( 'Size B', 'woocommerce-inpost' ),
                    'type'              => 'number',
                    'custom_attributes' => [ 'step' => 'any', 'min' => '0' ],
                    'class'             => 'easypack_gabaryt_b',
                    'default'           => '',
                    'desc_tip'          => __( 'Set a flat-rate shipping for size B', 'woocommerce-inpost' ),
                    'placeholder'       => '0.00',
                ],

                'gabaryt_c'     => [
                    'title'             => __( 'Size C', 'woocommerce-inpost' ),
                    'type'              => 'number',
                    'custom_attributes' => [ 'step' => 'any', 'min' => '0' ],
                    'class'             => 'easypack_gabaryt_c',
                    'default'           => '',
                    'desc_tip'          => __( 'Set a flat-rate shipping for size C', 'woocommerce-inpost' ),
                    'placeholder'       => '0.00',
                ],
            ];

            $settings = $this->add_shipping_classes_settings( $settings );

            $this->form_fields          = $settings;
            $this->instance_form_fields = $settings;
        }


        public function process_admin_options() {
            parent::process_admin_options();
            EasyPack_API()->clear_cache();
        }

        public function save_post( $post_id ) {

            if ( ! isset( $_POST['wp_nonce'] ) ) {
                return;
            }
            // Verify that the nonce is valid.
            if ( ! wp_verify_nonce( $_POST['wp_nonce'], self::NONCE_ACTION ) ) {
                return;
            }
            // If this is an autosave, our form has not been submitted, so we don't want to do anything.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            $status = get_post_meta( $post_id, '_easypack_status', true );

            if ( $status == '' ) {
                $status = 'new';
            }

            if ( $status == 'new' ) {

                EasyPack_Helper()->set_data_to_order_meta( $_POST, $post_id );
            }

        }

        public function order_metabox( $post ) {
            self::order_metabox_content( $post );
        }


        public function woocommerce_review_order_after_shipping() {
            return;
        }

        /**
         * @param unknown $package
         *
         */
        public function calculate_shipping_table_rate( $package ) {

            // based on gabaryt
            if ( $this->based_on == 'size' ) {

                $max_gabaryt = $this->get_max_gabaryt( $package );
                $cost = $this->instance_settings[ 'gabaryt_' . $max_gabaryt ];

                $add_rate = [
                    'id'    => $this->get_rate_id(),
                    'label' => $this->title,
                    'cost'  => $cost,
                    'package' => $package,
                ];
                $this->add_rate( $add_rate );

                return;
            }

            $rates = EasyPack_Helper()->get_saved_method_rates($this->id, $this->instance_id);
            foreach ( $rates as $key => $rate ) {
                if ( empty( $rates[ $key ]['min'] ) || trim( $rates[ $key ]['min'] ) == '' ) {
                    $rates[ $key ]['min'] = 0;
                }
                if ( empty( $rates[ $key ]['max'] ) || trim( $rates[ $key ]['max'] ) == '' ) {
                    $rates[ $key ]['max'] = PHP_INT_MAX;
                }
            }
            $value = 0;
            if ( $this->based_on == 'price' ) {
                $value = $this->package_subtotal( $package['contents'] );
            }
            if ( $this->based_on == 'weight' ) {
                $value = $this->package_weight( $package['contents'] );
            }
            foreach ( $rates as $rate ) {
                if ( floatval( $rate['min'] ) <= $value && floatval( $rate['max'] ) >= $value ) {
                    $cost = 0;
                    if ( isset( $rate['percent'] ) && floatval( $rate['percent'] ) != 0 ) {
                        $cost = $package['contents_cost'] * ( floatval( $rate['percent'] ) / 100 );
                    }
                    $cost     = $cost + floatval( $rate['cost'] );
                    $add_rate = [
                        'id'    => $this->get_rate_id(),
                        'label' => $this->title,
                        'cost'  => $cost,
                    ];
                    $this->add_rate( $add_rate );

                    return;
                }
            }
        }

        /**
         * @return ShipX_Shipment_Model
         */
        public static function ajax_create_shipment_model() {
            $shipmentService = EasyPack::EasyPack()->get_shipment_service();

            $order_id = sanitize_text_field( $_POST['order_id'] );
            $order = wc_get_order( $order_id );
            $order_amount = '';
            if( is_object( $order ) && ! is_wp_error( $order ) ) {
                $order_amount = $order->get_total();
            }

            $insurance_amount = '';
            $reference_number = '';
            $send_method = '';
            $parcels = [];

            // if Bulk create shipments
            if( isset( $_POST['action']) && $_POST['action'] === 'easypack_bulk_create_shipments' ) {

                $parcels = get_post_meta( $order_id, '_easypack_parcels', true )
                    ? get_post_meta( $order_id, '_easypack_parcels', true )
                    : array( Easypack_Helper()->get_parcel_size_from_settings( $order_id ) );

                $cod_amount = isset( $parcels[0]['cod_amount'] )
                    ? $parcels[0]['cod_amount']
                    : $order_amount;

                $insurance_amount = get_post_meta( $order_id, '_easypack_parcel_insurance', true )
                    ? get_post_meta( $order_id, '_easypack_parcel_insurance', true )
                    : floatval( get_option('easypack_insurance_amount_default') );

                $reference_number = get_post_meta( $order_id, '_reference_number', true )
                    ? get_post_meta( $order_id, '_reference_number', true )
                    : $order_id;

                $send_method = get_post_meta( $order_id, '_easypack_send_method', true )
                    ? get_post_meta( $order_id, '_easypack_send_method', true )
                    : get_option( 'easypack_default_send_method' );

            } else {

                if ( ! isset( $_POST['insurance_amounts'] ) || $_POST['insurance_amounts'][0] === '0' ) {
                    $insurance_amounts = [ null ];
                } else {
                    if( is_array( $_POST['insurance_amounts'] ) ) {
                        $insurance_amounts = array_map('sanitize_text_field', $_POST['insurance_amounts']);
                    }
                }

                $cod_amounts = isset( $_POST['cod_amounts'] )
                    ? array_map( 'sanitize_text_field', $_POST['cod_amounts'] )
                    : null;
                $cod_amount = isset( $cod_amounts[0] ) ? $cod_amounts[0] : $order_amount;

                $insurance_amount = isset( $insurance_amounts[0] )
                    ? $insurance_amounts[0]
                    : get_option( 'easypack_insurance_amount_default', null );

                $send_method = isset( $_POST['send_method'] )
                    ? sanitize_text_field( $_POST['send_method'] )
                    : 'parcel_machine';

                $reference_number = isset( $_POST['reference_number'] )
                    ? sanitize_text_field( $_POST['reference_number'] )
                    : $order_id;

                $parcels = isset( $_POST['parcels'] )
                    ? array_map( 'sanitize_text_field', $_POST['parcels'] )
                    : array( get_option( 'easypack_default_package_size_c2c' ) );
            }

            $shipment = $shipmentService->create_shipment_object_by_shiping_data(
                $parcels,
                (int) $order_id,
                $send_method,
                self::SERVICE_ID,
                [],
                null,
                $cod_amount,
                $insurance_amount,
                $reference_number,
                null

            );
            $shipment->getInternalData()->setOrderId( (int) $order_id );

            return $shipment;
        }

        /**
         * @param bool $courier
         *
         * @throws ReflectionException
         */
        public static function ajax_create_package( $courier = false ) {
            $ret = [ 'status' => 'ok' ];

            $shipment_model = self::ajax_create_shipment_model();

            $order_id         = $shipment_model->getInternalData()->getOrderId();
            $shipment_service = EasyPack::EasyPack()->get_shipment_service();
            $shipment_array   = $shipment_service->shipment_to_array( $shipment_model );
            $status_service   = EasyPack::EasyPack()->get_shipment_status_service();
            $label_url        = '';

            try {
                update_post_meta( $shipment_model->getInternalData()->getOrderId(),
                    '_easypack_parcel_create_args',
                    $shipment_array );
					
				$order = wc_get_order( $order_id );

                if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                    if ( $order && !is_wp_error($order) ) {
                        $order->update_meta_data( '_easypack_parcel_create_args', $shipment_array );
                        $order->save();
                    }
                }

                $response = EasyPack_API()->customer_parcel_create( $shipment_array );

                $internal_data = $shipment_model->getInternalData();
                $internal_data->setInpostId( $response['id'] );
                $internal_data->setStatus( $response['status'] );
                $internal_data->setStatusTitle( $status_service->getStatusTitle( $response['status'] ) );
                $internal_data->setStatusDescription( $status_service->getStatusDescription( $response['status'] ) );
                $internal_data->setStatusChangedTimestamp( time() );
                $internal_data->setCreatedAt( time() );
                $internal_data->setUrl( $response['href'] );
                $shipment_model->setInternalData( $internal_data );

                $search_in_api 	= EasyPack_API()->customer_parcel_get_by_id( $shipment_model->getInternalData()->getInpostId() );

                $label_url = null;
                $tracking_for_email = '';

                /*for ( $i = 0; $i < 10; $i ++ ) {
                    sleep( 1 );
                    $label_url 	= self::ajax_parcel_machines_get_stickers_url( $shipment_model );
                    if ( ! empty( $label_url ) ) {
                        break;
                    }

                     //{"status":400,"error":"invalid_action","message":"Action (get_label) can not be taken on shipment with status (created).","details":{"action":"get_label","shipment_status":"created","shipment_id":53256}}

                }*/

                for ( $i = 0; $i < 3; $i ++ ) {
                    sleep( 1 );
                    $search_in_api = EasyPack_API()->customer_parcel_get_by_id( $shipment_model->getInternalData()->getInpostId() );
                    if ( isset( $search_in_api['items'][0]['tracking_number'] ) ) {
                        $shipment_model->getInternalData()->setTrackingNumber( $search_in_api['items'][0]['tracking_number'] );
                        break;
                    }
                    if ( isset( $search_in_api['parcels'][0]['tracking_number'] ) ) {
                        $tracking_for_email = $search_in_api['parcels'][0]['tracking_number'];
                        $shipment_model->getInternalData()->setTrackingNumber( $search_in_api['parcels'][0]['tracking_number'] );
                        break;
                    }
                }

                $internal_data = $shipment_model->getInternalData();
                $internal_data->setLabelUrl( $label_url );
                $shipment_model->setInternalData( $internal_data );

                update_post_meta( $order_id, '_easypack_status', 'created' );
				
				if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                    if ( $order && !is_wp_error($order) ) {
                        $order->update_meta_data( '_easypack_status', 'created' );
                        $order->save();
                    }
                }
				
                if(! empty( $tracking_for_email ) ) {
                    update_post_meta( $order_id, '_easypack_parcel_tracking', sanitize_text_field( $tracking_for_email ) );
					
					if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                        if ( $order && !is_wp_error($order) ) {
                            $order->update_meta_data( '_easypack_parcel_tracking', $tracking_for_email );
                            $order->save();
                        }
                    }
                }

                //zapisz koszt przesyłki do przesyłki
                $price_calculator = EasyPack()->get_shipment_price_calculator_service();
                $shipment_service->update_shipment_to_db( $shipment_model );

            } catch ( Exception $e ) {
                $ret['status'] = 'error';
                $ret['message'] = __( 'There are some errors. Please fix it: <br>', 'woocommerce-inpost' ) . EasyPack_API()->translate_error( $e->getMessage() );
            }

            if ( $ret['status'] == 'ok' ) {
                $order = wc_get_order( $order_id );
                $tracking_url = EasyPack_Helper()->get_tracking_url();

                $order->add_order_note(
                    __( 'Shipment created', 'woocommerce-inpost' ), false
                );

                EasyPack_Helper()->set_order_status_completed( $order_id );

                if( isset( $_POST['action']) && $_POST['action'] === 'easypack_bulk_create_shipments' ) {
                    if( $tracking_for_email ) {
                        $ret['tracking_number'] = $tracking_for_email;
                    } else {
                        $ret['api_status'] = $status_service->getStatusDescription( $response['status'] );
                    }
                } else {
                    $ret['content'] = self::order_metabox_content( get_post( $order_id ), false, $shipment_model );
                }

                // send email to buyer with tracking details
                ( new TrackingInfoEmail() )->send_tracking_info_email( $order, $tracking_url,  $tracking_for_email );
            }
            echo json_encode( $ret );
            wp_die();
        }


        /**
         * @param                           $post
         * @param bool $output
         * @param ShipX_Shipment_Model|null $shipment
         *
         * @return string
         * @throws Exception
         */
        public static function order_metabox_content(
            $post,
            $output = true,
            $shipment = null
        ) {

            if ( ! $output ) {
                ob_start();
            }
            $shipment_service = EasyPack::EasyPack()->get_shipment_service();

            if ( is_a( $post, 'WC_Order' ) ) {
                $order_id = $post->get_id();
            } else {
                $order_id = $post->ID;
            }

            $geowidget_config = ( new Geowidget_v5() )->get_pickup_delivery_configuration( 'easypack_shipping_courier_c2c' );

            if ( false === $shipment instanceof ShipX_Shipment_Model ) {
                $shipment = $shipment_service->get_shipment_by_order_id( $order_id );
            }

            if ( $shipment instanceof ShipX_Shipment_Model
                && false === $shipment_service->is_shipment_match_to_current_api( $shipment )
            ) {
                wp_nonce_field( self::NONCE_ACTION, 'wp_nonce' );
                $wrong_api_env = true;
                include( 'views/html-order-matabox-parcel-courier-c2c_cod.php' );
                if ( ! $output ) {
                    $out = ob_get_clean();

                    return $out;
                }

                return '';
            }
            $wrong_api_env = false;

            $order = wc_get_order( $order_id );
            /**
             * id, template, dimensions, weight, tracking_number, is_not_standard
             */

            if ( null !== $shipment ) {
                $parcels      = $shipment->getParcels();
                $tracking_url = $shipment->getInternalData()->getTrackingNumber();
                $stickers_url = $shipment->getInternalData()->getLabelUrl();

                if ( true === $output ) {
                    $status_srv = EasyPack()->get_shipment_status_service();
                    $status_srv->refreshStatus( $shipment );
                }

                $status            = $shipment->getInternalData()->getStatus();
                $parcel_machine_id = $shipment->getCustomAttributes()->getTargetPoint();
                $send_method       = $shipment->getCustomAttributes()->getSendingMethod();
                $disabled          = true;
            } else {
                $package_sizes_display = EasyPack()->get_package_sizes_display();
                $parcels = [];
                $parcel  = new ShipX_Shipment_Parcel_Model();
                $parcel->setTemplate( get_option( 'easypack_default_package_size', 'small' ) );
                $parcels[] = $parcel;

                $parcel_machine_from_order = $order->get_meta( '_parcel_machine_id', $order_id );
                $parcel_machine_id = ! empty( $parcel_machine_from_order )
                    ? $parcel_machine_from_order
                    : get_option( 'easypack_default_machine_id' );

                $tracking_url = false;
                $status       = 'new';
                $send_method  = get_option( 'easypack_default_send_method', 'parcel_machine' );
                $disabled     = false;
            }
            $package_sizes = EasyPack()->get_package_sizes_xlarge();

            $send_method_disabled = false;
            $send_methods         = [
                'pop'            => __( 'POP', 'woocommerce-inpost' ),
                'parcel_machine' => __( 'Parcel locker', 'woocommerce-inpost' ),
				'courier' => __( 'Courier', 'woocommerce-inpost' ),
            ];

            $selected_service = $shipment_service->get_customer_service_name_by_id( self::SERVICE_ID );
            include( 'views/html-order-matabox-parcel-courier-c2c_cod.php' );


            wp_nonce_field( self::NONCE_ACTION, 'wp_nonce' );
            if ( ! $output ) {
                $out = ob_get_clean();

                return $out;
            }
        }

    }
}
