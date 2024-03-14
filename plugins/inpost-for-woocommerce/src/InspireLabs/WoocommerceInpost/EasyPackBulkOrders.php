<?php


namespace InspireLabs\WoocommerceInpost;

use Automattic\WooCommerce\Utilities\OrderUtil;
use WC_Order;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Parcel_Model;
use InspireLabs\WoocommerceInpost\EasyPack;
use InspireLabs\WoocommerceInpost\EasyPack_Helper;

use InspireLabs\WoocommerceInpost\EmailFilters\NewOrderEmail;
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
use InspireLabs\WoocommerceInpost\shipping\Easypack_Shipping_Rates;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shippng_Parcel_Machines;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shippng_Parcel_Machines_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Parcel_Machines_Weekend;
use InspireLabs\WoocommerceInpost\shipx\services\courier_pickup\ShipX_Courier_Pickup_Service;
use InspireLabs\WoocommerceInpost\shipx\services\organization\ShipX_Organization_Service;
use InspireLabs\WoocommerceInpost\shipx\services\shipment\ShipX_Shipment_Price_Calculator_Service;
use InspireLabs\WoocommerceInpost\shipx\services\shipment\ShipX_Shipment_Service;
use InspireLabs\WoocommerceInpost\shipx\services\shipment\ShipX_Shipment_Status_Service;

use WC_Shipping_Method;


class EasyPackBulkOrders
{

