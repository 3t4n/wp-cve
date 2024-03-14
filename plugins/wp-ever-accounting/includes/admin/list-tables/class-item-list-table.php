<?php
/**
 * Item list table
 *
 * Admin item list table, show all the item information.
 *
 * @since       1.1.0
 * @subpackage  EverAccounting\Admin\ListTables
 * @package     EverAccounting
 */

use EverAccounting\Models\Item;

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( '\EverAccounting_List_Table' ) ) {
	require_once dirname( __FILE__ ) . '/class-list-table.php';
}

/**
 * Class EverAccounting_Item_List_Table
 *
 * @since 1.1.0
 */
class EverAccounting_Item_List_Table extends EverAccounting_List_Table {
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
				'singular' => 'item',
				'plural'   => 'items',
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

		return ! (int) $wpdb->get_var( "SELECT COUNT(id) from {$wpdb->prefix}ea_items" );
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
				'page'   => 'ea-items',
				'tab'    => 'items',
				'action' => 'edit',
			)
		);
		?>
		<div class="ea-empty-table">
			<p class="ea-empty-table__message">
				<?php echo esc_html__( 'Use items as products or services. You can use items when creating invoices and bills to have the price, tax etc fields.', 'wp-ever-accounting' ); ?>
			</p>
			<a href="<?php echo esc_url( $url ); ?>" class="button-primary ea-empty-table__cta"><?php esc_html_e( 'Add Item', 'wp-ever-accounting' ); ?></a>
			<a href="https://wpeveraccounting.com/docs/general/how-to-add-items/?utm_source=listtable&utm_medium=link&utm_campaign=admin" class="button-secondary ea-empty-table__cta" target="_blank"><?php esc_html_e( 'Learn More', 'wp-ever-accounting' ); ?></a>
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
			'thumb'          => '<span class="ea-thumb dashicons dashicons-format-image">&nbsp;</span>',
			'name'           => __( 'Name', 'wp-ever-accounting' ),
			'sale_price'     => __( 'Sale Price', 'wp-ever-accounting' ),
			'purchase_price' => __( 'Purchase Price', 'wp-ever-accounting' ),
			'category_id'    => __( 'Category', 'wp-ever-accounting' ),
			'enabled'        => __( 'Enabled', 'wp-ever-accounting' ),
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
			'name'           => array( 'name', false ),
			'category_id'    => array( 'category_id', false ),
			'sale_price'     => array( 'sale_price', false ),
			'purchase_price' => array( 'purchase_price', false ),
			'enabled'        => array( 'enabled', false ),
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
			'enable'  => __( 'Enable', 'wp-ever-accounting' ),
			'disable' => __( 'Disable', 'wp-ever-accounting' ),
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
		return 'name';
	}

	/**
	 * Renders the checkbox column in the items list table.
	 *
	 * @param Item $item The current item object.
	 *
	 * @return string Displays a checkbox.
	 * @since  1.1.0
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="item_id[]" value="%d"/>', esc_attr( $item->get_id() ) );
	}

	/**
	 * This function renders most of the columns in the list table.
	 *
	 * @param Item $item        Contains all the data of the item.
	 * @param Item $column_name The name of the column.
	 *
	 * @return string The column value.
	 * @since 1.1.0
	 */
	public function column_default( $item, $column_name ) {
		$item_id = $item->get_id();
		switch ( $column_name ) {
			case 'thumb':
				$edit_url  = eaccounting_admin_url(
					array(
						'page'    => 'ea-items',
						'tab'     => 'items',
						'action'  => 'edit',
						'item_id' => $item_id,
					)
				);
				$thumb_url = wp_get_attachment_thumb_url( $item->get_thumbnail_id() );
				$thumb_url = empty( $thumb_url ) ? eaccounting()->plugin_url( '/dist/images/placeholder.png' ) : $thumb_url;
				$value     = '<a href="' . esc_url( $edit_url ) . '"><img src="' . $thumb_url . '" height="36" width="36" alt="' . $item->get_name() . '"></a>';
				break;
			case 'name':
				$edit_url = eaccounting_admin_url(
					array(
						'page'    => 'ea-items',
						'tab'     => 'items',
						'action'  => 'edit',
						'item_id' => $item_id,
					)
				);
				$nonce    = wp_create_nonce( 'item-nonce' );
				$actions  = array(
					'id'     => 'ID: ' . $item_id,
					'edit'   => '<a href="' . eaccounting_admin_url(
						array(
							'page'    => 'ea-items',
							'tab'     => 'items',
							'action'  => 'edit',
							'item_id' => $item_id,
						)
					) . '">' . __( 'Edit', 'wp-ever-accounting' ) . '</a>',
					'delete' => '<a href="' . eaccounting_admin_url(
						array(
							'page'     => 'ea-items',
							'tab'      => 'items',
							'action'   => 'delete',
							'item_id'  => $item_id,
							'_wpnonce' => $nonce,
						)
					) . '" class="del">' . __( 'Delete', 'wp-ever-accounting' ) . '</a>',
				);
				$value    = '<a href="' . esc_url( $edit_url ) . '"><strong>' . $item->get_name() . '</strong></a>' . $this->row_actions( $actions );
				break;
			case 'sale_price':
				$value = eaccounting_price( $item->get_sale_price() );
				break;
			case 'purchase_price':
				$value = eaccounting_price( $item->get_purchase_price() );
				break;
			case 'category_id':
				$category = eaccounting_get_category( $item->get_category_id() );
				$value    = $category ? $category->get_name() : '&mdash;';
				break;
			case 'enabled':
				$value  = '<label class="ea-toggle">';
				$value .= '<input type="checkbox" class="item-status" style="" value="true" data-id="' . esc_attr( $item->get_id() ) . '" ' . checked( $item->is_enabled(), true, false ) . '>';
				$value .= '<span data-label-off="' . esc_html__( 'No', 'wp-ever-accounting' ) . '" data-label-on="' . esc_html__( 'Yes', 'wp-ever-accounting' ) . '" class="ea-toggle-slider"></span>';
				$value .= '</label>';
				break;
			default:
				return parent::column_default( $item, $column_name );
		}

		return apply_filters( 'eaccounting_item_list_table_' . $column_name, $value, $item );
	}

	/**
	 * Renders the message to be displayed when there are no items.
	 *
	 * @return void
	 * @since  1.1.0
	 */
	public function no_items() {
		esc_html_e( 'There is no items found.', 'wp-ever-accounting' );
	}

	/**
	 * Process the bulk actions
	 *
	 * @return void
	 * @since 1.1.0
	 */
	public function process_bulk_action() {
		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'bulk-items' ) && ! wp_verify_nonce( $nonce, 'item-nonce' ) ) {
			return;
		}

		$ids = isset( $_GET['item_id'] ) ? wp_parse_id_list( wp_unslash( $_GET['item_id'] ) ) : false;

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
				case 'enable':
					eaccounting_insert_item(
						array(
							'id'      => $id,
							'enabled' => '1',
						)
					);
					break;
				case 'disable':
					eaccounting_insert_item(
						array(
							'id'      => $id,
							'enabled' => '0',
						)
					);
					break;
				case 'delete':
					eaccounting_delete_item( $id );
					break;
				default:
					do_action( 'eaccounting_items_do_bulk_action_' . $this->current_action(), $id );
			}
		}

		if ( $nonce ) {
			wp_safe_redirect(
				remove_query_arg(
					array(
						'item_id',
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
	 * @since 1.1.0
	 */
	public function get_views() {
		$base           = eaccounting_admin_url();
		$current        = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );
		$total_count    = '&nbsp;<span class="count">(' . $this->total_count . ')</span>';
		$active_count   = '&nbsp;<span class="count">(' . $this->active_count . ')</span>';
		$inactive_count = '&nbsp;<span class="count">(' . $this->inactive_count . ')</span>';

		$views = array(
			'all'      => sprintf( '<a href="%s"%s>%s</a>', esc_url( remove_query_arg( 'status', $base ) ), 'all' === $current || '' === $current ? ' class="current"' : '', __( 'All', 'wp-ever-accounting' ) . $total_count ),
			'active'   => sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'status', 'active', $base ) ), 'active' === $current ? ' class="current"' : '', __( 'Active', 'wp-ever-accounting' ) . $active_count ),
			'inactive' => sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'status', 'inactive', $base ) ), 'inactive' === $current ? ' class="current"' : '', __( 'Inactive', 'wp-ever-accounting' ) . $inactive_count ),
		);

		return $views;
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

		$page    = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT, array( 'options' => array( 'default' => 1 ) ) );
		$search  = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
		$order   = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_STRING, array( 'options' => array( 'default' => 'DESC' ) ) );
		$orderby = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_STRING, array( 'options' => array( 'default' => 'id' ) ) );
		$status  = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );

		$per_page = $this->per_page;

		$args = wp_parse_args(
			$this->query_args,
			array(
				'number'   => $per_page,
				'offset'   => $per_page * ( $page - 1 ),
				'per_page' => $per_page,
				'page'     => $page,
				'status'   => $status,
				'search'   => $search,
				'orderby'  => eaccounting_clean( $orderby ),
				'order'    => eaccounting_clean( $order ),
			)
		);
		eaccounting_get_currencies(
			array(
				'return' => 'raw',
				'number' => '-1',
			)
		);

		$args        = apply_filters( 'eaccounting_item_table_query_args', $args, $this );
		$this->items = eaccounting_get_items( $args );

		$this->active_count = eaccounting_get_items(
			array_merge(
				$args,
				array(
					'status'      => 'active',
					'count_total' => true,
				)
			)
		);

		$this->inactive_count = eaccounting_get_items(
			array_merge(
				$args,
				array(
					'status'      => 'inactive',
					'count_total' => true,
				)
			)
		);

		$this->total_count = $this->active_count + $this->inactive_count;
		switch ( $status ) {
			case 'active':
				$total_items = $this->active_count;
				break;
			case 'inactive':
				$total_items = $this->inactive_count;
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
