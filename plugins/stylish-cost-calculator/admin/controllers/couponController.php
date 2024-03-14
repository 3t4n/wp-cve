<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class couponController {

	protected $db;
	public function __construct() {
		global $wpdb;
		$this->db = $wpdb;
	}
	function __destruct() {
		// $this->db->close();
	}

	/**
	 * @param string $name
	 * @param string $code
	 * @param string $startdate
	 * @param string $enddate
	 * @param float $discountpercentage
	 * @param float $discountvalue
	 * @param float $minspend
	 * @param float $maxspend
	 * @return int $id of created element
	 */

	function create( array $values ) {

		( isset( $values['name'] ) ) ? $name                             = $values['name'] : $name = null;
		( isset( $values['code'] ) ) ? $code                             = $values['code'] : $code = null;
		( isset( $values['startdate'] ) ) ? $startdate                   = $values['startdate'] : $startdate = null;
		( isset( $values['enddate'] ) ) ? $enddate                       = $values['enddate'] : $enddate = null;
		( isset( $values['discountpercentage'] ) ) ? $discountpercentage = $values['discountpercentage'] : $discountpercentage = null;
		( isset( $values['discountvalue'] ) ) ? $discountvalue           = $values['discountvalue'] : $discountvalue = null;
		( isset( $values['minspend'] ) ) ? $minspend                     = $values['minspend'] : $minspend = null;
		( isset( $values['maxspend'] ) ) ? $maxspend                     = $values['maxspend'] : $maxspend = null;

		$query = $this->db->prepare(
			"INSERT INTO {$this->db->prefix}df_scc_coupons (`name`,code, startdate, enddate, discountpercentage, discountvalue, minspend, maxspend) VALUES (%s,%s,%s,%s,%s,%s,%s,%s) ; ",
			$name,
			$code,
			$startdate,
			$enddate,
			$discountpercentage,
			$discountvalue,
			$minspend,
			$maxspend
		);

		$result = $this->db->query( $query );
		$id     = $this->db->insert_id;
		if ( $result ) {
			return $id;
		} else {
			return 0;
		}

		return $query;
	}
	/**
	 * @param string $name
	 * @param string $code
	 * @param string $startdate
	 * @param string $enddate
	 * @param float $discountpercentage
	 * @param float $discountvalue
	 * @param float $minspend
	 * @param float $maxspend
	 * @return bool true or false
	 */
	function update( array $values ) {
		$id   = $values['id'];
		$i    = new self();
		$todo = $i->read( $id )[0];

		( isset( $values['name'] ) ) ? $name                             = $values['name'] : $name = $todo->name;
		( isset( $values['code'] ) ) ? $code                             = $values['code'] : $code = $todo->code;
		( isset( $values['startdate'] ) ) ? $startdate                   = $values['startdate'] : $startdate = $todo->startdate;
		( isset( $values['enddate'] ) ) ? $enddate                       = $values['enddate'] : $enddate = $todo->enddate;
		( isset( $values['discountpercentage'] ) ) ? $discountpercentage = $values['discountpercentage'] : $discountpercentage = $todo->discountpercentage;
		( isset( $values['discountvalue'] ) ) ? $discountvalue           = $values['discountvalue'] : $discountvalue = $todo->discountvalue;
		( isset( $values['minspend'] ) ) ? $minspend                     = $values['minspend'] : $minspend = $todo->minspend;
		( isset( $values['maxspend'] ) ) ? $maxspend                     = $values['maxspend'] : $maxspend = $todo->maxspend;

		$query = $this->db->prepare(
			"UPDATE {$this->db->prefix}df_scc_coupons SET `name`=%s,code=%s,startdate=%s,enddate=%s,discountpercentage=%f,discountvalue=%f,minspend=%f,maxspend=%f WHERE id = %d ;",
			$name,
			$code,
			$startdate,
			$enddate,
			$discountpercentage,
			$discountvalue,
			$minspend,
			$maxspend,
			$id
		);

		$result = $this->db->query( $query );
		if ( $result ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param integer $id
	 * @return array of coupon or coupons
	 */

	function read( int $id = 0 ) {
		( $id == 0 ) ? $result = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_coupons" ) ) :
			$result            = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_coupons WHERE id =%d", $id ) );
		return $result;
	}

	/**
	 * @param string $code
	 *
	 * @return array
	 */
	function findByCode( string $code ) {
		$result = $this->db->get_row( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_coupons WHERE code =%s", $code ) );
		return $result;
	}
	/**
	 * @param int $id if coupon to be deleted
	 * @return bool true or false
	 */
	function delete( int $id ) {
		$query    = $this->db->prepare( "DELETE FROM {$this->db->prefix}df_scc_coupons WHERE id = %d", $id );
		$response = $this->db->query( $query );
		if ( $response ) {
			return true;
		} else {
			return false;
		}
	}
}
