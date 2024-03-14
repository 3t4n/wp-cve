<?php

defined( 'ABSPATH' ) || exit();

class Dracula_Toggle_Builder {
	/**
	 * @var null
	 */
	protected static $instance = null;

	public function __construct() {
	}

	public function get_toggle( $id ) {
		global $wpdb;

		$table = $wpdb->prefix . 'dracula_toggles';

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id=%d", $id ) );

	}

	public function get_toggles( $args = [] ) {
		$order_by = ! empty( $args['order_by'] ) ?  $args['order_by']  : 'created_at';
		$order    = ! empty( $args['order'] ) ?  $args['order'] : 'DESC';

		global $wpdb;

		$table = $wpdb->prefix . 'dracula_toggles';

		return $wpdb->get_results( "SELECT * FROM $table ORDER BY $order_by $order" );
	}

	public function get_toggles_count() {
		global $wpdb;

		$table = $wpdb->prefix . 'dracula_toggles';

		return $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
	}

	public function update_toggle( $posted, $force_insert = false ) {
		global $wpdb;

		$table = $wpdb->prefix . 'dracula_toggles';
		$id    = ! empty( $posted['id'] ) ? intval( $posted['id'] ) : '';
		$title = ! empty( $posted['title'] ) ? sanitize_text_field( $posted['title'] ) : '';

		$data = [
			'title'  => $title,
			'config' => ! empty( $posted['config'] ) ? $posted['config'] : maybe_serialize( $posted ),
		];

		$data_format = [ '%s', '%s' ];

		if ( ! $id || $force_insert ) {
			$wpdb->insert( $table, $data, $data_format );

			return $wpdb->insert_id;
		} else {
			$wpdb->update( $table, $data, [ 'id' => $id ], $data_format, [ '%d' ] );

			return $id;
		}

	}

	public function delete_toggle( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dracula_toggles';

		$wpdb->delete( $table, [ 'id' => $id ], [ '%d' ] );
	}


	public function duplicate_toggle( $id ) {
		if ( empty( $id ) ) {
			return false;
		}

		$toggle = $this->get_toggle( $id );

		if ( $toggle ) {
			$toggle               = (array) $toggle;
			$toggle['title']      = 'Copy of ' . $toggle['title'];
			$toggle['created_at'] = current_time( 'mysql' );
			$toggle['updated_at'] = current_time( 'mysql' );

			$insert_id = $this->update_toggle( $toggle, true );

			return array_merge( $toggle, [
				'id'     => $insert_id,
				'config' => unserialize( $toggle['config'] ),
			] );
		}

		return false;
	}

	public static function view() { ?>
        <div id="dracula-toggle-builder"></div>
	<?php }

	/**
	 * @return Dracula_Toggle_Builder|null
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

Dracula_Toggle_Builder::instance();