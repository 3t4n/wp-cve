<?php
/**
 * Transactions list table
 *
 * Admin transactions list table it shows all kind of transactions
 * related to  the company
 *
 * @since       1.0.2
 * @subpackage  EverAccounting\Admin\ListTables
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( '\EverAccounting_List_Table' ) ) {
	require_once dirname( __FILE__ ) . '/class-list-table.php';
}

/**
 * Class EverAccounting_Transaction_List_Table
 *
 * @since 1.1.0
 */
class EverAccounting_Transaction_List_Table extends EverAccounting_List_Table {
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
	 * Number of income items found
	 *
	 * @since 1.0.2
	 * @var string
	 */
	public $income_count;

	/**
	 *  Number of expense items found
	 *
	 * @since 1.0.2
	 * @var string
	 */
	public $expense_count;

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
				'singular' => 'transaction',
				'plural'   => 'transactions',
			)
		);

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
			'date'        => __( 'Date', 'wp-ever-accounting' ),
			'amount'      => __( 'Amount', 'wp-ever-accounting' ),
			'type'        => __( 'Type', 'wp-ever-accounting' ),
			'account_id'  => __( 'Account', 'wp-ever-accounting' ),
			'category_id' => __( 'Category', 'wp-ever-accounting' ),
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
			'type'        => array( 'type', false ),
			'account_id'  => array( 'account_id', false ),
			'category_id' => array( 'category_id', false ),
			'reference'   => array( 'reference', false ),
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
	 * This function renders most of the columns in the list table.
	 *
	 * @param  \EverAccounting\Abstracts\Transaction $transaction Transaction object.
	 * @param string                                $column_name The name of the column.
	 *
	 * @return string The column value.
	 * @since 1.0.2
	 */
	public function column_default( $transaction, $column_name ) {
		switch ( $column_name ) {
			case 'date':
				$date   = $transaction->get_payment_date();
				$type   = $transaction->get_type();
				$page   = 'expense' !== $type ? 'ea-sales' : 'ea-expenses';
				$tab    = 'expense' !== $type ? 'revenues' : 'payments';
				$object = 'expense' !== $type ? 'revenue_id' : 'payment_id';
				$value  = sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url(
						eaccounting_admin_url(
							array(
								'action' => 'edit',
								'page'   => $page,
								'tab'    => $tab,
								$object  => $transaction->get_id(),
							)
						)
					),
					$date
				);
				break;
			case 'amount':
				$value = eaccounting_price( $transaction->get_amount(), $transaction->get_currency_code() );
				break;
			case 'type':
				$type  = $transaction->get_type();
				$types = eaccounting_get_transaction_types();
				$value = array_key_exists( $type, $types ) ? $types[ $type ] : ucfirst( $type );
				break;
			case 'account_id':
				$account = eaccounting_get_account( $transaction->get_account_id( 'edit' ) );
				$value   = $account ? sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url(
						eaccounting_admin_url(
							array(
								'page'       => 'ea-banking',
								'tab'        => 'accounts',
								'action'     => 'view',
								'account_id' => $transaction->get_account_id(),
							)
						)
					),
					$account->get_name()
				) : '&mdash;';

				break;
			case 'category_id':
				$category = eaccounting_get_category( $transaction->get_category_id( 'edit' ) );
				$value    = $category ? $category->get_name() : '&mdash;';
				break;
			case 'reference':
				$value = ! empty( $transaction->get_reference() ) ? $transaction->get_reference() : '&mdash;';
				break;
			default:
				return parent::column_default( $transaction, $column_name );
		}

		return apply_filters( 'eaccounting_transaction_list_table_' . $column_name, $value, $transaction );
	}

	/**
	 * Renders the message to be displayed when there are no items.
	 *
	 * @return void
	 * @since  1.0.2
	 */
	public function no_items() {
		esc_html_e( 'There is no transactions found.', 'wp-ever-accounting' );
	}

	/**
	 * Extra controls to be displayed between bulk actions and pagination.
	 *
	 * @param string $which The location of the extra table nav markup: 'top' or 'bottom'.
	 *
	 * @since 1.0.2
	 */
	public function extra_tablenav( $which ) {
		if ( 'stop' === $which ) {
			$account_id = filter_input( INPUT_GET, 'account_id', FILTER_SANITIZE_NUMBER_INT );
			$start_date = filter_input( INPUT_GET, 'start_date', FILTER_SANITIZE_STRING );
			$end_date   = filter_input( INPUT_GET, 'end_date', FILTER_SANITIZE_STRING );
			echo '<div class="alignleft actions ea-table-filter">';
			submit_button( __( 'Filter', 'wp-ever-accounting' ), 'action', false, false );
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
		$nonce = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
		if ( $nonce ) {
			wp_safe_redirect(
				remove_query_arg(
					array(
						'transaction_id',
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
	 * Retrieve the view types
	 *
	 * @access public
	 * @return array $views All the views available
	 * @since 1.0.2
	 */
	public function get_views() {
		$tab           = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		$base          = eaccounting_admin_url( [ 'tab' => $tab ] );
		$current       = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING );
		$total_count   = '&nbsp;<span class="count">(' . $this->total_count . ')</span>';
		$income_count  = '&nbsp;<span class="count">(' . $this->income_count . ')</span>';
		$expense_count = '&nbsp;<span class="count">(' . $this->expense_count . ')</span>';

		$views = array(
			'all'     => sprintf( '<a href="%s"%s>%s</a>', esc_url( remove_query_arg( 'type', $base ) ), 'all' === $current || '' === $current ? ' class="current"' : '', __( 'All', 'wp-ever-accounting' ) . $total_count ),
			'income'  => sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'type', 'income', $base ) ), 'income' === $current ? ' class="current"' : '', __( 'Income', 'wp-ever-accounting' ) . $income_count ),
			'expense' => sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'type', 'expense', $base ) ), 'expense' === $current ? ' class="current"' : '', __( 'Expense', 'wp-ever-accounting' ) . $expense_count ),
		);

		return $views;
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
		$customer_id = filter_input( INPUT_GET, 'customer_id', FILTER_SANITIZE_NUMBER_INT );
		$start_date  = filter_input( INPUT_GET, 'start_date', FILTER_SANITIZE_STRING );
		$end_date    = filter_input( INPUT_GET, 'end_date', FILTER_SANITIZE_STRING );
		$type        = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING );

		$per_page = $this->per_page;

		$args = wp_parse_args(
			$this->query_args,
			array(
				'type'        => $type,
				'per_page'    => $per_page,
				'page'        => $page,
				'number'      => $per_page,
				'offset'      => $per_page * ( $page - 1 ),
				'search'      => $search,
				'orderby'     => eaccounting_clean( $orderby ),
				'order'       => eaccounting_clean( $order ),
				'category_id' => $category_id,
				'account_id'  => $account_id,
				'contact_id'  => $customer_id,
			)
		);

		if ( ! empty( $start_date ) && ! empty( $end_date ) ) {
			$args['payment_date'] = array(
				'before' => wp_date( 'Y-m-d', strtotime( $end_date ) ),
				'after'  => wp_date( 'Y-m-d', strtotime( $start_date ) ),
			);
		}

		$args                = apply_filters( 'eaccounting_transaction_table_query_args', $args, $this );
		$this->items         = eaccounting_get_transactions( $args );
		$this->income_count  = eaccounting_get_transactions(
			array_merge(
				$args,
				array(
					'type'        => 'income',
					'count_total' => true,
				)
			)
		);
		$this->expense_count = eaccounting_get_transactions(
			array_merge(
				$args,
				array(
					'type'        => 'expense',
					'count_total' => true,
				)
			)
		);
		$this->total_count   = $this->income_count + $this->expense_count;
		switch ( $type ) {
			case 'income':
				$total_items = $this->income_count;
				break;
			case 'expense':
				$total_items = $this->expense_count;
				break;
			case 'any':
			default:
				$total_items = $this->total_count;
				break;
		}

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);
	}
}
