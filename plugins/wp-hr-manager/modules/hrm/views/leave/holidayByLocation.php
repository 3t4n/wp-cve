<?php
/**
 * List table class
 */
class Leave_HolidayByLocation_List_Table extends WP_List_Table {

    function __construct() {
        global $status, $page;

        parent::__construct( array(
            'singular' => 'holiday',
            'plural'   => 'holiday',
            'ajax'     => false
        ) );

    }

    /**
     * Get table classes
     *
     * @return array
     */
    function get_table_classes() {
        return array( 'widefat', 'fixed', 'striped', 'wphr-leave-policy-list-table', $this->_args['plural'] );
    }

    function extra_tablenav( $which ) {
        if ( $which != 'top' ) return;

        $from         = isset( $_GET['from'] ) ? sanitize_text_field( $_GET['from'] ) : date( 'Y-01-01' );
        $to           = isset( $_GET['to'] ) ? sanitize_text_field( $_GET['to'] ) : date( 'Y-12-31' );
        ?>

        <label class="screen-reader-text" for="new_role"><?php _e( 'From', 'wphr' ) ?></label>
        <input type="text" placeholder="<?php _e( 'From date', 'wphr' ); ?>" name="from" value="<?php echo $from; ?>" class="wphr-leave-date-picker-from">

        <label class="screen-reader-text" for="new_role"><?php _e( 'To', 'wphr' ) ?></label>
        <input type="text" placeholder="<?php _e( 'To date', 'wphr' ); ?>" name="to" value="<?php echo $to; ?>" class="wphr-leave-date-picker-to">
        <?php
        submit_button( __( 'Filter' ), 'button', 'filter', false );
    }

    /**
     * Message to show if no policy found
     *
     * @return void
     */
    function no_items() {
        _e( 'No record found!', 'wphr' );
    }

    /**
     * Default column values if no callback found
     *
     * @param  object  $item
     * @param  string  $column_name
     *
     * @return string
     */
    function column_default( $holiday, $column_name ) {

        switch ( $column_name ) {
            case 'name':
                return $holiday->title;

            case 'location_id':
                return $holiday->location_id;

            case 'start':
                return wphr_format_date( $holiday->start );

            case 'end':
                return wphr_format_date( date( 'Y-m-d' , strtotime( $holiday->end . '-1day' ) ) );

            case 'duration':
                $days = wphr_date_duration( $holiday->start, $holiday->end );
                return $days .' '. _n( __( 'day', 'wphr' ), __( 'days', 'wphr' ), $days );

            case 'description':
                return ! empty( $holiday->description ) ? $holiday->description : '--';

            default:
                return '';
        }
    }

    /**
     * Get the column names
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb'          => '<input type="checkbox" />',
            'name'        => __( 'Title', 'wphr' ),
            'start'       => __( 'Start Date', 'wphr' ),
            'end'         => __( 'End Date', 'wphr' ),
            'duration'    => __( 'Duration', 'wphr' ),
            'location_id'    => __( 'Location', 'wphr' ),
            'description' => __( 'Description', 'wphr' )
        );

        return apply_filters( 'wphr_hr_holiday_table_cols', $columns );
    }

    /**
     * Render the leave policy name column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_name( $holiday ) {

        $actions           = array();
        $delete_url        = '';
        $actions['edit']   = sprintf( '<a href="%s" data-id="%d" class="wphr-hr-holiday-by-location-edit" title="%s">%s</a>', $delete_url, $holiday->id, __( 'Edit this item', 'wphr' ), __( 'Edit', 'wphr' ) );
        $actions['delete'] = sprintf( '<a href="%s" class="wphr-hr-holiday-by-location-delete" data-id="%d" title="%s">%s</a>', $delete_url, $holiday->id, __( 'Delete this item', 'wphr' ), __( 'Delete', 'wphr' ) );

        return sprintf( '<a href="#" class="link" data-id="%3$s"><strong>%1$s</strong></a> %2$s', esc_html( $holiday->title ), $this->row_actions( $actions ), $holiday->id );
    }

    /**
     * Modify single row element
     *
     * @param  array $item
     *
     * @return void
     */
    function single_row( $item ) {
        ?>
            <tr data-json='<?php echo json_encode( $item ); ?>'>
                <?php $this->single_row_columns( $item ); ?>
            </tr>
        <?php
    }

    /**
     * Get sortable columns
     *
     * @return array
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'location_id' => array( 'location_id', true ),
            'start' => array( 'start', true ),
            'end' => array( 'end', true )
        );

        return $sortable_columns;
    }

    /**
     * Set the bulk actions
     *
     * @return array
     */
    function get_bulk_actions() {
        $actions = array(
            'trash'  => __( 'Delete', 'wphr' ),
        );
        return $actions;
    }

