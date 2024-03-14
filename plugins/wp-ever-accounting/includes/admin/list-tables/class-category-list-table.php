<?php
/**
 * Categories Admin List Table.
 *
 * @since       1.0.2
 * @subpackage  EverAccounting\Admin\ListTables
 * @package     EverAccounting
 */

use EverAccounting\Models\Category;

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( '\EverAccounting_List_Table' ) ) {
	require_once dirname( __FILE__ ) . '/class-list-table.php';
}

/**
 * Class EverAccounting_Category_List_Table
 *
 * @since 1.1.0
 */
class EverAccounting_Category_List_Table extends EverAccounting_List_Table {
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
	 * The base URL for the list table.
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
	 * @see    WP_List_Table::__construct()
	 *
	 * @since  1.0.2
	 */
	public function __construct( $args = array() ) {
		$args           = (array) wp_parse_args(
			$args,
			array(
				'singular' => 'category',
				'plural'   => 'categories',
			)
		);
		$this->base_url = admin_url( 'admin.php?page=ea-settings&tab=categories' );
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

		return ! (int) $wpdb->get_var( "SELECT COUNT(id) from {$wpdb->prefix}ea_categories" );
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
				'page'   => 'ea-settings',
				'tab'    => 'categories',
				'action' => 'edit',
			)
		);
		?>
		<div class="ea-empty-table">
			<p class="ea-empty-table__message">
				<?php echo esc_html__( 'Create categories for incomes, expenses, and see how your business\'s flow at a glance. Track which category is your business is spending most as well is making money.', 'wp-ever-accounting' ); ?>
			</p>
			<a href="<?php echo esc_url( $url ); ?>" class="button-primary ea-empty-table__cta"><?php esc_html_e( 'Add Categories', 'wp-ever-accounting' ); ?></a>
			<a href="https://wpeveraccounting.com/docs/general/how-to-add-categories/?utm_source=listtable&utm_medium=link&utm_campaign=admin" class="button-secondary ea-empty-table__cta" target="_blank"><?php esc_html_e( 'Learn More', 'wp-ever-accounting' ); ?></a>
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
			'cb'      => '<input type="checkbox" />',
			'name'    => __( 'Name', 'wp-ever-accounting' ),
			'type'    => __( 'Type', 'wp-ever-accounting' ),
			'color'   => __( 'Color', 'wp-ever-accounting' ),
			'enabled' => __( 'Enabled', 'wp-ever-accounting' ),
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
			'name'    => array( 'name', false ),
			'type'    => array( 'type', false ),
			'color'   => array( 'color', false ),
			'enabled' => array( 'enabled', false ),
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
			'enable'  => __( 'Enable', 'wp-ever-accounting' ),
			'disable' => __( 'Disable', 'wp-ever-accounting' ),
			'delete'  => __( 'Delete', 'wp-ever-accounting' ),
		);
	}


	/**
	 * Define primary column.
	 *
	 * @return string
	 * @since 1.0.2
	 */
	public function get_primary_column_name() {
		return 'name';
	}


	/**
	 * Renders the checkbox column in the categories list table.
	 *
	 * @param Category $category The current object.
	 *
	 * @return string Displays a checkbox.
	 * @since  1.0.2
	 */
	public function column_cb( $category ) {
		return sprintf( '<input type="checkbox" name="category_id[]" value="%d"/>', $category->get_id() );
	}

	/**
	 * This function renders most of the columns in the list table.
	 *
	 * @param Category $category The current object.
	 * @param string   $column_name The name of the column.
	 *
	 * @return string The column value.
	 * @since 1.0.2
	 */
	public function column_default( $category, $column_name ) {
		$category_id = $category->get_id();

		switch ( $column_name ) {
			case 'name':
				$name     = $category->get_name();
				$edit_url = eaccounting_admin_url(
					array(
						'page'        => 'ea-settings',
						'tab'         => 'categories',
						'action'      => 'edit',
						'category_id' => $category_id,
					)
				);
				$del_url  = eaccounting_admin_url(
					array(
						'page'        => 'ea-settings',
						'tab'         => 'categories',
						'action'      => 'delete',
						'category_id' => $category_id,
						'_wpnonce'    => wp_create_nonce( 'category-nonce' ),
					)
				);
				$actions  = array(
					'edit'   => sprintf( '<a href="%1$s">%2$s</a>', esc_url( $edit_url ), __( 'Edit', 'wp-ever-accounting' ) ),
					'delete' => sprintf( '<a href="%1$s" class="del">%2$s</a>', esc_url( $del_url ), __( 'Delete', 'wp-ever-accounting' ) ),
				);
				$value    = '<a href="' . $edit_url . '">' . $name . '</a>' . $this->row_actions( $actions );
				break;
			case 'type':
				$type  = $category->get_type();
				$types = eaccounting_get_category_types();
				$value = array_key_exists( $type, $types ) ? $types[ $type ] : ucfirst( $type );
				break;
			case 'color':
				$value = sprintf( '<span class="dashicons dashicons-marker" style="color:%s;">&nbsp;</span>', esc_attr( $category->get_color() ) );
				break;
			case 'enabled':
				$value  = '<label class="ea-toggle">';
				$value .= '<input type="checkbox" class="category-status" style="" value="true" data-id="' . esc_attr( $category->get_id() ) . '" ' . checked( $category->is_enabled(), true, false ) . '>';
				$value .= '<span data-label-off="' . esc_html__( 'No', 'wp-ever-accounting' ) . '" data-label-on="' . esc_html__( 'Yes', 'wp-ever-accounting' ) . '" class="ea-toggle-slider"></span>';
				$value .= '</label>';
				break;
			default:
				return parent::column_default( $category, $column_name );
		}

		return apply_filters( 'eaccounting_category_list_table_' . $column_name, $value, $category );
	}

	/**
	 * Renders the message to be displayed when there are no items.
	 *
	 * @return void
	 * @since  1.0.2
	 */
	public function no_items() {
		esc_html_e( 'There is no categories found.', 'wp-ever-accounting' );
	}

	/**
	 * Process the bulk actions
	 *
	 * @return void
	 * @since 1.0.2
	 */
	public function process_bulk_action() {
		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'bulk-categories' ) && ! wp_verify_nonce( $nonce, 'category-nonce' ) ) {
			return;
		}
		$ids = isset( $_GET['category_id'] ) ? wp_parse_id_list( wp_unslash( $_GET['category_id'] ) ) : false;

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
					eaccounting_insert_category(
						array(
							'id'      => $id,
							'enabled' => '1',
						)
					);
					break;
				case 'disable':
					eaccounting_insert_category(
						array(
							'id'      => $id,
							'enabled' => '0',
						)
					);
					break;
				case 'delete':
					eaccounting_delete_category( $id );
					break;
				default:
					do_action( 'eaccounting_categories_do_bulk_action_' . $this->current_action(), $id );
			}
		}

		if ( $nonce ) {
			wp_safe_redirect(
				remove_query_arg(
					array(
						'category_id',
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
	 * Retrieve the view types
	 *
	 * @access public
	 * @return array $views All the views available
	 * @since  1.0.2
	 */
	public function get_views() {
		$base           = eaccounting_admin_url( array( 'tab' => 'categories' ) );
		$current        = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING );
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
	 * @since 1.0.2
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
				'search'   => $search,
				'status'   => $status,
				'orderby'  => eaccounting_clean( $orderby ),
				'order'    => eaccounting_clean( $order ),
			)
		);

		$args = apply_filters( 'eaccounting_category_table_query_args', $args, $this );

		$this->items = eaccounting_get_categories( $args );

		$this->active_count = eaccounting_get_categories(
			array_merge(
				$args,
				array(
					'count_total' => true,
					'status'      => 'active',
				)
			)
		);

		$this->inactive_count = eaccounting_get_categories(
			array_merge(
				$args,
				array(
					'count_total' => true,
					'status'      => 'inactive',
				)
			)
		);

		$this->total_count = absint( $this->active_count ) + absint( $this->inactive_count );
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
