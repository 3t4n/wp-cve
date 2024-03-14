<?php
/**
 * Payment list table
 *
 * Admin payments list table, show all the incoming transactions.
 *
 * @since       1.0.2
 * @subpackage  EverAccounting\Admin\ListTables
 * @package     EverAccounting
 */

use EverAccounting\Models\Payment;

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( '\EverAccounting_List_Table' ) ) {
	require_once dirname( __FILE__ ) . '/class-list-table.php';
}

/**
 * Class EverAccounting_Payment_List_Table
 *
 * @since 1.1.0
 */
class EverAccounting_Payment_List_Table extends EverAccounting_List_Table {
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
	 * @see    WP_List_Table::__construct()
	 *
	 * @since  1.0.2
	 */
	public function __construct( $args = array() ) {
		$args = (array) wp_parse_args(
			$args,
			array(
				'singular' => 'payment',
				'plural'   => 'payments',
			)
		);

		parent::__construct( $args );
	}

	/**
	 * Check if there is contents in the database.
	 *
	 * @return bool
	 * @since 1.0.2
	 */
	public function is_empty() {
		global $wpdb;

		return ! (int) $wpdb->get_var( "SELECT COUNT(id) from {$wpdb->prefix}ea_transactions where type='expense'" );
	}

	/**
	 * Render blank state.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	protected function render_blank_state() {
		$url = eaccounting_admin_url(
			array(
				'page'   => 'ea-expenses',
				'tab'    => 'payments',
				'action' => 'edit',
			)
		);
		?>
		<div class="ea-empty-table">
			<p class="ea-empty-table__message">
				<?php echo esc_html__( 'Create and manage your business expenses in any currency you want, so your finances are always accurate and healthy. Know what and when to pay.', 'wp-ever-accounting' ); ?>
			</p>
			<a href="<?php echo esc_url( $url ); ?>" class="button-primary ea-empty-table__cta"><?php esc_html_e( 'Add Payment', 'wp-ever-accounting' ); ?></a>
			<a href="https://wpeveraccounting.com/docs/general/add-payments/?utm_source=listtable&utm_medium=link&utm_campaign=admin" class="button-secondary ea-empty-table__cta" target="_blank"><?php esc_html_e( 'Learn More', 'wp-ever-accounting' ); ?></a>
		</div>
		<?php
	}

	/**
	 * Define which columns to show on this screen.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	public function define_columns() {
		return array(
			'cb'          => '<input type="checkbox" />',
			'date'        => __( 'Date', 'wp-ever-accounting' ),
			'amount'      => __( 'Amount', 'wp-ever-accounting' ),
			'account_id'  => __( 'Account', 'wp-ever-accounting' ),
			'category_id' => __( 'Category', 'wp-ever-accounting' ),
			'contact_id'  => __( 'Vendor', 'wp-ever-accounting' ),
			'reference'   => __( 'Reference', 'wp-ever-accounting' ),
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
			'date'        => array( 'payment_date', false ),
			'amount'      => array( 'amount', false ),
			'account_id'  => array( 'account_id', false ),
			'category_id' => array( 'category_id', false ),
			'contact_id'  => array( 'contact_id', false ),
			'reference'   => array( 'reference', false ),
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
	 * Define primary column.
	 *
	 * @return string
	 * @since 1.0.2
	 */
	public function get_primary_column_name() {
		return 'date';
	}

	/**
	 * Renders the checkbox column in the revenues list table.
	 *
	 * @param Payment $payment The current object.
	 *
	 * @return string Displays a checkbox.
	 * @since  1.0.2
	 */
	public function column_cb( $payment ) {
		return sprintf( '<input type="checkbox" name="payment_id[]" value="%d"/>', esc_attr( $payment->get_id() ) );
	}

