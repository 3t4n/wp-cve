<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists('WP_List_Table') )
{
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Houzez Property Feed Automatic Imports Table Functions
 */
class Houzez_Property_Feed_Admin_Automatic_Imports_Table extends WP_List_Table {

	public function __construct( $args = array() ) 
    {
        parent::__construct( array(
            'singular'=> 'Import',
            'plural' => 'Imports',
            'ajax'   => false // We won't support Ajax for this table, ye
        ) );
	}

    public function extra_tablenav( $which ) 
    {
        
    }

    public function get_columns() 
    {
        return array(
            'col_import_format' =>__('Format', 'houzezpropertyfeed' ),
            'col_import_details' =>__( 'Details', 'houzezpropertyfeed' ),
            'col_import_frequency' =>__( 'Frequency', 'houzezpropertyfeed' ),
            'col_import_last_ran' =>__( 'Last Ran', 'houzezpropertyfeed' ),
            'col_import_next_due' =>__( 'Next Due To Run', 'houzezpropertyfeed' ),
        );
    }

    public function column_default( $item, $column_name )
    {
        switch( $column_name ) 
        {
            case 'col_import_format':
            case 'col_import_details':
            case 'col_import_frequency':
            case 'col_import_last_ran':
            case 'col_import_next_due':
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
        $imports = ( isset($options['imports']) && is_array($options['imports']) && !empty($options['imports']) ) ? $options['imports'] : array();

        $this->items = array();

        foreach ( $imports as $key => $import )
        {
            if ( isset($imports[$key]['deleted']) && $imports[$key]['deleted'] === true )
            {
                unset( $imports[$key] );
            }
        }

        $frequencies = get_houzez_property_feed_import_frequencies();

        foreach ( $imports as $key => $import )
        {
            // ensure frequency is not a PRO one if PRO not enabled
            if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true )
            {
                if ( isset($frequencies[$import['frequency']]['pro']) && $frequencies[$import['frequency']]['pro'] === true )
                {
                    $import['frequency'] = 'daily';
                }
            }

            $format = get_houzez_property_feed_import_format( $import['format'] );

            $details = '';
            if ( isset($format['fields']) && !empty($format['fields']) )
            {
                foreach ( $format['fields'] as $field )
                {
                    if ( isset($field['type']) && $field['type'] != 'hidden' && $field['type'] != 'html' )
                    {
                        $value = ( ( isset($import[$field['id']]) && !empty($import[$field['id']]) ) ? $import[$field['id']] : '' );
                        $details .= '<strong>' . $field['label'] . '</strong>: ' . ( $value != '' ? $value : '-' ) .  '<br>';
                    }
                }
            }
            if ( isset($format['export_enquiries']) && $format['export_enquiries'] === true )
            {
                $value = 'No';
                if ( isset($import['export_enquiries_enabled']) && $import['export_enquiries_enabled'] == 'yes' )
                {
                    $value = 'Yes';
                }
                $details .= '<strong>' . __( 'Export Enquiries', 'houzezpropertyfeed' ) . '</strong>: ' . $value . '<br>';
            }

            if ( apply_filters( 'houzez_property_feed_pro_active', false ) === true )
            {
                if ( isset($options['media_processing']) && $options['media_processing'] === 'background' )
                {
                    $media_to_import = $wpdb->get_results(
                        "
                        SELECT
                            GROUP_CONCAT(`id`) as `ids`,
                            `import_id`,
                            `post_id`,
                            `crm_id`,
                            `media_type`,
                            `media_order`,
                            SUBSTRING_INDEX(GROUP_CONCAT(`media_location` ORDER BY `media_modified` DESC SEPARATOR '~'), '~', 1 ) as `media_location`,
                            SUBSTRING_INDEX(GROUP_CONCAT(`media_description` ORDER BY `media_modified` DESC SEPARATOR '~'), '~', 1 ) as `media_description`,
                            SUBSTRING_INDEX(GROUP_CONCAT(`media_compare_url` ORDER BY `media_modified` DESC SEPARATOR '~'), '~', 1 ) as `media_compare_url`,
                            MAX(`media_modified`) as `media_modified`
                        FROM
                            " . $wpdb->prefix . "houzez_property_feed_media_queue
                        WHERE
                            `import_id` = '" . $key . "'
                        GROUP BY
                            post_id,
                            media_type,
                            media_order
                        ORDER BY
                            post_id,
                            media_type,
                            media_order
                        "
                    );
                    if ( count($media_to_import) > 0 )
                    {
                        $details .= '<strong>' . __( 'Queued Media Items', 'houzezpropertyfeed' ) . '</strong>: ' . count($media_to_import) . '<br>';
                    }
                }
            }
            
            $running = false;
            if ( isset($import['running']) && $import['running'] === true )
            {
                $running = true;
            }

            // Last ran
            $last_ran = '';
            $row = $wpdb->get_row( "
                SELECT 
                    start_date, end_date
                FROM 
                    " .$wpdb->prefix . "houzez_property_feed_logs_instance
                WHERE 
                    import_id = '" . $key . "'
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
                $last_ran .= '-';
            }

            // Next due
            $next_due_display = '';
            if ( isset($import['running']) && $import['running'] === true && $import['format'] != 'rtdf' )
            {
                $next_due = wp_next_scheduled( 'houzezpropertyfeedcronhook' );

                if ( $next_due == FALSE )
                {
                    $next_due_display .= 'Whoops. WordPress doesn\'t have the import scheduled. A quick fix to this is to deactivate, then re-activate the plugin.';
                }
                else
                {
                    $last_start_date = '2020-01-01 00:00:00';
                    $row = $wpdb->get_row( "
                        SELECT 
                            start_date
                        FROM 
                            " .$wpdb->prefix . "houzez_property_feed_logs_instance
                        WHERE
                            import_id = '" . $key . "'
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
                        switch ($import['frequency'])
                        {
                            case "every_fifteen_minutes":
                            {
                                if ( ( ($next_due - $last_start_date) / 60 / 60 ) >= 0.25 )
                                {
                                    $got_next_due = $next_due;
                                }
                                break;
                            }
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
                $next_due_display .= '-';
            }

            $this->items[] = array(
                'col_import_format' => '
                    <strong><a href="' . admin_url('admin.php?page=houzez-property-feed-import&action=editimport&import_id=' . (int)$key) . '" aria-label="' . __( 'Edit Import', 'houzezpropertyfeed' ) . '">' . ( $running ? '<span class="icon-running-status icon-running"></span>' : '<span class="icon-running-status icon-not-running"></span>' ) . ' ' . ( isset($format['name']) ? $format['name'] : '-' ) . '</a></strong>
                    <div class="row-actions">
                        <span class="edit">' . ( 
                            !$running ? 
                            '<a href="' . admin_url('admin.php?page=houzez-property-feed-import&action=startimport&import_id=' . (int)$key) . '" aria-label="' . __( 'Start Import', 'houzezpropertyfeed' ) . '">' . __( 'Start Import', 'houzezpropertyfeed' ) . '</a>' : 
                            '<a href="' . admin_url('admin.php?page=houzez-property-feed-import&action=pauseimport&import_id=' . (int)$key) . '" aria-label="' . __( 'Pause Import', 'houzezpropertyfeed' ) . '">' . __( 'Pause Import', 'houzezpropertyfeed' ) . '</a>' 
                        ) . ' | </span>
                        <span class="edit"><a href="' . admin_url('/admin.php?page=houzez-property-feed-import&tab=logs&import_id=' . (int)$key) . '" aria-label="' . __( 'View Logs', 'houzezpropertyfeed' ) . '">' . __( 'Logs', 'houzezpropertyfeed' ) . '</a> | </span>
                        <span class="edit"><a href="' . admin_url('admin.php?page=houzez-property-feed-import&action=editimport&import_id=' . (int)$key) . '" aria-label="' . __( 'Edit Import', 'houzezpropertyfeed' ) . '">' . __( 'Edit', 'houzezpropertyfeed' ) . '</a> | </span>
                        <span class="edit"><a href="' . admin_url('admin.php?page=houzez-property-feed-import&action=cloneimport&import_id=' . (int)$key) . '" aria-label="' . __( 'Clone Import', 'houzezpropertyfeed' ) . '">' . __( 'Clone', 'houzezpropertyfeed' ) . '</a> | </span>
                        <span class="trash"><a href="' . admin_url('admin.php?page=houzez-property-feed-import&action=deleteimport&import_id=' . (int)$key) . '" class="submitdelete" aria-label="' . __( 'Delete Import', 'houzezpropertyfeed' ) . '">' . __( 'Delete', 'houzezpropertyfeed' ) . '</a>
                    </div>',
                'col_import_details' => $details,
                'col_import_frequency' => ( isset($import['frequency']) ? ucwords(str_replace("_", " ", $import['frequency'])) : '-' ),
                'col_import_last_ran' => $last_ran,
                'col_import_next_due' => $next_due_display,
            );
        }

        $this->set_pagination_args(
            array(
                'total_items' => count($imports),
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