    /**
     * Render the checkbox column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="holiday_id[]" value="%s" />', $item->id
        );
    }

    /**
     * Set the views
     *
     * @return array
     */
    public function get_views_() {
        $status_links   = array();
        $base_link      = admin_url( 'admin.php?page=wphr-leave' );

        foreach ($this->counts as $key => $value) {
            $class = ( $key == $this->page_status ) ? 'current' : 'status-' . $key;
            $status_links[ $key ] = sprintf( '<a href="%s" class="%s">%s <span class="count">(%s)</span></a>', add_query_arg( array( 'status' => $key ), $base_link ), $class, $value['label'], $value['count'] );
        }

        return $status_links;
    }

    /**
     * Prepare the class items
     *
     * @return void
     */
    function prepare_items() {

        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );

        $per_page              = 20;
        $current_page          = $this->get_pagenum();
        $offset                = ( $current_page -1 ) * $per_page;
        $this->page_status     = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '2';

        // only ncessary because we have sample data
        $args = array(
            'offset' => $offset,
            'number' => $per_page,
            'orderby' => 'start',
            'order' => 'ASC'
        );


        if ( ! empty( $_GET['s'] ) ) {
            $args['s'] = sanitize_text_field($_GET['s']);
        }

        if ( isset( $_GET['from'] ) && sanitize_text_field($_GET['from']) != '' ) {
            $args['from'] = date( 'Y-m-d', strtotime( sanitize_text_field( $_GET['from'] ) ) );
        } else {
            $args['from'] = date( 'Y-01-01' );
        }

        if ( isset( $_GET['to'] ) && sanitize_text_field($_GET['to']) != '' ) {
            $args['to'] = date( 'Y-m-d', strtotime( sanitize_text_field( $_GET['to'] ). '+1day' ) );
        } else {
            $args['to'] = date( 'Y-12-31' );
        }

        if ( isset( $_GET['country'] ) && sanitize_text_field($_GET['country']) != '' ) {
            $args['country'] = sanitize_text_field( $_GET['country'] ) ;
        } else {
            $args['country'] = '';
        }

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = sanitize_text_field($_REQUEST['orderby']);
            $args['order'] = sanitize_text_field($_REQUEST['order']) ;
        }

        $this->items  = wphr_hr_get_holidaysByLocation( $args );

        $locations = WPHR\HR_MANAGER\Admin\Models\Company_Locations::select( '*' )->get()->toArray();
        $location = array();
        foreach ($locations as $loc) {
            $location[$loc['id']] = $loc['name'];
        }
        
        $i = 0;
        foreach ($this->items as $item) {
            foreach ($item as $key => $value) {
                if ( array_key_exists($value , $location) && $key == 'location_id') {
                    $this->items[$i]->location_id = $location[$value];
                }
            }
            $i++;
        }
        $args['location_id'] = 0;
        $this->set_pagination_args( array(
            'total_items' => wphr_hr_count_holidays( $args ),
            'per_page'    => $per_page
        ) );
    }

}

?>

<div class="wrap wphr-hr-holiday-by-location-wrap">
    <h2>
        <?php _e( 'Holiday By Location', 'wphr' ); ?>
        <a href="#" id="wphr-hr-new-holiday-by-location" class="add-new-h2"><?php _e( 'Add New', 'wphr' ); ?></a>
        <a href="#import-ical" id="wphr-hr-import-ical-by-location" class="add-new-h2"><?php _e( 'Import iCal', 'wphr' ); ?></a>
    </h2>

	<div class="holiday_handler">
		<a href="<?php echo admin_url('admin.php?page=wphr-holiday-assign'); ?>"><?php _e( 'Common Holidays', 'wphr' ); ?></a>
		<a href="<?php echo admin_url('admin.php?page=wphr-holiday-assign&subpage=location'); ?>" class="active"><?php _e( 'Local Holidays', 'wphr' ); ?></a>
	</div>
    <div class="list-table-wrap">
        <div class="list-wrap-inner">

            <form method="get">
				<input type="hidden" name="page" value="wphr-holiday-assign" />
                <input type="hidden" name="subpage" value="location">
                <?php

                $holiday = new Leave_HolidayByLocation_List_Table();
                $holiday->prepare_items();
                $holiday->search_box( __( 'Search', 'wphr' ), 'wphr-hr-holiday-serach');

                $holiday->views();

                $holiday->display();
                ?>
            </form>
        </div><!-- .list-table-inner -->
    </div><!-- .list-table-wrap -->

</div>
