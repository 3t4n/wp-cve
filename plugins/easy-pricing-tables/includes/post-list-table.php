<?php

class EPT3_List_Table extends WP_List_Table {

	public function __construct() {
		// Set parent defaults.
		parent::__construct( array(
			'singular' => 'table',
			'plural'   => 'tables',
			'ajax'     => false,
		) );
	}

	public function get_columns() {
		$columns = array(
			'title'    => _x( 'Pricing Table Name', 'Column label', 'wp-list-table-example' ),
			'shortcode'   => _x( 'Shortcode', 'Column label', 'wp-list-table-example' ),
			'date' => _x( 'Date', 'Column label', 'wp-list-table-example' ),
		);

		return $columns;
	}

	protected function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'shortcode':
				return '<input type="text" style="width: 300px;" readonly="readonly" onclick="this.select()" value="[ept3-block id=&quot;'. $item->ID . '&quot;]"/>';
			case 'date':
				$date_format = get_option( 'links_updated_date_format', 'Y/m/d \a\t g:i a' );
				return '<span>Published</span></br>' . date_i18n( $date_format, strtotime( $item->post_date ) );
			default:
				return print_r( $item, true ); // Show the whole array for troubleshooting purposes.
		}
	}
	
	protected function column_title( $item ) {
		$page = wp_unslash( $_REQUEST['page'] ); // WPCS: Input var ok.

		// Build edit row action.
		$edit_query_args = array(
			'post'  => $item->ID,
			'action' => 'edit',
		);

		$actions['edit'] = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( add_query_arg( $edit_query_args, 'post.php' ), $item->post_title ),
			_x( 'Edit', 'List table row action', 'wp-list-table-example' )
		);
		
		$clone_query =  add_query_arg( array(
			'post'  => $item->ID,
			'action' => 'clone',
			'ept_nonce' => wp_create_nonce( 'ept_clone' )
		));
		
		$actions['duplicate'] = "<a href='" . esc_url( $clone_query ) . "'>Make a Copy</a>";
		
		// Build delete row action.
		$delete_query_args = array(
			'post'  => $item->ID,
			'action' => 'trash',
			'ept_nonce' => wp_create_nonce( 'ept_delete' )
		);

		$actions['trash'] = sprintf(
			'<a class="ept-trash" href="%1$s" onclick="confirm( \'Are you sure?\' ) == false ? event.preventDefault() : null",>%2$s</a>',
			esc_url( add_query_arg( $delete_query_args ), $item->ID ),
			_x( 'Delete', 'List table row action', 'wp-list-table-example' ),
			
		);
		
		// Return the title contents.
		return sprintf( '<a class="row-title" href="' . esc_url( add_query_arg( $edit_query_args, 'post.php' ), $item->post_title ) . '">%1$s</a> <span style="display:none;">(id:%2$s)</span>%3$s',
			$item->post_title,
			$item->ID,
			$this->row_actions( $actions )
		);
	}

	protected function process_bulk_action() {
		// Detect when a bulk action is being triggered.
		if ( 'trash' === $this->current_action() ) {
			$postID = empty( $_GET['post'] ) ? '' : intval( $_GET['post'] );
			$nonce = empty( $_GET['ept_nonce'] ) ? '' : sanitize_text_field( $_GET['ept_nonce'] );
			if( wp_verify_nonce( $nonce, 'ept_delete' ) && $postID ){
				wp_delete_post( $postID );
			} else {
				wp_die( 'Not authorized, please try logging in again' );
			}
		}
		
		if ( 'clone' === $this->current_action() ) {
			$postID = empty( $_GET['post'] ) ? '' : intval( $_GET['post'] );
			$nonce = empty( $_GET['ept_nonce'] ) ? '' : sanitize_text_field( $_GET['ept_nonce'] );
			if( wp_verify_nonce( $nonce, 'ept_clone' ) && $postID ){
				fca_ept_clone_table( $postID );
			} else {
				wp_die( 'Not authorized, please try logging in again' );
			}
		}
	}

	function prepare_items() {

		$post_status = empty( $_GET['post_status'] ) ? '' : sanitize_text_field( $_GET['post_status'] );

		$per_page = 20;

		$columns  = $this->get_columns();
		$hidden   = array();

		$this->_column_headers = array( $columns, $hidden );

		$this->process_bulk_action();

    	$args = array(
			'post_status'	 => $post_status,
			'post_type'      => 'wp_block',
			'meta_key'		 => '1_dh_ptp_settings', 
			'posts_per_page' => '-1'
        );

		$data = get_posts( $args );

		$current_page = $this->get_pagenum();

		$total_items = count( $data );

		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

		$this->items = $data;

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page ),
		) );
	}

	protected function usort_reorder( $a, $b ) {
		// If no sort, default to title.
		$orderby = ! empty( $_REQUEST['orderby'] ) ? wp_unslash( $_REQUEST['orderby'] ) : 'title'; // WPCS: Input var ok.

		// If no order, default to asc.
		$order = ! empty( $_REQUEST['order'] ) ? wp_unslash( $_REQUEST['order'] ) : 'asc'; // WPCS: Input var ok.

		// Determine sort order.
		$result = strcmp( $a[ $orderby ], $b[ $orderby ] );

		return ( 'asc' === $order ) ? $result : - $result;
	}
}