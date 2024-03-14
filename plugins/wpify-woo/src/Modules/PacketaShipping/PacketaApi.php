<?php

namespace WpifyWoo\Modules\PacketaShipping;

use Exception;
use WP_Error;
use WpifyWoo\Models\PacketaOrderModel;
use WpifyWoo\PacketeraSDK\ClassMap;
use WpifyWoo\PacketeraSDK\ServiceType\Create;
use WpifyWoo\PacketeraSDK\ServiceType\Packets;
use WpifyWoo\PacketeraSDK\StructType\Attribute;
use WpifyWoo\PacketeraSDK\StructType\AttributeCollection;
use WpifyWoo\PacketeraSDK\StructType\Item;
use WpifyWoo\PacketeraSDK\StructType\ItemCollection;
use WpifyWoo\PacketeraSDK\StructType\PacketAttributes;
use WpifyWoo\PacketeraSDK\StructType\PacketIds;
use WpifyWoo\PacketeraSDK\StructType\StorageFileAttributes;
use WpifyWoo\Plugin;
use WpifyWoo\Repositories\PacketaOrderRepository;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractComponent;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * Class PacketaApi
 * @package WpifyWoo\Modules\PacketaShipping
 * @property Plugin $plugin
 */
class PacketaApi extends AbstractComponent {

	private $initialized = false;
	private $options;
	/** @var PacketaShippingModule $module */
	private $module;

	/**
	 * @param  PacketaOrderModel  $order
	 */
	public function create_packet( PacketaOrderModel $order ) {
		$this->initialize();
		$create     = new Create( $this->options );
		$attributes = new PacketAttributes( null );
		$wc_order   = $order->get_wc_order();
		$data       = array(
			'first_name'   => $wc_order->get_shipping_first_name() ?: $wc_order->get_billing_first_name(),
			'last_name'    => $wc_order->get_shipping_last_name() ?: $wc_order->get_billing_last_name(),
			'order_number' => $wc_order->get_order_number(),
			'email'        => $wc_order->get_billing_email(),
			'phone'        => $wc_order->get_billing_phone(),
			'total'        => $wc_order->get_total() > 0
				? floatval( $wc_order->get_total() )
				: floatval( $this->module->get_setting( 'order_default_price' ) ),
			'currency'     => $wc_order->get_currency(),
		);

		if ( ! $order->is_external_carrier() ) {
			$data['address_id'] = $order->get_packeta_id();
		} else {
			$data['street']     = $wc_order->get_shipping_address_1() ?: $wc_order->get_billing_address_1();
			$data['city']       = $wc_order->get_shipping_city() ?: $wc_order->get_billing_city();
			$zip                = str_replace( ' ', '', $wc_order->get_shipping_postcode() ?: $wc_order->get_billing_postcode() );
			$data['zip']        = substr( $zip, 0, 3 ) . ' ' . substr( $zip, 3 );
			$data['address_id'] = $order->get_carrier_id();
		}

		$is_cod    = ! empty( $this->module->get_setting( 'cod_gateways' ) ) && in_array( $wc_order->get_payment_method(), $this->module->get_setting( 'cod_gateways' ) );
		$round_cod = $this->module->get_setting( 'round_cod' );

		if ( $is_cod && $round_cod ) {
			$data['cod'] = round( floatval( $wc_order->get_total() ) );
		} elseif ( $is_cod ) {
			$data['cod'] = floatval( $wc_order->get_total() );
		}

		$weight = $order->get_package_weight();
		if ( $weight ) {
			$data['weight'] = $weight;
		}

		$data = apply_filters( 'wpify_woo_packeta_api_data', $data, $wc_order, $order );

		try {
			$attributes
				->setEshop( $this->module->get_setting( 'sender_name' ) )
				->setName( $data['first_name'] )
				->setSurname( $data['last_name'] )
				->setNumber( $data['order_number'] )
				->setEmail( $data['email'] )
				->setPhone( $data['phone'] )
				->setAddressId( $data['address_id'] )
				->setValue( $data['total'] )
				->setCurrency( $data['currency'] );

			if ( $is_cod && $data['cod'] ) {
				$attributes->setCod( $data['cod'] );
			}

			if ( $weight ) {
				$attributes->setWeight( floatval( $data['weight'] ) );
			}

			if ( ! empty( $data['street'] ) ) {
				$attributes->setStreet( $data['street'] );
			}

			if ( ! empty( $data['house_number'] ) ) {
				$attributes->setHouseNumber( $data['house_number'] );
			}

			if ( ! empty( $data['city'] ) ) {
				$attributes->setCity( $data['city'] );
			}

			if ( ! empty( $data['zip'] ) ) {
				$attributes->setZip( $data['zip'] );
			}
			if ( ! empty( $data['attributes'] ) ) {
				$extra = new AttributeCollection();
				foreach ( $data['attributes'] as $key => $val ) {
					$attr = new Attribute();
					$attr->setKey( $key )->setValue( $val );
					$extra->addToAttribute( $attr );
				}
				$attributes->setAttributes( $extra );
			}
			if ( ! empty( $data['items'] ) ) {
				$items = new ItemCollection();
				foreach ( $data['items'] as $item ) {
					$i     = new Item();
					$extra = new AttributeCollection();
					foreach ( $item as $key => $val ) {
						$attr = new Attribute();
						$attr->setKey( $key )->setValue( strval( $val ) );
						$extra->addToAttribute( $attr );
					}

					$i->setAttributes( $extra );
					$items->addToItem( $i );
				}

				$attributes->setItems( $items );
			}

			$result = $create->createPacket( $this->module->get_setting( 'api_password' ), $attributes );

			if ( $result !== false ) {
				$result = $create->getResult();
				$order->set_package_id( $result->getId() );
				$order->set_barcode( $result->getBarcode() );
				$this->plugin->get_repository( PacketaOrderRepository::class )->save( $order );
				$data = array(
					'order_id'   => $order->get_id(),
					'package_id' => $order->get_package_id(),
					'barcode'    => $order->get_barcode(),
				);
				$this->plugin->get_logger()->info(
					'Packeta - package created',
					array(
						'data' => $data,
					)
				);

				return $data;
			} else {
				foreach ( $create->getLastError() as $error ) {
					$message = sprintf( 'Packeta error: %s, Response: %s', $error->getMessage(), (string) $create->getLastResponse() );
					$this->plugin->get_logger()->info(
						$message,
						array(
							'data' => $data,
						)
					);
					$wc_order->add_order_note( $message );

					return new WP_Error( 'error', $error->getMessage() );
				}
			}
		} catch ( Exception $e ) {
			$message = sprintf( 'Packeta error: %s', $e->getMessage() );
			$this->plugin->get_logger()->info(
				$message,
				array(
					'data' => $data,
				)
			);
			$wc_order->add_order_note( $message );
		}
	}

