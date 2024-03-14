<?php
/**
 * Iubenda list table forms.
 *
 * @package  Iubenda
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Iubenda_List_Table_Forms class.
 *
 * @class Iubenda_List_Table_Forms
 */
class Iubenda_List_Table_Forms extends WP_List_Table {

	/**
	 * Items.
	 *
	 * @var array
	 */
	public $items;

	/**
	 * Extra_items.
	 *
	 * @var array
	 */
	public $extra_items;

	/**
	 * Base_url.
	 *
	 * @var string
	 */
	public $base_url;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		global $status, $page;
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['hook_suffix'] = isset( $GLOBALS['hook_suffix'] ) ? $GLOBALS['hook_suffix'] : '';

		// set parent defaults.
		parent::__construct(
			array(
				'ajax' => false,
			)
		);

		$this->base_url = esc_url_raw( add_query_arg( array( 'view' => 'cons-configuration' ), iubenda()->base_url ) );
	}



	/**
	 * Prepare the items for the table to process.
	 */
	public function prepare_items() {
		$temp_status = iub_get_request_parameter( 'status' );
		if ( ! empty( $temp_status ) && array_key_exists( $temp_status, iubenda()->forms->statuses ) ) {
			$status = $temp_status;
		} else {
			$status = '';
		}

		$orderby = iub_get_request_parameter( 'orderby', 'date' );
		$order   = iub_get_request_parameter( 'order' );
		if ( ! in_array( (string) $order, array( 'asc', 'desc' ), true ) ) {
			$order = 'desc';
		}

		$per_page = 20;
		$page     = $this->get_pagenum();

		$args = array(
			'orderby'     => $orderby,
			'order'       => $order,
			'offset'      => 0,
			'number'      => 0,
			'post_status' => $status,
		);

		$items = iubenda()->forms->get_forms( $args );

		$offset = ( $page * $per_page ) - $per_page;
		if ( is_array( $items ) ) {
			$this->items       = array_slice( $items, $offset, $per_page );
			$this->extra_items = array_slice( $items, $per_page );
		}

		$this->set_pagination_args(
			array(
				'total_items' => count( $items ),
				'per_page'    => $per_page,
			)
		);

		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table.
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'title'  => __( 'Form Title', 'iubenda' ),
			'ID'     => __( 'Form ID', 'iubenda' ),
			'source' => __( 'Form Source', 'iubenda' ),
			'fields' => __( 'Fields', 'iubenda' ),
			'date'   => __( 'Date', 'iubenda' ),
		);

		return $columns;
	}

	/**
	 * Define the sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$columns = array(
			'title' => array( 'name', true ),
		);
		return $columns;
	}

	/**
	 * Handle single row content.
	 *
	 * @param   array $item  item.
	 *
	 * @return mixed
	 */
	public function single_row( $item ) {
		$classes   = array();
		$classes[] = 'item-' . $item->ID;
		?>
		<tr id="item-<?php echo esc_html( $item->ID ); ?>" class="<?php echo esc_html( implode( ' ', $classes ) ); ?>">
			<?php $this->single_row_columns( $item ); ?>
		</tr>
		<?php
	}

	/**
	 * Get the name of the default primary column.
	 *
	 * @return string Name of the default primary column, in this case, 'title'.
	 */
	public function get_default_primary_column_name() {
		return 'title';
	}

	/**
	 * Generate and display row actions links.
	 *
	 * @param object $item item.
	 * @param string $column_name column_name.
	 * @param string $primary primary.
	 * @return string|void
	 */
	public function handle_row_actions( $item, $column_name, $primary ) {
		if ( 'title' !== $column_name ) {
			return '';
		}

		$output = '';

		$del_nonce  = esc_html( '_wpnonce=' . wp_create_nonce( "delete-form_{$item->ID}" ) );
		$url        = add_query_arg( array( 'form_id' => $item->ID ), $this->base_url );
		$edit_url   = add_query_arg( array( 'view' => 'cons-form-edit' ), $url );
		$delete_url = add_query_arg(
			array(
				'view'   => 'cons-configuration',
				'action' => 'delete',
			),
			$url
		) . "&$del_nonce";

		// preorder it: View | Approve | Unapprove | Delete.
		$actions = array(
			'view'   => '',
			'delete' => '',
		);

		$actions['view']   = "<a href='$edit_url' aria-label='" . esc_attr__( 'Edit this form', 'iubenda' ) . "'>" . __( 'Edit', 'iubenda' ) . '</a>';
		$actions['delete'] = "<a href='$delete_url' aria-label='" . esc_attr__( 'Delete this form', 'iubenda' ) . "'>" . __( 'Delete', 'iubenda' ) . '</a>';

		$i       = 0;
		$output .= '<div class="row-actions">';
		foreach ( $actions as $action => $link ) {
			++$i;
			$sep     = ( 1 === $i ) ? $sep = '' : $sep = ' | ';
			$output .= '<span class="' . ( 'delete' === $action ? 'delete delete-form' : $action ) . '">' . $sep . $link . '</span>';
		}
		$output .= '</div>';

		return $output;
	}

	/**
	 * Define what data to show on each column of the table.
	 *
	 * @param array  $item item.
	 * @param string $column_name column_name.
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		$output = '';

		$temp_status = iub_get_request_parameter( 'status' );
		if ( ! empty( $temp_status ) && array_key_exists( $temp_status, iubenda()->forms->statuses ) ) {
			$status = $temp_status;
		} else {
			$status = '';
		}

		// print_r( $item );.
		// get columns content.
		switch ( $column_name ) {
			case 'ID':
				$output = $item->ID;
				break;
			case 'title':
				$url    = esc_url(
					wp_nonce_url(
						add_query_arg(
							array(
								'form_id' => $item->ID,
								'view'    => 'cons-form-edit',
							),
							$this->base_url
						),
						'iub_cons_nonce'
					)
				);
				$output = '<strong>' . ( current_user_can( 'edit_post', $item->ID ) ? '<a href="' . $url . '">' . $item->post_title . '</a>' : $item->post_title );

				if ( ! $status ) {
					if ( in_array( (string) $item->post_status, array( 'publish', 'needs_update' ), true ) ) {
						$output .= ' &mdash; ';
						$output .= '<span class="post-state to-map-state">' . iubenda()->forms->statuses[ $item->post_status ] . '</span>';
					}
				}

				$output .= '</strong>';

				break;
			case 'source':
				$output = array_key_exists( $item->form_source, iubenda()->forms->sources ) ? iubenda()->forms->sources[ $item->form_source ] : '&#8212;';
				break;
			case 'fields':
				$output = count( $item->form_fields );
				break;
			case 'date':
				$output = date_i18n( $item->post_date );
				break;
			default:
				break;
		}

		return $output;
	}

	/**
	 * Generate the table navigation above or below the table
	 *
	 * @since 3.1.0
	 * @param string $which which.
	 */
	protected function display_tablenav( $which ) {
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
			<?php
			$this->pagination( $which );
			?>

			<br class="clear" />
		</div>
		<?php
	}

	/**
	 * Extra tablenav
	 *
	 * @param string $which which.
	 */
	protected function extra_tablenav( $which ) {
		?>
		<div class="alignleft actions">
		<?php
		if ( 'top' === $which ) {
			ob_start();

			$this->sources_dropdown();

			$output = ob_get_clean();

			if ( ! empty( $output ) ) {
				echo wp_kses_post( $output );
				submit_button( __( 'Filter', 'iubenda' ), '', 'filter_action', false, array( 'id' => 'post-query-submit' ) );
			}
		}
		?>
		</div>
		<?php
	}

	/**
	 * Displays a sources drop-down for filtering on the list table.
	 *
	 * @return mixed
	 */
	protected function sources_dropdown() {
		if ( ! empty( iubenda()->forms->sources ) ) {

			$current = iub_get_request_parameter( 'source' );
			if ( ! in_array( (string) $current, iubenda()->forms->sources, true ) ) {
				$current = '';
			}

			echo '
				<label class="screen-reader-text" for="cat">' . esc_html__( 'Filter by source', 'iubenda' ) . '</label>
				<select name="source" id="filter-by-source">
					<option ' . selected( '', $current, false ) . 'value="">' . esc_html__( 'All form sources', 'iubenda' ) . '</option>';
			foreach ( iubenda()->forms->sources as $key => $label ) {
				echo '
					<option ' . selected( $key, $current, false ) . 'value="' . esc_html( $key ) . '">' . esc_html( $label ) . '</option>';
			}

			echo '</select>';
		}
	}

	/**
	 * Display views.
	 *
	 * @return array
	 */
	public function get_views() {
		$temp_status = iub_get_request_parameter( 'status' );
		if ( ! empty( $temp_status ) && array_key_exists( $temp_status, iubenda()->forms->statuses ) ) {
			$status = $temp_status;
		} else {
			$status = '';
		}

		$orderby = iub_get_request_parameter( 'orderby' );
		$order   = iub_get_request_parameter( 'order' );
		if ( ! in_array( (string) $order, array( 'asc', 'desc' ), true ) ) {
			$order = '';
		}

		$per_page = 20;

		$number      = (int) iub_get_request_parameter( 'number', ( $per_page + min( 8, $per_page ) ) );
		$page        = $this->get_pagenum();
		$items_total = 0;

		$args = array(
			'orderby' => $orderby,
			'order'   => $order,
			'offset'  => 0,
			'number'  => 0,
		);

		foreach ( iubenda()->forms->statuses as $key => $view ) {
			$args['post_status'] = $key;

			$items = iubenda()->forms->get_forms( $args );

			$items_count[ $key ] = count( $items );
			$items_total         = $items_total + $items_count[ $key ];
		}

		$views = $items_total > 0 ? array(
			'all' => '<a href="' . $this->base_url . '"' . ( '' === (string) $status ? ' class="current"' : '' ) . '>' . esc_html__( 'All', 'iubenda' ) . ' <span class="count">(' . $items_total . ')</span></a>',
		) : '';

		foreach ( iubenda()->forms->statuses as $key => $view ) {
			if ( (int) $items_count[ $key ] > 0 ) {
				$url = esc_url(
					add_query_arg( array( 'status' => $key ), $this->base_url ),
					'iub_cons_nonce'
				);
				/* translators: 1:view, 2:count. */
				$views[ $key ] = '<a href="' . $url . '" ' . ( $status === $key ? ' class="current"' : '' ) . '>' . sprintf( _n( '%1$s <span class="count">(%2$s)</span>', '%1$s <span class="count">(%2$s)</span>', $items_count[ $key ], 'iubenda' ), $view, $items_count[ $key ] ) . '</a>';
			}
		}

		return $views;
	}

	/**
	 * Display empty result
	 */
	public function no_items() {
		echo esc_html__( 'No forms found.', 'iubenda' );
	}

	/**
	 * Prints column headers, accounting for hidden and sortable columns.
	 *
	 * @since 3.1.0
	 *
	 * @param bool $with_id Whether to set the ID attribute or not.
	 */
	public function print_column_headers( $with_id = true ) {
		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

		$current_url = remove_query_arg( 'paged', $this->base_url );

		$current_orderby = iub_get_request_parameter( 'orderby' );
		$current_order   = iub_get_request_parameter( 'order' );
		if ( ! in_array( (string) $current_order, array( 'asc', 'desc' ), true ) ) {
			$current_order = 'asc';
		}

		if ( ! empty( $columns['cb'] ) ) {
			static $cb_counter = 1;
			$columns['cb']     = '<label class="screen-reader-text" for="cb-select-all-' . $cb_counter . '">' . __( 'Select All', 'iubenda' ) . '</label>'
				. '<input id="cb-select-all-' . $cb_counter . '" type="checkbox" />';
			++$cb_counter;
		}

		foreach ( $columns as $column_key => $column_display_name ) {
			$classes = array( 'manage-column', "column-$column_key" );

			if ( in_array( $column_key, $hidden, true ) ) {
				$classes[] = 'hidden';
			}

			if ( 'cb' === $column_key ) {
				$classes[] = 'check-column';
			} elseif ( in_array( $column_key, array( 'posts', 'comments', 'links' ), true ) ) {
				$classes[] = 'num';
			}

			if ( $column_key === $primary ) {
				$classes[] = 'column-primary';
			}

			if ( isset( $sortable[ $column_key ] ) ) {
				list( $orderby, $desc_first ) = $sortable[ $column_key ];

				if ( $current_orderby === $orderby ) {
					$order = 'asc' === $current_order ? 'desc' : 'asc';

					$classes[] = 'sorted';
					$classes[] = $current_order;
				} else {
					$order = strtolower( $desc_first );

					if ( ! in_array( $order, array( 'desc', 'asc' ), true ) ) {
						$order = $desc_first ? 'desc' : 'asc';
					}

					$classes[] = 'sortable';
					$classes[] = 'desc' === $order ? 'asc' : 'desc';
				}

				$column_display_name = sprintf(
					'<a href="%s"><span>%s</span><span class="sorting-indicator"></span></a>',
					esc_url( add_query_arg( compact( 'orderby', 'order' ), $current_url ) ),
					$column_display_name
				);
			}

			$tag   = ( 'cb' === $column_key ) ? 'td' : 'th';
			$scope = ( 'th' === $tag ) ? 'scope="col"' : '';
			$id    = $with_id ? "id='$column_key'" : '';
			$class = "class='" . implode( ' ', $classes ) . "'";

			echo wp_kses_post( "<{$tag} {$scope} {$id} {$class}>{$column_display_name}</{$tag}>" );
		}
	}
}
