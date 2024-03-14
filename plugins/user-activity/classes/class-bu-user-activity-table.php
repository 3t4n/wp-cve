<?php
defined( 'ABSPATH' ) || die( 'No soup for you' );

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * User table, list over user activity
 */
class Bu_User_Activity_Table extends WP_List_Table {
	/**
	 * Short name for class, used for translations
	 *
	 * @var string
	 */
	private $short_name = 'buua';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			[
				'singular' => 'user_activity',
				'plural'   => 'user_activities',
				'ajax'     => true,
			]
		);
	}

	/**
	 * Get columns
	 *
	 * @return array columns
	 */
	public function get_columns() {
		$columns = [
			'userid'   => __( 'ID', 'buua' ),
			'username' => __( 'Name', 'buua' ),
			'total'    => __( 'Total', 'buua' ),
			'latest'   => __( 'Latest post date', 'buua' ),
		];
		return $columns;
	}

	/**
	 * Get column defaults
	 *
	 * @param  object $item        Item
	 * @param  string $column_name Column name
	 * @return string              Column default
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'userid':
			case 'total':
			case 'latest':
				return $item->$column_name;
			default:
				return var_export( $item, true );
		}
	}

	/**
	 * Username column
	 *
	 * @param  object $item Item
	 * @return void
	 */
	public function column_username( $item ) {
		$userdata = get_userdata( $item->userid );
		echo '<a href="' . get_edit_user_link( $item->userid ) . '">' . esc_attr( $userdata->display_name ) . '</a> (' . $item->username . ')';
	}

	/**
	 * Get list of sortable columns
	 *
	 * @return array Sortable columns
	 */
	public function get_sortable_columns() {
		$sortable = [
			'userid'   => [ 'b.ID', false ],
			'username' => [ 'user_nicename', false ],
			'total'    => [ 'total', false ],
			'latest'   => [ 'latest', false ],
		];
		return $sortable;
	}

	/**
	 * Get the data, here's where the magic happens
	 *
	 * @return void
	 */
	public function prepare_items() {
		global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();

		/**
		 * Handle request parameters
		 */
		$ptype     = ( isset( $_REQUEST['ptype'] ) && ! empty( $_REQUEST['ptype'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ptype'] ) ) : 'all' );
		$startdate = ( isset( $_REQUEST['startdate'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['startdate'] ) ) : '' );
		$enddate   = ( isset( $_REQUEST['enddate'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['enddate'] ) ) : '' );
		$username  = ( isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '' );

		$prepare_vars = [];

		/**
		 * User search
		 */
		$search_clause = '';
		if ( ! empty( $username ) ) {
			$search_clause  = 'and b.user_nicename = %s';
			$prepare_vars[] = $username;
		}

		/**
		 * Post type search
		 */
		$ptype_clause = '';
		if ( 'all' !== $ptype ) {
			$ptype_clause   = 'and a.post_type = %s';
			$prepare_vars[] = $ptype;
		} else {
			$args         = [ 'public' => true ];
			$post_types   = get_post_types( $args );
			$ptype_clause = sprintf(
				'and a.post_type IN (%s)',
				implode(
					',',
					array_map(
						function( $a ) {
							return "'" . $a . "'";
						},
						$post_types
					)
				)
			);
		}

		/**
		 * Start date search
		 */
		$startdate_clause = '';
		$enddate_clause   = '';
		if ( ! empty( $startdate ) ) {
			$startdate_clause = 'and a.post_date >= %s';
			$prepare_vars[]   = $startdate;
		}

		/**
		 * End date search
		 */
		if ( ! empty( $enddate ) ) {
			$enddate_clause = 'and a.post_date <= %s';
			$prepare_vars[] = $enddate;
		}

		/**
		 * Setup the query
		 */
		$query = 'select b.ID userid, b.user_nicename username, count(*) as total, max(a.post_date) as latest '
			. "from {$wpdb->users} b, {$wpdb->posts} a "
			. "where a.post_author = b.ID {$ptype_clause} {$search_clause} {$startdate_clause} {$enddate_clause} "
			. 'group by b.user_nicename';

		/**
		 * Handle the ordering
		 */
		$orderby = ! empty( $_GET['orderby'] ) ? esc_sql( $_GET['orderby'] ) : 'total';
		$order   = ! empty( $_GET['order'] ) ? esc_sql( $_GET['order'] ) : 'DESC';
		if ( ! empty( $orderby ) && ! empty( $order ) ) {
			$query .= ' ORDER BY ' . $orderby . ' ' . $order;
		}

		if ( false !== strpos( $query, '%s' ) ) {
			$totalitems = $wpdb->query( $wpdb->prepare( $query, $prepare_vars ) );
		} else {
			$totalitems = $wpdb->query( $query );
		}
		if ( empty( $per_page ) || $per_page < 1 ) {
			$per_page = $screen->get_option( 'per_page', 'default' );
		}

		/**
		 * Paging
		 */
		$paged = ! empty( $_GET['paged'] ) ? esc_sql( $_GET['paged'] ) : '';
		if ( empty( $paged ) || ! is_numeric( $paged ) || $paged <= 0 ) {
			$paged = 1;
		}

		$totalpages = ceil( $totalitems / $per_page );

		if ( ! empty( $paged ) && ! empty( $per_page ) ) {
			$offset = ( $paged - 1 ) * $per_page;
			$query .= ' LIMIT '. (int) $offset . ',' . (int) $per_page;
		}

		/* -- Register the pagination -- */
		$this->set_pagination_args(
			[
				'total_items' => $totalitems,
				'total_pages' => $totalpages,
				'per_page' => $per_page,
			]
		);

		$columns = $this->get_columns();
		$hidden = [];
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = [ $columns, $hidden, $sortable ];

		/**
		 * Fetch the items
		 */
		if ( false !== strpos( $query, '%s' ) ) {
			$this->items = $wpdb->get_results( $wpdb->prepare( $query, $prepare_vars ) );
		} else {
			$this->items = $wpdb->get_results( $query );
		}
	}

	/**
	 * Quick links to post type views
	 *
	 */
	public function get_views() {
		$views   = [];
		$current = ( ! empty( $_REQUEST['ptype'] ) ? $_REQUEST['ptype'] : 'all' );

		//All link
		$class        = ( 'all' === $current ? ' class="current"' : '' );
		$all_url      = remove_query_arg( 'ptype' );
		$views['all'] = "<a href='{$all_url }' {$class} >All</a>";

		$args       = [ 'public' => true ];
		$post_types = get_post_types( $args );
		foreach ( $post_types  as $post_type ) {
			$name           = $post_type;
			$url            = add_query_arg( 'ptype', $name );
			$class          = ( $current == $name ? ' class="current"' : '' );
			$ucname         = ucfirst( $name );
			$views[ $name ] = "<a href='{$url}' {$class} >{$ucname}</a>";
		}
		return $views;
	}

	/**
	 * Filter form items
	 * post type, start date and end date
	 *
	 * @param  string $which Which
	 */
	public function extra_tablenav( $which ) {
		if ( 'top' != $which ) {
			return;
		}
		$ptype     = isset( $_GET['ptype'] ) ? sanitize_text_field( wp_unslash( $_GET['ptype'] ) ) : '';
		$startdate = isset( $_GET['startdate'] ) ? sanitize_text_field( wp_unslash( $_GET['startdate'] ) ) : '';
		$enddate   = isset( $_GET['enddate'] ) ? sanitize_text_field( wp_unslash( $_GET['enddate'] ) ) : '';
		?>
		<div class="alignleft actions">
			<label class="screen-reader-text" for="ptype"><?php esc_html_e( 'Post type&hellip;', 'buua' ); ?></label>
			<select name="ptype" id="ptype">
				<option <?php selected( $ptype, '' ); ?> value=''><?php esc_html_e( 'Post type&hellip;', 'buua' ); ?></option>
				<?php
				$args       = [ 'public' => true ];
				$post_types = get_post_types( $args );
				foreach ( $post_types  as $post_type ) : ?>
					<option <?php selected( $ptype, $post_type ); ?> value="<?php echo $post_type; ?>"><?php echo ucfirst( $post_type ); ?></option>
				<?php endforeach ?>
			</select>
			<input placeholder="<?php esc_attr_e( 'Start date', 'buua' ); ?>" type="text" id="startdate" name="startdate" class="startdate datepicker" value="<?php echo esc_html( $startdate ); ?>" />
			<input placeholder="<?php esc_attr_e( 'End date', 'buua' ); ?>" type="text" id="enddate" name ="enddate" class="enddate datepicker" value="<?php echo $enddate; ?>" />
			<?php submit_button( __( 'Filter', 'buua' ), 'button', false, false, [ 'id' => 'post-query-submit' ] ); ?>
		</div>
		<?php
	}

}
