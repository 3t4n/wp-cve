<?php
/**
 * Transfers list table
 *
 * Admin transfers list table, show all the transfer transactions.
 *
 * @since       1.0.2
 * @subpackage  EverAccounting\Admin\ListTables
 * @package     EverAccounting
 */

use EverAccounting\Models\Transfer;

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( '\EverAccounting_List_Table' ) ) {
	require_once dirname( __FILE__ ) . '/class-list-table.php';
}

/**
 * Class EverAccounting_Transfer_List_Table
 *
 * @since 1.1.0
 */
class EverAccounting_Transfer_List_Table extends EverAccounting_List_Table {
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
	 * Get things started
	 *
	 * @param array $args Optional. Arbitrary display and query arguments to pass through the list table. Default empty array.
	 *
	 * @since  1.0.2
	 * @see    WP_List_Table::__construct()
	 */
	public function __construct( $args = array() ) {
		$args = (array) wp_parse_args(
			$args,
			array(
				'singular' => 'transfer',
				'plural'   => 'transfers',
			)
		);

		parent::__construct( $args );
	}

	/**
	 * Check if there is contents in the database.
	 *
	 * @since 1.0.2
	 * @return bool
	 */
	public function is_empty() {
		global $wpdb;

		return ! (int) $wpdb->get_var( "SELECT COUNT(id) from {$wpdb->prefix}ea_transfers" );
	}

	/**
	 * Render blank state.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	protected function render_blank_state() {
		$url = eaccounting_admin_url(
			array(
				'page'   => 'ea-banking',
				'tab'    => 'transfers',
				'action' => 'edit',
			)
		);
		?>
		<div class="ea-empty-table">
			<p class="ea-empty-table__message">
				<?php echo esc_html__( 'Add deposits to and transfers between accounts and keep the balance of your bank accounts active regardless of currency. The transferred amount will automatically adjust to the account currency.', 'wp-ever-accounting' ); ?>
			</p>
			<a href="<?php echo esc_url( $url ); ?>" class="button-primary ea-empty-table__cta">
				<?php esc_html_e( 'Add Transfers', 'wp-ever-accounting' ); ?>
			</a>
			<a href="https://wpeveraccounting.com/docs/general/add-transfers/?utm_source=listtable&utm_medium=link&utm_campaign=admin" class="button-secondary ea-empty-table__cta" target="_blank">
				<?php esc_html_e( 'Learn More', 'wp-ever-accounting' ); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Define which columns to show on this screen.
	 *
	 * @since 1.0.2
	 * @return array
	 */
	public function define_columns() {
		return array(
			'cb'              => '<input type="checkbox" />',
			'date'            => __( 'Date', 'wp-ever-accounting' ),
			'amount'          => __( 'Amount', 'wp-ever-accounting' ),
			'from_account_id' => __( 'From Account', 'wp-ever-accounting' ),
			'to_account_id'   => __( 'To Account', 'wp-ever-accounting' ),
			'reference'       => __( 'Reference', 'wp-ever-accounting' ),
		);
	}

	/**
	 * Define sortable columns.
	 *
	 * @since 1.0.2
	 * @return array
	 */
	protected function define_sortable_columns() {
		return array(
			'date'            => array( 'payment_date', false ),
			'amount'          => array( 'amount', false ),
			'reference'       => array( 'reference', false ),
			'from_account_id' => array( 'from_account_id', false ),
			'to_account_id'   => array( 'to_account_id', false ),
		);
	}

	/**
	 * Define bulk actions
	 *
	 * @since 1.0.2
	 * @return array
	 */
	public function define_bulk_actions() {
		return array(
			'delete' => __( 'Delete', 'wp-ever-accounting' ),
		);
	}

	/**
	 * Define primary column.
	 *
	 * @since 1.0.2
	 * @return string
	 */
	public function get_primary_column_name() {
		return 'date';
	}

	/**
	 * Renders the checkbox column in the transfers list table.
	 *
	 * @param Transfer $transfer The current object.
	 *
	 * @since  1.0.2
	 * @return string Displays a checkbox.
	 */
	public function column_cb( $transfer ) {
		return sprintf( '<input type="checkbox" name="transfer_id[]" value="%d"/>', esc_attr( $transfer->get_id() ) );
	}

