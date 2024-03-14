<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class elementitemController {

	protected $db;
	public function __construct() {
		global $wpdb;
		$this->db = $wpdb;
	}
	public function __destruct() {
		// $this->db->close();
	}
	/**
	 *
	 * @param integer $order not null
	 * @param string $name not null
	 * @param string $price not null
	 * @param string $description not null
	 * @param string $value1
	 * @param string $value2
	 * @param string $value3
	 * @param string $value4
	 * @param string $woocomerce_product_id
	 * @param integer $opt_default
	 * @param integer $element_id foreign key
	 * @return integer $id returns the id of element item created
	 */
	function create( array $values ) {

		if ( ! function_exists( 'unique' ) ) {
			function unique() {
				return substr( str_shuffle( '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ), 0, 10 );
			}
		}

		( isset( $values['order'] ) ) ? $order                                 = $values['order'] : $order = 0;
		( isset( $values['uniqueId'] ) ) ? $uniqueId                           = $values['uniqueId'] : $uniqueId = unique();
		( isset( $values['name'] ) ) ? $name                                   = $values['name'] : $name = '';
		( isset( $values['price'] ) ) ? $price                                 = $values['price'] : $price = '';
		( isset( $values['description'] ) ) ? $description                     = $values['description'] : $description = '';
		( isset( $values['value1'] ) ) ? $value1                               = $values['value1'] : $value1 = null;
		( isset( $values['value2'] ) ) ? $value2                               = $values['value2'] : $value2 = null;
		( isset( $values['value3'] ) ) ? $value3                               = $values['value3'] : $value3 = null;
		( isset( $values['value4'] ) ) ? $value4                               = $values['value4'] : $value4 = null;
		( isset( $values['woocomerce_product_id'] ) ) ? $woocomerce_product_id = $values['woocomerce_product_id'] : $woocomerce_product_id = null;
		( isset( $values['opt_default'] ) ) ? $opt_default                     = $values['opt_default'] : $opt_default = 0;
		( isset( $values['element_id'] ) ) ? $element_id                       = $values['element_id'] : $element_id = null;
		$query  = $this->db->prepare(
			"INSERT INTO {$this->db->prefix}df_scc_elementitems (`order`,`uniqueId`,`name`,`price`,`description`,`value1`,`value2`,`value3`,`value4`,`woocomerce_product_id`,`opt_default`,`element_id`) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%d,%d);",
			$order,
			$uniqueId,
			$name,
			$price,
			$description,
			$value1,
			$value2,
			$value3,
			$value4,
			$woocomerce_product_id,
			$opt_default,
			$element_id
		);
		$result = $this->db->query( $query );
		$id     = $this->db->insert_id;
		if ( $result ) {
			return $id;
		} else {
			return $this->db->last_error;
		}
	}
	/**
	 * @param int $id
	 * @return object of elementitems
	 * @return array iof elementitems
	 */
	function read( int $id = 0 ) {
		( $id == 0 ) ? $result = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_elementitems" ) ) :
			$result            = $this->db->get_row( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_elementitems WHERE id =%d", $id ) );
		return $result;
	}

	/**
	 * @param $id_element
	 * @return array of elementitems of one element
	 * todo: this is use lo load second selend in conditions of element
	 */
	function readOfElement( int $id_element ) {
		return $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_elementitems WHERE element_id =%s ;", $id_element ) );
	}

	/**
	 *
	 * @param string $order
	 * @param string $name
	 * @param string $price
	 * @param string $description
	 * @param string $value1
	 * @param string $value2
	 * @param string $value3
	 * @param string $value4
	 * @param string $woocomerce_product_id
	 * @param int $opt_default
	 * @param int $element_id foreign key
	 * @return int $id returns the id of element item created
	 */
	function update( array $values ) {
		$id                                    = $values['id'];
		$i                                     = new self();
		$todo                                  = $i->read( $id );
		( isset( $values['order'] ) ) ? $order = $values['order'] : $order = $todo->order;
		( isset( $values['name'] ) ) ? $name   = $values['name'] : $name = $todo->name;
		( isset( $values['price'] ) ) ? $price = $values['price'] : $price = $todo->price;
		( isset( $values['description'] ) ) ? $description                     = $values['description'] : $description = $todo->description;
		( isset( $values['value1'] ) ) ? $value1                               = $values['value1'] : $value1 = $todo->value1;
		( isset( $values['value2'] ) ) ? $value2                               = $values['value2'] : $value2 = $todo->value2;
		( isset( $values['value3'] ) ) ? $value3                               = $values['value3'] : $value3 = $todo->value3;
		( isset( $values['value4'] ) ) ? $value4                               = $values['value4'] : $value4 = $todo->value4;
		( isset( $values['woocomerce_product_id'] ) ) ? $woocomerce_product_id = $values['woocomerce_product_id'] : $woocomerce_product_id = $todo->woocomerce_product_id;
		( isset( $values['opt_default'] ) ) ? $opt_default                     = $values['opt_default'] : $opt_default = $todo->opt_default;
		( isset( $values['element_id'] ) ) ? $element_id                       = $values['element_id'] : $element_id = $todo->element_id;

		$query    = $this->db->prepare(
			"UPDATE {$this->db->prefix}df_scc_elementitems SET `order` =%s,`name`=%s,`price`=%s,`description`=%s,`value1`=%s,`value2`=%s,`value3`=%s,`value4`=%s,`woocomerce_product_id`=%s,`opt_default`=%d,`element_id`=%d WHERE id =%d",
			$order,
			$name,
			$price,
			$description,
			$value1,
			$value2,
			$value3,
			$value4,
			$woocomerce_product_id,
			$opt_default,
			$element_id,
			$id
		);
		$response = $this->db->query( $query );
		if ( $response ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param integer $id id of the elementitem
	 */
	function delete( int $id ) {
		$query    = $this->db->prepare( "DELETE FROM {$this->db->prefix}df_scc_elementitems WHERE id = %d", $id );
		$response = $this->db->query( $query );
		if ( $response ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * *Search the item by uniqueId
	 * @param integer $uniqueId
	 * @return integer $idElementitem
	 */

	function getByUniqueId( string $unqueId ) {
		$result = $this->db->get_row( $this->db->prepare( "SELECT id FROM {$this->db->prefix}df_scc_elementitems WHERE uniqueId =%s LIMIT 1", $unqueId ) );
		if ( $result ) {
			return $result;
		} else {
			return null;
		}
	}
}
