<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class quoteSubmissionsController {

	protected $db;
	public function __construct() {
		global $wpdb;
		$this->db = $wpdb;
	}
	function __destruct() {
		// $this->db->close();
	}

	/**
	 * @param string $status
	 * @param string $type
	 * @param integer $opened
	 * @param integer $starred
	 * @param string $submit_fields
	 * @param string $quote_data
	 * @param string $user_ip
	 * @param string $browser_ua
	 * @param integer $calc_id foreign key
	 * @return integer $id returns id of created element
	 */
	function create( array $values ) {
		( isset( $values['status'] ) ) ? $status               = $values['status'] : $status = '';
		( isset( $values['type'] ) ) ? $type                   = $values['type'] : $type = null;
		( isset( $values['opened'] ) ) ? $opened               = $values['opened'] : $opened = null;
		( isset( $values['starred'] ) ) ? $starred             = $values['starred'] : $starred = null;
		( isset( $values['submit_fields'] ) ) ? $submit_fields = $values['submit_fields'] : $submit_fields = null;
		( isset( $values['quote_data'] ) ) ? $quote_data       = $values['quote_data'] : $quote_data = null;
		( isset( $values['user_ip'] ) ) ? $user_ip             = $values['user_ip'] : $user_ip = null;
		( isset( $values['browser_ua'] ) ) ? $browser_ua       = $values['browser_ua'] : $browser_ua = null;
		( isset( $values['calc_id'] ) ) ? $calc_id             = $values['calc_id'] : $calc_id = null;

		$query = $this->db->prepare(
			"INSERT INTO {$this->db->prefix}df_scc_quote_submissions (`status`,`type`,opened,starred,submit_fields,quote_data,user_ip,browser_ua,calc_id) VALUES (%s,%s,%d,%d,%s,%s,%s,%s,%d) ;",
			$status,
			$type,
			$opened,
			$starred,
			$submit_fields,
			$quote_data,
			$user_ip,
			$browser_ua,
			$calc_id
		);

		$result = $this->db->query( $query );
		$id     = $this->db->insert_id;
		if ( $result ) {
			return $id;
		} else {
			return 0;
		}
	}
	/**
	 * @param integer $id of element
	 * @return object if id returs one object
	 * @return array if no param returns all quote Submissions
	 */
	function read( int $id = 0 ) {
		( $id == 0 ) ? $result = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_quote_submissions" ) ) :
			$result            = $this->db->get_row( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_quote_submissions WHERE id =%d", $id ) );
		return $result;
	}

	/**
	 * @param string $status
	 * @param string $type
	 * @param integer $opened
	 * @param integer $starred
	 * @param string $submit_fields
	 * @param string $quote_data
	 * @param string $user_ip
	 * @param string $browser_ua
	 * @param integer $calc_id foreign key
	 * @return bool true or false
	 */
	function update( array $values ) {
		$id   = $values['id'];
		$i    = new self();
		$todo = $i->read( $id );

		( isset( $values['status'] ) ) ? $status               = $values['status'] : $status = $todo->status;
		( isset( $values['type'] ) ) ? $type                   = $values['type'] : $type = $todo->type;
		( isset( $values['opened'] ) ) ? $opened               = $values['opened'] : $opened = $todo->opened;
		( isset( $values['starred'] ) ) ? $starred             = $values['starred'] : $starred = $todo->starred;
		( isset( $values['submit_fields'] ) ) ? $submit_fields = $values['submit_fields'] : $submit_fields = $todo->submit_fields;
		( isset( $values['quote_data'] ) ) ? $quote_data       = $values['quote_data'] : $quote_data = $todo->quote_data;
		( isset( $values['user_ip'] ) ) ? $user_ip             = $values['user_ip'] : $user_ip = $todo->user_ip;
		( isset( $values['browser_ua'] ) ) ? $browser_ua       = $values['browser_ua'] : $browser_ua = $todo->browser_ua;
		( isset( $values['calc_id'] ) ) ? $calc_id             = $values['calc_id'] : $calc_id = $todo->calc_id;

		$query = $this->db->prepare(
			" UPDATE {$this->db->prefix}df_scc_quote_submissions SET `status`=%s,`type`=%s,opened=%d,starred=%d,submit_fields=%s,quote_data=%s,user_ip=%s,browser_ua=%s,calc_id=%d WHERE id = %d ;",
			$status,
			$type,
			$opened,
			$starred,
			$submit_fields,
			$quote_data,
			$user_ip,
			$browser_ua,
			$calc_id,
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
	 * @param $id of row to be deleted
	 * @return bool true or false
	 */
	function delete( int $id ) {
		$query    = $this->db->prepare( "DELETE FROM {$this->db->prefix}df_scc_quote_submissions WHERE id = %d", $id );
		$response = $this->db->query( $query );
		if ( $response ) {
			return true;
		} else {
			return false;
		}
	}
}
