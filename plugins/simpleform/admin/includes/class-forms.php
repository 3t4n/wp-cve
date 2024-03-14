<?php

/**
 * The customized class that extends the WP_List_Table class
 *
 * @since      2.1
 */
		
if ( ! defined( 'ABSPATH' ) ) { exit; }

class SimpleForm_Forms_List extends WP_List_Table  {

	/**
	 * Override the parent constructor to pass our own arguments
	 *
	 * @since    2.1
	 */

    function __construct() {
	    
        parent::__construct(array(
           'singular' => 'sform-form',
           'plural' => 'sform-forms',
           'ajax'      => false 
        ));
	           
    }
     
	/**
	 * Return a list of views available for the submissions
	 *
	 * @since    2.1
	 */   

	function get_views() {
		
        $views = array();
        $current = isset($_REQUEST['view']) ? sanitize_text_field($_REQUEST['view']) : 'all';
        $search_order = isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc')) ? $_REQUEST['order'] : '';
        $search_orderby = isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array('name', 'entries', 'date')) ? sanitize_sql_orderby($_REQUEST['orderby']) : ''; 
	    global $wpdb;
        $sql = "SELECT status FROM {$wpdb->prefix}sform_shortcodes";
        $status_array = $wpdb->get_col($sql);
        $count_sql_all = count($status_array) - count(array_keys($status_array, "trash"));
        $count_sql_published = count(array_keys($status_array, "published"));
        $count_sql_drafts = count(array_keys($status_array, "draft"));
        $count_sql_trashed = count(array_keys($status_array, "trash"));
        $count_all = !is_null($count_sql_all) ? $count_sql_all : 0;
        $class = ($current == 'all' ? ' class="current"' :'');
        $all_url = remove_query_arg( array( 'view', 'paged' ) );
        $views['all'] = "<a id='view-all' href='{$all_url }' {$class} >".__( 'All', 'simpleform' )."</a> (" . $count_all . ")";  
        $count_published = !is_null($count_sql_published) ? $count_sql_published : 0;
        $class = ($current == 'published' ? ' class="current"' :'');
        $old_query_or_uri = remove_query_arg('paged');
        $published_url = add_query_arg( 'view','published', $old_query_or_uri);
        if ( $count_published > 0 ) {
         $views['published'] = "<a id='view-published' href='{$published_url }' {$class} >". _x( 'Published', 'Plural noun', 'simpleform' ) ."</a> (" . $count_published . ")";
        }
        $count_drafts = !is_null($count_sql_drafts) ? $count_sql_drafts : 0;
        $class = ($current == 'draft' ? ' class="current"' :'');
        $drafts_url = add_query_arg( 'view','draft', $old_query_or_uri);
        if ( $count_drafts > 0 ) {
          $views['draft'] = "<a id='view-draft' href='{$drafts_url }' {$class} >".__( 'Drafts', 'simpleform' )."</a> (" . $count_drafts . ")";   
        }
        $count_trashed = !is_null($count_sql_trashed) ? $count_sql_trashed : 0;
        $trashed_url = add_query_arg( 'view','trash', $old_query_or_uri);
        $class = ($current == 'trash' ? ' class="current"' :'');
        if ( $count_trashed > 0 ) {
          $views['trash'] = "<a id='view-trash' href='{$trashed_url}' {$class} >".__( 'Trash', 'simpleform' )."</a> (" . $count_trashed . ")";
        }
        $sform_screen_options = $current . ';' . $count_all . ';' . $count_published . ';' . $count_drafts . ';' . $count_trashed . ';' . $search_order . ';' . $search_orderby;       
        update_option( 'sform_forms_screen', $sform_screen_options, false );
              
