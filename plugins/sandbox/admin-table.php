<?php
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

/*
 * WP-List-Tables
 */

class Sandboxes_List_Table extends WP_List_Table {
  function __construct(){
    global $status, $page;

    //Set parent defaults
    parent::__construct( array(
        'singular'  => 'sandbox',     //singular name of the listed records
        'plural'    => 'sandboxes',    //plural name of the listed records
        'ajax'      => false        //does this table support ajax?
    ) );  
  }
  
  function column_default($item, $column_name){
    switch($column_name){
        case 'description':
            return $item[$column_name];
        case 'shortname':
            return $item[$column_name];
        case 'name':
            $actions = array(
                'edit'      => sprintf('<a href="?page=%s&action=%s&shortname=%s">Edit</a>',$_REQUEST['page'],'edit',$item['shortname']),
								'export'      => sprintf('<a href="?page=%s&action=%s&shortname=%s">Export</a>',$_REQUEST['page'],'export',$item['shortname']),
                'delete'    => sprintf('<a href="?page=%s&action=%s&shortname=%s">Delete</a>',$_REQUEST['page'],'delete',$item['shortname']),
            );

            return sprintf('%1$s %2$s', $item[$column_name], $this->row_actions($actions) );
        default:
            return print_r($item,true); //Show the whole array for troubleshooting purposes
    }
  }
  
  function column_functions($item){
      $button_html = '<a href="?page=sandbox&action=activate&shortname='.$item['shortname'].'" class="add-new-h2">Activate</a>';      
      return $button_html;
  }
  
  
  // Handles printing cell for instructions title 
  function column_title($item){
        
    //Build row actions
    $actions = array(
        'edit'      => sprintf('<a href="?page=%s&action=edit_instruction&id=%s">Edit</a>',$_REQUEST['page'],$item['id']),
        'delete'    => sprintf('<a href="?page=%s&action=delete_instruction&id=%s">Delete</a>',$_REQUEST['page'],$item['id'])
    );
    
    // Depending on if post is set, add link for Add or Edit post
    if (empty($item['post']) || !is_post($item['post']))
      $actions['post'] = '<a href="?page='.$_REQUEST['page'].'&action=add_post&id='.$item['id'].'">Add Post</a>';
    else 
      $actions['post'] = '<a href="'.get_admin_url().'post.php?post='.$item['post'].'&action=edit">Edit Post</a>';

    //Return the title contents
    return sprintf('%1$s <span style="color:silver">[instructions id=%2$s]</span>%3$s',
        /*$1%s*/ $item['title'],
        /*$2%s*/ $item['id'],
        /*$3%s*/ $this->row_actions($actions)
    );
  }
  
  function prepare_items() {
    // WordPress globals
    global $wpdb;
    // Sandbox globals
    global $sandboxes;
        
    /**
     * First, lets decide how many records per page to show
     */
    $per_page = 5;


    /**
     * Column headers defined in Sandbox object
     */
    $columns = Sandbox::get_columns();
    $columns['functions'] = '';    
    $hidden = array();
    $sortable = Sandbox::get_sortable_columns();


    /**
     * REQUIRED. Finally, we build an array to be used by the class for column 
     * headers. The $this->_column_headers property takes an array which contains
     * 3 other arrays. One for all columns, one for hidden columns, and one
     * for sortable columns.
     */
    $this->_column_headers = array($columns, $hidden, $sortable);

    //convert sandboxes to rows
    $data = array();
    foreach($sandboxes as $sandbox){
        $data[] = $sandbox->assoc();
    }

    /**
     * REQUIRED for pagination. Let's figure out what page the user is currently 
     * looking at. We'll need this later, so you should always include it in 
     * your own package classes.
     */
    $current_page = $this->get_pagenum();

    /**
     * REQUIRED for pagination. Let's check how many items are in our data array. 
     * In real-world use, this would be the total number of items in your database, 
     * without filtering. We'll need this later, so you should always include it 
     * in your own package classes.
     */
    $total_items = count($data);


    /**
     * The WP_List_Table class does not handle pagination for us, so we need
     * to ensure that the data is trimmed to only the current page. We can use
     * array_slice() to 
     */
    $data = array_slice($data,(($current_page-1)*$per_page),$per_page);


    /**
     * REQUIRED. Now we can add our *sorted* data to the items property, where 
     * it can be used by the rest of the class.
     */
    $this->items = $data;


    /**
     * REQUIRED. We also have to register our pagination options & calculations.
     */
    $this->set_pagination_args( array(
        'total_items' => $total_items,                  //WE have to calculate the total number of items
        'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
        'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
    ) );
  }
  
  function no_items() {
    _e( 'No sandboxes configured.' );
  }
}

?>
