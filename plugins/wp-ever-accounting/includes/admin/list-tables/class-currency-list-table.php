<?php
/**
 * Currency Admin List Table.
 *
 * @since       1.0.2
 * @subpackage  EverAccounting\Admin\ListTables
 * @package     EverAccounting
 */

use EverAccounting\Models\Currency;

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( '\EverAccounting_List_Table' ) ) {
	require_once dirname( __FILE__ ) . '/class-list-table.php';
}

/**
 * Class EverAccounting_Currency_List_Table
 *
 * @since 1.1.0
 */
class EverAccounting_Currency_List_Table extends EverAccounting_List_Table {
	/**
	 * Default number of items to show per page
	 *
	 * @since 1.0.2
	 * @var string
	 */
	public $per_page = 20;

	/**
	 * Total number of item found
	 *
	 * @since 1.0.2
	 * @var int
	 */
	public $total_count;

	/**
	 * Number of active items found
	 *
	 * @since 1.0
	 * @var string
	 */
	public $active_count;

	/**
	 *  Number of inactive items found
	 *
	 * @since 1.0
	 * @var string
	 */
	public $inactive_count;

	/**
	 * Base URL for the list table.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	public $base_url;

	/**
	 * Get things started
	 *
	 * @param array $args Optional. Arbitrary display and query arguments to pass through the list table. Default empty array.
	 *
	 * @since  1.0.2
	 *
	 * @see WP_List_Table::__construct()
	 */
	public function __construct( $args = array() ) {
		$args           = (array) wp_parse_args(
			$args,
			array(
				'singular' => 'currency',
				'plural'   => 'currencies',
			)
		);
		$this->base_url = admin_url( 'admin.php?page=ea-settings&tab=currencies' );
		parent::__construct( $args );
	}

	/**
	 * Define which columns to show on this screen.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	public function define_columns() {
		return array(
			'cb'     => '<input type="checkbox" />',
			'name'   => __( 'Name', 'wp-ever-accounting' ),
			'rate'   => __( 'Rate', 'wp-ever-accounting' ),
			'code'   => __( 'Code', 'wp-ever-accounting' ),
			'symbol' => __( 'Symbol', 'wp-ever-accounting' ),
		);
	}

	/**
	 * Define sortable columns.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function define_sortable_columns() {
		return array(
			'name' => array( 'name', false ),
			'code' => array( 'code', false ),
			'rate' => array( 'rate', false ),
		);
	}

	/**
	 * Define bulk actions
	 *
	 * @return array
	 * @since 1.0.2
	 */
	public function define_bulk_actions() {
		return array(
			'delete' => __( 'Delete', 'wp-ever-accounting' ),
		);
	}

	/**
	 * Renders the checkbox column in the categories list table.
	 *
	 * @param Currency $currency The current object.
	 *
	 * @return string Displays a checkbox.
	 * @since  1.0.2
	 */
	public function column_cb( $currency ) {
		return sprintf( '<input type="checkbox" name="currency_code[]" value="%s"/>', esc_attr( $currency->get_code() ) );
	}

	/**
	 * Define primary column.
	 *
	 * @return string
	 * @since 1.0.2
	 */
	public function get_primary_column() {
		return 'name';
	}


	/**
	 * This function renders most of the columns in the list table.
	 *
	 * @param Currency $currency The current object.
	 * @param string   $column_name The name of the column.
	 *
	 * @return string The column value.
	 * @since 1.0.2
	 */
	public function column_default( $currency, $column_name ) {
		$currency_code = $currency->get_code();
		switch ( $column_name ) {
			case 'name':
				$name     = $currency->get_name();
				$edit_url = eaccounting_admin_url(
					array(
						'page'          => 'ea-settings',
						'tab'           => 'currencies',
						'action'        => 'edit',
						'currency_code' => $currency_code,
					)
				);
				$del_url  = eaccounting_admin_url(
					array(
						'page'          => 'ea-settings',
						'tab'           => 'currencies',
						'action'        => 'delete',
						'currency_code' => $currency_code,
						'_wpnonce'      => wp_create_nonce( 'currency-nonce' ),
					)
				);
				$actions  = array(
					'edit'   => sprintf( '<a href="%1$s">%2$s</a>', esc_url( $edit_url ), __( 'Edit', 'wp-ever-accounting' ) ),
					'delete' => sprintf( '<a href="%1$s" class="del">%2$s</a>', esc_url( $del_url ), __( 'Delete', 'wp-ever-accounting' ) ),
				);
				$value    = '<a href="' . $edit_url . '">' . $name . '</a>' . $this->row_actions( $actions );
				break;
			case 'code':
				$value = esc_html( $currency->get_code() );
				break;
			case 'symbol':
				$value = esc_html( $currency->get_symbol() );
				break;
			case 'rate':
				$value = esc_html( $currency->get_rate() );
				break;
			default:
				return parent::column_default( $currency, $column_name );
		}

		return apply_filters( 'eaccounting_currency_list_table_' . $column_name, $value, $currency );
	}

	/**
	 * Renders the message to be displayed when there are no items.
	 *
	 * @return void
	 * @since  1.0.2
	 */
	public function no_items() {
		esc_html_e( 'There is no currencies found.', 'wp-ever-accounting' );
	}

	/**
	 * Process the bulk actions
	 *
	 * @return void
	 * @since 1.0.2
	 */
	public function process_bulk_action() {
		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'bulk-currencies' ) && ! wp_verify_nonce( $nonce, 'currency-nonce' ) ) {
			return;
		}

		$codes = isset( $_GET['currency_code'] ) ? wp_parse_id_list( wp_unslash( $_GET['currency_code'] ) ) : false;
		$codes = wp_parse_list( $codes );

		$action = $this->current_action();
		foreach ( $codes as $code ) {
			switch ( $action ) {
				case 'delete':
					eaccounting_delete_currency( $code );
					break;
				default:
					do_action( 'eaccounting_currencies_do_bulk_action_' . $this->current_action(), $code );
			}
		}

		if ( isset( $nonce ) || ! empty( $action ) ) {
			wp_safe_redirect(
				remove_query_arg(
					array(
						'currency_code',
						'action',
						'_wpnonce',
						'_wp_http_referer',
						'action2',
						'doaction',
						'paged',
					)
				)
			);
			exit();
		}
	}

	/**
	 * Retrieve all the data for the table.
	 * Setup the final data for the table
	 *
	 * @return void
	 * @since 1.0.2
	 */
	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->process_bulk_action();

		$page     = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT, array( 'options' => array( 'default' => 1 ) ) );
		$search   = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
		$order    = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_STRING, array( 'options' => array( 'default' => 'DESC' ) ) );
		$orderby  = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_STRING, array( 'options' => array( 'default' => 'id' ) ) );
		$status   = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );
		$per_page = $this->per_page;

		$args = wp_parse_args(
			$this->query_args,
			array(
				'number'   => $per_page,
				'offset'   => $per_page * ( $page - 1 ),
				'per_page' => $per_page,
				'page'     => $page,
				'search'   => $search,
				'status'   => $status,
				'orderby'  => eaccounting_clean( $orderby ),
				'order'    => eaccounting_clean( $order ),
			)
		);

		$args = apply_filters( 'eaccounting_currency_table_query_args', $args, $this );

		$this->items       = eaccounting_get_currencies( $args );
		$total_items       = eaccounting_get_currencies( array_merge( $args, array( 'count_total' => true ) ) );
		$this->total_count = $total_items;

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);
	}
}