	/**
	 * This function renders most of the columns in the list table.
	 *
	 * @param Transfer $transfer The current object.
	 * @param string   $column_name The name of the column.
	 *
	 * @since 1.0.2
	 * @return string The column value.
	 */
	public function column_default( $transfer, $column_name ) {
		$transfer_id = $transfer->get_id();
		switch ( $column_name ) {
			case 'date':
				$edit_url = eaccounting_admin_url(
					array(
						'page'        => 'ea-banking',
						'tab'         => 'transfers',
						'action'      => 'edit',
						'transfer_id' => $transfer_id,
					)
				);
				$del_url  = eaccounting_admin_url(
					array(
						'page'        => 'ea-banking',
						'tab'         => 'transfers',
						'action'      => 'delete',
						'transfer_id' => $transfer_id,
						'_wpnonce'    => wp_create_nonce( 'transfer-nonce' ),
					)
				);

				$actions = array(
					'edit'   => '<a href="' . $edit_url . '">' . __( 'Edit', 'wp-ever-accounting' ) . '</a>',
					'delete' => '<a href="' . $del_url . '" class="del">' . __( 'Delete', 'wp-ever-accounting' ) . '</a>',
				);
				$value   = sprintf( '<a href="%1$s">%2$s</a>', esc_url( $edit_url ), esc_html( eaccounting_date( $transfer->get_date() ) ) ) . $this->row_actions( $actions );
				break;
			case 'amount':
				$account = eaccounting_get_account( $transfer->get_from_account_id( 'edit' ) );
				$value   = '&mdash;';
				if ( $account ) {
					$value = eaccounting_price( $transfer->get_amount(), $account->get_currency_code() );
				}
				break;
			case 'from_account_id':
				$account = eaccounting_get_account( $transfer->get_from_account_id( 'edit' ) );
				$value   = $account ? sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url(
						eaccounting_admin_url(
							array(
								'page'       => 'ea-banking',
								'tab'        => 'accounts',
								'action'     => 'view',
								'account_id' => $transfer->get_from_account_id(),
							)
						)
					),
					$account->get_name()
				) : '&mdash;';

				break;
			case 'to_account_id':
				$account = eaccounting_get_account( $transfer->get_to_account_id( 'edit' ) );
				$value   = $account ? sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url(
						eaccounting_admin_url(
							array(
								'page'       => 'ea-banking',
								'tab'        => 'accounts',
								'action'     => 'view',
								'account_id' => $transfer->get_to_account_id(),
							)
						)
					),
					$account->get_name()
				) : '&mdash;';
				break;
			case 'reference':
				$value = ! empty( $transfer->get_reference() ) ? $transfer->get_reference() : '&mdash;';
				break;
			default:
				return parent::column_default( $transfer, $column_name );
		}

		return apply_filters( 'eaccounting_transfer_list_table_' . $column_name, $value, $transfer );
	}

	/**
	 * Renders the message to be displayed when there are no items.
	 *
	 * @since  1.0.2
	 * @return void
	 */
	public function no_items() {
		esc_html_e( 'There is no transfers found.', 'wp-ever-accounting' );
	}

	/**
	 * Process the bulk actions
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function process_bulk_action() {
		$nonce = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
		if ( ! wp_verify_nonce( $nonce, 'bulk-transfers' ) && ! wp_verify_nonce( $nonce, 'transfer-nonce' ) ) {
			return;
		}

		$ids = isset( $_GET['transfer_id'] ) ? wp_parse_id_list( wp_unslash( $_GET['transfer_id'] ) ) : false;

		if ( ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		$ids = array_map( 'absint', $ids );
		$ids = array_filter( $ids );

		if ( empty( $ids ) ) {
			return;
		}

		$action = $this->current_action();
		foreach ( $ids as $id ) {
			switch ( $action ) {
				case 'delete':
					eaccounting_delete_transfer( $id );
					break;
				default:
					do_action( 'eaccounting_transfers_do_bulk_action_' . $this->current_action(), $id );
			}
		}

		if ( $nonce ) {
			wp_safe_redirect(
				remove_query_arg(
					array(
						'transfer_id',
						'action',
						'_wpnonce',
						'_wp_http_referer',
						'action2',
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
	 * @since 1.0.2
	 * @return void
	 */
	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->process_bulk_action();

		$page       = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT, array( 'options' => array( 'default' => 1 ) ) );
		$search     = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
		$order      = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_STRING );
		$orderby    = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_STRING, array( 'options' => array( 'default' => 'id' ) ) );
		$from_id    = filter_input( INPUT_GET, 'from_account_id', FILTER_SANITIZE_NUMBER_INT );
		$start_date = filter_input( INPUT_GET, 'start_date', FILTER_SANITIZE_STRING );
		$end_date   = filter_input( INPUT_GET, 'end_date', FILTER_SANITIZE_STRING );

		$per_page = $this->per_page;

		$args = wp_parse_args(
			$this->query_args,
			array(
				'per_page' => $per_page,
				'page'     => $page,
				'number'   => $per_page,
				'offset'   => $per_page * ( $page - 1 ),
				'search'   => $search,
				'orderby'  => eaccounting_clean( $orderby ),
				'order'    => eaccounting_clean( $order ),
				'from_id'  => $from_id,
			)
		);

		if ( ! empty( $start_date ) && ! empty( $end_date ) ) {
			$args['payment_date'] = array(
				'before' => wp_date( 'Y-m-d', strtotime( $end_date ) ),
				'after'  => wp_date( 'Y-m-d', strtotime( $start_date ) ),
			);
		}

		$args        = apply_filters( 'eaccounting_transfer_table_query_args', $args, $this );
		$this->items = eaccounting_get_transfers( $args );

		$this->total_count = eaccounting_get_transfers( array_merge( $args, array( 'count_total' => true ) ) );

		$this->set_pagination_args(
			array(
				'total_items' => $this->total_count,
				'per_page'    => $per_page,
				'total_pages' => ceil( $this->total_count / $per_page ),
			)
		);
	}
}