        return $views;
        
    }
    
	/**
	 * Define the columns that are going to be used in the table
	 *
	 * @since    2.1
	 */

    function get_columns() {
      
	     $columns = array( 'name' => __('Name', 'simpleform'), 'target' => __('Visibility', 'simpleform'), 'locks' => __('Locks', 'simpleform'), 'entries' => __('Entries', 'simpleform'), 'movedentries' => __('Moved', 'simpleform'), 'forwarding' => __('Forwarding', 'simpleform'), 'creation' => __('Creation Date', 'simpleform') );
      
      $column_cb = array( 'cb' => '<input type="checkbox" />' );
      $sform_screen_options = get_option( 'sform_forms_screen' );
	  $count_trashed = isset(explode(';', $sform_screen_options)[4]) ? explode(';', $sform_screen_options)[4] : 0;

      if ( isset($_REQUEST['view']) && $_REQUEST['view'] == 'trash' && $count_trashed > 1 ) { 
	      $columns = $column_cb + $columns;

      }

      return $columns;
        
    }    
      
	/**
	 * Text displayed when no record data is available
	 *
	 * @since    2.1
	 */

    function no_items() {
	    
      _e('No forms found', 'simpleform');
      
    }  
    
	/**
	 * Render the checkbox column
	 *
	 * @since    2.1
	 */

    function column_cb($item) {
	    
        return sprintf('<input type="checkbox" name="id[]" value="%s" />',esc_attr($item['id']));
        
    }
   
	/**
	 * Render the name column with actions
	 *
	 * @since    2.1
	 */

    function column_name($item) {
	    
	   $form_id = isset( $_REQUEST['id'] ) ? absint($_REQUEST['id']) : '';
       $table_view = isset( $_REQUEST['view'] ) ? sanitize_text_field($_REQUEST['view']) : 'all';
       $form = !empty( $form_id ) ? '&id=' . $form_id : '';
       $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? '&order=' . $_REQUEST['order'] : '';
       $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array('subject', 'email', 'date'))) ? '&orderby=' . $_REQUEST['orderby'] : ''; 
       $view = isset( $_REQUEST['view'] ) && !empty( $_REQUEST['view'] ) ? '&view=' . sanitize_text_field($_REQUEST['view']) : '';
	   $pagenum = isset( $_REQUEST['paged'] ) ? absint($_REQUEST['paged']) : 0;
       $current_page = $this->get_pagenum();
       $per_page = $this->get_items_per_page('edit_form_per_page', 10);
       $sform_screen_options = get_option( 'sform_forms_screen' );
 	   if ( $item['status'] == 'trash' )  {    
		 $sform_count_trashed = explode(';', $sform_screen_options)[4];
         $total_pages = ceil( ($sform_count_trashed - 1) / $per_page );
		 if ( $current_page > $total_pages ) { $pagenum = $total_pages; } 
		 else { $pagenum = $pagenum; }
		 $page = isset($pagenum) && $pagenum != 0 ? '&paged=' . $pagenum : '';
  	     $query_args_restored = array(
			'action'	=> 'restore',
			'id'		=> esc_attr($item['id']),
			'_wpnonce'	=> wp_create_nonce( 'restore_nonce' ),
		 );
         $restore_link = esc_url( add_query_arg( $query_args_restored ).$form.$view.$page.$orderby.$order );
 	     $query_args_delete = array(
			'action'	=> 'delete',
			'id'		=> esc_attr($item['id']),
			'_wpnonce'	=> wp_create_nonce( 'delete_nonce' ),
		 );
		 $delete_link = esc_url( add_query_arg( $query_args_delete ).$form.$view.$page.$orderby.$order);		 
         $actions = array(
            'restore' => '<a href="' . $restore_link . '">' . __( 'Restore', 'simpleform') . '</a>',
            'delete' => '<a href="' . $delete_link . '">' . __( 'Delete Permanently', 'simpleform' ) . '</a>'           
         );        
        }
        else  { 
	     $sform_count_all =  explode(';', $sform_screen_options)[1];
 	     $sform_count_published =  explode(';', $sform_screen_options)[2];
 	     $sform_count_drafts =  explode(';', $sform_screen_options)[3];
		 switch ($table_view) {
           case $table_view == 'published':
           $total_pages = ceil( ($sform_count_published - 1) / $per_page );
           break;
           case $table_view == 'draft':
           $total_pages = ceil( ($sform_count_drafts - 1) / $per_page );
           break;
           default:
           $total_pages = ceil( ($sform_count_all - 1) / $per_page );
         }
		 if ( $current_page > $total_pages ) { $pagenum = $total_pages; } 
		 else { $pagenum = $pagenum; }
		 $page = isset($pagenum) && $pagenum != 0 ? '&paged=' . $pagenum : '';
         $actions = array( 'view' => sprintf('<a href="?page='.$this->_args['singular'].'&id=%s'.$form.$view.'&paged='.$current_page.$orderby.$order.'">%s</a>', esc_attr($item['id']), __('Manage', 'simpleform')) );
        }
	    $name = $item['name'] != '' ? esc_attr($item['name']) : esc_attr__( 'Unnamed', 'simpleform' );
	    
	    return sprintf('%s %s', $name, $this->row_actions($actions));
	    
    }
       
	/**
	 * Render the target column
	 *
	 * @since    2.1
	 */

    function column_target($item) {
	    
        $show_for = $item['target'] ? esc_attr($item['target']) : 'all';
        
        if ( $show_for == 'out' ) { $target = __( 'Logged-out users','simpleform'); }
        elseif ( $show_for == 'in' ) { $target = __( 'Logged-in users','simpleform'); }
        else { $target = __( 'Everyone','simpleform'); }

        return $target;
        
    }
    
	/**
	 * Render the locks column
	 *
	 * @since    2.1
	 */
    
        function column_locks($item) {
	        
	    if ( esc_attr($item['deletion']) == true && esc_attr($item['relocation']) == true ) {
          return '<span class="dashicons dashicons-unlock"></span>
          <span class="lock notes invisible">'. __( 'Deletion and moving allowed', 'simpleform' ) .'</span>';
		}
	    else {
	      $classlock = esc_attr($item['deletion']) == true || esc_attr($item['relocation']) == true ? 'orange' : 'red';  
          $icon = '<span class="dashicons dashicons-lock '. $classlock .'"></span>';
         
          if ( esc_attr($item['deletion']) != true && esc_attr($item['relocation']) == true ) {
	        $notes = __( 'Deletion not allowed', 'simpleform' );  
	      }
	      elseif ( esc_attr($item['deletion']) == true && esc_attr($item['relocation']) != true  ) { 
 	        $notes = __( 'Moving not allowed', 'simpleform' );  
	      }
 	      else { 
	        $notes = __( 'Deletion and moving not allowed', 'simpleform' );  
	      }
          
          return $icon . '<span class="lock notes invisible '. $classlock .'">'. $notes .'</span>';
		}
	        
    }

	/**
	 * Render the entries column
	 *
	 * @since    2.1
	 */

    function column_entries($item) {
	    
	    $from = $item['entries'] != '' ? esc_attr($item['entries']) : '0';
        return $from;
        
    }

	/**
	 * Render the moved column.
	 *
	 * @since    2.1
	 */

    function column_movedentries($item) {
	    
        return esc_attr($item['moved_entries']);
        
    }

	/**
	 * Render the forwarding column.
	 *
	 * @since    2.1
	 */
    
    function column_forwarding($item) {
	        
	    if ( esc_attr($item['moveto']) != '0' && esc_attr($item['to_be_moved']) == 'next' && esc_attr($item['onetime_moving']) == '0' ) {
	      global $wpdb;
	      $moveto = esc_sql($item['moveto']);
 	      $name = $wpdb->get_var( $wpdb->prepare( "SELECT name FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $moveto ) );
          return $name;
		}
	    else {
          return '-';
		}
	        
    }

	/**
	 * Render the creation column
	 *
	 * @since    2.1
	 */

    function column_creation($item) {
	    
        $tzcity = get_option('timezone_string'); 
        $tzoffset = get_option('gmt_offset');
        if ( ! empty($tzcity))  { 
        $current_time_timezone = date_create('now', timezone_open($tzcity));
        $timezone_offset =  date_offset_get($current_time_timezone);
        $submission_timestamp = strtotime($item['creation']) + $timezone_offset; 
        }
        else { 
        $timezone_offset =  $tzoffset * 3600;
        $submission_timestamp = strtotime(esc_attr($item['creation'])) + $timezone_offset;  
        }
        return date_i18n(get_option('date_format'),$submission_timestamp);
        
    }

	/**
	 * Decide which columns to activate the sorting functionality on
	 *
	 * @since    2.1
	 */

    function get_sortable_columns() {
	    
       $sortable_columns = array( 'name' => array('name', true), 'creation' => array('creation', true) );
       return $sortable_columns;
       
    }

	/**
	 * Define bulk actions
	 *
	 * @since    2.1
	 */

    function get_bulk_actions() {
   
        $sform_screen_options = get_option( 'sform_forms_screen' );
	    $count_trashed = explode(';', $sform_screen_options)[4];

        if ( isset($_REQUEST['view']) && $_REQUEST['view'] == 'trash' && $count_trashed > 1 ) { 
   
          $actions = array(
            'bulk-restore'   => 'Restore',
            'bulk-delete'    => 'Delete permanently'
          );
        
        return $actions;        
        
        }

    }

	/**
	 * Process the bulk actions
	 *
	 * @since    2.1
	 */ 
	    
    function process_bulk_action() {
	    
       global $wpdb;
       $msg = '';
       
       if ('delete' === $this->current_action()) {
	        $nonce = isset ( $_REQUEST['_wpnonce'] ) ? wp_unslash($_REQUEST['_wpnonce']) : '';
			if ( ! wp_verify_nonce( $nonce, 'delete_nonce' ) ) { $this->invalid_nonce_redirect(); }
            else { $id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : '';
             if (!empty($id)) {
	            $form_name = $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}sform_shortcodes WHERE id = {$id}" );
	            $success = $wpdb->query( $wpdb->prepare("DELETE FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $id) );	            
   	            if ( $success ):
   	            $wpdb->query( $wpdb->prepare("DELETE FROM {$wpdb->prefix}sform_submissions WHERE form = %d", $id) );    
                delete_option( 'sform_'.$id.'_attributes' );
                delete_option( 'sform_'.$id.'_settings' );
                delete_option( 'sform_last_'.$id.'_message' );
	            $action_notice = '<div class="notice notice-success is-dismissible"><p>' . sprintf( __( '%s permanently deleted', 'simpleform' ), $form_name ) .'</p></div>'; 
 	            set_transient( 'sform_form_action_notice', $action_notice, 5 );
               endif; 
	         }
            }
       }
       
       if ('restore' === $this->current_action()) {
	        $nonce = isset ( $_REQUEST['_wpnonce'] ) ? wp_unslash($_REQUEST['_wpnonce']) : '';
			if ( ! wp_verify_nonce( $nonce, 'restore_nonce' ) ) { $this->invalid_nonce_redirect(); }
			else { $id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : '';
             if (!empty($id)) {
               $form_data = $wpdb->get_row( "SELECT name, entries FROM {$wpdb->prefix}sform_shortcodes WHERE id = '$id'", 'ARRAY_A' );
	            $entries = $form_data['entries'];
	            $form_name = $form_data['name'];
	            $success = $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_shortcodes SET status = 'draft', deletion = '0' WHERE id = %d", $id) );
 	            if ( $success ):    
                // $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_submissions SET status = previous_status, previous_status = '', hidden = '0' WHERE form = %d", $id) );
                $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_submissions SET hidden = '0' WHERE form = %d", $id) );
	            $action_notice = '<div class="notice notice-success is-dismissible"><p>' . sprintf( __( '%s successfully restored from the Trash', 'simpleform' ), $form_name ) .'</p></div>'; 
 	            set_transient( 'sform_form_action_notice', $action_notice, 5 );
                endif; 
             }   
            }
       }
       
	   if ( 'bulk-delete' === $this->current_action() || ( isset( $_REQUEST['action2'] ) && $_REQUEST['action2'] === 'bulk-delete' ) ) {
	        $nonce = isset ( $_REQUEST['_wpnonce'] ) ? wp_unslash($_REQUEST['_wpnonce']) : '';
			if ( ! wp_verify_nonce( $nonce, 'bulk-' . $this->_args['plural'] ) ) { $this->invalid_nonce_redirect(); }
			else {   	        
              // Force $ids to be an array if it's not already one by creating a new array and adding the current value
              $ids = isset($_REQUEST['id']) && is_array($_REQUEST['id']) ? $_REQUEST['id'] : array($_REQUEST['id']);
              // Ensure that the values passed are all positive integers
              $ids = array_map('absint', $ids);
              // Count the number of values
              $ids_count = count($ids);
              // Prepare the right amount of placeholders in an array
              $placeholders_array = array_fill(0, $ids_count, '%s');
              // Chains all the placeholders into a comma-separated string
              $placeholders = implode(',', $placeholders_array);
              if (!empty($ids)) {
	            $form_names = $wpdb->get_col( $wpdb->prepare("SELECT name FROM {$wpdb->prefix}sform_shortcodes WHERE id IN($placeholders)", $ids) );
	            $success = $wpdb->query( $wpdb->prepare("DELETE FROM {$wpdb->prefix}sform_shortcodes WHERE id IN($placeholders)", $ids) );
 	            if ( $success ):
   	            $wpdb->query( $wpdb->prepare("DELETE FROM {$wpdb->prefix}sform_submissions WHERE form IN($placeholders)", $ids) );    
                foreach( $ids as $form ) { 
                  delete_option( 'sform_'.$form.'_attributes' );
                  delete_option( 'sform_'.$form.'_settings' );
                  delete_option( 'sform_last_'.$form.'_message' );
                }
	            $forms = implode(', ', $form_names);
	            // Replace last comma with "and" 
	            $forms = count($form_names) > 1 ? substr_replace($forms, ' and', strrpos($forms, ','), 1) : $forms;
                $action_notice = '<div class="notice notice-success is-dismissible"><p>' . sprintf( __( '%s permanently deleted', 'simpleform' ), $forms ) .'</p></div>'; 
                set_transient( 'sform_form_action_notice', $action_notice, 5 );
                endif; 
              }
            }
	    }
	    
	   if ( 'bulk-restore' === $this->current_action() || ( isset( $_REQUEST['action2'] ) && $_REQUEST['action2'] === 'bulk-restore' ) ) {
	        $nonce = isset ( $_REQUEST['_wpnonce'] ) ? wp_unslash($_REQUEST['_wpnonce']) : '';
			if ( ! wp_verify_nonce( $nonce, 'bulk-' . $this->_args['plural'] ) ) { $this->invalid_nonce_redirect(); }
			else {   	        
              $ids = isset($_REQUEST['id']) && is_array($_REQUEST['id']) ? $_REQUEST['id'] : array($_REQUEST['id']);
              $ids = array_map('absint', $ids);
              $ids_count = count($ids);
              $placeholders_array = array_fill(0, $ids_count, '%s');
              $placeholders = implode(',', $placeholders_array);
              if (!empty($ids)) {
	            $success = $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_shortcodes SET status = 'draft', deletion = '0' WHERE id IN($placeholders)", $ids) );	            
            	if ( $success ):
                // $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_submissions SET status = previous_status, previous_status = '', hidden = '0' WHERE form IN($placeholders)", $ids) );              
                $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_submissions SET hidden = '0' WHERE form IN($placeholders)", $ids) );              
 	            $form_names = $wpdb->get_col( $wpdb->prepare("SELECT name FROM {$wpdb->prefix}sform_shortcodes WHERE id IN($placeholders)", $ids) );
	            $forms = implode(', ', $form_names);
	            $forms = count($form_names) > 1 ? substr_replace($forms, ' and', strrpos($forms, ','), 1) : $forms;
	            $action_notice = '<div class="notice notice-success is-dismissible"><p>' . sprintf( __( '%s successfully restored from the Trash', 'simpleform' ), $forms ) .'</p></div>'; 
	            set_transient( 'sform_form_action_notice', $action_notice, 5 );
 	            endif; 
              }                
            }
	    }
	    
    }

	/**
	 * Die when the nonce check fails
	 *
	 * @since    2.1
	 */

	function invalid_nonce_redirect() {
		
		wp_die( __( 'Invalid Nonce', 'simpleform' ),__( 'Error', 'simpleform' ), array( 'response' 	=> 403, 'back_link' =>  esc_url( add_query_arg( array( 'page' => wp_unslash( $_REQUEST['page'] ) ) , admin_url( 'admin.php' ) ) ) ) );
		
	}
	
	/**
	 * Overwrite the pagination
	 *
	 * @since 2.1
	 */
	 
	function pagination( $which ) {
		
		if ( empty( $this->_pagination_args ) ) { return; }
		$total_items = $this->_pagination_args['total_items'];
		$total_pages = $this->_pagination_args['total_pages'];
		$infinite_scroll = false;
		if ( isset( $this->_pagination_args['infinite_scroll'] ) ) { $infinite_scroll = $this->_pagination_args['infinite_scroll']; }
		if ( 'top' === $which && $total_pages > 1 ) { $this->screen->render_screen_reader_content( 'heading_pagination' ); }
		$output = '<span class="displaying-num">' . sprintf(_n( '%s form', '%s forms', $total_items, 'simpleform' ),number_format_i18n( $total_items )) . '</span>';
		$current = $this->get_pagenum();
		// An array of query variable names to remove from a URL
		$removable_query_args = wp_removable_query_args();
		$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		$current_url = remove_query_arg( $removable_query_args, $current_url );
		$page_links = array();
		$total_pages_before = '<span class="paging-input">';
		$total_pages_after  = '</span></span>';
		$disable_first = false;
		$disable_last  = false;
		$disable_prev  = false;
		$disable_next  = false;
		if ( $current == 1 ) {
			$disable_first = true;
			$disable_prev  = true;
		}
		if ( $current == 2 ) {
			$disable_first = true;
		}
		if ( $current == $total_pages ) {
			$disable_last = true;
			$disable_next = true;
		}
		if ( $current == $total_pages - 1 ) {
			$disable_last = true;
		}
		if ( $disable_first ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&laquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='first-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( remove_query_arg( 'paged', $current_url ) ),
				__( 'First page' ),
				'&laquo;'
			);
		}
		if ( $disable_prev ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&lsaquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='prev-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', max( 1, $current - 1 ), $current_url ) ),
				__( 'Previous page' ),
				'&lsaquo;'
			);
		}
		if ( 'bottom' === $which ) {
			$html_current_page  = $current;
			$total_pages_before = '<span class="screen-reader-text">' . __( 'Current Page' ) . '</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">';
		} else {
			$html_current_page = sprintf(
				"%s<input class='current-page' id='current-page-selector' type='text' name='paged' value='%s' size='%d' aria-describedby='table-paging' /><span class='tablenav-paging-text'>",
				'<label for="current-page-selector" class="screen-reader-text">' . __( 'Current Page' ) . '</label>',
				$current,
				strlen( $total_pages )
			);
		}
		$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );
		$page_links[]     = $total_pages_before . sprintf(
			_x( '%1$s of %2$s', 'paging' ),
			$html_current_page,
			$html_total_pages
		) . $total_pages_after;

		if ( $disable_next ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rsaquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='next-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', min( $total_pages, $current + 1 ), $current_url ) ),
				__( 'Next page' ),
				'&rsaquo;'
			);
		}
		if ( $disable_last ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&raquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='last-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', $total_pages, $current_url ) ),
				__( 'Last page' ),
				'&raquo;'
			);
		}
		$pagination_links_class = 'pagination-links';
		if ( ! empty( $infinite_scroll ) ) {
			$pagination_links_class .= ' hide-if-js';
		}
		$output .= "\n<span class='$pagination_links_class'>" . join( "\n", $page_links ) . '</span>';
		if ( $total_pages ) {
			$page_class = $total_pages < 2 ? ' one-page' : '';
		} else {
			$page_class = ' no-pages';
		}
		$this->_pagination = "<div class='tablenav-pages{$page_class}'>$output</div>";
		echo $this->_pagination;
		
	}

	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 *
	 * @since    2.1
	 */
 
 	function prepare_items() {
	 	
	    $per_page = $this->get_items_per_page('edit_form_per_page', 10);
        $view = isset($_REQUEST['view']) ? sanitize_text_field($_REQUEST['view']) : 'all';
        if ($view == 'all') { $where = " WHERE status != 'trash'"; }
        if ($view == 'trash') { $where = " WHERE status = 'trash'"; }
        if ($view == 'published') { $where = " WHERE status = 'published'"; }
        if ($view == 'draft') { $where = " WHERE status = 'draft'"; }
	    $current_page = $this->get_pagenum();
		if ( 1 < $current_page ) { $paged = $per_page * ( $current_page - 1 ); } 
		else { $paged = 0; }
        $this->process_bulk_action();
	    $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? sanitize_sql_orderby($_REQUEST['orderby']) : 'id'; 
		$order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? sanitize_text_field($_REQUEST['order']) : 'desc';
		
		global $wpdb;
        $sql1 = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}sform_shortcodes $where ORDER BY $orderby $order LIMIT %d OFFSET %d", array($per_page, $paged) );
	    $sql2 = "SELECT COUNT(id) FROM {$wpdb->prefix}sform_shortcodes $where";
        $items = $wpdb->get_results( $sql1, ARRAY_A );
		$this->_column_headers = $this->get_column_info();
        $count = $wpdb->get_var( $sql2 );
		$this->items = $items;
		$this->set_pagination_args( array('total_items' => $count,'per_page' => $per_page,'total_pages' => ceil( $count / $per_page )) );
		
    }
		
	/**
	 * Display an admin notice whether the row/bulk action is successful 
	 *
	 * @since    2.1
	 */
 
 	function display_notice() {
	 	
	    $transient_notice = stripslashes(get_transient('sform_form_action_notice'));
        $notice = $transient_notice != '' ? $transient_notice : '';
        echo '<div class="submission-notice">' . $notice . '</div>';
        
    }

}