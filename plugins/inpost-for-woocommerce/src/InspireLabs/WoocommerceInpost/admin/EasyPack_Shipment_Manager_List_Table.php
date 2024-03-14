<?php

namespace InspireLabs\WoocommerceInpost\admin;

use Automattic\WooCommerce\Utilities\OrderUtil;
use InspireLabs\WoocommerceInpost\EasyPack;
use InspireLabs\WoocommerceInpost\EasyPack_Helper;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model;
use InspireLabs\WoocommerceInpost\shipx\services\shipment\ShipX_Shipment_Status_Service;
use WP_List_Table;
use WP_Query;

/**
 * EasyPack Shipment Manager List Table
 *
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EasyPack_Shipment_Manager_List_Table' ) ) :

	if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	/**
	 * EasyPack_Shipment_Manager_List_Table
	 */
	class EasyPack_Shipment_Manager_List_Table extends WP_List_Table {

		protected $data = [];
		protected $found_data = [];

		function __construct( $send_method ) {
			parent::__construct();

            //if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
            if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                // HPOS usage is enabled.
                $this->get_data_wc_orders();

            } else {

                $this->get_data_post_orders();
            }

		}

		private function getActions( ShipX_Shipment_Model $shipment ) {
			if ( null === $shipment->getInternalData()->getTrackingNumber() ) {
				return '';
			}

			$return = sprintf(
				'<a href="#" target="_blank" data-id="%s" class="get_sticker_action">
                    <span 
                    title="%s" 
                    class="dashicons dashicons-media-spreadsheet%s"></span>
                    </a>',
				$shipment->getInternalData()->getOrderId(),
				__( 'Print sticker', 'woocommerce-inpost' ),
				'',
				''
			);

			$shipmentSrv = EasyPack()->get_shipment_service();
			if ( true === $shipmentSrv->is_courier_service( $shipment ) ) {
				// Return label is available only for courier services.
				$return .= sprintf(
					'<a href="#" target="_blank" data-id="%s" class="get_sticker_return_action">
                    <span 
                    title="%s" 
                    class="dashicons dashicons-media-spreadsheet%s"></span>
                    </a>',
					$shipment->getInternalData()->getOrderId(),
					__( 'Print return sticker', 'woocommerce-inpost' ),
					'',
					''
				);
			}

			$return .= sprintf(
				'<br><a href="#" data-target="status_history_modal_%s" data-toggle="modal">Historia statusów</a>
                        <div id="status_history_modal_%s" class="modal">
                          <div class="modal-window small">
                              <p>%s</p>
                          </div>
                        </div>',
				$shipment->getInternalData()->getInpostId(),
				$shipment->getInternalData()->getInpostId(),

				EasyPack()->get_shipment_status_service()->formatStatusHistory( $shipment->getInternalData() )
			);

			return $return;
		}

		/**
		 * @param ShipX_Shipment_Model $shipment
		 *
		 * @return bool
		 */
		private function isShipmentMatchedToFilters( ShipX_Shipment_Model $shipment ) {
			$send_method = EasyPack_Shipment_Manager::getSendingMethodFilterFromRequest();

			if ( ( null !== $send_method && 'any' !== $send_method )
				&& $send_method !== $shipment->getCustomAttributes()->getSendingMethod()
			) {

				return false;
			}

			$status = EasyPack_Shipment_Manager::getStatusFilterFromRequest();

			if ( ( null !== $status && 'any' !== $status )
				&& $status !== $shipment->getInternalData()->getStatus()
			) {
				return false;
			}

			$service = EasyPack_Shipment_Manager::getServiceFilterFromRequest();

			if ( ( null !== $service && 'any' !== $service )
				&& $service !== $shipment->getService()
			) {
				return false;
			}


			$tracking_number = EasyPack_Shipment_Manager::getTrackingNumberFilterFromRequest();

			if ( null !== $tracking_number
				&& $tracking_number !== $shipment->getInternalData()->getTrackingNumber()
			) {
				return false;
			}

			$order_id = EasyPack_Shipment_Manager::getOrderIdFilterFromRequest();


			if ( null !== $order_id
				&& $order_id !== $shipment->getInternalData()->getOrderId()
			) {
				return false;
			}

			$reference_number = EasyPack_Shipment_Manager::getReferenceNumberFilterFromRequest();

			if ( null !== $reference_number
				&& (string) $reference_number
				   !== (string) $shipment->getReference()
			) {
				return false;
			}

			$receiver_email = EasyPack_Shipment_Manager::getReceiverEmailFilterFromRequest();

			if ( null !== $receiver_email
				&& $receiver_email !== $shipment->getReceiver()->getEmail()
			) {
				return false;
			}

			$receiver_phone = EasyPack_Shipment_Manager::getReceiverPhoneFilterFromRequest();

			if ( null !== $receiver_phone
				&& $receiver_phone !== $shipment->getReceiver()->getPhone()
			) {
				return false;
			}

			return true;
		}


		/**
		 * @param string $method
		 *
		 * @return string
		 */
		private function translateSendingMethod( $method ) {
			switch ( $method ) {
				case ShipX_Shipment_Model::SENDING_METHOD_PARCEL_LOCKER
				     === $method;
					return __( 'Parcel Locker', 'woocommerce-inpost' );

				case ShipX_Shipment_Model::SENDING_METHOD_DISPATCH_ORDER
				     === $method;
					return __( 'Courier', 'woocommerce-inpost' );

				case ShipX_Shipment_Model::SENDING_METHOD_POP
				     === $method;
					return __( 'POP', 'woocommerce-inpost' );
			}
		}

		function column_cb( $item ) {
			/**
			 * @var ShipX_Shipment_Model $shipment
			 */
            $shipment = $item['shipment'];

            if( is_object( $shipment ) ) {

                $srv = EasyPack()->get_shipment_service();

                return sprintf(
                    '<input 
							data-allow_return_stickers="%s"
                            data-status="%s"
                            class="easypack_parcel" 
                            type="checkbox" 
                            name="easypack_parcel[]" 
                            value="%s" />',
                    //$shipment->isCourier() ? '0' : '1',
                    $srv->is_courier_service($shipment) ? '1' : '0',
                    $shipment->getInternalData()->getStatus(),
                    $item['order_id']
                );
            }
		}

		function column_order( $item ) {
			$link = '<a href="' . admin_url( 'post.php?post=' . $item['order'] . '&action=edit' ) . '" >';
			$link .= '#' . $item['order'];
			$link .= '</a>';

			return $link;
		}

		/**
		 * @param array  $item
		 * @param string $column_name
		 *
		 * @return mixed|void
		 */
		function column_default( $item, $column_name ) {
			switch ( $column_name ) {
				case 'package_number':
				case 'send_method':
				case 'send_method_display':
				case 'status':
				case 'dispatch_order_status':
				case 'dispatch_point_name':
				case 'order':
				case 'shipping_address':
				case 'service':
				case 'attributes':
				case 'cod':
				case 'reference_number':
				case 'inpost_id':
				case 'actions':
				case 'created_timestamp':
				case 'status_timestamp':
					return $item[ $column_name ];
				default:
					print_r( $item,
						true ); //Show the whole array for troubleshooting purposes
			}
		}

		/**
		 * @return array
		 */
		function get_columns() {
			$columns = [
				'cb'                  => '<input type="checkbox" />',
				'package_number'      => __( 'Tracking number', 'woocommerce-inpost' ),
				'order'               => __( 'Order ID', 'woocommerce-inpost' ),
				'inpost_id'           => __( 'Inpost ID', 'woocommerce-inpost' ),
				'created_timestamp'   => __( 'Date created', 'woocommerce-inpost' ),
				'reference_number'    => __( 'Reference number', 'woocommerce-inpost' ),
				'status'              => __( 'Status', 'woocommerce-inpost' )
				                         . $this->get_refresh_statuses_btn(),
				'status_timestamp'    => __( 'Status change date', 'woocommerce-inpost' ),
				'service'             => __( 'Service', 'woocommerce-inpost' ),
				'attributes'          => __( 'Dimensions', 'woocommerce-inpost' ),
				'cod'                 => __( 'COD', 'woocommerce-inpost' ),
				'send_method_display' => __( 'Send method', 'woocommerce-inpost' ),
				'shipping_address'    => __( 'Shipping address', 'woocommerce-inpost' ),

			];

			if ( EasyPack_Shipment_Manager::is_courier_context() ) {
				$columns['dispatch_order_status'] = __( 'Dispatch order status', 'woocommerce-inpost' );
				$columns['dispatch_point_name'] = __( 'Dispatch point name', 'woocommerce-inpost' );
			}

			$columns['actions'] = __( 'Actions', 'woocommerce-inpost' );

			return $columns;
		}

		private function get_refresh_statuses_btn() {
			return '<a title="' . __( 'Refresh statuses now', 'woocommerce-inpost' ) . '" 
                id="refresh_statuses_btn"
                name="refresh_statuses"
                href="#">
                <span style="font-size: 10px" class="dashicons dashicons-image-rotate"></span>
                </a>';
		}

		private function get_tracking_number_link( ShipX_Shipment_Model $shipment ) {
			$srv             = EasyPack()->get_shipment_service();
			$tracking_number = $data['package_number']
				= $shipment->getInternalData()->getTrackingNumber();

			if ( null !== $tracking_number ) {
				return sprintf( '<a target="_blank" href="%s">%s</a>',
					$srv->getTrackingUrl( $shipment ),
					$tracking_number );
			}

			return '';
		}

		function get_hidden_columns() {
			return [];
		}

		function prepare_items() {

			$columns               = $this->get_columns();
			$hidden                = $this->get_hidden_columns();
			$sortable              = $this->get_sortable_columns();
			$this->_column_headers = [ $columns, $hidden, $sortable ];

			$per_page     = 5000000;
			$current_page = $this->get_pagenum();
			$total_items  = count( $this->data );

			$this->found_data = $this->data;

			$this->set_pagination_args( [
				'total_items' => $total_items,
				'per_page'    => $per_page,
			] );

			$this->items = $this->data;
		}


		public function get_data_post_orders() {
            global $post;

            $args = [
                'post_type'      => 'shop_order',
                'post_status'    => 'any',
                'posts_per_page' => - 1,
                'meta_query'     => [
                    [
                        'key'     => '_easypack_status',
                        'value'   => [
                            'prepared',
                            'created',
                            'ReadyToBeSent',
                        ],
                        'compare' => 'IN',
                    ],
                    [
                        'key'     => '_easypack_dispatched',
                        'value'   => '',
                        'compare' => 'NOT EXISTS',
                    ],
                ],
            ];

            $query = new WP_Query( $args );
            while ( $query->have_posts() ) {
                $query->the_post();
                if ( $post->post_status == 'wc-cancelled' ) {
                    /* skip cancelled orders */
                    continue;
                }
                $order    = wc_get_order( $post->ID );
                $order_id = $order->get_id();

                $shipment_service = EasyPack::EasyPack()->get_shipment_service();
                $pickup_service   = EasyPack::EasyPack()->get_courier_pickup_service();
                $status_service   = EasyPack::EasyPack()->get_shipment_status_service();
                $shipment         = $shipment_service->get_shipment_by_order_id( $order_id );

                if ( false === $shipment instanceof ShipX_Shipment_Model ) {
                    continue;
                }

                //prevent api versions conflicts
                if ( false === $shipment_service->is_shipment_match_to_current_api( $shipment ) ) {
                    continue;
                }

                if ( isset( $_POST['refresh_statuses'] ) && '1' === $_POST['refresh_statuses'] ) {
                    $status_service->refreshStatus( $shipment );
                }

                if ( false === $this->isShipmentMatchedToFilters( $shipment ) ) {
                    continue;
                }

                $easypack_parcels = $shipment->getParcels();

                if ( $easypack_parcels ) {
                    foreach ( $easypack_parcels as $key => $parcel ) {

                        $data                   = [];
                        $data['package_number'] = $this->get_tracking_number_link( $shipment );

                        $data['parcel']      = $parcel;
                        $data['send_method'] = $shipment->getCustomAttributes()->getSendingMethod();

                        $data['service']     = $shipment_service->get_customer_service_name( $shipment );

                        if( $shipment->getInternalData()->getWeekend() ) {
                            $data['service'] =  __('Parcel locker Weekend', 'woocommerce-inpost');
                        }

                        $data['attributes'] = Easypack_Helper()->convert_size_to_symbol( $shipment_service->get_table_attributes( $shipment ) );

                        $data['cod'] = null === $shipment->getCod()
                            ? __( 'No', 'woocommerce-inpost' )
                            : __( 'Yes', 'woocommerce-inpost' );

                        $data['actions'] = $this->getActions( $shipment );

                        $data['send_method_display'] = $this->translateSendingMethod(
                            $shipment->getCustomAttributes()->getSendingMethod() );

                        $data['status'] = $shipment
                                ->getInternalData()
                                ->getStatusTitle()
                            . ' (' . $shipment->getInternalData()
                                ->getStatus() . ')';
                        /*
                        $data['status_timestamp'] = date( 'd-m-Y H:i:s',
                            $shipment
                                ->getInternalData()
                                ->getStatusChangedTimestamp() );
                        */

                        if( is_numeric( $shipment->getInternalData()->getStatusChangedTimestamp() )
                            && (int)$shipment->getInternalData()->getStatusChangedTimestamp() == $shipment->getInternalData()->getStatusChangedTimestamp() )
                        {
                            $data['status_timestamp'] = date( 'd-m-Y H:i:s',  (int) $shipment->getInternalData()->getStatusChangedTimestamp() + 7200 );
                        } else {
                            $data['status_timestamp'] = date('d-m-Y H:i:s', (int) strtotime( $shipment->getInternalData()->getStatusChangedTimestamp() ) + 7200 );
                        }

                        /*
                         $data['created_timestamp'] = date( 'd-m-Y H:i:s',
                            (int) $shipment
                                ->getInternalData()->getCreatedAt() );
                        */

                        if( is_numeric( $shipment->getInternalData()->getCreatedAt() )
                            && (int)$shipment->getInternalData()->getCreatedAt() == $shipment->getInternalData()->getCreatedAt() )
                        {
                            $data['created_timestamp'] = date( 'd-m-Y H:i:s',  (int) $shipment->getInternalData()->getCreatedAt() + 7200 );
                        } else {
                            $data['created_timestamp'] = date('d-m-Y H:i:s', (int) strtotime( $shipment->getInternalData()->getCreatedAt() ) + 7200 );
                        }


                        if ( EasyPack_Shipment_Manager::is_courier_context() ) {

                            $dispatch_status = $shipment->getInternalData()
                                ->getDispatchStatus();
                            if ( null === $dispatch_status ) {
                                $data['dispatch_point_name']   = '-';
                                $data['dispatch_order_status'] = '-';
                            } else {
                                $data['dispatch_point_name'] = $pickup_service->getDispatchPointStr(
                                    $shipment->getInternalData()
                                        ->getDispatchStatus()
                                        ->getDispathOrderPointName() );

                                $data['dispatch_order_status'] = $pickup_service->get_dispatch_order_status_string( $dispatch_status->getDispathOrderStatus() );
                            }
                        }

                        $data['order']            = $order_id;
                        $data['shipping_address']
                            = $order->get_formatted_shipping_address();
                        $data['reference_number'] = $shipment->getReference();
                        $data['inpost_id']        = $shipment->getInternalData()->getInpostId();

                        if ( null !== $shipment->getCustomAttributes()->getTargetPoint() ) {
                            $data['shipping_address'] = __( 'Parcel Locker ', 'woocommerce-inpost' )
                                . ' '
                                . $shipment->getCustomAttributes()->getTargetPoint();
                        }


                        $data['parcel_id'] = $parcel->getId();
                        $data['order_id']  = $order_id;
                        $data['api']       = 'easypack';

                        $data['shipment'] = $shipment;
                        $this->data[]     = $data;
                    }
                }
            }
            wp_reset_postdata();

        }


        public function get_data_wc_orders() {

            $post_ids = [];

            global $wpdb;

            $query = $wpdb->prepare(
                "SELECT DISTINCT pm.post_id
                        FROM {$wpdb->prefix}postmeta AS pm
                        WHERE pm.meta_key = '_easypack_status' AND pm.meta_value IN (%s, %s, %s)
                        AND NOT EXISTS (
                            SELECT 1
                            FROM {$wpdb->prefix}postmeta AS pm2
                            WHERE pm.post_id = pm2.post_id
                            AND pm2.meta_key = '_easypack_dispatched'
                            AND pm2.meta_value = ''
                        )",
                'prepared',
                'created',
                'ReadyToBeSent'
            );

            $post_ids = $wpdb->get_col($query);

            $orders = [];
            if( !empty($post_ids) ) {
                $orders = wc_get_orders($post_ids);
            }

            if( ! empty($orders)) {

                foreach( $orders as $order ) {

                    $order_id = $order->get_id();

                    if ( $order->get_status() === 'cancelled' ) {
                        /* skip cancelled orders */
                        continue;
                    }


                    $shipment_service = EasyPack::EasyPack()->get_shipment_service();
                    $pickup_service   = EasyPack::EasyPack()->get_courier_pickup_service();
                    $status_service   = EasyPack::EasyPack()->get_shipment_status_service();
                    $shipment         = $shipment_service->get_shipment_by_order_id( $order_id );

                    if ( false === $shipment instanceof ShipX_Shipment_Model ) {
                        continue;
                    }

                    //prevent api versions conflicts
                    if ( false === $shipment_service->is_shipment_match_to_current_api( $shipment ) ) {
                        continue;
                    }

                    if ( isset( $_POST['refresh_statuses'] ) && '1' === $_POST['refresh_statuses'] ) {
                        $status_service->refreshStatus( $shipment );
                    }

                    if ( false === $this->isShipmentMatchedToFilters( $shipment ) ) {
                        continue;
                    }

                    $easypack_parcels = $shipment->getParcels();

                    if ( $easypack_parcels ) {
                        foreach ( $easypack_parcels as $key => $parcel ) {

                            $data                   = [];
                            $data['package_number'] = $this->get_tracking_number_link( $shipment );

                            $data['parcel']      = $parcel;
                            $data['send_method'] = $shipment->getCustomAttributes()->getSendingMethod();

                            $data['service']     = $shipment_service->get_customer_service_name( $shipment );

                            if( $shipment->getInternalData()->getWeekend() ) {
                                $data['service'] =  __('Parcel locker Weekend', 'woocommerce-inpost');
                            }

                            $data['attributes'] = Easypack_Helper()->convert_size_to_symbol( $shipment_service->get_table_attributes( $shipment ) );

                            $data['cod'] = null === $shipment->getCod()
                                ? __( 'No', 'woocommerce-inpost' )
                                : __( 'Yes', 'woocommerce-inpost' );

                            $data['actions'] = $this->getActions( $shipment );

                            $data['send_method_display'] = $this->translateSendingMethod(
                                $shipment->getCustomAttributes()->getSendingMethod() );

                            $data['status'] = $shipment
                                    ->getInternalData()
                                    ->getStatusTitle()
                                . ' (' . $shipment->getInternalData()
                                    ->getStatus() . ')';


                            if( is_numeric( $shipment->getInternalData()->getStatusChangedTimestamp() )
                                && (int)$shipment->getInternalData()->getStatusChangedTimestamp() == $shipment->getInternalData()->getStatusChangedTimestamp() )
                            {
                                $data['status_timestamp'] = date( 'd-m-Y H:i:s',  (int) $shipment->getInternalData()->getStatusChangedTimestamp() + 7200 );
                            } else {
                                $data['status_timestamp'] = date('d-m-Y H:i:s', (int) strtotime( $shipment->getInternalData()->getStatusChangedTimestamp() ) + 7200 );
                            }

                            if( is_numeric( $shipment->getInternalData()->getCreatedAt() )
                                && (int)$shipment->getInternalData()->getCreatedAt() == $shipment->getInternalData()->getCreatedAt() )
                            {
                                $data['created_timestamp'] = date( 'd-m-Y H:i:s',  (int) $shipment->getInternalData()->getCreatedAt() + 7200 );
                            } else {
                                $data['created_timestamp'] = date('d-m-Y H:i:s', (int) strtotime( $shipment->getInternalData()->getCreatedAt() ) + 7200 );
                            }


                            if ( EasyPack_Shipment_Manager::is_courier_context() ) {

                                $dispatch_status = $shipment->getInternalData()
                                    ->getDispatchStatus();
                                if ( null === $dispatch_status ) {
                                    $data['dispatch_point_name']   = '-';
                                    $data['dispatch_order_status'] = '-';
                                } else {
                                    $data['dispatch_point_name'] = $pickup_service->getDispatchPointStr(
                                        $shipment->getInternalData()
                                            ->getDispatchStatus()
                                            ->getDispathOrderPointName() );

                                    $data['dispatch_order_status'] = $pickup_service->get_dispatch_order_status_string( $dispatch_status->getDispathOrderStatus() );
                                }
                            }

                            $data['order']            = $order_id;
                            $data['shipping_address']
                                = $order->get_formatted_shipping_address();
                            $data['reference_number'] = $shipment->getReference();
                            $data['inpost_id']        = $shipment->getInternalData()->getInpostId();

                            if ( null !== $shipment->getCustomAttributes()->getTargetPoint() ) {
                                $data['shipping_address'] = __( 'Parcel Locker ', 'woocommerce-inpost' )
                                    . ' '
                                    . $shipment->getCustomAttributes()->getTargetPoint();
                            }


                            $data['parcel_id'] = $parcel->getId();
                            $data['order_id']  = $order_id;
                            $data['api']       = 'easypack';

                            $data['shipment'] = $shipment;
                            $this->data[]     = $data;
                        }
                    }
                }

            }

        }
	}

endif;