	public function initialize() {
		if ( $this->initialized ) {
			return;
		}

		$this->options = array(
			AbstractSoapClientBase::WSDL_URL      => 'https://www.zasilkovna.cz/api/soap-php-bugfix.wsdl',
			AbstractSoapClientBase::WSDL_CLASSMAP => ClassMap::get(),
		);

		$this->module      = $this->plugin->get_module( PacketaShippingModule::class );
		$this->initialized = true;
	}

	public function get_packets_labels( $ids ) {
		$ids = is_array( $ids ) ? $ids : (array) $ids;
		$this->initialize();
		$packets = new Packets( $this->options );
		if ( $packets->packetsLabelsPdf( $this->module->get_setting( 'api_password' ), new PacketIds( $ids ), 'A7 on A4', 0 ) !== false ) {
			return $packets->getResult();
		} else {
			$data = array(
				'package_ids' => $ids,
			);
			foreach ( $packets->getLastError() as $error ) {
				$message = sprintf( 'Packeta error: $s', $error->getMessage() );
				$this->plugin->get_logger()->info(
					$message,
					array(
						'data' => $data,
					)
				);

				return new WP_Error( 'error', $error->getMessage() );
			}
		}
	}

	public function store_file( PacketaOrderModel $order, $path ) {
		$create     = new Create( $this->options );
		$attributes = new StorageFileAttributes();
		$attributes->setContent( base64_encode( file_get_contents( $path ) ) )
		->setName(basename($path));
		$result = $create->createStorageFile($this->module->get_setting( 'api_password' ), $attributes);

		if ( $result !== false ) {
			$id = $create->getResult()->getId();
			$order->set_packeta_invoice_id($id );
			$this->plugin->get_repository( PacketaOrderRepository::class )->save( $order );
			return $id;
		} else {
			foreach ( $create->getLastError() as $error ) {
				$message = sprintf( 'Packeta error: %s, Response: %s', $error->getMessage(), (string) $create->getLastResponse() );
				$wc_order   = $order->get_wc_order();
				$wc_order->add_order_note( $message );
				return new WP_Error( 'error', $error->getMessage() );
			}
		}
	}
}
