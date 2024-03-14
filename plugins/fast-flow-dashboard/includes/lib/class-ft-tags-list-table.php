<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 *  class FT_Tags_List_Table
 */

if( !class_exists('WP_List_Table') ){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


if(  class_exists( 'WP_List_Table' ) ) {

    class FT_Tags_List_Table extends WP_List_Table {


		private $prepare_items_sent_data;

        function __construct(){
            global $status, $page;

            //Set parent defaults
            parent::__construct( array(
                'singular'  => 'tag',     //singular name of the listed records
                'plural'    => 'tags',    //plural name of the listed records
                'ajax'      => false        //does this table support ajax?
            ) );

        }



        function column_default($item, $column_name){
            switch($column_name){
                case 'type':
                    return $item[$column_name];
                case 'users':
                    return sprintf('<a href="admin.php?page=fast_tagged_users&fast_tag_term=%1$s">%2$s</a>',
                            /*$1%s*/ $item['ID'],
                            /*$2%s*/ $item[$column_name]);
                default:
                    return $item[$column_name];//Show the whole array for troubleshooting purposes
            }
        }



        function column_tag($item){

            //Build row actions
            $actions = array(
                'edit'      => sprintf('<a href="edit-tags.php?action=edit&taxonomy=fast_tag&tag_ID=%s&post_type=post">Edit</a>',$item['ID']),
                'delete'      => sprintf('<a class="delete-tag" href="admin.php?page=fast-tagger&action=delete&taxonomy=fast_tag&tag_ID=%s">Delete</a>',$item['ID'])
            );

            //Return the title contentsadmin.php?page=fast-tagger&
            return sprintf('%1$s <span style="color:silver">(ID: %2$s)</span>%3$s',
                /*$1%s*/ $item['tag'],
                /*$2%s*/ $item['ID'],
                /*$3%s*/ $this->row_actions($actions)
            );
        }



        function column_cb($item){
            return sprintf(
                '<input type="checkbox" name="%1$s[]" value="%2$s" />',
                /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
                /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
            );
        }



        function get_columns(){
            $columns = array(
                'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
                'tag'     => 'Tag',
                'type'    => 'Type',
                'users'  => 'Users'
            );
            return $columns;
        }



        function get_sortable_columns() {
            $sortable_columns = array(
                'tag'     => array('tag',false),     //true means it's already sorted
                'type'    => array('type',false),
                'users'  => array('users',false)
            );
            return $sortable_columns;
        }



        function get_bulk_actions() {
            $actions = array();
            $actions['delete'] = __( 'Delete' );

            return $actions;
        }



        function process_bulk_action() {

            //Detect when a bulk action is being triggered...
            if( 'delete'===$this->current_action() ) {
                //wp_die('Items deleted (or they would be if we had items to delete)!');
                //$log_var = "<pre>" . print_r( , true )
                $count = 0;
                foreach ( $_GET['tag'] as $tag_id ) {
                    $chk = wp_delete_term( $tag_id, 'fast_tag' );
                    if( $chk === true ) {
                        $count++;
                    }
                }

                if( $count > 0 ) {
                    return "<div class='updated notice notice-success' style=' display:block; margin-left:0; '><p>
                                    The selected tag(s) has been <strong>deleted</strong>.
                            </p></div>";
                }
            }

            if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'tag' )) {
              $delete_ids = esc_sql( $_GET['tag'] );

              // loop over the array of record IDs and delete them
              foreach ( $_GET['tag'] as $tag_id ) {
                  $chk = wp_delete_term( $tag_id, 'fast_tag' );

              }

              wp_redirect( esc_url( add_query_arg() ) );
              exit;
            }

        }



		function set_prepare_items_data($sent_data) {
			$this->prepare_items_sent_data = $sent_data;
		}


        function prepare_items() {
            global $wpdb; //This is used only if making any database queries

			$sent_data = $this->prepare_items_sent_data;

            /**
             * First, lets decide how many records per page to show
             */
            $per_page = 15;



            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();



            $this->_column_headers = array($columns, $hidden, $sortable);



            $data = $sent_data;



            function usort_reorder($a,$b){
                $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'tag'; //If no sort, default to title
                $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
                $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
                return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
            }
            usort($data, 'usort_reorder');



            $current_page = $this->get_pagenum();


            $total_items = count($data);



            $data = array_slice($data,(($current_page-1)*$per_page),$per_page);




            $this->items = $data;



            $this->set_pagination_args( array(
                'total_items' => $total_items,                  //WE have to calculate the total number of items
                'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
                'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
            ) );
        }

    }
}
