<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class BSK_GFCV_Dashboard_Entries_List extends WP_List_Table {
    
    var $form_plugin = '';
    var $form_id = 0;
    
    function __construct( $args ) {
		global $wpdb;
		
		//Set parent defaults
		parent::__construct( array( 
								'singular' => 'bsk-gfcv-entries',  //singular name of the listed records
								'plural'   => 'bsk-gfcv-entries', //plural name of the listed records
								'ajax'     => false                          //does this table support ajax?
								) 
						   );
		
        $this->form_plugin = $args['form_plugin'];
        $this->form_id = $args['form_id'];
    }

    function column_default( $list_item, $column_name ) {
        switch( $column_name ) {
			case 'id':
				echo $list_item['id'];
            break;
            case 'form_title':
				echo $list_item['form_title'];
            break;
			case 'form_data':
                $hits_data = false;
                $entry_html = BSK_GFCV_Dashboard_Common::bsk_gfcv_render_entry_html( $list_item['form_data'], $hits_data, $list_item['id'], $list_item['ip'], $list_item['form_plugin'] );
                echo $entry_html;
            break;
            case 'date':
                echo '<p>'.$list_item['date'].'</p>';
                echo '<p>'.$list_item['ip'].'</p>';
            break;  
        }
    }
   
    function column_cb( $item ) {
        return sprintf( 
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            esc_attr( $this->_args['singular'] ),
            esc_attr( $item['id'] )
        );
    }

    function get_columns() {
    
        $columns = array( 
			'cb'        	=> '<input type="checkbox"/>',
			'id'			=> 'ID',
            'form_title'    => 'Form Title',
            'form_data'     => 'Form Data',
            'date'          => 'Date&IP',
        );

        return $columns;
    }
   
	function get_sortable_columns() {
        
		$c = array( 'date' => 'date' );
		
		return $c;
	}
	
    function get_views() {

        return array();
    }
   
    function get_bulk_actions() {
    
        $actions = array( 
            'delete'=> 'Delete'
        );
        
                
        return $actions;
    }

    function do_bulk_action() {
        
        global $wpdb;
        
        $entries_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_entries_tbl_name;
        $hits_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_hits_tbl_name;
        
    }

    function get_data() {
		
		return false;
    }

    function prepare_items() {
       
        /**
         * First, lets decide how many records per page to show
         */
        $user = get_current_user_id();
        $per_page = 50;
        
        $data = array();
		
        add_thickbox();

		$this->do_bulk_action();
       
        $data = $this->get_data();
   
        $current_page = $this->get_pagenum();
        $total_items = 0;
        if( $data && is_array( $data ) ){
            count( $data );
        }
        
	    if ($total_items > 0){
        	$data = array_slice( $data,( ( $current_page-1 )*$per_page ),$per_page );
		}
        $this->items = $data;

        $this->set_pagination_args( array( 
            'total_items' => $total_items,                  // We have to calculate the total number of items
            'per_page'    => $per_page,                     // We have to determine how many items to show on a page
            'total_pages' => ceil( $total_items/$per_page ) // We have to calculate the total number of pages
        ) );
    }
	

	
	function get_column_info() {
		
		$columns = array( 
			'cb'        	=> '<input type="checkbox"/>',
			'id'			=> 'ID',
            'form_title'    => 'Form Title',
            'form_data'     => 'Form Data',
            'date'          => 'Date&IP',
        );
		
		$hidden = array();

		$_sortable = apply_filters( "manage_{$this->screen->id}_sortable_columns", $this->get_sortable_columns() );

		$sortable = array();
		foreach( $_sortable as $id => $data ) {
			if ( empty( $data ) )
				continue;

			$data = (array) $data;
			if ( !isset( $data[1] ) )
				$data[1] = false;

			$sortable[$id] = $data;
		}

		$_column_headers = array( $columns, $hidden, $sortable, array() );

		return $_column_headers;
	}
    
}
