<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/screen.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Wacs_List_Table extends WP_List_Table {

	function __construct() {
		parent::__construct( array(
			'singular' => __( 'state', 'woo-add-custom-states' ),
			'plural'   => __( 'states', 'woo-add-custom-states' ),
			'ajax'     => false
		) );
	}

	function get_columns() {
		return $columns = array(
			'cb'             => '<input type="checkbox" />',
			'col_state_name' => __( 'State Name', 'woo-add-custom-states' ),
			'col_state_id'   => __( 'State Code', 'woo-add-custom-states' )
		);
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'col_state_id':
			case 'col_state_name':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
		}
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'col_state_name' => array( 'col_state_name', false ),
			'col_state_id'   => array( 'col_state_id', false ),
		);

		return $sortable_columns;
	}

	function usort_reorder( $a, $b ) {
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? sanitize_text_field($_GET['orderby']) : 'col_state_name';
		$order   = ( ! empty( $_GET['order'] ) ) ? sanitize_text_field($_GET['order']) : 'asc';
		$result  = strcmp( $a[ $orderby ], $b[ $orderby ] );
		return ( $order === 'asc' ) ? $result : - $result;
	}

	function wacs_load_states() {
        if ( get_option( 'wacs_country' ) && get_option( 'wacs_states' ) && get_option( 'wacs_current_country' ) && get_option( 'wacs_country' ) == get_option( 'wacs_current_country' )) {
			$states                  = get_option( 'wacs_states' );
			update_option('wacs', true);
			return $states;
		} else {
			$states = WC()->countries->get_states( get_option( 'wacs_current_country' ) );
            if ( $states && get_option('wacs_current_country') != get_option('wacs_country')) {
                delete_option('wacs');
				return $states;
			} else {
                update_option('wacs', true);
				return '';
			}
		}
	}

	function get_states() {
		$data   = array();
		if(get_option( 'wacs_states' ) && ! empty( get_option( 'wacs_states' )) && get_option('wacs_country') && get_option( 'wacs_country' ) == get_option( 'wacs_current_country' )) {
			$states = get_option( 'wacs_states' );
            update_option('wacs', true);
		} else {
			$states = $this->wacs_load_states();
		}
		if ( ! empty( $states ) ) {
			foreach ( $states as $code => $name ) {
				$temp = array( 'col_state_name' => $name, 'col_state_id' => $code );
				array_push( $data, $temp );
			}
			return $data;
		} else {
			return '';
		}
	}

	function column_col_state_name( $item ) {
	    $nonce = wp_create_nonce('delete');
		$actions = array(
			'delete' => sprintf( '<a href="?page=%s&_wpnonce=%s&action=%s&state=%s">Delete</a>', sanitize_text_field($_REQUEST['page']), $nonce, 'delete', $item['col_state_id'] ),
		);

		return sprintf( '%1$s %2$s', $item['col_state_name'], $this->row_actions( $actions ) );
	}

	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="state[]" value="%s" />', $item['col_state_id']
		);
	}

	function get_bulk_actions() {
		$actions = array(
			'delete' => 'Delete'
		);

		return $actions;
	}

	function prepare_items() {
		$this->_column_headers = $this->get_column_info();
        $this->process_bulk_action();
		$data                  = $this->get_states();
		if ( ! empty( $data ) && is_array($data)) {
			usort( $data, array( &$this, 'usort_reorder' ) );
		}
		$per_page     = count( $data );
		$current_page = $this->get_pagenum();
		$total_items  = count( $data );
		if ( is_array($data) && count($data) > 1 ) {
			$found_data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
		} else {
			$found_data  = $data;
			$total_items = 0;
		}
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page
		) );
		$this->items = $found_data;
	}

	public function no_items() {
		_e( 'No states avaliable.', 'woo-add-custom-states' );
	}

    public function process_bulk_action()
    {
        $action = $this->current_action();
        if($action == 'delete') {
            $states_delete = array_map('sanitize_text_field', $_POST['state']);
            foreach ( $states_delete as $state_delete) {
                $states = array_map('esc_attr', get_option('wacs_states'));
                unset($states[$state_delete]);
                update_option('wacs_states', $states);
                delete_option('wacs_states');
                delete_option('wacs_country');
            }
        }
    }
}