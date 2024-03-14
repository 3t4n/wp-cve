<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class BSK_GFCV_Dashboard_CV_Items extends WP_List_Table {
    
    private $_bsk_gfcv_list_id = 0;
    
    function __construct( $list_id ) {
        $this->_bsk_gfcv_list_id = $list_id;
        
        //Set parent defaults
        parent::__construct( array( 
            'singular' => 'bsk-gfcv-item',  //singular name of the listed records
            'plural'   => 'bsk-gfcv-items', //plural name of the listed records
            'ajax'     => false                          //does this table support ajax?
        ) );
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) {
			case 'id':
				echo $item['id'];
				break;
			case 'value':
				echo $item['value'];
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
            'value'     		=> 'Rule',
			'action' 			=> 'Action'
        );
        
        return $columns;
    }
   
	function get_sortable_columns() {
		$c = array( 'value' => 'value' );
		
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
        
        $items_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_items_tbl_name;
        
        if( isset($_POST['bsk-gfcv-item']) ){
            $bsk_gfcv_item_selcted = array();
            if( is_array( $_POST['bsk-gfcv-item'] ) && count( $_POST['bsk-gfcv-item'] ) > 0 ){
                foreach( $_POST['bsk-gfcv-item'] as $item_id ){
                    $bsk_gfcv_item_selcted[] = absint( sanitize_text_field( $item_id ) );
                }
            }
            
            if( count( $bsk_gfcv_item_selcted ) > 0 ){
                if( isset($_POST['action']) || isset($_POST['action2']) ){
                    $action = isset($_POST['action']) ? sanitize_text_field( $_POST['action'] ) : '';
                    $action2 = isset($_POST['action2']) ? sanitize_text_field( $_POST['action2'] ) : '';
                    if( $action == 'delete' || $action2 == 'delete' ){
                        $sql = 'DELETE FROM `'.$items_table.'` WHERE `id` IN('.implode(',', $bsk_gfcv_item_selcted ).')';
                        $wpdb->query( $sql );
                    }
                }
            }
        }
    }

    function get_data() {
		global $wpdb;
		
        $items_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_items_tbl_name;
        
		$search = '';
		$orderby = 'id';
		$order = 'DESC';
        // check to see if we are searching
        if( isset( $_POST['s'] ) ) {
            $search = trim( sanitize_text_field( $_POST['s'] ) );
        }
		if( isset( $_REQUEST['orderby'] ) ){
			$orderby = sanitize_text_field( $_REQUEST['orderby'] );
            if ( $orderby != 'value' && $orderby != 'id' ) {
                $orderby = 'id';
            }
		}
		if( isset( $_REQUEST['order'] ) ){
			$order = strtoupper( sanitize_text_field( $_REQUEST['order'] ) );
            if ( $order != 'DESC' && $order != 'ASC' ) {
                $order = 'DESC';
            }
		}
		
		$sql = 'SELECT * FROM `'.
		       $items_table.'` AS i WHERE i.`list_id` = %d ';
		if( $search ){
			$sql .= ' AND i.`value` LIKE %s';
			$sql = $wpdb->prepare( $sql, $this->_bsk_gfcv_list_id, '%'.$search.'%' );
		}else{
			$sql = $wpdb->prepare( $sql, $this->_bsk_gfcv_list_id );
		}
		$orderCase = ' ORDER BY i.`id` DESC';
		if( $orderby ){
			$orderCase = ' ORDER BY i.`'.$orderby.'` '.$order;
		}
		$items = $wpdb->get_results($sql.$orderCase);
		if(!$items || count($items) < 1){
			return NULL;
		}
		
		$items_data = array();
		foreach ( $items as $item ) {
			$delete_anchor = '<a class="bsk-gfcv-action-anchor bsk-gfcv-action-anchor-first bsk-gfcv-rule-delete-anchor" rel="'.$item->id.'">Delete</a>';
            
            $rule_content = '';
            $rule_saved = unserialize( $item->value );
            if( $rule_saved && is_array($rule_saved) && count($rule_saved) > 0 ){
                $rule_sysytem_settings = BSK_GFCV_Rules::get_rule_settings_by_slug( $rule_saved['slug'] );
                $rule_content .= '<p>'.$rule_sysytem_settings['name'].'</p>';
                $rule_content .= '<p><hr /></p>';
                $settings = $rule_sysytem_settings['settings'];
                
                $min = isset($rule_saved['MIN']) ? $rule_saved['MIN'] : '';
                $min_oper = isset($rule_saved['MIN_OPER']) ? $rule_saved['MIN_OPER'] : '';
                $max = isset($rule_saved['MAX']) ? $rule_saved['MAX'] : '';
                $max_oper = isset($rule_saved['MAX_OPER']) ? $rule_saved['MAX_OPER'] : '';
                $text = isset($rule_saved['TEXT']) ? $rule_saved['TEXT'] : '';
                $number = isset($rule_saved['NUMBER']) ? $rule_saved['NUMBER'] : '';
                
                if( $min_oper == 'M' ){
                    $min_oper = '&gt;';
                }else if( $min_oper == 'M_S' ){
                    $min_oper = '&gt;=';
                }
                
                if( $max_oper == 'L' ){
                    $max_oper = '&lt;';
                }else if( $max_oper == 'L_S' ){
                    $max_oper = '&lt;=';
                }
                
                $settings = str_replace( '#BSK_CV_MIN_OPER#', $min_oper, $settings );
                $settings = str_replace( '#BSK_CV_MAX_OPER#', $max_oper, $settings );
                $settings = str_replace( '#BSK_CV_MIN#', $min, $settings );
                $settings = str_replace( '#BSK_CV_MAX#', $max, $settings );
                $settings = str_replace( '#BSK_CV_TEXT#', $text, $settings );
                $settings = str_replace( '#BSK_CV_NUMBER#', $number, $settings );
                
                $rule_content .= '<p>'.$settings.'</p>';
                $rule_content .= '<p><hr /></p>';
                
                $message = $rule_sysytem_settings['message'];
                $message = str_replace( '#BSK_CV_MIN#', $min, $message );
                $message = str_replace( '#BSK_CV_MAX#', $max, $message );
                $message = str_replace( '#BSK_CV_TEXT#', $text, $message );
                $message = str_replace( '#BSK_CV_NUMBER#', $number, $message );
                
                $rule_content .= '<p><label>Validation message:</label>'.$message;
            }
			
			//organise data
			$lists_data[] = array( 
			    'id' 				=> $item->id,
				'value'     		=> $rule_content,
				'action'			=> $delete_anchor
			);
		}
		
		return $lists_data;
    }

    function prepare_items() {
       
        $per_page = 50;
        $data = array();
		
        add_thickbox();

		$this->do_bulk_action();
       
        $data = $this->get_data();
   
        $current_page = $this->get_pagenum();
        $total_items = 0;
        if( $data && is_array( $data ) ){
            $total_items = count( $data );
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
							'value'     		=> 'Rule',
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