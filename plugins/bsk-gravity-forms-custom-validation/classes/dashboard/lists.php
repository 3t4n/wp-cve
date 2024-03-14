<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class BSK_GFCV_Dashboard_Lists extends WP_List_Table {
    
    private $_bsk_gfcv_current_view = 'list';
    private $_bsk_gfcv_list_type = 'CV_LIST';
    function __construct() {
		global $wpdb;
		
		//Set parent defaults
		parent::__construct( array( 
								'singular' => 'bsk-gfcv-lists',  //singular name of the listed records
								'plural'   => 'bsk-gfcv-lists', //plural name of the listed records
								'ajax'     => false                          //does this table support ajax?
								) 
						   );
		$this->_bsk_gfcv_current_view = ( !empty($_REQUEST['view']) ? $_REQUEST['view'] : 'list');
		$this->_bsk_gfcv_list_view = 'cvlist';
		$this->_bsk_gfcv_list_type = 'CV_LIST';
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) {
			case 'id':
				echo $item['id_link'];
				break;
			case 'list_name':
				echo $item['list_name'];
				break;
			case 'items_count':
				echo $item['items_count'];
				break;	
            case 'date':
                echo $item['date'];
                break;
			case 'action':
				echo $item['action'];
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
			'cb'        		=> '<input type="checkbox"/>',
			'id'				=> 'ID',
            'list_name'     	=> 'List Name',
			'items_count'     	=> 'Rule Count',
            'date' 				=> 'Date',
			'action' 			=> 'Action'
        );
        
        return $columns;
    }
   
	function get_sortable_columns() {
		$c = array(
					'list_name' => 'list_name',
					'date'    	=> 'date'
					);
		
		return $c;
	}
	
    function get_bulk_actions() {
    
        $actions = array( 
            //'delete'=> 'Delete'
        );
        
        return $actions;
    }

    function do_bulk_action() {
    }

    function get_data() {
		global $wpdb;
		
		$search = '';
		$orderby = 'list_name';
		$order = 'ASC';
        // check to see if we are searching
        if( isset( $_POST['s'] ) ) {
            $search = sanitize_text_field( $_POST['s'] );
        }
		if ( isset( $_REQUEST['orderby'] ) ){
			$orderby = sanitize_text_field( $_REQUEST['orderby'] );
			if ( $orderby != 'list_name' && $orderby != 'date' ) {
				$orderby = 'list_name';
			}
		}
		if ( isset( $_REQUEST['order'] ) ){
			$order = strtoupper(sanitize_text_field( $_REQUEST['order'] ));
			if ( $order != 'ASC' && $order != 'DESC' ) {
				$order = 'ASC';
			}
		}
		
		$sql = 'SELECT * FROM '.
		       $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_list_tbl_name.' AS l WHERE l.`list_type` = %s ';
		if( $search ){
			$sql .= ' AND l.list_name LIKE %s';
			$sql = $wpdb->prepare( $sql, $this->_bsk_gfcv_list_type, '%'.$search.'%' );
		}else{
			$sql = $wpdb->prepare( $sql, $this->_bsk_gfcv_list_type );
		}
		$orderCase = ' ORDER BY l.`'.$orderby.'` '.$order;
		$lists = $wpdb->get_results($sql.$orderCase);
		if (!$lists || count($lists) < 1){
			return NULL;
		}
		$list_page_url = admin_url( 'admin.php?page='.BSK_GFCV_Dashboard::$_bsk_gfcv_pages['base']['slug'] );
		
        $items_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_items_tbl_name;
		$lists_data = array();
		foreach ( $lists as $list ) {
			$items_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM `'.$items_table.'` WHERE `list_id` = %d', $list->id) );
            
            //list edit
			$list_edit_url = add_query_arg( array(
												  'view' 	 => 'edit', 
										 		  'id' 		 => $list->id),
											$list_page_url );
            $list_eidt_anchor = '<a class="bsk-gfcv-action-anchor bsk-gfcv-action-anchor-first bsk-gfcv-admin-edit-list" href="'.$list_edit_url.'">Edit</a>';
            $list_duplicate_url = add_query_arg( 
                                            array(
												  'view' 	 => 'duplicate', 
										 		  'id' 		 => $list->id,
                                                  'bsk-gfcv-action' => 'duplicate_list'
                                            ),
											$list_page_url 
                                          );
            $list_duplicate_url = wp_nonce_url( $list_duplicate_url, 'duplicate-list-160' );
            $list_duplicate_anchor = '<a class="bsk-gfcv-action-anchor bsk-gfcv-admin-duplicate-list" href="'.$list_duplicate_url.'">Duplicate</a>';
			//list delete
			$list_delete_url = add_query_arg( array('view' 	=> 'delete', 
										 		    'id' 	=> $list->id),
											  $list_page_url );
            
			$delete_anchor = '<a class="bsk-gfcv-action-anchor bsk-gfcv-admin-delete-cv-list" '.
							 'rel="'.$list->id.'" count="'.$items_count.'">Delete</a>'.
                             '<span class="bsk-gfcv-delete-confirm-span" style="display: none; margin-left: 20px;">Are you sure? '.
                             '<a class="bsk-gfcv-admin-delete-cv-list-yes" rel="'.$list->id.'">Yes</a><a class="bsk-gfcv-admin-delete-cv-list-cancel">Cancel</a>'.
                             '</span>';
			
			//organise data
			$lists_data[] = array( 
			    'id' 				=> $list->id,
				'id_link' 			=> '<a href="'.$list_edit_url.'">'.$list->id.'</a>',
				'list_name'     	=> '<a href="'.$list_edit_url.'">'.$list->list_name.'</a>',
				'date'				=> date('Y-m-d', strtotime($list->date)),
				'action'			=> $list_eidt_anchor.$list_duplicate_anchor.$delete_anchor,
				'items_count'		=> $items_count
			);
		}
		
		return $lists_data;
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
							'cb'        		=> '<input type="checkbox"/>',
							'id'				=> 'ID',
							'list_name'     	=> 'List Name',
							'items_count'     	=> 'Rule Count',
							'date' 				=> 'Date',
							'action' 			=> 'Action'
						);
		
		$hidden = array();

		$_sortable = apply_filters( "manage_{$this->screen->id}_sortable_columns", $this->get_sortable_columns() );

		$sortable = array();
		foreach ( $_sortable as $id => $data ) {
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