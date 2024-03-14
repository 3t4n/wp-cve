<?php
/**
 *
 * Logger class for saving smartbill data
 *
 * @link       http://www.smartbill.ro
 * @since      1.0.0
 *
 * @copyright  Intelligent IT SRL 2018
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/includes
 */

/**
 * Logger class for saving smartbill data
 *
 * @since      1.0.0
 * @copyright  Intelligent IT SRL 2018
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/includes
 * @author     Intelligent IT SRL <vreauapi@smartbill.ro>
 */
class SmartBill_Data_Logger {
	/**
	 * This stores the order id
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $order_id    woocommerce order id.
	 */
	private $order_id;

	/**
	 * This stores already saved data
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Array    $existing_data  smartbill invoice data.
	 */
	private $existing_data;

	/**
	 * Getter for WooCommerce ID
	 *
	 * @return string
	 */
	public function get_order_id() {
		return $this->order_id;
	}

	/**
	 * Constructor - if data does not exist - we do not set the order_id because we need to retrieve it
	 * in a separate call if data exists (in order to append JSON exchanged data) and we do not want false negatives
	 *
	 * @param string $order_id woocommerce order id.
	 *
	 * @return void
	 */
	public function __construct( $order_id ) {
		$existing_data = get_post_meta( $order_id, 'smartbill_invoice_log', $single = true );
		// if data exists, fill in the details.
		if ( ! empty( $existing_data ) ) {
			$this->existing_data = $existing_data;
			$this->order_id      = $order_id;
		} else {
			$this->existing_data = null;
		}
	}

	/**
	 * Get logger data
	 *
	 * @param string      $order_id woocommerce order id.
	 * @param string|null $key meta data key.
	 *
	 * @return Array|false
	 */
	public function get_data( $order_id, $key = null ) {
		if ( ! is_numeric( $order_id ) || ! $key ) {
			return false;
		}
		$existing_data = get_post_meta( $order_id, 'smartbill_invoice_log', $single = true );
		if ( $key && array_key_exists( $key, $existing_data ) ) {
			return $existing_data[ $key ];
		}
		return $existing_data;
	}

	/**
	 * Set logger data
	 *
	 * @param string $order_id woocommerce order id.
	 * @param string $key meta data key.
	 * @param string $value meta data value.
	 *
	 * @return SmartBill_Data_Logger|false
	 */
	public function set_data( $order_id, $key, $value ) {
		if ( ! is_numeric( $order_id ) || ! $key ) {
			return false;
		}
		$this->existing_data[ $key ] = $value;
		return $this;
	}

	/**
	 * Save information into database
	 *
	 * @param string $order_id woocommerce order id.
	 *
	 * @return Array|false
	 */
	public function save( $order_id = null ) {
		if ( ! $this->existing_data ) {
			return false;
		}
		if ( ! $this->get_order_id() ) {
			$this->order_id = $order_id;
		}
		$existing_data = $this->existing_data;
		return update_post_meta( $this->get_order_id(), 'smartbill_invoice_log', $existing_data );
	}

}