	/**
	 * This function renders most of the columns in the list table.
	 *
	 * @param Payment $payment The current object.
	 * @param string  $column_name The name of the column.
	 *
	 * @return string The column value.
	 * @since 1.0.2
	 */
	public function column_default( $payment, $column_name ) {
		$payment_id = $payment->get_id();
		switch ( $column_name ) {
			case 'date':
				$edit_url = eaccounting_admin_url(
					array(
						'page'       => 'ea-expenses',
						'tab'        => 'payments',
						'action'     => 'edit',
						'payment_id' => $payment_id,
					),
					'admin.php'
				);
				$del_url  = eaccounting_admin_url(
					array(
						'page'       => 'ea-expenses',
						'tab'        => 'payments',
						'action'     => 'delete',
						'payment_id' => $payment_id,
						'_wpnonce'   => wp_create_nonce( 'payment-nonce' ),
					),
					'admin.php'
				);

				$actions = array(
					'edit'   => '<a href="' . $edit_url . '">' . __( 'Edit', 'wp-ever-accounting' ) . '</a>',
					'delete' => '<a href="' . $del_url . '" class="del">' . __( 'Delete', 'wp-ever-accounting' ) . '</a>',
				);

				$value = '<a href="' . esc_url( $edit_url ) . '">' . esc_html( eaccounting_date( $payment->get_payment_date() ) ) . '</a>' . $this->row_actions( $actions );
				break;
			case 'amount':
				$value = eaccounting_format_price( $payment->get_amount(), $payment->get_currency_code() );
				break;
			case 'account_id':
				$account = eaccounting_get_account( $payment->get_account_id( 'edit' ) );
				$value   = $account ? sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url(
						eaccounting_admin_url(
							array(
								'page'       => 'ea-banking',
								'tab'        => 'accounts',
								'action'     => 'view',
								'account_id' => $payment->get_account_id( 'edit' ),
							)
						)
					),
					$account->get_name()
				) : '&mdash;';
				break;
			case 'category_id':
				$category = eaccounting_get_category( $payment->get_category_id( 'edit' ) );
				$value    = $category ? $category->get_name() : '&mdash;';
				break;
			case 'contact_id':
				$contact = eaccounting_get_vendor( $payment->get_contact_id( 'edit' ) );
				$value   = $contact ? sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url(
						eaccounting_admin_url(
							array(
								'page'      => 'ea-expenses',
								'tab'       => 'vendors',
								'action'    => 'view',
								'vendor_id' => $payment->get_contact_id( 'edit' ),
							)
						)
					),
					$contact->get_name()
				) : '&mdash;';
				break;
			default:
				return parent::column_default( $payment, $column_name );
		}

		return apply_filters( 'eaccounting_payment_list_table_' . $column_name, $value, $payment );
	}

	/**
	 * Renders the message to be displayed when there are no items.
	 *
	 * @return void
	 * @since  1.0.2
	 */
	public function no_items() {
		esc_html_e( 'There is no payments found.', 'wp-ever-accounting' );
	}

	/**
	 * Extra controls to be displayed between bulk actions and pagination.
	 *
	 * @param string $which The location of the extra table nav markup: 'top' or 'bottom'.
	 *
	 * @since 1.0.2
	 */
	public function extra_tablenav( $which ) {
		if ( 'top' === $which ) {
			$account_id  = filter_input( INPUT_GET, 'account_id', FILTER_VALIDATE_INT );
			$category_id = filter_input( INPUT_GET, 'category_id', FILTER_VALIDATE_INT );
			$vendor_id   = filter_input( INPUT_GET, 'vendor_id', FILTER_VALIDATE_INT );
			$month       = filter_input( INPUT_GET, 'month', FILTER_SANITIZE_STRING );
			$filter      = filter_input( INPUT_GET, 'filter', FILTER_SANITIZE_STRING );
			echo '<div class="alignleft actions ea-table-filter">';

			eaccounting_select2(
				array(
					'placeholder' => __( 'Select Month', 'wp-ever-accounting' ),
					'name'        => 'month',
					'options'     => eaccounting_get_months(),
					'value'       => $month,
				)
			);

			eaccounting_account_dropdown(
				array(
					'name'      => 'account_id',
					'value'     => $account_id,
					'default'   => '',
					'creatable' => false,
					'clearable' => false,
				)
			);

			eaccounting_category_dropdown(
				array(
					'name'        => 'category_id',
					'value'       => $category_id,
					'default'     => '',
					'type'        => 'expense',
					'ajax_action' => 'eaccounting_get_expense_categories',
					'creatable'   => false,
					'clearable'   => false,
				)
			);
			eaccounting_contact_dropdown(
				array(
					'name'        => 'vendor_id',
					'value'       => $vendor_id,
					'default'     => '',
					'placeholder' => __( 'Select Vendor', 'wp-ever-accounting' ),
					'type'        => 'vendor',
					'creatable'   => false,
					'clearable'   => false,
				)
			);
			eaccounting_hidden_input(
				array(
					'name'  => 'filter',
					'value' => 'true',
				)
			);

			submit_button( __( 'Filter', 'wp-ever-accounting' ), 'action', false, false );

			if ( $filter ) :
				?>
				<a class="button-primary button" href="<?php echo esc_url( admin_url( 'admin.php?page=ea-expenses&tab=payments' ) ); ?>"><?php esc_html_e( 'Reset', 'wp-ever-accounting' ); ?></a>
				<?php
			endif;

			echo "\n";

			echo '</div>';
		}
	}

	/**
	 * Process the bulk actions
	 *
	 * @return void
	 * @since 1.0.2
	 */
	public function process_bulk_action() {
		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'bulk-payments' ) && ! wp_verify_nonce( $nonce, 'payment-nonce' ) ) {
			return;
		}

		$ids = isset( $_GET['payment_id'] ) ? wp_parse_id_list( wp_unslash( $_GET['payment_id'] ) ) : false;

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
				case 'export_csv':
					break;
				case 'delete':
					eaccounting_delete_payment( $id );
					break;
				default:
					do_action( 'eaccounting_payments_do_bulk_action_' . $this->current_action(), $id );
			}
		}

		if ( $nonce ) {
			wp_safe_redirect(
				remove_query_arg(
					array(
						'payment_id',
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
	 * @since 1.0.2
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
		$category_id = filter_input( INPUT_GET, 'category_id', FILTER_SANITIZE_NUMBER_INT );
		$account_id  = filter_input( INPUT_GET, 'account_id', FILTER_SANITIZE_NUMBER_INT );
		$vendor_id   = filter_input( INPUT_GET, 'vendor_id', FILTER_SANITIZE_NUMBER_INT );
		$month       = filter_input( INPUT_GET, 'month', FILTER_SANITIZE_STRING );
		$per_page    = $this->per_page;

		$args = wp_parse_args(
			$this->query_args,
			array(
				'per_page'    => $per_page,
				'page'        => $page,
				'number'      => $per_page,
				'offset'      => $per_page * ( $page - 1 ),
				'search'      => $search,
				'orderby'     => eaccounting_clean( $orderby ),
				'order'       => eaccounting_clean( $order ),
				'category_id' => $category_id,
				'account_id'  => $account_id,
				'contact_id'  => $vendor_id,
			)
		);

		if ( ! empty( $month ) ) {
			$args['payment_date'] = array(
				'before' => wp_date( 'Y-m-01', strtotime( $month ) ),
				'after'  => wp_date( 'Y-m-t', strtotime( $month ) ),
			);
		}

		$args        = apply_filters( 'eaccounting_payment_table_query_args', $args, $this );
		$this->items = eaccounting_get_payments( $args );

		$this->total_count = eaccounting_get_payments( array_merge( $args, array( 'count_total' => true ) ) );

		$this->set_pagination_args(
			array(
				'total_items' => $this->total_count,
				'per_page'    => $per_page,
				'total_pages' => ceil( $this->total_count / $per_page ),
			)
		);
	}
}
