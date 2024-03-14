<?php
/**
 * List tables.
 *
 * @package    EverAccounting
 * @subpackage Abstracts
 * @version    1.0.2
 */

defined( 'ABSPATH' ) || exit();

// Load WP_List_Table if not loaded.
if ( ! class_exists( '\WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class.
 *
 * @since 1.0.0
 */
abstract class EverAccounting_List_Table extends \WP_List_Table {
	/**
	 * Optional arguments to pass when preparing items.
	 *
	 * @since  1.0.2
	 * @var    array
	 */
	public $query_args = array();

	/**
	 * Optional arguments to pass when preparing items for display.
	 *
	 * @since  1.0.2
	 * @var    array
	 */
	public $display_args = array();

	/**
	 * Current screen object.
	 *
	 * @since  1.0.2
	 * @var    \WP_Screen
	 */
	public $screen;

	/**
	 * Default number of items to show per page
	 *
	 * @since 1.0.2
	 * @var string
	 */
	public $per_page = 20;

	/**
	 * Table classes.
	 *
	 * @since 1.1.0
	 * @var array
	 */
	public $table_classes = array();

	/**
	 * Sets up the list table instance.
	 *
	 * @access public
	 *
	 * @param array $args {
	 *                                    Optional. Arbitrary display and query arguments to pass through to the list table.
	 *                                    Default empty array.
	 *
	 * @type string $singular Singular version of the list table item.
	 * @type string $plural Plural version of the list table item.
	 * @type array $query_args Optional. Arguments to pass through to the query used for preparing items.
	 *                               Accepts any valid arguments accepted by the given query methods.
	 * @type array $display_args {
	 *         Optional. Arguments to pass through for use when displaying queried items.
	 *
	 * @type string $pre_table_callback Callback to fire at the top of the list table, just before the list
	 *                                            table navigation is displayed. Default empty (disabled).
	 * @type bool $hide_table_nav Whether to hide the entire table navigation at the top and bottom
	 *                                            of the list table. Will hide the bulk actions, extra tablenav, and
	 *                                            pagination. Use `$hide_bulk_options`, or `$hide_pagination` for more
	 *                                            fine-grained control. Default false.
	 * @type bool $hide_bulk_options Whether to hide the bulk options controls at the top and bottom of
	 *                                            the list table. Default false.
	 * @type array $hide_pagination Whether to hide the pagination controls at the top and bottom of the
	 *                                            list table. Default false.
	 * @type bool $columns_to_hide An array of column IDs to hide for the current instance of the list
	 *                                            table. Note: other columns may be already hidden depending on current
	 *                                            user settings determined by screen options column controls. Default
	 *                                            empty array.
	 * @type bool $hide_column_controls Whether to hide the screen options column controls for the list table.
	 *                                            This should always be enabled when instantiating a standalone list
	 *                                            table in sub-views such as or view_payout due to
	 *                                            conflicts introduced in column controls generated for list tables
	 *                                            instantiated at the primary-view level. Default false.
	 *     }
	 * }
	 * @see    WP_List_Table::__construct()
	 *
	 * @since  1.0.2
	 */
	public function __construct( $args = array() ) {
		$this->screen = get_current_screen();
		$display_args = array(
			'pre_table_callback'   => '',
			'hide_table_nav'       => false,
			'hide_extra_table_nav' => false,
			'hide_bulk_options'    => false,
			'hide_pagination'      => false,
			'columns_to_hide'      => array(),
			'hide_column_controls' => false,
		);

		if ( ! empty( $args['query_args'] ) ) {
			$this->query_args = $args['query_args'];

			unset( $args['query_args'] );
		}

		if ( ! empty( $args['display_args'] ) ) {
			$this->display_args = wp_parse_args( $args['display_args'], $display_args );

			unset( $args['display_args'] );
		} else {
			$this->display_args = $display_args;
		}

		$args = wp_parse_args(
			$args,
			array(
				'ajax' => false,
			)
		);

		parent::__construct( $args );
	}

	/**
	 * Show blank slate.
	 *
	 * @param string $which String which tablenav is being shown.
	 *
	 * @since 1.0.2
	 */
	public function maybe_render_blank_state( $which ) {
		if ( 'bottom' === $which && $this->is_empty() ) {

			$this->render_blank_state();

			echo '<style type="text/css">.wp-list-table, .tablenav.top, .tablenav.bottom .actions, .wrap .subsubsub  { display: none; } .tablenav.bottom { height: auto; } </style>';
		}
	}

	/**
	 * Check if there is contents in the database.
	 *
	 * @return bool
	 * @since 1.0.2
	 */
	protected function is_empty() {
		return false;
	}

	/**
	 * Render blank state. Extend to add content.
	 */
	protected function render_blank_state() {

	}

	/**
	 * Prepares columns for display.
	 *
	 * Applies display arguments passed in the constructor to the list of columns.
	 *
	 * @param array $columns List of columns.
	 *
	 * @return array (Maybe) filtered list of columns.
	 * @since 1.1.0
	 */
	public function prepare_columns( $columns ) {
		if ( ! empty( $this->display_args['columns_to_hide'] ) ) {
			$columns_to_hide = $this->display_args['columns_to_hide'];

			foreach ( $columns_to_hide as $column ) {
				if ( array_key_exists( $column, $columns ) ) {
					unset( $columns[ $column ] );
				}
			}
		}

		return $columns;
	}

	/**
	 * Retrieve the table columns
	 *
	 * @return array $columns Array of all the list table columns
	 * @since 1.0.2
	 */
	public function get_columns() {
		$this->prepare_columns( $this->define_columns() );

		return $this->prepare_columns( $this->define_columns() );
	}

	/**
	 * Define which columns to show on this screen.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	public function define_columns() {
		return array();
	}

	/**
	 * Define which columns are sortable
	 *
	 * @return array
	 * @since 1.0.2
	 */
	public function get_sortable_columns() {
		return $this->prepare_columns( $this->define_sortable_columns() );
	}

	/**
	 * Define sortable columns.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function define_sortable_columns() {
		return array();
	}

	/**
	 * Retrieves a list of all, hidden,sortable, and primary columns, with filters applied.
	 *
	 * Also sets up column show/hide controls.
	 *
	 * @access protected
	 * @return array Column headers.
	 * @since  1.1.0
	 */
	public function get_column_info() {
		if ( true === $this->display_args['hide_column_controls'] ) {
			$columns = $this->get_columns();

			$hidden = array();

			$sortable = $this->get_sortable_columns();

			$this->_column_headers = array( $columns, $hidden, $sortable, $this->get_primary_column_name() );
		} else {
			$this->_column_headers = parent::get_column_info();
		}

		return $this->_column_headers;
	}

	/**
	 * Get an associative array ( option_name => option_title ) with the list
	 * of bulk actions available on this table.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	public function get_bulk_actions() {
		$columns = $this->define_bulk_actions();

		return apply_filters( 'eaccounting_' . $this->list_table_type . '_table_bulk_actions', $columns, $this );
	}

	/**
	 * Show the search field
	 *
	 * @param string $text Label for the search box.
	 * @param string $input_id ID of the search box.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	public function search_box( $text, $input_id ) {
		if ( empty( filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING ) ) && ! $this->has_items() ) {
			return;
		}

		$input_id = $input_id . '-search-input';
		$orderby  = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_STRING );
		$order    = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_STRING );

		if ( ! empty( $orderby ) ) {
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $orderby ) . '" />';
		}
		if ( ! empty( $order ) ) {
			echo '<input type="hidden" name="order" value="' . esc_attr( $order ) . '" />';
		}
		?>
		<p class="search-box">
			<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $text ); ?>:</label>
			<input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>"/>
			<?php submit_button( $text, 'button', false, false, array( 'ID' => 'search-submit' ) ); ?>
		</p>
		<?php
	}

	/**
	 * Generates the table navigation above or below the table.
	 *
	 * @param string $which Which location the bulk actions are being rendered for.Will be 'top' or 'bottom'.
	 *
	 * @since  1.0.2
	 */
	public function display_tablenav( $which ) {
		if ( 'top' === $which ) {
			wp_nonce_field( 'bulk-' . $this->_args['plural'] );
		}

		if ( ! empty( $this->display_args['pre_table_callback'] ) && is_callable( $this->display_args['pre_table_callback'] ) && 'top' === $which ) {
			call_user_func( $this->display_args['pre_table_callback'] );
		}

		$this->maybe_render_blank_state( $which );

		if ( true !== $this->display_args['hide_table_nav'] ) :
			?>
			<div class="tablenav <?php echo esc_attr( $which ); ?>">

				<?php if ( $this->has_items() && true !== $this->display_args['hide_bulk_options'] ) : ?>
					<div class="alignleft actions bulkactions">
						<?php $this->bulk_actions( $which ); ?>
					</div>
					<?php
				endif;
				if ( true !== $this->display_args['hide_extra_table_nav'] ) :
					$this->extra_tablenav( $which );
				endif;

				if ( true !== $this->display_args['hide_pagination'] ) :
					$this->pagination( $which );
				endif;
				?>

				<br class="clear"/>
			</div>
			<?php
		endif;
	}

	/**
	 * This function renders most of the columns in the list table.
	 *
	 * @param Object $item      The current item.
	 * @param string $column_name The name of the column.
	 *
	 * @return string The column value.
	 * @since 1.0.2
	 */
	public function column_default( $item, $column_name ) {
		$getter = "get_$column_name";
		if ( method_exists( $item, $getter ) ) {
			return esc_html( $item->$getter() );
		}

		return '&mdash;';
	}

	/**
	 * Gets a list of CSS classes for the WP_List_Table table tag.
	 *
	 * @return string[] Array of CSS classes for the table tag.
	 * @since 3.1.0
	 */
	public function get_table_classes() {
		$mode = get_user_setting( 'posts_list_mode', 'list' );

		$mode_class = esc_attr( 'table-view-' . $mode );

		$table_class = implode( ' ', wp_parse_list( $this->table_classes ) );

		return array( 'widefat', 'fixed', 'striped', $mode_class, $this->_args['plural'], $table_class );
	}
}
