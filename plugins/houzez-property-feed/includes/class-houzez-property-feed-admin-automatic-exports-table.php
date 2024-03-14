<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists('WP_List_Table') )
{
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Houzez Property Feed Automatic Exports Table Functions
 */
class Houzez_Property_Feed_Admin_Automatic_Exports_Table extends WP_List_Table {

	public function __construct( $args = array() ) 
    {
        parent::__construct( array(
            'singular'=> 'Export',
            'plural' => 'Exports',
            'ajax'   => false // We won't support Ajax for this table, yet
        ) );
	}

    public function extra_tablenav( $which ) 
    {
        
    }

    public function get_columns() 
    {
        return array(
            'col_export_name' =>__('Export Name', 'houzezpropertyfeed' ),
            'col_export_format' =>__('Format', 'houzezpropertyfeed' ),
            'col_export_details' =>__( 'Details', 'houzezpropertyfeed' ),
            'col_export_frequency' =>__( 'Frequency', 'houzezpropertyfeed' ),
            'col_export_last_ran' =>__( 'Last Ran', 'houzezpropertyfeed' ),
            'col_export_next_due' =>__( 'Next Due To Run', 'houzezpropertyfeed' ),
        );
    }

    public function column_default( $item, $column_name )
    {
        switch( $column_name ) 
        {
            case 'col_export_name':
            case 'col_export_format':
            case 'col_export_frequency':
            case 'col_export_details':
            case 'col_export_last_ran':
            case 'col_export_next_due':
                return $item[ $column_name ];
                break;
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

        $options = get_option( 'houzez_property_feed' , array() );
        $exports = ( isset($options['exports']) && is_array($options['exports']) && !empty($options['exports']) ) ? $options['exports'] : array();

        $this->items = array();

        foreach ( $exports as $key => $export )
        {
            if ( isset($exports[$key]['deleted']) && $exports[$key]['deleted'] === true )
            {
                unset( $exports[$key] );
            }
        }

        $frequencies = get_houzez_property_feed_export_frequencies();

        foreach ( $exports as $key => $export )
        {
            // ensure frequency is not a PRO one if PRO not enabled
            if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true )
            {
                if ( isset($frequencies[$export['frequency']]['pro']) && $frequencies[$export['frequency']]['pro'] === true )
                {
                    $export['frequency'] = 'daily';
                }
            }
            
            $running = false;
            if ( isset($export['running']) && $export['running'] === true )
            {
                $running = true;
            }

            $format = get_houzez_property_feed_export_format( $export['format'] );

            // Last ran
            $last_ran = '';
            $next_due_display = '';
            $frequency = '';
            if ( $format['method'] == 'cron' || $format['method'] == 'url' )
            {
                $row = $wpdb->get_row( "
                    SELECT 
                        start_date, end_date
                    FROM 
                        " .$wpdb->prefix . "houzez_property_feed_export_logs_instance
                    WHERE 
                        export_id = '" . $key . "'
                    ORDER BY start_date DESC LIMIT 1
                ", ARRAY_A);
                if ( null !== $row )
                {
                    if ($row['start_date'] <= $row['end_date'])
                    {
                        $last_ran .= get_date_from_gmt( $row['start_date'], "jS F Y H:i" );
                    }
                    elseif ($row['end_date'] == '0000-00-00 00:00:00')
                    {
                        $last_ran .= 'Running now...<br>Started at ' . get_date_from_gmt( $row['start_date'], "jS F Y H:i" );
                    }
                }
                else
                {
                    $last_ran = '-';
                }

                if ( isset($export['running']) && $export['running'] === true )
                {
                    $next_due = wp_next_scheduled( 'houzezpropertyfeedcronhook' );

                    if ( $next_due == FALSE )
                    {
                        $next_due_display .= 'Whoops. WordPress doesn\'t have the export scheduled. A quick fix to this is to deactivate, then re-activate the plugin.';
                    }
                    else
                    {
                        $last_start_date = '2020-01-01 00:00:00';
                        $row = $wpdb->get_row( "
                            SELECT 
                                start_date
                            FROM 
                                " .$wpdb->prefix . "houzez_property_feed_export_logs_instance
                            WHERE
                                export_id = '" . $key . "'
                            ORDER BY start_date DESC LIMIT 1
                        ", ARRAY_A);
                        if ( null !== $row )
                        {
                            $last_start_date = $row['start_date'];   
                        }
                        $last_start_date = strtotime($last_start_date);

                        $got_next_due = false;
                        $j = 0;
                        
                        while ( $got_next_due === false )
                        {
                            if ( $j > 500 )
                            {
                                break;
                            }
                            switch ($export['frequency'])
                            {
                                case "hourly":
                                {
                                    if ( ( ($next_due - $last_start_date) / 60 / 60 ) >= 1 )
                                    {
                                        $got_next_due = $next_due;
                                    }
                                    break;
                                }
                                case "twicedaily":
                                {
                                    if ( ( ($next_due - $last_start_date) / 60 / 60 ) >= 12 )
                                    {
                                        $got_next_due = $next_due;
                                    }
                                    break;
                                }
                                default: // daily
                                {
                                    if ( ( ($next_due - $last_start_date) / 60 / 60 ) >= 24 )
                                    {
                                        $got_next_due = $next_due;
                                    }
                                }
                            }
                            $next_due = $next_due + 300;
                            ++$j;
                        }

                        if ( $got_next_due !== false )
                        {
                            $got_next_due = new DateTimeImmutable( '@' . $got_next_due );
                            $got_next_due_new = $got_next_due->setTimezone(wp_timezone());

                            $current_date = current_datetime();

                            $tomorrows_date = new DateTime( 'now', wp_timezone() );
                            $tomorrows_date->modify('+1 day');

                            if ( $got_next_due_new->format("Y-m-d") == $current_date->format("Y-m-d") )
                            {
                                $next_due_display .= 'Today at ' . $got_next_due_new->format("H:i");
                            }
                            elseif ( $got_next_due_new->format("Y-m-d") == $tomorrows_date->format("Y-m-d") )
                            {
                                $next_due_display .= 'Tomorrow at ' . $got_next_due_new->format("H:i");
                            }
                            else
                            {
                                // should never get to this case
                                $next_due_display .= $got_next_due_new->format("H:i jS F");
                            }
                        }
                    }
                }
                else
                {
                    $next_due_display = '-';
                }

                $frequency = ( isset($export['frequency']) ? ucwords(str_replace("_", " ", $export['frequency'])) : '-' );
            }
            else
            {
                $last_ran = 'n/a';
                $next_due_display = 'n/a';
                $frequency = 'n/a';
            }

            $details = '';
            if ( $format['method'] == 'url' )
            {
                $url = '';
                $before = '';
                $after = '<br><em>(Export not generated yet)</em>';
                $wp_upload_dir = wp_upload_dir();
                if( $wp_upload_dir['error'] !== FALSE )
                {
                    
                }
                else
                {
                    $filename = $key . '.xml';
                    if ( $export['format'] == 'kyero' )
                    {
                        $filename = apply_filters( 'houzez_property_feed_export_kyero_url_filename', $filename, $key );
                    }

                    $url = $wp_upload_dir['baseurl'] . '/houzez_property_feed_export/' . $filename;
                    if ( file_exists($wp_upload_dir['basedir'] . '/houzez_property_feed_export/' . $filename) )
                    {
                        $before = '<a href="' . $url . '" target="_blank">';
                        $after = '</a>';
                    }
                }
                $details .= '<strong>URL</strong>: ' . $before . $url . $after . '<br>';
            }
            if ( isset($format['fields']) && !empty($format['fields']) )
            {
                foreach ( $format['fields'] as $field )
                {
                    if ( isset($field['type']) && $field['type'] != 'hidden' && $field['type'] != 'html' )
                    {
                        $value = ( ( isset($export[$field['id']]) && !empty($export[$field['id']]) ) ? $export[$field['id']] : '' );
                        $details .= '<strong>' . $field['label'] . '</strong>: ' . ( $value != '' ? $value : '-' ) .  '<br>';
                    }
                }
            }

            $this->items[] = array(
                'col_export_name' => '
                    <strong><a href="' . admin_url('admin.php?page=houzez-property-feed-export&action=editexport&export_id=' . (int)$key) . '" aria-label="' . __( 'Edit Export', 'houzezpropertyfeed' ) . '">' . ( $running ? '<span class="icon-running-status icon-running"></span>' : '<span class="icon-running-status icon-not-running"></span>' ) . ' ' . esc_html( ( (isset($export['name']) && !empty($export['name'])) ? $export['name'] : $export['format'] ) ) . '</a></strong>
                    <div class="row-actions">
                        <span class="edit">' . ( 
                            !$running ? 
                            '<a href="' . admin_url('admin.php?page=houzez-property-feed-export&action=startexport&export_id=' . (int)$key) . '" aria-label="' . __( 'Start Export', 'houzezpropertyfeed' ) . '">' . __( 'Start Export', 'houzezpropertyfeed' ) . '</a>' : 
                            '<a href="' . admin_url('admin.php?page=houzez-property-feed-export&action=pauseexport&export_id=' . (int)$key) . '" aria-label="' . __( 'Pause Export', 'houzezpropertyfeed' ) . '">' . __( 'Pause Export', 'houzezpropertyfeed' ) . '</a>' 
                        ) . ' | </span>
                        <span class="edit"><a href="' . admin_url('/admin.php?page=houzez-property-feed-export&tab=logs&export_id=' . (int)$key) . '" aria-label="' . __( 'View Logs', 'houzezpropertyfeed' ) . '">' . __( 'Logs', 'houzezpropertyfeed' ) . '</a> | </span>
                        <span class="edit"><a href="' . admin_url('admin.php?page=houzez-property-feed-export&action=editexport&export_id=' . (int)$key) . '" aria-label="' . __( 'Edit Export', 'houzezpropertyfeed' ) . '">' . __( 'Edit', 'houzezpropertyfeed' ) . '</a> | </span> ' .
                        ( ($export['format'] == 'blm' && $running) ? '<span class="edit"><a href="' . admin_url('admin.php?page=houzez-property-feed-export&custom_property_export_cron=houzezpropertyfeedcronhook&preview=' . (int)$key) . '" aria-label="' . __( 'Preview BLM', 'houzezpropertyfeed' ) . '">' . __( 'View BLM', 'houzezpropertyfeed' ) . '</a> | </span> ' : '' ) .
                        ( ($export['format'] == 'idealista' && $running) ? '<span class="edit"><a href="' . admin_url('admin.php?page=houzez-property-feed-export&custom_property_export_cron=houzezpropertyfeedcronhook&preview=' . (int)$key) . '" aria-label="' . __( 'Preview JSON', 'houzezpropertyfeed' ) . '">' . __( 'View JSON', 'houzezpropertyfeed' ) . '</a> | </span> ' : '' ) .
                        '<span class="trash"><a href="' . admin_url('admin.php?page=houzez-property-feed-export&action=deleteexport&export_id=' . (int)$key) . '" class="submitdelete" aria-label="' . __( 'Delete Export', 'houzezpropertyfeed' ) . '">' . __( 'Delete', 'houzezpropertyfeed' ) . '</a>
                    </div>',
                'col_export_format' => ( ($format !== false && isset($format['name'])) ? esc_html($format['name']) : '-' ),
                'col_export_details' => $details,
                'col_export_frequency' => $frequency,
                'col_export_last_ran' => $last_ran,
                'col_export_next_due' => $next_due_display,
            );
        }

        $this->set_pagination_args(
            array(
                'total_items' => count($exports),
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