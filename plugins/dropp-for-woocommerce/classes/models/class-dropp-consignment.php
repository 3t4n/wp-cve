<?php
/**
 * Consignment
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp\Models;

use Dropp\Actions\Get_Shipping_Method_From_Shipping_Item_Action;
use Dropp\API;
use Dropp\Shipping_Method\Dropp;
use Dropp\Shipping_Method\Shipping_Method;
use Exception;
use WC_Log_Levels;
use WC_Logger;
use WC_Order_Item_Shipping;
use WC_Shipping;
use WP_Error;

/**
 * Consignment
 */
class Dropp_Consignment extends Model {

	public int $id;
	public ?string $barcode = null;
	public ?string $return_barcode = null;
	public string $comment = '';
	public ?string $dropp_order_id;
	public string $status = 'ready';
	public string $status_message = '';
	public int $shipping_item_id;
	public bool $day_delivery = false;
	public string $location_id;
	public ?float $value = null;
	public array $products;
	public ?Dropp_Customer $customer;
	public bool $test = false;
	public bool $debug = false;
	public ?string $mynto_id;
	public string $updated_at;
	public string $created_at;
	public array $errors = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
	}

	/**
	 * Get status list.
	 *
	 * @return array List of status.
	 */
	public static function get_status_list(): array {
		return [
			'ready'       => __( 'Ready', 'dropp-for-woocommerce' ),
			'error'       => __( 'Error', 'dropp-for-woocommerce' ),
			'initial'     => __( 'Initial', 'dropp-for-woocommerce' ),
			'overweight'  => __( 'Overweight', 'dropp-for-woocommerce' ),
			'transit'     => __( 'Transit', 'dropp-for-woocommerce' ),
			'consignment' => __( 'Consignment', 'dropp-for-woocommerce' ),
			'delivered'   => __( 'Delivered', 'dropp-for-woocommerce' ),
			'cancelled'   => __( 'Cancelled', 'dropp-for-woocommerce' ),
		];
	}

	/**
	 * Get shipping method
	 *
	 * @return Shipping_Method
	 * @throws Exception
	 */
	public function get_shipping_method(): ?Shipping_Method {
		if ( ! $this->shipping_item_id ) {
			return null;
		}

		return ( new Get_Shipping_Method_From_Shipping_Item_Action() )( (int) $this->shipping_item_id );
	}

	/**
	 * Fill
	 *
	 * @param array $content Content.
	 *
	 * @return Dropp_Consignment          This object.
	 */
	public function fill( array $content ): Dropp_Consignment {
		if ( ! empty( $content['id'] ) ) {
			$this->id = (int) $content['id'];
		}
		$content = wp_parse_args(
			$content,
			[
				'barcode'          => null,
				'return_barcode'   => null,
				'day_delivery'     => false,
				'dropp_order_id'   => null,
				'shipping_item_id' => null,
				'status'           => 'ready',
				'comment'          => '',
				'location_id'      => '',
				'value'            => null,
				'test'             => false,
				'debug'            => false,
				'mynto_id'         => null,
				'updated_at'       => current_time( 'mysql' ),
				'created_at'       => current_time( 'mysql' ),
			]
		);

		$requires_value = [
			'barcode',
			'dropp_order_id',
			'shipping_item_id',
			'location_id',
		];
		foreach ( $requires_value as $name ) {
			// Skip if the property has a value, but the new value is empty.
			if ( ! empty( $this->{$name} ) && empty( $content[ $name ] ) ) {
				continue;
			}
			$this->{$name} = $content[ $name ];
		}

		if ( $content['debug'] ) {
			$this->debug = true;
		}
		$this->value          = $content['value'];
		$this->return_barcode = $content['return_barcode'];
		$this->comment        = $content['comment'];
		$this->status         = $content['status'];
		$this->test           = (int) $content['test'];
		$this->mynto_id       = $content['mynto_id'];
		$this->updated_at     = $content['updated_at'];
		$this->created_at     = $content['created_at'];
		$this->day_delivery   = ( filter_var( $content['day_delivery'], FILTER_VALIDATE_BOOLEAN ) ? 1 : 0 );

		// Process customer.
		if ( $content['customer'] instanceof Dropp_Customer ) {
			$this->customer = $content['customer'];
		} elseif ( is_array( $content['customer'] ) ) {
			$this->customer = new Dropp_Customer();
			$this->customer->fill( $content['customer'] );
		} elseif ( is_string( $content['customer'] ) ) {
			$this->customer = Dropp_Customer::json_decode( $content['customer'] );
		} else {
			$this->customer = new Dropp_Customer();
		}

		// pre-process product lines.
		$this->products = [];
		$products       = [];
		if ( is_array( $content['products'] ) ) {
			$products = $content['products'];
		} elseif ( is_string( $content['products'] ) ) {
			$products = json_decode( $content['products'], true );
		}

		// Fill products.
		if ( is_array( $products ) ) {
			$this->set_products( $products );
		}

		return $this;
	}

	public function set_products( $products ) {
		$this->products = [];
		foreach ( $products as $product ) {
			$this->products[] = ( new Dropp_Product_Line() )->fill( $product );
		}
	}

	public function set_customer( array $customer ) {
		$this->customer = ( new Dropp_Customer() )->fill( $customer );
	}

	/**
	 * To array
	 *
	 * @param boolean $for_request True limits the fields to only those used to send to Dropp.is.
	 *
	 * @return array                Array representation.
	 * @throws Exception
	 */
	public function to_array( bool $for_request = true ): array {
		$products = [];
		foreach ( $this->products as $product ) {
			$products[] = $product->to_array();
		}
		$shipping_method   = Dropp::get_instance();
		$consignment_array = [
			'locationId'  => $this->location_id,
			'value'       => $this->value,
			'barcode'     => $this->barcode,
			'products'    => $products,
			'customer'    => $this->get_customer_array(),
			'daydelivery' => $this->day_delivery,
			'comment'     => $this->comment,
			'returnorder' => $shipping_method ? $shipping_method->enable_return_labels : false,
		];

		if ( $this->mynto_id ) {
			$consignment_array['mynto_id'] = $this->mynto_id;
		}

		if ( ! $for_request ) {
			$consignment_array['id']               = $this->id;
			$consignment_array['return_barcode']   = $this->return_barcode;
			$consignment_array['status']           = $this->status;
			$consignment_array['dropp_order_id']   = $this->dropp_order_id;
			$consignment_array['shipping_item_id'] = $this->shipping_item_id;
			$consignment_array['test']             = $this->test;
			$consignment_array['created_at']       = $this->created_at;
			$consignment_array['updated_at']       = $this->updated_at;

			// Add location.
			$shipping_item          = new WC_Order_Item_Shipping( $this->shipping_item_id );
			$shipping_item_location = Dropp_Location::from_shipping_item( $shipping_item, $this->day_delivery );
			if ( $shipping_item_location->id === $this->location_id ) {
				$consignment_array['location'] = $shipping_item_location;
			} else {
				$consignment_array['location'] = Dropp_Location::remote_find( $this->location_id );
			}
		}

		return $consignment_array;
	}

	/**
	 * Find
	 *
	 * @param int $id ID.
	 *
	 * @return Dropp_Consignment     This.
	 * @throws Exception
	 */
	public static function find( int $id ): Dropp_Consignment {
		global $wpdb;
		$sql         = $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}dropp_consignments WHERE id = %d",
			$id
		);
		$row         = $wpdb->get_row( $sql, ARRAY_A );
		$consignment = new self();
		if ( ! empty( $row ) ) {
			$consignment->fill( $row );
		}
		$shipping_method = $consignment->get_shipping_method();
		if ( ! empty( $shipping_method ) ) {
			$consignment->debug = $shipping_method->debug_mode;
		}

		return $consignment;
	}

	/**
	 * Save
	 *
	 * @return Dropp_Consignment This.
	 */
	public function save(): Dropp_Consignment {
		if ( ! empty( $this->id ) ) {
			$this->update();
		} else {
			$this->insert();
		}

		return $this;
	}

	/**
	 * Update
	 *
	 * @return Dropp_Consignment This.
	 */
	protected function update(): Dropp_Consignment {
		global $wpdb;
		$table_name       = $wpdb->prefix . 'dropp_consignments';
		$this->updated_at = current_time( 'mysql' );
		$wpdb->update(
			$table_name,
			[
				'barcode'          => $this->barcode,
				'return_barcode'   => $this->return_barcode,
				'day_delivery'     => $this->day_delivery,
				'dropp_order_id'   => $this->dropp_order_id,
				'shipping_item_id' => $this->shipping_item_id,
				'location_id'      => $this->location_id,
				'value'            => $this->value,
				'products'         => wp_json_encode( $this->products ),
				'comment'          => $this->comment,
				'status'           => $this->status,
				'customer'         => wp_json_encode( $this->get_customer_array() ),
				'test'             => $this->test,
				'mynto_id'         => $this->mynto_id,
				'updated_at'       => $this->updated_at,
			],
			[
				'id' => $this->id,
			]
		);

		return $this;
	}

	/**
	 * Insert
	 *
	 * @return Dropp_Consignment This.
	 */
	protected function insert(): Dropp_Consignment {
		global $wpdb;
		$this->created_at = current_time( 'mysql' );
		$this->updated_at = current_time( 'mysql' );
		$table_name       = $wpdb->prefix . 'dropp_consignments';

		$row_count = $wpdb->insert(
			$table_name,
			[
				'barcode'          => $this->barcode,
				'day_delivery'     => $this->day_delivery,
				'dropp_order_id'   => $this->dropp_order_id,
				'shipping_item_id' => $this->shipping_item_id,
				'location_id'      => $this->location_id,
				'value'            => $this->value,
				'products'         => wp_json_encode( $this->products ),
				'comment'          => $this->comment,
				'status'           => $this->status,
				'customer'         => wp_json_encode( $this->get_customer_array() ),
				'test'             => $this->test,
				'mynto_id'         => $this->mynto_id,
				'updated_at'       => $this->updated_at,
				'created_at'       => $this->created_at,
			]
		);

		$this->id = $wpdb->insert_id;

		return $this;
	}

	/**
	 * From Order
	 *
	 * @param int|null $order_id (optional) Order ID.
	 *
	 * @return array             Array of Dropp_Consignment.
	 */
	public static function from_order( ?int $order_id = null ): array {
		if ( null === $order_id ) {
			$order_id = get_the_ID();
		}
		$order      = wc_get_order( $order_id );
		$line_items = $order->get_items( 'shipping' );
		$collection = [];
		foreach ( $line_items as $order_item_id => $order_item ) {
			$collection = array_merge( $collection, self::from_shipping_item( $order_item ) );
		}

		return $collection;
	}

	/**
	 * From Shipping Item
	 *
	 * @param WC_Order_Item_Shipping $shipping_item Shipping item.
	 *
	 * @return array                                 Array of Dropp_Consignment.
	 */
	public static function from_shipping_item( WC_Order_Item_Shipping $shipping_item ): array {
		global $wpdb;

		$sql = $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}dropp_consignments WHERE shipping_item_id = %d",
			$shipping_item->get_id()
		);

		$result = $wpdb->get_results( $sql, ARRAY_A );
		if ( empty( $result ) ) {
			return [];
		}
		$collection = [];
		foreach ( $result as $consignment_data ) {
			$consignment = new self();
			$consignment->fill( $consignment_data );
			$collection[] = $consignment;
		}

		return $collection;
	}

	/**
	 * Maybe update
	 *
	 * @return boolean True when updated.
	 */
	public function maybe_update(): bool {
		if ( time() < strtotime( $this->updated_at ) + 600 ) {
			return false;
		}
		try {
			$api       = new API();
			$api->test = $this->test;
			// Search the API.
			$consignment = self::remote_find(
				$this->shipping_item_id,
				$this->dropp_order_id
			);
			if ( $consignment && $consignment->status != $this->status ) {
				$this->status = $consignment->status;
				$this->save();
			}
		} catch ( Exception $e ) {
			return false;
		}

		return true;
	}

	/**
	 * Get customer array
	 *
	 * @return array Customer data.
	 */
	public function get_customer_array(): array {
		if ( empty( $this->customer ) ) {
			return [];
		}

		return $this->customer->to_array();
	}

	/**
	 * Check weight
	 *
	 * @return boolean True if weight is within limits.
	 * @throws Exception
	 */
	public function check_weight(): bool {
		$shipping_method = $this->get_shipping_method();
		if ( 0 === $shipping_method->weight_limit ) {
			return true;
		}
		$total_weight = 0;
		foreach ( $this->products as $product ) {
			$total_weight += $product->weight * $product->quantity;
		}

		return $total_weight <= ( $shipping_method->weight_limit ?? 10 );
	}

	/**
	 * Maybe Update order status
	 */
	public function maybe_update_order_status(): Dropp_Consignment {
		$shipping_method = Dropp::get_instance();
		$shipping_item   = new WC_Order_Item_Shipping( $this->shipping_item_id );
		if ( '' !== $shipping_method->new_order_status ) {
			$order = $shipping_item->get_order();
			$order->update_status(
				$shipping_method->new_order_status,
				__( 'Dropp booking complete.', 'dropp-for-woocommerce' )
			);
		}

		return $this;
	}

	/**
	 * Remote post / Booking
	 *
	 * @return Dropp_Consignment          This object.
	 * @throws Exception
	 */
	public function remote_post(): Dropp_Consignment {
		$api       = new API();
		$api->test = $this->test;

		$response = $api->post( 'orders', $this );

		return $this->process_remote_consignment( 'post', $response );
	}

	/**
	 * Remote patch / Update booking
	 *
	 * @return Dropp_Consignment          This object.
	 * @throws Exception   $e     Sending exception.
	 */
	public function remote_patch(): Dropp_Consignment {
		if ( ! $this->dropp_order_id ) {
			throw new Exception(
				sprintf(
				// translators: Consignment ID.
					__( 'Consignment, %d, does not have a dropp order id.', 'dropp-for-woocommerce' ),
					$this->id
				)
			);
		}
		$api       = new API();
		$api->test = $this->test;

		$response = $api->patch( 'orders/' . $this->dropp_order_id, $this );

		return $this->process_remote_consignment( 'patch', $response );
	}

	/**
	 * Remote delete / Cancel order
	 *
	 * @return Dropp_Consignment          This object.
	 * @throws Exception   $e     Sending exception.
	 */
	public function remote_delete(): Dropp_Consignment {
		if ( ! $this->dropp_order_id ) {
			throw new Exception(
				sprintf(
				// translators: Consignment ID.
					__( 'Consignment, %d, does not have a dropp order id.', 'dropp-for-woocommerce' ),
					$this->id
				)
			);
		}
		$api       = new API();
		$api->test = $this->test;

		$response = $api->delete( 'orders/' . $this->dropp_order_id, $this );

		return $this->process_remote_consignment( 'delete', $response );
	}

	/**
	 * Remote add
	 *
	 * @return array|string Decoded json, string body or raw response object.
	 */
	public function remote_add() {
		$api       = new API();
		$api->test = $this->test;

		return $api->post( 'orders/addnew/', $this );
	}

	/**
	 * Remote add
	 *
	 * @return Dropp_Consignment|null Decoded json, string body or raw response object.
	 */
	public static function remote_find( $shipping_item_id, $dropp_order_id ): ?Dropp_Consignment {
		// Instantiate a new consignment.
		$consignment                   = new self();
		$consignment->shipping_item_id = $shipping_item_id;

		// Ask the API about the dropp order id.
		$api      = new API();
		$response = $api->get( "orders/{$dropp_order_id}" );

		if ( ! empty( $response['id'] ) ) {
			$response['dropp_order_id'] = $response['id'];
			unset( $response['id'] );
		}

		if ( ! empty( $response['locationId'] ) ) {
			$response['location_id'] = $response['locationId'];
			unset( $response['locationId'] );
		}

		if ( empty( $response['products'] ) ) {
			$response['products'] = [];
		}
		if ( ! empty( $response['error'] ) ) {
			return null;
		}
		$response['test'] = $api->test;

		// Return the filled consignment.
		return $consignment->fill( $response );
	}

	/**
	 * Process response
	 *
	 * @param string $method Remote method, either 'get' or 'post'.
	 * @param WP_Error|array $response Array with response data on success.
	 *
	 * @return Dropp_Consignment              This object.
	 * @throws Exception      $e        Response exception.
	 */
	protected function process_remote_consignment( string $method, $response ): Dropp_Consignment {
		if ( ! is_array( $response ) ) {
			$this->errors['invalid_json'] = $response['body'];
			throw new Exception( __( 'Invalid json', 'dropp-for-woocommerce' ) );
		}
		if ( ! empty( $response['error'] ) ) {
			throw new Exception( $response['error'] );
		}
		if ( 'patch' === $method ) {
			// Patch calls should return an empty response.
			// No need for further processing.
			return $this;
		}
		if ( empty( $response['id'] ) ) {
			throw new Exception( __( 'Empty ID in the response', 'dropp-for-woocommerce' ) );
		}

		$this->dropp_order_id = $response['id'] ?? '';
		$this->status         = $response['status'] ?? '';
		if ( isset( $response['daydelivery'] ) ) {
			$this->day_delivery = ( filter_var( $response['daydelivery'], FILTER_VALIDATE_BOOLEAN ) ? 1 : 0 );
		}
		if ( ! empty( $response['barcode'] ) ) {
			$this->barcode = $response['barcode'];
		}

		if ( ! empty( $response['returnbarcode'] ) ) {
			$this->return_barcode = $response['returnbarcode'];
		}

		return $this;
	}
}
