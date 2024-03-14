<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists('WP_List_Table') )
{
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Houzez Property Feed Admin Logs View Import Table Functions
 */
class Houzez_Property_Feed_Admin_Logs_View_Import_Table extends WP_List_Table {

	public function __construct( $args = array() ) 
    {
        parent::__construct( array(
            'singular'=> 'Log Entry',
            'plural' => 'Log Entries',
            'ajax'   => false // We won't support Ajax for this table, ye
        ) );
	}

    public function extra_tablenav( $which ) 
    {
        /*if ( $which == "top" )
        {
            //The code that goes before the table is here
            echo"Hello, I'm before the table";
        }
        if ( $which == "bottom" )
        {
            //The code that goes after the table is there
            echo"Hi, I'm after the table";
        }*/
    }

    public function get_columns() 
    {
        return array(
            'col_log_date' =>__('Date / Time', 'houzezpropertyfeed' ),
            'col_log_property' =>__( 'Related To Property', 'houzezpropertyfeed' ),
            'col_log_crm_id' =>__( 'CRM ID', 'houzezpropertyfeed' ),
            'col_log_entry' =>__( 'Log Entry', 'houzezpropertyfeed' ),
        );
    }

    public function column_default( $item, $column_name )
    {
        switch( $column_name ) 
        {
            case 'col_log_date':
            {
                $return = get_date_from_gmt( $item->log_date, "H:i:s jS F Y" );

                return $return;
            }
            case 'col_log_property':
            {
                if ( empty($item->post_id) )
                {
                    return '-';
                }

                $title = get_the_title($item->post_id);
                if ( empty($title) )
                {
                    $title = '(no title)';
                }

                return '<a href="' . get_edit_post_link($item->post_id) . '" target="_blank">' . $title . '</a>';
            }
            case 'col_log_crm_id':
            {
                return $item->crm_id;
            }
            case 'col_log_entry':
            {   
                $return = $item->entry;
                if ( strpos($item->entry, '<iframe') )
                {
                    $return = htmlentities($item->entry);
                }

                return $return;
            }
            default:
                return print_r( $item, true ) ;
        }
    }

    public function prepare_items() 
    {
        global $wpdb;

        $columns = $this->get_columns(); 
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $per_page = 100000;
        $current_page = $this->get_pagenum();
        $offset = ( $current_page - 1 ) * $per_page;

        $this->_column_headers = array($columns, $hidden, $sortable);

        $query = "SELECT
            log_date,
            entry,
            post_id,
            crm_id
        FROM 
            " . $wpdb->prefix . "houzez_property_feed_logs_instance_log
        WHERE
            instance_id = '" . (int)$_GET['log_id'] . "'
        ORDER BY id ASC";

        $this->items = $wpdb->get_results($query);
        $totalitems = count($this->items);

        $this->set_pagination_args(
            array(
                'total_items' => $totalitems,
                'per_page'    => $per_page,
            )
        );
        
    }

    public function display() {
        $singular = $this->_args['singular'];

        $this->screen->render_screen_reader_content( 'heading_list' );
        ?>
<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
    <thead>
    <tr>
        <?php $this->print_column_headers(); ?>
    </tr>
    </thead>

    <tbody id="the-list"
        <?php
        if ( $singular ) {
            echo ' data-wp-lists="list:' . esc_attr($singular) . '"';
        }
        ?>
        >
        <?php $this->display_rows_or_placeholder(); ?>
    </tbody>

</table>
        <?php
    }

    protected function get_table_classes() {
        $mode = get_user_setting( 'posts_list_mode', 'list' );

        $mode_class = esc_attr( 'table-view-' . $mode );

        return array( 'widefat', 'striped', $mode_class, esc_attr($this->_args['plural']) );
    }

}