<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists('WP_List_Table') )
{
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Houzez Property Feed Admin Logs Import Table Functions
 */
class Houzez_Property_Feed_Admin_Logs_Import_Table extends WP_List_Table {

	public function __construct( $args = array() ) 
    {
        parent::__construct( array(
            'singular'=> 'Log',
            'plural' => 'Logs',
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
            'col_log_date'=>__('Date / Time', 'houzezpropertyfeed' ),
            'col_log_duration'=>__( 'Duration', 'houzezpropertyfeed' ),
            'col_log_import_format'=>__( 'Import Format', 'houzezpropertyfeed' ),
        );
    }

    public function column_default( $item, $column_name )
    {
        switch( $column_name ) 
        {
            case 'col_log_date':
            {
                $return = '<strong><a href="' . admin_url('admin.php?page=houzez-property-feed-import&tab=logs&action=view&log_id=' . $item->id . ( ( isset($_GET['import_id']) && !empty((int)$_GET['import_id']) ) ? '&import_id=' . (int)$_GET['import_id'] : '' ) ) . '">' . get_date_from_gmt( $item->start_date, "H:i:s jS F Y" ) . '</a></strong>';

                $return .= '<div class="row-actions">
                        <span class="edit"><a href="' . admin_url('admin.php?page=houzez-property-feed-import&tab=logs&action=view&log_id=' . $item->id . ( ( isset($_GET['import_id']) && !empty((int)$_GET['import_id']) ) ? '&import_id=' . (int)$_GET['import_id'] : '' ) ) . '" aria-label="' . __( 'View Log', 'houzezpropertyfeed' ) . '">' . __( 'View Log', 'houzezpropertyfeed' ) . '</a></span>
                    </div>';

                return $return;
            }
            case 'col_log_duration':
            {
                if ( $item->end_date == '0000-00-00 00:00:00' )
                {
                    return '-';
                }

                $diff = '';

                $diff_secs = strtotime($item->end_date) - strtotime($item->start_date);

                if ( $diff_secs >= 60 )
                {
                    $diff_mins = floor( $diff_secs / 60 );
                    $diff = $diff_mins . ' minutes, ';
                    $diff_secs = $diff_secs - ( $diff_mins * 60 );
                }

                $diff .= $diff_secs . ' seconds';

                return $diff;
            }
            case 'col_log_import_format':
            {
                $format = get_format_from_import_id( $item->import_id );

                if ( $format === false)
                {
                    return '-';
                }

                return $format['name'];
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

        $per_page = 10000;
        $current_page = $this->get_pagenum();
        $offset = ( $current_page - 1 ) * $per_page;

        $this->_column_headers = array($columns, $hidden, $sortable);

        $query = "SELECT
            id, 
            start_date, 
            end_date, 
            import_id
        FROM 
            " . $wpdb->prefix . "houzez_property_feed_logs_instance ";
        if ( isset($_GET['import_id']) && !empty((int)$_GET['import_id']) )
        {
            $query .= " WHERE import_id = '" . (int)$_GET['import_id'] . "' ";
        }
        $query .= " ORDER BY start_date ASC";

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