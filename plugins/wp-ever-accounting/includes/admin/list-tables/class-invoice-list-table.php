<?php
/**
 * Invoices Admin List Table
 *
 * @since       1.1.0
 * @subpackage  EverAccounting\Admin\ListTables
 * @package     EverAccounting
 */

use EverAccounting\Models\Invoice;

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( '\EverAccounting_List_Table' ) ) {
	require_once dirname( __FILE__ ) . '/class-list-table.php';
}

/**
 * Class EverAccounting_Invoice_List_Table
 *
 * @since 1.1.0
 */
class EverAccounting_Invoice_List_Table extends EverAccounting_List_Table {
	/**
	 * Default number of items to show per page
	 *
	 * @since 1.1.0
	 * @var string
	 */
	public $per_page = 20;

	/**
	 * Total number of item found
	 *
	 * @since 1.1.0
	 * @var int
	 */
	public $total_count;

	/**
	 * Number of active items found
	 *
	 * @since 1.1.0
	 * @var string
	 */
	public $active_count;

	/**
	 *  Number of inactive items found
	 *
	 * @since 1.1.0
	 * @var string
	 */
	public $inactive_count;

	/**
	 * Get things started
	 *
	 * @param array $args Optional. Arbitrary display and query arguments to pass through the list table. Default empty array.
	 *
	 * @since  1.1.0
	 *
	 * @see WP_List_Table::__construct()
	 */
	public function __construct( $args = array() ) {
		$args = (array) wp_parse_args(
			$args,
			array(
				'singular' => 'invoice',
				'plural'   => 'invoices',
			)
		);

		parent::__construct( $args );
	}

	/**
	 * Check if there is contents in the database.
	 *
	 * @return bool
	 * @since 1.1.0
	 */
	public function is_empty() {
		global $wpdb;

		return ! (int) $wpdb->get_var( "SELECT COUNT(id) from {$wpdb->prefix}ea_documents where type='invoice'" );
	}

	/**
	 * Render blank state.
	 *
	 * @return void
	 * @since 1.1.0
	 */
	protected function render_blank_state() {
		$url = eaccounting_admin_url(
			array(
				'page'   => 'ea-sales',
				'tab'    => 'invoices',
				'action' => 'edit',
			)
		);
		?>
		<div class="ea-empty-table">
			<p class="ea-empty-table__message">
				<?php echo esc_html__( 'Create professional invoices for your customers in their currency. Print and share invoice with easily. Invoice also support tax calculation & discount.', 'wp-ever-accounting' ); ?>
			</p>
			<a href="<?php echo esc_url( $url ); ?>" class="button-primary ea-empty-table__cta"><?php esc_html_e( 'Add Invoices', 'wp-ever-accounting' ); ?></a>
			<a href="https://wpeveraccounting.com/docs/general/add-invoice/?utm_source=listtable&utm_medium=link&utm_campaign=admin" class="button-secondary ea-empty-table__cta" target="_blank"><?php esc_html_e( 'Learn More', 'wp-ever-accounting' ); ?></a>
		</div>
		<?php
	}

	/**
	 * Define which columns to show on this screen.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	public function define_columns() {
		return array(
			'cb'             => '<input type="checkbox" />',
			'invoice_number' => __( 'Number', 'wp-ever-accounting' ),
			'total'          => __( 'Total', 'wp-ever-accounting' ),
			'name'           => __( 'Customer', 'wp-ever-accounting' ),
			'issue_date'     => __( 'Invoice Date', 'wp-ever-accounting' ),
			'due_date'       => __( 'Due Date', 'wp-ever-accounting' ),
			'status'         => __( 'Status', 'wp-ever-accounting' ),
		);
	}

	/**
	 * Define sortable columns.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	protected function define_sortable_columns() {
		return array(
			'invoice_number' => array( 'invoice_number', false ),
			'name'           => array( 'name', false ),
			'total'          => array( 'total', false ),
			'issue_date'     => array( 'issue_date', false ),
			'due_date'       => array( 'due_date', false ),
			'status'         => array( 'status', false ),
		);
	}

	/**
	 * Define bulk actions
	 *
	 * @return array
	 * @since 1.1.0
	 */
	public function define_bulk_actions() {
		return array(
			'cancel'  => __( 'Cancel', 'wp-ever-accounting' ),
			'paid'    => __( 'Paid', 'wp-ever-accounting' ),
			'pending' => __( 'Pending', 'wp-ever-accounting' ),
			'delete'  => __( 'Delete', 'wp-ever-accounting' ),
		);
	}

	/**
	 * Define primary column.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public function get_primary_column_name() {
		return 'invoice_number';
	}

	/**
	 * Renders the checkbox column in the accounts list table.
	 *
	 * @param Invoice $invoice The current account object.
	 *
	 * @return string Displays a checkbox.
	 * @since  1.1.0
	 */
	public function column_cb( $invoice ) {
		return sprintf( '<input type="checkbox" name="invoice_id[]" value="%d"/>', esc_attr( $invoice->get_id() ) );
	}