    public function hooks() {
        add_filter( 'bulk_actions-edit-shop_order', array( $this, 'easypack_register_bulk_action' ) );
        add_filter( 'bulk_actions-woocommerce_page_wc-orders', array( $this, 'easypack_register_bulk_action' ) );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ], 75 );
        add_filter( 'manage_woocommerce_page_wc-orders_columns', [ $this, 'manage_edit_shop_order_columns' ], 11 );
        add_filter( 'manage_edit-shop_order_columns', [ $this, 'manage_edit_shop_order_columns' ], 11 );

        add_action( 'wp_ajax_easypack_bulk_create_shipments', array( $this, 'easypack_bulk_create_shipments_callback' ) );
        add_action( 'manage_shop_order_posts_custom_column', [ $this, 'manage_shop_order_posts_custom_column' ], 11, 2 );
        add_action( 'manage_woocommerce_page_wc-orders_custom_column', [ $this, 'manage_shop_order_posts_custom_column' ], 11, 2 );
    }


    function easypack_register_bulk_action( $bulk_actions ) {
        $bulk_actions[ 'easypack_bulk_create_shipments' ] = __( 'InPost PL create shipments', 'woocommerce-inpost' );
        $bulk_actions[ 'easypack_bulk_create_labels' ] = __( 'InPost PL get labels', 'woocommerce-inpost' );
        $bulk_actions[ 'easypack_bulk_create_shipments_then_labels' ] = __( 'InPost PL create shipments & labels', 'woocommerce-inpost' );
        return $bulk_actions;
    }


    /**
     * @param array $columns .
     *
     * @return array
     */
    public function manage_edit_shop_order_columns( $columns ) {
        if ( isset( $columns['easypack_shipping_statuses'] ) ) {
            return $columns;
        }

        $ret = [];

        $col_added = false;

        foreach ( $columns as $key => $column ) {
            if ( ! $col_added && in_array( $key, [ 'order_actions', 'wc_actions' ], true ) ) {
                $ret['easypack_shipping_statuses'] = __( 'Inpost status', 'woocommerce-inpost' );
                $col_added                = true;
            }
            $ret[ $key ] = $column;
        }

        if ( ! $col_added ) {
            $ret['easypack_shipping_statuses'] = __( 'Inpost status', 'woocommerce-inpost' );
        }

        return $ret;
    }


    /**
     * @param string                $column .
     * @param int|\WP_Post|WC_Order $post   .
     *
     * @return void
     */
    public function manage_shop_order_posts_custom_column( string $column, $post_id ) {
        if ( 'easypack_shipping_statuses' !== $column ) {
            return;
        }

        $inpost_status = '';

        $order = wc_get_order( $post_id );

        if( $order ) {

            foreach ( $order->get_shipping_methods() as $shipping_method ) {
                // if order was made via Flexible Shipping
                $fs_method_name =  get_post_meta( $post_id, '_fs_easypack_method_name', true );

                if ( 0 === strpos( $shipping_method->get_method_id(), 'easypack_' )
                    || 0 === strpos( $fs_method_name, 'easypack_' )
                ) {

                    $status = get_post_meta( $post_id, '_easypack_status', true );

                    //if ( OrderUtil::custom_orders_table_usage_is_enabled() && ! $status ) {
                    if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                        // HPOS usage is enabled.
                        if ( is_a( $post_id, 'WC_Order' ) ) {
                            $post_id = $post_id->get_id();
                            $status = isset( get_post_meta( $post_id )['_easypack_status'][0] )
                                ? get_post_meta( $post_id )['_easypack_status'][0]
                                : null;
                        }
                    }

                    if( ! empty( $status ) ) {

                        $tracking_url = EasyPack_Helper()->get_tracking_url();
                        $tracking_number = get_post_meta( $post_id, '_easypack_parcel_tracking', true );


                        if(  empty( $tracking_number ) ) {
                            $shipment = $order->get_meta('_shipx_shipment_object');
							
							//if ( ! $shipment &&  OrderUtil::custom_orders_table_usage_is_enabled() ) {
							if ( ! $shipment &&  'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                                $from_order_meta_raw = isset( get_post_meta( $post_id )['_shipx_shipment_object'][0] )
                                    ? get_post_meta( $post_id )['_shipx_shipment_object'][0]
                                    : '';

                                if( !empty( $from_order_meta_raw ) ) {
                                    $shipment = unserialize( $from_order_meta_raw );
                                }
                            }
							
                            if( is_object( $shipment ) && $shipment instanceof ShipX_Shipment_Model ) {
                                $tracking_number = $shipment->getInternalData()->getTrackingNumber();
                                if( ! empty( $tracking_number ) ) {
                                    update_post_meta( $post_id, '_easypack_parcel_tracking', sanitize_text_field( $tracking_number ) );
                                }
                            }
                        }
                        if( ! empty( $tracking_number ) ) {
                            $print_label_icon = sprintf(
                                        '<a href="#" target="_blank" data-id="%s" class="get_sticker_action_orders">
                                                <span 
                                                title="%s" 
                                                data-id="%s"
                                                class="dashicons dashicons-media-spreadsheet%s"></span>
                                                </a>',
                                                $post_id,
                                                __( 'Print sticker', 'woocommerce-inpost' ),
                                                $post_id,
                                                ''
                                        );

                            $link_to_tracking = sprintf( '<a target="_blank" href="%s">%s</a>',
                                $tracking_url . $tracking_number,
                                $tracking_number );

                            $inpost_status = '<div class="inpost-status-inside-td">'
                                            . $print_label_icon . ' ' . $shipping_method->get_method_title() . ' ' . $link_to_tracking
                                            . '</div>';

                        } else {



                            $shipment_service = EasyPack::EasyPack()->get_shipment_service();
                            if( is_object( $shipment_service ) ) {
                                $shipment = $shipment_service->get_shipment_by_order_id( $post_id );
                                if( is_object( $shipment ) && is_object( $shipment->getInternalData() ) ) {

                                    if( 'offer_selected' === $shipment->getInternalData()->getStatus() ) {

                                        $inpost_status = '<div class="inpost-status-inside-td">'
                                            . _e( 'The package has not been created! You do not have funds in your Parcel Manager account or a contract for InPost services.', 'woocommerce-inpost' )
                                            . ' ('
                                            . $shipping_method->get_method_title()
                                            . ')'
                                            . '</div>';

                                    } else {
                                        $status_desc = $shipment->getInternalData()->getStatusDescription();

                                        $inpost_status = '<div class="inpost-status-inside-td">'
                                            . $status_desc
                                            . ' ('
                                            . $shipping_method->get_method_title()
                                            . ')'
                                            . '</div>';
                                    }
                                }
                            }
                        }
                    } else {

                        if( EasyPack_Helper()->is_courier_service_by_id( $shipping_method->get_method_id() ) ) {

                            $dimensions = EasyPack_Helper()->get_dimensions_for_courier_shipments( $post_id );

                            if( empty( $dimensions['weight'] ) )
                            {
                                $inpost_status = '<div class="inpost-status-inside-td easypack-alert-status">'
                                    . __( 'Adding of dimensions is required', 'woocommerce-inpost' )
                                    . ' ('
                                    . $shipping_method->get_method_title()
                                    . ')'
                                    . '</div>';
                            } else {
								
								
								$inpost_status = '<div class="inpost-status-inside-td">'
                                . __( 'Not created yet', 'woocommerce-inpost' )
                                . ' ('
                                . $shipping_method->get_method_title()
                                . ')'
                                . '</div>';
							}

                        } else {
                            $inpost_status = '<div class="inpost-status-inside-td">'
                                . __( 'Not created yet', 'woocommerce-inpost' )
                                . ' ('
                                . $shipping_method->get_method_title()
                                . ')'
                                . '</div>';
                        }
                    }
                }
            }
        }

        echo $inpost_status;


    }


    public function easypack_bulk_create_shipments_callback()
    {
        if ( ! is_admin() ) exit;

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['nonce'], 'easypack-bulk-actions' ) ) {
            $return_content = array( 'status' => 'bad', 'message' => __( 'Bad nonce', 'woocommerce-inpost' ) );
            echo json_encode( $return_content );
            exit;
        }

        if ( ! isset( $_POST['order_id'] ) ) {

            $return_content = array( 'status' => 'bad', 'message' => __( 'No orders selected', 'woocommerce-inpost' ) );
            echo json_encode( $return_content );
            exit;

        } else {

            $order_id = sanitize_text_field( $_POST['order_id'] );

            $status = get_post_meta( $order_id, '_easypack_status', true );

            if( ! empty( $status ) ) {
                $return_content = array( 'status' => 'already_created', 'message' => __( 'Shipment already created', 'woocommerce-inpost' ) );
                echo json_encode( $return_content );
                exit;

            } else {

                // detect InPost shipping class we need for each order
                $service = '';
                $order = wc_get_order( $order_id );
                foreach( $order->get_items( 'shipping' ) as $item_id => $item ) {
                    $item_data = $item->get_data();
                    $service = $item_data['method_id'];
                }

                $fs_method_name =  get_post_meta( $order_id, '_fs_easypack_method_name', true );

                $is_any_inpost_method = ! empty( $service ) &&  0 === strpos( $service, 'easypack_');
                $is_inpost_method_linked_via_flexible_shipping = ! empty( $service ) &&  0 === strpos( $fs_method_name, 'easypack_');
                if( $is_inpost_method_linked_via_flexible_shipping ) {
                    // use InPost method name linked to FS from metadata
                    $service = $fs_method_name;
                }


                if( $is_any_inpost_method || $is_inpost_method_linked_via_flexible_shipping ) {

                    $shipping_method_class_name = EasyPack_Helper()->get_class_name_by_shipping_id( $service );

                    $class_with_namespace = 'InspireLabs\WoocommerceInpost\shipping\\' . $shipping_method_class_name;

                    if( class_exists( $class_with_namespace ) ) {
                        $class_instance = new $class_with_namespace();
                        $class_instance::ajax_create_package();

                    } else {
						$return_content = array( 'status' => 'bad', 'message' => __( 'Order was placed with not InPost shipping method', 'woocommerce-inpost' ) );
						echo json_encode( $return_content );
						exit;
					}

                } else {
                    $return_content = array('status' => 'bad', 'message' => __( 'Order was placed with not InPost shipping method', 'woocommerce-inpost' ) );
                    echo json_encode($return_content);
                    exit;
                }
            }
        }

    }


    function enqueue_admin_scripts() {

        $current_screen = get_current_screen();
        // only on order's list page
        if ( is_a( $current_screen, 'WP_Screen' ) && 'edit-shop_order' === $current_screen->id
            || 'woocommerce_page_wc-orders' === $current_screen->id ) {
            $plugin_data = new EasyPack();

            wp_enqueue_style('easypack-bulk-actions', $plugin_data->getPluginCss() . 'easypack-bulk-actions.css');
            wp_enqueue_script('easypack-bulk-actions', $plugin_data->getPluginJs() . 'bulk-actions.js', ['jquery']);
            wp_localize_script(
                'easypack-bulk-actions',
                'easypack_bulk',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('easypack-bulk-actions')
                )
            );
        }
    }



}