<?php

defined('ABSPATH') or die('Nope nope nope...');

if ( ! class_exists( 'WP_List_Table' ) ) 
{
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class ADEV_BlockedEmails_List extends WP_List_Table {

	/** Class constructor */
    public function __construct() {

        parent::__construct([
          'singular' => __( 'Blocked Email', 'antideo-email-validator' ), //singular name of the listed records
          'plural'   => __( 'Blocked Emails', 'antideo-email-validator' ), //plural name of the listed records
          'ajax'     => true //should this table support ajax?
        ]);
      
    }

    public static function get_blocked_email( $per_page = 10, $page_number = 1 ) {

        global $wpdb;
        
        $sql = "SELECT `email`,REPLACE(`type`, '_', ' ') `type`, block_count FROM {$wpdb->prefix}adev_blocked_emails ";

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }

        $sql .= " LIMIT $per_page";

        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }

    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}adev_blocked_emails ";

        return $wpdb->get_var( $sql );
    }

    public function grouped_list(){
        global $wpdb;
        
        $sql = "SELECT REPLACE(`type`, '_', ' ') `type`, SUM(block_count) c FROM {$wpdb->prefix}adev_blocked_emails ";
        $sql .= " GROUP BY `type`";

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        return $result;
    }

    public function no_items() {
        _e( 'No blocked emails avaliable.', 'sp' );
    }

    public function get_columns() {
        $columns = [
            'email'    => __( 'Email', 'safe-email' ),
            'type' => __( 'Type', 'safe-email' ),
            'block_count'    => __( 'Count', 'safe-email' )
        ];

        return $columns;
    }

    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'email':
            case 'type':
            case 'block_count':  
                return $item[ $column_name ];
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    public function get_sortable_columns() {
        
        $sortable_columns = array(
          'email' => array( 'email', true ),
          'type' => array( 'type', true ),
          'block_count' => array( 'block_count', true )
        );

        return $sortable_columns;
    }
    
    public function prepare_items() {

        $this->_column_headers = $this->get_column_info();

        $per_page     = $this->get_items_per_page( 'emails_per_page', 10 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( [
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ] );

        $this->items = self::get_blocked_email( $per_page, $current_page );
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
    }
}