	/**
	 * This function renders most of the columns in the list table.
	 *
	 * @param Invoice $invoice The current account object.
	 * @param string  $column_name The name of the column.
	 *
	 * @return string The column value.
	 * @since 1.1.0
	 */
	public function column_default( $invoice, $column_name ) {
		$invoice_id = $invoice->get_id();
		switch ( $column_name ) {
			case 'invoice_number':
				$invoice_number = $invoice->get_invoice_number();

				$nonce    = wp_create_nonce( 'invoice-nonce' );
				$view_url = eaccounting_admin_url(
					array(
						'page'       => 'ea-sales',
						'tab'        => 'invoices',
						'action'     => 'view',
						'invoice_id' => $invoice_id,
					)
				);
				$edit_url = eaccounting_admin_url(
					array(
						'page'       => 'ea-sales',
						'tab'        => 'invoices',
						'action'     => 'edit',
						'invoice_id' => $invoice_id,
					)
				);
				$del_url  = eaccounting_admin_url(
					array(
						'page'       => 'ea-sales',
						'tab'        => 'invoices',
						'action'     => 'delete',
						'invoice_id' => $invoice_id,
						'_wpnonce'   => $nonce,
					)
				);

				$actions          = array();
				$actions['view']  = '<a href="' . esc_url( $view_url ) . '">' . esc_html__( 'View', 'wp-ever-accounting' ) . '</a>';
				$actions['print'] = '<a href="' . esc_url( $invoice->get_url() ) . '" target="_blank">' . esc_html__( 'Print', 'wp-ever-accounting' ) . '</a>';
				if ( $invoice->is_editable() ) {
					$actions['edit'] = '<a href="' . esc_url( $edit_url ) . '">' . esc_html__( 'Edit', 'wp-ever-accounting' ) . '</a>';
				}
				$actions['delete'] = '<a href="' . esc_url( $del_url ) . '" class="del">' . esc_html__( 'Delete', 'wp-ever-accounting' ) . '</a>';

				$value = '<a href="' . esc_url( $view_url ) . '">' . esc_html( $invoice_number ) . '</a>' . $this->row_actions( $actions );
				break;
			case 'total':
				$value = eaccounting_price( $invoice->get_total(), $invoice->get_currency_code() );
				break;
			case 'name':
				$value = esc_html( $invoice->get_name() );
				if ( ! empty( $invoice->get_contact_id() ) ) {
					$value = sprintf(
						'<a href="%1$s">%2$s</a>',
						esc_url(
							eaccounting_admin_url(
								array(
									'page'        => 'ea-sales',
									'tab'         => 'customers',
									'action'      => 'view',
									'customer_id' => $invoice->get_contact_id(),
								)
							)
						),
						$invoice->get_name()
					);
				}
				break;
			case 'issue_date':
				$value = eaccounting_date( $invoice->get_issue_date(), 'Y-m-d' );
				break;
			case 'due_date':
				$value = eaccounting_date( $invoice->get_due_date(), 'Y-m-d' );
				break;
			case 'status':
				$value = sprintf( '<div class="ea-document__status %s"><span>%s</span></div>', $invoice->get_status(), $invoice->get_status_nicename() );
				break;
			default:
				return parent::column_default( $invoice, $column_name );
		}

		return apply_filters( 'eaccounting_invoice_list_table_' . $column_name, $value, $invoice );
	}

	/**
	 * Renders the message to be displayed when there are no items.
	 *
	 * @return void
	 * @since  1.1.0
	 */
	public function no_items() {
		esc_html_e( 'There is no invoices found.', 'wp-ever-accounting' );
	}

	/**
	 * Process the bulk actions
	 *
	 * @return void
	 * @since 1.1.0
	 */
	public function process_bulk_action() {
		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'bulk-invoices' ) && ! wp_verify_nonce( $nonce, 'invoice-nonce' ) ) {
			return;
		}

		$ids = isset( $_GET['invoice_id'] ) ? wp_parse_id_list( wp_unslash( $_GET['invoice_id'] ) ) : false;

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
			$invoice = new Invoice( $id );
			switch ( $action ) {
				case 'cancel':
					$invoice->delete_payments();
					$invoice->set_status( 'cancelled' );
					$invoice->save();
					break;
				case 'paid':
					$invoice->set_paid();
					$invoice->save();
					break;
				case 'pending':
					$invoice->set_status( 'pending' );
					$invoice->save();
					break;
				case 'delete':
					eaccounting_delete_invoice( $id );
					break;
				default:
					do_action( 'eaccounting_invoices_do_bulk_action_' . $this->current_action(), $id );
			}
		}

		if ( $nonce ) {
			wp_safe_redirect(
				remove_query_arg(
					array(
						'invoice_id',
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
	 * @return void
	 * @since 1.1.0
	 */
	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->process_bulk_action();

		$page        = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT, array( 'options' => array( 'default' => 1 ) ) );
		$search      = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
		$order       = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_STRING, array( 'options' => array( 'default' => 'DESC' ) ) );
		$orderby     = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_STRING, array( 'options' => array( 'default' => 'id' ) ) );
		$status      = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );
		$customer_id = filter_input( INPUT_GET, 'customer_id', FILTER_SANITIZE_NUMBER_INT );
		$per_page    = $this->per_page;

		$args = wp_parse_args(
			$this->query_args,
			array(
				'number'      => $per_page,
				'offset'      => $per_page * ( $page - 1 ),
				'per_page'    => $per_page,
				'page'        => $page,
				'status'      => $status,
				'search'      => $search,
				'orderby'     => eaccounting_clean( $orderby ),
				'order'       => eaccounting_clean( $order ),
				'customer_id' => $customer_id,
			)
		);

		$args              = apply_filters( 'eaccounting_invoice_table_query_args', $args, $this );
		$this->items       = eaccounting_get_invoices( $args );
		$this->total_count = eaccounting_get_invoices( array_merge( $args, array( 'count_total' => true ) ) );
		$this->set_pagination_args(
			array(
				'total_items' => $this->total_count,
				'per_page'    => $per_page,
				'total_pages' => ceil( $this->total_count / $per_page ),
			)
		);
	}
}
